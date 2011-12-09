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

require_once dirname(__FILE__) . '/CodeFormatter.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title></title>
    <style type="text/css">
        body { font-family: Verdana, sans-serif; font-size: 0.75em; }

		h1 { font-family: "Times New Roman", serif; font-size: 1.5em; }
		code { font-size: 1.3em; }

		.notice { padding-left: 6px; border-left: 3px double #ccc; font-style: italic; }

		.example {  }
		.example h1 { margin-bottom: 0.4em; font-family: Verdana, sans-serif; font-size: 1em; }

		.example code { display: block; padding: 0.5em; background: #eee; font-family: Consolas, "Andale Mono WT", "Andale Mono", "Lucida Console", Monaco, "Courier New", Courier, monospace; font-size: 13px; white-space: pre; }
		.example code .tab { display: inline-block; overflow: hidden; width: 3em; }

		.example .result { margin-top: 1em; }
		.example .result iframe { width: 100%; height: 200px; background: #eee; }
    </style>
</head>
<body>

<?php
function printExample($name, $code, $settings = array())
{
	print '<div class="example">' . "\r\n";

	if ($name != '')
		print '<h1>' . htmlspecialchars($name) . ':</h1>' . "\r\n";

	printCode($code);

	if (!in_array('noResult', $settings))
		printResult($code, $settings);

	print '</div>' . "\r\n";
}

function printCode($code)
{
	$codeFormatter = new CodeFormatter();
	$codeFormatter->setHighlightCode(true);
	$codeFormatter->setHighlightSpaces(false);
	$codeFormatter->setRemoveLeadingTabColumns(true);
	$codeFormatter->setWrapTapsToTag(true);
	$codeFormatter->setTabWrapperCssClass('tab');
	$codeFormatter->setIndentionType('tabs');

	print '<code>';
	print $codeFormatter->format($code);
	print '</code>' . "\r\n";
}

function printResult($code, $settings = array())
{
	print '<div class="result">' . "\r\n";
	print '<h1>Результат:</h1>' . "\r\n";

	$height = @$settings['height'];
	unset($settings['height']);
	if ($height)
		$style = "height: {$height}px;";
	else
		$style = '';

	$cacheFilename = generateCacheFilename();

	if ($_SERVER['SERVER_NAME'] == 'spectrum.local' && $_SERVER['SERVER_ADDR'] == '127.0.0.1')
	{
		$url = 'runner.php?code=' . urlencode($code) . '&' . implode('&', $settings);
		$resultPage = file_get_contents('http://spectrum.local/docs/' . $url);
		file_put_contents(dirname(__FILE__) . '/' . $cacheFilename, $resultPage);
	}

	print '<iframe src="' . htmlspecialchars($cacheFilename) . '" frameborder="0" style="' . $style . '"></iframe>' . "\r\n";
	print '</div>' . "\r\n";
}

function createCacheResult($code, $settings = array())
{
	static $lastCacheId;

}

function generateCacheFilename()
{
	static $lastCacheId;
	$lastCacheId = (int) $lastCacheId + 1;
	return "cache/result_$lastCacheId.html";
}
?>

<h1>Spectrum — PHP фреймворк для BDD тестирования (alpha версия)</h1>

<ol>
	<li><a href="#base">Базовая структура</a></li>
	<li><a href="#data-providers">Дата провайдеры</a></li>
	<li><a href="#asserts">Утверждения и матчеры</a></li>
	<li><a href="#worlds">Миры (фикстуры)</a></li>
	<li><a href="#contexts">Контексты</a></li>
	<li><a href="#anonymous-containers">Анонимные контейнеры</a></li>
	<li><a href="#run">Запуск</a></li>
	<li><a href="#run-result">Результат выполнения</a></li>
	<li><a href="#disable">Включение/отключение узлов</a></li>
	<li><a href="#error-handling">Обработка ошибок</a></li>
	<li><a href="#reports">Отчеты</a></li>
	<li><a href="#construction-commands">Команды конструирования</a></li>
	<li><a href="#plugins">Плагины</a></li>
	<li><a href="#plugin-events">События</a></li>
	<li><a href="#todo">Планируется</a></li>
	<li><a href="#contacts">Обратная связь</a></li>
</ol>

<p>Spectrum — это PHP фреймворк, предназначенный для так называемых specification тестов (аналог RSpec и т.п.) и предоставляющий довольно
богатые возможности по настройке и расширению.</p>

<p>Скачать исходные коды можно с <a href="https://github.com/mkharitonov/spectrum/">github.com/mkharitonov/spectrum/</a></p>

<p class="notice">* Все примеры и сама статья пока проверялись только в браузерах Chrome и Firefox.</p>

<div class="example">
	<?php
	printExample('', <<<'CODE'
		<?php
		require_once 'spectrum/application/init.php';

		describe('Космический корабль', function(){
			it('Должен бороздить просторы вселенной', function(){
				$spaceship = new Spaceship();
				actual($spaceship->getLocation())->beEq('space');
			});

			it('Не должен прохлаждаться', function(){
				$spaceship = new Spaceship();
				actual($spaceship->getTask())->not->beEq('foo');
			});
		});

		\net\mkharitonov\spectrum\RootDescribe::run();
CODE
, array('noRun', 'height' => 100));
	?>
</div>

<h1 id="base">Базовая структура</h1>

<p>Для написание спецификаций Spectrum предоставляет несколько различных <a href="#construction-commands">команд конструирования</a>. Базовая древовидная
структура создается с помощью команд конструирования it(), describe() и context() (про контексты см. в нижеследующем разделе), притом ни глубина ни кол-во команд никак не ограничено.</p>

<?php
printExample('Пример вложенной структуры', <<<'CODE'
	describe('Космический корабль', function(){
		it('Должен бороздить просторы вселенной', function(){ actual(true)->beTrue(); });

		describe('Боевое оснащение', function(){
			it('Должены быть установлены огромные пушки', function(){ actual(true)->beTrue(); });
			it('Должены быть установлены крутые лезеры', function(){ actual(true)->beTrue(); });
		});

		describe('Каюта капитана', function(){
			it('Должена быть оборудована ванной типа «Джакузи»', function(){ actual(true)->beTrue(); });
			it('Должена убираться каждый день', function(){ actual(true)->beTrue(); });

			describe('Спальное место', function(){
				it('Должено быть застелено шелковыми простынями', function(){ actual(true)->beTrue(); });
				it('Должено вмещать не меньше двух человек', function(){ actual(true)->beTrue(); });
			});
		});
	});
CODE
, array('height' => 220));
?>

<h1 id="data-providers">Поставщики данных</h1>

<p>Бывает, что сами данные сами по себе являются лучшим описанием требуемого поведения. Для таких случает существуют поставщики
данных (data providers).</p>

<?php
printExample('Пример поставщиков данных', <<<'CODE'
	describe('Форма ввода телефона', function(){
		it('Должена принимать различные форматы тефонных номеров',
		array('123-456-7', '+7 (495) 123-456-7', '(495) 123-456-7'),
		function($world, $tel){
			if (preg_match('/\+/', $tel))
				actual(false)->beTrue();
			else
				actual(true)->beTrue();
		});

		// Если требуется передать несколько аргументов, то элемент поставщика данных должен сам быть массивом
		it('Должена принимать различные форматы тефонных номеров', array(
			'foo',
			array('bar', 'bar2'),
			'baz'
		), function($world, $arg1, $arg2 = null){
			actual(true)->beTrue(); // do something
		});

		// Так же поставщик данных может быть функцией, которая должна будет вернуть массив
		it('Должена принимать различные форматы тефонных номеров',
		function(){ return array('foo', array('bar', 'bar2'), 'baz'); },
		function($world, $arg1, $arg2 = null){
			actual(true)->beTrue(); // do something
		});
	});
CODE
, array('height' => 240));
?>

<h1 id="asserts">Утверждения и матчеры</h1>
<p>Утверждения создаются с помодью команды конструирования <code>actual()</code>, которая возвращает объект класса
<code>core/Assert</code>, обращаясь к которому можно вызывать различные матчеры (вызовы методов перехватываются и перенаправляются
к требуему матчеру).</p>

<p>Либо можно обратиться к специальному свойству <code>not</code> (опять же не реальному), которое
инвертирует результат последующего вызова матчера.</p>

<?php
printExample('Пример различных вызовов матчеров', <<<'CODE'
	it('Должен', function(){
		actual(true)->beTrue();
		actual(true)->not->beFalse();

		// Так же можно записывать несколько матчеров в одной строке (обратите внимание
		// что "not" действует только на первый из последующих матчеров)
		actual(true)->not->beFalse()->beTrue();
	});
CODE
, array('height' => 50));
?>

<p>Вы так же можете добавлять собственные матчеры с помощью команды конструирования <code>addMatcher($name, $callback)</code>.
Callback функции будет передано актуальное значение первым параметром и все агрументы, переданные матчеру при вызове, последующими параметрами.</p>

<?php
printExample('Пример callback функций матчеров', <<<'CODE'
	describe('', function(){
		addMatcher('beFoo', function($actual){
			return ($actual == 'foo');
		});

		it('Должен', function(){
			actual('foo')->beFoo();
			actual('bar')->not->beFoo();
		});

		// Матчер с дополнительными параметрами
		addMatcher('beSomething', function($actual, $expected, $elseArg){
			// $actual - foo
			// $expected - bar
			// $elseArg - baz

			return false;
		});

		it('Должен еще', function(){
			actual('foo')->beSomething('bar', 'baz');
		});
	});
CODE
, array('height' => 60));
?>

<p>С помощью команды <code>addMatcher($name, $callback)</code> можно добавить матчер в любой it/describe/context и дочерние
команды конструирования будут вызывать матчеры из своих родителей/предков (в случае объявления матчеров в контекстах,
поиск матчера будет осуществляться в стеке выполняющихся контекстов, см. подробности в главе про контексты ниже).</p>

<?php
printExample('Пример матчеров во вложенных структурах', <<<'CODE'
	describe('Пример добавления', function(){
		addMatcher('beFoo', function(){
			return true;
		});

		it('Должен', function(){
			actual(true)->beFoo(); // Из родителя
		});

		describe('Второй', function(){
			it('Должен', function(){
				actual(true)->beFoo(); // Из предка (т.е. оттуда же, что и предыдущий)
			});
		});
	});

	describe('Пример переопределения', function(){
		addMatcher('beFoo', function(){
			return true;
		});
		
		it('Должен', function(){
			actual(true)->beFoo();
		});

		describe('Со своей версией beFoo', function(){
			addMatcher('beFoo', function(){
				return false;
			});

			it('Должен', function(){
				actual(true)->beFoo();
			});
		});
	});
CODE
, array('height' => 160));
?>

<p>Spectrum содержит некоторое кол-во стандартных матчеров, которые уже добавлены в RootDescribe.</p>

<?php
printExample('Пример переопределения стандартных матчеров', <<<'CODE'
	describe('', function(){
		it('Должен', function(){
			actual(null)->beNull();
			actual(true)->beTrue();
			actual(1)->not->beTrue();
			actual(false)->beFalse();
			actual(0)->not->beFalse();

			actual('foo')->beEq('foo');
			actual(new Spaceship())->not->beIdent(new Spaceship());

			actual(5)->beLt(10); // Less than
			actual(10)->beLtOrEq(10); // Less than or equal
			actual(10)->beGt(5); // Greater than
			actual(10)->beGtOrEq(10); // Greater than or equal

			actual(function(){
				throw new Exception();
			})->beThrow();
			
			actual(function(){
				throw new ErrorException();
			})->beThrow('\ErrorException');

			actual(function(){
				throw new ErrorException('Foo is not bar', 123);
			})->beThrow('\ErrorException', 'foo', 123);
			
			actual(function(){
				throw new Exception('Foo is not bar');
			})->beThrow(null, 'foo');
		});

		it('Должен', function(){
			actual(function(){
				throw new Exception();
			})->beThrow('\ErrorException');
		});

		it('Должен', function(){
			actual(function(){
				throw new ErrorException();
			})->beThrow('\ErrorException', 'foo');
		});

		// Использование мира в beThrow()
		it('Должен', function($world){
			actual(function() use($world){
				$world->foo = 'bar';
			})->not->beThrow();
		});
	});
CODE
, array('height' => 100));
?>

<p>Поскольку все стандартные матчеры содержатся в RootDescribe, их можно с легкостью переопределить.</p>

<?php
printExample('Пример переопределения стандартных матчеров', <<<'CODE'
	addMatcher('beTrue', function($actual){
		return ($actual !== true);
	});

	describe('', function(){
		it('Должен', function(){
			actual(true)->beTrue();
		});
	});
CODE
, array('height' => 50));
?>

<h1 id="worlds">Миры (фикстуры)</h1>

<p>Т.к. природа BDD тестов предполагает детальное описание поведения, во многих случаях код смежных it() будет дублироваться.
Что бы избежать этого, в Spectrum'е существуют миры (worlds), которые создаются при помощи команд конструирования
beforeEach($callback) и afterEach($callback) и в которые следует выносить общие для всех it() части.</p>

<p class="notice">Пару слов о терминологии. У каждого it() есть свой мир (world), к которому могут применяться творцы мира
(world creators): beforEach() — строитель (builder) и afterEach() — разрушитель (destroyer).</p>

<?php
printExample('Пример создания мира', <<<'CODE'
	describe('Космический корабль', function(){
		beforeEach(function($world){
			// World является простым объектом без каких-либо свойств, поэтому можно «наживую» создавать
			// в нем любые свойства, а так же просматривать все его свойства в цикле foreach
			$world->spaceship = new Spaceship();
			$world->spaceship->startSystems();
		});

		afterEach(function($world){
			$world->spaceship->stopSystems();
		});

		it('Должен бороздить просторы вселенной', function($world){
			actual($world->spaceship->getLocation())->beEq('space');
		});

		it('Не должен прохлаждаться', function($world){
			actual($world->spaceship->getTask())->not->beEq('foo');
		});
	});
CODE
, array('height' => 80));
?>

<p>Так же, как и в случае с матчерами, творцы применяются к миру всех своих потомков и у любой группы можно создать
дополнительных творцов для постройки и разрушения.</p>

<?php
printExample('Пример создания мира', <<<'CODE'
	describe('Космический корабль', function(){
		beforeEach(function($world){
			$world->spaceship = new Spaceship();
			$world->spaceship->startSystems();
		});

		afterEach(function($world){
			$world->spaceship->stopSystems();
		});

		it('Должен бороздить просторы вселенной', function($world){
			actual($world->spaceship->getLocation())->beEq('space');
		});

		describe('Межпространственный полет', function(){
			beforeEach(function($world){
				$world->spaceship->startEngine();
			});

			afterEach(function($world){
				$world->spaceship->stopEngine();
			});

			it('Должен облетать звёзды', function($world){
				new Star(25, 50, 100);
				$world->spaceship->setDestination(30, 50, 100);
				actual($world->spaceship->isHasCollision())->beFalse();
			});
		});
	});
CODE
, array('height' => 100));
?>

<p>Порядок применения творцов к мирам дочерних узлов — во внутрь. Т.к. Сначала строители родителя, потом строители
ребенка, затем разрушители ребенка, потом разрушители родителя.</p>

<p>И опять, так же, как и в случае с матчерами, можно объявлять своих творцов миров в каждом контексте. См. главу про контексты.</p>


<h1 id="contexts">Контексты</h1>

<p>Суть контекстов можно продемонстрировать на примере, когда требуется одни и те же it() выполнить в разных мирах (тобишь при разных внешних условиях).</p>

<?php
printExample('Пример без использования контекстов', <<<'CODE'
	describe('Космический корабль', function(){
		describe('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});

			it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
			it('Должен собирать неизвестные ископаемые', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
			it('Должен защищать слабых и обездоленных', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		});

		describe('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});

			// Копипастим
			it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
			it('Должен собирать неизвестные ископаемые', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
			it('Должен защищать слабых и обездоленных', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		});

		describe('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('rest'); // Отличается
			});

			// Снова копипастим
			it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
			it('Должен собирать неизвестные ископаемые', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
			it('Должен защищать слабых и обездоленных', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		});
	});
CODE
, array('height' => 240));
?>

<p>С помощью контекстов можно избавить себя от скушных copy/paste.</p>

<?php
printExample('Пример с использованием контекстов', <<<'CODE'
	describe('Космический корабль', function(){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		context('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});
		
		context('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('rest');
			});
		});

		it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		it('Должен собирать неизвестные ископаемые', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		it('Должен защищать слабых и обездоленных', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
	});
CODE
, array('height' => 240));
?>

<p>Работают контексты согласно следующим правилам:</p>
<ol>
	<li>Если у describe() есть context() дети (не потомки), то выполняются только эти контексты;</li>
	<li>Если у context() есть context() дети (не потомки), то так же выполняются только эти контексты;</li>
	<li>Если у context() нет context() детей, то данный контекст запускает выполнение все describe/it до ближайшего describe предка (т.е. сначала запускает describe/it детей ближайшего describe предка, затем describe/it детей промежуточных контекстов, а затем своих детей);</li>
</ol>


<p>В частности, это позволяет использовать вложенные контексты для еще большего устранения дублирований в творцах:</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		// Используем анонимный контекст
		context(function(){
			beforeEach(function($world){
				// Выносим общий код создания экземпляра класса
				$world->spaceship = new Spaceship();
			});

			context('В галактике Альфа Центавра', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
			});

			context('В галактике Хоага', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
			});

			context('В галактике Мейола', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('rest');
				});
			});
		});

		it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		it('Должен собирать неизвестные ископаемые', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		it('Должен защищать слабых и обездоленных', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
	});
