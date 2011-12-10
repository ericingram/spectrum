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
use net\mkharitonov\spectrum\constructionCommands\Manager;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @throws \net\mkharitonov\spectrum\constructionCommands\Exception If called not at declaring state or if data provider is bad
 * @param  string|null $name
 * @param  array|callback|null $dataProviderOrTestCallback
 * @param  callback|null $testCallback
 * @return \net\mkharitonov\spectrum\core\SpecItemIt
 */
class It
{
	static public function it($name = null, $dataProvider = null, $testCallback = null)
	{
		if (!Manager::isDeclaringState())
			throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Construction command "it" should be call only at declaring state');

		if ($testCallback === null) // Constructor with two arguments
		{
			$testCallback = $dataProvider;
			$dataProvider = null;
		}


		if ($dataProvider !== null)
		{
			$spec = static::createDataProviderSpec($name, $dataProvider, $testCallback);
		}
		else
		{
			$itClass = \net\mkharitonov\spectrum\core\Config::getSpecItemItClass();
			$spec = new $itClass($name);
			$spec->setTestCallback($testCallback);
		}

		Manager::getCurrentContainer()->addSpec($spec);
		return $spec;
	}

	static protected function createDataProviderSpec($name, $dataProvider, $testCallback)
	{
		$dataProvider = static::convertArrayChildrenToArray($dataProvider);

		$dataProviderClass = \net\mkharitonov\spectrum\core\Config::getSpecContainerDataProviderClass();
		$spec = new $dataProviderClass($name);

		foreach ($dataProvider as $args)
		{
			$itClass = \net\mkharitonov\spectrum\core\Config::getSpecItemItClass();
			$it = new $itClass();
			$it->setTestCallback($testCallback);
			$it->setAdditionalArguments($args);

			$spec->addSpec($it);
		}
		
		return $spec;
	}

	static protected function convertArrayChildrenToArray(array $array)
	{
		foreach ($array as $key => $val)
		{
			if (!is_array($val))
			{
				$array[$key] = array($val);
			}
		}

		return $array;
	}
}