<?php
/**
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
 * @author Brooke Vibber
 * @author <mail@tgries.de>
 * @author Tim Starling
 * @author Luke Welling lwelling@wikimedia.org
 */

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Notification\RecipientSet;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserArray;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\RecentChangeNotification;

/**
 * Find watchers and create notifications after a page is changed.
 *
 * After an edit is published to RCFeed, RecentChange::save calls EmailNotification.
 * Here we query the `watchlist` table (via WatchedItemStore) to find who is watching
 * a given page, format the emails in question, and dispatch notifications to each of them
 * via the JobQueue.
 *
 * Visit the documentation pages under
 * https://www.mediawiki.org/wiki/Help:Watching_pages
 *
 * @todo Use UserOptionsLookup and other services, consider converting this to a service
 *
 * @since 1.11.0
 * @ingroup Mail
 */
class EmailNotification {

	protected string $pageStatus = '';

	/**
	 * Extensions that have hooks for
	 * UpdateUserMailerFormattedPageStatus (to provide additional
	 * pageStatus indicators) need a way to make sure that, when their
	 * hook is called in SendWatchlistemailNotification, they only
	 * handle notifications using their pageStatus indicator.
	 *
	 * @since 1.33
	 * @return string
	 */
	public function getPageStatus() {
		return $this->pageStatus;
	}

	/**
	 * Send emails corresponding to the user $editor editing the page $title.
	 *
	 * May be deferred via the job queue.
	 *
	 * @param RecentChange $recentChange
	 * @return bool Whether an email & notification job was created or not.
	 * @internal
	 */
	public function notifyOnPageChange(
		RecentChange $recentChange
	): bool {
		// Never send an RC notification email about categorization changes
		if ( $recentChange->getAttribute( 'rc_type' ) === RC_CATEGORIZE ) {
			return false;
		}
		$mwServices = MediaWikiServices::getInstance();
		$config = $mwServices->getMainConfig();

		$minorEdit = $recentChange->getAttribute( 'rc_minor' );
		$editor = $mwServices->getUserFactory()
			->newFromUserIdentity( $recentChange->getPerformerIdentity() );

		$title = Title::castFromPageReference( $recentChange->getPage() );
		if ( $title === null || $title->getNamespace() < 0 ) {
			return false;
		}

		// update wl_notificationtimestamp for watchers
		$watchers = [];
		if ( $config->get( MainConfigNames::EnotifWatchlist ) || $config->get( MainConfigNames::ShowUpdatedMarker ) ) {
			$watchers = $mwServices->getWatchedItemStore()->updateNotificationTimestamp(
				$editor,
				$title,
				$recentChange->getAttribute( 'rc_timestamp' )
			);
		}

		// Don't send email for bots
		if ( $editor->isBot() ) {
			return false;
		}

		$sendNotification = true;
		// $watchers deals with $wgEnotifWatchlist.
		// If nobody is watching the page, and there are no users notified on all changes
		// don't bother creating a job/trying to send emails, unless it's a
		// talk page with an applicable notification.
		if ( $watchers === [] &&
			!count( $config->get( MainConfigNames::UsersNotifiedOnAllChanges ) )
		) {
			$sendNotification = false;
			// Only send notification for non minor edits, unless $wgEnotifMinorEdits
			if ( !$minorEdit ||
				( $config->get( MainConfigNames::EnotifMinorEdits ) &&
					!$editor->isAllowed( 'nominornewtalk' ) )
			) {
				if ( $config->get( MainConfigNames::EnotifUserTalk )
					&& $title->getNamespace() === NS_USER_TALK
					&& $this->canSendUserTalkEmail( $editor, $title, $minorEdit )
				) {
					$sendNotification = true;
				}
			}
		}

		if ( $sendNotification ) {
			$mwServices->getJobQueueGroup()->lazyPush( new EnotifNotifyJob(
				$title,
				[
					'editor' => $editor->getName(),
					'editorID' => $editor->getId(),
					'watchers' => $watchers,
					'pageStatus' => $recentChange->mExtra['pageStatus'] ?? 'changed',
					'rc_id' => $recentChange->getAttribute( 'rc_id' ),
				]
			) );
		}

		return $sendNotification;
	}

