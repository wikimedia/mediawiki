<?php
/**
 * Make test edits for a user to populate a test wiki
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */
// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Content\ContentHandler;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Make test edits for a user to populate a test wiki
 *
 * @ingroup Maintenance
 */
class MakeTestEdits extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Make test edits for a user' );
		$this->addOption( 'user', 'User name', true, true );
		$this->addOption( 'count', 'Number of edits', true, true );
		$this->addOption( 'namespace', 'Namespace number', false, true );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$user = User::newFromName( $this->getOption( 'user' ) );
		if ( !$user->isRegistered() ) {
			$this->fatalError( "No such user exists." );
		}

		$count = $this->getOption( 'count' );
		$namespace = (int)$this->getOption( 'namespace', 0 );
		$batchSize = $this->getBatchSize();
		$services = $this->getServiceContainer();
		$wikiPageFactory = $services->getWikiPageFactory();

		/** @var iterable<Title[]> $titleBatches */
		$titleBatches = $this->newBatchIterator(
			static function () use ( $namespace, $count ) {
				for ( $i = 0; $i < $count; ++$i ) {
					yield Title::makeTitleSafe( $namespace, "Page " . wfRandomString( 2 ) );
				}
			}
		);

		foreach ( $titleBatches as $titleBatch ) {
			$this->beginTransactionRound( __METHOD__ );
			foreach ( $titleBatch as $title ) {
				$page = $wikiPageFactory->newFromTitle( $title );
				$content = ContentHandler::makeContent( wfRandomString(), $title );
				$summary = "Change " . wfRandomString( 6 );

				$page->doUserEditContent( $content, $user, $summary );

				$this->output( "Edited $title\n" );
			}
			$this->commitTransactionRound( __METHOD__ );
		}

		$this->output( "Done\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = MakeTestEdits::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
