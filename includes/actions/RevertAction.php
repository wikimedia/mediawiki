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

namespace MediaWiki\Actions;

use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\File\OldLocalFile;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiFilePage;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;

/**
 * File reversion user interface
 * WikiPage must contain getFile method: \WikiFilePage
 * Article::getFile is only for b/c: \ImagePage
 *
 * @ingroup Actions
 */
class RevertAction extends FormAction {

	private Language $contentLanguage;
	private RepoGroup $repoGroup;

	/**
	 * @param Article $article
	 * @param IContextSource $context
	 * @param Language $contentLanguage
	 * @param RepoGroup $repoGroup
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		Language $contentLanguage,
		RepoGroup $repoGroup
	) {
		parent::__construct( $article, $context );
		$this->contentLanguage = $contentLanguage;
		$this->repoGroup = $repoGroup;
	}

	/**
	 * @var OldLocalFile
	 */
	protected $oldFile;

	public function getName() {
		return 'revert';
	}

	public function getRestriction() {
		// Required permissions of revert are complicated, will be checked below.
		return 'upload';
	}

	protected function checkCanExecute( User $user ) {
		if ( $this->getTitle()->getNamespace() !== NS_FILE ) {
			throw new ErrorPageError( $this->msg( 'nosuchaction' ), $this->msg( 'nosuchactiontext' ) );
		}

		$rights = [ 'reupload' ];
		if ( $user->equals( $this->getFile()->getUploader() ) ) {
			// reupload-own is more basic, put it in the front for error messages.
			array_unshift( $rights, 'reupload-own' );
		}
		if ( !$user->isAllowedAny( ...$rights ) ) {
			throw new PermissionsError( $rights[0] );
		}

		parent::checkCanExecute( $user );

		$oldimage = $this->getRequest()->getText( 'oldimage' );
		if ( strlen( $oldimage ) < 16
			|| strpos( $oldimage, '/' ) !== false
			|| strpos( $oldimage, '\\' ) !== false
		) {
			throw new ErrorPageError( 'internalerror', 'unexpected', [ 'oldimage', $oldimage ] );
		}

		$this->oldFile = $this->repoGroup->getLocalRepo()
			->newFromArchiveName( $this->getTitle(), $oldimage );

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
		$contLang = $this->contentLanguage;
		$siteDate = $contLang->date( $ts, false, false );
		$siteTime = $contLang->time( $ts, false, false );
		$tzMsg = $siteTs->getTimezoneMessage()->inContentLanguage()->text();

		return [
			'intro' => [
				'type' => 'info',
				'raw' => true,
				'default' => $this->msg( 'filerevert-intro',
					$this->getTitle()->getText(), $userDate, $userTime,
					(string)MediaWikiServices::getInstance()->getUrlUtils()->expand(
						$this->getFile()
							->getArchiveUrl(
								$this->getRequest()->getText( 'oldimage' )
							),
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
		/** @var LocalFile $localFile */
		$localFile = $this->getFile();
		'@phan-var LocalFile $localFile';
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
			$this->getAuthority(),
			[],
			true,
			true
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
			(string)MediaWikiServices::getInstance()->getUrlUtils()->expand(
				$this->getFile()
					->getArchiveUrl(
						$this->getRequest()->getText( 'oldimage' )
					),
				PROTO_CURRENT
			) );
		$this->getOutput()->returnToMain( false, $this->getTitle() );
	}

	protected function getPageTitle() {
		return $this->msg( 'filerevert' )->plaintextParams( $this->getTitle()->getText() );
	}

	protected function getDescription() {
		return OutputPage::buildBacklinkSubtitle( $this->getTitle() )->escaped();
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * @since 1.35
	 * @return File
	 */
	private function getFile(): File {
		/** @var WikiFilePage $wikiPage */
		$wikiPage = $this->getWikiPage();
		// @phan-suppress-next-line PhanUndeclaredMethod
		return $wikiPage->getFile();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RevertAction::class, 'RevertAction' );
