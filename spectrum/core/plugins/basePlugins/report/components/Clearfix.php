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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\report\components;
/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Clearfix extends \net\mkharitonov\spectrum\core\plugins\basePlugins\report\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				'.g-clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }' . $this->getNewline() .
				'.g-clearfix { *zoom: 1; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}
}