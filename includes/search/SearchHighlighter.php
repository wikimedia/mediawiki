<?php
/**
 * Basic search engine highlighting
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
 * @ingroup Search
 */

/**
 * Highlight bits of wikitext
 *
 * @ingroup Search
 */
class SearchHighlighter {
	protected $mCleanWikitext = true;

	function __construct( $cleanupWikitext = true ) {
		$this->mCleanWikitext = $cleanupWikitext;
	}

	/**
	 * Wikitext highlighting when $wgAdvancedSearchHighlighting = true
	 *
	 * @param string $text
	 * @param array $terms Terms to highlight (not html escaped but
	 *   regex escaped via SearchDatabase::regexTerm())
	 * @param int $contextlines
	 * @param int $contextchars
	 * @return string
	 */
	public function highlightText( $text, $terms, $contextlines, $contextchars ) {
		global $wgContLang, $wgSearchHighlightBoundaries;

		if ( $text == '' ) {
			return '';
		}

		// spli text into text + templates/links/tables
		$spat = "/(\\{\\{)|(\\[\\[[^\\]:]+:)|(\n\\{\\|)";
		// first capture group is for detecting nested templates/links/tables/references
		$endPatterns = [
			1 => '/(\{\{)|(\}\})/', // template
			2 => '/(\[\[)|(\]\])/', // image
			3 => "/(\n\\{\\|)|(\n\\|\\})/" ]; // table

		// @todo FIXME: This should prolly be a hook or something
		// instead of hardcoding a class name from the Cite extension
		if ( class_exists( 'Cite' ) ) {
			$spat .= '|(<ref>)'; // references via cite extension
			$endPatterns[4] = '/(<ref>)|(<\/ref>)/';
		}
		$spat .= '/';
		$textExt = []; // text extracts
		$otherExt = []; // other extracts
		$start = 0;
		$textLen = strlen( $text );
		$count = 0; // sequence number to maintain ordering
		while ( $start < $textLen ) {
			// find start of template/image/table
			if ( preg_match( $spat, $text, $matches, PREG_OFFSET_CAPTURE, $start ) ) {
				$epat = '';
				foreach ( $matches as $key => $val ) {
					if ( $key > 0 && $val[1] != - 1 ) {
						if ( $key == 2 ) {
							// see if this is an image link
							$ns = substr( $val[0], 2, - 1 );
							if ( $wgContLang->getNsIndex( $ns ) != NS_FILE ) {
								break;
							}

						}
						$epat = $endPatterns[$key];
						$this->splitAndAdd( $textExt, $count, substr( $text, $start, $val[1] - $start ) );
						$start = $val[1];
						break;
					}
				}
				if ( $epat ) {
					// find end (and detect any nested elements)
					$level = 0;
					$offset = $start + 1;
					$found = false;
					while ( preg_match( $epat, $text, $endMatches, PREG_OFFSET_CAPTURE, $offset ) ) {
						if ( array_key_exists( 2, $endMatches ) ) {
							// found end
							if ( $level == 0 ) {
								$len = strlen( $endMatches[2][0] );
								$off = $endMatches[2][1];
								$this->splitAndAdd( $otherExt, $count,
									substr( $text, $start, $off + $len - $start ) );
								$start = $off + $len;
								$found = true;
								break;
							} else {
								// end of nested element
								$level -= 1;
							}
						} else {
							// nested
							$level += 1;
						}
						$offset = $endMatches[0][1] + strlen( $endMatches[0][0] );
					}
					if ( !$found ) {
						// couldn't find appropriate closing tag, skip
						$this->splitAndAdd( $textExt, $count, substr( $text, $start, strlen( $matches[0][0] ) ) );
						$start += strlen( $matches[0][0] );
					}
					continue;
				}
			}
			// else: add as text extract
			$this->splitAndAdd( $textExt, $count, substr( $text, $start ) );
			break;
		}

		$all = $textExt + $otherExt; // these have disjunct key sets

		// prepare regexps
		foreach ( $terms as $index => $term ) {
			// manually do upper/lowercase stuff for utf-8 since PHP won't do it
			if ( preg_match( '/[\x80-\xff]/', $term ) ) {
				$terms[$index] = preg_replace_callback(
					'/./us',
					[ $this, 'caseCallback' ],
					$terms[$index]
				);
			} else {
				$terms[$index] = $term;
			}
		}
		$anyterm = implode( '|', $terms );
		$phrase = implode( "$wgSearchHighlightBoundaries+", $terms );
		// @todo FIXME: A hack to scale contextchars, a correct solution
		// would be to have contextchars actually be char and not byte
		// length, and do proper utf-8 substrings and lengths everywhere,
		// but PHP is making that very hard and unclean to implement :(
		$scale = strlen( $anyterm ) / mb_strlen( $anyterm );
		$contextchars = intval( $contextchars * $scale );

		$patPre = "(^|$wgSearchHighlightBoundaries)";
		$patPost = "($wgSearchHighlightBoundaries|$)";

		$pat1 = "/(" . $phrase . ")/ui";
		$pat2 = "/$patPre(" . $anyterm . ")$patPost/ui";

		$left = $contextlines;

		$snippets = [];
		$offsets = [];

		// show beginning only if it contains all words
		$first = 0;
		$firstText = '';
		foreach ( $textExt as $index => $line ) {
			if ( strlen( $line ) > 0 && $line[0] != ';' && $line[0] != ':' ) {
				$firstText = $this->extract( $line, 0, $contextchars * $contextlines );
				$first = $index;
				break;
			}
		}
		if ( $firstText ) {
			$succ = true;
			// check if first text contains all terms
			foreach ( $terms as $term ) {
				if ( !preg_match( "/$patPre" . $term . "$patPost/ui", $firstText ) ) {
					$succ = false;
					break;
				}
			}
			if ( $succ ) {
				$snippets[$first] = $firstText;
				$offsets[$first] = 0;
			}
		}
		if ( !$snippets ) {
			// match whole query on text
			$this->process( $pat1, $textExt, $left, $contextchars, $snippets, $offsets );
			// match whole query on templates/tables/images
			$this->process( $pat1, $otherExt, $left, $contextchars, $snippets, $offsets );
			// match any words on text
			$this->process( $pat2, $textExt, $left, $contextchars, $snippets, $offsets );
			// match any words on templates/tables/images
			$this->process( $pat2, $otherExt, $left, $contextchars, $snippets, $offsets );

			ksort( $snippets );
		}

		// add extra chars to each snippet to make snippets constant size
		$extended = [];
		if ( count( $snippets ) == 0 ) {
			// couldn't find the target words, just show beginning of article
			if ( array_key_exists( $first, $all ) ) {
				$targetchars = $contextchars * $contextlines;
				$snippets[$first] = '';
				$offsets[$first] = 0;
			}
		} else {
			// if begin of the article contains the whole phrase, show only that !!
			if ( array_key_exists( $first, $snippets ) && preg_match( $pat1, $snippets[$first] )
				&& $offsets[$first] < $contextchars * 2 ) {
				$snippets = [ $first => $snippets[$first] ];
			}

			// calc by how much to extend existing snippets
			$targetchars = intval( ( $contextchars * $contextlines ) / count( $snippets ) );
		}

		foreach ( $snippets as $index => $line ) {
			$extended[$index] = $line;
			$len = strlen( $line );
			if ( $len < $targetchars - 20 ) {
				// complete this line
				if ( $len < strlen( $all[$index] ) ) {
					$extended[$index] = $this->extract(
						$all[$index],
						$offsets[$index],
						$offsets[$index] + $targetchars,
						$offsets[$index]
					);
					$len = strlen( $extended[$index] );
				}

				// add more lines
				$add = $index + 1;
				while ( $len < $targetchars - 20
						&& array_key_exists( $add, $all )
						&& !array_key_exists( $add, $snippets ) ) {
					$offsets[$add] = 0;
					$tt = "\n" . $this->extract( $all[$add], 0, $targetchars - $len, $offsets[$add] );
					$extended[$add] = $tt;
					$len += strlen( $tt );
					$add++;
				}
			}
		}

		// $snippets = array_map( 'htmlspecialchars', $extended );
		$snippets = $extended;
		$last = - 1;
		$extract = '';
		foreach ( $snippets as $index => $line ) {
			if ( $last == - 1 ) {
				$extract .= $line; // first line
			} elseif ( $last + 1 == $index
				&& $offsets[$last] + strlen( $snippets[$last] ) >= strlen( $all[$last] )
			) {
				$extract .= " " . $line; // continous lines
			} else {
				$extract .= '<b> ... </b>' . $line;
			}

			$last = $index;
		}
		if ( $extract ) {
			$extract .= '<b> ... </b>';
		}

		$processed = [];
		foreach ( $terms as $term ) {
			if ( !isset( $processed[$term] ) ) {
				$pat3 = "/$patPre(" . $term . ")$patPost/ui"; // highlight word
				$extract = preg_replace( $pat3,
					"\\1<span class='searchmatch'>\\2</span>\\3", $extract );
				$processed[$term] = true;
			}
		}

		return $extract;
	}

