<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands;

class Config
{
	private static $managerClass = '\spectrum\constructionCommands\Manager';
	private static $allowConstructionCommandsRegistration = true;
	private static $allowConstructionCommandsOverride = true;

	private static $locked = false;

	public static function setManagerClass($className){ return static::setConfigClassValue(static::$managerClass, $className, '\spectrum\constructionCommands\ManagerInterface'); }
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