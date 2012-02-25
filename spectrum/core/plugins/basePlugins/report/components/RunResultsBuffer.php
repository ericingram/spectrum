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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\report\components;
use \net\mkharitonov\spectrum\core\asserts\MatcherCallDetailsInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class RunResultsBuffer extends \net\mkharitonov\spectrum\core\plugins\basePlugins\report\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
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
				'.g-runResultsBuffer .row.false:last-child { border-right: 0; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		if (!($this->getReport()->getOwner() instanceof SpecItemInterface))
			return null;

		$output = '';

		$output .= '<div class="g-runResultsBuffer g-clearfix">' . $this->getNewline();
		foreach ($this->getReport()->getOwner()->getRunResultsBuffer()->getResults() as $result)
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
}