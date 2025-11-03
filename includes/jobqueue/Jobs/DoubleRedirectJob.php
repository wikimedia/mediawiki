<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\JobQueue\Job;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Fix any double redirects after moving a page.
 *
 * @ingroup JobQueue
 */
class DoubleRedirectJob extends Job {
	/**
	 * @var int Max number of double redirect jobs counter.
	 *   This is meant to avoid excessive memory usage. This is
	 *   also used in fixDoubleRedirects.php script.
	 */
	public const MAX_DR_JOBS_COUNTER = 10000;

	/** @var Title The title which has changed, redirects pointing to this
	 *    title are fixed
	 */
	private $redirTitle;

	/** @var User */
	private static $user;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var MagicWordFactory */
	private $magicWordFactory;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/**
	 * @param PageReference $page
	 * @param array $params Expected to contain these elements:
	 * - 'redirTitle' => string The title that changed and should be fixed.
	 * - 'reason' => string Reason for the change, can be "move" or "maintenance". Used as a suffix
	 *   for the message keys "double-redirect-fixed-move" and
	 *   "double-redirect-fixed-maintenance".
	 * ]
	 * @param RevisionLookup $revisionLookup
	 * @param MagicWordFactory $magicWordFactory
	 * @param WikiPageFactory $wikiPageFactory
	 */
	public function __construct(
		PageReference $page,
		array $params,
		RevisionLookup $revisionLookup,
		MagicWordFactory $magicWordFactory,
		WikiPageFactory $wikiPageFactory
	) {
		parent::__construct( 'fixDoubleRedirect', $page, $params );
		$this->redirTitle = Title::newFromText( $params['redirTitle'] );
		$this->revisionLookup = $revisionLookup;
		$this->magicWordFactory = $magicWordFactory;
		$this->wikiPageFactory = $wikiPageFactory;
	}

	/**
	 * Insert jobs into the job queue to fix redirects to the given title
	 * @param string $reason The reason for the fix, see message
	 *   "double-redirect-fixed-<reason>"
	 * @param LinkTarget $redirTitle The title which has changed, redirects
	 *   pointing to this title are fixed
	 */
	public static function fixRedirects( $reason, $redirTitle ) {
		# Need to use the primary DB to get the redirect table updated in the same transaction
		$services = MediaWikiServices::getInstance();
		$dbw = $services->getConnectionProvider()->getPrimaryDatabase();
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title' ] )
			->from( 'redirect' )
			->join( 'page', null, 'page_id = rd_from' )
			->where( [ 'rd_namespace' => $redirTitle->getNamespace(), 'rd_title' => $redirTitle->getDBkey() ] )
			->andWhere( [ 'rd_interwiki' => '' ] )
			->caller( __METHOD__ )->fetchResultSet();
		if ( !$res->numRows() ) {
			return;
		}
		$jobs = [];
		$jobQueueGroup = $services->getJobQueueGroup();
		foreach ( $res as $row ) {
			$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
			if ( !$title || !$title->canExist() ) {
				continue;
			}

			$jobs[] = new self(
				$title,
				[
					'reason' => $reason,
					'redirTitle' => $services->getTitleFormatter()
						->getPrefixedDBkey( $redirTitle )
				],
				$services->getRevisionLookup(),
				$services->getMagicWordFactory(),
				$services->getWikiPageFactory()
			);
			# Avoid excessive memory usage
			if ( count( $jobs ) > self::MAX_DR_JOBS_COUNTER ) {
				$jobQueueGroup->push( $jobs );
				$jobs = [];
			}
		}
		$jobQueueGroup->push( $jobs );
	}

