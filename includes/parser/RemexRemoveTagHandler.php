<?php

namespace MediaWiki\Parser;

use Wikimedia\RemexHtml\Tokenizer\Attributes;
use Wikimedia\RemexHtml\Tokenizer\PlainAttributes;
use Wikimedia\RemexHtml\Tokenizer\RelayTokenHandler;
use Wikimedia\RemexHtml\Tokenizer\TokenHandler;

/**
 * Helper class for Sanitizer::removeSomeTags().
 * @internal
 */
class RemexRemoveTagHandler extends RelayTokenHandler {
	/**
	 * @var string The original HTML source string (used for fallback text
	 * when rejecting an HTML tag).
	 */
	private $source;

	/**
	 * @var array<string,true> Set of HTML tags which can be self-closed.
	 */
	private $htmlsingle;

	/**
	 * @var array<string,true> Self-closed tags which are on $htmlsingle
	 * but not on $htmlsingleonly will be emitted as an empty element.
	 */
	private $htmlsingleonly;

	/**
	 * @var array<string,true> Set of allowed HTML open/close tags.
	 */
	private $htmlelements;

	/**
	 * @var ?callable(Attributes,mixed...):Attributes Callback to mutate or
	 * sanitize attributes.
	 */
	private $attrCallback;

	/**
	 * @var ?array $args Optional extra arguments to provide to the
	 * $attrCallback.
	 */
	private $callbackArgs;

	/**
	 * @param TokenHandler $nextHandler Handler to relay accepted tokens.
	 * @param string $source Input source string.
	 * @param array $tagData Information about allowed/rejected tags.
	 * @param ?callable $attrCallback Attribute handler callback.
	 *   The full signature is ?callable(Attributes,mixed...):Attributes
	 * @param ?array $callbackArgs Optional arguments to attribute handler.
	 */
	public function __construct(
		TokenHandler $nextHandler,
		string $source,
		array $tagData,
		?callable $attrCallback,
		?array $callbackArgs
	) {
		parent::__construct( $nextHandler );
		$this->source = $source;
		$this->htmlsingle = $tagData['htmlsingle'];
		$this->htmlsingleonly = $tagData['htmlsingleonly'];
		$this->htmlelements = $tagData['htmlelements'];
		$this->attrCallback = $attrCallback;
		$this->callbackArgs = $callbackArgs ?? [];
	}

	/**
	 * @inheritDoc
	 */
	public function comment( $text, $sourceStart, $sourceLength ) {
		// Don't relay comments.
	}

	/**
	 * Takes attribute names and values for a tag and the tag name and
	 * validates that the tag is allowed to be present.
	 * This DOES NOT validate the attributes, nor does it validate the
	 * tags themselves. This method only handles the special circumstances
	 * where we may want to allow a tag within content but ONLY when it has
	 * specific attributes set.
	 *
	 * @param string $element
	 * @param Attributes $attrs
	 * @return bool
	 *
	 * @see Sanitizer::validateTag()
	 */
	private static function validateTag( string $element, Attributes $attrs ): bool {
		if ( $element == 'meta' || $element == 'link' ) {
			$params = $attrs->getValues();
			if ( !isset( $params['itemprop'] ) ) {
				// <meta> and <link> must have an itemprop="" otherwise they are not valid or safe in content
				return false;
			}
			if ( $element == 'meta' && !isset( $params['content'] ) ) {
				// <meta> must have a content="" for the itemprop
				return false;
			}
			if ( $element == 'link' && !isset( $params['href'] ) ) {
				// <link> must have an associated href=""
				return false;
			}
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function startTag( $name, Attributes $attrs, $selfClose, $sourceStart, $sourceLength ) {
		// Handle a start tag from the tokenizer: either relay it to the
		// next stage, or re-emit it as raw text.

		$badtag = false;
		$t = strtolower( $name );
		if ( isset( $this->htmlelements[$t] ) ) {
			if ( $this->attrCallback ) {
				$attrs = ( $this->attrCallback )( $attrs, ...$this->callbackArgs );
			}
			if ( $selfClose && !( isset( $this->htmlsingle[$t] ) || isset( $this->htmlsingleonly[$t] ) ) ) {
				// Remove the self-closing slash, to be consistent with
				// HTML5 semantics. T134423
				$selfClose = false;
			}
			if ( !self::validateTag( $t, $attrs ) ) {
				$badtag = true;
			}
			$fixedAttrs = Sanitizer::validateTagAttributes( $attrs->getValues(), $t );
			$attrs = new PlainAttributes( $fixedAttrs );
			if ( !$badtag ) {
				if ( $selfClose && !isset( $this->htmlsingleonly[$t] ) ) {
					// Interpret self-closing tags as empty tags even when
					// HTML5 would interpret them as start tags.  Such input
					// is commonly seen on Wikimedia wikis with this intention.
					$this->nextHandler->startTag( $name, $attrs, false, $sourceStart, $sourceLength );
					$this->nextHandler->endTag( $name, $sourceStart + $sourceLength, 0 );
				} else {
					$this->nextHandler->startTag( $name, $attrs, $selfClose, $sourceStart, $sourceLength );
				}
				return;
			}
		}
		// Emit this as a text node instead.
		$this->nextHandler->characters( $this->source, $sourceStart, $sourceLength, $sourceStart, $sourceLength );
	}

	/**
	 * @inheritDoc
	 */
	public function endTag( $name, $sourceStart, $sourceLength ) {
		// Handle an end tag from the tokenizer: either relay it to the
		// next stage, or re-emit it as raw text.

		$t = strtolower( $name );
		if ( isset( $this->htmlelements[$t] ) ) {
			// This is a good tag, relay it.
			$this->nextHandler->endTag( $name, $sourceStart, $sourceLength );
		} else {
			// Emit this as a text node instead.
			$this->nextHandler->characters( $this->source, $sourceStart, $sourceLength, $sourceStart, $sourceLength );
		}
	}

}
