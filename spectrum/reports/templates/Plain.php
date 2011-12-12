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

namespace net\mkharitonov\spectrum\core\reports\formats;
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Plain extends \net\mkharitonov\spectrum\core\reports\Format
{
	protected function getNamedSpecPrintout(SpecInterface $spec)
	{
		$out = '';
		$out .= $this->formatter->putNewline($this->getSpecNamePrintout($spec));
		$out .= $this->formatter->putIndentionToEachLineAndNewline($this->getSpecChildrenPrintout($spec));

		return rtrim($out);
	}

	protected function formatChildrenPrintout($text)
	{
		return $this->formatter->putNewline($text);
	}

/**/

	protected function getSpecNamePrintout(SpecInterface $spec)
	{
		$shortClassName = $this->getSpecLabel($spec);
		if ($shortClassName == 'context')
			$title = $shortClassName . ': ';
		else
			$title = '';

		return '- ' . $title . $this->getSpecName($spec);
	}

/**/

	protected function getSpecChildrenOpenPrintout(SpecInterface $spec)
	{
		return '';
	}

	protected function getSpecChildrenClosePrintout(SpecInterface $spec)
	{
		return '';
	}
}