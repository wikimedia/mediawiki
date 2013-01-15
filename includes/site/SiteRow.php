<?php

/**
 * Represents a single row in the SitesTable
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
 * @since 1.21
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Werner
 */
class SiteRow extends ORMRow {

	/**
	 * @see IORMRow::save
	 * @see Site::save
	 *
	 * @since 1.21
	 *
	 * @param string|null $functionName
	 *
	 * @return boolean Success indicator
	 */
	public function save( $functionName = null ) {
		$dbw = $this->table->getWriteDbConnection();

		$trx = $dbw->trxLevel();

		if ( $trx == 0 ) {
			$dbw->begin( __METHOD__ );
		}

		$this->setField( 'protocol', $this->getProtocol() );
		$this->setField( 'domain', strrev( $this->getDomain() ) . '.' );

		$existedAlready = $this->hasIdField();

		$success = parent::save( $functionName );

		if ( $success && $existedAlready ) {
			$dbw->delete(
				'site_identifiers',
				array( 'si_site' => $this->getId() ),
				__METHOD__
			);
		}

		if ( $success && $this->localIds !== false ) {
			foreach ( $this->localIds as $type => $ids ) {
				foreach ( $ids as $id ) {
					$dbw->insert(
						'site_identifiers',
						array(
							'si_site' => $this->getId(),
							'si_type' => $type,
							'si_key' => $id,
						),
						__METHOD__
					);
				}
			}
		}

		if ( $trx == 0 ) {
			$dbw->commit( __METHOD__ );
		}

		return $success;
	}

	/**
	 * @since 1.21
	 *
	 * @see ORMRow::onRemoved
	 */
	protected function onRemoved() {
		$dbw = $this->table->getWriteDbConnection();

		$dbw->delete(
			'site_identifiers',
			array(
				'si_site' => $this->getId()
			),
			__METHOD__
		);

		parent::onRemoved();
	}

}
