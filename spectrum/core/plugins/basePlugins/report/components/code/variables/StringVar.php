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
class StringVar extends Variable
{
	protected $type = 'string';

	protected function getHtmlForType($variable)
	{
		return '<span class="type">' . htmlspecialchars($this->type) . '(' . mb_strlen($variable) . ')</span>';
	}

	protected function getHtmlForValue($variable)
	{
		return ' <span class="value">' . htmlspecialchars('"' . $variable . '"') . '</span>';
	}
}