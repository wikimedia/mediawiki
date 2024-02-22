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

	public static function setup( Parser $parser ) {
		$parser->setHook( 'tag', [ __CLASS__, 'dumpHook' ] );
		$parser->setHook( 'tåg', [ __CLASS__, 'dumpHook' ] );
		$parser->setHook( 'statictag', [ __CLASS__, 'staticTagHook' ] );
		$parser->setHook( 'asidetag', [ __CLASS__, 'asideTagHook' ] );
		$parser->setHook( 'pwraptest', [ __CLASS__, 'pWrapTestHook' ] );
		$parser->setHook( 'spantag', [ __CLASS__, 'spanTagHook' ] );
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
			if ( $vals === null ) {
				return '';
			}
			return array_key_last( $vals );
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
	 * @param string $in
	 * @param array $argv
	 * @param Parser $parser
	 * @return string
	 */
	public static function spanTagHook( $in, $argv, $parser ): string {
		return '<span>' .
			Parser::stripOuterParagraph( $parser->recursiveTagParse( $in ) ) .
			'</span>';
	}
}
