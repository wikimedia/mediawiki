<?php
/**
 *  Copyright 2016 JetBrains
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// Start of xmlreader v.0.2

/**
 * The XMLReader extension is an XML Pull parser. The reader acts as a
 * cursor going forward on the document stream and stopping at each node
 * on the way.
 * @link http://php.net/manual/en/class.xmlreader.php
 *
 * @property-read int $attributeCount The number of attributes on the node
 * @property-read string $baseURI The base URI of the node
 * @property-read int $depth Depth of the node in the tree, starting at 0
 * @property-read bool $hasAttributes Indicates if node has attributes
 * @property-read bool $hasValue Indicates if node has a text value
 * @property-read bool $isDefault Indicates if attribute is defaulted from DTD
 * @property-read bool $isEmptyElement Indicates if node is an empty element tag
 * @property-read string $localName The local name of the node
 * @property-read string $name The qualified name of the node
 * @property-read string $namespaceURI The URI of the namespace associated with the node
 * @property-read int $nodeType The node type for the node
 * @property-read string $prefix The prefix of the namespace associated with the node
 * @property-read string $value The text value of the node
 * @property-read string $xmlLang The xml:lang scope which the node resides
 */
class XMLReader  {
	/**
	 * No node type
	 */
	const NONE = 0;
	/**
	 * Start element
	 */
	const ELEMENT = 1;
	/**
	 * Attribute node
	 */
	const ATTRIBUTE = 2;
	/**
	 * Text node
	 */
	const TEXT = 3;
	/**
	 * CDATA node
	 */
	const CDATA = 4;
	/**
	 * Entity Reference node
	 */
	const ENTITY_REF = 5;
	/**
	 * Entity Declaration node
	 */
	const ENTITY = 6;
	/**
	 * Processing Instruction node
	 */
	const PI = 7;
	/**
	 * Comment node
	 */
	const COMMENT = 8;
	/**
	 * Document node
	 */
	const DOC = 9;
	/**
	 * Document Type node
	 */
	const DOC_TYPE = 10;
	/**
	 * Document Fragment node
	 */
	const DOC_FRAGMENT = 11;
	/**
	 * Notation node
	 */
	const NOTATION = 12;
	/**
	 * Whitespace node
	 */
	const WHITESPACE = 13;
	/**
	 * Significant Whitespace node
	 */
	const SIGNIFICANT_WHITESPACE = 14;
	/**
	 * End Element
	 */
	const END_ELEMENT = 15;
	/**
	 * End Entity
	 */
	const END_ENTITY = 16;
	/**
	 * XML Declaration node
	 */
	const XML_DECLARATION = 17;
	/**
	 * Load DTD but do not validate
	 */
	const LOADDTD = 1;
	/**
	 * Load DTD and default attributes but do not validate
	 */
	const DEFAULTATTRS = 2;
	/**
	 * Load DTD and validate while parsing
	 */
	const VALIDATE = 3;
	/**
	 * Substitute entities and expand references
	 */
	const SUBST_ENTITIES = 4;


	/**
	 * Close the XMLReader input
	 * @link http://php.net/manual/en/xmlreader.close.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function close () {}

	/**
	 * Get the value of a named attribute
	 * @link http://php.net/manual/en/xmlreader.getattribute.php
	 * @param string $name <p>
	 * The name of the attribute.
	 * </p>
	 * @return string The value of the attribute, or <b>NULL</b> if no attribute with the given
	 * <i>name</i> is found or not positioned on an element node.
	 * @since 5.1.2
	 */
	public function getAttribute ($name) {}

	/**
	 * Get the value of an attribute by index
	 * @link http://php.net/manual/en/xmlreader.getattributeno.php
	 * @param int $index <p>
	 * The position of the attribute.
	 * </p>
	 * @return string|NULL The value of the attribute, or <b>NULL</b> if no attribute exists
	 * at <i>index</i> or not positioned of element.
	 * @since 5.1.2
	 */
	public function getAttributeNo ($index) {}

	/**
	 * Get the value of an attribute by localname and URI
	 * @link http://php.net/manual/en/xmlreader.getattributens.php
	 * @param string $localName <p>
	 * The local name.
	 * </p>
	 * @param string $namespaceURI <p>
	 * The namespace URI.
	 * </p>
	 * @return string|NULL The value of the attribute, or <b>NULL</b> if no attribute with the
	 * given <i>localName</i> and
	 * <i>namespaceURI</i> is found or not positioned of element.
	 * @since 5.1.2
	 */
	public function getAttributeNs ($localName, $namespaceURI) {}

