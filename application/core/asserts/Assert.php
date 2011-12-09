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

namespace net\mkharitonov\spectrum\core\asserts;
use net\mkharitonov\spectrum\core\Exception;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @property not
 */
class Assert implements AssertInterface
{
	protected $actualValue;
	protected $isNotEnabled = false;

	public function __construct($actualValue)
	{
		$this->actualValue = $actualValue;
	}

	public function __call($name, array $expectedArgs = array())
	{
		$this->callMatcher($name, $expectedArgs);
		return $this;
	}

	protected function callMatcher($matcherName, array $expectedArgs)
	{
		$it = $this->getItemRunningInstance();
		$runResultDetails = $this->createRunResultDetails($matcherName, $expectedArgs);

		try
		{
			$result = $it->matchers->callMatcher($matcherName, array_merge(array($this->getActualValue()), $expectedArgs));
			$runResultDetails->setMatcherReturnValue($result);

			if ($this->isNot())
				$result = !$result;
		}
		catch (\Exception $e)
		{
			if ($it->errorHandling->getCatchExceptionsCascade())
			{
				$result = false;
				$runResultDetails->setMatcherException($e);
			}
			else
				throw $e;
		}

		$it->getResultBuffer()->addResult($result, $runResultDetails);
		$this->resetNot();

		if (!$result && $it->errorHandling->getBreakOnFirstMatcherFailCascade())
			throw new \net\mkharitonov\spectrum\core\ExceptionBreak();
	}

	/**
	 * @return \net\mkharitonov\spectrum\core\SpecItemIt
	 */
	protected function getItemRunningInstance()
	{
		$class = \net\mkharitonov\spectrum\core\Config::getSpecItemClass();
		return $class::getRunningInstance();
	}

	protected function createRunResultDetails($matcherName, array $expectedArgs)
	{
		$class = \net\mkharitonov\spectrum\core\Config::getAssertRunResultDetailsClass();
		$runResultDetails = new $class();
		$runResultDetails->setActualValue($this->getActualValue());
		$runResultDetails->setIsNot($this->isNot());
		$runResultDetails->setMatcherName($matcherName);
		$runResultDetails->setMatcherArgs($expectedArgs);
		return $runResultDetails;
	}

	public function __get($name)
	{
		if ($name == 'not')
			$this->invertNot();
		else
		{
			// TODO write tests
			// TODO подумать как реализовать обработку подобных ошибок: учитывать ли BreakOnFirstMatcherFail или нет
			$this->getItemRunningInstance()->getResultBuffer()->addResult(false, 'Undefined property "' . $name . '", only "not" property available in assert');
		}

		return $this;
	}

	public function getActualValue()
	{
		return $this->actualValue;
	}

	public function isNot()
	{
		return $this->isNotEnabled;
	}

	public function invertNot()
	{
		$this->isNotEnabled = !$this->isNotEnabled;
	}

	public function resetNot()
	{
		$this->isNotEnabled = false;
	}
}