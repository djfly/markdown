<?php
/**
 * @copyright Copyright (c) 2014 Carsten Brandt
 * @license https://github.com/cebe/markdown/blob/master/LICENSE
 * @link https://github.com/cebe/markdown#readme
 */

namespace cebe\markdown\tests;

use cebe\markdown\Parser;

/**
 * Test case for the parser base class.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @group default
 */
class ParserTest extends  \PHPUnit_Framework_TestCase
{
	public function testMarkerOrder()
	{
		$parser = new TestParser();
		$parser->markers = [
			'[' => 'parseMarkerA',
			'[[' => 'parseMarkerB',
		];

		$this->assertEquals("<p>Result is A</p>\n", $parser->parse('Result is [abc]'));
		$this->assertEquals("<p>Result is B</p>\n", $parser->parse('Result is [[abc]]'));
		$this->assertEquals('Result is A', $parser->parseParagraph('Result is [abc]'));
		$this->assertEquals('Result is B', $parser->parseParagraph('Result is [[abc]]'));

		$parser = new TestParser();
		$parser->markers = [
			'[[' => 'parseMarkerB',
			'[' => 'parseMarkerA',
		];

		$this->assertEquals("<p>Result is A</p>\n", $parser->parse('Result is [abc]'));
		$this->assertEquals("<p>Result is B</p>\n", $parser->parse('Result is [[abc]]'));
		$this->assertEquals('Result is A', $parser->parseParagraph('Result is [abc]'));
		$this->assertEquals('Result is B', $parser->parseParagraph('Result is [[abc]]'));
	}
}

class TestParser extends Parser
{
	public $markers = [];

	public function inlineMarkers()
	{
		return $this->markers;
	}

	public function parseMarkerA($text)
	{
		return ['A', strrpos($text, ']') + 1];
	}

	public function parseMarkerB($text)
	{
		return ['B', strrpos($text, ']') + 1];
	}
}
