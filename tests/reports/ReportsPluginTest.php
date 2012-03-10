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

namespace net\mkharitonov\spectrum\reports;
require_once dirname(__FILE__) . '/../init.php';

use net\mkharitonov\spectrum\core\Config;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ReportsPluginTest extends \net\mkharitonov\spectrum\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->restoreStaticProperties('\net\mkharitonov\spectrum\core\plugins\Manager');
	}

	public function testSetIndention_ShouldBeThrowExceptionIfNotAllowReportSettingsModify()
	{
		Config::setAllowReportSettingsModify(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Report settings modify deny', function(){
			$spec = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
			$spec->reports->setIndention(false);
		});
	}

	public function testSetNewline_ShouldBeThrowExceptionIfNotAllowReportSettingsModify()
	{
		Config::setAllowReportSettingsModify(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Report settings modify deny', function(){
			$spec = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
			$spec->reports->setNewline(false);
		});
	}
}