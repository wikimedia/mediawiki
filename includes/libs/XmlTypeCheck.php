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
	 * Will be set to true or false to indicate whether the file is
	 * well-formed XML. Note that this doesn't check schema validity.
	 */
	public $wellFormed = null;

	/**
	 * Will be set to true if the optional element filter returned
	 * a match at some point.
	 */
	public $filterMatch = false;

	/**
	 * Name of the document's root element, including any namespace
	 * as an expanded URL.
	 */
	public $rootElement = '';

	/**
	 * A stack of strings containing the data of each xml element as it's processed. Append
	 * data to the top string of the stack, then pop off the string and process it when the
	 * element is closed.
	 */
	protected $elementData = array();

	/**
	 * A stack of element names and attributes, as we process them.
	 */
	protected $elementDataContext = array();

	/**
	 * Current depth of the data stack.
	 */
	protected $stackDepth = 0;

	/**
	 * Additional parsing options
	 */
	private $parserOptions = array(
		'processing_instruction_handler' => '',
	);

	/**
	 * @param string $input a filename or string containing the XML element
	 * @param callable $filterCallback (optional)
	 *        Function to call to do additional custom validity checks from the
	 *        SAX element handler event. This gives you access to the element
	 *        namespace, name, attributes, and text contents.
	 *        Filter should return 'true' to toggle on $this->filterMatch
	 * @param bool $isFile (optional) indicates if the first parameter is a
	 *        filename (default, true) or if it is a string (false)
	 * @param array $options list of additional parsing options:
	 *        processing_instruction_handler: Callback for xml_set_processing_instruction_handler
	 */
	function __construct( $input, $filterCallback = null, $isFile = true, $options = array() ) {
		$this->filterCallback = $filterCallback;
		$this->parserOptions = array_merge( $this->parserOptions, $options );
		$this->validateFromInput( $input, $isFile );
	}

	/**
	 * Alternative constructor: from filename
	 *
	 * @param string $fname the filename of an XML document
	 * @param callable $filterCallback (optional)
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
	 * @param callable $filterCallback (optional)
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
	 * @param string $fname the filename
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
		set_error_handler( array( $this, 'XmlErrorHandler' ) );
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
			if( !$this->readNext( $reader ) ) {
				// Hit the end of the document before any elements
				$this->wellFormed = false;
				return;
			}
			if ( $reader->nodeType === XMLReader::PI ) {
				$this->processingInstructionHandler( $reader->name, $reader->value );
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
				default:
					// One of DOC, DOC_TYPE, ENTITY, END_ENTITY,
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
	 * @param $r XMLReader
	 * @return array of attributes
	 */
	private function getAttributesArray( XMLReader $r ) {
		$attrs = array();
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
	 * @param $name element or attribute name, maybe with a full or short prefix
	 * @param $namespaceURI the namespaceURI
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
	 * @param $name
	 * @param $attribs
	 */
	private function elementOpen( $name, $attribs ) {
		$this->elementDataContext[] = array( $name, $attribs );
		$this->elementData[] = '';
		$this->stackDepth++;
	}

	/**
	 */
	private function elementClose() {
		list( $name, $attribs ) = array_pop( $this->elementDataContext );
		$data = array_pop( $this->elementData );
		$this->stackDepth--;

		if ( is_callable( $this->filterCallback )
			&& call_user_func(
				$this->filterCallback,
				$name,
				$attribs,
				$data
			)
		) {
			// Filter hit
			$this->filterMatch = true;
		}
	}

	/**
	 * @param $data
	 */
	private function elementData( $data ) {
		// Collect any data here, and we'll run the callback in elementClose
		$this->elementData[ $this->stackDepth - 1 ] .= trim( $data );
	}

	/**
	 * @param $target
	 * @param $data
	 */
	private function processingInstructionHandler( $target, $data ) {
		if ( $this->parserOptions['processing_instruction_handler'] ) {
			if ( call_user_func(
				$this->parserOptions['processing_instruction_handler'],
				$target,
				$data
			) ) {
				// Filter hit!
				$this->filterMatch = true;
			}
		}
	}
}
