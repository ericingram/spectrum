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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins;
require_once dirname(__FILE__) . '/../../../init.php';

use net\mkharitonov\spectrum\core\Config;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class LiveReportTest extends Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->restoreStaticProperties('\net\mkharitonov\spectrum\core\plugins\Manager');
	}

	public function testSetOutputDebug_ShouldBeThrowExceptionIfNotAllowLiveReportModify()
	{
		Config::setAllowLiveReportModify(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Live report modify deny', function(){
			$spec = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
			$spec->liveReport->setOutputDebug(false);
		});
	}

	public function testSetIndention_ShouldBeThrowExceptionIfNotAllowLiveReportModify()
	{
		Config::setAllowLiveReportModify(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Live report modify deny', function(){
			$spec = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
			$spec->liveReport->setIndention(false);
		});
	}

	public function testSetNewline_ShouldBeThrowExceptionIfNotAllowLiveReportModify()
	{
		Config::setAllowLiveReportModify(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Live report modify deny', function(){
			$spec = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
			$spec->liveReport->setNewline(false);
		});
	}
}