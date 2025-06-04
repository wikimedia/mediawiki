<?php

namespace MediaWiki\Tests\Structure;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\DerivativeContext;
use MediaWiki\ResourceLoader\Module;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Wikimedia\DependencyStore\DependencyStore;
use Wikimedia\ObjectCache\HashBagOStuff;
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

	public static function provideBundleSize() {
		$file = static::getBundleSizeConfigData();
		$content = json_decode( file_get_contents( $file ), true );

		if ( !is_array( $content ) ) {
			throw new RuntimeException( "Failed to load JSON from $file" );
		}

		foreach ( $content as $testCase ) {
			yield $testCase['resourceModule'] => [ $testCase ];
		}
	}

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
	 * @dataProvider provideBundleSize
	 * @coversNothing
	 */
	public function testBundleSize( $testCase ) {
		$this->assertArrayHasKey( 'resourceModule', $testCase );
		$moduleName = $testCase['resourceModule'];

		$this->assertTrue(
			array_key_exists( 'maxSizeUncompressed', $testCase )
				xor array_key_exists( 'maxSize', $testCase ),
			'Exactly one of "maxSize" or "maxSizeUncompressed" must be defined for module ' .
				$moduleName . '.'
		);

		$maxSizeUncompressed = $testCase['maxSizeUncompressed'] ?? null;
		$maxSize = $testCase['maxSize'] ?? null;
		$projectName = $testCase['projectName'] ?? '';

		if ( $maxSize === null && $maxSizeUncompressed === null ) {
			$this->markTestSkipped( "The module $moduleName has opted out of bundle size testing." );
		}

		$maxSize = self::normalizeSize( $maxSize );
		$maxSizeUncompressed = self::normalizeSize( $maxSizeUncompressed );

		$resourceLoader = MediaWikiServices::getInstance()->getResourceLoader();
		$resourceLoader->setDependencyStore( new DependencyStore( new HashBagOStuff() ) );
		$request = new FauxRequest(
			[
				'lang' => 'en',
				'modules' => $moduleName,
				'skin' => $this->getSkinName(),
			]
		);

		$context = new Context( $resourceLoader, $request );
		$module = $resourceLoader->getModule( $moduleName );
		$contentContext = new DerivativeContext( $context );
		$contentContext->setOnly(
			$module->getType() === Module::LOAD_STYLES
				? Module::TYPE_STYLES
				: Module::TYPE_COMBINED
		);
		$content = $resourceLoader->makeModuleResponse( $context, [ $moduleName => $module ] );
		$contentTransferSizeUncompressed = strlen( $content );
		$contentTransferSize = strlen( gzencode( $content, 9 ) );
		$contentTransferSize -= array_sum( self::CORE_SIZE_ADJUSTMENTS );
		if ( $maxSize !== null ) {
			$message = $projectName ?
				"$projectName: $moduleName should be less than $maxSize bytes (compressed)" :
				"$moduleName should be less than $maxSize bytes (compressed)";
			$this->assertLessThan( $maxSize, $contentTransferSize, $message );
		}
		if ( $maxSizeUncompressed !== null ) {
			$messageUncompressed = $projectName ?
				"$projectName: $moduleName should be less than $maxSizeUncompressed bytes (uncompressed)" :
				"$moduleName should be less than $maxSizeUncompressed (uncompressed) bytes";
			$this->assertLessThan( $maxSizeUncompressed, $contentTransferSizeUncompressed, $messageUncompressed );
		}
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
