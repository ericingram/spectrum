###Spectrum

**Current version is alpha and maybe not stable.**

Documentation (on Russian): http://mkharitonov.net/spectrum/

###Example:
    <?php
    require_once 'spectrum/application/init.php';

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

###Copyright
Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>.

See LICENSE.txt for details.