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
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class SpecContainerPatternTest extends SpecContainerTest
{
	protected $currentSpecClass = '\net\mkharitonov\spectrum\core\SpecContainerPattern';

	/**
	 * @var SpecContainerArgumentsProvider
	 */
	private $spec;
	protected function setUp()
	{
		parent::setUp();
		$this->spec = new SpecContainerPattern();
	}

/**/

	public function testGetUidInContext_RunningState_ShouldBeReturnUidWithRunningContextId()
	{
		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Context
			->Describe
			->Describe
			->->Context
			->->Context
			->->' . $this->currentSpecClass . '(spec)
			->->->It(it)
		');

		$specs['it']->setTestCallback(function() use(&$uids, $specs){
			$uids[] = $specs['spec']->getUidInContext();
		});

		$specs['spec']->run();

		$this->assertSame(array(
			'spec_0_3_2_context_0_0',
			'spec_0_3_2_context_0_1',
			'spec_0_3_2_context_1_0',
			'spec_0_3_2_context_1_1',
		), $uids);
	}

/**/

	public function testSetArguments_ShouldBeThrowExceptionIfNotAllowSpecsModifyWhenRunning()
	{
		Config::setAllowSpecsModifyWhenRunning(false);
		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[0]->errorHandling->setCatchExceptions(false);
		$specs[1]->setTestCallback(function() use($specs){
			$specs[0]->setArguments('foo');
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', 'Modify specs when running deny', function() use($specs){
			$specs[0]->run();
		});
	}
}