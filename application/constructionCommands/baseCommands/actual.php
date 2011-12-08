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
use \net\mkharitonov\spectrum\constructionCommands\Manager;
/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @throws \net\mkharitonov\spectrum\constructionCommands\Exception If called not at running state
 * @param  mixed $value
 * @return \net\mkharitonov\spectrum\core\assert\Assert
 */
function actual($value)
{
	if (!Manager::isRunningState())
		throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Construction command "actual" should be call only at running state');

	$class = \net\mkharitonov\spectrum\core\Config::getAssertClass();
	return new $class($value);
}