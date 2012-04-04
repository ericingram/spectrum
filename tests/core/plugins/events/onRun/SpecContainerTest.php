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

namespace spectrum\core\plugins\events\onRun;
use spectrum\core\plugins\Manager;
use spectrum\core\SpecItemIt;
use spectrum\core\RunResultsBuffer;
use spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class SpecContainerTest extends Test
{
	public function testBefore_ShouldBeTriggeredBeforeRunChildren()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\Test::$tmp['triggeredEvents']['onRun'];
		});

		$specs[0]->run();

		$this->assertEquals('onRunBefore', $triggeredEventsBeforeExecution[0]['name']);

		Manager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterRunChildren()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\Test::$tmp['triggeredEvents']['onRun'];
		});

		$specs[0]->run();

		$this->assertGreaterThan(0, count($triggeredEventsBeforeExecution));
		foreach ($triggeredEventsBeforeExecution as $event)
		{
			$this->assertNotEquals('onRunAfter', $event['name']);
		}

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertEquals('onRunAfter', $event['name']);

		Manager::unregisterPlugin('foo');
	}

	public function testAfter_SuccessResult_ShouldBePassResultToArguments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use($specs){
			$specs[1]->getRunResultsBuffer()->addResult(true);
		});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(true), $event['arguments']);

		Manager::unregisterPlugin('foo');
	}

	public function testAfter_FailResult_ShouldBePassResultToArguments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use($specs){
			$specs[1]->getRunResultsBuffer()->addResult(false);
		});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(false), $event['arguments']);

		Manager::unregisterPlugin('foo');
	}

	public function testAfter_EmptyResult_ShouldBePassResultToArguments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function(){});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(null), $event['arguments']);

		Manager::unregisterPlugin('foo');
	}
}