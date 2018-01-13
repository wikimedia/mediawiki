<?php
/**
 * Invalidate the sessions of certain users on the wiki.
 * If you want to invalidate all sessions, use $wgAuthenticationTokenVersion instead.
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
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Session\SessionManager;

require_once __DIR__ . '/Maintenance.php';

/**
 * Invalidate the sessions of certain users on the wiki.
 * If you want to invalidate all sessions, use $wgAuthenticationTokenVersion instead.
 *
 * @ingroup Maintenance
 */
class InvalidateUserSesssions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Invalidate the sessions of certain users on the wiki.'
		);
		$this->addOption( 'user', 'Username', false, true, 'u' );
		$this->addOption( 'file', 'File with one username per line', false, true, 'f' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$username = $this->getOption( 'user' );
		$file = $this->getOption( 'file' );

		if ( $username === null && $file === null ) {
			$this->fatalError( 'Either --user or --file is required' );
		} elseif ( $username !== null && $file !== null ) {
			$this->fatalError( 'Cannot use both --user and --file' );
		}

		if ( $username !== null ) {
			$usernames = [ $username ];
		} else {
			$usernames = is_readable( $file ) ?
				file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES ) : false;
			if ( $usernames === false ) {
				$this->fatalError( "Could not open $file", 2 );
			}
		}

		$i = 0;
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$sessionManager = SessionManager::singleton();
		foreach ( $usernames as $username ) {
			$i++;
			$user = User::newFromName( $username );
			try {
				$sessionManager->invalidateSessionsForUser( $user );
				if ( $user->getId() ) {
					$this->output( "Invalidated sessions for user $username\n" );
				} else {
					# session invalidation might still work if there is a central identity provider
					$this->output( "Could not find user $username, tried to invalidate anyway\n" );
				}
			} catch ( Exception $e ) {
				$this->output( "Failed to invalidate sessions for user $username | "
					. str_replace( [ "\r", "\n" ], ' ', $e->getMessage() ) . "\n" );
			}

			if ( $i % $this->getBatchSize() ) {
				$lbFactory->waitForReplication();
			}
		}
	}
}

$maintClass = InvalidateUserSesssions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
