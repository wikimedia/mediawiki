<?php
/**
 * Raw page text accessor
 *
 * Copyright © 2004 Gabriel Wicke <wicke@wikidev.net>
 * http://wikidev.net/
 *
 * Based on HistoryPage and SpecialExport
 *
 * License: GPL (http://www.gnu.org/copyleft/gpl.html)
 *
 * @author Gabriel Wicke <wicke@wikidev.net>
 * @file
 */

/**
 * A simple method to retrieve the plain source of an article,
 * using "action=raw" in the GET request string.
 */
class RawAction extends FormlessAction {
	private $mGen;

	public function getName() {
		return 'raw';
	}

	public function requiresWrite() {
		return false;
	}

	public function requiresUnblock() {
		return false;
	}

	function onView() {
		global $wgGroupPermissions, $wgSquidMaxage, $wgForcedRawSMaxage, $wgJsMimeType;

		$this->getOutput()->disable();
		$request = $this->getRequest();

		if ( !$request->checkUrlExtension() ) {
			return;
		}

		if ( $this->getOutput()->checkLastModified( $this->page->getTouched() ) ) {
			return; // Client cache fresh and headers sent, nothing more to do.
		}

		# special case for 'generated' raw things: user css/js
		# This is deprecated and will only return empty content
		$gen = $request->getVal( 'gen' );
		$smaxage = $request->getIntOrNull( 'smaxage' );

		if ( $gen == 'css' || $gen == 'js' ) {
			$this->mGen = $gen;
			if ( $smaxage === null ) {
				$smaxage = $wgSquidMaxage;
			}
		} else {
			$this->mGen = false;
		}

		$contentType = $this->getContentType();

		# Force caching for CSS and JS raw content, default: 5 minutes
		if ( $smaxage === null ) {
			if ( $contentType == 'text/css' || $contentType == $wgJsMimeType ) {
				$smaxage = intval( $wgForcedRawSMaxage );
			} else {
				$smaxage = 0;
			}
		}

		$maxage = $request->getInt( 'maxage', $wgSquidMaxage );

		$response = $request->response();

		$response->header( 'Content-type: ' . $contentType . '; charset=UTF-8' );
		# Output may contain user-specific data;
		# vary generated content for open sessions on private wikis
		$privateCache = !$wgGroupPermissions['*']['read'] && ( $smaxage == 0 || session_id() != '' );
		# allow the client to cache this for 24 hours
		$mode = $privateCache ? 'private' : 'public';
		$response->header( 'Cache-Control: ' . $mode . ', s-maxage=' . $smaxage . ', max-age=' . $maxage );

		$text = $this->getRawText();

		if ( $text === false && $contentType == 'text/x-wiki' ) {
			# Don't return a 404 response for CSS or JavaScript;
			# 404s aren't generally cached and it would create
			# extra hits when user CSS/JS are on and the user doesn't
			# have the pages.
			$response->header( 'HTTP/1.x 404 Not Found' );
		}

		if ( !wfRunHooks( 'RawPageViewBeforeOutput', array( &$this, &$text ) ) ) {
			wfDebug( __METHOD__ . ": RawPageViewBeforeOutput hook broke raw page output.\n" );
		}

		echo $text;
	}

	/**
	 * Get the text that should be returned, or false if the page or revision
	 * was not found.
	 *
	 * @return String|Bool
	 */
	public function getRawText() {
		global $wgParser;

		# No longer used
		if( $this->mGen ) {
			return '';
		}

		$text = false;
		$title = $this->getTitle();
		$request = $this->getRequest();

		// If it's a MediaWiki message we can just hit the message cache
		if ( $request->getBool( 'usemsgcache' ) && $title->getNamespace() == NS_MEDIAWIKI ) {
			$key = $title->getDBkey();
			$msg = wfMessage( $key )->inContentLanguage();
			# If the message doesn't exist, return a blank
			$text = !$msg->exists() ? '' : $msg->plain();
		} else {
			// Get it from the DB
			$rev = Revision::newFromTitle( $title, $this->getOldId() );
			if ( $rev ) {
				$lastmod = wfTimestamp( TS_RFC2822, $rev->getTimestamp() );
				$request->response()->header( "Last-modified: $lastmod" );

				// Public-only due to cache headers
				$text = $rev->getText();
				$section = $request->getIntOrNull( 'section' );
				if ( $section !== null ) {
					$text = $wgParser->getSection( $text, $section );
				}
			}
		}

		if ( $text !== false && $text !== '' && $request->getVal( 'templates' ) === 'expand' ) {
			$text = $wgParser->preprocess( $text, $title, ParserOptions::newFromContext( $this->getContext() ) );
		}

		return $text;
	}

	/**
	 * Get the ID of the revision that should used to get the text.
	 *
	 * @return Integer
	 */
	public function getOldId() {
		$oldid = $this->getRequest()->getInt( 'oldid' );
		switch ( $this->getRequest()->getText( 'direction' ) ) {
			case 'next':
				# output next revision, or nothing if there isn't one
				if( $oldid ) {
					$oldid = $this->getTitle()->getNextRevisionId( $oldid );
				}
				$oldid = $oldid ? $oldid : -1;
				break;
			case 'prev':
				# output previous revision, or nothing if there isn't one
				if( !$oldid ) {
					# get the current revision so we can get the penultimate one
					$oldid = $this->page->getLatest();
				}
				$prev = $this->getTitle()->getPreviousRevisionId( $oldid );
				$oldid = $prev ? $prev : -1 ;
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
	 * @return String
	 */
	public function getContentType() {
		global $wgJsMimeType;

		$ctype = $this->getRequest()->getVal( 'ctype' );

		if ( $ctype == '' ) {
			$gen = $this->getRequest()->getVal( 'gen' );
			if ( $gen == 'js' ) {
				$ctype = $wgJsMimeType;
			} elseif ( $gen == 'css' ) {
				$ctype = 'text/css';
			}
		}

		$allowedCTypes = array( 'text/x-wiki', $wgJsMimeType, 'text/css', 'application/x-zope-edit' );
		if ( $ctype == '' || !in_array( $ctype, $allowedCTypes ) ) {
			$ctype = 'text/x-wiki';
		}

		return $ctype;
	}
}

/**
 * Backward compatibility for extensions
 *
 * @deprecated in 1.19
 */
class RawPage extends RawAction {
	public $mOldId;

	function __construct( Page $page, $request = false ) {
		wfDeprecated( __CLASS__, '1.19' );
		parent::__construct( $page );

		if ( $request !== false ) {
			$context = new DerivativeContext( $this->getContext() );
			$context->setRequest( $request );
			$this->context = $context;
		}
	}

	public function view() {
		$this->onView();
	}

	public function getOldId() {
		# Some extensions like to set $mOldId
		if ( $this->mOldId !== null ) {
			return $this->mOldId;
		}
		return parent::getOldId();
	}
}
