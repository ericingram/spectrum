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

namespace net\mkharitonov\spectrum\core\plugins;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Plugin implements PluginInterface
{
	/** @var \net\mkharitonov\spectrum\core\Spec */
	protected $owner;
	protected $accessName;

	public function __construct(\net\mkharitonov\spectrum\core\SpecInterface $owner, $accessName)
	{
		$this->owner = $owner;
		$this->accessName = $accessName;
	}

	public function getOwner()
	{
		return $this->owner;
	}

	public function getAccessName()
	{
		return $this->accessName;
	}

	protected function callCascadeThroughRunningContexts($methodName, $args = array(), $defaultReturnValue = null, $emptyReturnValue = null)
	{
		$stack = $this->getOwner()->selector->getAncestorsWithRunningContextsStack();
		$stack[] = $this->getOwner();
		$stack = array_reverse($stack);

		$accessName = $this->accessName;
		foreach ($stack as $spec)
		{
			$return = call_user_func_array(array($spec->$accessName, $methodName), $args);
			if ($return !== $emptyReturnValue)
				return $return;
		}

		return $defaultReturnValue;
	}
}