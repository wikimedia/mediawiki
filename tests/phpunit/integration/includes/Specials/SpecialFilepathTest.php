<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialFilepath
 */
class SpecialFilepathTest extends SpecialPageTestBase {

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Filepath' );
	}

	public static function provideRedirects() {
		return [
			'no file name: empty form without errors' => [ '', 'file', [] ],
			'valid file name' => [ 'Example.jpg', 'file/Example.jpg', [] ],
			'invalid file name: form with the input prefilled' => [ 'Foo<bar', 'file', [ 'wpvalue' => 'Foo<bar' ] ],
		];
	}

	/**
	 * @dataProvider provideRedirects
	 */
	public function testRedirect( string $subpage, string $expectedSubpage, array $expectedQuery ) {
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );

		$this->executeSpecialPage( $subpage, null, null, null, false, $context );

		$this->assertSame(
			SpecialPage::getTitleFor( 'Redirect', $expectedSubpage )->getFullUrlForRedirect( $expectedQuery ),
			$context->getOutput()->getRedirect()
		);
	}
}
