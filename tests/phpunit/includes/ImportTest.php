<?php

/**
 * Test class for Import methods.
 *
 * @group Database
 *
 * @author Sebastian Brückner < sebastian.brueckner@student.hpi.uni-potsdam.de >
 */
class ImportTest extends MediaWikiLangTestCase {

	private function getInputStreamSource( $xml ) {
		$file = 'data:application/xml,' . $xml;
		$status = ImportStreamSource::newFromFile( $file );
		if ( !$status->isGood() ) {
			throw new MWException( "Cannot create InputStreamSource." );
		}
		return $status->value;
	}

	/**
	 * @covers WikiImporter::handlePage
	 * @dataProvider getRedirectXML
	 * @param string $xml
	 * @param string|null $redirectTitle
	 */
	public function testHandlePageContainsRedirect( $xml, $redirectTitle ) {
		$source = $this->getInputStreamSource( $xml );

		$redirect = null;
		$callback = function ( $title, $origTitle, $revCount, $sRevCount, $pageInfo ) use ( &$redirect ) {
			if ( array_key_exists( 'redirect', $pageInfo ) ) {
				$redirect = $pageInfo['redirect'];
			}
		};

		$importer = new WikiImporter( $source );
		$importer->setPageOutCallback( $callback );
		$importer->doImport();

		$this->assertEquals( $redirectTitle, $redirect );
	}

	public function getRedirectXML() {
		return array(
			array(
				<<< EOF
<mediawiki>
	<page>
		<title>Test</title>
		<ns>0</ns>
		<id>21</id>
		<redirect title="Test22"/>
		<revision>
			<id>20</id>
			<timestamp>2014-05-27T10:00:00Z</timestamp>
			<contributor>
				<username>Admin</username>
				<id>10</id>
			</contributor>
			<comment>Admin moved page [[Test]] to [[Test22]]</comment>
			<text xml:space="preserve" bytes="20">#REDIRECT [[Test22]]</text>
			<sha1>tq456o9x3abm7r9ozi6km8yrbbc56o6</sha1>
			<model>wikitext</model>
			<format>text/x-wiki</format>
		</revision>
	</page>
</mediawiki>
EOF
			,
				'Test22'
			),
			array(
				<<< EOF
<mediawiki>
	<page>
		<title>Test</title>
		<ns>0</ns>
		<id>42</id>
		<revision>
			<id>421</id>
			<timestamp>2014-05-27T11:00:00Z</timestamp>
			<contributor>
				<username>Admin</username>
				<id>10</id>
			</contributor>
			<text xml:space="preserve" bytes="4">Abcd</text>
			<sha1>n7uomjq96szt60fy5w3x7ahf7q8m8rh</sha1>
			<model>wikitext</model>
			<format>text/x-wiki</format>
		</revision>
	</page>
</mediawiki>
EOF
			,
				null
			),
		);
	}

}
