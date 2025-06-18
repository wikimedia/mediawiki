<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use DOMDocument;
use SimpleXMLElement;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitXml {

	private SimpleXMLElement $xml;

	public function __construct( string $phpUnitXmlFile ) {
		$this->xml = new SimpleXMLElement( file_get_contents( $phpUnitXmlFile ) );
	}

	public static function isPhpUnitXmlPrepared( string $targetFile ): bool {
		if ( !file_exists( $targetFile ) ) {
			return false;
		}
		$unitFile = new PhpUnitXml( $targetFile );
		return $unitFile->containsSplitGroups();
	}

	public function containsSplitGroups(): bool {
		if ( !property_exists( $this->xml, "testsuites" ) ||
			!property_exists( $this->xml->testsuites, "testsuite" ) ) {
			return false;
		}
		foreach ( $this->xml->testsuites->testsuite as $child ) {
			if ( isset( $child->attributes()["name"] ) &&
				str_starts_with( (string)$child->attributes()["name"], "split_group_" ) ) {
				return true;
			}
		}
		return false;
	}

	public function addSplitGroups( array $splitGroups ) {
		$groups = count( $splitGroups );
		for ( $i = 0; $i < $groups; $i++ ) {
			$suite = $this->xml->testsuites->addChild( "testsuite" );
			$suite->addAttribute( "name", "split_group_" . $i );
			$group = $splitGroups[$i];
			if ( !empty( $group["list"] ) ) {
				foreach ( $group["list"] as $file ) {
					$suite->addChild( "file", $file );
				}
			}
		}
	}

	/**
	 * @throws SuiteGenerationException
	 */
	private function getSplitGroupSuite( int $groupId ): SimpleXMLElement {
		foreach ( $this->xml->testsuites->testsuite as $child ) {
			if ( isset( $child->attributes()["name"] ) &&
				(string)$child->attributes()["name"] === "split_group_" . $groupId ) {
				return $child;
			}
		}
		throw new SuiteGenerationException( $groupId );
	}

	/**
	 * There are some tests suites / classes where the test listing does not work because test
	 * cases are generated dynamically. For this special cases, we need to add the classes
	 * manually back into the suites list to ensure that they get included in a test run.
	 * @see T345481
	 * @see T358394
	 * @throws SuiteGenerationException
	 */
	public function addSpecialCaseTests( int $groupCount ) {
		$suite = $this->xml->testsuites->addChild( "testsuite" );
		$suite->addAttribute( "name", "split_group_" . ( $groupCount - 1 ) );
		$suite->addChild( "file", "tests/phpunit/suites/ExtensionsParserTestSuite.php" );

		$sandboxTest = "extensions/Scribunto/tests/phpunit/Engines/LuaSandbox/SandboxTest.php";
		if ( file_exists( $sandboxTest ) ) {
			$suite = $this->getSplitGroupSuite( 0 );
			$suite->addChild( "file", $sandboxTest );
		}
	}

	public function saveToDisk( string $targetXml ) {
		$dom = new DOMDocument( '1.0' );
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML( $this->xml->asXML() );
		file_put_contents( $targetXml, $dom->saveXML() );
	}

}
