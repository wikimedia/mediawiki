<?php
/**
 * Raw page text accessor
 *
 * Copyright © 2004 Gabriel Wicke <wicke@wikidev.net>
 * http://wikidev.net/
 *
 * Based on HistoryAction and SpecialExport
 *
 * @license GPL-2.0-or-later
 * @author Gabriel Wicke <wicke@wikidev.net>
 * @file
 */

namespace MediaWiki\Actions;

use MediaWiki\Content\TextContent;
use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\HttpError;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Article;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * A simple method to retrieve the plain source of an article,
 * using "action=raw" in the GET request string.
 *
 * @ingroup Actions
 */
class RawAction extends FormlessAction {

	/**
	 * Let browsers cache unconditionally for 5 minutes.
	 *
	 * After 5 minutes, browsers must revalidate the resource and get either a quick
	 * HTTP 304 response and renew for another 5min, or a fresh HTTP 200 download.
	 *
	 * This is designed to match ResourceLoader ($wgResourceLoaderMaxage)
	 * where changes also propagate within 5 minutes via the unversioned startup module.
	 */
	private const CLIENT_CACHE_TTL = 300;

	public function __construct(
		Article $article,
		IContextSource $context,
		private readonly Parser $parser,
		private readonly PermissionManager $permissionManager,
		private readonly RevisionLookup $revisionLookup,
		private readonly RestrictionStore $restrictionStore,
		private readonly UserFactory $userFactory,
	) {
		parent::__construct( $article, $context );
	}

	/** @inheritDoc */
	public function getName() {
		return 'raw';
	}

	/** @inheritDoc */
	public function requiresWrite() {
		return false;
	}

	/** @inheritDoc */
	public function requiresUnblock() {
		return false;
	}

