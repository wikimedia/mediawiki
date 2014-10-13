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
class ContentTransclusion implements Transclusion {

	/**
	 * @var Content
	 */
	private $content;

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
	public function __construct( Content $content, Title $title, array $dependencies = array() ) {
		$this->content = $content;
		$this->title = $title;
		$this->dependencies = $dependencies;
	}

	private function getParserOutput( Parser $parser, TransclusionParameters $parameters ) {
		$parserOutput = $this->content->getParserOutputForTransclusion(
			$this->title,
			$parameters,
			$parser->getRevisionId(),
			$parser->getOptions()
		);

		return $parserOutput;
	}

	/**
	 * @see Transclusion::getWikitext()
	 *
	 * @param Parser $parser
	 * @param TransclusionParameters $parameters
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function getWikitext( Parser $parser, TransclusionParameters $parameters ) {
		$text = $this->content->getWikitextForTransclusion();

		if ( $text === false || $text === null ) {
			$parserOutput = $this->getParserOutput( $parser, $parameters );
			$htmlTransclusion = new HtmlTransclusion( $parserOutput, $this->title, $this->dependencies );
			$text = $htmlTransclusion->getWikitext( $parser, $parameters );
		}

		//FIXME: return $parserOutput too, so meta-data can be transferred. augmentParserOutput() doesn't cut it.
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
		//FIXME: don't want to re-parse here! Also need params...
	}

	public function getDom( Parser $parser, TransclusionParameters $parameters ) {
		//TODO: Some kinds of content could generate a wikitext DOM directly.
		$text = $this->getWikitext( $parser, $parameters );
		$dom = $parser->preprocessToDom( $text, Parser::PTD_FOR_INCLUSION );
		return $dom;
	}

}
 