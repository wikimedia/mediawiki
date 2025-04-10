<?php
/**
 * Holder for stripped items when parsing wiki markup.
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
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use Closure;
use InvalidArgumentException;
use Wikimedia\Parsoid\Fragments\PFragment;

/**
 * @todo document, briefly.
 * @newable
 * @ingroup Parser
 */
class StripState {
	/** @var array[] */
	protected $data;
	/** @var array[] */
	protected $extra;
	/** @var string */
	protected $regex;

	protected ?Parser $parser;

	/** @var array */
	protected $circularRefGuard;
	/** @var int */
	protected $depth = 0;
	/** @var int */
	protected $highestDepth = 0;
	/** @var int */
	protected $expandSize = 0;

	/** @var int */
	protected $depthLimit = 20;
	/** @var int */
	protected $sizeLimit = 5_000_000;

	/**
	 * @stable to call
	 *
	 * @param Parser|null $parser
	 * @param array $options
	 */
	public function __construct( ?Parser $parser = null, $options = [] ) {
		$this->data = [
			'nowiki' => [],
			'general' => []
		];
		$this->extra = [];
		$this->regex = '/' . Parser::MARKER_PREFIX . "([^\x7f<>&'\"]+)" . Parser::MARKER_SUFFIX . '/';
		$this->circularRefGuard = [];
		$this->parser = $parser;

		if ( isset( $options['depthLimit'] ) ) {
			$this->depthLimit = $options['depthLimit'];
		}
		if ( isset( $options['sizeLimit'] ) ) {
			$this->sizeLimit = $options['sizeLimit'];
		}
	}

	/**
	 * Add a nowiki strip item
	 * @param string $marker
	 * @param string|Closure $value
	 * @param ?string $extra Used to distinguish items added by <nowiki>
	 *  from items added by other mechanisms.
	 */
	public function addNoWiki( $marker, $value, ?string $extra = null ) {
		$this->addItem( 'nowiki', $marker, $value, $extra );
	}

	/**
	 * @param string $marker
	 * @param string|Closure $value
	 */
	public function addGeneral( $marker, $value ) {
		$this->addItem( 'general', $marker, $value );
	}

	/**
	 * @param string $marker
	 * @param string|Closure $value
	 * @since 1.44
	 * @internal Parsoid use only.
	 */
	public function addExtTag( $marker, $value ) {
		$this->addItem( 'exttag', $marker, $value );
	}

	/**
	 * @param string $marker
	 * @param PFragment $extra
	 * @since 1.44
	 * @internal Parsoid use only.
	 */
	public function addParsoidOpaque( $marker, PFragment $extra ) {
		$this->addItem( 'parsoid', $marker, '<parsoid opaque>', $extra );
	}

	/**
	 * @param string $type
	 * @param-taint $type none
	 * @param string $marker
	 * @param-taint $marker none
	 * @param string|Closure $value
	 * @param string|PFragment|null $extra
	 * @param-taint $value exec_html
	 */
	protected function addItem( $type, $marker, $value, $extra = null ) {
		if ( !preg_match( $this->regex, $marker, $m ) ) {
			throw new InvalidArgumentException( "Invalid marker: $marker" );
		}

		$this->data[$type][$m[1]] = $value;
		if ( $extra !== null ) {
			$this->extra[$type][$m[1]] = $extra;
		}
	}

	/**
	 * @param string $text
	 * @return mixed
	 */
	public function unstripGeneral( $text ) {
		return $this->unstripType( 'general', $text );
	}

	/**
	 * @param string $text
	 * @return mixed
	 */
	public function unstripNoWiki( $text ) {
		return $this->unstripType( 'nowiki', $text );
	}

	/**
	 * @param string $text
	 * @param callable $callback
	 * @return string
	 * @unstable Temporarily used by Scribunto
	 */
	public function replaceNoWikis( string $text, callable $callback ): string {
		// Shortcut
		if ( !count( $this->data['nowiki'] ) ) {
			return $text;
		}

		$callback = function ( $m ) use ( $callback ) {
			$marker = $m[1];
			if ( isset( $this->data['nowiki'][$marker] ) ) {
				$value = $this->data['nowiki'][$marker];
				if ( $value instanceof Closure ) {
					$value = $value();
				}

				$this->expandSize += strlen( $value );
				if ( $this->expandSize > $this->sizeLimit ) {
					return $this->getLimitationWarning( 'unstrip-size', $this->sizeLimit );
				}

				return $callback( $value );
			} else {
				return $m[0];
			}
		};

		return preg_replace_callback( $this->regex, $callback, $text );
	}

