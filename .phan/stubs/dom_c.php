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

//20120405 AG synced to official docs

/**
 * The DOMNode class
 * @link http://php.net/manual/en/class.domnode.php
 */
class DOMNode  {

    /**
     * @var string
     * @since 5.0
     * Returns the most accurate name for the current node type
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.nodename
     */
    public $nodeName;

    /**
     * @var string
     * @since 5.0
     * The value of this node, depending on its type
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.nodevalue
     */
    public $nodeValue;

    /**
     * @var int
     * @since 5.0
     * Gets the type of the node. One of the predefined
     * <a href="http://www.php.net/manual/en/dom.constants.php">XML_xxx_NODE</a> constants
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.nodetype
     */
    public $nodeType;

    /**
     * @var DOMNode
     * @since 5.0
     * The parent of this node
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.parentnode
     */
    public $parentNode;

    /**
     * @var DOMNodeList
     * @since 5.0
     * A <classname>DOMNodeList</classname> that contains all children of this node. If there are no children, this is an empty <classname>DOMNodeList</classname>.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.childnodes
     */
    public $childNodes;

    /**
     * @var DOMNode
     * @since 5.0
     * The first child of this node. If there is no such node, this returns NULL.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.firstchild
     */
    public $firstChild;

    /**
     * @var DOMNode
     * @since 5.0
     * The last child of this node. If there is no such node, this returns NULL.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.lastchild
     */
    public $lastChild;

    /**
     * @var DOMNode
     * @since 5.0
     * The node immediately preceding this node. If there is no such node, this returns NULL.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.previoussibling
     */
    public $previousSibling;

    /**
     * @var DOMNode
     * @since 5.0
     * The node immediately following this node. If there is no such node, this returns NULL.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.nextsibling
     */
    public $nextSibling;

    /**
     * @var DOMNamedNodeMap
     * @since 5.0
     * A <classname>DOMNamedNodeMap</classname> containing the attributes of this node (if it is a <classname>DOMElement</classname>) or NULL otherwise.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.attributes
     */
    public $attributes;

    /**
     * @var DOMDocument
     * @since 5.0
     * The <classname>DOMDocument</classname> object associated with this node.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.ownerdocument
     */
    public $ownerDocument;

    /**
     * @var string
     * @since 5.0
     * The namespace URI of this node, or NULL if it is unspecified.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.namespaceuri
     */
    public $namespaceURI;

    /**
     * @var string
     * @since 5.0
     * The namespace prefix of this node, or NULL if it is unspecified.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.prefix
     */
    public $prefix;

    /**
     * @var string
     * @since 5.0
     * Returns the local part of the qualified name of this node.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.localname
     */
    public $localName;

    /**
     * @var string
     * @since 5.0
     * The absolute base URI of this node or NULL if the implementation wasn't able to obtain an absolute URI.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.baseuri
     */
    public $baseURI;

    /**
     * @var string
     * @since 5.0
     * This attribute returns the text content of this node and its descendants.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.textcontent
     */
    public $textContent;

    /**
     * Adds a new child before a reference node
     * @link http://php.net/manual/en/domnode.insertbefore.php
     * @param DOMNode $newnode <p>
     * The new node.
     * </p>
     * @param DOMNode $refnode [optional] <p>
     * The reference node. If not supplied, newnode is
     * appended to the children.
     * </p>
     * @return DOMNode The inserted node.
     * @since 5.0
     */
    public function insertBefore (DOMNode $newnode, DOMNode $refnode = null) {}

    /**
     * Replaces a child
     * @link http://php.net/manual/en/domnode.replacechild.php
     * @param DOMNode $newnode <p>
     * The new node. It must be a member of the target document, i.e.
     * created by one of the DOMDocument->createXXX() methods or imported in
     * the document by .
     * </p>
     * @param DOMNode $oldnode <p>
     * The old node.
     * </p>
     * @return DOMNode The old node or false if an error occur.
     * @since 5.0
     */
    public function replaceChild (DOMNode $newnode , DOMNode $oldnode ) {}

    /**
     * Removes child from list of children
     * @link http://php.net/manual/en/domnode.removechild.php
     * @param DOMNode $oldnode <p>
     * The removed child.
     * </p>
     * @return DOMNode If the child could be removed the functions returns the old child.
     * @since 5.0
     */
    public function removeChild (DOMNode $oldnode ) {}

    /**
     * Adds new child at the end of the children
     * @link http://php.net/manual/en/domnode.appendchild.php
     * @param DOMNode $newnode <p>
     * The appended child.
     * </p>
     * @return DOMNode The node added.
     * @since 5.0
     */
    public function appendChild (DOMNode $newnode ) {}

    /**
     * Checks if node has children
     * @link http://php.net/manual/en/domnode.haschildnodes.php
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function hasChildNodes () {}

    /**
     * Clones a node
     * @link http://php.net/manual/en/domnode.clonenode.php
     * @param bool $deep [optional] <p>
     * Indicates whether to copy all descendant nodes. This parameter is
     * defaulted to false.
     * </p>
     * @return DOMNode The cloned node.
     * @since 5.0
     */
    public function cloneNode ($deep = null) {}

    /**
     * Normalizes the node
     * @link http://php.net/manual/en/domnode.normalize.php
     * @return void
     * @since 5.0
     */
    public function normalize () {}

    /**
     * Checks if feature is supported for specified version
     * @link http://php.net/manual/en/domnode.issupported.php
     * @param string $feature <p>
     * The feature to test. See the example of
     * DOMImplementation::hasFeature for a
     * list of features.
     * </p>
     * @param string $version <p>
     * The version number of the feature to test.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function isSupported ($feature, $version) {}

    /**
     * Checks if node has attributes
     * @link http://php.net/manual/en/domnode.hasattributes.php
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function hasAttributes () {}

    /**
     * @param DOMNode $other
     */
    public function compareDocumentPosition (DOMNode $other) {}

    /**
     * Indicates if two nodes are the same node
     * @link http://php.net/manual/en/domnode.issamenode.php
     * @param DOMNode $node <p>
     * The compared node.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function isSameNode (DOMNode $node ) {}

    /**
     * Gets the namespace prefix of the node based on the namespace URI
     * @link http://php.net/manual/en/domnode.lookupprefix.php
     * @param string $namespaceURI <p>
     * The namespace URI.
     * </p>
     * @return string The prefix of the namespace.
     * @since 5.0
     */
    public function lookupPrefix ($namespaceURI) {}

    /**
     * Checks if the specified namespaceURI is the default namespace or not
     * @link http://php.net/manual/en/domnode.isdefaultnamespace.php
     * @param string $namespaceURI <p>
     * The namespace URI to look for.
     * </p>
     * @return bool Return true if namespaceURI is the default
     * namespace, false otherwise.
     * @since 5.0
     */
    public function isDefaultNamespace ($namespaceURI) {}

    /**
     * Gets the namespace URI of the node based on the prefix
     * @link http://php.net/manual/en/domnode.lookupnamespaceuri.php
     * @param string $prefix <p>
     * The prefix of the namespace.
     * </p>
     * @return string The namespace URI of the node.
     * @since 5.0
     */
    public function lookupNamespaceUri ($prefix) {}

