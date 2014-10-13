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
	 * TBD
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function getWikitext( Parser $parser, TransclusionParameters $parameters );

	/**
	 * @param Parser $parser
	 * @param TransclusionParameters $parameters
	 *
	 * @return PPNode
	 */
	public function getDom( Parser $parser, TransclusionParameters $parameters );

	/**
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

	/**
	 * @param ParserOutput $out
	 */
	public function augmentParserOutput( ParserOutput $out );
}
 