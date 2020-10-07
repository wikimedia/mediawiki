<?php
/**
 * File deletion user interface.
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
 * @author Rob Church <robchur@gmail.com>
 * @ingroup Media
 */
use MediaWiki\MediaWikiServices;

/**
 * File deletion user interface
 *
 * @ingroup Media
 */
class FileDeleteForm {

	/**
	 * @var Title
	 */
	private $title = null;

	/**
	 * @var LocalFile
	 */
	private $file = null;

	/**
	 * @var User
	 */
	private $user = null;

	/**
	 * @var LocalFile
	 */
	private $oldfile = null;

	/**
	 * @var string
	 */
	private $oldimage = '';

	/**
	 * Option to pass a user added in 1.35
	 * Constructing without passing a user is hard deprecated in 1.35
	 *
	 * @param LocalFile $file File object we're deleting
	 * @param User|null $user
	 */
	public function __construct( $file, $user = null ) {
		$this->title = $file->getTitle();
		$this->file = $file;

		if ( $user === null ) {
			wfDeprecatedMsg(
				'Construction of ' . __CLASS__ . ' without a $user parameter ' .
				'was deprecated in MediaWiki 1.35',
				'1.35'
			);
			global $wgUser;
			$user = $wgUser;
		}
		$this->user = $user;
	}

	/**
	 * Fulfil the request; shows the form or deletes the file,
	 * pending authentication, confirmation, etc.
	 */
	public function execute() {
		global $wgOut, $wgRequest, $wgUploadMaintenance;

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		$permissionErrors = $permissionManager->getPermissionErrors(
			'delete',
			$this->user,
			$this->title
		);
		if ( count( $permissionErrors ) ) {
			throw new PermissionsError( 'delete', $permissionErrors );
		}

		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		if ( $wgUploadMaintenance ) {
			throw new ErrorPageError( 'filedelete-maintenance-title', 'filedelete-maintenance' );
		}

		$this->setHeaders();

		$this->oldimage = $wgRequest->getText( 'oldimage', '' );
		$token = $wgRequest->getText( 'wpEditToken' );
		# Flag to hide all contents of the archived revisions
		$suppress = $wgRequest->getCheck( 'wpSuppress' ) &&
			$permissionManager->userHasRight( $this->user, 'suppressrevision' );

		if ( $this->oldimage ) {
			$repoGroup = MediaWikiServices::getInstance()->getRepoGroup();
			$this->oldfile = $repoGroup->getLocalRepo()->newFromArchiveName(
				$this->title,
				$this->oldimage
			);
		}

		if ( !self::haveDeletableFile( $this->file, $this->oldfile, $this->oldimage ) ) {
			$wgOut->addHTML( $this->prepareMessage( 'filedelete-nofile' ) );
			$wgOut->addReturnTo( $this->title );
			return;
		}

		// Perform the deletion if appropriate
		if ( $wgRequest->wasPosted() && $this->user->matchEditToken( $token, $this->oldimage ) ) {
			$deleteReasonList = $wgRequest->getText( 'wpDeleteReasonList' );
			$deleteReason = $wgRequest->getText( 'wpReason' );

			if ( $deleteReasonList == 'other' ) {
				$reason = $deleteReason;
			} elseif ( $deleteReason != '' ) {
				// Entry from drop down menu + additional comment
				$reason = $deleteReasonList . wfMessage( 'colon-separator' )
					->inContentLanguage()->text() . $deleteReason;
			} else {
				$reason = $deleteReasonList;
			}

			$status = self::doDelete(
				$this->title,
				$this->file,
				$this->oldimage,
				$reason,
				$suppress,
				$this->user
			);

			if ( !$status->isGood() ) {
				$wgOut->addHTML( '<h2>' . $this->prepareMessage( 'filedeleteerror-short' ) . "</h2>\n" );
				$wgOut->wrapWikiTextAsInterface(
					'error',
					$status->getWikiText( 'filedeleteerror-short', 'filedeleteerror-long' )
				);
			}
			if ( $status->isOK() ) {
				$wgOut->setPageTitle( wfMessage( 'actioncomplete' ) );
				$wgOut->addHTML( $this->prepareMessage( 'filedelete-success' ) );
				// Return to the main page if we just deleted all versions of the
				// file, otherwise go back to the description page
				$wgOut->addReturnTo( $this->oldimage ? $this->title : Title::newMainPage() );

				WatchAction::doWatchOrUnwatch(
					$wgRequest->getCheck( 'wpWatch' ),
					$this->title,
					$this->user
				);
			}
			return;
		}

		$this->showForm();
		$this->showLogEntries();
	}

