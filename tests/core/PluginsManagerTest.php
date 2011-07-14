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
class PluginsManagerTest extends Test
{
	public function setUp()
	{
		parent::setUp();
		PluginsManager::unregisterAllPlugins();
	}

	public function testShouldBeHaveRegisteredBasePluginsByDefault()
	{
		PluginsManager::registerPlugins($this->oldPlugins);

		$this->assertSame(array(
			'matchers' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\Matchers', 'activateMoment' => 'whenCallOnce'),
			'builders' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\worldCreators\Builders', 'activateMoment' => 'whenCallOnce'),
			'destroyers' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\worldCreators\Destroyers', 'activateMoment' => 'whenCallOnce'),
//			'report' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\report\Report', 'activateMoment' => 'whenCallOnce'),
			'selector' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\Selector', 'activateMoment' => 'whenCallOnce'),
			'errorHandling' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\ErrorHandling', 'activateMoment' => 'whenCallOnce'),
		), PluginsManager::getRegisteredPlugins());
	}

/**/

	public function testRegisterPlugin_ShouldBeCollectPlugins()
	{
		$this->assertSame(array(), PluginsManager::getRegisteredPlugins());

		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin', 'whenConstructOnce');
		$this->assertSame(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugin\Plugin', 'activateMoment' => 'whenConstructOnce'),
		), PluginsManager::getRegisteredPlugins());

		PluginsManager::registerPlugin('bar', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');
		$this->assertSame(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugin\Plugin', 'activateMoment' => 'whenConstructOnce'),
			'bar' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
		), PluginsManager::getRegisteredPlugins());

		PluginsManager::registerPlugin('baz', '\net\mkharitonov\spectrum\core\basePlugins\Matchers', 'whenCallAlways');
		$this->assertSame(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugin\Plugin', 'activateMoment' => 'whenConstructOnce'),
			'bar' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
			'baz' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\Matchers', 'activateMoment' => 'whenCallAlways'),
		), PluginsManager::getRegisteredPlugins());
	}

	public function testRegisterPlugin_ShouldBeReplaceExistsPlugin()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin', 'whenCallAlways');
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenConstructOnce');

		$this->assertSame(
			array('foo' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'activateMoment' => 'whenConstructOnce'))
			, PluginsManager::getRegisteredPlugins()
		);
	}

	public function testRegisterPlugin_ShouldBeSetStackIndexedClassAndWhenCallOnceActivatedMomentByDefault()
	{
		PluginsManager::registerPlugin('foo');

		$this->assertSame(
			array('foo' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'))
			, PluginsManager::getRegisteredPlugins()
		);
	}

	public function testRegisterPlugin_ShouldThrowExceptionIfPluginNotImplementInterface()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', function() {
			PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\NotPlugin');
		});
	}

	public function testRegisterPlugin_ShouldThrowExceptionIfSetIncorrectActivateMoment()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', function() {
			PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin', 'foo');
		});
	}

	public function testRegisterPlugin_ShouldAcceptAllowedActivateMoments()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin', 'whenConstructOnce');
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin', 'whenCallOnce');
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin', 'whenCallAlways');
	}

	public function testRegisterPlugins_ShouldBeSubstituteDefaultClassAndActivateMoment()
	{
		PluginsManager::registerPlugins(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugin\Plugin', 'activateMoment' => 'whenCallAlways'),
			'bar' => array('class' => '\net\mkharitonov\spectrum\core\plugin\Plugin'),
			'baz' => array(),
			'qux' => null,
		));

		$this->assertSame(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugin\Plugin', 'activateMoment' => 'whenCallAlways'),
			'bar' => array('class' => '\net\mkharitonov\spectrum\core\plugin\Plugin', 'activateMoment' => 'whenCallOnce'),
			'baz' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
			'qux' => array('class' => '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
		), PluginsManager::getRegisteredPlugins());
	}

/**/

	public function testUnregisterPlugin()
	{
		PluginsManager::registerPlugin('foo');
		PluginsManager::unregisterPlugin('foo');

		$this->assertFalse(PluginsManager::hasRegisteredPlugin('foo'));
		$this->assertSame(array(), PluginsManager::getRegisteredPlugins());
	}

/**/

	public function testUnregisterAllPlugins_ShouldBeLeaveEmptyArray()
	{
		$this->assertSame(array(), PluginsManager::getRegisteredPlugins());
	}

/**/

	public function testHasRegisteredPlugin_ShouldBeReturnTrueIfPluginExists()
	{
		PluginsManager::registerPlugin('foo');
		$this->assertTrue(PluginsManager::hasRegisteredPlugin('foo'));
	}

	public function testHasRegisteredPlugin_ShouldBeReturnFalseIfPluginNotExists()
	{
		$this->assertFalse(PluginsManager::hasRegisteredPlugin('foo'));
	}

/**/

	public function testGetAccessNamesForEventPlugins_ShouldBeReturnAllPluginsWhichImplementsEventInterface()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');
		PluginsManager::registerPlugin('bar', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');
		PluginsManager::registerPlugin('baz', '\net\mkharitonov\spectrum\core\plugin\Plugin');

		$this->assertSame(array('foo', 'bar'), PluginsManager::getAccessNamesForEventPlugins('onRunBefore'));
	}

	public function testGetAccessNamesForEventPlugins_ShouldBeThrowExceptionIfEventNameNotExists()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', '"onFooBar"', function() {
			PluginsManager::getAccessNamesForEventPlugins('onFooBar');
		});
	}

/**/

	public function testGetRegisteredPlugin()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin', 'whenCallAlways');

		$this->assertSame(
			array('class' => '\net\mkharitonov\spectrum\core\plugin\Plugin', 'activateMoment' => 'whenCallAlways')
			, PluginsManager::getRegisteredPlugin('foo')
		);
	}

	public function testGetRegisteredPlugin_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', function() {
			PluginsManager::getRegisteredPlugin('foo');
		});
	}

/**/

	public function testCreatePluginInstance_ShouldBeReturnRespectivePluginInstance()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin');
		PluginsManager::registerPlugin('bar', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed');

		$this->assertEquals('\net\mkharitonov\spectrum\core\plugin\Plugin', '\\' . get_class(PluginsManager::createPluginInstance(new SpecContainerDescribe(), 'foo')));
		$this->assertEquals('\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', '\\' . get_class(PluginsManager::createPluginInstance(new SpecContainerDescribe(), 'bar')));
	}

	public function testCreatePluginInstance_ShouldBeSetAccessNameAndOwnerToPluginInstance()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin');

		$spec = new SpecContainerDescribe();
		$plugin = PluginsManager::createPluginInstance($spec, 'foo');

		$this->assertEquals('foo', $plugin->getAccessName());
		$this->assertSame($spec, $plugin->getOwner());
	}

	public function testCreatePluginInstance_ShouldBeReturnNewInstanceAlways()
	{
		PluginsManager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugin\Plugin');
		PluginsManager::registerPlugin('bar', '\net\mkharitonov\spectrum\core\plugin\Plugin');

		$spec = new SpecContainerDescribe();
		$this->assertNotSame(
			PluginsManager::createPluginInstance($spec, 'foo'),
			PluginsManager::createPluginInstance($spec, 'bar')
		);
	}

	public function testCreatePluginInstance_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', function() {
			PluginsManager::createPluginInstance(new SpecContainerDescribe(), 'foo');
		});
	}
}