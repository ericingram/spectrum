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

namespace net\mkharitonov\spectrum\constructionCommands\baseCommands;
use net\mkharitonov\spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class AddPatternTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->assertPatternNotExistsInDescribe('foo');
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = Manager::describe('', function(){
			Manager::addPattern('foo', function(){});
		});

		$this->assertTrue($describe->patterns->isExists('foo'));
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', '"addPattern" should be call only at declaring state', function()
		{
			$it = new \net\mkharitonov\spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				Manager::addPattern('foo', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnAddedCallback()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function, &$return) {
			$return = Manager::addPattern('foo', $function);
		});

		$this->assertSame($function, $return);
	}

	public function testShouldNotBeCallCallbackDuringCall()
	{
		Manager::addPattern('foo', function() use(&$isCalled){
			$isCalled = true;
		});

		$this->assertNull($isCalled);
	}

	public function testNoParentCommand_ShouldBeAddPatternToRootDescribe()
	{
		$function = function(){};
		Manager::addPattern('foo', $function);

		$this->assertSame($function, \net\mkharitonov\spectrum\RootDescribe::getOnceInstance()->patterns->get('foo'));
	}

	public function testInsideDescribeCommand_ShouldBeAddPatternToParentDescribe()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function) {
			Manager::addPattern('foo', $function);
		});

		$this->assertSame($function, $describe->patterns->get('foo'));
	}

	public function testInsideContextCommand_ShouldBeAddPatternToParentContext()
	{
		$function = function(){};
		$context = Manager::context('', function() use($function) {
			Manager::addPattern('foo', $function);
		});

		$this->assertSame($function, $context->patterns->get('foo'));
	}

/**/

	public function assertPatternNotExistsInDescribe($name)
	{
		$describe = Manager::describe('', function(){});
		$this->assertFalse($describe->patterns->isExists($name));
	}
}