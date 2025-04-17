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
 */

namespace MediaWiki\Mail;

use MailAddress;
use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Language\MessageParser;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\Utils\UrlUtils;
use StatusValue;

/**
 * Component responsible for composing and sending emails triggered after a RecentChange.
 * This includes watchlist notifications, user_talk notifications.
 *
 * @internal
 * @unstable
 * @since 1.44
 */
class RecentChangeMailComposer {

	/**
	 * Notification is due to user's user talk being edited
	 */
	public const USER_TALK = 'user_talk';
	/**
	 * Notification is due to a watchlisted page being edited
	 */
	public const WATCHLIST = 'watchlist';
	/**
	 * Notification because user is notified for all changes
	 */
	public const ALL_CHANGES = 'all_changes';

	protected string $subject = '';

	protected string $body = '';

	protected string $summary = '';

	protected ?MailAddress $replyto;

	protected ?MailAddress $from;

	protected bool $composed_common = false;

	/** @var MailAddress[] */
	protected array $mailTargets = [];

	protected ?bool $minorEdit;

	/** @var int|null|bool */
	protected $oldid;

	protected string $timestamp;

	protected string $pageStatus = '';

	protected Title $title;

	protected User $editor;

	private Config $mainConfig;
	private UserOptionsLookup $userOptionsLookup;
	private UrlUtils $urlUtils;
	private MessageParser $messageParser;
	private Language $contentLanguage;
	private Emailer $emailer;

	public function __construct(
		Authority $editor,
		Title $title,
		RecentChange $recentChange,
		string $pageStatus
	) {
		$services = MediaWikiServices::getInstance();
		$this->editor = $services->getUserFactory()->newFromAuthority( $editor );
		$this->title = $title;
		$this->oldid = $recentChange->getAttribute( 'rc_last_oldid' );
		$this->minorEdit = $recentChange->getAttribute( 'rc_minor' );
		$this->timestamp = $recentChange->getAttribute( 'rc_timestamp' );
		$this->summary = $recentChange->getAttribute( 'rc_comment' );
		$this->pageStatus = $pageStatus;

		// Prepare for dependency injection
		$this->mainConfig = $services->getMainConfig();
		$this->userOptionsLookup = $services->getUserOptionsLookup();
		$this->urlUtils = $services->getUrlUtils();
		$this->messageParser = $services->getMessageParser();
		$this->contentLanguage = $services->getContentLanguage();
		$this->emailer = $services->getEmailer();
	}

