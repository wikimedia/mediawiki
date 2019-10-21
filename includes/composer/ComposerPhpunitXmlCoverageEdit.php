<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

/**
 * Edit phpunit.xml to speed up code coverage generation.
 *
 * Usage: composer phpunit:coverage-edit -- extensions/ExtensionName
 *
 * This class runs *outside* of the normal MediaWiki
 * environment and cannot depend upon any MediaWiki
 * code.
 */
class ComposerPhpunitXmlCoverageEdit {

	public static function onEvent( $event ) {
		$IP = dirname( dirname( __DIR__ ) );
		// TODO: Support passing arbitrary directories for core (or extensions/skins).
		$args = $event->getArguments();
		if ( count( $args ) !== 1 ) {
			throw new InvalidArgumentException( 'Pass extensions/$extensionName as an argument, ' .
				'e.g. "composer phpunit:coverage-edit -- extensions/BoilerPlate"' );
		}
		$project = current( $args );
		$phpunitXml = \PHPUnit\Util\Xml::loadFile( $IP . '/phpunit.xml.dist' );
		$whitelist = iterator_to_array( $phpunitXml->getElementsByTagName( 'whitelist' ) );
		/** @var DOMNode $childNode */
		foreach ( $whitelist as $childNode ) {
			$childNode->parentNode->removeChild( $childNode );
		}
		$whitelistElement = $phpunitXml->createElement( 'whitelist' );
		$whitelistElement->setAttribute( 'addUncoveredFilesFromWhitelist', 'false' );
		// TODO: Use AutoloadClasses from extension.json to load the relevant directories
		foreach ( [ 'includes', 'src', 'maintenance' ] as $dir ) {
			$dirElement = $phpunitXml->createElement( 'directory', $project . '/' . $dir );
			$dirElement->setAttribute( 'suffix', '.php' );
			$whitelistElement->appendChild( $dirElement );

		}
		$phpunitXml->getElementsByTagName( 'filter' )->item( 0 )
			->appendChild( $whitelistElement );
		$phpunitXml->formatOutput = true;
		$phpunitXml->save( $IP . '/phpunit.xml' );
	}
}
