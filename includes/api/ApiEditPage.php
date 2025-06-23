<?php
/**
 * Copyright © 2007 Iker Labarga "<Firstname><Lastname>@gmail.com"
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
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\EditPage\EditPage;
use MediaWiki\Exception\MWContentSerializationException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Page\Article;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Request\DerivativeRequest;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A module that allows for editing and creating pages.
 *
 * Currently, this wraps around the EditPage class in an ugly way,
 * EditPage.php should be rewritten to provide a cleaner interface,
 * see T20654 if you're inspired to fix this.
 *
 * WARNING: This class is //not// stable to extend. However, it is
 * currently extended by the ApiThreadAction class in the LiquidThreads
 * extension, which is deployed on WMF servers. Changes that would
 * break LiquidThreads will likely be reverted. See T264200 for context
 * and T264213 for removing LiquidThreads' unsupported extending of this
 * class.
 *
 * @ingroup API
 */
class ApiEditPage extends ApiBase {
	use ApiCreateTempUserTrait;
	use ApiWatchlistTrait;

	private IContentHandlerFactory $contentHandlerFactory;
	private RevisionLookup $revisionLookup;
	private WatchedItemStoreInterface $watchedItemStore;
	private WikiPageFactory $wikiPageFactory;
	private RedirectLookup $redirectLookup;
	private TempUserCreator $tempUserCreator;
	private UserFactory $userFactory;

