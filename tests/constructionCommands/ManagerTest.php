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
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ManagerTest extends \net\mkharitonov\spectrum\Test
{
	public function testShouldBeHaveRegisteredBaseCommandsByDefault()
	{
		$this->assertSame(array(
			'addPattern' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\addPattern',
			'addMatcher' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\addMatcher',
			'beforeEach' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\beforeEach',
			'afterEach' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\afterEach',

			'container' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\container',
			'describe' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\describe',
			'context' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\context',
			'it' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\it',
			'itLikePattern' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\itLikePattern',

			'be' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\be',

			'fail' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\fail',
			'message' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\message',

			'getCurrentContainer' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\getCurrentContainer',
			'setDeclaringContainer' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\setDeclaringContainer',
			'getDeclaringContainer' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\getDeclaringContainer',
			'getCurrentItem' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\getCurrentItem',

			'setSettings' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\setSettings',

			'isDeclaringState' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\isDeclaringState',
			'isRunningState' => '\net\mkharitonov\spectrum\constructionCommands\baseCommands\isRunningState',
		), Manager::getRegisteredCommands());
	}

	public function testCallStatic_ShouldBeCallRegisteredCommandAndPassArgumentsToCallback()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function($a, $b) use(&$passedA, &$passedB){
			$passedA = $a;
			$passedB = $b;
		});

		Manager::foo('aaa', 'bbb');

		$this->assertEquals('aaa', $passedA);
		$this->assertEquals('bbb', $passedB);
	}

	public function testCallStatic_ShouldBeCallRegisteredCommandAndReturnCallbackResult()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function(){ return 'bar'; });
		$this->assertEquals('bar', Manager::foo());
	}

	public function testCallCommand_ShouldBePassArgumentsToRegisteredCommandCallback()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function($a, $b) use(&$passedA, &$passedB){
			$passedA = $a;
			$passedB = $b;
		});

		Manager::callCommand('foo', array('aaa', 'bbb'));

		$this->assertEquals('aaa', $passedA);
		$this->assertEquals('bbb', $passedB);
	}

	public function testCallCommand_ShouldBeReturnRegisteredCommandResult()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function(){ return 'bar'; });
		$this->assertEquals('bar', Manager::callCommand('foo'));
	}

	public function testCreateGlobalAliasOnce_ShouldBeCreateGlobalFunction()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('globalTestFunc1', function(){});

		$this->assertFalse(function_exists('globalTestFunc1'));
		Manager::createGlobalAliasOnce('globalTestFunc1');

		$this->assertTrue(function_exists('globalTestFunc1'));
	}

	public function testCreateGlobalAliasOnce_CallGlobalFunction_ShouldBePassArgumentsToCommand()
	{
		Manager::unregisterAllCommands();
		$args = null;
		Manager::registerCommand('globalTestFunc2', function($arg1, $arg2) use(&$args) {
			$args = func_get_args();
		});

		Manager::createGlobalAliasOnce('globalTestFunc2');
		\globalTestFunc2('foo', 'bar');

		$this->assertEquals(array('foo', 'bar'), $args);
	}

	public function testCreateGlobalAliasOnce_CallGlobalFunction_ShouldBeProvideCommandReturn()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('globalTestFunc3', function(){
			return 'foo';
		});

		Manager::createGlobalAliasOnce('globalTestFunc3');

		$this->assertEquals('foo', \globalTestFunc3());
	}

	public function testCreateGlobalAliasOnce_ShouldBeReturnTrueIfAliasCreated()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('globalTestFunc4', function(){});

		$this->assertTrue(Manager::createGlobalAliasOnce('globalTestFunc4'));
	}

	public function testCreateGlobalAliasOnce_ShouldBeReturnFalseIfAliasAlreadyCreatedThroughCreateGlobalAliasOnceMethod()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('globalTestFunc5', function(){});
		Manager::createGlobalAliasOnce('globalTestFunc5');

		$this->assertFalse(Manager::createGlobalAliasOnce('globalTestFunc5'));
	}

	public function testCreateGlobalAliasOnce_ShouldNotBeThrowErrorIfAliasAlreadyCreatedThroughCreateGlobalAliasOnceMethod()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('globalTestFunc6', function(){});

		Manager::createGlobalAliasOnce('globalTestFunc6');
		Manager::createGlobalAliasOnce('globalTestFunc6');

		$this->assertTrue(function_exists('globalTestFunc6'));
	}

	public function testCreateGlobalAliasOnce_ShouldBeThrowExceptionIfFunctionAlreadyExists()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('trim', function(){});
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'Function "trim" already exists', function(){
			Manager::createGlobalAliasOnce('trim');
		});
	}

	public function testCreateGlobalAliasOnce_ShouldBeThrowExceptionIfCommandNotRegistered()
	{
		Manager::unregisterAllCommands();
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'Command "foo" not exists', function(){
			Manager::createGlobalAliasOnce('foo');
		});
	}

	public function testCreateGlobalAliasOnce_ShouldBeThrowExceptionIfNotAllowCreateGlobalAlias()
	{
		Manager::unregisterAllCommands();
		Config::setAllowCreateGlobalAlias(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'Create global alias for construction commands deny', function(){
			Manager::createGlobalAliasOnce('foo');
		});
	}

