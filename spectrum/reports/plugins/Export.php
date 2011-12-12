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
use \net\mkharitonov\spectrum\core\reports\Buffer;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Export extends \net\mkharitonov\spectrum\core\plugins\basePlugins\Report
{
	protected $isEnabled = true;
	protected $putDisabledSpecs = false;
//	protected $structureType = 'declaring';

/**/

//	public function setStructureType($type)
//	{
//		if (!in_array($type, array('declaring', 'running')))
//			throw new Exception('Wrong structure type ("' . $type . '"), allowed only "declaring" and "running"');
//
//		$this->structureType = $type;
//	}
//
//	public function getStructureType()
//	{
//		return $this->structureType;
//	}

/**/

	public function setPutDisabledSpecs($putDisabledSpecs)
	{
		$this->putDisabledSpecs = $putDisabledSpecs;
	}

	public function getPutDisabledSpecs()
	{
		return $this->putDisabledSpecs;
	}

/**/

	public function getReportBufferWithDeclaringStructure()
	{
		$buffer = $this->createBuffer($this->getOwner());
		$this->appendChildren($buffer, $this->getOwner(), 'declaring');
		return $buffer;
	}

	public function getReportBufferWithRunningStructure()
	{
		$buffer = $this->createBuffer($this->getOwner());
		$this->appendChildren($buffer, $this->getOwner(), 'running');
		return $buffer;
	}

	protected function appendChildren($buffer, SpecInterface $spec, $structureType = 'declaring')
	{
		if ($spec instanceof SpecContainerInterface)
		{
			foreach ($this->getSpecChildren($spec, $structureType) as $child)
			{
				$childBuffer = $this->createBuffer($child);
				$buffer->addBuffer($childBuffer);
				$this->appendChildren($childBuffer, $child, $structureType);
			}
		}

		return $buffer;
	}

/**/

	protected function getSpecChildren(SpecInterface $spec, $structureType = 'declaring')
	{
		if ($structureType == 'declaring')
			$specs = $spec->getSpecs();
		else
			$specs = $spec->getSpecsToRun();

		$result = array();
		foreach ($specs as $index => $child)
		{
			if ($child->isEnabled() || $this->getPutDisabledSpecs())
				$result[$index] = $child;
		}

		return $result;
	}

	protected function createBuffer(SpecInterface $spec)
	{
		$buffer = new Buffer();
		$buffer->setHasResult(false);
		$buffer->setName($this->convertFromInputEncoding($spec->getName()));
		$buffer->setSourceSpec($spec);

		if ($spec instanceof SpecItemInterface)
			$buffer->setAdditionalArguments($this->convertFromInputEncoding($spec->getAdditionalArguments()));
		
		$buffer->setIsEnabled($spec->isEnabled());
		$buffer->setIsAnonymous($spec->isAnonymous());
		return $buffer;
	}
}