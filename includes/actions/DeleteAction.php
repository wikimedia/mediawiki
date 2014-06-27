<?php
/**
 * User interface/form for deleting pages.
 *
 * Copyright © 2014 Withoutaname
 *
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
 * @author Withoutaname
 */

/**
 * User interface/form for deleting pages.
 *
 * @ingroup Actions
 */
class DeleteAction extends FormAction {

	/**
	 * @var string Oldimage parameter in URL, if specified.
	 */
	protected $oldimage;

	public function __construct( Page $page, IContextSource $context ) {
		parent::__construct( $page, $context );
		$this->oldimage = $this->getRequest()->getVal( 'oldimage' );
	}

	public function checkCanExecute( User $user ) {
		parent::checkCanExecute( $user );

		$title = $this->getTitle();
		$file = wfLocalFile( $title );
		if ( $file && $file->exists() ) {
			if ( $this->oldimage ) {
				$oldfile = OldLocalFile::newFromArchiveName(
					$title,
					RepoGroup::singleton()->getLocalRepo(),
					$this->oldimage
				);
				if ( !$oldfile || !$oldfile->exists() ) {
					$nofiletitle = $this->msg( 'cannotdelete', $title );
					$nofiletext = $this->msg( 'filedelete-nofile-old', $title );
					throw new ErrorPageError( $nofiletitle, $nofiletext );
				}
			}

			global $wgUploadMaintenance;
			if ( $wgUploadMaintenance ) {
				$filemainttitle = $this->msg( 'filedelete-maintenance-title' );
				$filemainttext = $this->msg( 'filedelete-maintenance' );
				throw new ErrorPageError( $filemainttitle, $filemainttext );
			}
		}
	}

	public function getFormFields() {
		$fields = array();
		$user = $this->getUser();
		// Note that if ( $this->oldimage ) will cause the whole filepage to be deleted,
		// so instead we check for the presence of any existing file.
		if ( wfLocalFile( $this->getTitle() ) ) {
			$fields['OldFile'] = array(
				'default' => $this->oldimage,
				'id' => 'wpOldFile',
				'type' => 'hidden',
			);
		}
		$fields['Reason'] = array(
			'autofocus' => true,
			'cssclass' => 'wpReasonDropDown',
			'id' => 'wpReason',
			'label-message' => 'deletecomment',
			'maxlength' => 255,
			'options-message' => 'deletereason-dropdown',
			'section' => 'legend',
			'size' => 60,
			'type' => 'selectandother',
		);
		if ( !$user->isAnon() && $user->isAllowed( 'editmywatchlist' ) ) {
			$watchitem = WatchedItem::fromUserTitle(
				$this->getUser(),
				$this->getTitle(),
				WatchedItem::CHECK_USER_RIGHTS
			);
			$fields['Watch'] = array(
				'default' => $user->getBoolOption( 'watchdeletion' ) || $watchitem->isWatched(),
				'id' => 'wpWatch',
				'label-message' => 'watchthis',
				'section' => 'legend',
				'type' => 'check',
			);
		}
		if ( $user->isAllowed( 'suppressrevision' ) ) {
			$fields['Suppress'] = array(
				'default' => false,
				'id' => 'wpSuppress',
				'label-message' => 'revdelete-suppress',
				'section' => 'legend',
				'type' => 'check',
			);
		}
		return $fields;
	}

	public function getName() {
		return 'delete';
	}

	public function getRestriction() {
		return 'delete';
	}

	public function onSubmit( $data ) {
		$reason = $data['Reason'][0];

		$suppress = false;
		if ( isset( $data['Suppress'] ) && $data['Suppress'] ) {
			$suppress = true;
		}

		$status = Status::newGood( $this->getTitle() );
		$dbw = wfGetDB( DB_MASTER );
		$articleWasDeleted = $fileWasDeleted = false;
		$file = wfLocalFile( $this->getTitle() );

		if ( $file && $file->exists() ) {

			$logtype = $suppress ? 'suppress' : 'delete';
			$logEntry = new ManualLogEntry( $logtype, 'delete' );
			$logEntry->setPerformer( $this->getUser() );
			$logEntry->setTarget( $this->getTitle() );

			if ( isset( $data['OldFile'] ) ) {
				try {
					$this->oldimage = $data['OldFile'];
					$reason = $this->msg( 'deletedrevision', $this->oldimage )->inContentLanguage()->text() .
						$this->msg( 'colon-separator' )->inContentLanguage()->text() . trim( $reason );
					$fileStatus = $file->deleteOld( $this->oldimage, $reason, $suppress, $this->getUser() );
				} catch ( MWException $e ) {
					$dbw->rollback( __METHOD__ );
					throw $e;
				}
			} else {
				try {
					$fileStatus = $file->delete( $reason, $suppress, $this->getUser() );
				} catch ( MWException $e ) {
					$dbw->rollback( __METHOD__ );
					throw $e;
				}
			}

			$status->merge( $fileStatus );
			$fileWasDeleted = true;
		}

		if ( $this->page->exists() && !isset( $data['OldFile'] ) ) {
			try {
				/**
				 * @todo Use getWikiPage() when Action is converted into a ContextSource subclass
				 * @todo FIXME: Workaround pass-by-reference bug for WikiPage::doDeleteArticleReal()
				 */
				$articleStatus = $this->page->doDeleteArticleReal( $reason, $suppress, 0, true );
				$status->merge( $articleStatus );
				$articleWasDeleted = true;
			} catch ( MWException $e ) {
				$dbw->rollback( __METHOD__ );
				throw $e;
			}
		}

		if ( $status->isGood() ) {

			$watchitem = WatchedItem::fromUserTitle(
				$this->getUser(),
				$this->getTitle(),
				WatchedItem::CHECK_USER_RIGHTS
			);
			if ( isset( $data['Watch'] ) && $data['Watch'] && !$watchitem->isWatched() ) {
				$watchitem->addWatch();
				$this->getUser()->invalidateCache();
			} elseif ( !$data['Watch'] && $watchitem->isWatched() ) {
				$watchitem->removeWatch();
				$this->getUser()->invalidateCache();
			}

			if ( $fileWasDeleted && !$articleWasDeleted ) {
				$logEntry->setComment( $reason );
				$logid = $logEntry->insert();
				$logEntry->publish( $logid );
			}
		}
		return $status;
	}

