<?php
/**
 * Implements Special:ListDuplicatedFiles
 *
 * Copyright © 2013 Brian Wolff
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
 * @file
 * @ingroup SpecialPage
 * @author Brian Wolff
 */

use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * Special:ListDuplicatedFiles Lists all files where the current version is
 *   a duplicate of the current version of some other file.
 * @ingroup SpecialPage
 */
class SpecialListDuplicatedFiles extends QueryPage {
	function __construct( $name = 'ListDuplicatedFiles' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	/**
	 * Get all the duplicates by grouping on sha1s.
	 *
	 * A cheaper (but less useful) version of this
	 * query would be to not care how many duplicates a
	 * particular file has, and do a self-join on image table.
	 * However this version should be no more expensive then
	 * Special:MostLinked, which seems to get handled fine
	 * with however we are doing cached special pages.
	 * @return array
	 */
	public function getQueryInfo() {
		return [
			'tables' => [ 'image' ],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'MIN(img_name)',
				'value' => 'count(*)'
			],
			'options' => [
				'GROUP BY' => 'img_sha1',
				'HAVING' => 'count(*) > 1',
			],
		];
	}

	/**
	 * Pre-fill the link cache
	 *
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		// Future version might include a list of the first 5 duplicates
		// perhaps separated by an "↔".
		$image1 = Title::makeTitle( $result->namespace, $result->title );
		$dupeSearch = SpecialPage::getTitleFor( 'FileDuplicateSearch', $image1->getDBkey() );

		$msg = $this->msg( 'listduplicatedfiles-entry' )
			->params( $image1->getText() )
			->numParams( $result->value - 1 )
			->params( $dupeSearch->getPrefixedDBkey() );

		return $msg->parse();
	}

	public function execute( $par ) {
		$this->addHelpLink( 'Help:Managing_files' );
		parent::execute( $par );
	}

	protected function getGroupName() {
		return 'media';
	}
}