CODE
, array('height' => 240));
?>

<p>Устранять дублирования не только в двумерных describe():</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		context(function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
			});

			context('В галактиках', function(){
				context('В галактике Альфа Центавра', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('study');
					});
				});

				context('В галактике Хоага', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('study');
					});
				});

				context('В галактике Мейола', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('rest');
					});
				});
			});

			context('На планетах', function(){
				context('На планете Земля', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('study');
					});
				});

				context('На планете Марс', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('rest');
					});
				});
			});
		});

		it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
	});
CODE
, array('height' => 240));
?>


<p>Добавлять при необходимости в какой-либо контекст дополнительные спецификации (как it(), так и describe(), который в свою очередь может содержать любое кол-во потомков, включая контексты):</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		context(function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
			});

			context('В галактике Альфа Центавра', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
				
				it('Должен собирать неизвестные ископаемые', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
				it('Должен защищать слабых и обездоленных', function($world){ actual($world->spaceship->getTask())->beEq('rest'); });
			});

			context('В галактике Хоага', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
			});

			context('В галактике Мейола', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('rest');
				});
			});
		});

		it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
	});
CODE
);
?>

<p>Создавать многоуровневые контексты:</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		// В данном случае анонимный контекст можно опустить, разместив творцов на
		// одном уровне с контекстами, к которым он должен применяться
		beforeEach(function($world){
			$world->spaceship = new Spaceship();
		});

		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship->setTask('study');
			});
		});

		context('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship->setTask('study');
			});
		});

		context('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship->setTask('study');
			});
		});

		describe('Миссия', function(){
			context('В космосе', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
			});

			context('На планете', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('rest');
				});
			});

			it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		});
	});
