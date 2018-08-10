<?php

/**
 * Note: this is not a unit test, as it touches the file system and reads an actual file.
 * If unit tests are added for MediaWikiVersionFetcher, this should be done in a distinct test case.
 *
 * @covers MediaWikiVersionFetcher
 *
 * @group ComposerHooks
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiVersionFetcherTest extends MediaWikiTestCase {

	use MediaWikiCoversValidator;

	public function testReturnsResult() {
		global $wgVersion;
		$versionFetcher = new MediaWikiVersionFetcher();
		$this->assertSame( $wgVersion, $versionFetcher->fetchVersion() );
	}

}
