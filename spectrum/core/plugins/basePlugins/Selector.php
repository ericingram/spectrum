<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
use \spectrum\core\plugins\Exception;

class Selector extends \spectrum\core\plugins\Plugin
{
	public function getRoot()
	{
		$root = $this->getOwnerSpec();
		while ($root->getParent())
		{
			$root = $root->getParent();
		}

		return $root;
	}
	
	public function getNearestNotContextAncestor()
	{
		$parent = $this->getOwnerSpec();
		while ($parent = $parent->getParent())
		{
			if ($parent instanceof \spectrum\core\SpecContainerInterface && !($parent instanceof \spectrum\core\SpecContainerContextInterface))
				return $parent;
		}

		return null;
	}

	/**
	 * @return array
	 */
	public function getNotContextSpecsUpToNearestNotContextAncestor()
	{
		$specs = array();

		$parent = $this->getOwnerSpec();
		while ($parent)
		{
			$specs = array_merge($parent->selector->getNotContextChildren(), $specs);

			// Not context come
			if (!($parent instanceof \spectrum\core\SpecContainerContextInterface))
				return $specs;

			$parent = $parent->getParent();
		}

		return $specs;
	}

	public function getEnabledChildren()
	{
		$enabledSpecs = array();

		foreach ($this->getSpecs() as $spec)
		{
			if ($spec->isEnabled())
				$enabledSpecs[] = $spec;
		}

		return $enabledSpecs;
	}

	public function hasChildContexts()
	{
		foreach ($this->getSpecs() as $spec)
		{
			if ($spec instanceof \spectrum\core\SpecContainerContextInterface)
				return true;
		}

		return false;
	}

	public function getNotContextChildren()
	{
		$specs = array();
		foreach ($this->getSpecs() as $spec)
		{
			if (!($spec instanceof \spectrum\core\SpecContainerContextInterface))
				$specs[] = $spec;
		}

		return $specs;
	}

	/**
	 * @return SpecContainerContext[]
	 */
	public function getChildContexts()
	{
		$result = array();
		foreach ($this->getSpecs() as $spec)
		{
			if ($spec instanceof \spectrum\core\SpecContainerContextInterface)
				$result[] = $spec;
		}

		return $result;
	}

	public function getChildRunningContext()
	{
		if (!($this->getOwnerSpec() instanceof \spectrum\core\SpecContainerInterface))
			return null;

		foreach ($this->getSpecs() as $spec)
		{
			if ($spec instanceof \spectrum\core\SpecContainerContextInterface && $spec->isRunning())
			{
				return $spec;
			}
		}

		return null;
	}
	
	public function getChildRunningContextsStack()
	{
		$stack = array();

		$childRunningContext = $this->getChildRunningContext();
		while ($childRunningContext)
		{
			$stack[] = $childRunningContext;
			$childRunningContext = $childRunningContext->selector->getChildRunningContext();
		}

		return $stack;
	}

	public function getDeepChildRunningContext()
	{
		$childRunningContext = $this->getChildRunningContext();

		if ($childRunningContext && $childRunningContext->selector->hasChildContexts())
			return $childRunningContext->selector->getDeepChildRunningContext();
		else
			return $childRunningContext;
	}

	public function getAncestorsStack()
	{
		$stack = array();

		$parent = $this->getOwnerSpec();
		while ($parent = $parent->getParent())
		{
			$stack[] = $parent;
		}

		return array_reverse($stack);
	}


	public function getAncestorsWithRunningContextsStack()
	{
		$stack = array();
		foreach ($this->getAncestorsStack() as $spec)
		{
			$stack[] = $spec;
			$stack = array_merge($stack, $spec->selector->getChildRunningContextsStack());
		}

		return $stack;
	}

/*	public function getAncestorRunningContextsStack()
	{
		$stack = array();
		foreach ($this->getAncestorsStack() as $spec)
		{
			$stack = array_merge($stack, $spec->selector->getChildRunningContextsStack());
		}

		return $stack;
	}*/

	/**
	 * Return all find specs with same name.
	 * @return array
	 */
	public function getChildrenWithName($name)
	{
		$findSpecs = array();

		foreach ($this->getSpecs() as $index => $spec)
		{
			if ($spec->getName() == $name)
			{
				$findSpecs[$index] = $spec;
			}
		}

		return $findSpecs;
	}

	/**
	 * Return first find spec with same name.
	 */
	public function getChildByName($name)
	{
		foreach ($this->getSpecs() as $spec)
		{
			if ($spec->getName() == $name)
				return $spec;
		}

		return null;
	}

	public function getChildByIndex($index)
	{
		$specs = $this->getSpecs();
		if (array_key_exists($index, $specs))
			return $specs[$index];
		else
			return null;
	}

	/**
	 * @return \spectrum\core\SpecInterface[]
	 */
	private function getSpecs()
	{
		if ($this->getOwnerSpec() instanceof \spectrum\core\SpecContainerInterface)
			return $this->getOwnerSpec()->getSpecs();
		else
			return array();
	}
}