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
class Messages extends \net\mkharitonov\spectrum\core\plugins\basePlugins\report\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-messages:before { content: "Сообщения: "; display: block; position: absolute; top: -1.8em; left: 0; padding: 0.3em 0.5em; background: #f5f1f1; color: #888; font-style: italic; }' . $this->getNewline() .
				$this->getIndention() . '.g-messages { position: relative; margin: 2em 0 1em 0; }' . $this->getNewline() .
				$this->getIndention() . '.g-messages ul { display: inline-block; list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-messages ul li { padding: 5px; margin-bottom: 1px; background: #ccc; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		$messages = $this->getReport()->getOwner()->messages->getAll();

		if (!count($messages))
			return null;

		$output = '';

		$output .= '<div class="g-messages g-clearfix">' . $this->getNewline();
		$output .= $this->getIndention() . '<ul>' . $this->getNewline();
		foreach ($messages as $message)
			$output .= $this->getIndention(2) . '<li>' . htmlspecialchars($message) . '</li>' . $this->getNewline();

		$output .= $this->getIndention() . '</ul>' . $this->getNewline();
		$output .= '</div>' . $this->getNewline();

		return $output;
	}
}