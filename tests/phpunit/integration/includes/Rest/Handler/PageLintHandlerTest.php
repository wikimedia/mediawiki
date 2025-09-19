<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Hook\ParserLogLinterDataHook;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\PageLintHandler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;

/**
 * @covers \MediaWiki\Rest\Handler\PageLintHandler
 * @group Database
 */
class PageLintHandlerTest extends MediaWikiIntegrationTestCase {
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
	 * @return PageLintHandler
	 */
	private function newHandler( ?Parsoid $parsoid = null ): PageLintHandler {
		return $this->newPageLintHandler( null, $parsoid );
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
		$this->executePageLintRequest( $page );
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

		$response = $this->executePageLintRequest( $page );

		$this->assertSame( 200, $response->getStatusCode() );

		$jsonResponse = (string)$response->getBody();
		$this->assertSame( $expectedJSON, $jsonResponse );
	}

	public static function provideWikiRedirect() {
		yield 'follow wiki redirects per default (clean)' => [
			[], self::CLEAN_WIKITEXT, 307, null
		];
		yield 'follow wiki redirects per default (broken)' => [
			[], self::BROKEN_WIKITEXT, 307, null
		];
		yield 'bad redirect param' => [
			[ 'redirect' => 'wrong' ], self::CLEAN_WIKITEXT, 400, null
		];
		yield 'redirect=no' => [
			[ 'redirect' => 'no' ], self::BROKEN_WIKITEXT, 200, self::BROKEN_WIKITEXT_TAGS
		];
		yield 'redirect=false' => [
			[ 'redirect' => 'false' ], self::BROKEN_WIKITEXT, 200, self::BROKEN_WIKITEXT_TAGS
		];
		yield 'redirect=true' =>
			[ [ 'redirect' => 'true' ], self::BROKEN_WIKITEXT, 307, null
		];
	}

	/**
	 * @dataProvider provideWikiRedirect
	 */
	public function testExecuteRedirectPage(
		$params,
		$footerWikitext,
		$expectedStatus,
		$expectedErrorTags
	) {
		$redirect = $this->getExistingTestPage( 'HtmlEndpointTestPage/redirect' );
		$page = $this->getExistingTestPage( 'HtmlEndpointTestPage/target' );

		$this->editPage(
			$redirect,
			"#REDIRECT [[{$page->getTitle()->getPrefixedDBkey()}]]\n" .
			$footerWikitext
		);

		try {
			$response = $this->executePageLintRequest( $redirect, $params );

			$this->assertSame( $expectedStatus, $response->getStatusCode() );

			$jsonErrors = (string)$response->getBody();
			if ( $expectedErrorTags === [] ) {
				$this->assertSame( '', $jsonErrors );
			} elseif ( is_array( $expectedErrorTags ) ) {
				foreach ( $expectedErrorTags as $errorTag ) {
					$this->assertStringContainsString( $errorTag, $jsonErrors );
				}
			}
		} catch ( HttpException $ex ) {
			$this->assertSame( $expectedStatus, $ex->getCode() );
		}
	}

	/**
	 * Assert that we return a 404 even if an associated remote file description
	 * page exists (T353688).
	 */
	public function testRemoteDescriptionWithNonexistentFilePage() {
		$name = 'JustSomeSillyFile.png';

		$this->installMockFileRepo( $name );

		$page = $this->getNonexistingTestPage( "File:$name" );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedDBkey() ] ]
		);
		$handler = $this->newHandler();
		$exception = $this->executeHandlerAndGetHttpException( $handler, $request );

		$this->assertSame( 404, $exception->getCode() );
	}

	/**
	 * Assert that we return the local page content even if an associated remote
	 * file description page exists (T353688).
	 */
	public function testRemoteDescriptionWithExistingFilePage() {
		$name = 'JustSomeSillyFile.png';

		$this->installMockFileRepo( $name );

		$pageName = "File:$name";
		$this->editPage( $pageName, self::BROKEN_WIKITEXT );

		$request = new RequestData(
			[ 'pathParams' => [ 'title' => $pageName ] ]
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
			[ 'pathParams' => [ 'title' => $page->getTitle()->getPrefixedText() ] ]
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
				new MessageValue( "paramvalidator-missingparam", [ 'title' ] ),
				400
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	public function testExecuteWithMissingPage() {
		$request = new RequestData( [ 'pathParams' => [ 'title' => 'DoesNotExist8237456assda1234' ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-title", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

}
