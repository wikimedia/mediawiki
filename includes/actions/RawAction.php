<?php
/**
 * Raw page text accessor
 *
 * Copyright © 2004 Gabriel Wicke <wicke@wikidev.net>
 * http://wikidev.net/
 *
 * Based on HistoryAction and SpecialExport
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
 * @author Gabriel Wicke <wicke@wikidev.net>
 * @file
 */

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;

/**
 * A simple method to retrieve the plain source of an article,
 * using "action=raw" in the GET request string.
 *
 * @ingroup Actions
 */
class RawAction extends FormlessAction {

	/** @var HookRunner */
	private $hookRunner;

	/** @var Parser */
	private $parser;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var RevisionLookup */
	private $revisionLookup;

	/**
	 * @param Page $page
	 * @param IContextSource $context
	 * @param HookContainer $hookContainer
	 * @param Parser $parser
	 * @param PermissionManager $permissionManager
	 * @param RevisionLookup $revisionLookup
	 */
	public function __construct(
		Page $page,
		IContextSource $context,
		HookContainer $hookContainer,
		Parser $parser,
		PermissionManager $permissionManager,
		RevisionLookup $revisionLookup
	) {
		parent::__construct( $page, $context );
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->parser = $parser;
		$this->permissionManager = $permissionManager;
		$this->revisionLookup = $revisionLookup;
	}

	public function getName() {
		return 'raw';
	}

	public function requiresWrite() {
		return false;
	}

	public function requiresUnblock() {
		return false;
	}

	/**
	 * @suppress SecurityCheck-XSS Non html mime type
	 * @return string|null
	 */
	public function onView() {
		$this->getOutput()->disable();
		$request = $this->getRequest();
		$response = $request->response();
		$config = $this->context->getConfig();

		if ( $this->getOutput()->checkLastModified(
			$this->getWikiPage()->getTouched()
		) ) {
			return null; // Client cache fresh and headers sent, nothing more to do.
		}

		$contentType = $this->getContentType();

		$maxage = $request->getInt( 'maxage', $config->get( 'CdnMaxAge' ) );
		$smaxage = $request->getIntOrNull( 'smaxage' );
		if ( $smaxage === null ) {
			if (
				$contentType == 'text/css' ||
				$contentType == 'application/json' ||
				$contentType == 'text/javascript'
			) {
				// CSS/JSON/JS raw content has its own CDN max age configuration.
				// Note: HtmlCacheUpdater::getUrls() includes action=raw for css/json/js
				// pages, so if using the canonical url, this will get HTCP purges.
				$smaxage = intval( $config->get( 'ForcedRawSMaxage' ) );
			} else {
				// No CDN cache for anything else
				$smaxage = 0;
			}
		}

		// Set standard Vary headers so cache varies on cookies and such (T125283)
		$response->header( $this->getOutput()->getVaryHeader() );

		// Output may contain user-specific data;
		// vary generated content for open sessions on private wikis
		$privateCache = !$this->permissionManager->isEveryoneAllowed( 'read' ) &&
			( $smaxage == 0 || MediaWiki\Session\SessionManager::getGlobalSession()->isPersistent() );
		// Don't accidentally cache cookies if user is registered (T55032)
		$privateCache = $privateCache || $this->getUser()->isRegistered();
		$mode = $privateCache ? 'private' : 'public';
		$response->header(
			'Cache-Control: ' . $mode . ', s-maxage=' . $smaxage . ', max-age=' . $maxage
		);

		// In the event of user JS, don't allow loading a user JS/CSS/Json
		// subpage that has no registered user associated with, as
		// someone could register the account and take control of the
		// JS/CSS/Json page.
		$title = $this->getTitle();
		if ( $title->isUserConfigPage() && $contentType !== 'text/x-wiki' ) {
			// not using getRootText() as we want this to work
			// even if subpages are disabled.
			$rootPage = strtok( $title->getText(), '/' );
			$userFromTitle = User::newFromName( $rootPage, 'usable' );
			if ( !$userFromTitle || $userFromTitle->getId() === 0 ) {
				$elevated = $this->getContext()->getAuthority()->isAllowed( 'editinterface' );
				$elevatedText = $elevated ? 'by elevated ' : '';
				$log = LoggerFactory::getInstance( "security" );
				$log->warning(
					"Unsafe JS/CSS/Json {$elevatedText}load - {user} loaded {title} with {ctype}",
					[
						'user' => $this->getUser()->getName(),
						'title' => $title->getPrefixedDBkey(),
						'ctype' => $contentType,
						'elevated' => $elevated
					]
				);
				throw new HttpError( 403, wfMessage( 'unregistered-user-config' ) );
			}
		}

		// Don't allow loading non-protected pages as javascript.
		// In future we may further restrict this to only CONTENT_MODEL_JAVASCRIPT
		// in NS_MEDIAWIKI or NS_USER, as well as including other config types,
		// but for now be more permissive. Allowing protected pages outside of
		// NS_USER and NS_MEDIAWIKI in particular should be considered a temporary
		// allowance.
		if (
			$contentType === 'text/javascript' &&
			!$title->isUserJsConfigPage() &&
			!$title->inNamespace( NS_MEDIAWIKI ) &&
			!in_array( 'sysop', $title->getRestrictions( 'edit' ) ) &&
			!in_array( 'editprotected', $title->getRestrictions( 'edit' ) )
		) {

			$log = LoggerFactory::getInstance( "security" );
			$log->info( "Blocked loading unprotected JS {title} for {user}",
				[
					'user' => $this->getUser()->getName(),
					'title' => $title->getPrefixedDBkey(),
				]
			);
			throw new HttpError( 403, wfMessage( 'unprotected-js' ) );
		}

		$response->header( 'Content-type: ' . $contentType . '; charset=UTF-8' );

		$text = $this->getRawText();

		// Don't return a 404 response for CSS or JavaScript;
		// 404s aren't generally cached and it would create
		// extra hits when user CSS/JS are on and the user doesn't
		// have the pages.
		if ( $text === false && $contentType == 'text/x-wiki' ) {
			$response->statusHeader( 404 );
		}

		if ( !$this->hookRunner->onRawPageViewBeforeOutput( $this, $text ) ) {
			wfDebug( __METHOD__ . ": RawPageViewBeforeOutput hook broke raw page output." );
		}

		echo $text;

		return null;
	}

