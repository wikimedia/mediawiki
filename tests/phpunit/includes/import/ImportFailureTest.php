<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\MainConfigNames;

/**
 * Import failure test.
 *
 * @group Database
 * @covers \WikiImporter
 */
class ImportFailureTest extends MediaWikiLangTestCase {

	protected function setUp(): void {
		parent::setUp();

		$slotRoleRegistry = $this->getServiceContainer()->getSlotRoleRegistry();

		if ( !$slotRoleRegistry->isDefinedRole( 'ImportFailureTest' ) ) {
			$slotRoleRegistry->defineRoleWithModel( 'ImportFailureTest', CONTENT_MODEL_WIKITEXT );
		}
	}

	/**
	 * @param ImportSource $source
	 * @return WikiImporter
	 */
	private function getImporter( ImportSource $source ) {
		$config = new HashConfig( [
			MainConfigNames::MaxArticleSize => 2048,
		] );
		$services = $this->getServiceContainer();
		$importer = new WikiImporter(
			$source,
			$this->getTestSysop()->getAuthority(),
			$config,
			$services->getHookContainer(),
			$services->getContentLanguage(),
			$services->getNamespaceInfo(),
			$services->getTitleFactory(),
			$services->getWikiPageFactory(),
			$services->getWikiRevisionUploadImporter(),
			$services->getContentHandlerFactory(),
			$services->getSlotRoleRegistry()
		);
		return $importer;
	}

	/**
	 * @param string $testName
	 *
	 * @return string
	 */
	private function getFileToImport( string $testName ) {
		return __DIR__ . "/../../data/import/$testName.xml";
	}

	/**
	 * @param string $prefix
	 * @param string[] $keys
	 *
	 * @return string[]
	 */
	private function getUniqueNames( string $prefix, array $keys ) {
		$names = [];

		foreach ( $keys as $k ) {
			$names[$k] = "$prefix-$k-" . wfRandomString( 6 );
		}

		return $names;
	}

	/**
	 * @param string $xmlData
	 * @param string[] $pageTitles
	 *
	 * @return string
	 */
	private function injectPageTitles( string $xmlData, array $pageTitles ) {
		$keys = array_map( static function ( $name ) {
			return "{{{$name}_title}}";
		}, array_keys( $pageTitles ) );

		return str_replace(
			$keys,
			array_values( $pageTitles ),
			$xmlData
		);
	}

	public static function provideImportFailure() {
		yield [ 'BadXML', RuntimeException::class, '/^XML error at line 3: Opening and ending tag mismatch:.*$/' ];
		yield [ 'MissingMediaWikiTag', UnexpectedValueException::class, "/^Expected '<mediawiki>' tag, got .*$/" ];
		yield [ 'MissingMainTextField', InvalidArgumentException::class, '/^Missing text field in import.$/' ];
		yield [ 'MissingSlotTextField', InvalidArgumentException::class, '/^Missing text field in import.$/' ];
		yield [ 'MissingSlotRole', RuntimeException::class, '/^Missing role for imported slot.$/' ];
		yield [ 'UndefinedSlotRole', RuntimeException::class, '/^Undefined slot role .*$/' ];
		yield [ 'UndefinedContentModel', MWUnknownContentModelException::class, '/not registered on this wiki/' ];
	}

	/**
	 * @dataProvider provideImportFailure
	 */
	public function testImportFailure( $testName, $exceptionName, $exceptionMessage ) {
		$fileName = $this->getFileToImport( $testName );

		$pageKeys = [ 'page1', 'page2', 'page3', 'page4', ];
		$pageTitles = $this->getUniqueNames( $testName, $pageKeys );

		$xmlData = file_get_contents( $fileName );
		$xmlData = $this->injectPageTitles( $xmlData, $pageTitles );

		$source = new ImportStringSource( $xmlData );
		$importer = $this->getImporter( $source );
		$this->expectException( $exceptionName );
		$this->expectExceptionMessageMatches( $exceptionMessage );
		$importer->doImport();
	}
}
