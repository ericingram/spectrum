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
 */
/**
 * @throws \net\mkharitonov\spectrum\constructionCommands\Exception If called not at declaring state or if data provider is bad
 * @param  string|null $name
 * @param  array|null $argumentsProvider
 * @param  callback|null $testCallback
 * @return \net\mkharitonov\spectrum\core\SpecItemIt
 */
function it($name = null, $argumentsProvider = null, $testCallback = null)
{
	$managerClass = \net\mkharitonov\spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Construction command "it" should be call only at declaring state');

	// Constructor with two arguments
	if ($testCallback === null)
	{
		$testCallback = $argumentsProvider;
		$argumentsProvider = null;
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

	$managerClass::getCurrentContainer()->addSpec($spec);
	return $spec;
}