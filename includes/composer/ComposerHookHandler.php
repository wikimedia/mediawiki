<?php

use Composer\Package\Package;
use Composer\Script\Event;

$GLOBALS['IP'] = __DIR__ . '/../../';
require_once __DIR__ . '/../AutoLoader.php';

/**
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ComposerHookHandler {

	public static function onPreUpdate( Event $event ) {
		self::handleChangeEvent( $event );
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
