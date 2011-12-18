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

class AddressBook
{
	private $person = array(
		'name' => 'Bob',
		'firstName' => 'Bob',
		'lastName' => 'Smith',
		'phoneNumber' => '+74951234567',
	);
	public function setDataStorage($dataStorage) {}
	public function setCacheSql($enable) {}

	public function findPersonByName($name) { return $this->person; }
	public function findPersonByFirstName($name) { return $this->person; }
	public function findPersonByLastName($name) { return $this->person; }
	public function findPersonByPhoneNumber($phoneNumber) { return $this->person; }
}

describe('AddressBook', function(){
	include __DIR__ . '/addressBookContexts.php';

describe('Search person', function(){
it('Should find person by first name', function($w){
$person = $w->addressBook->findPersonByFirstName('Bob');
be($person['firstName'])->eq('Bob');
});

it('Should find person by last name', function($w){
$person = $w->addressBook->findPersonByLastName('Smith');
be($person['lastName'])->eq('Smith');
});
    });
});

\net\mkharitonov\spectrum\RootDescribe::run();
