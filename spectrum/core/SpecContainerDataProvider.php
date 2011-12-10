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
class SpecContainerDataProvider extends SpecContainer implements SpecContainerDataProviderInterface
{
	public function addSpec(SpecInterface $spec)
	{
		if (!($spec instanceof SpecItemItInterface))
			throw new Exception('SpecContainerDataProvider::addSpec() can accept only SpecItemItInterface instances');

		parent::addSpec($spec);
	}

	public function getSpecsToRun()
	{
		return $this->getSpecs();
	}
}