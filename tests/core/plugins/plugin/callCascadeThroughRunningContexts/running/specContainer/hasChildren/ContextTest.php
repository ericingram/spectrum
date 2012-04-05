<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\running\specContainer\hasChildren;
require_once dirname(__FILE__) . '/../../../../../../../init.php';

class ContextTest extends \spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\running\Test
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerContext';
	protected $currentSpecMockClass = '\spectrum\core\testEnv\SpecContainerContextMock';

	protected function executeContext($callback, \spectrum\core\SpecInterface $spec)
	{
		return \spectrum\core\testEnv\ContextsExecutor::runningSpecContainerHasChildrenContext($callback, $spec);
	}
}