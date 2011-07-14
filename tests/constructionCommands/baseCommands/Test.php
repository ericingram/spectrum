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

namespace net\mkharitonov\spectrum\constructionCommands\baseCommands;
use net\mkharitonov\spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \net\mkharitonov\spectrum\Test
{
	protected function setUp()
	{
		parent::setUp();
		\net\mkharitonov\spectrum\RootDescribe::getInstance()->removeAllSpecs();
		\net\mkharitonov\spectrum\RootDescribe::getInstance()->matchers->removeAll();
		\net\mkharitonov\spectrum\RootDescribe::getInstance()->builders->removeAll();
		\net\mkharitonov\spectrum\RootDescribe::getInstance()->destroyers->removeAll();
		Manager::setCurrentContainer(null);
	}
}