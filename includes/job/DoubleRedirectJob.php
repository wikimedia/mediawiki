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
	var $reason, $redirTitle;

	/**
	 * @var User
	 */
	static $user;

	/**
	 * Insert jobs into the job queue to fix redirects to the given title
	 * @param $reason String: the reason for the fix, see message "double-redirect-fixed-<reason>"
	 * @param $redirTitle Title: the title which has changed, redirects pointing to this title are fixed
	 * @param $destTitle bool Not used
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
				Job::batchInsert( $jobs );
				$jobs = array();
			}
		}
		Job::batchInsert( $jobs );
	}

	function __construct( $title, $params = false, $id = 0 ) {
		parent::__construct( 'fixDoubleRedirect', $title, $params, $id );
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
			wfDebug( __METHOD__.": target redirect already deleted, ignoring\n" );
			return true;
		}
		$content = $targetRev->getContent();
		$currentDest = $content->getRedirectTarget();
		if ( !$currentDest || !$currentDest->equals( $this->redirTitle ) ) {
			wfDebug( __METHOD__.": Redirect has changed since the job was queued\n" );
			return true;
		}

		# Check for a suppression tag (used e.g. in periodically archived discussions)
		$mw = MagicWord::get( 'staticredirect' );
		if ( $content->matchMagicWord( $mw ) ) {
			wfDebug( __METHOD__.": skipping: suppressed with __STATICREDIRECT__\n" );
			return true;
		}

		# Find the current final destination
		$newTitle = self::getFinalDestination( $this->redirTitle );
		if ( !$newTitle ) {
			wfDebug( __METHOD__.": skipping: single redirect, circular redirect or invalid redirect destination\n" );
			return true;
		}
		if ( $newTitle->equals( $this->redirTitle ) ) {
			# The redirect is already right, no need to change it
			# This can happen if the page was moved back (say after vandalism)
			wfDebug( __METHOD__.": skipping, already good\n" );
		}

		# Preserve fragment (bug 14904)
		$newTitle = Title::makeTitle( $newTitle->getNamespace(), $newTitle->getDBkey(),
			$currentDest->getFragment(), $newTitle->getInterwiki() );

		# Fix the text
		$newContent = $content->updateRedirect( $newTitle );

		if ( $newContent->equals( $content ) ) {
			$this->setLastError( 'Content unchanged???' );
			return false;
		}

		# Save it
		global $wgUser;
		$oldUser = $wgUser;
		$wgUser = $this->getUser();
		$article = WikiPage::factory( $this->title );
		$reason = wfMessage( 'double-redirect-fixed-' . $this->reason,
			$this->redirTitle->getPrefixedText(), $newTitle->getPrefixedText()
		)->inContentLanguage()->text();
		$article->doEditContent( $newContent, $reason, EDIT_UPDATE | EDIT_SUPPRESS_RC, false, $this->getUser() );
		$wgUser = $oldUser;

		return true;
	}

	/**
	 * Get the final destination of a redirect
	 *
	 * @param $title Title
	 *
	 * @return bool if the specified title is not a redirect, or if it is a circular redirect
	 */
	public static function getFinalDestination( $title ) {
		$dbw = wfGetDB( DB_MASTER );

		$seenTitles = array(); # Circular redirect check
		$dest = false;

		while ( true ) {
			$titleText = $title->getPrefixedDBkey();
			if ( isset( $seenTitles[$titleText] ) ) {
				wfDebug( __METHOD__, "Circular redirect detected, aborting\n" );
				return false;
			}
			$seenTitles[$titleText] = true;

			if ( $title->getInterwiki() ) {
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
				$dest = $title = Title::makeTitle( $row->rd_namespace, $row->rd_title, '', $row->rd_interwiki );
			}
		}
		return $dest;
	}

	/**
	 * Get a user object for doing edits, from a request-lifetime cache
	 * @return User
	 */
	function getUser() {
		if ( !self::$user ) {
			self::$user = User::newFromName( wfMessage( 'double-redirect-fixer' )->inContentLanguage()->text(), false );
			# FIXME: newFromName could return false on a badly configured wiki.
			if ( !self::$user->isLoggedIn() ) {
				self::$user->addToDatabase();
			}
		}
		return self::$user;
	}
}