CODE
, array('height' => 350));
?>

<p>Вы так же можете объявлять свои матчеры в каждом контексте:</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		context(function(){
			context('В галактике Альфа Центавра', function(){
				addMatcher('beFoo', function($actual){
					return ($actual == 'foo');
				});
			});

			context('В галактике Хоага', function(){
				addMatcher('beFoo', function($actual){
					return ($actual == 'foo');
				});
			});

			context('В галактике Мейола', function(){
				addMatcher('beFoo', function($actual){
					return ($actual == 'bar');
				});
			});
		});

		it('Должен быть как Foo', function(){ actual('foo')->beFoo(); });
	});
CODE
, array('height' => 160));
?>

<h1 id="anonymous-containers">Анонимные контейнеры</h1>

<p>Команды конструирования describe() и context() могут принимать callback функцию в качестве единственого аргумента (либо можно задать пустую строку в качестве имени).
В таком случае речь идет об анонимном контейнере, имена которых будут исключаться из отчетов, но которые по прежнему
можно использовать для физической организации.</p>

<?php
printExample('Пример анонимного describe', <<<'CODE'
	describe('Космический корабль', function(){
		it('Должен (из именного контекнера)', function(){ actual(true)->beTrue(); });

		describe(function(){
			// Тут, например, можно добавить творцов миров (или матчеры), которые
			// будут применяться только к детям и потомкам данного describe
			it('Должен (из анониимного контейнера)', function(){ actual(true)->beTrue(); });
			it('Должен (из анониимного контейнера)', function(){ actual(true)->beTrue(); });
		});
	});
CODE
, array('height' => 100));
?>