	/**
	 * Immediate version of notifyOnPageChange().
	 *
	 * Send emails corresponding to the user $editor editing the page $title.
	 *
	 * @note Use notifyOnPageChange so that wl_notificationtimestamp is updated.
	 *
	 * @param Authority $editor
	 * @param Title $title
	 * @param RecentChange $recentChange
	 * @param array $watchers Array of user IDs
	 * @param string $pageStatus
	 * @internal
	 */
	public function actuallyNotifyOnPageChange(
		Authority $editor,
		$title,
		RecentChange $recentChange,
		array $watchers,
		$pageStatus = 'changed'
	) {
		# we use $wgPasswordSender as sender's address
		$mwServices = MediaWikiServices::getInstance();
		$config = $mwServices->getMainConfig();
		$notifService = $mwServices->getNotificationService();
		$userFactory = $mwServices->getUserFactory();
		$hookRunner = new HookRunner( $mwServices->getHookContainer() );

		$minorEdit = $recentChange->getAttribute( 'rc_minor' );
		# The following code is only run, if several conditions are met:
		# 1. EmailNotification for pages (other than user_talk pages) must be enabled
		# 2. minor edits (changes) are only regarded if the global flag indicates so
		$this->pageStatus = $pageStatus;
		$formattedPageStatus = [ 'deleted', 'created', 'moved', 'restored', 'changed' ];

		$hookRunner->onUpdateUserMailerFormattedPageStatus( $formattedPageStatus );
		if ( !in_array( $this->pageStatus, $formattedPageStatus ) ) {
			throw new UnexpectedValueException( 'Not a valid page status!' );
		}
		$agent = $mwServices->getUserFactory()->newFromAuthority( $editor );

		$userTalkId = false;
		if ( !$minorEdit ||
			( $config->get( MainConfigNames::EnotifMinorEdits ) &&
				!$editor->isAllowed( 'nominornewtalk' ) )
		) {
			if ( $config->get( MainConfigNames::EnotifUserTalk )
				&& $title->getNamespace() === NS_USER_TALK
				&& $this->canSendUserTalkEmail( $editor->getUser(), $title, $minorEdit )
			) {
				$targetUser = $userFactory->newFromName( $title->getText() );
				if ( $targetUser ) {
					$talkNotification = new RecentChangeNotification(
						$agent,
						$title,
						$recentChange,
						$pageStatus,
						RecentChangeNotification::TALK_NOTIFICATION
					);
					$notifService->notify( $talkNotification, new RecipientSet( [ $targetUser ] ) );
					$userTalkId = $targetUser->getId();
				}
			}

			if ( $config->get( MainConfigNames::EnotifWatchlist ) ) {
				$userOptionsLookup = $mwServices->getUserOptionsLookup();
				// Send updates to watchers other than the current editor
				// and don't send to watchers who are blocked and cannot login
				$userArray = UserArray::newFromIDs( $watchers );
				$recipients = new RecipientSet( [] );
				foreach ( $userArray as $watchingUser ) {
					if ( $userOptionsLookup->getOption( $watchingUser, 'enotifwatchlistpages' )
						&& ( !$minorEdit || $userOptionsLookup->getOption( $watchingUser, 'enotifminoredits' ) )
						&& $watchingUser->getId() != $userTalkId
						&& !in_array( $watchingUser->getName(),
							$config->get( MainConfigNames::UsersNotifiedOnAllChanges ) )
						// @TODO Partial blocks should not prevent the user from logging in.
						//       see: https://phabricator.wikimedia.org/T208895
						&& !( $config->get( MainConfigNames::BlockDisablesLogin ) &&
							$watchingUser->getBlock() )
						&& $hookRunner->onSendWatchlistEmailNotification( $watchingUser, $title, $this )
					) {
						$recipients->addRecipient( $watchingUser );
					}
				}
				if ( count( $recipients ) !== 0 ) {
					$talkNotification = new RecentChangeNotification(
						$agent,
						$title,
						$recentChange,
						$pageStatus,
						RecentChangeNotification::WATCHLIST_NOTIFICATION
					);
					$notifService->notify( $talkNotification, $recipients );
				}
			}
		}

		foreach ( $config->get( MainConfigNames::UsersNotifiedOnAllChanges ) as $name ) {
			$admins = [];
			if ( $editor->getUser()->getName() == $name ) {
				// No point notifying the user that actually made the change!
				continue;
			}
			$user = $userFactory->newFromName( $name );
			if ( $user instanceof User ) {
				$admins[] = $user;
			}
			$notifService->notify(
				new RecentChangeNotification(
					$agent,
					$title,
					$recentChange,
					$pageStatus,
					RecentChangeNotification::ADMIN_NOTIFICATION
				),
				new RecipientSet( $admins )
			);

		}
	}

	/**
	 * @param UserIdentity $editor
	 * @param Title $title
	 * @param bool $minorEdit
	 * @return bool
	 */
	private function canSendUserTalkEmail( UserIdentity $editor, $title, $minorEdit ) {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();

		if ( !$config->get( MainConfigNames::EnotifUserTalk ) || $title->getNamespace() !== NS_USER_TALK ) {
			return false;
		}

		$userOptionsLookup = $services->getUserOptionsLookup();
		$targetUser = User::newFromName( $title->getText() );

		if ( !$targetUser || $targetUser->isAnon() ) {
			wfDebug( __METHOD__ . ": user talk page edited, but user does not exist" );
		} elseif ( $targetUser->getId() == $editor->getId() ) {
			wfDebug( __METHOD__ . ": user edited their own talk page, no notification sent" );
		} elseif ( $targetUser->isTemp() ) {
			wfDebug( __METHOD__ . ": talk page owner is a temporary user so doesn't have email" );
		} elseif ( $config->get( MainConfigNames::BlockDisablesLogin ) &&
			$targetUser->getBlock()
		) {
			// @TODO Partial blocks should not prevent the user from logging in.
			//       see: https://phabricator.wikimedia.org/T208895
			wfDebug( __METHOD__ . ": talk page owner is blocked and cannot login, no notification sent" );
		} elseif ( $userOptionsLookup->getOption( $targetUser, 'enotifusertalkpages' )
			&& ( !$minorEdit || $userOptionsLookup->getOption( $targetUser, 'enotifminoredits' ) )
		) {
			if ( !$targetUser->isEmailConfirmed() ) {
				wfDebug( __METHOD__ . ": talk page owner doesn't have validated email" );
			} elseif ( !( new HookRunner( $services->getHookContainer() ) )
				->onAbortTalkPageEmailNotification( $targetUser, $title )
			) {
				wfDebug( __METHOD__ . ": talk page update notification is aborted for this user" );
			} else {
				wfDebug( __METHOD__ . ": sending talk page update notification" );
				return true;
			}
		} else {
			wfDebug( __METHOD__ . ": talk page owner doesn't want notifications" );
		}
		return false;
	}

}
