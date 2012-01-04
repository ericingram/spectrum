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
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 *
 * Support params variants:
 * it($name)
 * it($name, $settings)
 * it($name, $testCallback)
 * it($name, $testCallback, $settings)
 * it($name, $argumentsProvider, $testCallback)
 * it($name, $argumentsProvider, $testCallback, $settings)
 *
 * @throws \net\mkharitonov\spectrum\constructionCommands\Exception If called not at declaring state or if data provider is bad
 * @param  string|null $name
 * @param  array|null $argumentsProvider
 * @param  callback|null $testCallback
 * @return \net\mkharitonov\spectrum\core\SpecItemIt
 */
function it($name, $argumentsProvider = null, $testCallback = null, $settings = array())
{
	$managerClass = \net\mkharitonov\spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Construction command "it" should be call only at declaring state');

	$arg1 = $name;
	$arg2 = $argumentsProvider;
	$arg3 = $testCallback;
	if (!is_callable($arg1) && !is_callable($arg2) && !is_callable($arg3)) // it($name [, $settings])
	{
		$argumentsProvider = null;
		$testCallback = null;
		if ($arg2 !== null)
			$settings = $arg2;
	}
	else if (!is_callable($arg1) && is_callable($arg2)) // it($name, $testCallback [, $settings])
	{
		$argumentsProvider = null;
		$testCallback = $arg2;
		if ($arg3 !== null)
			$settings = $arg3;
	}

	if ($argumentsProvider !== null)
	{
		if (!is_array($argumentsProvider))
			throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Construction command "it" should be accept array as $argumentsProvider (now passed "' . gettype($argumentsProvider) . '")');

		$argumentsProviderClass = \net\mkharitonov\spectrum\core\Config::getSpecContainerArgumentsProviderClass();
		$spec = new $argumentsProviderClass($name);
		$spec->createSpecItemForEachArgumentsRow($testCallback, $argumentsProvider);
	}
	else
	{
		$itClass = \net\mkharitonov\spectrum\core\Config::getSpecItemItClass();
		$spec = new $itClass($name);
		$spec->setTestCallback($testCallback);
	}

	$managerClass::setSpecSettings($spec, $settings);
	$managerClass::getCurrentContainer()->addSpec($spec);
	return $spec;
}