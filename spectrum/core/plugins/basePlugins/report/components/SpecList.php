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
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecList extends \net\mkharitonov\spectrum\core\plugins\basePlugins\report\Component
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				'ol.g-specList { padding-left: 1.8em; }' . $this->getNewline() .
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

	public function getScripts()
	{
		return
			'<script type="text/javascript">' . $this->getNewline() .
				'function updateResult(uid, resultLabel, resultName){' . $this->getNewline() .
					'var spec = document.getElementById(uid);' . $this->getNewline() .
					'var result = spec.childNodes[3];' . $this->getNewline() .
					'result.className += " " + resultLabel;' . $this->getNewline() .
					'result.innerHTML = resultName;' . $this->getNewline() .
				'}' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml()
	{
		throw new \net\mkharitonov\spectrum\core\plugins\Exception('Method "getHtml()" of component "SpecList" forbidden, use "getHtmlBegin()" and "getHtmlEnd()" methods');
	}

	public function getHtmlBegin()
	{
		$output = '';

		if (!$this->getReport()->getOwner()->getParent())
			$output .= '<ol class="g-specList">' . $this->getNewline();

		if (!$this->getReport()->getOwner()->isAnonymous())
		{
			$output .= '<li class="' . $this->getSpecLabel() . '" id="' . $this->getReport()->getOwner()->selector->getUidForSpec() . '">' . $this->getNewline();

			$output .= '<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>' . $this->getNewline();
			$output .= '<span class="g-finalResult">wait...</span>' . $this->getNewline();

			if ($this->getReport()->getOwner() instanceof SpecContainerInterface || !$this->getReport()->getOwner()->getParent())
				$output .= '<ol class="g-specList">' . $this->getNewline();
		}

		return $output;
	}

	public function getHtmlEnd($finalResult)
	{
		$output = '';

		if (!$this->getReport()->getOwner()->isAnonymous())
		{
			if ($this->getReport()->getOwner() instanceof SpecContainerInterface)
				$output .= '</ol>' . $this->getNewline();

			$output .= $this->getScriptForResultUpdate($this->getReport()->getOwner()->selector->getUidForSpec(), $this->getSpecResultLabel($finalResult));
			if ($finalResult === false)
			{
				$runResultsBufferComponent = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\RunResultsBuffer($this->getReport());
				$output .= $runResultsBufferComponent->getHtml();
			}

			$messagesComponent = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\Messages($this->getReport());
			$output .= $messagesComponent->getHtml();

			$output .= '</li>' . $this->getNewline();
		}

		if (!$this->getReport()->getOwner()->getParent())
			$output .= '</ol>' . $this->getNewline();

		return $output;
	}

	protected function getScriptForResultUpdate($specUid, $resultLabel)
	{
		// TODO заменить на периодическую проверку по коду в <head>
		return
			'<script type="text/javascript">' .
				'updateResult("' . $specUid . '", "' . $resultLabel . '", "' . $resultLabel . '");' .
			'</script>';
	}

	protected function getSpecName()
	{
		$parent = $this->getReport()->getOwner()->getParent();
		$name = $this->getReport()->getOwner()->getName();

		if ($name == '' && $parent && $parent instanceof \net\mkharitonov\spectrum\core\SpecContainerArgumentsProviderInterface)
			return $this->getAdditionalArgumentsDumpOut();
		else
			return $name;
	}

	protected function getAdditionalArgumentsDumpOut()
	{
		$output = '';
		foreach ($this->getReport()->getOwner()->getAdditionalArguments() as $arg)
			$output .= $arg . ', ';

		return mb_substr($output, 0, -2);
	}

	protected function getSpecLabel()
	{
		if ($this->getReport()->getOwner() instanceof SpecContainerDescribeInterface)
			return 'describe';
		else if ($this->getReport()->getOwner() instanceof SpecContainerContextInterface)
			return 'context';
		else if ($this->getReport()->getOwner() instanceof SpecItemItInterface)
			return 'it';
		else if ($this->getReport()->getOwner() instanceof SpecContainerInterface)
			return 'container';
		else if ($this->getReport()->getOwner() instanceof SpecItemInterface)
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
}