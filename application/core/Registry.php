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

namespace net\mkharitonov\spectrum\core;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Registry
{
	/**
	 * @var \net\mkharitonov\spectrum\core\SpecItemInterface
	 */
	static protected $runningSpecItem;

	static public function getRunningSpecItem()
	{
		return static::$runningSpecItem;
	}

	static public function setRunningSpecItem(SpecItemInterface $instance = null)
	{
		if ($instance && !$instance->isRunning())
			throw new Exception('Method "' . __METHOD__ . '" should be accept only running specs');

		static::$runningSpecItem = $instance;
	}
}