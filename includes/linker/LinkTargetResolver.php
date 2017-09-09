<?php
/**
 * Resolve LinkTargets into URLs
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
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

namespace MediaWiki\Linker;

use Hooks;
use MediaWiki\Interwiki\InterwikiLookup;
use Sanitizer;
use SpecialPage;
use Title;
use TitleFormatter;

class LinkTargetResolver {

	/**
	 * @var InterwikiLookup
	 */
	private $interwikiLookup;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	public function __construct( InterwikiLookup $interwikiLookup, TitleFormatter $titleFormatter ) {
		$this->interwikiLookup = $interwikiLookup;
		$this->titleFormatter = $titleFormatter;
	}

	/**
	 * Get the fragment in URL form, including the "#" character if there is one
	 *
	 * @param LinkTarget $target
	 * @return string Fragment in URL form
	 */
	public function getFragmentForURL( LinkTarget $target ) {
		if ( !$target->hasFragment() ) {
			return '';
		} elseif ( $target->isExternal()
			&& !$this->interwikiLookup->fetch( $target->getInterwiki() )->getWikiID()
		) {
			return '#' . Sanitizer::escapeIdForExternalInterwiki( $target->getFragment() );
		}
		return '#' . Sanitizer::escapeIdForLink( $target->getFragment() );
	}

	/**
	 * Get a real URL referring to this title, with interwiki link and
	 * fragment
	 *
	 * @see self::getLocalURL for the arguments.
	 * @see wfExpandUrl
	 * @param LinkTarget $target
	 * @param string|string[] $query
	 * @param string $proto Protocol type to use in URL
	 * @return string The URL
	 */
	public function getFullURL( LinkTarget $target, $query = '', $proto = PROTO_RELATIVE ) {
		# Hand off all the decisions on urls to getLocalURL
		$url = $this->getLocalURL( $target, $query );

		# Expand the url to make it a full url. Note that getLocalURL has the
		# potential to output full urls for a variety of reasons, so we use
		# wfExpandUrl instead of simply prepending $wgServer
		$url = wfExpandUrl( $url, $proto );

		# Finally, add the fragment.
		$url .= $this->getFragmentForURL( $target );
		// Avoid PHP 7.1 warning from passing $this by reference
		$titleRef = Title::newFromLinkTarget( $target );
		Hooks::run( 'GetFullURL', [ &$titleRef, &$url, $query ], '1.30' );
		return $url;
	}

	/**
	 * Get a url appropriate for making redirects based on an untrusted url arg
	 *
	 * This is basically the same as getFullUrl(), but in the case of external
	 * interwikis, we send the user to a landing page, to prevent possible
	 * phishing attacks and the like.
	 *
	 * @note Uses current protocol by default, since technically relative urls
	 *   aren't allowed in redirects per HTTP spec, so this is not suitable for
	 *   places where the url gets cached, as might pollute between
	 *   https and non-https users.
	 * @see self::getLocalURL for the arguments.
	 * @param LinkTarget $target
	 * @param array|string $query
	 * @param string $proto Protocol type to use in URL
	 * @return String. A url suitable to use in an HTTP location header.
	 */
	public function getFullUrlForRedirect( LinkTarget $target, $query = '', $proto = PROTO_CURRENT ) {
		if ( $target->isExternal() ) {
			$target = SpecialPage::getTitleValueFor(
				'GoToInterwiki',
				$this->titleFormatter->getPrefixedDBkey( $target )
			);
		}
		return $this->getFullUrl( $target, $query, $proto );
	}

	/**
	 * Get a URL with no fragment or server name (relative URL) from a Title object.
	 * If this page is generated with action=render, however,
	 * $wgServer is prepended to make an absolute URL.
	 *
	 * @see self::getFullURL to always get an absolute URL.
	 * @see self::getLinkURL to always get a URL that's the simplest URL that will be
	 *  valid to link, locally, to the current Title.
	 * @see self::newFromText to produce a Title object.
	 *
	 * @param LinkTarget $linkTarget Target to create a URL to
	 * @param string|string[] $query An optional query string,
	 *   not used for interwiki links. Can be specified as an associative array as well,
	 *   e.g., array( 'action' => 'edit' ) (keys and values will be URL-escaped).
	 *   Some query patterns will trigger various shorturl path replacements.
	 *
	 * @return string String of the URL.
	 */
	public function getLocalURL( LinkTarget $linkTarget, $query = '' ) {
		global $wgArticlePath, $wgScript, $wgServer, $wgRequest;

		if ( is_array( $query ) ) {
			$query = wfArrayToCgi( $query );
		}

		$interwiki = $this->interwikiLookup->fetch( $linkTarget->getInterwiki() );
		if ( $interwiki ) {
			$namespace = $this->titleFormatter->getNamespaceName(
				$linkTarget->getNamespace(), $linkTarget->getDBkey()
			);
			if ( $namespace != '' ) {
				# Can this actually happen? Interwikis shouldn't be parsed.
				# Yes! It can in interwiki transclusion. But... it probably shouldn't.
				$namespace .= ':';
			}
			$url = $interwiki->getURL( $namespace . $linkTarget->getDBkey() );
			$url = wfAppendQuery( $url, $query );
		} else {
			$dbkey = wfUrlencode( $this->titleFormatter->getPrefixedDBkey( $linkTarget ) );
			if ( $query == '' ) {
				$url = str_replace( '$1', $dbkey, $wgArticlePath );
				// Run deprecated hook
				$titleRef = Title::newFromLinkTarget( $linkTarget );
				Hooks::run( 'GetLocalURL::Article', [ &$titleRef, &$url ], '1.30' );
			} else {
				global $wgVariantArticlePath, $wgActionPaths, $wgContLang;
				$url = false;
				$matches = [];

				if ( !empty( $wgActionPaths )
					&& preg_match( '/^(.*&|)action=([^&]*)(&(.*)|)$/', $query, $matches )
				) {
					$action = urldecode( $matches[2] );
					if ( isset( $wgActionPaths[$action] ) ) {
						$query = $matches[1];
						if ( isset( $matches[4] ) ) {
							$query .= $matches[4];
						}
						$url = str_replace( '$1', $dbkey, $wgActionPaths[$action] );
						if ( $query != '' ) {
							$url = wfAppendQuery( $url, $query );
						}
					}
				}

				if ( $url === false
					&& $wgVariantArticlePath
					&& preg_match( '/^variant=([^&]*)$/', $query, $matches )
				) {
					$titleRef = Title::newFromLinkTarget( $linkTarget );
					if ( $titleRef->getPageLanguage()->equals( $wgContLang )
						&& $titleRef->getPageLanguage()->hasVariants()
					) {
						$variant = urldecode( $matches[1] );
						if ( $titleRef->getPageLanguage()->hasVariant( $variant ) ) {
							// Only do the variant replacement if the given variant is a valid
							// variant for the page's language.
							$url = str_replace( '$2', urlencode( $variant ), $wgVariantArticlePath );
							$url = str_replace( '$1', $dbkey, $url );
						}
					}
				}

				if ( $url === false ) {
					if ( $query == '-' ) {
						$query = '';
					}
					$url = "{$wgScript}?title={$dbkey}&{$query}";
				}
			}
			// Avoid PHP 7.1 warning from passing $this by reference
			$titleRef = Title::newFromLinkTarget( $linkTarget );
			Hooks::run( 'GetLocalURL::Internal', [ &$titleRef, &$url, $query ], '1.30' );

			// @todo FIXME: This causes breakage in various places when we
			// actually expected a local URL and end up with dupe prefixes.
			if ( $wgRequest->getVal( 'action' ) == 'render' ) {
				$url = $wgServer . $url;
			}
		}
		// Avoid PHP 7.1 warning from passing $this by reference
		$titleRef = Title::newFromLinkTarget( $linkTarget );
		Hooks::run( 'GetLocalURL', [ &$titleRef, &$url, $query ], '1.30' );
		return $url;
	}

	/**
	 * Get a URL that's the simplest URL that will be valid to link, locally,
	 * to the current Title.  It includes the fragment, but does not include
	 * the server unless action=render is used (or the link is external).  If
	 * there's a fragment but the prefixed text is empty, we just return a link
	 * to the fragment.
	 *
	 * The result obviously should not be URL-escaped, but does need to be
	 * HTML-escaped if it's being output in HTML.
	 *
	 * @param LinkTarget $target
	 * @param string|string[] $query
	 * @param string|int|bool $proto A PROTO_* constant on how the URL should be expanded,
	 *                               or false (default) for no expansion
	 * @see self::getLocalURL for the arguments.
	 * @return string The URL
	 */
	public function getLinkURL( LinkTarget $target, $query = '', $proto = false ) {
		if ( $target->isExternal() || $proto !== false ) {
			$ret = $this->getFullURL( $target, $query, $proto );
		} elseif ( $this->titleFormatter->getPrefixedText( $target ) === ''
			&& $target->hasFragment()
		) {
			$ret = $this->getFragmentForURL( $target );
		} else {
			$ret = $this->getLocalURL( $target, $query ) . $this->getFragmentForURL( $target );
		}
		return $ret;
	}

	/**
	 * Get the URL form for an internal link.
	 * - Used in various CDN-related code, in case we have a different
	 * internal hostname for the server from the exposed one.
	 *
	 * This uses $wgInternalServer to qualify the path, or $wgServer
	 * if $wgInternalServer is not set. If the server variable used is
	 * protocol-relative, the URL will be expanded to http://
	 *
	 * @see self::getLocalURL for the arguments.
	 * @param LinkTarget $target
	 * @param string $query
	 * @return string The URL
	 */
	public function getInternalURL( LinkTarget $target, $query = '' ) {
		global $wgInternalServer, $wgServer;
		$server = $wgInternalServer !== false ? $wgInternalServer : $wgServer;
		$url = wfExpandUrl( $server . $this->getLocalURL( $target, $query ), PROTO_HTTP );
		// Avoid PHP 7.1 warning from passing $this by reference
		$titleRef = Title::newFromLinkTarget( $target );
		Hooks::run( 'GetInternalURL', [ &$titleRef, &$url, $query ], '1.30' );
		return $url;
	}

	/**
	 * Get the URL for a canonical link, for use in things like IRC and
	 * e-mail notifications. Uses $wgCanonicalServer and the
	 * GetCanonicalURL hook.
	 *
	 * NOTE: Unlike getInternalURL(), the canonical URL includes the fragment
	 *
	 * @see self::getLocalURL for the arguments.
	 * @return string The URL
	 * @since 1.18
	 */
	public function getCanonicalURL( LinkTarget $target, $query = '' ) {
		$url = wfExpandUrl(
			$this->getLocalURL( $target, $query ) . $this->getFragmentForURL( $target ),
			PROTO_CANONICAL
		);
		// Avoid PHP 7.1 warning from passing $this by reference
		$titleRef = Title::newFromLinkTarget( $target );
		Hooks::run( 'GetCanonicalURL', [ &$titleRef, &$url, $query ], '1.30' );
		return $url;
	}

}
