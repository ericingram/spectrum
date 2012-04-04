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

namespace spectrum\reports\widgets\finalResult;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Update extends \spectrum\reports\widgets\Widget
{
	public function getStyles()
	{
		return
			'<style type="text/css">' . $this->getNewline() .
				$this->getIndention() . '.g-finalResult-update { display: none; }' . $this->getNewline() .
			'</style>' . $this->getNewline();
	}

	public function getScripts()
	{
		return
			'<script type="text/javascript">
				(function(){
					finalResult = window.finalResult || {};
					finalResult.update = function()
					{
						var finalResult = getFinalResult();
						var resultNodes = document.querySelectorAll(".g-finalResult-result[data-specUid=\'" + finalResult.specUid + "\']");
						for (var i = 0; i < resultNodes.length; i++)
						{
							var resultNode = resultNodes[i];
							resultNode.className += " " + finalResult.resultAlias;
							resultNode.innerHTML = finalResult.resultTitle;
						}
					}

					function getFinalResult()
					{
						var finalResultNode = getExecutedScriptNode();
						while (finalResultNode.className != "g-finalResult-update")
							finalResultNode = finalResultNode.parentNode;

						return {
							specUid: finalResultNode.getAttribute("data-specUid"),
							resultAlias: finalResultNode.getAttribute("data-resultAlias"),
							resultTitle: finalResultNode.getAttribute("data-resultTitle")
						};
					}

					function getExecutedScriptNode()
					{
						var scripts = document.getElementsByTagName("script");
						return scripts[scripts.length - 1];
					}
				})();' . $this->getNewline() .
			'</script>' . $this->getNewline();
	}

	public function getHtml($finalResult)
	{
		$resultAlias = $this->getFinalResultAlias($finalResult);
		return
			'<span class="g-finalResult-update"
				data-specUid="' . htmlspecialchars($this->getOwnerPlugin()->getOwnerSpec()->identify->getSpecId()) . '"
				data-resultAlias="' . htmlspecialchars($resultAlias) . '"
				data-resultTitle="' . $this->translate($resultAlias) . '">' . $this->getNewline() .

				$this->getIndention() . '<script type="text/javascript">finalResult.update();</script>' . $this->getNewline() .
			'</span>';
	}

	protected function getFinalResultAlias($result)
	{
		if ($result === false)
			$alias = 'fail';
		else if ($result === true)
			$alias = 'success';
		else
			$alias = 'empty';

		return $alias;
	}
}