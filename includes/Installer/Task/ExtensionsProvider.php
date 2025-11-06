<?php

namespace MediaWiki\Installer\Task;

use AutoLoader;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\MainConfigSchema;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionProcessor;
use MediaWiki\Status\Status;

/**
 * A scheduled provider which loads extensions
 *
 * @internal For use by the installer
 */
class ExtensionsProvider extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'extensions';
	}

	/** @inheritDoc */
	public function getProvidedNames() {
		return [ 'HookContainer', 'VirtualDomains', 'ExtensionTaskSpecs' ];
	}

	public function execute(): Status {
		if ( !$this->getOption( 'Extensions' ) ) {
			$this->getContext()->provide( 'VirtualDomains', [] );
			$this->getContext()->provide( 'ExtensionTaskSpecs', [] );
			return Status::newGood();
		}

		// Marker for DatabaseUpdater::loadExtensions so we don't
		// double load extensions
		define( 'MW_EXTENSIONS_LOADED', true );

		$legacySchemaHooks = $this->getAutoExtensionLegacySchemaHooks();
		$data = $this->getAutoExtensionData();
		if ( isset( $data['globals']['wgHooks']['LoadExtensionSchemaUpdates'] ) ) {
			$legacySchemaHooks = array_merge( $legacySchemaHooks,
				$data['globals']['wgHooks']['LoadExtensionSchemaUpdates'] );
		}
		$extDeprecatedHooks = $data['attributes']['DeprecatedHooks'] ?? [];

		$legacyHooks = $legacySchemaHooks ? [ 'LoadExtensionSchemaUpdates' => $legacySchemaHooks ] : [];
		$this->getContext()->provide( 'HookContainer',
			new HookContainer(
				new StaticHookRegistry(
					$legacyHooks,
					$data['attributes']['Hooks'] ?? [],
					$extDeprecatedHooks
				),
				MediaWikiServices::getInstance()->getObjectFactory()
			)
		);
		$this->getContext()->provide( 'VirtualDomains',
			$data['attributes']['DatabaseVirtualDomains'] ?? [] );
		$this->getContext()->provide( 'ExtensionTaskSpecs',
			$data['attributes']['InstallerTasks'] ?? [] );

		return Status::newGood();
	}

	/**
	 * @return string
	 */
	protected function getExtensionsDir() {
		return MW_INSTALL_PATH . '/extensions';
	}

	/**
	 * @return string
	 */
	protected function getSkinsDir() {
		return MW_INSTALL_PATH . '/skins';
	}

	/**
	 * Auto-detect extensions with an old style .php registration file, load
	 * the extensions, and return the LoadExtensionSchemaUpdates legacy handlers.
	 *
	 * @return array
	 */
	private function getAutoExtensionLegacySchemaHooks() {
		$exts = $this->getOption( 'Extensions' );
		$extensionsDir = $this->getExtensionsDir();
		$files = [];
		foreach ( $exts as $e ) {
			if ( file_exists( "$extensionsDir/$e/$e.php" ) ) {
				$files[] = "$extensionsDir/$e/$e.php";
			}
		}

		if ( $files ) {
			return $this->includeExtensionFiles( $files );
		} else {
			return [];
		}
	}

	/**
	 * Include the specified extension PHP files. Populate $wgAutoloadClasses
	 * and return the LoadExtensionSchemaUpdates hooks.
	 *
	 * @param string[] $files
	 * @return string[] LoadExtensionSchemaUpdates legacy hooks
	 */
	private function includeExtensionFiles( $files ) {
		/**
		 * We need to define the $wgXyz variables before including extensions to avoid
		 * warnings about unset variables. However, the only thing we really
		 * want here is $wgHooks['LoadExtensionSchemaUpdates']. This won't work
		 * if the extension has hidden hook registration in $wgExtensionFunctions,
		 * but we're not opening that can of worms
		 * @see https://phabricator.wikimedia.org/T28857
		 */
		// Extract the defaults into the current scope
		foreach ( MainConfigSchema::listDefaultValues( 'wg' ) as $var => $value ) {
			$$var = $value;
		}

		// phpcs:ignore MediaWiki.VariableAnalysis.UnusedGlobalVariables
		global $IP, $wgAutoloadClasses, $wgExtensionDirectory, $wgStyleDirectory;
		$wgExtensionDirectory = $this->getExtensionsDir();
		$wgStyleDirectory = $this->getSkinsDir();

		foreach ( $files as $file ) {
			require_once $file;
		}

		// Ignore everyone else's hooks. Lord knows what someone might be doing
		// in ParserFirstCallInit (see T29171)
		// @phpcs:disable MediaWiki.VariableAnalysis.MisleadingGlobalNames.Misleading$wgHooks
		// @phpcs:ignore Generic.Files.LineLength.TooLong
		// @phan-suppress-next-line PhanUndeclaredVariable,PhanCoalescingAlwaysNull $wgHooks is defined by MainConfigSchema
		$hooksWeWant = $wgHooks['LoadExtensionSchemaUpdates'] ?? [];
		// @phpcs:enable MediaWiki.VariableAnalysis.MisleadingGlobalNames.Misleading$wgHooks
		return $hooksWeWant;
	}

	/**
	 * Auto-detect extensions with an extension.json file. Load the extensions,
	 * register classes with the autoloader and return the merged registry data.
	 *
	 * @return array
	 */
	private function getAutoExtensionData() {
		$exts = $this->getOption( 'Extensions' );

		$extensionProcessor = new ExtensionProcessor();
		foreach ( $exts as $e ) {
			$jsonPath = $this->getExtensionsDir() . "/$e/extension.json";
			if ( file_exists( $jsonPath ) ) {
				$extensionProcessor->extractInfoFromFile( $jsonPath );
			}
		}

		$autoload = $extensionProcessor->getExtractedAutoloadInfo();
		AutoLoader::loadFiles( $autoload['files'] );
		AutoLoader::registerClasses( $autoload['classes'] );
		AutoLoader::registerNamespaces( $autoload['namespaces'] );

		return $extensionProcessor->getExtractedInfo();
	}

}
