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

namespace net\mkharitonov\spectrum\constructionCommands;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ConfigTest extends \net\mkharitonov\spectrum\Test
{
	public function testGetManagerClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\constructionCommands\Manager', Config::getManagerClass());
	}

/**/

	public function testSetManagerClass_ShouldBeSetNewClass()
	{
		Config::setManagerClass('\net\mkharitonov\spectrum\constructionCommands\testEnv\emptyStubs\Manager');
		$this->assertEquals('\net\mkharitonov\spectrum\constructionCommands\testEnv\emptyStubs\Manager', Config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'not exists', function(){
			Config::setManagerClass('\net\mkharitonov\spectrum\constructionCommands\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'should be implement interface', function(){
			Config::setManagerClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}

	public function testSetManagerClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			Config::setManagerClass('\net\mkharitonov\spectrum\constructionCommands\testEnv\emptyStubs\Manager');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}
	
/**/

	public function testGetAllowConstructionCommandsRegistration_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowConstructionCommandsRegistration());
	}

/**/

	public function testSetAllowConstructionCommandsRegistration_ShouldBeSetNewValue()
	{
		Config::setAllowConstructionCommandsRegistration(false);
		$this->assertFalse(Config::getAllowConstructionCommandsRegistration());
	}

	public function testSetAllowConstructionCommandsRegistration_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			Config::setAllowConstructionCommandsRegistration(false);
		});

		$this->assertEquals(true, Config::getAllowConstructionCommandsRegistration());
	}
	
/**/

	public function testGetAllowConstructionCommandsOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowConstructionCommandsOverride());
	}

/**/

	public function testSetAllowConstructionCommandsOverride_ShouldBeSetNewValue()
	{
		Config::setAllowConstructionCommandsOverride(false);
		$this->assertFalse(Config::getAllowConstructionCommandsOverride());
	}

	public function testSetAllowConstructionCommandsOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			Config::setAllowConstructionCommandsOverride(false);
		});

		$this->assertEquals(true, Config::getAllowConstructionCommandsOverride());
	}
	
/**/

	public function testGetAllowCreateGlobalAlias_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowCreateGlobalAlias());
	}

/**/

	public function testSetAllowCreateGlobalAlias_ShouldBeSetNewValue()
	{
		Config::setAllowCreateGlobalAlias(false);
		$this->assertFalse(Config::getAllowCreateGlobalAlias());
	}

	public function testSetAllowCreateGlobalAlias_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			Config::setAllowCreateGlobalAlias(false);
		});

		$this->assertEquals(true, Config::getAllowCreateGlobalAlias());
	}
}