    /**
     * @param DOMNode $arg
     * @return boolean
     */
    public function isEqualNode (DOMNode $arg) {}

    /**
     * @param $feature
     * @param $version
     * @return mixed
     */
    public function getFeature ($feature, $version) {}

    /**
     * @param $key
     * @param $data
     * @param $handler
     */
    public function setUserData ($key, $data, $handler) {}

    /**
     * @param $key
     * @return mixed
     */
    public function getUserData ($key) {}

    /**
     * Gets an XPath location path for the node
     * @return string the XPath, or NULL in case of an error.
     * @link http://www.php.net/manual/en/domnode.getnodepath.php
     * @since 5.3.0
     */
    public function getNodePath () {}


	/**
	 * Get line number for a node
	 * @link http://php.net/manual/en/domnode.getlineno.php
	 * @return int Always returns the line number where the node was defined in.
	 * @since 5.3.0
	 */
     public function getLineNo () {}

    /**
     * Canonicalize nodes to a string
     * @param bool $exclusive [optional] Enable exclusive parsing of only the nodes matched by the provided xpath or namespace prefixes.
     * @param bool $with_comments [optional] Retain comments in output.
     * @param array $xpath [optional] An array of xpaths to filter the nodes by.
     * @param array $ns_prefixes [optional] An array of namespace prefixes to filter the nodes by.
     * @return string canonicalized nodes as a string or FALSE on failure
     */
    public function C14N ($exclusive, $with_comments, array $xpath = null, $ns_prefixes = null) {}

    /**
     * Canonicalize nodes to a file.
     * @param $uri Number of bytes written or FALSE on failure
     * @param $exclusive [optional] Enable exclusive parsing of only the nodes matched by the provided xpath or namespace prefixes.
     * @param $with_comments [optional]  Retain comments in output.
     * @param $xpath [optional] An array of xpaths to filter the nodes by.
     * @param $ns_prefixes [optional] An array of namespace prefixes to filter the nodes by.
     * @return int Number of bytes written or FALSE on failure
     */
    public function C14NFile ($uri, $exclusive, array $with_comments, array $xpath = null, $ns_prefixes = null) {}

}

/**
 * DOM operations raise exceptions under particular circumstances, i.e.,
 * when an operation is impossible to perform for logical reasons.
 * @link http://php.net/manual/en/class.domexception.php
 */
class DOMException extends Exception  {

    /**
     * @var
     * @since 5.0
     * An integer indicating the type of error generated
     * @link http://php.net/manual/en/class.domexception.php#domexception.props.code
     */
    public $code;
}

class DOMStringList  {

        /**
	 * @param $index
         * @return mixed
         */
        public function item ($index) {}

}

/**
 * @link http://php.net/manual/en/ref.dom.php
 */
class DOMNameList  {

        /**
	 * @param $index
         * @return mixed
         */
        public function getName ($index) {}

        /**
	 * @param $index
         * @return mixed
         */
        public function getNamespaceURI ($index) {}

}

class DOMImplementationList  {

        /**
	 * @param $index
         * @return mixed
         */
        public function item ($index) {}

}

class DOMImplementationSource  {

        /**
	 * @param $features
         * @return mixed
         */
        public function getDomimplementation ($features) {}

        /**
	 * @param $features
         * @return mixed
         */
        public function getDomimplementations ($features) {}

}

/**
 * The DOMImplementation interface provides a number
 * of methods for performing operations that are independent of any 
 * particular instance of the document object model.
 * @link http://php.net/manual/en/class.domimplementation.php
 */
class DOMImplementation  {

    /**
     * Creates a new DOMImplementation object
     * @link http://http://php.net/manual/en/domimplementation.construct.php
     * @since 5.0
     */
    public function __construct(){}

    /**
     * @param $feature
     * @param $version
     * @return mixed
     */
    public function getFeature ($feature, $version) {}

	/**
	 * Test if the DOM implementation implements a specific feature
	 * @link http://php.net/manual/en/domimplementation.hasfeature.php
	 * @param string $feature <p>
	 * The feature to test.
	 * </p>
	 * @param string $version <p>
	 * The version number of the feature to test. In 
	 * level 2, this can be either 2.0 or
	 * 1.0.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function hasFeature ($feature, $version) {}

    /**
     * Creates an empty DOMDocumentType object
     * @link http://php.net/manual/en/domimplementation.createdocumenttype.php
     * @param string $qualifiedName [optional] <p>
     * The qualified name of the document type to create.
     * </p>
     * @param string $publicId [optional] <p>
     * The external subset public identifier.
     * </p>
     * @param string $systemId [optional] <p>
     * The external subset system identifier.
     * </p>
     * @return DOMDocumentType A new DOMDocumentType node with its
     * ownerDocument set to &null;.
     * @since 5.0
     */
    public function createDocumentType ($qualifiedName = null, $publicId = null, $systemId = null) {}

    /**
     * Creates a DOMDocument object of the specified type with its document element
     * @link http://php.net/manual/en/domimplementation.createdocument.php
     * @param string $namespaceURI [optional] <p>
     * The namespace URI of the document element to create.
     * </p>
     * @param string $qualifiedName [optional] <p>
     * The qualified name of the document element to create.
     * </p>
     * @param DOMDocumentType $doctype [optional] <p>
     * The type of document to create or &null;.
     * </p>
     * @return DOMDocument A new DOMDocument object. If
     * namespaceURI, qualifiedName,
     * and doctype are null, the returned
     * DOMDocument is empty with no document element
     * @since 5.0
     */
    public function createDocument ($namespaceURI = null, $qualifiedName = null, DOMDocumentType $doctype = null) {}

}


class DOMNameSpaceNode  {
}

/**
 * The DOMDocumentFragment class
 * @link http://php.net/manual/en/class.domdocumentfragment.php
 */
class DOMDocumentFragment extends DOMNode  {

    public function __construct () {}

    /**
     * Append raw XML data
     * @link http://php.net/manual/en/domdocumentfragment.appendxml.php
     * @param string $data <p>
     * XML to append.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.1.0
     */
    public function appendXML ($data) {}

}

/**
 * The DOMDocument class represents an entire HTML or XML
 * document; serves as the root of the document tree.
 * @link http://php.net/manual/class.domdocument.php
 */
class DOMDocument extends DOMNode  {

    /**
     * @var string
     * @since 5.0
     * Deprecated. Actual encoding of the document, is a readonly equivalent to encoding.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.actualencoding
     * @deprecated
     */
    public $actualEncoding;

    /**
     * @var DOMConfiguration
     * @since 5.0
     * Deprecated. Configuration used when {@link DOMDocument::normalizeDocument()} is invoked.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.config
     * @deprecated
     */
    public $config;

    /**
     * @var DOMDocumentType
     * @since 5.0
     * The Document Type Declaration associated with this document.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.doctype
     */
    public $doctype;

    /**
     * @var DOMElement
     * @since 5.0
     * This is a convenience attribute that allows direct access to the child node
     * that is the document element of the document.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.documentelement
     */
    public $documentElement;

