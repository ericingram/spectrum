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

namespace net\mkharitonov\spectrum\core\reports\transformations;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
interface TransformationInterface
{
	public function getIndention($repeat = 1);
	public function setIndention($string);
	public function putIndention($text);
	public function putIndentionAndNewline($text);
	public function putIndentionToEachLine($text, $repeat = 1);
	public function putIndentionToEachLineAndNewline($text, $repeat = 1);
	public function setNewline($newline);
	public function getNewline();
	public function putNewline($text);
}