<h1 id="run">Запуск</h1>

<p>Запускать можно не только корневой describe, но и любой другой узел, ссылку на которым можно получить с помощью
<a href="#plugins">плагина</a> selector, начиная поиск от RootDescribe, либо присвоив результат работы it/describe/context
переменной.</p>

<p class="notice">Все корневые узлы добавляются в класс RootDescribe, который сам по себе является анонимным describe (точнее,
это статический класс, у которого есть метод getInstance(), возвращающий экземпляр класса core\SpecContainerDescribe),
поэтому вызов метода RootDescribe->getInstance()->run() (RootDescribe::run() просто удобный алиас) ведет себя аналогично
другим узлам.</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){ actual(true)->beTrue(); });
		it('Должен собирать неизвестные ископаемые', function(){ actual(true)->beTrue(); });
		it('Должен защищать слабых и обездоленных', function(){ actual(true)->beTrue(); });
	});

	$spec->run(); // В данном случае аналогично RootDescribe::run()
CODE
, array('noRun', 'height' => 100));
?>

<p>Можно запустить только дочерние узлы.</p>
<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){ actual(true)->beTrue(); });
		it('Должен собирать неизвестные ископаемые', function(){ actual(true)->beTrue(); });
		it('Должен защищать слабых и обездоленных', function(){ actual(true)->beTrue(); });
	});

	$spec->selector->getChildByIndex(1)->run(); // Нумерация начинается с нуля
