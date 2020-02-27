<?php

namespace MediaWiki\Tests\Rest\Handler;

use HashConfig;
use Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler\SearchHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MockSearchResultSet;
use PHPUnit\Framework\MockObject\MockObject;
use SearchEngine;
use SearchEngineFactory;
use SearchResult;
use Status;
use User;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\SearchHandler
 */
class SearchHandlerTest extends \MediaWikiUnitTestCase {

	use HandlerTestTrait;

	private function newHandler(
		$query,
		$titleResult,
		$textResult
	) {
		$config = new HashConfig( [
			'SearchType' => 'test',
			'SearchTypeAlternatives' => [],
			'NamespacesToBeSearchedDefault' => [ NS_MAIN => true ],
		] );

		$language = $this->createNoOpMock( Language::class );
		$searchEngineConfig = new \SearchEngineConfig( $config, $language );

		/** @var PermissionManager|MockObject $permissionManager */
		$permissionManager = $this->createNoOpMock(
			PermissionManager::class, [ 'userCan' ]
		);
		$permissionManager->method( 'userCan' )
			->willReturnCallback( function ( $action, User $user, LinkTarget $page ) {
				return !preg_match( '/Forbidden/', $page->getText() );
			} );

		/** @var SearchEngine|MockObject $searchEngine */
		$searchEngine = $this->createMock( SearchEngine::class );
		$searchEngine->method( 'searchTitle' )
			->with( $query )
			->willReturn( $titleResult );
		$searchEngine->method( 'searchText' )
			->with( $query )
			->willReturn( $textResult );

		/** @var SearchEngineFactory|MockObject $searchEngineFactory */
		$searchEngineFactory = $this->createNoOpMock( SearchEngineFactory::class, [ 'create' ] );
		$searchEngineFactory->method( 'create' )
			->willReturn( $searchEngine ); // TODO

		return new SearchHandler(
			$permissionManager,
			$searchEngineFactory,
			$searchEngineConfig
		);
	}

	/**
	 * @return SearchResult
	 */
	private function makeMockSearchResult(
		$pageName,
		$textSnippet = 'Lorem Ipsum',
		$broken = false,
		$missing = false
	) {
		$title = $this->makeMockTitle( $pageName );

		/** @var SearchResult|MockObject $result */
		$result = $this->createNoOpMock( SearchResult::class, [
			'getTitle', 'isBrokenTitle', 'isMissingRevision', 'getTextSnippet'
		] );
		$result->method( 'getTitle' )->willReturn( $title );
		$result->method( 'getTextSnippet' )->willReturn( $textSnippet );
		$result->method( 'isBrokenTitle' )->willReturn( $broken );
		$result->method( 'isMissingRevision' )->willReturn( $missing );

		return $result;
	}

	public function testExecute() {
		$titleResults = new MockSearchResultSet( [
			$this->makeMockSearchResult( 'Foo', 'one' ),
			$this->makeMockSearchResult( 'Forbidden Foo', 'two' ), // forbidden
			$this->makeMockSearchResult( 'FooBar', 'three' ),
			$this->makeMockSearchResult( 'Foo Moo', 'four', true, false ), // missing
		] );
		$textResults = new MockSearchResultSet( [
			$this->makeMockSearchResult( 'Quux', 'one' ),
			$this->makeMockSearchResult( 'Forbidden Quux', 'two' ), // forbidden
			$this->makeMockSearchResult( 'Xyzzy', 'three' ),
			$this->makeMockSearchResult( 'Yookoo', 'four', false, true ), // broken
		] );

		$query = 'foo';
		$request = new RequestData( [ 'queryParams' => [ 'q' => $query ] ] );

		$handler = $this->newHandler( $query, $titleResults, $textResults );
		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertArrayHasKey( 'pages', $data );
		$this->assertCount( 4, $data['pages'] );
		$this->assertSame( 'Foo', $data['pages'][0]['title'] );
		$this->assertSame( 'one', $data['pages'][0]['excerpt'] );
		$this->assertSame( 'FooBar', $data['pages'][1]['title'] );
		$this->assertSame( 'three', $data['pages'][1]['excerpt'] );
		$this->assertSame( 'Quux', $data['pages'][2]['title'] );
		$this->assertSame( 'one', $data['pages'][2]['excerpt'] );
		$this->assertSame( 'Xyzzy', $data['pages'][3]['title'] );
		$this->assertSame( 'three', $data['pages'][3]['excerpt'] );
	}

	public function testExecute_status() {
		$titleResults = Status::newGood( new MockSearchResultSet( [
			$this->makeMockSearchResult( 'Foo', 'one' ),
			$this->makeMockSearchResult( 'FooBar', 'three' ),
		] ) );
		$textResults = Status::newGood( new MockSearchResultSet( [
			$this->makeMockSearchResult( 'Quux', 'one' ),
			$this->makeMockSearchResult( 'Xyzzy', 'three' ),
		] ) );

		$query = 'foo';
		$request = new RequestData( [ 'queryParams' => [ 'q' => $query ] ] );

		$handler = $this->newHandler( $query, $titleResults, $textResults );
		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertArrayHasKey( 'pages', $data );
		$this->assertCount( 4, $data['pages'] );
	}

	public function testExecute_missingparam() {
		$titleResults = Status::newFatal( 'testing' );
		$textResults = new MockSearchResultSet( [] );

		$query = '';
		$request = new RequestData( [ 'queryParams' => [ 'q' => $query ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "paramvalidator-missingparam", [ 'q' ] ),
				400
			)
		);

		$handler = $this->newHandler( $query, $titleResults, $textResults );
		$this->executeHandler( $handler, $request );
	}

	public function testExecute_error() {
		$titleResults = Status::newFatal( 'testing' );
		$textResults = new MockSearchResultSet( [] );

		$query = 'foo';
		$request = new RequestData( [ 'queryParams' => [ 'q' => $query ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( "rest-search-error", [ 'testing' ] ) )
		);

		$handler = $this->newHandler( $query, $titleResults, $textResults );
		$this->executeHandler( $handler, $request );
	}

	public function testNeedsWriteWriteAccess() {
		$titleResults = new MockSearchResultSet( [] );
		$textResults = new MockSearchResultSet( [] );

		$handler = $this->newHandler( '', $titleResults, $textResults );
		$this->assertTrue( $handler->needsReadAccess() );
		$this->assertFalse( $handler->needsWriteAccess() );
	}

}
