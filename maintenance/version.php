<?php
/**
 * Prints the version of MediaWiki.
 *
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
 * @ingroup Maintenance
 * @author Derick Alangi
 * @since 1.36
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * @ingroup Maintenance
 */
class Version extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Prints the current version of MediaWiki' );
	}

	public function canExecuteWithoutLocalSettings(): bool {
		return true;
	}

	public function execute() {
		if ( !defined( 'MW_VERSION' ) ) {
			$this->fatalError( "MediaWiki version not defined or unknown" );
		}

		global $IP;
		$contentLang = \MediaWiki\MediaWikiServices::getInstance()->getContentLanguage();

		$version = MW_VERSION;
		$strictVersion = substr( $version, 0, 4 );
		$isLTS = false;

		// See: https://www.mediawiki.org/wiki/Topic:U4u94htjqupsosea
		if ( $strictVersion >= '1.19' ) {
			$x = (float)explode( '.', $strictVersion )[1];
			$isLTS = ( $x - 19 ) % 4 === 0;
		}

		// Get build date and append if available
		$gitInfo = new GitInfo( $IP );
		$gitHeadCommitDate = $gitInfo->getHeadCommitDate();
		$buildDate = $contentLang->timeanddate( (string)$gitHeadCommitDate, true );

		$text = "MediaWiki version: " . $version;
		if ( $isLTS ) {
			$text .= " LTS";
		}
		if ( $buildDate ) {
			$text .= " (built: $buildDate)";
		}

		$this->output( $text . "\n" );
	}
}

$maintClass = Version::class;
require_once RUN_MAINTENANCE_IF_MAIN;
