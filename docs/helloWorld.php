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

require_once dirname(__FILE__) . '/../spectrum/init.php';

class Person
{
	public $firstName = 'Bob';
	public $lastName = 'Smith';
	public $phoneNumber = '+74951234567';
}

class AddressBook
{
	public function setDataStorage($dataStorage) {}
	public function setCacheSql($enable) {}

	public function findPerson($searchString) { return new Person(); }
}



describe('AddressBook', function(){
	addPattern('Автомобить', function($doorsCount){
		describe('sadf', function() use($doorsCount){
			it('Кол-во дверей должно быть ' . $doorsCount, function($w) use($doorsCount){
				be(4)->eq($doorsCount);
			});
		});
	});

	itLikePattern('Автомобить', 3);
	itLikePattern('Автомобить', 4);
});

\net\mkharitonov\spectrum\RootDescribe::run();