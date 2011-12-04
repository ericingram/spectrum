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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\stack\named;
use net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @see IndexedTest
 */
abstract class Test extends \net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Test
{
	protected function setUp()
	{
		parent::setUp();
		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Named');
	}

	protected function tearDown()
	{
		\net\mkharitonov\spectrum\core\plugins\Manager::unregisterPlugin('testPlugin');
		parent::tearDown();
	}
}