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

namespace net\mkharitonov\spectrum\core;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
interface SpecInterface
{
	public function __construct($name = null);
	public function __get($accessName);

	public function setName($name);
	public function getName();
	public function isAnonymous();
	public function getUid();

	public function setParent(SpecContainerInterface $spec = null);
	public function getParent();

	public function callPlugin($accessName);

	public function enable();
	public function disable();
	public function isEnabled();

	public function run();
	public function isRunning();
}