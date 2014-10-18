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
class WikitextTransclusion implements Transclusion {

	/**
	 * @var string
	 */
	private $wikitext;

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
	public function __construct( $wikitext, Title $title, array $dependencies = array() ) {
		if ( !is_string( $wikitext ) ) {
			throw new InvalidArgumentException( '$wikitext must be a string!' );
		}

		$this->wikitext = $wikitext;
		$this->title = $title;
		$this->dependencies = $dependencies;
	}

	/**
	 * TBD
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function generateWikitext( Parser $parser, TransclusionParameters $parameters ) {
		return $this->wikitext;
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
 