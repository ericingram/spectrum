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
use \net\mkharitonov\spectrum\core\SpecContainerDataProvider;
use \net\mkharitonov\spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ItTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	public function testNoArguments_ShouldBeReturnEmptySpecItemItInstance()
	{
		$this->assertSpecIsItemIt(null, array(), null, Manager::it());
	}

	public function testOneArgument_ShouldBeReturnSpecItemItInstanceWithName()
	{
		$this->assertSpecIsItemIt('foo name', array(), null, Manager::it('foo name'));
	}

	public function testTwoArgument_ShouldBeReturnSpecItemItInstanceWithNameAndTestCallback()
	{
		$testCallback = function(){};
		$this->assertSpecIsItemIt('foo name', array(), $testCallback, Manager::it('foo name', $testCallback));
	}

	public function testThreeArgument_ThirdArgumentIsNull_ShouldBeReturnSpecItemItInstanceWithNameAndTestCallback()
	{
		$testCallback = function(){};
		$this->assertSpecIsItemIt('foo name', array(), $testCallback, Manager::it('foo name', $testCallback));
	}

	public function testThreeArgument_SecondArgumentIsNull_ShouldBeReturnSpecItemItInstanceWithNameAndTestCallback()
	{
		$testCallback = function(){};
		$this->assertSpecIsItemIt('foo name', array(), $testCallback, Manager::it('foo name', $testCallback));
	}

/**/

	public function testThreeArgument_SecondArgumentIsEmptyArray_ShouldBeReturnEmptyDataProviderContainer()
	{
		$testCallback = function(){};
		$spec = Manager::it('some spec', array(), $testCallback);

		$this->assertTrue($spec instanceof SpecContainerDataProvider);
		$this->assertSame('some spec', $spec->getName());
		$this->assertSame(array(), $spec->getSpecs());
	}

	public function testThreeArgument_SecondArgumentIsOneItemArray_ShouldBeReturnDataProviderContainer()
	{
		$testCallback = function(){};
		$spec = Manager::it('some spec', array('foo'), $testCallback);

		$this->assertSpecIsContainerDataProvider('some spec', 1, $spec);

		$children = $spec->getSpecs();
		$this->assertSpecIsItemIt(null, array('foo'), $testCallback, $children[0]);
	}

	public function testThreeArgument_SecondArgumentIsManyItemsArray_OneArgumentRows_ShouldBeReturnDataProviderContainer()
	{
		$testCallback = function(){};
		$spec = Manager::it('some spec', array(
			'foo',
			'bar',
			'baz',
		), $testCallback);

		$this->assertSpecIsContainerDataProvider('some spec', 3, $spec);

		$children = $spec->getSpecs();
		$this->assertSpecIsItemIt(null, array('foo'), $testCallback, $children[0]);
		$this->assertSpecIsItemIt(null, array('bar'), $testCallback, $children[1]);
		$this->assertSpecIsItemIt(null, array('baz'), $testCallback, $children[2]);
	}

	public function testThreeArgument_SecondArgumentIsManyItemsArray_ManyArgumentsRows_ShouldBeReturnDataProviderContainer()
	{
		$testCallback = function(){};
		$spec = Manager::it('some spec', array(
			array('foo1', 'foo2'),
			array('bar1', 'bar2'),
			array('baz1', 'baz2', 'baz3'),
		), $testCallback);

		$this->assertSpecIsContainerDataProvider('some spec', 3, $spec);

		$children = $spec->getSpecs();
		$this->assertSpecIsItemIt(null, array('foo1', 'foo2'), $testCallback, $children[0]);
		$this->assertSpecIsItemIt(null, array('bar1', 'bar2'), $testCallback, $children[1]);
		$this->assertSpecIsItemIt(null, array('baz1', 'baz2', 'baz3'), $testCallback, $children[2]);
	}

	public function testThreeArgument_SecondArgumentIsManyItemsArray_MixedArgumentsRows_ShouldBeReturnDataProviderContainer()
	{
		$testCallback = function(){};
		$spec = Manager::it('some spec', array(
			array('foo'),
			'bar',
			array('baz1', 'baz2'),
		), $testCallback);

		$this->assertSpecIsContainerDataProvider('some spec', 3, $spec);

		$children = $spec->getSpecs();
		$this->assertSpecIsItemIt(null, array('foo'), $testCallback, $children[0]);
		$this->assertSpecIsItemIt(null, array('bar'), $testCallback, $children[1]);
		$this->assertSpecIsItemIt(null, array('baz1', 'baz2'), $testCallback, $children[2]);
	}

/**/

	public function testShouldNotBeCallTestCallbackDuringDeclaringState()
	{
		Manager::it('foo', function() use(&$isCalled){ $isCalled = true; });
		$this->assertNull($isCalled);
	}

/**/

	public function testNoParentCommand_ShouldBeAddInstanceToRootDescribe()
	{
		$it = Manager::it('foo');
		$this->assertSame(array($it), \net\mkharitonov\spectrum\RootDescribe::getOnceInstance()->getSpecs());
	}

	public function testInsideDescribeCommand_ShouldBeAddInstanceToParentDescribe()
	{
		$describe = Manager::describe('', function() use(&$it) {
			$it = Manager::it('foo');
		});

		$this->assertSame(array($it), $describe->getSpecs());
	}

	public function testInsideContextCommand_ShouldBeAddInstanceToParentContext()
	{
		$context = Manager::context('', function() use(&$it) {
			$it = Manager::it('foo');
		});

		$this->assertSame(array($it), $context->getSpecs());
	}
	
/*** Test ware ***/


	protected function assertSpecIsItemIt($name, array $arguments, $testCallback, $spec)
	{
		$this->assertTrue($spec instanceof SpecItemIt);
		$this->assertSame($name, $spec->getName());
		$this->assertSame($arguments, $spec->getAdditionalArguments());
		$this->assertSame($testCallback, $spec->getTestCallback());
	}

	protected function assertSpecIsContainerDataProvider($name, $childrenCount, $spec)
	{
		$this->assertTrue($spec instanceof SpecContainerDataProvider);
		$this->assertSame($name, $spec->getName());
		$this->assertSame($childrenCount, count($spec->getSpecs()));
	}

	static public function myDataProvider()
	{
		return array(
			array('foo'),
			'bar',
			array('baz1', 'baz2'),
		);
	}
}