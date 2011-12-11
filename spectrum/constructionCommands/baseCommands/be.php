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
 * @throws \net\mkharitonov\spectrum\constructionCommands\Exception If called not at running state
 * @param  mixed $actualValue
 * @return \net\mkharitonov\spectrum\core\asserts\Assert
 */
function be($actualValue)
{
	$managerClass = \net\mkharitonov\spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Construction command "be" should be call only at running state');

	$class = \net\mkharitonov\spectrum\core\Config::getAssertClass();
	return new $class($actualValue);
}