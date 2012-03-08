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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\code;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Code extends \net\mkharitonov\spectrum\core\plugins\basePlugins\report\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-code-variable { display: inline-block; }' . $this->getNewline() .
				$this->getIndention() . '.g-code-value { display: inline-block; }' . $this->getNewline() .
				$this->getIndention() . '.g-code-value.array { vertical-align: top; }' . $this->getNewline() .
				$this->getIndention() . '.g-code-value.array .elements { display: block; }' . $this->getNewline() .
				$this->getIndention() . '.g-code-value.array .elements .element { display: block; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtmlForOperator($operator)
	{
		return '<span class="g-code-operator">' . htmlspecialchars($operator) . '</span>';
	}

	public function getHtmlForPropertyAccess($propertyName)
	{
		return '<span class="g-code-property">' . htmlspecialchars($propertyName) . '</span>';
	}

	public function getHtmlForMethod($methodName, array $arguments, $valueMaxLength = null)
	{
		return
			'<span class="g-code-method">' .
				'<span class="methodName">' . htmlspecialchars($methodName) . '</span>' .
				'<span class="arguments">(' . $this->getHtmlForArguments($arguments, $valueMaxLength) . ')</span>' .
			'</span>';
	}

	public function getHtmlForArguments(array $arguments, $valueMaxLength = null)
	{
		$output = '';
		foreach ($arguments as $argument)
			$output .= $this->getHtmlForVariable($argument, $valueMaxLength) . ', ';

		return mb_substr($output, 0, -2);
	}

	public function getHtmlForVariable($variable, $valueMaxLength = null)
	{
		$type = $this->getVariableType($variable);
		$variableComponentClassName = mb_strtoupper(mb_substr($type, 0, 1)) . mb_substr($type, 1) . 'Var';
		$variableComponentClass = '\net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\code\variables\\' . $variableComponentClassName;
		$variableComponent = new $variableComponentClass($this->getReport());
		return $variableComponent->getHtml($variable);
	}

	public function getVariableType($variable)
	{
		$type = mb_strtolower(gettype($variable));

		if ($type == 'boolean')
			$type = 'bool';
		else if ($type == 'integer')
			$type = 'int';
		else if ($type == 'double')
			$type = 'float';
		else if ($type == 'string')
			$type = 'string';
		else if ($type == 'array')
			$type = 'array';
		else if ($type == 'object')
		{
			$closure = function(){};
			if ($variable instanceof $closure)
				$type = 'closure';
			else
				$type = 'object';
		}
		else if ($type == 'resource')
			$type = 'resource';
		else if ($type == 'null')
			$type = 'null';
		else
			$type = 'unknown';

		return $type;
	}
}