CODE
, array('noRun', 'height' => 60));
?>

<p>Можно так же передать ссылку на переменную в замыкание, куда и сохранить результат.</p>
<?php
printExample('', <<<'CODE'

	describe('Космический корабль', function() use(&$spec){
		it('Должен изучать живые организмы', function(){ actual(true)->beTrue(); });
		$spec = it('Должен собирать неизвестные ископаемые', function(){ actual(true)->beTrue(); });
		it('Должен защищать слабых и обездоленных', function(){ actual(true)->beTrue(); });
	});

	$spec->run();
CODE
, array('noRun', 'height' => 60));
?>

<p>Можно запускать целые describe.</p>
<?php
printExample('', <<<'CODE'

	describe('Космический корабль', function() use(&$spec){
		it('Должен', function(){ actual(true)->beTrue(); });

		$spec = describe('Миссия', function(){
			it('Должен изучать живые организмы', function(){ actual(true)->beTrue(); });
			it('Должен собирать неизвестные ископаемые', function(){ actual(true)->beTrue(); });
			it('Должен защищать слабых и обездоленных', function(){ actual(true)->beTrue(); });
		});
	});

	$spec->run();
CODE
, array('noRun', 'height' => 120));
?>

<p>Если есть контексты, узел будет запущен во всех из них.</p>
<?php
printExample('', <<<'CODE'

	describe('Космический корабль', function() use(&$spec){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('rest');
			});
		});

		context('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		context('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		$spec = it('Должен собирать неизвестные ископаемые', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		it('Должен защищать слабых и обездоленных', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
	});

	$spec->run();
CODE
, array('noRun', 'height' => 160));
?>

<p>Запуск узла в многоуровневом контексте так же возможен.</p>
<?php
printExample('', <<<'CODE'

	context('Некий контекст', function(){});
	context('Еще один контекст', function(){});

	describe('Космический корабль', function() use(&$spec){
		context('В галактике Альфа Центавра', function(){});
		context('В галактике Хоага', function(){});
		context('В галактике Мейола', function(){});

		it('Должен изучать живые организмы', function($world){ actual(true)->beTrue(); });
		$spec = it('Должен собирать неизвестные ископаемые', function($world){ actual(true)->beTrue(); });
		it('Должен защищать слабых и обездоленных', function($world){ actual(true)->beTrue(); });
	});

	$spec->run();
CODE
, array('noRun', 'height' => 300));
?>

<p>Можно так же запустить и все узлы какого-либо контекста.</p>
<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function() use(&$spec){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('rest');
			});
		});

		context('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		$spec = context('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		it('Должен изучать живые организмы', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		it('Должен собирать неизвестные ископаемые', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
		it('Должен защищать слабых и обездоленных', function($world){ actual($world->spaceship->getTask())->beEq('study'); });
	});

	$spec->run();
CODE
, array('noRun', 'height' => 100));
?>


<h1 id="run-result">Результат выполнения</h1>

<p>Помимо отображения результата в отчете, метод run() возвращает результат запуска.</p>
<ol>
	<li>false — тест (и или один из дочерних тестов) не пройден</li>
	<li>true — тест (и тесты всех дочерних узлов) пройден (нет непройденных и пустых тестов)</li>
	<li>null — тест (либо тест одного из дочерних узлов) не содержит ни одного положительного утверждения или ошибки</li>
</ol>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){ actual(true)->beTrue(); });
		it('Должен собирать неизвестные ископаемые', function(){ actual(false)->beTrue(); });
		it('Должен защищать слабых и обездоленных', function(){  });

		describe('Миссия', function(){
			it('Должен изучать живые организмы', function(){ actual(true)->beTrue(); });
			it('Должен служить людям', function(){});
		});
	});

	$spec->run();
CODE
, array('noRun', 'height' => 160));
?>

<h1 id="disable">Включение/отключение узлов</h1>

<p>Если требуется запуск не только одного, а нескольких узлов из многих, то ненужные узлы можно предварительно отключить,
вызывая методы <code>disable()</code> и <code>enable()</code> узлов.</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function($world){ actual(true)->beTrue(); });
		it('Должен собирать неизвестные ископаемые', function($world){ actual(true)->beTrue(); });
		it('Должен защищать слабых и обездоленных', function($world){ actual(true)->beTrue(); });
		it('Должен служить людям', function($world){ actual(true)->beTrue(); });
	});

	$spec->selector->getChildByIndex(0)->disable();
	$spec->selector->getChildByIndex(2)->disable();
	$spec->run();
