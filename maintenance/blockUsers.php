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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

class BlockUsers extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription(
			"Blocks a list of usernames. Can use STDIN or the file argument.\n\n" .
			'Note, this is probably most useful when dealing with spammers, with a ' .
			"list you've generated with an SQL query or similar.\n\n" .
			'By default, all users are hard blocked, auto blocked from any current and subsequent ' .
			'IP addresses, email disabled, unable to write to their user page and unable to ' .
			'create further accounts with no expiry to this block. You can change the expiry ' .
			'with --expiry parameter.'
		);

		$this->addArg(
			'file',
			'File with list of users to block',
			false
		);

		$this->addOption(
			'performer',
			'User to make the blocks',
			false,
			true
		);

		$this->addOption(
			'reason',
			'Reason for the blocks',
			false,
			true
		);

		$this->addOption(
			'reblock',
			'Reblock users who are already blocked',
			false,
			false
		);

		$this->addOption(
			'expiry',
			'Expiry of your block',
			false,
			true
		);

		$this->addOption(
			'unblock',
			'Should this unblock?',
			false,
			false
		);

		$this->addOption(
			'allow-createaccount',
			'Allow account creation for blocked IPs',
			false
		);

		$this->addOption(
			'allow-email',
			'Allow blocked accounts to send emails',
			false
		);

		$this->addOption(
			'allow-talkedit',
			'Allow blocked accounts to edit their own talk page',
			false
		);

		$this->addOption(
			'disable-hardblock',
			'Don\'t block logged in accounts from a blocked IP address',
			false
		);

		$this->addOption(
			'disable-autoblock',
			'Don\'t autoblock IP addresses used by the accounts',
			false
		);
	}

	public function execute() {
		$performerName = $this->getOption( 'performer', false );
		$reason = $this->getOption( 'reason', '' );
		$unblocking = $this->getOption( 'unblock', false );
		$reblock = $this->hasOption( 'reblock' );
		$expiry = $this->getOption( 'expiry', 'indefinite' );

		$performer = null;
		if ( $performerName ) {
			$performer = User::newFromName( $performerName );
		} else {
			$performer = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		}

		if ( $performer === null ) {
			$this->fatalError( "Unable to parse performer's username" );
		}

		if ( $this->hasArg( 0 ) ) {
			$file = fopen( $this->getArg( 0 ), 'r' );
		} else {
			$file = $this->getStdin();
		}

		# Setup
		if ( !$file ) {
			$this->fatalError( "Unable to read file, exiting" );
		}

		# Handle each entry
		$blockUserFactory = MediaWikiServices::getInstance()->getBlockUserFactory();
		$unblockUserFactory = MediaWikiServices::getInstance()->getUnblockUserFactory();
		$action = $unblocking ? "Unblocking" : "Blocking";
		for ( $linenum = 1; !feof( $file ); $linenum++ ) {
			$line = trim( fgets( $file ) );
			if ( $line == '' ) {
				continue;
			}

			if ( $unblocking ) {
				$res = $unblockUserFactory->newUnblockUser(
					$line,
					$performer,
					$reason
				)->unblockUnsafe();
			} else {
				$res = $blockUserFactory->newBlockUser(
					$line,
					$performer,
					$expiry,
					$reason,
					[
						'isCreateAccountBlocked' => !$this->hasOption( 'allow-createaccount' ),
						'isEmailBlocked' => !$this->hasOption( 'allow-email' ),
						'isUserTalkEditBlocked' => !$this->hasOption( 'allow-talkedit' ),
						'isHardBlock' => !$this->hasOption( 'disable-hardblock' ),
						'isAutoblocking' => !$this->hasOption( 'disable-autoblock' ),
					]
				)->placeBlockUnsafe( $reblock );
			}

			if ( $res->isOK() ) {
				$this->output( "{$action} '{$line}' succeeded.\n" );
			} else {
				$errorsText = $res->getMessage()->text();
				$this->output( "{$action} '{$line}' failed ({$errorsText}).\n" );
			}
		}
	}
}

$maintClass = BlockUsers::class;
require_once RUN_MAINTENANCE_IF_MAIN;
