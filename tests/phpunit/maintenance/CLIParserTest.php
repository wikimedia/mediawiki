<?php

namespace MediaWiki\Tests\Maintenance;

use CLIParser;

/**
 * @covers \CLIParser
 * @group Database
 * @author Dreamy Jazz
 */
class CLIParserTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CLIParser::class;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $inputWikitext, $options, $expectedOutput ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		// Get a file with the input wikitext as the content of the file and pass the filename
		// to the maintenance script
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, $inputWikitext );
		fclose( $testFile );
		$this->maintenance->setArg( 'file', $testFilename );
		// Run the maintenance script and expect that the output is the expected HTML
		$this->maintenance->execute();
		$this->expectOutputString( $expectedOutput );
	}

	public static function provideExecute() {
		return [
			'No wikitext in the content' => [ 'testing1234', [], "<p>testing1234\n</p>" ],
			'Wikitext in the content' => [ '* testing1234', [], '<ul><li>testing1234</li></ul>' ],
		];
	}
}
