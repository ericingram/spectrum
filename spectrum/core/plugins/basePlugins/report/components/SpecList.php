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
	static protected $depth;

	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-specList { padding-left: 1.8em; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.name { }' . $this->getNewline() .

				$this->getIndention() . '.g-specList>li>.finalResult { color: #ccc; font-weight: bold; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.finalResult.fail { color: #a31010; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.finalResult.success { color: #009900; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.finalResult.empty { color: #cc9900; }' . $this->getNewline() .

				$this->getIndention() . '.g-specList>li.it { }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.describe { }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.context { }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">' . $this->getNewline() .
				$this->getIndention() . 'function updateResult(uid, resultLabel, resultName){' . $this->getNewline() .
					$this->getIndention(2) . 'var spec = document.getElementById(uid);' . $this->getNewline() .
					$this->getIndention(2) . 'var result = spec.childNodes[5];' . $this->getNewline() .
					$this->getIndention(2) . 'result.className += " " + resultLabel;' . $this->getNewline() .
					$this->getIndention(2) . 'result.innerHTML = resultName;' . $this->getNewline() .
				$this->getIndention() . '}' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtmlBegin()
	{
		$output = '';

		if (!$this->getReport()->getOwner()->getParent())
		{
			static::$depth = 0;
			$output .= $this->getIndention(static::$depth + 1) . '<ol class="g-specList">' . $this->getNewline();
		}

		if (!$this->getReport()->getOwner()->isAnonymous())
		{
			$output .= $this->getIndention(static::$depth * 2 + 2) . '<li class="' . $this->getSpecLabel() . '" id="' . $this->getReport()->getOwner()->selector->getUidForSpec() . '">' . $this->getNewline();
			$output .= $this->getIndention(static::$depth * 2 + 3) . '<span class="name">' . htmlspecialchars($this->getSpecName()) . '</span>' . $this->getNewline();
			$output .= $this->getIndention(static::$depth * 2 + 3) . '<span class="separator"> — </span>' . $this->getNewline();
			$output .= $this->getIndention(static::$depth * 2 + 3) . '<span class="finalResult">wait...</span>' . $this->getNewline();

			if ($this->getReport()->getOwner() instanceof SpecContainerInterface || !$this->getReport()->getOwner()->getParent())
			{
				$output .= $this->getIndention(static::$depth * 2 + 3) . '<ol class="g-specList">' . $this->getNewline();
				static::$depth++;
			}
		}

		return $output;
	}

	public function getHtmlEnd($finalResult)
	{
		$output = '';

		if (!$this->getReport()->getOwner()->isAnonymous())
		{
			if ($this->getReport()->getOwner() instanceof SpecContainerInterface)
			{
				static::$depth--;
				$output .= $this->getIndention(static::$depth * 2 + 3) . '</ol>' . $this->getNewline();
			}

			$output .= $this->getScriptForResultUpdate($this->getReport()->getOwner()->selector->getUidForSpec(), $this->getSpecResultLabel($finalResult)) . $this->getNewline();
			if ($finalResult === false)
			{
				$runResultsBufferComponent = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\RunResultsBuffer($this->getReport());
				$output .= $this->prependIndentionToEachLine($this->trimNewline($runResultsBufferComponent->getHtml()), static::$depth * 2 + 3) . $this->getNewline();
			}

			$messagesComponent = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\Messages($this->getReport());
			$output .= $this->prependIndentionToEachLine($this->trimNewline($messagesComponent->getHtml()), static::$depth * 2 + 3) . $this->getNewline();

			$output .= $this->getIndention(static::$depth * 2 + 2) . '</li>' . $this->getNewline();
		}

		if (!$this->getReport()->getOwner()->getParent())
			$output .= $this->getIndention(static::$depth + 1) . '</ol>' . $this->getNewline();

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