<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events\onRun;
use spectrum\core\SpecItemIt;
use spectrum\core\RunResultsBuffer;
use spectrum\core\World;

require_once __DIR__ . '/../../../../init.php';

class SpecContainerDescribeTest extends SpecContainerTest
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerDescribe';
}