	/**
	 * Split text into lines and add it to extracts array
	 *
	 * @param array $extracts Index -> $line
	 * @param int $count
	 * @param string $text
	 */
	function splitAndAdd( &$extracts, &$count, $text ) {
		$split = explode( "\n", $this->mCleanWikitext ? $this->removeWiki( $text ) : $text );
		foreach ( $split as $line ) {
			$tt = trim( $line );
			if ( $tt ) {
				$extracts[$count++] = $tt;
			}
		}
	}

	/**
	 * Do manual case conversion for non-ascii chars
	 *
	 * @param array $matches
	 * @return string
	 */
	function caseCallback( $matches ) {
		global $wgContLang;
		if ( strlen( $matches[0] ) > 1 ) {
			return '[' . $wgContLang->lc( $matches[0] ) . $wgContLang->uc( $matches[0] ) . ']';
		} else {
			return $matches[0];
		}
	}

	/**
	 * Extract part of the text from start to end, but by
	 * not chopping up words
	 * @param string $text
	 * @param int $start
	 * @param int $end
	 * @param int $posStart (out) actual start position
	 * @param int $posEnd (out) actual end position
	 * @return string
	 */
	function extract( $text, $start, $end, &$posStart = null, &$posEnd = null ) {
		if ( $start != 0 ) {
			$start = $this->position( $text, $start, 1 );
		}
		if ( $end >= strlen( $text ) ) {
			$end = strlen( $text );
		} else {
			$end = $this->position( $text, $end );
		}

		if ( !is_null( $posStart ) ) {
			$posStart = $start;
		}
		if ( !is_null( $posEnd ) ) {
			$posEnd = $end;
		}

		if ( $end > $start ) {
			return substr( $text, $start, $end - $start );
		} else {
			return '';
		}
	}