    /**
     * @var string
     * @since 5.0
     * The location of the document or NULL if undefined.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.documenturi
     */
    public $documentURI ;

    /**
     * @var string
     * @since 5.0
     * Encoding of the document, as specified by the XML declaration. This attribute is not present
     * in the final DOM Level 3 specification, but is the only way of manipulating XML document
     * encoding in this implementation.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.encoding
     */
    public $encoding ;

    /**
     * @var bool
     * @since 5.0
     * Nicely formats output with indentation and extra space.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.formatoutput
     */
    public $formatOutput ;

    /**
     * @var DOMImplementation
     * @since 5.0
     * The <classname>DOMImplementation</classname> object that handles this document.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.implementation
     */
    public $implementation ;

    /**
     * @var bool
     * @since 5.0
     * Do not remove redundant white space. Default to TRUE.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.preservewhitespace
     */
    public $preserveWhiteSpace = true ;

    /**
     * @var bool
     * @since 5.0
     * Proprietary. Enables recovery mode, i.e. trying to parse non-well formed documents.
     * This attribute is not part of the DOM specification and is specific to libxml.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.recover
     */
    public $recover ;

    /**
     * @var bool
     * @since 5.0
     * Set it to TRUE to load external entities from a doctype declaration. This is useful for
     * including character entities in your XML document.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.resolveexternals
     */
    public $resolveExternals ;

    /**
     * @var bool
     * @since 5.0
     * Deprecated. Whether or not the document is standalone, as specified by the XML declaration,
     * corresponds to xmlStandalone.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.standalone
     * @deprecated
     */
    public $standalone ;

    /**
     * @var bool
     * @since 5.0
     * Throws <classname>DOMException</classname> on errors. Default to TRUE.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.stricterrorchecking
     */
    public $strictErrorChecking = true ;

    /**
     * @var bool
     * @since 5.0
     * Proprietary. Whether or not to substitute entities. This attribute is not part of the DOM
     * specification and is specific to libxml.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.substituteentities
     */
    public $substituteEntities ;

    /**
     * @var bool
     * @since 5.0
     * Loads and validates against the DTD. Default to FALSE.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.validateonparse
     */
    public $validateOnParse = false ;

    /**
     * @var string
     * @since 5.0
     * Deprecated. Version of XML, corresponds to xmlVersion
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.version
     */
    public $version ;

    /**
     * @var string
     * @since 5.0
     * An attribute specifying, as part of the XML declaration, the encoding of this document. This is NULL when
     * unspecified or when it is not known, such as when the Document was created in memory.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.xmlencoding
     */
    public $xmlEncoding ;

    /**
     * @var bool
     * @since 5.0
     * An attribute specifying, as part of the XML declaration, whether this document is standalone.
     * This is FALSE when unspecified.
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.xmlstandalone
     */
    public $xmlStandalone ;

    /**
     * @var string
     * @since 5.0
     * An attribute specifying, as part of the XML declaration, the version number of this document. If there is no
     * declaration and if this document supports the "XML" feature, the value is "1.0".
     * @link http://php.net/manual/class.domdocument.php#domdocument.props.xmlversion
     */
    public $xmlVersion ;

    /**
     * Create new element node
     * @link http://php.net/manual/domdocument.createelement.php
     * @param string $name <p>
     * The tag name of the element.
     * </p>
     * @param string $value [optional] <p>
     * The value of the element. By default, an empty element will be created.
     * You can also set the value later with DOMElement->nodeValue.
     * </p>
     * @return DOMElement a new instance of class DOMElement or false
     * if an error occured.
     * @since 5.0
     */
    public function createElement ($name, $value = null) {}

    /**
     * Create new document fragment
     * @link http://php.net/manual/domdocument.createdocumentfragment.php
     * @return DOMDocumentFragment The new DOMDocumentFragment or false if an error occured.
     * @since 5.0
     */
    public function createDocumentFragment () {}

    /**
     * Create new text node
     * @link http://php.net/manual/domdocument.createtextnode.php
     * @param string $content <p>
     * The content of the text.
     * </p>
     * @return DOMText The new DOMText or false if an error occured.
     * @since 5.0
     */
    public function createTextNode ($content) {}

    /**
     * Create new comment node
     * @link http://php.net/manual/domdocument.createcomment.php
     * @param string $data <p>
     * The content of the comment.
     * </p>
     * @return DOMComment The new DOMComment or false if an error occured.
     * @since 5.0
     */
    public function createComment ($data) {}

    /**
     * Create new cdata node
     * @link http://php.net/manual/domdocument.createcdatasection.php
     * @param string $data <p>
     * The content of the cdata.
     * </p>
     * @return DOMCDATASection The new DOMCDATASection or false if an error occured.
     * @since 5.0
     */
    public function createCDATASection ($data) {}

    /**
     * Creates new PI node
     * @link http://php.net/manual/domdocument.createprocessinginstruction.php
     * @param string $target <p>
     * The target of the processing instruction.
     * </p>
     * @param string $data [optional] <p>
     * The content of the processing instruction.
     * </p>
     * @return DOMProcessingInstruction The new DOMProcessingInstruction or false if an error occured.
     * @since 5.0
     */
    public function createProcessingInstruction ($target, $data = null) {}

    /**
     * Create new attribute
     * @link http://php.net/manual/domdocument.createattribute.php
     * @param string $name <p>
     * The name of the attribute.
     * </p>
     * @return DOMAttr The new DOMAttr or false if an error occured.
     * @since 5.0
     */
    public function createAttribute ($name) {}

    /**
     * Create new entity reference node
     * @link http://php.net/manual/domdocument.createentityreference.php
     * @param string $name <p>
     * The content of the entity reference, e.g. the entity reference minus
     * the leading &amp; and the trailing
     * ; characters.
     * </p>
     * @return DOMEntityReference The new DOMEntityReference or false if an error
     * occured.
     * @since 5.0
     */
    public function createEntityReference ($name) {}

    /**
     * Searches for all elements with given tag name
     * @link http://php.net/manual/domdocument.getelementsbytagname.php
     * @param string $name <p>
     * The name of the tag to match on. The special value *
     * matches all tags.
     * </p>
     * @return DOMNodeList A new DOMNodeList object containing all the matched
     * elements.
     * @since 5.0
     */
    public function getElementsByTagName ($name) {}

    /**
     * Import node into current document
     * @link http://php.net/manual/domdocument.importnode.php
     * @param DOMNode $importedNode <p>
     * The node to import.
     * </p>
     * @param bool $deep [optional] <p>
     * If set to true, this method will recursively import the subtree under
     * the importedNode.
     * </p>
     * <p>
     * To copy the nodes attributes deep needs to be set to true
     * </p>
     * @return DOMNode The copied node or false, if it cannot be copied.
     * @since 5.0
     */
    public function importNode (DOMNode $importedNode , $deep = null) {}

    /**
     * Create new element node with an associated namespace
     * @link http://php.net/manual/domdocument.createelementns.php
     * @param string $namespaceURI <p>
     * The URI of the namespace.
     * </p>
     * @param string $qualifiedName <p>
     * The qualified name of the element, as prefix:tagname.
     * </p>
     * @param string $value [optional] <p>
     * The value of the element. By default, an empty element will be created.
     * You can also set the value later with DOMElement->nodeValue.
     * </p>
     * @return DOMElement The new DOMElement or false if an error occured.
     * @since 5.0
     */
    public function createElementNS ($namespaceURI, $qualifiedName, $value = null) {}

