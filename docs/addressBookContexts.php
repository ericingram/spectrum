<?php
beforeEach(function($w){
    $w->addressBook = new AddressBook();
});

context('Database storage', function(){
beforeEach(function($w){ $w->addressBook->setCacheSql(false); });

context('MySQL', function(){
beforeEach(function($w){ $w->addressBook->setDataStorage('mysql'); });
});

context('Oracle', function(){
beforeEach(function($w){ $w->addressBook->setDataStorage('oracle'); });
});
});

context('Data storage "files"', function(){
    beforeEach(function($w){ $w->addressBook->setDataStorage('files'); });
});