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
 *
 * @since 1.27
 *
 */
class PageProps {

	/** @var int $pageId page ID of the page being queried */
	private $pageId;

	/** Used to select master DB for reading */
	const USE_MASTER = 1;

	/**
	 * Create a PageProps object
	 *
	 * @param int $pageId
	 *
	 */
	public function __construct( $pageId ) {
		$this->pageId = $pageId;
	}

	/**
	 * Get all page property values.
	 *
	 * @param int $flags Use PageProps::USE_MASTER to use master DB
	 *
	 * @return string|bool property value array or false if not found
	 *
	 */
	public function getProperties( $flags = 0 ) {

		$db = ( $flags & self::USE_MASTER ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		$result = $db->select(
			'page_props',
			array(
				'pp_propname',
				'pp_value'
			),
			array(
				'pp_page' => $this->pageId
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
	 * @param string $propertyName
	 * @param int $flags Use PageProps::USE_MASTER to use master DB
	 *
	 * @return string|bool property value or false if not found
	 *
	 */
	public function getProperty( $propertyName, $flags = 0 ) {

		$db = ( $flags & self::USE_MASTER ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		$row = $db->selectRow(
			'page_props',
			array( 'pp_value' ),
			array(
				'pp_page' => $this->pageId,
				'pp_propname' => $propertyName
			),
			__METHOD__
		);

		if ( $row !== false ) {
			return $row->pp_value;
		}

		return false;
	}

	/**
	 * Determines the sort key for the given property value.
	 * This will return $value if it is a float or int,
	 * 1 or resp. 0 if it is a bool, and null otherwise.
	 *
	 * @param mixed $value
	 *
	 * @return float|null
	 *
	 */
	private function getPropertySortKeyValue( $value ) {
		if ( is_int( $value ) || is_float( $value ) || is_bool( $value ) ) {
			return floatval( $value );
		}

		return null;
	}

	/**
	 * Set page property values.
	 *
	 * @param array mapping property name to property value
	 *
	 */
	public function setProperties( $properties ) {

		$rows = array();

		foreach ( $properties as $propertyName => $propertyValue ) {

			$row = array(
				'pp_page' => $this->pageId,
				'pp_propname' => $propertyName,
				'pp_value' => $propertyValue
			);

			global $wgPagePropsHaveSortkey;
			if ( $wgPagePropsHaveSortkey ) {
				$row['pp_sortkey'] =
					$this->getPropertySortKeyValue( $propertyValue );
			}

			$rows[] = $row;
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'page_props',
			array (
				array (
					'pp_page',
					'pp_propname'
				)
			),
			$rows,
			__METHOD__
		);
	}

	/**
	 * Set page property value.
	 *
	 * @param string $propertyName
	 * @param mixed $propertyValue
	 *
	 */
	public function setProperty( $propertyName, $propertyValue ) {

		$properties = array();
		$properties[$propertyName] = $propertyValue;

		$this->setProperties( $properties );

	}

}