    /**
     * Create new attribute node with an associated namespace
     * @link http://php.net/manual/domdocument.createattributens.php
     * @param string $namespaceURI <p>
     * The URI of the namespace.
     * </p>
     * @param string $qualifiedName <p>
     * The tag name and prefix of the attribute, as prefix:tagname.
     * </p>
     * @return DOMAttr The new DOMAttr or false if an error occured.
     * @since 5.0
     */
    public function createAttributeNS ($namespaceURI, $qualifiedName) {}

    /**
     * Searches for all elements with given tag name in specified namespace
     * @link http://php.net/manual/domdocument.getelementsbytagnamens.php
     * @param string $namespaceURI <p>
     * The namespace URI of the elements to match on.
     * The special value * matches all namespaces.
     * </p>
     * @param string $localName <p>
     * The local name of the elements to match on.
     * The special value * matches all local names.
     * </p>
     * @return DOMNodeList A new DOMNodeList object containing all the matched
     * elements.
     * @since 5.0
     */
    public function getElementsByTagNameNS ($namespaceURI, $localName) {}

    /**
     * Searches for an element with a certain id
     * @link http://php.net/manual/domdocument.getelementbyid.php
     * @param string $elementId <p>
     * The unique id value for an element.
     * </p>
     * @return DOMElement the DOMElement or &null; if the element is
     * not found.
     * @since 5.0
     */
    public function getElementById ($elementId) {}

    /**
     * @param DOMNode $source
     */
    public function adoptNode (DOMNode $source) {}

    /**
     * Normalizes the document
     * @link http://php.net/manual/domdocument.normalizedocument.php
     * @return void
     * @since 5.0
     */
    public function normalizeDocument () {}

    /**
     * @param DOMNode $node
     * @param $namespaceURI
     * @param $qualifiedName
     */
    public function renameNode (DOMNode $node, $namespaceURI, $qualifiedName) {}

    /**
     * Load XML from a file
     * @link http://php.net/manual/domdocument.load.php
     * @param string $filename <p>
     * The path to the XML document.
     * </p>
     * @param int $options [optional] <p>
     * Bitwise OR
     * of the libxml option constants.
     * </p>
     * @return mixed true on success or false on failure. If called statically, returns a
     * DOMDocument and issues E_STRICT
     * warning.
     * @since 5.0
     */
    public function load ($filename, $options = null) {}

    /**
     * Dumps the internal XML tree back into a file
     * @link http://php.net/manual/domdocument.save.php
     * @param string $filename <p>
     * The path to the saved XML document.
     * </p>
     * @param int $options [optional] <p>
     * Additional Options. Currently only LIBXML_NOEMPTYTAG is supported.
     * </p>
     * @return int the number of bytes written or false if an error occurred.
     * @since 5.0
     */
    public function save ($filename, $options = null) {}

    /**
     * Load XML from a string
     * @link http://php.net/manual/domdocument.loadxml.php
     * @param string $source <p>
     * The string containing the XML.
     * </p>
     * @param int $options [optional] <p>
     * Bitwise OR
     * of the libxml option constants.
     * </p>
     * @return mixed true on success or false on failure. If called statically, returns a
     * DOMDocument and issues E_STRICT
     * warning.
     * @since 5.0
     */
    public function loadXML ($source, $options = null) {}

    /**
     * Dumps the internal XML tree back into a string
     * @link http://php.net/manual/domdocument.savexml.php
     * @param DOMNode $node [optional] <p>
     * Use this parameter to output only a specific node without XML declaration
     * rather than the entire document.
     * </p>
     * @param int $options [optional] <p>
     * Additional Options. Currently only LIBXML_NOEMPTYTAG is supported.
     * </p>
     * @return string the XML, or false if an error occurred.
     * @since 5.0
     */
    public function saveXML ($node = null , $options = null) {}

    /**
     * Creates a new DOMDocument object
     * @link http://php.net/manual/domdocument.construct.php
     * @param $version [optional] The version number of the document as part of the XML declaration.
     * @param $encoding [optional] The encoding of the document as part of the XML declaration.
     * @since 5.0
     */
    public function __construct ($version, $encoding) {}

    /**
     * Validates the document based on its DTD
     * @link http://php.net/manual/domdocument.validate.php
     * @return bool true on success or false on failure.
     * If the document have no DTD attached, this method will return false.
     * @since 5.0
     */
    public function validate () {}

    /**
     * Substitutes XIncludes in a DOMDocument Object
     * @link http://php.net/manual/domdocument.xinclude.php
     * @param int $options [optional] <p>
     * libxml parameters. Available
     * since PHP 5.1.0 and Libxml 2.6.7.
     * </p>
     * @return int the number of XIncludes in the document.
     * @since 5.0
     */
    public function xinclude ($options = null) {}

    /**
     * Load HTML from a string
     * @link http://php.net/manual/domdocument.loadhtml.php
     * @param string $source <p>
     * The HTML string.
     * </p>
     * @param string $options [optional] <p>
     * Since PHP 5.4.0 and Libxml 2.6.0, you may also 
     * use the options parameter to specify additional Libxml parameters.
     * </p>
     * @return bool true on success or false on failure. If called statically, returns a
     * DOMDocument and issues E_STRICT
     * warning.
     * @since 5.0
     */
    public function loadHTML ($source, $options = 0) {}

    /**
     * Load HTML from a file
     * @link http://php.net/manual/domdocument.loadhtmlfile.php
     * @param string $filename <p>
     * The path to the HTML file.
     * </p>
     * @param string $options [optional] <p>
     * Since PHP 5.4.0 and Libxml 2.6.0, you may also 
     * use the options parameter to specify additional Libxml parameters.
     * </p>
     * @return bool true on success or false on failure. If called statically, returns a
     * DOMDocument and issues E_STRICT
     * warning.
     * @since 5.0
     */
    public function loadHTMLFile ($filename, $options = 0) {}

    /**
     * Dumps the internal document into a string using HTML formatting
     * @link http://php.net/manual/domdocument.savehtml.php
     * @param DOMNode $node [optional] parameter to output a subset of the document.
     * @return string the HTML, or false if an error occurred.
     * @since 5.0
     */
    public function saveHTML (DOMNode $node = NULL) {}

    /**
     * Dumps the internal document into a file using HTML formatting
     * @link http://php.net/manual/domdocument.savehtmlfile.php
     * @param string $filename <p>
     * The path to the saved HTML document.
     * </p>
     * @return int the number of bytes written or false if an error occurred.
     * @since 5.0
     */
    public function saveHTMLFile ($filename) {}

