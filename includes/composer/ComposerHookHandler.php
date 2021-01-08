<?php

use Composer\Package\Package;
use Composer\Script\Event;

/**
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ComposerHookHandler {

	private static function startAutoloader() {
		$GLOBALS['IP'] = __DIR__ . '/../../';
		require_once __DIR__ . '/../AutoLoader.php';
	}

	public static function onPreUpdate( Event $event ) {
		self::startAutoloader();
		self::handleChangeEvent( $event );
	}

	public static function onPreInstall( Event $event ) {
		self::startAutoloader();
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
