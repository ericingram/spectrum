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

namespace net\mkharitonov\spectrum\reports\widgets;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecList extends \net\mkharitonov\spectrum\reports\widgets\Widget
{
	static protected $depth;
	static protected $numbers = array();

	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-specList { list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList .g-specList { padding-left: 25px; list-style: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li>.indention { display: inline-block; width: 0; white-space: pre; }' . $this->getNewline() .

				$this->getIndention() . '.g-specList>li.it { }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.describe { }' . $this->getNewline() .
				$this->getIndention() . '.g-specList>li.context { }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtmlBegin()
	{
		$output = '';

		if (!$this->getOwnerPlugin()->getOwnerSpec()->getParent())
		{
			static::$depth = 0;
			$output .= $this->getIndention($this->getSpecDepth() + 1) . '<ol class="g-specList">' . $this->getNewline();
		}

		if (!$this->getOwnerPlugin()->getOwnerSpec()->isAnonymous())
		{
			@static::$numbers[static::$depth]++;

			$output .= $this->getIndention($this->getSpecDepth() * 2 + 2) . '<li class="' . $this->getSpecCssClass() . '" id="' . $this->getOwnerPlugin()->getOwnerSpec()->selector->getUidForSpec() . '">' . $this->getNewline();
			$output .= $this->getHtmlForCurrentSpecIndention() . $this->getHtmlForSpecNumber() . $this->getNewline();
			$output .= $this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('specTitle')->getHtml(), $this->getSpecDepth() * 2 + 3) . $this->getNewline();

			if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerInterface || !$this->getOwnerPlugin()->getOwnerSpec()->getParent())
			{
				$output .= $this->getIndention($this->getSpecDepth() * 2 + 3) . '<ol class="g-specList">' . $this->getNewline();
				static::$depth++;
			}
		}

		return $output;
	}

	public function getHtmlEnd($finalResult)
	{
		$output = '';

		if (!$this->getOwnerPlugin()->getOwnerSpec()->isAnonymous())
		{
			if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerInterface)
			{
				static::$numbers[static::$depth] = 0;
				static::$depth--;
				$output .= $this->getIndention($this->getSpecDepth() * 2 + 3) . '</ol>' . $this->getNewline();
			}

			$output .= $this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('finalResult')->getHtml($finalResult), $this->getSpecDepth() * 2 + 3) . $this->getNewline();
			$output .= $this->getIndention($this->getSpecDepth() * 2 + 3) . $this->trimNewline($this->getRunDetails($finalResult)) . $this->getNewline();

			$output .= $this->getIndention($this->getSpecDepth() * 2 + 2) . '</li>' . $this->getNewline();
		}

		if (!$this->getOwnerPlugin()->getOwnerSpec()->getParent())
			$output .= $this->getIndention($this->getSpecDepth() + 1) . '</ol>' . $this->getNewline();

		return $output;
	}

	public function getSpecDepth()
	{
		return static::$depth;
	}

	public function getSpecNumber()
	{
		return @static::$numbers[static::$depth];
	}

	protected function getHtmlForSpecNumber()
	{
		return '<span class="number">' . htmlspecialchars($this->getSpecNumber()) . '. </span>';
	}

	protected function getHtmlForCurrentSpecIndention()
	{
		return $this->getIndention($this->getSpecDepth() * 2 + 3) . str_repeat('<span class="indention">' . $this->getIndention() . '</span>', $this->getSpecDepth());
	}

	protected function getRunDetails($finalResult)
	{
		$output = '';

		if ($finalResult === false)
			$output .= $this->getOwnerPlugin()->createWidget('runResultsBuffer')->getHtml() . $this->getNewline();

		$output .= $this->prependIndentionToEachLine($this->getOwnerPlugin()->createWidget('messages')->getHtml(), $this->getSpecDepth() * 2 + 3) . $this->getNewline();

		if (trim($output) != '')
			$output = '<div class="runDetails">' . $output . '</div>';

		return $output;
	}

	protected function getSpecCssClass()
	{
		if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerDescribeInterface)
			return 'describe';
		else if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerContextInterface)
			return 'context';
		else if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecItemItInterface)
			return 'it';
		else if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecContainerInterface)
			return 'container';
		else if ($this->getOwnerPlugin()->getOwnerSpec() instanceof SpecItemInterface)
			return 'item';
		else
			return 'spec';
	}
}