    /**
     * Validates a document based on a schema
     * @link http://php.net/manual/domdocument.schemavalidate.php
     * @param string $filename <p>
     * The path to the schema.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function schemaValidate ($filename) {}

    /**
     * Validates a document based on a schema
     * @link http://php.net/manual/domdocument.schemavalidatesource.php
     * @param string $source <p>
     * A string containing the schema.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function schemaValidateSource ($source) {}

    /**
     * Performs relaxNG validation on the document
     * @link http://php.net/manual/domdocument.relaxngvalidate.php
     * @param string $filename <p>
     * The RNG file.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function relaxNGValidate ($filename) {}

    /**
     * Performs relaxNG validation on the document
     * @link http://php.net/manual/domdocument.relaxngvalidatesource.php
     * @param string $source <p>
     * A string containing the RNG schema.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function relaxNGValidateSource ($source) {}

    /**
     * Register extended class used to create base node type
     * @link http://php.net/manual/domdocument.registernodeclass.php
     * @param string $baseclass <p>
     * The DOM class that you want to extend. You can find a list of these
     * classes in the chapter introduction.
     * </p>
     * @param string $extendedclass <p>
     * Your extended class name. If &null; is provided, any previously
     * registered class extending baseclass will
     * be removed.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.2.0
     */
    public function registerNodeClass ($baseclass, $extendedclass) {}

}

/**
 * The DOMNodeList class
 * @link http://php.net/manual/en/class.domnodelist.php
 */
class DOMNodeList implements Traversable {

    /**
     * @var int
     * @since 5.0
     * The number of nodes in the list. The range of valid child node indices is 0 to length - 1 inclusive.
     * @link http://php.net/manual/en/class.domnodelist.php#domnodelist.props.length
     */
    public $length;

  /**
	 * Retrieves a node specified by index
	 * @link http://php.net/manual/en/domnodelist.item.php
	 * @param int $index <p>
	 * Index of the node into the collection.
	 * </p>
	 * @return DOMNode The node at the indexth position in the 
	 * DOMNodeList, or &null; if that is not a valid
	 * index.
	 * @since 5.0
   */
        public function item ($index) {}

}

/**
 * The DOMNamedNodeMap class
 * @link http://php.net/manual/en/class.domnamednodemap.php
 * @property-read $length The number of nodes in the map. The range of valid child node indices is 0 to length - 1 inclusive.
 */
class DOMNamedNodeMap  {

    /**
     * Retrieves a node specified by name
     * @link http://php.net/manual/en/domnamednodemap.getnameditem.php
     * @param string $name <p>
     * The nodeName of the node to retrieve.
     * </p>
     * @return DOMNode A node (of any type) with the specified nodeName, or
     * &null; if no node is found.
     * @since 5.0
     */
    public function getNamedItem ($name) {}

    /**
     * @param DOMNode $arg
     */
    public function setNamedItem (DOMNode $arg) {}

    /**
     * @param $name [optional]
     */
    public function removeNamedItem ($name) {}

    /**
     * Retrieves a node specified by index
     * @link http://php.net/manual/en/domnamednodemap.item.php
     * @param int $index <p>
     * Index into this map.
     * </p>
     * @return DOMNode The node at the indexth position in the map, or &null;
     * if that is not a valid index (greater than or equal to the number of nodes
     * in this map).
     * @since 5.0
     */
    public function item ($index) {}

    /**
     * Retrieves a node specified by local name and namespace URI
     * @link http://php.net/manual/en/domnamednodemap.getnameditemns.php
     * @param string $namespaceURI <p>
     * The namespace URI of the node to retrieve.
     * </p>
     * @param string $localName <p>
     * The local name of the node to retrieve.
     * </p>
     * @return DOMNode A node (of any type) with the specified local name and namespace URI, or
     * &null; if no node is found.
     * @since 5.0
     */
    public function getNamedItemNS ($namespaceURI, $localName) {}

    /**
     * @param DOMNode $arg [optional]
     */
    public function setNamedItemNS (DOMNode $arg) {}

    /**
     * @param $namespaceURI [optional]
     * @param $localName [optional]
     */
    public function removeNamedItemNS ($namespaceURI, $localName) {}

}

/**
 * The DOMCharacterData class represents nodes with character data.
 * No nodes directly correspond to this class, but other nodes do inherit from it.
 * @link http://php.net/manual/en/class.domcharacterdata.php
 */
class DOMCharacterData extends DOMNode  {


    /**
     * @var string
     * @since 5.0
     * The contents of the node.
     * @link http://php.net/manual/en/class.domcharacterdata.php#domcharacterdata.props.data
     */
    public $data;

    /**
     * @var int
     * @since 5.0
     * The length of the contents.
     * @link http://php.net/manual/en/class.domcharacterdata.php#domcharacterdata.props.length
     */
    public $length;

    /**
     * Extracts a range of data from the node
     * @link http://php.net/manual/en/domcharacterdata.substringdata.php
     * @param int $offset <p>
     * Start offset of substring to extract.
     * </p>
     * @param int $count <p>
     * The number of characters to extract.
     * </p>
     * @return string The specified substring. If the sum of offset
     * and count exceeds the length, then all 16-bit units
     * to the end of the data are returned.
     * @since 5.0
     */
    public function substringData ($offset, $count) {}

    /**
     * Append the string to the end of the character data of the node
     * @link http://php.net/manual/en/domcharacterdata.appenddata.php
     * @param string $data <p>
     * The string to append.
     * </p>
     * @return void
     * @since 5.0
     */
    public function appendData ($data) {}

    /**
     * Insert a string at the specified 16-bit unit offset
     * @link http://php.net/manual/en/domcharacterdata.insertdata.php
     * @param int $offset <p>
     * The character offset at which to insert.
     * </p>
     * @param string $data <p>
     * The string to insert.
     * </p>
     * @return void
     * @since 5.0
     */
    public function insertData ($offset, $data) {}

    /**
     * Remove a range of characters from the node
     * @link http://php.net/manual/en/domcharacterdata.deletedata.php
     * @param int $offset <p>
     * The offset from which to start removing.
     * </p>
     * @param int $count <p>
     * The number of characters to delete. If the sum of
     * offset and count exceeds
     * the length, then all characters to the end of the data are deleted.
     * </p>
     * @return void
     * @since 5.0
     */
    public function deleteData ($offset, $count) {}

    /**
     * Replace a substring within the DOMCharacterData node
     * @link http://php.net/manual/en/domcharacterdata.replacedata.php
     * @param int $offset <p>
     * The offset from which to start replacing.
     * </p>
     * @param int $count <p>
     * The number of characters to replace. If the sum of
     * offset and count exceeds
     * the length, then all characters to the end of the data are replaced.
     * </p>
     * @param string $data <p>
     * The string with which the range must be replaced.
     * </p>
     * @return void
     * @since 5.0
     */
    public function replaceData ($offset, $count, $data) {}

}

/**
 * The DOMAttr interface represents an attribute in an DOMElement object.
 * @link http://php.net/manual/en/class.domattr.php
 */
class DOMAttr extends DOMNode
{

    /**
     * @var string
     * (PHP5)<br/>
     * The name of the attribute
     * @link http://php.net/manual/en/class.domattr.php#domattr.props.name
     */
    public $name;

    /**
     * @var DOMElement
     * (PHP5)<br/>
     * The element which contains the attribute
     * @link http://php.net/manual/en/class.domattr.php#domattr.props.ownerelement
     */
    public $ownerElement;

