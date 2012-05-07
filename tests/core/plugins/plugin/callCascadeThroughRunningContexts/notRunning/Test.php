<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\notRunning;
require_once __DIR__ . '/../../../../../init.php';

abstract class Test extends \spectrum\core\plugins\plugin\callCascadeThroughRunningContexts\Test
{
	public function testHasParents_DescribeDescribeDescribe_Level1HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Describe(sibling)
			Describe
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_DescribeDescribeDescribe_Level2HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe(sibling)
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_DescribeDescribeDescribe_Level3HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe(sibling)
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_DescribeDescribeDescribe_SelfHasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
			->->->' . $this->currentSpecClass . '(sibling)
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

/**/

	public function testHasParents_DescribeContextContext_Level1HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Describe(sibling)
			Describe
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_DescribeContextContext_Level2HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context(sibling)
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_DescribeContextContext_Level3HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context(sibling)
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_DescribeContextContext_SelfHasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(sibling)
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

/**/

	public function testHasParents_ContextContextContext_Level1HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Context(sibling)
			Context
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_ContextContextContext_Level2HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context(sibling)
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_ContextContextContext_Level3HasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Context(sibling)
			->->Context
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}

	public function testHasParents_ContextContextContext_SelfHasSiblingWithNotEmptyReturn_ShouldNotBeReturnSiblingReturn()
	{
		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Context
			->->->' . $this->currentSpecClass . '(sibling)
			->->->' . $this->currentSpecClass . '(spec)
		');

		$specs['sibling']->testPlugin->setFoo('val1');

		$this->assertCallCascadeReturnSame(null, $specs['spec']);
	}
}