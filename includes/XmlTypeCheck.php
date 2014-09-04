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
	 * @param $file string filename
	 * @param $filterCallback callable (optional)
	 *        Function to call to do additional custom validity checks from the
	 *        SAX element handler event. This gives you access to the element
	 *        namespace, name, attributes, and text contents.
	 *        Filter should return 'true' to toggle on $this->filterMatch
	 * @param array $options list of additional parsing options:
	 *	processing_instruction_handler: Callback for xml_set_processing_instruction_handler
	 */
	function __construct( $file, $filterCallback=null, $options=array() ) {
		$this->filterCallback = $filterCallback;
		$this->parserOptions = array_merge( $this->parserOptions, $options );
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

		if ( $this->parserOptions['processing_instruction_handler'] ) {
			xml_set_processing_instruction_handler(
				$parser,
				array( $this, 'processingInstructionHandler' )
			);
		}

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
			xml_set_element_handler(
				$parser,
				array( $this, 'elementOpen' ),
				array( $this, 'elementClose' )
			);
			xml_set_character_data_handler( $parser, array( $this, 'elementData' ) );
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
		$this->elementDataContext[] = array( $name, $attribs );
		$this->elementData[] = '';
		$this->stackDepth++;
	}

	/**
	 * @param $parser
	 * @param $name
	 */
	private function elementClose( $parser, $name ) {
		list( $name, $attribs ) = array_pop( $this->elementDataContext );
		$data = array_pop( $this->elementData );
		$this->stackDepth--;

		if ( call_user_func(
			$this->filterCallback,
			$name,
			$attribs,
			$data
		) ) {
			// Filter hit!
			$this->filterMatch = true;
		}
	}

	/**
	 * @param $parser
	 * @param $data
	 */
	private function elementData( $parser, $data ) {
		// xml_set_character_data_handler breaks the data on & characters, so
		// we collect any data here, and we'll run the callback in elementClose
		$this->elementData[ $this->stackDepth - 1 ] .= trim( $data );
	}

	/**
	 * @param $parser
	 * @param $target
	 * @param $data
	 */
	private function processingInstructionHandler( $parser, $target, $data ) {
		if ( call_user_func( $this->parserOptions['processing_instruction_handler'], $target, $data ) ) {
			// Filter hit!
			$this->filterMatch = true;
		}
	}
}
