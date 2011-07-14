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
class ActualTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::actual('');
		});

		$it->run();
		$this->assertTrue($assert instanceof \net\mkharitonov\spectrum\core\assert\Assert);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', '"actual"', function(){
			Manager::describe('', function(){
				Manager::actual('');
			});
		});
	}

	public function testShouldBeReturnAssertInstance()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::actual('');
		});

		$it->run();
		$this->assertTrue($assert instanceof \net\mkharitonov\spectrum\core\assert\Assert);
	}

	public function testShouldBeSetActualValueToAssertInstance()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::actual('foo');
		});

		$it->run();
		$this->assertEquals('foo', $assert->getActualValue());
	}
}