    /**
     * @var bool
     * (PHP5)<br/>
     * Not implemented yet, always is NULL
     * @link http://php.net/manual/en/class.domattr.php#domattr.props.schematypeinfo
     */
    public $schemaTypeInfo;

    /**
     * @var bool
     * (PHP5)<br/>
     * Not implemented yet, always is NULL
     * @link http://php.net/manual/en/class.domattr.php#domattr.props.specified
     */
    public $specified;

    /**
     * @var string
     * (PHP5)<br/>
     * The value of the attribute
     * @link http://php.net/manual/en/class.domattr.php#domattr.props.value
     */
    public $value;

    /**
     * Checks if attribute is a defined ID
     * @link http://php.net/manual/en/domattr.isid.php
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function isId() {}

    /**
     * Creates a new <classname>DOMAttr</classname> object
     * @link http://php.net/manual/en/domattr.construct.php
     * @param $name
     * @param $value [optional]
     * @since 5.0
     */
    public function __construct($name, $value) {}
}

/**
 * The DOMElement class
 * @link http://php.net/manual/en/class.domelement.php
 */
class DOMElement extends DOMNode  {


    /**
     * @var DOMElement
     * @since 5.0
     * The parent of this node
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.parentnode
     */
    public $parentNode;

    /**
     * @var DOMElement
     * @since 5.0
     * The first child of this node. If there is no such node, this returns NULL.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.firstchild
     */
    public $firstChild;

    /**
     * @var DOMElement
     * @since 5.0
     * The last child of this node. If there is no such node, this returns NULL.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.lastchild
     */
    public $lastChild;

    /**
     * @var DOMElement
     * @since 5.0
     * The node immediately preceding this node. If there is no such node, this returns NULL.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.previoussibling
     */
    public $previousSibling;

    /**
     * @var DOMElement
     * @since 5.0
     * The node immediately following this node. If there is no such node, this returns NULL.
     * @link http://php.net/manual/en/class.domnode.php#domnode.props.nextsibling
     */
    public $nextSibling;

    /**
     * @var bool
     * @since 5.0
     * Not implemented yet, always return NULL
     * @link http://php.net/manual/en/class.domelement.php#domelement.props.schematypeinfo
     */
    public $schemaTypeInfo ;

    /**
     * @var string
     * @since 5.0
     * The element name
     * @link http://php.net/manual/en/class.domelement.php#domelement.props.tagname
     */
    public $tagName ;

    /**
     * Returns value of attribute
     * @link http://php.net/manual/en/domelement.getattribute.php
     * @param string $name <p>
     * The name of the attribute.
     * </p>
     * @return string The value of the attribute, or an empty string if no attribute with the
     * given name is found.
     * @since 5.0
     */
    public function getAttribute ($name) {}

    /**
     * Adds new attribute
     * @link http://php.net/manual/en/domelement.setattribute.php
     * @param string $name <p>
     * The name of the attribute.
     * </p>
     * @param string $value <p>
     * The value of the attribute.
     * </p>
     * @return DOMAttr The new DOMAttr or false if an error occured.
     * @since 5.0
     */
    public function setAttribute ($name, $value) {}

    /**
     * Removes attribute
     * @link http://php.net/manual/en/domelement.removeattribute.php
     * @param string $name <p>
     * The name of the attribute.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function removeAttribute ($name) {}

    /**
     * Returns attribute node
     * @link http://php.net/manual/en/domelement.getattributenode.php
     * @param string $name <p>
     * The name of the attribute.
     * </p>
     * @return DOMAttr The attribute node.
     * @since 5.0
     */
    public function getAttributeNode ($name) {}

    /**
     * Adds new attribute node to element
     * @link http://php.net/manual/en/domelement.setattributenode.php
     * @param DOMAttr $attr <p>
     * The attribute node.
     * </p>
     * @return DOMAttr old node if the attribute has been replaced or &null;.
     * @since 5.0
     */
    public function setAttributeNode (DOMAttr $attr) {}

    /**
     * Removes attribute
     * @link http://php.net/manual/en/domelement.removeattributenode.php
     * @param DOMAttr $oldnode <p>
     * The attribute node.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function removeAttributeNode (DOMAttr $oldnode) {}

    /**
     * Gets elements by tagname
     * @link http://php.net/manual/en/domelement.getelementsbytagname.php
     * @param string $name <p>
     * The tag name. Use * to return all elements within
     * the element tree.
     * </p>
     * @return DOMNodeList This function returns a new instance of the class
     * DOMNodeList of all matched elements.
     * @since 5.0
     */
    public function getElementsByTagName ($name) {}

    /**
     * Returns value of attribute
     * @link http://php.net/manual/en/domelement.getattributens.php
     * @param string $namespaceURI <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name.
     * </p>
     * @return string The value of the attribute, or an empty string if no attribute with the
     * given localName and namespaceURI
     * is found.
     * @since 5.0
     */
    public function getAttributeNS ($namespaceURI, $localName) {}

    /**
     * Adds new attribute
     * @link http://php.net/manual/en/domelement.setattributens.php
     * @param string $namespaceURI <p>
     * The namespace URI.
     * </p>
     * @param string $qualifiedName <p>
     * The qualified name of the attribute, as prefix:tagname.
     * </p>
     * @param string $value <p>
     * The value of the attribute.
     * </p>
     * @return void
     * @since 5.0
     */
    public function setAttributeNS ($namespaceURI, $qualifiedName, $value) {}

    /**
     * Removes attribute
     * @link http://php.net/manual/en/domelement.removeattributens.php
     * @param string $namespaceURI <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function removeAttributeNS ($namespaceURI, $localName) {}

    /**
     * Returns attribute node
     * @link http://php.net/manual/en/domelement.getattributenodens.php
     * @param string $namespaceURI <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name.
     * </p>
     * @return DOMAttr The attribute node.
     * @since 5.0
     */
    public function getAttributeNodeNS ($namespaceURI, $localName) {}

    /**
     * Adds new attribute node to element
     * @link http://php.net/manual/en/domelement.setattributenodens.php
     * @param DOMAttr $attr
     * @return DOMAttr the old node if the attribute has been replaced.
     * @since 5.0
     */
    public function setAttributeNodeNS (DOMAttr $attr) {}

    /**
     * Get elements by namespaceURI and localName
     * @link http://php.net/manual/en/domelement.getelementsbytagnamens.php
     * @param string $namespaceURI <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name. Use * to return all elements within
     * the element tree.
     * </p>
     * @return DOMNodeList This function returns a new instance of the class
     * DOMNodeList of all matched elements in the order in
     * which they are encountered in a preorder traversal of this element tree.
     * @since 5.0
     */
    public function getElementsByTagNameNS ($namespaceURI, $localName) {}

