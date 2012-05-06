<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

interface SpecItemItInterface extends SpecItemInterface
{
	public function setTestCallback(\Closure $callback = null);
	public function getTestCallback();
	public function setTestCallbackArguments(array $args);
	public function getTestCallbackArguments();
}