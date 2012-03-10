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

namespace net\mkharitonov\spectrum\reports\components\runResultsBuffer\details;
use \net\mkharitonov\spectrum\core\asserts\MatcherCallDetailsInterface;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class MatcherCall extends Details
{
	public function getStyles()
	{
		return
			parent::getStyles() . $this->getNewline() .
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-matcherCall>.matcherCall { margin-bottom: 4px; }' . $this->getNewline() .
				$this->getIndention() . '.g-runResultsBuffer-details-matcherCall>div>.title { font-weight: bold; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getHtml(MatcherCallDetailsInterface $details)
	{
		$output = '';
		$output .= '<div class="g-runResultsBuffer-details g-runResultsBuffer-details-matcherCall">' . $this->getNewline();
		$output .= $this->prependIndentionToEachTagOnNewline($this->trimNewline($this->getHtmlForMatcherCall($details))) . $this->getNewline();
		$output .= $this->getIndention() . '<div class="returnValue"><span class="title" title="Matcher return value">Return:</span> ' . $this->codeComponent->getHtmlForVariable($details->getMatcherReturnValue()) . '</div>' . $this->getNewline();
		$output .= $this->getIndention() . '<div class="thrownException"><span class="title" title="Matcher thrown exception">Thrown:</span> ' . $this->codeComponent->getHtmlForVariable($details->getException()) . '</div>' . $this->getNewline();
		$output .= '</div>' . $this->getNewline();
		return $output;
	}

	protected function getHtmlForMatcherCall(MatcherCallDetailsInterface $details)
	{
		$output = '';

		$output .= '<div class="matcherCall">';
		$output .= $this->codeComponent->getHtmlForMethod('be', array($details->getActualValue()));

		if ($details->getIsNot())
		{
			$output .= $this->codeComponent->getHtmlForOperator('->');
			$output .= $this->codeComponent->getHtmlForPropertyAccess('not');
		}

		$output .= $this->codeComponent->getHtmlForOperator('->');
		$output .= $this->codeComponent->getHtmlForMethod($details->getMatcherName(), $details->getMatcherArgs());
		$output .= '</div>' . $this->getNewline();

		return $output;
	}
}