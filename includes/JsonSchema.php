<?php
/**
 * JSON Schema Validation Library
 *
 * Copyright (c) 2005-2012, Rob Lanphier
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 * 	* Redistributions of source code must retain the above copyright
 * 	  notice, this list of conditions and the following disclaimer.
 *
 * 	* Redistributions in binary form must reproduce the above
 * 	  copyright notice, this list of conditions and the following
 * 	  disclaimer in the documentation and/or other materials provided
 * 	  with the distribution.
 *
 * 	* Neither my name nor the names of my contributors may be used to
 * 	  endorse or promote products derived from this software without
 * 	  specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Rob Lanphier <robla@wikimedia.org>
 * @copyright © 2011-2012 Rob Lanphier
 * @licence http://jsonwidget.org/LICENSE BSD 3-clause
 */

/*
 * Note, this is a standalone component.  Please don't mix MediaWiki-specific
 * code or library calls into this file.
 */

class JsonSchemaException extends Exception {
	public $subtype;
	// subtypes: "validate-fail", "validate-fail-null"
}

class JsonUtil {
	/**
	 * Converts the string into something safe for an HTML id.
	 * performs the easiest transformation to safe id, but is lossy
	 */
	public static function stringToId( $var ) {
		if ( is_int( $var ) ) {
			return (string)$var;
		} elseif ( is_string( $var ) ) {
			return preg_replace( '/[^a-z0-9\-_:\.]/i', '', $var );
		} else {
			$msg = JsonUtil::uiMessage( 'jsonschema-idconvert', JsonUtil::encodeForMsg( $var ) );
			throw new JsonSchemaException( $msg );
		}

	}

	/**
	 * Converts data to JSON format with pretty-formatting, but limited to a single line and escaped
	 * to be suitable for wikitext message parameters.
	 */
	public static function encodeForMsg( $data ) {
		if ( class_exists( 'FormatJson' ) && function_exists( 'wfEscapeWikiText' ) ) {
			$json = FormatJson::encode( $data, "\t", FormatJson::ALL_OK );
			// Literal newlines can't appear in JSON string values, so this neatly folds the formatting
			$json = preg_replace( "/\n\t+/", ' ', $json );
			return wfEscapeWikiText( $json );
		} else {
			return json_encode( $data );
		}
	}

	/**
	 * Given a type (e.g. 'object', 'integer', 'string'), return the default/empty
	 * value for that type.
	 */
	public static function getNewValueForType( $thistype ) {
		switch ( $thistype ) {
			case 'object':
				$newvalue = [];
				break;
			case 'array':
				$newvalue = [];
				break;
			case 'number':
				case 'integer':
					$newvalue = 0;
					break;
				case 'string':
					$newvalue = "";
					break;
				case 'boolean':
					$newvalue = false;
					break;
				default:
					$newvalue = null;
					break;
		}

		return $newvalue;
	}

	/**
	 * Return a JSON-schema type for arbitrary data $foo
	 */
	public static function getType( $foo ) {
		if ( is_null( $foo ) ) {
			return null;
		}

		switch ( gettype( $foo ) ) {
			case "array":
				$retval = "array";
				foreach ( array_keys( $foo ) as $key ) {
					if ( !is_int( $key ) ) {
						$retval = "object";
					}
				}
				return $retval;
				break;
			case "integer":
			case "double":
				return "number";
				break;
			case "boolean":
				return "boolean";
				break;
			case "string":
				return "string";
				break;
			default:
				return null;
				break;
		}

	}

	/**
	 * Generate a schema from a data example ($parent)
	 */
	public static function getSchemaArray( $parent ) {
		$schema = [];
		$schema['type'] = JsonUtil::getType( $parent );
		switch ( $schema['type'] ) {
			case 'object':
				$schema['properties'] = [];
				foreach ( $parent as $name ) {
					$schema['properties'][$name] = JsonUtil::getSchemaArray( $parent[$name] );
				}

				break;
			case 'array':
				$schema['items'] = [];
				$schema['items'][0] = JsonUtil::getSchemaArray( $parent[0] );
				break;
		}

		return $schema;
	}

