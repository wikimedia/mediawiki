<?php
declare( strict_types = 1 );

// This belongs in Wikimedia\Parsoid\ParserTests
namespace MediaWiki\Tests;

/**
 * Represents a parser test mode, that is, a certain way of executing a
 * parser tests and evaluating the result.
 *
 * As a trivial example, a parser test will typically have a
 * "wikitext" section and an "html" section.  Two possible modes for
 * evaluating the test are "wt2html" (where you programatically
 * convert the "wikitext" section to HTML and verify that the result
 * matches the "html" section, after normalization) and "html2wt"
 * (where you programmatically convert the "html" section back to
 * wikitext and verify that the result matches the "wikitext" section,
 * after normalization).
 */
class TestMode {

	/** Valid test modes, as keys for efficient query/set intersection. */
	public const TEST_MODES = [
		'legacy' => true, // wt2html with legacy parser
		'wt2html' => true,
		'wt2wt' => true,
		'html2html' => true,
		'html2wt' => true,
		'selser' => true,
	];

	/**
	 * Selected test mode, typically one of the values from self::TEST_MODES.
	 * @var string
	 */
	public $mode;

	/**
	 * The "selser" test mode can operate with an explicit changetree
	 * provided in this property.
	 * @var ?array
	 */
	public $changetree;

	/**
	 * Create a new test mode
	 * @param string $mode The test mode.  An external caller should use
	 *   one of `self::TEST_MODES`, although ParserTestRunner uses a few
	 *   additional values internally.
	 * @param ?array $changetree The specific changes to apply in selser test
	 *   mode.
	 */
	public function __construct(
		string $mode,
		?array $changetree = null
	) {
		$this->mode = $mode;
		$this->changetree = $changetree;
	}

	/**
	 * Helper function: returns true if this test mode is 'legacy'; that is,
	 * is this test to run with the legacy parser.
	 * @return bool
	 */
	public function isLegacy() {
		return $this->mode === 'legacy';
	}

	/**
	 * Helper function: returns true if we are running this test to cache some info
	 * for use in later tests.
	 * @return bool
	 */
	public function isCachingMode() {
		return $this->mode === 'cache';
	}

	/**
	 * Returns a string representation of this test mode, which can also
	 * be used as an array key or for human-friendly output.
	 * @return string
	 */
	public function __toString(): string {
		$s = $this->mode;
		if ( $this->changetree !== null ) {
			$s .= ' ' . json_encode(
				$this->changetree,
				JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
			);
		}
		return $s;
	}

	/**
	 * Helper function: filter a given set of options against the
	 * TEST_MODES. Optionally ensure that all modes are returned if
	 * none are explicitly set.
	 *
	 * @param string[] $options The user-specified test modes
	 * @param bool $ifEmptySetAll If true, ensure that the result always
	 *   includes at least one set test mode by setting all available test
	 *   modes if the passed $options array does not contain any.
	 * @return string[] A filtered set of test modes
	 */
	public static function requestedTestModes( array $options, bool $ifEmptySetAll = true ) {
		if ( $ifEmptySetAll ) {
			$allModes = true;
			foreach ( self::TEST_MODES as $mode => $ignore ) {
				if ( $options[$mode] ?? false ) {
					$allModes = false;
				}
			}
			if ( $allModes ) {
				return array_keys( self::TEST_MODES );
			}
		}
		return array_keys( array_intersect_assoc(
			$options, self::TEST_MODES
		) );
	}
}
