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
use net\mkharitonov\spectrum\core\Config;
use \net\mkharitonov\spectrum\core\asserts\MatcherCallDetailsInterface;
use \net\mkharitonov\spectrum\core\plugins\Exception;
use \net\mkharitonov\spectrum\core\plugins\events;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Report extends \net\mkharitonov\spectrum\core\plugins\Plugin implements events\OnRunInterface
{
	protected $indention = "\t";
	protected $newline = "\r\n";

/**/

	public function getIndention($repeat = 1)
	{
		return str_repeat($this->indention, $repeat);
	}

	public function setIndention($string)
	{
		if (!Config::getAllowReportSettingsModify())
			throw new Exception('Report settings modify deny in Config');

		$this->indention = $string;
	}

	protected function putIndention($text)
	{
		if ($text != '')
			return $this->getIndention() . $text;
		else
			return $text;
	}

	protected function prependIndentionToEachLine($text, $repeat = 1)
	{
		if ($text != '')
			return $this->getIndention($repeat) . str_replace("\n", "\n" . $this->getIndention($repeat), $text);
		else
			return $text;
	}

/**/

	public function setNewline($newline)
	{
		if (!Config::getAllowReportSettingsModify())
			throw new Exception('Report settings modify deny in Config');

		$this->newline = $newline;
	}

	public function getNewline($repeat = 1)
	{
		return str_repeat($this->newline, $repeat);
	}

	protected function putNewline($text)
	{
		if ($text != '')
			return $text . $this->getNewline();
		else
			return $text;
	}

/**/

	public function onRunBefore()
	{
		if (!$this->getOwner()->getParent()) // Root describe
			$this->getOwner()->output->put($this->getHeader());

		if (!$this->getOwner()->isAnonymous())
		{
			$this->getOwner()->output->put('<li class="' . $this->getSpecLabel() . '" id="' . $this->getOwner()->selector->getUidForSpec() . '">');

			$this->getOwner()->output->put('<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>');
			$this->getOwner()->output->put('<span class="g-finalResult">wait...</span>');

			if ($this->getOwner() instanceof SpecContainerInterface)
				$this->getOwner()->output->put('<ol>');

			$this->flush();
		}
	}

	public function onRunAfter($finalResult)
	{
		if (!$this->getOwner()->isAnonymous())
		{
			if ($this->getOwner() instanceof SpecContainerInterface)
				$this->getOwner()->output->put('</ol>');

			$this->getOwner()->output->put($this->getScriptForResultUpdate($this->getOwner()->selector->getUidForSpec(), $this->getSpecResultLabel($finalResult)));
			if ($finalResult === false)
				$this->getOwner()->output->put($this->getHtmlForRunResultsBuffer($finalResult));

			$this->getOwner()->output->put($this->getHtmlForMessages());

			$this->getOwner()->output->put('</li>');

			$this->flush();
		}

		if (!$this->getOwner()->getParent()) // Root describe
			$this->getOwner()->output->put($this->getFooter());
	}

/**/

	protected function getHeader()
	{
		return
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . $this->getNewline() .
			'<html xmlns="http://www.w3.org/1999/xhtml">' . $this->getNewline() .
			'<head>' . $this->getNewline() .
				$this->getIndention() . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . $this->getNewline() .
				$this->getIndention() . '<title></title>' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getStyles()) .
				$this->prependIndentionToEachLine($this->getScripts()) . $this->getNewline() .
			'</head>' . $this->getNewline() .
			$this->getBodyTag() .
				$this->getIndention() . '<ol>' . $this->getNewline();
	}

	protected function getBodyTag()
	{
		return
			'<!--[if IE 6]><body class="g-browser-ie g-browser-ie6"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 7]><body class="g-browser-ie g-browser-ie7"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 8]><body class="g-browser-ie g-browser-ie8"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 9]><body class="g-browser-ie g-browser-ie9"><![endif]-->' . $this->getNewline() .
			'<!--[if !IE]>--><body><!--<![endif]-->' . $this->getNewline();
	}

	protected function getFooter()
	{
		return
				$this->getIndention() . '</ol>' . $this->getNewline() .
			'</body>' . $this->getNewline() .
			'</html>';
	}

