<?php
/**
 *
 *
 * Created on Sep 19, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

/**
 * API XML output formatter
 * @ingroup API
 */
class ApiFormatXml extends ApiFormatBase {

	private $mRootElemName = 'api';
	public static $namespace = 'http://www.mediawiki.org/xml/api/';
	private $mIncludeNamespace = false;
	private $mXslt = null;

	public function getMimeType() {
		return 'text/xml';
	}

	public function setRootElement( $rootElemName ) {
		$this->mRootElemName = $rootElemName;
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$this->mIncludeNamespace = $params['includexmlnamespace'];
		$this->mXslt = $params['xslt'];

		$this->printText( '<?xml version="1.0"?>' );
		if ( !is_null( $this->mXslt ) ) {
			$this->addXslt();
		}

		$result = $this->getResult();
		if ( $this->mIncludeNamespace && $result->getResultData( 'xmlns' ) === null ) {
			// If the result data already contains an 'xmlns' namespace added
			// for custom XML output types, it will override the one for the
			// generic API results.
			// This allows API output of other XML types like Atom, RSS, RSD.
			$result->addValue( null, 'xmlns', self::$namespace, ApiResult::NO_SIZE_CHECK );
		}
		$data = $result->getResultData( null, [
			'Custom' => function ( &$data, &$metadata ) {
				if ( isset( $metadata[ApiResult::META_TYPE] ) ) {
					// We want to use non-BC for BCassoc to force outputting of _idx.
					switch ( $metadata[ApiResult::META_TYPE] ) {
						case 'BCassoc':
							$metadata[ApiResult::META_TYPE] = 'assoc';
							break;
					}
				}
			},
			'BC' => [ 'nobool', 'no*', 'nosub' ],
			'Types' => [ 'ArmorKVP' => '_name' ],
		] );

		$this->printText(
			static::recXmlPrint( $this->mRootElemName,
				$data,
				$this->getIsHtml() ? -2 : null
			)
		);
	}

	/**
	 * This method takes an array and converts it to XML.
	 *
	 * @param string|null $name Tag name
	 * @param mixed $value Tag value (attributes/content/subelements)
	 * @param int|null $indent Indentation
	 * @param array $attributes Additional attributes
	 * @return string
	 */
	public static function recXmlPrint( $name, $value, $indent, $attributes = [] ) {
		$retval = '';
		if ( $indent !== null ) {
			if ( $name !== null ) {
				$indent += 2;
			}
			$indstr = "\n" . str_repeat( ' ', $indent );
		} else {
			$indstr = '';
		}

		if ( is_object( $value ) ) {
			$value = (array)$value;
		}
		if ( is_array( $value ) ) {
			$contentKey = isset( $value[ApiResult::META_CONTENT] )
				? $value[ApiResult::META_CONTENT]
				: '*';
			$subelementKeys = isset( $value[ApiResult::META_SUBELEMENTS] )
				? $value[ApiResult::META_SUBELEMENTS]
				: [];
			if ( isset( $value[ApiResult::META_BC_SUBELEMENTS] ) ) {
				$subelementKeys = array_merge(
					$subelementKeys, $value[ApiResult::META_BC_SUBELEMENTS]
				);
			}
			$preserveKeys = isset( $value[ApiResult::META_PRESERVE_KEYS] )
				? $value[ApiResult::META_PRESERVE_KEYS]
				: [];
			$indexedTagName = isset( $value[ApiResult::META_INDEXED_TAG_NAME] )
				? self::mangleName( $value[ApiResult::META_INDEXED_TAG_NAME], $preserveKeys )
				: '_v';
			$bcBools = isset( $value[ApiResult::META_BC_BOOLS] )
				? $value[ApiResult::META_BC_BOOLS]
				: [];
			$indexSubelements = isset( $value[ApiResult::META_TYPE] )
				? $value[ApiResult::META_TYPE] !== 'array'
				: false;

			$content = null;
			$subelements = [];
			$indexedSubelements = [];
			foreach ( $value as $k => $v ) {
				if ( ApiResult::isMetadataKey( $k ) && !in_array( $k, $preserveKeys, true ) ) {
					continue;
				}

				$oldv = $v;
				if ( is_bool( $v ) && !in_array( $k, $bcBools, true ) ) {
					$v = $v ? 'true' : 'false';
				}

				if ( $name !== null && $k === $contentKey ) {
					$content = $v;
				} elseif ( is_int( $k ) ) {
					$indexedSubelements[$k] = $v;
				} elseif ( is_array( $v ) || is_object( $v ) ) {
					$subelements[self::mangleName( $k, $preserveKeys )] = $v;
				} elseif ( in_array( $k, $subelementKeys, true ) || $name === null ) {
					$subelements[self::mangleName( $k, $preserveKeys )] = [
						'content' => $v,
						ApiResult::META_CONTENT => 'content',
						ApiResult::META_TYPE => 'assoc',
					];
				} elseif ( is_bool( $oldv ) ) {
					if ( $oldv ) {
						$attributes[self::mangleName( $k, $preserveKeys )] = '';
					}
				} elseif ( $v !== null ) {
					$attributes[self::mangleName( $k, $preserveKeys )] = $v;
				}
			}

			if ( $content !== null ) {
				if ( $subelements || $indexedSubelements ) {
					$subelements[self::mangleName( $contentKey, $preserveKeys )] = [
						'content' => $content,
						ApiResult::META_CONTENT => 'content',
						ApiResult::META_TYPE => 'assoc',
					];
					$content = null;
				} elseif ( is_scalar( $content ) ) {
					// Add xml:space="preserve" to the element so XML parsers
					// will leave whitespace in the content alone
					$attributes += [ 'xml:space' => 'preserve' ];
				}
			}

			if ( $content !== null ) {
				if ( is_scalar( $content ) ) {
					$retval .= $indstr . Xml::element( $name, $attributes, $content );
				} else {
					if ( $name !== null ) {
						$retval .= $indstr . Xml::element( $name, $attributes, null );
					}
					$retval .= static::recXmlPrint( null, $content, $indent );
					if ( $name !== null ) {
						$retval .= $indstr . Xml::closeElement( $name );
					}
				}
			} elseif ( !$indexedSubelements && !$subelements ) {
				if ( $name !== null ) {
					$retval .= $indstr . Xml::element( $name, $attributes );
				}
			} else {
				if ( $name !== null ) {
					$retval .= $indstr . Xml::element( $name, $attributes, null );
				}
				foreach ( $subelements as $k => $v ) {
					$retval .= static::recXmlPrint( $k, $v, $indent );
				}
				foreach ( $indexedSubelements as $k => $v ) {
					$retval .= static::recXmlPrint( $indexedTagName, $v, $indent,
						$indexSubelements ? [ '_idx' => $k ] : []
					);
				}
				if ( $name !== null ) {
					$retval .= $indstr . Xml::closeElement( $name );
				}
			}
		} else {
			// to make sure null value doesn't produce unclosed element,
			// which is what Xml::element( $name, null, null ) returns
			if ( $value === null ) {
				$retval .= $indstr . Xml::element( $name, $attributes );
			} else {
				$retval .= $indstr . Xml::element( $name, $attributes, $value );
			}
		}

		return $retval;
	}

