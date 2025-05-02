<?php

namespace MediaWiki\Tests\Maintenance;

use FormatInstallDoc;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;

/**
 * @covers \FormatInstallDoc
 * @group Database
 * @author Dreamy Jazz
 */
class FormatInstallDocTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return FormatInstallDoc::class;
	}

	/** @dataProvider provideExecute */
	public function testExecute( string $inFileContent, string $expectedOutFileContent ) {
		$inFile = $this->getNewTempFile();
		file_put_contents( $inFile, $inFileContent );
		$outFile = $this->getNewTempFile();

		$this->maintenance->setArg( 'path', $inFile );
		$this->maintenance->setOption( 'outfile', $outFile );
		$this->maintenance->execute();

		$this->assertStringEqualsFile( $outFile, $expectedOutFileContent );
	}

	public static function provideExecute() {
		return [
			'Content of input file is empty' => [ '', '' ],
			'Input file has content' => [
				"= MediaWiki 1.44 =\n=== Configuration changes for system administrators in 1.44 ===\n" .
					"* (T382987) Test\n*Test 2\n  - Testing",
				"= MediaWiki 1.44 =\n=== Configuration changes for system administrators in 1.44 ===\n" .
					"* (<span class=\"config-plainlink\">[https://phabricator.wikimedia.org/T382987 T382987]</span>)" .
					" Test\n*Test 2   - Testing",
			],
		];
	}

	/** @dataProvider provideExecute */
	public function testExecuteWhenUsingHtmlMode( string $inFileContent, string $expectedOutFileContent ) {
		$this->maintenance->setOption( 'html', 1 );

		// Generate the HTML using the same way as the script. Doing this makes it resistant to changes to
		// the HTML structure, so avoiding a brittle test.
		$parser = $this->getServiceContainer()->getParser();
		$opt = ParserOptions::newFromAnon();
		$title = Title::newFromText( 'Text file' );
		$out = $parser->parse( $expectedOutFileContent, $title, $opt );
		$expectedOutFileContent = "<html><body>\n" .
			$this->getServiceContainer()->getDefaultOutputPipeline()
				->run( $out, $opt, [] )
				->getContentHolderText()
			. "\n</body></html>\n";

		$this->testExecute( $inFileContent, $expectedOutFileContent );
	}
}
