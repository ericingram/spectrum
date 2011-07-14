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

namespace net\mkharitonov\spectrum\matchers;
require_once dirname(__FILE__) . '/../init.php';
require_once 'beThrow.php';

class ExceptionFoo extends \Exception {}
class ExceptionFooFoo extends ExceptionFoo {}
class ExceptionBar extends \Exception {}

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class BeThrowTest extends \net\mkharitonov\spectrum\matchers\Test
{
	public function testExpectedClass_ShouldBeAcceptRootClassException()
	{
		beThrow(function(){}, '\net\mkharitonov\spectrum\core\Exception');
	}

	public function testExpectedClass_ShouldBeAcceptSubclassOfRootClassException()
	{
		beThrow(function(){}, '\Exception');
	}

	public function testExpectedClass_ShouldBeThrowExceptionIfClassIsNotSubclassOfException()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be subclass', function(){
			beThrow(function(){}, '\net\mkharitonov\spectrum\core\assert\Assert');
		});
	}

	public function testExpectedClass_ShouldBeReturnTrueIfCallbackThrownExceptionOfSameClass()
	{
		$this->assertTrue(beThrow(function(){ throw new \Exception(); }, '\Exception'));
	}

	public function testExpectedClass_ShouldBeReturnTrueIfCallbackThrownExceptionOfSubclassOfSameClass()
	{
		$this->assertTrue(beThrow(function(){ throw new \net\mkharitonov\spectrum\core\Exception(); }, '\Exception'));
	}

	public function testExpectedClass_ShouldBeReturnFalseIfCallbackNotThrownException()
	{
		$this->assertFalse(beThrow(function(){}, '\Exception'));
	}

	public function testExpectedClass_ShouldBeReturnFalseIfCallbackThrownExceptionOfSuperClassOfExpectedClass()
	{
		$this->assertFalse(beThrow(function(){ throw new \Exception(); }, '\net\mkharitonov\spectrum\matchers\ExceptionFoo'));
	}

	public function testExpectedClass_ShouldBeReturnFalseIfCallbackThrownExceptionOfAncestorSuperClassOfExpectedClass()
	{
		$this->assertFalse(beThrow(function(){ throw new \Exception(); }, '\net\mkharitonov\spectrum\matchers\ExceptionFooFoo'));
	}

	public function testExpectedClass_ShouldBeReturnFalseIfCallbackThrownExceptionOfSiblingClass()
	{
		$this->assertFalse(beThrow(function(){ throw new ExceptionBar(); }, '\net\mkharitonov\spectrum\matchers\ExceptionFoo'));
	}

	public function testExpectedClass_ShouldBeUseRootExceptionIfExpectedClassIsNull()
	{
		$this->assertTrue(beThrow(function(){ throw new \Exception(); }, null));
	}

/**/

	public function testExpectedStringInMessage_ShouldBeReturnTrueIfExceptionMessageContainsString()
	{
		$this->assertTrue(beThrow(function(){ throw new \Exception('Foo not bar'); }, null, 'bar'));
	}

	public function testExpectedStringInMessage_ShouldBeReturnTrueIfExceptionMessageContainsStringAtBegin()
	{
		$this->assertTrue(beThrow(function(){ throw new \Exception('bar not foo'); }, null, 'bar'));
	}

	public function testExpectedStringInMessage_ShouldBeReturnFalseIfExceptionMessageNotContainsString()
	{
		$this->assertFalse(beThrow(function(){ throw new \Exception('Foo not foo'); }, null, 'bar'));
	}

	public function testExpectedStringInMessage_ShouldBeIgnoreCase()
	{
		$this->assertTrue(beThrow(function(){ throw new \Exception('Foo not BAR'); }, null, 'bar'));
	}

	public function testExpectedStringInMessage_ShouldNotBeVerifyIfExpectedStringIsNull()
	{
		$this->assertTrue(beThrow(function(){ throw new \Exception('Foo not BAR'); }, null, null));
	}

/**/

	public function testExpectedCode_ShouldBeReturnTrueIfExceptionCodeEqualExpectedCode()
	{
		$this->assertTrue(beThrow(function(){ throw new \Exception('', 123); }, null, null, 123));
	}

	public function testExpectedCode_ShouldBeReturnFalseIfExceptionCodeNotEqualExpectedCode()
	{
		$this->assertFalse(beThrow(function(){ throw new \Exception('', 12345); }, null, null, 123));
	}

	public function testExpectedCode_ShouldNotBeVerifyIfExpectedCodeIsNull()
	{
		$this->assertTrue(beThrow(function(){ throw new \Exception('', 123); }, null, null, null));
	}
}