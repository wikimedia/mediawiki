<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Language;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\LeximorphFactory;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Leximorph\Manager;
use Wikimedia\Leximorph\Provider;

/**
 * This test class verifies the behavior of the LeximorphFactory.
 *
 * @group Language
 * @covers \MediaWiki\Language\LeximorphFactory
 * @author Doğu Abaris (abaris@null.net)
 */
class LeximorphFactoryTest extends MediaWikiIntegrationTestCase {

	private LeximorphFactory $factory;

	protected function setUp(): void {
		parent::setUp();

		$options = new ServiceOptions(
			LeximorphFactory::CONSTRUCTOR_OPTIONS, [ MainConfigNames::UseLeximorph => true ]
		);

		$this->factory = new LeximorphFactory(
			$options, new NullLogger()
		);
	}

	public function testGetManagerCachesByCode(): void {
		$code = new Bcp47CodeValue( 'en' );

		$m1 = $this->factory->getManager( $code );
		$m2 = $this->factory->getManager( $code );

		$this->assertSame( $m1, $m2 );
	}

	public function testGetProviderCachesByCode(): void {
		$code = new Bcp47CodeValue( 'en' );

		$p1 = $this->factory->getProvider( $code );
		$p2 = $this->factory->getProvider( $code );

		$this->assertSame( $p1, $p2 );
	}

	public function testDifferentCodesYieldDifferentInstances(): void {
		$mEn = $this->factory->getManager( new Bcp47CodeValue( 'en' ) );
		$mDe = $this->factory->getManager( new Bcp47CodeValue( 'tr' ) );
		$this->assertNotSame( $mEn, $mDe, 'Different codes => different Manager' );

		$pEn = $this->factory->getProvider( new Bcp47CodeValue( 'en' ) );
		$pDe = $this->factory->getProvider( new Bcp47CodeValue( 'tr' ) );
		$this->assertNotSame( $pEn, $pDe, 'Different codes => different Provider' );
	}

	public function testRepeatedCallsReturnSameInstance(): void {
		$m1 = $this->factory->getManager( new Bcp47CodeValue( 'tr' ) );
		$m2 = $this->factory->getManager( new Bcp47CodeValue( 'tr' ) );
		$this->assertSame( $m1, $m2, 'LRU caches Manager instances per code' );

		$p1 = $this->factory->getProvider( new Bcp47CodeValue( 'tr' ) );
		$p2 = $this->factory->getProvider( new Bcp47CodeValue( 'tr' ) );
		$this->assertSame( $p1, $p2, 'LRU caches Provider instances per code' );
	}

	public function testDisabledFlagReturnsNull(): void {
		$options = new ServiceOptions(
			LeximorphFactory::CONSTRUCTOR_OPTIONS, [ MainConfigNames::UseLeximorph => false ]
		);
		$factory = new LeximorphFactory(
			$options, new NullLogger()
		);

		$this->assertNull( $factory->getManager( new Bcp47CodeValue( 'en' ) ) );
		$this->assertNull( $factory->getProvider( new Bcp47CodeValue( 'en' ) ) );
	}

	public function testEnabledFlagCreatesInstances(): void {
		$this->assertInstanceOf(
			Manager::class,
			$this->factory->getManager( new Bcp47CodeValue( 'en' ) )
		);
		$this->assertInstanceOf(
			Provider::class,
			$this->factory->getProvider( new Bcp47CodeValue( 'en' ) )
		);
	}

	public function testCacheEvictionWhenCapacityExceeded(): void {
		$instances = [];
		for ( $i = 0; $i < 120; $i++ ) {
			$instances[] = $this->factory->getManager( new Bcp47CodeValue( 'x' . $i ) );
		}
		$newFirst = $this->factory->getManager( new Bcp47CodeValue( 'x0' ) );
		$this->assertNotSame( $instances[0], $newFirst );
	}
}
