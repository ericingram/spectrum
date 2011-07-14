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
abstract class SpecContainerTest extends Test
{
	public function testBefore_ShouldBeTriggeredBeforeRunChildren()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'];
		});

		$specs[0]->run();

		$this->assertEquals('onRunBefore', $triggeredEventsBeforeExecution[0]['name']);

		PluginsManager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterRunChildren()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \net\mkharitonov\spectrum\Test::$tmp['triggeredEvents']['onRun'];
		});

		$specs[0]->run();

		$this->assertGreaterThan(0, count($triggeredEventsBeforeExecution));
		foreach ($triggeredEventsBeforeExecution as $event)
		{
			$this->assertNotEquals('onRunAfter', $event['name']);
		}

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertEquals('onRunAfter', $event['name']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testAfter_SuccessResult_ShouldBePassResultToArguments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use($specs){
			$specs[1]->getResultBuffer()->addResult(true);
		});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(true), $event['arguments']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testAfter_FailResult_ShouldBePassResultToArguments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use($specs){
			$specs[1]->getResultBuffer()->addResult(false);
		});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(false), $event['arguments']);

		PluginsManager::unregisterPlugin('foo');
	}

	public function testAfter_EmptyResult_ShouldBePassResultToArguments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function(){});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(null), $event['arguments']);

		PluginsManager::unregisterPlugin('foo');
	}
}