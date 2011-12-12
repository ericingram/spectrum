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
		'phoneNumber' => '+74951234567',
	);
	public function setDataStorage($dataStorage) {}

	public function findPersonByName($name)
	{
		return $this->person;
	}

	public function findPersonByPhoneNumber($phoneNumber)
	{
		return $this->person;
	}
}

describe('AddressBook', function(){
    beforeEach(function($w){
        $w->addressBook = new AddressBook();
    });

    context('Data storage "MySQL"', function(){
        beforeEach(function($w){ $w->addressBook->setDataStorage('mysql'); });
    });

    context('Data storage "Oracle"', function(){
        beforeEach(function($w){ $w->addressBook->setDataStorage('oracle'); });
    });

    context('Data storage "files"', function(){
        beforeEach(function($w){ $w->addressBook->setDataStorage('files'); });
    });

    it('Should find person by name', function($w){
        $person = $w->addressBook->findPersonByName('Bob');
        be($person['name'])->eq('Bob');
    });

    it('Should find person by phone number', array(
    	'+7 (495) 123-456-7',
    	'(495) 123-456-7',
    	'123-456-7',
    ), function($w, $phoneNumber){
        $person = $w->addressBook->findPersonByPhoneNumber($phoneNumber);
        be($person['phoneNumber'])->eq('+74951234567');
    });
});

\net\mkharitonov\spectrum\RootDescribe::run();
