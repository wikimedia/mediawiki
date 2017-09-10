<?php
/**
 * Reader for XMP data containing properties relevant to images.
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
 * @ingroup Media
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\ScopedCallback;

/**
 * Class for reading xmp data containing properties relevant to
 * images, and spitting out an array that FormatMetadata accepts.
 *
 * Note, this is not meant to recognize every possible thing you can
 * encode in XMP. It should recognize all the properties we want.
 * For example it doesn't have support for structures with multiple
 * nesting levels, as none of the properties we're supporting use that
 * feature. If it comes across properties it doesn't recognize, it should
 * ignore them.
 *
 * The public methods one would call in this class are
 * - parse( $content )
 *    Reads in xmp content.
 *    Can potentially be called multiple times with partial data each time.
 * - parseExtended( $content )
 *    Reads XMPExtended blocks (jpeg files only).
 * - getResults
 *    Outputs a results array.
 *
 * Note XMP kind of looks like rdf. They are not the same thing - XMP is
 * encoded as a specific subset of rdf. This class can read XMP. It cannot
 * read rdf.
 */
class XMPReader implements LoggerAwareInterface {
	/** @var array XMP item configuration array */
	protected $items;

	/** @var array Array to hold the current element (and previous element, and so on) */
	private $curItem = [];

	/** @var bool|string The structure name when processing nested structures. */
	private $ancestorStruct = false;

	/** @var bool|string Temporary holder for character data that appears in xmp doc. */
	private $charContent = false;

	/** @var array Stores the state the xmpreader is in (see MODE_FOO constants) */
	private $mode = [];

	/** @var array Array to hold results */
	private $results = [];

	/** @var bool If we're doing a seq or bag. */
	private $processingArray = false;

	/** @var bool|string Used for lang alts only */
	private $itemLang = false;

	/** @var resource A resource handle for the XML parser */
	private $xmlParser;

	/** @var bool|string Character set like 'UTF-8' */
	private $charset = false;

	/** @var int */
	private $extendedXMPOffset = 0;

	/** @var int Flag determining if the XMP is safe to parse **/
	private $parsable = 0;

	/** @var string Buffer of XML to parse **/
	private $xmlParsableBuffer = '';

	/**
	 * These are various mode constants.
	 * they are used to figure out what to do
	 * with an element when its encountered.
	 *
	 * For example, MODE_IGNORE is used when processing
	 * a property we're not interested in. So if a new
	 * element pops up when we're in that mode, we ignore it.
	 */
	const MODE_INITIAL = 0;
	const MODE_IGNORE = 1;
	const MODE_LI = 2;
	const MODE_LI_LANG = 3;
	const MODE_QDESC = 4;

	// The following MODE constants are also used in the
	// $items array to denote what type of property the item is.
	const MODE_SIMPLE = 10;
	const MODE_STRUCT = 11; // structure (associative array)
	const MODE_SEQ = 12; // ordered list
	const MODE_BAG = 13; // unordered list
	const MODE_LANG = 14;
	const MODE_ALT = 15; // non-language alt. Currently not implemented, and not needed atm.
	const MODE_BAGSTRUCT = 16; // A BAG of Structs.

	const NS_RDF = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
	const NS_XML = 'http://www.w3.org/XML/1998/namespace';

