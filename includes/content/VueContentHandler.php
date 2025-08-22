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
 */

namespace MediaWiki\Content;

use InvalidArgumentException;
use MediaWiki\Config\Config;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\ResourceLoader\VueComponentParser;
use StatusValue;
use WikiPage;

/**
 * Content handler for Vue pages.
 *
 * @since 1.45
 * @ingroup Content
 */
class VueContentHandler extends CodeContentHandler {

	private array $textModelsToParse;

	private ?VueComponentParser $vueComponentParser = null;

	public function __construct(
		string $modelId,
		Config $config,
		private readonly ParserFactory $parserFactory,
	) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_VUE ] );
		$this->textModelsToParse = $config->get( MainConfigNames::TextModelsToParse ) ?? [];
	}

	/**
	 * @return class-string<VueContent>
	 */
	protected function getContentClass() {
		return VueContent::class;
	}

	public function makeEmptyContent() {
		$class = $this->getContentClass();
		return new $class( "<template>\n</template>\n\n<script>\n</script>\n\n<style>\n</style>\n" );
	}

	/**
	 * Fills the provided ParserOutput object with information derived from the content.
	 * Unless $cpo->getGenerateHtml was false, this includes an HTML representation of the content.
	 *
	 * For content models listed in $wgTextModelsToParse, this method will call the MediaWiki
	 * wikitext parser on the text to extract any (wikitext) links, magic words, etc.
	 *
	 * Subclasses may override this to provide custom content processing..
	 *
	 * @stable to override
	 *
	 * @param Content $content
	 * @param ContentParseParams $cpoParams
	 * @param ParserOutput &$output The output object to fill (reference).
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	) {
		'@phan-var VueContent $content';
		if ( in_array( $content->getModel(), $this->textModelsToParse ) ) {
			// parse just to get links etc into the database, HTML is replaced below.
			$output = $this->parserFactory->getInstance()->parse(
				$content->getText(),
				$cpoParams->getPage(),
				WikiPage::makeParserOptionsFromTitleAndModel(
					$cpoParams->getPage(),
					$content->getModel(),
					'canonical'
				),
				true,
				true,
				$cpoParams->getRevId()
			);
		}

		if ( $cpoParams->getGenerateHtml() ) {
			// Return Vue code wrapped in a <pre> tag.
			$html = Html::element(
				'pre',
				[ 'class' => 'mw-code mw-vue', 'dir' => 'ltr' ],
				"\n" . $content->getText() . "\n"
			) . "\n";
		} else {
			$html = null;
		}

		$output->clearWrapperDivClass();
		$output->setRawText( $html );
		// Suppress the TOC (T307691)
		$output->setOutputFlag( ParserOutputFlags::NO_TOC );
		$output->setSections( [] );
	}

	/**
	 * @param Content $content
	 * @param ValidationParams $validationParams
	 * @return StatusValue
	 */
	public function validateSave( Content $content, ValidationParams $validationParams ): StatusValue {
		$this->vueComponentParser ??= new VueComponentParser;
		try {
			$parsedComponent = $this->vueComponentParser->parse( $content->serialize() );
		} catch ( InvalidArgumentException $e ) {
			// TODO: i18n for error messages
			return StatusValue::newFatal( 'vue-invalid-content', $e->getMessage() );
		}
		if ( $parsedComponent['styleLang'] === 'less' ) {
			return StatusValue::newFatal( 'vue-less-notsupported' );
		}
		return StatusValue::newGood();
	}
}
