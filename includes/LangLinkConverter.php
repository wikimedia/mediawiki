<?php
/**
 *
 * Copyright © 2013 by the authors listed below.
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
 * @license GPL 2+
 * @file
 *
 * @author Daniel Kinzler
 */

/**
 * Converts language links between various forms.
 *
 * @class
 */
class LangLinkConverter {

	/**
	 * Converts a stdClass row to a link string.
	 *
	 * @param object $row A stdClass object as returned from the database,
	 *        with at least the members ll_lang and ll_title.
	 *
	 * @return string A link of the form "language:title"
	 */
	public function rowToLink( $row ) {
		return "{$row->ll_lang}:{$row->ll_title}";
	}

	/**
	 * Converts a list of rows into a list of link strings.
	 * @see rowToLink()
	 *
	 * @param object[] $rows A list of row objects
	 *
	 * @return string[] A list of links of the form "language:title"
	 */
	public function rowsToLinks( array $rows ) {
		$links = array_map(
			array( $this, 'rowToLink' ),
			$rows
		);

		return $links;
	}

	/**
	 * Converts a link string to a stdClass object, similar to the ones
	 * returned by the database.
	 *
	 * @param int $from The ID of the page the link was on (0 will work as a placeholder).
	 * @param string $link A link of the form "language:title"
	 *
	 * @return object A stdClass object  with at least the members ll_lang and ll_title.
	 */
	public function linkToRow( $from, $link ) {
		list( $lang, $title ) = explode( ':', $link , 2 );

		$row = new stdClass();
		$row->ll_from = $from;
		$row->ll_lang = $lang;
		$row->ll_title = $title;

		return $row;
	}

	/**
	 * Converts a list of link string to a list of stdClass objects.
	 * @see linkToRow();
	 *
	 * @param int $from The ID of the page the links are on (0 will work as a placeholder).
	 * @param string[] $links A list of links of the form "language:title"
	 *
	 * @return object[] A list of stdClass objects
	 */
	public function linksToRows( $from, array $links ) {
		$this_ = $this;
		$rows = array_map(
			function ( $link ) use ( $this_, $from ) {
				return $this_->linkToRow( $from, $link );
			},
			$links
		);

		return $rows;
	}

	/**
	 * Adds flags to a row object. The ll_flags field will be added to
	 * the row if not already present. The given flags are added to
	 * any already present in ll_flags.
	 *
	 * @param object $row The row object to be modified.
	 * @param string[] $flags The flags to add to the row
	 *
	 * @return object $row
	 */
	public function flagRow( $row, array $flags ) {
		if ( !isset( $row->ll_flags ) ) {
			$row->ll_flags = $flags;
		} else {
			$row->ll_flags = array_unique( array_merge ( $row->ll_flags, $flags ) );
		}

		return $row;
	}

	/**
	 * Adds flags to a list of row object. A ll_flags field will be added to each row.
	 * The flags are given as a map from links to flags.
	 *
	 * @see      flagRow()
	 *
	 * @param object[] $rows A list of row objects to be modified.
	 * @param string [][] $flags A map associating links with flag lists;
	 *        The keys are prefixed link strings, or as language codes.
	 *
	 * @return object[] $rows
	 */
	public function flagRows( array $rows, array $flags ) {
		$this_ = $this;
		$flaggedRows = array_map(
			function ( $row ) use ( $this_, $flags ) {
				if ( !isset( $row->ll_flags ) ) {
					$row->ll_flags = array();
				}

				$key = $row->ll_lang;

				// can we use the language to look up flags?
				if ( !isset( $flags[ $key ] ) ) {
					// no? try the full link instead.
					$key = $this_->rowToLink( $row );
				}

				if ( isset( $flags[ $key ] ) ) {
					$this_->flagRow( $row, $flags[ $key ] );
				}

				return $row;
			},
			$rows
		);

		return $flaggedRows;
	}

}