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
 */
class PluginsManager
{
	static protected $registeredPlugins = array(
		'matchers' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\Matchers', 'activateMoment' => 'whenCallOnce'),
		'builders' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\worldCreators\Builders', 'activateMoment' => 'whenCallOnce'),
		'destroyers' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\worldCreators\Destroyers', 'activateMoment' => 'whenCallOnce'),
		'selector' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\Selector', 'activateMoment' => 'whenCallOnce'),
		'errorHandling' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\ErrorHandling', 'activateMoment' => 'whenCallOnce'),
		'liveReport' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\LiveReport', 'activateMoment' => 'whenCallOnce'),
	);

	static public function registerPlugin($accessName, $class = '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', $activateMoment = 'whenCallOnce')
	{
		$reflection = new \ReflectionClass($class);
		if (!$reflection->implementsInterface('\net\mkharitonov\spectrum\core\plugin\PluginInterface'))
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
		unset(static::$registeredPlugins[$accessName]);
	}

	static public function unregisterAllPlugins()
	{
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
		$interface = '\net\mkharitonov\spectrum\core\plugin\events\\' . $interface . 'Interface';

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