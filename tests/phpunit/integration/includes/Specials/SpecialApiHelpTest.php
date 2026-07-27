<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Specials\SpecialPageExecutor;
use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialApiHelp
 * @group Database
 */
class SpecialApiHelpTest extends SpecialPageTestBase {

	/** @dataProvider provideViewingSpecialPageRedirectsToHelpAction */
	public function testViewingSpecialPageRedirectsToHelpAction(
		string $subPage,
		array $expectedRedirectQueryParams
	): void {
		$context = RequestContext::getMain();
		[ $html ] = $this->executeSpecialPage( $subPage, null, null, null, false, $context );

		$this->assertSame(
			$this->getServiceContainer()->getUrlUtils()->expand( wfAppendQuery(
				wfScript( 'api' ), array_merge( [ 'action' => 'help' ], $expectedRedirectQueryParams )
			) ),
			$context->getOutput()->getRedirect()
		);
		$this->assertSame( '', $html );
	}

	public static function provideViewingSpecialPageRedirectsToHelpAction(): array {
		return [
			'Root page' => [ 'subPage' => '', 'expectedRedirectQueryParams' => [ 'modules' => 'main' ] ],
			'Subpage for edit module' => [
				'subPage' => 'edit',
				'expectedRedirectQueryParams' => [ 'modules' => 'edit' ],
			],
			'Subpage for query module with submodules' => [
				'subPage' => 'sub/query',
				'expectedRedirectQueryParams' => [ 'submodules' => '1', 'modules' => 'query' ],
			],
			'Subpage for main module with recursivesubmodules' => [
				'subPage' => 'rsub/main',
				'expectedRedirectQueryParams' => [ 'recursivesubmodules' => '1', 'modules' => 'main' ],
			],
		];
	}

	public function testViewingWhenIncludedInOtherPageRendersApiHelp(): void {
		// Use SpecialPageExecutor directly so that we can set including on the SpecialPage instance it executes
		$specialPageExecutor = new SpecialPageExecutor();
		$specialPage = $this->newSpecialPage();
		$specialPage->including( true );
		[ $html ] = $specialPageExecutor->executeSpecialPage( $specialPage, 'edit' );

		$apiHelpHeaderName = $this->assertSelectorMatchesOneElement( $html, '.apihelp-module-name' );
		$this->assertStringContainsString( 'action=edit', $apiHelpHeaderName );
	}

	public function testViewingWhenIncludedInOtherPageWithUnknownModule(): void {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );

		// Use SpecialPageExecutor directly so that we can set including on the SpecialPage instance it executes
		$specialPageExecutor = new SpecialPageExecutor();
		$specialPage = $this->newSpecialPage();
		$specialPage->including( true );
		[ $html ] = $specialPageExecutor->executeSpecialPage( $specialPage, 'unknown-api-module' );

		$this->assertStringContainsString( '(apihelp-no-such-module: unknown-api-module)', $html );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'ApiHelp' );
	}
}
