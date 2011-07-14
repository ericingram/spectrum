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

namespace net\mkharitonov\spectrum\core\basePlugins\worldCreators;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Destroyers extends WorldCreators
{
	public function applyToWorld($world)
	{
		foreach ($this->getAllAppendAncestorsWithRunningContexts() as $creator)
		{
			call_user_func($creator['callback'], $world);
		}

		return $world;
	}
}