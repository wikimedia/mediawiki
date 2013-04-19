<?php
 /**
 *
 * Copyright © 19.04.13 by the authors listed below.
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
 * @author daniel
 */

/**
 * Loads language links from the database
 *
 * @class
 */
class DBLangLinkLoader extends DBAccessBase implements LangLinkLoader {

	public function loadLanguageLinks(
		$fromPageIds,
		$dir,
		$limit = null,
		$forLang = null,
		$forTitle = null,
		$continueFrom = null,
		$continueLang = null
	) {
		wfProfileIn( __METHOD__ );

		$db = $this->getConnection( DB_SLAVE );

		$fields = array(
			'll_from',
			'll_lang',
			'll_title'
		) ;

		$tables = array( 'langlinks' );
		$where = array( 'll_from' => $fromPageIds );
		$options = array();

		if ( $continueFrom !== null && $continueLang !== null ) {
			$op = $dir == 'descending' ? '<' : '>';
			$llfrom = intval( $continueFrom );
			$lllang = $db->addQuotes( $continueLang );
			$where[] =
				"ll_from $op $llfrom OR " .
					"(ll_from = $llfrom AND " .
					"ll_lang $op= $lllang)";
		}

		// Note that, since (ll_from, ll_lang) is a unique key, we don't need
		// to sort by ll_title to ensure deterministic ordering.
		$sort = ( $dir == 'descending' ? ' DESC' : '' );
		if ( isset( $forLang ) ) {
			$where['ll_lang'] = $forLang;
			if ( isset( $forTitle ) ) {
				$where['ll_title'] = $forTitle;
			}

			$options['ORDER BY'] = 'll_from' . $sort;
		} else {
			// Don't order by ll_from if it's constant in the WHERE clause
			if ( count( $fromPageIds ) == 1 ) {
				$options['ORDER BY'] = 'll_lang' . $sort;
			} else {
				$options['ORDER BY'] = array(
					'll_from' . $sort,
					'll_lang' . $sort
				);
			}
		}

		if ( $limit !== null ) {
			$options['LIMIT'] = $limit;
		}

		$res = $db->select( $tables, $fields, $where, __METHOD__, $options );

		$links = array();
		foreach ( $res as $row ) {
			$links[] = $row;
		}

		$this->releaseConnection( $db );

		wfProfileOut( __METHOD__ );
		return $links;
	}
}