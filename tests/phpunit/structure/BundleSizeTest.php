<?php

namespace MediaWiki\Tests\Structure;

use HashBagOStuff;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\DerivativeContext;
use MediaWiki\ResourceLoader\Module;
use MediaWikiIntegrationTestCase;
use Wikimedia\DependencyStore\KeyValueDependencyStore;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;

/**
 * Compare bundle sizes from each skin/extension bundlesize.config.json with ResourceLoader output.
 *
 * Extensions and skins can subclass this and override getTestCases with just their own bundlesize
 * file. This allows one to run that test suite by its own, for faster CLI feedback.
 */
abstract class BundleSizeTest extends MediaWikiIntegrationTestCase {
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

	public function provideBundleSize() {
		foreach ( json_decode( file_get_contents( $this->getBundleSizeConfig() ), true ) as $testCase ) {
			yield $testCase['resourceModule'] => [ $testCase ];
		}
	}

	/**
	 * @dataProvider provideBundleSize
	 * @coversNothing
	 */
	public function testBundleSize( $testCase ) {
		$maxSize = $testCase['maxSize'];
		$projectName = $testCase['projectName'] ?? '';
		$moduleName = $testCase['resourceModule'];
		if ( is_string( $maxSize ) ) {
			if ( strpos( $maxSize, 'KB' ) !== false || strpos( $maxSize, 'kB' ) !== false ) {
				$maxSize = (float)str_replace( [ 'KB', 'kB', ' KB', ' kB' ], '', $maxSize );
				$maxSize = $maxSize * 1024;
			} elseif ( strpos( $maxSize, 'B' ) !== false ) {
				$maxSize = (float)str_replace( [ ' B', 'B' ], '', $maxSize );
			}
		}
		$resourceLoader = MediaWikiServices::getInstance()->getResourceLoader();
		$resourceLoader->setDependencyStore( new KeyValueDependencyStore( new HashBagOStuff() ) );
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
		$contentTransferSize = strlen( gzencode( $content, 9 ) );
		$contentTransferSize -= array_sum( self::CORE_SIZE_ADJUSTMENTS );
		$message = $projectName ?
			"$projectName: $moduleName is less than $maxSize" :
			"$moduleName is less than $maxSize";
		$this->assertLessThan( $maxSize, $contentTransferSize, $message );
	}

	/**
	 * @return string Path to bundlesize.config.json
	 */
	abstract public function getBundleSizeConfig(): string;

	/**
	 * @return string Skin name
	 */
	public function getSkinName(): string {
		return MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::DefaultSkin );
	}

}
