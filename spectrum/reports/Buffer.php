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

namespace net\mkharitonov\spectrum\core\reports;
use \net\mkharitonov\spectrum\core\Exception;
use \net\mkharitonov\spectrum\core\SpecInterface;
use \net\mkharitonov\spectrum\core\SpecContainerInterface;
use \net\mkharitonov\spectrum\core\SpecContainerContextInterface;
use \net\mkharitonov\spectrum\core\SpecContainerDescribeInterface;
use \net\mkharitonov\spectrum\core\SpecItemInterface;
use \net\mkharitonov\spectrum\core\SpecItemItInterface;

use \net\mkharitonov\spectrum\core\plugins\events;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Buffer
{
	protected $outputEncoding = 'utf-8';

	protected $buffers = array();
	protected $parent;
	protected $name;
	protected $hasResult = true;
	protected $result;
	protected $resultBuffer;
	protected $additionalArguments = array();
	protected $sourceSpec;
	protected $isEnabled;
	protected $isAnonymous;

	public function __toString()
	{
		$out = '';
		$out .= $this->getName() . "\r\n";
		foreach ($this->buffers as $resultList)
		{
			$out .= '    ' . rtrim(str_replace("\r\n", "\r\n    ", $resultList->__toString())) . "\r\n";
		}

		return $out;
	}

/**/

	public function setOutputEncoding($outputEncoding)
	{
		if (!$outputEncoding)
			$outputEncoding = 'utf-8';

		$this->outputEncoding = $outputEncoding;
	}

	public function getOutputEncoding()
	{
		return $this->outputEncoding;
	}

	protected function convertToOutputEncoding($string)
	{
		if (is_array($string))
		{
			foreach ($string as $key => $val)
				$string[$key] = $this->convertToOutputEncoding($val);

			return $string;
		}
		else
		{
			if (strtolower($this->outputEncoding) != 'utf-8')
				return iconv('utf-8', $this->outputEncoding, $string);
			else
				return $string;
		}
	}

/**/

	public function addBuffer(Buffer $buffer)
	{
		$this->buffers[] = $buffer;
		$buffer->setParent($this);
	}

	public function addBufferBasicOfSpec(SpecInterface $spec)
	{

	}

	public function getBuffers()
	{
		return $this->buffers;
	}

/**/

	public function setParent(Buffer $buffer = null)
	{
		$this->parent = $buffer;
	}

	public function getParent()
	{
		return $this->parent;
	}

/**/

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

/**/

	public function setAdditionalArguments($additionalArguments)
	{
		$this->additionalArguments = $additionalArguments;
	}

	public function getAdditionalArguments()
	{
		return $this->additionalArguments;
	}

/**/

	public function setHasResult($isHasResult)
	{
		$this->hasResult = $isHasResult;
	}

	public function getHasResult()
	{
		return $this->hasResult;
	}

	public function setResult($result)
	{
		$this->result = $result;
	}

	public function getResult()
	{
		return $this->result;
	}

/**/

	public function setResultBuffer($resultBuffer)
	{
		$this->resultBuffer = $resultBuffer;
	}

	public function getResultBuffer()
	{
		return $this->resultBuffer;
	}

/**/

	public function setSourceSpec($sourceSpec)
	{
		$this->sourceSpec = $sourceSpec;
	}

	public function getSourceSpec()
	{
		return $this->sourceSpec;
	}

/**/

	public function setIsAnonymous($isAnonymous)
	{
		$this->isAnonymous = $isAnonymous;
	}

	public function getIsAnonymous()
	{
		return $this->isAnonymous;
	}

/**/

	public function setIsEnabled($isEnabled)
	{
		$this->isEnabled = $isEnabled;
	}

	public function getIsEnabled()
	{
		return $this->isEnabled;
	}

/**/

	public function getAsXhtml($format = 'xhtml', $putHeader = true, $putFooter = true)
	{
		return $this->getAsFormat($format, $putHeader, $putFooter);
	}

	public function getAsXml($format = 'xml', $putHeader = true, $putFooter = true)
	{
		return $this->getAsFormat($format, $putHeader, $putFooter);
	}

	public function getAsPlain($format = 'plain', $putHeader = true, $putFooter = true)
	{
		return $this->getAsFormat($format, $putHeader, $putFooter);
	}

	public function getAsFormat($formatOrClassName = 'xhtml', $putHeader = true, $putFooter = true)
	{
		$formatOrClassName = $this->createFormat($formatOrClassName);
		return $formatOrClassName->getContent($putHeader, $putFooter);

		$template = $this->createTemplate($formatOrClassName);
		$transformation = $this->createTransformation($template);
		return $transformation->transform();
	}

	protected function createTemplate($format)
	{
		if ($format == 'xhtml')
			$format = '\net\mkharitonov\spectrum\core\reports\formats\Xhtml';
		else if ($format == 'xml')
			$format = '\net\mkharitonov\spectrum\core\reports\formats\Xml';
		else if ($format == 'plain')
			$format = '\net\mkharitonov\spectrum\core\reports\formats\Plain';

		$reflection = new \ReflectionClass($format);
		if (!$reflection->implementsInterface('\net\mkharitonov\spectrum\core\plugins\basePlugins\reports\FormatInterface'))
			throw new Exception('Class "' . $format . '" should be implements report\FormatInterface');

		return $format;
	}

	/**
	 * @return \net\mkharitonov\spectrum\core\plugins\basePlugins\reports\formats\Xhtml
	 */
	protected function createTransformation($format)
	{
		if ($format == 'xhtml')
			$format = '\net\mkharitonov\spectrum\core\reports\formats\Xhtml';
		else if ($format == 'xml')
			$format = '\net\mkharitonov\spectrum\core\reports\formats\Xml';
		else if ($format == 'plain')
			$format = '\net\mkharitonov\spectrum\core\reports\formats\Plain';

		$reflection = new \ReflectionClass($format);
		if (!$reflection->implementsInterface('\net\mkharitonov\spectrum\core\plugins\basePlugins\reports\FormatInterface'))
			throw new Exception('Class "' . $format . '" should be implements report\FormatInterface');

		return new $format($this, $this->createFormatter());
	}

	protected function createFormatter()
	{
		$formatter = new Formatter();
		return $formatter;
	}
}