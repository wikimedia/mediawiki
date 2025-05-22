<?php
/**
 * Edit rollback user interface
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
 */

namespace MediaWiki\Actions;

use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Config\ConfigException;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Exception\BadRequestError;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\Exception\ThrottledError;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Page\Article;
use MediaWiki\Page\RollbackPageFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\Watchlist\WatchlistManager;
use Profiler;

/**
 * User interface for the rollback action
 *
 * @ingroup Actions
 */
class RollbackAction extends FormAction {

	private IContentHandlerFactory $contentHandlerFactory;
	private RollbackPageFactory $rollbackPageFactory;
	private UserOptionsLookup $userOptionsLookup;
	private WatchlistManager $watchlistManager;
	private CommentFormatter $commentFormatter;

	/**
	 * @param Article $article
	 * @param IContextSource $context
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param RollbackPageFactory $rollbackPageFactory
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param WatchlistManager $watchlistManager
	 * @param CommentFormatter $commentFormatter
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		IContentHandlerFactory $contentHandlerFactory,
		RollbackPageFactory $rollbackPageFactory,
		UserOptionsLookup $userOptionsLookup,
		WatchlistManager $watchlistManager,
		CommentFormatter $commentFormatter
	) {
		parent::__construct( $article, $context );
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->rollbackPageFactory = $rollbackPageFactory;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->watchlistManager = $watchlistManager;
		$this->commentFormatter = $commentFormatter;
	}

	public function getName() {
		return 'rollback';
	}

	public function getRestriction() {
		return 'rollback';
	}

	protected function usesOOUI() {
		return true;
	}

	protected function getDescription() {
		return '';
	}

	public function doesWrites() {
		return true;
	}

	public function onSuccess() {
		return false;
	}

	public function onSubmit( $data ) {
		return false;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'confirm-rollback-top' );
		$form->setSubmitTextMsg( 'confirm-rollback-button' );
		$form->setTokenSalt( 'rollback' );

		$from = $this->getRequest()->getVal( 'from' );
		if ( $from === null ) {
			throw new BadRequestError( 'rollbackfailed', 'rollback-missingparam' );
		}
		foreach ( [ 'from', 'bot', 'hidediff', 'summary', 'token' ] as $param ) {
			$val = $this->getRequest()->getVal( $param );
			if ( $val !== null ) {
				$form->addHiddenField( $param, $val );
			}
		}
	}

	/**
	 * @throws ErrorPageError
	 * @throws ReadOnlyError
	 * @throws ThrottledError
	 */
	public function show() {
		$this->setHeaders();
		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		if ( !$this->userOptionsLookup->getOption( $this->getUser(), 'showrollbackconfirmation' ) ||
			$this->getRequest()->wasPosted()
		) {
			$this->handleRollbackRequest();
		} else {
			$this->showRollbackConfirmationForm();
		}
	}

