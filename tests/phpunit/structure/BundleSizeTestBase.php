<?php

namespace MediaWiki\Tests\Structure;

use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;

/**
 * Compare bundle sizes from each skin/extension bundlesize.config.json with ResourceLoader output.
 *
 * Extensions and skins can subclass this and override getTestCases with just their own bundlesize
 * file. This allows one to run that test suite by its own, for faster CLI feedback.
 */
abstract class BundleSizeTestBase extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();
		$db = $this->createMock( IDatabase::class );
		$db->method( 'getSessionLagStatus' )->willReturn( [ 'lag' => 0, 'since' => 0 ] );
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getReplicaDatabase' )->willReturn( $db );
		$this->setService( 'DBLoadBalancerFactory', $lbFactory );
	}

	/**
	 * Adjustments for bundle size increases caused by core, to avoid breaking
	 * previously introduced extension tests.
	 */
	private const CORE_SIZE_ADJUSTMENTS = [
		'mw.loader.impl' => 17
	];

	private static function stringToFloat( string $maxSize ): float {
		if ( str_contains( $maxSize, 'KB' ) || str_contains( $maxSize, 'kB' ) ) {
			$maxSize = (float)str_replace( [ 'KB', 'kB', ' KB', ' kB' ], '', $maxSize );
			$maxSize = $maxSize * 1024;
		} elseif ( str_contains( $maxSize, 'B' ) ) {
			$maxSize = (float)str_replace( [ ' B', 'B' ], '', $maxSize );
		} else {
			$maxSize = (float)$maxSize;
		}

		return $maxSize;
	}

	private static function normalizeSize( $maxSize ): ?float {
		if ( $maxSize === null ) {
			return null;
		}

		if ( is_string( $maxSize ) ) {
			$floatSize = self::stringToFloat( $maxSize );
		} else {
			$floatSize = (float)$maxSize;
		}

		Assert::assertGreaterThan(
			0,
			$floatSize,
			'Expected "' . $maxSize . '" to convert to a number grater than 0'
		);

		return $floatSize;
	}

	/**
	 * @coversNothing
	 */
	public function testBundleSize() {
		$file = static::getBundleSizeConfigData();
		$content = json_decode( file_get_contents( $file ), true );

		if ( !is_array( $content ) ) {
			throw new RuntimeException( "Failed to load JSON from $file" );
		}

		$failures = [];
		$resourceLoader = $this->getServiceContainer()->getResourceLoader();
		$skin = $this->getSkinName();
		foreach ( $content as $i => $testCase ) {
			if ( !isset( $testCase['resourceModule'] ) ) {
				$failures[] = 'Missing key "resourceModule" at item at #' . $i;
				continue;
			}

			$moduleName = $testCase['resourceModule'];
			$moduleMessage = $this->verifyBundleSize(
				$resourceLoader,
				$skin,
				$moduleName,
				$testCase
			);

			if ( $moduleMessage !== null ) {
				$failures[] = $moduleMessage;
			}
		}

		$this->assertSame( [], $failures, 'Errors with bundle sizes for some modules, sizes defined in ' . $file );
	}

	private function verifyBundleSize(
		ResourceLoader $resourceLoader,
		string $skin,
		string $moduleName,
		array $testCase
	): ?string {
		if ( !( array_key_exists( 'maxSizeUncompressed', $testCase )
			xor array_key_exists( 'maxSize', $testCase ) )
		) {
			return 'Exactly one of "maxSize" or "maxSizeUncompressed" must be defined for module ' .
				$moduleName . '.';
		}

		$maxSizeUncompressed = $testCase['maxSizeUncompressed'] ?? null;
		$maxSize = $testCase['maxSize'] ?? null;

		if ( $maxSize === null && $maxSizeUncompressed === null ) {
			// The module has opted out of bundle size testing.
			return null;
		}

		$maxSize = self::normalizeSize( $maxSize );
		$maxSizeUncompressed = self::normalizeSize( $maxSizeUncompressed );

		$request = new FauxRequest(
			[
				'lang' => 'en',
				'modules' => $moduleName,
				'skin' => $skin,
			]
		);

		$context = new Context( $resourceLoader, $request );
		$module = $resourceLoader->getModule( $moduleName );
		$content = $resourceLoader->makeModuleResponse( $context, [ $moduleName => $module ] );
		if ( $maxSize !== null ) {
			$contentTransferSize = strlen( gzencode( $content, 9 ) );
			$contentTransferSize -= array_sum( self::CORE_SIZE_ADJUSTMENTS );
			$kilobytes = round( $contentTransferSize / 1024, 1, PHP_ROUND_HALF_UP );
			if ( $maxSize < $contentTransferSize ) {
				return "$moduleName should be less than $maxSize bytes (compressed), but is $kilobytes kB";
			}
		}
		if ( $maxSizeUncompressed !== null ) {
			$contentTransferSizeUncompressed = strlen( $content );
			$kilobytes = round( $contentTransferSizeUncompressed / 1024, 1, PHP_ROUND_HALF_UP );
			if ( $maxSizeUncompressed < $contentTransferSizeUncompressed ) {
				return "$moduleName should be less than $maxSizeUncompressed (uncompressed) bytes, but is $kilobytes kB";
			}
		}
		return null;
	}

	/**
	 * @return string Path to bundlesize.config.json
	 */
	abstract public static function getBundleSizeConfigData(): string;

	/**
	 * @return string Skin name
	 */
	public function getSkinName(): string {
		return $this->getConfVar( MainConfigNames::DefaultSkin );
	}

}
