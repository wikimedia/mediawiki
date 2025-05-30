<?php

namespace MediaWiki\Content;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\MWException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\User\UserFactory;

/**
 * Backend logic for changing the content model of a page.
 *
 * Note that you can create a new page directly with a desired content
 * model and format, e.g. via EditPage or externally from ApiEditPage.
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
	private LogFormatterFactory $logFormatterFactory;
	/** @var Authority making the change */
	private $performer;
	/** @var WikiPage */
	private $page;
	/** @var PageIdentity */
	private $pageIdentity;
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
	 * @internal Create via the ContentModelChangeFactory service.
	 *
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param HookContainer $hookContainer
	 * @param RevisionLookup $revLookup
	 * @param UserFactory $userFactory
	 * @param WikiPageFactory $wikiPageFactory
	 * @param LogFormatterFactory $logFormatterFactory
	 * @param Authority $performer
	 * @param PageIdentity $page
	 * @param string $newModel
	 */
	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		HookContainer $hookContainer,
		RevisionLookup $revLookup,
		UserFactory $userFactory,
		WikiPageFactory $wikiPageFactory,
		LogFormatterFactory $logFormatterFactory,
		Authority $performer,
		PageIdentity $page,
		string $newModel
	) {
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->revLookup = $revLookup;
		$this->userFactory = $userFactory;
		$this->logFormatterFactory = $logFormatterFactory;

		$this->performer = $performer;
		$this->page = $wikiPageFactory->newFromTitle( $page );
		$this->pageIdentity = $page;
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
	 *
	 * @return PermissionStatus
	 */
	private function authorizeInternal( callable $authorizer ): PermissionStatus {
		$current = $this->page->getTitle();
		$titleWithNewContentModel = clone $current;
		$titleWithNewContentModel->setContentModel( $this->newModel );

		$status = PermissionStatus::newEmpty();
		$authorizer( 'editcontentmodel', $this->pageIdentity, $status );
		$authorizer( 'edit', $this->pageIdentity, $status );
		$authorizer( 'editcontentmodel', $titleWithNewContentModel, $status );
		$authorizer( 'edit', $titleWithNewContentModel, $status );

		return $status;
	}

	/**
	 * Check whether $performer can execute the content model change.
	 *
	 * @note this method does not guarantee full permissions check, so it should
	 * only be used to to decide whether to show a content model change form.
	 * To authorize the content model change action use {@link self::authorizeChange} instead.
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
	 * Authorize the content model change by $performer.
	 *
	 * @note this method should be used right before the actual content model change is performed.
	 * To check whether a current performer has the potential to change the content model of the page,
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
	 * Specify the tags the user wants to add, and check permissions
	 *
	 * @param string[] $tags
	 *
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
		$latestRevRecord = $this->revLookup->getRevisionByTitle( $this->pageIdentity );

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
			} catch ( MWException ) {
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
	 *
	 * @return Status
	 */
	public function doContentModelChange(
		IContextSource $context,
		string $comment,
		$bot
	) {
		$status = $this->createNewContent();
		if ( !$status->isGood() ) {
			return $status;
		}

		$page = $this->page;
		$title = $page->getTitle();
		$user = $this->userFactory->newFromAuthority( $this->performer );

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

		$formatter = $this->logFormatterFactory->newFromEntry( $log );
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
			if ( !$status->getMessages() ) {
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

/** @deprecated class alias since 1.43 */
class_alias( ContentModelChange::class, 'ContentModelChange' );