CODE
, array('noRun', 'height' => 80));
?>

<p>А так, например, можно использовать анонивные контекнеры для удобного отключения группы узлов.</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		describe(function(){
			it('Должен изучать живые организмы', function($world){ actual(true)->beTrue(); });
			it('Должен собирать неизвестные ископаемые', function($world){ actual(true)->beTrue(); });
		});

		it('Должен защищать слабых и обездоленных', function($world){ actual(true)->beTrue(); });
		it('Должен служить людям', function($world){ actual(true)->beTrue(); });
	});

	$spec->selector->getChildByIndex(0)->disable();
	$spec->run();
CODE
, array('noRun', 'height' => 80));
?>

<p>Можно так же отключить и контексты.</p>
<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function() use(&$spec, &$context){
		context('В галактике Альфа Центавра', function(){});
		context('В галактике Хоага', function(){});
		$context = context('В галактике Мейола', function(){});

		it('Должен изучать живые организмы', function($world){ actual(true)->beTrue(); });
		$spec = it('Должен собирать неизвестные ископаемые', function($world){ actual(true)->beTrue(); });
		it('Должен защищать слабых и обездоленных', function($world){ actual(true)->beTrue(); });
	});

	$context->disable();
	$spec->run();
CODE
, array('noRun', 'height' => 100));
?>

<p>Основная идеология, которой придерживается Spectrum при отключении узлов — отключение не должно изменять
поведение тестов (только результаты). В следующем примере, отключение всех контекстов не заставит Spectrum
выполнять it узлы describe (хотя именно такое поведение распространяется на describe без дочерних контекстов). В
данном случае это просто привело бы к ошибке, т.к. it ожидает наличие переменной $world->spaceship</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
			});
		});

		it('Должен изучать живые организмы', function($world){ actual($world->spaceship)->beEq(new Spaceship()); });
	});

	$spec->selector->getChildByIndex(0)->disable();
	$spec->run();
CODE
, array('noRun', 'height' => 50));
?>

<p>Тем не менее, можно изменить объектную структуру узлов прямо перед запуском путем их удаления.</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
			});
		});

		it('Должен изучать живые организмы', function($world){
			if (isset($world->spaceship))
				actual($world->spaceship)->beEq(new Spaceship());
			else
				actual(true)->beFalse();
		});
	});

	$spec->selector->getChildByIndex(0)->removeFromParent();
	$spec->run();
CODE
, array('noRun', 'height' => 70));
?>

<h1 id="error-handling">Обработка ошибок</h1>

<p>Spectrum позволяет управлять обработкой ошибок на уровне каждого из узлов с помощью следующих методов плагина errorHandling:</p>
<ol>
	<li>$spec->errorHandling->setCatchExceptions(true|false) — перехватывать исключения, выбрасываемые тестами и добавлять их в результирующий буффер</li>
	<li>$spec->errorHandling->setCatchPhpErrors(true|false|errorLevel) — перехватывать ошибки (те, которые может отловить set_error_handler()), генерируемые тестами и добавлять их в результирующий буффер</li>
	<li>$spec->errorHandling->setBreakOnFirstPhpError(false) — прерывать выполнение теста при первой php ошибке или нет</li>
	<li>$spec->errorHandling->setBreakOnFirstMatcherFail(false) — прерывать выполнение теста при первом провальном утверждении (матчере) или нет</li>
</ol>

<p class="notice">Результирующий буффер — это экземпляр класса core\RunResultsBuffer, который создается при каждом выполнении it и в который
заносятся результаты всех утверждения и ошибок. В этот буфер можно добавить свои результаты, например, из <a href="#plugins">плагинов</a> и просмотреть его содержимое в любой момент.</p>

<p>По умолчанию, перехватываются все исключения и ошибки php и отключено прерывание выполнения при php ошибке и провальном матчере.</p>

<p>Все эти параметры можно задавать для конкретных it/describe/context.</p>

<h1 id="reports">Отчеты</h1>

<p>Отчет об ошибках включается/выключается в плагине liveReport. По умолчанию, включены. Отчет об ошибках можно включать/отключать для конкретных it/describe/context</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){
			actual('foo')->beEq('foo');
			actual('foo')->beEq('bar');
		});
	});

	$spec->run();
CODE
, array('noRun', 'enableDebug', 'height' => 300));
?>


<h1 id="construction-commands">Команды конструирования</h1>

<p>Spectrum спроектирован тиким образом, что команды конструирования полностью отделены от объектной структуры.
Например, следующие два примера дадут одинаковый результат.</p>

<?php
printExample('', <<<'CODE'
	use \net\mkharitonov\spectrum\RootDescribe;

	describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){
			actual(true)->beTrue();
		});
	});

	RootDescribe::run();
CODE
, array('noRun', 'height' => 70));
?>

<p>То же, но без использования команд конструирования:</p>

<?php
printExample('', <<<'CODE'
	use \net\mkharitonov\spectrum\RootDescribe;
	use \net\mkharitonov\spectrum\core\SpecItemIt;
	use \net\mkharitonov\spectrum\core\asserts\Assert;
	use \net\mkharitonov\spectrum\core\SpecContainerDescribe;

	$it = new SpecItemIt('Должен изучать живые организмы');
	$it->setTestCallback(function(){
		$assert = new Assert(true);
		$assert->beTrue();
	});

	$describe = new SpecContainerDescribe('Космический корабль');
	$describe->addSpec($it);

	RootDescribe::getInstance()->addSpec($describe);
	RootDescribe::run();
CODE
, array('noRun', 'height' => 70));
?>

