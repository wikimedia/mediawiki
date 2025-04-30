<?php

use MediaWiki\Title\ForeignTitle;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Test class for Import methods.
 *
 * @group Database
 *
 * @author Sebastian Brückner < sebastian.brueckner@student.hpi.uni-potsdam.de >
 */
class ImportTest extends MediaWikiLangTestCase {

	/**
	 * @covers \WikiImporter
	 * @dataProvider provideUnknownTagsXML
	 * @param string $xml
	 * @param string $text
	 * @param string $title
	 */
	public function testUnknownXMLTags( $xml, $text, $title ) {
		$source = new ImportStringSource( $xml );
		$services = $this->getServiceContainer();

		$importer = $services->getWikiImporterFactory()->getWikiImporter( $source, $this->getTestSysop()->getAuthority() );

		$importer->doImport();
		$title = Title::newFromText( $title );
		$this->assertTrue( $title->exists() );

		$this->assertEquals(
			$services->getWikiPageFactory()->newFromTitle( $title )->getContent()->getText(),
			$text
		);
	}

	public function provideUnknownTagsXML() {
		return [
			[
				<<< EOF
<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.10/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.10/ http://www.mediawiki.org/xml/export-0.10.xsd" version="0.10" xml:lang="en">
  <page unknown="123" dontknow="533">
    <title>TestImportPage</title>
    <unknowntag>Should be ignored</unknowntag>
    <ns>0</ns>
    <id unknown="123" dontknow="533">14</id>
    <revision>
      <id unknown="123" dontknow="533">15</id>
      <unknowntag>Should be ignored</unknowntag>
      <timestamp>2016-01-03T11:18:43Z</timestamp>
      <contributor>
        <unknowntag>Should be ignored</unknowntag>
        <username unknown="123" dontknow="533">Admin</username>
        <id>1</id>
      </contributor>
      <model>wikitext</model>
      <format>text/x-wiki</format>
      <text xml:space="preserve" bytes="0">noitazinagro tseb eht si ikiWaideM</text>
      <sha1>phoiac9h4m842xq45sp7s6u21eteeq1</sha1>
      <unknowntag>Should be ignored</unknowntag>
    </revision>
  </page>
  <unknowntag>Should be ignored</unknowntag>
</mediawiki>
EOF
				,
				'noitazinagro tseb eht si ikiWaideM',
				'TestImportPage'
			]
		];
		// phpcs:enable
	}

	/**
	 * @covers \WikiImporter::handlePage
	 * @dataProvider provideRedirectXML
	 * @param string $xml
	 * @param string|null $redirectTitle
	 */
	public function testHandlePageContainsRedirect( $xml, $redirectTitle ) {
		$source = new ImportStringSource( $xml );

		$redirect = null;
		$callback = static function ( Title $title, ForeignTitle $foreignTitle, $revCount,
			$sRevCount, $pageInfo ) use ( &$redirect ) {
			if ( array_key_exists( 'redirect', $pageInfo ) ) {
				$redirect = $pageInfo['redirect'];
			}
		};

		$importer = $this->getServiceContainer()
			->getWikiImporterFactory()
			->getWikiImporter( $source, $this->getTestSysop()->getAuthority() );
		$importer->setPageOutCallback( $callback );
		$importer->doImport();

		$this->assertEquals( $redirectTitle, $redirect );
	}

	public function provideRedirectXML() {
		return [
			[
				<<< EOF
<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.10/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.10/ http://www.mediawiki.org/xml/export-0.10.xsd" version="0.10" xml:lang="en">
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
			<model>wikitext</model>
			<format>text/x-wiki</format>
			<text xml:space="preserve" bytes="20">#REDIRECT [[Test22]]</text>
			<sha1>tq456o9x3abm7r9ozi6km8yrbbc56o6</sha1>
		</revision>
	</page>
</mediawiki>
EOF
			,
				'Test22'
			],
			[
				<<< EOF
<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.9/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.9/ http://www.mediawiki.org/xml/export-0.9.xsd" version="0.9" xml:lang="en">
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
			],
		];
		// phpcs:enable
	}

	/**
	 * @covers \WikiImporter::handleSiteInfo
	 * @dataProvider provideSiteInfoXML
	 * @param string $xml
	 * @param array|null $namespaces
	 */
	public function testSiteInfoContainsNamespaces( $xml, $namespaces ) {
		$source = new ImportStringSource( $xml );

		$importNamespaces = null;
		$callback = static function ( array $siteinfo, $innerImporter ) use ( &$importNamespaces ) {
			$importNamespaces = $siteinfo['_namespaces'];
		};

		$importer = $this->getServiceContainer()
			->getWikiImporterFactory()
			->getWikiImporter( $source, $this->getTestSysop()->getAuthority() );
		$importer->setSiteInfoCallback( $callback );
		$importer->doImport();

		$this->assertEquals( $importNamespaces, $namespaces );
	}

