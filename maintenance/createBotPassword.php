<?php
/**
 * Creates a bot password for an existing user account.
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
 * @author Alex Dean <wikimedia@mostlyalex.com>
 */

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;

class CreateBotPassword extends Maintenance {
	/**
	 * Width of initial column of --showgrants output
	 */
	private const SHOWGRANTS_COLUMN_WIDTH = 20;

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Create a bot password for a user. ' .
			'See https://www.mediawiki.org/wiki/Manual:Bot_passwords for more information.'
		);

		$this->addOption( "showgrants",
			"Prints a description of available grants and exits."
		);
		$this->addOption( "appid",
			"App id for the new bot password.", false, true
		);
		$this->addOption( "grants",
			"CSV list of permissions to grant.", false, true
		);
		$this->addArg( "user",
			"The username to create a bot password for.", false
		);
		$this->addArg( "password",
			"A password will be generated if this is omitted." .
			" If supplied, it must be exactly 32 characters.", false
		);
	}

	public function execute() {
		if ( $this->hasOption( 'showgrants' ) ) {
			$this->showGrants();
			return;
		}

		$username = $this->getArg( 0 );
		$password = $this->getArg( 1 );
		$appId = $this->getOption( 'appid' );
		$grants = explode( ',', $this->getOption( 'grants' ) );

		$errors = [];
		if ( $username === null ) {
			$errors[] = "Argument <user> required!";
		}
		if ( $appId == null ) {
			$errors[] = "Param appid required!";
		}
		if ( $this->getOption( 'grants' ) === null ) {
			$errors[] = "Param grants required!";
		}
		if ( count( $errors ) > 0 ) {
			$this->fatalError( implode( "\n", $errors ) );
		}

		$services = MediaWikiServices::getInstance();
		$grantsInfo = $services->getGrantsInfo();
		$invalidGrants = array_diff( $grants, $grantsInfo->getValidGrants() );
		if ( count( $invalidGrants ) > 0 ) {
			$this->fatalError(
				"These grants are invalid: " . implode( ', ', $invalidGrants ) . "\n" .
				"Use the --showgrants option for a full list of valid grant names."
			);
		}

		$passwordFactory = $services->getPasswordFactory();

		$userId = User::idFromName( $username );
		if ( $userId === null ) {
			$this->fatalError( "Cannot create bot password for non-existent user '$username'." );
		}

		if ( $password === null ) {
			$password = BotPassword::generatePassword( $this->getConfig() );
		} else {
			$passwordLength = strlen( $password );
			if ( $passwordLength < BotPassword::PASSWORD_MINLENGTH ) {
				$message = "Bot passwords must have at least " . BotPassword::PASSWORD_MINLENGTH .
					" characters. Given password is $passwordLength characters.";
				$this->fatalError( $message );
			}
		}

		$bp = BotPassword::newUnsaved( [
			'username' => $username,
			'appId' => $appId,
			'grants' => $grants
		] );

		if ( $bp === null ) {
			$this->fatalError( "Bot password creation failed." );
		}

		$passwordInstance = $passwordFactory->newFromPlaintext( $password );
		$status = $bp->save( 'insert', $passwordInstance );

		if ( $status->isGood() ) {
			$this->output( "Success.\n" );
			$this->output( "Log in using username:'{$username}@{$appId}' and password:'{$password}'.\n" );
		} else {
			$this->fatalError(
				"Bot password creation failed. Does this appid already exist for the user perhaps?\n\nErrors:\n" .
				print_r( $status->getErrors(), true )
			);
		}
	}

	public function showGrants() {
		$permissions = MediaWikiServices::getInstance()->getGrantsInfo()->getValidGrants();
		sort( $permissions );

		$this->output( str_pad( 'GRANT', self::SHOWGRANTS_COLUMN_WIDTH ) . " DESCRIPTION\n" );
		foreach ( $permissions as $permission ) {
			$this->output(
				str_pad( $permission, self::SHOWGRANTS_COLUMN_WIDTH ) . " " .
				User::getRightDescription( $permission ) . "\n"
			);
		}
	}
}

$maintClass = CreateBotPassword::class;
require_once RUN_MAINTENANCE_IF_MAIN;
