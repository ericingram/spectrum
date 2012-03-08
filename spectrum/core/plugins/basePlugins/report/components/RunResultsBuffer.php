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
use \net\mkharitonov\spectrum\core\asserts\MatcherCallDetailsInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class RunResultsBuffer extends \net\mkharitonov\spectrum\core\plugins\basePlugins\report\Component
{
	protected $codeComponent;

	public function __construct(\net\mkharitonov\spectrum\core\plugins\basePlugins\report\Report $report)
	{
		parent::__construct($report);
		$this->codeComponent = new \net\mkharitonov\spectrum\core\plugins\basePlugins\report\components\code\Code($this->getReport());
	}

	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer { position: relative; margin: 0.5em 0 1em 0; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>h1 { float: left; margin-bottom: 2px; padding: 0.3em 0.5em 0 0.5em; color: #888; font-size: 0.9em; font-weight: normal; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results { clear: both; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result { float: left; position: relative; margin: 0 2px 2px 0; border: 1px solid; border-left: 0; border-top: 0; border-radius: 5px; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.num { float: left; margin-right: 2px; padding: 2px 5px; border-radius: 4px 0 4px 0; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.value { float: left; padding: 2px 5px; border-radius: 0 0 4px 4px; font-size: 0.9em; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.expand { display: block; position: absolute; right: 0; bottom: 0; padding: 2px 5px; border-radius: 4px 0 4px 0; font-size: 0.9em; font-weight: bold; text-decoration: none; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.details { clear: both; padding: 7px; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result>.details .title { font-weight: bold; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true { border-color: #b5dfb5; background: #ccffcc; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true>.num { background: #b5dfb5; color: #3a473a; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true>.value { background: #b5dfb5; color: #3a473a; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.true>.expand { background: #85cc8c; color: #e4ffe0; }' . $this->getNewline() .

				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false { border-color: #e2b5b5; background: #ffcccc; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false>.num { background: #e2b5b5; color: #3d3232; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false>.value { background: #e2b5b5; color: #3d3232; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer>.results>.result.false>.expand { background: #db9a9a; color: #ffe3db; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml()
	{
		if (!($this->getReport()->getOwner() instanceof SpecItemInterface))
			return null;

		$output = '';

		$output .= '<div class="g-runResultsBuffer g-clearfix">' . $this->getNewline();
		$output .= $this->getIndention() . '<h1>Run results buffer contains:</h1>' . $this->getNewline();
		$output .= $this->getIndention() . '<div class="results">' . $this->getNewline();
		$num = 0;
		foreach ($this->getReport()->getOwner()->getRunResultsBuffer()->getResults() as $result)
		{
			$num++;
			$output .= $this->getIndention(2) . '<div class="result ' . ($result['result'] ? 'true' : 'false') . '">' . $this->getNewline();
			$output .= $this->getIndention(3) . '<div class="num" title="Order in run results buffer">No. ' . $num . '</div>' . $this->getNewline();
			$output .= $this->getIndention(3) . '<div class="value" title="Result">' . ($result['result'] ? 'true' : 'false') . '</div>' . $this->getNewline();
			$output .= $this->getIndention(3) . '<a href="#" class="expand" title="Show full details">+</a>' . $this->getNewline();
			$output .= $this->prependIndentionToEachLine($this->trimNewline($this->getHtmlForResultDetails($result['details'])), 3) . $this->getNewline();
			$output .= $this->getIndention(2) . '</div>' . $this->getNewline();
		}

		$output .= $this->getIndention() . '</div>' . $this->getNewline();

		$output .= '</div>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForResultDetails($details)
	{
		if (is_object($details) && $details instanceof MatcherCallDetailsInterface)
			return $this->getHtmlForResultDetailsForMatcherCall($details);
		else
			return $this->getHtmlForResultDetailsForOther($details);
	}

	protected function getHtmlForResultDetailsForMatcherCall(MatcherCallDetailsInterface $details)
	{
		$output = '';

		// TODO добавить больше свободного пространства вокруг вызова матчера
		$output .= '<div class="details matcherCall">' . $this->getNewline();

		$output .= $this->getIndention() . $this->codeComponent->getHtmlForMethod('be', array($details->getActualValue()));

		if ($details->getIsNot())
		{
			$output .= $this->codeComponent->getHtmlForOperator('->');
			$output .= $this->codeComponent->getHtmlForPropertyAccess('not');
		}

		$output .= $this->codeComponent->getHtmlForOperator('->');
		$output .= $this->codeComponent->getHtmlForMethod($details->getMatcherName(), $details->getMatcherArgs());

		$output .= $this->getNewline();
		$output .= $this->getIndention() . '<div class="returnValue"><span class="title" title="Matcher return value">Return:</span> ' . $this->codeComponent->getHtmlForVariable($details->getMatcherReturnValue()) . '</div>' . $this->getNewline();
		$output .= $this->getIndention() . '<div class="returnValue"><span class="title" title="Matcher thrown exception">Thrown:</span> ' . $this->codeComponent->getHtmlForVariable($details->getException()) . '</div>' . $this->getNewline();
		$output .= '</div>' . $this->getNewline();

		return $output;
	}

	protected function getHtmlForResultDetailsForOther($details)
	{
		return
			'<div class="details other">' . $this->getNewline() .
				$this->getIndention() . $this->codeComponent->getHtmlForVariable($details) . $this->getNewline() .
			'</div>' . $this->getNewline();
	}
}