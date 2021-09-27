<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

use CommentStore;
use DeleteAction;
use ErrorPageError;
use File;
use FileDeleteForm;
use Html;
use IContextSource;
use LocalFile;
use LogEventsList;
use LogPage;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\User\UserOptionsLookup;
use OldLocalFile;
use Page;
use PermissionsError;
use ReadOnlyError;
use ReadOnlyMode;
use Title;
use Xml;

/**
 * Handle file deletion
 *
 * @ingroup Actions
 */
class FileDeleteAction extends DeleteAction {
	/** @var File */
	private $file;
	/** @var Title */
	private $title;
	/** @var ReadOnlyMode */
	private $readOnlyMode;
	/** @var UserOptionsLookup */
	private $userOptionsLookup;
	/** @var string Descriptor for the old version of the image, if applicable */
	private $oldImage;
	/** @var OldLocalFile|null Corresponding to oldImage, if applicable */
	private $oldFile;

	/**
	 * @inheritDoc
	 */
	public function __construct( Page $page, IContextSource $context = null ) {
		parent::__construct( $page, $context );
		$services = MediaWikiServices::getInstance();
		$this->readOnlyMode = $services->getReadOnlyMode();
		$this->file = $this->getArticle()->getFile();
		$this->title = $this->file->getTitle();
		$this->userOptionsLookup = $services->getUserOptionsLookup();
		$this->oldImage = $this->getRequest()->getText( 'oldimage', '' );
		if ( $this->oldImage !== '' ) {
			$this->oldFile = $services->getRepoGroup()->getLocalRepo()->newFromArchiveName(
				$this->title,
				$this->oldImage
			);
		}
	}

	protected function tempDelete() {
		if ( !$this->file->exists() || !$this->file->isLocal() || $this->file->getRedirected() ) {
			// Standard article deletion
			parent::tempDelete();
			return;
		}

		$file = $this->file;
		/** @var LocalFile $file */'@phan-var LocalFile $file';
		$this->tempExecute( $file );
	}

	private function tempExecute( LocalFile $file ): void {
		if ( $this->readOnlyMode->isReadOnly() ) {
			throw new ReadOnlyError;
		}

		if ( $this->context->getConfig()->get( 'UploadMaintenance' ) ) {
			throw new ErrorPageError( 'filedelete-maintenance-title', 'filedelete-maintenance' );
		}

		$this->context->getOutput()->setPageTitle( $this->context->msg( 'filedelete', $this->title->getText() ) );
		$this->context->getOutput()->setRobotPolicy( 'noindex,nofollow' );
		$this->context->getOutput()->addBacklinkSubtitle( $this->title );

		$request = $this->context->getRequest();
		$token = $request->getText( 'wpEditToken' );
		# Flag to hide all contents of the archived revisions
		$suppress = $request->getCheck( 'wpSuppress' ) &&
			$this->context->getAuthority()->isAllowed( 'suppressrevision' );

		if ( !FileDeleteForm::haveDeletableFile( $file, $this->oldFile, $this->oldImage ) ) {
			$this->context->getOutput()->addHTML( $this->prepareMessage( 'filedelete-nofile' ) );
			$this->context->getOutput()->addReturnTo( $this->title );
			return;
		}

		// Perform the deletion if appropriate
		if ( $request->wasPosted() && $this->context->getUser()->matchEditToken( $token, $this->oldImage ) ) {
			$permissionStatus = PermissionStatus::newEmpty();
			if ( !$this->context->getAuthority()->authorizeWrite(
				'delete', $this->title, $permissionStatus
			) ) {
				throw new PermissionsError( 'delete', $permissionStatus );
			}

			$deleteReasonList = $request->getText( 'wpDeleteReasonList' );
			$deleteReason = $request->getText( 'wpReason' );

			if ( $deleteReasonList == 'other' ) {
				$reason = $deleteReason;
			} elseif ( $deleteReason != '' ) {
				// Entry from drop down menu + additional comment
				$reason = $deleteReasonList . $this->context->msg( 'colon-separator' )
						->inContentLanguage()->text() . $deleteReason;
			} else {
				$reason = $deleteReasonList;
			}

			$status = FileDeleteForm::doDelete(
				$this->title,
				$file,
				$this->oldImage,
				$reason,
				$suppress,
				$this->context->getUser()
			);

			$out = $this->context->getOutput();

			if ( !$status->isGood() ) {
				$out->addHTML(
					'<h2>' . $this->prepareMessage( 'filedeleteerror-short' ) . "</h2>\n"
				);
				$out->wrapWikiTextAsInterface(
					'error',
					$status->getWikiText( 'filedeleteerror-short', 'filedeleteerror-long' )
				);
			}
			if ( $status->isOK() ) {
				$out->setPageTitle( $this->context->msg( 'actioncomplete' ) );
				$out->addHTML( $this->prepareMessage( 'filedelete-success' ) );
				// Return to the main page if we just deleted all versions of the
				// file, otherwise go back to the description page
				$out->addReturnTo( $this->oldImage ? $this->title : Title::newMainPage() );

				$this->watchlistManager->setWatch(
					$request->getCheck( 'wpWatch' ),
					$this->context->getAuthority(),
					$this->title
				);
			}
			return;
		}

		$this->showForm();
		$this->showLogEntries();
	}

