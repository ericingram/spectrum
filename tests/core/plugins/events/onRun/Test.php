<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events\onRun;
use spectrum\core\plugins\Manager;
use spectrum\core\SpecItemIt;
use spectrum\core\RunResultsBuffer;
use spectrum\core\World;

require_once __DIR__ . '/../../../../init.php';

abstract class Test extends \spectrum\core\plugins\events\Test
{
	public function testBefore_ShouldBeTriggeredAfterRunStart()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunBefore', \spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertTrue(\spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['isRunning']);

		Manager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBeTriggeredBeforeRunStart()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$this->assertNull(\spectrum\Test::$tmp['triggeredEvents']['onRun']);

		Manager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBeTriggeredOnce()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertEquals(2, count(\spectrum\Test::$tmp['triggeredEvents']['onRun']));
		$this->assertEquals('onRunBefore',\spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		Manager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBePassArguments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertSame(array(), \spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['arguments']);

		Manager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterOnRunBeforeEvent()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunBefore', \spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		Manager::unregisterPlugin('foo');
	}

	public function testAfter_ShouldBeTriggeredBeforeRunStop()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunAfter', \spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);
		$this->assertTrue(\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['isRunning']);

		Manager::unregisterPlugin('foo');
	}

	public function testAfter_ShouldBeTriggeredOnce()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertEquals(2, count(\spectrum\Test::$tmp['triggeredEvents']['onRun']));
		$this->assertEquals('onRunBefore', \spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		Manager::unregisterPlugin('foo');
	}
}