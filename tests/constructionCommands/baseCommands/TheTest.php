<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

class TheTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::the('');
		});

		$it->run();
		$this->assertTrue($assert instanceof \spectrum\core\asserts\Assert);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"the" should be call only at running state', function(){
			Manager::describe('', function(){
				Manager::the('');
			});
		});
	}

	public function testShouldBeReturnAssertInstance()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::the('');
		});

		$it->run();
		$this->assertTrue($assert instanceof \spectrum\core\asserts\Assert);
	}

	public function testShouldBeSetActualValueToAssertInstance()
	{
		$it = Manager::it('', function() use(&$assert) {
			$assert = Manager::the('foo');
		});

		$it->run();
		$this->assertEquals('foo', $assert->getActualValue());
	}
}