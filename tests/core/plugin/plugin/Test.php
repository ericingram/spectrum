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

namespace net\mkharitonov\spectrum\core\plugin\plugin;
require_once dirname(__FILE__) . '/../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \net\mkharitonov\spectrum\core\Test
{
	protected function setUp()
	{
		parent::setUp();
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\testEnv\PluginPublicMock');
	}

	protected function tearDown()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::unregisterPlugin('testPlugin');
		parent::tearDown();
	}
}