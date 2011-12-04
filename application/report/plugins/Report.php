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
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;

use \net\mkharitonov\spectrum\core\plugins\events;
use \net\mkharitonov\spectrum\core\report\Buffer;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Report extends \net\mkharitonov\spectrum\core\plugins\Plugin
{
	protected $inputEncoding = 'utf-8';


	public function setInputEncoding($inputEncoding)
	{
		if (!$inputEncoding)
			$inputEncoding = 'utf-8';

		$this->inputEncoding = $inputEncoding;
	}

	public function getInputEncoding()
	{
		return $this->inputEncoding;
	}

	protected function convertFromInputEncoding($string)
	{
		if (is_array($string))
		{
			foreach ($string as $key => $val)
				$string[$key] = $this->convertFromInputEncoding($val);

			return $string;
		}
		else
		{
			if (strtolower($this->inputEncoding) != 'utf-8')
				return iconv($this->inputEncoding, 'utf-8', $string);
			else
				return $string;
		}
	}
}