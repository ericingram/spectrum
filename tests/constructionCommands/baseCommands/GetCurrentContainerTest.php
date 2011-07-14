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
class GetCurrentContainerTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	public function testDeclaringState_ShouldBeReturnCurrentContainerIfItSet()
	{
		$container = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
		Manager::setCurrentContainer($container);

		$this->assertSame($container, Manager::getCurrentContainer());
	}

	public function testDeclaringState_ShouldBeReturnRootDescribeIfCurrentContainerNotSet()
	{
		$this->assertSame(\net\mkharitonov\spectrum\RootDescribe::getInstance(), Manager::getCurrentContainer());
	}

	public function testDeclaringState_ShouldBeReturnOnceRootDescribeInstance()
	{
		$container1 = Manager::getCurrentContainer();
		$container2 = Manager::getCurrentContainer();
		$this->assertSame($container1, $container2);
	}

/**/

	public function testRunningState_ParentDescribe_ShouldBeReturnNearestParentDescribe()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->It
		');

		$specs[2]->setTestCallback(function() use(&$currentContainer) {
			$currentContainer = Manager::getCurrentContainer();
		});
		$specs[2]->run();

		$this->assertSame($specs[1], $currentContainer);
	}

	public function testRunningState_ParentContext_ShouldBeReturnNearestParentContext()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->It
		');

		$specs[2]->setTestCallback(function() use(&$currentContainer) {
			$currentContainer = Manager::getCurrentContainer();
		});
		$specs[2]->run();

		$this->assertSame($specs[1], $currentContainer);
	}

	public function testRunningState_ShouldBeReturnNullIfHasNoAncestorContainer()
	{
		$it = new \net\mkharitonov\spectrum\core\SpecItemIt();
		$it->setTestCallback(function() use(&$isCalled, &$currentContainer) {
			$isCalled = true;
			$currentContainer = Manager::getCurrentContainer();
		});
		$it->run();

		$this->assertTrue($isCalled);
		$this->assertNull($currentContainer);
	}
}