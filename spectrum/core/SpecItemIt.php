<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

class SpecItemIt extends SpecItem implements SpecItemItInterface
{
	protected $runningSpecItemBackup;
	/**
	 * @var \spectrum\core\RunResultsBuffer|null
	 */
	protected $runResultsBuffer;

	/**
	 * @var \Closure
	 */
	protected $testCallback;
	protected $testCallbackArguments = array();
	protected $isErrorHandlerSets = false;

	public function setTestCallback(\Closure $callback = null)
	{
		$this->handleSpecModifyDeny();
		$this->testCallback = $callback;
	}

	public function getTestCallback()
	{
		return $this->testCallback;
	}

/**/

	public function setTestCallbackArguments(array $args)
	{
		$this->handleSpecModifyDeny();
		$this->testCallbackArguments = $args;
	}

	public function getTestCallbackArguments()
	{
		return $this->testCallbackArguments;
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

		$this->startRun();
		$this->dispatchEvent('onRunBefore');
		$this->dispatchEvent('onRunItemBefore');

		$this->execute();
		$result = $this->getRunResultsBuffer()->calculateFinalResult();

		$this->dispatchEvent('onRunItemAfter', $result);
		$this->dispatchEvent('onRunAfter', $result);
		$this->stopRun();

		return $result;
	}

	protected function startRun()
	{
		parent::startRun();

		$runResultsBufferClass = Config::getRunResultsBufferClass();
		$this->runResultsBuffer = new $runResultsBufferClass($this);

		$registryClass = \spectrum\core\Config::getRegistryClass();
		$this->runningSpecItemBackup = $registryClass::getRunningSpecItem();
		$registryClass::setRunningSpecItem($this);
	}

	protected function stopRun()
	{
		$registryClass = \spectrum\core\Config::getRegistryClass();
		$registryClass::setRunningSpecItem($this->runningSpecItemBackup);

		$this->runResultsBuffer = null;

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
		$this->dispatchEvent('onTestCallbackCallBefore', $world);
		Tools::callClosureInWorld($this->getTestCallback(), $this->getTestCallbackArguments(), $world);
		$this->dispatchEvent('onTestCallbackCallAfter', $world);
	}
}