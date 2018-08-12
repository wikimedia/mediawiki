<?php
/**
 * File reversion user interface
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
 * @ingroup Media
 * @author Alexandre Emsenhuber
 * @author Rob Church <robchur@gmail.com>
 */

use MediaWiki\MediaWikiServices;

/**
 * File reversion user interface
 *
 * @ingroup Actions
 */
class RevertAction extends FormAction {
	/**
	 * @var OldLocalFile
	 */
	protected $oldFile;

	public function getName() {
		return 'revert';
	}

	public function getRestriction() {
		return 'upload';
	}

	protected function checkCanExecute( User $user ) {
		if ( $this->getTitle()->getNamespace() !== NS_FILE ) {
			throw new ErrorPageError( $this->msg( 'nosuchaction' ), $this->msg( 'nosuchactiontext' ) );
		}
		parent::checkCanExecute( $user );

		$oldimage = $this->getRequest()->getText( 'oldimage' );
		if ( strlen( $oldimage ) < 16
			|| strpos( $oldimage, '/' ) !== false
			|| strpos( $oldimage, '\\' ) !== false
		) {
			throw new ErrorPageError( 'internalerror', 'unexpected', [ 'oldimage', $oldimage ] );
		}

		$this->oldFile = RepoGroup::singleton()->getLocalRepo()->newFromArchiveName(
			$this->getTitle(),
			$oldimage
		);

		if ( !$this->oldFile->exists() ) {
			throw new ErrorPageError( '', 'filerevert-badversion' );
		}
	}

	protected function usesOOUI() {
		return true;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'filerevert-legend' );
		$form->setSubmitTextMsg( 'filerevert-submit' );
		$form->addHiddenField( 'oldimage', $this->getRequest()->getText( 'oldimage' ) );
		$form->setTokenSalt( [ 'revert', $this->getTitle()->getPrefixedDBkey() ] );
	}

	protected function getFormFields() {
		$timestamp = $this->oldFile->getTimestamp();

		$user = $this->getUser();
		$lang = $this->getLanguage();
		$userDate = $lang->userDate( $timestamp, $user );
		$userTime = $lang->userTime( $timestamp, $user );
		$siteTs = MWTimestamp::getLocalInstance( $timestamp );
		$ts = $siteTs->format( 'YmdHis' );
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$siteDate = $contLang->date( $ts, false, false );
		$siteTime = $contLang->time( $ts, false, false );
		$tzMsg = $siteTs->getTimezoneMessage()->inContentLanguage()->text();

		return [
			'intro' => [
				'type' => 'info',
				'vertical-label' => true,
				'raw' => true,
				'default' => $this->msg( 'filerevert-intro',
					$this->getTitle()->getText(), $userDate, $userTime,
					wfExpandUrl(
						$this->page->getFile()->getArchiveUrl( $this->getRequest()->getText( 'oldimage' ) ),
						PROTO_CURRENT
					) )->parseAsBlock()
			],
			'comment' => [
				'type' => 'text',
				'label-message' => 'filerevert-comment',
				'default' => $this->msg( 'filerevert-defaultcomment', $siteDate, $siteTime,
					$tzMsg )->inContentLanguage()->text()
			]
		];
	}

	public function onSubmit( $data ) {
		$this->useTransactionalTimeLimit();

		$old = $this->getRequest()->getText( 'oldimage' );
		$localFile = $this->page->getFile();
		$oldFile = OldLocalFile::newFromArchiveName( $this->getTitle(), $localFile->getRepo(), $old );

		$source = $localFile->getArchiveVirtualUrl( $old );
		$comment = $data['comment'];

		if ( $localFile->getSha1() === $oldFile->getSha1() ) {
			return Status::newFatal( 'filerevert-identical' );
		}

		// TODO: Preserve file properties from database instead of reloading from file
		return $localFile->upload(
			$source,
			$comment,
			$comment,
			0,
			false,
			false,
			$this->getUser()
		);
	}

	public function onSuccess() {
		$timestamp = $this->oldFile->getTimestamp();
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$userDate = $lang->userDate( $timestamp, $user );
		$userTime = $lang->userTime( $timestamp, $user );

		$this->getOutput()->addWikiMsg( 'filerevert-success', $this->getTitle()->getText(),
			$userDate, $userTime,
			wfExpandUrl( $this->page->getFile()->getArchiveUrl( $this->getRequest()->getText( 'oldimage' ) ),
				PROTO_CURRENT
		) );
		$this->getOutput()->returnToMain( false, $this->getTitle() );
	}

	protected function getPageTitle() {
		return $this->msg( 'filerevert', $this->getTitle()->getText() );
	}

	protected function getDescription() {
		return OutputPage::buildBacklinkSubtitle( $this->getTitle() );
	}

	public function doesWrites() {
		return true;
	}
}
