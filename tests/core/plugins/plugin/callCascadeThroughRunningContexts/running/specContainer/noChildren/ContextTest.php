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

namespace net\mkharitonov\spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\running\specContainer\noChildren;
require_once dirname(__FILE__) . '/../../../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ContextTest extends \net\mkharitonov\spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\running\Test
{
	protected $currentSpecClass = '\net\mkharitonov\spectrum\core\SpecContainerContext';
	protected $currentSpecMockClass = '\net\mkharitonov\spectrum\core\testEnv\SpecContainerContextMock';

	protected function executeContext($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		return \net\mkharitonov\spectrum\core\testEnv\ContextsExecutor::runningSpecContainerNoChildrenContext($callback, $spec);
	}
}