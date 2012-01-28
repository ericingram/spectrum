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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Config
{
	private static $managerClass = '\net\mkharitonov\spectrum\constructionCommands\Manager';
	private static $allowConstructionCommandsRegistration = true;
	private static $allowConstructionCommandsOverride = true;

	private static $locked = false;

	public static function setManagerClass($className){ return static::setConfigClassValue(static::$managerClass, $className, '\net\mkharitonov\spectrum\constructionCommands\ManagerInterface'); }
	public static function getManagerClass(){ return static::$managerClass; }

	public static function setAllowConstructionCommandsRegistration($isEnable){ return static::setConfigValue(static::$allowConstructionCommandsRegistration, $isEnable); }
	public static function getAllowConstructionCommandsRegistration(){ return static::$allowConstructionCommandsRegistration; }

	public static function setAllowConstructionCommandsOverride($isEnable){ return static::setConfigValue(static::$allowConstructionCommandsOverride, $isEnable); }
	public static function getAllowConstructionCommandsOverride(){ return static::$allowConstructionCommandsOverride; }

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
			throw new Exception('spectrum\constructionCommands\Config is locked');

		$var = $value;
	}
}