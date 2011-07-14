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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */

require_once dirname(__FILE__) . '/../application/init.php';

class Spaceship
{
	private $location = 'space';
	private $task = 'kill_people';

	public function getLocation()
	{
		return $this->location;
	}

	public function setLocation($location)
	{
		$this->location = $location;
	}

	public function setTask($foo)
	{
		$this->task = $foo;
	}

	public function getTask()
	{
		return $this->task;
	}
}

describe('Spaceship', function(){
	it('Should be in space', function(){
		$spaceship = new Spaceship();
		actual($spaceship->getLocation())->beEq('space');
	});

	it('Should be busy', function(){
		$spaceship = new Spaceship();
		actual($spaceship->getTask())->not->beEq('foo');
	});
});

\net\mkharitonov\spectrum\RootDescribe::run();
