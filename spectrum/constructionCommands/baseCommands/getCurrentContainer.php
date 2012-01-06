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
 * Available at declaring and running state.
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @return \net\mkharitonov\spectrum\core\SpecContainerInterface|null
 */
function getCurrentContainer()
{
	$declaringContainer = Manager::getDeclaringContainer();
	$registryClass = \net\mkharitonov\spectrum\core\Config::getRegistryClass();
	$managerClass = \net\mkharitonov\spectrum\constructionCommands\Config::getManagerClass();

	if ($declaringContainer)
		return $declaringContainer;
	else if ($registryClass::getRunningSpecContainer())
		return $registryClass::getRunningSpecContainer();
	else if ($managerClass::isDeclaringState())
		return \net\mkharitonov\spectrum\RootDescribe::getOnceInstance();
	else
		return null;
}