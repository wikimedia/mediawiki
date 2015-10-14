<?php
/**
 * Access to properties of a page.
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

/**
 * Gives access to properties of a page.
 */
class PageProps {

	private $id;

	/**
	 * Create a PageProps object
	 *
	 * @param $id
	 *
	**/
	public function __construct( $id ) {
		$this->id = $id;
	}

	/**
	 * Get all page property values.
	 *
	 * @return property value array or false if not found
	 *
	 */
	public function getProperties() {

		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			'page_props',
			array(
				'pp_propname',
				'pp_value'
			),
			array(
				'pp_page' => $this->id
			),
			__METHOD__
		);

		$pageProperties = array();

		foreach ( $result as $row ) {
			$pageProperties[$row->pp_propname] = $row->pp_value;
		}

		return $pageProperties;
	}

	/**
	 * Get page property value for a given property name.
	 *
	 * @param $propertyName
	 *
	 * @return property value or false if not found
	 *
	 */
	public function getProperty( $propertyName ) {

		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			'page_props',
			array( 'pp_value' ),
			array(
				'pp_page' => $this->id,
				'pp_propname' => $propertyName
			),
			__METHOD__
		);

		if ( $result->numRows() > 0 ) {
			$row = $result->fetchRow();
			$value = $row['pp_value'];
			return $value;
		}

		return false;
	}

	/**
	 * Set page property value.
	 *
	 * @param $propertyName
	 * @param $propertyValue
	 *
	 */
	 public function setProperty( $propertyName, $propertyValue ) {

		$old_value = $this->getProperty( $propertyName );

		if ( $old_value === false ) {

			$dbw = wfGetDB( DB_MASTER );
			$result = $dbw->insert(
				'page_props',
				array(
					'pp_page' => $this->id,
					'pp_propname' => $propertyName,
					'pp_value' => $propertyValue
				),
				__METHOD__
			);

		} else if ( $old_value !== $propertyValue ) {

			$dbw = wfGetDB( DB_MASTER );
			$result = $dbw->update(
				'page_props',
				array(
					'pp_value' => $propertyValue
				),
				array(
					'pp_page' => $this->id,
					'pp_propname' => $propertyName
				),
				__METHOD__
			);

		}

	}

}
