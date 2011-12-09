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

namespace net\mkharitonov\spectrum\core\testEnv\emptyStubs\asserts;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @property not
 */
class ResultDetails implements \net\mkharitonov\spectrum\core\asserts\ResultDetailsInterface
{
	public function setActualValue($actualValue){}
	public function getActualValue(){}
	public function setIsNot($isNot){}
	public function getIsNot(){}
	public function setMatcherName($matcherName){}
	public function getMatcherName(){}
	public function setMatcherArgs(array $matcherArgs){}
	public function getMatcherArgs(){}
	public function setMatcherReturnValue($matcherReturnValue){}
	public function getMatcherReturnValue(){}
	public function setMatcherException(\Exception $matcherException = null){}
	public function getMatcherException(){}
}