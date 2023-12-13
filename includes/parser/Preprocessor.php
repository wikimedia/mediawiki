<?php
/**
 * Interfaces for preprocessors
 *
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
 * @ingroup Parser
 */

use MediaWiki\Parser\Parser;

/**
 * @ingroup Parser
 */
abstract class Preprocessor {
	/** Transclusion mode flag for Preprocessor::preprocessToObj() */
	public const DOM_FOR_INCLUSION = 1;
	/** Language conversion construct omission flag for Preprocessor::preprocessToObj() */
	public const DOM_LANG_CONVERSION_DISABLED = 2;
	/** Preprocessor cache bypass flag for Preprocessor::preprocessToObj */
	public const DOM_UNCACHED = 4;

	/** @var Parser */
	public $parser;

	/** @var WANObjectCache */
	protected $wanCache;

	/** @var bool Whether language variant conversion is disabled */
	protected $disableLangConversion;

	/** @var array Brace matching rules */
	protected $rules = [
		'{' => [
			'end' => '}',
			'names' => [
				2 => 'template',
				3 => 'tplarg',
			],
			'min' => 2,
			'max' => 3,
		],
		'[' => [
			'end' => ']',
			'names' => [ 2 => null ],
			'min' => 2,
			'max' => 2,
		],
		'-{' => [
			'end' => '}-',
			'names' => [ 2 => null ],
			'min' => 2,
			'max' => 2,
		],
	];

	/**
	 * @param Parser $parser
	 * @param WANObjectCache|null $wanCache
	 * @param array $options Map of additional options, including:
	 * 	 - disableLangConversion: disable language variant conversion. [Default: false]
	 */
	public function __construct(
		Parser $parser,
		WANObjectCache $wanCache = null,
		array $options = []
	) {
		$this->parser = $parser;
		$this->wanCache = $wanCache ?: WANObjectCache::newEmpty();
		$this->disableLangConversion = !empty( $options['disableLangConversion'] );
	}

	/**
	 * Allows resetting the internal Parser reference after Preprocessor is
	 * cloned.
	 *
	 * Do not use this function in new code, since this method will be
	 * moved once Parser cloning goes away (T250448)
	 *
	 * @param ?Parser $parser
	 * @internal
	 */
	public function resetParser( ?Parser $parser ) {
		// @phan-suppress-next-line PhanPossiblyNullTypeMismatchProperty For internal use only
		$this->parser = $parser;
	}

	/**
	 * Create a new top-level frame for expansion of a page
	 *
	 * @return PPFrame
	 */
	abstract public function newFrame();

	/**
	 * Create a new custom frame for programmatic use of parameter replacement
	 *
	 * This is useful for certain types of extensions
	 *
	 * @param array $args
	 * @return PPFrame
	 */
	abstract public function newCustomFrame( $args );

	/**
	 * Create a new custom node for programmatic use of parameter replacement
	 *
	 * This is useful for certain types of extensions
	 *
	 * @param array $values
	 */
	abstract public function newPartNodeArray( $values );

	/**
	 * Get the document object model for the given wikitext
	 *
	 * Any flag added to the $flags parameter here, or any other parameter liable to cause
	 * a change in the DOM tree for the given wikitext, must be passed through the section
	 * identifier in the section edit link and thus back to extractSections().
	 *
	 * @param string $text Wikitext
	 * @param int $flags Bit field of Preprocessor::DOM_* flags:
	 *   - Preprocessor::DOM_FOR_INCLUSION: treat the wikitext as transcluded content from
	 *      a page rather than direct content of a page or message. By default, the text is
	 *      assumed to be undergoing processing for use by direct page views. The use of this
	 *      flag causes text within <noinclude> tags to be ignored, text within <includeonly>
	 *      to be included, and text outside of <onlyinclude> to be ignored.
	 *   - Preprocessor::DOM_NO_LANG_CONV: do not parse "-{ ... }-" constructs, which are
	 *      involved in language variant conversion. (deprecated since 1.36)
	 *   - Preprocessor::DOM_UNCACHED: disable use of the preprocessor cache.
	 * @return PPNode
	 */
	abstract public function preprocessToObj( $text, $flags = 0 );
}
