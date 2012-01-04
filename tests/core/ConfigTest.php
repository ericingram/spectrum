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
class ConfigTest extends Test
{
	public function testGetAssertClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\asserts\Assert', Config::getAssertClass());
	}

/**/

	public function testSetAssertClass_ShouldBeSetNewClass()
	{
		Config::setAssertClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\asserts\Assert');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\asserts\Assert', Config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setAssertClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setAssertClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getAssertClass());
	}

	public function testSetAssertClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setAssertClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\asserts\Assert');
		});

		$this->assertEquals($oldClass, Config::getAssertClass());
	}

/**/

	public function testGetAssertRunResultDetailsClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\asserts\RunResultDetails', Config::getAssertRunResultDetailsClass());
	}

/**/

	public function testSetAssertRunResultDetailsClass_ShouldBeSetNewClass()
	{
		Config::setAssertRunResultDetailsClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\asserts\RunResultDetails');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\asserts\RunResultDetails', Config::getAssertRunResultDetailsClass());
	}

	public function testSetAssertRunResultDetailsClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertRunResultDetailsClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setAssertRunResultDetailsClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getAssertRunResultDetailsClass());
	}

	public function testSetAssertRunResultDetailsClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertRunResultDetailsClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setAssertRunResultDetailsClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getAssertRunResultDetailsClass());
	}

	public function testSetAssertRunResultDetailsClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertRunResultDetailsClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setAssertRunResultDetailsClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\asserts\RunResultDetails');
		});

		$this->assertEquals($oldClass, Config::getAssertRunResultDetailsClass());
	}

/**/

	public function testGetPluginsManagerClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\plugins\Manager', Config::getPluginsManagerClass());
	}

/**/

	public function testSetPluginsManagerClass_ShouldBeSetNewClass()
	{
		Config::setPluginsManagerClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\plugins\Manager');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\plugins\Manager', Config::getPluginsManagerClass());
	}

	public function testSetPluginsManagerClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getPluginsManagerClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setPluginsManagerClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getPluginsManagerClass());
	}

	public function testSetPluginsManagerClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getPluginsManagerClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setPluginsManagerClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getPluginsManagerClass());
	}

	public function testSetPluginsManagerClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getPluginsManagerClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setPluginsManagerClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\plugins\Manager');
		});

		$this->assertEquals($oldClass, Config::getPluginsManagerClass());
	}
/**/

	public function testGetRegistryClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\Registry', Config::getRegistryClass());
	}

/**/

	public function testSetRegistryClass_ShouldBeSetNewClass()
	{
		Config::setRegistryClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\Registry');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\Registry', Config::getRegistryClass());
	}

	public function testSetRegistryClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRegistryClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setRegistryClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getRegistryClass());
	}

	public function testSetRegistryClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRegistryClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setRegistryClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getRegistryClass());
	}

	public function testSetRegistryClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRegistryClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setRegistryClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\Registry');
		});

		$this->assertEquals($oldClass, Config::getRegistryClass());
	}
	
/**/

	public function testGetRunResultsBufferClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\RunResultsBuffer', Config::getRunResultsBufferClass());
	}

/**/

	public function testSetRunResultsBufferClass_ShouldBeSetNewClass()
	{
		Config::setRunResultsBufferClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\RunResultsBuffer');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\RunResultsBuffer', Config::getRunResultsBufferClass());
	}

	public function testSetRunResultsBufferClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRunResultsBufferClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setRunResultsBufferClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getRunResultsBufferClass());
	}

	public function testSetRunResultsBufferClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRunResultsBufferClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setRunResultsBufferClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getRunResultsBufferClass());
	}

	public function testSetRunResultsBufferClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRunResultsBufferClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setRunResultsBufferClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\RunResultsBuffer');
		});

		$this->assertEquals($oldClass, Config::getRunResultsBufferClass());
	}

/**/

	public function testGetSpecContainerContextClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\SpecContainerContext', Config::getSpecContainerContextClass());
	}

/**/

	public function testSetSpecContainerContextClass_ShouldBeSetNewClass()
	{
		Config::setSpecContainerContextClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerContext');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerContext', Config::getSpecContainerContextClass());
	}

	public function testSetSpecContainerContextClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerContextClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecContainerContextClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerContextClass());
	}

	public function testSetSpecContainerContextClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerContextClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecContainerContextClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerContextClass());
	}

	public function testSetSpecContainerContextClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerContextClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecContainerContextClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerContext');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerContextClass());
	}

/**/

	public function testGetSpecContainerArgumentsProviderClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\SpecContainerArgumentsProvider', Config::getSpecContainerArgumentsProviderClass());
	}

/**/

	public function testSetSpecContainerArgumentsProviderClass_ShouldBeSetNewClass()
	{
		Config::setSpecContainerArgumentsProviderClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerArgumentsProvider');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerArgumentsProvider', Config::getSpecContainerArgumentsProviderClass());
	}

	public function testSetSpecContainerArgumentsProviderClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerArgumentsProviderClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecContainerArgumentsProviderClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerArgumentsProviderClass());
	}

	public function testSetSpecContainerArgumentsProviderClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerArgumentsProviderClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecContainerArgumentsProviderClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerArgumentsProviderClass());
	}

	public function testSetSpecContainerArgumentsProviderClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerArgumentsProviderClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecContainerArgumentsProviderClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerArgumentsProvider');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerArgumentsProviderClass());
	}

/**/

	public function testGetSpecContainerDescribeClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\SpecContainerDescribe', Config::getSpecContainerDescribeClass());
	}

