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

namespace net\mkharitonov\spectrum\constructionCommands;

require_once dirname(__FILE__) . '/baseCommands/addMatcher.php';
require_once dirname(__FILE__) . '/baseCommands/beforeEach.php';
require_once dirname(__FILE__) . '/baseCommands/afterEach.php';

require_once dirname(__FILE__) . '/baseCommands/container.php';
require_once dirname(__FILE__) . '/baseCommands/describe.php';
require_once dirname(__FILE__) . '/baseCommands/context.php';
require_once dirname(__FILE__) . '/baseCommands/It.php';

require_once dirname(__FILE__) . '/baseCommands/actual.php';

require_once dirname(__FILE__) . '/baseCommands/setCurrentContainer.php';
require_once dirname(__FILE__) . '/baseCommands/getCurrentContainer.php';
require_once dirname(__FILE__) . '/baseCommands/getCurrentItem.php';

require_once dirname(__FILE__) . '/baseCommands/isDeclaringState.php';
require_once dirname(__FILE__) . '/baseCommands/isRunningState.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @method \net\mkharitonov\spectrum\core\assert\Assert actual()
 * @method addMatcher()
 * @method afterEach()
 * @method beforeEach()
 * @method \net\mkharitonov\spectrum\core\SpecContainerInterface container()
 * @method \net\mkharitonov\spectrum\core\SpecContainerContext context()
 * @method \net\mkharitonov\spectrum\core\SpecContainerDescribe describe()
 * @method fail()
 * @method \net\mkharitonov\spectrum\core\SpecContainerInterface getCurrentContainer()
 * @method \net\mkharitonov\spectrum\core\SpecItemInterface getCurrentItem()
 * @method isDeclaringState()
 * @method isRunningState()
 * @method \net\mkharitonov\spectrum\core\SpecItemIt it()
 * @method setCurrentContainer()
 */
class Manager
{
	static protected $registeredCommands = array(
		'addMatcher' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\addMatcher',
		'beforeEach' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\beforeEach',
		'afterEach' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\afterEach',

		'container' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\container',
		'describe' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\describe',
		'context' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\context',
		'it' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\It::it',

		'actual' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\actual',

		'setCurrentContainer' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\setCurrentContainer',
		'getCurrentContainer' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\getCurrentContainer',
		'getCurrentItem' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\getCurrentItem',

		'isDeclaringState' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\isDeclaringState',
		'isRunningState' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\isRunningState',
	);
	
	static protected $createdAliases = array();

	static public function __callStatic($name, $args = array())
	{
		return call_user_func_array(static::getRegisteredCommandCallback($name), $args);
	}
	
	static public function createGeneralGlobalAliasesOnce()
	{
		static::createGlobalAliasOnce('describe');
		static::createGlobalAliasOnce('context');
		static::createGlobalAliasOnce('it');
		static::createGlobalAliasOnce('addMatcher');
		static::createGlobalAliasOnce('beforeEach');
		static::createGlobalAliasOnce('afterEach');
		static::createGlobalAliasOnce('actual');
	}

	static public function createGlobalAliasOnce($commandName)
	{
		if (!static::hasRegisteredCommand($commandName))
			throw new Exception('Command "' . $commandName . '" not exists');

		if (in_array($commandName, static::$createdAliases))
			return false;

		if (function_exists($commandName))
			throw new Exception('Function "' . $commandName . '" already exists');
		
		$result = eval("namespace {
			function $commandName ()
			{
				\$args = func_get_args();
				return call_user_func_array('\\net\\mkharitonov\\spectrum\\constructionCommands\\Manager::$commandName', \$args);
			}
		}");

		if ($result === false)
			throw new Exception('Error while creating global alias for "' . $commandName . '"');

		static::$createdAliases[] = $commandName;
		return true;
	}

	static public function registerCommand($name, $callback)
	{
		// RegExp from http://www.php.net/manual/en/functions.user-defined.php
		if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/is', $name))
			throw new Exception('Bad name for command "' . $name . '"');

		static::$registeredCommands[$name] = $callback;
	}

	static public function registerCommands($commands)
	{
		foreach ($commands as $name => $callback)
		{
			static::registerCommand($name, $callback);
		}
	}

	static public function unregisterCommand($name)
	{
		unset(static::$registeredCommands[$name]);
	}

	static public function unregisterAllCommands()
	{
		static::$registeredCommands = array();
	}

	static public function getRegisteredCommands()
	{
		return static::$registeredCommands;
	}

	static public function getRegisteredCommandCallback($name)
	{
		if (!static::hasRegisteredCommand($name))
			throw new Exception('Command "' . $name . '" not exists');

		return static::$registeredCommands[$name];
	}

	static public function hasRegisteredCommand($name)
	{
		return array_key_exists($name, static::$registeredCommands);
	}
}