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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\report\formats;
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Xhtml extends \net\mkharitonov\spectrum\core\plugins\basePlugins\report\Format
{
	public function getHeader()
	{
		$output = '';
		$output .= $this->formatter->putNewline('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
		$output .= $this->formatter->putNewline('<html xmlns="http://www.w3.org/1999/xhtml">');
		$output .= $this->formatter->putNewline('<head>');
		$output .= $this->formatter->putIndentionAndNewline('<meta http-equiv="content-type" content="text/html; charset=' . $this->formatter->getOutputEncoding() . '" />');
		$output .= $this->formatter->putIndentionAndNewline('<title>' . htmlspecialchars($this->formatter->convertToOutputEncoding($this->getSpec()->getName())) . '</title>');
		$output .= $this->formatter->putIndentionToEachLineAndNewline($this->getStyles()) . $this->formatter->getNewline();
		$output .= $this->formatter->putNewline('</head>');
		$output .= $this->formatter->putNewline('<body>');

		return rtrim($output);
	}

	protected function getStyles()
	{
		$output = '';
		$output .= $this->formatter->putNewline('<style type="text/css">');
		$output .= $this->formatter->putIndentionToEachLine($this->getCssRules()) . $this->formatter->getNewline();
		$output .= $this->formatter->putNewline('</style>');

		return rtrim($output);
	}

	protected function getCssRules()
	{
		$output = '';
		$output .= 'body { font-family: Verdana, sans-serif; font-size: 0.75em; }' . $this->formatter->getNewline();

		$output .= '.clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }' . $this->formatter->getNewline();
		$output .= '.clearfix { *zoom: 1; }' . $this->formatter->getNewline();

		$output .= '.name { }' . $this->formatter->getNewline();
		$output .= '.it { font-weight: normal; }' . $this->formatter->getNewline();
		$output .= '.describe { }' . $this->formatter->getNewline();
		$output .= '.context { }' . $this->formatter->getNewline();
		$output .= '.context>.name:after { content: " (context)"; }' . $this->formatter->getNewline();

		$output .= '.finalResult:before { content: "— "; }' . $this->formatter->getNewline();
		$output .= '.finalResult { color: #ccc; }' . $this->formatter->getNewline();
		$output .= '.finalResult.fail { color: #a31010; }' . $this->formatter->getNewline();
		$output .= '.finalResult.success { color: #009900; }' . $this->formatter->getNewline();
		$output .= '.finalResult.empty { color: #cc9900; }' . $this->formatter->getNewline();

		$output .= '.resultBuffer:before { content: "Содержимое результирующего буфера: "; display: block; position: absolute; top: -1.8em; left: 0; padding: 0.3em 0.5em; background: #eee; color: #888; font-style: italic; }' . $this->formatter->getNewline();
		$output .= '.resultBuffer { position: relative; margin: 2em 0 1em 0; border-top: 1px dotted #ddd; background: #eee; }' . $this->formatter->getNewline();

		$output .= '.resultBuffer .result { float: left; padding: 0.5em; border: 1px dotted #ddd; border-top: 0; border-left: 0; }' . $this->formatter->getNewline();

		$output .= '.resultBuffer .value:before { content: "Result: "; font-weight: bold; }' . $this->formatter->getNewline();

		$output .= '.resultBuffer .details:before { display: block; content: "Details: "; font-weight: bold; }' . $this->formatter->getNewline();
		$output .= '.resultBuffer .details { white-space: pre; }' . $this->formatter->getNewline();
		$output .= '.resultBuffer .details.assert .actualValue:before { content: "ActualValue: "; font-size: 0.9em; color: #888; }' . $this->formatter->getNewline();
		$output .= '.resultBuffer .details.assert .isNot:before { content: "IsNot: "; font-size: 0.9em; color: #888; }' . $this->formatter->getNewline();
		$output .= '.resultBuffer .details.assert .matcherName:before { content: "MatcherName: "; font-size: 0.9em; color: #888; }' . $this->formatter->getNewline();
		$output .= '.resultBuffer .details.assert .matcherArgs:before { content: "MatcherArgs: "; font-size: 0.9em; color: #888; }' . $this->formatter->getNewline();
		$output .= '.resultBuffer .details.assert .matcherReturnValue:before { content: "MatcherReturnValue (without invert): "; font-size: 0.9em; color: #888; }' . $this->formatter->getNewline();
		$output .= '.resultBuffer .details.assert .matcherException:before { content: "MatcherException: "; font-size: 0.9em; color: #888; }' . $this->formatter->getNewline();

		return rtrim($output);
	}

	public function getFooter()
	{
		$output = '';
		$output .= '</body>' . $this->formatter->getNewline();
		$output .= '</html>' . $this->formatter->getNewline();

		return rtrim($output);
	}

	public function getSpecOpen()
	{
		if ($this->getPutId())
			return '<li class="' . $this->getSpecLabel() . '" id="' . $this->getSpec()->getUid() . '">';
		else
			return '<li class="' . $this->getSpecLabel() . '">';
	}

	public function getSpecName()
	{
		return htmlspecialchars(parent::getSpecName());
	}

	public function getSpecClose()
	{
		return '</li>';
	}

	public function getSpecNameOpen()
	{
		return '<span class="name">';
	}

	public function getSpecNameClose()
	{
		return '</span>';
	}

	public function getSpecResultOpen()
	{
		return '<span class="result">';
	}

	public function getSpecResultName()
	{
		return htmlspecialchars(parent::getSpecResultName());
	}

	public function getSpecResultClose()
	{
		return '</span>';
	}
	public function getSpecChildrenOpen()
	{
		return '<ol>';
	}

	public function getSpecChildrenClose()
	{
		return '</ol>';
	}
}