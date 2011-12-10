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

namespace net\mkharitonov\spectrum\core\report\formatters;
use \net\mkharitonov\spectrum\core\Exception;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Formatter implements FormatterInterface
{
	protected $indention = '    ';
	protected $newline = "\r\n";

/**/

	public function getIndention($repeat = 1)
	{
		return str_repeat($this->indention, $repeat);
	}

	public function setIndention($string)
	{
		$this->indention = $string;
	}

	public function putIndention($text)
	{
		if ($text != '')
			return $this->getIndention() . $text;
		else
			return $text;
	}

	public function putIndentionAndNewline($text)
	{
		return $this->putNewline($this->putIndention($text));
	}

	public function putIndentionToEachLine($text, $repeat = 1)
	{
		if ($text != '')
			return $this->getIndention($repeat) . str_replace("\r\n", "\r\n" . $this->getIndention($repeat), $text);
		else
			return $text;
	}

	public function putIndentionToEachLineAndNewline($text, $repeat = 1)
	{
		return $this->putNewline($this->putIndentionToEachLine($text, $repeat));
	}

/**/

	public function setNewline($newline)
	{
		$this->newline = $newline;
	}

	public function getNewline()
	{
		return $this->newline;
	}

	public function putNewline($text)
	{
		if ($text != '')
			return $text . $this->getNewline();
		else
			return $text;
	}
}