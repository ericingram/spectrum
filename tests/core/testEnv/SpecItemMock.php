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

namespace net\mkharitonov\spectrum\core\testEnv;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class SpecItemMock extends \net\mkharitonov\spectrum\core\SpecItem
{
	static public function createRunningInstance()
	{
		static::setRunningInstance(new SpecItemItMock());
		return static::getRunningInstance();
	}

	static public function setRunningInstancePublic(\net\mkharitonov\spectrum\core\SpecItemInterface $instance = null)
	{
		return parent::setRunningInstance($instance);
	}
}