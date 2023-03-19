<?php

use MediaWiki\Html\Html;
use MediaWiki\Parser\Parser;

/**
 * A basic extension that's used by the parser tests to test whether input and
 * arguments are passed to extensions properly.
 *
 * Copyright © 2005, 2006 Ævar Arnfjörð Bjarmason
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
 * @ingroup Testing
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

class ParserTestParserHook {

	/** Set up extension tags and parser functions for parser tests. */
	public static function setup( Parser $parser ): bool {
		// Install a magic word.
		$parser->setHook( 'tag', [ self::class, 'dumpHook' ] );
		$parser->setHook( 'tåg', [ self::class, 'dumpHook' ] );
		$parser->setHook( 'statictag', [ self::class, 'staticTagHook' ] );
		$parser->setHook( 'asidetag', [ self::class, 'asideTagHook' ] );
		$parser->setHook( 'pwraptest', [ self::class, 'pWrapTestHook' ] );
		foreach ( [ 'div', 'span' ] as $tag ) {
			// spantag, divtag
			$parser->setHook( $tag . 'tag', static function ( $in, $argv, $parser ) use ( $tag ) {
				// @phan-suppress-next-line SecurityCheck-XSS parser test code only
				return self::divspanTagHook( $tag, $in, $argv, $parser );
			} );
			// spantagpf, divtagpf
			$parser->setFunctionHook( $tag . 'tagpf', static function ( $parser, ...$args ) use ( $tag ) {
				return self::divspanPFHook( $tag, $parser, ...$args );
			}, Parser::SFH_NO_HASH );
		}
		return true;
	}

	public static function dumpHook( $in, $argv ) {
		// @phan-suppress-next-line SecurityCheck-XSS
		return "<pre>\n" .
			var_export( $in, true ) . "\n" .
			var_export( $argv, true ) . "\n" .
			"</pre>";
	}

	/**
	 * @param string $in
	 * @param array $argv
	 * @param Parser $parser
	 * @return string
	 * @suppress SecurityCheck-XSS
	 * @suppress UnusedSuppression
	 */
	public static function staticTagHook( $in, $argv, $parser ) {
		$KEY = 'mw:tests:static-tag-hook';
		$po = $parser->getOutput();
		if ( !count( $argv ) ) {
			$po->appendExtensionData( $KEY, $in );
			return '';
		} elseif ( count( $argv ) === 1 && isset( $argv['action'] )
			&& $argv['action'] === 'flush' && $in === null
		) {
			// This pattern is deprecated, since the order of parsing will
			// in the future not be guaranteed.  A better approach is to
			// collect/emit the buffered content in a post-processing pass
			// over the document after parsing of the article and all contained
			// fragments is completed and the fragments are merged.
			// T357838, T300979
			$vals = $po->getExtensionData( $KEY );
			return $vals ? array_key_last( $vals ) : '';
		} else { // wtf?
			return "\nCall this extension as <statictag>string</statictag> or as" .
				" <statictag action=flush/>, not in any other way.\n" .
				"text: " . var_export( $in, true ) . "\n" .
				"argv: " . var_export( $argv, true ) . "\n";
		}
	}

	public static function asideTagHook(): string {
		return Html::element( 'aside', [], 'Some aside content' );
	}

	public static function pWrapTestHook(): string {
		return '<!--CMT--><style>p{}</style>';
	}

	/**
	 * @param string $tag "div" or "span"
	 * @param string $in
	 * @param array $argv
	 * @param Parser $parser
	 * @return array
	 */
	public static function divspanTagHook( $tag, $in, $argv, $parser ) {
		$result = [];
		$fixcase = [
			'markertype' => 'markerType',
			'israwhtml' => 'isRawHTML',
		];
		$result[] = "<$tag>" . (
			( $argv['raw'] ?? false ) ?
			$in :
			Parser::stripOuterParagraph(
				( $argv['isRawHTML'] ?? false ) ?
					$parser->recursiveTagParseFully( $in ) :
					$parser->recursiveTagParse( $in )
			)
		) . "</$tag>";
		// Allow setting noparse, isHTML, nowiki, isRawHTML, etc.
		foreach ( $argv as $arg => $ignore ) {
			if ( $arg !== 'raw' ) {
				$result[$fixcase[$arg] ?? $arg] = true;
			}
		}
		return $result;
	}

	/**
	 * @param string $tag "div" or "span"
	 * @param Parser $parser
	 * @param string ...$args
	 * @return array
	 */
	public static function divspanPfHook( $tag, $parser, ...$args ) {
		$result = [];
		$in = array_shift( $args );
		$result[] = "<$tag>" . (
			in_array( 'raw', $args, true ) ? $in :
			Parser::stripOuterParagraph(
				in_array( 'isRawHTML', $args, true ) ?
					$parser->recursiveTagParseFully( $in ) :
					$parser->recursiveTagParse( $in )
			)
		) . "</$tag>";
		// Allow setting noparse, isHTML, nowiki, isRawHTML, etc.
		foreach ( $args as $arg ) {
			if ( $arg !== 'raw' ) {
				$result[$arg] = true;
			}
		}
		return $result;
	}
}
