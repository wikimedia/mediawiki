<?php

/**
 * Note: this is not a unit test, as it touches the file system and reads an actual file.
 * If unit tests are added for MediaWikiVersionFetcher, this should be done in a distinct test case.
 *
 * @covers MediaWikiVersionFetcher
 *
 * @group ComposerHooks
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiVersionFetcherTest extends PHPUnit_Framework_TestCase {

	public function testReturnsResult() {
		$versionFetcher = new MediaWikiVersionFetcher();
		$this->assertInternalType( 'string', $versionFetcher->fetchVersion() );
	}

}
