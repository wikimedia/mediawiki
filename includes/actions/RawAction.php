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
use MediaWiki\Page\Article;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserRigorOptions;

/**
 * A simple method to retrieve the plain source of an article,
 * using "action=raw" in the GET request string.
 *
 * @ingroup Actions
 */
class RawAction extends FormlessAction {

	private Parser $parser;
	private PermissionManager $permissionManager;
	private RevisionLookup $revisionLookup;
	private RestrictionStore $restrictionStore;
	private UserFactory $userFactory;

	/**
	 * @param Article $article
	 * @param IContextSource $context
	 * @param Parser $parser
	 * @param PermissionManager $permissionManager
	 * @param RevisionLookup $revisionLookup
	 * @param RestrictionStore $restrictionStore
	 * @param UserFactory $userFactory
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		Parser $parser,
		PermissionManager $permissionManager,
		RevisionLookup $revisionLookup,
		RestrictionStore $restrictionStore,
		UserFactory $userFactory
	) {
		parent::__construct( $article, $context );
		$this->parser = $parser;
		$this->permissionManager = $permissionManager;
		$this->revisionLookup = $revisionLookup;
		$this->restrictionStore = $restrictionStore;
		$this->userFactory = $userFactory;
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

		if ( $this->getOutput()->checkLastModified(
			$this->getWikiPage()->getTouched()
		) ) {
			// Client cache fresh and headers sent, nothing more to do.
			return null;
		}

		$contentType = $this->getContentType();

		$maxage = $request->getInt( 'maxage', $config->get( MainConfigNames::CdnMaxAge ) );
		$smaxage = $request->getIntOrNull( 'smaxage' );
		if ( $smaxage === null ) {
			if (
				$contentType === 'text/css' ||
				$contentType === 'application/json' ||
				$contentType === 'text/javascript'
			) {
				// CSS/JSON/JS raw content has its own CDN max age configuration.
				// Note: HTMLCacheUpdater::getUrls() includes action=raw for css/json/js
				// pages, so if using the canonical url, this will get HTCP purges.
				$smaxage = intval( $config->get( MainConfigNames::ForcedRawSMaxage ) );
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
			( $smaxage === 0 || $request->getSession()->isPersistent() );
		// Don't accidentally cache cookies if the user is registered (T55032)
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
		$pageRestrictions = $this->restrictionStore->getRestrictions( $title, 'edit' );
		if (
			$contentType === 'text/javascript' &&
			!$title->isUserJsConfigPage() &&
			!$title->inNamespace( NS_MEDIAWIKI ) &&
			!in_array( 'sysop', $pageRestrictions ) &&
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
			$lastMod = wfTimestamp( TS_RFC2822, $rev->getTimestamp() );
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
					# get the current revision so we can get the penultimate one
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
