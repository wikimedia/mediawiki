<?php

namespace MediaWiki\Tests\Actions;

use MediaWiki\Actions\DeleteAction;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Page\Article;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Actions\DeleteAction
 * @group Action
 * @group Database
 */
class DeleteActionTest extends MediaWikiIntegrationTestCase {

	private function newDeleteAction(): DeleteAction {
		$title = Title::makeTitle( NS_MEDIAWIKI, 'CTest.js' );
		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setTitle( $title );
		$context->setUser( $this->getTestUser()->getUser() );
		$context->setRequest( new FauxRequest() );
		$context->setLanguage( 'qqx' );
		return new DeleteAction( Article::newFromWikiPage( $wikiPage, $context ), $context );
	}

	public function testHandleRetrievedDataStoresStashedData(): void {
		$action = $this->newDeleteAction();
		$wrapper = TestingAccessWrapper::newFromObject( $action );

		$payload = [
			'wpReason' => 'test reason',
			'wpDeleteReasonList' => 'other',
			'wpSuppress' => '1',
			'wpConfirmationRevId' => '42',
		];
		$wrapper->handleRetrievedData( $payload );

		$this->assertSame( $payload, $wrapper->stashedData );
	}

	public function testGetDeleteReasonFromValuesFreeFormOnly(): void {
		$action = $this->newDeleteAction();
		$wrapper = TestingAccessWrapper::newFromObject( $action );

		$reason = $wrapper->getDeleteReasonFromValues( [
			'wpDeleteReasonList' => 'other',
			'wpReason' => 'free-form text',
		] );

		$this->assertSame( 'free-form text', $reason );
	}

	public function testGetDeleteReasonFromValuesListOnly(): void {
		$action = $this->newDeleteAction();
		$wrapper = TestingAccessWrapper::newFromObject( $action );

		$reason = $wrapper->getDeleteReasonFromValues( [
			'wpDeleteReasonList' => 'vandalism',
			'wpReason' => '',
		] );

		$this->assertSame( 'vandalism', $reason );
	}

	public function testGetDeleteReasonFromValuesCombinesListAndFreeForm(): void {
		$action = $this->newDeleteAction();
		$wrapper = TestingAccessWrapper::newFromObject( $action );

		$reason = $wrapper->getDeleteReasonFromValues( [
			'wpDeleteReasonList' => 'vandalism',
			'wpReason' => 'additional details',
		] );

		$this->assertStringStartsWith( 'vandalism', $reason );
		$this->assertStringContainsString( 'additional details', $reason );
	}

	public function testGetDeleteReasonFromValuesDefaultsToOther(): void {
		$action = $this->newDeleteAction();
		$wrapper = TestingAccessWrapper::newFromObject( $action );

		// No wpDeleteReasonList in values — defaults to 'other', so return the
		// (empty) free-form reason.
		$reason = $wrapper->getDeleteReasonFromValues( [] );

		$this->assertSame( '', $reason );
	}

	public function testGetStashKeyForTitle(): void {
		$action = $this->newDeleteAction();
		$wrapper = TestingAccessWrapper::newFromObject( $action );

		$this->assertSame(
			'delete:MediaWiki:CTest.js',
			$wrapper->getStashKeyForTitle()
		);
	}
}
