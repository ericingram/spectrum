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

namespace net\mkharitonov\spectrum\reports\widgets\code\variables;

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
		$output .= $this->getIndention() . $this->codeWidget->getHtmlForOperator('{');

		if (count($variable))
		{
			$output .= '<span class="elements">';
			foreach ($variable as $key => $val)
				$output .= $this->getHtmlForElement($key, $val);

			$output .= '</span>';
		}

		$output .= $this->codeWidget->getHtmlForOperator('}');
		$output .= '</span>';

		return $output;
	}

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '<span title="Elements count">(' . count($variable) . ')</span></span>';
	}
}