	/**
	 * Show the confirmation form
	 */
	private function showForm() {
		$permissionStatus = PermissionStatus::newEmpty();
		if ( !$this->context->getAuthority()->definitelyCan(
			'delete', $this->title, $permissionStatus
		) ) {
			throw new PermissionsError( 'delete', $permissionStatus );
		}

		$this->context->getOutput()->addModules( 'mediawiki.action.delete' );
		$this->context->getOutput()->addModuleStyles( 'mediawiki.action.styles' );

		$checkWatch =
			$this->userOptionsLookup->getBoolOption( $this->context->getUser(), 'watchdeletion' ) ||
			$this->watchlistManager->isWatched( $this->context->getUser(), $this->title );

		$this->context->getOutput()->enableOOUI();

		$fields = [];

		$fields[] = new \OOUI\LabelWidget( [ 'label' => new \OOUI\HtmlSnippet(
				$this->prepareMessage( 'filedelete-intro' ) ) ]
		);

		$suppressAllowed = $this->context->getAuthority()->isAllowed( 'suppressrevision' );
		$dropDownReason = $this->context->msg( 'filedelete-reason-dropdown' )->inContentLanguage()->text();
		// Add additional specific reasons for suppress
		if ( $suppressAllowed ) {
			$dropDownReason .= "\n" . $this->context->msg( 'filedelete-reason-dropdown-suppress' )
					->inContentLanguage()->text();
		}

		$options = Xml::listDropDownOptions(
			$dropDownReason,
			[ 'other' => $this->context->msg( 'filedelete-reason-otherlist' )->inContentLanguage()->text() ]
		);
		$options = Xml::listDropDownOptionsOoui( $options );

		$fields[] = new \OOUI\FieldLayout(
			new \OOUI\DropdownInputWidget( [
				'name' => 'wpDeleteReasonList',
				'inputId' => 'wpDeleteReasonList',
				'tabIndex' => 1,
				'infusable' => true,
				'value' => '',
				'options' => $options,
			] ),
			[
				'label' => $this->context->msg( 'filedelete-comment' )->text(),
				'align' => 'top',
			]
		);

		// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
		// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
		// Unicode codepoints.
		$fields[] = new \OOUI\FieldLayout(
			new \OOUI\TextInputWidget( [
				'name' => 'wpReason',
				'inputId' => 'wpReason',
				'tabIndex' => 2,
				'maxLength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				'infusable' => true,
				'value' => $this->context->getRequest()->getText( 'wpReason' ),
				'autofocus' => true,
			] ),
			[
				'label' => $this->context->msg( 'filedelete-otherreason' )->text(),
				'align' => 'top',
			]
		);

		if ( $suppressAllowed ) {
			$fields[] = new \OOUI\FieldLayout(
				new \OOUI\CheckboxInputWidget( [
					'name' => 'wpSuppress',
					'inputId' => 'wpSuppress',
					'tabIndex' => 3,
					'selected' => false,
				] ),
				[
					'label' => $this->context->msg( 'revdelete-suppress' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}

		if ( $this->context->getUser()->isRegistered() ) {
			$fields[] = new \OOUI\FieldLayout(
				new \OOUI\CheckboxInputWidget( [
					'name' => 'wpWatch',
					'inputId' => 'wpWatch',
					'tabIndex' => 3,
					'selected' => $checkWatch,
				] ),
				[
					'label' => $this->context->msg( 'watchthis' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}

		$fields[] = new \OOUI\FieldLayout(
			new \OOUI\ButtonInputWidget( [
				'name' => 'mw-filedelete-submit',
				'inputId' => 'mw-filedelete-submit',
				'tabIndex' => 4,
				'value' => $this->context->msg( 'filedelete-submit' )->text(),
				'label' => $this->context->msg( 'filedelete-submit' )->text(),
				'flags' => [ 'primary', 'destructive' ],
				'type' => 'submit',
			] ),
			[
				'align' => 'top',
			]
		);

		$fieldset = new \OOUI\FieldsetLayout( [
			'label' => $this->context->msg( 'filedelete-legend' )->text(),
			'items' => $fields,
		] );

		$form = new \OOUI\FormLayout( [
			'method' => 'post',
			'action' => $this->getFormAction(),
			'id' => 'mw-img-deleteconfirm',
		] );
		$form->appendContent(
			$fieldset,
			new \OOUI\HtmlSnippet(
				Html::hidden(
					'wpEditToken',
					$this->context->getUser()->getEditToken( $this->oldImage )
				)
			)
		);

		$this->context->getOutput()->addHTML(
			new \OOUI\PanelLayout( [
				'classes' => [ 'deletepage-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
		);

		if ( $this->context->getAuthority()->isAllowed( 'editinterface' ) ) {
			$link = '';
			if ( $suppressAllowed ) {
				$link .= $this->linkRenderer->makeKnownLink(
					$this->context->msg( 'filedelete-reason-dropdown-suppress' )->inContentLanguage()->getTitle(),
					$this->context->msg( 'filedelete-edit-reasonlist-suppress' )->text(),
					[],
					[ 'action' => 'edit' ]
				);
				$link .= $this->context->msg( 'pipe-separator' )->escaped();
			}
			$link .= $this->linkRenderer->makeKnownLink(
				$this->context->msg( 'filedelete-reason-dropdown' )->inContentLanguage()->getTitle(),
				$this->context->msg( 'filedelete-edit-reasonlist' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$this->context->getOutput()->addHTML( '<p class="mw-filedelete-editreasons">' . $link . '</p>' );
		}
	}

	/**
	 * Show deletion log fragments pertaining to the current file
	 */
	private function showLogEntries() {
		$deleteLogPage = new LogPage( 'delete' );
		$this->context->getOutput()->addHTML( '<h2>' . $deleteLogPage->getName()->escaped() . "</h2>\n" );

		$out = $this->context->getOutput();
		LogEventsList::showLogExtract( $out, 'delete', $this->title );
	}

	/**
	 * Prepare a message referring to the file being deleted,
	 * showing an appropriate message depending upon whether
	 * it's a current file or an old version
	 *
	 * @param string $message Message base
	 * @return string
	 */
	private function prepareMessage( string $message ) {
		if ( $this->oldFile ) {
			$lang = $this->context->getLanguage();
			# Message keys used:
			# 'filedelete-intro-old', 'filedelete-nofile-old', 'filedelete-success-old'
			return $this->context->msg(
				"{$message}-old",
				wfEscapeWikiText( $this->title->getText() ),
				$lang->date( $this->oldFile->getTimestamp(), true ),
				$lang->time( $this->oldFile->getTimestamp(), true ),
				wfExpandUrl( $this->file->getArchiveUrl( $this->oldImage ), PROTO_CURRENT )
			)->parseAsBlock();
		} else {
			return $this->context->msg(
				$message,
				wfEscapeWikiText( $this->title->getText() )
			)->parseAsBlock();
		}
	}

	/**
	 * Prepare the form action
	 *
	 * @return string
	 */
	private function getFormAction() {
		$q = [];
		$q['action'] = 'delete';

		if ( $this->oldImage ) {
			$q['oldimage'] = $this->oldImage;
		}

		return $this->title->getLocalURL( $q );
	}
}
