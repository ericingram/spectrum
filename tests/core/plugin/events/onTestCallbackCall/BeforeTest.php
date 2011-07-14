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

namespace net\mkharitonov\spectrum\core\plugin\events\onTestCallbackCall;
use net\mkharitonov\spectrum\core\PluginsManager;
use net\mkharitonov\spectrum\core\SpecItemIt;
use net\mkharitonov\spectrum\core\ResultBuffer;
use net\mkharitonov\spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class BeforeTest extends Test
{
	private $eventName = 'onTestCallbackCallBefore';

	public function testShouldBeTriggeredAfterRunStart()
	{
		$this->createItWithPluginEventAndRun('\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getFirstEvent($this->eventName);
		$this->assertTrue($event['isRunning']);
	}

	public function testShouldBeTriggeredAfterResultBufferCreation()
	{
		$this->createItWithPluginEventAndRun('\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getFirstEvent($this->eventName);
		$this->assertTrue($event['resultBuffer'] instanceof ResultBuffer);
	}

	public function testShouldBeTriggeredAfterWorldBuildersApply()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->builders->add(function($world){ $world->foo = 'bar'; });
		$spec->setTestCallback(function(){});
		$spec->run();

		PluginsManager::unregisterPlugin('foo');

		$event = $this->getFirstEvent($this->eventName);
		$this->assertEquals('bar', $event['worldFooValue']);
	}

	public function testShouldBeTriggeredBeforeTestCallbackExecution()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'];
		});

		$spec->run();

		PluginsManager::unregisterPlugin('foo');

		$this->assertEquals($this->eventName, $triggeredEventsBeforeExecution[0]['name']);
	}

	public function testShouldBeTriggeredOnce()
	{
		$this->createItWithPluginEventAndRun('\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$this->assertEventTriggeredCount(1, $this->eventName);
	}

	public function testAdditionalArgumentsNotSet_ShouldBePassWorldToArguments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){});

		$spec->run();

		PluginsManager::unregisterPlugin('foo');

		$event = $this->getFirstEvent($this->eventName);
		$this->assertEquals(1, count($event['arguments']));
		$this->assertTrue($event['arguments'][0] instanceof World);
	}
}