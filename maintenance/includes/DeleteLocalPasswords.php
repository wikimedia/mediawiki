<?php
/**
 * Helper for deleting unused local passwords.
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

use MediaWiki\Password\InvalidPassword;
use MediaWiki\Password\PasswordFactory;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\RawSQLValue;

require_once __DIR__ . '/../Maintenance.php';

/**
 * Delete unused local passwords.
 *
 * Mainly intended to be used as a base class by authentication extensions to provide maintenance
 * scripts which allow deleting local passwords for users who have another way of logging in.
 * Such scripts would customize how to locate users who have other login methods and don't need
 * local login anymore.
 * Make sure to set LocalPasswordPrimaryAuthenticationProvider to loginOnly => true or disable it
 * completely before running this, otherwise it might recreate passwords.
 *
 * This class can also be used directly to just delete all local passwords, or those for a specific
 * user. Deleting all passwords is useful when the wiki has used local password login in the past
 * but it has been disabled.
 */
class DeleteLocalPasswords extends Maintenance {
	/** @var string|null User to run on, or null for all. */
	protected $user;

	/** @var int Number of deleted passwords. */
	protected $total;

	public function __construct() {
		parent::__construct();
		$this->addDescription( "Deletes local password for users." );
		$this->setBatchSize( 1000 );

		$this->addOption( 'user', 'If specified, only checks the given user', false, true );
		$this->addOption( 'delete', 'Really delete. To prevent accidents, you must provide this flag.' );
		$this->addOption( 'prefix', "Instead of deleting, make passwords invalid by prefixing with "
			. "':null:'. Make sure PasswordConfig has a 'null' entry. This is meant for testing before "
			. "hard delete." );
		$this->addOption( 'unprefix', 'Instead of deleting, undo the effect of --prefix.' );
	}

	protected function initialize() {
		if (
			(int)$this->hasOption( 'delete' ) + (int)$this->hasOption( 'prefix' )
			+ (int)$this->hasOption( 'unprefix' ) !== 1
		) {
			$this->fatalError( "Exactly one of the 'delete', 'prefix', 'unprefix' options must be used\n" );
		}
		if ( $this->hasOption( 'prefix' ) || $this->hasOption( 'unprefix' ) ) {
			$passwordHashTypes = $this->getServiceContainer()->getPasswordFactory()->getTypes();
			if (
				!isset( $passwordHashTypes['null'] )
				|| $passwordHashTypes['null']['class'] !== InvalidPassword::class
			) {
				$this->fatalError(
<<<'ERROR'
'null' password entry missing. To use password prefixing, add
    $wgPasswordConfig['null'] = [ 'class' => InvalidPassword::class ];
to your configuration (and remove once the passwords were deleted).
ERROR
				);
			}
		}

		$user = $this->getOption( 'user', false );
		if ( $user !== false ) {
			$userNameUtils = $this->getServiceContainer()->getUserNameUtils();
			$this->user = $userNameUtils->getCanonical( $user );
			if ( $this->user === false ) {
				$this->fatalError( "Invalid user name\n" );
			}
		}
	}

	public function execute() {
		$this->initialize();

		foreach ( $this->getUserBatches() as $userBatch ) {
			$this->processUsers( $userBatch, $this->getUserDB() );
		}

		$this->output( "done. (wrote $this->total rows)\n" );
	}

	/**
	 * Get the primary DB handle for the current user batch. This is provided for the benefit
	 * of authentication extensions which subclass this and work with wiki farms.
	 * @return IDatabase
	 */
	protected function getUserDB() {
		return $this->getPrimaryDB();
	}

	protected function processUsers( array $userBatch, IDatabase $dbw ) {
		if ( !$userBatch ) {
			return;
		}
		if ( $this->getOption( 'delete' ) ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'user' )
				->set( [ 'user_password' => PasswordFactory::newInvalidPassword()->toString() ] )
				->where( [ 'user_name' => $userBatch ] )
				->caller( __METHOD__ )->execute();
		} elseif ( $this->getOption( 'prefix' ) ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'user' )
				->set( [
					'user_password' => new RawSQLValue(
						$dbw->buildConcat( [ $dbw->addQuotes( ':null:' ), 'user_password' ] )
					)
				] )
				->where( [
					$dbw->expr( 'user_password', IExpression::NOT_LIKE, new LikeValue( ':null:', $dbw->anyString() ) ),
					$dbw->expr( 'user_password', '!=', PasswordFactory::newInvalidPassword()->toString() ),
					$dbw->expr( 'user_password', '!=', null ),
					'user_name' => $userBatch,
				] )
				->caller( __METHOD__ )->execute();
		} elseif ( $this->getOption( 'unprefix' ) ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'user' )
				->set( [
					'user_password' => new RawSQLValue(
						$dbw->buildSubString( 'user_password', strlen( ':null:' ) + 1 )
					)
				] )
				->where( [
					$dbw->expr( 'user_password', IExpression::LIKE, new LikeValue( ':null:', $dbw->anyString() ) ),
					'user_name' => $userBatch,
				] )
				->caller( __METHOD__ )->execute();
		}
		$this->total += $dbw->affectedRows();
		$this->waitForReplication();
	}

	/**
	 * This method iterates through the requested users and returns their names in batches of
	 * self::$mBatchSize.
	 *
	 * Subclasses should reimplement this and locate users who use the specific authentication
	 * method. The default implementation just iterates through all users. Extensions that work
	 * with wikifarm should also update self::getUserDB() as necessary.
	 * @return Generator
	 */
	protected function getUserBatches() {
		if ( $this->user !== null ) {
			$this->output( "\t ... querying '$this->user'\n" );
			yield [ [ $this->user ] ];
			return;
		}

		$lastUsername = '';
		$dbw = $this->getPrimaryDB();
		do {
			$this->output( "\t ... querying from '$lastUsername'\n" );
			$users = $dbw->newSelectQueryBuilder()
				->select( 'user_name' )
				->from( 'user' )
				->where( $dbw->expr( 'user_name', '>', $lastUsername ) )
				->orderBy( 'user_name ASC' )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )->fetchFieldValues();
			if ( $users ) {
				yield $users;
				$lastUsername = end( $users );
			}
		} while ( count( $users ) === $this->getBatchSize() );
	}
}
