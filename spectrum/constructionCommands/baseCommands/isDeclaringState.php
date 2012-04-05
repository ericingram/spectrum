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

namespace spectrum\constructionCommands\baseCommands;

/**
 * Available at declaring and running state.
 * @return bool
 */
function isDeclaringState()
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	return !$managerClass::isRunningState();
}