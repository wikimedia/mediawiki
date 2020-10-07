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
	 * @var array
	 */
	private $varMapping = [];

	/**
	 * @param string $schemaVersion see XML_DUMP_SCHEMA_VERSION_XX
	 */
	public function __construct( $schemaVersion ) {
		$this->schemaVersion = $schemaVersion;
	}

	/**
	 * Step the current XML reader until node start of given name is found.
	 *
	 * @param string $name Name of the element to look for
	 *   (e.g.: "text" when looking for <text>)
	 *
	 * @param bool $allowAscend Whether the search should continue in parent
	 *   nodes of the current position. If false (the default), the search will be aborted
	 *   on the next closing element.
	 *
	 * @return bool True if the node could be found. false otherwise.
	 */
	public function skipToNode( $name, $allowAscend = false ) {
		$depth = 0;
		while ( true ) {
			$current = $this->xml->name;
			if ( $this->xml->nodeType == XMLReader::ELEMENT ) {
				if ( $current == $name ) {
					return true;
				}

				if ( !$this->xml->isEmptyElement ) {
					$depth++;
				}
			}

			if ( $this->xml->nodeType == XMLReader::END_ELEMENT ) {
				$depth--;
				if ( $depth < 0 && !$allowAscend )
				return false;
			}

			if ( !$this->xml->read() ) {
				break;
			}
		}

		return false;
	}

	/**
	 * Step the current XML reader until node start of given name is found,
	 * and advance to the first child node.
	 *
	 * @param string $name Name of the element to look for
	 *   (e.g.: "text" when looking for <text>)
	 *
	 * @param bool $allowAscend Whether the search should continue in parent
	 *   nodes of the current position. If false (the default), the search will be aborted
	 *   on the next closing element.
	 */
	public function skipIntoNode( $name, $allowAscend = false ) {
		Assert::assertTrue( $this->skipToNode( $name, $allowAscend ),
			"Skipping to $name" );

		Assert::assertTrue( !$this->xml->isEmptyElement,
			"Skipping into $name" );

		$this->xml->read();
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
	 * Opens an XML file to analyze.
	 *
	 * @param string $fname Name of file to analyze
	 */
	public function open( $fname ) {
		$this->xml = new XMLReader();

		Assert::assertTrue( $this->xml->open( $fname ),
			"Opening temporary file $fname via XMLReader failed" );
	}

	/**
	 * Opens an XML file to analyze, verifies the top level tags,
	 * and skips past <siteinfo>.
	 *
	 * The contents of the <siteinfo> tag can be checked if $siteInfoTemplate
	 * is given. See assertDumpHead().
	 *
	 * @param string $fname Name of file to analyze
	 *
	 * @param string|null $siteInfoTemplate
	 * @param string $language
	 */
	public function assertDumpStart( $fname, $siteInfoTemplate = null, $language = 'en' ) {
		$this->open( $fname );
		$this->assertDumpHead( $siteInfoTemplate, $language );
	}

	/**
	 * Asserts that the head of a dump is valid.
	 * This checks the attributes of the top level <mediawiki> tag.
	 *
	 * If $siteInfoTemplate is given, it is interpreted as the file name
	 * of an XML template that will be used with assertDOM() to check the contents
	 * of the <siteinfo> tag, which is expected to be the first child of
	 * the top level <mediawiki>. Variable substitution applies as defined by
	 * calling setVarMapping().
	 *
	 * After this method returns, the XML reader's position will be after
	 * the closing </siteinfo> tag, before the next tag.
	 *
	 * @param string|null $siteInfoTemplate
	 * @param string $language
	 */
	public function assertDumpHead( $siteInfoTemplate = null, $language = 'en' ) {
		$this->assertNodeStart( 'mediawiki', false );
		$this->assertAttributes( [
			"xmlns" => "http://www.mediawiki.org/xml/export-{$this->schemaVersion}/",
			"xmlns:xsi" => "http://www.w3.org/2001/XMLSchema-instance",
			"xsi:schemaLocation" => "http://www.mediawiki.org/xml/export-{$this->schemaVersion}/ "
				. "http://www.mediawiki.org/xml/export-{$this->schemaVersion}.xsd",
			"version" => "{$this->schemaVersion}",
			"xml:lang" => "{$language}"
		] );

		$this->assertNodeStart( 'siteinfo', false );

		if ( $siteInfoTemplate ) {
			// Checking site info
			$this->assertDOM( $siteInfoTemplate );
		}

		// skip past extra namespaces
		$this->skipPastNodeEnd( 'siteinfo' );
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
		$this->close();
	}

	public function close() {
		$this->xml->close();
	}

	/**
	 * Steps the xml reader over white space
	 */
	public function skipWhitespace() {
		$cont = true;
		while ( $cont && ( ( $this->xml->nodeType == XMLReader::NONE )
			|| ( $this->xml->nodeType == XMLReader::WHITESPACE )
			|| ( $this->xml->nodeType == XMLReader::SIGNIFICANT_WHITESPACE ) ) ) {
			$cont = $this->xml->read();
		}
	}

	/**
	 * Asserts that the xml reader is at an element of given name, and optionally
	 * skips past it. If the reader is at a whitespace element, the whitespace is
	 * skipped first.
	 *
	 * @param string $name The name of the element to check for
	 *   (e.g.: "mediawiki" for <mediawiki>)
	 * @param bool $skip (optional) if true, skip past the found element
	 */
	public function assertNodeStart( $name, $skip = true ) {
		$this->skipWhitespace();
		Assert::assertEquals( $name, $this->xml->name, "Node name" );
		Assert::assertEquals( XMLReader::ELEMENT, $this->xml->nodeType, "Node type" );
		if ( $skip ) {
			Assert::assertTrue( $this->xml->read(), "Skipping past start tag" );
		}
	}

	/**
	 * Asserts that the XML reader is at an element start, and that the element
	 * has the given attributes with the given values.
	 * Variable substitution applies for variables set via setVarMapping().
	 *
	 * @param array $attributes
	 * @param bool $skip (optional) if true, skip past the found element
	 */
	public function assertAttributes( $attributes, $skip = true ) {
		Assert::assertEquals( XMLReader::ELEMENT, $this->xml->nodeType, "Node type" );
		$actualAttributes = $this->getAttributeArray( $this->xml );

		$attributes = array_map(
			function ( $v ) {
				return $this->resolveVars( $v );
			},
			$attributes
		);
		$actualAttributes = array_intersect_key( $actualAttributes, $attributes );

		Assert::assertEquals( $attributes, $actualAttributes, "Attributes" );

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
		Assert::assertFalse( !$this->xml->isEmptyElement, "$name tag has content" );

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
	 * skips past it. If the reader is at a whitespace element, the whitespace is
	 * skipped first.
	 *
	 * @param string $name The name of the closing element to check for
	 *   (e.g.: "mediawiki" for </mediawiki>)
	 * @param bool $skip (optional) if true, skip past the found element
	 */
	public function assertNodeEnd( $name, $skip = true ) {
		$this->skipWhitespace();
		Assert::assertEquals( $name, $this->xml->name, "Node name" );
		Assert::assertEquals( XMLReader::END_ELEMENT, $this->xml->nodeType, "Node type" );
		if ( $skip ) {
			// note: if there is no more content after the tag and read() returns false,
			// that's fine.
			$this->xml->read();
		}
	}

	/**
	 * Asserts that the xml reader is at an element of given tag that contains a given text,
	 * and skips over the element.
	 *
	 * @param string $name The name of the element to check for
	 *   (e.g.: "mediawiki" for <mediawiki>...</mediawiki>)
	 * @param string|bool $text If string, check if it equals the elements text.
	 *   Variable substitution applies. If false, ignore the element's text.
	 * @param bool $skip_ws (optional) if true, skip past white spaces that trail the
	 *   closing element.
	 */
	public function assertTextNode( $name, $text, $skip_ws = true ) {
		$this->assertNodeStart( $name );

		if ( $text !== false ) {
			$text = $this->resolveVars( $text );
			$actual = $this->resolveVars( $this->xml->value );
			Assert::assertEquals( $text, $actual, "Text of node " . $name );
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
	}

	/**
	 * Checks and skips tags that represent the properties of a revision.
	 *
	 * @param int $id Id of the revision
	 * @param string $summary Summary of the revision
	 * @param string $text_sha1 The base36 SHA-1 of the revision's text
	 * @param string $hasEarlyText Whether a text tag is expected before the <sha1> tag.
	 *               Must be one of 'yes', 'no', or maybe.
	 * @param int|bool $parentid (optional) id of the parent revision
	 * @param string $model The expected content model id (default: CONTENT_MODEL_WIKITEXT)
	 * @param string $format The expected format model id (default: CONTENT_FORMAT_WIKITEXT)
	 * @param bool &$foundText Output, whether a text tag was found before the SHA1 tag.
	 *        If this returns false, the text tag should be the next tag after the method returns.
	 */
	public function assertRevisionProperties( $id, $summary,
		$text_sha1, $hasEarlyText = 'maybe', $parentid = false,
		$model = CONTENT_MODEL_WIKITEXT, $format = CONTENT_FORMAT_WIKITEXT,
		&$foundText = ''
	) {
		$this->assertTextNode( "id", $id );
		if ( $parentid !== false ) {
			$this->assertTextNode( "parentid", $parentid );
		}
		$this->assertTextNode( "timestamp", false );

		$this->assertNodeStart( "contributor" );
		$this->assertTextNode( "username", false );
		$this->assertTextNode( "id", false );
		$this->assertNodeEnd( "contributor" );

		$this->assertTextNode( "comment", $summary );

		if ( $this->schemaVersion >= XML_DUMP_SCHEMA_VERSION_11 ) {
			$this->assertTextNode( "origin", false );
		}

		$this->assertTextNode( "model", $model );

		$this->assertTextNode( "format", $format );

		if ( $hasEarlyText === 'yes' || ( $this->xml->name == "text" && $hasEarlyText === 'maybe' ) ) {
			$foundText = true;
			$this->assertNodeStart( "text", false );
			$this->xml->next();
			$this->skipWhitespace();
		} else {
			$foundText = false;
		}

		if ( $text_sha1 ) {
			$this->assertTextNode( "sha1", $text_sha1 );
		} else {
			$this->assertEmptyNode( "sha1" );
		}
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

		$this->assertRevisionProperties(
			$id,
			$summary,
			$text_sha1,
			'maybe',
			$parentid,
			$model,
			$format,
			$text_found
		);

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
			Assert::assertNull( $this->xml->getAttribute( "xml:space" ),
				"xml:space attribute shout not be present" );
			$this->assertEmptyNode( "text" );
		} else {
			// Testing for a real dump
			Assert::assertEquals( $this->xml->getAttribute( "xml:space" ), "preserve",
				"xml:space=preserve should be present" );
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

		$this->assertTextNode( "id", $id );
		$this->assertTextNode( "timestamp", false );

		$this->assertNodeStart( "contributor" );
		$this->assertTextNode( "username", $user_name );
		$this->assertTextNode( "id", $user_id );
		$this->assertNodeEnd( "contributor" );

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

		$this->assertNodeEnd( "logitem" );
	}

	/**
	 * Returns the XMLReader's current line number for reporting.
	 *
	 * @param XMLReader|null $xml
	 *
	 * @return int
	 */
	public function getLineNumber( XMLReader $xml = null ) {
		if ( !$xml ) {
			$xml = $this->xml;
		}

		if ( $xml->nodeType == XMLReader::NONE ) {
			return 0;
		}

		return $xml->expand()->getLineNo();
	}

	/**
	 * Opens an XML template file and compares it to the XML structure at the current position of
	 * this asserter.
	 *
	 * If the outer-most tag of the template file is <test:data>, that tag is
	 * ignored during comparison. This allows template files to contain arbitrary snippets of XML.
	 * When the tag <test:end/> is encountered in the template, the comparison is ended.
	 * This allows template files to be written to match the beginning of a structure,
	 * without the need for subsequent contents to match.
	 *
	 * The contents of $file are subject to variable substitution based on
	 * the values provided via setVarMapping().
	 *
	 * @param string $file Name of file to analyze
	 */
	public function assertDOM( $file ) {
		$exXml = new XMLReader();

		Assert::assertTrue( $exXml->open( $file ),
			"Opening fixture file $file via XMLReader failed" );

		$line = 0;
		while ( true ) {
			$line = max( $line, $this->getLineNumber( $exXml ) );
			$location = "[$file line $line] ";

			while ( $exXml->nodeType == XMLReader::NONE
				|| $exXml->nodeType == XMLReader::WHITESPACE
				|| $exXml->nodeType == XMLReader::SIGNIFICANT_WHITESPACE
				|| $exXml->nodeType == XMLReader::COMMENT
				|| ( $exXml->nodeType == XMLReader::ELEMENT && $exXml->name === 'test:data' ) ) {

				// Reached the end of the template file, so we are done here.
				if ( !$exXml->read() ) {
					break 2;
				}

				// Reached the end of the test data, so we are done here.
				if ( $exXml->nodeType == XMLReader::END_ELEMENT && $exXml->name === 'test:data' ) {
					break 2;
				}
			}

			while ( $this->xml->nodeType == XMLReader::NONE
				|| $this->xml->nodeType == XMLReader::WHITESPACE
				|| $this->xml->nodeType == XMLReader::SIGNIFICANT_WHITESPACE
				|| $this->xml->nodeType == XMLReader::COMMENT ) {
				Assert::assertTrue( $this->xml->read(), $location . 'Document ended unexpectedly' );
			}

			// End comparison early, ignore the rest of the contents of the template file.
			if ( $exXml->nodeType == XMLReader::ELEMENT && $exXml->name === 'test:end' ) {
				break;
			}

			$line = max( $line, $this->getLineNumber( $exXml ) );
			$location = "[$file line $line] ";

			Assert::assertSame( $exXml->nodeType, $this->xml->nodeType, $location . 'Node type' );
			Assert::assertSame( $exXml->name, $this->xml->name, $location . 'Node type' );
			Assert::assertSame(
				$exXml->hasValue,
				$this->xml->hasValue,
				$location . 'Node has value?'
			);
			Assert::assertSame(
				$exXml->hasAttributes,
				$this->xml->hasAttributes,
				$location . 'Node has attributes?'
			);

			if ( $exXml->hasValue ) {
				$expValue = $this->resolveVars( $exXml->value );
				$actValue = $this->resolveVars( $this->xml->value );
				Assert::assertSame( $expValue, $actValue, $location . 'Node value' );
			}

			if ( $exXml->hasAttributes ) {
				$expectedAttributes = $this->getAttributeArray( $exXml );
				$actualAttributes = $this->getAttributeArray( $this->xml );

				Assert::assertEquals( $expectedAttributes, $actualAttributes, $location . 'Attributes' );
			}

			// Reached the end of the template file, so we are done here.
			if ( !$exXml->read() ) {
				break;
			}

			// Reached the end of the test data, so we are done here.
			if ( $exXml->nodeType == XMLReader::END_ELEMENT && $exXml->name === 'test:data' ) {
				break;
			}

			Assert::assertTrue( $this->xml->read(), $location . 'Document ended unexpectedly' );
		}

		$exXml->close();
	}

	/**
	 * Strip any <test:...> tags from a string.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function stripTestTags( $text ) {
		$text = preg_replace( '@<!--.*?-->@s', '', $text );
		$text = preg_replace( '@</?test:[^>]+>@s', '', $text );
		return $text;
	}

	private function getAttributeArray( XMLReader $xml = null ) {
		if ( !$xml ) {
			$xml = $this->xml;
		}

		if ( $xml->nodeType !== XMLReader::ELEMENT ) {
			return null;
		}

		if ( !$xml->hasAttributes ) {
			return [];
		}

		$attr = [];
		while ( $xml->moveToNextAttribute() ) {
			$attr[$xml->name] = $this->resolveVars( $xml->value );
		}

		return $attr;
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function resolveVars( $text ) {
		return str_replace(
			array_keys( $this->varMapping ),
			array_values( $this->varMapping ),
			$text
		);
	}

	/**
	 * Define a variable mapping to be applied by assertDOM
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function setVarMapping( $name, $value ) {
		$key = '{{' . $name . '}}';
		$this->varMapping[$key] = $value;
	}

	/**
	 * @return string
	 */
	public function getSchemaVersion() {
		return $this->schemaVersion;
	}

}
