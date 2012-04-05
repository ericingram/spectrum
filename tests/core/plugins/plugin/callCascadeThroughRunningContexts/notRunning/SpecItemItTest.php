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

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\notRunning;
require_once dirname(__FILE__) . '/../../../../../init.php';

class SpecItemItTest extends Test
{
	protected $currentSpecClass = '\spectrum\core\SpecItemIt';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecItemItMock';
	
	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::notRunningSpecItemIt($callback, $spec);
	}
}