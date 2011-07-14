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

namespace net\mkharitonov\spectrum\core\assert\assert\callMatcher;
use net\mkharitonov\spectrum\core\assert\Assert;

require_once dirname(__FILE__) . '/../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Test extends \net\mkharitonov\spectrum\core\assert\assert\Test
{

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setCatchExceptions(true);
		return $it;
	}
}