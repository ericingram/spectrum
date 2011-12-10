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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @property \net\mkharitonov\spectrum\core\plugins\basePlugins\Matchers matchers
 * @property \net\mkharitonov\spectrum\core\plugins\basePlugins\worldCreators\Builders builders
 * @property \net\mkharitonov\spectrum\core\plugins\basePlugins\worldCreators\Destroyers destroyers
 * @property \net\mkharitonov\spectrum\core\plugins\basePlugins\Selector selector
 * @property \net\mkharitonov\spectrum\core\plugins\basePlugins\ErrorHandling errorHandling
 * @property \net\mkharitonov\spectrum\core\plugins\basePlugins\LiveReport liveReport
 * @todo implements \ArrayAccess for plugin access
 */
abstract class Spec implements SpecInterface
{
	protected $name;
	/**
	 * @var SpecInterface
	 */
	protected $parent;
	private $isEnabled = true;
	private $isEnabledTemporarily = null;
	protected $activatedPlugins = array();
	protected $isRunning = false;

	public function __construct($name = null)
	{
		$this->setName($name);

		$class = Config::getPluginsManagerClass();
		foreach ($class::getRegisteredPlugins() as $pluginAccessName => $plugin)
		{
			if ($plugin['activateMoment'] == 'whenConstructOnce')
				$this->activatePlugin($pluginAccessName);
		}
	}

	public function __get($pluginAccessName)
	{
		return $this->callPlugin($pluginAccessName);
	}
	
	public function callPlugin($pluginAccessName)
	{
		$manager = Config::getPluginsManagerClass();
		$plugin = $manager::getRegisteredPlugin($pluginAccessName);

		if ($plugin['activateMoment'] == 'whenCallAlways' || !$this->isPluginActivated($pluginAccessName))
			return $this->activatePlugin($pluginAccessName);
		else
			return $this->activatedPlugins[$pluginAccessName];
	}

	protected function isPluginActivated($pluginAccessName)
	{
		return array_key_exists($pluginAccessName, $this->activatedPlugins);
	}
	
	protected function activatePlugin($pluginAccessName)
	{
		$manager = Config::getPluginsManagerClass();
		$this->activatedPlugins[$pluginAccessName] = $manager::createPluginInstance($this, $pluginAccessName);
		return $this->activatedPlugins[$pluginAccessName];
	}

	protected function triggerEvent($eventName)
	{
		$args = func_get_args();
		unset($args[0]);
		$args = array_values($args);

		$manager = Config::getPluginsManagerClass();
		foreach ($manager::getAccessNamesForEventPlugins($eventName) as $pluginAccessName)
		{
			$pluginInstance = $this->callPlugin($pluginAccessName);
			call_user_func_array(array($pluginInstance, $eventName), $args);
		}
	}

/**/

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	/**
	 * Be carefully, this method return correct result only when called for running spec. For not running spec uid
	 * does not have running contexts stack uid.
	 */
	public function getUid()
	{
		$uid = '';

		$stack = $this->selector->getAncestorsStack();
		$stack[] = $this;
		$uid .= 'spec' . $this->getUidIndexes($stack);

		$contextsStack = $this->selector->getAncestorRunningContextsStack();
		$uid .= '_context' . $this->getUidIndexes($contextsStack);

		return $uid;
	}

	private function getUidIndexes(array $stack)
	{
		$uid = '';
		foreach ($stack as $spec)
		{
			$index = $spec->selector->getIndexInParent();
			if ($index === null)
				$index = 0;

			$uid .= '_' . $index;
		}

		return $uid;
	}

/**/

	public function setParent(SpecContainerInterface $spec = null)
	{
		$this->parent = $spec;
	}

	/**
	 * @return SpecContainerInterface
	 */
	public function getParent()
	{
		return $this->parent;
	}

	public function removeFromParent()
	{
		if ($this->getParent())
		{
			$this->getParent()->removeSpec($this);
			$this->setParent(null);
		}
	}

/**/

	public function enable()
	{
		$this->isEnabled = true;
		$this->isEnabledTemporarily = null;
	}

	public function disable()
	{
		$this->isEnabled = false;
		$this->isEnabledTemporarily = null;
	}

	public function isEnabled()
	{
		if ($this->isEnabledTemporarily !== null)
			return $this->isEnabledTemporarily;
		else
			return $this->isEnabled;
	}

/**/

	protected function enableTemporarily()
	{
		$this->isEnabledTemporarily = true;
	}

	protected function disableTemporarily()
	{
		$this->isEnabledTemporarily = false;
	}

	protected function resetEnabledTemporarily()
	{
		$this->isEnabledTemporarily = null;
	}

/**/

	public function isRunning()
	{
		return $this->isRunning;
	}

	protected function startRun()
	{
		$this->isRunning = true;
	}

	protected function stopRun()
	{
		$this->isRunning = false;
	}

	protected function runSelfThroughAncestors()
	{
		$this->disableSiblingsTemporarily();
		$this->enableTemporarily();

		$result = $this->getParent()->run();

		$this->resetEnabledTemporarily();
		$this->resetSiblingsEnabledTemporarily();

		return $result;
	}

	protected function disableSiblingsTemporarily()
	{
		foreach ($this->getParent()->getSpecs() as $spec)
		{
			if ($this->isSibling($spec))
				$spec->disableTemporarily();
		}
	}

	protected function resetSiblingsEnabledTemporarily()
	{
		foreach ($this->getParent()->getSpecs() as $spec)
		{
			if ($this->isSibling($spec))
				$spec->resetEnabledTemporarily();
		}
	}

	protected function isSibling(SpecInterface $spec)
	{
		return !($spec instanceof SpecContainerContextInterface);
	}

/**/

	protected function calculateFinalResult(array $results)
	{
		foreach ($results as $result)
		{
			if ($result === false)
				return false;
			else if ($result === null)
				return null;
		}

		if (count($results))
			return true;
		else
			return null;
	}
}