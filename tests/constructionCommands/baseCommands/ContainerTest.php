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
class ContainerTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeCanAcceptCallbackWithoutName()
	{
		$describe = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', function() use(&$isCalled) {
			$isCalled = true;
		});

		$this->assertNull($describe->getName());
		$this->assertTrue($isCalled);
	}

	public function testShouldBeCanAcceptNameAndCallback()
	{
		$describe = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) {
			$isCalled = true;
		});

		$this->assertEquals('foo', $describe->getName());
		$this->assertTrue($isCalled);
	}
	
	public function testShouldBeReturnNewSpecContainerInstance()
	{
		$describe1 = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		$describe2 = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){});

		$this->assertTrue($describe1 instanceof \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface);
		$this->assertTrue($describe2 instanceof \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface);
		$this->assertNotSame($describe1, $describe2);
	}

	public function testShouldBeReturnInstanceWithNoChild()
	{
		$describe = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		$this->assertSame(array(), $describe->getSpecs());
	}

	public function testShouldBeCallCallbackDuringCall()
	{
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function() use(&$isCalled) {
			$isCalled = true;
		});
		
		$this->assertTrue($isCalled);
	}

/**/

	public function testFirstLevelContainer_ShouldBeAddInstanceToRootDescribe()
	{
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){});
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'bar', function(){});
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'baz', function(){});

		$rootSpecs = \net\mkharitonov\spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(3, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
		$this->assertEquals('bar', $rootSpecs[1]->getName());
		$this->assertEquals('baz', $rootSpecs[2]->getName());
	}

	public function testFirstLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		$describe1 = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){});
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){});

		$this->assertSame(array(), $describe1->getSpecs());
	}

/**/

	public function testSecondLevelContainer_ShouldBeAddInstanceToParentContainer()
	{
		$describe = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function()
		{
			Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){});
			Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'bar', function(){});
			Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'baz', function(){});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(3, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
		$this->assertEquals('bar', $specs[1]->getName());
		$this->assertEquals('baz', $specs[2]->getName());
	}

	public function testSecondLevelContainer_ShouldNotBeAddInstanceToRootDescribe()
	{
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){
			Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'bar', function(){});
		});

		$rootSpecs = \net\mkharitonov\spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(1, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
	}

	public function testSecondLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
		{
			$describe1 = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){});
			Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){});
		});

		$this->assertSame(array(), $describe1->getSpecs());
	}

/**/

	public function testThirdLevelContainer_ShouldBeAddInstanceToParentContainer()
	{
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function() use(&$describe)
		{
			$describe = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function()
			{
				Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){});
				Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'bar', function(){});
				Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'baz', function(){});
			});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(3, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
		$this->assertEquals('bar', $specs[1]->getName());
		$this->assertEquals('baz', $specs[2]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToRootDescribe()
	{
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){
			Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){
				Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$rootSpecs = \net\mkharitonov\spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(1, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToAncestorContainer()
	{
		$describe = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){
			Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', 'foo', function(){
				Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(1, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
		{
			Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function() use(&$describe1)
			{
				$describe1 = Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){});
				Manager::container('\net\mkharitonov\spectrum\core\SpecContainerDescribe', '', function(){});
			});
		});

		$this->assertSame(array(), $describe1->getSpecs());
	}
}