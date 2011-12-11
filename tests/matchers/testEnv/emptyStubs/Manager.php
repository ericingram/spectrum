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

namespace net\mkharitonov\spectrum\matchers\testEnv\emptyStubs;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Manager implements \net\mkharitonov\spectrum\matchers\ManagerInterface
{
	static public function addAllMatchersToSpec(\net\mkharitonov\spectrum\core\SpecInterface $spec){}
	static public function addBaseMatchersToSpec(\net\mkharitonov\spectrum\core\SpecInterface $spec){}
}