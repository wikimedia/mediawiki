<?php
/**
 *
 *
 * Created on Sep 19, 2006
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiFormatBase.php' );
}

/**
 * API XML output formatter
 * @ingroup API
 */
class ApiFormatXml extends ApiFormatBase {

	private $mRootElemName = 'api';
	private $mDoubleQuote = false;
	private $mXslt = null;

	public function __construct( $main, $format ) {
		parent::__construct( $main, $format );
	}

	public function getMimeType() {
		return 'text/xml';
	}

	public function getNeedsRawData() {
		return true;
	}

	public function setRootElement( $rootElemName ) {
		$this->mRootElemName = $rootElemName;
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$this->mDoubleQuote = $params['xmldoublequote'];
		$this->mXslt = $params['xslt'];

		$this->printText( '<?xml version="1.0"?>' );
		if ( !is_null( $this->mXslt ) ) {
			$this->addXslt();
		}
		$this->printText(
			self::recXmlPrint( $this->mRootElemName,
				$this->getResultData(),
				$this->getIsHtml() ? - 2 : null,
				$this->mDoubleQuote
			)
		);
	}

	/**
	 * This method takes an array and converts it to XML.
	 * There are several noteworthy cases:
	 *
	 *  If array contains a key '_element', then the code assumes that ALL other keys are not important and replaces them with the value['_element'].
	 *	Example:	name='root',  value = array( '_element'=>'page', 'x', 'y', 'z') creates <root>  <page>x</page>  <page>y</page>  <page>z</page> </root>
	 *
	 *  If any of the array's element key is '*', then the code treats all other key->value pairs as attributes, and the value['*'] as the element's content.
	 *	Example:	name='root',  value = array( '*'=>'text', 'lang'=>'en', 'id'=>10)   creates  <root lang='en' id='10'>text</root>
	 *
	 * If neither key is found, all keys become element names, and values become element content.
	 * The method is recursive, so the same rules apply to any sub-arrays.
	 */
	public static function recXmlPrint( $elemName, $elemValue, $indent, $doublequote = false ) {
		$retval = '';
		if ( !is_null( $indent ) ) {
			$indent += 2;
			$indstr = "\n" . str_repeat( ' ', $indent );
		} else {
			$indstr = '';
		}
		$elemName = str_replace( ' ', '_', $elemName );

		switch ( gettype( $elemValue ) ) {
			case 'array':
				if ( isset( $elemValue['*'] ) ) {
					$subElemContent = $elemValue['*'];
					if ( $doublequote ) {
						$subElemContent = Sanitizer::encodeAttribute( $subElemContent );
					}
					unset( $elemValue['*'] );

					// Add xml:space="preserve" to the
					// element so XML parsers will leave
					// whitespace in the content alone
					$elemValue['xml:space'] = 'preserve';
				} else {
					$subElemContent = null;
				}

				if ( isset( $elemValue['_element'] ) ) {
					$subElemIndName = $elemValue['_element'];
					unset( $elemValue['_element'] );
				} else {
					$subElemIndName = null;
				}

				$indElements = array();
				$subElements = array();
				foreach ( $elemValue as $subElemId => & $subElemValue ) {
					if ( is_string( $subElemValue ) && $doublequote ) {
						$subElemValue = Sanitizer::encodeAttribute( $subElemValue );
					}

					if ( gettype( $subElemId ) === 'integer' ) {
						$indElements[] = $subElemValue;
						unset( $elemValue[$subElemId] );
					} elseif ( is_array( $subElemValue ) ) {
						$subElements[$subElemId] = $subElemValue;
						unset ( $elemValue[$subElemId] );
					}
				}

				if ( is_null( $subElemIndName ) && count( $indElements ) ) {
					ApiBase::dieDebug( __METHOD__, "($elemName, ...) has integer keys without _element value. Use ApiResult::setIndexedTagName()." );
				}

				if ( count( $subElements ) && count( $indElements ) && !is_null( $subElemContent ) ) {
					ApiBase::dieDebug( __METHOD__, "($elemName, ...) has content and subelements" );
				}

				if ( !is_null( $subElemContent ) ) {
					$retval .= $indstr . Xml::element( $elemName, $elemValue, $subElemContent );
				} elseif ( !count( $indElements ) && !count( $subElements ) ) {
					$retval .= $indstr . Xml::element( $elemName, $elemValue );
				} else {
					$retval .= $indstr . Xml::element( $elemName, $elemValue, null );

					foreach ( $subElements as $subElemId => & $subElemValue ) {
						$retval .= self::recXmlPrint( $subElemId, $subElemValue, $indent );
					}

					foreach ( $indElements as &$subElemValue ) {
						$retval .= self::recXmlPrint( $subElemIndName, $subElemValue, $indent );
					}

					$retval .= $indstr . Xml::closeElement( $elemName );
				}
				break;
			case 'object':
				// ignore
				break;
			default:
				$retval .= $indstr . Xml::element( $elemName, null, $elemValue );
				break;
		}
		return $retval;
	}

	function addXslt() {
		$nt = Title::newFromText( $this->mXslt );
		if ( is_null( $nt ) || !$nt->exists() ) {
			$this->setWarning( 'Invalid or non-existent stylesheet specified' );
			return;
		}
		if ( $nt->getNamespace() != NS_MEDIAWIKI ) {
			$this->setWarning( 'Stylesheet should be in the MediaWiki namespace.' );
			return;
		}
		if ( substr( $nt->getText(), - 4 ) !== '.xsl' ) {
			$this->setWarning( 'Stylesheet should have .xsl extension.' );
			return;
		}
		$this->printText( '<?xml-stylesheet href="' . $nt->escapeLocalURL( 'action=raw' ) . '" type="text/xsl" ?>' );
	}

	public function getAllowedParams() {
		return array(
			'xmldoublequote' => false,
			'xslt' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'xmldoublequote' => 'If specified, double quotes all attributes and content',
			'xslt' => 'If specified, adds <xslt> as stylesheet',
		);
	}

	public function getDescription() {
		return 'Output data in XML format' . parent::getDescription();
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