	/**
	 * Split the given text by strip markers, returning an array
	 * of [ 'type' => ..., 'content' => ... ] elements. The type
	 * property is:
	 * - 'string' for plain strings and 'exttag' strip markers
	 * - otherwise, name of the strip marker that generated the 'content' value
	 * @return array<string|array{type:string,content:string}>
	 */
	public function split( string $text ): array {
		$result = [];
		$pieces = preg_split( $this->regex, $text, -1, PREG_SPLIT_DELIM_CAPTURE );
		for ( $i = 0; $i < count( $pieces ); $i++ ) {
			if ( $i % 2 === 0 ) {
				$result[] = [
					'type' => 'string',
					'content' => $pieces[$i],
				];
				continue;
			}
			$marker = $pieces[$i];
			foreach ( $this->data as $type => $items ) {
				if ( isset( $items[$marker] ) ) {
					$value = $items[$marker];
					$extra = $this->extra[$type][$marker] ?? null;
					if ( $value instanceof Closure ) {
						$value = $value();
					}

					if ( $type === 'exttag' ) {
						// Catch circular refs / enforce depth limits
						// similar to code in unstripType().
						if ( isset( $this->circularRefGuard[$marker] ) ) {
							$result[] = [
								'type' => 'string',
								'content' => $this->getWarning( 'parser-unstrip-loop-warning' )
							];
							continue;
						}

						if ( $this->depth > $this->highestDepth ) {
							$this->highestDepth = $this->depth;
						}
						if ( $this->depth >= $this->depthLimit ) {
							$result[] = [
								'type' => 'string',
								'content' => $this->getLimitationWarning( 'unstrip-depth', $this->depthLimit )
							];
							continue;
						}

						// For exttag types, the output size should include the output of
						// the extension, but don't think unstripType is doing that, and so
						// we aren't doing that either here. But this is kinda broken.
						// See T380758#10355050.
						$this->expandSize += strlen( $value );
						if ( $this->expandSize > $this->sizeLimit ) {
							$result[] = [
								'type' => 'string',
								'content' => $this->getLimitationWarning( 'unstrip-size', $this->sizeLimit )
							];
							continue;
						}

						$this->circularRefGuard[$marker] = true;
						$this->depth++;
						$result = array_merge( $result, $this->split( $value ) );
						$this->depth--;
						unset( $this->circularRefGuard[$marker] );
					} else {
						$result[] = [
							'type' => $type,
							'content' => $value,
							'extra' => $extra,
							'marker' => Parser::MARKER_PREFIX . $marker . Parser::MARKER_SUFFIX,
						];
					}
					continue 2;
				}
			}
			$result[] = [
				'type' => 'unknown',
				'content' => null,
				'marker' => Parser::MARKER_PREFIX . $marker . Parser::MARKER_SUFFIX,
			];
		}
		return $result;
	}

	/**
	 * @param string $text
	 * @return mixed
	 */
	public function unstripBoth( $text ) {
		$text = $this->unstripType( 'general', $text );
		$text = $this->unstripType( 'nowiki', $text );
		return $text;
	}

	/**
	 * @param string $type
	 * @param string $text
	 * @return mixed
	 */
	protected function unstripType( $type, $text ) {
		// Shortcut
		if ( !count( $this->data[$type] ) ) {
			return $text;
		}

		$callback = function ( $m ) use ( $type ) {
			$marker = $m[1];
			if ( isset( $this->data[$type][$marker] ) ) {
				if ( isset( $this->circularRefGuard[$marker] ) ) {
					return $this->getWarning( 'parser-unstrip-loop-warning' );
				}

				if ( $this->depth > $this->highestDepth ) {
					$this->highestDepth = $this->depth;
				}
				if ( $this->depth >= $this->depthLimit ) {
					return $this->getLimitationWarning( 'unstrip-depth', $this->depthLimit );
				}

				$value = $this->data[$type][$marker];
				if ( $value instanceof Closure ) {
					$value = $value();
				}

				$this->expandSize += strlen( $value );
				if ( $this->expandSize > $this->sizeLimit ) {
					return $this->getLimitationWarning( 'unstrip-size', $this->sizeLimit );
				}

				$this->circularRefGuard[$marker] = true;
				$this->depth++;
				$ret = $this->unstripType( $type, $value );
				$this->depth--;
				unset( $this->circularRefGuard[$marker] );

				return $ret;
			} else {
				return $m[0];
			}
		};

		$text = preg_replace_callback( $this->regex, $callback, $text );
		return $text;
	}

	/**
	 * Get warning HTML and register a limitation warning with the parser
	 *
	 * @param string $type
	 * @param int|string $max
	 * @return string
	 */
	private function getLimitationWarning( $type, $max = '' ) {
		if ( $this->parser ) {
			$this->parser->limitationWarn( $type, $max );
		}
		return $this->getWarning( "$type-warning", $max );
	}

	/**
	 * Get warning HTML
	 *
	 * @param string $message
	 * @param int|string $max
	 * @return string
	 */
	private function getWarning( $message, $max = '' ) {
		return '<span class="error">' .
			wfMessage( $message )
				->numParams( $max )->inContentLanguage()->text() .
			'</span>';
	}

	/**
	 * Get an array of parameters to pass to ParserOutput::setLimitReportData()
	 *
	 * @internal Should only be called by Parser
	 * @return array
	 */
	public function getLimitReport() {
		return [
			[ 'limitreport-unstrip-depth',
				[
					$this->highestDepth,
					$this->depthLimit
				],
			],
			[ 'limitreport-unstrip-size',
				[
					$this->expandSize,
					$this->sizeLimit
				],
			]
		];
	}

	/**
	 * Remove any strip markers found in the given text.
	 *
	 * @param string $text
	 * @return string
	 */
	public function killMarkers( $text ) {
		return preg_replace( $this->regex, '', $text );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( StripState::class, 'StripState' );