Команды конструирования делятся на два этапа:
<ol>
	<li>Обьявление (declaring)</li>
	<li>Выполнение (running)</li>
</ol>

<p>На этапе обьявления вызываются callback функции таких команд, как describe и context.
Callback ф-я it вызывается на этапе выполнения (т.е. после вызова метода run()).</p>

<p>Помимо прочего, это означает, что доступ к объектной структуре можно получить до запуска и, например,
сформировать список спецификаций без выполнения запуска.</p>

<p>Управление командами конструирования возложено на класс constructionCommands\Manager,
который позволяет регистрировать новые или переопределять текущие команды.</p>

<?php
printExample('', <<<'CODE'
	use \net\mkharitonov\spectrum\constructionCommands\Manager;

	Manager::registerCommand('crash', function(){
		Manager::getCurrentItem()->getRunResultsBuffer()->addResult(false, 'Космический корабль разбился');
	});

	it('Полет космического корабля', function(){
		Manager::crash();
	});

	// А можно создать глобальный алиас
	Manager::createGlobalAliasOnce('crash');
	it('Полет космического корабля', function(){
		crash();
	});
CODE
, array('enableDebug', 'height' => 250));
?>

<p>Команды конструирования можно вызывать как угодно и откуда угодно.</p>

<p>Можно генерировать их динамически:</p>
<?php
printExample('', <<<'CODE'
	for ($i = 0; $i < 5; $i++)
	{
		it("Должен $i", function() use($i){
			actual($i)->beLt(2);
		});
	}
CODE
, array('height' => 100));
?>

<p>А можно и подключать файлы, содержащие команды конструирования:</p>
<?php
printExample('', <<<'CODE'

	// specs.php
	// it('Должен изучать живые организмы', function(){ actual(true)->beTrue(); });

	describe('Космический корабль', function(){
		include('specs.php');
	});
CODE
, array('height' => 100));
?>

<h1 id="plugins">Плагины</h1>

<p>Если структура объявления может расширяться за счет команд конструирования, то структура объектной структуры может
расширяться за счет плагинов. Основой объектной структуры Spectrum являются следующие классы узлов:</p>

<ol>
	<li>Spec</li>
	<li>SpecContainer</li>
	<li>SpecContainerDescribe</li>
	<li>SpecContainerContext</li>
	<li>SpecContainerDataProvider</li>
	<li>SpecItem</li>
	<li>SpecItemIt</li>
</ol>

<p>Плагины представляют собой объекты соответствующих классов (зарегистрированных через мереджер плагинов),
которые создаются для каждого экземпляра Spec классов в объектной структуре.</p>

<p>В нижеследующем примере, у каждого из трех узлов (экземпляров классов SpecContainerDescribe, SpecItemIt и, опять же,
SpecItemIt) существует свой экземпляр плагина foo.</p>
<?php
printExample('', <<<'CODE'
	\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('foo');
	$describe = describe('Космический корабль', function() use(&$it1, &$it2){
		$it1 = it('Должен летать', function(){});
		$it2 = it('Не должен плавать', function(){});
	});

	$describe->foo->add('foo');
	$it1->foo->add('bar');
	$it2->foo->add('baz');

	print '<pre>';
	print_r($describe->foo->getAll());
	print_r($it1->foo->getAll());
	print_r($it2->foo->getAll());
CODE
, array('noRun', 'height' => 220));
?>

<p>Методу Manager::registerPlugin() принимает 3 параметра:</p>
<ol>
	<li>Имя для доступа к плагину (foo в примере выше);</li>
	<li>Класс плагина, который должен реализовывать интерфейс core\plugin\PluginInterface (по умолчанию — это базовый плагин core\plugins\basePlugins\stack\Indexed);</li>
	<li>Момент создание экземпляра (активации) плагина:
		<ol>
			<li>whenConstructOnce — экземпляр плагина будет создан во время создания соответствующего экземпляра узла;</li>
			<li>whenCallOnce (используется по умолчанию) — экземпляр плагина будет создан только при первом обращении к нему, а при повторном обращении будет возвращаться ранее созданный экземпляр;</li>
			<li>whenCallAlways — при каждом обращении будет создаваться (и возвращаться) новый экземпляр плагина.</li>
		</ol>
	</li>
</ol>

<p>Создаваемый класс плагина не обязательно реализовавыть с нуля. Можно унаследовать его от класса core\plugin\Plugin (который, в частности,
содержит полезный метод callCascadeThroughRunningContexts(), позволяющий получить значение вызова функции через стек запущенных контекстов).</p>

