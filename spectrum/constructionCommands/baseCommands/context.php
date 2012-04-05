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
 * Support params variants:
 * context($name)
 * context($name, $settings)
 *
 * context($callback)
 * context($callback, $settings)
 *
 * context($name, $callback)
 * context($name, $callback, $settings)
 *
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string|callback $nameOrCallback
 * @param  callback|null $callback
 * @return \spectrum\core\SpecContainerContext
 */
function context($name = null, $callback = null, $settings = array())
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \spectrum\constructionCommands\Exception('Construction command "context" should be call only at declaring state');

	return $managerClass::container(\spectrum\core\Config::getSpecContainerContextClass(), $name, $callback, $settings);
}