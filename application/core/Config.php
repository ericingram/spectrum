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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Config
{
	private static $assertClass = '\net\mkharitonov\spectrum\core\asserts\Assert';
	private static $assertRunResultDetailsClass = '\net\mkharitonov\spectrum\core\asserts\RunResultDetails';
	private static $pluginsManagerClass = '\net\mkharitonov\spectrum\core\plugins\Manager';
	private static $resultBufferClass = '\net\mkharitonov\spectrum\core\ResultBuffer';
	private static $specContainerContextClass = '\net\mkharitonov\spectrum\core\SpecContainerContext';
	private static $specContainerDataProviderClass = '\net\mkharitonov\spectrum\core\SpecContainerDataProvider';
	private static $specContainerDescribeClass = '\net\mkharitonov\spectrum\core\SpecContainerDescribe';
	private static $specItemClass = '\net\mkharitonov\spectrum\core\SpecItem';
	private static $specItemItClass = '\net\mkharitonov\spectrum\core\SpecItemIt';
	private static $worldClass = '\net\mkharitonov\spectrum\core\World';
	// TODO: $constructionCommandsManagerClass, $allowPluginsRegistration, $allowErrorHandlingModify, $allowLiveReportModify, $allowConstructionCommandsRegistration, $allowMatchersOverwrite, $allowMatchersAdding

	private static $locked = false;

	public static function setAssertClass($className){ return static::setConfigClassValue(static::$assertClass, $className, '\net\mkharitonov\spectrum\core\asserts\AssertInterface'); }
	public static function getAssertClass(){ return static::$assertClass; }

	public static function setAssertRunResultDetailsClass($className){ return static::setConfigClassValue(static::$assertRunResultDetailsClass, $className, '\net\mkharitonov\spectrum\core\asserts\RunResultDetailsInterface'); }
	public static function getAssertRunResultDetailsClass(){ return static::$assertRunResultDetailsClass; }

	public static function setPluginsManagerClass($className){ return static::setConfigClassValue(static::$pluginsManagerClass, $className, '\net\mkharitonov\spectrum\core\plugins\ManagerInterface'); }
	public static function getPluginsManagerClass(){ return static::$pluginsManagerClass; }

	public static function setResultBufferClass($className){ return static::setConfigClassValue(static::$resultBufferClass, $className, '\net\mkharitonov\spectrum\core\ResultBufferInterface'); }
	public static function getResultBufferClass(){ return static::$resultBufferClass; }

	public static function setSpecContainerContextClass($className){ return static::setConfigClassValue(static::$specContainerContextClass, $className, '\net\mkharitonov\spectrum\core\SpecContainerContextInterface'); }
	public static function getSpecContainerContextClass(){ return static::$specContainerContextClass; }

	public static function setSpecContainerDataProviderClass($className){ return static::setConfigClassValue(static::$specContainerDataProviderClass, $className, '\net\mkharitonov\spectrum\core\SpecContainerDataProviderInterface'); }
	public static function getSpecContainerDataProviderClass(){ return static::$specContainerDataProviderClass; }

	public static function setSpecContainerDescribeClass($className){ return static::setConfigClassValue(static::$specContainerDescribeClass, $className, '\net\mkharitonov\spectrum\core\SpecContainerDescribeInterface'); }
	public static function getSpecContainerDescribeClass(){ return static::$specContainerDescribeClass; }

	public static function setSpecItemClass($className){ return static::setConfigClassValue(static::$specItemClass, $className, '\net\mkharitonov\spectrum\core\SpecItemInterface'); }
	public static function getSpecItemClass(){ return static::$specItemClass; }

	public static function setSpecItemItClass($className){ return static::setConfigClassValue(static::$specItemItClass, $className, '\net\mkharitonov\spectrum\core\SpecItemItInterface'); }
	public static function getSpecItemItClass(){ return static::$specItemItClass; }

	public static function setWorldClass($className){ return static::setConfigClassValue(static::$worldClass, $className, '\net\mkharitonov\spectrum\core\WorldInterface'); }
	public static function getWorldClass(){ return static::$worldClass; }

	public static function lock(){ static::$locked = true; }
	public static function isLocked(){ return static::$locked; }

	private static function setConfigClassValue(&$var, $className, $requiredInterface = null)
	{
		if (!class_exists($className))
			throw new Exception('Class "' . $className . '" not exists');
		else if ($requiredInterface != null)
		{
			$reflection = new \ReflectionClass($className);
			if (!$reflection->implementsInterface($requiredInterface))
				throw new Exception('Class "' . $className . '" should be implement interface "' . $requiredInterface . '"');
		}

		return static::setConfigValue($var, $className);
	}

	private static function setConfigValue(&$var, $value)
	{
		if (static::$locked)
			throw new Exception('spectrum\Config is locked');

		$var = $value;
	}
}