/**/

	protected function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getCssRulesForDocument()) . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getCssRulesForClearfix()) . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getCssRulesForFinalResult()) . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getCssRulesForRunResultsBuffer()) . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getCssRulesForMessages()) . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	protected function getScripts()
	{
		return
			'<script type="text/javascript">' . $this->getNewline() .
				'function updateResult(uid, resultLabel, resultName){' . $this->getNewline() .
					'var spec = document.getElementById(uid);' . $this->getNewline() .
					'var result = spec.childNodes[1];' . $this->getNewline() .
					'result.className += " " + resultLabel;' . $this->getNewline() .
					'result.innerHTML = resultName;' . $this->getNewline() .
				'}' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

/**/

	protected function getCssRulesForDocument()
	{
		return
			'body { font-family: Verdana, sans-serif; font-size: 0.75em; }' . $this->getNewline() .
	
			'ol { padding-left: 1.8em; }' . $this->getNewline() .
			'.name { }' . $this->getNewline() .
			'.it { font-weight: normal; }' . $this->getNewline() .
			'.describe { }' . $this->getNewline() .
			'.context { }' . $this->getNewline() .
			'.context>.name:after { content: " (context)"; }' . $this->getNewline();
	}

	protected function getCssRulesForClearfix()
	{
		return
			'.g-clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }' . $this->getNewline() .
			'.g-clearfix { *zoom: 1; }' . $this->getNewline();
	}

	protected function getCssRulesForRunResultsBuffer()
	{
		return
			'.g-runResultsBuffer:before { content: "Содержимое результирующего буфера: "; display: block; position: absolute; top: -1.8em; left: 0; padding: 0.3em 0.5em; background: #f5f1f1; color: #888; font-style: italic; }' . $this->getNewline() .
			'.g-runResultsBuffer { position: relative; margin: 2em 0 1em 0; }' . $this->getNewline() .

			'.g-runResultsBuffer .row .result:before { content: "Result: "; font-weight: bold; }' . $this->getNewline() .
			'.g-runResultsBuffer .row { float: left; padding: 0.5em; }' . $this->getNewline() .

			'.g-runResultsBuffer .row .details:before { display: block; content: "Details: "; font-weight: bold; }' . $this->getNewline() .
			'.g-runResultsBuffer .row .details { white-space: pre; }' . $this->getNewline() .
			'.g-runResultsBuffer .row .details.assert .title { font-size: 0.9em; }' . $this->getNewline() .
			'.g-runResultsBuffer .row .details.assert .title:after { content: ": ";}' . $this->getNewline() .

			'.g-runResultsBuffer .row.true { border-right: 1px solid #b5dfb5; background: #ccffcc; }' . $this->getNewline() .
			'.g-runResultsBuffer .row.true .details.assert .title { color: #789578; }' . $this->getNewline() .
			'.g-runResultsBuffer .row.true:last-child { border-right: 0; }' . $this->getNewline() .

			'.g-runResultsBuffer .row.false { border-right: 1px solid #e2b5b5; background: #ffcccc; }' . $this->getNewline() .
			'.g-runResultsBuffer .row.false .details.assert .title { color: #957979; }' . $this->getNewline() .
			'.g-runResultsBuffer .row.false:last-child { border-right: 0; }' . $this->getNewline();
	}

	protected function getCssRulesForFinalResult()
	{
		return
			'.g-finalResult:before { content: " — "; }' . $this->getNewline() .
			'.g-finalResult { color: #ccc; font-weight: bold; }' . $this->getNewline() .
			'.g-finalResult.fail { color: #a31010; }' . $this->getNewline() .
			'.g-finalResult.success { color: #009900; }' . $this->getNewline() .
			'.g-finalResult.empty { color: #cc9900; }' . $this->getNewline();
	}

	protected function getCssRulesForMessages()
	{
		return
			'.g-messages:before { content: "Сообщения: "; display: block; position: absolute; top: -1.8em; left: 0; padding: 0.3em 0.5em; background: #f5f1f1; color: #888; font-style: italic; }' . $this->getNewline() .
			'.g-messages { position: relative; margin: 2em 0 1em 0; }' . $this->getNewline() .
			'.g-messages ul { display: inline-block; list-style: none; }' . $this->getNewline() .
			'.g-messages ul li { padding: 5px; margin-bottom: 1px; background: #ccc; }' . $this->getNewline();
	}

/**/

	protected function getHtmlForRunResultsBuffer()
	{
		if (!($this->getOwner() instanceof SpecItemInterface))
			return null;

		$output = '';

		$output .= '<div class="g-runResultsBuffer g-clearfix">' . $this->getNewline();
		foreach ($this->getOwner()->getRunResultsBuffer()->getResults() as $result)
		{
			$output .= $this->getIndention() . '<div class="result ' . ($result['result'] ? 'true' : 'false') . '">' . $this->getNewline();
			$output .= $this->prependIndentionToEachLine($this->getHtmlForResultValue($result['result']), 2);
			$output .= $this->prependIndentionToEachLine($this->getHtmlForResultDetails($result['details']), 2);
			$output .= $this->getIndention() . '</div>' . $this->getNewline();
		}

		$output .= '</div>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForResultValue($result)
	{
		return '<div class="value">' . $this->getHtmlForVariable($result) . '</div>' . $this->getNewline();
	}

	protected function getHtmlForResultDetails($details)
	{
		if (is_object($details) && $details instanceof MatcherCallDetailsInterface)
			return $this->getHtmlForResultDetailsForMatcherCall($details);
		else
			return $this->getHtmlForResultDetailsForOther($details);
	}

	protected function getHtmlForResultDetailsForMatcherCall(MatcherCallDetailsInterface $details)
	{
		$output = '';
		$output .= '<div class="details matcherCall">' . $this->getNewline();

		$output .= $this->getHtmlForMethod('be', array($details->getActualValue()));

		if ($details->getIsNot())
		{
			$output .= '<span class="operator objectAccess">-&gt;</span>';
			$output .= '<span class="property not">not</span>';
		}

		$output .= '<span class="opeator objectAccess">-&gt;</span>';
		$output .= $this->getHtmlForMethod(
			$details->getMatcherName(),
			$details->getMatcherArgs(),
			$details->getMatcherReturnValue(),
			$details->getException()
		);

		$output .= $this->getNewline();
		$output .= '</div>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForMethod($methodName, array $arguments, $returnValue = null, $exception = null)
	{
		return
			'<span class="method ' . htmlspecialchars($methodName) . '">' .
				(func_num_args() >= 3 ? '<span class="returnValue">' . $this->getHtmlForVariable($returnValue) . ' </span>' : '') .
				($exception ? '<span class="exception">' . $this->getHtmlForVariable($exception) . ' </span>' : '') .
				'<span class="methodName">' . htmlspecialchars($methodName) . '</span>' .
				'<span class="arguments">(' . $this->getHtmlForArguments($arguments) . ')</span>' .
			'</span>';
	}

	protected function getHtmlForArguments(array $arguments)
	{
		$output = '';
		foreach ($arguments as $argument)
			$output .= $this->getHtmlForVariable($argument) . ', ';

		return mb_substr($output, 0, -2);
	}

	protected function getHtmlForVariable($variable)
	{
		$type = $this->getVariableType($variable);
		$value = $this->getVariableValueDump($variable);

		return
			'<span class="variable">' .
				($type != '' ? '<span class="type">' . htmlspecialchars($type) . '</span>' : '') .
				($type != '' && $value != '' ? ' ' : '') .
				($value != '' ? '<span class="value">' . htmlspecialchars($value) . '</span>' : '') .
			'</span>';
	}

	protected function getVariableType($variable)
	{
		$type = mb_strtolower(gettype($variable));

		if ($type == 'boolean')
			$type = 'bool';
		else if ($type == 'integer')
			$type = 'int';
		else if ($type == 'double')
			$type = 'float';
		else if ($type == 'string')
			$type = 'string(' . mb_strlen($variable) . ')';
		else if ($type == 'array')
			$type = 'array(' . count($variable) . ')';

		return $type;
	}

	protected function getVariableValueDump($variable)
	{
		$type = mb_strtolower(gettype($variable));

		if ($type == 'null')
			return '';
		else if ($type == 'boolean')
			return ($variable ? 'true' : 'false');
		else if ($type == 'integer')
			return $variable;
		else if ($type == 'double')
			return $variable;
		else if ($type == 'string')
			return '"' . $variable . '"';
		else if ($type == 'array')
			return $this->getArrayDump($variable);
		else //if ($type == 'object' || $type == 'resource')
		{
			ob_start();
			var_dump($variable);
			return ob_get_clean();
		}
	}

	protected function getArrayDump(array $var)
	{
		$output = '';
		$output .= '{';

		if (count($var))
		{
			$output .= $this->getNewline();

			foreach ($var as $key => $val)
			{
				// TODO nested array print
				$output .= $this->getIndention() . "[" . htmlspecialchars($key) . "] => " . $this->getVariableValueDump($val) . $this->getNewline();
			}
		}

		$output .= '}';
		return $output;
	}

	protected function getHtmlForResultDetailsForOther($details)
	{
		return
			'<div class="details other">' . $this->getNewline() .
				$this->getIndention() . var_dump($details) . $this->getNewline() .
			'</div>' . $this->getNewline();
	}

	protected function getHtmlForMessages()
	{
		$messages = $this->getOwner()->messages->getAll();

		if (!count($messages))
			return null;

		$output = '';

		$output .= '<div class="g-messages g-clearfix">' . $this->getNewline();
		$output .= $this->getIndention() . '<ul>' . $this->getNewline();
		foreach ($messages as $message)
			$output .= $this->getIndention(2) . '<li>' . htmlspecialchars($message) . '</li>' . $this->getNewline();

		$output .= $this->getIndention() . '</ul>' . $this->getNewline();
		$output .= '</div>' . $this->getNewline();

		return $output;
	}

/**/

	protected function getSpecName()
	{
		$parent = $this->getOwner()->getParent();
		$name = $this->getOwner()->getName();

		if ($name == '' && $parent && $parent instanceof \net\mkharitonov\spectrum\core\SpecContainerArgumentsProviderInterface)
			return $this->getAdditionalArgumentsDumpOut();
		else
			return $name;
	}

	protected function getAdditionalArgumentsDumpOut()
	{
		$output = '';
		foreach ($this->getOwner()->getAdditionalArguments() as $arg)
			$output .= $arg . ', ';

		return mb_substr($output, 0, -2);
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

	protected function getScriptForResultUpdate($specUid, $resultLabel)
	{
		// TODO заменить на периодическую проверку по коду в <head>
		return
			'<script type="text/javascript">' .
				'updateResult("' . $specUid . '", "' . $resultLabel . '", "' . $resultLabel . '");' .
			'</script>';
	}

	protected function flush()
	{
		$this->getOwner()->output->put('<span style="display: none;">' . str_repeat(' ', 256) . '</span>' . $this->getNewline());
		flush();
	}
}