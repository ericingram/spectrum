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
use \net\mkharitonov\spectrum\core\SpecContainerDataProvider;
use \net\mkharitonov\spectrum\core\SpecItemIt;

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
			$spec = new SpecItemIt($name);
			$spec->setTestCallback($testCallback);
		}

		Manager::getCurrentContainer()->addSpec($spec);
		return $spec;
	}

	static protected function createDataProviderSpec($name, $dataProvider, $testCallback)
	{
		if (!is_array($dataProvider))
			$dataProvider = static::callDataProvider($dataProvider);

		$dataProvider = static::convertArrayItemsToArray($dataProvider);

		$spec = new SpecContainerDataProvider($name);

		foreach ($dataProvider as $args)
		{
			$it = new SpecItemIt();
			$it->setTestCallback($testCallback);
			$it->setAdditionalArguments($args);

			$spec->addSpec($it);
		}
		
		return $spec;
	}

	static protected function callDataProvider($callback)
	{
		if (!is_callable($callback))
			throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Data provider is not callable');

		$return = call_user_func($callback);

		if (!is_array($return))
			throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Data provider function should be return array (now data provider return ' . gettype($return) . ')');

		return $return;
	}

	static protected function convertArrayItemsToArray(array $array)
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