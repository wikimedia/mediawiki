<?php
/**
 * Prints the version of MediaWiki.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 * @author Derick Alangi
 * @since 1.36
 */

namespace MediaWiki\Maintenance;

use MediaWiki\Utils\GitInfo;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
		$contentLang = $this->getServiceContainer()->getContentLanguage();

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

// @codeCoverageIgnoreStart
$maintClass = Version::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
