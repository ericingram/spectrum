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
abstract class WorldCreators extends \net\mkharitonov\spectrum\core\basePlugins\stack\Indexed
{
	public function add($callback, $type = 'each')
	{
		if (!in_array($type, array('each')))
			throw new \net\mkharitonov\spectrum\core\Exception('Wrong world creator type (allowed "each", but "' . $type . '" given)');

		parent::add(array(
			'callback' => $callback,
			'type' => $type,
		));

		return $callback;
	}

	public function remove($indexOrCallback)
	{
		if (is_int($indexOrCallback))
			return parent::removeByKey($indexOrCallback);
		else
			return parent::removeByValue($indexOrCallback);
	}

	abstract public function applyToWorld($world);
}