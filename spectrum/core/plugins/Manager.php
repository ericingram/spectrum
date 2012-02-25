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

namespace net\mkharitonov\spectrum\core\plugins;
use net\mkharitonov\spectrum\core\Config;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Manager implements ManagerInterface
{
	static protected $registeredPlugins = array(
		'matchers' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Matchers', 'activateMoment' => 'whenCallOnce'),
		'builders' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\worldCreators\Builders', 'activateMoment' => 'whenCallOnce'),
		'destroyers' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\worldCreators\Destroyers', 'activateMoment' => 'whenCallOnce'),
		'selector' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Selector', 'activateMoment' => 'whenCallOnce'),
		'errorHandling' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\ErrorHandling', 'activateMoment' => 'whenCallOnce'),
		'report' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\report\Report', 'activateMoment' => 'whenCallOnce'),
		'output' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Output', 'activateMoment' => 'whenCallOnce'),
		'messages' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Messages', 'activateMoment' => 'whenCallOnce'),
		'patterns' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Patterns', 'activateMoment' => 'whenCallOnce'),
	);

	static public function registerPlugin($accessName, $class = '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', $activateMoment = 'whenCallOnce')
	{
		if (!Config::getAllowPluginsRegistration())
			throw new Exception('Plugins registration deny in Config');

		if (!Config::getAllowPluginsOverride() && static::hasRegisteredPlugin($accessName))
			throw new Exception('Plugins override deny in Config');

		$reflection = new \ReflectionClass($class);
		if (!$reflection->implementsInterface('\net\mkharitonov\spectrum\core\plugins\PluginInterface'))
			throw new Exception('Class "' . $class . '" should be implements PluginInterface');

		if (!in_array($activateMoment, array('whenConstructOnce', 'whenCallOnce', 'whenCallAlways')))
			throw new Exception('Wrong activateMoment "' . $activateMoment . '" for plugin with access name "' . $accessName . '"');

		static::$registeredPlugins[$accessName] = array('class' => $class, 'activateMoment' => $activateMoment);
	}

	static public function registerPlugins($plugins)
	{
		foreach ($plugins as $accessName => $pluginInfo)
		{
			if (@$pluginInfo['class'] !== null && @$pluginInfo['activateMoment'] !== null)
				static::registerPlugin($accessName, $pluginInfo['class'], $pluginInfo['activateMoment']);
			else if (@$pluginInfo['class'] !== null)
				static::registerPlugin($accessName, $pluginInfo['class']);
			else
				static::registerPlugin($accessName);
		}
	}

	static public function unregisterPlugin($accessName)
	{
		if (!Config::getAllowPluginsOverride())
			throw new Exception('Plugins override deny in Config');

		unset(static::$registeredPlugins[$accessName]);
	}

	static public function unregisterAllPlugins()
	{
		if (!Config::getAllowPluginsOverride())
			throw new Exception('Plugins override deny in Config');

		static::$registeredPlugins = array();
	}

	static public function getRegisteredPlugins()
	{
		return static::$registeredPlugins;
	}

	static public function getAccessNamesForEventPlugins($eventName)
	{
		$eventInterface = static::getEventInterfaceByEventName($eventName);
		
		$result = array();
		foreach (static::$registeredPlugins as $accessName => $pluginInfo)
		{
			$reflection = new \ReflectionClass($pluginInfo['class']);
			if ($reflection->implementsInterface($eventInterface))
				$result[] = $accessName;
		}

		return $result;
	}

	static protected function getEventInterfaceByEventName($eventName)
	{
		$interface = $eventName;
		$interface = preg_replace('/(Before|After)$/s', '', $interface);
		$interface = preg_replace('/^on/s', 'On', $interface);
		$interface = '\net\mkharitonov\spectrum\core\plugins\events\\' . $interface . 'Interface';

		if (!interface_exists($interface))
			throw new Exception('Interface "' . $interface . '" for event "' . $eventName . '" not exists');

		return $interface;
	}

	static public function getRegisteredPlugin($accessName)
	{
		if (!static::hasRegisteredPlugin($accessName))
			throw new Exception('Plugin with access name "' . $accessName . '" not exists');

		return static::$registeredPlugins[$accessName];
	}

	static public function hasRegisteredPlugin($accessName)
	{
		return array_key_exists($accessName, static::$registeredPlugins);
	}

	static public function createPluginInstance(\net\mkharitonov\spectrum\core\SpecInterface $owner, $accessName)
	{
		$pluginInfo = static::getRegisteredPlugin($accessName);
		return new $pluginInfo['class']($owner, $accessName);
	}
}