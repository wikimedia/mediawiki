<?php

use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @since 1.31
 */
class ImportableOldRevisionImporter implements OldRevisionImporter {

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var bool
	 */
	private $doUpdates;

	/**
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @param bool $doUpdates
	 * @param LoggerInterface $logger
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		$doUpdates,
		LoggerInterface $logger,
		ILoadBalancer $loadBalancer
	) {
		$this->doUpdates = $doUpdates;
		$this->logger = $logger;
		$this->loadBalancer = $loadBalancer;
	}

	public function import( ImportableOldRevision $importableRevision, $doUpdates = true ) {
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );

		# Sneak a single revision into place
		$user = $importableRevision->getUserObj() ?: User::newFromName( $importableRevision->getUser() );
		if ( $user ) {
			$userId = intval( $user->getId() );
			$userText = $user->getName();
		} else {
			$userId = 0;
			$userText = $importableRevision->getUser();
			$user = new User;
		}

		// avoid memory leak...?
		Title::clearCaches();

		$page = WikiPage::factory( $importableRevision->getTitle() );
		$page->loadPageData( 'fromdbmaster' );
		if ( !$page->exists() ) {
			// must create the page...
			$pageId = $page->insertOn( $dbw );
			$created = true;
			$oldcountable = null;
		} else {
			$pageId = $page->getId();
			$created = false;

			// Note: sha1 has been in XML dumps since 2012. If you have an
			// older dump, the duplicate detection here won't work.
			if ( $importableRevision->getSha1Base36() !== false ) {
				$prior = $dbw->selectField( 'revision', '1',
					[ 'rev_page' => $pageId,
					'rev_timestamp' => $dbw->timestamp( $importableRevision->getTimestamp() ),
					'rev_sha1' => $importableRevision->getSha1Base36() ],
					__METHOD__
				);
				if ( $prior ) {
					// @todo FIXME: This could fail slightly for multiple matches :P
					$this->logger->debug( __METHOD__ . ": skipping existing revision for [[" .
						$importableRevision->getTitle()->getPrefixedText() . "]], timestamp " .
						$importableRevision->getTimestamp() . "\n" );
					return false;
				}
			}
		}

		if ( !$pageId ) {
			// This seems to happen if two clients simultaneously try to import the
			// same page
			$this->logger->debug( __METHOD__ . ': got invalid $pageId when importing revision of [[' .
				$importableRevision->getTitle()->getPrefixedText() . ']], timestamp ' .
				$importableRevision->getTimestamp() . "\n" );
			return false;
		}

		// Select previous version to make size diffs correct
		// @todo This assumes that multiple revisions of the same page are imported
		// in order from oldest to newest.
		$prevId = $dbw->selectField( 'revision', 'rev_id',
			[
				'rev_page' => $pageId,
				'rev_timestamp <= ' . $dbw->addQuotes( $dbw->timestamp( $importableRevision->getTimestamp() ) ),
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
			'title' => $importableRevision->getTitle(),
			'page' => $pageId,
			'content_model' => $importableRevision->getModel(),
			'content_format' => $importableRevision->getFormat(),
			// XXX: just set 'content' => $wikiRevision->getContent()?
			'text' => $importableRevision->getContent()->serialize( $importableRevision->getFormat() ),
			'comment' => $importableRevision->getComment(),
			'user' => $userId,
			'user_text' => $userText,
			'timestamp' => $importableRevision->getTimestamp(),
			'minor_edit' => $importableRevision->getMinor(),
			'parent_id' => $prevId,
		] );
		$revision->insertOn( $dbw );
		$changed = $page->updateIfNewerOn( $dbw, $revision );

		$tags = $importableRevision->getTags();
		if ( $tags !== [] ) {
			ChangeTags::addTags( $tags, null, $revision->getId() );
		}

		if ( $changed !== false && $this->doUpdates ) {
			$this->logger->debug( __METHOD__ . ": running updates\n" );
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
