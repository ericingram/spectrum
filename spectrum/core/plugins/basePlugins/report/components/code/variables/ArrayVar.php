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
class ArrayVar extends VariableHierarchical
{
	protected $type = 'array';

	public function getHtml($variable)
	{
		$output = '';
		$output .= '<span class="g-code-variables g-code-variables-' . htmlspecialchars($this->type) . '">';
		$output .= $this->getIndention() . $this->getHtmlForType($variable) . $this->getNewline();
		$output .= $this->getIndention() . $this->codeComponent->getHtmlForOperator('{');

		if (count($variable))
		{
			$output .= '<span class="elements">';
			foreach ($variable as $key => $val)
				$output .= $this->getHtmlForElement($key, $val);

			$output .= '</span>';
		}

		$output .= $this->codeComponent->getHtmlForOperator('}');
		$output .= '</span>';

		return $output;
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '<span title="Elements count">(' . count($variable) . ')</span></span>';
	}
}