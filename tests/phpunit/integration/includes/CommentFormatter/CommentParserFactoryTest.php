<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Integration\CommentFormatter;

use MediaWiki\CommentFormatter\CommentParser;
use MediaWikiIntegrationTestCase;

/**
 * @group CommentFormatter
 * @covers \MediaWiki\CommentFormatter\CommentParserFactory
 */
class CommentParserFactoryTest extends MediaWikiIntegrationTestCase {

	public function testCreate() {
		$factory = $this->getServiceContainer()->getCommentParserFactory();

		$this->assertInstanceOf( CommentParser::class, $factory->create() );
	}
}
