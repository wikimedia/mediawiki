<?php
/**
 * Job to fix double redirects after moving a page.
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
 * @ingroup JobQueue
 */

/**
 * Job to fix double redirects after moving a page
 *
 * @ingroup JobQueue
 */
class DoubleRedirectJob extends Job {
	/** @var string Reason for the change, 'maintenance' or 'move'. Suffix fo
	 *    message key 'double-redirect-fixed-'.
	 */
	private $reason;

	/** @var Title The title which has changed, redirects pointing to this
	 *    title are fixed
	 */
	private $redirTitle;

	/** @var User */
	private static $user;

	/**
	 * Insert jobs into the job queue to fix redirects to the given title
	 * @param string $reason The reason for the fix, see message
	 *   "double-redirect-fixed-<reason>"
	 * @param Title $redirTitle The title which has changed, redirects
	 *   pointing to this title are fixed
	 * @param bool $destTitle Not used
	 */
	public static function fixRedirects( $reason, $redirTitle, $destTitle = false ) {
		# Need to use the master to get the redirect table updated in the same transaction
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->select(
			array( 'redirect', 'page' ),
			array( 'page_namespace', 'page_title' ),
			array(
				'page_id = rd_from',
				'rd_namespace' => $redirTitle->getNamespace(),
				'rd_title' => $redirTitle->getDBkey()
			), __METHOD__ );
		if ( !$res->numRows() ) {
			return;
		}
		$jobs = array();
		foreach ( $res as $row ) {
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			if ( !$title ) {
				continue;
			}

			$jobs[] = new self( $title, array(
				'reason' => $reason,
				'redirTitle' => $redirTitle->getPrefixedDBkey() ) );
			# Avoid excessive memory usage
			if ( count( $jobs ) > 10000 ) {
				JobQueueGroup::singleton()->push( $jobs );
				$jobs = array();
			}
		}
		JobQueueGroup::singleton()->push( $jobs );
	}

	/**
	 * @param Title $title
	 * @param array|bool $params
	 */
	function __construct( $title, $params = false ) {
		parent::__construct( 'fixDoubleRedirect', $title, $params );
		$this->reason = $params['reason'];
		$this->redirTitle = Title::newFromText( $params['redirTitle'] );
	}

	/**
	 * @return bool
	 */
	function run() {
		if ( !$this->redirTitle ) {
			$this->setLastError( 'Invalid title' );

			return false;
		}

		$targetRev = Revision::newFromTitle( $this->title, false, Revision::READ_LATEST );
		if ( !$targetRev ) {
			wfDebug( __METHOD__ . ": target redirect already deleted, ignoring\n" );

			return true;
		}
		$content = $targetRev->getContent();
		$currentDest = $content ? $content->getRedirectTarget() : null;
		if ( !$currentDest || !$currentDest->equals( $this->redirTitle ) ) {
			wfDebug( __METHOD__ . ": Redirect has changed since the job was queued\n" );

			return true;
		}

		// Check for a suppression tag (used e.g. in periodically archived discussions)
		$mw = MagicWord::get( 'staticredirect' );
		if ( $content->matchMagicWord( $mw ) ) {
			wfDebug( __METHOD__ . ": skipping: suppressed with __STATICREDIRECT__\n" );

			return true;
		}

		// Find the current final destination
		$newTitle = self::getFinalDestination( $this->redirTitle );
		if ( !$newTitle ) {
			wfDebug( __METHOD__ .
				": skipping: single redirect, circular redirect or invalid redirect destination\n" );

			return true;
		}
		if ( $newTitle->equals( $this->redirTitle ) ) {
			// The redirect is already right, no need to change it
			// This can happen if the page was moved back (say after vandalism)
			wfDebug( __METHOD__ . " : skipping, already good\n" );
		}

		// Preserve fragment (bug 14904)
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
		global $wgUser;
		$oldUser = $wgUser;
		$wgUser = $user;
		$article = WikiPage::factory( $this->title );

		// Messages: double-redirect-fixed-move, double-redirect-fixed-maintenance
		$reason = wfMessage( 'double-redirect-fixed-' . $this->reason,
			$this->redirTitle->getPrefixedText(), $newTitle->getPrefixedText()
		)->inContentLanguage()->text();
		$article->doEditContent( $newContent, $reason, EDIT_UPDATE | EDIT_SUPPRESS_RC, false, $user );
		$wgUser = $oldUser;

		return true;
	}

	/**
	 * Get the final destination of a redirect
	 *
	 * @param Title $title
	 *
	 * @return bool If the specified title is not a redirect, or if it is a circular redirect
	 */
	public static function getFinalDestination( $title ) {
		$dbw = wfGetDB( DB_MASTER );

		// Circular redirect check
		$seenTitles = array();
		$dest = false;

		while ( true ) {
			$titleText = $title->getPrefixedDBkey();
			if ( isset( $seenTitles[$titleText] ) ) {
				wfDebug( __METHOD__, "Circular redirect detected, aborting\n" );

				return false;
			}
			$seenTitles[$titleText] = true;

			if ( $title->isExternal() ) {
				// If the target is interwiki, we have to break early (bug 40352).
				// Otherwise it will look up a row in the local page table
				// with the namespace/page of the interwiki target which can cause
				// unexpected results (e.g. X -> foo:Bar -> Bar -> .. )
				break;
			}

			$row = $dbw->selectRow(
				array( 'redirect', 'page' ),
				array( 'rd_namespace', 'rd_title', 'rd_interwiki' ),
				array(
					'rd_from=page_id',
					'page_namespace' => $title->getNamespace(),
					'page_title' => $title->getDBkey()
				), __METHOD__ );
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
	 * @return User|bool
	 */
	function getUser() {
		if ( !self::$user ) {
			$username = wfMessage( 'double-redirect-fixer' )->inContentLanguage()->text();
			self::$user = User::newFromName( $username );
			# User::newFromName() can return false on a badly configured wiki.
			if ( self::$user && !self::$user->isLoggedIn() ) {
				self::$user->addToDatabase();
			}
		}

		return self::$user;
	}
}
