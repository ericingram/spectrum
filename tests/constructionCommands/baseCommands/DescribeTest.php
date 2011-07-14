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
class DescribeTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = Manager::describe('', function(){});
		$this->assertTrue($describe instanceof \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface);
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', '"describe"', function()
		{
			$it = new \net\mkharitonov\spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				Manager::describe('', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnNewSpecContainerDescribeInstance()
	{
		$describe = Manager::describe('', function(){});
		$this->assertTrue($describe instanceof \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface);
	}
}