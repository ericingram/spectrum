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

namespace net\mkharitonov\spectrum\constructionCommands\baseCommands;

/**
 * Function with base functional for construction commands describe() and context()
 *
 * Support params variants:
 * container($specClass, $name)
 * container($specClass, $name, $settings)
 *
 * container($specClass, $callback)
 * container($specClass, $callback, $settings)
 *
 * container($specClass, $name, $callback)
 * container($specClass, $name, $callback, $settings)
 *
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @param  string $specClass
 * @param  string|callback $nameOrCallback
 * @param  callback|null $callback
 * @return \net\mkharitonov\spectrum\core\SpecContainer
 */
function container($specClass, $name = null, $callback = null, $settings = array())
{
	$managerClass = \net\mkharitonov\spectrum\constructionCommands\Config::getManagerClass();

	$arg1 = $name;
	$arg2 = $callback;
	$arg3 = $settings;
	if (!is_callable($arg1) && !is_callable($arg2)) // container($specClass, $name [, $settings])
	{
		$name = $arg1;
		$callback = null;
		if ($arg2 !== null)
			$settings = $arg2;
	}
	else if (is_callable($arg1)) // container($specClass, $callback [, $settings])
	{
		$name = null;
		$callback = $arg1;
		if ($arg2 !== null)
			$settings = $arg2;
	}
	else if (is_callable($arg2)) // container($specClass, $name, $callback [, $settings])
	{
		$name = $arg1;
		$callback = $arg2;
		if ($arg3 !== null)
			$settings = $arg3;
	}

	$spec = new $specClass();
	$spec->setName($name);
	$managerClass::setSpecSettings($spec, $settings);

	$currentContainer = $managerClass::getCurrentContainer();
	$currentContainer->addSpec($spec);

	if ($callback)
	{
		$managerClass::setCurrentContainer($spec);
		call_user_func($callback);
		$managerClass::setCurrentContainer($currentContainer);
	}

	return $spec;
}