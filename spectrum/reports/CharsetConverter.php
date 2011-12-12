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

namespace net\mkharitonov\spectrum\core\reports;
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;

use \net\mkharitonov\spectrum\core\plugins\events;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class CharsetConverter
{
	protected $outputEncoding = 'utf-8';

	public function setOutputEncoding($outputEncoding)
	{
		if (!$outputEncoding)
			$outputEncoding = 'utf-8';

		$this->outputEncoding = $outputEncoding;
	}

	public function getOutputEncoding()
	{
		return $this->outputEncoding;
	}

	protected function convertToOutputEncoding($string)
	{
		if (is_array($string))
		{
			foreach ($string as $key => $val)
				$string[$key] = $this->convertToOutputEncoding($val);

			return $string;
		}
		else
		{
			if (strtolower($this->outputEncoding) != 'utf-8')
				return iconv('utf-8', $this->outputEncoding, $string);
			else
				return $string;
		}
	}
}