<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Parsoid\LintErrorChecker;
use MediaWiki\Registration\ExtensionRegistry;

/**
 * @group Parser
 * @group Database
 * @covers \MediaWiki\Parser\Parsoid\LintErrorChecker
 */
class LintErrorCheckerTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::ParsoidSettings, [
			'linting' => true
		] );
		$this->overrideConfigValue( 'LinterCategories', [
			// No hidden categories in default set up
		] );
	}

	/**
	 * Get a basic LintErrorChecker for testing with.
	 * @return LintErrorChecker
	 */
	protected function getLintErrorChecker() {
		$services = $this->getServiceContainer();
		$extReg = $this->createMock( ExtensionRegistry::class );
		$extReg->method( 'isLoaded' )->willReturnCallback( static function ( string $which ) {
			return $which == 'Linter';
		} );

		return new LintErrorChecker(
			$services->get( '_Parsoid' ),
			$services->getParsoidPageConfigFactory(),
			$services->getTitleFactory(),
			$extReg,
			$services->getMainConfig(),
		 );
	}

	/**
	 * @dataProvider provideCheck
	 */
	public function testCheck( $wikitext, $expected ) {
		$errors = $this->getLintErrorChecker()->check( $wikitext );
		$this->assertSame( $expected, $errors );
	}

	public static function provideCheck() {
			yield 'Perfect' => [ '<strong>Foo</strong>', [] ];
			yield 'Unclosed tag' => [
				'<strong>Foo',
				[
					[
						'type' => 'missing-end-tag',
						'dsr' => [ 0, 11, 8, 0 ],
						'templateInfo' => null,
						'params' => [
							'name' => 'strong',
							'inTable' => false,
						]
					]
				]
			];
	}

	public function testCheckSome() {
		// Take the same "Unclosed tag" test from above but disable the category
		$errors = $this->getLintErrorChecker()->checkSome( '<strong>Foo', [ 'missing-end-tag' ] );
		$this->assertSame( [], $errors );
	}

	/** Test when categories are diabled in $wgLinterCategories */
	public function testLinterCategory() {
		$input = '<font color="red">RED</font>';
		$errors = $this->getLintErrorChecker()->check( $input );
		$this->assertEquals( 'obsolete-tag', $errors[0]['type'] );

		// Now disable the category
		$this->overrideConfigValue( 'LinterCategories', [
			'obsolete-tag' => [ 'priority' => 'none' ],
		] );

		$errors = $this->getLintErrorChecker()->check( $input );
		$this->assertSame( [], $errors );
	}
}