	/**
	 * User interface messages suitable for translation.
	 * Note: this merely acts as a passthrough to MediaWiki's wfMessage call.
	 */
	public static function uiMessage() {
		if ( function_exists( 'wfMessage' ) ) {
			return call_user_func_array( 'wfMessage', $params = func_get_args() );
		} else {
			// TODO: replace this with a real solution that works without
			// MediaWiki
			$params = func_get_args();
			return implode( " ", $params );
		}
	}
}

/*
 * Internal terminology:
 *   Node: "node" in the graph theory sense, but specifically, a node in the
 *    raw PHP data representation of the structure
 *   Ref: a node in the object tree.  Refs contain nodes and metadata about the
 *    nodes, as well as pointers to parent refs
 */

/**
 * Structure for representing a generic tree which each node is aware of its
 * context (can refer to its parent).  Used for schema refs.
 */
class TreeRef {
	public $node;
	public $parent;
	public $nodeindex;
	public $nodename;
	public function __construct( $node, $parent, $nodeindex, $nodename ) {
		$this->node = $node;
		$this->parent = $parent;
		$this->nodeindex = $nodeindex;
		$this->nodename = $nodename;
	}
}

/**
 * Structure for representing a data tree, where each node (ref) is aware of its
 * context and associated schema.
 */
class JsonTreeRef {
	public function __construct( $node, $parent = null, $nodeindex = null,
			$nodename = null, $schemaref = null ) {
		$this->node = $node;
		$this->parent = $parent;
		$this->nodeindex = $nodeindex;
		$this->nodename = $nodename;
		$this->schemaref = $schemaref;
		$this->fullindex = $this->getFullIndex();
		$this->datapath = [];
		if ( !is_null( $schemaref ) ) {
			$this->attachSchema();
		}
	}

	/**
	 * Associate the relevant node of the JSON schema to this node in the JSON
	 */
	public function attachSchema( $schema = null ) {
		if ( !is_null( $schema ) ) {
			$this->schemaindex = new JsonSchemaIndex( $schema );
			$this->nodename =
				isset( $schema['title'] ) ? $schema['title'] : "Root node";
			$this->schemaref = $this->schemaindex->newRef( $schema, null, null, $this->nodename );
		} elseif ( !is_null( $this->parent ) ) {
			$this->schemaindex = $this->parent->schemaindex;
		}
	}

	/**
	 *  Return the title for this ref, typically defined in the schema as the
	 *  user-friendly string for this node.
	 */
	public function getTitle() {
		if ( isset( $this->nodename ) ) {
			return $this->nodename;
		} elseif ( isset( $this->node['title'] ) ) {
			return $this->node['title'];
		} else {
			return $this->nodeindex;
		}
	}

	/**
	 * Rename a user key.  Useful for interactive editing/modification, but not
	 * so helpful for static interpretation.
	 */
	public function renamePropname( $newindex ) {
		$oldindex = $this->nodeindex;
		$this->parent->node[$newindex] = $this->node;
		$this->nodeindex = $newindex;
		$this->nodename = $newindex;
		$this->fullindex = $this->getFullIndex();
		unset( $this->parent->node[$oldindex] );
	}

	/**
	 * Return the type of this node as specified in the schema.  If "any",
	 * infer it from the data.
	 */
	public function getType() {
		if ( array_key_exists( 'type', $this->schemaref->node ) ) {
			$nodetype = $this->schemaref->node['type'];
		} else {
			$nodetype = 'any';
		}

		if ( $nodetype == 'any' ) {
			if ( $this->node === null ) {
				return null;
			} else {
				return JsonUtil::getType( $this->node );
			}
		} else {
			return $nodetype;
		}

	}

	/**
	 * Return a unique identifier that may be used to find a node.  This
	 * is only as robust as stringToId is (i.e. not that robust), but is
	 * good enough for many cases.
	 */
	public function getFullIndex() {
		if ( is_null( $this->parent ) ) {
			return "json_root";
		} else {
			return $this->parent->getFullIndex() . "." . JsonUtil::stringToId( $this->nodeindex );
		}
	}

