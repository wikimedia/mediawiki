<?php

use MediaWiki\Actions\RawAction;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Article;
use MediaWiki\Request\FauxRequest;

/**
 * @covers \MediaWiki\Actions\RawAction
 * @group Database
 */
class RawActionTest extends MediaWikiIntegrationTestCase {

	private function getCacheControl( array $query ): string {
		$this->overrideConfigValue( MainConfigNames::CdnMaxAge, 18000 );
		$page = $this->getExistingTestPage( 'Help:RawActionTest' );

		$context = new RequestContext();
		$context->setTitle( $page->getTitle() );
		$context->setRequest( new FauxRequest( [ 'action' => 'raw' ] + $query ) );
		$context->setUser( $this->getServiceContainer()->getUserFactory()->newAnonymous() );

		$action = $this->getServiceContainer()->getActionFactory()
			->getAction( 'raw', Article::newFromWikiPage( $page, $context ), $context );
		$this->assertInstanceOf( RawAction::class, $action );

		ob_start();
		$action->onView();
		ob_end_clean();

		return $context->getRequest()->response()->getHeader( 'Cache-Control' );
	}

	public static function provideCacheControl() {
		yield 'defaults' => [ [], 'public, s-maxage=0, max-age=18000' ];
		yield 'valid maxage and smaxage' => [
			[ 'maxage' => '600', 'smaxage' => '60' ],
			'public, s-maxage=60, max-age=600'
		];
		// T345609: Cache-Control does not allow negative values
		yield 'negative maxage uses the default' => [
			[ 'maxage' => '-42' ],
			'public, s-maxage=0, max-age=18000'
		];
		yield 'negative smaxage uses the default' => [
			[ 'smaxage' => '-5' ],
			'public, s-maxage=0, max-age=18000'
		];
	}

	/**
	 * @dataProvider provideCacheControl
	 */
	public function testCacheControl( array $query, string $expected ) {
		$this->assertSame( $expected, $this->getCacheControl( $query ) );
	}
}
