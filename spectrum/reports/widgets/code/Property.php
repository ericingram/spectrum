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

namespace spectrum\reports\widgets\code;

class Property extends \spectrum\reports\widgets\Widget
{
	public function getHtml($propertyName)
	{
		return '<span class="g-code-property">' . htmlspecialchars($propertyName) . '</span>';
	}
}