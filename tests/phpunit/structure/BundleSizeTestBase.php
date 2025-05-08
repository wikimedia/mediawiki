<?php

namespace MediaWiki\Tests\Structure;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\DerivativeContext;
use MediaWiki\ResourceLoader\Module;
use MediaWikiIntegrationTestCase;
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
		$content = json_decode( file_get_contents( static::getBundleSizeConfigData() ), true );

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
		}
		return $maxSize;
	}

	/**
	 * @dataProvider provideBundleSize
	 * @coversNothing
	 */
	public function testBundleSize( $testCase ) {
		$maxSizeUncompressed = $testCase['maxSizeUncompressed'] ?? null;
		$maxSize = $testCase['maxSize'] ?? null;
		$projectName = $testCase['projectName'] ?? '';
		$moduleName = $testCase['resourceModule'];
		if ( $maxSize === null && $maxSizeUncompressed === null ) {
			$this->markTestSkipped( "The module $moduleName has opted out of bundle size testing." );
			return;
		}
		$this->assertFalse(
			$maxSize !== null && $maxSizeUncompressed !== null,
			'Only maxSize or maxSizeCompressed should be defined for module ' . $moduleName . '. Only defined maxSizeCompressed.'
		);
		if ( is_string( $maxSize ) ) {
			$maxSize = self::stringToFloat( $maxSize );
		}
		if ( is_string( $maxSizeUncompressed ) ) {
			$maxSizeUncompressed = self::stringToFloat( $maxSizeUncompressed );
		}
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
		if ( $maxSize ) {
			$message = $projectName ?
				"$projectName: $moduleName is less than $maxSize" :
				"$moduleName is less than $maxSize";
			$this->assertLessThan( $maxSize, $contentTransferSize, $message );
		}
		if ( $maxSizeUncompressed ) {
			$messageUncompressed = $projectName ?
				"$projectName: $moduleName is less than $maxSize (uncompressed)" :
				"$moduleName is less than $maxSizeUncompressed";
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
