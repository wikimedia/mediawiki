<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;

/**
 * Helper class for category membership changes
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
 * @author Kai Nissen
 * @author Addshore
 * @since 1.27
 */

class CategoryMembershipChange {

	private const CATEGORY_ADDITION = 1;
	private const CATEGORY_REMOVAL = -1;

	/**
	 * @var string Current timestamp, set during CategoryMembershipChange::__construct()
	 */
	private $timestamp;

	/**
	 * @var Title Title instance of the categorized page
	 */
	private $pageTitle;

	/**
	 * @var RevisionRecord|null Latest Revision instance of the categorized page
	 */
	private $revision;

	/**
	 * @var int
	 * Number of pages this WikiPage is embedded by
	 * Set by CategoryMembershipChange::checkTemplateLinks()
	 */
	private $numTemplateLinks = 0;

	/**
	 * @var callable|null
	 */
	private $newForCategorizationCallback = null;

	/**
	 * @param Title $pageTitle Title instance of the categorized page
	 * @param RevisionRecord|Revision|null $revision Latest Revision instance of the categorized page.
	 *   Since 1.35 passing a Revision object is deprecated in favor of RevisionRecord.
	 *
	 * @throws MWException
	 */
	public function __construct( Title $pageTitle, $revision = null ) {
		$this->pageTitle = $pageTitle;
		if ( $revision instanceof Revision ) {
			wfDeprecatedMsg(
				'Passing a Revision for the $revision parameter to ' . __METHOD__ .
				' was deprecated in MediaWiki 1.35',
				'1.35'
			);
			$revision = $revision->getRevisionRecord();
		}
		$this->revision = $revision;
		if ( $revision === null ) {
			$this->timestamp = wfTimestampNow();
		} else {
			$this->timestamp = $revision->getTimestamp();
		}
		$this->newForCategorizationCallback = [ RecentChange::class, 'newForCategorization' ];
	}

