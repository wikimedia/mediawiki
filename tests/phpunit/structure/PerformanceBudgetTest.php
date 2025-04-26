<?php

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Output\OutputPage;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\Module;
use MediaWiki\Skin\Skin;
use MediaWiki\Skin\SkinException;
use MediaWiki\User\User;

/**
 * @group Database
 * @coversNothing
 */
class PerformanceBudgetTest extends MediaWikiIntegrationTestCase {
	/**
	 * Calculates the size of a module
	 *
	 * @param array $moduleNames
	 * @param string $skinName
	 * @param bool $isScripts
	 *
	 * @return float|int
	 * @throws \Wikimedia\RequestTimeout\TimeoutException
	 * @throws MediaWiki\Config\ConfigException
	 */
	protected function getContentTransferSize( $moduleNames, $skinName, $isScripts = false ) {
		// Calculate Size
		$resourceLoader = $this->getServiceContainer()->getResourceLoader();
		$request = new FauxRequest(
			[
				'lang' => 'en',
				'modules' => implode( '|', $moduleNames ),
				'skin' => $skinName,
			]
		);
		$modules = [];
		foreach ( $moduleNames as $moduleName ) {
			$modules[ $moduleName ] = $resourceLoader->getModule( $moduleName );
		}

		$context = new Context( $resourceLoader, $request );
		$contentContext = new \MediaWiki\ResourceLoader\DerivativeContext( $context );
		$contentContext->setOnly(
			$isScripts
				? Module::TYPE_COMBINED
				: Module::TYPE_STYLES
		);
		// Create a module response for the given module and calculate the size
		$content = $resourceLoader->makeModuleResponse( $contentContext, $modules );
		return [
			'compressed' => strlen( gzencode( $content, 9 ) ),
			'uncompressed' => strlen( $content ),
		];
	}

	/**
	 * Prepares a skin for testing, assigning context and output page
	 *
	 * @param string $skinName
	 *
	 * @return Skin
	 * @throws SkinException
	 */
	protected function prepareSkin( string $skinName ): Skin {
		$skinFactory = $this->getServiceContainer()->getSkinFactory();
		$skin = $skinFactory->makeSkin( $skinName );
		$title = $this->getExistingTestPage()->getTitle();
		$context = new DerivativeContext( RequestContext::getMain() );
		$anon = new User();
		$context->setUser( $anon );
		$context->setTitle( $title );
		$context->setSkin( $skin );
		$outputPage = new OutputPage( $context );
		$context->setOutput( $outputPage );
		$skin->setContext( $context );
		$outputPage->setTitle( $title );
		$outputPage->output( true );
		return $skin;
	}

	/**
	 * Converts a string to bytes
	 *
	 * @param string|int|float $size
	 *
	 * @return float|int
	 */
	private function getSizeInBytes( $size ) {
		if ( is_string( $size ) ) {
			if ( strpos( $size, 'KB' ) !== false || strpos( $size, 'kB' ) !== false ) {
				$size = (float)str_replace( [ 'KB', 'kB', ' KB', ' kB' ], '', $size );
				$size = $size * 1024;
			} elseif ( strpos( $size, 'B' ) !== false ) {
				$size = (float)str_replace( [ ' B', 'B' ], '', $size );
			}
		}
		return $size;
	}

	/**
	 * @param string $skinName
	 * @param array $moduleNames
	 * @param bool $isScripts
	 */
	private function testModuleSizes( $skinName, $moduleNames, $isScripts = false ) {
		$size = $this->getContentTransferSize( $moduleNames, $skinName, $isScripts );

		$moduleType = $isScripts ? 'scripts' : 'styles';
		$sizeKb = ceil( ( $size['compressed'] * 10 ) / 1024 ) / 10;
		$sizeKbUncompressed = ceil( ( $size['uncompressed'] * 10 ) / 1024 ) / 10;
		$warning = "Total size of $moduleType modules is " . $sizeKb . "kB ( $sizeKbUncompressed kB uncompressed).\n" .
			"If you are adding code on page load, please reduce $moduleType that you are loading on page load.\n" .
			"Read https://www.mediawiki.org/wiki/Performance_budgeting for more context on this number.\n\n";
		print( $warning );
	}

	/**
	 * Find all bundle size configs in all repos and create a way to look up
	 * the bundle size for a given module.
	 */
	private function getBudgetConfig(): array {
		$installed = ExtensionRegistry::getInstance()->getAllThings();
		$allModules = [];

		// Add MediaWiki core's own config file.
		$installed['mw-core'] = [
			'path' => getcwd() . '/package.json',
		];
		foreach ( $installed as $key => $install ) {
			$configFile = dirname( $install['path'] ) . '/bundlesize.config.json';
			if ( file_exists( $configFile ) ) {
				$bundleSizeConfig = json_decode( file_get_contents( $configFile ), true );
				foreach ( $bundleSizeConfig as $moduleBundle ) {
					$module = $moduleBundle['resourceModule'] ?? null;
					if ( $module ) {
						// defaults to 0 if not defined for some reason
						$maxSize = $this->getSizeInBytes(
							$moduleBundle['maxSize'] ?? '0 KB'
						);
						$allModules[$module] = $maxSize;
					}
				}
			}
		}
		return $allModules;
	}

	/**
	 * @param string $skinName
	 * @param array $moduleNames
	 * @param bool $isScripts
	 */
	private function testForUnexpectedModules( $skinName, $moduleNames, $isScripts = false ) {
		$budgetConfig = $this->getBudgetConfig();
		$undefinedModules = [];
		foreach ( $moduleNames as $moduleName ) {
			$expectedModuleSize = $budgetConfig[ $moduleName ] ?? false;
			if ( $expectedModuleSize === false ) {
				$undefinedModules[] = $moduleName;
			}
		}
		$debugInformation = "⚠️ PLEASE DO NOT SKIP THIS TEST ⚠️\n\n" .
			"If this is blocking a merge this might signal a potential performance regression with the desktop site.\n\n" .
			"All extensions/skins adding code to page load for an article must monitor their ResourceLoader modules.\n\n" .
			"Read https://www.mediawiki.org/wiki/Performance_budgeting for guidance on how to suppress this error message.\n\n" .
			"The following modules have not declared budgets:\n\n" .
			implode( "\n", $undefinedModules ) .
			"\n";
		$this->assertCount( 0, $undefinedModules, $debugInformation );
	}

	/**
	 * Tests the size of modules in allowed skins
	 *
	 * @coversNothing
	 *
	 * @return void
	 * @throws \Wikimedia\RequestTimeout\TimeoutException
	 * @throws MediaWiki\Config\ConfigException
	 */
	public function testTotalModulesSize() {
		$skinName = 'vector-2022';
		$skin = $this->prepareSkin( $skinName );
		$moduleStyles = $skin->getOutput()->getModuleStyles();
		$moduleScripts = $skin->getOutput()->getModules();
		$this->testForUnexpectedModules( $skinName, $moduleStyles );
		$this->testForUnexpectedModules( $skinName, $moduleScripts, true );
		$this->testModuleSizes( $skinName, $moduleStyles );
		$this->testModuleSizes( $skinName, $moduleScripts, true );
		$this->markTestSkipped( 'Tests are non-blocking for now.' );
	}
}
