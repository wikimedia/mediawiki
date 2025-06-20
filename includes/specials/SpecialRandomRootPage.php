<?php
/**
 * Copyright © 2008 Hojjat (aka Huji)
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

use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Redirect to a random page that isn't a subpage.
 *
 * @ingroup SpecialPage
 */
class SpecialRandomRootPage extends SpecialRandomPage {

	public function __construct(
		IConnectionProvider $dbProvider,
		NamespaceInfo $nsInfo
	) {
		parent::__construct( $dbProvider, $nsInfo );
		$this->mName = 'Randomrootpage';
		$dbr = $dbProvider->getReplicaDatabase();
		$this->extra[] = $dbr->expr(
			'page_title',
			IExpression::NOT_LIKE,
			new LikeValue( $dbr->anyString(), '/', $dbr->anyString() )
		);
	}

	/** @inheritDoc */
	public function isRedirect() {
		// Don't select redirects
		return false;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRandomRootPage::class, 'SpecialRandomRootPage' );
