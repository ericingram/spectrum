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

namespace net\mkharitonov\spectrum\core\plugins\events\onTestCallbackCall;
use net\mkharitonov\spectrum\core\plugins\Manager;
use net\mkharitonov\spectrum\core\SpecItemIt;
use net\mkharitonov\spectrum\core\RunResultsBuffer;
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

	public function testShouldBeTriggeredAfterRunResultsBufferCreation()
	{
		$this->createItWithPluginEventAndRun('\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getFirstEvent($this->eventName);
		$this->assertTrue($event['runResultsBuffer'] instanceof RunResultsBuffer);
	}

	public function testShouldBeTriggeredAfterWorldBuildersApply()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->builders->add(function($world){ $world->foo = 'bar'; });
		$spec->setTestCallback(function(){});
		$spec->run();

		Manager::unregisterPlugin('foo');

		$event = $this->getFirstEvent($this->eventName);
		$this->assertEquals('bar', $event['worldFooValue']);
	}

	public function testShouldBeTriggeredBeforeTestCallbackExecution()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'];
		});

		$spec->run();

		Manager::unregisterPlugin('foo');

		$this->assertEquals($this->eventName, $triggeredEventsBeforeExecution[0]['name']);
	}

	public function testShouldBeTriggeredOnce()
	{
		$this->createItWithPluginEventAndRun('\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$this->assertEventTriggeredCount(1, $this->eventName);
	}

	public function testAdditionalArgumentsNotSet_ShouldBePassWorldToArguments()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){});

		$spec->run();

		Manager::unregisterPlugin('foo');

		$event = $this->getFirstEvent($this->eventName);
		$this->assertEquals(1, count($event['arguments']));
		$this->assertTrue($event['arguments'][0] instanceof World);
	}
}