	/**
	 * Find a nonletter near a point (index) in the text
	 *
	 * @param string $text
	 * @param int $point
	 * @param int $offset Offset to found index
	 * @return int Nearest nonletter index, or beginning of utf8 char if none
	 */
	function position( $text, $point, $offset = 0 ) {
		$tolerance = 10;
		$s = max( 0, $point - $tolerance );
		$l = min( strlen( $text ), $point + $tolerance ) - $s;
		$m = [];

		if ( preg_match(
			'/[ ,.!?~!@#$%^&*\(\)+=\-\\\|\[\]"\'<>]/',
			substr( $text, $s, $l ),
			$m,
			PREG_OFFSET_CAPTURE
		) ) {
			return $m[0][1] + $s + $offset;
		} else {
			// check if point is on a valid first UTF8 char
			$char = ord( $text[$point] );
			while ( $char >= 0x80 && $char < 0xc0 ) {
				// skip trailing bytes
				$point++;
				if ( $point >= strlen( $text ) ) {
					return strlen( $text );
				}
				$char = ord( $text[$point] );
			}

			return $point;

		}
	}

	/**
	 * Search extracts for a pattern, and return snippets
	 *
	 * @param string $pattern Regexp for matching lines
	 * @param array $extracts Extracts to search
	 * @param int $linesleft Number of extracts to make
	 * @param int $contextchars Length of snippet
	 * @param array $out Map for highlighted snippets
	 * @param array $offsets Map of starting points of snippets
	 * @protected
	 */
	function process( $pattern, $extracts, &$linesleft, &$contextchars, &$out, &$offsets ) {
		if ( $linesleft == 0 ) {
			return; // nothing to do
		}
		foreach ( $extracts as $index => $line ) {
			if ( array_key_exists( $index, $out ) ) {
				continue; // this line already highlighted
			}

			$m = [];
			if ( !preg_match( $pattern, $line, $m, PREG_OFFSET_CAPTURE ) ) {
				continue;
			}

			$offset = $m[0][1];
			$len = strlen( $m[0][0] );
			if ( $offset + $len < $contextchars ) {
				$begin = 0;
			} elseif ( $len > $contextchars ) {
				$begin = $offset;
			} else {
				$begin = $offset + intval( ( $len - $contextchars ) / 2 );
			}

			$end = $begin + $contextchars;

			$posBegin = $begin;
			// basic snippet from this line
			$out[$index] = $this->extract( $line, $begin, $end, $posBegin );
			$offsets[$index] = $posBegin;
			$linesleft--;
			if ( $linesleft == 0 ) {
				return;
			}
		}
	}

