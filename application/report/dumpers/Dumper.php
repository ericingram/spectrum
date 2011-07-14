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

namespace net\mkharitonov\spectrum\core\report;
use \net\mkharitonov\spectrum\core\Exception;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Dumper implements DumperInterface
{
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

	public function getArrayDump(array $var)
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