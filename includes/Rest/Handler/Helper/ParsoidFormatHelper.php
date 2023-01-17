<?php
/**
 * Copyright (C) 2011-2020 Wikimedia Foundation and others.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace MediaWiki\Rest\Handler\Helper;

use InvalidArgumentException;
use MediaWiki\Rest\ResponseInterface;

/**
 * Format-related REST API helper.
 * Probably should be turned into an object encapsulating format and content version at some point.
 */
class ParsoidFormatHelper {

	public const FORMAT_WIKITEXT = 'wikitext';
	public const FORMAT_HTML = 'html';
	public const FORMAT_PAGEBUNDLE = 'pagebundle';
	public const FORMAT_LINT = 'lint';

	public const ERROR_ENCODING = [
		self::FORMAT_WIKITEXT => 'plain',
		self::FORMAT_HTML => 'html',
		self::FORMAT_PAGEBUNDLE => 'json',
		self::FORMAT_LINT => 'json',
	];

	public const VALID_PAGE = [
		self::FORMAT_WIKITEXT, self::FORMAT_HTML, self::FORMAT_PAGEBUNDLE, self::FORMAT_LINT
	];

	public const VALID_TRANSFORM = [
		self::FORMAT_WIKITEXT => [ self::FORMAT_HTML, self::FORMAT_PAGEBUNDLE, self::FORMAT_LINT ],
		self::FORMAT_HTML => [ self::FORMAT_WIKITEXT ],
		self::FORMAT_PAGEBUNDLE => [ self::FORMAT_WIKITEXT, self::FORMAT_PAGEBUNDLE ],
	];

	/**
	 * Get the content type appropriate for a given response format.
	 * @param string $format One of the FORMAT_* constants
	 * @param ?string $contentVersion Output version, only for HTML and pagebundle
	 *   formats. See Env::getcontentVersion().
	 * @return string
	 */
	public static function getContentType(
		string $format, ?string $contentVersion = null
	): string {
		if ( $format !== self::FORMAT_WIKITEXT && !$contentVersion ) {
			throw new InvalidArgumentException( '$contentVersion is required for this format' );
		}

		switch ( $format ) {
			case self::FORMAT_WIKITEXT:
				$contentType = 'text/plain';
				// PORT-FIXME in the original the version number is from MWParserEnvironment.wikitextVersion
				// but it did not seem to be used anywhere
				$profile = 'https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0';
				break;
			case self::FORMAT_HTML:
				$contentType = 'text/html';
				$profile = 'https://www.mediawiki.org/wiki/Specs/HTML/' . $contentVersion;
				break;
			case self::FORMAT_PAGEBUNDLE:
				$contentType = 'application/json';
				$profile = 'https://www.mediawiki.org/wiki/Specs/pagebundle/' . $contentVersion;
				break;
			default:
				throw new InvalidArgumentException( "Invalid format $format" );
		}
		return "$contentType; charset=utf-8; profile=\"$profile\"";
	}

	/**
	 * Set the Content-Type header appropriate for a given response format.
	 * @param ResponseInterface $response
	 * @param string $format One of the FORMAT_* constants
	 * @param ?string $contentVersion Output version, only for HTML and pagebundle
	 *   formats. See Env::getcontentVersion().
	 */
	public static function setContentType(
		ResponseInterface $response, string $format,
		?string $contentVersion = null
	): void {
		$response->setHeader( 'Content-Type', self::getContentType( $format, $contentVersion ) );
	}

	/**
	 * Parse a Content-Type header and return the format type and version.
	 * Mostly the inverse of getContentType() but also accounts for legacy formats.
	 * @param string $contentTypeHeader The value of the Content-Type header.
	 * @param ?string &$format Format type will be set here (as a FORMAT_* constant).
	 * @return ?string Format version, or null if it couldn't be identified.
	 * @see Env::getInputContentVersion()
	 */
	public static function parseContentTypeHeader(
		string $contentTypeHeader, ?string &$format = null
	): ?string {
		$newProfileSyntax = 'https://www.mediawiki.org/wiki/Specs/(HTML|pagebundle)/';
		$oldProfileSyntax = 'mediawiki.org/specs/(html)/';
		$profileRegex = "#\bprofile=\"(?:$newProfileSyntax|$oldProfileSyntax)(\d+\.\d+\.\d+)\"#";
		preg_match( $profileRegex, $contentTypeHeader, $m );
		if ( $m ) {
			switch ( $m[1] ?: $m[2] ) {
				case 'HTML':
				case 'html':
					$format = self::FORMAT_HTML;
					break;
				case 'pagebundle':
					$format = self::FORMAT_PAGEBUNDLE;
					break;
			}
			return $m[3];
		}
		return null;
	}

}

/** @deprecated since 1.40, remove in 1.41 */
class_alias( ParsoidFormatHelper::class, "MediaWiki\\Rest\\Handler\\ParsoidFormatHelper" );
