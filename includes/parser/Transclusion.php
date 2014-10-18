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
interface Transclusion {

	/**
	 * Generates the wikitext to be transcluded.
	 * This is a twin of generateDom() and should generate an equivalent result.
	 *
	 * @note Calling this may have change the state of $parser. In particular,
	 * implementations are free to manipulate $parser's ParserOutput to include
	 * additional modules, scripts, or styles needed by the transcluded text,
	 * or register pagelinks or other links. Similarly, caching options may be
	 * altered.
	 *
	 * @note Implementations should take care not to corrupt $parser's parse state.
	 * If a parser is needed internally, $parser->getFreshParser() should be used
	 * to ensure a fresh copy.
	 *
	 * @note When transcluding raw HTML, $parser->insertStripItem() should be
	 * used to guard the generated HTML against wikitext processing.
	 *
	 * @see generateDom()
	 *
	 * @param Parser $parser
	 * @param TransclusionParameters $parameters
	 *
	 * @return string
	 */
	public function generateWikitext( Parser $parser, TransclusionParameters $parameters );

	/**
	 * Returns a preprocessor DOM representing the wikitext to be transcluded.
	 * This is a twin of generateWikitext() and should generate an equivalent result.
	 *
	 * In the simplest case, this just calls $parser->preprocessToDom on the text
	 * returned by generateWikitext().
	 *
	 * @note Just like generateWikitext(), this may change the state of $parser.
	 * See there for details.
	 *
	 * @see generateWikitext()
	 *
	 * @param Parser $parser
	 * @param TransclusionParameters $parameters
	 *
	 * @return PPNode
	 */
	public function generateDom( Parser $parser, TransclusionParameters $parameters );

	/**
	 * Returns the title of the transcluded template.
	 *
	 * @return Title
	 */
	public function getTemplateTitle();

	/**
	 * Returns the dependencies of the transcluded template as a list of records.
	 * Each record is an associative array with the following fields:
	 *
	 * array(
	 *    'title' => Title,
	 *    'page_id' => int,
	 *    'rev_id' => int|null
	 * )
	 *
	 * @return array[]
	 */
	public function getDependencies();

}
 