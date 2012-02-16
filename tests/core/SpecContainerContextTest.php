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
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecContainerContextTest extends SpecContainerTest
{
	protected $currentSpecClass = '\net\mkharitonov\spectrum\core\SpecContainerContext';
	protected $currentSpecMockClass = '\net\mkharitonov\spectrum\core\testEnv\SpecContainerContextMock';

/**/

/*	public function testGetUidInContext_RunningState_ShouldBeReturnUidWithRunningContextId()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Context
			->Describe
			->Describe
			->->Context
			->->Context
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
			->->->->It(it)
		');

		$specs['it']->setTestCallback(function() use(&$uids, $specs){
			$uids[] = $specs['spec']->getUidInContext();
		});

		$specs['spec']->run();

		$this->assertSame(array(
			'spec_0_3_2_0_context_0_0',
			'spec_0_3_2_0_context_0_1',
			'spec_0_3_2_0_context_1_0',
			'spec_0_3_2_0_context_1_1',
		), $uids);
	}

	public function testGetUidInContext_RunningState_ShouldNotBeAppentSelfToContextId()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Describe
			->->Context
			->->Describe
			->->->' . $this->currentSpecClass . '(spec)
			->->->->It(it)
		');

		$specs['it']->setTestCallback(function() use(&$uids, $specs){
			$uids[] = $specs['spec']->getUidInContext();
		});

		$specs['spec']->run();

		$this->assertSame(array(
			'spec_0_1_1_0_context_0_0',
		), $uids);
	}*/

/**/

	public function testRun_HasNoChildContexts_ShouldBeRunAllEnabledChildrenOfNearestNotContextAncestor()
	{
		$specs = $this->createSpecsTree('
			Describe
			->DescribeMock
			->ItMock
			->Context
			->->Context
		');

		$this->injectToRunStartSaveInstanceToCollection($specs[1]);
		$this->injectToRunStartSaveInstanceToCollection($specs[2]);

		$specs[0]->run();
		$this->assertInstanceInCollection($specs[1]);
		$this->assertInstanceInCollection($specs[2]);
	}

	public function testRun_HasNoChildContexts_ShouldNotBeRunDisabledChildrenOfNearestNotContextAncestor()
	{
		$specs = $this->createSpecsTree('
			Describe
			->DescribeMock
			->ItMock
			->Context
			->->Context
		');

		$specs[1]->disable();
		$specs[2]->disable();
		$this->injectToRunStartCallsCounter($specs[1]);
		$this->injectToRunStartCallsCounter($specs[2]);

		$specs[0]->run();
		$this->assertCallsCounterEquals(0);
	}

	public function testRun_HasNoChildContexts_ShouldBeRunAllEnabledChildrenOfMiddleContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->DescribeMock
			->->ItMock
			->->Context
			->->->DescribeMock
			->->->ItMock
			->->->Context
		');

		$this->injectToRunStartSaveInstanceToCollection($specs[2]);
		$this->injectToRunStartSaveInstanceToCollection($specs[3]);
		$this->injectToRunStartSaveInstanceToCollection($specs[5]);
		$this->injectToRunStartSaveInstanceToCollection($specs[6]);

		$specs[0]->run();
		$this->assertInstanceInCollection($specs[2]);
		$this->assertInstanceInCollection($specs[3]);
		$this->assertInstanceInCollection($specs[5]);
		$this->assertInstanceInCollection($specs[6]);
	}

	public function testRun_HasNoChildContexts_ShouldNotBeRunDisabledChildrenOfMiddleContexts()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->DescribeMock
			->->ItMock
			->->Context
			->->->DescribeMock
			->->->ItMock
			->->->Context
		');

		$specs[2]->disable();
		$specs[3]->disable();
		$specs[5]->disable();
		$specs[6]->disable();

		$this->injectToRunStartCallsCounter($specs[2]);
		$this->injectToRunStartCallsCounter($specs[3]);
		$this->injectToRunStartCallsCounter($specs[5]);
		$this->injectToRunStartCallsCounter($specs[6]);

		$specs[0]->run();
		$this->assertCallsCounterEquals(0);
	}

	public function testRun_HasNoChildContexts_ShouldBeRunAllEnabledOwnChildren()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->DescribeMock
			->->ItMock
		');

		$this->injectToRunStartSaveInstanceToCollection($specs[2]);
		$this->injectToRunStartSaveInstanceToCollection($specs[3]);

		$specs[0]->run();
		$this->assertInstanceInCollection($specs[2]);
		$this->assertInstanceInCollection($specs[3]);
	}

	public function testRun_HasNoChildContexts_ShouldNotBeRunDisabledOwnChildren()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->DescribeMock
			->->ItMock
		');

		$specs[2]->disable();
		$specs[3]->disable();

		$this->injectToRunStartCallsCounter($specs[2]);
		$this->injectToRunStartCallsCounter($specs[3]);

		$specs[0]->run();
		$this->assertCallsCounterEquals(0);
	}

	public function testRun_HasNoChildContexts_ShouldNotBeRunChildrenFromAncestorsAboveNearestNotContextAncestor()
	{
		$specs = $this->createSpecsTree('
			Describe
			->ItMock
			->Describe
			->->Context
		');

		$this->injectToRunStartCallsCounter($specs[1]);

		$specs[0]->run();
		$this->assertCallsCounterEquals(1); // Not 2
	}

	public function testRun_HasNoChildContexts_ShouldBeRunAncestorsSpecsFirst()
	{
		$specs = $this->createSpecsTree('
			Describe
			->DescribeMock
			->ItMock
			->Context
			->->DescribeMock
			->->ItMock
			->->Context
			->->->DescribeMock
			->->->ItMock
			->->->Context
			->->->->DescribeMock
			->->->->ItMock
		');

		$this->injectToRunStartCallsOrderChecker($specs[1], 0);
		$this->injectToRunStartCallsOrderChecker($specs[2], 1);
		$this->injectToRunStartCallsOrderChecker($specs[4], 2);
		$this->injectToRunStartCallsOrderChecker($specs[5], 3);
		$this->injectToRunStartCallsOrderChecker($specs[7], 4);
		$this->injectToRunStartCallsOrderChecker($specs[8], 5);
		$this->injectToRunStartCallsOrderChecker($specs[10], 6);
		$this->injectToRunStartCallsOrderChecker($specs[11], 7);

		$specs[0]->run();
		$this->assertCallsInOrder(8);
	}

/**/

	public function testRun_DirectRunWhenHasParents_ShouldBeReturnParentRunResult()
	{
		$specs = $this->createSpecsTree('
			DescribeMock
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs[0]->__setRunReturnValue('foo');
		$this->assertEquals('foo', $specs['testSpec']->run());
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeDisableAllContextSiblingsDuringRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->ContextMock
			->' . $this->currentSpecClass . '(testSpec)
			->ContextMock
		');

		$this->injectToRunStartCallsCounter($specs[1]);
		$this->injectToRunStartCallsCounter($specs[3]);

		$specs['testSpec']->run();
		$this->assertCallsCounterEquals(0);
	}

	public function testRun_DirectRunWhenHasParents_ShouldNotBeDisableNotContextSiblings()
	{
		$specs = $this->createSpecsTree('
			Describe
			->DescribeMock
			->' . $this->currentSpecClass . '(testSpec)
			->ItMock
		');

		$this->injectToRunStartSaveInstanceToCollection($specs[1]);
		$this->injectToRunStartSaveInstanceToCollection($specs[3]);

		$specs['testSpec']->run();
		$this->assertInstanceInCollection($specs[1]);
		$this->assertInstanceInCollection($specs[3]);
	}

	public function testRun_DirectRunWhenHasParents_ShouldNotBeEnableDisabledNotContextSiblings()
	{
		$specs = $this->createSpecsTree('
			Describe
			->DescribeMock
			->' . $this->currentSpecClass . '(testSpec)
			->ItMock
		');

		$this->injectToRunStartCallsCounter($specs[1]);
		$this->injectToRunStartCallsCounter($specs[3]);

		$specs[1]->disable();
		$specs[3]->disable();
		$specs['testSpec']->run();

		$this->assertCallsCounterEquals(0);
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeEnableSelfDuringRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecMockClass . '(testSpec)
		');

		$this->injectToRunStartCallsCounter($specs['testSpec']);
		$specs['testSpec']->disable();

		$specs['testSpec']->run();
		$this->assertCallsCounterEquals(1);
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeRestoreSiblingsEnabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Describe
			->It
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs['testSpec']->run();

		$this->assertTrue($specs[1]->isEnabled());
		$this->assertTrue($specs[2]->isEnabled());
		$this->assertTrue($specs[3]->isEnabled());
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeRestoreSiblingsDisabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Describe
			->It
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs[1]->disable();
		$specs[2]->disable();
		$specs[3]->disable();

		$specs['testSpec']->run();

		$this->assertFalse($specs[1]->isEnabled());
		$this->assertFalse($specs[2]->isEnabled());
		$this->assertFalse($specs[3]->isEnabled());
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeRestoreSelfEnabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs['testSpec']->run();

		$this->assertTrue($specs['testSpec']->isEnabled());
	}

	public function testRun_DirectRunWhenHasParents_ShouldBeRestoreSelfDisabledStatusAfterRun()
	{
		$specs = $this->createSpecsTree('
			Describe
			->' . $this->currentSpecClass . '(testSpec)
		');

		$specs['testSpec']->disable();
		$specs['testSpec']->run();

		$this->assertFalse($specs['testSpec']->isEnabled());
	}
}