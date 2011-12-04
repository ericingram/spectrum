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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class LiveReport extends \net\mkharitonov\spectrum\core\plugins\Plugin implements events\OnRunInterface
{
	protected $printDebug;
	protected $indention = '    ';
	protected $newline = "\r\n";

/**/

	public function setPrintDebug($isEnable)
	{
		$this->printDebug = $isEnable;
	}

	public function getPrintDebug()
	{
		return $this->printDebug;
	}

	public function getPrintDebugCascade()
	{
		return $this->callCascadeThroughRunningContexts('getPrintDebug', array(), true);
	}

/**/

	public function getIndention($repeat = 1)
	{
		return str_repeat($this->indention, $repeat);
	}

	public function setIndention($string)
	{
		$this->indention = $string;
	}

	protected function putIndention($text)
	{
		if ($text != '')
			return $this->getIndention() . $text;
		else
			return $text;
	}

	protected function putIndentionAndNewline($text)
	{
		return $this->putNewline($this->putIndention($text));
	}

	protected function putIndentionToEachLine($text, $repeat = 1)
	{
		if ($text != '')
			return $this->getIndention($repeat) . str_replace("\r\n", "\r\n" . $this->getIndention($repeat), $text);
		else
			return $text;
	}

	protected function putIndentionToEachLineAndNewline($text, $repeat = 1)
	{
		return $this->putNewline($this->putIndentionToEachLine($text, $repeat));
	}

/**/

	public function setNewline($newline)
	{
		$this->newline = $newline;
	}

	public function getNewline()
	{
		return $this->newline;
	}

	protected function putNewline($text)
	{
		if ($text != '')
			return $text . $this->getNewline();
		else
			return $text;
	}

/**/

	public function getHeader()
	{
		$output = '';
		$output .= $this->putNewline('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
		$output .= $this->putNewline('<html xmlns="http://www.w3.org/1999/xhtml">');
		$output .= $this->putNewline('<head>');
		$output .= $this->putIndentionAndNewline('<meta http-equiv="content-type" content="text/html; charset=utf-8" />');
		$output .= $this->putIndentionAndNewline('<title></title>');
		$output .= $this->putIndentionToEachLineAndNewline($this->getStyles()) . $this->getNewline();
		$output .= $this->putIndentionToEachLineAndNewline($this->getScripts()) . $this->getNewline();
		$output .= $this->putNewline('</head>');
		$output .= $this->putNewline('<body>');
		$output .= $this->putNewline('<ol>');

		return rtrim($output);
	}

	protected function getStyles()
	{
		$output = '';
		$output .= $this->putNewline('<style type="text/css">');
		$output .= $this->putIndentionToEachLine($this->getCssRules()) . $this->getNewline();
		$output .= $this->putNewline('</style>');

		return rtrim($output);
	}

	protected function getCssRules()
	{
		$output = '';
		$output .= 'body { font-family: Verdana, sans-serif; font-size: 0.75em; }' . $this->getNewline();

		$output .= '.clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }' . $this->getNewline();
		$output .= '.clearfix { *zoom: 1; }' . $this->getNewline();

		$output .= 'ol { padding-left: 1.8em; }' . $this->getNewline();
		$output .= '.name { }' . $this->getNewline();
		$output .= '.it { font-weight: normal; }' . $this->getNewline();
		$output .= '.describe { }' . $this->getNewline();
		$output .= '.context { }' . $this->getNewline();
		$output .= '.context>.name:after { content: " (context)"; }' . $this->getNewline();

		$output .= '.finalResult:before { content: " — "; }' . $this->getNewline();
		$output .= '.finalResult { color: #ccc; font-weight: bold; }' . $this->getNewline();
		$output .= '.finalResult.fail { color: #a31010; }' . $this->getNewline();
		$output .= '.finalResult.success { color: #009900; }' . $this->getNewline();
		$output .= '.finalResult.empty { color: #cc9900; }' . $this->getNewline();

		$output .= '.resultBuffer:before { content: "Содержимое результирующего буфера: "; display: block; position: absolute; top: -1.8em; left: 0; padding: 0.3em 0.5em; background: #f5f1f1; color: #888; font-style: italic; }' . $this->getNewline();
		$output .= '.resultBuffer { position: relative; margin: 2em 0 1em 0; }' . $this->getNewline();

		$output .= '.resultBuffer .row .result:before { content: "Result: "; font-weight: bold; }' . $this->getNewline();
		$output .= '.resultBuffer .row { float: left; padding: 0.5em; }' . $this->getNewline();

		$output .= '.resultBuffer .row .details:before { display: block; content: "Details: "; font-weight: bold; }' . $this->getNewline();
		$output .= '.resultBuffer .row .details { white-space: pre; }' . $this->getNewline();
		$output .= '.resultBuffer .row .details.assert .title { font-size: 0.9em; }' . $this->getNewline();
		$output .= '.resultBuffer .row .details.assert .title:after { content: ": ";}' . $this->getNewline();

		$output .= '.resultBuffer .row.true { border-right: 1px solid #b5dfb5; background: #ccffcc; }' . $this->getNewline();
		$output .= '.resultBuffer .row.true .details.assert .title { color: #789578; }' . $this->getNewline();
		$output .= '.resultBuffer .row.true:last-child { border-right: 0; }' . $this->getNewline();

		$output .= '.resultBuffer .row.false { border-right: 1px solid #e2b5b5; background: #ffcccc; }' . $this->getNewline();
		$output .= '.resultBuffer .row.false .details.assert .title { color: #957979; }' . $this->getNewline();
		$output .= '.resultBuffer .row.false:last-child { border-right: 0; }' . $this->getNewline();

		return rtrim($output);
	}

	protected function getScripts()
	{
		$output = '
<script type="text/javascript">
	function updateResult(uid, resultLabel, resultName){
		var spec = document.getElementById(uid);
		var result = spec.childNodes[1];
		result.className += " " + resultLabel;
		result.innerHTML = resultName;
	}
</script>
		';

		return rtrim($output);
	}

	public function getFooter()
	{
		$output = '';
		$output .= '</ol>' . $this->getNewline();
		$output .= '</body>' . $this->getNewline();
		$output .= '</html>' . $this->getNewline();

		return rtrim($output);
	}

	public function onRunBefore()
	{
		if (!$this->getOwner()->getParent()) // Root describe
			print $this->getHeader();

		if (!$this->getOwner()->isAnonymous())
		{
			print '<li class="' . $this->getSpecLabel() . '" id="' . $this->getOwner()->getUid() . '">';

			print '<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>';
			print '<span class="finalResult">wait...</span>';

			if ($this->getOwner() instanceof SpecContainerInterface)
				print '<ol>';

			$this->flush();
		}
	}

	public function onRunAfter($finalResult)
	{
		if (!$this->getOwner()->isAnonymous())
		{
			if ($this->getOwner() instanceof SpecContainerInterface)
				print '</ol>';

			$this->updateResult($this->getOwner()->getUid(), $this->getSpecResultLabel($finalResult));
			if ($this->getPrintDebugCascade())
				$this->printResultBuffer($finalResult);
			
			print '</li>';

			$this->flush();
		}

		if (!$this->getOwner()->getParent()) // Root describe
			print $this->getFooter();
	}

	protected function printResultBuffer($finalResult)
	{
		if ($finalResult === false && $this->getOwner() instanceof SpecItemInterface)
		{
			print '<div class="resultBuffer clearfix">';
			foreach ($this->getOwner()->getResultBuffer()->getResults() as $result)
			{
				print '<div class="row ' . ($result['result'] === true ? 'true' : 'false') . '">';

				print '<div class="result">';
				print htmlspecialchars($this->getVarDump($result['result']));
				print '</div>';

				$details = $result['details'];
				if (is_object($details) && $details instanceof \net\mkharitonov\spectrum\core\assert\ResultDetails)
				{
					print '<div class="details assert">';
					// TODO print matcher view, like Details: bool false be(string "foo")->not->eq(string "bar", int 1)
					$this->printDetailsAssert('actualValue', $this->getVarDump($details->getActualValue()));
					$this->printDetailsAssert('isNot', $this->getVarDump($details->getIsNot()));
					$this->printDetailsAssert('matcherName', $details->getMatcherName());
					$this->printDetailsAssert('matcherArgs', $this->getVarDump($details->getMatcherArgs()));
					$this->printDetailsAssert('matcherReturnValue', $this->getVarDump($details->getMatcherReturnValue()));
					$this->printDetailsAssert('matcherException', $this->getVarDump($details->getMatcherException()));
					print '</div>';
				}
				else
				{
					print '<div class="details">';
					print var_dump($details);
					print '</div>';
				}

				print '</div>';
			}

			print '</div>';
		}
	}

	protected function printDetailsAssert($name, $value)
	{
		print '<div class="' . htmlspecialchars($name) . '">';
		print '<span class="title">' . htmlspecialchars($name) . '</span>';
		print '<span class="value">' . htmlspecialchars($value) . '</span>';
		print '</div>';
	}

	protected function getSpecName()
	{
		$parent = $this->getOwner()->getParent();
		$name = $this->getOwner()->getName();

		if ($name == '' && $parent && $parent instanceof \net\mkharitonov\spectrum\core\SpecContainerDataProvider)
			return $this->getAdditionalArgumentsDumpOut();
		else
			return $name;
	}

	protected function getAdditionalArgumentsDumpOut()
	{
		$out = '';
		foreach ($this->getOwner()->getAdditionalArguments() as $arg)
		{
			$out .= $arg . ', ';
		}

		return mb_substr($out, 0, -2);
	}

	protected function getSpecLabel()
	{
		if ($this->getOwner() instanceof SpecContainerDescribeInterface)
			return 'describe';
		else if ($this->getOwner() instanceof SpecContainerContextInterface)
			return 'context';
		else if ($this->getOwner() instanceof SpecItemItInterface)
			return 'it';
		else if ($this->getOwner() instanceof SpecContainerInterface)
			return 'container';
		else if ($this->getOwner() instanceof SpecItemInterface)
			return 'item';
		else
			return 'spec';
	}

	protected function getSpecResultLabel($result)
	{
		if ($result === false)
			$name = 'fail';
		else if ($result === true)
			$name = 'success';
		else
			$name = 'empty';

		return $name;
	}

/**/

	protected function updateResult($specUid, $resultLabel)
	{
		print '
			<script type="text/javascript">
				updateResult("' . $specUid . '", "' . $resultLabel . '", "' . $resultLabel . '");
			</script>
		';
	}

	protected function flush()
	{
		print '<span style="display: none;">' . str_repeat(' ', 256) . '</span>' . $this->getNewline();
		flush();
	}

/**/

	protected function getVarDump($var)
	{
		switch (gettype($var))
		{
			case 'NULL':
				return 'null';
			case 'boolean':
				return 'bool(' . ($var ? 'true' : 'false') . ')';
			case 'integer':
				return "int($var)";
			case 'double':
				return "float($var)";
			case 'string':
				return 'string(' . mb_strlen($var) . ')' . ' "' . $var . '"';
			case 'array':
				return $this->getArrayDump($var);
			case 'object':
			case 'resource':
				ob_start();
				var_dump($var);
				return ob_get_clean();
		}

		return null;
	}

	protected function getArrayDump(array $var)
	{
		$out = '';
		$out .= 'array(' . count($var) . ')' . ' {';

		if (count($var))
		{
			$out .= $this->getNewline();

			foreach ($var as $key => $val)
			{
				// TODO nested array print
				// TODO get indention from Formatter
				$out .= "    [$key] => " . $this->getVarDump($val) . $this->getNewline();
			}
		}

		$out .= '}';
		return $out;
	}
}