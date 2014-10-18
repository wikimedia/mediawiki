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
class SpecialPageTransclusion implements Transclusion {

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
	public function __construct( Title $title, array $dependencies = array() ) {
		if ( $title->getNamespace() !== NS_SPECIAL ) {
			throw new InvalidArgumentException( '$title must represent a page in the Special namespace' );
		}

		$this->title = $title;
		$this->dependencies = $dependencies;
	}

	private function getOutput( Parser $parser, TransclusionParameters $parameters ) {
		$pageArgs = $parameters->getParameters();

		// Create a new context to execute the special page
		$context = new RequestContext;
		$context->setTitle( $this->title );
		$context->setRequest( new FauxRequest( $pageArgs ) );
		$context->setUser( $parser->getUser() );
		$context->setLanguage( $parser->getOptions()->getUserLangObj() );

		$ret = SpecialPageFactory::capturePath( $this->title, $context );

		return $ret ? $context->getOutput() : null;
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
		$output = $this->getOutput( $parser, $parameters );

		if ( !$output ) {
			//FIXME!
			return $this->getTransclusionWikitext( $parser, $parameters );
		}

		$htmlTransclusion = HtmlTransclusion::newFromOutputPage( $output, $this->title, $this->dependencies );
		$text = $htmlTransclusion->generateWikitext( $parser, $parameters );

		$parser->disableCache();

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

	public function generateDom( Parser $parser, TransclusionParameters $parameters ) {
		$text = $this->generateWikitext( $parser, $parameters );
		$dom = $parser->preprocessToDom( $text, Parser::PTD_FOR_INCLUSION );
		return $dom;
	}

}
 