	/**
	 * Get the text that should be returned, or false if the page or revision
	 * was not found.
	 *
	 * @return string|bool
	 */
	public function getRawText() {
		$text = false;
		$title = $this->getTitle();
		$request = $this->getRequest();

		// Get it from the DB
		$rev = $this->revisionLookup->getRevisionByTitle( $title, $this->getOldId() );
		if ( $rev ) {
			$lastmod = wfTimestamp( TS_RFC2822, $rev->getTimestamp() );
			$request->response()->header( "Last-modified: $lastmod" );

			// Public-only due to cache headers
			$content = $rev->getContent( SlotRecord::MAIN );

			if ( $content === null ) {
				// revision not found (or suppressed)
			} elseif ( !$content instanceof TextContent ) {
				// non-text content
				wfHttpError( 415, "Unsupported Media Type", "The requested page uses the content model `"
					. $content->getModel() . "` which is not supported via this interface." );
				die();
			} else {
				// want a section?
				$section = $request->getIntOrNull( 'section' );
				if ( $section !== null ) {
					$content = $content->getSection( $section );
				}

				if ( $content === null || $content === false ) {
					// section not found (or section not supported, e.g. for JS, JSON, and CSS)
				} else {
					$text = $content->getText();
				}
			}
		}

		if ( $text !== false && $text !== '' && $request->getRawVal( 'templates' ) === 'expand' ) {
			$text = $this->parser->preprocess(
				$text,
				$title,
				ParserOptions::newFromContext( $this->getContext() )
			);
		}

		return $text;
	}

	/**
	 * Get the ID of the revision that should used to get the text.
	 *
	 * @return int
	 */
	public function getOldId() {
		$oldid = $this->getRequest()->getInt( 'oldid' );
		$rl = $this->revisionLookup;
		switch ( $this->getRequest()->getText( 'direction' ) ) {
			case 'next':
				# output next revision, or nothing if there isn't one
				$nextRev = null;
				if ( $oldid ) {
					$oldRev = $rl->getRevisionById( $oldid );
					if ( $oldRev ) {
						$nextRev = $rl->getNextRevision( $oldRev );
					}
				}
				$oldid = $nextRev ? $nextRev->getId() : -1;
				break;
			case 'prev':
				# output previous revision, or nothing if there isn't one
				$prevRev = null;
				if ( !$oldid ) {
					# get the current revision so we can get the penultimate one
					$oldid = $this->getWikiPage()->getLatest();
				}
				$oldRev = $rl->getRevisionById( $oldid );
				if ( $oldRev ) {
					$prevRev = $rl->getPreviousRevision( $oldRev );
				}
				$oldid = $prevRev ? $prevRev->getId() : -1;
				break;
			case 'cur':
				$oldid = 0;
				break;
		}

		return $oldid;
	}

	/**
	 * Get the content type to use for the response
	 *
	 * @return string
	 */
	public function getContentType() {
		// Optimisation: Avoid slow getVal(), this isn't user-generated content.
		$ctype = $this->getRequest()->getRawVal( 'ctype' );

		if ( $ctype == '' ) {
			// Legacy compatibilty
			$gen = $this->getRequest()->getRawVal( 'gen' );
			if ( $gen == 'js' ) {
				$ctype = 'text/javascript';
			} elseif ( $gen == 'css' ) {
				$ctype = 'text/css';
			}
		}

		$allowedCTypes = [
			'text/x-wiki',
			'text/javascript',
			'text/css',
			// FIXME: Should we still allow Zope editing? External editing feature was dropped
			'application/x-zope-edit',
			'application/json'
		];
		if ( $ctype == '' || !in_array( $ctype, $allowedCTypes ) ) {
			$ctype = 'text/x-wiki';
		}

		return $ctype;
	}
}
