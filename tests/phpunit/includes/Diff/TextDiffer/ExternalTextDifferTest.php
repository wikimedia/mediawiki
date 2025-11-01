<?php

namespace MediaWiki\Tests\Diff\TextDiffer;

use MediaWiki\Diff\TextDiffer\ExternalTextDiffer;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Diff\TextDiffer\ExternalTextDiffer
 */
class ExternalTextDifferTest extends MediaWikiIntegrationTestCase {
	public function testRender() {
		if ( !is_executable( '/bin/sh' ) ) {
			$this->markTestSkipped( 'ExternalTextDiffer can\'t pass extra ' .
				'arguments like $wgPhpCli, so it\'s hard to be platform-independent' );
		}
		$oldText = 'foo';
		$newText = 'bar';
		$differ = new ExternalTextDiffer( __DIR__ . '/externalDiffTest.sh' );
		$result = $differ->render( $oldText, $newText, 'external' );
		$this->assertSame( "- foo\n+ bar\n", $result );
	}
}