	public function onSuccess() {
		$output = $this->getOutput();
		$output->setRobotPolicy( 'noindex,nofollow' );
		$output->setPageTitle( $this->msg( 'actioncomplete' ) );

		if ( $this->oldimage ) {
			$oldfile = OldLocalFile::newFromArchiveName(
				$this->getTitle(),
				RepoGroup::singleton()->getLocalRepo(),
				$this->oldimage
			);
			$msgfinal = $this->msg( 'filedelete-success-old',
				$this->getTitle()->getText(),
				$this->getLanguage()->date( $oldfile->getTimestamp(), true ),
				$this->getLanguage()->time( $oldfile->getTimestamp(), true )
			)->parseAsBlock() . "\n";
		} else {
			$msgfinal = Html::openElement( 'p' ) .
			$this->msg( 'deletedtext', $this->getTitle(),
				Linker::linkKnown(
					SpecialPage::getSafeTitleFor( 'Log', 'delete' ),
					$this->msg( 'deletionlog' )->parse()
				)
			)->text() .
			Html::closeElement( 'p' ) . "\n";
		}

		$output->addHTML( $msgfinal );
		$output->returnToMain();
	}

	public function preText() {
		$form = '';
		$title = $this->getTitle();

		if ( $this->oldimage ) {
			$oldfile = OldLocalFile::newFromArchiveName(
				$title,
				RepoGroup::singleton()->getLocalRepo(),
				$this->oldimage
			);
			$form .= $this->msg( 'filedelete-intro-old',
				$title->getText(),
				$this->getLanguage()->date( $oldfile->getTimestamp(), true ),
				$this->getLanguage()->time( $oldfile->getTimestamp(), true ),
				wfExpandUrl( wfLocalFile( $title )->getArchiveUrl( $this->oldimage ), null )
			)->parseAsBlock();
			return $form;
		}

		$revisions = $title->estimateRevisionCount();
		if ( $revisions > 1 ) {
			$form .= Html::rawElement( 'strong', array( 'class' => 'mw-delete-warning-revisions' ),
				$this->msg( 'historywarning' )->numParams( $revisions )->parse() .
				$this->msg( 'word-separator' )->plain() .
				Linker::linkKnown(
					$title,
					$this->msg( 'history' )->escaped(),
					array( 'rel' => 'archives' ),
					array( 'action' => 'history' )
				)
			);

			if ( $title->isBigDeletion() ) {
				global $wgDeleteRevisionsLimit;
				$form .= Html::element( 'div', array( 'class' => 'error' ),
					"\n" .
					$this->msg( 'delete-warning-toobig' )->numParams( $wgDeleteRevisionsLimit )->parse() .
					"\n"
				) . "\n";
			}
		}

		$backlinkCache = $title->getBacklinkCache();
		if ( $backlinkCache->hasLinks( 'pagelinks' ) || $backlinkCache->hasLinks( 'templatelinks' ) ) {
			$form .= Html::element( 'div', array( 'class' => 'mw-warning plainlinks' ),
				"\n" .
				$this->msg( 'deleting-backlinks-warning' )->parse() .
				"\n"
			) . "\n";
		}

		$form .= $this->msg( 'confirmdeletetext' )->parseAsBlock();

		return $form;
	}

	public function postText() {
		$form = '';

		if ( $this->getUser()->isAllowed( 'editinterface' ) ) {
			$mwtitle = Title::makeTitle( NS_MEDIAWIKI, 'Deletereason-dropdown' );
			$form .= Html::rawElement( 'p', array( 'class' => 'mw-delete-editreasons' ),
				Linker::link(
					$mwtitle,
					$this->msg( 'delete-edit-reasonlist' )->escaped(),
					array(),
					array( 'action' => 'edit' )
				)
			) . "\n";
		}

		$deleteLogPage = new LogPage( 'delete' );
		$deleteLogMsg = $deleteLogPage->getName()->text();
		$form .= Xml::openElement( 'h2' ) .
			Xml::element( 'span', array( 'class' => 'mw-headline', 'id' => $deleteLogMsg ), $deleteLogMsg ) .
		Xml::closeElement( 'h2' ) . "\n";
		$html = '';
		LogEventsList::showLogExtract( $html, 'delete', $this->getTitle() );
		$form .= $html;

		return $form;
	}

	public function alterForm( HTMLForm $form ) {
		$form->setSubmitID( 'wpConfirmB' );
		$form->setSubmitName( 'wpConfirmB' );
		$form->setSubmitText( $this->msg( 'deletepage' )->text() );
	}

	public function setHeaders() {
		$output = $this->getOutput();
		$output->setRobotPolicy( 'noindex,nofollow' );
		$output->setPageTitle( $this->msg( 'delete-confirm', $this->getTitle() )->text() );
		$output->addBacklinkSubtitle( $this->getTitle() );
		$output->setArticleRelated( true );
	}
}
