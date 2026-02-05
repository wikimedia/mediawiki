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
	 * @var array
	 */
	private $cachedBudgetConfig = null;

	/**
	 * @var array
	 */
	private $dependencyOf = [];

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
		$this->addEndOfRunTestWarning( $warning );
	}

	/**
	 * Find all bundle size configs in all repos and create a way to look up
	 * the bundle size for a given module.
	 */
	private function getBudgetConfig(): array {
		if ( $this->cachedBudgetConfig ) {
			return $this->cachedBudgetConfig;
		}
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
						$allModules[$module] = [
							"maxSize" => $maxSize,
							"ignoreDependencies" => $moduleBundle['ignoreDependencies'] ?? false,
						];
					}
				}
			}
		}
		$this->cachedBudgetConfig = $allModules;
		return $allModules;
	}

	/**
	 * @param array $moduleNames
	 */
	private function testForUnexpectedModules( $moduleNames ) {
		$budgetConfig = $this->getBudgetConfig();
		$undefinedModules = [];
		foreach ( $moduleNames as $moduleName ) {
			$existingModule = $budgetConfig[ $moduleName ] ?? false;
			if ( $existingModule === false ) {
				$undefinedModules[] = $moduleName;
			}
		}

		// Check undefined modules for known exceptions.
		// This allows known offenders to fail but prevents against new offenders.
		// This block can be removed when https://phabricator.wikimedia.org/T395698 is resolved.
		$unexpectedModules = [];
		foreach ( $undefinedModules as $moduleName ) {
			$loadedByModules = $this->dependencyOf[ $moduleName ] ?? [];
			$unknownDependencies = array_filter(
				$loadedByModules,
				static function ( $moduleName ) use ( $undefinedModules ) {
					return !in_array(
						$moduleName,
						[
							// https://phabricator.wikimedia.org/T395698
							'wikibase.client.data-bridge.init'
						]
					) && !in_array( $moduleName, $undefinedModules );
				}
			);
			$isUnknown = count( $loadedByModules ) === 0 || count( $unknownDependencies ) > 0;
			if ( $isUnknown ) {
				$unexpectedModules[] = $moduleName;
			}
		}
		$undefinedModuleMessage = implode( "\n",
			array_map(
				function ( $moduleName ) {
					$loadedBy = implode( ",", $this->dependencyOf[ $moduleName ] ?? [ 'unknown' ] );
					return "$moduleName (loaded by $loadedBy )";
				},
				$unexpectedModules
			)
		);
		$debugInformation = "⚠️ PLEASE DO NOT SKIP THIS TEST ⚠️\n\n" .
			"If this is blocking a merge this might signal a potential performance regression with the desktop site.\n\n" .
			"All extensions/skins adding code to page load for an article must monitor their ResourceLoader modules.\n\n" .
			"Read https://www.mediawiki.org/wiki/Performance_budgeting for guidance on how to suppress this error message.\n\n" .
			"The following modules have not declared budgets:\n\n" .
			$undefinedModuleMessage .
			"\n";
		$this->assertCount( 0, $unexpectedModules, $debugInformation );
	}

	/**
	 * Expand a list of modules based on what modules they depend on.
	 *
	 * @param array $modules
	 * @param array $ignore a list of module names to not expand due to known issues.
	 * @return array
	 */
	private function expandWithModuleDependencies( $modules, $ignore = [] ) {
		$expandedModules = [];
		$budgetConfig = $this->getBudgetConfig();
		$resourceLoader = $this->getServiceContainer()->getResourceLoader();
		foreach ( $modules as $moduleName ) {
			$budgetDefinition = $budgetConfig[ $moduleName ] ?? [
				'ignoreDependencies' => false,
			];
			// Do not expand the module if it has a known issue!
			if ( in_array( $moduleName, $ignore ) ) {
				continue;
			}
			$expandedModules[] = $moduleName;
			$module = $resourceLoader->getModule( $moduleName );
			// Check module dependencies unless told otherwise.
			$dependencies = $budgetDefinition[ 'ignoreDependencies' ] ? [] :
				$module->getDependencies();
			$dependenciesExpanded = $this->expandWithModuleDependencies( $dependencies, $ignore );
			foreach ( $dependenciesExpanded as $dependencyName ) {
				if ( !isset( $this->dependencyOf[ $dependencyName ] ) ) {
					$this->dependencyOf[ $dependencyName ] = [];
				}
				$this->dependencyOf[ $dependencyName ][] = $moduleName;
				if ( !in_array( $dependencyName, $expandedModules ) ) {
					$expandedModules[] = $dependencyName;
				}
			}
		}
		return $expandedModules;
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
		$moduleScripts = $this->expandWithModuleDependencies(
			$skin->getOutput()->getModules(),
			// This list should be empty. If exceptions are needed they should have
			// an associated Phabricator ticket.
			[]
		);
		$this->testForUnexpectedModules( $moduleStyles );
		$this->testForUnexpectedModules( $moduleScripts );
		$this->testModuleSizes( $skinName, $moduleStyles );
		$this->testModuleSizes( $skinName, $moduleScripts, true );
		$this->markTestSkipped( 'Tests are non-blocking for now.' );
	}
}
