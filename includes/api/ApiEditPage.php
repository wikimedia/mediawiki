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

		if ( is_null( $params['text'] ) && is_null( $params['appendtext'] ) &&
			is_null( $params['prependtext'] ) &&
			$params['undo'] == 0
		) {
			$this->dieUsageMsg( 'missingtext' );
		}

		$pageObj = $this->getTitleOrPageId( $params );
		$titleObj = $pageObj->getTitle();
		$apiResult = $this->getResult();

		if ( $params['redirect'] ) {
			if ( $params['prependtext'] === null && $params['appendtext'] === null
				&& $params['section'] !== 'new'
			) {
				$this->dieUsage( 'You have attempted to edit using the "redirect"-following'
					. ' mode, which must be used in conjuction with section=new, prependtext'
					. ', or appendtext.', 'redirect-appendonly' );
			}
			if ( $titleObj->isRedirect() ) {
				$oldTitle = $titleObj;

				$titles = Revision::newFromTitle( $oldTitle, false, Revision::READ_LATEST )
					->getContent( Revision::FOR_THIS_USER, $user )
					->getRedirectChain();
				// array_shift( $titles );

				$redirValues = array();

				/** @var $newTitle Title */
				foreach ( $titles as $id => $newTitle ) {

					if ( !isset( $titles[$id - 1] ) ) {
						$titles[$id - 1] = $oldTitle;
					}

					$redirValues[] = array(
						'from' => $titles[$id - 1]->getPrefixedText(),
						'to' => $newTitle->getPrefixedText()
					);

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

		$name = $titleObj->getPrefixedDBkey();
		$model = $contentHandler->getModelID();
		if ( $contentHandler->supportsDirectApiEditing() === false ) {
			$this->dieUsage(
				"Direct editing via API is not supported for content model $model used by $name",
				'no-direct-editing'
			);
		}

		if ( !isset( $params['contentformat'] ) || $params['contentformat'] == '' ) {
			$params['contentformat'] = $contentHandler->getDefaultFormat();
		}

		$contentFormat = $params['contentformat'];

		if ( !$contentHandler->isSupportedFormat( $contentFormat ) ) {

			$this->dieUsage( "The requested format $contentFormat is not supported for content model " .
				" $model used by $name", 'badformat' );
		}

		if ( $params['createonly'] && $titleObj->exists() ) {
			$this->dieUsageMsg( 'createonly-exists' );
		}
		if ( $params['nocreate'] && !$titleObj->exists() ) {
			$this->dieUsageMsg( 'nocreate-missing' );
		}

		// Now let's check whether we're even allowed to do this
		$errors = $titleObj->getUserPermissionsErrors( 'edit', $user );
		if ( !$titleObj->exists() ) {
			$errors = array_merge( $errors, $titleObj->getUserPermissionsErrors( 'create', $user ) );
		}
		if ( count( $errors ) ) {
			if ( is_array( $errors[0] ) ) {
				switch ( $errors[0][0] ) {
					case 'blockedtext':
						$this->dieUsage(
							'You have been blocked from editing',
							'blocked',
							0,
							array( 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $user->getBlock() ) )
						);
						break;
					case 'autoblockedtext':
						$this->dieUsage(
							'Your IP address has been blocked automatically, because it was used by a blocked user',
							'autoblocked',
							0,
							array( 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $user->getBlock() ) )
						);
						break;
					default:
						$this->dieUsageMsg( $errors[0] );
				}
			} else {
				$this->dieUsageMsg( $errors[0] );
			}
		}

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
						$this->dieUsage( $ex->getMessage(), 'parseerror' );

						return;
					}
				} else {
					# Otherwise, make a new empty content.
					$content = $contentHandler->makeEmptyContent();
				}
			}

			// @todo Add support for appending/prepending to the Content interface

			if ( !( $content instanceof TextContent ) ) {
				$mode = $contentHandler->getModelID();
				$this->dieUsage( "Can't append to pages using content model $mode", 'appendnotsupported' );
			}

			if ( !is_null( $params['section'] ) ) {
				if ( !$contentHandler->supportsSections() ) {
					$modelName = $contentHandler->getModelID();
					$this->dieUsage(
						"Sections are not supported for this content model: $modelName.",
						'sectionsnotsupported'
					);
				}

				if ( $params['section'] == 'new' ) {
					// DWIM if they're trying to prepend/append to a new section.
					$content = null;
				} else {
					// Process the content for section edits
					$section = $params['section'];
					$content = $content->getSection( $section );

					if ( !$content ) {
						$this->dieUsage( "There is no section {$section}.", 'nosuchsection' );
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
						array( $params['undoafter'], $params['undo'] );
				}
				$undoafterRev = Revision::newFromId( $params['undoafter'] );
			}
			$undoRev = Revision::newFromId( $params['undo'] );
			if ( is_null( $undoRev ) || $undoRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->dieUsageMsg( array( 'nosuchrevid', $params['undo'] ) );
			}

			if ( $params['undoafter'] == 0 ) {
				$undoafterRev = $undoRev->getPrevious();
			}
			if ( is_null( $undoafterRev ) || $undoafterRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->dieUsageMsg( array( 'nosuchrevid', $params['undoafter'] ) );
			}

			if ( $undoRev->getPage() != $pageObj->getID() ) {
				$this->dieUsageMsg( array( 'revwrongpage', $undoRev->getID(),
					$titleObj->getPrefixedText() ) );
			}
			if ( $undoafterRev->getPage() != $pageObj->getID() ) {
				$this->dieUsageMsg( array( 'revwrongpage', $undoafterRev->getID(),
					$titleObj->getPrefixedText() ) );
			}

			$newContent = $contentHandler->getUndoContent(
				$pageObj->getRevision(),
				$undoRev,
				$undoafterRev
			);

			if ( !$newContent ) {
				$this->dieUsageMsg( 'undo-failure' );
			}

			$params['text'] = $newContent->serialize( $params['contentformat'] );

			// If no summary was given and we only undid one rev,
			// use an autosummary
			if ( is_null( $params['summary'] ) &&
				$titleObj->getNextRevisionID( $undoafterRev->getID() ) == $params['undo']
			) {
				$params['summary'] = wfMessage( 'undo-summary' )
					->params( $params['undo'], $undoRev->getUserText() )->inContentLanguage()->text();
			}
		}

		// See if the MD5 hash checks out
		if ( !is_null( $params['md5'] ) && md5( $toMD5 ) !== $params['md5'] ) {
			$this->dieUsageMsg( 'hashcheckfailed' );
		}

		// EditPage wants to parse its stuff from a WebRequest
		// That interface kind of sucks, but it's workable
		$requestArray = array(
			'wpTextbox1' => $params['text'],
			'format' => $contentFormat,
			'model' => $contentHandler->getModelID(),
			'wpEditToken' => $params['token'],
			'wpIgnoreBlankSummary' => true,
			'wpIgnoreBlankArticle' => true,
			'wpIgnoreSelfRedirect' => true,
			'bot' => $params['bot'],
		);

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
				$this->dieUsage( "The section parameter must be a valid section id or 'new'",
					"invalidsection" );
			}
			$content = $pageObj->getContent();
			if ( $section !== '0' && $section != 'new'
				&& ( !$content || !$content->getSection( $section ) )
			) {
				$this->dieUsage( "There is no section {$section}.", 'nosuchsection' );
			}
			$requestArray['wpSection'] = $params['section'];
		} else {
			$requestArray['wpSection'] = '';
		}

		$watch = $this->getWatchlistValue( $params['watchlist'], $titleObj );

		// Deprecated parameters
		if ( $params['watch'] ) {
			$this->logFeatureUsage( 'action=edit&watch' );
			$watch = true;
		} elseif ( $params['unwatch'] ) {
			$this->logFeatureUsage( 'action=edit&unwatch' );
			$watch = false;
		}

		if ( $watch ) {
			$requestArray['wpWatchthis'] = '';
		}

		// Apply change tags
		if ( count( $params['tags'] ) ) {
			if ( $user->isAllowed( 'applychangetags' ) ) {
				$requestArray['wpChangeTags'] = implode( ',', $params['tags'] );
			} else {
				$this->dieUsage( 'You don\'t have permission to set change tags.', 'taggingnotallowed' );
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

		// The following is needed to give the hook the full content of the
		// new revision rather than just the current section. (Bug 52077)
		if ( !is_null( $params['section'] ) &&
			$contentHandler->supportsSections() && $titleObj->exists()
		) {
			// If sectiontitle is set, use it, otherwise use the summary as the section title (for
			// backwards compatibility with old forms/bots).
			if ( $ep->sectiontitle !== '' ) {
				$sectionTitle = $ep->sectiontitle;
			} else {
				$sectionTitle = $ep->summary;
			}

			$contentObj = $contentHandler->unserializeContent( $content, $contentFormat );

			$fullContentObj = $articleObject->replaceSectionContent(
				$params['section'],
				$contentObj,
				$sectionTitle
			);
			if ( $fullContentObj ) {
				$content = $fullContentObj->serialize( $contentFormat );
			} else {
				// This most likely means we have an edit conflict which means that the edit
				// wont succeed anyway.
				$this->dieUsageMsg( 'editconflict' );
			}
		}

		// Run hooks
		// Handle APIEditBeforeSave parameters
		$r = array();
		if ( !Hooks::run( 'APIEditBeforeSave', array( $ep, $content, &$r ) ) ) {
			if ( count( $r ) ) {
				$r['result'] = 'Failure';
				$apiResult->addValue( null, $this->getModuleName(), $r );

				return;
			}

			$this->dieUsageMsg( 'hookaborted' );
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
				} else {
					$this->dieUsageMsg( 'hookaborted' );
				}

			case EditPage::AS_PARSE_ERROR:
				$this->dieUsage( $status->getMessage(), 'parseerror' );

			case EditPage::AS_IMAGE_REDIRECT_ANON:
				$this->dieUsageMsg( 'noimageredirect-anon' );

			case EditPage::AS_IMAGE_REDIRECT_LOGGED:
				$this->dieUsageMsg( 'noimageredirect-logged' );

			case EditPage::AS_SPAM_ERROR:
				$this->dieUsageMsg( array( 'spamdetected', $result['spam'] ) );

			case EditPage::AS_BLOCKED_PAGE_FOR_USER:
				$this->dieUsage(
					'You have been blocked from editing',
					'blocked',
					0,
					array( 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $user->getBlock() ) )
				);

			case EditPage::AS_MAX_ARTICLE_SIZE_EXCEEDED:
			case EditPage::AS_CONTENT_TOO_BIG:
				$this->dieUsageMsg( array( 'contenttoobig', $this->getConfig()->get( 'MaxArticleSize' ) ) );

			case EditPage::AS_READ_ONLY_PAGE_ANON:
				$this->dieUsageMsg( 'noedit-anon' );

			case EditPage::AS_READ_ONLY_PAGE_LOGGED:
				$this->dieUsageMsg( 'noedit' );

			case EditPage::AS_READ_ONLY_PAGE:
				$this->dieReadOnly();

			case EditPage::AS_RATE_LIMITED:
				$this->dieUsageMsg( 'actionthrottledtext' );

			case EditPage::AS_ARTICLE_WAS_DELETED:
				$this->dieUsageMsg( 'wasdeleted' );

			case EditPage::AS_NO_CREATE_PERMISSION:
				$this->dieUsageMsg( 'nocreate-loggedin' );

			case EditPage::AS_NO_CHANGE_CONTENT_MODEL:
				$this->dieUsageMsg( 'cantchangecontentmodel' );

			case EditPage::AS_BLANK_ARTICLE:
				$this->dieUsageMsg( 'blankpage' );

			case EditPage::AS_CONFLICT_DETECTED:
				$this->dieUsageMsg( 'editconflict' );

			case EditPage::AS_TEXTBOX_EMPTY:
				$this->dieUsageMsg( 'emptynewsection' );

			case EditPage::AS_CHANGE_TAG_ERROR:
				$this->dieStatus( $status );

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

			case EditPage::AS_SUMMARY_NEEDED:
				// Shouldn't happen since we set wpIgnoreBlankSummary, but just in case
				$this->dieUsageMsg( 'summaryrequired' );

			case EditPage::AS_END:
			default:
				// $status came from WikiPage::doEdit()
				$errors = $status->getErrorsArray();
				$this->dieUsageMsg( $errors[0] ); // TODO: Add new errors to message map
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
		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'pageid' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'section' => null,
			'sectiontitle' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'text' => array(
				ApiBase::PARAM_TYPE => 'text',
			),
			'summary' => null,
			'tags' => array(
				ApiBase::PARAM_TYPE => ChangeTags::listExplicitlyDefinedTags(),
				ApiBase::PARAM_ISMULTI => true,
			),
			'minor' => false,
			'notminor' => false,
			'bot' => false,
			'basetimestamp' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
			),
			'starttimestamp' => array(
				ApiBase::PARAM_TYPE => 'timestamp',
			),
			'recreate' => false,
			'createonly' => false,
			'nocreate' => false,
			'watch' => array(
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'unwatch' => array(
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'watchlist' => array(
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => array(
					'watch',
					'unwatch',
					'preferences',
					'nochange'
				),
			),
			'md5' => null,
			'prependtext' => array(
				ApiBase::PARAM_TYPE => 'text',
			),
			'appendtext' => array(
				ApiBase::PARAM_TYPE => 'text',
			),
			'undo' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'undoafter' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'redirect' => array(
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DFLT => false,
			),
			'contentformat' => array(
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
			),
			'contentmodel' => array(
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
			),
			'token' => array(
				// Standard definition automatically inserted
				ApiBase::PARAM_HELP_MSG_APPEND => array( 'apihelp-edit-param-token' ),
			),
		);
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=edit&title=Test&summary=test%20summary&' .
				'text=article%20content&basetimestamp=2007-08-24T12:34:54Z&token=123ABC'
				=> 'apihelp-edit-example-edit',
			'action=edit&title=Test&summary=NOTOC&minor=&' .
				'prependtext=__NOTOC__%0A&basetimestamp=2007-08-24T12:34:54Z&token=123ABC'
				=> 'apihelp-edit-example-prepend',
			'action=edit&title=Test&undo=13585&undoafter=13579&' .
				'basetimestamp=2007-08-24T12:34:54Z&token=123ABC'
				=> 'apihelp-edit-example-undo',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Edit';
	}
}