	/**
	 * Mangle XML-invalid names to be valid in XML
	 * @param string $name
	 * @param array $preserveKeys Names to not mangle
	 * @return string Mangled name
	 */
	private static function mangleName( $name, $preserveKeys = [] ) {
		static $nsc = null, $nc = null;

		if ( in_array( $name, $preserveKeys, true ) ) {
			return $name;
		}

		if ( $name === '' ) {
			return '_';
		}

		if ( $nsc === null ) {
			// Note we omit ':' from $nsc and $nc because it's reserved for XML
			// namespacing, and we omit '_' from $nsc (but not $nc) because we
			// reserve it.
			$nsc = 'A-Za-z\x{C0}-\x{D6}\x{D8}-\x{F6}\x{F8}-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}' .
				'\x{200C}-\x{200D}\x{2070}-\x{218F}\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}' .
				'\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}';
			$nc = $nsc . '_\-.0-9\x{B7}\x{300}-\x{36F}\x{203F}-\x{2040}';
		}

		if ( preg_match( "/^[$nsc][$nc]*$/uS", $name ) ) {
			return $name;
		}

		return '_' . preg_replace_callback(
			"/[^$nc]/uS",
			function ( $m ) {
				return sprintf( '.%X.', UtfNormal\Utils::utf8ToCodepoint( $m[0] ) );
			},
			str_replace( '.', '.2E.', $name )
		);
	}

	protected function addXslt() {
		$nt = Title::newFromText( $this->mXslt );
		if ( is_null( $nt ) || !$nt->exists() ) {
			$this->addWarning( 'apiwarn-invalidxmlstylesheet' );

			return;
		}
		if ( $nt->getNamespace() != NS_MEDIAWIKI ) {
			$this->addWarning( 'apiwarn-invalidxmlstylesheetns' );

			return;
		}
		if ( substr( $nt->getText(), -4 ) !== '.xsl' ) {
			$this->addWarning( 'apiwarn-invalidxmlstylesheetext' );

			return;
		}
		$this->printText( '<?xml-stylesheet href="' .
			htmlspecialchars( $nt->getLocalURL( 'action=raw' ) ) . '" type="text/xsl" ?>' );
	}

	public function getAllowedParams() {
		return parent::getAllowedParams() + [
			'xslt' => [
				ApiBase::PARAM_HELP_MSG => 'apihelp-xml-param-xslt',
			],
			'includexmlnamespace' => [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-xml-param-includexmlnamespace',
			],
		];
	}
}
