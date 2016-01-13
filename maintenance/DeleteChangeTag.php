<?php
/**
 * Deletes a batch of pages.
 * Usage: php deleteChangeTag.php [--tag <tag>] [--l <limit>]  [-i <interval>] [listfile]
 * where
 *   <tag> is a change tag to delete from the wiki.
 * 	 <limit> is a number of tags to delete per one cycle.
 *   <interval> is the number of seconds to sleep for after each delete
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
 * Maintenance script to delete change tag.
 *
 * @ingroup Maintenance
 */
class DeleteChangeTag extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Deletes change tag';
		$this->addOption( 'i', 'Interval to sleep between deletions' );
		$this->addOption( 'tag', 'Change tag to delete from database.', true, true );
		$this->addOption( 'l', 'Number of tags to delete per one cycle', false, true );
	}

	public function execute() {

		# Options processing
		$interval = $this->getOption( 'i', 0 );
		$tag = $this->getOption( 'tag', '' );
		$limit = $this->getOption( 'l', 0 );

		$dbw = $this->getDB( DB_MASTER );
		echo "$tag\n$limit\n." . $dbw->selectField( 'change_tag', 'ct_tag', array( 'ct_tag' => $tag ) );
		# Handle each page
		while ( $dbw->selectField( 'change_tag', 'ct_tag', array( 'ct_tag' => $tag ) ) !== false ) {
			ChangeTags::deleteTagEverywhere( $tag, $limit );
			if ( $interval ) {
				sleep( $interval );
			}
			wfWaitForSlaves();
		}
	}
}

$maintClass = 'DeleteChangeTag';
require_once RUN_MAINTENANCE_IF_MAIN;
