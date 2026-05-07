<?php
declare( strict_types = 1 );
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Parsoid\LintErrorChecker;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;

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
		$extReg->method( 'isLoaded' )->willReturn( true );

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
	public function testCheck( $wikitext, $expected, $title = null ) {
		$errors = $this->getLintErrorChecker()->check( self::getRevision( $wikitext, $title ) );
		$this->assertSame( $expected, $errors );
	}

	public static function getRevision(
		string $wikitext, ?string $title = null
	): RevisionRecord {
		return MutableRevisionRecord::newFromContent(
			( $title !== null ) ? Title::newFromText( $title ) : Title::newMainPage(),
			new WikitextContent( $wikitext )
		);
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
			// The following test is a bit contrived and would probably be
			// better representated by what's found in the wild (see T419596)
			// '{{#ifeq:{{NAMESPACE}}|User|<!--do nothing-->|<strong>Foo}}'
			// but that requires the ParserFunctions extension
			yield 'Page context 1' => [
				'{{#tag:{{PAGENAME}}|test}}',
				[],
				'Div'
			];
			yield 'Page context 2' => [
				'{{#tag:{{PAGENAME}}|test}}',
				[
					[
						'type' => 'obsolete-tag',
						'dsr' => [ 0, 26, null, null ],
						'templateInfo' => [
							'parserFunction' => true
						],
						'params' => [
							'name' => 'strike',
						]
					]
				],
				'Strike'
			];
	}

	public function testCheckSome() {
		// Take the same "Unclosed tag" test from above but disable the category
		$errors = $this->getLintErrorChecker()->checkSome(
			self::getRevision( '<strong>Foo' ), [ 'missing-end-tag' ]
		);
		$this->assertSame( [], $errors );
	}

	/** Test when categories are disabled in $wgLinterCategories */
	public function testLinterCategory() {
		$input = self::getRevision( '<font color="red">RED</font>' );
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
