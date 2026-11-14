<?php

namespace MediaWiki\Tests\Specials;

use MediaWiki\Request\FauxRequest;

/**
 * @covers \MediaWiki\Specials\SpecialChangeContentModel
 * @group Database
 */
class SpecialChangeContentModelTest extends SpecialPageTestBase {

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'ChangeContentModel' );
	}

	private function newTitleFormRequest( string $pageTitle ): FauxRequest {
		// SpecialPageExecutor adds the edit token for posted requests
		return new FauxRequest( [
			'pagetitle' => $pageTitle,
			'wpFormIdentifier' => 'titleform',
		], wasPosted: true );
	}

	public static function provideTitlesThatCannotExist() {
		yield 'special page' => [ 'Special:SpecialPages' ];
		yield 'media link' => [ 'Media:Example.png' ];
	}

	/**
	 * @dataProvider provideTitlesThatCannotExist
	 */
	public function testTitleThatCannotExist( string $pageTitle ) {
		$user = $this->getTestSysop()->getUser();

		// Explain that the page can't be created, not that the user lacks a right (T444618)
		[ $html ] = $this->executeSpecialPage( '', $this->newTitleFormRequest( $pageTitle ), 'qqx', $user );

		$this->assertStringContainsString( '(htmlform-title-not-creatable: ' . $pageTitle . ')', $html );
		$this->assertStringNotContainsString( '(changecontentmodel-model-label)', $html );
	}

	public function testTitleThatCanExist() {
		$user = $this->getTestSysop()->getUser();

		[ $html ] = $this->executeSpecialPage(
			'', $this->newTitleFormRequest( 'Help:ChangeContentModel test' ), 'qqx', $user
		);

		$this->assertStringContainsString( '(changecontentmodel-model-label)', $html );
		$this->assertStringNotContainsString( 'htmlform-title-not-creatable', $html );
	}
}