    /**
     * Checks to see if attribute exists
     * @link http://php.net/manual/en/domelement.hasattribute.php
     * @param string $name <p>
     * The attribute name.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function hasAttribute ($name) {}

    /**
     * Checks to see if attribute exists
     * @link http://php.net/manual/en/domelement.hasattributens.php
     * @param string $namespaceURI <p>
     * The namespace URI.
     * </p>
     * @param string $localName <p>
     * The local name.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function hasAttributeNS ($namespaceURI, $localName) {}

    /**
     * Declares the attribute specified by name to be of type ID
     * @link http://php.net/manual/en/domelement.setidattribute.php
     * @param string $name <p>
     * The name of the attribute.
     * </p>
     * @param bool $isId <p>
     * Set it to true if you want name to be of type
     * ID, false otherwise.
     * </p>
     * @return void
     * @since 5.0
     */
    public function setIdAttribute ($name, $isId) {}

    /**
     * Declares the attribute specified by local name and namespace URI to be of type ID
     * @link http://php.net/manual/en/domelement.setidattributens.php
     * @param string $namespaceURI <p>
     * The namespace URI of the attribute.
     * </p>
     * @param string $localName <p>
     * The local name of the attribute, as prefix:tagname.
     * </p>
     * @param bool $isId <p>
     * Set it to true if you want name to be of type
     * ID, false otherwise.
     * </p>
     * @return void
     * @since 5.0
     */
    public function setIdAttributeNS ($namespaceURI, $localName, $isId) {}

    /**
     * Declares the attribute specified by node to be of type ID
     * @link http://php.net/manual/en/domelement.setidattributenode.php
     * @param DOMAttr $attr <p>
     * The attribute node.
     * </p>
     * @param bool $isId <p>
     * Set it to true if you want name to be of type
     * ID, false otherwise.
     * </p>
     * @return void
     * @since 5.0
     */
    public function setIdAttributeNode (DOMAttr $attr, $isId) {}

    /**
     * Creates a new DOMElement object
     * @link http://php.net/manual/en/domelement.construct.php
     * @param $name string The tag name of the element. When also passing in namespaceURI, the element name may take a prefix to be associated with the URI.
     * @param $value string [optional] The value of the element.
     * @param $uri string [optional] A namespace URI to create the element within a specific namespace.
     * @since 5.0
     */
    public function __construct ($name, $value, $uri) {}

    /**
     * Adds a new child before a reference node
     * @link http://php.net/manual/en/domnode.insertbefore.php
     * @param DOMNode $newnode <p>
     * The new node.
     * </p>
     * @param DOMNode $refnode [optional] <p>
     * The reference node. If not supplied, newnode is
     * appended to the children.
     * </p>
     * @return DOMNode The inserted node.
     * @since 5.0
     */
    public function insertBefore (DOMNode $newnode , $refnode = null) {}

    /**
     * Replaces a child
     * @link http://php.net/manual/en/domnode.replacechild.php
     * @param DOMNode $newnode <p>
     * The new node. It must be a member of the target document, i.e.
     * created by one of the DOMDocument->createXXX() methods or imported in
     * the document by .
     * </p>
     * @param DOMNode $oldnode <p>
     * The old node.
     * </p>
     * @return DOMNode The old node or false if an error occur.
     * @since 5.0
     */
    public function replaceChild (DOMNode $newnode , DOMNode $oldnode ) {}

    /**
     * Removes child from list of children
     * @link http://php.net/manual/en/domnode.removechild.php
     * @param DOMNode $oldnode <p>
     * The removed child.
     * </p>
     * @return DOMNode If the child could be removed the functions returns the old child.
     * @since 5.0
     */
    public function removeChild (DOMNode $oldnode ) {}

}

/**
 * The DOMText class inherits from <classname>DOMCharacterData</classname> and represents the textual content of
 * a <classname>DOMElement</classname> or <classname>DOMAttr</classname>.
 * @link http://php.net/manual/en/class.domtext.php
 */
class DOMText extends DOMCharacterData  {

    /**
     * @var
     * @since 5.0
     * Holds all the text of logically-adjacent (not separated by Element, Comment or Processing Instruction) Text nodes.
     * @link http://php.net/manual/en/class.domtext.php#domtext.props.wholeText
     */
    public $wholeText;

    /**
     * Breaks this node into two nodes at the specified offset
     * @link http://php.net/manual/en/domtext.splittext.php
     * @param int $offset <p>
     * The offset at which to split, starting from 0.
     * </p>
     * @return DOMText The new node of the same type, which contains all the content at and after the
     * offset.
     * @since 5.0
     */
    public function splitText ($offset) {}

    /**
     * Indicates whether this text node contains whitespace
     * @link http://php.net/manual/en/domtext.iswhitespaceinelementcontent.php
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function isWhitespaceInElementContent () {}

    public function isElementContentWhitespace () {}

    /**
     * @param $content
     */
    public function replaceWholeText ($content) {}

    /**
     * Creates a new <classname>DOMText</classname> object
     * @link http://php.net/manual/en/domtext.construct.php
     * @param $value [optional] The value of the text node. If not supplied an empty text node is created.
     * @since 5.0
     */
    public function __construct ($value) {}

}

/**
 * The DOMComment class represents comment nodes,
 * characters delimited by lt;!-- and --&gt;.
 * @link http://php.net/manual/en/class.domcomment.php
 */
class DOMComment extends DOMCharacterData  {

    /**
     * Creates a new DOMComment object
     * @link http://php.net/manual/en/domcomment.construct.php
     * @param $value [optional] The value of the comment
     * @since 5.0
     */
    public function __construct ($value) {}
}

class DOMTypeinfo  {
}

class DOMUserDataHandler  {

    public function handle () {}

}

class DOMDomError  {
}

class DOMErrorHandler  {

    /**
     * @param DOMDomError $error
     */
    public function handleError (DOMDomError $error) {}

}

class DOMLocator  {
}

class DOMConfiguration  {

    /**
     * @param $name
     * @param $value
     */
    public function setParameter ($name, $value) {}

    /**
     * @param $name [optional]
     */
    public function getParameter ($name) {}

    /**
     * @param $name [optional]
     * @param $value [optional]
     */
    public function canSetParameter ($name, $value) {}

}

/**
 * The DOMCdataSection inherits from DOMText for textural representation of CData constructs.
 * @link http://www.php.net/manual/en/class.domcdatasection.php
 */
class DOMCdataSection extends DOMText  {

    /**
     * The value of the CDATA node. If not supplied, an empty CDATA node is created.
     * @param string $value The value of the CDATA node. If not supplied, an empty CDATA node is created.
     * @link http://www.php.net/manual/en/domcdatasection.construct.php
     * @since 5.0
     */
    public function __construct ($value) {}
}

/**
 * The DOMDocumentType class
 * @link http://php.net/manual/en/class.domdocumenttype.php
 */
class DOMDocumentType extends DOMNode
{

    /**
     * @var string
     * @since 5.0
     * The public identifier of the external subset.
     * @link http://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.publicid
     */
    public $publicId;

    /**
     * @var string
     * @since 5.0
     * The system identifier of the external subset. This may be an absolute URI or not.
     * @link http://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.systemid
     */
    public $systemId;

    /**
     * @var string
     * @since 5.0
     * The name of DTD; i.e., the name immediately following the DOCTYPE keyword.
     * @link http://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.name
     */
    public $name;

    /**
     * @var DOMNamedNodeMap
     * @since 5.0
     * A <classname>DOMNamedNodeMap</classname> containing the general entities, both external and internal, declared in the DTD.
     * @link http://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.entities
     */
    public $entities;