/**/

	public function testSetSpecContainerDescribeClass_ShouldBeSetNewClass()
	{
		Config::setSpecContainerDescribeClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerDescribe');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerDescribe', Config::getSpecContainerDescribeClass());
	}

	public function testSetSpecContainerDescribeClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDescribeClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecContainerDescribeClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDescribeClass());
	}

	public function testSetSpecContainerDescribeClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDescribeClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecContainerDescribeClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDescribeClass());
	}

	public function testSetSpecContainerDescribeClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDescribeClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecContainerDescribeClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerDescribe');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDescribeClass());
	}

/**/

	public function testGetSpecItemItClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\SpecItemIt', Config::getSpecItemItClass());
	}

/**/

	public function testSetSpecItemItClass_ShouldBeSetNewClass()
	{
		Config::setSpecItemItClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecItemIt');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecItemIt', Config::getSpecItemItClass());
	}

	public function testSetSpecItemItClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecItemItClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecItemItClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecItemItClass());
	}

	public function testSetSpecItemItClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecItemItClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecItemItClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecItemItClass());
	}

	public function testSetSpecItemItClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecItemItClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecItemItClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecItemIt');
		});

		$this->assertEquals($oldClass, Config::getSpecItemItClass());
	}

/**/

	public function testGetWorldClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\World', Config::getWorldClass());
	}

/**/

	public function testSetWorldClass_ShouldBeSetNewClass()
	{
		Config::setWorldClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\World');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\World', Config::getWorldClass());
	}

	public function testSetWorldClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getWorldClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setWorldClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getWorldClass());
	}

	public function testSetWorldClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecItemItClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecItemItClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecItemItClass());
	}

	public function testSetWorldClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getWorldClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setWorldClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\World');
		});

		$this->assertEquals($oldClass, Config::getWorldClass());
	}

/**/

	public function testGetAllowPluginsRegistration_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowPluginsRegistration());
	}

/**/

	public function testSetAllowPluginsRegistration_ShouldBeSetNewValue()
	{
		Config::setAllowPluginsRegistration(false);
		$this->assertFalse(Config::getAllowPluginsRegistration());
	}

	public function testSetAllowPluginsRegistration_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowPluginsRegistration(false);
		});

		$this->assertEquals(true, Config::getAllowPluginsRegistration());
	}

/**/

	public function testGetAllowPluginsOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowPluginsOverride());
	}

/**/

	public function testSetAllowPluginsOverride_ShouldBeSetNewValue()
	{
		Config::setAllowPluginsOverride(false);
		$this->assertFalse(Config::getAllowPluginsOverride());
	}

	public function testSetAllowPluginsOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowPluginsOverride(false);
		});

		$this->assertEquals(true, Config::getAllowPluginsOverride());
	}

/**/

	public function testGetAllowMatchersAdd_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowMatchersAdd());
	}

/**/

	public function testSetAllowMatchersAdd_ShouldBeSetNewValue()
	{
		Config::setAllowMatchersAdd(false);
		$this->assertFalse(Config::getAllowMatchersAdd());
	}

	public function testSetAllowMatchersAdd_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowMatchersAdd(false);
		});

		$this->assertEquals(true, Config::getAllowMatchersAdd());
	}

/**/

	public function testGetAllowMatchersOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowMatchersOverride());
	}

/**/

	public function testSetAllowMatchersOverride_ShouldBeSetNewValue()
	{
		Config::setAllowMatchersOverride(false);
		$this->assertFalse(Config::getAllowMatchersOverride());
	}

	public function testSetAllowMatchersOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowMatchersOverride(false);
		});

		$this->assertEquals(true, Config::getAllowMatchersOverride());
	}

/**/

	public function testGetAllowErrorHandlingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowErrorHandlingModify());
	}

/**/

	public function testSetAllowErrorHandlingModify_ShouldBeSetNewValue()
	{
		Config::setAllowErrorHandlingModify(false);
		$this->assertFalse(Config::getAllowErrorHandlingModify());
	}

	public function testSetAllowErrorHandlingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowErrorHandlingModify(false);
		});

		$this->assertEquals(true, Config::getAllowErrorHandlingModify());
	}

/**/

	public function testGetAllowLiveReportModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowLiveReportModify());
	}

/**/

	public function testSetAllowLiveReportModify_ShouldBeSetNewValue()
	{
		Config::setAllowLiveReportModify(false);
		$this->assertFalse(Config::getAllowLiveReportModify());
	}

	public function testSetAllowLiveReportModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowLiveReportModify(false);
		});

		$this->assertEquals(true, Config::getAllowLiveReportModify());
	}

/**/

	public function testGetAllowInputEncodingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowInputEncodingModify());
	}

/**/

	public function testSetAllowInputEncodingModify_ShouldBeSetNewValue()
	{
		Config::setAllowInputEncodingModify(false);
		$this->assertFalse(Config::getAllowInputEncodingModify());
	}

	public function testSetAllowInputEncodingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowInputEncodingModify(false);
		});

		$this->assertEquals(true, Config::getAllowInputEncodingModify());
	}

/**/

	public function testGetAllowOutputEncodingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowOutputEncodingModify());
	}

/**/

	public function testSetAllowOutputEncodingModify_ShouldBeSetNewValue()
	{
		Config::setAllowOutputEncodingModify(false);
		$this->assertFalse(Config::getAllowOutputEncodingModify());
	}

	public function testSetAllowOutputEncodingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowOutputEncodingModify(false);
		});

		$this->assertEquals(true, Config::getAllowOutputEncodingModify());
	}
}