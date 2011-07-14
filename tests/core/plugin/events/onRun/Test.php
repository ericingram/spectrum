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

namespace net\mkharitonov\spectrum\core\plugin\events\onRun;
use net\mkharitonov\spectrum\core\PluginsManager;
use net\mkharitonov\spectrum\core\SpecItemIt;
use net\mkharitonov\spectrum\core\ResultBuffer;
use net\mkharitonov\spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \net\mkharitonov\spectrum\core\plugin\events\Test
{
	public function testBefore_ShouldBeTriggeredAfterRunStart()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunBefore', \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertTrue(\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['isRunning']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBeTriggeredBeforeRunStart()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$this->assertNull(\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBeTriggeredOnce()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertEquals(2, count(\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun']));
		$this->assertEquals('onRunBefore',\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBePassArguments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertSame(array(), \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['arguments']);

		PluginsManager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterOnRunBeforeEvent()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunBefore', \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testAfter_ShouldBeTriggeredBeforeRunStop()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunAfter', \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);
		$this->assertTrue(\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['isRunning']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testAfter_ShouldBeTriggeredOnce()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertEquals(2, count(\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun']));
		$this->assertEquals('onRunBefore', \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		PluginsManager::unregisterPlugin('foo');
	}
}