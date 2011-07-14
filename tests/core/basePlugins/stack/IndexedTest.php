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

namespace net\mkharitonov\spectrum\core\basePlugins\stack;
require_once dirname(__FILE__) . '/../../../init.php';

/**
 * @see \net\mkharitonov\spectrum\core\basePlugins\stack\named\getCascadeThroughRunningContexts\Test
 */
class IndexedTest extends Test
{
	public function testAdd_ShouldBeAddValuesWithNumbersInConsecutiveOrder()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');

		$plugin->add('foo');
		$this->assertSame(array(0 => 'foo'), $plugin->getAll());

		$plugin->add('bar');
		$this->assertSame(array(
			0 => 'foo',
			1 => 'bar',
		), $plugin->getAll());

		$plugin->add('baz');
		$this->assertSame(array(
			0 => 'foo',
			1 => 'bar',
			2 => 'baz',
		), $plugin->getAll());
	}

	public function testAdd_ShouldBeAddValuesToEnd()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');

		$plugin->add('foo');
		$this->assertSame(array('foo'), $plugin->getAll());

		$plugin->add('bar');
		$this->assertSame(array('foo', 'bar'), $plugin->getAll());
		
		$plugin->add('baz');
		$this->assertSame(array('foo', 'bar', 'baz'), $plugin->getAll());
	}

	public function testAdd_ShouldBeReturnAddedValue()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');
		$this->assertEquals('foo', $plugin->add('foo'));
	}
	
/**/

	public function testRemove_ShouldBeRemoveValueWithSameKey()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');

		$plugin->add('foo');
		$plugin->add('bar');
		$plugin->add('baz');

		$plugin->remove(2);
		$this->assertSame(array('foo', 'bar'), $plugin->getAll());

		$plugin->remove(1);
		$this->assertSame(array('foo'), $plugin->getAll());

		$plugin->remove(0);
		$this->assertSame(array(), $plugin->getAll());
	}

	public function testRemove_ShouldBeReturnRemovedValue()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');

		$plugin->add('foo');
		$plugin->add('bar');

		$this->assertEquals('foo', $plugin->remove(0));
	}

/**/

	public function testGet_ShouldBeGetValueWithSameKeyFromSelfIfValueExists()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');

		$plugin->add('foo');
		$plugin->add('bar');
		$plugin->add('baz');

		$this->assertSame('foo', $plugin->get(0));
		$this->assertSame('bar', $plugin->get(1));
		$this->assertSame('baz', $plugin->get(2));
	}

	public function testGet_ShouldNotBeGetValueFromParent()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');
		
		$specs = $this->createSpecsTree('
			Describe
			->Describe
		');

		$specs[0]->testPlugin->add('foo');

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', function() use($specs) {
			$specs[1]->testPlugin->get(0);
		});
	}

	public function testGet_ShouldBeThrowExceptionIfValueNotExists()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');
		$plugin->add('foo');
		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', function() use($plugin) {
			$plugin->get(99);
		});
	}

