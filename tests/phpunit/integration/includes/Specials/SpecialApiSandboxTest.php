<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @covers \MediaWiki\Specials\SpecialApiSandbox
 */
class SpecialApiSandboxTest extends SpecialPageTestBase {
	use MockAuthorityTrait;

	/** @dataProvider provideExecute */
	public function testExecute( array $authorityRights, bool $expectedApiHighLimitsValue ): void {
		$context = RequestContext::getMain();
		$context->setAuthority( $this->mockRegisteredAuthorityWithPermissions( $authorityRights ) );
		$context->setLanguage( 'qqx' );
		[ $html ] = $this->executeSpecialPage( '', null, null, null, false, $context );

		$jsConfigVars = $context->getOutput()->getJsConfigVars();
		$this->assertArrayHasKey( 'apihighlimits', $jsConfigVars );
		$this->assertSame( $expectedApiHighLimitsValue, $jsConfigVars['apihighlimits'] );

		$this->assertArrayContains(
			[ 'mediawiki.special', 'mediawiki.hlist' ],
			$context->getOutput()->getModuleStyles()
		);
		$this->assertArrayContains(
			[ 'mediawiki.special.apisandbox', 'mediawiki.apipretty' ],
			$context->getOutput()->getModules()
		);

		$apiSandboxHtml = $this->assertSelectorMatchesOneElement( $html, '#mw-apisandbox' );
		$this->assertStringContainsString( 'apisandbox-jsonly', $apiSandboxHtml );
	}

	public static function provideExecute(): array {
		return [
			'Lacks any rights' => [
				'authorityRights' => [],
				'expectedApiHighLimitsValue' => false,
			],
			'Has the apihighlimits right' => [
				'authorityRights' => [ 'apihighlimits' ],
				'expectedApiHighLimitsValue' => true,
			],
		];
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'ApiSandbox' );
	}
}