	/**
	 *  Get a path to the element in the array.  if $foo['a'][1] would load the
	 *  node, then the return value of this would be array('a',1)
	 */
	public function getDataPath() {
		if ( !is_object( $this->parent ) ) {
			return [];
		} else {
			$retval = $this->parent->getDataPath();
			$retval[] = $this->nodeindex;
			return $retval;
		}
	}

	/**
	 *  Return path in something that looks like an array path.  For example,
	 *  for this data: [{'0a':1,'0b':{'0ba':2,'0bb':3}},{'1a':4}]
	 *  the leaf node with a value of 4 would have a data path of '[1]["1a"]',
	 *  while the leaf node with a value of 2 would have a data path of
	 *  '[0]["0b"]["oba"]'
	 */
	public function getDataPathAsString() {
		$retval = "";
		foreach ( $this->getDataPath() as $item ) {
			$retval .= '[' . json_encode( $item ) . ']';
		}
		return $retval;
	}

	/**
	 *  Return data path in user-friendly terms.  This will use the same
	 *  terminology as used in the user interface (1-indexed arrays)
	 */
	public function getDataPathTitles() {
		if ( !is_object( $this->parent ) ) {
			return $this->getTitle();
		} else {
			return $this->parent->getDataPathTitles() . ' -> '
				. $this->getTitle();
		}
	}

	/**
	 * Return the child ref for $this ref associated with a given $key
	 */
	public function getMappingChildRef( $key ) {
		$snode = $this->schemaref->node;
		$schemadata = [];
		$nodename = $key;
		if ( array_key_exists( 'properties', $snode ) &&
			array_key_exists( $key, $snode['properties'] ) ) {
			$schemadata = $snode['properties'][$key];
			$nodename = isset( $schemadata['title'] ) ? $schemadata['title'] : $key;
		} elseif ( array_key_exists( 'additionalProperties', $snode ) ) {
			// additionalProperties can *either* be a boolean or can be
			// defined as a schema (an object)
			if ( gettype( $snode['additionalProperties'] ) == "boolean" ) {
				if ( !$snode['additionalProperties'] ) {
					$msg = JsonUtil::uiMessage( 'jsonschema-invalidkey',
												$key, $this->getDataPathTitles() );
					throw new JsonSchemaException( $msg );
				}
			} else {
				$schemadata = $snode['additionalProperties'];
				$nodename = $key;
			}
		}
		$value = $this->node[$key];
		$schemai = $this->schemaindex->newRef( $schemadata, $this->schemaref, $key, $key );
		$jsoni = new JsonTreeRef( $value, $this, $key, $nodename, $schemai );
		return $jsoni;
	}

	/**
	 * Return the child ref for $this ref associated with a given index $i
	 */
	public function getSequenceChildRef( $i ) {
		// TODO: make this conform to draft-03 by also allowing single object
		if ( array_key_exists( 'items', $this->schemaref->node ) ) {
			$schemanode = $this->schemaref->node['items'][0];
		} else {
			$schemanode = [];
		}
		$itemname = isset( $schemanode['title'] ) ? $schemanode['title'] : "Item";
		$nodename = $itemname . " #" . ( (string)$i + 1 );
		$schemai = $this->schemaindex->newRef( $schemanode, $this->schemaref, 0, $i );
		$jsoni = new JsonTreeRef( $this->node[$i], $this, $i, $nodename, $schemai );
		return $jsoni;
	}

