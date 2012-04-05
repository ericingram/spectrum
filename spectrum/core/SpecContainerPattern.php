<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

class SpecContainerPattern extends SpecContainer implements SpecContainerPatternInterface
{
	protected $arguments = array();

	public function setArguments($arguments)
	{
		$this->handleSpecModifyDeny();
		$this->arguments = $arguments;
	}

	public function getArguments()
	{
		return $this->arguments;
	}
}