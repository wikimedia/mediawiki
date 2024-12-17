<?php

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\Module;
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
		$contentTransferSize = strlen( gzencode( $content, 9 ) );
		return $contentTransferSize;
	}

	/**
	 * Prepares a skin for testing, assigning context and output page
	 *
	 * @param string $skinName
	 *
	 * @return \Skin
	 * @throws \SkinException
	 */
	protected function prepareSkin( string $skinName ): \Skin {
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
		$sizeKb = ceil( ( $size * 10 ) / 1024 ) / 10;
		$warning = "Total size of $moduleType modules is " . $sizeKb . "kB.\n" .
			"If you are adding code on page load, please reduce $moduleType that you are loading on page load.\n" .
			"Read https://www.mediawiki.org/wiki/Performance_budgeting for more context on this number.\n\n";
		print( $warning );
		$this->markTestSkipped( 'Tests are non-blocking for now.' );
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
		$this->testModuleSizes( $skinName, $moduleStyles );
		$this->testModuleSizes( $skinName, $moduleScripts, true );
	}
}
