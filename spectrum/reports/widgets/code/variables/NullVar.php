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
class NullVar extends Variable
{
	protected $type = 'null';

	protected function getHtmlForType($variable)
	{
		return null;
	}

	protected function getHtmlForValue($variable)
	{
		return '<span class="value">null</span>';
	}
}