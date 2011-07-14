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
class ContextTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$context = Manager::context('', function(){});
		$this->assertTrue($context instanceof \net\mkharitonov\spectrum\core\SpecContainerContextInterface);
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', '"context"', function()
		{
			$it = new \net\mkharitonov\spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				Manager::context('', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnNewSpecContainerContextInstance()
	{
		$describe = Manager::context('', function(){});
		$this->assertTrue($describe instanceof \net\mkharitonov\spectrum\core\SpecContainerContextInterface);
	}

//	public function testShouldBeThrowExceptionIfCalledAtRunningState()
//	{
//		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', '"context"', function()
//		{
//			$it = Manager::it('', function(){
//				Manager::context('', function(){});
//			});
//
//			$it->run();
//		});
//	}
}