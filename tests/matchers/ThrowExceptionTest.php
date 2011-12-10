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
require_once 'throwException.php';

class ExceptionFoo extends \Exception {}
class ExceptionFooFoo extends ExceptionFoo {}
class ExceptionBar extends \Exception {}

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ThrowExceptionTest extends \net\mkharitonov\spectrum\matchers\Test
{
	public function testExpectedClass_ShouldBeAcceptRootClassException()
	{
		throwException(function(){}, '\net\mkharitonov\spectrum\core\Exception');
	}

	public function testExpectedClass_ShouldBeAcceptSubclassOfRootClassException()
	{
		throwException(function(){}, '\Exception');
	}

	public function testExpectedClass_ShouldBeThrowExceptionIfClassIsNotSubclassOfException()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be subclass', function(){
			throwException(function(){}, '\net\mkharitonov\spectrum\core\asserts\Assert');
		});
	}

	public function testExpectedClass_ShouldBeReturnTrueIfCallbackThrownExceptionOfSameClass()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception(); }, '\Exception'));
	}

	public function testExpectedClass_ShouldBeReturnTrueIfCallbackThrownExceptionOfSubclassOfSameClass()
	{
		$this->assertTrue(throwException(function(){ throw new \net\mkharitonov\spectrum\core\Exception(); }, '\Exception'));
	}

	public function testExpectedClass_ShouldBeReturnFalseIfCallbackNotThrownException()
	{
		$this->assertFalse(throwException(function(){}, '\Exception'));
	}

	public function testExpectedClass_ShouldBeReturnFalseIfCallbackThrownExceptionOfSuperClassOfExpectedClass()
	{
		$this->assertFalse(throwException(function(){ throw new \Exception(); }, '\net\mkharitonov\spectrum\matchers\ExceptionFoo'));
	}

	public function testExpectedClass_ShouldBeReturnFalseIfCallbackThrownExceptionOfAncestorSuperClassOfExpectedClass()
	{
		$this->assertFalse(throwException(function(){ throw new \Exception(); }, '\net\mkharitonov\spectrum\matchers\ExceptionFooFoo'));
	}

	public function testExpectedClass_ShouldBeReturnFalseIfCallbackThrownExceptionOfSiblingClass()
	{
		$this->assertFalse(throwException(function(){ throw new ExceptionBar(); }, '\net\mkharitonov\spectrum\matchers\ExceptionFoo'));
	}

	public function testExpectedClass_ShouldBeUseRootExceptionIfExpectedClassIsNull()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception(); }, null));
	}

/**/

	public function testExpectedStringInMessage_ShouldBeReturnTrueIfExceptionMessageContainsString()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('Foo not bar'); }, null, 'bar'));
	}

	public function testExpectedStringInMessage_ShouldBeReturnTrueIfExceptionMessageContainsStringAtBegin()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('bar not foo'); }, null, 'bar'));
	}

	public function testExpectedStringInMessage_ShouldBeReturnFalseIfExceptionMessageNotContainsString()
	{
		$this->assertFalse(throwException(function(){ throw new \Exception('Foo not foo'); }, null, 'bar'));
	}

	public function testExpectedStringInMessage_ShouldBeIgnoreCase()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('Foo not BAR'); }, null, 'bar'));
	}

	public function testExpectedStringInMessage_ShouldNotBeVerifyIfExpectedStringIsNull()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('Foo not BAR'); }, null, null));
	}

/**/

	public function testExpectedCode_ShouldBeReturnTrueIfExceptionCodeEqualExpectedCode()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('', 123); }, null, null, 123));
	}

	public function testExpectedCode_ShouldBeReturnFalseIfExceptionCodeNotEqualExpectedCode()
	{
		$this->assertFalse(throwException(function(){ throw new \Exception('', 12345); }, null, null, 123));
	}

	public function testExpectedCode_ShouldNotBeVerifyIfExpectedCodeIsNull()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('', 123); }, null, null, null));
	}
}