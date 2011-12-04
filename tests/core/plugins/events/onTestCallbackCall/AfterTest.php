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
use net\mkharitonov\spectrum\core\ResultBuffer;
use net\mkharitonov\spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class AfterTest extends Test
{
	private $eventName = 'onTestCallbackCallAfter';
	
	public function testShouldBeTriggeredAfterBeforeEvent()
	{
		$this->createItWithPluginEventAndRun('\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$events = \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'];
		$this->assertEquals('onTestCallbackCallBefore', $events[0]['name']);
		$this->assertEquals($this->eventName, $events[1]['name']);
	}

	public function testShouldBeTriggeredAfterTestCallbackExecution()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'];
		});

		$spec->run();

		Manager::unregisterPlugin('foo');

		$this->assertEquals(1, count($triggeredEventsBeforeExecution));
		$this->assertNotEquals($this->eventName, $triggeredEventsBeforeExecution[0]['name']);
		$this->assertEquals($this->eventName, \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'][1]['name']);
	}

	public function testShouldBeTriggeredBeforeWorldDestroyersApply()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function(){});
		$spec->destroyers->add(function($world){ $world->foo = 'bar'; });
		$spec->run();

		Manager::unregisterPlugin('foo');

		$event = $this->getSecondEvent($this->eventName);
		$this->assertNull($event['worldFooValue']);
	}

	public function testShouldBeTriggeredBeforeResultBufferUnset()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){
			$spec->getResultBuffer()->addResult(false);
		});
		$spec->run();

		Manager::unregisterPlugin('foo');

		$event = $this->getSecondEvent($this->eventName);
		$this->assertTrue($event['resultBuffer'] instanceof ResultBuffer);
		$this->assertSame(array(
			array('result' => false, 'details' => null),
		), $event['resultBuffer']->getResults());
	}

	public function testShouldBeTriggeredBeforeRunStop()
	{
		$this->createItWithPluginEventAndRun('\net\mkharitonov\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getSecondEvent($this->eventName);
		$this->assertTrue($event['isRunning']);
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

		$event = $this->getSecondEvent($this->eventName);
		$this->assertEquals(1, count($event['arguments']));
		$this->assertTrue($event['arguments'][0] instanceof World);
	}
}