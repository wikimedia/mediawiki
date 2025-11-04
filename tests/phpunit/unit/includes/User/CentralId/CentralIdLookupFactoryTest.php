<?php

namespace MediaWiki\Tests\User\CentralId;

use InvalidArgumentException;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use MediaWiki\User\CentralId\LocalIdLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityLookup;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @covers \MediaWiki\User\CentralId\CentralIdLookupFactory
 */
class CentralIdLookupFactoryTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	private CentralIdLookup $centralLookupMock;

	protected function setUp(): void {
		parent::setUp();
		$this->centralLookupMock = $this->getMockForAbstractClass( CentralIdLookup::class );
	}

	private function makeFactory(): CentralIdLookupFactory {
		$services = [
			'DBLoadBalancerFactory' => $this->createMock( IConnectionProvider::class ),
			'MainConfig' => new HashConfig( [
				MainConfigNames::SharedDB => null,
				MainConfigNames::SharedTables => [],
				MainConfigNames::LocalDatabases => [],
			] ),
			'HideUserUtils' => new HideUserUtils()
		];
		$localIdLookupTest = [
			'class' => LocalIdLookup::class,
			'services' => [
				'MainConfig',
				'DBLoadBalancerFactory',
				'HideUserUtils',
			]
		];
		return new CentralIdLookupFactory(
			new ServiceOptions(
				CentralIdLookupFactory::CONSTRUCTOR_OPTIONS,
				[
					MainConfigNames::CentralIdLookupProviders => [
						'local' => $localIdLookupTest,
						'local2' => $localIdLookupTest,
						'mock' => [ 'factory' => function () {
							return $this->centralLookupMock;
						} ]
					],
					MainConfigNames::CentralIdLookupProvider => 'mock',
				]
			),
			$this->getDummyObjectFactory( $services ),
			$this->createNoOpMock( UserIdentityLookup::class ),
			$this->createNoOpMock( UserFactory::class )
		);
	}

	public function testCreation() {
		$factory = $this->makeFactory();

		$this->assertSame( $this->centralLookupMock, $factory->getLookup() );
		$this->assertSame( $this->centralLookupMock, $factory->getLookup( 'mock' ) );
		$this->assertSame( 'mock', $this->centralLookupMock->getProviderId() );

		$local = $factory->getLookup( 'local' );
		$this->assertNotSame( $this->centralLookupMock, $local );
		$this->assertInstanceOf( LocalIdLookup::class, $local );
		$this->assertSame( $local, $factory->getLookup( 'local' ) );
		$this->assertSame( 'local', $local->getProviderId() );

		$local2 = $factory->getLookup( 'local2' );
		$this->assertNotSame( $local, $local2 );
		$this->assertInstanceOf( LocalIdLookup::class, $local2 );
		$this->assertSame( 'local2', $local2->getProviderId() );
	}

	public function testUnconfiguredProvidersThrow() {
		$this->expectException( InvalidArgumentException::class );
		$factory = $this->makeFactory();
		$factory->getLookup( 'unconfigured' );
	}

	public function testGetNonLocal() {
		$factory = $this->makeFactory();
		$this->assertInstanceOf( CentralIdLookup::class, $factory->getNonLocalLookup() );
		$this->assertNull( $factory->getNonLocalLookup( 'local' ) );
	}

	public function testGetProviderIds() {
		$factory = $this->makeFactory();
		$expected = [ 'local', 'local2', 'mock' ];
		$this->assertSame( $expected, $factory->getProviderIds() );
	}

	public function testGetDefaultProviderId() {
		$factory = $this->makeFactory();
		$this->assertSame( 'mock', $factory->getDefaultProviderId() );
	}
}
