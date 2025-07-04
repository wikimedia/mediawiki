<?php

namespace MediaWiki\Installer\Task;

use AutoLoader;
use LogicException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\Installer\Installer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Status\Status;

/**
 * TODO: remove this and run web upgrade with normal startup (T386661)
 *
 * This is very similar to ExtensionTablesTask, except that it updates
 * core as well as extensions.
 */
class WebUpgradeExtensionsProvider extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'extensions';
	}

	/** @inheritDoc */
	public function getAliases() {
		return 'tables';
	}

	/** @inheritDoc */
	public function getProvidedNames() {
		return [ 'HookContainer', 'VirtualDomains', 'ExtensionTaskSpecs' ];
	}

	public function execute(): Status {
		$vars = Installer::getExistingLocalSettings();

		$registry = ExtensionRegistry::getInstance();
		$queue = $registry->getQueue();
		// Don't accidentally load extensions in the future
		$registry->clearQueue();

		// Read extension.json files
		$extInfo = $registry->readFromQueue( $queue );

		// Merge extension attribute hooks with hooks defined by a .php
		// registration file included from LocalSettings.php
		$legacySchemaHooks = $extInfo['globals']['wgHooks']['LoadExtensionSchemaUpdates'] ?? [];
		if ( $vars && isset( $vars['wgHooks']['LoadExtensionSchemaUpdates'] ) ) {
			$legacySchemaHooks = array_merge( $legacySchemaHooks, $vars['wgHooks']['LoadExtensionSchemaUpdates'] );
		}

		// Register classes defined by extensions that are loaded by including of a file that
		// updates global variables, rather than having an extension.json manifest.
		if ( $vars && isset( $vars['wgAutoloadClasses'] ) ) {
			AutoLoader::registerClasses( $vars['wgAutoloadClasses'] );
		}

		// Register class definitions from extension.json files
		if ( !isset( $extInfo['autoloaderPaths'] )
			|| !isset( $extInfo['autoloaderClasses'] )
			|| !isset( $extInfo['autoloaderNS'] )
		) {
			// NOTE: protect against changes to the structure of $extInfo.
			// It's volatile, and this usage is easy to miss.
			throw new LogicException( 'Missing autoloader keys from extracted extension info' );
		}
		AutoLoader::loadFiles( $extInfo['autoloaderPaths'] );
		AutoLoader::registerClasses( $extInfo['autoloaderClasses'] );
		AutoLoader::registerNamespaces( $extInfo['autoloaderNS'] );

		$legacyHooks = $legacySchemaHooks ? [ 'LoadExtensionSchemaUpdates' => $legacySchemaHooks ] : [];

		$this->getContext()->provide( 'HookContainer',
			new HookContainer(
				new StaticHookRegistry(
					$legacyHooks,
					$extInfo['attributes']['Hooks'] ?? [],
					$extInfo['attributes']['DeprecatedHooks'] ?? []
				),
				MediaWikiServices::getInstance()->getObjectFactory()
			)
		);
		$this->getContext()->provide( 'VirtualDomains',
			$extInfo['attributes']['DatabaseVirtualDomains'] ?? [] );

		$this->getContext()->provide( 'ExtensionTaskSpecs', [] );
		return Status::newGood();
	}

}
