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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Matchers extends Stack\Named
{
	public function add($name, $callback)
	{
		if (in_array($name, array('not', 'isNot', 'getActualValue', 'be')))
			throw new \net\mkharitonov\spectrum\core\Exception('Name "' . $name . '" was reserved, you can\'t add matcher with same name');

		return parent::add($name, $callback);
	}

	public function callMatcher($name, array $args = array())
	{
		$callback = $this->getCascadeThroughRunningContexts($name);

		if (!is_callable($callback))
			throw new \net\mkharitonov\spectrum\core\Exception('Callback for matcher "' . $name . '" is not callable');

		return call_user_func_array($callback, $args);
	}
}