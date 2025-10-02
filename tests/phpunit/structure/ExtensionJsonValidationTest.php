<?php
/**
 * @license GPL-2.0-or-later
 */

use MediaWiki\Registration\ExtensionJsonValidationError;
use MediaWiki\Registration\ExtensionJsonValidator;
use MediaWiki\Registration\ExtensionRegistry;

/**
 * Validates all loaded extensions and skins using the ExtensionRegistry
 * against the extension.json schema in the docs/ folder.
 * @coversNothing
 */
class ExtensionJsonValidationTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @var ExtensionJsonValidator
	 */
	protected $validator;

	protected function setUp(): void {
		parent::setUp();

		$this->validator = new ExtensionJsonValidator( [ $this, 'markTestSkipped' ] );
		$this->validator->checkDependencies();

		if ( !ExtensionRegistry::getInstance()->getAllThings() ) {
			$this->markTestSkipped(
				'There are no extensions or skins loaded via the ExtensionRegistry'
			);
		}
	}

	public static function providePassesValidation() {
		$allThings = ExtensionRegistry::getInstance()->getAllThings();

		foreach ( $allThings as $thing ) {
			yield [ $thing['path'] ];
		}
	}

	/**
	 * @dataProvider providePassesValidation
	 * @param string $path Path to thing's json file
	 */
	public function testPassesValidation( $path ) {
		try {
			$this->validator->validate( $path );
			// All good
			$this->assertTrue( true );
		} catch ( ExtensionJsonValidationError $e ) {
			$this->fail( $e->getMessage() );
		}
	}
}
