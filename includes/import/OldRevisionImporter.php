<?php

use Psr\Log\LoggerInterface;

/**
 * @since 1.30
 */
class OldRevisionImporter {

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var bool
	 */
	private $doUpdates;

	/**
	 * @param bool $doUpdates
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		$doUpdates,
		LoggerInterface $logger
	) {
		$this->doUpdates = $doUpdates;
		$this->logger = $logger;
	}

	/**
	 * @since 1.30
	 * 
	 * @param WikiRevisionOldRevision $wikiRevision
	 * 
	 * @return bool
	 */
	public function import( WikiRevisionOldRevision $wikiRevision ) {
		$dbw = wfGetDB( DB_MASTER );

		# Sneak a single revision into place
		$user = $wikiRevision->getUserObj() ?: User::newFromName( $wikiRevision->getUser() );
		if ( $user ) {
			$userId = intval( $user->getId() );
			$userText = $user->getName();
		} else {
			$userId = 0;
			$userText = $wikiRevision->getUser();
			$user = new User;
		}

		// avoid memory leak...?
		Title::clearCaches();

		$page = WikiPage::factory( $wikiRevision->getTitle() );
		$page->loadPageData( 'fromdbmaster' );
		if ( !$page->exists() ) {
			// must create the page...
			$pageId = $page->insertOn( $dbw );
			$created = true;
			$oldcountable = null;
		} else {
			$pageId = $page->getId();
			$created = false;

			$prior = $dbw->selectField( 'revision', '1',
				[ 'rev_page' => $pageId,
					'rev_timestamp' => $dbw->timestamp( $wikiRevision->getTimestamp() ),
					'rev_user_text' => $userText,
					'rev_comment' => $wikiRevision->getComment() ],
				__METHOD__
			);
			if ( $prior ) {
				// @todo FIXME: This could fail slightly for multiple matches :P
				wfDebug( __METHOD__ . ": skipping existing revision for [[" .
					$wikiRevision->getTitle()->getPrefixedText() . "]], timestamp " . $wikiRevision->getTimestamp() . "\n" );
				return false;
			}
		}

		if ( !$pageId ) {
			// This seems to happen if two clients simultaneously try to import the
			// same page
			wfDebug( __METHOD__ . ': got invalid $pageId when importing revision of [[' .
				$wikiRevision->getTitle()->getPrefixedText() . ']], timestamp ' . $wikiRevision->getTimestamp() . "\n" );
			return false;
		}

		// Select previous version to make size diffs correct
		// @todo This assumes that multiple revisions of the same page are imported
		// in order from oldest to newest.
		$prevId = $dbw->selectField( 'revision', 'rev_id',
			[
				'rev_page' => $pageId,
				'rev_timestamp <= ' . $dbw->addQuotes( $dbw->timestamp( $wikiRevision->getTimestamp() ) ),
			],
			__METHOD__,
			[ 'ORDER BY' => [
				'rev_timestamp DESC',
				'rev_id DESC', // timestamp is not unique per page
			]
			]
		);

		# @todo FIXME: Use original rev_id optionally (better for backups)
		# Insert the row
		$revision = new Revision( [
			'title' => $wikiRevision->getTitle(),
			'page' => $pageId,
			'content_model' => $wikiRevision->getModel(),
			'content_format' => $wikiRevision->getFormat(),
			// XXX: just set 'content' => $wikiRevision->getContent()?
			'text' => $wikiRevision->getContent()->serialize( $wikiRevision->getFormat() ),
			'comment' => $wikiRevision->getComment(),
			'user' => $userId,
			'user_text' => $userText,
			'timestamp' => $wikiRevision->getTimestamp(),
			'minor_edit' => $wikiRevision->getMinor(),
			'parent_id' => $prevId,
		] );
		$revision->insertOn( $dbw );
		$changed = $page->updateIfNewerOn( $dbw, $revision );

		if ( $changed !== false && $this->doUpdates ) {
			wfDebug( __METHOD__ . ": running updates\n" );
			// countable/oldcountable stuff is handled in WikiImporter::finishImportPage
			$page->doEditUpdates(
				$revision,
				$user,
				[ 'created' => $created, 'oldcountable' => 'no-change' ]
			);
		}

		return true;
	}

}
