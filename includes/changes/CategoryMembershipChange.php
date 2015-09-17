<?php
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
 * @author Adam Shorland
 * @since 1.26
 */

use Wikimedia\Assert\Assert;

class CategoryMembershipChange {

	const CATEGORY_ADDITION = 1;
	const CATEGORY_REMOVAL = -1;

	/**
	 * @var string Current timestamp, set during CategoryMembershipChange::__construct()
	 */
	private $timestamp;

	/**
	 * @var Title Title instance of the categorized page
	 */
	private $pageTitle;

	/**
	 * @var Revision|null Latest Revision instance of the categorized page
	 */
	private $revision;

	/**
	 * @var callable|null
	 */
	private $notifyCategorizationCallback = null;

	/**
	 * @param Title $pageTitle Title instance of the categorized page
	 * @param Revision $revision Latest Revision instance of the categorized page
	 *
	 * @throws MWException
	 */
	public function __construct( Title $pageTitle, Revision $revision = null ) {
		$this->pageTitle = $pageTitle;
		$this->timestamp = wfTimestampNow();
		$this->revision = $revision;
	}

	/**
	 * Overrides the default notify categorization callback
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param callable $callback
	 *
	 * @throws MWException
	 */
	public function overrideNotifyCategorizationCallback( $callback ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException( 'Cannot reset hooks in operation.' );
		}
		Assert::parameterType( 'callable', $callback, '$callback' );
		$this->notifyCategorizationCallback = $callback;
	}

	/**
	 * Create a recentchanges entry for category additions
	 *
	 * @param string $categoryName Format: spaces, no namespace
	 */
	public function triggerCategoryAddedNotification( $categoryName ) {
		$this->createRecentChangesEntry( $categoryName, self::CATEGORY_ADDITION );
	}

	/**
	 * Create a recentchanges entry for category removals
	 *
	 * @param string $categoryName Format: spaces, no namespace
	 */
	public function triggerCategoryRemovedNotification( $categoryName ) {
		$this->createRecentChangesEntry( $categoryName, self::CATEGORY_REMOVAL );
	}

	/**
	 * Create a recentchanges entry using RecentChange::notifyCategorization()
	 *
	 * @param string $categoryName Format: spaces, no namespace
	 * @param int $type
	 */
	private function createRecentChangesEntry( $categoryName, $type ) {
		$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
		if ( $categoryTitle === null ) {
			throw new RuntimeException( 'Could not get Title for $categoryName: ' . $categoryName );
		}

		$this->notifyCategorization(
			$this->timestamp,
			$categoryTitle,
			$this->getUser(),
			$this->getChangeMessageText( $type, array(
				'prefixedUrl' => $this->pageTitle->getPrefixedText(),
			) ),
			$this->pageTitle,
			$this->getPreviousRevisionTimestamp(),
			$this->revision
		);
	}

	/**
	 * @param string $timestamp Timestamp of the recent change to occur in TS_MW format
	 * @param Title $categoryTitle Title of the category a page is being added to or removed from
	 * @param User $user User object of the user that made the change
	 * @param string $comment Change summary
	 * @param Title $pageTitle Title of the page that is being added or removed
	 * @param string $lastTimestamp Parent revision timestamp of this change in TS_MW format
	 * @param Revision|null $revision
	 *
	 * @throws MWException
	 */
	private function notifyCategorization(
		$timestamp,
		Title $categoryTitle,
		User $user = null,
		$comment,
		Title $pageTitle,
		$lastTimestamp,
		$revision
	) {
		$deleted = $revision ? $revision->getVisibility() & Revision::SUPPRESSED_USER : 0;
		$newRevId = $revision ? $revision->getId() : 0;

		/**
		 * T109700 - Default bot flag to true when there is no corresponding RC entry
		 * This means all changes caused by parser functions & Lua on reparse are marked as bot
		 * Also in the case no RC entry could be found due to slave lag
		 */
		$bot = 1;
		$lastRevId = 0;
		$ip = '';

		# If no revision is given, the change was probably triggered by parser functions
		if ( $revision !== null ) {
			// TODO if no RC try again from the master DB?
			$correspondingRc = $this->revision->getRecentChange();
			if ( $correspondingRc !== null ) {
				$bot = $correspondingRc->getAttribute( 'rc_bot' ) ?: 0;
				$ip = $correspondingRc->getAttribute( 'rc_ip' ) ?: '';
				$lastRevId = $correspondingRc->getAttribute( 'rc_last_oldid' ) ?: 0;
			}
		}

		if ( $this->notifyCategorizationCallback === null ) {
			RecentChange::notifyCategorization(
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
				$deleted
			);
		} else {
			call_user_func_array(
				$this->notifyCategorizationCallback,
				array(
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
					$deleted
				)
			);
		}
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
			$userId = $this->revision->getUser( Revision::RAW );
			if ( $userId === 0 ) {
				return User::newFromName( $this->revision->getUserText( Revision::RAW ), false );
			} else {
				return User::newFromId( $userId );
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
	 * - recentchanges-page-removed-from-category
	 *
	 * @param int $type may be CategoryMembershipChange::CATEGORY_ADDITION
	 * or CategoryMembershipChange::CATEGORY_REMOVAL
	 * @param array $params
	 * - prefixedUrl: result of Title::->getPrefixedURL()
	 *
	 * @return string
	 */
	private function getChangeMessageText( $type, array $params ) {
		$array = array(
			self::CATEGORY_ADDITION => 'recentchanges-page-added-to-category',
			self::CATEGORY_REMOVAL => 'recentchanges-page-removed-from-category',
		);

		return wfMessage( $array[$type], $params )->inContentLanguage()->text();
	}

	/**
	 * Returns the timestamp of the page's previous revision or null if the latest revision
	 * does not refer to a parent revision
	 *
	 * @return null|string
	 */
	private function getPreviousRevisionTimestamp() {
		$previousRev = Revision::newFromId(
				$this->pageTitle->getPreviousRevisionID( $this->pageTitle->getLatestRevID() )
			);

		return $previousRev ? $previousRev->getTimestamp() : null;
	}

}
