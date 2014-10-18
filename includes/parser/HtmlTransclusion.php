<?php
/**
 * Object representing a transclusion into wikitext using the
 * preprocessor facility.
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
 * @since 1.25
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */
class HtmlTransclusion implements Transclusion {

	/**
	 * @var ParserOutput
	 */
	private $parserOutput;

	/**
	 * @var Title
	 */
	private $title;

	/**
	 * @var array[]
	 */
	private $dependencies;


	/**
	 * @throws InvalidArgumentException
	 */
	public function __construct( ParserOutput $parserOutput, Title $title, array $dependencies = array() ) {
		$this->parserOutput = $parserOutput;
		$this->title = $title;
		$this->dependencies = $dependencies;
	}

	public static function newFromOutputPage( OutputPage $outputPage, Title $title = null, array $dependencies = array() ) {
		if ( !$title ) {
			$title = $outputPage->getTitle();
		}

		$parserOutput = new ParserOutput( $outputPage->getHTML() );
		$parserOutput->addOutputPageMetadata( $outputPage );

		return new self( $parserOutput, $title, $dependencies );
	}

	public static function newFromHtml( $html, Title $title, array $dependencies = array() ) {
		$parserOutput = new ParserOutput( $html );
		return new self( $parserOutput, $title, $dependencies );
	}

	public function getTransclusionWikitext( PPFrame $frame, TransclusionParameters $parameters ) {
		if ( $parameters instanceof PPTransclusionParameters ) {
			$ppArgs = $parameters->getParameterNodes();
		} else {
			$ppArgs = $parameters->getParameters();
		}

		$templateName = $this->getTemplateTitle()->getPrefixedText();
		$wikitextBits = $frame->virtualBracketedImplode( '{{', '|', '}}', $templateName, $ppArgs );
		$wikitext = $frame->implode( '', $wikitextBits );
		return $wikitext;
	}

	/**
	 * @see Transclusion::getWikitext(generateWikitext
	 *
	 * @param Parser $parser
	 * @param TransclusionParameters $parameters
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function generateWikitext( Parser $parser, TransclusionParameters $parameters ) {
		$html = $this->parserOutput->getText();

		if ( $parameters instanceof PPTransclusionParameters ) {
			$frame = $parameters->getFrame();
		} else {
			$frame = $parser->getPreprocessor()->newFrame();
		}

		$wikitext = $this->getTransclusionWikitext( $frame, $parameters );

		if ( !is_string( $wikitext ) ) {
			throw new InvalidArgumentException( '$wikitext must be a string' );
		}

		$ot = $parser->OutputType();

		if ( $ot === Parser::OT_HTML ) {
			$text = $parser->insertStripItem( $html );
		} elseif ( $ot === Parser::OT_PREPROCESS_TRANSCLUDE_HTML ) {
			// This is the mode used by parsoid don't keep the transclusion syntax in the wikitext,
			// that would cause parsoid to try to expandit over and over.
			$text = '<mw-transclude content-type="text/html" data-wikitext="' . $wikitext . '">';
			$text .= $parser->insertStripItem( $html );
			$text .= '</mw-transclude>';
		} else {
			// Use strip mark to avoid template loop
			$text = $parser->insertStripItem( $wikitext );
		}

		$this->augmentParserOutput( $parser->getOutput() );

		return $text;
	}

	/**
	 * @return Title
	 */
	public function getTemplateTitle() {
		return $this->title;
	}

	/**
	 * @return array[]
	 */
	public function getDependencies() {
		return $this->dependencies;
	}

	public function augmentParserOutput( ParserOutput $out ) {
		# @todo: merge more, e.g. links, expiry, etc.
		$out->mergePageMetadata( $this->parserOutput );
	}

	public function generateDom( Parser $parser, TransclusionParameters $parameters ) {
		$text = $this->generateWikitext( $parser, $parameters );
		$dom = $parser->preprocessToDom( $text, Parser::PTD_FOR_INCLUSION );
		return $dom;
	}

}
 