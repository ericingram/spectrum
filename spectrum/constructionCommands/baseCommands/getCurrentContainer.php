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

/**
 * Available at declaring and running state.
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @return \net\mkharitonov\spectrum\core\SpecContainerInterface|null
 */
function getCurrentContainer()
{
	$managerClass = \net\mkharitonov\spectrum\constructionCommands\Config::getManagerClass();
	if ($managerClass::isDeclaringState())
	{
		$reflection = new \ReflectionFunction('\net\mkharitonov\spectrum\constructionCommands\baseCommands\setCurrentContainer');
		$vars = $reflection->getStaticVariables();

		if ($vars['currentContainer'] !== null)
			return $vars['currentContainer'];
		else
			return \net\mkharitonov\spectrum\RootDescribe::getOnceInstance();
	}
	else
	{
		foreach (debug_backtrace() as $trace)
		{
			if (!is_object(@$trace['object']))
				continue;

			if ($trace['object'] instanceof \net\mkharitonov\spectrum\core\SpecContainerInterface)
				return $trace['object'];
			else if ($trace['object'] instanceof \net\mkharitonov\spectrum\core\SpecItemInterface)
				return $trace['object']->getParent();
		}
	}

	return null;
}