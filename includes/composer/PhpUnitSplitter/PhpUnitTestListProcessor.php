<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use SimpleXMLElement;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitTestListProcessor {

	private SimpleXMLElement $xml;

	public function __construct( string $testListFile ) {
		$this->xml = new SimpleXMLElement( file_get_contents( $testListFile ) );
	}

	/**
	 * @return TestDescriptor[] A list of TestDescriptor objects representing the
	 *                          test classes found in the `--list-tests` XML
	 *                          output
	 */
	public function getTestClasses(): array {
		if ( !property_exists( $this->xml, "testCaseClass" ) ) {
			return [];
		}
		return array_map(
			fn ( $element ) => self::extractNamespace( (string)$element->attributes()["name"] ),
			iterator_to_array( $this->xml->testCaseClass, false )
		);
	}

	private static function extractNamespace( string $qualifiedClassName ): TestDescriptor {
		$parts = explode( '\\', $qualifiedClassName );
		$className = array_pop( $parts );
		return new TestDescriptor( $className, $parts );
	}
}
