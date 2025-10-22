<?php

namespace MediaWiki\Tests\SpecialPage;

use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Status\Status;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @covers \MediaWiki\SpecialPage\QueryPage
 */
class QueryPageTest extends MediaWikiUnitTestCase {

	/** @var HttpRequestFactory|MockObject */
	private $httpRequestFactory;

	/** @var QueryPage|MockObject */
	private $queryPage;

	/** @var Config */
	private $config;

	protected function setUp(): void {
		parent::setUp();

		$this->config = new HashConfig( [
			MainConfigNames::ExternalQuerySources => [
				'TestQueryPage' => [
					'enabled' => true,
					'url' => 'https://example.com/api/test',
					'timeout' => 10
				]
			]
		] );

		$this->queryPage = $this->getMockBuilder( QueryPage::class )
			->disableOriginalConstructor()
			->onlyMethods( [
				'getName',
				'getConfig',
				'usesExternalSource',
				'getQueryInfo',
				'execute',
				'formatResult',
				'isExpensive',
				'isSyndicated',
				'reallyDoQueryInternal'
			] )
			->getMock();

		$this->queryPage->method( 'getName' )->willReturn( 'TestQueryPage' );
		$this->queryPage->method( 'getConfig' )->willReturn( $this->config );
		$this->queryPage->method( 'usesExternalSource' )->willReturn( true );
		$this->queryPage->method( 'getQueryInfo' )->willReturn( [] );
		$this->queryPage->method( 'isExpensive' )->willReturn( false );
		$this->queryPage->method( 'isSyndicated' )->willReturn( false );

		$this->httpRequestFactory = $this->createMock( HttpRequestFactory::class );

		$reflection = new ReflectionProperty( QueryPage::class, 'httpRequestFactory' );
		$reflection->setAccessible( true );
		$reflection->setValue( $this->queryPage, $this->httpRequestFactory );
	}

	private function mockHttpRequest( string $content, bool $isOK = true, string $errorMessage = '' ): void {
		$request = $this->createMock( 'MWHttpRequest' );

		$status = $this->createMock( Status::class );
		$status->method( 'isOK' )->willReturn( $isOK );
		$status->method( 'getMessage' )->willReturn( $errorMessage );

		$request->method( 'execute' )->willReturn( $status );
		$request->method( 'getContent' )->willReturn( $content );

		$this->httpRequestFactory->method( 'create' )->willReturn( $request );
	}

	public function testUsesExternalSourceWhenEnabled() {
		$testData = [
			[ 'qc_namespace' => 0, 'qc_title' => 'Test', 'qc_value' => 1 ]
		];
		$this->mockHttpRequest( json_encode( $testData ) );

		$reflection = new ReflectionClass( $this->queryPage );
		$method = $reflection->getMethod( 'reallyDoQueryExternal' );
		$method->setAccessible( true );

		$result = $method->invokeArgs( $this->queryPage, [ 10, 0 ] );

		$this->assertInstanceOf( IResultWrapper::class, $result );
		$this->assertCount( 1, $result );
	}

	public function testHandlesEmptyResponse() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Empty response' );

		$reflection = new ReflectionClass( $this->queryPage );
		$method = $reflection->getMethod( 'reallyDoQueryExternal' );
		$method->setAccessible( true );

		$request = $this->createMock( 'MWHttpRequest' );
		$message = $this->createMock( Message::class );
		$message->method( 'text' )->willReturn( 'Empty response' );

		$status = $this->createMock( Status::class );
		$status->method( 'isOK' )->willReturn( false );
		$status->method( 'getMessage' )->willReturn( $message );
		$request->method( 'execute' )->willReturn( $status );
		$request->method( 'getContent' )->willReturn( '' );
		$this->httpRequestFactory->method( 'create' )->willReturn( $request );

		$method->invokeArgs( $this->queryPage, [ 10, 0 ] );
	}

	public function testHandlesInvalidJson() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Invalid JSON' );

		$reflection = new ReflectionClass( $this->queryPage );
		$method = $reflection->getMethod( 'reallyDoQueryExternal' );
		$method->setAccessible( true );

		$this->mockHttpRequest( 'invalid json' );
		$method->invokeArgs( $this->queryPage, [ 10, 0 ] );
	}

	public function testHandlesNonArrayResponse() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Expected array' );

		$reflection = new ReflectionClass( $this->queryPage );
		$method = $reflection->getMethod( 'reallyDoQueryExternal' );
		$method->setAccessible( true );

		$this->mockHttpRequest( '"not an array"' );
		$method->invokeArgs( $this->queryPage, [ 10, 0 ] );
	}

	public function testSkipsInvalidRows() {
		$testData = [
			[ 'qc_namespace' => 0, 'qc_title' => 'Valid', 'qc_value' => 1 ],
			'invalid row',
			[ 'missing' => 'fields' ]
		];

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Invalid row data' );

		$reflection = new ReflectionClass( $this->queryPage );
		$method = $reflection->getMethod( 'reallyDoQueryExternal' );
		$method->setAccessible( true );

		$this->mockHttpRequest( json_encode( $testData ) );
		$method->invokeArgs( $this->queryPage, [ 10, 0 ] );
	}

	public function testUsesConfiguredTimeout() {
		$testData = [
			[ 'qc_namespace' => 0, 'qc_title' => 'Test', 'qc_value' => 1 ]
		];

		$request = $this->createMock( 'MWHttpRequest' );
		$status = $this->createMock( Status::class );
		$status->method( 'isOK' )->willReturn( true );
		$request->method( 'execute' )->willReturn( $status );
		$request->method( 'getContent' )->willReturn( json_encode( $testData ) );

		$this->httpRequestFactory->expects( $this->once() )
			->method( 'create' )
			->with(
				'https://example.com/api/test',
				$this->callback( static function ( $options ) {
					return isset( $options['timeout'] ) && $options['timeout'] === 10;
				} ),
				$this->anything()
			)
			->willReturn( $request );

		$reflection = new ReflectionClass( $this->queryPage );
		$method = $reflection->getMethod( 'reallyDoQueryExternal' );
		$method->setAccessible( true );

		$result = $method->invokeArgs( $this->queryPage, [ 10, 0 ] );

		$this->assertInstanceOf( IResultWrapper::class, $result );
	}
}