/**/

	public function testGetCascadeThroughRunningContexts_ShouldBeSearchValueInSelfFirst()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Describe
		');

		$specs[0]->testPlugin->add('foo');
		$specs[1]->testPlugin->add('bar');

		$this->assertSame('bar', $specs[1]->testPlugin->getCascadeThroughRunningContexts(0));
	}

	public function testGetCascadeThroughRunningContexts_ShouldBeSearchValueInParentIfInSelfValueNotExists()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
		');

		$specs[0]->testPlugin->add('foo0');
		$specs[0]->testPlugin->add('foo1');

		$specs[1]->testPlugin->add('bar0');
		$specs[1]->testPlugin->add('bar1');

		$specs[2]->testPlugin->add('baz0');

		$this->assertSame('bar1', $specs[2]->testPlugin->getCascadeThroughRunningContexts(1));
	}

	public function testGetCascadeThroughRunningContexts_ShouldBeSearchValueInAncestorIfInParentValueNotExists()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
		');

		$specs[0]->testPlugin->add('foo0');
		$specs[0]->testPlugin->add('foo1');

		$specs[1]->testPlugin->add('bar0');

		$specs[2]->testPlugin->add('baz0');

		$this->assertSame('foo1', $specs[2]->testPlugin->getCascadeThroughRunningContexts(1));
	}

	public function testGetCascadeThroughRunningContexts_ParentDescribeWithChildContexts_ShouldBeGetValueFromCurrentDeepChildRunningContext()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->Context
			->It
		');

		$specs[0]->testPlugin->add('foo');
		$specs[1]->testPlugin->add('bar');
		$specs[2]->testPlugin->add('baz');
		$specs[3]->testPlugin->add('qux');

		$results = array();
		$specs[4]->setTestCallback(function() use($specs, &$results) {
			$results[] = $specs[4]->testPlugin->getCascadeThroughRunningContexts(0);
		});

		$specs[0]->run();
		$this->assertSame(array(
			'baz',
			'qux',
		), $results);
	}

	public function testGetCascadeThroughRunningContexts_ParentContextWithChildContexts_ShouldBeGetValueFromCurrentDeepChildRunningContext()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Context
			->Context
			->->Context
			->Context
			->It
		');

		$specs[0]->testPlugin->add('foo');
		$specs[1]->testPlugin->add('bar');
		$specs[2]->testPlugin->add('baz');
		$specs[3]->testPlugin->add('qux');

		$results = array();
		$specs[4]->setTestCallback(function() use($specs, &$results) {
			$results[] = $specs[4]->testPlugin->getCascadeThroughRunningContexts(0);
		});

		$specs[0]->run();
		$this->assertSame(array(
			'baz',
			'qux',
		), $results);
	}

	public function testGetCascadeThroughRunningContexts_ParentContext_ShouldNotBeGetValueFromParentSiblingContexts()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Context
			->Context
			->->It
		');

		$specs[0]->testPlugin->add('foo');
		$specs[1]->testPlugin->add('bar');

		$results = array();
		$specs[3]->setTestCallback(function() use($specs, &$results) {
			$results[] = $specs[3]->testPlugin->getCascadeThroughRunningContexts(0);
		});

		$specs[0]->run();
		$this->assertSame(array(
			'foo',
		), $results);
	}

	public function testGetCascadeThroughRunningContexts_ShouldBeThrowExceptionIfValueNotExistsInAllAncestors()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Describe
			->->Describe
		');

		$specs[0]->testPlugin->add('foo0');
		$specs[1]->testPlugin->add('bar0');
		$specs[2]->testPlugin->add('baz0');

		$this->assertThrowException('\net\mkharitonov\spectrum\core\Exception', function() use($specs) {
			$specs[2]->testPlugin->get(1);
		});
	}

/**/

	public function testGetAll_ShouldBeGetAllValuesFromSelf()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');

		$plugin->add('foo');
		$plugin->add('bar');
		$plugin->add('baz');

		$this->assertSame(array('foo', 'bar', 'baz'), $plugin->getAll());
	}

	public function testGetAll_ShouldNotBeGetValuesFromParent()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Describe
		');

		$specs[0]->testPlugin->add('foo');
		$specs[1]->testPlugin->add('bar');

		$this->assertSame(array('bar'), $specs[1]->testPlugin->getAll());
	}

	public function testGetAll_ShouldBeReturnEmptyArrayIfNoValuesByDefault()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');
		$this->assertSame(array(), $plugin->getAll());
	}

	public function testGetAll_ShouldBeReturnEmptyArrayIfNoValuesAfterRemove()
	{
		$plugin = new Indexed(new \net\mkharitonov\spectrum\core\SpecContainerDescribe(), 'testPlugin');

		$plugin->add('foo');
		$plugin->remove(0);
		$this->assertSame(array(), $plugin->getAll());
	}

