<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace net\mkharitonov\spectrum\core\plugins\basePlugins;
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;

use \net\mkharitonov\spectrum\core\plugins\events;
use \net\mkharitonov\spectrum\core\report\Buffer;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class RunReport extends \net\mkharitonov\spectrum\core\plugins\basePlugins\Report implements events\OnRunItemInterface, events\OnRunContainerInterface
{
	protected $isEnabled = true;

	protected $buffers = array();
	protected $prevBuffers;
	static protected $currentBuffers;

	public function enable()
	{
		$this->isEnabled = true;
	}

	public function disable()
	{
		$this->isEnabled = false;
	}

	public function isEnabled()
	{
		return $this->isEnabled;
	}

/**/

	public function addBufferForFilling(Buffer $buffer = null)
	{
		if ($this->getOwner()->getParent())
			throw new Exception('Only root spec can collect report');

		if (!$buffer)
			$buffer = new Buffer();

		$this->buffers[] = $buffer;

		return $buffer;
	}

/**/

	public function onRunContainerBefore()
	{
		foreach ($this->getBuffers() as $index => $buffer)
		{
			if (!@static::$currentBuffers[$index])
				static::$currentBuffers[$index] = $buffer;

			$newBuffer = $this->createBuffer();
			$this->prevBuffers[$index] = static::$currentBuffers[$index];
			$this->prevBuffers[$index]->addBuffer($newBuffer);

			static::$currentBuffers[$index] = $newBuffer;
		}
	}

	public function onRunContainerAfter($result)
	{
		foreach ($this->getBuffers() as $index => $buffer)
		{
			static::$currentBuffers[$index]->setResult($result);
			static::$currentBuffers[$index] = $this->prevBuffers[$index];
		}
	}

	public function onRunItemBefore()
	{
	}

	public function onRunItemAfter($result)
	{
		foreach ($this->getBuffers() as $index => $buffer)
		{
			$buffer = $this->createBuffer($result);
			$buffer->setAdditionalArguments($this->convertFromInputEncoding($this->getOwner()->getAdditionalArguments()));
			$buffer->setResult($result);
			$buffer->setResultBuffer($this->getOwner()->getResultBuffer());

			static::$currentBuffers[$index]->addBuffer($buffer);
		}
	}

	protected function getBuffers()
	{
		$root = $this->getOwner()->selector->getRoot();
		$accessName = $this->getAccessName();
		return $root->$accessName->buffers;
	}

	protected function createBuffer()
	{
		$buffer = new Buffer();
		$buffer->setHasResult(true);
		$buffer->setName($this->convertFromInputEncoding($this->getOwner()->getName()));
		$buffer->setSourceSpec($this->getOwner());
		$buffer->setIsEnabled($this->getOwner()->isEnabled());
		$buffer->setIsAnonymous($this->getOwner()->isAnonymous());
		return $buffer;
	}
}