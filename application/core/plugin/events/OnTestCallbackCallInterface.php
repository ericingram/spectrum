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

namespace net\mkharitonov\spectrum\core\plugin\events;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
interface OnTestCallbackCallInterface extends \net\mkharitonov\spectrum\core\plugin\EventInterface
{
	public function onTestCallbackCallBefore(\net\mkharitonov\spectrum\core\World $world);
	public function onTestCallbackCallAfter(\net\mkharitonov\spectrum\core\World $world);
}