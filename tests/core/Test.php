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

namespace net\mkharitonov\spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \net\mkharitonov\spectrum\Test
{
	private $oldInstance;
	protected $oldPlugins;

	protected $currentSpecClass;
	protected $currentSpecMockClass;

	protected function setUp()
	{
		parent::setUp();

		$this->oldInstance = \net\mkharitonov\spectrum\core\SpecItem::getRunningInstance();

		$this->oldPlugins = \net\mkharitonov\spectrum\core\plugins\Manager::getRegisteredPlugins();
		\net\mkharitonov\spectrum\core\testEnv\PluginStub::reset();
//		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('matchers', '\net\mkharitonov\spectrum\core\testEnv\MatchersStub');
//		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('builders', '\net\mkharitonov\spectrum\core\testEnv\WorldCreatorsBuildersStub');
//		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('destroyers', '\net\mkharitonov\spectrum\core\testEnv\WorldCreatorsDestroyersStub');
	}

	protected function tearDown()
	{
		\net\mkharitonov\spectrum\core\plugins\Manager::unregisterAllPlugins();
		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugins($this->oldPlugins);
		
		\net\mkharitonov\spectrum\core\testEnv\SpecItemMock::setRunningInstancePublic($this->oldInstance);

		parent::tearDown();
	}

/*** Test ware ***/
	
	/**
	 * @return SpecContainerContext|
	 *         SpecContainerDescribe|
	 *         SpecItemIt
	 */
	protected function createCurrentSpec()
	{
		$args = func_get_args();
		$reflection = new \ReflectionClass($this->currentSpecClass);
		return $reflection->newInstanceArgs($args);
	}

	/**
	 * @return \net\mkharitonov\spectrum\core\testEnv\SpecContainerContextMock|
	 *         \net\mkharitonov\spectrum\core\testEnv\SpecContainerDescribeMock|
	 *         \net\mkharitonov\spectrum\core\testEnv\SpecItemItMock
	 */
	protected function createCurrentSpecMock()
	{
		$args = func_get_args();
		$reflection = new \ReflectionClass($this->currentSpecMockClass);
		return $reflection->newInstanceArgs($args);
	}

	protected function assertEventTriggeredCount($expectedCount, $eventName)
	{
		$eventClassName = $this->getEventClassNameByEventName($eventName);

		$count = 0;
		foreach (\net\mkharitonov\spectrum\Test::$tmp['triggeredEvents'][$eventClassName] as $event)
		{
			if ($event['name'] == $eventName)
				$count++;
		}

		$this->assertEquals($expectedCount, $count);
	}

	protected function getEventClassNameByEventName($eventName)
	{
		return preg_replace('/(Before|After)$/s', '', $eventName);
	}
}