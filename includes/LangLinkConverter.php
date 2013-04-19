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

	public function rowToLink( $row ) {
		return "{$row->ll_lang}:{$row->ll_title}";
	}

	public function rowsToLinks( array $rows ) {
		$links = array_map(
			array( $this, 'rowToLink' ),
			$rows
		);

		return $links;
	}

	public function linkToRow( $from, $link ) {
		list( $lang, $title ) = explode( ':', $link , 2 );

		$row = new stdClass();
		$row->ll_from = $from;
		$row->ll_lang = $lang;
		$row->ll_title = $title;

		return $row;
	}

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

	public function linkToTitle( $link ) {
		$title = Title::newFromText( $link );
		return $title;
	}

	public function linksToTitles( array $links ) {
		$titles = array_map(
			array( $this, 'linkToTitle' ),
			$links
		);

		return $titles;
	}

	public function getLinkLanguage( $link ) {
		list( $lang, ) = explode( ':', $link, 2 );
		return $lang;
	}

	public function getLinkLanguages( array $links ) {
		// get the languages of the extra links
		$languages = array_map(
			array( $this, 'getLinkLanguage' ),
			$links
		);

		return $languages;
	}

	public function flagRow( $row, $flag ) {
		if ( !isset( $row->ll_flags ) ) {
			$row->ll_flags = array();
		}

		$row->ll_flags[] = $flag;

		return $row;
	}

	public function flagRows( array $rows, $flag, $languages = null ) {
		$this_ = $this;
		$flaggedRows = array_map(
			function ( $row ) use ( $this_, $flag, $languages ) {
				if ( !isset( $row->ll_flags ) ) {
					$row->ll_flags = array();
				}

				if ( in_array( $row->ll_lang, $languages ) ) {
					$this_->flagRow( $row, $flag );
				}

				return $row;
			},
			$rows
		);

		return $flaggedRows;
	}

}