/**/

	public function testGetAllPrependAncestorsWithRunningContexts_ShouldBeGetValuesFromSelfAndPrependAncestorsValuesAndValuesFromAncestorsChildRunningContexts()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->Context
			->Describe
			->->Context
			->->->Context
			->->It
		');

		$specs[0]->testPlugin->add('0_foo');
		$specs[0]->testPlugin->add('0_bar');

		$specs[1]->testPlugin->add('1_foo');
		$specs[1]->testPlugin->add('1_bar');

		$specs[2]->testPlugin->add('2_foo');
		$specs[2]->testPlugin->add('2_bar');

		$specs[3]->testPlugin->add('3_foo');
		$specs[3]->testPlugin->add('3_bar');

		$specs[4]->testPlugin->add('4_foo');
		$specs[4]->testPlugin->add('4_bar');

		$specs[5]->testPlugin->add('5_foo');
		$specs[5]->testPlugin->add('5_bar');

		$specs[6]->testPlugin->add('6_foo');
		$specs[6]->testPlugin->add('6_bar');

		$specs[7]->testPlugin->add('7_foo');
		$specs[7]->testPlugin->add('7_bar');

		$results = array();
		$specs[7]->setTestCallback(function() use($specs, &$results) {
			$results[] = $specs[7]->testPlugin->getAllPrependAncestorsWithRunningContexts();
		});

		$specs[0]->run();
		$this->assertSame(array(
			array(
				'0_foo', '0_bar',
				'1_foo', '1_bar',
				'2_foo', '2_bar',
				'4_foo', '4_bar',
				'5_foo', '5_bar',
				'6_foo', '6_bar',
				'7_foo', '7_bar',
			),
			array(
				'0_foo', '0_bar',
				'3_foo', '3_bar',
				'4_foo', '4_bar',
				'5_foo', '5_bar',
				'6_foo', '6_bar',
				'7_foo', '7_bar',
			),
		), $results);
	}

	public function testGetAllPrependAncestorsWithRunningContexts_ShouldBeReturnEmptyArrayIfNoValues()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');
		$spec = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
		$this->assertSame(array(), $spec->testPlugin->getAllPrependAncestorsWithRunningContexts());
	}

/**/

	public function testGetAllAppendAncestorsWithRunningContexts_ShouldBeGetValuesFromSelfAndAppendAncestorsValuesAndValuesFromAncestorsChildRunningContexts()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');

		$specs = $this->createSpecsTree('
			Describe
			->Context
			->->Context
			->Context
			->Describe
			->->Context
			->->->Context
			->->It
		');

		$specs[0]->testPlugin->add('0_foo');
		$specs[0]->testPlugin->add('0_bar');

		$specs[1]->testPlugin->add('1_foo');
		$specs[1]->testPlugin->add('1_bar');

		$specs[2]->testPlugin->add('2_foo');
		$specs[2]->testPlugin->add('2_bar');

		$specs[3]->testPlugin->add('3_foo');
		$specs[3]->testPlugin->add('3_bar');

		$specs[4]->testPlugin->add('4_foo');
		$specs[4]->testPlugin->add('4_bar');

		$specs[5]->testPlugin->add('5_foo');
		$specs[5]->testPlugin->add('5_bar');

		$specs[6]->testPlugin->add('6_foo');
		$specs[6]->testPlugin->add('6_bar');

		$specs[7]->testPlugin->add('7_foo');
		$specs[7]->testPlugin->add('7_bar');

		$results = array();
		$specs[7]->setTestCallback(function() use($specs, &$results) {
			$results[] = $specs[7]->testPlugin->getAllAppendAncestorsWithRunningContexts();
		});

		$specs[0]->run();
		$this->assertSame(array(
			array(
				'7_foo', '7_bar',
				'6_foo', '6_bar',
				'5_foo', '5_bar',
				'4_foo', '4_bar',
				'2_foo', '2_bar',
				'1_foo', '1_bar',
				'0_foo', '0_bar',
			),
			array(
				'7_foo', '7_bar',
				'6_foo', '6_bar',
				'5_foo', '5_bar',
				'4_foo', '4_bar',
				'3_foo', '3_bar',
				'0_foo', '0_bar',
			),
		), $results);
	}

	public function testGetAllAppendAncestorsWithRunningContexts_ShouldBeReturnEmptyArrayIfNoValues()
	{
		\net\mkharitonov\spectrum\core\PluginsManager::registerPlugin('testPlugin', '\net\mkharitonov\spectrum\core\basePlugins\stack\Indexed', 'whenCallOnce');
		$spec = new \net\mkharitonov\spectrum\core\SpecContainerDescribe();
		$this->assertSame(array(), $spec->testPlugin->getAllAppendAncestorsWithRunningContexts());
	}
}