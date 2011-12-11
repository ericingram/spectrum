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

namespace net\mkharitonov\spectrum;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class RootDescribe
{
	/**
	 * @var \net\mkharitonov\spectrum\core\SpecContainerDescribe
	 */
	protected static $onceInstance;

	static public function getOnceInstance()
	{
		if (!static::$onceInstance)
		{
			static::$onceInstance = static::createSpecContainer();
			static::configureSpec(static::$onceInstance);
			static::addMatchersToSpec(static::$onceInstance);
		}

		return static::$onceInstance;
	}

	static protected function createSpecContainer()
	{
		$describeClass = \net\mkharitonov\spectrum\core\Config::getSpecContainerDescribeClass();
		return new $describeClass();
	}

	static protected function configureSpec(\net\mkharitonov\spectrum\core\SpecContainerInterface $spec)
	{
		$spec->errorHandling->setCatchExceptions(true);
		$spec->errorHandling->setCatchPhpErrors(true);
		$spec->errorHandling->setBreakOnFirstPhpError(false);
		$spec->errorHandling->setBreakOnFirstMatcherFail(false);
	}

	static protected function addMatchersToSpec(\net\mkharitonov\spectrum\core\SpecContainerInterface $spec)
	{
		$managerClass = \net\mkharitonov\spectrum\matchers\Config::getManagerClass();
		$managerClass::addAllMatchersToSpec($spec);
	}

	static public function run()
	{
		return static::getOnceInstance()->run();
	}
}