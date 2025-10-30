<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use SimpleXMLElement;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitTestListProcessor {

	private SimpleXMLElement $xml;
	private array $resultsCache;

	/**
	 * @throws PhpUnitResultsCachingException
	 */
	public function __construct(
		string $testListFile,
		?string $resultsCacheFile = null,
		?string $testGroup = null
	) {
		$this->xml = new SimpleXMLElement( file_get_contents( $testListFile ) );
		// Load phpunit result cache information if available to create more balanced split groups
		if ( $resultsCacheFile && $testGroup ) {
			if ( !file_exists( $resultsCacheFile ) ) {
				throw new PhpUnitResultsCachingException(
					'Results cache file "' . $resultsCacheFile . '" specified but not found on filesystem'
				);
			}
			$loadedResults = json_decode( file_get_contents( $resultsCacheFile ), true );
			if ( array_key_exists( $testGroup, $loadedResults ) ) {
				$this->resultsCache = $loadedResults[$testGroup];
			} else {
				$this->resultsCache = [];
			}
		} else {
			$this->resultsCache = [];
		}
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
			fn ( $element ) => self::createTestDescriptor(
				(string)$element->attributes()["name"],
				$this->resultsCache
			),
			iterator_to_array( $this->xml->testCaseClass, false )
		);
	}

	private static function createTestDescriptor(
		string $qualifiedClassName,
		array $resultsCache
	): TestDescriptor {
		$duration = 0;
		if ( array_key_exists( $qualifiedClassName, $resultsCache ) ) {
			$duration = $resultsCache[$qualifiedClassName];
		}
		$parts = explode( '\\', $qualifiedClassName );
		$className = array_pop( $parts );
		return new TestDescriptor( $className, $parts, null, $duration );
	}
}
