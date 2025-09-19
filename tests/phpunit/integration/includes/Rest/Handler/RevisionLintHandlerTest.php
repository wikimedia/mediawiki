<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Hook\ParserLogLinterDataHook;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\RevisionLintHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;

/**
 * @covers \MediaWiki\Rest\Handler\RevisionLintHandler
 * @group Database
 */
class RevisionLintHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use PageHandlerTestTrait;
	use LintHandlerTestTrait;

	private const CLEAN_WIKITEXT = 'Hello \'\'\'World\'\'\'';
	private const CLEAN_WIKITEXT_LINTS = '[]';
	private const CLEAN_WIKITEXT_TAGS = [];

	private const BROKEN_WIKITEXT = 'Broken <table><table>';
	private const BROKEN_WIKITEXT_LINTS = '[{"type":"missing-end-tag","dsr":[14,21,7,0],"templateInfo":null,"params":{"name":"table","inTable":true}},{"type":"deletable-table-tag","dsr":[14,21,0,0],"templateInfo":null,"params":{"name":"table"}}]';
	private const BROKEN_WIKITEXT_TAGS = [ '"missing-end-tag"', '"deletable-table-tag"' ];

	/**
	 * @param Parsoid|MockObject|null $parsoid
	 *
	 * @return RevisionLintHandler
	 */
	private function newHandler( ?Parsoid $parsoid = null ): RevisionLintHandler {
		return $this->newRevisionLintHandler( null, $parsoid );
	}

	public function testExecuteWillNotLogErrors() {
		$this->overrideConfigValue( MainConfigNames::ParsoidSettings, [
			'linting' => true
		] );

		$mockHandler = $this->createMock( ParserLogLinterDataHook::class );
		$mockHandler->expects( $this->never() )
			->method( 'onParserLogLinterData' );

		$this->setTemporaryHook(
			'ParserLogLinterData',
			$mockHandler
		);

		$page = $this->getExistingTestPage( 'LintEndpointTestPage/with/slashes' );
		$this->executeRevisionLintRequest( $page->getLatest() );
	}

	public static function provideWikiNonRedirect() {
		yield 'good page' => [ self::CLEAN_WIKITEXT, self::CLEAN_WIKITEXT_LINTS ];
		yield 'broken page' => [ self::BROKEN_WIKITEXT, self::BROKEN_WIKITEXT_LINTS ];
	}

	/**
	 * @dataProvider provideWikiNonRedirect
	 */
	public function testExecuteNonRedirectPage( $wikitext, $expectedJSON ) {
		$page = $this->getExistingTestPage( 'LintEndpointTestPage/with/slashes' );
		$this->assertStatusGood( $this->editPage( $page, $wikitext ), 'Edited a page' );

		$response = $this->executeRevisionLintRequest( $page->getLatest() );

		$this->assertSame( 200, $response->getStatusCode() );

		$jsonResponse = (string)$response->getBody();
		$this->assertSame( $expectedJSON, $jsonResponse );
	}

	public static function provideWikiRedirect() {
		yield 'redirect page (clean)' => [ self::CLEAN_WIKITEXT, self::CLEAN_WIKITEXT_LINTS ];
		yield 'redirect page (broken)' => [ self::BROKEN_WIKITEXT, self::BROKEN_WIKITEXT_LINTS ];
	}

	/**
	 * @dataProvider provideWikiRedirect
	 */
	public function testExecuteRedirectPage(
		$footerWikitext,
		$expectedErrorTags
	) {
		$redirect = $this->getExistingTestPage( 'HtmlEndpointTestPage/redirect' );
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/target' );

		$this->editPage(
			$redirect,
			"#REDIRECT [[{$page->getTitle()->getPrefixedDBkey()}]]\n" .
			$footerWikitext
		);

		$response = $this->executeRevisionLintRequest( $redirect->getLatest() );

		$this->assertSame( 200, $response->getStatusCode() );

		$jsonErrors = (string)$response->getBody();
		if ( $expectedErrorTags === [] ) {
			$this->assertSame( '', $jsonErrors );
		} elseif ( is_array( $expectedErrorTags ) ) {
			foreach ( $expectedErrorTags as $errorTag ) {
				$this->assertStringContainsString( $errorTag, $jsonErrors );
			}
		}
	}

	/**
	 * Assert that we return the local page content even if an associated remote
	 * file description page exists (T353688).
	 */
	public function testRemoteDescriptionWithExistingFilePage() {
		$name = 'JustSomeSillyFile.png';

		$this->installMockFileRepo( $name );

		$pageName = "File:$name";
		$status = $this->editPage( $pageName, self::BROKEN_WIKITEXT );

		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $status->getNewRevision()->getId() ] ]
		);
		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request );

		$this->assertSame( 200, $response->getStatusCode() );

		$jsonErrors = (string)$response->getBody();

		$this->assertSame( self::BROKEN_WIKITEXT_LINTS, $jsonErrors );
	}

	public static function provideHandlesParsoidError() {
		yield 'ClientError' => [
			new ClientError( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-lint-backend-error' ),
				400,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
		yield 'ResourceLimitExceededException' => [
			new ResourceLimitExceededException( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-resource-limit-exceeded' ),
				413,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
	}

	/**
	 * @dataProvider provideHandlesParsoidError
	 */
	public function testHandlesParsoidError(
		Exception $parsoidException,
		Exception $expectedException
	) {
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/with/slashes' );
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $page->getLatest() ] ]
		);

		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2lint' ] );
		$parsoid->expects( $this->once() )
			->method( 'wikitext2lint' )
			->willThrowException( $parsoidException );

		$handler = $this->newHandler( $parsoid );
		$this->expectExceptionObject( $expectedException );
		$this->executeHandler( $handler, $request );
	}

	public function testExecuteWithMissingParam() {
		$request = new RequestData();

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "paramvalidator-missingparam", [ 'id' ] ),
				400
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	public function testExecuteWithMissingRevision() {
		$request = new RequestData( [ 'pathParams' => [ 'id' => 850285205 ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-revision", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

}
