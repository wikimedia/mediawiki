<?php

namespace Mediawiki\OutputTransform;

use ParserOptions;
use ParserOutput;

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
	 * 	applied. It is copied before applying transformations and is hence not
	 * modified by this method.
	 * @param ?ParserOptions $popts - will eventually replace options as container
	 *    for transformation options
	 * @param array $options Transformations to apply to the HTML
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
	 *  - bodyContentOnly: (bool) . Default: true
	 */
	public function run( ParserOutput $in, ?ParserOptions $popts, array $options ): ParserOutput {
		$out = clone $in;
		foreach ( $this->stages as $stage ) {
			if ( $stage->shouldRun( $out, $popts, $options ) ) {
				// Some stages may (for now) modify $options. See OutputTransformStage documentation for more info.
				$out = $stage->transform( $out, $popts, $options );
			}
		}
		return $out;
	}
}
