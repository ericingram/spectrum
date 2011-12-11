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

namespace net\mkharitonov\spectrum\core;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecContainerArgumentsProvider extends SpecContainer implements SpecContainerArgumentsProviderInterface
{
	public function addSpec(SpecInterface $spec)
	{
		// Arguments should be already sets to $spec
		if (!($spec instanceof SpecItemItInterface))
			throw new Exception('SpecContainerArgumentsProvider::addSpec() can accept only SpecItemItInterface instances');

		parent::addSpec($spec);
	}

	public function getSpecsToRun()
	{
		return $this->getSpecs();
	}
}