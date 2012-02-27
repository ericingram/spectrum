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
				$this->getIndention() . '.g-runResultsBuffer { position: relative; margin: 0.5em 0 1em 0; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0.5em; color: #888; font-size: 0.9em; font-weight: normal; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results { clear: both; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results *[title] { cursor: help; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result { float: left; position: relative; margin: 1.4em 2px 2px 0; padding: 0.5em; border: 1px solid; border-left: 0; border-top: 0; border-radius: 0 0 5px 5px; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.num { position: absolute; top: -1.4em; left: 0; padding: 3px 5px 0.1em 5px; border-radius: 10px 10px 0 0; background: #ccc; color: #555; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.value { position: absolute; top: -1.4em; right: -1px; padding: 3px 5px 0.1em 5px; border-right: 1px solid; border-radius: 10px 10px 0 0; background: #ccc; color: #555; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result .title { font-weight: bold; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true { border-color: #b5dfb5; background: #ccffcc; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true>.num { background: #ccffcc; color: #3a473a; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true>.value { border-color: #b5dfb5; background: #ccffcc; color: #3a473a; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false { border-color: #e2b5b5; background: #ffcccc; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false>.num { background: #ffcccc; color: #3d3232; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false>.value { border-color: #e2b5b5; background: #ffcccc; color: #3d3232; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		if (!($this->getReport()->getOwner() instanceof SpecItemInterface))
			return null;

		$output = '';

		$output .= '<div class="g-runResultsBuffer g-clearfix">' . $this->getNewline();
		$output .= $this->getIndention() . '<div class="results">' . $this->getNewline();
		$num = 0;
		foreach ($this->getReport()->getOwner()->getRunResultsBuffer()->getResults() as $result)
		{
			$num++;
			$output .= $this->getIndention(2) . '<div class="result ' . ($result['result'] ? 'true' : 'false') . '">' . $this->getNewline();
			$output .= $this->getIndention(3) . '<div class="num" title="Order in run results buffer">' . $num . '</div>' . $this->getNewline();
			$output .= $this->getIndention(3) . '<div class="value" title="Value, contains in run results buffer">' . $this->getVariableValueDump($result['result']) . '</div>' . $this->getNewline();
			$output .= $this->prependIndentionToEachLine($this->trimNewline($this->getHtmlForResultDetails($result['details'])), 3) . $this->getNewline();
			$output .= $this->getIndention(2) . '</div>' . $this->getNewline();
		}

		$output .= $this->getIndention() . '</div>' . $this->getNewline();

		$output .= '</div>' . $this->getNewline();

		return $output;
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

		$output .= $this->getIndention() . $this->getHtmlForMethod('be', array($details->getActualValue()));

		if ($details->getIsNot())
		{
			$output .= '<span class="operator objectAccess">-&gt;</span>';
			$output .= '<span class="property not">not</span>';
		}

		$output .= '<span class="opeator objectAccess">-&gt;</span>';
		$output .= $this->getHtmlForMethod($details->getMatcherName(), $details->getMatcherArgs());

		$output .= $this->getNewline();
		$output .= $this->getIndention() . '<div class="returnValue"><span class="title" title="Matcher return value">Return:</span> ' . $this->getHtmlForVariable($details->getMatcherReturnValue()) . '</div>' . $this->getNewline();
		$output .= $this->getIndention() . '<div class="returnValue"><span class="title" title="Matcher thrown exception">Thrown:</span> ' . $this->getHtmlForVariable($details->getException()) . '</div>' . $this->getNewline();
		$output .= '</div>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForMethod($methodName, array $arguments)
	{
		return
			'<span class="method ' . htmlspecialchars($methodName) . '">' .
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