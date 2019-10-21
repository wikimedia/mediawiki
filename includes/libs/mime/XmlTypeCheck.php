<?php
/**
 * XML syntax and type checker.
 *
 * Since 1.24.2, it uses XMLReader instead of xml_parse, which gives us
 * more control over the expansion of XML entities. When passed to the
 * callback, entities will be fully expanded, but may report the XML is
 * invalid if expanding the entities are likely to cause a DoS.
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

class XmlTypeCheck {
	/**
	 * @var bool|null Will be set to true or false to indicate whether the file is
	 * well-formed XML. Note that this doesn't check schema validity.
	 */
	public $wellFormed = null;

	/**
	 * @var bool Will be set to true if the optional element filter returned
	 * a match at some point.
	 */
	public $filterMatch = false;

	/**
	 * Will contain the type of filter hit if the optional element filter returned
	 * a match at some point.
	 * @var mixed
	 */
	public $filterMatchType = false;

	/**
	 * @var string Name of the document's root element, including any namespace
	 * as an expanded URL.
	 */
	public $rootElement = '';

	/**
	 * @var string[] A stack of strings containing the data of each xml element as it's processed.
	 * Append data to the top string of the stack, then pop off the string and process it when the
	 * element is closed.
	 */
	protected $elementData = [];

	/**
	 * @var array A stack of element names and attributes, as we process them.
	 */
	protected $elementDataContext = [];

	/**
	 * @var int Current depth of the data stack.
	 */
	protected $stackDepth = 0;

	/** @var callable|null */
	protected $filterCallback;

	/**
	 * @var array Additional parsing options
	 */
	private $parserOptions = [
		'processing_instruction_handler' => null,
		'external_dtd_handler' => '',
		'dtd_handler' => '',
		'require_safe_dtd' => true
	];

	/**
	 * Allow filtering an XML file.
	 *
	 * Filters should return either true or a string to indicate something
	 * is wrong with the file. $this->filterMatch will store if the
	 * file failed validation (true = failed validation).
	 * $this->filterMatchType will contain the validation error.
	 * $this->wellFormed will contain whether the xml file is well-formed.
	 *
	 * @note If multiple filters are hit, only one of them will have the
	 *  result stored in $this->filterMatchType.
	 *
	 * @param string $input a filename or string containing the XML element
	 * @param callable|null $filterCallback (optional)
	 *        Function to call to do additional custom validity checks from the
	 *        SAX element handler event. This gives you access to the element
	 *        namespace, name, attributes, and text contents.
	 *        Filter should return a truthy value describing the error.
	 * @param bool $isFile (optional) indicates if the first parameter is a
	 *        filename (default, true) or if it is a string (false)
	 * @param array $options list of additional parsing options:
	 *        processing_instruction_handler: Callback for xml_set_processing_instruction_handler
	 *        external_dtd_handler: Callback for the url of external dtd subset
	 *        dtd_handler: Callback given the full text of the <!DOCTYPE declaration.
	 *        require_safe_dtd: Only allow non-recursive entities in internal dtd (default true)
	 */
	function __construct( $input, $filterCallback = null, $isFile = true, $options = [] ) {
		$this->filterCallback = $filterCallback;
		$this->parserOptions = array_merge( $this->parserOptions, $options );
		$this->validateFromInput( $input, $isFile );
	}

	/**
	 * Alternative constructor: from filename
	 *
	 * @param string $fname the filename of an XML document
	 * @param callable|null $filterCallback (optional)
	 *        Function to call to do additional custom validity checks from the
	 *        SAX element handler event. This gives you access to the element
	 *        namespace, name, and attributes, but not to text contents.
	 *        Filter should return 'true' to toggle on $this->filterMatch
	 * @return XmlTypeCheck
	 */
	public static function newFromFilename( $fname, $filterCallback = null ) {
		return new self( $fname, $filterCallback, true );
	}

	/**
	 * Alternative constructor: from string
	 *
	 * @param string $string a string containing an XML element
	 * @param callable|null $filterCallback (optional)
	 *        Function to call to do additional custom validity checks from the
	 *        SAX element handler event. This gives you access to the element
	 *        namespace, name, and attributes, but not to text contents.
	 *        Filter should return 'true' to toggle on $this->filterMatch
	 * @return XmlTypeCheck
	 */
	public static function newFromString( $string, $filterCallback = null ) {
		return new self( $string, $filterCallback, false );
	}

	/**
	 * Get the root element. Simple accessor to $rootElement
	 *
	 * @return string
	 */
	public function getRootElement() {
		return $this->rootElement;
	}

	/**
	 * @param string $xml
	 * @param bool $isFile
	 */
	private function validateFromInput( $xml, $isFile ) {
		$reader = new XMLReader();
		if ( $isFile ) {
			$s = $reader->open( $xml, null, LIBXML_NOERROR | LIBXML_NOWARNING );
		} else {
			$s = $reader->XML( $xml, null, LIBXML_NOERROR | LIBXML_NOWARNING );
		}
		if ( $s !== true ) {
			// Couldn't open the XML
			$this->wellFormed = false;
		} else {
			$oldDisable = libxml_disable_entity_loader( true );
			$reader->setParserProperty( XMLReader::SUBST_ENTITIES, true );
			try {
				$this->validate( $reader );
			} catch ( Exception $e ) {
				// Calling this malformed, because we didn't parse the whole
				// thing. Maybe just an external entity refernce.
				$this->wellFormed = false;
				$reader->close();
				libxml_disable_entity_loader( $oldDisable );
				throw $e;
			}
			$reader->close();
			libxml_disable_entity_loader( $oldDisable );
		}
	}

	private function readNext( XMLReader $reader ) {
		set_error_handler( [ $this, 'XmlErrorHandler' ] );
		$ret = $reader->read();
		restore_error_handler();
		return $ret;
	}

	public function XmlErrorHandler( $errno, $errstr ) {
		$this->wellFormed = false;
	}

	private function validate( $reader ) {
		// First, move through anything that isn't an element, and
		// handle any processing instructions with the callback
		do {
			if ( !$this->readNext( $reader ) ) {
				// Hit the end of the document before any elements
				$this->wellFormed = false;
				return;
			}
			if ( $reader->nodeType === XMLReader::PI ) {
				$this->processingInstructionHandler( $reader->name, $reader->value );
			}
			if ( $reader->nodeType === XMLReader::DOC_TYPE ) {
				$this->DTDHandler( $reader );
			}
		} while ( $reader->nodeType != XMLReader::ELEMENT );

		// Process the rest of the document
		do {
			switch ( $reader->nodeType ) {
				case XMLReader::ELEMENT:
					$name = $this->expandNS(
						$reader->name,
						$reader->namespaceURI
					);
					if ( $this->rootElement === '' ) {
						$this->rootElement = $name;
					}
					$empty = $reader->isEmptyElement;
					$attrs = $this->getAttributesArray( $reader );
					$this->elementOpen( $name, $attrs );
					if ( $empty ) {
						$this->elementClose();
					}
					break;

				case XMLReader::END_ELEMENT:
					$this->elementClose();
					break;

				case XMLReader::WHITESPACE:
				case XMLReader::SIGNIFICANT_WHITESPACE:
				case XMLReader::CDATA:
				case XMLReader::TEXT:
					$this->elementData( $reader->value );
					break;

				case XMLReader::ENTITY_REF:
					// Unexpanded entity (maybe external?),
					// don't send to the filter (xml_parse didn't)
					break;

				case XMLReader::COMMENT:
					// Don't send to the filter (xml_parse didn't)
					break;

				case XMLReader::PI:
					// Processing instructions can happen after the header too
					$this->processingInstructionHandler(
						$reader->name,
						$reader->value
					);
					break;
				case XMLReader::DOC_TYPE:
					// We should never see a doctype after first
					// element.
					$this->wellFormed = false;
					break;
				default:
					// One of DOC, ENTITY, END_ENTITY,
					// NOTATION, or XML_DECLARATION
					// xml_parse didn't send these to the filter, so we won't.
			}
		} while ( $this->readNext( $reader ) );

		if ( $this->stackDepth !== 0 ) {
			$this->wellFormed = false;
		} elseif ( $this->wellFormed === null ) {
			$this->wellFormed = true;
		}
	}

	/**
	 * Get all of the attributes for an XMLReader's current node
	 * @param XMLReader $r
	 * @return array of attributes
	 */
	private function getAttributesArray( XMLReader $r ) {
		$attrs = [];
		while ( $r->moveToNextAttribute() ) {
			if ( $r->namespaceURI === 'http://www.w3.org/2000/xmlns/' ) {
				// XMLReader treats xmlns attributes as normal
				// attributes, while xml_parse doesn't
				continue;
			}
			$name = $this->expandNS( $r->name, $r->namespaceURI );
			$attrs[$name] = $r->value;
		}
		return $attrs;
	}

	/**
	 * @param string $name element or attribute name, maybe with a full or short prefix
	 * @param string $namespaceURI
	 * @return string the name prefixed with namespaceURI
	 */
	private function expandNS( $name, $namespaceURI ) {
		if ( $namespaceURI ) {
			$parts = explode( ':', $name );
			$localname = array_pop( $parts );
			return "$namespaceURI:$localname";
		}
		return $name;
	}

	/**
	 * @param string $name
	 * @param array $attribs
	 */
	private function elementOpen( $name, $attribs ) {
		$this->elementDataContext[] = [ $name, $attribs ];
		$this->elementData[] = '';
		$this->stackDepth++;
	}

	private function elementClose() {
		list( $name, $attribs ) = array_pop( $this->elementDataContext );
		$data = array_pop( $this->elementData );
		$this->stackDepth--;
		$callbackReturn = false;

		if ( is_callable( $this->filterCallback ) ) {
			$callbackReturn = call_user_func(
				$this->filterCallback,
				$name,
				$attribs,
				$data
			);
		}
		if ( $callbackReturn ) {
			// Filter hit!
			$this->filterMatch = true;
			$this->filterMatchType = $callbackReturn;
		}
	}

	/**
	 * @param string $data
	 */
	private function elementData( $data ) {
		// Collect any data here, and we'll run the callback in elementClose
		$this->elementData[ $this->stackDepth - 1 ] .= trim( $data );
	}

	/**
	 * @param string $target
	 * @param string $data
	 */
	private function processingInstructionHandler( $target, $data ) {
		$callbackReturn = false;
		if ( $this->parserOptions['processing_instruction_handler'] ) {
			$callbackReturn = call_user_func(
				$this->parserOptions['processing_instruction_handler'],
				$target,
				$data
			);
		}
		if ( $callbackReturn ) {
			// Filter hit!
			$this->filterMatch = true;
			$this->filterMatchType = $callbackReturn;
		}
	}

	/**
	 * Handle coming across a <!DOCTYPE declaration.
	 *
	 * @param XMLReader $reader Reader currently pointing at DOCTYPE node.
	 */
	private function DTDHandler( XMLReader $reader ) {
		$externalCallback = $this->parserOptions['external_dtd_handler'];
		$generalCallback = $this->parserOptions['dtd_handler'];
		$checkIfSafe = $this->parserOptions['require_safe_dtd'];
		if ( !$externalCallback && !$generalCallback && !$checkIfSafe ) {
			return;
		}
		$dtd = $reader->readOuterXml();
		$callbackReturn = false;

		if ( $generalCallback ) {
			$callbackReturn = call_user_func( $generalCallback, $dtd );
		}
		if ( $callbackReturn ) {
			// Filter hit!
			$this->filterMatch = true;
			$this->filterMatchType = $callbackReturn;
			$callbackReturn = false;
		}

		$parsedDTD = $this->parseDTD( $dtd );
		if ( $externalCallback && isset( $parsedDTD['type'] ) ) {
			$callbackReturn = call_user_func(
				$externalCallback,
				$parsedDTD['type'],
				$parsedDTD['publicid'] ?? null,
				$parsedDTD['systemid'] ?? null
			);
		}
		if ( $callbackReturn ) {
			// Filter hit!
			$this->filterMatch = true;
			$this->filterMatchType = $callbackReturn;
			$callbackReturn = false;
		}

		if ( $checkIfSafe && isset( $parsedDTD['internal'] ) &&
			!$this->checkDTDIsSafe( $parsedDTD['internal'] )
		) {
			$this->wellFormed = false;
		}
	}

	/**
	 * Check if the internal subset of the DTD is safe.
	 *
	 * We whitelist an extremely restricted subset of DTD features.
	 *
	 * Safe is defined as:
	 *  * Only contains entity definitions (e.g. No <!ATLIST )
	 *  * Entity definitions are not "system" entities
	 *  * Entity definitions are not "parameter" (i.e. %) entities
	 *  * Entity definitions do not reference other entities except &amp;
	 *    and quotes. Entity aliases (where the entity contains only
	 *    another entity are allowed)
	 *  * Entity references aren't overly long (>255 bytes).
	 *  * <!ATTLIST svg xmlns:xlink CDATA #FIXED "http://www.w3.org/1999/xlink">
	 *    allowed if matched exactly for compatibility with graphviz
	 *  * Comments.
	 *
	 * @param string $internalSubset The internal subset of the DTD
	 * @return bool true if safe.
	 */
	private function checkDTDIsSafe( $internalSubset ) {
		$offset = 0;
		$res = preg_match(
			'/^(?:\s*<!ENTITY\s+\S+\s+' .
				'(?:"(?:&[^"%&;]{1,64};|(?:[^"%&]|&amp;|&quot;){0,255})"' .
				'|\'(?:&[^"%&;]{1,64};|(?:[^\'%&]|&amp;|&apos;){0,255})\')\s*>' .
				'|\s*<!--(?:[^-]|-[^-])*-->' .
				'|\s*<!ATTLIST svg xmlns:xlink CDATA #FIXED ' .
				'"http:\/\/www.w3.org\/1999\/xlink">)*\s*$/',
			$internalSubset
		);

		return (bool)$res;
	}

	/**
	 * Parse DTD into parts.
	 *
	 * If there is an error parsing the dtd, sets wellFormed to false.
	 *
	 * @param string $dtd
	 * @return array Possibly containing keys publicid, systemid, type and internal.
	 */
	private function parseDTD( $dtd ) {
		$m = [];
		$res = preg_match(
			'/^<!DOCTYPE\s*\S+\s*' .
			'(?:(?P<typepublic>PUBLIC)\s*' .
				'(?:"(?P<pubquote>[^"]*)"|\'(?P<pubapos>[^\']*)\')' . // public identifer
				'\s*"(?P<pubsysquote>[^"]*)"|\'(?P<pubsysapos>[^\']*)\'' . // system identifier
			'|(?P<typesystem>SYSTEM)\s*' .
				'(?:"(?P<sysquote>[^"]*)"|\'(?P<sysapos>[^\']*)\')' .
			')?\s*' .
			'(?:\[\s*(?P<internal>.*)\])?\s*>$/s',
			$dtd,
			$m
		);
		if ( !$res ) {
			$this->wellFormed = false;
			return [];
		}
		$parsed = [];
		foreach ( $m as $field => $value ) {
			if ( $value === '' || is_numeric( $field ) ) {
				continue;
			}
			switch ( $field ) {
				case 'typepublic':
				case 'typesystem':
					$parsed['type'] = $value;
					break;
				case 'pubquote':
				case 'pubapos':
					$parsed['publicid'] = $value;
					break;
				case 'pubsysquote':
				case 'pubsysapos':
				case 'sysquote':
				case 'sysapos':
					$parsed['systemid'] = $value;
					break;
				case 'internal':
					$parsed['internal'] = $value;
					break;
			}
		}
		return $parsed;
	}
}
