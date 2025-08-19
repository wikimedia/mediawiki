<?php
/**
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
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
 */

namespace MediaWiki\Specials;

use MediaWiki\Deferred\LinksUpdate\ImageLinksTable;
use MediaWiki\SpecialPage\ImageQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of the most used images.
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */
class SpecialMostImages extends ImageQueryPage {

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'Mostimages' );
		$this->setDatabaseProvider( $dbProvider );
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [
			'tables' => [ 'imagelinks' ],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'il_to',
				'value' => 'COUNT(*)'
			],
			'options' => [
				'GROUP BY' => 'il_to',
				'HAVING' => 'COUNT(*) > 1'
			]
		];
	}

	/** @inheritDoc */
	protected function getCellHtml( $row ) {
		return $this->msg( 'nimagelinks' )->numParams( $row->value )->escaped() . '<br />';
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'highuse';
	}

	/** @inheritDoc */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase(
			ImageLinksTable::VIRTUAL_DOMAIN,
			'vslow'
		);
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialMostImages::class, 'MostimagesPage' );
