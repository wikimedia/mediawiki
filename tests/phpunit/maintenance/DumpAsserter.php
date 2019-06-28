<?php

namespace MediaWiki\Tests\Maintenance;

use PHPUnit\Framework\Assert;
use XMLReader;

/**
 * Helper for asserting the structure of an XML dump stream.
 */
class DumpAsserter {

	/**
	 * Holds the XMLReader used for analyzing an XML dump
	 *
	 * @var XMLReader|null
	 */
	protected $xml = null;

	/**
	 * XML dump schema version
	 *
	 * @var string
	 */
	protected $schemaVersion;

	/**
	 * DumpAsserts constructor.
	 *
	 * @param string $schemaVersion see XML_DUMP_SCHEMA_VERSION_XX
	 */
	public function __construct( $schemaVersion ) {
		$this->schemaVersion = $schemaVersion;
	}

	/**
	 * Step the current XML reader until node end of given name is found.
	 *
	 * @param string $name Name of the closing element to look for
	 *   (e.g.: "mediawiki" when looking for </mediawiki>)
	 *
	 * @return bool True if the end node could be found. false otherwise.
	 */
	public function skipToNodeEnd( $name ) {
		while ( $this->xml->read() ) {
			if ( $this->xml->nodeType == XMLReader::END_ELEMENT &&
				$this->xml->name == $name
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Step the current XML reader to the first element start after the node
	 * end of a given name.
	 *
	 * @param string $name Name of the closing element to look for
	 *   (e.g.: "mediawiki" when looking for </mediawiki>)
	 *
	 * @return bool True if new element after the closing of $name could be
	 *   found. false otherwise.
	 */
	public function skipPastNodeEnd( $name ) {
		Assert::assertTrue( $this->skipToNodeEnd( $name ),
			"Skipping to end of $name" );
		while ( $this->xml->read() ) {
			if ( $this->xml->nodeType == XMLReader::ELEMENT ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Opens an XML file to analyze and optionally skips past siteinfo.
	 *
	 * @param string $fname Name of file to analyze
	 * @param bool $skip_siteinfo (optional) If true, step the xml reader
	 *   to the first element after </siteinfo>
	 */
	public function assertDumpStart( $fname, $skip_siteinfo = true ) {
		$this->xml = new XMLReader();

		Assert::assertTrue( $this->xml->open( $fname ),
			"Opening temporary file $fname via XMLReader failed" );
		if ( $skip_siteinfo ) {
			Assert::assertTrue( $this->skipPastNodeEnd( "siteinfo" ),
				"Skipping past end of siteinfo" );
		}
	}

	/**
	 * Asserts that the xml reader is at the final closing tag of an xml file and
	 * closes the reader.
	 *
	 * @param string $name (optional) the name of the final tag
	 *   (e.g.: "mediawiki" for </mediawiki>)
	 */
	public function assertDumpEnd( $name = "mediawiki" ) {
		$this->assertNodeEnd( $name, false );
		if ( $this->xml->read() ) {
			$this->skipWhitespace();
		}
		Assert::assertEquals( $this->xml->nodeType, XMLReader::NONE,
			"No proper entity left to parse" );
		$this->xml->close();
	}

	/**
	 * Steps the xml reader over white space
	 */
	public function skipWhitespace() {
		$cont = true;
		while ( $cont && ( ( $this->xml->nodeType == XMLReader::WHITESPACE )
			|| ( $this->xml->nodeType == XMLReader::SIGNIFICANT_WHITESPACE ) ) ) {
			$cont = $this->xml->read();
		}
	}

	/**
	 * Asserts that the xml reader is at an element of given name, and optionally
	 * skips past it.
	 *
	 * @param string $name The name of the element to check for
	 *   (e.g.: "mediawiki" for <mediawiki>)
	 * @param bool $skip (optional) if true, skip past the found element
	 */
	public function assertNodeStart( $name, $skip = true ) {
		Assert::assertEquals( $name, $this->xml->name, "Node name" );
		Assert::assertEquals( XMLReader::ELEMENT, $this->xml->nodeType, "Node type" );
		if ( $skip ) {
			Assert::assertTrue( $this->xml->read(), "Skipping past start tag" );
		}
	}

	/**
	 * Asserts that the xml reader is at an element of given name, and that element
	 * is an empty tag.
	 *
	 * @param string $name The name of the element to check for
	 *   (e.g.: "text" for <text/>)
	 * @param bool $skip (optional) if true, skip past the found element
	 * @param bool $skip_ws (optional) if true, also skip past white spaces that trail the
	 *   closing element.
	 */
	public function assertEmptyNode( $name, $skip = true, $skip_ws = true ) {
		$this->assertNodeStart( $name, false );
		Assert::assertFalse( $this->xml->hasValue, "$name tag has content" );

		if ( $skip ) {
			Assert::assertTrue( $this->xml->read(), "Skipping $name tag" );
			if ( ( $this->xml->nodeType == XMLReader::END_ELEMENT )
				&& ( $this->xml->name == $name )
			) {
				$this->xml->read();
			}

			if ( $skip_ws ) {
				$this->skipWhitespace();
			}
		}
	}

	/**
	 * Asserts that the xml reader is at an closing element of given name, and optionally
	 * skips past it.
	 *
	 * @param string $name The name of the closing element to check for
	 *   (e.g.: "mediawiki" for </mediawiki>)
	 * @param bool $skip (optional) if true, skip past the found element
	 */
	public function assertNodeEnd( $name, $skip = true ) {
		Assert::assertEquals( $name, $this->xml->name, "Node name" );
		Assert::assertEquals( XMLReader::END_ELEMENT, $this->xml->nodeType, "Node type" );
		if ( $skip ) {
			Assert::assertTrue( $this->xml->read(), "Skipping past end tag" );
		}
	}

	/**
	 * Asserts that the xml reader is at an element of given tag that contains a given text,
	 * and skips over the element.
	 *
	 * @param string $name The name of the element to check for
	 *   (e.g.: "mediawiki" for <mediawiki>...</mediawiki>)
	 * @param string|bool $text If string, check if it equals the elements text.
	 *   If false, ignore the element's text
	 * @param bool $skip_ws (optional) if true, skip past white spaces that trail the
	 *   closing element.
	 */
	public function assertTextNode( $name, $text, $skip_ws = true ) {
		$this->assertNodeStart( $name );

		if ( $text !== false ) {
			Assert::assertEquals( $text, $this->xml->value, "Text of node " . $name );
		}
		Assert::assertTrue( $this->xml->read(), "Skipping past processed text of " . $name );
		$this->assertNodeEnd( $name );

		if ( $skip_ws ) {
			$this->skipWhitespace();
		}
	}

	/**
	 * Asserts that the xml reader is at the start of a page element and skips over the first
	 * tags, after checking them.
	 *
	 * Besides the opening page element, this function also checks for and skips over the
	 * title, ns, and id tags. Hence after this function, the xml reader is at the first
	 * revision of the current page.
	 *
	 * @param int $id Id of the page to assert
	 * @param int $ns Number of namespage to assert
	 * @param string $name Title of the current page
	 */
	public function assertPageStart( $id, $ns, $name ) {
		$this->assertNodeStart( "page" );
		$this->skipWhitespace();

		$this->assertTextNode( "title", $name );
		$this->assertTextNode( "ns", $ns );
		$this->assertTextNode( "id", $id );
	}

	/**
	 * Asserts that the xml reader is at the page's closing element and skips to the next
	 * element.
	 */
	public function assertPageEnd() {
		$this->assertNodeEnd( "page" );
		$this->skipWhitespace();
	}

	/**
	 * Asserts that the xml reader is at a revision and checks its representation before
	 * skipping over it.
	 *
	 * @param int $id Id of the revision
	 * @param string $summary Summary of the revision
	 * @param int $text_id Id of the revision's text
	 * @param int $text_bytes Number of bytes in the revision's text
	 * @param string $text_sha1 The base36 SHA-1 of the revision's text
	 * @param string|bool $text (optional) The revision's string, or false to check for a
	 *            revision stub
	 * @param int|bool $parentid (optional) id of the parent revision
	 * @param string $model The expected content model id (default: CONTENT_MODEL_WIKITEXT)
	 * @param string $format The expected format model id (default: CONTENT_FORMAT_WIKITEXT)
	 */
	public function assertRevision( $id, $summary, $text_id, $text_bytes,
		$text_sha1, $text = false, $parentid = false,
		$model = CONTENT_MODEL_WIKITEXT, $format = CONTENT_FORMAT_WIKITEXT
	) {
		$this->assertNodeStart( "revision" );
		$this->skipWhitespace();

		$this->assertTextNode( "id", $id );
		if ( $parentid !== false ) {
			$this->assertTextNode( "parentid", $parentid );
		}
		$this->assertTextNode( "timestamp", false );

		$this->assertNodeStart( "contributor" );
		$this->skipWhitespace();
		$this->assertTextNode( "username", false );
		$this->assertTextNode( "id", false );
		$this->assertNodeEnd( "contributor" );
		$this->skipWhitespace();

		$this->assertTextNode( "comment", $summary );
		$this->skipWhitespace();

		if ( $this->schemaVersion >= XML_DUMP_SCHEMA_VERSION_11 ) {
			$this->assertTextNode( "origin", false );
			$this->skipWhitespace();
		}

		$this->assertTextNode( "model", $model );
		$this->skipWhitespace();

		$this->assertTextNode( "format", $format );
		$this->skipWhitespace();

		if ( $this->xml->name == "text" ) {
			// note: <text> tag may occur here or at the very end.
			$text_found = true;
			$this->assertText( $id, $text_id, $text_bytes, $text );
		} else {
			$text_found = false;
			if ( $this->schemaVersion >= XML_DUMP_SCHEMA_VERSION_11 ) {
				Assert::fail( 'Missing text node' );
			}
		}

		if ( $text_sha1 ) {
			$this->assertTextNode( "sha1", $text_sha1 );
		} else {
			$this->assertEmptyNode( "sha1" );
		}

		if ( !$text_found ) {
			$this->assertText( $id, $text_id, $text_bytes, $text );
		}

		$this->assertNodeEnd( "revision" );
		$this->skipWhitespace();
	}

	public function assertText( $id, $text_id, $text_bytes, $text ) {
		$this->assertNodeStart( "text", false );
		if ( $text_bytes !== false ) {
			Assert::assertEquals( $this->xml->getAttribute( "bytes" ), $text_bytes,
				"Attribute 'bytes' of revision " . $id );
		}

		if ( $text === false ) {
			Assert::assertEquals( $this->xml->getAttribute( "id" ), $text_id,
				"Text id of revision " . $id );
			$this->assertEmptyNode( "text" );
		} else {
			// Testing for a real dump
			Assert::assertTrue( $this->xml->read(), "Skipping text start tag" );
			Assert::assertEquals( $text, $this->xml->value, "Text of revision " . $id );
			Assert::assertTrue( $this->xml->read(), "Skipping past text" );
			$this->assertNodeEnd( "text" );
			$this->skipWhitespace();
		}
	}

	/**
	 * asserts that the xml reader is at the beginning of a log entry and skips over
	 * it while analyzing it.
	 *
	 * @param int $id Id of the log entry
	 * @param string $user_name User name of the log entry's performer
	 * @param int $user_id User id of the log entry 's performer
	 * @param string|null $comment Comment of the log entry. If null, the comment text is ignored.
	 * @param string $type Type of the log entry
	 * @param string $subtype Subtype of the log entry
	 * @param string $title Title of the log entry's target
	 * @param array $parameters (optional) unserialized data accompanying the log entry
	 */
	public function assertLogItem( $id, $user_name, $user_id, $comment, $type,
		$subtype, $title, $parameters = []
	) {
		$this->assertNodeStart( "logitem" );
		$this->skipWhitespace();

		$this->assertTextNode( "id", $id );
		$this->assertTextNode( "timestamp", false );

		$this->assertNodeStart( "contributor" );
		$this->skipWhitespace();
		$this->assertTextNode( "username", $user_name );
		$this->assertTextNode( "id", $user_id );
		$this->assertNodeEnd( "contributor" );
		$this->skipWhitespace();

		if ( $comment !== null ) {
			$this->assertTextNode( "comment", $comment );
		}
		$this->assertTextNode( "type", $type );
		$this->assertTextNode( "action", $subtype );
		$this->assertTextNode( "logtitle", $title );

		$this->assertNodeStart( "params" );
		$parameters_xml = unserialize( $this->xml->value );
		Assert::assertEquals( $parameters, $parameters_xml );
		Assert::assertTrue( $this->xml->read(), "Skipping past processed text of params" );
		$this->assertNodeEnd( "params" );
		$this->skipWhitespace();

		$this->assertNodeEnd( "logitem" );
		$this->skipWhitespace();
	}
}
