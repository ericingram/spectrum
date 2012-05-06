<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\worldCreators;

class Builders extends WorldCreators
{
	public function applyToWorld($world)
	{
		foreach ($this->getAllPrependAncestorsWithRunningContexts() as $creator)
			\spectrum\core\Tools::callClosureInWorld($creator['callback'], array(), $world);

		return $world;
	}
}