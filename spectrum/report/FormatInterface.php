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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
interface FormatInterface
{
	public function getHeader();
	public function getContent($putHeader = true, $putFooter = true);
	public function getFooter();

	public function getSpecLabel();
	public function getSpecOpen();
	public function getSpecClose();

	// TODO getSpecNameOpen
	// TODO getSpecNameNode
	public function getSpecNameOpen();
	public function getSpecName();
	public function getSpecNameClose();

	public function getSpecResultOpen();
	public function getSpecResultLabel();
	public function getSpecResultName();
	public function getSpecResultClose();

	public function getSpecChildrenOpen();
	public function getSpecChildrenClose();
}