	/**
	 * Sends a cookie so anons get talk message notifications, mirroring SubmitAction (T295910)
	 */
	private function persistGlobalSession() {
		\MediaWiki\Session\SessionManager::getGlobalSession()->persist();
	}

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		?IContentHandlerFactory $contentHandlerFactory = null,
		?RevisionLookup $revisionLookup = null,
		?WatchedItemStoreInterface $watchedItemStore = null,
		?WikiPageFactory $wikiPageFactory = null,
		?WatchlistManager $watchlistManager = null,
		?UserOptionsLookup $userOptionsLookup = null,
		?RedirectLookup $redirectLookup = null,
		?TempUserCreator $tempUserCreator = null,
		?UserFactory $userFactory = null
	) {
		parent::__construct( $mainModule, $moduleName );

		// This class is extended and therefor fallback to global state - T264213
		$services = MediaWikiServices::getInstance();
		$this->contentHandlerFactory = $contentHandlerFactory ?? $services->getContentHandlerFactory();
		$this->revisionLookup = $revisionLookup ?? $services->getRevisionLookup();
		$this->watchedItemStore = $watchedItemStore ?? $services->getWatchedItemStore();
		$this->wikiPageFactory = $wikiPageFactory ?? $services->getWikiPageFactory();

		// Variables needed in ApiWatchlistTrait trait
		$this->watchlistExpiryEnabled = $this->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->watchlistMaxDuration =
			$this->getConfig()->get( MainConfigNames::WatchlistExpiryMaxDuration );
		$this->watchlistManager = $watchlistManager ?? $services->getWatchlistManager();
		$this->userOptionsLookup = $userOptionsLookup ?? $services->getUserOptionsLookup();
		$this->redirectLookup = $redirectLookup ?? $services->getRedirectLookup();
		$this->tempUserCreator = $tempUserCreator ?? $services->getTempUserCreator();
		$this->userFactory = $userFactory ?? $services->getUserFactory();
	}

	/**
	 * @see EditPage::getUserForPermissions
	 * @return User
	 */
	private function getUserForPermissions() {
		$user = $this->getUser();
		if ( $this->tempUserCreator->shouldAutoCreate( $user, 'edit' ) ) {
			return $this->userFactory->newUnsavedTempUser(
				$this->tempUserCreator->getStashedName( $this->getRequest()->getSession() )
			);
		}
		return $user;
	}

	public function execute() {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$this->requireAtLeastOneParameter( $params, 'text', 'appendtext', 'prependtext', 'undo' );

		$pageObj = $this->getTitleOrPageId( $params );
		$titleObj = $pageObj->getTitle();
		$this->getErrorFormatter()->setContextTitle( $titleObj );
		$apiResult = $this->getResult();

		if ( $params['redirect'] ) {
			if ( $params['prependtext'] === null
				&& $params['appendtext'] === null
				&& $params['section'] !== 'new'
			) {
				$this->dieWithError( 'apierror-redirect-appendonly' );
			}
			if ( $titleObj->isRedirect() ) {
				$oldTarget = $titleObj;
				$redirTarget = $this->redirectLookup->getRedirectTarget( $oldTarget );
				$redirTarget = Title::castFromLinkTarget( $redirTarget );

				$redirValues = [
					'from' => $titleObj->getPrefixedText(),
					'to' => $redirTarget->getPrefixedText()
				];

				// T239428: Check whether the new title is valid
				if ( $redirTarget->isExternal() || !$redirTarget->canExist() ) {
					$redirValues['to'] = $redirTarget->getFullText();
					$this->dieWithError(
						[
							'apierror-edit-invalidredirect',
							Message::plaintextParam( $oldTarget->getPrefixedText() ),
							Message::plaintextParam( $redirTarget->getFullText() ),
						],
						'edit-invalidredirect',
						[ 'redirects' => $redirValues ]
					);
				}

				ApiResult::setIndexedTagName( $redirValues, 'r' );
				$apiResult->addValue( null, 'redirects', $redirValues );

				// Since the page changed, update $pageObj and $titleObj
				$pageObj = $this->wikiPageFactory->newFromTitle( $redirTarget );
				$titleObj = $pageObj->getTitle();

				$this->getErrorFormatter()->setContextTitle( $redirTarget );
			}
		}

		if ( $params['contentmodel'] ) {
			$contentHandler = $this->contentHandlerFactory->getContentHandler( $params['contentmodel'] );
		} else {
			$contentHandler = $pageObj->getContentHandler();
		}
		$contentModel = $contentHandler->getModelID();

		$name = $titleObj->getPrefixedDBkey();

		if ( $params['undo'] > 0 ) {
			// allow undo via api
		} elseif ( $contentHandler->supportsDirectApiEditing() === false ) {
			$this->dieWithError( [ 'apierror-no-direct-editing', $contentModel, $name ] );
		}

		$contentFormat = $params['contentformat'] ?: $contentHandler->getDefaultFormat();

		if ( !$contentHandler->isSupportedFormat( $contentFormat ) ) {
			$this->dieWithError( [ 'apierror-badformat', $contentFormat, $contentModel, $name ] );
		}

		if ( $params['createonly'] && $titleObj->exists() ) {
			$this->dieWithError( 'apierror-articleexists' );
		}
		if ( $params['nocreate'] && !$titleObj->exists() ) {
			$this->dieWithError( 'apierror-missingtitle' );
		}

		// Now let's check whether we're even allowed to do this
		$this->checkTitleUserPermissions(
			$titleObj,
			'edit',
			[ 'autoblock' => true, 'user' => $this->getUserForPermissions() ]
		);

		$toMD5 = $params['text'];
		if ( $params['appendtext'] !== null || $params['prependtext'] !== null ) {
			$content = $pageObj->getContent();

			if ( !$content ) {
				if ( $titleObj->getNamespace() === NS_MEDIAWIKI ) {
					# If this is a MediaWiki:x message, then load the messages
					# and return the message value for x.
					$text = $titleObj->getDefaultMessageText();
					if ( $text === false ) {
						$text = '';
					}

					try {
						$content = ContentHandler::makeContent( $text, $titleObj );
					} catch ( MWContentSerializationException $ex ) {
						$this->dieWithException( $ex, [
							'wrap' => ApiMessage::create( 'apierror-contentserializationexception', 'parseerror' )
						] );
					}
				} else {
					# Otherwise, make a new empty content.
					$content = $contentHandler->makeEmptyContent();
				}
			}

			// @todo Add support for appending/prepending to the Content interface

			if ( !( $content instanceof TextContent ) ) {
				$this->dieWithError( [ 'apierror-appendnotsupported', $contentModel ] );
			}

			if ( $params['section'] !== null ) {
				if ( !$contentHandler->supportsSections() ) {
					$this->dieWithError( [ 'apierror-sectionsnotsupported', $contentModel ] );
				}

				if ( $params['section'] == 'new' ) {
					// DWIM if they're trying to prepend/append to a new section.
					$content = null;
				} else {
					// Process the content for section edits
					$section = $params['section'];
					$content = $content->getSection( $section );

					if ( !$content ) {
						$this->dieWithError( [ 'apierror-nosuchsection', wfEscapeWikiText( $section ) ] );
					}
				}
			}

			if ( !$content ) {
				$text = '';
			} else {
				$text = $content->serialize( $contentFormat );
			}

			$params['text'] = $params['prependtext'] . $text . $params['appendtext'];
			$toMD5 = $params['prependtext'] . $params['appendtext'];
		}

		if ( $params['undo'] > 0 ) {
			$undoRev = $this->revisionLookup->getRevisionById( $params['undo'] );
			if ( $undoRev === null || $undoRev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $params['undo'] ] );
			}

			if ( $params['undoafter'] > 0 ) {
				$undoafterRev = $this->revisionLookup->getRevisionById( $params['undoafter'] );
			} else {
				// undoafter=0 or null
				$undoafterRev = $this->revisionLookup->getPreviousRevision( $undoRev );
			}
			if ( $undoafterRev === null || $undoafterRev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $params['undoafter'] ] );
			}

			if ( $undoRev->getPageId() != $pageObj->getId() ) {
				$this->dieWithError( [ 'apierror-revwrongpage', $undoRev->getId(),
					$titleObj->getPrefixedText() ] );
			}
			if ( $undoafterRev->getPageId() != $pageObj->getId() ) {
				$this->dieWithError( [ 'apierror-revwrongpage', $undoafterRev->getId(),
					$titleObj->getPrefixedText() ] );
			}

			$newContent = $contentHandler->getUndoContent(
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Content is for public use here
				$pageObj->getRevisionRecord()->getContent( SlotRecord::MAIN ),
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Content is for public use here
				$undoRev->getContent( SlotRecord::MAIN ),
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Content is for public use here
				$undoafterRev->getContent( SlotRecord::MAIN ),
				$pageObj->getRevisionRecord()->getId() === $undoRev->getId()
			);

			if ( !$newContent ) {
				$this->dieWithError( 'undo-failure', 'undofailure' );
			}
			if ( !$params['contentmodel'] && !$params['contentformat'] ) {
				// If we are reverting content model, the new content model
				// might not support the current serialization format, in
				// which case go back to the old serialization format,
				// but only if the user hasn't specified a format/model
				// parameter.
				if ( !$newContent->isSupportedFormat( $contentFormat ) ) {
					$undoafterRevMainSlot = $undoafterRev->getSlot(
						SlotRecord::MAIN,
						RevisionRecord::RAW
					);
					$contentFormat = $undoafterRevMainSlot->getFormat();
					if ( !$contentFormat ) {
						// fall back to default content format for the model
						// of $undoafterRev
						$contentFormat = $this->contentHandlerFactory
							->getContentHandler( $undoafterRevMainSlot->getModel() )
							->getDefaultFormat();
					}
				}
				// Override content model with model of undid revision.
				$contentModel = $newContent->getModel();
				$undoContentModel = true;
			}
			$params['text'] = $newContent->serialize( $contentFormat );
			// If no summary was given and we only undid one rev,
			// use an autosummary

			if ( $params['summary'] === null ) {
				$nextRev = $this->revisionLookup->getNextRevision( $undoafterRev );
				if ( $nextRev && $nextRev->getId() == $params['undo'] ) {
					$undoRevUser = $undoRev->getUser();
					$params['summary'] = $this->msg( 'undo-summary' )
						->params( $params['undo'], $undoRevUser ? $undoRevUser->getName() : '' )
						->inContentLanguage()->text();
				}
			}
		}

		// See if the MD5 hash checks out
		if ( $params['md5'] !== null && md5( $toMD5 ) !== $params['md5'] ) {
			$this->dieWithError( 'apierror-badmd5' );
		}

		// EditPage wants to parse its stuff from a WebRequest
		// That interface kind of sucks, but it's workable
		$requestArray = [
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
			'wpTextbox1' => $params['text'],
			'format' => $contentFormat,
			'model' => $contentModel,
			'wpEditToken' => $params['token'],
			'wpIgnoreBlankSummary' => true,
			'wpIgnoreBlankArticle' => true,
			'wpIgnoreSelfRedirect' => true,
			'wpIgnoreBrokenRedirects' => true,
			'wpIgnoreDoubleRedirects' => true,
			'bot' => $params['bot'],
			'wpUnicodeCheck' => EditPage::UNICODE_CHECK,
		];

		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
		if ( $params['summary'] !== null ) {
			$requestArray['wpSummary'] = $params['summary'];
		}

		if ( $params['sectiontitle'] !== null ) {
			$requestArray['wpSectionTitle'] = $params['sectiontitle'];
		}

		if ( $params['undo'] > 0 ) {
			$requestArray['wpUndidRevision'] = $params['undo'];
		}
		if ( $params['undoafter'] > 0 ) {
			$requestArray['wpUndoAfter'] = $params['undoafter'];
		}

		// Skip for baserevid == null or '' or '0' or 0
		if ( !empty( $params['baserevid'] ) ) {
			$requestArray['editRevId'] = $params['baserevid'];
		}

		// Watch out for basetimestamp == '' or '0'
		// It gets treated as NOW, almost certainly causing an edit conflict
		if ( $params['basetimestamp'] !== null && (bool)$this->getMain()->getVal( 'basetimestamp' ) ) {
			$requestArray['wpEdittime'] = $params['basetimestamp'];
		} elseif ( empty( $params['baserevid'] ) ) {
			// Only set if baserevid is not set. Otherwise, conflicts would be ignored,
			// due to the way userWasLastToEdit() works.
			$requestArray['wpEdittime'] = $pageObj->getTimestamp();
		}

		if ( $params['starttimestamp'] !== null ) {
			$requestArray['wpStarttime'] = $params['starttimestamp'];
		} else {
			$requestArray['wpStarttime'] = wfTimestampNow(); // Fake wpStartime
		}

		if ( $params['minor'] || ( !$params['notminor'] &&
			$this->userOptionsLookup->getOption( $user, 'minordefault' ) )
		) {
			$requestArray['wpMinoredit'] = '';
		}

		if ( $params['recreate'] ) {
			$requestArray['wpRecreate'] = '';
		}

		if ( $params['section'] !== null ) {
			$section = $params['section'];
			if ( !preg_match( '/^((T-)?\d+|new)$/', $section ) ) {
				$this->dieWithError( 'apierror-invalidsection' );
			}
			$content = $pageObj->getContent();
			if ( $section !== '0'
				&& $section != 'new'
				&& ( !$content || !$content->getSection( $section ) )
			) {
				$this->dieWithError( [ 'apierror-nosuchsection', $section ] );
			}
			$requestArray['wpSection'] = $params['section'];
		} else {
			$requestArray['wpSection'] = '';
		}

		$watch = $this->getWatchlistValue( $params['watchlist'], $titleObj, $user );

		// Deprecated parameters
		if ( $params['watch'] ) {
			$watch = true;
		} elseif ( $params['unwatch'] ) {
			$watch = false;
		}

		if ( $watch ) {
			$requestArray['wpWatchthis'] = true;
			$prefName = 'watchdefault-expiry';
			if ( !$pageObj->exists() ) {
				$prefName = 'watchcreations-expiry';
			}
			$watchlistExpiry = $this->getExpiryFromParams( $params, $titleObj, $user, $prefName );

			if ( $watchlistExpiry ) {
				$requestArray['wpWatchlistExpiry'] = $watchlistExpiry;
			}
		}

		// Apply change tags
		if ( $params['tags'] ) {
			$tagStatus = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $this->getAuthority() );
			if ( $tagStatus->isOK() ) {
				$requestArray['wpChangeTags'] = implode( ',', $params['tags'] );
			} else {
				$this->dieStatus( $tagStatus );
			}
		}

		// Pass through anything else we might have been given, to support extensions
		// This is kind of a hack but it's the best we can do to make extensions work
		$requestArray += $this->getRequest()->getValues();

		// phpcs:ignore MediaWiki.Usage.ExtendClassUsage.FunctionVarUsage,MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgTitle
		global $wgTitle, $wgRequest;

		$req = new DerivativeRequest( $this->getRequest(), $requestArray, true );

		// Some functions depend on $wgTitle == $ep->mTitle
		// TODO: Make them not or check if they still do
		$wgTitle = $titleObj;

		$articleContext = new RequestContext;
		$articleContext->setRequest( $req );
		$articleContext->setWikiPage( $pageObj );
		$articleContext->setUser( $this->getUser() );

		/** @var Article $articleObject */
		$articleObject = Article::newFromWikiPage( $pageObj, $articleContext );

		$ep = new EditPage( $articleObject );

		$ep->setApiEditOverride( true );
		$ep->setContextTitle( $titleObj );
		$ep->importFormData( $req );
		$tempUserCreateStatus = $ep->maybeActivateTempUserCreate( true );
		if ( !$tempUserCreateStatus->isOK() ) {
			$this->dieWithError( 'apierror-tempuseracquirefailed', 'tempuseracquirefailed' );
		}

		// T255700: Ensure content models of the base content
		// and fetched revision remain the same before attempting to save.
		$editRevId = $requestArray['editRevId'] ?? false;
		$baseRev = $this->revisionLookup->getRevisionByTitle( $titleObj, $editRevId );
		$baseContentModel = null;

		if ( $baseRev ) {
			$baseContent = $baseRev->getContent( SlotRecord::MAIN );
			$baseContentModel = $baseContent ? $baseContent->getModel() : null;
		}

		$baseContentModel ??= $pageObj->getContentModel();

		// However, allow the content models to possibly differ if we are intentionally
		// changing them or we are doing an undo edit that is reverting content model change.
		$contentModelsCanDiffer = $params['contentmodel'] || isset( $undoContentModel );

		if ( !$contentModelsCanDiffer && $contentModel !== $baseContentModel ) {
			$this->dieWithError( [ 'apierror-contentmodel-mismatch', $contentModel, $baseContentModel ] );
		}

		// Do the actual save
		$oldRevId = $articleObject->getRevIdFetched();
		$result = null;

		// Fake $wgRequest for some hooks inside EditPage
		// @todo FIXME: This interface SUCKS
		// phpcs:disable MediaWiki.Usage.ExtendClassUsage.FunctionVarUsage
		$oldRequest = $wgRequest;
		$wgRequest = $req;

		$status = $ep->attemptSave( $result );
		$statusValue = is_int( $status->value ) ? $status->value : 0;
		$wgRequest = $oldRequest;
		// phpcs:enable MediaWiki.Usage.ExtendClassUsage.FunctionVarUsage

		$r = [];
		switch ( $statusValue ) {
			case EditPage::AS_HOOK_ERROR:
			case EditPage::AS_HOOK_ERROR_EXPECTED:
				if ( $status->statusData !== null ) {
					$r = $status->statusData;
					$r['result'] = 'Failure';
					$apiResult->addValue( null, $this->getModuleName(), $r );
					return;
				}
				if ( !$status->getMessages() ) {
					// This appears to be unreachable right now, because all
					// code paths will set an error.  Could change, though.
					$status->fatal( 'hookaborted' ); // @codeCoverageIgnore
				}
				$this->dieStatus( $status );

			// These two cases will normally have been caught earlier, and will
			// only occur if something blocks the user between the earlier
			// check and the check in EditPage (presumably a hook).  It's not
			// obvious that this is even possible.
			// @codeCoverageIgnoreStart
			case EditPage::AS_BLOCKED_PAGE_FOR_USER:
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Block is checked and not null
				$this->dieBlocked( $user->getBlock() );
				// dieBlocked prevents continuation

			case EditPage::AS_READ_ONLY_PAGE:
				$this->dieReadOnly();
			// @codeCoverageIgnoreEnd

			case EditPage::AS_SUCCESS_NEW_ARTICLE:
				$r['new'] = true;
				// fall-through

			case EditPage::AS_SUCCESS_UPDATE:
				$r['result'] = 'Success';
				$r['pageid'] = (int)$titleObj->getArticleID();
				$r['title'] = $titleObj->getPrefixedText();
				$r['contentmodel'] = $articleObject->getPage()->getContentModel();
				$newRevId = $articleObject->getPage()->getLatest();
				if ( $newRevId == $oldRevId ) {
					$r['nochange'] = true;
				} else {
					$r['oldrevid'] = (int)$oldRevId;
					$r['newrevid'] = (int)$newRevId;
					$r['newtimestamp'] = wfTimestamp( TS_ISO_8601,
						$pageObj->getTimestamp() );
				}

				if ( $watch ) {
					$r['watched'] = true;

					$watchlistExpiry = $this->getWatchlistExpiry(
						$this->watchedItemStore,
						$titleObj,
						$user
					);

					if ( $watchlistExpiry ) {
						$r['watchlistexpiry'] = $watchlistExpiry;
					}
				}
				$this->persistGlobalSession();

				// If the temporary account was created in this request,
				// or if the temporary account has zero edits (implying
				// that the account was created during a failed edit
				// attempt in a previous request), perform the top-level
				// redirect to ensure the account is attached.
				// Note that the temp user could already have performed
				// the top-level redirect if this a first edit on
				// a wiki that is not the user's home wiki.
				$shouldRedirectForTempUser = isset( $result['savedTempUser'] ) ||
					( $user->isTemp() && ( $user->getEditCount() === 0 ) );
				if ( $shouldRedirectForTempUser ) {
					$r['tempusercreated'] = true;
					$params['returnto'] ??= $titleObj->getPrefixedDBkey();
					$redirectUrl = $this->getTempUserRedirectUrl(
						$params,
						$result['savedTempUser'] ?? $user
					);
					if ( $redirectUrl ) {
						$r['tempusercreatedredirect'] = $redirectUrl;
					}
				}

				break;

			default:
				if ( !$status->getMessages() ) {
					// EditPage sometimes only sets the status code without setting
					// any actual error messages. Supply defaults for those cases.
					switch ( $statusValue ) {
						// Currently needed
						case EditPage::AS_IMAGE_REDIRECT_ANON:
							$status->fatal( 'apierror-noimageredirect-anon' );
							break;
						case EditPage::AS_IMAGE_REDIRECT_LOGGED:
							$status->fatal( 'apierror-noimageredirect' );
							break;
						case EditPage::AS_READ_ONLY_PAGE_ANON:
							$status->fatal( 'apierror-noedit-anon' );
							break;
						case EditPage::AS_NO_CHANGE_CONTENT_MODEL:
							$status->fatal( 'apierror-cantchangecontentmodel' );
							break;
						case EditPage::AS_ARTICLE_WAS_DELETED:
							$status->fatal( 'apierror-pagedeleted' );
							break;
						case EditPage::AS_CONFLICT_DETECTED:
							$status->fatal( 'edit-conflict' );
							break;

						// Currently shouldn't be needed, but here in case
						// hooks use them without setting appropriate
						// errors on the status.
						// @codeCoverageIgnoreStart
						case EditPage::AS_SPAM_ERROR:
							// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset
							$status->fatal( 'apierror-spamdetected', $result['spam'] );
							break;
						case EditPage::AS_READ_ONLY_PAGE_LOGGED:
							$status->fatal( 'apierror-noedit' );
							break;
						case EditPage::AS_RATE_LIMITED:
							$status->fatal( 'apierror-ratelimited' );
							break;
						case EditPage::AS_NO_CREATE_PERMISSION:
							$status->fatal( 'nocreate-loggedin' );
							break;
						case EditPage::AS_BLANK_ARTICLE:
							$status->fatal( 'apierror-emptypage' );
							break;
						case EditPage::AS_TEXTBOX_EMPTY:
							$status->fatal( 'apierror-emptynewsection' );
							break;
						case EditPage::AS_SUMMARY_NEEDED:
							$status->fatal( 'apierror-summaryrequired' );
							break;
						default:
							wfWarn( __METHOD__ . ": Unknown EditPage code $statusValue with no message" );
							$status->fatal( 'apierror-unknownerror-editpage', $statusValue );
							break;
						// @codeCoverageIgnoreEnd
					}
				}
				$this->dieStatus( $status );
		}
		$apiResult->addValue( null, $this->getModuleName(), $r );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$params = [
			'title' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'pageid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'section' => null,
			'sectiontitle' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'text' => [
				ParamValidator::PARAM_TYPE => 'text',
			],
			'summary' => null,
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
			'minor' => false,
			'notminor' => false,
			'bot' => false,
			'baserevid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'basetimestamp' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
			],
			'starttimestamp' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
			],
			'recreate' => false,
			'createonly' => false,
			'nocreate' => false,
			'watch' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'unwatch' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
		];

		// Params appear in the docs in the order they are defined,
		// which is why this is here and not at the bottom.
		$params += $this->getWatchlistParams();

		$params += [
			'md5' => null,
			'prependtext' => [
				ParamValidator::PARAM_TYPE => 'text',
			],
			'appendtext' => [
				ParamValidator::PARAM_TYPE => 'text',
			],
			'undo' => [
				ParamValidator::PARAM_TYPE => 'integer',
				IntegerDef::PARAM_MIN => 0,
				ApiBase::PARAM_RANGE_ENFORCE => true,
			],
			'undoafter' => [
				ParamValidator::PARAM_TYPE => 'integer',
				IntegerDef::PARAM_MIN => 0,
				ApiBase::PARAM_RANGE_ENFORCE => true,
			],
			'redirect' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
			],
			'contentformat' => [
				ParamValidator::PARAM_TYPE => $this->contentHandlerFactory->getAllContentFormats(),
			],
			'contentmodel' => [
				ParamValidator::PARAM_TYPE => $this->contentHandlerFactory->getContentModels(),
			],
			'token' => [
				// Standard definition automatically inserted
				ApiBase::PARAM_HELP_MSG_APPEND => [ 'apihelp-edit-param-token' ],
			],
		];

		$params += $this->getCreateTempUserParams();

		return $params;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=edit&title=Test&summary=test%20summary&' .
				'text=article%20content&baserevid=1234567&token=123ABC'
				=> 'apihelp-edit-example-edit',
			'action=edit&title=Test&summary=NOTOC&minor=&' .
				'prependtext=__NOTOC__%0A&basetimestamp=2007-08-24T12:34:54Z&token=123ABC'
				=> 'apihelp-edit-example-prepend',
			'action=edit&title=Test&undo=13585&undoafter=13579&' .
				'basetimestamp=2007-08-24T12:34:54Z&token=123ABC'
				=> 'apihelp-edit-example-undo',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Edit';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiEditPage::class, 'ApiEditPage' );
