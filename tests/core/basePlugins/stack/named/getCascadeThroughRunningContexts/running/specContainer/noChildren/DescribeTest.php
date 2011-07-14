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

namespace net\mkharitonov\spectrum\core\basePlugins\stack\named\getCascadeThroughRunningContexts\running\specContainer\noChildren;
use net\mkharitonov\spectrum\core\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class DescribeTest extends Test
{
	protected $currentSpecClass = '\net\mkharitonov\spectrum\core\SpecContainerDescribe';
	protected $currentSpecMockClass = '\net\mkharitonov\spectrum\core\testEnv\SpecContainerDescribeMock';

	protected function executeContext($callback, \net\mkharitonov\spectrum\core\SpecInterface $spec)
	{
		return \net\mkharitonov\spectrum\core\testEnv\ContextsExecutor::runningSpecContainerNoChildrenDescribe($callback, $spec);
	}
}