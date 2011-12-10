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

namespace net\mkharitonov\spectrum\core\plugins;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
interface PluginInterface
{
	/**
	 * For example, call parent spec plugin: $owner->getParent()->$accessName()
	 * @param $owner
	 * @param string $accessName
	 */
	public function __construct(\net\mkharitonov\spectrum\core\SpecInterface $owner, $accessName);
	public function getOwner();
	public function getAccessName();
}