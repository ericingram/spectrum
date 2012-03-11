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

namespace net\mkharitonov\spectrum\reports\widgets\runResultsBuffer\details;
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
		$output .= '<div class="g-runResultsBuffer-details g-runResultsBuffer-details-matcherCall">';
		$output .= $this->getHtmlForMatcherCall($details);
		$output .= '<div class="returnValue"><span class="title" title="Matcher return value">Return:</span> ' . $this->codeWidget->getHtmlForVariable($details->getMatcherReturnValue()) . '</div>';
		$output .= '<div class="thrownException"><span class="title" title="Matcher thrown exception">Thrown:</span> ' . $this->codeWidget->getHtmlForVariable($details->getException()) . '</div>';
		$output .= '</div>';
		return $output;
	}

	protected function getHtmlForMatcherCall(MatcherCallDetailsInterface $details)
	{
		$output = '';

		$output .= '<div class="matcherCall">';
		$output .= $this->codeWidget->getHtmlForMethod('be', array($details->getActualValue()));

		if ($details->getIsNot())
		{
			$output .= $this->codeWidget->getHtmlForOperator('->');
			$output .= $this->codeWidget->getHtmlForPropertyAccess('not');
		}

		$output .= $this->codeWidget->getHtmlForOperator('->');
		$output .= $this->codeWidget->getHtmlForMethod($details->getMatcherName(), $details->getMatcherArgs());
		$output .= '</div>';

		return $output;
	}
}