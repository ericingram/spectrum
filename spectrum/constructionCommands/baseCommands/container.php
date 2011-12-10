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

/**
 * Function with base functional for construction commands describe() and context()
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @param  string $specClass
 * @param  string|callback $nameOrCallback
 * @param  callback|null $callback
 * @return \net\mkharitonov\spectrum\core\SpecContainer
 */
function container($specClass, $nameOrCallback, $callback = null)
{
	// Anonymous container
	if ($callback === null)
	{
		$name = null;
		$callback = $nameOrCallback;
	}
	else
		$name = $nameOrCallback;

	$spec = new $specClass($name);

	$currentContainer = Manager::getCurrentContainer();
	$currentContainer->addSpec($spec);

	Manager::setCurrentContainer($spec);
	call_user_func($callback);
	Manager::setCurrentContainer($currentContainer);

	return $spec;
}