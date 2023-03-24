<?php

use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Shell\ShellboxClientFactory;
use Shellbox\RPC\RpcClient;

/**
 * @group Shell
 * @covers MediaWiki\Shell\ShellboxClientFactory
 */
class ShellboxClientFactoryTest extends MediaWikiUnitTestCase {

	/** @dataProvider provideEnabledArgs */
	public function testIsEnabled( ?array $urls, ?string $service, bool $expected ): void {
		$shellboxClientFactory = new ShellboxClientFactory(
			$this->createMock( HttpRequestFactory::class ),
			$urls,
			'key'
		);

		$actual = $shellboxClientFactory->isEnabled( $service );

		$this->assertSame( $expected, $actual );
	}

	public static function provideEnabledArgs(): iterable {
		yield 'not configured, default service' => [
			'urls' => null,
			'service' => null,
			'expected' => false,
		];

		yield 'not configured, custom service' => [
			'urls' => null,
			'service' => 'custom',
			'expected' => false,
		];

		yield 'default configured, default service' => [
			'urls' => [ 'default' => 'http://example.com' ],
			'service' => null,
			'expected' => true,
		];

		yield 'default configured, custom service' => [
			'urls' => [ 'default' => 'http://example.com' ],
			'service' => 'custom',
			'expected' => true,
		];

		yield 'custom configured, custom service' => [
			'urls' => [ 'custom' => 'http://example.com' ],
			'service' => 'custom',
			'expected' => true,
		];

		yield 'custom disabled, default service' => [
			'urls' => [
				'default' => 'http://example.com',
				'custom' => false,
			],
			'service' => 'default',
			'expected' => true,
		];

		yield 'custom disabled, custom service' => [
			'urls' => [
				'default' => 'http://example.com',
				'custom' => false,
			],
			'service' => 'custom',
			'expected' => false,
		];
	}

	public function testIsEnabledWithoutKey(): void {
		$shellboxClientFactory = new ShellboxClientFactory(
			$this->createMock( HttpRequestFactory::class ),
			[ 'default' => 'http://example.com' ],
			null
		);

		$this->assertFalse( $shellboxClientFactory->isEnabled() );
	}

	public function testGetRemoteRpcClientNotEnabled() {
		$shellboxClientFactory = new ShellboxClientFactory(
			$this->createMock( HttpRequestFactory::class ),
			null,
			'key'
		);
		$this->expectException( RuntimeException::class );
		$shellboxClientFactory->getRemoteRpcClient();
	}

	public function testGetRpcClientNotEnabled() {
		$shellboxClientFactory = new ShellboxClientFactory(
			$this->createMock( HttpRequestFactory::class ),
			null,
			'key'
		);
		$this->assertInstanceOf( RpcClient::class, $shellboxClientFactory->getRpcClient() );
	}
}
