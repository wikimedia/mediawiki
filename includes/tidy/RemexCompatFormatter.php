<?php

namespace MediaWiki\Tidy;

use RemexHtml\HTMLData;
use RemexHtml\Serializer\HtmlFormatter;
use RemexHtml\Serializer\SerializerNode;
use Sanitizer;

/**
 * @internal
 */
class RemexCompatFormatter extends HtmlFormatter {
	private static $markedEmptyElements = [
		'li' => true,
		'p' => true,
		'tr' => true,
	];

	/* @var ?callable */
	private $textProcessor;

	public function __construct( $options = [] ) {
		parent::__construct( $options );
		$this->attributeEscapes["\u{00A0}"] = '&#160;';
		unset( $this->attributeEscapes["&"] );
		$this->textEscapes["\u{00A0}"] = '&#160;';
		unset( $this->textEscapes["&"] );
		$this->textProcessor = $options['textProcessor'] ?? null;
	}

	public function startDocument( $fragmentNamespace, $fragmentName ) {
		return '';
	}

	public function characters( SerializerNode $parent, $text, $start, $length ) {
		$text = parent::characters( $parent, $text, $start, $length );

		if ( $parent->namespace !== HTMLData::NS_HTML
			|| !isset( $this->rawTextElements[$parent->name] )
		) {
			if ( $this->textProcessor !== null ) {
				$text = call_user_func( $this->textProcessor, $text );
			}
		}

		// Ensure a consistent representation for all entities
		$text = Sanitizer::normalizeCharReferences( $text );
		return $text;
	}

	public function element( SerializerNode $parent, SerializerNode $node, $contents ) {
		$data = $node->snData;
		if ( $data && $data->isPWrapper ) {
			if ( $data->nonblankNodeCount ) {
				return "<p>$contents</p>";
			} else {
				return $contents;
			}
		}

		$name = $node->name;
		$attrs = $node->attrs;
		if ( isset( self::$markedEmptyElements[$name] ) && $attrs->count() === 0
			&& strspn( $contents, "\t\n\f\r " ) === strlen( $contents )
		) {
			return "<{$name} class=\"mw-empty-elt\">$contents</{$name}>";
		}

		$s = "<$name";
		foreach ( $attrs->getValues() as $attrName => $attrValue ) {
			$encValue = strtr( $attrValue, $this->attributeEscapes );
			$encValue = Sanitizer::normalizeCharReferences( $encValue );
			$s .= " $attrName=\"$encValue\"";
		}
		if ( $node->namespace === HTMLData::NS_HTML && isset( $this->voidElements[$name] ) ) {
			$s .= ' />';
			return $s;
		}

		$s .= '>';
		if ( $node->namespace === HTMLData::NS_HTML
			&& isset( $contents[0] ) && $contents[0] === "\n"
			&& isset( $this->prefixLfElements[$name] )
		) {
			$s .= "\n$contents</$name>";
		} else {
			$s .= "$contents</$name>";
		}
		return $s;
	}
}
