<?php

/**
 * Tests for the SiteArray class.
 * The tests for methods defined in the SiteList interface are in SiteListTest.
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
 *Both
 * Bith
 * @file
 * @since 1.20
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteArrayTest extends GenericArrayObjectTest {

	/**
	 * @see GenericArrayObjectTest::elementInstancesProvider
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function elementInstancesProvider() {
		$sites = TestSites::getSites();

		$siteArrays = array();

		$siteArrays[] = $sites;

		$siteArrays[] = array( array_shift( $sites ) );

		$siteArrays[] = array( array_shift( $sites ), array_shift( $sites ) );

		return $this->arrayWrap( $siteArrays );
	}

	/**
	 * @see GenericArrayObjectTest::getInstanceClass
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getInstanceClass() {
		return 'SiteArray';
	}

}