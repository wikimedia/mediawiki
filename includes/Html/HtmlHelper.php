<?php

namespace MediaWiki\Html;

use MediaWiki\Tidy\RemexCompatFormatter;
use Wikimedia\RemexHtml\HTMLData;
use Wikimedia\RemexHtml\Serializer\HtmlFormatter;
use Wikimedia\RemexHtml\Serializer\Serializer;
use Wikimedia\RemexHtml\Tokenizer\Tokenizer;
use Wikimedia\RemexHtml\TreeBuilder\Dispatcher;
use Wikimedia\RemexHtml\TreeBuilder\TreeBuilder;

/**
 * Static utilities for manipulating HTML strings.
 */
class HtmlHelper {

	/**
	 * Modify elements of an HTML fragment via a user-provided callback.
	 * @param string $htmlFragment HTML fragment. Must be valid (ie. coming from the parser, not
	 *   the user).
	 * @param callable $shouldModifyCallback A callback which takes a single
	 *   RemexHtml\Serializer\SerializerNode argument, and returns true if it should be modified.
	 * @param callable $modifyCallback A callback which takes a single
	 *   RemexHtml\Serializer\SerializerNode argument and actually performs the modification on it.
	 *   It must return the new node (which can be the original node object).
	 * @param bool $html5format Defaults to true, which uses standard HTML5
	 *   serialization for the parsed HTML.  If set to false, uses a
	 *   serialization which is more compatible with the output of the
	 *   legacy parser; see RemexCompatFormatter for more details.
	 *   When false, attributes and text nodes contain unexpanded character references (entities).
	 * @return string
	 */
	public static function modifyElements(
		string $htmlFragment,
		callable $shouldModifyCallback,
		callable $modifyCallback,
		bool $html5format = true
	) {
		if ( $html5format ) {
			$formatter = new class( [], $shouldModifyCallback, $modifyCallback ) extends HtmlFormatter {
				use HtmlHelperTrait;
			};
		} else {
			$formatter = new class( [], $shouldModifyCallback, $modifyCallback ) extends RemexCompatFormatter {
				use HtmlHelperTrait;
			};
		}
		$serializer = new Serializer( $formatter );
		$treeBuilder = new TreeBuilder( $serializer, $html5format ? [] : [
			'ignoreErrors' => true,
			'ignoreNulls' => true,
		] );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher, $htmlFragment, $html5format ? [] : [
			// RemexCompatFormatter expects 'ignoreCharRefs' to be used (T354361). The other options are
			// for consistency with RemexDriver and supposedly improve performance.
			'ignoreErrors' => true,
			'ignoreCharRefs' => true,
			'ignoreNulls' => true,
			'skipPreprocess' => true,
		] );

		$tokenizer->execute( [
			'fragmentNamespace' => HTMLData::NS_HTML,
			'fragmentName' => 'body',
		] );

		return $serializer->getResult();
	}

}

/** @deprecated class alias since 1.40 */
class_alias( HtmlHelper::class, 'MediaWiki\\HtmlHelper' );