	/**
	 * @return bool
	 */
	public function run() {
		if ( !$this->redirTitle ) {
			$this->setLastError( 'Invalid title' );

			return false;
		}

		if ( !$this->title->canExist() ) {
			// Needs a proper title for WikiPageFactory::newFromTitle and RevisionStore::getRevisionByTitle
			$this->setLastError( 'Cannot edit title' );

			return false;
		}

		$targetRev = $this->revisionLookup
			->getRevisionByTitle( $this->title, 0, IDBAccessObject::READ_LATEST );
		if ( !$targetRev ) {
			wfDebug( __METHOD__ . ": target redirect already deleted, ignoring" );

			return true;
		}
		$content = $targetRev->getContent( SlotRecord::MAIN );
		$currentDest = $content ? $content->getRedirectTarget() : null;
		if ( !$currentDest || !$currentDest->equals( $this->redirTitle ) ) {
			wfDebug( __METHOD__ . ": Redirect has changed since the job was queued" );

			return true;
		}

		// Check for a suppression tag (used e.g. in periodically archived discussions)
		$mw = $this->magicWordFactory->get( 'staticredirect' );
		if ( $content->matchMagicWord( $mw ) ) {
			wfDebug( __METHOD__ . ": skipping: suppressed with __STATICREDIRECT__" );

			return true;
		}

		// Find the current final destination
		$newTitle = self::getFinalDestination( $this->redirTitle );
		if ( !$newTitle ) {
			wfDebug( __METHOD__ .
				": skipping: single redirect, circular redirect or invalid redirect destination" );

			return true;
		}
		if ( $newTitle->equals( $this->redirTitle ) ) {
			// The redirect is already right, no need to change it
			// This can happen if the page was moved back (say after vandalism)
			wfDebug( __METHOD__ . " : skipping, already good" );
		}

		// Preserve fragment (T16904)
		$newTitle = Title::makeTitle( $newTitle->getNamespace(), $newTitle->getDBkey(),
			$currentDest->getFragment(), $newTitle->getInterwiki() );

		// Fix the text
		$newContent = $content->updateRedirect( $newTitle );

		if ( $newContent->equals( $content ) ) {
			$this->setLastError( 'Content unchanged???' );

			return false;
		}

		$user = $this->getUser();
		if ( !$user ) {
			$this->setLastError( 'Invalid user' );

			return false;
		}

		// Save it
		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgUser
		global $wgUser;
		$oldUser = $wgUser;
		$wgUser = $user;
		$article = $this->wikiPageFactory->newFromTitle( $this->title );

		// Messages: double-redirect-fixed-move, double-redirect-fixed-maintenance
		$reason = wfMessage( 'double-redirect-fixed-' . $this->params['reason'],
			$this->redirTitle->getPrefixedText(), $newTitle->getPrefixedText()
		)->inContentLanguage()->text();
		// Avoid RC flood, and use minor to avoid email notifs
		$flags = EDIT_UPDATE | EDIT_SUPPRESS_RC | EDIT_INTERNAL | EDIT_MINOR;
		$article->doUserEditContent( $newContent, $user, $reason, $flags );
		$wgUser = $oldUser;

		return true;
	}

	/**
	 * Get the final destination of a redirect
	 *
	 * @param LinkTarget $title
	 *
	 * @return Title|false The final Title after following all redirects, or false if
	 *  the page is not a redirect or the redirect loops.
	 */
	public static function getFinalDestination( $title ) {
		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();

		// Circular redirect check
		$seenTitles = [];
		$dest = false;

		while ( true ) {
			$titleText = CacheKeyHelper::getKeyForPage( $title );
			if ( isset( $seenTitles[$titleText] ) ) {
				wfDebug( __METHOD__, "Circular redirect detected, aborting" );

				return false;
			}
			$seenTitles[$titleText] = true;

			if ( $title->isExternal() ) {
				// If the target is interwiki, we have to break early (T42352).
				// Otherwise it will look up a row in the local page table
				// with the namespace/page of the interwiki target which can cause
				// unexpected results (e.g. X -> foo:Bar -> Bar -> .. )
				break;
			}
			$row = $dbw->newSelectQueryBuilder()
				->select( [ 'rd_namespace', 'rd_title', 'rd_interwiki' ] )
				->from( 'redirect' )
				->join( 'page', null, 'page_id = rd_from' )
				->where( [ 'page_namespace' => $title->getNamespace() ] )
				->andWhere( [ 'page_title' => $title->getDBkey() ] )
				->caller( __METHOD__ )->fetchRow();
			if ( !$row ) {
				# No redirect from here, chain terminates
				break;
			} else {
				$dest = $title = Title::makeTitle(
					$row->rd_namespace,
					$row->rd_title,
					'',
					$row->rd_interwiki
				);
			}
		}

		return $dest;
	}

	/**
	 * Get a user object for doing edits, from a request-lifetime cache
	 * False will be returned if the user name specified in the
	 * 'double-redirect-fixer' message is invalid.
	 *
	 * @return User|false
	 */
	private function getUser() {
		if ( !self::$user ) {
			$username = wfMessage( 'double-redirect-fixer' )->inContentLanguage()->text();
			self::$user = User::newFromName( $username );
			# User::newFromName() can return false on a badly configured wiki.
			if ( self::$user && !self::$user->isRegistered() ) {
				self::$user->addToDatabase();
			}
		}

		return self::$user;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( DoubleRedirectJob::class, 'DoubleRedirectJob' );