	/**
	 * Really delete the file
	 *
	 * @param Title &$title
	 * @param LocalFile &$file
	 * @param ?string &$oldimage Archive name
	 * @param string $reason Reason of the deletion
	 * @param bool $suppress Whether to mark all deleted versions as restricted
	 * @param User|null $user User object performing the request (null defaults to $wgUser
	 *        and is deprecated as of 1.35)
	 * @param array $tags Tags to apply to the deletion action
	 * @throws MWException
	 * @return Status
	 */
	public static function doDelete( &$title, &$file, &$oldimage, $reason,
		$suppress, User $user = null, $tags = []
	) {
		if ( $user === null ) {
			wfDeprecated( __METHOD__ . ' without passing a $user parameter', '1.35' );
			global $wgUser;
			$user = $wgUser;
		}

		if ( $oldimage ) {
			$page = null;
			$status = $file->deleteOldFile( $oldimage, $reason, $user, $suppress );
			if ( $status->isOK() ) {
				// Need to do a log item
				$logComment = wfMessage( 'deletedrevision', $oldimage )->inContentLanguage()->text();
				if ( trim( $reason ) != '' ) {
					$logComment .= wfMessage( 'colon-separator' )
						->inContentLanguage()->text() . $reason;
				}

				$logtype = $suppress ? 'suppress' : 'delete';

				$logEntry = new ManualLogEntry( $logtype, 'delete' );
				$logEntry->setPerformer( $user );
				$logEntry->setTarget( $title );
				$logEntry->setComment( $logComment );
				$logEntry->addTags( $tags );
				$logid = $logEntry->insert();
				$logEntry->publish( $logid );

				$status->value = $logid;
			}
		} else {
			$status = Status::newFatal( 'cannotdelete',
				wfEscapeWikiText( $title->getPrefixedText() )
			);
			$page = WikiPage::factory( $title );
			$dbw = wfGetDB( DB_MASTER );
			$dbw->startAtomic( __METHOD__ );
			// delete the associated article first
			$error = '';
			$deleteStatus = $page->doDeleteArticleReal(
				$reason,
				$user,
				$suppress,
				null,
				$error,
				null,
				$tags
			);
			// doDeleteArticleReal() returns a non-fatal error status if the page
			// or revision is missing, so check for isOK() rather than isGood()
			if ( $deleteStatus->isOK() ) {
				$status = $file->deleteFile( $reason, $user, $suppress );
				if ( $status->isOK() ) {
					if ( $deleteStatus->value === null ) {
						// No log ID from doDeleteArticleReal(), probably
						// because the page/revision didn't exist, so create
						// one here.
						$logtype = $suppress ? 'suppress' : 'delete';
						$logEntry = new ManualLogEntry( $logtype, 'delete' );
						$logEntry->setPerformer( $user );
						$logEntry->setTarget( clone $title );
						$logEntry->setComment( $reason );
						$logEntry->addTags( $tags );
						$logid = $logEntry->insert();
						$dbw->onTransactionPreCommitOrIdle(
							function () use ( $logEntry, $logid ) {
								$logEntry->publish( $logid );
							},
							__METHOD__
						);
						$status->value = $logid;
					} else {
						$status->value = $deleteStatus->value; // log id
					}
					$dbw->endAtomic( __METHOD__ );
				} else {
					// Page deleted but file still there? rollback page delete
					$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
					$lbFactory->rollbackMasterChanges( __METHOD__ );
				}
			} else {
				$dbw->endAtomic( __METHOD__ );
			}
		}

		if ( $status->isOK() ) {
			Hooks::runner()->onFileDeleteComplete( $file, $oldimage, $page, $user, $reason );
		}

		return $status;
	}

