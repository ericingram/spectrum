<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts;

interface MatcherCallDetailsInterface
{
	public function setActualValue($actualValue);
	public function getActualValue();
	public function setIsNot($isNot);
	public function getIsNot();
	public function setMatcherName($matcherName);
	public function getMatcherName();
	public function setMatcherArgs(array $matcherArgs);
	public function getMatcherArgs();
	public function setMatcherReturnValue($matcherReturnValue);
	public function getMatcherReturnValue();
	public function setException(\Exception $matcherException = null);
	public function getException();
}