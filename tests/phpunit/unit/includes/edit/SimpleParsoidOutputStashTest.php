<?php

namespace MediaWiki\Tests\Unit\Edit;

use HashBagOStuff;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use Wikimedia\Parsoid\Core\PageBundle;

/** @covers \MediaWiki\Edit\SimpleParsoidOutputStash */
class SimpleParsoidOutputStashTest extends \MediaWikiUnitTestCase {

	public function testSetAndGet() {
		$stash = new SimpleParsoidOutputStash( new HashBagOStuff(), 12 );

		$key = new ParsoidRenderID( 7, 'acme' );
		$pageBundle = new PageBundle( 'Hello World' );

		$stash->set( $key, $pageBundle );
		$this->assertEquals( $pageBundle, $stash->get( $key ) );
	}

}