	/**
	 * Show the confirmation form
	 */
	private function showForm() {
		global $wgOut, $wgRequest;
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$wgOut->addModules( 'mediawiki.action.delete.file' );

		$checkWatch = $this->user->getBoolOption( 'watchdeletion' ) ||
			$this->user->isWatched( $this->title );

		$wgOut->enableOOUI();

		$fields = [];

		$fields[] = new OOUI\LabelWidget( [ 'label' => new OOUI\HtmlSnippet(
			$this->prepareMessage( 'filedelete-intro' ) ) ]
		);

		$suppressAllowed = $permissionManager->userHasRight( $this->user, 'suppressrevision' );
		$dropDownReason = $wgOut->msg( 'filedelete-reason-dropdown' )->inContentLanguage()->text();
		// Add additional specific reasons for suppress
		if ( $suppressAllowed ) {
			$dropDownReason .= "\n" . $wgOut->msg( 'filedelete-reason-dropdown-suppress' )
				->inContentLanguage()->text();
		}

		$options = Xml::listDropDownOptions(
			$dropDownReason,
			[ 'other' => $wgOut->msg( 'filedelete-reason-otherlist' )->inContentLanguage()->text() ]
		);
		$options = Xml::listDropDownOptionsOoui( $options );

		$fields[] = new OOUI\FieldLayout(
			new OOUI\DropdownInputWidget( [
				'name' => 'wpDeleteReasonList',
				'inputId' => 'wpDeleteReasonList',
				'tabIndex' => 1,
				'infusable' => true,
				'value' => '',
				'options' => $options,
			] ),
			[
				'label' => $wgOut->msg( 'filedelete-comment' )->text(),
				'align' => 'top',
			]
		);

		// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
		// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
		// Unicode codepoints.
		$fields[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'name' => 'wpReason',
				'inputId' => 'wpReason',
				'tabIndex' => 2,
				'maxLength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				'infusable' => true,
				'value' => $wgRequest->getText( 'wpReason' ),
				'autofocus' => true,
			] ),
			[
				'label' => $wgOut->msg( 'filedelete-otherreason' )->text(),
				'align' => 'top',
			]
		);

		if ( $suppressAllowed ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpSuppress',
					'inputId' => 'wpSuppress',
					'tabIndex' => 3,
					'selected' => false,
				] ),
				[
					'label' => $wgOut->msg( 'revdelete-suppress' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}

		if ( $this->user->isLoggedIn() ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpWatch',
					'inputId' => 'wpWatch',
					'tabIndex' => 3,
					'selected' => $checkWatch,
				] ),
				[
					'label' => $wgOut->msg( 'watchthis' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}

		$fields[] = new OOUI\FieldLayout(
			new OOUI\ButtonInputWidget( [
				'name' => 'mw-filedelete-submit',
				'inputId' => 'mw-filedelete-submit',
				'tabIndex' => 4,
				'value' => $wgOut->msg( 'filedelete-submit' )->text(),
				'label' => $wgOut->msg( 'filedelete-submit' )->text(),
				'flags' => [ 'primary', 'destructive' ],
				'type' => 'submit',
			] ),
			[
				'align' => 'top',
			]
		);

		$fieldset = new OOUI\FieldsetLayout( [
			'label' => $wgOut->msg( 'filedelete-legend' )->text(),
			'items' => $fields,
		] );

		$form = new OOUI\FormLayout( [
			'method' => 'post',
			'action' => $this->getAction(),
			'id' => 'mw-img-deleteconfirm',
		] );
		$form->appendContent(
			$fieldset,
			new OOUI\HtmlSnippet(
				Html::hidden( 'wpEditToken', $this->user->getEditToken( $this->oldimage ) )
			)
		);

		$wgOut->addHTML(
			new OOUI\PanelLayout( [
				'classes' => [ 'deletepage-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
		);

		if ( $permissionManager->userHasRight( $this->user, 'editinterface' ) ) {
			$link = '';
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
			if ( $suppressAllowed ) {
				$link .= $linkRenderer->makeKnownLink(
					$wgOut->msg( 'filedelete-reason-dropdown-suppress' )->inContentLanguage()->getTitle(),
					$wgOut->msg( 'filedelete-edit-reasonlist-suppress' )->text(),
					[],
					[ 'action' => 'edit' ]
				);
				$link .= $wgOut->msg( 'pipe-separator' )->escaped();
			}
			$link .= $linkRenderer->makeKnownLink(
				$wgOut->msg( 'filedelete-reason-dropdown' )->inContentLanguage()->getTitle(),
				$wgOut->msg( 'filedelete-edit-reasonlist' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$wgOut->addHTML( '<p class="mw-filedelete-editreasons">' . $link . '</p>' );
		}
	}

	/**
	 * Show deletion log fragments pertaining to the current file
	 */
	private function showLogEntries() {
		global $wgOut;
		$deleteLogPage = new LogPage( 'delete' );
		$wgOut->addHTML( '<h2>' . $deleteLogPage->getName()->escaped() . "</h2>\n" );
		LogEventsList::showLogExtract( $wgOut, 'delete', $this->title );
	}

	/**
	 * Prepare a message referring to the file being deleted,
	 * showing an appropriate message depending upon whether
	 * it's a current file or an old version
	 *
	 * @param string $message Message base
	 * @return string
	 */
	private function prepareMessage( $message ) {
		global $wgLang;
		if ( $this->oldimage ) {
			# Message keys used:
			# 'filedelete-intro-old', 'filedelete-nofile-old', 'filedelete-success-old'
			return wfMessage(
				"{$message}-old",
				wfEscapeWikiText( $this->title->getText() ),
				$wgLang->date( $this->getTimestamp(), true ),
				$wgLang->time( $this->getTimestamp(), true ),
				wfExpandUrl( $this->file->getArchiveUrl( $this->oldimage ), PROTO_CURRENT ) )->parseAsBlock();
		} else {
			return wfMessage(
				$message,
				wfEscapeWikiText( $this->title->getText() )
			)->parseAsBlock();
		}
	}

	/**
	 * Set headers, titles and other bits
	 */
	private function setHeaders() {
		global $wgOut;
		$wgOut->setPageTitle( wfMessage( 'filedelete', $this->title->getText() ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->addBacklinkSubtitle( $this->title );
	}

	/**
	 * Is the provided `oldimage` value valid?
	 *
	 * @param string $oldimage
	 * @return bool
	 */
	public static function isValidOldSpec( $oldimage ) {
		return strlen( $oldimage ) >= 16
			&& strpos( $oldimage, '/' ) === false
			&& strpos( $oldimage, '\\' ) === false;
	}

	/**
	 * Could we delete the file specified? If an `oldimage`
	 * value was provided, does it correspond to an
	 * existing, local, old version of this file?
	 *
	 * @param LocalFile &$file
	 * @param LocalFile &$oldfile
	 * @param string $oldimage
	 * @return bool
	 */
	public static function haveDeletableFile( &$file, &$oldfile, $oldimage ) {
		return $oldimage
			? $oldfile && $oldfile->exists() && $oldfile->isLocal()
			: $file && $file->exists() && $file->isLocal();
	}

	/**
	 * Prepare the form action
	 *
	 * @return string
	 */
	private function getAction() {
		$q = [];
		$q['action'] = 'delete';

		if ( $this->oldimage ) {
			$q['oldimage'] = $this->oldimage;
		}

		return $this->title->getLocalURL( $q );
	}

	/**
	 * Extract the timestamp of the old version
	 *
	 * @return string
	 */
	private function getTimestamp() {
		return $this->oldfile->getTimestamp();
	}
}