	/**
	 * Indicates if specified property has been set
	 * @link http://php.net/manual/en/xmlreader.getparserproperty.php
	 * @param int $property <p>
	 * One of the parser option
	 * constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function getParserProperty ($property) {}

	/**
	 * Indicates if the parsed document is valid
	 * @link http://php.net/manual/en/xmlreader.isvalid.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function isValid () {}

	/**
	 * Lookup namespace for a prefix
	 * @link http://php.net/manual/en/xmlreader.lookupnamespace.php
	 * @param string $prefix <p>
	 * String containing the prefix.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function lookupNamespace ($prefix) {}

	/**
	 * Move cursor to an attribute by index
	 * @link http://php.net/manual/en/xmlreader.movetoattributeno.php
	 * @param int $index <p>
	 * The position of the attribute.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function moveToAttributeNo ($index) {}

	/**
	 * Move cursor to a named attribute
	 * @link http://php.net/manual/en/xmlreader.movetoattribute.php
	 * @param string $name <p>
	 * The name of the attribute.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function moveToAttribute ($name) {}

	/**
	 * Move cursor to a named attribute
	 * @link http://php.net/manual/en/xmlreader.movetoattributens.php
	 * @param string $localName <p>
	 * The local name.
	 * </p>
	 * @param string $namespaceURI <p>
	 * The namespace URI.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function moveToAttributeNs ($localName, $namespaceURI) {}

	/**
	 * Position cursor on the parent Element of current Attribute
	 * @link http://php.net/manual/en/xmlreader.movetoelement.php
	 * @return bool <b>TRUE</b> if successful and <b>FALSE</b> if it fails or not positioned on
	 * Attribute when this method is called.
	 * @since 5.1.2
	 */
	public function moveToElement () {}

	/**
	 * Position cursor on the first Attribute
	 * @link http://php.net/manual/en/xmlreader.movetofirstattribute.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function moveToFirstAttribute () {}

	/**
	 * Position cursor on the next Attribute
	 * @link http://php.net/manual/en/xmlreader.movetonextattribute.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function moveToNextAttribute () {}

	/**
	 * Set the URI containing the XML to parse
	 * @link http://php.net/manual/en/xmlreader.open.php
	 * @param string $URI <p>
	 * URI pointing to the document.
	 * </p>
	 * @param string $encoding [optional] <p>
	 * The document encoding or <b>NULL</b>.
	 * </p>
	 * @param int $options [optional] <p>
	 * A bitmask of the LIBXML_*
	 * constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. If called statically, returns an
	 * <b>XMLReader</b> or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function open ($URI, $encoding = null, $options = 0) {}

	/**
	 * Move to next node in document
	 * @link http://php.net/manual/en/xmlreader.read.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function read () {}

	/**
	 * Move cursor to next node skipping all subtrees
	 * @link http://php.net/manual/en/xmlreader.next.php
	 * @param string $localname [optional] <p>
	 * The name of the next node to move to.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function next ($localname = null) {}

	/**
	 * Retrieve XML from current node
	 * @link http://php.net/manual/en/xmlreader.readinnerxml.php
	 * @return string the contents of the current node as a string. Empty string on failure.
	 * @since 5.2.0
	 */
	public function readInnerXml () {}

	/**
	 * Retrieve XML from current node, including it self
	 * @link http://php.net/manual/en/xmlreader.readouterxml.php
	 * @return string the contents of current node, including itself, as a string. Empty string on failure.
	 * @since 5.2.0
	 */
	public function readOuterXml () {}

	/**
	 * Reads the contents of the current node as a string
	 * @link http://php.net/manual/en/xmlreader.readstring.php
	 * @return string the content of the current node as a string. Empty string on
	 * failure.
	 * @since 5.2.0
	 */
	public function readString () {}

	/**
	 * Validate document against XSD
	 * @link http://php.net/manual/en/xmlreader.setschema.php
	 * @param string $filename <p>
	 * The filename of the XSD schema.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.2.0
	 */
	public function setSchema ($filename) {}

	/**
	 * Set parser options
	 * @link http://php.net/manual/en/xmlreader.setparserproperty.php
	 * @param int $property <p>
	 * One of the parser option
	 * constants.
	 * </p>
	 * @param bool $value <p>
	 * If set to <b>TRUE</b> the option will be enabled otherwise will
	 * be disabled.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function setParserProperty ($property, $value) {}

	/**
	 * Set the filename or URI for a RelaxNG Schema
	 * @link http://php.net/manual/en/xmlreader.setrelaxngschema.php
	 * @param string $filename <p>
	 * filename or URI pointing to a RelaxNG Schema.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.2.0
	 */
	public function setRelaxNGSchema ($filename) {}

	/**
	 * Set the data containing a RelaxNG Schema
	 * @link http://php.net/manual/en/xmlreader.setrelaxngschemasource.php
	 * @param string $source <p>
	 * String containing the RelaxNG Schema.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function setRelaxNGSchemaSource ($source) {}

	/**
	 * Set the data containing the XML to parse
	 * @link http://php.net/manual/en/xmlreader.xml.php
	 * @param string $source <p>
	 * String containing the XML to be parsed.
	 * </p>
	 * @param string $encoding [optional] <p>
	 * The document encoding or <b>NULL</b>.
	 * </p>
	 * @param int $options [optional] <p>
	 * A bitmask of the LIBXML_*
	 * constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure. If called statically, returns an
	 * <b>XMLReader</b> or <b>FALSE</b> on failure.
	 * @since 5.1.2
	 */
	public function XML ($source, $encoding = null, $options = 0) {}

	/**
	 * Returns a copy of the current node as a DOM object
	 * @link http://php.net/manual/en/xmlreader.expand.php
	 * @param DOMNode $basenode [optional]
	 * @return DOMNode The resulting <b>DOMNode</b> or <b>FALSE</b> on error.
	 * @since 5.1.2
	 */
	public function expand (DOMNode $basenode = null) {}

}
// End of xmlreader v.0.2
