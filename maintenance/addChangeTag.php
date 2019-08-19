<?php

/**
 * Adds a change tag to the wiki.
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Adds a change tag to the wiki
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class AddChangeTag extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Adds a change tag to the wiki.' );

		$this->addOption( 'tag', 'Tag to add', true, true );
		$this->addOption( 'reason', 'Reason for adding the tag', true, true );
	}

	public function execute() {
		$user = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );

		$tag = $this->getOption( 'tag' );

		$status = ChangeTags::createTagWithChecks(
			$tag,
			$this->getOption( 'reason' ),
			$user
		);

		if ( !$status->isGood() ) {
			$this->fatalError( $status->getMessage( false, false, 'en' )->text() );
		}

		$this->output( "$tag was created.\n" );
	}
}

$maintClass = AddChangeTag::class;
require_once RUN_MAINTENANCE_IF_MAIN;
