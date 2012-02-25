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

namespace net\mkharitonov\spectrum\core\plugins\basePlugins\report;
use net\mkharitonov\spectrum\core\Config;
use \net\mkharitonov\spectrum\core\asserts\MatcherCallDetailsInterface;
use \net\mkharitonov\spectrum\core\plugins\Exception;
use \net\mkharitonov\spectrum\core\plugins\events;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Report extends \net\mkharitonov\spectrum\core\plugins\Plugin implements events\OnRunInterface
{
	protected $indention = "\t";
	protected $newline = "\r\n";
	protected $components = array();

	public function __construct(\net\mkharitonov\spectrum\core\SpecInterface $owner, $accessName)
	{
		parent::__construct($owner, $accessName);

		$this->components['messages'] = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\Messages($this);
		$this->components['clearfix'] = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\Clearfix($this);
		$this->components['runResultsBuffer'] = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\RunResultsBuffer($this);
	}

/**/

	public function setIndention($string)
	{
		if (!Config::getAllowReportSettingsModify())
			throw new Exception('Report settings modify deny in Config');

		$this->indention = $string;
	}

	public function getIndention($repeat = 1)
	{
		return str_repeat($this->indention, $repeat);
	}

	public function prependIndentionToEachLine($text, $repeat = 1)
	{
		if ($text != '')
			return $this->getIndention($repeat) . str_replace($this->getNewline(), $this->getNewline() . $this->getIndention($repeat), $text);
		else
			return $text;
	}

/**/

	public function setNewline($newline)
	{
		if (!Config::getAllowReportSettingsModify())
			throw new Exception('Report settings modify deny in Config');

		$this->newline = $newline;
	}

	public function getNewline($repeat = 1)
	{
		return str_repeat($this->newline, $repeat);
	}

/**/

	public function onRunBefore()
	{
		if (!$this->getOwner()->getParent()) // Root describe
			$this->getOwner()->output->put($this->getHeader());

		if (!$this->getOwner()->isAnonymous())
		{
			$this->getOwner()->output->put('<li class="' . $this->getSpecLabel() . '" id="' . $this->getOwner()->selector->getUidForSpec() . '">');

			$this->getOwner()->output->put('<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>');
			$this->getOwner()->output->put('<span class="g-finalResult">wait...</span>');

			if ($this->getOwner() instanceof SpecContainerInterface)
				$this->getOwner()->output->put('<ol>');

			$this->flush();
		}
	}

	public function onRunAfter($finalResult)
	{
		if (!$this->getOwner()->isAnonymous())
		{
			if ($this->getOwner() instanceof SpecContainerInterface)
				$this->getOwner()->output->put('</ol>');

			$this->getOwner()->output->put($this->getScriptForResultUpdate($this->getOwner()->selector->getUidForSpec(), $this->getSpecResultLabel($finalResult)));
			if ($finalResult === false)
				$this->getOwner()->output->put($this->components['runResultsBuffer']->getHtml());

			$this->getOwner()->output->put($this->components['messages']->getHtml());

			$this->getOwner()->output->put('</li>');

			$this->flush();
		}

		if (!$this->getOwner()->getParent()) // Root describe
			$this->getOwner()->output->put($this->getFooter());
	}

/**/

	protected function getHeader()
	{
		return
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . $this->getNewline() .
			'<html xmlns="http://www.w3.org/1999/xhtml">' . $this->getNewline() .
			'<head>' . $this->getNewline() .
				$this->getIndention() . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . $this->getNewline() .
				$this->getIndention() . '<title></title>' . $this->getNewline() .
				$this->prependIndentionToEachLine($this->getStyles()) .
				$this->prependIndentionToEachLine($this->getScripts()) . $this->getNewline() .
			'</head>' . $this->getNewline() .
			$this->getBodyTag() .
				$this->getIndention() . '<ol>' . $this->getNewline();
	}

	protected function getBodyTag()
	{
		return
			'<!--[if IE 6]><body class="g-browser-ie g-browser-ie6"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 7]><body class="g-browser-ie g-browser-ie7"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 8]><body class="g-browser-ie g-browser-ie8"><![endif]-->' . $this->getNewline() .
			'<!--[if IE 9]><body class="g-browser-ie g-browser-ie9"><![endif]-->' . $this->getNewline() .
			'<!--[if !IE]>--><body><!--<![endif]-->' . $this->getNewline();
	}

	protected function getFooter()
	{
		return
				$this->getIndention() . '</ol>' . $this->getNewline() .
			'</body>' . $this->getNewline() .
			'</html>';
	}

/**/

	protected function getStyles()
	{
		$output = '';
		$output .= $this->getCommonStyles();

		foreach ($this->components as $component)
			$output .= $component->getStyles();

		return $output;
	}

	protected function getCommonStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				'body { font-family: Verdana, sans-serif; font-size: 0.75em; }' . $this->getNewline() .

				'ol { padding-left: 1.8em; }' . $this->getNewline() .
				'.name { }' . $this->getNewline() .
				'.it { font-weight: normal; }' . $this->getNewline() .
				'.describe { }' . $this->getNewline() .
				'.context { }' . $this->getNewline() .
				'.context>.name:after { content: " (context)"; }' . $this->getNewline() .

				'.g-finalResult:before { content: " — "; }' . $this->getNewline() .
				'.g-finalResult { color: #ccc; font-weight: bold; }' . $this->getNewline() .
				'.g-finalResult.fail { color: #a31010; }' . $this->getNewline() .
				'.g-finalResult.success { color: #009900; }' . $this->getNewline() .
				'.g-finalResult.empty { color: #cc9900; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}


	protected function getScripts()
	{
		$output = '';
		$output .= $this->getCommonScripts();

		foreach ($this->components as $component)
			$output .= $component->getScripts();

		return $output;
	}

	protected function getCommonScripts()
	{
		return
			'<script type="text/javascript">' . $this->getNewline() .
				'function updateResult(uid, resultLabel, resultName){' . $this->getNewline() .
					'var spec = document.getElementById(uid);' . $this->getNewline() .
					'var result = spec.childNodes[1];' . $this->getNewline() .
					'result.className += " " + resultLabel;' . $this->getNewline() .
					'result.innerHTML = resultName;' . $this->getNewline() .
				'}' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

/**/

	protected function getSpecName()
	{
		$parent = $this->getOwner()->getParent();
		$name = $this->getOwner()->getName();

		if ($name == '' && $parent && $parent instanceof \net\mkharitonov\spectrum\core\SpecContainerArgumentsProviderInterface)
			return $this->getAdditionalArgumentsDumpOut();
		else
			return $name;
	}

	protected function getAdditionalArgumentsDumpOut()
	{
		$output = '';
		foreach ($this->getOwner()->getAdditionalArguments() as $arg)
			$output .= $arg . ', ';

		return mb_substr($output, 0, -2);
	}

	protected function getSpecLabel()
	{
		if ($this->getOwner() instanceof SpecContainerDescribeInterface)
			return 'describe';
		else if ($this->getOwner() instanceof SpecContainerContextInterface)
			return 'context';
		else if ($this->getOwner() instanceof SpecItemItInterface)
			return 'it';
		else if ($this->getOwner() instanceof SpecContainerInterface)
			return 'container';
		else if ($this->getOwner() instanceof SpecItemInterface)
			return 'item';
		else
			return 'spec';
	}

	protected function getSpecResultLabel($result)
	{
		if ($result === false)
			$name = 'fail';
		else if ($result === true)
			$name = 'success';
		else
			$name = 'empty';

		return $name;
	}

	protected function getScriptForResultUpdate($specUid, $resultLabel)
	{
		// TODO заменить на периодическую проверку по коду в <head>
		return
			'<script type="text/javascript">' .
				'updateResult("' . $specUid . '", "' . $resultLabel . '", "' . $resultLabel . '");' .
			'</script>';
	}

	protected function flush()
	{
		$this->getOwner()->output->put('<span style="display: none;">' . str_repeat(' ', 256) . '</span>' . $this->getNewline());
		flush();
	}
}