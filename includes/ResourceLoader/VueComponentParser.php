<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Roan Kattouw
 */

namespace MediaWiki\ResourceLoader;

use InvalidArgumentException;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\RemexHtml\HTMLData;
use Wikimedia\RemexHtml\Serializer\HtmlFormatter;
use Wikimedia\RemexHtml\Serializer\Serializer;
use Wikimedia\RemexHtml\Serializer\SerializerNode;
use Wikimedia\RemexHtml\Tokenizer\Attributes;
use Wikimedia\RemexHtml\Tokenizer\Tokenizer;
use Wikimedia\RemexHtml\TreeBuilder\Dispatcher;
use Wikimedia\RemexHtml\TreeBuilder\TreeBuilder;

/**
 * Parser for Vue single file components (.vue files). See parse() for usage.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class VueComponentParser {
	/**
	 * Parse a Vue single file component, and extract the script, template and style parts.
	 *
	 * Returns an associative array with the following keys:
	 * - 'script': The JS code in the <script> tag
	 * - 'template': The HTML in the <template> tag
	 * - 'style': The CSS/LESS styles in the <style> tag, or null if the <style> tag was missing
	 * - 'styleLang': The language used for 'style'; either 'css' or 'less', or null if no <style> tag
	 *
	 * The following options can be passed in the $options parameter:
	 * - 'minifyTemplate': Whether to minify the HTML in the template tag. This removes
	 *                     HTML comments and strips whitespace. Default: false
	 *
	 * @param string $html HTML with <script>, <template> and <style> tags at the top level
	 * @param array $options Associative array of options
	 * @return array
	 * @throws InvalidArgumentException If the input is invalid
	 */
	public function parse( string $html, array $options = [] ): array {
		// Ensure that <script>,<template>,etc tags go into the <body>, not
		// the <head>
		$doc = DOMUtils::parseHTML( "<body>$html" );
		$body = DOMCompat::getBody( $doc );

		// Find the <script>, <template> and <style> tags. They can appear in any order, but they
		// must be at the top level, and there can only be one of each.
		if ( !$body ) {
			throw new InvalidArgumentException( 'Parsed DOM did not contain a <body> tag' );
		}
		$nodes = $this->findUniqueTags( $body, [ 'script', 'template', 'style' ] );

		// Throw an error if we didn't find a <script> or <template> tag. <style> is optional.
		foreach ( [ 'script', 'template' ] as $requiredTag ) {
			if ( !isset( $nodes[ $requiredTag ] ) ) {
				throw new InvalidArgumentException( "No <$requiredTag> tag found" );
			}
		}

		$this->validateAttributes( $nodes['script'], [] );
		$this->validateAttributes( $nodes['template'], [] );
		if ( isset( $nodes['style'] ) ) {
			$this->validateAttributes( $nodes['style'], [ 'lang' ] );
		}

		$styleData = isset( $nodes['style'] ) ? $this->getStyleAndLang( $nodes['style'] ) : null;
		$template = $this->getTemplateHtml( $html, $options['minifyTemplate'] ?? false );

		return [
			'script' => trim( $nodes['script']->textContent ),
			'template' => $template,
			'style' => $styleData ? $styleData['style'] : null,
			'styleLang' => $styleData ? $styleData['lang'] : null
		];
	}

	/**
	 * Find occurrences of specified tags in a DOM node, expecting at most one occurrence of each.
	 * This method only looks at the top-level children of $rootNode, it doesn't descend into them.
	 *
	 * @param Element $rootNode Node whose children to look at
	 * @param string[] $tagNames Tag names to look for (must be all lowercase)
	 * @return Element[] Associative arrays whose keys are tag names and values are DOM nodes
	 */
	private function findUniqueTags( Element $rootNode, array $tagNames ): array {
		$nodes = [];
		for ( $node = DOMCompat::getFirstElementChild( $rootNode );
			 $node !== null;
			 $node = DOMCompat::getNextElementSibling( $node ) ) {
			$tagName = DOMUtils::nodeName( $node );
			if ( in_array( $tagName, $tagNames ) ) {
				if ( isset( $nodes[ $tagName ] ) ) {
					throw new InvalidArgumentException( "More than one <$tagName> tag found" );
				}
				$nodes[ $tagName ] = $node;
			}
		}
		return $nodes;
	}

	/**
	 * Verify that a given node only has a given set of attributes, and no others.
	 * @param Element $node Node to check
	 * @param list<string> $allowedAttributes Attributes the node is allowed to have
	 * @throws InvalidArgumentException If the node has an attribute it's not allowed to have
	 */
	private function validateAttributes( Element $node, array $allowedAttributes ): void {
		if ( $allowedAttributes ) {
			foreach ( DOMCompat::attributes( $node ) as $name => $value ) {
				if ( !in_array( $name, $allowedAttributes ) ) {
					$nodeName = DOMUtils::nodeName( $node );
					throw new InvalidArgumentException( "<{$nodeName}> may not have the " .
						"{$name} attribute" );
				}
			}
		} elseif ( count( DOMCompat::attributes( $node ) ) > 0 ) {
			$nodeName = DOMUtils::nodeName( $node );
			throw new InvalidArgumentException( "<{$nodeName}> may not have any attributes" );
		}
	}

	/**
	 * Get the contents and language of the <style> tag. The language can be 'css' or 'less'.
	 * @param Element $styleNode The <style> tag.
	 * @return array [ 'style' => string, 'lang' => string ]
	 * @throws InvalidArgumentException If an invalid language is used, or if the 'scoped' attribute is set.
	 */
	private function getStyleAndLang( Element $styleNode ): array {
		$style = trim( $styleNode->textContent );
		$styleLang = DOMCompat::getAttribute( $styleNode, 'lang' ) ?? 'css';
		if ( $styleLang !== 'css' && $styleLang !== 'less' ) {
			throw new InvalidArgumentException( "<style lang=\"$styleLang\"> is invalid," .
				" lang must be \"css\" or \"less\"" );
		}
		return [
			'style' => $style,
			'lang' => $styleLang,
		];
	}

	/**
	 * Get the HTML contents of the <template> tag, optionally minifed.
	 *
	 * To work around a bug in PHP's DOMDocument where attributes like @click get mangled,
	 * we re-parse the entire file using a Remex parse+serialize pipeline, with a custom dispatcher
	 * to zoom in on just the contents of the <template> tag, and a custom formatter for minification.
	 * Keeping everything in Remex and never converting it to DOM avoids the attribute mangling issue.
	 *
	 * @param string $html HTML that contains a <template> tag somewhere
	 * @param bool $minify Whether to minify the output (remove comments, strip whitespace)
	 * @return string HTML contents of the template tag
	 */
	private function getTemplateHtml( string $html, bool $minify ): string {
		$serializer = new Serializer( $this->newTemplateFormatter( $minify ) );
		$tokenizer = new Tokenizer(
			$this->newFilteringDispatcher(
				new TreeBuilder( $serializer, [ 'ignoreErrors' => true ] ),
				'template'
			),
			$html, [ 'ignoreErrors' => true ]
		);
		$tokenizer->execute( [ 'fragmentNamespace' => HTMLData::NS_HTML, 'fragmentName' => 'template' ] );
		return trim( $serializer->getResult() );
	}

	/**
	 * Custom HtmlFormatter subclass that optionally removes comments and strips whitespace.
	 * If $minify=false, this formatter falls through to HtmlFormatter for everything (except that
	 * it strips the <!doctype html> tag).
	 *
	 * @param bool $minify If true, remove comments and strip whitespace
	 */
	private function newTemplateFormatter( bool $minify ): HtmlFormatter {
		return new class( $minify ) extends HtmlFormatter {
			private bool $minify;

			public function __construct( bool $minify ) {
				$this->minify = $minify;
			}

			/** @inheritDoc */
			public function startDocument( $fragmentNamespace, $fragmentName ) {
				// Remove <!doctype html>
				return '';
			}

			/** @inheritDoc */
			public function comment( SerializerNode $parent, $text ) {
				if ( $this->minify ) {
					// Remove all comments
					return '';
				}
				return parent::comment( $parent, $text );
			}

			/** @inheritDoc */
			public function characters( SerializerNode $parent, $text, $start, $length ) {
				if (
					$this->minify && (
						// Don't touch <pre>/<listing>/<textarea> nodes
						$parent->namespace !== HTMLData::NS_HTML ||
						!isset( $this->prefixLfElements[ $parent->name ] )
					)
				) {
					$text = substr( $text, $start, $length );
					// Collapse runs of adjacent whitespace, and convert all whitespace to spaces
					$text = preg_replace( '/[ \r\n\t]+/', ' ', $text );
					$start = 0;
					$length = strlen( $text );
				}
				return parent::characters( $parent, $text, $start, $length );
			}

			/** @inheritDoc */
			public function element( SerializerNode $parent, SerializerNode $node, $contents ) {
				if (
					$this->minify && (
						// Don't touch <pre>/<listing>/<textarea> nodes
						$node->namespace !== HTMLData::NS_HTML ||
						!isset( $this->prefixLfElements[ $node->name ] )
					) &&
					$contents !== null
				) {
					// Remove leading and trailing whitespace
					$contents = preg_replace( '/(^[ \r\n\t]+)|([\r\n\t ]+$)/', '', $contents );
				}
				return parent::element( $parent, $node, $contents );
			}
		};
	}

	/**
	 * Custom Dispatcher subclass that only dispatches tree events inside a tag with a certain name.
	 * This effectively filters the tree to only the contents of that tag.
	 *
	 * @param TreeBuilder $treeBuilder
	 * @param string $nodeName Tag name to filter for
	 */
	private function newFilteringDispatcher( TreeBuilder $treeBuilder, string $nodeName ): Dispatcher {
		return new class( $treeBuilder, $nodeName ) extends Dispatcher {
			private string $nodeName;
			private int $nodeDepth = 0;
			private bool $seenTag = false;

			public function __construct( TreeBuilder $treeBuilder, string $nodeName ) {
				$this->nodeName = $nodeName;
				parent::__construct( $treeBuilder );
			}

			/** @inheritDoc */
			public function startTag( $name, Attributes $attrs, $selfClose, $sourceStart, $sourceLength ) {
				if ( $this->nodeDepth ) {
					parent::startTag( $name, $attrs, $selfClose, $sourceStart, $sourceLength );
				}

				if ( $name === $this->nodeName ) {
					if ( $this->nodeDepth === 0 && $this->seenTag ) {
						// This is the second opening tag, not nested in the first one
						throw new InvalidArgumentException( "More than one <{$this->nodeName}> tag found" );
					}
					$this->nodeDepth++;
					$this->seenTag = true;
				}
			}

			/** @inheritDoc */
			public function endTag( $name, $sourceStart, $sourceLength ) {
				if ( $name === $this->nodeName ) {
					$this->nodeDepth--;
				}
				if ( $this->nodeDepth ) {
					parent::endTag( $name, $sourceStart, $sourceLength );
				}
			}

			/** @inheritDoc */
			public function characters( $text, $start, $length, $sourceStart, $sourceLength ) {
				if ( $this->nodeDepth ) {
					parent::characters( $text, $start, $length, $sourceStart, $sourceLength );
				}
			}

			/** @inheritDoc */
			public function comment( $text, $sourceStart, $sourceLength ) {
				if ( $this->nodeDepth ) {
					parent::comment( $text, $sourceStart, $sourceLength );
				}
			}
		};
	}
}
