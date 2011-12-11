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
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @throws \net\mkharitonov\spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string|callback $nameOrCallback
 * @param  callback|null $callback
 * @return \net\mkharitonov\spectrum\core\SpecContainerDescribe
 */
function describe($nameOrCallback, $callback = null)
{
	$managerClass = \net\mkharitonov\spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Construction command "describe" should be call only at declaring state');

	return $managerClass::container(\net\mkharitonov\spectrum\core\Config::getSpecContainerDescribeClass(), $nameOrCallback, $callback);
}

