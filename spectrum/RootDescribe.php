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
require_once dirname(__FILE__) . '/matchers/null.php';
require_once dirname(__FILE__) . '/matchers/true.php';
require_once dirname(__FILE__) . '/matchers/false.php';
require_once dirname(__FILE__) . '/matchers/eq.php';
require_once dirname(__FILE__) . '/matchers/ident.php';
require_once dirname(__FILE__) . '/matchers/lt.php';
require_once dirname(__FILE__) . '/matchers/ltOrEq.php';
require_once dirname(__FILE__) . '/matchers/gt.php';
require_once dirname(__FILE__) . '/matchers/gtOrEq.php';
require_once dirname(__FILE__) . '/matchers/throwException.php';

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
		$spec->matchers->add('null', '\net\mkharitonov\spectrum\matchers\null');
		$spec->matchers->add('true', '\net\mkharitonov\spectrum\matchers\true');
		$spec->matchers->add('false', '\net\mkharitonov\spectrum\matchers\false');
		$spec->matchers->add('eq', '\net\mkharitonov\spectrum\matchers\eq');
		$spec->matchers->add('ident', '\net\mkharitonov\spectrum\matchers\ident');
		$spec->matchers->add('lt', '\net\mkharitonov\spectrum\matchers\lt');
		$spec->matchers->add('ltOrEq', '\net\mkharitonov\spectrum\matchers\ltOrEq');
		$spec->matchers->add('gt', '\net\mkharitonov\spectrum\matchers\gt');
		$spec->matchers->add('gtOrEq', '\net\mkharitonov\spectrum\matchers\gtOrEq');
		$spec->matchers->add('throwException', '\net\mkharitonov\spectrum\matchers\throwException');
	}

	static public function run()
	{
		return static::getOnceInstance()->run();
	}
}