<?php
/**
 *
 *
 * Created on August 16, 2007
 *
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

/**
 * A module that allows for editing and creating pages.
 *
 * Currently, this wraps around the EditPage class in an ugly way,
 * EditPage.php should be rewritten to provide a cleaner interface,
 * see T20654 if you're inspired to fix this.
 *
 * @ingroup API
 */
class ApiEditPage extends ApiBase {
	public function execute() {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$this->requireAtLeastOneParameter( $params, 'text', 'appendtext', 'prependtext', 'undo' );

		$pageObj = $this->getTitleOrPageId( $params );
		$titleObj = $pageObj->getTitle();
		$apiResult = $this->getResult();

		if ( $params['redirect'] ) {
			if ( $params['prependtext'] === null && $params['appendtext'] === null
				&& $params['section'] !== 'new'
			) {
				$this->dieWithError( 'apierror-redirect-appendonly' );
			}
			if ( $titleObj->isRedirect() ) {
				$oldTitle = $titleObj;

				$titles = Revision::newFromTitle( $oldTitle, false, Revision::READ_LATEST )
					->getContent( Revision::FOR_THIS_USER, $user )
					->getRedirectChain();
				// array_shift( $titles );

				$redirValues = [];

				/** @var $newTitle Title */
				foreach ( $titles as $id => $newTitle ) {

					if ( !isset( $titles[$id - 1] ) ) {
						$titles[$id - 1] = $oldTitle;
					}

					$redirValues[] = [
						'from' => $titles[$id - 1]->getPrefixedText(),
						'to' => $newTitle->getPrefixedText()
					];

					$titleObj = $newTitle;
				}

				ApiResult::setIndexedTagName( $redirValues, 'r' );
				$apiResult->addValue( null, 'redirects', $redirValues );

				// Since the page changed, update $pageObj
				$pageObj = WikiPage::factory( $titleObj );
			}
		}

		if ( !isset( $params['contentmodel'] ) || $params['contentmodel'] == '' ) {
			$contentHandler = $pageObj->getContentHandler();
		} else {
			$contentHandler = ContentHandler::getForModelID( $params['contentmodel'] );
		}
		$contentModel = $contentHandler->getModelID();

		$name = $titleObj->getPrefixedDBkey();
		$model = $contentHandler->getModelID();

		if ( $params['undo'] > 0 ) {
			// allow undo via api
		} elseif ( $contentHandler->supportsDirectApiEditing() === false ) {
			$this->dieWithError( [ 'apierror-no-direct-editing', $model, $name ] );
		}

		if ( !isset( $params['contentformat'] ) || $params['contentformat'] == '' ) {
			$contentFormat = $contentHandler->getDefaultFormat();
		} else {
			$contentFormat = $params['contentformat'];
		}

		if ( !$contentHandler->isSupportedFormat( $contentFormat ) ) {
			$this->dieWithError( [ 'apierror-badformat', $contentFormat, $model, $name ] );
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
			$titleObj->exists() ? 'edit' : [ 'edit', 'create' ]
		);

		$toMD5 = $params['text'];
		if ( !is_null( $params['appendtext'] ) || !is_null( $params['prependtext'] ) ) {
			$content = $pageObj->getContent();

			if ( !$content ) {
				if ( $titleObj->getNamespace() == NS_MEDIAWIKI ) {
					# If this is a MediaWiki:x message, then load the messages
					# and return the message value for x.
					$text = $titleObj->getDefaultMessageText();
					if ( $text === false ) {
						$text = '';
					}

					try {
						$content = ContentHandler::makeContent( $text, $this->getTitle() );
					} catch ( MWContentSerializationException $ex ) {
						$this->dieWithException( $ex, [
							'wrap' => ApiMessage::create( 'apierror-contentserializationexception', 'parseerror' )
						] );
						return;
					}
				} else {
					# Otherwise, make a new empty content.
					$content = $contentHandler->makeEmptyContent();
				}
			}

			// @todo Add support for appending/prepending to the Content interface

			if ( !( $content instanceof TextContent ) ) {
				$modelName = $contentHandler->getModelID();
				$this->dieWithError( [ 'apierror-appendnotsupported', $modelName ] );
			}

			if ( !is_null( $params['section'] ) ) {
				if ( !$contentHandler->supportsSections() ) {
					$modelName = $contentHandler->getModelID();
					$this->dieWithError( [ 'apierror-sectionsnotsupported', $modelName ] );
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
			if ( $params['undoafter'] > 0 ) {
				if ( $params['undo'] < $params['undoafter'] ) {
					list( $params['undo'], $params['undoafter'] ) =
						[ $params['undoafter'], $params['undo'] ];
				}
				$undoafterRev = Revision::newFromId( $params['undoafter'] );
			}
			$undoRev = Revision::newFromId( $params['undo'] );
			if ( is_null( $undoRev ) || $undoRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $params['undo'] ] );
			}

			if ( $params['undoafter'] == 0 ) {
				$undoafterRev = $undoRev->getPrevious();
			}
			if ( is_null( $undoafterRev ) || $undoafterRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $params['undoafter'] ] );
			}