	/**
	 * Generate the generic "this page has been changed" e-mail text.
	 */
	private function composeCommonMailtext() {
		$this->composed_common = true;

		# You as the WikiAdmin and Sysops can make use of plenty of
		# named variables when composing your notification emails while
		# simply editing the Meta pages

		$keys = [];
		$postTransformKeys = [];
		$pageTitleUrl = $this->title->getCanonicalURL();
		$pageTitle = $this->title->getPrefixedText();

		if ( $this->oldid ) {
			// Always show a link to the diff which triggered the mail. See T34210.
			$keys['$NEWPAGE'] = "\n\n" . wfMessage(
					'enotif_lastdiff',
					$this->title->getCanonicalURL( [ 'diff' => 'next', 'oldid' => $this->oldid ] )
				)->inContentLanguage()->text();

			if ( !$this->mainConfig->get( MainConfigNames::EnotifImpersonal ) ) {
				// For personal mail, also show a link to the diff of all changes
				// since last visited.
				$keys['$NEWPAGE'] .= "\n\n" . wfMessage(
						'enotif_lastvisited',
						$this->title->getCanonicalURL( [ 'diff' => '0', 'oldid' => $this->oldid ] )
					)->inContentLanguage()->text();
			}
			$keys['$OLDID'] = $this->oldid;
			$keys['$PAGELOG'] = '';
		} else {
			// If there is no revision to link to, link to the page log, which should have details. See T115183.
			$keys['$OLDID'] = '';
			$keys['$NEWPAGE'] = '';
			$keys['$PAGELOG'] = "\n\n" . wfMessage(
					'enotif_pagelog',
					SpecialPage::getTitleFor( 'Log' )->getCanonicalURL( [ 'page' =>
						$this->title->getPrefixedDBkey() ] )
				)->inContentLanguage()->text();
		}

		$keys['$PAGETITLE'] = $this->title->getPrefixedText();
		$keys['$PAGETITLE_URL'] = $this->title->getCanonicalURL();
		$keys['$PAGEMINOREDIT'] = $this->minorEdit ?
			"\n\n" . wfMessage( 'enotif_minoredit' )->inContentLanguage()->text() :
			'';
		$keys['$UNWATCHURL'] = $this->title->getCanonicalURL( 'action=unwatch' );

		if ( $this->editor->isAnon() ) {
			# real anon (user:xxx.xxx.xxx.xxx)
			$keys['$PAGEEDITOR'] = wfMessage( 'enotif_anon_editor', $this->editor->getName() )
				->inContentLanguage()->text();
			$keys['$PAGEEDITOR_EMAIL'] = wfMessage( 'noemailtitle' )->inContentLanguage()->text();
		} elseif ( $this->editor->isTemp() ) {
			$keys['$PAGEEDITOR'] = wfMessage( 'enotif_temp_editor', $this->editor->getName() )
				->inContentLanguage()->text();
			$keys['$PAGEEDITOR_EMAIL'] = wfMessage( 'noemailtitle' )->inContentLanguage()->text();
		} else {
			$keys['$PAGEEDITOR'] = $this->mainConfig->get( MainConfigNames::EnotifUseRealName ) &&
			$this->editor->getRealName() !== ''
				? $this->editor->getRealName() : $this->editor->getName();
			$emailPage = SpecialPage::getSafeTitleFor( 'Emailuser', $this->editor->getName() );
			$keys['$PAGEEDITOR_EMAIL'] = $emailPage->getCanonicalURL();
		}

		$keys['$PAGEEDITOR_WIKI'] = $this->editor->getTalkPage()->getCanonicalURL();
		$keys['$HELPPAGE'] = $this->urlUtils->expand(
			Skin::makeInternalOrExternalUrl( wfMessage( 'helppage' )->inContentLanguage()->text() ),
			PROTO_CURRENT
		) ?? false;

		# Replace this after transforming the message, T37019
		$postTransformKeys['$PAGESUMMARY'] = $this->summary == '' ? ' - ' : $this->summary;

		// Now build message's subject and body

		// Messages:
		// enotif_subject_deleted, enotif_subject_created, enotif_subject_moved,
		// enotif_subject_restored, enotif_subject_changed
		$this->subject = wfMessage( 'enotif_subject_' . $this->pageStatus )->inContentLanguage()
			->params( $pageTitle, $keys['$PAGEEDITOR'] )->text();

		// Messages:
		// enotif_body_intro_deleted, enotif_body_intro_created, enotif_body_intro_moved,
		// enotif_body_intro_restored, enotif_body_intro_changed
		$keys['$PAGEINTRO'] = wfMessage( 'enotif_body_intro_' . $this->pageStatus )
			->inContentLanguage()
			->params( $pageTitle, $keys['$PAGEEDITOR'], "<{$pageTitleUrl}>" )
			->text();

		$body = wfMessage( 'enotif_body' )->inContentLanguage()->plain();
		$body = strtr( $body, $keys );
		$body = $this->messageParser->transform( $body, false, null, $this->title );
		$this->body = wordwrap( strtr( $body, $postTransformKeys ), 72 );

		# Reveal the page editor's address as REPLY-TO address only if
		# the user has not opted-out and the option is enabled at the
		# global configuration level.
		$adminAddress = new MailAddress(
			$this->mainConfig->get( MainConfigNames::PasswordSender ),
			wfMessage( 'emailsender' )->inContentLanguage()->text()
		);
		if ( $this->mainConfig->get( MainConfigNames::EnotifRevealEditorAddress )
			&& ( $this->editor->getEmail() != '' )
			&& $this->userOptionsLookup->getOption( $this->editor, 'enotifrevealaddr' )
		) {
			$editorAddress = MailAddress::newFromUser( $this->editor );
			if ( $this->mainConfig->get( MainConfigNames::EnotifFromEditor ) ) {
				$this->from = $editorAddress;
			} else {
				$this->from = $adminAddress;
				$this->replyto = $editorAddress;
			}
		} else {
			$this->from = $adminAddress;
			$this->replyto = new MailAddress(
				$this->mainConfig->get( MainConfigNames::NoReplyAddress )
			);
		}
	}

