<?php
/**
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
 * @author Roan Kattouw
 */

/**
 * Parser for Vue single file components (.vue files). See parse() for usage.
 *
 * @ingroup ResourceLoader
 * @since 1.35
 */
class VueComponentParser {

	/**
	 * Parse a Vue single file component, and extract the script, template and style parts.
	 *
	 * Returns an associative array with the following keys:
	 * - 'script': The JS code in the <script> tag
	 * - 'template': The HTML in the <template> tag, with whitespace removed
	 * - 'rawTemplate': The HTML in the <template> tag, unmodified (before whitespace removal)
	 * - 'style': The CSS/LESS styles in the <style> tag, or null if the <style> tag was missing
	 * - 'styleLang': The language used for 'style'; either 'css' or 'less', or null if no <style> tag
	 *
	 * @param string $html HTML with <script>, <template> and <style> tags at the top level
	 * @return array
	 * @throws Exception If the input is invalid
	 */
	public function parse( $html ) : array {
		$dom = $this->parseHTML( $html );

		// Find the <script>, <template> and <style> tags. They can appear in any order, but they
		// must be at the top level, and there can only be one of each.
		$nodes = $this->findUniqueTags( $dom, [ 'script', 'template', 'style' ] );

		// Throw an error if we didn't find a <script> or <template> tag. <style> is optional.
		foreach ( [ 'script', 'template' ] as $requiredTag ) {
			if ( !isset( $nodes[ $requiredTag ] ) ) {
				throw new Exception( "No <$requiredTag> tag found" );
			}
		}

		$this->validateAttributes( $nodes['script'], [] );
		$this->validateAttributes( $nodes['template'], [] );
		if ( isset( $nodes['style'] ) ) {
			$this->validateAttributes( $nodes['style'], [ 'lang' ] );
		}

		$rootTemplateNode = $this->getRootTemplateNode( $nodes['template'] );

		$rawTemplate = $dom->saveHTML( $rootTemplateNode );
		$this->removeWhitespaceAndComments( $rootTemplateNode );
		$minifiedTemplate = $dom->saveHTML( $rootTemplateNode );

		$styleData = isset( $nodes['style'] ) ? $this->getStyleAndLang( $nodes['style'] ) : null;

		return [
			'script' => trim( $nodes['script']->nodeValue ),
			'template' => $minifiedTemplate,
			'rawTemplate' => $rawTemplate,
			'style' => $styleData ? $styleData['style'] : null,
			'styleLang' => $styleData ? $styleData['lang'] : null
		];
	}

	/**
	 * Parse HTML, working around various annoying libxml behaviors.
	 * @param string $html
	 * @return DOMDocument
	 */
	private function parseHTML( $html ) : DOMDocument {
		$dom = new DOMDocument();
		// Disable external entities, see https://www.mediawiki.org/wiki/XML_External_Entity_Processing
		$loadEntities = libxml_disable_entity_loader( true );
		$useErrors = libxml_use_internal_errors( true );
		// Force UTF-8, and disable network access
		$dom->loadHTML( '<?xml encoding="utf-8">' . $html, LIBXML_NONET | LIBXML_HTML_NOIMPLIED );
		libxml_disable_entity_loader( $loadEntities );

		$errors = array_filter( libxml_get_errors(), function ( $error ) {
			return $error->code !== 801; // XML_HTML_UNKNOWN_TAG
		} );
		libxml_clear_errors();
		libxml_use_internal_errors( $useErrors );

		if ( $errors ) {
			throw new Exception( "HTML parse errors:\n" .
				implode( "\n", array_map( function ( $error ) {
					return $error->message . ' on line ' . $error->line;
				}, $errors ) )
			);
		}
		return $dom;
	}

	/**
	 * Find occurrences of specified tags in a DOM node, expecting at most one occurrence of each.
	 * This method only looks at the top-level children of $rootNode, it doesn't descend into them.
	 *
	 * @param DOMNode $rootNode Node whose children to look at
	 * @param string[] $tagNames Tag names to look for (must be all lowercase)
	 * @return DOMElement[] Associative arrays whose keys are tag names and values are DOM nodes
	 */
	private function findUniqueTags( DOMNode $rootNode, array $tagNames ) : array {
		$nodes = [];
		foreach ( $rootNode->childNodes as $node ) {
			$tagName = strtolower( $node->nodeName );
			if ( in_array( $tagName, $tagNames ) ) {
				if ( isset( $nodes[ $tagName ] ) ) {
					throw new Exception( "More than one <$tagName> tag found" );
				}
				$nodes[ $tagName ] = $node;
			}
		}
		return $nodes;
	}