			if ( $undoRev->getPage() != $pageObj->getId() ) {
				$this->dieWithError( [ 'apierror-revwrongpage', $undoRev->getId(),
					$titleObj->getPrefixedText() ] );
			}
			if ( $undoafterRev->getPage() != $pageObj->getId() ) {
				$this->dieWithError( [ 'apierror-revwrongpage', $undoafterRev->getId(),
					$titleObj->getPrefixedText() ] );
			}

			$newContent = $contentHandler->getUndoContent(
				$pageObj->getRevision(),
				$undoRev,
				$undoafterRev
			);

			if ( !$newContent ) {
				$this->dieWithError( 'undo-failure', 'undofailure' );
			}
			if ( empty( $params['contentmodel'] )
				&& empty( $params['contentformat'] )
			) {
				// If we are reverting content model, the new content model
				// might not support the current serialization format, in
				// which case go back to the old serialization format,
				// but only if the user hasn't specified a format/model
				// parameter.
				if ( !$newContent->isSupportedFormat( $contentFormat ) ) {
					$contentFormat = $undoafterRev->getContentFormat();
				}
				// Override content model with model of undid revision.
				$contentModel = $newContent->getModel();
			}
			$params['text'] = $newContent->serialize( $contentFormat );
			// If no summary was given and we only undid one rev,
			// use an autosummary
			if ( is_null( $params['summary'] ) &&
				$titleObj->getNextRevisionID( $undoafterRev->getId() ) == $params['undo']
			) {
				$params['summary'] = wfMessage( 'undo-summary' )
					->params( $params['undo'], $undoRev->getUserText() )->inContentLanguage()->text();
			}
		}

		// See if the MD5 hash checks out
		if ( !is_null( $params['md5'] ) && md5( $toMD5 ) !== $params['md5'] ) {
			$this->dieWithError( 'apierror-badmd5' );
		}

		// EditPage wants to parse its stuff from a WebRequest
		// That interface kind of sucks, but it's workable
		$requestArray = [
			'wpTextbox1' => $params['text'],
			'format' => $contentFormat,
			'model' => $contentModel,
			'wpEditToken' => $params['token'],
			'wpIgnoreBlankSummary' => true,
			'wpIgnoreBlankArticle' => true,
			'wpIgnoreSelfRedirect' => true,
			'bot' => $params['bot'],
		];

		if ( !is_null( $params['summary'] ) ) {
			$requestArray['wpSummary'] = $params['summary'];
		}

		if ( !is_null( $params['sectiontitle'] ) ) {
			$requestArray['wpSectionTitle'] = $params['sectiontitle'];
		}

		// TODO: Pass along information from 'undoafter' as well
		if ( $params['undo'] > 0 ) {
			$requestArray['wpUndidRevision'] = $params['undo'];
		}

		// Watch out for basetimestamp == '' or '0'
		// It gets treated as NOW, almost certainly causing an edit conflict
		if ( $params['basetimestamp'] !== null && (bool)$this->getMain()->getVal( 'basetimestamp' ) ) {
			$requestArray['wpEdittime'] = $params['basetimestamp'];
		} else {
			$requestArray['wpEdittime'] = $pageObj->getTimestamp();
		}

		if ( $params['starttimestamp'] !== null ) {
			$requestArray['wpStarttime'] = $params['starttimestamp'];
		} else {
			$requestArray['wpStarttime'] = wfTimestampNow(); // Fake wpStartime
		}

		if ( $params['minor'] || ( !$params['notminor'] && $user->getOption( 'minordefault' ) ) ) {
			$requestArray['wpMinoredit'] = '';
		}

		if ( $params['recreate'] ) {
			$requestArray['wpRecreate'] = '';
		}

		if ( !is_null( $params['section'] ) ) {
			$section = $params['section'];
			if ( !preg_match( '/^((T-)?\d+|new)$/', $section ) ) {
				$this->dieWithError( 'apierror-invalidsection' );
			}
			$content = $pageObj->getContent();
			if ( $section !== '0' && $section != 'new'
				&& ( !$content || !$content->getSection( $section ) )
			) {
				$this->dieWithError( [ 'apierror-nosuchsection', $section ] );
			}
			$requestArray['wpSection'] = $params['section'];
		} else {
			$requestArray['wpSection'] = '';
		}

		$watch = $this->getWatchlistValue( $params['watchlist'], $titleObj );

		// Deprecated parameters
		if ( $params['watch'] ) {
			$watch = true;
		} elseif ( $params['unwatch'] ) {
			$watch = false;
		}

		if ( $watch ) {
			$requestArray['wpWatchthis'] = '';
		}

		// Apply change tags
		if ( count( $params['tags'] ) ) {
			$tagStatus = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $user );
			if ( $tagStatus->isOK() ) {
				$requestArray['wpChangeTags'] = implode( ',', $params['tags'] );
			} else {
				$this->dieStatus( $tagStatus );
			}
		}

		// Pass through anything else we might have been given, to support extensions
		// This is kind of a hack but it's the best we can do to make extensions work
		$requestArray += $this->getRequest()->getValues();

		global $wgTitle, $wgRequest;

		$req = new DerivativeRequest( $this->getRequest(), $requestArray, true );

		// Some functions depend on $wgTitle == $ep->mTitle
		// TODO: Make them not or check if they still do
		$wgTitle = $titleObj;

		$articleContext = new RequestContext;
		$articleContext->setRequest( $req );
		$articleContext->setWikiPage( $pageObj );
		$articleContext->setUser( $this->getUser() );

		/** @var $articleObject Article */
		$articleObject = Article::newFromWikiPage( $pageObj, $articleContext );

		$ep = new EditPage( $articleObject );

		$ep->setApiEditOverride( true );
		$ep->setContextTitle( $titleObj );
		$ep->importFormData( $req );
		$content = $ep->textbox1;

		// Run hooks
		// Handle APIEditBeforeSave parameters
		$r = [];
		// Deprecated in favour of EditFilterMergedContent
		if ( !Hooks::run( 'APIEditBeforeSave', [ $ep, $content, &$r ], '1.28' ) ) {
			if ( count( $r ) ) {
				$r['result'] = 'Failure';
				$apiResult->addValue( null, $this->getModuleName(), $r );

				return;
			}

			$this->dieWithError( 'hookaborted' );
		}

		// Do the actual save
		$oldRevId = $articleObject->getRevIdFetched();
		$result = null;
		// Fake $wgRequest for some hooks inside EditPage
		// @todo FIXME: This interface SUCKS
		$oldRequest = $wgRequest;
		$wgRequest = $req;

		$status = $ep->attemptSave( $result );
		$wgRequest = $oldRequest;

		switch ( $status->value ) {
			case EditPage::AS_HOOK_ERROR:
			case EditPage::AS_HOOK_ERROR_EXPECTED:
				if ( isset( $status->apiHookResult ) ) {
					$r = $status->apiHookResult;
					$r['result'] = 'Failure';
					$apiResult->addValue( null, $this->getModuleName(), $r );
					return;
				}
				if ( !$status->getErrors() ) {
					$status->fatal( 'hookaborted' );
				}
				$this->dieStatus( $status );

			case EditPage::AS_BLOCKED_PAGE_FOR_USER:
				$this->dieWithError(
					'apierror-blocked',
					'blocked',
					[ 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $user->getBlock() ) ]
				);

			case EditPage::AS_READ_ONLY_PAGE:
				$this->dieReadOnly();

			case EditPage::AS_SUCCESS_NEW_ARTICLE:
				$r['new'] = true;
				// fall-through

			case EditPage::AS_SUCCESS_UPDATE:
				$r['result'] = 'Success';
				$r['pageid'] = intval( $titleObj->getArticleID() );
				$r['title'] = $titleObj->getPrefixedText();
				$r['contentmodel'] = $articleObject->getContentModel();
				$newRevId = $articleObject->getLatest();
				if ( $newRevId == $oldRevId ) {
					$r['nochange'] = true;
				} else {
					$r['oldrevid'] = intval( $oldRevId );
					$r['newrevid'] = intval( $newRevId );
					$r['newtimestamp'] = wfTimestamp( TS_ISO_8601,
						$pageObj->getTimestamp() );
				}
				break;

			default:
				if ( !$status->getErrors() ) {
					// EditPage sometimes only sets the status code without setting
					// any actual error messages. Supply defaults for those cases.
					switch ( $status->value ) {
						// Currently needed
						case EditPage::AS_IMAGE_REDIRECT_ANON:
							$status->fatal( 'apierror-noimageredirect-anon' );
							break;
						case EditPage::AS_IMAGE_REDIRECT_LOGGED:
							$status->fatal( 'apierror-noimageredirect-logged' );
							break;
						case EditPage::AS_CONTENT_TOO_BIG:
						case EditPage::AS_MAX_ARTICLE_SIZE_EXCEEDED:
							$status->fatal( 'apierror-contenttoobig', $this->getConfig()->get( 'MaxArticleSize' ) );
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
							$status->fatal( 'editconflict' );
							break;

						// Currently shouldn't be needed, but here in case
						// hooks use them without setting appropriate
						// errors on the status.
						case EditPage::AS_SPAM_ERROR:
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
							wfWarn( __METHOD__ . ": Unknown EditPage code {$status->value} with no message" );
							$status->fatal( 'apierror-unknownerror-editpage', $status->value );
							break;
					}
				}
				$this->dieStatus( $status );
				break;
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
		return [
			'title' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'pageid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'section' => null,
			'sectiontitle' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'text' => [
				ApiBase::PARAM_TYPE => 'text',
			],
			'summary' => null,
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
			'minor' => false,
			'notminor' => false,
			'bot' => false,
			'basetimestamp' => [
				ApiBase::PARAM_TYPE => 'timestamp',
			],
			'starttimestamp' => [
				ApiBase::PARAM_TYPE => 'timestamp',
			],
			'recreate' => false,
			'createonly' => false,
			'nocreate' => false,
			'watch' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
			'unwatch' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			],
			'watchlist' => [
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => [
					'watch',
					'unwatch',
					'preferences',
					'nochange'
				],
			],
			'md5' => null,
			'prependtext' => [
				ApiBase::PARAM_TYPE => 'text',
			],
			'appendtext' => [
				ApiBase::PARAM_TYPE => 'text',
			],
			'undo' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'undoafter' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'redirect' => [
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DFLT => false,
			],
			'contentformat' => [
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
			],
			'contentmodel' => [
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
			],
			'token' => [
				// Standard definition automatically inserted
				ApiBase::PARAM_HELP_MSG_APPEND => [ 'apihelp-edit-param-token' ],
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=edit&title=Test&summary=test%20summary&' .
				'text=article%20content&basetimestamp=2007-08-24T12:34:54Z&token=123ABC'
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