    /**
     * @var DOMNamedNodeMap
     * @since 5.0
     * A <clasname>DOMNamedNodeMap</classname> containing the notations declared in the DTD.
     * @link http://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.notations
     */
    public $notations;

    /**
     * @var string
     * @since 5.0
     * The internal subset as a string, or null if there is none. This is does not contain the delimiting square brackets.
     * @link http://php.net/manual/en/class.domdocumenttype.php#domdocumenttype.props.internalsubset
     */
    public $internalSubset;
}

/**
 * The DOMNotation class
 * @link http://php.net/manual/en/class.domnotation.php
 */
class DOMNotation  extends DOMNode{


    /**
     * @var string
     * @since 5.0
     *
     * @link http://php.net/manual/en/class.domnotation.php#domnotation.props.publicid
     */
    public $publicId;

    /**
     * @var string
     * @since 5.0
     *
     * @link http://php.net/manual/en/class.domnotation.php#domnotation.props.systemid
     */
    public $systemId;

}

/**
 * The DOMEntity class represents a known entity, either parsed or unparsed, in an XML document.
 * @link http://php.net/manual/en/class.domentity.php
 */
class DOMEntity extends DOMNode  {

    /**
     * @var string
     * @since 5.0
     * The public identifier associated with the entity if specified, and NULL otherwise.
     * @link http://php.net/manual/en/class.domentity.php#domentity.props.publicid
     */
    public $publicId ;

    /**
     * @var string
     * @since 5.0
     * The system identifier associated with the entity if specified, and NULL otherwise. This may be an
     * absolute URI or not.
     * @link http://php.net/manual/en/class.domentity.php#domentity.props.systemid
     */
    public $systemId ;

    /**
     * @var string
     * @since 5.0
     * For unparsed entities, the name of the notation for the entity. For parsed entities, this is NULL.
     * @link http://php.net/manual/en/class.domentity.php#domentity.props.notationname
     */
    public $notationName ;

    /**
     * @var string
     * @since 5.0
     * An attribute specifying the encoding used for this entity at the time of parsing, when it is an external
     * parsed entity. This is NULL if it an entity from the internal subset or if it is not known.
     * @link http://php.net/manual/en/class.domentity.php#domentity.props.actualencoding
     */
    public $actualEncoding ;

    /**
     * @var string
     * @since 5.0
     * An attribute specifying, as part of the text declaration, the encoding of this entity, when it is an external
     * parsed entity. This is NULL otherwise.
     * @link http://php.net/manual/en/class.domentity.php#domentity.props.encoding
     */
    public $encoding ;

    /**
     * @var string
     * @since 5.0
     * An attribute specifying, as part of the text declaration, the version number of this entity, when it is an
     * external parsed entity. This is NULL otherwise.
     * @link http://php.net/manual/en/class.domentity.php#domentity.props.version
     */
    public $version ;

}

/**
 * Extends DOMNode.
 * @link http://php.net/manual/en/class.domentityreference.php
 */
class DOMEntityReference extends DOMNode  {

    /**
     * Creates a new DOMEntityReference object
     * @link http://php.net/manual/en/domentityreference.construct.php
     * @param $name string The name of the entity reference.
     * @since 5.0
     */
    public function __construct ($name) {}

}

/**
 * The DOMProcessingInstruction class
 * @link http://php.net/manual/en/class.domprocessinginstruction.php
 */
class DOMProcessingInstruction extends DOMNode  {

    /**
     * @var
     * @since 5.0
     *
     * @link http://php.net/manual/en/class.domprocessinginstruction.php#domprocessinginstruction.props.target
     */
    public $target;

    /**
     * @var
     * @since 5.0
     *
     * @link http://php.net/manual/en/class.domprocessinginstruction.php#domprocessinginstruction.props.data
     */
    public $data;

    /**
     * Creates a new <classname>DOMProcessingInstruction</classname> object
     * @link http://php.net/manual/en/domprocessinginstruction.construct.php
     * @param $name string The tag name of the processing instruction.
     * @param $value string [optional] The value of the processing instruction.
     * @since 5.0
     */
    public function __construct ($name, $value) {}

}

class DOMStringExtend  {

    /**
     * @param $offset32
     */
    public function findOffset16 ($offset32) {}

    /**
     * @param $offset16
     */
    public function findOffset32 ($offset16) {}

}

/**
 * The DOMXPath class (supports XPath 1.0)
 * @link http://php.net/manual/en/class.domxpath.php
 */
class DOMXPath  {



    /**
     * @var DOMDocument
     * @since 5.0
     *
     * @link http://php.net/manual/en/class.domxpath.php#domxpath.props.document
     */
    public $document;

    /**
     * Creates a new <classname>DOMXPath</classname> object
     * @link http://php.net/manual/en/domxpath.construct.php
     * @param DOMDocument $doc The <classname>DOMDocument</classname> associated with the <classname>DOMXPath</classname>.
     * @since 5.0
     */
    public function __construct (DOMDocument $doc) {}

    /**
     * Registers the namespace with the <classname>DOMXPath</classname> object
     * @link http://php.net/manual/en/domxpath.registernamespace.php
     * @param string $prefix <p>
     * The prefix.
     * </p>
     * @param string $namespaceURI <p>
     * The URI of the namespace.
     * </p>
     * @return bool true on success or false on failure.
     * @since 5.0
     */
    public function registerNamespace ($prefix, $namespaceURI) {}

    /**
     * Evaluates the given XPath expression
     * @link http://php.net/manual/en/domxpath.query.php
     * @param string $expression <p>
     * The XPath expression to execute.
     * </p>
     * @param DOMNode $contextnode [optional] <p>
     * The optional contextnode can be specified for
     * doing relative XPath queries. By default, the queries are relative to
     * the root element.
     * </p>
     * @return DOMNodeList a DOMNodeList containing all nodes matching
     * the given XPath expression. Any expression which do
     * not return nodes will return an empty DOMNodeList.
     * @since 5.0
     */
    public function query ($expression, $contextnode = null) {}

    /**
     * Evaluates the given XPath expression and returns a typed result if possible.
     * @link http://php.net/manual/en/domxpath.evaluate.php
     * @param string $expression <p>
     * The XPath expression to execute.
     * </p>
     * @param DOMNode $contextnode [optional] <p>
     * The optional contextnode can be specified for
     * doing relative XPath queries. By default, the queries are relative to
     * the root element.
     * </p>
     * @return mixed a typed result if possible or a DOMNodeList
     * containing all nodes matching the given XPath expression.
     * @since 5.1.0
     */
    public function evaluate ($expression, $contextnode = null) {}

    /**
     * Register PHP functions as XPath functions
     * @link http://php.net/manual/en/domxpath.registerphpfunctions.php
     * @param mixed $restrict [optional] <p>
     * Use this parameter to only allow certain functions to be called from XPath.
     * </p>
     * <p>
     * This parameter can be either a string (a function name) or
     * an array of function names.
     * </p>
     * @return void
     * @since 5.3.0
     */
    public function registerPhpFunctions ($restrict = null) {}

}