	public function provideSiteInfoXML() {
		return [
			[
				<<< EOF
<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.10/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.10/ http://www.mediawiki.org/xml/export-0.10.xsd" version="0.10" xml:lang="en">
  <siteinfo>
    <namespaces>
      <namespace key="-2" case="first-letter">Media</namespace>
      <namespace key="-1" case="first-letter">Special</namespace>
      <namespace key="0" case="first-letter" />
      <namespace key="1" case="first-letter">Talk</namespace>
      <namespace key="2" case="first-letter">User</namespace>
      <namespace key="3" case="first-letter">User talk</namespace>
      <namespace key="100" case="first-letter">Portal</namespace>
      <namespace key="101" case="first-letter">Portal talk</namespace>
    </namespaces>
  </siteinfo>
</mediawiki>
EOF
			,
				[
					'-2' => 'Media',
					'-1' => 'Special',
					'0' => '',
					'1' => 'Talk',
					'2' => 'User',
					'3' => 'User talk',
					'100' => 'Portal',
					'101' => 'Portal talk',
				]
			],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideUnknownUserHandling
	 * @covers \WikiImporter::setUsernamePrefix
	 * @covers \MediaWiki\User\ExternalUserNames::addPrefix
	 * @covers \MediaWiki\User\ExternalUserNames::applyPrefix
	 * @param bool $assign
	 * @param bool $create
	 */
	public function testUnknownUserHandling( $assign, $create ) {
		$hookId = -99;
		$this->setTemporaryHook(
			'ImportHandleUnknownUser',
			function ( $name ) use ( $assign, $create, &$hookId ) {
				if ( !$assign ) {
					$this->fail( 'ImportHandleUnknownUser was called unexpectedly' );
				}

				$this->assertEquals( 'UserDoesNotExist', $name );
				if ( $create ) {
					$user = User::createNew( $name );
					$this->assertNotNull( $user );
					$hookId = $user->getId();
					return false;
				}
				return true;
			}
		);

		$user = $this->getTestUser()->getUser();

		$n = ( $assign ? 1 : 0 ) + ( $create ? 2 : 0 );
		$source = new ImportStringSource( <<<EOF
<mediawiki xmlns="http://www.mediawiki.org/xml/export-0.10/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.10/ http://www.mediawiki.org/xml/export-0.10.xsd" version="0.10" xml:lang="en">
  <page>
    <title>TestImportPage</title>
    <ns>0</ns>
    <id>14</id>
    <revision>
      <id>15</id>
      <timestamp>2016-01-01T0$n:00:00Z</timestamp>
      <contributor>
        <username>UserDoesNotExist</username>
        <id>1</id>
      </contributor>
      <model>wikitext</model>
      <format>text/x-wiki</format>
      <text xml:space="preserve" bytes="3">foo</text>
      <sha1>1e6gpc3ehk0mu2jqu8cg42g009s796b</sha1>
    </revision>
    <revision>
      <id>16</id>
      <timestamp>2016-01-01T0$n:00:01Z</timestamp>
      <contributor>
        <username>{$user->getName()}</username>
        <id>{$user->getId()}</id>
      </contributor>
      <model>wikitext</model>
      <format>text/x-wiki</format>
      <text xml:space="preserve" bytes="3">bar</text>
      <sha1>bjhlo6dxh5wivnszm93u4b78fheiy4t</sha1>
    </revision>
  </page>
</mediawiki>
EOF
		);
		// phpcs:enable

		$services = $this->getServiceContainer();
		$importer = $services->getWikiImporterFactory()->getWikiImporter( $source, $this->getTestSysop()->getAuthority() );

		$importer->setUsernamePrefix( 'Xxx', $assign );
		$importer->doImport();

		$db = $this->getDb();
		$row = $services->getRevisionStore()->newSelectQueryBuilder( $db )
			->where( [ 'rev_timestamp' => $db->timestamp( "201601010{$n}0000" ) ] )
			->caller( __METHOD__ )->fetchRow();
		$this->assertSame(
			$assign && $create ? 'UserDoesNotExist' : 'Xxx>UserDoesNotExist',
			$row->rev_user_text
		);
		$this->assertSame( $assign && $create ? $hookId : 0, (int)$row->rev_user );

		$row = $services->getRevisionStore()->newSelectQueryBuilder( $db )
			->where( [ 'rev_timestamp' => $db->timestamp( "201601010{$n}0001" ) ] )
			->caller( __METHOD__ )->fetchRow();
		$this->assertSame( ( $assign ? '' : 'Xxx>' ) . $user->getName(), $row->rev_user_text );
		$this->assertSame( $assign ? $user->getId() : 0, (int)$row->rev_user );
	}

	public static function provideUnknownUserHandling() {
		return [
			'no assign' => [ false, false ],
			'assign, no create' => [ true, false ],
			'assign, create' => [ true, true ],
		];
	}

}
