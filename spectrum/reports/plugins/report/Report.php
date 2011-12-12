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

use \net\mkharitonov\spectrum\core\plugins\events;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Report extends \net\mkharitonov\spectrum\core\plugins\Plugin implements ReportInterface, events\OnRunInterface, events\OnRunItemInterface, events\OnRunContainerInterface
{
	protected $inputEncoding;
	protected $outputEncoding;

	protected $liveOutput = false;
	protected $lastRunResults = array();

	public function setInputEncoding($inputEncoding)
	{
		$this->inputEncoding = $inputEncoding;
	}

	public function getInputEncoding()
	{
		return $this->inputEncoding;
	}

/**/

	public function setOutputEncoding($outputEncoding)
	{
		$this->outputEncoding = $outputEncoding;
	}

	public function getOutputEncoding()
	{
		return $this->outputEncoding;
	}

/**/

	public function getLastRunResults()
	{
		return $this->lastRunResults;
	}

/**/

	public function getLastRunReport($format = 'xhtml', $putHeader = true, $putFooter = true)
	{
		return $this->getReport($format, 'running', true, $putHeader, $putFooter);
	}

	public function getSpecsList($format = 'xhtml', $putHeader = true, $putFooter = true)
	{
		return $this->getReport($format, 'declaring', false, $putHeader, $putFooter);
	}

	public function getReport($format = 'xhtml', $structureType = 'running', $putLastRunResults = true, $putHeader = true, $putFooter = true)
	{
		$format = $this->createFormat($format);
		$format->setStructureType($structureType);
		$format->setPutDisabledSpecs(true);
		$format->setPutLastRunResults($putLastRunResults);
		return $format->getReport($putHeader, $putFooter);
	}

	/**
	 * @param boolean|string $format xml|xhtml|text, false - disable output, true - 'xhtml'
	 */
	public function setLiveOutput($format)
	{
		if ($format === true)
			$format = 'xhtml';
		
		$this->liveOutput = $format;
	}

	public function getLiveOutput()
	{
		return $this->liveOutput;
	}

/**/

	public function onRunBefore()
	{
	}

	public function onRunAfter($result)
	{
		$this->lastRunResults[$this->getOwner()->getUid()] = $result;
	}

	public function onRunContainerBefore()
	{
		if ($this->omitEvent())
			return;

		$liveOutputFormat = $this->createLiveOutputFormat();

		print $liveOutputFormat->getSpecOpen();

		print $liveOutputFormat->getSpecNameOpen();
		print $liveOutputFormat->getSpecName();
		print $liveOutputFormat->getSpecNameClose();

		print $liveOutputFormat->getSpecResultOpen();
		print 'wait...';
		print $liveOutputFormat->getSpecResultClose();

		print $liveOutputFormat->getSpecChildrenOpen();

		$this->flush();
	}

	public function onRunContainerAfter($result)
	{
		if ($this->omitEvent())
			return;

		$liveOutputFormat = $this->createLiveOutputFormat();

		$liveOutputFormat->getSpecChildrenClose();
		if ($this->liveOutput == 'xhtml')
			$this->updateResult();

		$liveOutputFormat->getSpecClose();
		$this->flush();
	}

	public function onRunItemBefore()
	{
		if ($this->omitEvent())
			return;

		$liveOutputFormat = $this->createLiveOutputFormat();

		print $liveOutputFormat->getSpecOpen();

		print $liveOutputFormat->getSpecNameOpen();
		print $liveOutputFormat->getSpecName();
		print $liveOutputFormat->getSpecNameClose();

		print $liveOutputFormat->getSpecResultOpen();
		print 'wait...';
		print $liveOutputFormat->getSpecResultClose();

		$this->flush();
	}

	public function onRunItemAfter($result)
	{
		if ($this->omitEvent())
			return;
		
		$this->updateResult();
		
//		if ($result === false)
//		{
//			print '<div class="resultBuffer clearfix">';
//			foreach ($this->getOwner()->getResultBuffer()->getResults() as $result)
//			{
//				print '<div class="result">';
//
//				print '<div class="value">';
//				print htmlspecialchars($this->getVarDump($result['result']));
//				print '</div>';
//
//				$details = $result['details'];
//				if (is_object($details) && $details instanceof \net\mkharitonov\spectrum\core\assert\ResultDetails)
//				{
//					print '<div class="details assert">';
//					print '<div class="actualValue">' . htmlspecialchars($this->getVarDump($details->getActualValue())) . '</div>';
//					print '<div class="isNot">' . htmlspecialchars($this->getVarDump($details->getIsNot())) . '</div>';
//					print '<div class="matcherName">' . htmlspecialchars($details->getMatcherName()) . '</div>';
//					print '<div class="matcherArgs">' . htmlspecialchars($this->getVarDump($details->getMatcherArgs())) . '</div>';
//					print '<div class="matcherReturnValue">' . htmlspecialchars($this->getVarDump($details->getMatcherReturnValue())) . '</div>';
//					print '<div class="matcherException">' . htmlspecialchars($this->getVarDump($details->getMatcherException())) . '</div>';
//					print '</div>';
//				}
//				else
//				{
//					print '<div class="details">';
//					print var_dump($details);
//					print '</div>';
//				}
//
//				print '</div>';
//			}
//
//			print '</div>';
//		}


		$this->createLiveOutputFormat()->getSpecClose();
		$this->flush();
	}
	
	
	protected function omitEvent()
	{
		return ($this->getOwner()->isAnonymous() || !$this->callCascadeThroughRunningContexts('getLiveOutput', array(), 'xhtml'));
	}

/**/

	protected function updateResult()
	{
		$liveOutputFormat = $this->createLiveOutputFormat();

		$specUid = $this->getOwner()->getUid();
		$resultLabel = $liveOutputFormat->getSpecResultLabel();
		$resultName = $liveOutputFormat->getSpecResultName();
		
		print '
			<script type="text/javascript">
				(function(uid, resultLabel, resultName){
					var spec = document.getElementById(uid);
					var result = spec.childNodes[3];
					result.className += " " + resultLabel;
					result.innerText = resultName;
				})("' . $specUid . '", "' . $resultLabel . '", "' . $resultName . '");
			</script>
		';
	}

	protected function flush()
	{
		print '<span style="display: none;">' . str_repeat(' ', 9999) . '</span>';
		flush();
	}

/**/

	protected function getVarDump($var)
	{
		return $this->createFormatter()->getVarDump($var);
	}

	/**
	 * @return \net\mkharitonov\spectrum\core\plugins\basePlugins\reports\Format
	 */
	protected function createLiveOutputFormat()
	{
		$format = $this->createFormat($this->liveOutput);
		$format->setStructureType('running');
		$format->setPutId(true);
		$format->setPutDisabledSpecs(false);
		$format->setPutLastRunResults(false);
		return $format;
	}

	/**
	 * @return \net\mkharitonov\spectrum\core\plugins\basePlugins\reports\formats\Xhtml
	 */
	protected function createFormat($format)
	{
		if ($format == 'xhtml')
			$format = '\net\mkharitonov\spectrum\core\plugins\basePlugins\reports\formats\Xhtml';
		else if ($format == 'xml')
			$format = '\net\mkharitonov\spectrum\core\plugins\basePlugins\reports\formats\Xml';
		else if ($format == 'plain')
			$format = '\net\mkharitonov\spectrum\core\plugins\basePlugins\reports\formats\Plain';

		$reflection = new \ReflectionClass($format);
		if (!$reflection->implementsInterface('\net\mkharitonov\spectrum\core\plugins\basePlugins\reports\FormatInterface'))
			throw new Exception('Class "' . $format . '" should be implements report\FormatInterface');

		return new $format($this, $this->createFormatter());
	}

	protected function createFormatter()
	{
		$formatter = new Formatter();
		$formatter->setInputEncoding($this->getInputEncoding());
		$formatter->setOutputEncoding($this->getOutputEncoding());
		return $formatter;
	}
}