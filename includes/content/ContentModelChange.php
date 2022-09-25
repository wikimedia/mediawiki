<?php

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserFactory;

/**
 * Helper class to change the content model of pages
 *
 * For creating new pages via the action API,
 * use the edit api and specify the desired content model and format.
 *
 * @since 1.35
 * @author DannyS712
 */
class ContentModelChange {

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var HookRunner */
	private $hookRunner;

	/** @var RevisionLookup */
	private $revLookup;

	/** @var UserFactory */
	private $userFactory;

	/** @var Authority making the change */
	private $performer;

	/** @var WikiPage */
	private $page;

	/** @var string */
	private $newModel;

	/** @var string[] tags to add */
	private $tags;

	/** @var Content */
	private $newContent;

	/** @var int|false latest revision id, or false if creating */
	private $latestRevId;

	/** @var string 'new' or 'change' */
	private $logAction;

	/** @var string 'apierror-' or empty string, for status messages */
	private $msgPrefix;

	/**
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param HookContainer $hookContainer
	 * @param RevisionLookup $revLookup
	 * @param UserFactory $userFactory
	 * @param Authority $performer
	 * @param WikiPage $page
	 * @param string $newModel
	 */
	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		HookContainer $hookContainer,
		RevisionLookup $revLookup,
		UserFactory $userFactory,
		Authority $performer,
		WikiPage $page,
		string $newModel
	) {
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->revLookup = $revLookup;
		$this->userFactory = $userFactory;

		$this->performer = $performer;
		$this->page = $page;
		$this->newModel = $newModel;

		// SpecialChangeContentModel doesn't support tags
		// api can specify tags via ::setTags, which also checks if user can add
		// the tags specified
		$this->tags = [];

		// Requires createNewContent to be called first
		$this->logAction = '';

		// Defaults to nothing, for special page
		$this->msgPrefix = '';
	}

	/**
	 * @param string $msgPrefix
	 */
	public function setMessagePrefix( $msgPrefix ) {
		$this->msgPrefix = $msgPrefix;
	}

	/**
	 * @param callable $authorizer ( string $action, PageIdentity $target, PermissionStatus $status )
	 * @return PermissionStatus
	 */
	private function authorizeInternal( callable $authorizer ): PermissionStatus {
		$current = $this->page->getTitle();
		$titleWithNewContentModel = clone $current;
		$titleWithNewContentModel->setContentModel( $this->newModel );

		$status = PermissionStatus::newEmpty();
		$authorizer( 'editcontentmodel', $current, $status );
		$authorizer( 'edit', $current, $status );
		$authorizer( 'editcontentmodel', $titleWithNewContentModel, $status );
		$authorizer( 'edit', $titleWithNewContentModel, $status );
		return $status;
	}

	/**
	 * Check whether $performer can execute the move.
	 *
	 * @note this method does not guarantee full permissions check, so it should
	 * only be used to to decide whether to show a move form. To authorize the move
	 * action use {@link self::authorizeChange} instead.
	 *
	 * @return PermissionStatus
	 */
	public function probablyCanChange(): PermissionStatus {
		return $this->authorizeInternal(
			function ( string $action, PageIdentity $target, PermissionStatus $status ) {
				return $this->performer->probablyCan( $action, $target, $status );
			}
		);
	}

	/**
	 * Authorize the move by $performer.
	 *
	 * @note this method should be used right before the actual move is performed.
	 * To check whether a current performer has the potential to move the page,
	 * use {@link self::probablyCanChange} instead.
	 *
	 * @return PermissionStatus
	 */
	public function authorizeChange(): PermissionStatus {
		return $this->authorizeInternal(
			function ( string $action, PageIdentity $target, PermissionStatus $status ) {
				return $this->performer->authorizeWrite( $action, $target, $status );
			}
		);
	}

	/**
	 * Check user can edit and editcontentmodel before and after
	 *
	 * @deprecated since 1.36. Use ::probablyCanChange or ::authorizeChange instead.
	 * @return array from wfMergeErrorArrays
	 */
	public function checkPermissions() {
		wfDeprecated( __METHOD__, '1.36' );
		$status = $this->authorizeInternal(
			function ( string $action, PageIdentity $target, PermissionStatus $status ) {
				return $this->performer->definitelyCan( $action, $target, $status );
			} );
		return $status->toLegacyErrorArray();
	}

	/**
	 * Specify the tags the user wants to add, and check permissions
	 *
	 * @param string[] $tags
	 * @return Status
	 */
	public function setTags( $tags ) {
		$tagStatus = ChangeTags::canAddTagsAccompanyingChange( $tags, $this->performer );
		if ( $tagStatus->isOK() ) {
			$this->tags = $tags;
			return Status::newGood();
		} else {
			return $tagStatus;
		}
	}

	/**
	 * @return Status
	 */
	private function createNewContent() {
		$contentHandlerFactory = $this->contentHandlerFactory;

		$title = $this->page->getTitle();
		$latestRevRecord = $this->revLookup->getRevisionByTitle( $title );

		if ( $latestRevRecord ) {
			$latestContent = $latestRevRecord->getContent( SlotRecord::MAIN );
			$latestHandler = $latestContent->getContentHandler();
			$latestModel = $latestContent->getModel();
			if ( !$latestHandler->supportsDirectEditing() ) {
				// Only reachable via api
				return Status::newFatal(
					'apierror-changecontentmodel-nodirectediting',
					ContentHandler::getLocalizedName( $latestModel )
				);
			}

			$newModel = $this->newModel;
			if ( $newModel === $latestModel ) {
				// Only reachable via api
				return Status::newFatal( 'apierror-nochanges' );
			}
			$newHandler = $contentHandlerFactory->getContentHandler( $newModel );
			if ( !$newHandler->canBeUsedOn( $title ) ) {
				// Only reachable via api
				return Status::newFatal(
					'apierror-changecontentmodel-cannotbeused',
					ContentHandler::getLocalizedName( $newModel ),
					Message::plaintextParam( $title->getPrefixedText() )
				);
			}

			try {
				$newContent = $newHandler->unserializeContent(
					$latestContent->serialize()
				);
			} catch ( MWException $e ) {
				// Messages: changecontentmodel-cannot-convert,
				// apierror-changecontentmodel-cannot-convert
				return Status::newFatal(
					$this->msgPrefix . 'changecontentmodel-cannot-convert',
					Message::plaintextParam( $title->getPrefixedText() ),
					ContentHandler::getLocalizedName( $newModel )
				);
			}
			$this->latestRevId = $latestRevRecord->getId();
			$this->logAction = 'change';
		} else {
			// Page doesn't exist, create an empty content object
			$newContent = $contentHandlerFactory
				->getContentHandler( $this->newModel )
				->makeEmptyContent();
			$this->latestRevId = false;
			$this->logAction = 'new';
		}
		$this->newContent = $newContent;
		return Status::newGood();
	}

	/**
	 * Handle change and logging after validation
	 *
	 * Can still be intercepted by hooks
	 *
	 * @param IContextSource $context
	 * @param string $comment
	 * @param bool $bot Mark as a bot edit if the user can
	 * @return Status
	 * @throws ThrottledError
	 */
	public function doContentModelChange(
		IContextSource $context,
		$comment,
		$bot
	) {
		$status = $this->createNewContent();
		if ( !$status->isGood() ) {
			return $status;
		}

		$page = $this->page;
		$title = $page->getTitle();
		$user = $this->userFactory->newFromAuthority( $this->performer );

		// TODO: fold into authorizeChange
		if ( $user->pingLimiter( 'editcontentmodel' ) ) {
			throw new ThrottledError();
		}

		// Create log entry
		$log = new ManualLogEntry( 'contentmodel', $this->logAction );
		$log->setPerformer( $this->performer->getUser() );
		$log->setTarget( $title );
		$log->setComment( $comment );
		$log->setParameters( [
			'4::oldmodel' => $title->getContentModel(),
			'5::newmodel' => $this->newModel
		] );
		$log->addTags( $this->tags );

		$formatter = LogFormatter::newFromEntry( $log );
		$formatter->setContext( RequestContext::newExtraneousContext( $title ) );
		$reason = $formatter->getPlainActionText();

		if ( $comment !== '' ) {
			$reason .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $comment;
		}

		// Run edit filters
		$derivativeContext = new DerivativeContext( $context );
		$derivativeContext->setTitle( $title );
		$derivativeContext->setWikiPage( $page );
		$status = new Status();

		$newContent = $this->newContent;

		if ( !$this->hookRunner->onEditFilterMergedContent( $derivativeContext, $newContent,
			$status, $reason, $user, false )
		) {
			if ( $status->isGood() ) {
				// TODO: extensions should really specify an error message
				$status->fatal( 'hookaborted' );
			}
			return $status;
		}
		if ( !$status->isOK() ) {
			if ( !$status->getErrors() ) {
				$status->fatal( 'hookaborted' );
			}
			return $status;
		}

		// Make the edit
		$flags = $this->latestRevId ? EDIT_UPDATE : EDIT_NEW;
		$flags |= EDIT_INTERNAL;
		if ( $bot && $this->performer->isAllowed( 'bot' ) ) {
			$flags |= EDIT_FORCE_BOT;
		}

		$status = $page->doUserEditContent(
			$newContent,
			$this->performer,
			$reason,
			$flags,
			$this->latestRevId,
			$this->tags
		);

		if ( !$status->isOK() ) {
			return $status;
		}

		$logid = $log->insert();
		$log->publish( $logid );

		$values = [
			'logid' => $logid
		];

		return Status::newGood( $values );
	}

}
