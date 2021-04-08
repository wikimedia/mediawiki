<?php

use Composer\Package\Package;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;

$GLOBALS['IP'] = __DIR__ . '/../../';
require_once __DIR__ . '/../AutoLoader.php';

/**
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ComposerHookHandler {

	public static function onPreUpdate( Event $event ) {
		self::checkMergePluginActive( $event );
		self::handleChangeEvent( $event );
	}

	/**
	 * Check if composer-merge-plugin has been activated. If not, the most
	 * likely explanation is that an old version of the plugin was present
	 * which did not support Composer 2, and so Composer disabled it.
	 *
	 * @param Event $event
	 * @throws Exception
	 */
	private static function checkMergePluginActive( Event $event ) {
		if ( version_compare( PluginInterface::PLUGIN_API_VERSION, '2.0.0', '>=' ) ) {
			foreach ( $event->getComposer()->getPluginManager()->getPlugins() as $plugin ) {
				if ( $plugin instanceof \Wikimedia\Composer\Merge\V2\MergePlugin ) {
					// If v2 of wikimedia/composer-merge-plugin is already installed,
					// nothing needs doing
					return;
				}

				// Check if v1 of wikimedia/composer-merge-plugin is still installed
				if ( $plugin instanceof \Wikimedia\Composer\MergePlugin ) {
					throw new \Exception( "wikimedia/composer-merge-plugin 2.x is not activated. " .
						"Use Composer 1.x to update wikimedia/composer-merge-plugin to version " .
						"2.x before running Composer 2.x." );
				}
			}
		}
	}

	public static function onPreInstall( Event $event ) {
		self::handleChangeEvent( $event );
	}

	private static function handleChangeEvent( Event $event ) {
		$package = $event->getComposer()->getPackage();

		if ( $package instanceof Package ) {
			$packageModifier = new ComposerPackageModifier(
				$package,
				new ComposerVersionNormalizer(),
				new MediaWikiVersionFetcher()
			);

			$packageModifier->setProvidesMediaWiki();
		}
	}
}
