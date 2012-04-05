<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

class AddMatcherTest extends \spectrum\constructionCommands\baseCommands\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->assertMatcherNotExistsInDescribe('foo');
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = Manager::describe('', function(){
			Manager::addMatcher('foo', function(){});
		});

		$this->assertTrue($describe->matchers->isExists('foo'));
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"addMatcher"', function()
		{
			$it = new \spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				Manager::addMatcher('foo', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnAddedCallback()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function, &$return) {
			$return = Manager::addMatcher('foo', $function);
		});

		$this->assertSame($function, $return);
	}

	public function testShouldNotBeCallCallbackDuringCall()
	{
		Manager::addMatcher('foo', function() use(&$isCalled){
			$isCalled = true;
		});

		$this->assertNull($isCalled);
	}

	public function testNoParentCommand_ShouldBeAddMatcherToRootDescribe()
	{
		$function = function(){};
		Manager::addMatcher('foo', $function);

		$this->assertSame($function, \spectrum\RootDescribe::getOnceInstance()->matchers->get('foo'));
	}

	public function testInsideDescribeCommand_ShouldBeAddMatcherToParentDescribe()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function) {
			Manager::addMatcher('foo', $function);
		});

		$this->assertSame($function, $describe->matchers->get('foo'));
	}

	public function testInsideContextCommand_ShouldBeAddMatcherToParentContext()
	{
		$function = function(){};
		$context = Manager::context('', function() use($function) {
			Manager::addMatcher('foo', $function);
		});

		$this->assertSame($function, $context->matchers->get('foo'));
	}

/**/

	public function assertMatcherNotExistsInDescribe($name)
	{
		$describe = Manager::describe('', function(){});
		$this->assertFalse($describe->matchers->isExists($name));
	}
}