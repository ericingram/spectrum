<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs;

class SpecItemIt extends SpecItem implements \spectrum\core\SpecItemItInterface
{
	public function getRunResultsBuffer(){}
	public function setTestCallback(\Closure $callback = null){}
	public function getTestCallback(){}
	public function setTestCallbackArguments(array $args){}
	public function getTestCallbackArguments(){}
}