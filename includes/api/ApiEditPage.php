<?php
/**
 *
 *
 * Created on August 16, 2007
 *
 * Copyright © 2007 Iker Labarga <Firstname><Lastname>@gmail.com
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
 * EditPage.php should be rewritten to provide a cleaner interface
 * @ingroup API
 */
class ApiEditPage extends ApiBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName );
	}

	public function execute() {
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		if ( is_null( $params['text'] ) && is_null( $params['appendtext'] ) &&
				is_null( $params['prependtext'] ) &&
				$params['undo'] == 0 )
		{
			$this->dieUsageMsg( 'missingtext' );
		}

		$titleObj = Title::newFromText( $params['title'] );
		if ( !$titleObj || $titleObj->isExternal() ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
		}

		$apiResult = $this->getResult();

		if ( $params['redirect'] ) {
			if ( $titleObj->isRedirect() ) {
				$oldTitle = $titleObj;

				$titles = Title::newFromRedirectArray( Revision::newFromTitle( $oldTitle )->getText( Revision::FOR_THIS_USER ) );
				// array_shift( $titles );

				$redirValues = array();
				foreach ( $titles as $id => $newTitle ) {

					if ( !isset( $titles[ $id - 1 ] ) ) {
						$titles[ $id - 1 ] = $oldTitle;
					}

					$redirValues[] = array(
						'from' => $titles[ $id - 1 ]->getPrefixedText(),
						'to' => $newTitle->getPrefixedText()
					);

					$titleObj = $newTitle;
				}

				$apiResult->setIndexedTagName( $redirValues, 'r' );
				$apiResult->addValue( null, 'redirects', $redirValues );
			}
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
			$this->dieUsageMsg( $errors[0] );
		}

		$articleObj = Article::newFromTitle( $titleObj, $this->getContext() );

		$toMD5 = $params['text'];
		if ( !is_null( $params['appendtext'] ) || !is_null( $params['prependtext'] ) )
		{
			// For non-existent pages, Article::getContent()
			// returns an interface message rather than ''
			// We do want getContent()'s behavior for non-existent
			// MediaWiki: pages, though
			if ( $articleObj->getID() == 0 && $titleObj->getNamespace() != NS_MEDIAWIKI ) {
				$content = '';
			} else {
				$content = $articleObj->getContent();
			}

			if ( !is_null( $params['section'] ) ) {
				// Process the content for section edits
				global $wgParser;
				$section = intval( $params['section'] );
				$content = $wgParser->getSection( $content, $section, false );
				if ( $content === false ) {
					$this->dieUsage( "There is no section {$section}.", 'nosuchsection' );
				}
			}
			$params['text'] = $params['prependtext'] . $content . $params['appendtext'];
			$toMD5 = $params['prependtext'] . $params['appendtext'];
		}

		if ( $params['undo'] > 0 ) {
			if ( $params['undoafter'] > 0 ) {
				if ( $params['undo'] < $params['undoafter'] ) {
					list( $params['undo'], $params['undoafter'] ) =
					array( $params['undoafter'], $params['undo'] );
				}
				$undoafterRev = Revision::newFromID( $params['undoafter'] );
			}
			$undoRev = Revision::newFromID( $params['undo'] );
			if ( is_null( $undoRev ) || $undoRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->dieUsageMsg( array( 'nosuchrevid', $params['undo'] ) );
			}

			if ( $params['undoafter'] == 0 ) {
				$undoafterRev = $undoRev->getPrevious();
			}
			if ( is_null( $undoafterRev ) || $undoafterRev->isDeleted( Revision::DELETED_TEXT ) ) {
				$this->dieUsageMsg( array( 'nosuchrevid', $params['undoafter'] ) );
			}

			if ( $undoRev->getPage() != $articleObj->getID() ) {
				$this->dieUsageMsg( array( 'revwrongpage', $undoRev->getID(), $titleObj->getPrefixedText() ) );
			}
			if ( $undoafterRev->getPage() != $articleObj->getID() ) {
				$this->dieUsageMsg( array( 'revwrongpage', $undoafterRev->getID(), $titleObj->getPrefixedText() ) );
			}

			$newtext = $articleObj->getUndoText( $undoRev, $undoafterRev );
			if ( $newtext === false ) {
				$this->dieUsageMsg( 'undo-failure' );
			}
			$params['text'] = $newtext;
			// If no summary was given and we only undid one rev,
			// use an autosummary
			if ( is_null( $params['summary'] ) && $titleObj->getNextRevisionID( $undoafterRev->getID() ) == $params['undo'] ) {
				$params['summary'] = wfMsgForContent( 'undo-summary', $params['undo'], $undoRev->getUserText() );
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
			'wpEditToken' => $params['token'],
			'wpIgnoreBlankSummary' => ''
		);

		if ( !is_null( $params['summary'] ) ) {
			$requestArray['wpSummary'] = $params['summary'];
		}
		
		if ( !is_null( $params['sectiontitle'] ) ) {
			$requestArray['wpSectionTitle'] = $params['sectiontitle'];
		}

		// Watch out for basetimestamp == ''
		// wfTimestamp() treats it as NOW, almost certainly causing an edit conflict
		if ( !is_null( $params['basetimestamp'] ) && $params['basetimestamp'] != '' ) {
			$requestArray['wpEdittime'] = wfTimestamp( TS_MW, $params['basetimestamp'] );
		} else {
			$requestArray['wpEdittime'] = $articleObj->getTimestamp();
		}

		if ( !is_null( $params['starttimestamp'] ) && $params['starttimestamp'] != '' ) {
			$requestArray['wpStarttime'] = wfTimestamp( TS_MW, $params['starttimestamp'] );
		} else {
			$requestArray['wpStarttime'] = wfTimestampNow();	// Fake wpStartime
		}

		if ( $params['minor'] || ( !$params['notminor'] && $user->getOption( 'minordefault' ) ) )	{
			$requestArray['wpMinoredit'] = '';
		}

		if ( $params['recreate'] ) {
			$requestArray['wpRecreate'] = '';
		}

		if ( !is_null( $params['section'] ) ) {
			$section = intval( $params['section'] );
			if ( $section == 0 && $params['section'] != '0' && $params['section'] != 'new' ) {
				$this->dieUsage( "The section parameter must be set to an integer or 'new'", "invalidsection" );
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

		global $wgTitle, $wgRequest;

		$req = new DerivativeRequest( $this->getRequest(), $requestArray, true );

		// Some functions depend on $wgTitle == $ep->mTitle
		// TODO: Make them not or check if they still do
		$wgTitle = $titleObj;

		$ep = new EditPage( $articleObj );
		$ep->setContextTitle( $titleObj );
		$ep->importFormData( $req );

		// Run hooks
		// Handle APIEditBeforeSave parameters
		$r = array();
		if ( !wfRunHooks( 'APIEditBeforeSave', array( $ep, $ep->textbox1, &$r ) ) ) {
			if ( count( $r ) ) {
				$r['result'] = 'Failure';
				$apiResult->addValue( null, $this->getModuleName(), $r );
				return;
			} else {
				$this->dieUsageMsg( 'hookaborted' );
			}
		}

		// Do the actual save
		$oldRevId = $articleObj->getRevIdFetched();
		$result = null;
		// Fake $wgRequest for some hooks inside EditPage
		// @todo FIXME: This interface SUCKS
		$oldRequest = $wgRequest;
		$wgRequest = $req;

		$status = $ep->internalAttemptSave( $result, $user->isAllowed( 'bot' ) && $params['bot'] );
		$wgRequest = $oldRequest;
		global $wgMaxArticleSize;

		switch( $status->value ) {
			case EditPage::AS_HOOK_ERROR:
			case EditPage::AS_HOOK_ERROR_EXPECTED:
				$this->dieUsageMsg( 'hookaborted' );

			case EditPage::AS_IMAGE_REDIRECT_ANON:
				$this->dieUsageMsg( 'noimageredirect-anon' );

			case EditPage::AS_IMAGE_REDIRECT_LOGGED:
				$this->dieUsageMsg( 'noimageredirect-logged' );

			case EditPage::AS_SPAM_ERROR:
				$this->dieUsageMsg( array( 'spamdetected', $result['spam'] ) );

			case EditPage::AS_FILTERING:
				$this->dieUsageMsg( 'filtered' );

			case EditPage::AS_BLOCKED_PAGE_FOR_USER:
				$this->dieUsageMsg( 'blockedtext' );

			case EditPage::AS_MAX_ARTICLE_SIZE_EXCEEDED:
			case EditPage::AS_CONTENT_TOO_BIG:
				$this->dieUsageMsg( array( 'contenttoobig', $wgMaxArticleSize ) );

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

			case EditPage::AS_BLANK_ARTICLE:
				$this->dieUsageMsg( 'blankpage' );

			case EditPage::AS_CONFLICT_DETECTED:
				$this->dieUsageMsg( 'editconflict' );

			// case EditPage::AS_SUMMARY_NEEDED: Can't happen since we set wpIgnoreBlankSummary
			case EditPage::AS_TEXTBOX_EMPTY:
				$this->dieUsageMsg( 'emptynewsection' );

			case EditPage::AS_SUCCESS_NEW_ARTICLE:
				$r['new'] = '';

			case EditPage::AS_SUCCESS_UPDATE:
				$r['result'] = 'Success';
				$r['pageid'] = intval( $titleObj->getArticleID() );
				$r['title'] = $titleObj->getPrefixedText();
				$newRevId = $articleObj->getLatest();
				if ( $newRevId == $oldRevId ) {
					$r['nochange'] = '';
				} else {
					$r['oldrevid'] = intval( $oldRevId );
					$r['newrevid'] = intval( $newRevId );
					$r['newtimestamp'] = wfTimestamp( TS_ISO_8601,
						$articleObj->getTimestamp() );
				}
				break;

			case EditPage::AS_SUMMARY_NEEDED:
				$this->dieUsageMsg( 'summaryrequired' );

			case EditPage::AS_END:
				// $status came from WikiPage::doEdit()
				$errors = $status->getErrorsArray();
				$this->dieUsageMsg( $errors[0] ); // TODO: Add new errors to message map
				break;
			default:
				if ( is_string( $status->value ) && strlen( $status->value ) ) {
					$this->dieUsage( "An unknown return value was returned by Editpage. The code returned was \"{$status->value}\"" , $status->value );
				} else {
					$this->dieUsageMsg( array( 'unknownerror', $status->value ) );
				}
		}
		$apiResult->addValue( null, $this->getModuleName(), $r );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getDescription() {
		return 'Create and edit pages.';
	}

	public function getPossibleErrors() {
		global $wgMaxArticleSize;

		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingtext' ),
			array( 'invalidtitle', 'title' ),
			array( 'createonly-exists' ),
			array( 'nocreate-missing' ),
			array( 'nosuchrevid', 'undo' ),
			array( 'nosuchrevid', 'undoafter' ),
			array( 'revwrongpage', 'id', 'text' ),
			array( 'undo-failure' ),
			array( 'hashcheckfailed' ),
			array( 'hookaborted' ),
			array( 'noimageredirect-anon' ),
			array( 'noimageredirect-logged' ),
			array( 'spamdetected', 'spam' ),
			array( 'summaryrequired' ),
			array( 'filtered' ),
			array( 'blockedtext' ),
			array( 'contenttoobig', $wgMaxArticleSize ),
			array( 'noedit-anon' ),
			array( 'noedit' ),
			array( 'actionthrottledtext' ),
			array( 'wasdeleted' ),
			array( 'nocreate-loggedin' ),
			array( 'blankpage' ),
			array( 'editconflict' ),
			array( 'emptynewsection' ),
			array( 'unknownerror', 'retval' ),
			array( 'code' => 'nosuchsection', 'info' => 'There is no section section.' ),
			array( 'code' => 'invalidsection', 'info' => 'The section parameter must be set to an integer or \'new\'' ),
			array( 'customcssprotected' ),
			array( 'customjsprotected' ),
		) );
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'section' => null,
			'sectiontitle' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => false,
			),
			'text' => null,
			'token' => null,
			'summary' => null,
			'minor' => false,
			'notminor' => false,
			'bot' => false,
			'basetimestamp' => null,
			'starttimestamp' => null,
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
			'prependtext' => null,
			'appendtext' => null,
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
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'title' => 'Page title',
			'section' => 'Section number. 0 for the top section, \'new\' for a new section',
			'sectiontitle' => 'The title for a new section',
			'text' => 'Page content',
			'token' => array( 'Edit token. You can get one of these through prop=info.',
						'The token should always be sent as the last parameter, or at least, after the text parameter'
			),
			'summary' => 'Edit summary. Also section title when section=new',
			'minor' => 'Minor edit',
			'notminor' => 'Non-minor edit',
			'bot' => 'Mark this edit as bot',
			'basetimestamp' => array( 'Timestamp of the base revision (obtained through prop=revisions&rvprop=timestamp).',
						'Used to detect edit conflicts; leave unset to ignore conflicts.'
			),
			'starttimestamp' => array( 'Timestamp when you obtained the edit token.',
						'Used to detect edit conflicts; leave unset to ignore conflicts'
			),
			'recreate' => 'Override any errors about the article having been deleted in the meantime',
			'createonly' => 'Don\'t edit the page if it exists already',
			'nocreate' => 'Throw an error if the page doesn\'t exist',
			'watch' => 'Add the page to your watchlist',
			'unwatch' => 'Remove the page from your watchlist',
			'watchlist' => 'Unconditionally add or remove the page from your watchlist, use preferences or do not change watch',
			'md5' => array(	"The MD5 hash of the {$p}text parameter, or the {$p}prependtext and {$p}appendtext parameters concatenated.",
					'If set, the edit won\'t be done unless the hash is correct' ),
			'prependtext' => "Add this text to the beginning of the page. Overrides {$p}text",
			'appendtext' => "Add this text to the end of the page. Overrides {$p}text",
			'undo' => "Undo this revision. Overrides {$p}text, {$p}prependtext and {$p}appendtext",
			'undoafter' => 'Undo all revisions from undo to this one. If not set, just undo one revision',
			'redirect' => 'Automatically resolve redirects',
		);
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getExamples() {
		return array(

			'api.php?action=edit&title=Test&summary=test%20summary&text=article%20content&basetimestamp=20070824123454&token=%2B\\'
				=> 'Edit a page (anonymous user)',

			'api.php?action=edit&title=Test&summary=NOTOC&minor=&prependtext=__NOTOC__%0A&basetimestamp=20070824123454&token=%2B\\'
				=> 'Prepend __NOTOC__ to a page (anonymous user)',
			'api.php?action=edit&title=Test&undo=13585&undoafter=13579&basetimestamp=20070824123454&token=%2B\\'
				=> 'Undo r13579 through r13585 with autosummary (anonymous user)',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Edit';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
