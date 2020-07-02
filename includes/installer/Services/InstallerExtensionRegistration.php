<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Installer
 *
 * @author Art Baltai
 */

declare( strict_types = 1 );
namespace MediaWiki\Installer\Services;

use ExtensionRegistry;
use LocalisationCache;

/**
 * @since 1.35
 */
class InstallerExtensionRegistration {

	/** @var string */
	private $extensionDir;

	/** @var LocalisationCache */
	private $localisationCache;

	public function __construct( string $extensionDir, LocalisationCache $localisationCache ) {
		$this->extensionDir = $extensionDir;
		$this->localisationCache = $localisationCache;
	}

	/**
	 * Register class autoloader, i18n messages
	 * @param string $extensionName
	 * @param array $extJsonOptions
	 */
	public function register(
		string $extensionName,
		array $extJsonOptions
	): void {
		$this->registerClassAutoloader( $extensionName, $extJsonOptions );
		$this->registerMessagesDirs( $extensionName, $extJsonOptions );
	}

	public function registerClassAutoloader(
		string $extensionName,
		array $extJsonOptions
	): void {
		global $wgAutoloadLocalClasses;

		$extPath = $this->getExtensionPath( $extensionName );
		ExtensionRegistry::exportAutoloadClassesAndNamespaces(
			$extPath,
			$extJsonOptions
		);
		// important for upgrade (mw-config/?page=ExistingWiki) with existing LocalSettings.php
		if (
			!empty( $extJsonOptions['AutoloadClasses'] )
			&& is_array( $extJsonOptions['AutoloadClasses'] )
		) {
			foreach ( $extJsonOptions['AutoloadClasses'] as $className => $fileName ) {
				$wgAutoloadLocalClasses[$className] = "$extPath/$fileName";
			}
		}
	}

	public function registerMessagesDirs(
		string $extensionName,
		array $extJsonOptions
	): void {
		if ( !is_array( $extJsonOptions['MessagesDirs'] ?? null ) ) {
			return;
		}

		foreach ( $extJsonOptions['MessagesDirs'] as $messageType => $messagesDirs ) {
			$messagesDirs = (array)$messagesDirs;
			foreach ( $messagesDirs as $key => $messageDir ) {
				$i18nDir = $this->getExtensionPath( $extensionName ) . "/$messageDir";
				if ( !is_dir( $i18nDir ) ) {
					continue;
				}
				$this->localisationCache->addMessagesDir(
					count( $messagesDirs ) === 1
						? $messageType
						: "{$messageType}_{$key}",
					$i18nDir
				);
			}
		}
	}

	private function getExtensionPath( string $extensionName ) : string {
		return "{$this->extensionDir}/{$extensionName}";
	}
}
