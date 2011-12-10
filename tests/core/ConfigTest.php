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

	public function testGetSpecContainerDataProviderClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\core\SpecContainerDataProvider', Config::getSpecContainerDataProviderClass());
	}

/**/

	public function testSetSpecContainerDataProviderClass_ShouldBeSetNewClass()
	{
		Config::setSpecContainerDataProviderClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerDataProvider');
		$this->assertEquals('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerDataProvider', Config::getSpecContainerDataProviderClass());
	}

	public function testSetSpecContainerDataProviderClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDataProviderClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecContainerDataProviderClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDataProviderClass());
	}

	public function testSetSpecContainerDataProviderClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDataProviderClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecContainerDataProviderClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDataProviderClass());
	}

	public function testSetSpecContainerDataProviderClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDataProviderClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecContainerDataProviderClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\SpecContainerDataProvider');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDataProviderClass());
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

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Config is locked', function(){
			Config::setWorldClass('\net\mkharitonov\spectrum\core\testEnv\emptyStubs\World');
		});

		$this->assertEquals($oldClass, Config::getWorldClass());
	}

/**/


//заменить вхождения классов в проекте на вызов методов конфига
}