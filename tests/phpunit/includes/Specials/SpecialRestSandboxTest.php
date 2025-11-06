<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialRestSandbox;

/**
 * TODO: Add tests confirming the interactive portion of the sandbox loads properly
 *
 * @covers \MediaWiki\Specials\SpecialRestSandbox
 */
class SpecialRestSandboxTest extends SpecialPageTestBase {
	protected function setUp(): void {
		parent::setUp();

		$scriptPath = $this->getConfVar( MainConfigNames::ScriptPath );
		$this->overrideConfigValues( [
			MainConfigNames::RestSandboxSpecs => [
				'mw' => [
					'url' => $scriptPath . '/rest.php/specs/v0/module/-',
					'name' => 'MediaWiki REST API',
				]
			]
		] );
	}

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialRestSandbox
	 */
	protected function newSpecialPage() {
		return new SpecialRestSandbox( $this->getServiceContainer()->getUrlUtils() );
	}

	public function testEnglishDisclaimerNotPresent() {
		[ $html ] = $this->executeSpecialPage( '', null, 'en' );
		$this->assertStringNotContainsString( 'cdx-message--notice', $html );
	}

	public function testNonEnglishDisclaimerPresent() {
		[ $html ] = $this->executeSpecialPage( '', null, 'qqx' );
		$this->assertStringContainsString( 'cdx-message--notice', $html );
	}
}
