<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

/**
 * @since 1.31
 */
trait PHPUnit4And6Compat {
	/**
	 * @see PHPUnit_Framework_TestCase::setExpectedException
	 *
	 * This function was renamed to expectException() in PHPUnit 6, so this
	 * is a temporary backwards-compatibility layer while we transition.
	 */
	public function setExpectedException( $name, $message = '', $code = null ) {
		if ( is_callable( [ $this, 'expectException' ] ) ) {
			$this->expectException( $name );
			if ( $message !== '' ) {
				$this->expectExceptionMessage( $message );
			}
			if ( $code !== null ) {
				$this->expectExceptionCode( $code );
			}
		} else {
			parent::setExpectedException( $name, $message, $code );
		}
	}

	/**
	 * @see PHPUnit_Framework_TestCase::getMock
	 *
	 * @param string $originalClass
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	public function getMock( $originalClass ) {
		if ( is_callable( 'parent::getMock' ) ) {
			return parent::getMock( $originalClass );
		} else {
			return $this->getMockBuilder( $originalClass )->getMock();
		}
	}

	/**
	 * Return a test double for the specified class. This
	 * is a forward port of the createMock function that
	 * was introduced in PHPUnit 5.4.
	 *
	 * @param string $originalClassName
	 * @return PHPUnit_Framework_MockObject_MockObject
	 * @throws Exception
	 */
	public function createMock( $originalClassName ) {
		if ( is_callable( 'parent::createMock' ) ) {
			return parent::createMock( $originalClassName );
		}
		// Compat for PHPUnit <= 5.4
		return $this->getMockBuilder( $originalClassName )
			->disableOriginalConstructor()
			->disableOriginalClone()
			->disableArgumentCloning()
			// New in phpunit-mock-objects 3.2 (phpunit 5.4.0)
			// ->disallowMockingUnknownTypes()
			->getMock();
	}
}