<?php
printExample('', <<<'CODE'

	use \net\mkharitonov\spectrum\core\plugins\Manager;
	use \net\mkharitonov\spectrum\constructionCommands\Manager;

	class MyPlugin extends \net\mkharitonov\spectrum\core\plugins\Plugin
	{
		private $foo = null; // Значение по умолчанию должно задаваться не здесь, а при вызове callCascadeThroughRunningContexts()

		public function setFoo($foo)
		{
			$this->foo = $foo;
		}

		public function getFoo()
		{
			return $this->foo;
		}

		public function getFooCascade()
		{
			return $this->callCascadeThroughRunningContexts(
				'getFoo', // Вызываемый метод
				array(), // С аргументами
				'foo', // Значение по умолчанию (возвращаемое, если требуемое значение не найдено вплоть до самого верхнего уровня)
				null // Если getFoo возвратит идентичное значение, то поиск будет продолжен выше
			);
		}
	}

	Manager::registerPlugin('foo', 'MyPlugin');

	$spec = describe('Космический корабль', function() use (&$context1, &$context2, &$context3){
		$context1 = context('В галактике Альфа Центавра', function(){});
		$context2 = context('В галактике Хоага', function(){});
		$context3 = context('В галактике Мейола', function(){});

		it('Должен', function(){
			print '<strong>' . Manager::getCurrentItem()->foo->getFooCascade() . '</strong>';
		});
	});

	$context1->foo->setFoo('bar');
	$context2->foo->setFoo('baz');
	// А для $context3 будет возвращего значение по умолчанию

	$spec->run();

CODE
, array('noRun', 'height' => 160));
?>

<p>Так же можно унаследовать плагин от одного из базовых плагинов, самым востребованным из которых, возможно, является
плагин Stack (и конечные классы Indexed и Named), позволяющий работать со стеком значений, в частности, получить значения
через стек запущенных контекстов (именно от данного плагина и унаследованы плагины Matchers и WorldCreators).</p>


<h1 id="plugin-events">События</h1>

<p>Плагин так же может реализовать один или несколько интерфейсов событий. В таком случае, соответствующие методы
данного плагина будут вызваны, когда указанное событие произойдет в соответствующем узле (будут вызваны методы только
экземпляров плагинов, принадлежащих данному узлу).</p>

<p>На текущий момент доступны следующие интерфейсы плагинов (расположенные в core\plugin\events).</p>
<ol>
	<li>OnRunInterface — методы вызываются при запуске в узлах любого типа
		<ol>
			<li>onRunBefore()</li>
			<li>onRunAfter($result)</li>
		</ol>
	</li>
	<li>OnRunContainerInterface — методы вызываются при запуске в узлах контейнерного типа (SpecContainer*)
		<ol>
			<li>onRunContainerBefore()</li>
			<li>onRunContainerAfter($result)</li>
		</ol>
	</li>
	<li>OnRunItemInterface — методы вызываются при запуске в узлах типа SpecItem*
		<ol>
			<li>onRunItemBefore()</li>
			<li>onRunItemAfter($result)</li>
		</ol>
	</li>
	<li>OnTestCallbackCallInterface — методы вызываются непосредственно при выполнении тестовой функции (и могут получать сведения о мире, в отличии от OnRun событий)
		<ol>
			<li>onTestCallbackCallBefore(core\World $world)</li>
			<li>onTestCallbackCallAfter(core\World $world)</li>
		</ol>
	</li>
</ol>

<p>Порядок вызова событий следующий:</p>

<p class="example"><code>onRunBefore
<span class="tab">	</span>onRunContainerBefore или onRunItemBefore
<span class="tab">	</span><span class="tab">	</span>Применение строителей к миру
<span class="tab">	</span><span class="tab">	</span><span class="tab">	</span>onTestCallbackCallBefore
<span class="tab">	</span><span class="tab">	</span><span class="tab">	</span><span class="tab">	</span>Вызов testCallback
<span class="tab">	</span><span class="tab">	</span><span class="tab">	</span>onTestCallbackCallAfter
<span class="tab">	</span><span class="tab">	</span>Применение разрушителей к миру
<span class="tab">	</span>onRunContainerAfter или onRunItemAfter
onRunAfter
</code></p>

<h1 id="todo">Планируется</h1>

<p>В ближайшем будущем пранируется реализовать следующие нововведения:</p>
<ol>
	<li>
		<p>Изменить синтаксис утверждений на более минималистический:</p>
<?php
printExample('', <<<'CODE'
	be('actual')->true();
	be('actual')->not->null();
	be('actual')->fileExists();
CODE
, array('noResult'));
?>

	</li>

	<li><p>Добавить образцы. Что-то вроде:</p>

<?php
printExample('', <<<'CODE'
	addExample('Автомобиль', function($doorsCount){
		return it("Должен иметь $doorsCount дверей", function(){});
	});
	
	itLikeExample('Автомобиль', 4);

CODE
, array('noResult'));
?>
	</li>

	<li><p>Добавить возможность задавать параметры обработки ошибок через команды конструирования it/describe/context:</p>

<?php
printExample('', <<<'CODE'
	it('Должен', function(){}, E_ALL);
	
	it('Должен', function(){}, array(
		'catchExceptions' => true,
		'breakOnFirstMatcherFail' => true,
		// и т.п.
	));

CODE
, array('noResult'));
?>
	</li>

	<li><p>Добавить дополнительных творцов: beforeAll(), afterAll() и т.п.</p></li>

	<li><p>Доработать отчеты</p></li>
</ol>

<h1 id="contacts">Обратная связь</h1>
Предложения и пожелания можно оставлять <a href="http://phpclub.ru/talk/threads/spectrum-%E2%80%94-php-%D1%84%D1%80%D0%B5%D0%B9%D0%BC%D0%B2%D0%BE%D1%80%D0%BA-%D0%B4%D0%BB%D1%8F-bdd-%D1%82%D0%B5%D1%81%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F-alpha-%D0%B2%D0%B5%D1%80%D1%81%D0%B8%D1%8F.68706/">в теме на форуме PHPClub</a>
или отправлять мне на <a href="mailto:m.v.kharitonov@gmail.com">e-mail</a>.

</body>
</html>