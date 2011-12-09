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
abstract class SpecItem extends Spec implements SpecItemInterface
{
	/**
	 * Forbid redefinition in subclasses, because $runningInstance should be only one copy, available
	 * through SpecItem::getRunningInstance()
	 * @var \net\mkharitonov\spectrum\core\SpecItemInterface
	 */
	static private $runningInstance;
	
	protected $runningInstanceBackup;
	
	/**
	 * @var \RunResultsBuffer\mkharitonov\spectrum\core\RunResultsBuffer|null
	 */
	protected $runResultsBuffer;

	protected $testCallback;
	protected $additionalArguments = array();
	protected $isErrorHandlerSets = false;

/**/

	final static public function getRunningInstance()
	{
		return self::$runningInstance;
	}

	final static protected function setRunningInstance(SpecItemInterface $instance = null)
	{
		self::$runningInstance = $instance;
	}

/**/

//	public function __construct($name = null, $testCallback = null, array $additionalArguments = array())
//	{
//		parent::__construct($name);
//		$this->setTestCallback($testCallback);
//		$this->setAdditionalArguments($additionalArguments);
//	}

	public function setTestCallback($callback)
	{
		$this->testCallback = $callback;
	}

	public function getTestCallback()
	{
		return $this->testCallback;
	}

/**/

	public function setAdditionalArguments(array $args)
	{
		$this->additionalArguments = $args;
	}

	public function getAdditionalArguments()
	{
		return $this->additionalArguments;
	}

/**/

	public function isAnonymous()
	{
		return false;
	}

	public function getRunResultsBuffer()
	{
		return $this->runResultsBuffer;
	}

	public function run()
	{
		if ($this->getParent() && !$this->getParent()->isRunning())
			return $this->runSelfThroughAncestors();

		$runResultsBufferClass = Config::getRunResultsBufferClass();
		$this->runResultsBuffer = new $runResultsBufferClass($this);
		$this->startRun();
		$this->triggerEvent('onRunBefore');
		$this->triggerEvent('onRunItemBefore');

		$this->execute();
		$result = $this->getRunResultsBuffer()->calculateFinalResult();

		$this->triggerEvent('onRunItemAfter', $result);
		$this->triggerEvent('onRunAfter', $result);
		$this->stopRun();
		$this->runResultsBuffer = null;

		return $result;
	}

	protected function startRun()
	{
		parent::startRun();
		$this->runningInstanceBackup = self::getRunningInstance();
		self::setRunningInstance($this);
	}

	protected function stopRun()
	{
		self::setRunningInstance($this->runningInstanceBackup);
		parent::stopRun();
	}

	protected function execute()
	{
		if ($this->testCallback === null)
			return;

		$this->setErrorHandlerIfNeed();

		try
		{
			$worldClass = Config::getWorldClass();
			$world = new $worldClass();
			$this->builders->applyToWorld($world);
			$this->callTestCallback($world);
			$this->destroyers->applyToWorld($world);
		}
		catch (ExceptionBreak $e)
		{
			// Just ignore special break exceptions
		}
		catch (\Exception $e)
		{
			if ($this->errorHandling->getCatchExceptionsCascade())
				$this->getRunResultsBuffer()->addResult(false, $e);
			else
			{
				$this->restoreErrorHandlerIsSets();
				throw $e;
			}
		}

		$this->restoreErrorHandlerIsSets();
	}

	protected function setErrorHandlerIfNeed()
	{
		$catchPhpErrors = $this->errorHandling->getCatchPhpErrorsCascade();

		if (!$catchPhpErrors)
			return;

		$this->isErrorHandlerSets = true;

		$it = $this;
		set_error_handler(function($severity, $message, $file, $line) use($it)
		{
			if (error_reporting() == 0)
				return;

			$it->getRunResultsBuffer()->addResult(false, new ExceptionPhpError($message, 0, $severity, $file, $line));

			if ($it->errorHandling->getBreakOnFirstPhpErrorCascade())
				throw new ExceptionBreak();

		}, $catchPhpErrors);
	}

	protected function restoreErrorHandlerIsSets()
	{
		if ($this->isErrorHandlerSets)
			restore_error_handler();

		$this->isErrorHandlerSets = false;
	}

	protected function callTestCallback($world)
	{
		$this->triggerEvent('onTestCallbackCallBefore', $world);

		if (!is_callable($this->testCallback))
			throw new Exception('Test callback is not callable');

		call_user_func_array($this->testCallback, array_merge(array($world), $this->getAdditionalArguments()));

		$this->triggerEvent('onTestCallbackCallAfter', $world);
	}
}