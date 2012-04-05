<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt;
use spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../init.php';

class IsRunningTest extends Test
{
	public function testShouldBeReturnTrueDuringRunning()
	{
		$isRunning = null;
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$isRunning) { $isRunning = $it->isRunning(); });

		$it->run();

		$this->assertTrue($isRunning);
	}

	public function testShouldBeReturnFalseIfSpecNotRunning()
	{
		$it = new SpecItemIt();
		$this->assertFalse($it->isRunning());
	}

	public function testShouldBeReturnFalseAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function(){});

		$it->run();

		$this->assertFalse($it->isRunning());
	}
}