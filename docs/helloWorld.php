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
	require(__DIR__ . '/addressBookContexts.php');

	describe('Search person', function(){
		it('Should find person by first name', function($w){
			be($w->addressBook->findPerson('Bob')->firstName)->eq('Bob');
		});

		it('Should find person by last name', function($w){
			be($w->addressBook->findPerson('Bob')->lastName)->eq('Smith');
		});
	});
});

\net\mkharitonov\spectrum\RootDescribe::run();