	/**
	 * Verify that a given node only has a given set of attributes, and no others.
	 * @param DOMNode $node Node to check
	 * @param array $allowedAttributes Attributes the node is allowed to have
	 * @throws Exception If the node has an attribute it's not allowed to have
	 */
	private function validateAttributes( DOMNode $node, array $allowedAttributes ) {
		if ( $allowedAttributes ) {
			foreach ( $node->attributes as $attr ) {
				if ( !in_array( $attr->name, $allowedAttributes ) ) {
					throw new Exception( "<{$node->nodeName}> may not have the " .
						"{$attr->name} attribute" );
				}
			}
		} elseif ( $node->attributes->length > 0 ) {
			throw new Exception( "<{$node->nodeName}> may not have any attributes" );
		}
	}

	/**
	 * Get the root node of the template. This is the only child node of the <template> node.
	 * If the <template> node has multiple children, or is empty, or contains (non-whitespace) text,
	 * an exception is thrown.
	 *
	 * @param DOMNode $templateNode The <template> node
	 * @return DOMNode The only child of the template node
	 * @throws Exception If the contents of the <template> node are invalid
	 */
	private function getRootTemplateNode( DOMNode $templateNode ) : DOMNode {
		// Verify that the <template> tag only contains one tag, and put it in $rootTemplateNode
		// We can't use ->childNodes->length === 1 here because whitespace shows up as text nodes,
		// and comments are also allowed.
		$rootTemplateNode = null;
		foreach ( $templateNode->childNodes as $node ) {
			if ( $node->nodeType === XML_ELEMENT_NODE ) {
				if ( $rootTemplateNode !== null ) {
					throw new Exception( '<template> tag may not have multiple child tags' );
				}
				$rootTemplateNode = $node;
			} elseif ( $node->nodeType === XML_TEXT_NODE ) {
				// Text nodes are allowed, as long as they only contain whitespace
				if ( trim( $node->nodeValue ) !== '' ) {
					throw new Exception( '<template> tag may not contain text' );
				}
			} elseif ( $node->nodeType !== XML_COMMENT_NODE ) {
				// Comment nodes are allowed, anything else is not allowed
				throw new Exception( "<template> tag may only contain element and comment nodes, " .
					" found node of type {$node->nodeType}" );
			}
		}
		if ( $rootTemplateNode === null ) {
			throw new Exception( '<template> tag may not be empty' );
		}
		return $rootTemplateNode;
	}

	/**
	 * Recursively remove whitespace from all text nodes in a DOM subtree, and remove all comment nodes
	 * @param DOMNode $node
	 */
	private function removeWhitespaceAndComments( DOMNode $node ) {
		$toRemove = [];
		foreach ( $node->childNodes as $child ) {
			if ( $child->nodeType === XML_TEXT_NODE ) {
				$trimmedText = trim( $child->data );
				if ( $trimmedText === '' ) {
					$toRemove[] = $child;
				} else {
					$child->replaceData( 0, strlen( $child->data ), $trimmedText );
				}
			} elseif ( $child->nodeType === XML_COMMENT_NODE ) {
				$toRemove[] = $child;
			} elseif ( $child->nodeType === XML_ELEMENT_NODE ) {
				// Recurse, but don't strip whitespace inside <pre> tags
				if ( !in_array( strtolower( $child->nodeName ), [ 'pre', 'listing', 'textarea' ] ) ) {
					$this->removeWhitespaceAndComments( $child );
				}
			}
		}
		foreach ( $toRemove as $child ) {
			$node->removeChild( $child );
		}
	}

	/**
	 * Get the contents and language of the <style> tag. The language can be 'css' or 'less'.
	 * @param DOMElement $styleNode The <style> tag.
	 * @return array [ 'style' => string, 'lang' => string ]
	 * @throws Exception If an invalid language is used, or if the 'scoped' attribute is set.
	 */
	private function getStyleAndLang( DOMElement $styleNode ) : array {
		$style = $styleNode->nodeValue;
		$styleLang = $styleNode->hasAttribute( 'lang' ) ?
			$styleNode->getAttribute( 'lang' ) : 'css';
		if ( $styleLang !== 'css' && $styleLang !== 'less' ) {
			throw new Exception( "<style lang=\"$styleLang\"> is invalid," .
				" lang must be \"css\" or \"less\"" );
		}
		return [
			'style' => $style,
			'lang' => $styleLang,
		];
	}
}