/**/

	public function testRegisterCommand_ShouldBeCollectCommands()
	{
		Manager::unregisterAllCommands();

		$this->assertSame(array(), Manager::getRegisteredCommands());

		$function1 = function(){};
		$function2 = function(){};
		$function3 = 'testFunc';

		Manager::registerCommand('foo', $function1);
		$this->assertSame(array(
			'foo' => $function1,
		), Manager::getRegisteredCommands());

		Manager::registerCommand('bar', $function2);
		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
		), Manager::getRegisteredCommands());

		Manager::registerCommand('baz', $function3);
		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		), Manager::getRegisteredCommands());
	}

	public function testRegisterCommand_ShouldBeReplaceExistsCommand()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', 'fooFunc');
		Manager::registerCommand('foo', 'barFunc');

		$this->assertSame(
			array('foo' => 'barFunc')
			, Manager::getRegisteredCommands()
		);
	}

	public function testRegisterCommand_ShouldBeAcceptClosureFunction()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function(){ return 'bar'; });

		$this->assertEquals('bar', Manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptCreatedAnonymousFunction()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', create_function('', 'return "bar";'));

		$this->assertEquals('bar', Manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptUserDefinedFunction()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', __CLASS__ . '::myCommand');

		$this->assertEquals('bar', Manager::foo());
	}

	public function testRegisterCommand_ShouldBeAcceptCallbackArray()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', array(__CLASS__, 'myCommand'));

		$this->assertEquals('bar', Manager::foo());
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfCommandNameIsNotValidFunctionName()
	{
		Manager::unregisterAllCommands();
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'Bad name', function(){
			Manager::registerCommand('-foo', function(){});
		});
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfNotAllowConstructionCommandsRegistration()
	{
		Manager::unregisterAllCommands();
		Config::setAllowConstructionCommandsRegistration(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'Construction commands registration deny', function(){
			Manager::registerCommand('foo', function(){});
		});
	}

	public function testRegisterCommand_ShouldBeThrowExceptionIfCommandExistsAndNotAllowConstructionCommandsOverride()
	{
		Manager::unregisterAllCommands();
		Config::setAllowConstructionCommandsOverride(false);
		Manager::registerCommand('foo', function(){});
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			Manager::registerCommand('foo', function(){});
		});
	}

	public function testRegisterCommands_ShouldBeAcceptArrayWithCommandNameAndCallback()
	{
		Manager::unregisterAllCommands();

		$function1 = function(){};
		$function2 = function(){};
		$function3 = 'testFunc';

		Manager::registerCommands(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		));

		$this->assertSame(array(
			'foo' => $function1,
			'bar' => $function2,
			'baz' => $function3,
		), Manager::getRegisteredCommands());
	}

/**/

	public function testUnregisterCommand_ShouldBeRemoveCommandByName()
	{
		Manager::unregisterAllCommands();

		Manager::registerCommand('foo', function(){});
		Manager::unregisterCommand('foo');

		$this->assertFalse(Manager::hasRegisteredCommand('foo'));
		$this->assertSame(array(), Manager::getRegisteredCommands());
	}

	public function testUnregisterCommand_ShouldBeThrowExceptionIfNotAllowConstructionCommandsOverride()
	{
		Manager::unregisterAllCommands();
		Config::setAllowConstructionCommandsOverride(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			Manager::unregisterCommand('foo');
		});
	}

/**/

	public function testUnregisterAllCommands_ShouldBeLeaveEmptyArray()
	{
		Manager::registerCommand('foo', function(){});
		Manager::unregisterAllCommands();
		$this->assertSame(array(), Manager::getRegisteredCommands());
	}

	public function testUnregisterAllCommands_ShouldBeThrowExceptionIfNotAllowConstructionCommandsOverride()
	{
		Manager::unregisterAllCommands();
		Config::setAllowConstructionCommandsOverride(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'Construction commands override deny', function(){
			Manager::unregisterAllCommands('foo');
		});
	}

/**/

	public function testHasRegisteredCommand_ShouldBeReturnTrueIfCommandExists()
	{
		Manager::unregisterAllCommands();
		Manager::registerCommand('foo', function(){});
		$this->assertTrue(Manager::hasRegisteredCommand('foo'));
	}

	public function testHasRegisteredCommand_ShouldBeReturnFalseIfCommandNotExists()
	{
		Manager::unregisterAllCommands();
		$this->assertFalse(Manager::hasRegisteredCommand('foo'));
	}

	public function testGetRegisteredCommandCallback_ShouldBeReturnCallbackByCommandName()
	{
		Manager::unregisterAllCommands();
		$function = function(){};
		Manager::registerCommand('foo', $function);

		$this->assertSame($function, Manager::getRegisteredCommandCallback('foo'));
	}

	public function testGetRegisteredCommandCallback_ShouldBeThrowExceptionIfCommandNotExists()
	{
		Manager::unregisterAllCommands();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', function(){
			Manager::getRegisteredCommandCallback('foo');
		});
	}

/**/

	public function myCommand()
	{
		return 'bar';
	}
}