	/**
	 * Compose a mail to a given user and either queue it for sending, or send it now,
	 * depending on settings.
	 *
	 * Call sendMails() to send any mails that were queued.
	 * @param UserEmailContact $user
	 * @param string $source
	 */
	public function compose( UserEmailContact $user, $source ) {
		if ( !$this->composed_common ) {
			$this->composeCommonMailtext();
		}

		if ( $this->mainConfig->get( MainConfigNames::EnotifImpersonal ) ) {
			wfDeprecated( 'EnotifImpersonal is now deprecated', '1.44' );

			$this->mailTargets[] = MailAddress::newFromUser( $user );
		} else {
			$this->sendPersonalised( $user, $source );
		}
	}

	/**
	 * Send any queued mails
	 */
	public function sendMails() {
		if ( $this->mainConfig->get( MainConfigNames::EnotifImpersonal ) ) {
			wfDeprecated( 'EnotifImpersonal is now deprecated', '1.44' );
			$this->sendImpersonal( $this->mailTargets );
		}
	}

	/**
	 * Does the per-user customizations to a notification e-mail (name,
	 * timestamp in proper timezone, etc) and sends it out.
	 * Returns Status if email was sent successfully or not (Status::newGood()
	 * or Status::newFatal() respectively).
	 *
	 * @param UserEmailContact $watchingUser
	 * @param string $source
	 * @return StatusValue
	 */
	private function sendPersonalised( UserEmailContact $watchingUser, $source ): StatusValue {
		// From the PHP manual:
		//   Note: The to parameter cannot be an address in the form of
		//   "Something <someone@example.com>". The mail command will not parse
		//   this properly while talking with the MTA.
		$to = MailAddress::newFromUser( $watchingUser );

		# $PAGEEDITDATE is the time and date of the page change
		# expressed in terms of individual local time of the notification
		# recipient, i.e. watching user
		$watchingUserName = (
			$this->mainConfig->get( MainConfigNames::EnotifUseRealName ) &&
			$watchingUser->getRealName() !== ''
		) ? $watchingUser->getRealName() : $watchingUser->getUser()->getName();
		$body = str_replace(
			[
				'$WATCHINGUSERNAME',
				'$PAGEEDITDATE',
				'$PAGEEDITTIME'
			],
			[
				$watchingUserName,
				$this->contentLanguage->userDate( $this->timestamp, $watchingUser->getUser() ),
				$this->contentLanguage->userTime( $this->timestamp, $watchingUser->getUser() )
			],
			$this->body
		);

		$headers = [];
		if ( $source === self::WATCHLIST ) {
			$headers['List-Help'] = 'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:Watchlist';
		}

		return $this->emailer->send(
				[ $to ],
				$this->from,
				$this->subject,
				$body,
				null,
				[
					'replyTo' => $this->replyto,
					'headers' => $headers,
				]
			);
	}

	/**
	 * Same as sendPersonalised but does impersonal mail suitable for bulk
	 * mailing.  Takes an array of MailAddress objects.
	 * @param MailAddress[] $addresses
	 * @return ?StatusValue
	 */
	private function sendImpersonal( array $addresses ): ?StatusValue {
		if ( count( $addresses ) === 0 ) {
			return null;
		}
		$services = MediaWikiServices::getInstance();
		$contLang = $services->getContentLanguage();
		$body = str_replace(
			[
				'$WATCHINGUSERNAME',
				'$PAGEEDITDATE',
				'$PAGEEDITTIME'
			],
			[
				wfMessage( 'enotif_impersonal_salutation' )->inContentLanguage()->text(),
				$contLang->date( $this->timestamp, false, false ),
				$contLang->time( $this->timestamp, false, false )
			],
			$this->body
		);

		return $services
			->getEmailer()
			->send(
				$addresses,
				$this->from,
				$this->subject,
				$body,
				null,
				[
					'replyTo' => $this->replyto,
				]
			);
	}
}
