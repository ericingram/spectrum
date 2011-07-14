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
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ResultBufferTest extends Test
{
	/**
	 * @var \net\mkharitonov\spectrum\core\ResultBuffer
	 */
	private $resultBuffer;

	public function setUp()
	{
		parent::setUp();

		$this->resultBuffer = new ResultBuffer(new SpecItemIt());
	}

	public function testAddResult_ShouldBeCanAcceptResultOnly()
	{
		$this->resultBuffer->addResult(false);

		$this->assertSame(array(
			array('result' => false, 'details' => null),
		), $this->resultBuffer->getResults());
	}

	public function testAddResult_ShouldBeCanAcceptResultAndDetails()
	{
		$exception = new Exception();
		$this->resultBuffer->addResult(false, $exception);

		$this->assertSame(array(
			array('result' => false, 'details' => $exception),
		), $this->resultBuffer->getResults());
	}

	public function testAddResult_ShouldBeCastResultsLikeFalseToBooleanFalse()
	{
		$this->resultBuffer->addResult(false);
		$this->resultBuffer->addResult(null);
		$this->resultBuffer->addResult(0);
		$this->resultBuffer->addResult('');

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => false, 'details' => null),
			array('result' => false, 'details' => null),
			array('result' => false, 'details' => null),
		), $this->resultBuffer->getResults());
	}

	public function testAddResult_ShouldBeCastResultsLikeTrueToBooleanTrue()
	{
		$this->resultBuffer->addResult(true);
		$this->resultBuffer->addResult(1);
		$this->resultBuffer->addResult(-1);
		$this->resultBuffer->addResult('foo');

		$this->assertSame(array(
			array('result' => true, 'details' => null),
			array('result' => true, 'details' => null),
			array('result' => true, 'details' => null),
			array('result' => true, 'details' => null),
		), $this->resultBuffer->getResults());
	}

	public function testAddResult_ShouldBeCanAcceptMixedDetails()
	{
		$exception = new Exception();
		$this->resultBuffer->addResult(true, $exception);
		$this->resultBuffer->addResult(true, 'foo is not bar');
		$this->resultBuffer->addResult(true, array('foo', 'bar'));

		$this->assertSame(array(
			array('result' => true, 'details' => $exception),
			array('result' => true, 'details' => 'foo is not bar'),
			array('result' => true, 'details' => array('foo', 'bar')),
		), $this->resultBuffer->getResults());
	}

	public function testAddResult_ShouldBeCollectResults()
	{
		$exception1 = new Exception();
		$exception2 = new Exception();

		$this->resultBuffer->addResult(false, $exception1);
		$this->resultBuffer->addResult(false, $exception2);
		$this->resultBuffer->addResult(true);

		$this->assertSame(array(
			array('result' => false, 'details' => $exception1),
			array('result' => false, 'details' => $exception2),
			array('result' => true, 'details' => null),
		), $this->resultBuffer->getResults());
	}
	
/**/

	public function testGetResults_ShouldBeReturnEmptyArrayByDefault()
	{
		$this->assertSame(array(), $this->resultBuffer->getResults());
	}

	public function testGetResults_ShouldBeReturnAddedResults()
	{
		$this->resultBuffer->addResult(0);
		$this->resultBuffer->addResult('foo');

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => true, 'details' => null),
		), $this->resultBuffer->getResults());
	}

/**/

	/**
	 * @see ResultBufferTest::addResult()
	 */
	public function testCalculateFinalResult_ShouldBeReturnFalseIfAnyResultIsFalse()
	{
		$this->resultBuffer->addResult(true);
		$this->resultBuffer->addResult(false);
		$this->resultBuffer->addResult(true);

		$this->assertFalse($this->resultBuffer->calculateFinalResult());
	}

	public function testCalculateFinalResult_ShouldBeReturnFalseIfAnyResultIsNull()
	{
		$this->resultBuffer->addResult(true);
		$this->resultBuffer->addResult(null);
		$this->resultBuffer->addResult(true);

		$this->assertFalse($this->resultBuffer->calculateFinalResult());
	}

	public function testCalculateFinalResult_ShouldBeReturnTrueIfAllResultsIsTrue()
	{
		$this->resultBuffer->addResult(true);
		$this->resultBuffer->addResult(true);

		$this->assertTrue($this->resultBuffer->calculateFinalResult());
	}

	public function testCalculateFinalResult_ShouldBeReturnNullOnlyIfNoResults()
	{
		$this->assertSame(array(), $this->resultBuffer->getResults());
		
		$this->assertNull($this->resultBuffer->calculateFinalResult());
	}
}