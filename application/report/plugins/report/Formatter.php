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

namespace net\mkharitonov\spectrum\core\basePlugins\report;
use \net\mkharitonov\spectrum\core\Exception;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Formatter implements FormatterInterface
{
	protected $indention = '    ';
	protected $newline = "\r\n";
	protected $inputEncoding = 'utf-8';
	protected $outputEncoding = 'utf-8';

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

	public function decodeFromInputEncoding($string)
	{
		if (strtolower($this->inputEncoding) != 'utf-8')
			return iconv($this->inputEncoding, 'utf-8', $string);
		else
			return $string;
	}

/**/

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

	public function encodeToOutputEncoding($string)
	{
		if (strtolower($this->outputEncoding) != 'utf-8')
			return iconv('utf-8', $this->outputEncoding, $string);
		else
			return $string;
	}

	public function convertToOutputEncoding($string)
	{
		$string = $this->decodeFromInputEncoding($string);
		$string = $this->encodeToOutputEncoding($string);
		return $string;
	}

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

/**/

	public function getVarDump($var)
	{
		switch (gettype($var))
		{
			case 'NULL':
				return 'null';
			case 'boolean':
				return 'bool(' . ($var ? 'true' : 'false') . ')';
			case 'integer':
				return "int($var)";
			case 'double':
				return "float($var)";
			case 'string':
				return 'string(' . mb_strlen($var) . ')' . ' "' . $var . '"';
			case 'array':
				return $this->getArrayDump($var);
			case 'object':
			case 'resource':
				ob_start();
				var_dump($var);
				return ob_get_clean();
		}

		return null;
	}

	protected function getArrayDump(array $var)
	{
		$out = '';
		$out .= 'array(' . count($var) . ')' . ' {';

		if (count($var))
		{
			$out .= "\r\n";

			foreach ($var as $key => $val)
			{
				// TODO nested array print
				// TODO get indention from Formatter
				$out .= "    [$key] => " . $this->getVarDump($val) . "\r\n";
			}
		}

		$out .= '}';
		return $out;
	}
}