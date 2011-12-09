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

namespace net\mkharitonov\spectrum\core\specItemIt\errorHandling\catchExceptions;
require_once dirname(__FILE__) . '/../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class DisabledTest extends Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->it->errorHandling->setCatchExceptions(false);
	}

	public function testShouldBeThrowExceptionAbove()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){
			$it->run();
		});
	}

	public function testShouldNotBeCatchPhpErrorException()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			throw new \net\mkharitonov\spectrum\core\ExceptionPhpError('foo');
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\ExceptionPhpError', 'foo', function() use($it){
			$it->run();
		});
	}

	public function testShouldBeRestoreErrorHandler()
	{
		$it = $this->it;
		$it->errorHandling->setCatchPhpErrors(true);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		set_error_handler('trim');
		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertEquals('trim', $this->getErrorHandler());
		restore_error_handler();
	}

/*	public function testShouldBeUnsetRunResultsBuffer()
	{
		$it = new SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertNull($it->getRunResultsBuffer());
	}

	public function testShouldBeRestoreRunningInstance()
	{
		$oldRunningInstance = SpecItem::getRunningInstance();

		$it = new SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertSame($oldRunningInstance, SpecItem::getRunningInstance());
	}

	public function testShouldBeStopRun()
	{
		$it = new SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertFalse($it->isRunning());
	}

	public function testShouldBeTriggerEventOnRunAfter()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');

		$it = new SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertEventTriggeredCount(1, 'onRunAfter');

		Manager::unregisterPlugin('foo');
	}*/
}