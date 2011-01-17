<?php
/**
 * Script will find all rows in the categorylinks table whose collation is
 * out-of-date (cl_collation != $wgCategoryCollation) and repopulate cl_sortkey
 * using the page title and cl_sortkey_prefix.
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
 * @author Aryeh Gregor (Simetrical)
 */

#$optionsWithArgs = array( 'begin', 'max-slave-lag' );

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class UpdateCollation extends Maintenance {
	const BATCH_SIZE = 1000;

	public function __construct() {
		parent::__construct();

		global $wgCategoryCollation;
		$this->mDescription = <<<TEXT
This script will find all rows in the categorylinks table whose collation is
out-of-date (cl_collation != '$wgCategoryCollation') and repopulate cl_sortkey
using the page title and cl_sortkey_prefix.  If everything's collation is
up-to-date, it will do nothing.
TEXT;

		#$this->addOption( 'force', 'Run on all rows, even if the collation is supposed to be up-to-date.' );
	}

	public function execute() {
		global $wgCategoryCollation, $wgContLang;

		$dbw = wfGetDB( DB_MASTER );
		$count = $dbw->selectField(
			'categorylinks',
			'COUNT(*)',
			'cl_collation != ' . $dbw->addQuotes( $wgCategoryCollation ),
			__METHOD__
		);

		if ( $count == 0 ) {
			$this->output( "Collations up-to-date.\n" );
			return;
		}
		$this->output( "Fixing collation for $count rows.\n" );

		$count = 0;
		do {
			$res = $dbw->select(
				array( 'categorylinks', 'page' ),
				array( 'cl_from', 'cl_to', 'cl_sortkey_prefix', 'cl_collation',
					'cl_sortkey', 'page_namespace', 'page_title'
				),
				array(
					'cl_collation != ' . $dbw->addQuotes( $wgCategoryCollation ),
					'cl_from = page_id'
				),
				__METHOD__,
				array( 'LIMIT' => self::BATCH_SIZE )
			);

			$dbw->begin();
			foreach ( $res as $row ) {
				$title = Title::newFromRow( $row );
				if ( !$row->cl_collation ) {
					# This is an old-style row, so the sortkey needs to be
					# converted.
					if ( $row->cl_sortkey == $title->getText()
					|| $row->cl_sortkey == $title->getPrefixedText() ) {
						$prefix = '';
					} else {
						# Custom sortkey, use it as a prefix
						$prefix = $row->cl_sortkey;
					}
				} else {
					$prefix = $row->cl_sortkey_prefix;
				}
				# cl_type will be wrong for lots of pages if cl_collation is 0,
				# so let's update it while we're here.
				if ( $title->getNamespace() == NS_CATEGORY ) {
					$type = 'subcat';
				} elseif ( $title->getNamespace() == NS_FILE ) {
					$type = 'file';
				} else {
					$type = 'page';
				}
				$dbw->update(
					'categorylinks',
					array(
						'cl_sortkey' => $wgContLang->convertToSortkey(
							$title->getCategorySortkey( $prefix ) ),
						'cl_sortkey_prefix' => $prefix,
						'cl_collation' => $wgCategoryCollation,
						'cl_type' => $type,
						'cl_timestamp = cl_timestamp',
					),
					array( 'cl_from' => $row->cl_from, 'cl_to' => $row->cl_to ),
					__METHOD__
				);
			}
			$dbw->commit();

			$count += $res->numRows();
			$this->output( "$count done.\n" );
		} while ( $res->numRows() == self::BATCH_SIZE );
	}
}

$maintClass = "UpdateCollation";
require_once( RUN_MAINTENANCE_IF_MAIN );