	/**
	 * @suppress SecurityCheck-XSS Non html mime type
	 * @return string|null
	 */
	public function onView() {
		$this->getOutput()->disable();
		ContentSecurityPolicy::sendRestrictiveHeader();
		$request = $this->getRequest();
		$response = $request->response();
		$config = $this->context->getConfig();

		// Handle If-Not-Modified HTTP 304 response, same as ViewAction::show on pageviews
		//
		// FIXME: When OutputPage::checkLastModified responds with HTTP 304, it sets a
		// different Cache-Control value than RawAction does for HTTP 200 responses.
		//
		// RawAction is called from ActionEntryPoint::performAction, which uses
		// OutputPage::setCdnMaxage to set `s-maxage=$wgCdnMaxAge` on canonical CSS/JSON/JS
		// action=raw URLs that we purge after edit. RawAction uses OutputPage::disable and
		// makes its own HTTP response which ignores OutputPage::setCdnMaxage.
		// Except... we reuse OutputPage::checkLastModified here,
		// which brings it back to live for the HTTP 304 response (when $wgUseCdn is on),
		// or worse replace it with CC:private (when $wgUseCdn is off).
		if ( $this->getOutput()->checkLastModified( $this->getWikiPage()->getTouched() ) ) {
			// Client cache fresh and headers sent, nothing more to do.
			return null;
		}

		$contentType = $this->getContentType();
		$title = $this->getTitle();

		// Basic CDN caching for action=raw to reduce server load (T279120)
		//
		// including:
		// * action=raw on CSS/JSON/JS pages with a non-canonical URL,
		//   such by having a oldid or curid param, or any unknown param noise.
		// * action=raw for wikitext and pages
		//   These don't have canonical URLs anyway, and are never purged
		//   by HTMLCacheUpdater::getUrls
		//
		// This is designed to match the CDN maxage for non-canonical pageview URLs,
		// as set by ActionEntryPoint::performAction.
		$smaxage = 3600;
		if (
			$contentType === 'text/css' ||
			$contentType === 'application/json' ||
			$contentType === 'text/javascript'
		) {
			// Enable full CDN caching for canonical URLs (T279120).
			// same as ActionEntryPoint::performAction on pageviews. These are purged on edit.
			if ( $config->get( MainConfigNames::UseCdn ) ) {
				$htmlCacheUpdater = MediaWikiServices::getInstance()->getHTMLCacheUpdater();
				if ( $request->matchURLForCDN( $htmlCacheUpdater->getUrls( $title ) ) ) {
					$smaxage = $config->get( MainConfigNames::CdnMaxAge );
				}
			}
		}

		$maxage = self::CLIENT_CACHE_TTL;

		// Set standard Vary headers so cache varies on cookies and such (T125283)
		$response->header( $this->getOutput()->getVaryHeader() );

		$privateCache = (
			// Disable CDN cache on private wikis if the request has any session
			( !$this->permissionManager->isEveryoneAllowed( 'read' ) && $request->getSession()->isPersistent() )
			// Disable CDN cache for all logged-in users even on public wikis,
			// to avoid accidentally caching personal session cookies (T55032)
			|| $this->getUser()->isRegistered()
		);
		if ( $privateCache ) {
			$response->header( "Cache-Control: private, must-revalidate, max-age=$maxage" );
		} else {
			$response->header( "Cache-Control: public, s-maxage=$smaxage, max-age=$maxage" );
		}

		// In the event of user JS, don't allow loading a user JS/CSS/Json
		// subpage that has no registered user associated with, as
		// someone could register the account and take control of the
		// JS/CSS/Json page.
		if ( $title->isUserConfigPage() && $contentType !== 'text/x-wiki' ) {
			// not using getRootText() as we want this to work
			// even if subpages are disabled.
			$rootPage = strtok( $title->getText(), '/' );
			$userFromTitle = $this->userFactory->newFromName( $rootPage, UserRigorOptions::RIGOR_USABLE );
			if ( !$userFromTitle || !$userFromTitle->isRegistered() ) {
				$elevated = $this->getAuthority()->isAllowed( 'editinterface' );
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
		// In the future, we may further restrict this to only CONTENT_MODEL_JAVASCRIPT
		// in NS_MEDIAWIKI or NS_USER, as well as including other config types,
		// but for now be more permissive. Allowing protected pages outside
		// NS_USER and NS_MEDIAWIKI in particular should be considered a temporary
		// allowance.
		if (
			$contentType === 'text/javascript' &&
			!$title->isUserJsConfigPage() &&
			!$title->inNamespace( NS_MEDIAWIKI )
		) {
			$pageRestrictions = $this->restrictionStore->getRestrictions( $title, 'edit' );
			if ( !in_array( 'sysop', $pageRestrictions ) &&
				!in_array( 'editprotected', $pageRestrictions )
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
		}

		// Content-Type: text/javascript should only work when the following are true:
		// page is in User subpage or Mediawiki namespace
		// page title ends in .js or .vue
		// page content type is CONTENT_MODEL_JAVASCRIPT or CONTENT_MODEL_VUE
		// currently only logging to determine how many pages would be impacted by this change
		// checking only for pages that exist
		if ( $contentType === 'text/javascript' && $title->exists() ) {
			if ( !( $title->isSiteJsConfigPage() || $title->isUserJsConfigPage() ) ) {
				$redirectTarget = MediaWikiServices::getInstance()
					->getRedirectLookup()
					->getRedirectTarget( $title );
				$redirectTitle = $redirectTarget ? Title::newFromLinkTarget( $redirectTarget ) : null;
				$isRedirectToJsConfigPage = $redirectTitle &&
					( $redirectTitle->isSiteJsConfigPage() || $redirectTitle->isUserJsConfigPage() );

				$log = LoggerFactory::getInstance( "security" );
				if ( $isRedirectToJsConfigPage ) {
					$log->info(
						"Did not block loading JS redirect {title} to {redirectTarget} "
							. "for {user} with more restrictions",
						[
							'user' => $this->getUser()->getName(),
							'title' => $title->getPrefixedDBkey(),
							'redirectTarget' => $redirectTitle->getPrefixedDBkey(),
						]
					);
				} else {
					$log->info( "Did not block loading unprotected JS {title} for {user} with more restrictions",
						[
							'user' => $this->getUser()->getName(),
							'title' => $title->getPrefixedDBkey(),
						]
					);
				}
			}
		}

		$response->header( 'Content-type: ' . $contentType . '; charset=UTF-8' );

		$text = $this->getRawText();

		// Don't return a 404 response for CSS or JavaScript;
		// 404s aren't generally cached, and it would create
		// extra hits when user CSS/JS are on and the user doesn't
		// have the pages.
		if ( $text === false && $contentType === 'text/x-wiki' ) {
			$response->statusHeader( 404 );
		}

		if ( !$this->getHookRunner()->onRawPageViewBeforeOutput( $this, $text ) ) {
			wfDebug( __METHOD__ . ": RawPageViewBeforeOutput hook broke raw page output." );
		}

		echo $text;

		return null;
	}

	/**
	 * Get the text that should be returned, or false if the page or revision
	 * was not found.
	 *
	 * @return string|false
	 */
	public function getRawText() {
		$text = false;
		$title = $this->getTitle();
		$request = $this->getRequest();

		// Get it from the DB
		$rev = $this->revisionLookup->getRevisionByTitle( $title, $this->getOldId() );
		if ( $rev ) {
			$lastMod = wfTimestamp( TS::RFC2822, $rev->getTimestamp() );
			$request->response()->header( "Last-modified: $lastMod" );

			// Public-only due to cache headers
			// Fetch specific slot if defined
			$slot = $this->getRequest()->getText( 'slot' );
			if ( $slot ) {
				if ( $rev->hasSlot( $slot ) ) {
					$content = $rev->getContent( $slot );
				} else {
					$content = null;
				}
			} else {
				$content = $rev->getContent( SlotRecord::MAIN );
			}

			if ( $content === null ) {
				// revision or slot was not found (or suppressed)
			} elseif ( !$content instanceof TextContent && !method_exists( $content, 'getText' ) ) {
				// non-text content
				wfHttpError(
					415,
					"Unsupported Media Type", "The requested page uses the content model `"
					. $content->getModel() . "` which is not supported via this interface."
				);
				die();
			} else {
				// want a section?
				$section = $request->getIntOrNull( 'section' );
				if ( $section !== null ) {
					$content = $content->getSection( $section );
				}

				if ( $content !== null && $content !== false ) {
					// section found (and section supported, e.g. not for JS, JSON, and CSS)
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
	 * Get the ID of the revision that should be used to get the text.
	 *
	 * @return int
	 */
	public function getOldId() {
		$oldId = $this->getRequest()->getInt( 'oldid' );
		$rl = $this->revisionLookup;
		switch ( $this->getRequest()->getText( 'direction' ) ) {
			case 'next':
				# output next revision, or nothing if there isn't one
				$nextRev = null;
				if ( $oldId ) {
					$oldRev = $rl->getRevisionById( $oldId );
					if ( $oldRev ) {
						$nextRev = $rl->getNextRevision( $oldRev );
					}
				}
				$oldId = $nextRev ? $nextRev->getId() : -1;
				break;
			case 'prev':
				# output previous revision, or nothing if there isn't one
				$prevRev = null;
				if ( !$oldId ) {
					# get the latest revision so we can get the penultimate one
					$oldId = $this->getWikiPage()->getLatest();
				}
				$oldRev = $rl->getRevisionById( $oldId );
				if ( $oldRev ) {
					$prevRev = $rl->getPreviousRevision( $oldRev );
				}
				$oldId = $prevRev ? $prevRev->getId() : -1;
				break;
			case 'cur':
				$oldId = 0;
				break;
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable RevisionRecord::getId does not return null here
		return $oldId;
	}

	/**
	 * Get the content type to be used for the response
	 *
	 * @return string
	 */
	public function getContentType() {
		// Optimisation: Avoid slow getVal(), this isn't user-generated content.
		$ctype = $this->getRequest()->getRawVal( 'ctype' );

		if ( $ctype == '' ) {
			// Legacy compatibility
			$gen = $this->getRequest()->getRawVal( 'gen' );
			if ( $gen == 'js' ) {
				$ctype = 'text/javascript';
			} elseif ( $gen == 'css' ) {
				$ctype = 'text/css';
			}
		}

		static $allowedCTypes = [
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

/** @deprecated class alias since 1.44 */
class_alias( RawAction::class, 'RawAction' );
