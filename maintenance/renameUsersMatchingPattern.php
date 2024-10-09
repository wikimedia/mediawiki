<?php

use MediaWiki\Page\MovePageFactory;
use MediaWiki\RenameUser\RenameuserSQL;
use MediaWiki\Status\Status;
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

	/** @var MovePageFactory */
	private $movePageFactory;

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
		$this->movePageFactory = $services->getMovePageFactory();
		$this->titleFactory = $services->getTitleFactory();
	}

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
		$id = $this->userFactory->newFromName( $oldName )->getId();
		if ( !$id ) {
			$this->output( "Cannot rename non-existent user \"$oldName\"" );
		}

		if ( $this->dryRun ) {
			$this->output( "$oldName would be renamed to $newName\n" );
		} else {
			$renamer = new RenameuserSQL(
				$oldName,
				$newName,
				$id,
				$this->performer,
				[
					'reason' => $this->reason
				]
			);

			if ( !$renamer->rename() ) {
				$this->output( "Unable to rename $oldName" );
				return false;
			} else {
				$this->output( "$oldName was successfully renamed to $newName.\n" );
			}
		}

		if ( $this->skipPageMoves ) {
			return true;
		}

		$this->movePageAndSubpages( NS_USER, 'User', $oldName, $newName );
		$this->movePageAndSubpages( NS_USER_TALK, 'User talk', $oldName, $newName );
		return true;
	}

	private function movePageAndSubpages( $ns, $nsName, $oldName, $newName ) {
		$oldTitle = $this->titleFactory->makeTitleSafe( $ns, $oldName );
		if ( !$oldTitle ) {
			$this->output( "[[$nsName:$oldName]] is an invalid title, can't move it.\n" );
			return true;
		}
		$newTitle = $this->titleFactory->makeTitleSafe( $ns, $newName );
		if ( !$newTitle ) {
			$this->output( "[[$nsName:$newName]] is an invalid title, can't move to it.\n" );
			return true;
		}

		$movePage = $this->movePageFactory->newMovePage( $oldTitle, $newTitle );
		$movePage->setMaximumMovedPages( -1 );

		$logMessage = wfMessage(
			'renameuser-move-log', $oldName, $newName
		)->inContentLanguage()->text();

		if ( $this->dryRun ) {
			if ( $oldTitle->exists() ) {
				$this->output( "Would move [[$nsName:$oldName]] to [[$nsName:$newName]].\n" );
			}
		} else {
			if ( $oldTitle->exists() ) {
				$status = $movePage->move(
					$this->performer, $logMessage, !$this->suppressRedirect );
			} else {
				$status = Status::newGood();
			}
			$status->merge( $movePage->moveSubpages(
				$this->performer, $logMessage, !$this->suppressRedirect ) );
			if ( !$status->isGood() ) {
				$this->output( "Failed to rename user page\n" );
				$this->error( $status );
			}
		}
		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = RenameUsersMatchingPattern::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
