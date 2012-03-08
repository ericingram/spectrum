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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\code\variables;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ArrayVar extends Variable
{
	protected $type = 'array';

	public function getStyles()
	{
		$componentSelector = '.g-code-variables-' . htmlspecialchars($this->type);

		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . "$componentSelector { display: inline-block; vertical-align: top; }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector $componentSelector { display: inline;  }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector>.elements { display: block; margin-left: 1px; padding-left: 20px; border-left: 1px solid #ccc; }" . $this->getNewline() .
				$this->getIndention() . "$componentSelector>.elements>.element { display: block; }" . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml($variable)
	{
		$output = '';
		$output .= '<span class="g-code-variables g-code-variables-' . htmlspecialchars($this->type) . '">';
		$output .= $this->getIndention() . $this->getHtmlForType($variable) . $this->getNewline();
		$output .= $this->getIndention() . '<span class="bracket open">{</span>' . $this->getNewline();

		if (count($variable))
		{
			$output .= $this->getIndention() . '<span class="elements">' . $this->getNewline();
			foreach ($variable as $key => $val)
				$output .= $this->prependIndentionToEachLine($this->trimNewline($this->getHtmlForElement($key, $val)), 2) . $this->getNewline();

			$output .= $this->getIndention() . '</span>' . $this->getNewline();
		}

		$output .= $this->getIndention() . '<span class="bracket close">}</span>' . $this->getNewline();
		$output .= '</span>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForElement($key, $val)
	{
		$codeComponent = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\code\Code($this->getReport());

		$keyHtml = '<span class="key">' . htmlspecialchars("[$key]") . '</span>';
		$operatorHtml = ' ' . $codeComponent->getHtmlForOperator('=>') . ' ';
		$valHtml = $this->trimNewline($codeComponent->getHtmlForVariable($val));

		return
			$this->getIndention() . '<span class="element">' . $this->getNewline() .
			$this->prependIndentionToEachLine($keyHtml . $operatorHtml . $valHtml, 2) . $this->getNewline() .
			$this->getIndention() . '</span>' . $this->getNewline();
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '(' . count($variable) . ')</span>';
	}
}