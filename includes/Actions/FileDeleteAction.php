<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\File\OldLocalFile;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Article;
use MediaWiki\Page\File\FileDeleteForm;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Handle file deletion
 *
 * @ingroup Actions
 */
class FileDeleteAction extends DeleteAction {
	/** @var File */
	private $file;
	/** @var string Descriptor for the old version of the image, if applicable */
	private $oldImage;
	/** @var OldLocalFile|null Corresponding to oldImage, if applicable */
	private $oldFile;

	/**
	 * @inheritDoc
	 */
	public function __construct( Article $article, IContextSource $context ) {
		parent::__construct( $article, $context );
		$services = MediaWikiServices::getInstance();
		$this->file = $this->getArticle()->getFile();
		$this->oldImage = $this->getRequest()->getText( 'oldimage', '' );
		if ( $this->oldImage !== '' ) {
			$this->oldFile = $services->getRepoGroup()->getLocalRepo()->newFromArchiveName(
				$this->getTitle(),
				$this->oldImage
			);
		}
	}

	/** @inheritDoc */
	protected function getPageTitle() {
		$title = $this->getTitle();
		return $this->msg( 'filedelete' )->plaintextParams( $title->getText() );
	}

	protected function tempDelete() {
		$file = $this->file;
		/** @var LocalFile $file */'@phan-var LocalFile $file';
		$this->tempExecute( $file );
	}

	private function tempExecute( LocalFile $file ): void {
		$context = $this->getContext();
		$title = $this->getTitle();
		$article = $this->getArticle();
		$outputPage = $context->getOutput();
		$request = $context->getRequest();

		$checkFile = $this->oldFile ?: $file;
		if ( !$checkFile->exists() || !$checkFile->isLocal() ) {
			$outputPage->addHTML( $this->prepareMessage( 'filedelete-nofile' ) );
			$outputPage->addReturnTo( $title );
			return;
		}

		// Perform the deletion if appropriate
		$token = $request->getVal( 'wpEditToken' );
		if (
			!$request->wasPosted() ||
			!$context->getUser()->matchEditToken( $token, [ 'delete', $title->getPrefixedText() ] )
		) {
			$this->showConfirm();
			return;
		}

		// Check to make sure the page has not been edited while the deletion was being confirmed
		if ( $article->getRevIdFetched() !== $request->getIntOrNull( 'wpConfirmationRevId' ) ) {
			$this->showEditedWarning();
			$this->showConfirm();
			return;
		}

		$permissionStatus = PermissionStatus::newEmpty();
		if ( !$context->getAuthority()->authorizeWrite(
			'delete', $title, $permissionStatus
		) ) {
			throw new PermissionsError( 'delete', $permissionStatus );
		}

		$reason = $this->getDeleteReason();

		# Flag to hide all contents of the archived revisions
		$suppress = $request->getCheck( 'wpSuppress' ) &&
			$context->getAuthority()->isAllowed( 'suppressrevision' );

		$status = FileDeleteForm::doDelete(
			$title,
			$file,
			$this->oldImage,
			$reason,
			$suppress,
			$context->getUser(),
			[],
			$request->getCheck( 'wpDeleteTalk' )
		);

		if ( !$status->isGood() ) {
			$outputPage->setPageTitleMsg(
				$this->msg( 'cannotdelete-title' )->plaintextParams( $title->getPrefixedText() )
			);
			$outputPage->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
			foreach ( $status->getMessages() as $msg ) {
				$outputPage->addHTML( Html::errorBox(
					$context->msg( $msg )->parse()
				) );
			}
		}
		if ( $status->isOK() ) {
			$outputPage->setPageTitleMsg( $context->msg( 'actioncomplete' ) );
			$outputPage->addHTML( $this->prepareMessage( 'filedelete-success' ) );
			// Return to the main page if we just deleted all versions of the
			// file, otherwise go back to the description page
			$outputPage->addReturnTo( $this->oldImage ? $title : Title::newMainPage() );

			$this->watchlistManager->setWatch(
				$request->getCheck( 'wpWatch' ),
				$context->getAuthority(),
				$title
			);
		}
	}

	protected function showFormWarnings(): void {
		$this->getOutput()->addHTML( $this->prepareMessage( 'filedelete-intro' ) );
		$this->showSubpagesWarnings();
	}

	/**
	 * Show the confirmation form
	 */
	private function showConfirm() {
		$this->prepareOutputForForm();
		$context = $this->getContext();
		$article = $this->getArticle();

		// oldid is set to the revision id of the page when the page was displayed.
		// Check to make sure the page has not been edited between loading the page
		// and clicking the delete link
		$oldid = $context->getRequest()->getIntOrNull( 'oldid' );
		if ( $oldid !== null && $oldid !== $article->getRevIdFetched() ) {
			$this->showEditedWarning();
		}

		$this->showFormWarnings();
		$form = $this->getForm();
		if ( $form->show() ) {
			$this->onSuccess();
		}
		$this->showEditReasonsLinks();
		$this->showLogEntries();
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
				wfEscapeWikiText( $this->getTitle()->getText() ),
				$lang->date( $this->oldFile->getTimestamp(), true ),
				$lang->time( $this->oldFile->getTimestamp(), true ),
				(string)MediaWikiServices::getInstance()->getUrlUtils()->expand(
					$this->file->getArchiveUrl( $this->oldImage ),
					PROTO_CURRENT
				)
			)->parseAsBlock();
		} else {
			return $this->getContext()->msg(
				$message,
				wfEscapeWikiText( $this->getTitle()->getText() )
			)->parseAsBlock();
		}
	}

	protected function getFormAction(): string {
		$q = [];
		$q['action'] = 'delete';

		if ( $this->oldImage ) {
			$q['oldimage'] = $this->oldImage;
		}

		return $this->getTitle()->getLocalURL( $q );
	}

	protected function checkCanExecute( User $user ) {
		parent::checkCanExecute( $user );

		if ( $this->getContext()->getConfig()->get( MainConfigNames::UploadMaintenance ) ) {
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
}
