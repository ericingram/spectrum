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

namespace net\mkharitonov\spectrum\core\testEnv\emptyStubs;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecItem extends Spec implements \net\mkharitonov\spectrum\core\SpecItemInterface
{
	static function getRunningInstance(){}
	public function getResultBuffer(){}
	public function setTestCallback($callback){}
	public function getTestCallback(){}
	public function setAdditionalArguments(array $args){}
	public function getAdditionalArguments(){}
}