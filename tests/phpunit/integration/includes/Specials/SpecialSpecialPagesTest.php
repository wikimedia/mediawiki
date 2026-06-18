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
	 * Regression test for T429584: a listed special page whose canonical name has no
	 * content-language alias entry (e.g. many extension-provided pages such as
	 * TranslatorSignup) must not trigger "Undefined array key" /
	 * "foreach() argument must be ... null given" warnings.
	 *
	 * resolveAlias() returns a canonical name for *every* registered page, but
	 * Language::getSpecialPageAliases() only contains pages that ship a localised alias,
	 * so the per-page lookup must tolerate a missing key.
	 */
	public function testNoWarningForPageWithoutAlias() {
		$this->overrideConfigValues( [
			// Register a listed special page whose name has no alias in any content-language
			// specialPageAliases map, guaranteeing the no-alias code path is exercised.
			MainConfigNames::SpecialPages =>
				[ 'NoAliasRegressionT429584' => SpecialNoAliasRegressionStub::class ]
					+ MainConfigSchema::getDefaultValue( MainConfigNames::SpecialPages ),
				// A page without an alias also makes getLocalNameFor() emit a wfWarn() when
				// building its link, but to regression test T429584 we need to skip past that
				// warning
				MainConfigNames::DevelopmentWarnings => false,
			]
		);

		[ $html ] = $this->executeSpecialPage();

		$this->assertStringContainsString( 'NoAliasRegressionT429584', $html );
	}

}

/**
 * Minimal listed special page with no registered alias, used by the T429584 regression test.
 */
class SpecialNoAliasRegressionStub extends SpecialPage {

	public function __construct() {
		parent::__construct( 'NoAliasRegressionT429584' );
	}

	public function execute( $par ) {
		$this->getOutput()->addWikiTextAsInterface( 'stub' );
	}
}