	public function handleRollbackRequest() {
		$this->enableTransactionalTimelimit();
		$this->getOutput()->addModuleStyles( 'mediawiki.interface.helpers.styles' );

		$request = $this->getRequest();
		$user = $this->getUser();
		$from = $request->getVal( 'from' );
		$rev = $this->getWikiPage()->getRevisionRecord();
		if ( $from === null ) {
			throw new ErrorPageError( 'rollbackfailed', 'rollback-missingparam' );
		}
		if ( !$rev ) {
			throw new ErrorPageError( 'rollbackfailed', 'rollback-missingrevision' );
		}

		$revUser = $rev->getUser();
		$userText = $revUser ? $revUser->getName() : '';
		if ( $from !== $userText ) {
			throw new ErrorPageError( 'rollbackfailed', 'alreadyrolled', [
				$this->getTitle()->getPrefixedText(),
				wfEscapeWikiText( $from ),
				$userText
			] );
		}

		if ( !$user->matchEditToken( $request->getVal( 'token' ), 'rollback' ) ) {
			throw new ErrorPageError( 'sessionfailure-title', 'sessionfailure' );
		}

		// The revision has the user suppressed, so the rollback has empty 'from',
		// so the check above would succeed in that case.
		// T307278 - Also check if the user has rights to view suppressed usernames
		if ( !$revUser ) {
			if ( $this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$revUser = $rev->getUser( RevisionRecord::RAW );
			} else {
				$userFactory = MediaWikiServices::getInstance()->getUserFactory();
				$revUser = $userFactory->newFromName( $this->context->msg( 'rev-deleted-user' )->plain() );
			}
		}

		$rollbackResult = $this->rollbackPageFactory
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable use of raw avoids null here
			->newRollbackPage( $this->getWikiPage(), $this->getAuthority(), $revUser )
			->setSummary( $request->getText( 'summary' ) )
			->markAsBot( $request->getBool( 'bot' ) )
			->rollbackIfAllowed();
		$data = $rollbackResult->getValue();

		if ( $rollbackResult->hasMessage( 'actionthrottledtext' ) ) {
			throw new ThrottledError;
		}

		# NOTE: Permission errors already handled by Action::checkExecute.
		if ( $rollbackResult->hasMessage( 'readonlytext' ) ) {
			throw new ReadOnlyError;
		}

		if ( $rollbackResult->getMessages() ) {
			$this->getOutput()->setPageTitleMsg( $this->msg( 'rollbackfailed' ) );

			foreach ( $rollbackResult->getMessages() as $msg ) {
				$this->getOutput()->addWikiMsg( $msg );
			}

			if (
				( $rollbackResult->hasMessage( 'alreadyrolled' ) || $rollbackResult->hasMessage( 'cantrollback' ) )
				&& isset( $data['current-revision-record'] )
			) {
				/** @var RevisionRecord $current */
				$current = $data['current-revision-record'];

				if ( $current->getComment() != null ) {
					$this->getOutput()->addWikiMsg(
						'editcomment',
						Message::rawParam(
							$this->commentFormatter
								->format( $current->getComment()->text )
						)
					);
				}
			}

			return;
		}

		/** @var RevisionRecord $current */
		$current = $data['current-revision-record'];
		$target = $data['target-revision-record'];
		$newId = $data['newid'];
		$this->getOutput()->setPageTitleMsg( $this->msg( 'actioncomplete' ) );
		$this->getOutput()->setRobotPolicy( 'noindex,nofollow' );

		$old = Linker::revUserTools( $current );
		$new = Linker::revUserTools( $target );

		$currentUser = $current->getUser( RevisionRecord::FOR_THIS_USER, $user );
		$targetUser = $target->getUser( RevisionRecord::FOR_THIS_USER, $user );
		$userOptionsLookup = $this->userOptionsLookup;
		$this->getOutput()->addHTML(
			$this->msg( 'rollback-success' )
				->rawParams( $old, $new )
				->params( $currentUser ? $currentUser->getName() : '' )
				->params( $targetUser ? $targetUser->getName() : '' )
				->parseAsBlock()
		);
		// Load the mediawiki.misc-authed-curate module, so that we can fire the JavaScript
		// postEdit hook on a successful rollback.
		$this->getOutput()->addModules( 'mediawiki.misc-authed-curate' );
		// Export a success flag to the frontend, so that the mediawiki.misc-authed-curate
		// ResourceLoader module can use this as an indicator to fire the postEdit hook.
		$this->getOutput()->addJsConfigVars( [
			'wgRollbackSuccess' => true,
			// Don't show an edit confirmation with mw.notify(), the rollback success page
			// is already a visual confirmation.
			'wgPostEditConfirmationDisabled' => true,
		] );

		// Watch the page for the user-chosen period of time, unless the page is already watched.
		if ( $userOptionsLookup->getBoolOption( $user, 'watchrollback' ) &&
			!$this->watchlistManager->isWatchedIgnoringRights( $user, $this->getTitle() )
		) {
			$this->watchlistManager->addWatchIgnoringRights( $user, $this->getTitle(),
				$userOptionsLookup->getOption( $user, 'watchrollback-expiry' ) );
		}

		$this->getOutput()->returnToMain( false, $this->getTitle() );

		if ( !$request->getBool( 'hidediff', false ) &&
			!$userOptionsLookup->getBoolOption( $this->getUser(), 'norollbackdiff' )
		) {
			$contentModel = $current->getMainContentModel();
			$contentHandler = $this->contentHandlerFactory->getContentHandler( $contentModel );
			$de = $contentHandler->createDifferenceEngine(
				$this->getContext(),
				$current->getId(),
				$newId,
				0,
				true
			);
			$de->showDiff( '', '' );
		}
	}

	/**
	 * Enables transactional time limit for POST and GET requests to RollbackAction
	 * @throws ConfigException
	 */
	private function enableTransactionalTimelimit() {
		// If Rollbacks are made POST-only, use $this->useTransactionalTimeLimit()
		wfTransactionalTimeLimit();
		if ( !$this->getRequest()->wasPosted() ) {
			/**
			 * We apply the higher POST limits on GET requests
			 * to prevent logstash.wikimedia.org from being spammed
			 */
			$fname = __METHOD__;
			$trxLimits = $this->context->getConfig()->get( MainConfigNames::TrxProfilerLimits );
			$trxProfiler = Profiler::instance()->getTransactionProfiler();
			$trxProfiler->redefineExpectations( $trxLimits['POST'], $fname );
			DeferredUpdates::addCallableUpdate( static function () use ( $trxProfiler, $trxLimits, $fname
			) {
				$trxProfiler->redefineExpectations( $trxLimits['PostSend-POST'], $fname );
			} );
		}
	}

	private function showRollbackConfirmationForm() {
		$form = $this->getForm();
		if ( $form->show() ) {
			$this->onSuccess();
		}
	}

	protected function getFormFields() {
		return [
			'intro' => [
				'type' => 'info',
				'raw' => true,
				'default' => $this->msg( 'confirm-rollback-bottom' )->parse()
			]
		];
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RollbackAction::class, 'RollbackAction' );
