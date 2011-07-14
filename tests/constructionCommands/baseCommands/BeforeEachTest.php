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
class BeforeEachTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->assertBuilderNotExistsInDescribe(0);
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = Manager::describe('', function(){
			Manager::beforeEach(function(){});
		});

		$this->assertTrue($describe->builders->isExists(0));
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', '"beforeEach"', function()
		{
			$it = new \net\mkharitonov\spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				Manager::beforeEach(function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnAddedValue()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function, &$return) {
			$return = Manager::beforeEach($function);
		});

		$this->assertSame($function, $return);
	}

	public function testShouldNotBeCallCallbackDuringCall()
	{
		Manager::beforeEach(function() use(&$isCalled){
			$isCalled = true;
		});

		$this->assertNull($isCalled);
	}

	public function testNoParentCommand_ShouldBeAddBuilderToRootDescribe()
	{
		$function = function(){};
		Manager::beforeEach($function);

		$builder = \net\mkharitonov\spectrum\RootDescribe::getInstance()->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

	public function testInsideDescribeCommand_ShouldBeAddBuilderToParentDescribe()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function) {
			Manager::beforeEach($function);
		});

		$builder = $describe->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

	public function testInsideContextCommand_ShouldBeAddBuilderToParentContext()
	{
		$function = function(){};
		$context = Manager::context('', function() use($function) {
			Manager::beforeEach($function);
		});

		$builder = $context->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

/**/

	public function assertBuilderNotExistsInDescribe($name)
	{
		$describe = Manager::describe('', function(){});
		$this->assertFalse($describe->builders->isExists($name));
	}
}