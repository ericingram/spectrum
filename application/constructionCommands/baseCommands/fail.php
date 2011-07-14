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

/**
 * Add to ResultBuffer of running SpecItem false result wits exception as details.
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @throws \net\mkharitonov\spectrum\constructionCommands\Exception If called not at running state
 * @param string|null $message
 * @param int $code
 */
function fail($message = null, $code = 0)
{
	if (!Manager::isRunningState())
		throw new \net\mkharitonov\spectrum\constructionCommands\Exception('Construction command "fail" should be call only at running state');
	
	Manager::getCurrentItem()->getResultBuffer()->addResult(false, new \net\mkharitonov\spectrum\constructionCommands\Exception($message, $code));
}