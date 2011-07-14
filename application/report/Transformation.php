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

namespace net\mkharitonov\spectrum\core\report;
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;

use \net\mkharitonov\spectrum\core\report\Formatter;
use \net\mkharitonov\spectrum\core\report\FormatterInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Transformation implements FormatInterface
{
	protected $buffer;

	/**
	 * @var \net\mkharitonov\spectrum\core\report\Formatter
	 */
	protected $formatter;

	/**
	 * @var \net\mkharitonov\spectrum\core\report\Dumper
	 */
	protected $dumper;
	protected $putId = true;
	protected $putDisabledSpecs = false;
	protected $putLastRunResults = false;

	public function __construct(Buffer $buffer, FormatterInterface $formatter = null, DumperInterface $dumper = null)
	{
		if (!$formatter)
			$formatter = new Formatter();

		if (!$dumper)
			$dumper = new Formatter();

		$this->buffer = $buffer;
		$this->formatter = $formatter;
		$this->dumper = $dumper;
	}

/**/

//	public function setPutId($isEnable)
//	{
//		$this->putId = $isEnable;
//	}
//
//	public function getPutId()
//	{
//		return $this->putId;
//	}

/**/

//	public function setPutDisabledSpecs($putDisabledSpecs)
//	{
//		$this->putDisabledSpecs = $putDisabledSpecs;
//	}
//
//	public function getPutDisabledSpecs()
//	{
//		return $this->putDisabledSpecs;
//	}

/**/


	public function getSpecName()
	{
		$parent = $this->getSpec()->getParent();
		$name = $this->getSpec()->getName();

		if ($name == '' && $parent && $parent instanceof \net\mkharitonov\spectrum\core\SpecContainerDataProvider)
			return $this->getAdditionalArgumentsDumpOut();
		else
			return $this->formatter->convertToOutputEncoding($name);
	}

	protected function getAdditionalArgumentsDumpOut()
	{
		$out = '';
		foreach ($this->getSpec()->getAdditionalArguments() as $arg)
		{
			$out .= $this->formatter->convertToOutputEncoding($arg) . ', ';
		}

		return mb_substr($out, 0, -2);
	}

	public function getSpecLabel()
	{
		if ($this->getSpec() instanceof SpecContainerDescribeInterface)
			return 'describe';
		else if ($this->getSpec() instanceof SpecContainerContextInterface)
			return 'context';
		else if ($this->getSpec() instanceof SpecItemItInterface)
			return 'it';
		else if ($this->getSpec() instanceof SpecContainerInterface)
			return 'container';
		else if ($this->getSpec() instanceof SpecItemInterface)
			return 'item';
		else
			return 'spec';
	}


	public function getSpecResultName()
	{
		$result = $this->getSpecLastRunResult();

		if ($result == false)
			$name = 'fail';
		else if ($result == true)
			$name = 'success';
		else
			$name = 'empty';

		return $this->formatter->convertToOutputEncoding($name);
	}

	public function getSpecResultLabel()
	{
		$result = $this->getSpecLastRunResult();

		if ($result == false)
			$name = 'fail';
		else if ($result == true)
			$name = 'success';
		else
			$name = 'empty';

		return $this->formatter->convertToOutputEncoding($name);
	}

/**/

	public function transform($putHeader = true, $putFooter = true)
	{
		$out = '';

		if ($putHeader)
			$out .= $this->formatter->putNewline($this->getHeader());

		$out .= $this->formatter->putNewline($this->getSpecChildrenOpen());
		$out .= $this->formatBufferChildrenOut($this->getBufferOut($this->buffer));
		$out .= $this->formatter->putNewline($this->getSpecChildrenClose());

		if ($putFooter)
			$out .= $this->formatter->putNewline($this->getFooter());

		return rtrim($out);
	}

	protected function getBufferOut(Buffer $buffer)
	{
		if ($buffer->getIsAnonymous())
			return $this->getAnonymousBufferOut($buffer);
		else
			return $this->getNamedBufferOut($buffer);
	}

	protected function getAnonymousBufferOut(Buffer $buffer)
	{
		return $this->getBufferChildrenOut($buffer);
	}

	protected function getNamedBufferOut(Buffer $buffer)
	{
		$out = '';
		$out .= $this->formatter->putNewline($this->getSpecOpen());
		$out .= $this->formatter->putIndentionAndNewline($this->getSpecNameOpen() . $this->getSpecName() . $this->getSpecNameClose());
		if ($buffer->getHasResult())
			$out .= $this->formatter->putIndentionAndNewline($this->getSpecResultOpen() . $this->getSpecResultName() . $this->getSpecResultClose());
		$out .= $this->formatBufferChildrenOut($this->getBufferChildrenOut($buffer));
		$out .= $this->formatter->putNewline($this->getSpecClose());

		return rtrim($out);
	}

/**/

	protected function getBufferChildrenOut(Buffer $buffer)
	{
		if ($buffer->getIsAnonymous())
			return $this->getAnonymousBufferChildrenOut($buffer);
		else
			return $this->getNamedBufferChildrenOut($buffer);
	}

	protected function getAnonymousBufferChildrenOut(Buffer $buffer)
	{
		$out = '';
		foreach ($buffer->getBuffers() as $spec)
			$out .= $this->formatter->putNewline($this->delegate($spec)->getContentSpecOut());

		return rtrim($out);
	}

	protected function getNamedBufferChildrenOut(Buffer $buffer)
	{
		$out = '';
		$out .= $this->formatter->putNewline($this->getSpecChildrenOpen());

		foreach ($buffer->getBuffers() as $spec)
			$out .= $this->formatBufferChildrenOut($this->delegate($spec)->getContentSpecOut());

		$out .= $this->formatter->putNewline($this->getSpecChildrenClose());

		return rtrim($out);
	}

	protected function formatBufferChildrenOut($text)
	{
		return $this->formatter->putIndentionToEachLineAndNewline($text);
	}
}