	/**
	 * Basic wikitext removal
	 * @protected
	 * @param string $text
	 * @return mixed
	 */
	function removeWiki( $text ) {
		$text = preg_replace( "/\\{\\{([^|]+?)\\}\\}/", "", $text );
		$text = preg_replace( "/\\{\\{([^|]+\\|)(.*?)\\}\\}/", "\\2", $text );
		$text = preg_replace( "/\\[\\[([^|]+?)\\]\\]/", "\\1", $text );
		$text = preg_replace_callback(
			"/\\[\\[([^|]+\\|)(.*?)\\]\\]/",
			[ $this, 'linkReplace' ],
			$text
		);
		$text = preg_replace( "/<\/?[^>]+>/", "", $text );
		$text = preg_replace( "/'''''/", "", $text );
		$text = preg_replace( "/('''|<\/?[iIuUbB]>)/", "", $text );
		$text = preg_replace( "/''/", "", $text );

		return $text;
	}

	/**
	 * callback to replace [[target|caption]] kind of links, if
	 * the target is category or image, leave it
	 *
	 * @param array $matches
	 * @return string
	 */
	function linkReplace( $matches ) {
		$colon = strpos( $matches[1], ':' );
		if ( $colon === false ) {
			return $matches[2]; // replace with caption
		}
		global $wgContLang;
		$ns = substr( $matches[1], 0, $colon );
		$index = $wgContLang->getNsIndex( $ns );
		if ( $index !== false && ( $index == NS_FILE || $index == NS_CATEGORY ) ) {
			return $matches[0]; // return the whole thing
		} else {
			return $matches[2];
		}
	}

	/**
	 * Simple & fast snippet extraction, but gives completely unrelevant
	 * snippets
	 *
	 * Used when $wgAdvancedSearchHighlighting is false.
	 *
	 * @param string $text
	 * @param array $terms Escaped for regex by SearchDatabase::regexTerm()
	 * @param int $contextlines
	 * @param int $contextchars
	 * @return string
	 */
	public function highlightSimple( $text, $terms, $contextlines, $contextchars ) {
		global $wgContLang;

		$lines = explode( "\n", $text );

		$terms = implode( '|', $terms );
		$max = intval( $contextchars ) + 1;
		$pat1 = "/(.*)($terms)(.{0,$max})/i";

		$lineno = 0;

		$extract = "";
		foreach ( $lines as $line ) {
			if ( 0 == $contextlines ) {
				break;
			}
			++$lineno;
			$m = [];
			if ( !preg_match( $pat1, $line, $m ) ) {
				continue;
			}
			--$contextlines;
			// truncate function changes ... to relevant i18n message.
			$pre = $wgContLang->truncate( $m[1], - $contextchars, '...', false );

			if ( count( $m ) < 3 ) {
				$post = '';
			} else {
				$post = $wgContLang->truncate( $m[3], $contextchars, '...', false );
			}

			$found = $m[2];

			$line = htmlspecialchars( $pre . $found . $post );
			$pat2 = '/(' . $terms . ")/i";
			$line = preg_replace( $pat2, "<span class='searchmatch'>\\1</span>", $line );

			$extract .= "${line}\n";
		}

		return $extract;
	}

	/**
	 * Returns the first few lines of the text
	 *
	 * @param string $text
	 * @param int $contextlines Max number of returned lines
	 * @param int $contextchars Average number of characters per line
	 * @return string
	 */
	public function highlightNone( $text, $contextlines, $contextchars ) {
		$match = [];
		$text = ltrim( $text ) . "\n"; // make sure the preg_match may find the last line
		$text = str_replace( "\n\n", "\n", $text ); // remove empty lines
		preg_match( "/^(.*\n){0,$contextlines}/", $text, $match );

		// Trim and limit to max number of chars
		$text = htmlspecialchars( substr( trim( $match[0] ), 0, $contextlines * $contextchars ) );
		return str_replace( "\n", '<br>', $text );
	}
}
