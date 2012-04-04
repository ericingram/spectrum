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

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class BeTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::be('');
		});

		$it->run();
		$this->assertTrue($assert instanceof \spectrum\core\asserts\Assert);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"be" should be call only at running state', function(){
			Manager::describe('', function(){
				Manager::be('');
			});
		});
	}

	public function testShouldBeReturnAssertInstance()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::be('');
		});

		$it->run();
		$this->assertTrue($assert instanceof \spectrum\core\asserts\Assert);
	}

	public function testShouldBeSetActualValueToAssertInstance()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::be('foo');
		});

		$it->run();
		$this->assertEquals('foo', $assert->getActualValue());
	}
}