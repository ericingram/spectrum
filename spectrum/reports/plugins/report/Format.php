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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\reports;
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;

use \net\mkharitonov\spectrum\core\plugins\basePlugins\reports\Formatter;
use \net\mkharitonov\spectrum\core\plugins\basePlugins\reports\FormatterInterface;
use \net\mkharitonov\spectrum\core\plugins\PluginInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Format implements FormatInterface
{
	protected $owner;

	/**
	 * @var \net\mkharitonov\spectrum\core\plugins\basePlugins\reports\Formatter
	 */
	protected $formatter;
	protected $putId = true;
	protected $putDisabledSpecs = false;
	protected $putLastRunResults = false;
	protected $structureType = 'declaring';

	public function __construct(PluginInterface $owner, FormatterInterface $formatter = null)
	{
		if (!$formatter)
			$formatter = new Formatter();

		$this->owner = $owner;
		$this->formatter = $formatter;
	}

/**/

	public function setOwner($owner)
	{
		$this->owner = $owner;
	}

	public function getOwner()
	{
		return $this->owner;
	}

/**/

	public function setPutId($isEnable)
	{
		$this->putId = $isEnable;
	}

	public function getPutId()
	{
		return $this->putId;
	}

/**/

	public function setStructureType($type)
	{
		$this->structureType = $type;
	}

	public function getStructureType()
	{
		return $this->structureType;
	}

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

	public function setPutLastRunResults($putLastRunResults)
	{
		$this->putLastRunResults = $putLastRunResults;
	}

	public function getPutLastRunResults()
	{
		return $this->putLastRunResults;
	}
	
/**/

	public function getHeader()
	{
		return '';
	}

	public function getFooter()
	{
		return '';
	}

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

	public function getReport($putHeader = true, $putFooter = true)
	{
		$out = '';

		if ($putHeader)
			$out .= $this->formatter->putNewline($this->getHeader());

		$out .= $this->formatter->putNewline($this->getSpecChildrenOpen());
		$out .= $this->formatReportSpecChildrenOut($this->getReportSpecOut());
		$out .= $this->formatter->putNewline($this->getSpecChildrenClose());

		if ($putFooter)
			$out .= $this->formatter->putNewline($this->getFooter());

		return rtrim($out);
	}

	protected function getReportSpecOut()
	{
		if ($this->getSpec()->isAnonymous())
			return $this->getReportAnonymousSpecOut();
		else
			return $this->getReportNamedSpecOut();
	}

	protected function getReportAnonymousSpecOut()
	{
		return $this->getReportSpecChildrenOut();
	}

	protected function getReportNamedSpecOut()
	{
		$out = '';
		$out .= $this->formatter->putNewline($this->getSpecOpen());
		$out .= $this->formatter->putIndentionAndNewline($this->getSpecNameOpen() . $this->getSpecName() . $this->getSpecNameClose());
		if ($this->putLastRunResults && $this->isSpecLastRunResultExists())
			$out .= $this->formatter->putIndentionAndNewline($this->getSpecResultOpen() . $this->getSpecResultName() . $this->getSpecResultClose());
		$out .= $this->formatReportSpecChildrenOut($this->getReportSpecChildrenOut());
		$out .= $this->formatter->putNewline($this->getSpecClose());

		return rtrim($out);
	}

/**/

	protected function getReportSpecChildrenOut()
	{
		if ($this->getSpec() instanceof SpecContainerInterface && count($this->getSpecChildren()))
		{
			if ($this->getSpec()->isAnonymous())
				return $this->getReportAnonymousSpecChildrenOut();
			else
				return $this->getReportNamedSpecChildrenOut();
		}

		return '';
	}

	protected function getReportAnonymousSpecChildrenOut()
	{
		$out = '';
		foreach ($this->getSpecChildren() as $spec)
			$out .= $this->formatter->putNewline($this->delegate($spec)->getReportSpecOut());

		return rtrim($out);
	}

	protected function getReportNamedSpecChildrenOut()
	{
		$out = '';
		$out .= $this->formatter->putNewline($this->getSpecChildrenOpen());

		foreach ($this->getSpecChildren() as $spec)
			$out .= $this->formatReportSpecChildrenOut($this->delegate($spec)->getReportSpecOut());

		$out .= $this->formatter->putNewline($this->getSpecChildrenClose());

		return rtrim($out);
	}

	protected function formatReportSpecChildrenOut($text)
	{
		return $this->formatter->putIndentionToEachLineAndNewline($text);
	}

/**/

	protected function getSpec()
	{
		return $this->owner->getOwner();
	}
	
	protected function getSpecChildren()
	{
		if ($this->structureType == 'declaring')
			$specs = $this->getSpec()->getSpecs();
		else
			$specs = $this->getSpec()->getSpecsToRun();

		$result = array();
		foreach ($specs as $index => $spec)
		{
			if ($spec->isEnabled() || $this->getPutDisabledSpecs())
				$result[$index] = $spec;
		}

		return $result;
	}

	public function isSpecLastRunResultExists()
	{
		return array_key_exists($this->getSpec()->getUid(), $this->owner->getLastRunResults());
	}

	public function getSpecLastRunResult()
	{
		$results = $this->owner->getLastRunResults();
		return @$results[$this->getSpec()->getUid()];
	}

	protected function delegate(SpecInterface $spec)
	{
		$accessName = $this->getOwner()->getAccessName();
		$format = clone $this;
		$format->setOwner($spec->$accessName);
		return $format;
	}
}