	/**
	 * Overrides the default new for categorization callback
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param callable $callback
	 * @see RecentChange::newForCategorization for callback signiture
	 *
	 * @throws MWException
	 */
	public function overrideNewForCategorizationCallback( callable $callback ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException( 'Cannot override newForCategorization callback in operation.' );
		}
		$this->newForCategorizationCallback = $callback;
	}

	/**
	 * Determines the number of template links for recursive link updates
	 */
	public function checkTemplateLinks() {
		$this->numTemplateLinks = $this->pageTitle->getBacklinkCache()->getNumLinks( 'templatelinks' );
	}

	/**
	 * Create a recentchanges entry for category additions
	 *
	 * @param Title $categoryTitle
	 */
	public function triggerCategoryAddedNotification( Title $categoryTitle ) {
		$this->createRecentChangesEntry( $categoryTitle, self::CATEGORY_ADDITION );
	}

	/**
	 * Create a recentchanges entry for category removals
	 *
	 * @param Title $categoryTitle
	 */
	public function triggerCategoryRemovedNotification( Title $categoryTitle ) {
		$this->createRecentChangesEntry( $categoryTitle, self::CATEGORY_REMOVAL );
	}

	/**
	 * Create a recentchanges entry using RecentChange::notifyCategorization()
	 *
	 * @param Title $categoryTitle
	 * @param int $type
	 */
	private function createRecentChangesEntry( Title $categoryTitle, $type ) {
		$this->notifyCategorization(
			$this->timestamp,
			$categoryTitle,
			$this->getUser(),
			$this->getChangeMessageText(
				$type,
				$this->pageTitle->getPrefixedText(),
				$this->numTemplateLinks
			),
			$this->pageTitle,
			$this->getPreviousRevisionTimestamp(),
			$this->revision,
			$type === self::CATEGORY_ADDITION
		);
	}

	/**
	 * @param string $timestamp Timestamp of the recent change to occur in TS_MW format
	 * @param Title $categoryTitle Title of the category a page is being added to or removed from
	 * @param User|null $user User object of the user that made the change
	 * @param string $comment Change summary
	 * @param Title $pageTitle Title of the page that is being added or removed
	 * @param string $lastTimestamp Parent revision timestamp of this change in TS_MW format
	 * @param RevisionRecord|null $revision
	 * @param bool $added true, if the category was added, false for removed
	 *
	 * @throws MWException
	 */
	private function notifyCategorization(
		$timestamp,
		Title $categoryTitle,
		?User $user,
		$comment,
		Title $pageTitle,
		$lastTimestamp,
		$revision,
		$added
	) {
		$deleted = $revision ? $revision->getVisibility() & RevisionRecord::SUPPRESSED_USER : 0;
		$newRevId = $revision ? $revision->getId() : 0;

		/**
		 * T109700 - Default bot flag to true when there is no corresponding RC entry
		 * This means all changes caused by parser functions & Lua on reparse are marked as bot
		 * Also in the case no RC entry could be found due to replica DB lag
		 */
		$bot = 1;
		$lastRevId = 0;
		$ip = '';

		# If no revision is given, the change was probably triggered by parser functions
		if ( $revision !== null ) {
			$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();

			$correspondingRc = $revisionStore->getRecentChange( $this->revision );
			if ( $correspondingRc === null ) {
				$correspondingRc = $revisionStore->getRecentChange(
					$this->revision,
					RevisionStore::READ_LATEST
				);
			}
			if ( $correspondingRc !== null ) {
				$bot = $correspondingRc->getAttribute( 'rc_bot' ) ?: 0;
				$ip = $correspondingRc->getAttribute( 'rc_ip' ) ?: '';
				$lastRevId = $correspondingRc->getAttribute( 'rc_last_oldid' ) ?: 0;
			}
		}

		/** @var RecentChange $rc */
		$rc = ( $this->newForCategorizationCallback )(
			$timestamp,
			$categoryTitle,
			$user,
			$comment,
			$pageTitle,
			$lastRevId,
			$newRevId,
			$lastTimestamp,
			$bot,
			$ip,
			$deleted,
			$added
		);
		$rc->save();
	}

	/**
	 * Get the user associated with this change.
	 *
	 * If there is no revision associated with the change and thus no editing user
	 * fallback to a default.
	 *
	 * False will be returned if the user name specified in the
	 * 'autochange-username' message is invalid.
	 *
	 * @return User|bool
	 */
	private function getUser() {
		if ( $this->revision ) {
			$userIdentity = $this->revision->getUser( RevisionRecord::RAW );
			if ( $userIdentity ) {
				return User::newFromIdentity( $userIdentity );
			}
		}

		$username = wfMessage( 'autochange-username' )->inContentLanguage()->text();
		$user = User::newFromName( $username );
		# User::newFromName() can return false on a badly configured wiki.
		if ( $user && !$user->isLoggedIn() ) {
			$user->addToDatabase();
		}

		return $user;
	}

	/**
	 * Returns the change message according to the type of category membership change
	 *
	 * The message keys created in this method may be one of:
	 * - recentchanges-page-added-to-category
	 * - recentchanges-page-added-to-category-bundled
	 * - recentchanges-page-removed-from-category
	 * - recentchanges-page-removed-from-category-bundled
	 *
	 * @param int $type may be CategoryMembershipChange::CATEGORY_ADDITION
	 * or CategoryMembershipChange::CATEGORY_REMOVAL
	 * @param string $prefixedText result of Title::->getPrefixedText()
	 * @param int $numTemplateLinks
	 *
	 * @return string
	 */
	private function getChangeMessageText( $type, $prefixedText, $numTemplateLinks ) {
		$array = [
			self::CATEGORY_ADDITION => 'recentchanges-page-added-to-category',
			self::CATEGORY_REMOVAL => 'recentchanges-page-removed-from-category',
		];

		$msgKey = $array[$type];

		if ( intval( $numTemplateLinks ) > 0 ) {
			$msgKey .= '-bundled';
		}

		return wfMessage( $msgKey, $prefixedText )->inContentLanguage()->text();
	}

	/**
	 * Returns the timestamp of the page's previous revision or null if the latest revision
	 * does not refer to a parent revision
	 *
	 * @return null|string
	 */
	private function getPreviousRevisionTimestamp() {
		$rl = MediaWikiServices::getInstance()->getRevisionLookup();
		$latestRev = $rl->getRevisionByTitle( $this->pageTitle );
		if ( $latestRev ) {
			$previousRev = $rl->getPreviousRevision( $latestRev );
			if ( $previousRev ) {
				return $previousRev->getTimestamp();
			}
		}
		return null;
	}

}
