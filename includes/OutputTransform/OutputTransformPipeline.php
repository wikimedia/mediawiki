<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;

/**
 * @unstable
 */
class OutputTransformPipeline {

	/** @var OutputTransformStage[] */
	private array $stages = [];

	public function addStage( OutputTransformStage $stage ): OutputTransformPipeline {
		$this->stages[] = $stage;
		return $this;
	}

	/**
	 * Runs the pipeline on the ParserOutput, yielding a transformed ParserOutput.
	 * @param ParserOutput $in Parser output to which the transformations are
	 * 	applied. It is typically copied before applying transformations and is
	 * hence not mutated by this method, but if $options['allowClone'] is
	 * set it to false WILL be mutated!
	 * @param ?ParserOptions $popts - will eventually replace options as container
	 *    for transformation options
	 * @param array $options Transformations to apply to the HTML
	 *  - allowClone: (bool) Whether to clone the ParserOutput before
	 *     applying transformations. Default is true.
	 *  - allowTOC: (bool) Show the TOC, assuming there were enough headings
	 *     to generate one and `__NOTOC__` wasn't used. Default is true,
	 *     but might be statefully overridden.
	 *  - injectTOC: (bool) Replace the TOC_PLACEHOLDER with TOC contents;
	 *     otherwise the marker will be left in the article (and the skin
	 *     will be responsible for replacing or removing it).  Default is
	 *     true.
	 *  - enableSectionEditLinks: (bool) Include section edit links, assuming
	 *     section edit link tokens are present in the HTML. Default is true,
	 *     but might be statefully overridden.
	 *  - userLang: (Language) Language object used for localizing UX messages,
	 *    for example the heading of the table of contents. If omitted, will
	 *    use the language of the main request context.
	 *  - skin: (Skin) Skin object used for transforming section edit links.
	 *  - unwrap: (bool) Return text without a wrapper div. Default is false,
	 *    meaning a wrapper div will be added if getWrapperDivClass() returns
	 *    a non-empty string.
	 *  - wrapperDivClass: (string) Wrap the output in a div and apply the given
	 *    CSS class to that div. This overrides the output of getWrapperDivClass().
	 *    Setting this to an empty string has the same effect as 'unwrap' => true.
	 *  - deduplicateStyles: (bool) When true, which is the default, `<style>`
	 *    tags with the `data-mw-deduplicate` attribute set are deduplicated by
	 *    value of the attribute: all but the first will be replaced by `<link
	 *    rel="mw-deduplicated-inline-style" href="mw-data:..."/>` tags, where
	 *    the scheme-specific-part of the href is the (percent-encoded) value
	 *    of the `data-mw-deduplicate` attribute.
	 *  - absoluteURLs: (bool) use absolute URLs in all links. Default: false
	 *  - includeDebugInfo: (bool) render PP limit report in HTML. Default: false
	 */
	public function run( ParserOutput $in, ?ParserOptions $popts, array $options ): ParserOutput {
		// Initialize some $options from the ParserOutput
		$options += [
			'enableSectionEditLinks' => !$in->getOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS ),
			'wrapperDivClass' => $in->getWrapperDivClass(),
			'isParsoidContent' => PageBundleParserOutputConverter::hasPageBundle( $in ),
		];
		if ( $options['allowClone'] ?? true ) {
			$out = clone $in;
		} else {
			// T353257: This should be a clone, but we've need to suppress it
			// for some legacy codepaths.
			$out = $in;
		}
		foreach ( $this->stages as $stage ) {
			if ( $stage->shouldRun( $out, $popts, $options ) ) {
				// Some stages may (for now) modify $options. See OutputTransformStage documentation for more info.
				$out = $stage->transform( $out, $popts, $options );
			}
		}
		return $out;
	}
}
