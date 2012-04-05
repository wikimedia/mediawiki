<?php
/**
 * See docs/deferred.txt
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
 * Abstract base class for update jobs that do something with some secondary
 * data extracted from article.
 */
abstract class SecondaryDataUpdate {

	/**@{{
	 * @private
	 */
	var $mId,            //!< Page ID of the article linked from
		$mTitle,         //!< Title object of the article linked from
		$mParserOutput;     //!< Whether to queue jobs for recursive updates
	/**@}}*/

	/**
	 * Constructor
	 *
	 * @param $title Title of the page we're updating
	 * @param $parserOutput ParserOutput: output from a full parse of this page
	 * @param $recursive Boolean: queue jobs for recursive updates?
	 */
    public function __construct( Title $title, ParserOutput $parserOutput) {
		$this->mTitle = $title;
		$this->mId = $title->getArticleID();

		$this->mParserOutput = $parserOutput;
	}

	/**
	 * Update link tables with outgoing links from an updated article
	 */
	public abstract function doUpdate();

	/**
	 * Return the title object of the page being updated
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Returns parser output
	 * @since 1.19
	 * @return ParserOutput
	 */
	public function getParserOutput() {
		return $this->mParserOutput;
	}

}
