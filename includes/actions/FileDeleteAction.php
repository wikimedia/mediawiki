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
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\PermissionStatus;
use OldLocalFile;
use Page;
use PermissionsError;
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
	/**
	 * XXX Can we use $this->getTitle() instead?
	 * @var Title
	 */
	private $title;
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
		$this->file = $this->getArticle()->getFile();
		$this->title = $this->file->getTitle();
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
		$context = $this->getContext();

		$this->runExecuteChecks( $this->title );

		$outputPage = $context->getOutput();
		$this->prepareOutput( $context->msg( 'filedelete', $this->title->getText() ), $this->title );

		$request = $context->getRequest();

		$checkFile = $this->oldFile ?: $file;
		if ( !$checkFile->exists() || !$checkFile->isLocal() ) {
			$outputPage->addHTML( $this->prepareMessage( 'filedelete-nofile' ) );
			$outputPage->addReturnTo( $this->title );
			return;
		}

		// Perform the deletion if appropriate
		$token = $request->getVal( 'wpEditToken' );
		if (
			!$request->wasPosted() ||
			!$context->getUser()->matchEditToken( $token, [ 'delete', $this->title->getPrefixedText() ] )
		) {
			$this->showForm();
			return;
		}

		$permissionStatus = PermissionStatus::newEmpty();
		if ( !$context->getAuthority()->authorizeWrite(
			'delete', $this->title, $permissionStatus
		) ) {
			throw new PermissionsError( 'delete', $permissionStatus );
		}

		$reason = $this->getDeleteReason();

		# Flag to hide all contents of the archived revisions
		$suppress = $request->getCheck( 'wpSuppress' ) &&
			$context->getAuthority()->isAllowed( 'suppressrevision' );

		$status = FileDeleteForm::doDelete(
			$this->title,
			$file,
			$this->oldImage,
			$reason,
			$suppress,
			$context->getUser()
		);

		if ( !$status->isGood() ) {
			$outputPage->addHTML(
				'<h2>' . $this->prepareMessage( 'filedeleteerror-short' ) . "</h2>\n"
			);
			$outputPage->wrapWikiTextAsInterface(
				'error',
				$status->getWikiText( 'filedeleteerror-short', 'filedeleteerror-long' )
			);
		}
		if ( $status->isOK() ) {
			$outputPage->setPageTitle( $context->msg( 'actioncomplete' ) );
			$outputPage->addHTML( $this->prepareMessage( 'filedelete-success' ) );
			// Return to the main page if we just deleted all versions of the
			// file, otherwise go back to the description page
			$outputPage->addReturnTo( $this->oldImage ? $this->title : Title::newMainPage() );

			$this->watchlistManager->setWatch(
				$request->getCheck( 'wpWatch' ),
				$context->getAuthority(),
				$this->title
			);
		}
	}

	protected function showFormWarnings(): void {
		$this->getOutput()->addHTML( $this->prepareMessage( 'filedelete-intro' ) );
	}

	/**
	 * Show the confirmation form
	 */
	private function showForm() {
		$this->prepareOutputForForm();
		$ctx = $this->getContext();

		$outputPage = $ctx->getOutput();

		$this->showFormWarnings();

		$user = $ctx->getUser();
		$checkWatch = $this->userOptionsLookup->getBoolOption( $user, 'watchdeletion' ) ||
			$this->watchlistManager->isWatched( $user, $this->title );

		$fields = [];

		$suppressAllowed = $ctx->getAuthority()->isAllowed( 'suppressrevision' );
		$dropDownReason = $this->getFormMsg( self::MSG_REASON_DROPDOWN )->inContentLanguage()->text();
		// Add additional specific reasons for suppress
		if ( $suppressAllowed ) {
			$dropDownReason .= "\n" . $this->getFormMsg( self::MSG_REASON_DROPDOWN_SUPPRESS )
					->inContentLanguage()->text();
		}

		$options = Xml::listDropDownOptions(
			$dropDownReason,
			[ 'other' => $this->getFormMsg( self::MSG_REASON_DROPDOWN_OTHER )->inContentLanguage()->text() ]
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
				'label' => $this->getFormMsg( self::MSG_COMMENT )->text(),
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
				'value' => $this->getDefaultReason(),
				'autofocus' => true,
			] ),
			[
				'label' => $this->getFormMsg( self::MSG_REASON_OTHER )->text(),
				'align' => 'top',
			]
		);

		if ( $user->isRegistered() ) {
			$fields[] = new \OOUI\FieldLayout(
				new \OOUI\CheckboxInputWidget( [
					'name' => 'wpWatch',
					'inputId' => 'wpWatch',
					'tabIndex' => 3,
					'selected' => $checkWatch,
				] ),
				[
					'label' => $ctx->msg( 'watchthis' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}
		if ( $suppressAllowed ) {
			$fields[] = new \OOUI\FieldLayout(
				new \OOUI\CheckboxInputWidget( [
					'name' => 'wpSuppress',
					'inputId' => 'wpSuppress',
					'tabIndex' => 4,
					'selected' => false,
				] ),
				[
					'label' => $ctx->msg( 'revdelete-suppress' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}

		$fields[] = new \OOUI\FieldLayout(
			new \OOUI\ButtonInputWidget( [
				'name' => 'wpConfirmB',
				'inputId' => 'wpConfirmB',
				'tabIndex' => 5,
				'value' => $this->getFormMsg( self::MSG_SUBMIT )->text(),
				'label' => $this->getFormMsg( self::MSG_SUBMIT )->text(),
				'flags' => [ 'primary', 'destructive' ],
				'type' => 'submit',
			] ),
			[
				'align' => 'top',
			]
		);

		$fieldset = new \OOUI\FieldsetLayout( [
			'label' => $this->getFormMsg( self::MSG_LEGEND )->text(),
			'id' => 'mw-delete-table',
			'items' => $fields,
		] );

		$form = new \OOUI\FormLayout( [
			'method' => 'post',
			'action' => $this->getFormAction(),
			'id' => 'deleteconfirm',
		] );
		$form->appendContent(
			$fieldset,
			new \OOUI\HtmlSnippet(
				Html::hidden(
					'wpEditToken',
					$user->getEditToken( [ 'delete', $this->title->getPrefixedText() ] )
				)
			)
		);

		$outputPage->addHTML(
			new \OOUI\PanelLayout( [
				'classes' => [ 'deletepage-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
		);

		if ( $ctx->getAuthority()->isAllowed( 'editinterface' ) ) {
			$link = '';
			if ( $suppressAllowed ) {
				$link .= $this->linkRenderer->makeKnownLink(
					$this->getFormMsg( self::MSG_REASON_DROPDOWN_SUPPRESS )->inContentLanguage()->getTitle(),
					$this->getFormMsg( self::MSG_EDIT_REASONS_SUPPRESS )->text(),
					[],
					[ 'action' => 'edit' ]
				);
				$link .= $ctx->msg( 'pipe-separator' )->escaped();
			}
			$link .= $this->linkRenderer->makeKnownLink(
				$this->getFormMsg( self::MSG_REASON_DROPDOWN )->inContentLanguage()->getTitle(),
				$this->getFormMsg( self::MSG_EDIT_REASONS )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$outputPage->addHTML( '<p class="mw-delete-editreasons">' . $link . '</p>' );
		}

		$this->showLogEntries( $this->title );
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
			$lang = $this->getContext()->getLanguage();
			# Message keys used:
			# 'filedelete-intro-old', 'filedelete-nofile-old', 'filedelete-success-old'
			return $this->getContext()->msg(
				"{$message}-old",
				wfEscapeWikiText( $this->title->getText() ),
				$lang->date( $this->oldFile->getTimestamp(), true ),
				$lang->time( $this->oldFile->getTimestamp(), true ),
				wfExpandUrl( $this->file->getArchiveUrl( $this->oldImage ), PROTO_CURRENT )
			)->parseAsBlock();
		} else {
			return $this->getContext()->msg(
				$message,
				wfEscapeWikiText( $this->title->getText() )
			)->parseAsBlock();
		}
	}

	/**
	 * @return string
	 */
	protected function getFormAction(): string {
		$q = [];
		$q['action'] = 'delete';

		if ( $this->oldImage ) {
			$q['oldimage'] = $this->oldImage;
		}

		return $this->title->getLocalURL( $q );
	}

	/**
	 * @inheritDoc
	 */
	protected function runExecuteChecks( PageIdentity $title ): void {
		parent::runExecuteChecks( $title );

		if ( $this->getContext()->getConfig()->get( 'UploadMaintenance' ) ) {
			throw new ErrorPageError( 'filedelete-maintenance-title', 'filedelete-maintenance' );
		}
	}

	/**
	 * TODO Do we need all these messages to be different?
	 * @return string[]
	 */
	protected function getFormMessages(): array {
		return [
			self::MSG_REASON_DROPDOWN => 'filedelete-reason-dropdown',
			self::MSG_REASON_DROPDOWN_SUPPRESS => 'filedelete-reason-dropdown-suppress',
			self::MSG_REASON_DROPDOWN_OTHER => 'filedelete-reason-otherlist',
			self::MSG_COMMENT => 'filedelete-comment',
			self::MSG_REASON_OTHER => 'filedelete-otherreason',
			self::MSG_SUBMIT => 'filedelete-submit',
			self::MSG_LEGEND => 'filedelete-legend',
			self::MSG_EDIT_REASONS => 'filedelete-edit-reasonlist',
			self::MSG_EDIT_REASONS_SUPPRESS => 'filedelete-edit-reasonlist-suppress',
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getDefaultReason( bool &$hasHistory = false ): string {
		// TODO Should we autogenerate something for files?
		// FIXME $hasHistory not working here
		return $this->getRequest()->getText( 'wpReason' );
	}
}
