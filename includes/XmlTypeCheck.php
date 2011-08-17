<?php

class XmlTypeCheck {
	/**
	 * Will be set to true or false to indicate whether the file is
	 * well-formed XML. Note that this doesn't check schema validity.
	 */
	public $wellFormed = false;

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
	 * @param $file string filename
	 * @param $filterCallback callable (optional)
	 *        Function to call to do additional custom validity checks from the
	 *        SAX element handler event. This gives you access to the element
	 *        namespace, name, and attributes, but not to text contents.
	 *        Filter should return 'true' to toggle on $this->filterMatch
	 */
	function __construct( $file, $filterCallback=null ) {
		$this->filterCallback = $filterCallback;
		$this->run( $file );
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
	 * @param $fname
	 */
	private function run( $fname ) {
		$parser = xml_parser_create_ns( 'UTF-8' );

		// case folding violates XML standard, turn it off
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );

		xml_set_element_handler( $parser, array( $this, 'rootElementOpen' ), false );

		if ( file_exists( $fname ) ) {
			$file = fopen( $fname, "rb" );
			if ( $file ) {
				do {
					$chunk = fread( $file, 32768 );
					$ret = xml_parse( $parser, $chunk, feof( $file ) );
					if( $ret == 0 ) {
						// XML isn't well-formed!
						fclose( $file );
						xml_parser_free( $parser );
						return;
					}
				} while( !feof( $file ) );

				fclose( $file );
			}
		}

		$this->wellFormed = true;

		xml_parser_free( $parser );
	}

	/**
	 * @param $parser
	 * @param $name
	 * @param $attribs
	 */
	private function rootElementOpen( $parser, $name, $attribs ) {
		$this->rootElement = $name;

		if( is_callable( $this->filterCallback ) ) {
			xml_set_element_handler( $parser, array( $this, 'elementOpen' ), false );
			$this->elementOpen( $parser, $name, $attribs );
		} else {
			// We only need the first open element
			xml_set_element_handler( $parser, false, false );
		}
	}

	/**
	 * @param $parser
	 * @param $name
	 * @param $attribs
	 */
	private function elementOpen( $parser, $name, $attribs ) {
		if( call_user_func( $this->filterCallback, $name, $attribs ) ) {
			// Filter hit!
			$this->filterMatch = true;
		}
	}
}
