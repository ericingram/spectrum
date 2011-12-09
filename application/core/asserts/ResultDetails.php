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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ResultDetails implements ResultDetailsInterface
{
	protected $actualValue;
	protected $isNot;
	protected $matcherName;
	protected $matcherArgs = array();
	protected $matcherReturnValue;
	protected $matcherException;

	public function __construct()
	{
	}

	public function setActualValue($actualValue)
	{
		$this->actualValue = $actualValue;
	}

	public function getActualValue()
	{
		return $this->actualValue;
	}

/**/

	public function setIsNot($isNot)
	{
		$this->isNot = $isNot;
	}

	public function getIsNot()
	{
		return $this->isNot;
	}

/**/

	public function setMatcherName($matcherName)
	{
		$this->matcherName = $matcherName;
	}

	public function getMatcherName()
	{
		return $this->matcherName;
	}

/**/

	public function setMatcherArgs(array $matcherArgs)
	{
		$this->matcherArgs = $matcherArgs;
	}

	public function getMatcherArgs()
	{
		return $this->matcherArgs;
	}

/**/

	public function setMatcherReturnValue($matcherReturnValue)
	{
		$this->matcherReturnValue = $matcherReturnValue;
	}

	public function getMatcherReturnValue()
	{
		return $this->matcherReturnValue;
	}
	
/**/

	public function setMatcherException(\Exception $matcherException = null)
	{
		$this->matcherException = $matcherException;
	}

	public function getMatcherException()
	{
		return $this->matcherException;
	}
}