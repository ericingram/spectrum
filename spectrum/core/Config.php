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
	private static $registryClass = '\net\mkharitonov\spectrum\core\Registry';
	private static $runResultsBufferClass = '\net\mkharitonov\spectrum\core\RunResultsBuffer';
	private static $specContainerContextClass = '\net\mkharitonov\spectrum\core\SpecContainerContext';
	private static $specContainerArgumentsProviderClass = '\net\mkharitonov\spectrum\core\SpecContainerArgumentsProvider';
	private static $specContainerDescribeClass = '\net\mkharitonov\spectrum\core\SpecContainerDescribe';
	private static $specItemItClass = '\net\mkharitonov\spectrum\core\SpecItemIt';
	private static $worldClass = '\net\mkharitonov\spectrum\core\World';
	private static $allowPluginsRegistration = true;
	private static $allowPluginsOverride = true;
	private static $allowMatchersAdd = true;
	private static $allowMatchersOverride = true;
	private static $allowErrorHandlingModify = true;
	private static $allowLiveReportModify = true;
	private static $allowInputEncodingModify = true;
	private static $allowOutputEncodingModify = true;
	private static $allowSpecsModifyWhenRunning = false;

	private static $locked = false;

	public static function setAssertClass($className){ return static::setConfigClassValue(static::$assertClass, $className, '\net\mkharitonov\spectrum\core\asserts\AssertInterface'); }
	public static function getAssertClass(){ return static::$assertClass; }

	public static function setAssertRunResultDetailsClass($className){ return static::setConfigClassValue(static::$assertRunResultDetailsClass, $className, '\net\mkharitonov\spectrum\core\asserts\RunResultDetailsInterface'); }
	public static function getAssertRunResultDetailsClass(){ return static::$assertRunResultDetailsClass; }

	public static function setPluginsManagerClass($className){ return static::setConfigClassValue(static::$pluginsManagerClass, $className, '\net\mkharitonov\spectrum\core\plugins\ManagerInterface'); }
	public static function getPluginsManagerClass(){ return static::$pluginsManagerClass; }

	public static function setRegistryClass($className){ return static::setConfigClassValue(static::$registryClass, $className, '\net\mkharitonov\spectrum\core\RegistryInterface'); }
	public static function getRegistryClass(){ return static::$registryClass; }

	public static function setRunResultsBufferClass($className){ return static::setConfigClassValue(static::$runResultsBufferClass, $className, '\net\mkharitonov\spectrum\core\RunResultsBufferInterface'); }
	public static function getRunResultsBufferClass(){ return static::$runResultsBufferClass; }

	public static function setSpecContainerContextClass($className){ return static::setConfigClassValue(static::$specContainerContextClass, $className, '\net\mkharitonov\spectrum\core\SpecContainerContextInterface'); }
	public static function getSpecContainerContextClass(){ return static::$specContainerContextClass; }

	public static function setSpecContainerArgumentsProviderClass($className){ return static::setConfigClassValue(static::$specContainerArgumentsProviderClass, $className, '\net\mkharitonov\spectrum\core\SpecContainerArgumentsProviderInterface'); }
	public static function getSpecContainerArgumentsProviderClass(){ return static::$specContainerArgumentsProviderClass; }

	public static function setSpecContainerDescribeClass($className){ return static::setConfigClassValue(static::$specContainerDescribeClass, $className, '\net\mkharitonov\spectrum\core\SpecContainerDescribeInterface'); }
	public static function getSpecContainerDescribeClass(){ return static::$specContainerDescribeClass; }

	public static function setSpecItemItClass($className){ return static::setConfigClassValue(static::$specItemItClass, $className, '\net\mkharitonov\spectrum\core\SpecItemItInterface'); }
	public static function getSpecItemItClass(){ return static::$specItemItClass; }

	public static function setWorldClass($className){ return static::setConfigClassValue(static::$worldClass, $className, '\net\mkharitonov\spectrum\core\WorldInterface'); }
	public static function getWorldClass(){ return static::$worldClass; }

	public static function setAllowPluginsRegistration($isEnable){ return static::setConfigValue(static::$allowPluginsRegistration, $isEnable); }
	public static function getAllowPluginsRegistration(){ return static::$allowPluginsRegistration; }

	public static function setAllowPluginsOverride($isEnable){ return static::setConfigValue(static::$allowPluginsOverride, $isEnable); }
	public static function getAllowPluginsOverride(){ return static::$allowPluginsOverride; }

	public static function setAllowMatchersAdd($isEnable){ return static::setConfigValue(static::$allowMatchersAdd, $isEnable); }
	public static function getAllowMatchersAdd(){ return static::$allowMatchersAdd; }

	public static function setAllowMatchersOverride($isEnable){ return static::setConfigValue(static::$allowMatchersOverride, $isEnable); }
	public static function getAllowMatchersOverride(){ return static::$allowMatchersOverride; }

	public static function setAllowErrorHandlingModify($isEnable){ return static::setConfigValue(static::$allowErrorHandlingModify, $isEnable); }
	public static function getAllowErrorHandlingModify(){ return static::$allowErrorHandlingModify; }

	public static function setAllowLiveReportModify($isEnable){ return static::setConfigValue(static::$allowLiveReportModify, $isEnable); }
	public static function getAllowLiveReportModify(){ return static::$allowLiveReportModify; }

	public static function setAllowInputEncodingModify($isEnable){ return static::setConfigValue(static::$allowInputEncodingModify, $isEnable); }
	public static function getAllowInputEncodingModify(){ return static::$allowInputEncodingModify; }

	public static function setAllowOutputEncodingModify($isEnable){ return static::setConfigValue(static::$allowOutputEncodingModify, $isEnable); }
	public static function getAllowOutputEncodingModify(){ return static::$allowOutputEncodingModify; }

	/**
	 * Use this method for protection against possible tests structure damage from tested code.
	 */
	public static function setAllowSpecsModifyWhenRunning($isEnable){ return static::setConfigValue(static::$allowSpecsModifyWhenRunning, $isEnable); }
	public static function getAllowSpecsModifyWhenRunning(){ return self::$allowSpecsModifyWhenRunning; }

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
			throw new Exception('spectrum\core\Config is locked');

		$var = $value;
	}
}