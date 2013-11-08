<?php

/**
 * @covers \WikiImporter
 *
 * @ingroup Import
 * @group Import
 *
 * @licence GNU GPL v2+
 * @since 1.23
 *
 * @author mwjames
 */
class WikiImporterTest extends MediaWikiTestCase {

	/**
	 * @since 1.23
	 */
	public function testCanConstructInstance() {

		$importStreamSource = $this->getMockBuilder( 'ImportStreamSource' )
			->disableOriginalConstructor()
			->getMock();

		$importer = new WikiImporter( $importStreamSource );
		$this->assertInstanceOf( 'WikiImporter', $importer );

		$importer = new WikiImporter( $importStreamSource );
		$this->assertInstanceOf(
			'WikiImporter',
			$importer,
			"Asserts that an additional instance can be constructed"
		);

	}

}
