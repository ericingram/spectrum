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
class SetCurrentContainerTest extends \net\mkharitonov\spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeSetCurrentContainer()
	{
		$describe = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
		Manager::setCurrentContainer($describe);

		$this->assertSame($describe, Manager::getCurrentContainer());
	}

	public function testShouldBeAcceptNull()
	{
		Manager::setCurrentContainer(new \net\mkharitonov\spectrum\core\SpecContainerDescribe());
		Manager::setCurrentContainer(null);
		$this->assertSame(\net\mkharitonov\spectrum\RootDescribe::getInstance(), Manager::getCurrentContainer());
	}

	public function testShouldBeAcceptOnlySpecContainerInstances()
	{
		$this->assertThrowException('\Exception', function(){
			Manager::setCurrentContainer(new \net\mkharitonov\spectrum\core\SpecItemIt());
		});
	}
}