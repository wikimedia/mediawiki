<?php

namespace MediaWiki\Tidy;

use RemexHtml\HTMLData;
use RemexHtml\Serializer\HtmlFormatter;
use RemexHtml\Serializer\SerializerNode;
use RemexHtml\Tokenizer\PlainAttributes;

/**
 * @internal
 */
class RemexCompatFormatter extends HtmlFormatter {
	private static $markedEmptyElements = [
		'li' => true,
		'p' => true,
		'tr' => true,
	];

	public function __construct( $options = [] ) {
		parent::__construct( $options );
		$this->attributeEscapes["\xc2\xa0"] = '&#160;';
		unset( $this->attributeEscapes["&"] );
		$this->textEscapes["\xc2\xa0"] = '&#160;';
		unset( $this->textEscapes["&"] );
	}

	public function startDocument( $fragmentNamespace, $fragmentName ) {
		return '';
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
		if ( isset( self::$markedEmptyElements[$name] ) && $attrs->count() === 0 ) {
			if ( strspn( $contents, "\t\n\f\r " ) === strlen( $contents ) ) {
				return "<{$name} class=\"mw-empty-elt\">$contents</{$name}>";
			}
		}

		$s = "<$name";
		foreach ( $attrs->getValues() as $attrName => $attrValue ) {
			$encValue = strtr( $attrValue, $this->attributeEscapes );
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
