<?php
/**
 * Modern interactive shell within the MediaWiki engine.
 *
 * Merely wraps around http://psysh.org/ and drop an interactive PHP shell in
 * the global scope.
 *
 * Copyright © 2017 Antoine Musso <hashar@free.fr>
 * Copyright © 2017 Gergő Tisza <tgr.huwiki@gmail.com>
 * Copyright © 2017 Justin Hileman <justin@justinhileman.info>
 * Copyright © 2017 Wikimedia Foundation Inc.
 * https://www.mediawiki.org/
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
 *
 * @author Antoine Musso <hashar@free.fr>
 * @author Justin Hileman <justin@justinhileman.info>
 * @author Gergő Tisza <tgr.huwiki@gmail.com>
 */

use MediaWiki\Logger\ConsoleSpi;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Interactive shell with completion and global scope.
 *
 */
class MediaWikiShell extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addOption( 'd',
			'For back compatibility with eval.php. ' .
			'1 send debug to stderr. ' .
			'With 2 additionally initialize database with debugging ',
			false, true
		);
	}

	public function execute() {
		if ( !class_exists( \Psy\Shell::class ) ) {
			$this->fatalError( 'PsySH not found. Please run composer with the --dev option.' );
		}

		$traverser = new \PhpParser\NodeTraverser();
		$codeCleaner = new \Psy\CodeCleaner( null, null, $traverser );

		// add this after initializing the code cleaner so all the default passes get added first
		$traverser->addVisitor( new CodeCleanerGlobalsPass() );

		$config = new \Psy\Configuration();
		$config->setCodeCleaner( $codeCleaner );
		$config->setUpdateCheck( \Psy\VersionUpdater\Checker::NEVER );
		// prevent https://github.com/bobthecow/psysh/issues/443 when using sudo -E
		$config->setRuntimeDir( wfTempDir() );

		$shell = new \Psy\Shell( $config );
		if ( $this->hasOption( 'd' ) ) {
			$this->setupLegacy();
		}

		$shell->run();
	}

	/**
	 * For back compatibility with eval.php
	 */
	protected function setupLegacy() {
		$d = intval( $this->getOption( 'd' ) );
		if ( $d > 0 ) {
			LoggerFactory::registerProvider( new ConsoleSpi );
			// Some services hold Logger instances in object properties
			MediaWikiServices::resetGlobalInstance();
		}
		if ( $d > 1 ) {
			# Set DBO_DEBUG (equivalent of $wgDebugDumpSql)
			$this->getDB( DB_MASTER )->setFlag( DBO_DEBUG );
			$this->getDB( DB_REPLICA )->setFlag( DBO_DEBUG );
		}
	}

}

$maintClass = MediaWikiShell::class;
require_once RUN_MAINTENANCE_IF_MAIN;
