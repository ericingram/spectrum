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

namespace net\mkharitonov\spectrum\core;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class SpecContainer extends Spec implements SpecContainerInterface
{
	/** @var Spec[] */
	protected $specs = array();

	/** @var SpecContainerContext|null */
	protected $runningContext;

	/** @var SpecContainerContext|null */
	protected $parentOldRunningContext;

	public function __clone()
	{
		foreach ($this->specs as $key => $spec)
		{
			$clone = clone $spec;
			$clone->setParent($this);

			$this->specs[$key] = $clone;
		}
	}

	public function isAnonymous()
	{
		return ($this->getName() == '');
	}

	public function addSpec(SpecInterface $spec)
	{
		$this->specs[] = $spec;
		$spec->setParent($this);
	}

	public function getSpecs()
	{
		return $this->specs;
	}

	public function removeSpec(SpecInterface $spec)
	{
		foreach ($this->specs as $key => $val)
		{
			if ($val === $spec)
				unset($this->specs[$key]);
		}
	}

	public function removeAllSpecs()
	{
		$this->specs = array();
	}

	public function run()
	{
		if ($this->getParent() && !$this->getParent()->isRunning())
			return $this->runSelfThroughAncestors();

		$this->startRun();
		$this->triggerEvent('onRunBefore');
		$this->triggerEvent('onRunContainerBefore');

		$results = array();
		foreach ($this->getSpecsToRun() as $spec)
		{
			if ($spec->isEnabled())
				$results[] = $spec->run();
		}

		$result = $this->calculateFinalResult($results);

		$this->triggerEvent('onRunContainerAfter', $result);
		$this->triggerEvent('onRunAfter', $result);
		$this->stopRun();

		return $result;
	}
}