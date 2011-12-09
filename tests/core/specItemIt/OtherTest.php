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

namespace net\mkharitonov\spectrum\core\specItemIt;
use net\mkharitonov\spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class OtherTest extends Test
{
	public function testConstructor_ShouldBeCanAcceptNoArguments()
	{
		$it = new SpecItemIt();

		$this->assertNull($it->getName());
		$this->assertNull($it->getTestCallback());
		$this->assertSame(array(), $it->getAdditionalArguments());
	}

	public function testConstructor_ShouldBeCanAcceptNameOnly()
	{
		$it = new SpecItemIt('foo name');

		$this->assertEquals('foo name', $it->getName());
		$this->assertNull($it->getTestCallback());
		$this->assertSame(array(), $it->getAdditionalArguments());
	}

//	public function testConstructor_ShouldBeCanAcceptNameAndTestCallback()
//	{
//		$callback = function(){};
//		$it = new SpecItemIt('foo name', $callback);
//
//		$this->assertEquals('foo name', $it->getName());
//		$this->assertSame($callback, $it->getTestCallback());
//		$this->assertSame(array(), $it->getAdditionalArguments());
//	}
//
//	public function testConstructor_ShouldBeCanAcceptNameAndTestCallbackAndAdditionalArguments()
//	{
//		$callback = function(){};
//		$it = new SpecItemIt('foo name', $callback, array('foo', 'bar'));
//
//		$this->assertEquals('foo name', $it->getName());
//		$this->assertSame($callback, $it->getTestCallback());
//		$this->assertSame(array('foo', 'bar'), $it->getAdditionalArguments());
//	}

/**/

	public function testSetTestCallback_ShouldBeAcceptNull()
	{
		$it = new SpecItemIt();

		$it->setTestCallback(function(){ });
		$it->setTestCallback(null);

		$this->assertNull($it->getTestCallback());
	}

	public function testSetTestCallback_ShouldBeAcceptClosureFunction()
	{
		$it = new SpecItemIt();

		$it->setTestCallback(function() use($it) { $it->getRunResultsBuffer()->addResult(true); });

		$this->assertTrue($it->run());
	}

	public function testSetTestCallback_ShouldBeAcceptCreatedAnonymousFunction()
	{
		$it = new SpecItemIt();

		\net\mkharitonov\spectrum\Test::$tmp['testSpec'] = $it;
		$it->setTestCallback(create_function('', '\net\mkharitonov\spectrum\Test::$tmp[\'testSpec\']->getRunResultsBuffer()->addResult(true);'));

		$this->assertTrue($it->run());
	}

	public function testSetTestCallback_ShouldBeAcceptFunctionStringName()
	{
		$it = new SpecItemIt();

		\net\mkharitonov\spectrum\Test::$tmp['testSpec'] = $it;
		$it->setTestCallback(__CLASS__ . '::myTestCallback');

		$this->assertTrue($it->run());
	}

//	public function testSetTestCallback_ShouldBeAcceptCallbackArray()
//	{
//		$it = new SpecItemIt();
//
//		\net\mkharitonov\spectrum\Test::$tmp['testSpec'] = $it;
//		$it->setTestCallback(array(__CLASS__, 'myTestCallback'));
//
//		$this->assertTrue($it->run());
//	}

/**/

	public function testGetTestCallback_ShouldBeReturnNullByDefault()
	{
		$it = new SpecItemIt();
		$this->assertNull($it->getTestCallback());
	}

	public function testGetTestCallback_ShouldBeReturnSourceValue()
	{
		$it = new SpecItemIt();

		$callback1 = function(){};
		$it->setTestCallback($callback1);

		$this->assertSame($callback1, $it->getTestCallback());
	}

/**/

	public function testSetAdditionalArguments_ShouldBeAcceptArray()
	{
		$it = new SpecItemIt();
		$it->setAdditionalArguments(array('foo', 'bar'));
		$this->assertSame(array('foo', 'bar'), $it->getAdditionalArguments());
	}

/**/

	public function testGetAdditionalArguments_ShouldBeReturnEmptyArrayByDefault()
	{
		$it = new SpecItemIt();
		$this->assertSame(array(), $it->getAdditionalArguments());
	}

	public function testGetAdditionalArguments_ShouldBeReturnSourceValue()
	{
		$it = new SpecItemIt();
		$it->setAdditionalArguments(array('foo'));
		$this->assertSame(array('foo'), $it->getAdditionalArguments());
	}


/**/

	public function testGetUid_ShouldBeReturnUidInRunningContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Context
			->->Context
			->->It(it)
		');

		$specs['it']->setTestCallback(function() use(&$uids, $specs){
			$uids[] = $specs['it']->getUid();
		});

		$specs['it']->run();

		$this->assertSame(array(
			'spec_0_0_2_context_0',
			'spec_0_0_2_context_1',
		), $uids);
	}

/*** Test ware ***/

	static public function myTestCallback()
	{
		\net\mkharitonov\spectrum\Test::$tmp['testSpec']->getRunResultsBuffer()->addResult(true);
	}
}