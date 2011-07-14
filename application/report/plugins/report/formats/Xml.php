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

namespace net\mkharitonov\spectrum\core\basePlugins\report\formats;
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Xml extends \net\mkharitonov\spectrum\core\basePlugins\report\Format
{
	public function getHeader()
	{
		return '<?xml version="1.0" encoding="' . $this->formatter->getOutputEncoding() . '"?>';
	}

	public function getFooter()
	{
		return '';
	}

	protected function getSpecOpenPrintout(SpecInterface $spec)
	{
		if ($this->getPutId())
			return '<' . $this->getSpecLabel($spec) . ' id="' . $spec->getUid() . '">';
		else
			return '<' . $this->getSpecLabel($spec) . '>';
	}

	protected function getSpecClosePrintout(SpecInterface $spec)
	{
		return '</' . $this->getSpecLabel($spec) . '>';
	}

/**/

	protected function getSpecNamePrintout(SpecInterface $spec)
	{
		return '<name>' . htmlspecialchars($this->getSpecName($spec)) . '</name>';
	}

/**/

	protected function getSpecChildrenOpenPrintout(SpecInterface $spec)
	{
		return '<specs>';
	}

	protected function getSpecChildrenClosePrintout(SpecInterface $spec)
	{
		return '</specs>';
	}
}