	// States used while determining if XML is safe to parse
	const PARSABLE_UNKNOWN = 0;
	const PARSABLE_OK = 1;
	const PARSABLE_BUFFERING = 2;
	const PARSABLE_NO = 3;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * Primary job is to initialize the XMLParser
	 * @param LoggerInterface|null $logger
	 */
	function __construct( LoggerInterface $logger = null ) {
		if ( !function_exists( 'xml_parser_create_ns' ) ) {
			// this should already be checked by this point
			throw new RuntimeException( 'XMP support requires XML Parser' );
		}
		if ( $logger ) {
			$this->setLogger( $logger );
		} else {
			$this->setLogger( new NullLogger() );
		}

		$this->items = XMPInfo::getItems();

		$this->resetXMLParser();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * free the XML parser.
	 *
	 * @note It is unclear to me if we really need to do this ourselves
	 *  or if php garbage collection will automatically free the xmlParser
	 *  when it is no longer needed.
	 */
	private function destroyXMLParser() {
		if ( $this->xmlParser ) {
			xml_parser_free( $this->xmlParser );
			$this->xmlParser = null;
		}
	}

	/**
	 * Main use is if a single item has multiple xmp documents describing it.
	 * For example in jpeg's with extendedXMP
	 */
	private function resetXMLParser() {
		$this->destroyXMLParser();

		$this->xmlParser = xml_parser_create_ns( 'UTF-8', ' ' );
		xml_parser_set_option( $this->xmlParser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $this->xmlParser, XML_OPTION_SKIP_WHITE, 1 );

		xml_set_element_handler( $this->xmlParser,
			[ $this, 'startElement' ],
			[ $this, 'endElement' ] );

		xml_set_character_data_handler( $this->xmlParser, [ $this, 'char' ] );

		$this->parsable = self::PARSABLE_UNKNOWN;
		$this->xmlParsableBuffer = '';
	}

	/**
	 * Check if this instance supports using this class
	 * @return bool
	 */
	public static function isSupported() {
		return function_exists( 'xml_parser_create_ns' ) && class_exists( 'XMLReader' );
	}

	/** Get the result array. Do some post-processing before returning
	 * the array, and transform any metadata that is special-cased.
	 *
	 * @return array Array of results as an array of arrays suitable for
	 *    FormatMetadata::getFormattedData().
	 */
	public function getResults() {
		// xmp-special is for metadata that affects how stuff
		// is extracted. For example xmpNote:HasExtendedXMP.

		// It is also used to handle photoshop:AuthorsPosition
		// which is weird and really part of another property,
		// see 2:85 in IPTC. See also pg 21 of IPTC4XMP standard.
		// The location fields also use it.

		$data = $this->results;

		if ( isset( $data['xmp-special']['AuthorsPosition'] )
			&& is_string( $data['xmp-special']['AuthorsPosition'] )
			&& isset( $data['xmp-general']['Artist'][0] )
		) {
			// Note, if there is more than one creator,
			// this only applies to first. This also will
			// only apply to the dc:Creator prop, not the
			// exif:Artist prop.

			$data['xmp-general']['Artist'][0] =
				$data['xmp-special']['AuthorsPosition'] . ', '
				. $data['xmp-general']['Artist'][0];
		}

		// Go through the LocationShown and LocationCreated
		// changing it to the non-hierarchal form used by
		// the other location fields.

		if ( isset( $data['xmp-special']['LocationShown'][0] )
			&& is_array( $data['xmp-special']['LocationShown'][0] )
		) {
			// the is_array is just paranoia. It should always
			// be an array.
			foreach ( $data['xmp-special']['LocationShown'] as $loc ) {
				if ( !is_array( $loc ) ) {
					// To avoid copying over the _type meta-fields.
					continue;
				}
				foreach ( $loc as $field => $val ) {
					$data['xmp-general'][$field . 'Dest'][] = $val;
				}
			}
		}
		if ( isset( $data['xmp-special']['LocationCreated'][0] )
			&& is_array( $data['xmp-special']['LocationCreated'][0] )
		) {
			// the is_array is just paranoia. It should always
			// be an array.
			foreach ( $data['xmp-special']['LocationCreated'] as $loc ) {
				if ( !is_array( $loc ) ) {
					// To avoid copying over the _type meta-fields.
					continue;
				}
				foreach ( $loc as $field => $val ) {
					$data['xmp-general'][$field . 'Created'][] = $val;
				}
			}
		}

		// We don't want to return the special values, since they're
		// special and not info to be stored about the file.
		unset( $data['xmp-special'] );

		// Convert GPSAltitude to negative if below sea level.
		if ( isset( $data['xmp-exif']['GPSAltitudeRef'] )
			&& isset( $data['xmp-exif']['GPSAltitude'] )
		) {
			// Must convert to a real before multiplying by -1
			// XMPValidate guarantees there will always be a '/' in this value.
			list( $nom, $denom ) = explode( '/', $data['xmp-exif']['GPSAltitude'] );
			$data['xmp-exif']['GPSAltitude'] = $nom / $denom;

			if ( $data['xmp-exif']['GPSAltitudeRef'] == '1' ) {
				$data['xmp-exif']['GPSAltitude'] *= -1;
			}
			unset( $data['xmp-exif']['GPSAltitudeRef'] );
		}

		return $data;
	}

	/**
	 * Main function to call to parse XMP. Use getResults to
	 * get results.
	 *
	 * Also catches any errors during processing, writes them to
	 * debug log, blanks result array and returns false.
	 *
	 * @param string $content XMP data
	 * @param bool $allOfIt If this is all the data (true) or if its split up (false). Default true
	 * @throws RuntimeException
	 * @return bool Success.
	 */
	public function parse( $content, $allOfIt = true ) {
		if ( !$this->xmlParser ) {
			$this->resetXMLParser();
		}
		try {

			// detect encoding by looking for BOM which is supposed to be in processing instruction.
			// see page 12 of http://www.adobe.com/devnet/xmp/pdfs/XMPSpecificationPart3.pdf
			if ( !$this->charset ) {
				$bom = [];
				if ( preg_match( '/\xEF\xBB\xBF|\xFE\xFF|\x00\x00\xFE\xFF|\xFF\xFE\x00\x00|\xFF\xFE/',
					$content, $bom )
				) {
					switch ( $bom[0] ) {
						case "\xFE\xFF":
							$this->charset = 'UTF-16BE';
							break;
						case "\xFF\xFE":
							$this->charset = 'UTF-16LE';
							break;
						case "\x00\x00\xFE\xFF":
							$this->charset = 'UTF-32BE';
							break;
						case "\xFF\xFE\x00\x00":
							$this->charset = 'UTF-32LE';
							break;
						case "\xEF\xBB\xBF":
							$this->charset = 'UTF-8';
							break;
						default:
							// this should be impossible to get to
							throw new RuntimeException( "Invalid BOM" );
					}
				} else {
					// standard specifically says, if no bom assume utf-8
					$this->charset = 'UTF-8';
				}
			}
			if ( $this->charset !== 'UTF-8' ) {
				// don't convert if already utf-8
				MediaWiki\suppressWarnings();
				$content = iconv( $this->charset, 'UTF-8//IGNORE', $content );
				MediaWiki\restoreWarnings();
			}

			// Ensure the XMP block does not have an xml doctype declaration, which
			// could declare entities unsafe to parse with xml_parse (T85848/T71210).
			if ( $this->parsable !== self::PARSABLE_OK ) {
				if ( $this->parsable === self::PARSABLE_NO ) {
					throw new RuntimeException( 'Unsafe doctype declaration in XML.' );
				}

				$content = $this->xmlParsableBuffer . $content;
				if ( !$this->checkParseSafety( $content ) ) {
					if ( !$allOfIt && $this->parsable !== self::PARSABLE_NO ) {
						// parse wasn't Unsuccessful yet, so return true
						// in this case.
						return true;
					}
					$msg = ( $this->parsable === self::PARSABLE_NO ) ?
						'Unsafe doctype declaration in XML.' :
						'No root element found in XML.';
					throw new RuntimeException( $msg );
				}
			}

			$ok = xml_parse( $this->xmlParser, $content, $allOfIt );
			if ( !$ok ) {
				$code = xml_get_error_code( $this->xmlParser );
				$error = xml_error_string( $code );
				$line = xml_get_current_line_number( $this->xmlParser );
				$col = xml_get_current_column_number( $this->xmlParser );
				$offset = xml_get_current_byte_index( $this->xmlParser );

				$this->logger->warning(
					'{method} : Error reading XMP content: {error} ' .
					'(line: {line} column: {column} byte offset: {offset})',
					[
						'method' => __METHOD__,
						'error_code' => $code,
						'error' => $error,
						'line' => $line,
						'column' => $col,
						'offset' => $offset,
						'content' => $content,
				] );
				$this->results = []; // blank if error.
				$this->destroyXMLParser();
				return false;
			}
		} catch ( Exception $e ) {
			$this->logger->warning(
				'{method} Exception caught while parsing: ' . $e->getMessage(),
				[
					'method' => __METHOD__,
					'exception' => $e,
					'content' => $content,
				]
			);
			$this->results = [];
			return false;
		}
		if ( $allOfIt ) {
			$this->destroyXMLParser();
		}

		return true;
	}

	/** Entry point for XMPExtended blocks in jpeg files
	 *
	 * @todo In serious need of testing
	 * @see http://www.adobe.ge/devnet/xmp/pdfs/XMPSpecificationPart3.pdf XMP spec part 3 page 20
	 * @param string $content XMPExtended block minus the namespace signature
	 * @return bool If it succeeded.
	 */
	public function parseExtended( $content ) {
		// @todo FIXME: This is untested. Hard to find example files
		// or programs that make such files..
		$guid = substr( $content, 0, 32 );
		if ( !isset( $this->results['xmp-special']['HasExtendedXMP'] )
			|| $this->results['xmp-special']['HasExtendedXMP'] !== $guid
		) {
			$this->logger->info( __METHOD__ .
				" Ignoring XMPExtended block due to wrong guid (guid= '$guid')" );

			return false;
		}
		$len = unpack( 'Nlength/Noffset', substr( $content, 32, 8 ) );

		if ( !$len ||
			$len['length'] < 4 ||
			$len['offset'] < 0 ||
			$len['offset'] > $len['length']
		) {
			$this->logger->info(
				__METHOD__ . 'Error reading extended XMP block, invalid length or offset.'
			);

			return false;
		}

		// we're not very robust here. we should accept it in the wrong order.
		// To quote the XMP standard:
		// "A JPEG writer should write the ExtendedXMP marker segments in order,
		// immediately following the StandardXMP. However, the JPEG standard
		// does not require preservation of marker segment order. A robust JPEG
		// reader should tolerate the marker segments in any order."
		// On the other hand, the probability that an image will have more than
		// 128k of metadata is rather low... so the probability that it will have
		// > 128k, and be in the wrong order is very low...

		if ( $len['offset'] !== $this->extendedXMPOffset ) {
			$this->logger->info( __METHOD__ . 'Ignoring XMPExtended block due to wrong order. (Offset was '
				. $len['offset'] . ' but expected ' . $this->extendedXMPOffset . ')' );

			return false;
		}

		if ( $len['offset'] === 0 ) {
			// if we're starting the extended block, we've probably already
			// done the XMPStandard block, so reset.
			$this->resetXMLParser();
		}

		$this->extendedXMPOffset += $len['length'];

		$actualContent = substr( $content, 40 );

		if ( $this->extendedXMPOffset === strlen( $actualContent ) ) {
			$atEnd = true;
		} else {
			$atEnd = false;
		}

		$this->logger->debug( __METHOD__ . 'Parsing a XMPExtended block' );

		return $this->parse( $actualContent, $atEnd );
	}

	/**
	 * Character data handler
	 * Called whenever character data is found in the xmp document.
	 *
	 * does nothing if we're in MODE_IGNORE or if the data is whitespace
	 * throws an error if we're not in MODE_SIMPLE (as we're not allowed to have character
	 * data in the other modes).
	 *
	 * As an example, this happens when we encounter XMP like:
	 * <exif:DigitalZoomRatio>0/10</exif:DigitalZoomRatio>
	 * and are processing the 0/10 bit.
	 *
	 * @param resource $parser XMLParser reference to the xml parser
	 * @param string $data Character data
	 * @throws RuntimeException On invalid data
	 */
	function char( $parser, $data ) {
		$data = trim( $data );
		if ( trim( $data ) === "" ) {
			return;
		}

		if ( !isset( $this->mode[0] ) ) {
			throw new RuntimeException( 'Unexpected character data before first rdf:Description element' );
		}

		if ( $this->mode[0] === self::MODE_IGNORE ) {
			return;
		}

		if ( $this->mode[0] !== self::MODE_SIMPLE
			&& $this->mode[0] !== self::MODE_QDESC
		) {
			throw new RuntimeException( 'character data where not expected. (mode ' . $this->mode[0] . ')' );
		}

		// to check, how does this handle w.s.
		if ( $this->charContent === false ) {
			$this->charContent = $data;
		} else {
			$this->charContent .= $data;
		}
	}

	/**
	 * Check if a block of XML is safe to pass to xml_parse, i.e. doesn't
	 * contain a doctype declaration which could contain a dos attack if we
	 * parse it and expand internal entities (T85848).
	 *
	 * @param string $content xml string to check for parse safety
	 * @return bool true if the xml is safe to parse, false otherwise
	 */
	private function checkParseSafety( $content ) {
		$reader = new XMLReader();
		$result = null;

		// For XMLReader to parse incomplete/invalid XML, it has to be open()'ed
		// instead of using XML().
		$reader->open(
			'data://text/plain,' . urlencode( $content ),
			null,
			LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET
		);

		$oldDisable = libxml_disable_entity_loader( true );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$reset = new ScopedCallback(
			'libxml_disable_entity_loader',
			[ $oldDisable ]
		);
		$reader->setParserProperty( XMLReader::SUBST_ENTITIES, false );

		// Even with LIBXML_NOWARNING set, XMLReader::read gives a warning
		// when parsing truncated XML, which causes unit tests to fail.
		MediaWiki\suppressWarnings();
		while ( $reader->read() ) {
			if ( $reader->nodeType === XMLReader::ELEMENT ) {
				// Reached the first element without hitting a doctype declaration
				$this->parsable = self::PARSABLE_OK;
				$result = true;
				break;
			}
			if ( $reader->nodeType === XMLReader::DOC_TYPE ) {
				$this->parsable = self::PARSABLE_NO;
				$result = false;
				break;
			}
		}
		MediaWiki\restoreWarnings();

		if ( !is_null( $result ) ) {
			return $result;
		}

		// Reached the end of the parsable xml without finding an element
		// or doctype. Buffer and try again.
		$this->parsable = self::PARSABLE_BUFFERING;
		$this->xmlParsableBuffer = $content;
		return false;
	}

	/** When we hit a closing element in MODE_IGNORE
	 * Check to see if this is the element we started to ignore,
	 * in which case we get out of MODE_IGNORE
	 *
	 * @param string $elm Namespace of element followed by a space and then tag name of element.
	 */
	private function endElementModeIgnore( $elm ) {
		if ( $this->curItem[0] === $elm ) {
			array_shift( $this->curItem );
			array_shift( $this->mode );
		}
	}

	/**
	 * Hit a closing element when in MODE_SIMPLE.
	 * This generally means that we finished processing a
	 * property value, and now have to save the result to the
	 * results array
	 *
	 * For example, when processing:
	 * <exif:DigitalZoomRatio>0/10</exif:DigitalZoomRatio>
	 * this deals with when we hit </exif:DigitalZoomRatio>.
	 *
	 * Or it could be if we hit the end element of a property
	 * of a compound data structure (like a member of an array).
	 *
	 * @param string $elm Namespace, space, and tag name.
	 */
	private function endElementModeSimple( $elm ) {
		if ( $this->charContent !== false ) {
			if ( $this->processingArray ) {
				// if we're processing an array, use the original element
				// name instead of rdf:li.
				list( $ns, $tag ) = explode( ' ', $this->curItem[0], 2 );
			} else {
				list( $ns, $tag ) = explode( ' ', $elm, 2 );
			}
			$this->saveValue( $ns, $tag, $this->charContent );

			$this->charContent = false; // reset
		}
		array_shift( $this->curItem );
		array_shift( $this->mode );
	}

	/**
	 * Hit a closing element in MODE_STRUCT, MODE_SEQ, MODE_BAG
	 * generally means we've finished processing a nested structure.
	 * resets some internal variables to indicate that.
	 *
	 * Note this means we hit the closing element not the "</rdf:Seq>".
	 *
	 * @par For example, when processing:
	 * @code{,xml}
	 * <exif:ISOSpeedRatings> <rdf:Seq> <rdf:li>64</rdf:li>
	 *   </rdf:Seq> </exif:ISOSpeedRatings>
	 * @endcode
	 *
	 * This method is called when we hit the "</exif:ISOSpeedRatings>" tag.
	 *
	 * @param string $elm Namespace . space . tag name.
	 * @throws RuntimeException
	 */
	private function endElementNested( $elm ) {
		/* cur item must be the same as $elm, unless if in MODE_STRUCT
		 * in which case it could also be rdf:Description */
		if ( $this->curItem[0] !== $elm
			&& !( $elm === self::NS_RDF . ' Description'
				&& $this->mode[0] === self::MODE_STRUCT )
		) {
			throw new RuntimeException( "nesting mismatch. got a </$elm> but expected a </" .
				$this->curItem[0] . '>' );
		}

		// Validate structures.
		list( $ns, $tag ) = explode( ' ', $elm, 2 );
		if ( isset( $this->items[$ns][$tag]['validate'] ) ) {
			$info =& $this->items[$ns][$tag];
			$finalName = isset( $info['map_name'] )
				? $info['map_name'] : $tag;

			if ( is_array( $info['validate'] ) ) {
				$validate = $info['validate'];
			} else {
				$validator = new XMPValidate( $this->logger );
				$validate = [ $validator, $info['validate'] ];
			}

			if ( !isset( $this->results['xmp-' . $info['map_group']][$finalName] ) ) {
				// This can happen if all the members of the struct failed validation.
				$this->logger->debug( __METHOD__ . " <$ns:$tag> has no valid members." );
			} elseif ( is_callable( $validate ) ) {
				$val =& $this->results['xmp-' . $info['map_group']][$finalName];
				call_user_func_array( $validate, [ $info, &$val, false ] );
				if ( is_null( $val ) ) {
					// the idea being the validation function will unset the variable if
					// its invalid.
					$this->logger->info( __METHOD__ . " <$ns:$tag> failed validation." );
					unset( $this->results['xmp-' . $info['map_group']][$finalName] );
				}
			} else {
				$this->logger->warning( __METHOD__ . " Validation function for $finalName ("
					. $validate[0] . '::' . $validate[1] . '()) is not callable.' );
			}
		}

		array_shift( $this->curItem );
		array_shift( $this->mode );
		$this->ancestorStruct = false;
		$this->processingArray = false;
		$this->itemLang = false;
	}

	/**
	 * Hit a closing element in MODE_LI (either rdf:Seq, or rdf:Bag )
	 * Add information about what type of element this is.
	 *
	 * Note we still have to hit the outer "</property>"
	 *
	 * @par For example, when processing:
	 * @code{,xml}
	 * <exif:ISOSpeedRatings> <rdf:Seq> <rdf:li>64</rdf:li>
	 *   </rdf:Seq> </exif:ISOSpeedRatings>
	 * @endcode
	 *
	 * This method is called when we hit the "</rdf:Seq>".
	 * (For comparison, we call endElementModeSimple when we
	 * hit the "</rdf:li>")
	 *
	 * @param string $elm Namespace . ' ' . element name
	 * @throws RuntimeException
	 */
	private function endElementModeLi( $elm ) {
		list( $ns, $tag ) = explode( ' ', $this->curItem[0], 2 );
		$info = $this->items[$ns][$tag];
		$finalName = isset( $info['map_name'] )
			? $info['map_name'] : $tag;

		array_shift( $this->mode );

		if ( !isset( $this->results['xmp-' . $info['map_group']][$finalName] ) ) {
			$this->logger->debug( __METHOD__ . " Empty compund element $finalName." );

			return;
		}

		if ( $elm === self::NS_RDF . ' Seq' ) {
			$this->results['xmp-' . $info['map_group']][$finalName]['_type'] = 'ol';
		} elseif ( $elm === self::NS_RDF . ' Bag' ) {
			$this->results['xmp-' . $info['map_group']][$finalName]['_type'] = 'ul';
		} elseif ( $elm === self::NS_RDF . ' Alt' ) {
			// extra if needed as you could theoretically have a non-language alt.
			if ( $info['mode'] === self::MODE_LANG ) {
				$this->results['xmp-' . $info['map_group']][$finalName]['_type'] = 'lang';
			}
		} else {
			throw new RuntimeException(
				__METHOD__ . " expected </rdf:seq> or </rdf:bag> but instead got $elm."
			);
		}
	}

	/**
	 * End element while in MODE_QDESC
	 * mostly when ending an element when we have a simple value
	 * that has qualifiers.
	 *
	 * Qualifiers aren't all that common, and we don't do anything
	 * with them.
	 *
	 * @param string $elm Namespace and element
	 */
	private function endElementModeQDesc( $elm ) {
		if ( $elm === self::NS_RDF . ' value' ) {
			list( $ns, $tag ) = explode( ' ', $this->curItem[0], 2 );
			$this->saveValue( $ns, $tag, $this->charContent );

			return;
		} else {
			array_shift( $this->mode );
			array_shift( $this->curItem );
		}
	}

	/**
	 * Handler for hitting a closing element.
	 *
	 * generally just calls a helper function depending on what
	 * mode we're in.
	 *
	 * Ignores the outer wrapping elements that are optional in
	 * xmp and have no meaning.
	 *
	 * @param resource $parser
	 * @param string $elm Namespace . ' ' . element name
	 * @throws RuntimeException
	 */
	function endElement( $parser, $elm ) {
		if ( $elm === ( self::NS_RDF . ' RDF' )
			|| $elm === 'adobe:ns:meta/ xmpmeta'
			|| $elm === 'adobe:ns:meta/ xapmeta'
		) {
			// ignore these.
			return;
		}

		if ( $elm === self::NS_RDF . ' type' ) {
			// these aren't really supported properly yet.
			// However, it appears they almost never used.
			$this->logger->info( __METHOD__ . ' encountered <rdf:type>' );
		}

		if ( strpos( $elm, ' ' ) === false ) {
			// This probably shouldn't happen.
			// However, there is a bug in an adobe product
			// that forgets the namespace on some things.
			// (Luckily they are unimportant things).
			$this->logger->info( __METHOD__ . " Encountered </$elm> which has no namespace. Skipping." );

			return;
		}

		if ( count( $this->mode[0] ) === 0 ) {
			// This should never ever happen and means
			// there is a pretty major bug in this class.
			throw new RuntimeException( 'Encountered end element with no mode' );
		}

		if ( count( $this->curItem ) == 0 && $this->mode[0] !== self::MODE_INITIAL ) {
			// just to be paranoid. Should always have a curItem, except for initially
			// (aka during MODE_INITAL).
			throw new RuntimeException( "Hit end element </$elm> but no curItem" );
		}

		switch ( $this->mode[0] ) {
			case self::MODE_IGNORE:
				$this->endElementModeIgnore( $elm );
				break;
			case self::MODE_SIMPLE:
				$this->endElementModeSimple( $elm );
				break;
			case self::MODE_STRUCT:
			case self::MODE_SEQ:
			case self::MODE_BAG:
			case self::MODE_LANG:
			case self::MODE_BAGSTRUCT:
				$this->endElementNested( $elm );
				break;
			case self::MODE_INITIAL:
				if ( $elm === self::NS_RDF . ' Description' ) {
					array_shift( $this->mode );
				} else {
					throw new RuntimeException( 'Element ended unexpectedly while in MODE_INITIAL' );
				}
				break;
			case self::MODE_LI:
			case self::MODE_LI_LANG:
				$this->endElementModeLi( $elm );
				break;
			case self::MODE_QDESC:
				$this->endElementModeQDesc( $elm );
				break;
			default:
				$this->logger->warning( __METHOD__ . " no mode (elm = $elm)" );
				break;
		}
	}

	/**
	 * Hit an opening element while in MODE_IGNORE
	 *
	 * XMP is extensible, so ignore any tag we don't understand.
	 *
	 * Mostly ignores, unless we encounter the element that we are ignoring.
	 * in which case we add it to the item stack, so we can ignore things
	 * that are nested, correctly.
	 *
	 * @param string $elm Namespace . ' ' . tag name
	 */
	private function startElementModeIgnore( $elm ) {
		if ( $elm === $this->curItem[0] ) {
			array_unshift( $this->curItem, $elm );
			array_unshift( $this->mode, self::MODE_IGNORE );
		}
	}

	/**
	 *  Start element in MODE_BAG (unordered array)
	 * this should always be <rdf:Bag>
	 *
	 * @param string $elm Namespace . ' ' . tag
	 * @throws RuntimeException If we have an element that's not <rdf:Bag>
	 */
	private function startElementModeBag( $elm ) {
		if ( $elm === self::NS_RDF . ' Bag' ) {
			array_unshift( $this->mode, self::MODE_LI );
		} else {
			throw new RuntimeException( "Expected <rdf:Bag> but got $elm." );
		}
	}

	/**
	 * Start element in MODE_SEQ (ordered array)
	 * this should always be <rdf:Seq>
	 *
	 * @param string $elm Namespace . ' ' . tag
	 * @throws RuntimeException If we have an element that's not <rdf:Seq>
	 */
	private function startElementModeSeq( $elm ) {
		if ( $elm === self::NS_RDF . ' Seq' ) {
			array_unshift( $this->mode, self::MODE_LI );
		} elseif ( $elm === self::NS_RDF . ' Bag' ) {
			# T29105
			$this->logger->info( __METHOD__ . ' Expected an rdf:Seq, but got an rdf:Bag. Pretending'
				. ' it is a Seq, since some buggy software is known to screw this up.' );
			array_unshift( $this->mode, self::MODE_LI );
		} else {
			throw new RuntimeException( "Expected <rdf:Seq> but got $elm." );
		}
	}

	/**
	 * Start element in MODE_LANG (language alternative)
	 * this should always be <rdf:Alt>
	 *
	 * This tag tends to be used for metadata like describe this
	 * picture, which can be translated into multiple languages.
	 *
	 * XMP supports non-linguistic alternative selections,
	 * which are really only used for thumbnails, which
	 * we don't care about.
	 *
	 * @param string $elm Namespace . ' ' . tag
	 * @throws RuntimeException If we have an element that's not <rdf:Alt>
	 */
	private function startElementModeLang( $elm ) {
		if ( $elm === self::NS_RDF . ' Alt' ) {
			array_unshift( $this->mode, self::MODE_LI_LANG );
		} else {
			throw new RuntimeException( "Expected <rdf:Seq> but got $elm." );
		}
	}

	/**
	 * Handle an opening element when in MODE_SIMPLE
	 *
	 * This should not happen often. This is for if a simple element
	 * already opened has a child element. Could happen for a
	 * qualified element.
	 *
	 * For example:
	 * <exif:DigitalZoomRatio><rdf:Description><rdf:value>0/10</rdf:value>
	 *   <foo:someQualifier>Bar</foo:someQualifier> </rdf:Description>
	 *   </exif:DigitalZoomRatio>
	 *
	 * This method is called when processing the <rdf:Description> element
	 *
	 * @param string $elm Namespace and tag names separated by space.
	 * @param array $attribs Attributes of the element.
	 * @throws RuntimeException
	 */
	private function startElementModeSimple( $elm, $attribs ) {
		if ( $elm === self::NS_RDF . ' Description' ) {
			// If this value has qualifiers
			array_unshift( $this->mode, self::MODE_QDESC );
			array_unshift( $this->curItem, $this->curItem[0] );

			if ( isset( $attribs[self::NS_RDF . ' value'] ) ) {
				list( $ns, $tag ) = explode( ' ', $this->curItem[0], 2 );
				$this->saveValue( $ns, $tag, $attribs[self::NS_RDF . ' value'] );
			}
		} elseif ( $elm === self::NS_RDF . ' value' ) {
			// This should not be here.
			throw new RuntimeException( __METHOD__ . ' Encountered <rdf:value> where it was unexpected.' );
		} else {
			// something else we don't recognize, like a qualifier maybe.
			$this->logger->info( __METHOD__ .
				" Encountered element <$elm> where only expecting character data as value of " .
				$this->curItem[0] );
			array_unshift( $this->mode, self::MODE_IGNORE );
			array_unshift( $this->curItem, $elm );
		}
	}

	/**
	 * Start an element when in MODE_QDESC.
	 * This generally happens when a simple element has an inner
	 * rdf:Description to hold qualifier elements.
	 *
	 * For example in:
	 * <exif:DigitalZoomRatio><rdf:Description><rdf:value>0/10</rdf:value>
	 *   <foo:someQualifier>Bar</foo:someQualifier> </rdf:Description>
	 *   </exif:DigitalZoomRatio>
	 * Called when processing the <rdf:value> or <foo:someQualifier>.
	 *
	 * @param string $elm Namespace and tag name separated by a space.
	 */
	private function startElementModeQDesc( $elm ) {
		if ( $elm === self::NS_RDF . ' value' ) {
			return; // do nothing
		} else {
			// otherwise its a qualifier, which we ignore
			array_unshift( $this->mode, self::MODE_IGNORE );
			array_unshift( $this->curItem, $elm );
		}
	}

	/**
	 * Starting an element when in MODE_INITIAL
	 * This usually happens when we hit an element inside
	 * the outer rdf:Description
	 *
	 * This is generally where most properties start.
	 *
	 * @param string $ns Namespace
	 * @param string $tag Tag name (without namespace prefix)
	 * @param array $attribs Array of attributes
	 * @throws RuntimeException
	 */
	private function startElementModeInitial( $ns, $tag, $attribs ) {
		if ( $ns !== self::NS_RDF ) {
			if ( isset( $this->items[$ns][$tag] ) ) {
				if ( isset( $this->items[$ns][$tag]['structPart'] ) ) {
					// If this element is supposed to appear only as
					// a child of a structure, but appears here (not as
					// a child of a struct), then something weird is
					// happening, so ignore this element and its children.

					$this->logger->warning( "Encountered <$ns:$tag> outside"
						. " of its expected parent. Ignoring." );

					array_unshift( $this->mode, self::MODE_IGNORE );
					array_unshift( $this->curItem, $ns . ' ' . $tag );

					return;
				}
				$mode = $this->items[$ns][$tag]['mode'];
				array_unshift( $this->mode, $mode );
				array_unshift( $this->curItem, $ns . ' ' . $tag );
				if ( $mode === self::MODE_STRUCT ) {
					$this->ancestorStruct = isset( $this->items[$ns][$tag]['map_name'] )
						? $this->items[$ns][$tag]['map_name'] : $tag;
				}
				if ( $this->charContent !== false ) {
					// Something weird.
					// Should not happen in valid XMP.
					throw new RuntimeException( 'tag nested in non-whitespace characters.' );
				}
			} else {
				// This element is not on our list of allowed elements so ignore.
				$this->logger->debug( __METHOD__ . " Ignoring unrecognized element <$ns:$tag>." );
				array_unshift( $this->mode, self::MODE_IGNORE );
				array_unshift( $this->curItem, $ns . ' ' . $tag );

				return;
			}
		}
		// process attributes
		$this->doAttribs( $attribs );
	}

	/**
	 * Hit an opening element when in a Struct (MODE_STRUCT)
	 * This is generally for fields of a compound property.
	 *
	 * Example of a struct (abbreviated; flash has more properties):
	 *
	 * <exif:Flash> <rdf:Description> <exif:Fired>True</exif:Fired>
	 *  <exif:Mode>1</exif:Mode></rdf:Description></exif:Flash>
	 *
	 * or:
	 *
	 * <exif:Flash rdf:parseType='Resource'> <exif:Fired>True</exif:Fired>
	 *  <exif:Mode>1</exif:Mode></exif:Flash>
	 *
	 * @param string $ns Namespace
	 * @param string $tag Tag name (no ns)
	 * @param array $attribs Array of attribs w/ values.
	 * @throws RuntimeException
	 */
	private function startElementModeStruct( $ns, $tag, $attribs ) {
		if ( $ns !== self::NS_RDF ) {
			if ( isset( $this->items[$ns][$tag] ) ) {
				if ( isset( $this->items[$ns][$this->ancestorStruct]['children'] )
					&& !isset( $this->items[$ns][$this->ancestorStruct]['children'][$tag] )
				) {
					// This assumes that we don't have inter-namespace nesting
					// which we don't in all the properties we're interested in.
					throw new RuntimeException( " <$tag> appeared nested in <" . $this->ancestorStruct
						. "> where it is not allowed." );
				}
				array_unshift( $this->mode, $this->items[$ns][$tag]['mode'] );
				array_unshift( $this->curItem, $ns . ' ' . $tag );
				if ( $this->charContent !== false ) {
					// Something weird.
					// Should not happen in valid XMP.
					throw new RuntimeException( "tag <$tag> nested in non-whitespace characters (" .
						$this->charContent . ")." );
				}
			} else {
				array_unshift( $this->mode, self::MODE_IGNORE );
				array_unshift( $this->curItem, $ns . ' ' . $tag );

				return;
			}
		}

		if ( $ns === self::NS_RDF && $tag === 'Description' ) {
			$this->doAttribs( $attribs );
			array_unshift( $this->mode, self::MODE_STRUCT );
			array_unshift( $this->curItem, $this->curItem[0] );
		}
	}

	/**
	 * opening element in MODE_LI
	 * process elements of arrays.
	 *
	 * Example:
	 * <exif:ISOSpeedRatings> <rdf:Seq> <rdf:li>64</rdf:li>
	 *   </rdf:Seq> </exif:ISOSpeedRatings>
	 * This method is called when we hit the <rdf:li> element.
	 *
	 * @param string $elm Namespace . ' ' . tagname
	 * @param array $attribs Attributes. (needed for BAGSTRUCTS)
	 * @throws RuntimeException If gets a tag other than <rdf:li>
	 */
	private function startElementModeLi( $elm, $attribs ) {
		if ( ( $elm ) !== self::NS_RDF . ' li' ) {
			throw new RuntimeException( "<rdf:li> expected but got $elm." );
		}

		if ( !isset( $this->mode[1] ) ) {
			// This should never ever ever happen. Checking for it
			// to be paranoid.
			throw new RuntimeException( 'In mode Li, but no 2xPrevious mode!' );
		}

		if ( $this->mode[1] === self::MODE_BAGSTRUCT ) {
			// This list item contains a compound (STRUCT) value.
			array_unshift( $this->mode, self::MODE_STRUCT );
			array_unshift( $this->curItem, $elm );
			$this->processingArray = true;

			if ( !isset( $this->curItem[1] ) ) {
				// be paranoid.
				throw new RuntimeException( 'Can not find parent of BAGSTRUCT.' );
			}
			list( $curNS, $curTag ) = explode( ' ', $this->curItem[1] );
			$this->ancestorStruct = isset( $this->items[$curNS][$curTag]['map_name'] )
				? $this->items[$curNS][$curTag]['map_name'] : $curTag;

			$this->doAttribs( $attribs );
		} else {
			// Normal BAG or SEQ containing simple values.
			array_unshift( $this->mode, self::MODE_SIMPLE );
			// need to add curItem[0] on again since one is for the specific item
			// and one is for the entire group.
			array_unshift( $this->curItem, $this->curItem[0] );
			$this->processingArray = true;
		}
	}

	/**
	 * Opening element in MODE_LI_LANG.
	 * process elements of language alternatives
	 *
	 * Example:
	 * <dc:title> <rdf:Alt> <rdf:li xml:lang="x-default">My house
	 *  </rdf:li> </rdf:Alt> </dc:title>
	 *
	 * This method is called when we hit the <rdf:li> element.
	 *
	 * @param string $elm Namespace . ' ' . tag
	 * @param array $attribs Array of elements (most importantly xml:lang)
	 * @throws RuntimeException If gets a tag other than <rdf:li> or if no xml:lang
	 */
	private function startElementModeLiLang( $elm, $attribs ) {
		if ( $elm !== self::NS_RDF . ' li' ) {
			throw new RuntimeException( __METHOD__ . " <rdf:li> expected but got $elm." );
		}
		if ( !isset( $attribs[self::NS_XML . ' lang'] )
			|| !preg_match( '/^[-A-Za-z0-9]{2,}$/D', $attribs[self::NS_XML . ' lang'] )
		) {
			throw new RuntimeException( __METHOD__
				. " <rdf:li> did not contain, or has invalid xml:lang attribute in lang alternative" );
		}

		// Lang is case-insensitive.
		$this->itemLang = strtolower( $attribs[self::NS_XML . ' lang'] );

		// need to add curItem[0] on again since one is for the specific item
		// and one is for the entire group.
		array_unshift( $this->curItem, $this->curItem[0] );
		array_unshift( $this->mode, self::MODE_SIMPLE );
		$this->processingArray = true;
	}

	/**
	 * Hits an opening element.
	 * Generally just calls a helper based on what MODE we're in.
	 * Also does some initial set up for the wrapper element
	 *
	 * @param resource $parser
	 * @param string $elm Namespace "<space>" element
	 * @param array $attribs Attribute name => value
	 * @throws RuntimeException
	 */
	function startElement( $parser, $elm, $attribs ) {
		if ( $elm === self::NS_RDF . ' RDF'
			|| $elm === 'adobe:ns:meta/ xmpmeta'
			|| $elm === 'adobe:ns:meta/ xapmeta'
		) {
			/* ignore. */
			return;
		} elseif ( $elm === self::NS_RDF . ' Description' ) {
			if ( count( $this->mode ) === 0 ) {
				// outer rdf:desc
				array_unshift( $this->mode, self::MODE_INITIAL );
			}
		} elseif ( $elm === self::NS_RDF . ' type' ) {
			// This doesn't support rdf:type properly.
			// In practise I have yet to see a file that
			// uses this element, however it is mentioned
			// on page 25 of part 1 of the xmp standard.
			// Also it seems as if exiv2 and exiftool do not support
			// this either (That or I misunderstand the standard)
			$this->logger->info( __METHOD__ . ' Encountered <rdf:type> which isn\'t currently supported' );
		}

		if ( strpos( $elm, ' ' ) === false ) {
			// This probably shouldn't happen.
			$this->logger->info( __METHOD__ . " Encountered <$elm> which has no namespace. Skipping." );

			return;
		}

		list( $ns, $tag ) = explode( ' ', $elm, 2 );

		if ( count( $this->mode ) === 0 ) {
			// This should not happen.
			throw new RuntimeException( 'Error extracting XMP, '
				. "encountered <$elm> with no mode" );
		}

		switch ( $this->mode[0] ) {
			case self::MODE_IGNORE:
				$this->startElementModeIgnore( $elm );
				break;
			case self::MODE_SIMPLE:
				$this->startElementModeSimple( $elm, $attribs );
				break;
			case self::MODE_INITIAL:
				$this->startElementModeInitial( $ns, $tag, $attribs );
				break;
			case self::MODE_STRUCT:
				$this->startElementModeStruct( $ns, $tag, $attribs );
				break;
			case self::MODE_BAG:
			case self::MODE_BAGSTRUCT:
				$this->startElementModeBag( $elm );
				break;
			case self::MODE_SEQ:
				$this->startElementModeSeq( $elm );
				break;
			case self::MODE_LANG:
				$this->startElementModeLang( $elm );
				break;
			case self::MODE_LI_LANG:
				$this->startElementModeLiLang( $elm, $attribs );
				break;
			case self::MODE_LI:
				$this->startElementModeLi( $elm, $attribs );
				break;
			case self::MODE_QDESC:
				$this->startElementModeQDesc( $elm );
				break;
			default:
				throw new RuntimeException( 'StartElement in unknown mode: ' . $this->mode[0] );
		}
	}

	// @codingStandardsIgnoreStart Generic.Files.LineLength
	/**
	 * Process attributes.
	 * Simple values can be stored as either a tag or attribute
	 *
	 * Often the initial "<rdf:Description>" tag just has all the simple
	 * properties as attributes.
	 *
	 * @par Example:
	 * @code
	 * <rdf:Description rdf:about="" xmlns:exif="http://ns.adobe.com/exif/1.0/" exif:DigitalZoomRatio="0/10">
	 * @endcode
	 *
	 * @param array $attribs Array attribute=>value
	 * @throws RuntimeException
	 */
	// @codingStandardsIgnoreEnd
	private function doAttribs( $attribs ) {
		// first check for rdf:parseType attribute, as that can change
		// how the attributes are interperted.

		if ( isset( $attribs[self::NS_RDF . ' parseType'] )
			&& $attribs[self::NS_RDF . ' parseType'] === 'Resource'
			&& $this->mode[0] === self::MODE_SIMPLE
		) {
			// this is equivalent to having an inner rdf:Description
			$this->mode[0] = self::MODE_QDESC;
		}
		foreach ( $attribs as $name => $val ) {
			if ( strpos( $name, ' ' ) === false ) {
				// This shouldn't happen, but so far some old software forgets namespace
				// on rdf:about.
				$this->logger->info( __METHOD__ . ' Encountered non-namespaced attribute: '
					. " $name=\"$val\". Skipping. " );
				continue;
			}
			list( $ns, $tag ) = explode( ' ', $name, 2 );
			if ( $ns === self::NS_RDF ) {
				if ( $tag === 'value' || $tag === 'resource' ) {
					// resource is for url.
					// value attribute is a weird way of just putting the contents.
					$this->char( $this->xmlParser, $val );
				}
			} elseif ( isset( $this->items[$ns][$tag] ) ) {
				if ( $this->mode[0] === self::MODE_SIMPLE ) {
					throw new RuntimeException( __METHOD__
						. " $ns:$tag found as attribute where not allowed" );
				}
				$this->saveValue( $ns, $tag, $val );
			} else {
				$this->logger->debug( __METHOD__ . " Ignoring unrecognized element <$ns:$tag>." );
			}
		}
	}

	/**
	 * Given an extracted value, save it to results array
	 *
	 * note also uses $this->ancestorStruct and
	 * $this->processingArray to determine what name to
	 * save the value under. (in addition to $tag).
	 *
	 * @param string $ns Namespace of tag this is for
	 * @param string $tag Tag name
	 * @param string $val Value to save
	 */
	private function saveValue( $ns, $tag, $val ) {
		$info =& $this->items[$ns][$tag];
		$finalName = isset( $info['map_name'] )
			? $info['map_name'] : $tag;
		if ( isset( $info['validate'] ) ) {
			if ( is_array( $info['validate'] ) ) {
				$validate = $info['validate'];
			} else {
				$validator = new XMPValidate( $this->logger );
				$validate = [ $validator, $info['validate'] ];
			}

			if ( is_callable( $validate ) ) {
				call_user_func_array( $validate, [ $info, &$val, true ] );
				// the reasoning behind using &$val instead of using the return value
				// is to be consistent between here and validating structures.
				if ( is_null( $val ) ) {
					$this->logger->info( __METHOD__ . " <$ns:$tag> failed validation." );

					return;
				}
			} else {
				$this->logger->warning( __METHOD__ . " Validation function for $finalName ("
					. $validate[0] . '::' . $validate[1] . '()) is not callable.' );
			}
		}

		if ( $this->ancestorStruct && $this->processingArray ) {
			// Aka both an array and a struct. ( self::MODE_BAGSTRUCT )
			$this->results['xmp-' . $info['map_group']][$this->ancestorStruct][][$finalName] = $val;
		} elseif ( $this->ancestorStruct ) {
			$this->results['xmp-' . $info['map_group']][$this->ancestorStruct][$finalName] = $val;
		} elseif ( $this->processingArray ) {
			if ( $this->itemLang === false ) {
				// normal array
				$this->results['xmp-' . $info['map_group']][$finalName][] = $val;
			} else {
				// lang array.
				$this->results['xmp-' . $info['map_group']][$finalName][$this->itemLang] = $val;
			}
		} else {
			$this->results['xmp-' . $info['map_group']][$finalName] = $val;
		}
	}
}
