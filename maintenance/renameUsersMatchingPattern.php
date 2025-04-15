<?php

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\RenameUser\RenameUserFactory;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\TempUser\Pattern;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use Wikimedia\Rdbms\IExpression;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class RenameUsersMatchingPattern extends Maintenance {
	/** @var UserFactory */
	private $userFactory;

	/** @var RenameUserFactory */
	private $renameUserFactory;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var User */
	private $performer;

	/** @var string */
	private $reason;

	/** @var bool */
	private $dryRun;

	/** @var bool */
	private $suppressRedirect;

	/** @var bool */
	private $skipPageMoves;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Rename users with a name matching a pattern. ' .
			'This can be used to migrate to a temporary user (IP masking) configuration.' );
		$this->addOption( 'from', 'A username pattern where $1 is ' .
			'the wildcard standing in for any number of characters. All users ' .
			'matching this pattern will be renamed.', true, true );
		$this->addOption( 'to', 'A username pattern where $1 is ' .
			'the part of the username matched by $1 in --from. Users will be ' .
			' renamed to this pattern.', true, true );
		$this->addOption( 'performer', 'Performer of the rename action', false, true );
		$this->addOption( 'reason', 'Reason of the rename', false, true );
		$this->addOption( 'suppress-redirect', 'Don\'t create redirects when moving pages' );
		$this->addOption( 'skip-page-moves', 'Don\'t move associated user pages' );
		$this->addOption( 'dry-run', 'Don\'t actually rename the ' .
			'users, just report what it would do.' );
		$this->setBatchSize( 1000 );
	}

	private function initServices() {
		$services = $this->getServiceContainer();
		if ( $services->getCentralIdLookupFactory()->getNonLocalLookup() ) {
			$this->fatalError( "This script cannot be run when CentralAuth is enabled." );
		}
		$this->userFactory = $services->getUserFactory();
		$this->renameUserFactory = $services->getRenameUserFactory();
		$this->titleFactory = $services->getTitleFactory();
	}

	/** @inheritDoc */
	public function execute() {
		$this->initServices();

		$fromPattern = new Pattern( 'from', $this->getOption( 'from' ) );
		$toPattern = new Pattern( 'to', $this->getOption( 'to' ) );

		if ( $this->getOption( 'performer' ) === null ) {
			$performer = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		} else {
			$performer = $this->userFactory->newFromName( $this->getOption( 'performer' ) );
		}
		if ( !$performer ) {
			$this->fatalError( "Unable to get performer account" );
		}
		$this->performer = $performer;

		$this->reason = $this->getOption( 'reason', '' );
		$this->dryRun = $this->getOption( 'dry-run' );
		$this->suppressRedirect = $this->getOption( 'suppress-redirect' );
		$this->skipPageMoves = $this->getOption( 'skip-page-moves' );

		$dbr = $this->getReplicaDB();
		$batchConds = [];
		$batchSize = $this->getBatchSize();
		$numRenamed = 0;
		do {
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'user_name' ] )
				->from( 'user' )
				->where( $dbr->expr( 'user_name', IExpression::LIKE, $fromPattern->toLikeValue( $dbr ) ) )
				->andWhere( $batchConds )
				->orderBy( 'user_name' )
				->limit( $batchSize )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $res as $row ) {
				$oldName = $row->user_name;
				$batchConds = [ $dbr->expr( 'user_name', '>', $oldName ) ];
				$variablePart = $fromPattern->extract( $oldName );
				if ( $variablePart === null ) {
					$this->output( "Username \"fromName\" matched the LIKE " .
						"but does not seem to match the pattern" );
					continue;
				}
				$newName = $toPattern->generate( $variablePart );

				// Canonicalize
				$newTitle = $this->titleFactory->makeTitleSafe( NS_USER, $newName );
				$newUser = $this->userFactory->newFromName( $newName );
				if ( !$newTitle || !$newUser ) {
					$this->output( "Cannot rename \"$oldName\" " .
						"because \"$newName\" is not a valid title\n" );
					continue;
				}
				$newName = $newTitle->getText();

				// Check destination existence
				if ( $newUser->isRegistered() ) {
					$this->output( "Cannot rename \"$oldName\" " .
						"because \"$newName\" already exists\n" );
					continue;
				}

				$numRenamed += $this->renameUser( $oldName, $newName ) ? 1 : 0;
				$this->waitForReplication();
			}
		} while ( $res->numRows() === $batchSize );
		$this->output( "Renamed $numRenamed user(s)\n" );
		return true;
	}

	/**
	 * @param string $oldName
	 * @param string $newName
	 * @return bool True if the user was renamed
	 */
	private function renameUser( $oldName, $newName ) {
		$oldUser = $this->userFactory->newFromName( $oldName );
		if ( !$oldUser ) {
			$this->output( "Invalid user name \"$oldName\"" );
			return false;
		}

		$id = $oldUser->getId();
		if ( !$id ) {
			$this->output( "Cannot rename non-existent user \"$oldName\"" );
			return false;
		}

		if ( $this->dryRun ) {
			$this->output( "$oldName would be renamed to $newName\n" );
		} else {
			$rename = $this->renameUserFactory->newRenameUser( $this->performer, $oldUser, $newName, $this->reason, [
				'forceGlobalDetach' => $this->getOption( 'force-global-detach' ),
				'movePages' => !$this->getOption( 'skip-page-moves' ),
				'suppressRedirect' => $this->getOption( 'suppress-redirect' ),
			] );
			$status = $rename->renameGlobal();

			if ( $status->isGood() ) {
				$this->output( "$oldName was successfully renamed to $newName.\n" );
			} else {
				if ( $status->isOK() ) {
					$this->output( "$oldName was renamed to $newName.\n" );
				} else {
					$this->output( "Unable to rename $oldName.\n" );
				}
				foreach ( $status->getMessages() as $msg ) {
					$this->output( '  - ' . wfMessage( $msg )->text() );
				}
			}
		}
		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = RenameUsersMatchingPattern::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
