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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\report;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Component implements ComponentInterface
{
	protected $report;

	public function __construct(\net\mkharitonov\spectrum\core\plugins\basePlugins\report\Report $report)
	{
		$this->report = $report;
	}
	public function getReport()
	{
		return $this->report;
	}

	public function getStyles()
	{
		return null;
	}

	public function getScripts()
	{
		return null;
	}

	public function getHtml()
	{
		return null;
	}


	protected function getIndention($repeat = 1)
	{
		return $this->getReport()->getIndention($repeat);
	}

	protected function prependIndentionToEachLine($text, $repeat = 1)
	{
		return $this->getReport()->prependIndentionToEachLine($text, $repeat);
	}

	protected function getNewline($repeat = 1)
	{
		return $this->getReport()->getNewline($repeat);
	}
}