	/**
	 * Validate the JSON node in this ref against the attached schema ref.
	 * Return true on success, and throw a JsonSchemaException on failure.
	 */
	public function validate() {
		if ( array_key_exists( 'enum', $this->schemaref->node ) &&
			!in_array( $this->node, $this->schemaref->node['enum'] ) ) {
			$msg = JsonUtil::uiMessage( 'jsonschema-invalid-notinenum',
				JsonUtil::encodeForMsg( $this->node ), $this->getDataPathTitles() );
			$e = new JsonSchemaException( $msg );
			$e->subtype = "validate-fail";
			throw( $e );
		}
		$datatype = JsonUtil::getType( $this->node );
		$schematype = $this->getType();
		if ( $datatype == 'array' && $schematype == 'object' ) {
			// PHP datatypes are kinda loose, so we'll fudge
			$datatype = 'object';
		}
		if ( $datatype == 'number' && $schematype == 'integer' &&
			 $this->node == (int)$this->node ) {
			// Alright, it'll work as an int
			$datatype = 'integer';
		}
		if ( $datatype != $schematype ) {
			if ( is_null( $datatype ) && !is_object( $this->parent ) ) {
				$msg = JsonUtil::uiMessage( 'jsonschema-invalidempty' );
				$e = new JsonSchemaException( $msg );
				$e->subtype = "validate-fail-null";
				throw( $e );
			} else {
				$datatype = is_null( $datatype ) ? "null" : $datatype;
				$msg = JsonUtil::uiMessage( 'jsonschema-invalidnode',
					$schematype, $datatype, $this->getDataPathTitles() );
				$e = new JsonSchemaException( $msg );
				$e->subtype = "validate-fail";
				throw( $e );
			}
		}
		switch ( $schematype ) {
			case 'object':
				$this->validateObjectChildren();
				break;
			case 'array':
				$this->validateArrayChildren();
				break;
		}
		return true;
	}

	/**
	 */
	private function validateObjectChildren() {
		if ( array_key_exists( 'properties', $this->schemaref->node ) ) {
			foreach ( $this->schemaref->node['properties'] as $skey => $svalue ) {
				$keyRequired = array_key_exists( 'required', $svalue ) ? $svalue['required'] : false;
				if ( $keyRequired && !array_key_exists( $skey, $this->node ) ) {
					$msg = JsonUtil::uiMessage( 'jsonschema-invalid-missingfield', $skey );
					$e = new JsonSchemaException( $msg );
					$e->subtype = "validate-fail-missingfield";
					throw( $e );
				}
			}
		}

		foreach ( $this->node as $key => $value ) {
			$jsoni = $this->getMappingChildRef( $key );
			$jsoni->validate();
		}
		return true;
	}

	/*
	 */
	private function validateArrayChildren() {
		$length = count( $this->node );
		for ( $i = 0; $i < $length; $i++ ) {
			$jsoni = $this->getSequenceChildRef( $i );
			$jsoni->validate();
		}
	}
}

/**
 * The JsonSchemaIndex object holds all schema refs with an "id", and is used
 * to resolve an idref to a schema ref.  This also holds the root of the schema
 * tree.  This also serves as sort of a class factory for schema refs.
 */
class JsonSchemaIndex {
	public $root;
	public $idtable;
	/**
	 * The whole tree is indexed on instantiation of this class.
	 */
	public function __construct( $schema ) {
		$this->root = $schema;
		$this->idtable = [];

		if ( is_null( $this->root ) ) {
			return null;
		}

		$this->indexSubtree( $this->root );
	}

	/**
	 * Recursively find all of the ids in this schema, and store them in the
	 * index.
	 */
	public function indexSubtree( $schemanode ) {
		if ( !array_key_exists( 'type', $schemanode ) ) {
			$schemanode['type'] = 'any';
		}
		$nodetype = $schemanode['type'];
		switch ( $nodetype ) {
			case 'object':
				foreach ( $schemanode['properties'] as $value ) {
					$this->indexSubtree( $value );
				}

				break;
			case 'array':
				foreach ( $schemanode['items'] as $value ) {
					$this->indexSubtree( $value );
				}

				break;
		}
		if ( isset( $schemanode['id'] ) ) {
			$this->idtable[$schemanode['id']] = $schemanode;
		}
	}

	/**
	 * Generate a new schema ref, or return an existing one from the index if
	 * the node is an idref.
	 */
	public function newRef( $node, $parent, $nodeindex, $nodename ) {
		if ( array_key_exists( '$ref', $node ) ) {
			if ( strspn( $node['$ref'], '#' ) != 1 ) {
				$error = JsonUtil::uiMessage( 'jsonschema-badidref', $node['$ref'] );
				throw new JsonSchemaException( $error );
			}
			$idref = $node['$ref'];
			try {
				$node = $this->idtable[$idref];
			}
			catch ( Exception $e ) {
				$error = JsonUtil::uiMessage( 'jsonschema-badidref', $node['$ref'] );
				throw new JsonSchemaException( $error );
			}
		}

		return new TreeRef( $node, $parent, $nodeindex, $nodename );
	}
}
