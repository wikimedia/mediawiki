<?php
/**
 * Raw page text accessor
 *
 * Copyright © 2004 Gabriel Wicke <wicke@wikidev.net>
 * http://wikidev.net/
 *
 * Based on HistoryPage and SpecialExport
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

/**
 * A simple method to retrieve the plain source of an article,
 * using "action=raw" in the GET request string.
 *
 * @ingroup Actions
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
		global $wgSquidMaxage, $wgForcedRawSMaxage;

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

		# Force caching for CSS and JS raw content, default: 5 minutes.
		# Note: If using a canonical url for userpage css/js, we send an HTCP purge.
		if ( $smaxage === null ) {
			if ( $contentType == 'text/css' || $contentType == 'text/javascript' ) {
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
		$privateCache = !User::isEveryoneAllowed( 'read' ) && ( $smaxage == 0 || session_id() != '' );
		// Bug 53032 - make this private if user is logged in,
		// so we don't accidentally cache cookies
		$privateCache = $privateCache ?: $this->getUser()->isLoggedIn();
		# allow the client to cache this for 24 hours
		$mode = $privateCache ? 'private' : 'public';
		$response->header(
			'Cache-Control: ' . $mode . ', s-maxage=' . $smaxage . ', max-age=' . $maxage
		);

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
	 * @return string|bool
	 */
	public function getRawText() {
		global $wgParser;

		# No longer used
		if ( $this->mGen ) {
			return '';
		}

		$text = false;
		$title = $this->getTitle();
		$request = $this->getRequest();

		// If it's a MediaWiki message we can just hit the message cache
		if ( $request->getBool( 'usemsgcache' ) && $title->getNamespace() == NS_MEDIAWIKI ) {
			// The first "true" is to use the database, the second is to use
			// the content langue and the last one is to specify the message
			// key already contains the language in it ("/de", etc.).
			$text = MessageCache::singleton()->get( $title->getDBkey(), true, true, true );
			// If the message doesn't exist, return a blank
			if ( $text === false ) {
				$text = '';
			}
		} else {
			// Get it from the DB
			$rev = Revision::newFromTitle( $title, $this->getOldId() );
			if ( $rev ) {
				$lastmod = wfTimestamp( TS_RFC2822, $rev->getTimestamp() );
				$request->response()->header( "Last-modified: $lastmod" );

				// Public-only due to cache headers
				$content = $rev->getContent();

				if ( $content === null ) {
					// revision not found (or suppressed)
					$text = false;
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
						// section not found (or section not supported, e.g. for JS and CSS)
						$text = false;
					} else {
						$text = $content->getNativeData();
					}
				}
			}
		}

		if ( $text !== false && $text !== '' && $request->getVal( 'templates' ) === 'expand' ) {
			$text = $wgParser->preprocess(
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
		switch ( $this->getRequest()->getText( 'direction' ) ) {
			case 'next':
				# output next revision, or nothing if there isn't one
				if ( $oldid ) {
					$oldid = $this->getTitle()->getNextRevisionID( $oldid );
				}
				$oldid = $oldid ? $oldid : -1;
				break;
			case 'prev':
				# output previous revision, or nothing if there isn't one
				if ( !$oldid ) {
					# get the current revision so we can get the penultimate one
					$oldid = $this->page->getLatest();
				}
				$prev = $this->getTitle()->getPreviousRevisionID( $oldid );
				$oldid = $prev ? $prev : -1;
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
		$ctype = $this->getRequest()->getVal( 'ctype' );

		if ( $ctype == '' ) {
			$gen = $this->getRequest()->getVal( 'gen' );
			if ( $gen == 'js' ) {
				$ctype = 'text/javascript';
			} elseif ( $gen == 'css' ) {
				$ctype = 'text/css';
			}
		}

		$allowedCTypes = array( 'text/x-wiki', 'text/javascript', 'text/css', 'application/x-zope-edit' );
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

	/**
	 * @param Page $page
	 * @param WebRequest|bool $request The WebRequest (default: false).
	 */
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
