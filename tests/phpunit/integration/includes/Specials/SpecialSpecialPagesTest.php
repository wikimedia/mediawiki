<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialSpecialPages;
use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialSpecialPages
 */
class SpecialSpecialPagesTest extends SpecialPageTestBase {

	protected function newSpecialPage() {
		return new SpecialSpecialPages();
	}

	/**
	 * Register a single listed stub special page (on top of the core list) and return the
	 * rendered Special:SpecialPages HTML.
	 *
	 * @param string $name Canonical name; also used as the registration key.
	 * @param string $group Group name, may contain '/' to form a subgroup.
	 * @param bool $silenceWfWarn Set DevelopmentWarnings off, needed if stub has no content-
	 *  language alias, as getLocalNameFor() then emits a wfWarn() in tests (but not prod).
	 * @param array<string,string[]> $aliases Optional content-language aliases to seed,
	 *  keyed by canonical name (there is no hook for this).
	 */
	private function executeWithStub(
		string $name,
		string $group,
		bool $silenceWfWarn,
		array $aliases = []
	): string {
		$config = [
			MainConfigNames::SpecialPages =>
				[ $name => [
					'factory' => static function () use ( $name, $group ) {
						return new SpecialSpecialPagesStub( $name, $group );
					},
				] ] + MainConfigSchema::getDefaultValue( MainConfigNames::SpecialPages ),
		];
		if ( $silenceWfWarn ) {
			$config[ MainConfigNames::DevelopmentWarnings ] = false;
		}
		$this->overrideConfigValues( $config );

		if ( $aliases ) {
			// Seed the content language's special page alias cache *after* overrideConfigValues(),
			// which resets the service container and would otherwise discard the instance we mutate.
			$contLang = $this->getServiceContainer()->getContentLanguage();
			$contLang->mExtendedSpecialPageAliases = $aliases + $contLang->getSpecialPageAliases();
		}

		[ $html ] = $this->executeSpecialPage();
		return $html;
	}

	/**
	 * Regression test for T429584: a listed special page whose canonical name has no
	 * content-language alias entry (e.g. extension pages such as TranslatorSignup) must not
	 * trigger "Undefined array key" / "foreach() argument must be …, null given" warnings.
	 *
	 * resolveAlias() returns a canonical name for *every* registered page, but
	 * Language::getSpecialPageAliases() only contains pages that ship a localised alias, so
	 * the per-page lookup must tolerate a missing key.
	 */
	public function testNoWarningForPageWithoutAlias() {
		$html = $this->executeWithStub( 'NoAliasRegressionT429584', 'other', true );

		$this->assertStringContainsString( 'NoAliasRegressionT429584', $html );
	}

	/**
	 * A page that does have aliases (T219543) exposes each one as a lowercased,
	 * underscore-free data-search-index-N attribute for the client-side search.
	 */
	public function testAliasesAreRenderedAsSearchIndexAttributes() {
		// Already-lowercase aliases to keep the test independent of the output language's lc()
		$html = $this->executeWithStub(
			'AliasRegressionT429584',
			'other',
			false,
			[ 'AliasRegressionT429584' => [ 'aliasregressionalpha', 'alias_regression_beta' ] ]
		);

		$this->assertStringContainsString( 'data-search-index-0="aliasregressionalpha"', $html );
		$this->assertStringContainsString( 'data-search-index-1="alias regression beta"', $html );
	}

	/**
	 * A group name containing '/' is rendered as a nested <h3> subgroup heading rather than a
	 * top-level <h2> group heading.
	 */
	public function testSubgroupRendersAsSubgroupHeading() {
		$html = $this->executeWithStub( 'SubgroupRegressionT429584', 'testgroup/testsub', true );

		$this->assertStringContainsString( '<h3 class="mw-specialpagessubgroup"', $html );
	}

}

/**
 * Minimal listed special page used by the SpecialSpecialPages tests. Its name and group are
 * configurable so a single stub can drive the no-alias, alias, and subgroup cases.
 */
class SpecialSpecialPagesStub extends SpecialPage {

	private string $groupName;

	public function __construct( string $name = 'SpecialSpecialPagesStub', string $group = 'other' ) {
		parent::__construct( $name );
		$this->groupName = $group;
	}

	protected function getGroupName() {
		return $this->groupName;
	}

	public function execute( $par ) {
		$this->getOutput()->addWikiTextAsInterface( 'stub' );
	}
}
