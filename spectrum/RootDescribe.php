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
require_once dirname(__FILE__) . '/matchers/beNull.php';
require_once dirname(__FILE__) . '/matchers/beTrue.php';
require_once dirname(__FILE__) . '/matchers/beFalse.php';
require_once dirname(__FILE__) . '/matchers/beEq.php';
require_once dirname(__FILE__) . '/matchers/beIdent.php';
require_once dirname(__FILE__) . '/matchers/beLt.php';
require_once dirname(__FILE__) . '/matchers/beLtOrEq.php';
require_once dirname(__FILE__) . '/matchers/beGt.php';
require_once dirname(__FILE__) . '/matchers/beGtOrEq.php';
require_once dirname(__FILE__) . '/matchers/beThrow.php';

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

	static public function getInstance()
	{
		if (!static::$onceInstance)
		{
			$describeClass = \net\mkharitonov\spectrum\core\Config::getSpecContainerDescribeClass();
			static::$onceInstance = new $describeClass();

			static::$onceInstance->errorHandling->setCatchExceptions(true);
			static::$onceInstance->errorHandling->setCatchPhpErrors(true);
			static::$onceInstance->errorHandling->setBreakOnFirstPhpError(false);
			static::$onceInstance->errorHandling->setBreakOnFirstMatcherFail(false);

			static::addAllMatchersTo(static::$onceInstance);
		}

		return static::$onceInstance;
	}

	static public function addAllMatchersTo(\net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		$spec->matchers->add('beNull', '\net\mkharitonov\spectrum\matchers\beNull');
		$spec->matchers->add('beTrue', '\net\mkharitonov\spectrum\matchers\beTrue');
		$spec->matchers->add('beFalse', '\net\mkharitonov\spectrum\matchers\beFalse');
		$spec->matchers->add('beEq', '\net\mkharitonov\spectrum\matchers\beEq');
		$spec->matchers->add('beIdent', '\net\mkharitonov\spectrum\matchers\beIdent');
		$spec->matchers->add('beLt', '\net\mkharitonov\spectrum\matchers\beLt');
		$spec->matchers->add('beLtOrEq', '\net\mkharitonov\spectrum\matchers\beLtOrEq');
		$spec->matchers->add('beGt', '\net\mkharitonov\spectrum\matchers\beGt');
		$spec->matchers->add('beGtOrEq', '\net\mkharitonov\spectrum\matchers\beGtOrEq');
		$spec->matchers->add('beThrow', '\net\mkharitonov\spectrum\matchers\beThrow');
	}

	static public function run()
	{
		return static::getInstance()->run();
	}
}