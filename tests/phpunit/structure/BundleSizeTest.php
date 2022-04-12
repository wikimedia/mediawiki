<?php

namespace MediaWiki\Tests\Structure;

use DerivativeResourceLoaderContext;
use FauxRequest;
use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;
use ResourceLoaderContext;
use ResourceLoaderModule;

/**
 * Compare bundle sizes from each skin/extension bundlesize.config.json with ResourceLoader output.
 *
 * Extensions and skins can subclass this and override getTestCases with just their own bundlesize
 * file. This allows one to run that test suite by its own, for faster CLI feedback.
 */
abstract class BundleSizeTest extends MediaWikiIntegrationTestCase {

	/**
	 * @coversNothing
	 */
	public function testBundleSize() {
		$bundleSizeConfig = json_decode( file_get_contents( $this->getBundleSizeConfig() ), true );
		foreach ( $bundleSizeConfig as $testCase ) {
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
			$request = new FauxRequest(
				[
					'lang' => 'en',
					'modules' => $moduleName,
					'skin' => $this->getSkinName(),
				]
			);

			$context = new ResourceLoaderContext( $resourceLoader, $request );
			$module = $resourceLoader->getModule( $moduleName );
			$contentContext = new DerivativeResourceLoaderContext( $context );
			$contentContext->setOnly(
				$module->getType() === ResourceLoaderModule::LOAD_STYLES
					? ResourceLoaderModule::TYPE_STYLES
					: ResourceLoaderModule::TYPE_COMBINED
			);
			$content = $resourceLoader->makeModuleResponse( $context, [ $moduleName => $module ] );
			$contentTransferSize = strlen( gzencode( $content, 9 ) );
			$message = $projectName ?
				"$projectName: $moduleName is less than $maxSize" :
				"$moduleName is less than $maxSize";
			$this->assertLessThan( $maxSize, $contentTransferSize, $message );
		}
	}

	/**
	 * @return string Path to bundlesize.config.json
	 */
	abstract public function getBundleSizeConfig(): string;

	/**
	 * @return string Skin name
	 */
	public function getSkinName(): string {
		return MediaWikiServices::getInstance()->getMainConfig()->get( 'DefaultSkin' );
	}

}
