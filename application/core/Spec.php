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
 * @property \net\mkharitonov\spectrum\core\basePlugins\Matchers matchers
 * @property \net\mkharitonov\spectrum\core\basePlugins\worldCreators\Builders builders
 * @property \net\mkharitonov\spectrum\core\basePlugins\worldCreators\Destroyers destroyers
 * @property \net\mkharitonov\spectrum\core\basePlugins\Selector selector
 * @property \net\mkharitonov\spectrum\core\basePlugins\ErrorHandling errorHandling
 * @property \net\mkharitonov\spectrum\core\basePlugins\LiveReport liveReport
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
	protected $pluginManagerClass = '\net\mkharitonov\spectrum\core\PluginsManager';

	public function __construct($name = null)
	{
		$this->setName($name);

		$class = $this->pluginManagerClass;
		foreach ($class::getRegisteredPlugins() as $accessName => $plugin)
		{
			if ($plugin['activateMoment'] == 'whenConstructOnce')
				$this->activatePlugin($accessName);
		}
	}

	public function __get($accessName)
	{
		return $this->callPlugin($accessName);
	}
	
	public function callPlugin($accessName)
	{
		$manager = $this->pluginManagerClass;
		$plugin = $manager::getRegisteredPlugin($accessName);

		if ($plugin['activateMoment'] == 'whenCallAlways' || !$this->isPluginActivated($accessName))
			return $this->activatePlugin($accessName);
		else
			return $this->activatedPlugins[$accessName];
	}

	protected function isPluginActivated($accessName)
	{
		return array_key_exists($accessName, $this->activatedPlugins);
	}
	
	protected function activatePlugin($accessName)
	{
		$manager = $this->pluginManagerClass;
		$this->activatedPlugins[$accessName] = $manager::createPluginInstance($this, $accessName);
		return $this->activatedPlugins[$accessName];
	}

	protected function triggerEvent($eventName)
	{
		$args = func_get_args();
		unset($args[0]);
		$args = array_values($args);

		$manager = $this->pluginManagerClass;
		foreach ($manager::getAccessNamesForEventPlugins($eventName) as $accessName)
		{
			$pluginInstance = $this->callPlugin($accessName);
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