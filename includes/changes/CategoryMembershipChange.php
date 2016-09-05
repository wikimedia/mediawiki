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
 * @author Addshore
 * @since 1.27
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
	 * @param Revision $revision Latest Revision instance of the categorized page
	 *
	 * @throws MWException
	 */
	public function __construct( Title $pageTitle, Revision $revision = null ) {
		$this->pageTitle = $pageTitle;
		if ( $revision === null ) {
			$this->timestamp = wfTimestampNow();
		} else {
			$this->timestamp = $revision->getTimestamp();
		}
		$this->revision = $revision;
		$this->newForCategorizationCallback = [ 'RecentChange', 'newForCategorization' ];
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
	public function overrideNewForCategorizationCallback( $callback ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException( 'Cannot override newForCategorization callback in operation.' );
		}
		Assert::parameterType( 'callable', $callback, '$callback' );
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
				[ 'prefixedText' => $this->pageTitle->getPrefixedText() ],
				$this->numTemplateLinks
			),
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
		 * Also in the case no RC entry could be found due to replica DB lag
		 */
		$bot = 1;
		$lastRevId = 0;
		$ip = '';

		# If no revision is given, the change was probably triggered by parser functions
		if ( $revision !== null ) {
			$correspondingRc = $this->revision->getRecentChange();
			if ( $correspondingRc === null ) {
				$correspondingRc = $this->revision->getRecentChange( Revision::READ_LATEST );
			}
			if ( $correspondingRc !== null ) {
				$bot = $correspondingRc->getAttribute( 'rc_bot' ) ?: 0;
				$ip = $correspondingRc->getAttribute( 'rc_ip' ) ?: '';
				$lastRevId = $correspondingRc->getAttribute( 'rc_last_oldid' ) ?: 0;
			}
		}

		/** @var RecentChange $rc */
		$rc = call_user_func_array(
			$this->newForCategorizationCallback,
			[
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
			]
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
	 * - recentchanges-page-added-to-category-bundled
	 * - recentchanges-page-removed-from-category
	 * - recentchanges-page-removed-from-category-bundled
	 *
	 * @param int $type may be CategoryMembershipChange::CATEGORY_ADDITION
	 * or CategoryMembershipChange::CATEGORY_REMOVAL
	 * @param array $params
	 * - prefixedText: result of Title::->getPrefixedText()
	 * @param int $numTemplateLinks
	 *
	 * @return string
	 */
	private function getChangeMessageText( $type, array $params, $numTemplateLinks ) {
		$array = [
			self::CATEGORY_ADDITION => 'recentchanges-page-added-to-category',
			self::CATEGORY_REMOVAL => 'recentchanges-page-removed-from-category',
		];

		$msgKey = $array[$type];

		if ( intval( $numTemplateLinks ) > 0 ) {
			$msgKey .= '-bundled';
		}

		return wfMessage( $msgKey, $params )->inContentLanguage()->text();
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
