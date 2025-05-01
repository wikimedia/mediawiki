<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\EtcdSource;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Source\EtcdSource
 */
class EtcdSourceTest extends TestCase {
	use MediaWikiCoversValidator;

	public function testGetExpiryTtl() {
		$source = new EtcdSource();
		$this->assertSame( 10, $source->getExpiryTtl() );
	}

	public function testGetExpiryWeight() {
		$source = new EtcdSource();
		$this->assertSame( 1.0, $source->getExpiryWeight() );
	}

	public function testGetHashKey() {
		$source = new EtcdSource( [ 'host' => 'an.example' ] );

		$this->assertSame(
			'https://_etcd-client-ssl._tcp.an.example/v2/keys/mediawiki/?recursive=true',
			$source->getHashKey()
		);
	}

	public function testGetHashKeyNoDiscovery() {
		$source = new EtcdSource( [
			'host' => 'an.example',
			'discover' => false,
		] );

		$this->assertSame(
			'https://an.example:2379/v2/keys/mediawiki/?recursive=true',
			$source->getHashKey()
		);
	}

	public function testToString() {
		$source = new EtcdSource( [
			'host' => 'an.example',
			'discover' => false,
		] );

		$this->assertSame(
			'https://an.example:2379/v2/keys/mediawiki/?recursive=true',
			(string)$source
		);
	}

	public function testLoad() {
		$mapper = $this->mockCallable();
		$client = $this->mockClientWithResponses( [ [ 200, 'valid.json' ] ] );
		$resolver = $this->mockCallable();
		$settings = [
			'Fish/One' => 'fish',
			'Fish/Two' => 'fish',
			'MoreFish/Red' => 'fish',
			'MoreFish/Blue' => 'fish',
		];

		$source = new EtcdSource( [], $mapper, $client, $resolver );

		$mapper->expects( $this->once() )
			->method( '__invoke' )
			->with( $settings )
			->willReturn( $settings );

		$resolver->expects( $this->once() )
			->method( '__invoke' )
			->willReturn( [
				[ 'an.example', 4001 ]
			] );

		$this->assertSame(
			[
				'Fish/One' => 'fish',
				'Fish/Two' => 'fish',
				'MoreFish/Red' => 'fish',
				'MoreFish/Blue' => 'fish',
			],
			$source->load()
		);
	}

	/**
	 * @dataProvider provideServerFailures
	 */
	public function testLoadAllServersFailed( GuzzleException $exception ) {
		$client = $this->mockClientWithResponses( [
			$exception,
			$exception,
		] );
		$resolver = $this->mockCallable();

		$source = new EtcdSource( [], null, $client, $resolver );

		$resolver->expects( $this->once() )
			->method( '__invoke' )
			->willReturn( [
				[ 'bad.example', 123 ],
				[ 'bad.example', 321 ],
			] );

		$this->expectException( SettingsBuilderException::class );

		$source->load();
	}

	/**
	 * @dataProvider provideServerFailures
	 */
	public function testLoadSomeServersFailed( GuzzleException $exception ) {
		$client = $this->mockClientWithResponses( [
			$exception,
			[ 200, 'valid.json' ],
		] );
		$resolver = $this->mockCallable();

		$source = new EtcdSource( [], null, $client, $resolver );

		$resolver->expects( $this->once() )
			->method( '__invoke' )
			->willReturn( [
				[ 'bad.example', 123 ],
				[ 'ok.example', 321 ],
			] );

		$this->assertSame(
			[
				'Fish/One' => 'fish',
				'Fish/Two' => 'fish',
				'MoreFish/Red' => 'fish',
				'MoreFish/Blue' => 'fish',
			],
			$source->load()
		);
	}

	public function testLoadClientFailed() {
		$client = $this->mockClientWithResponses( [
			new ClientException(
				'bad request',
				new Request( 'GET', '/' ),
				new Response( 400, [] )
			)
		] );
		$resolver = $this->mockCallable();

		$source = new EtcdSource( [], null, $client, $resolver );

		$resolver->expects( $this->once() )
			->method( '__invoke' )
			->willReturn( [
				[ 'ok.example', 123 ],
				[ 'ok.example', 321 ],
			] );

		$this->expectException( SettingsBuilderException::class );

		$source->load();
	}

	public function testLoadNotAnEtcdDirectory() {
		$client = $this->mockClientWithResponses( [
			[ 200, 'notadirectory.json' ],
		] );
		$resolver = $this->mockCallable();

		$source = new EtcdSource( [], null, $client, $resolver );

		$resolver->expects( $this->once() )
			->method( '__invoke' )
			->willReturn( [
				[ 'an.example', 123 ],
			] );

		$this->expectException( SettingsBuilderException::class );

		$source->load();
	}

	/**
	 * All possible server-side exceptions.
	 */
	public function provideServerFailures(): array {
		return [
			[
				new ConnectException(
					'connection failure',
					new Request( 'GET', '/foo' )
				)
			],
			[
				new ServerException(
					'server side failure',
					new Request( 'GET', '/foo' ),
					new Response( 500, [], 'server error' )
				),
			],
		];
	}

	private function mockClientWithResponses( array $responses ): Client {
		return new Client( [
			'handler' => HandlerStack::create(
				new MockHandler( array_map( static function ( $response ) {
					return is_array( $response )
						? new Response(
							$response[0],
							[ 'content-type' => 'application/json' ],
							file_get_contents( __DIR__ . '/fixtures/etcd/' . $response[1] )
						)
						: $response;
				}, $responses ) )
			),
		] );
	}

	/**
	 * @return callable
	 */
	private function mockCallable() {
		return $this
			->getMockBuilder( __CLASS__ )
			->addMethods( [ '__invoke' ] )
			->getMock();
	}
}
