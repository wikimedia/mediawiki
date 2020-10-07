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

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @since 1.31
 * @deprecated since 1.35; we don't support PHPUnit 4 any more
 */
trait PHPUnit4And6Compat {
	/**
	 * @deprecated since 1.35
	 *
	 * This function was renamed to expectException() in PHPUnit 6, so this
	 * is a temporary backwards-compatibility layer while we transition.
	 */
	public function setExpectedException( $name, $message = '', $code = null ) {
		if ( $name !== null ) {
			$this->expectException( $name );
		}
		if ( $message !== '' ) {
			$this->expectExceptionMessage( $message );
		}
		if ( $code !== null ) {
			$this->expectExceptionCode( $code );
		}
	}

	/**
	 * @deprecated since 1.35, use createMock() or getMockBuilder()
	 *
	 * @return MockObject
	 */
	public function getMock( $originalClassName, $methods = [], array $arguments = [],
		$mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true,
		$callAutoload = true, $cloneArguments = false, $callOriginalMethods = false,
		$proxyTarget = null
	) {
		$builder = $this->getMockBuilder( $originalClassName )
			->setMethods( $methods )
			->setConstructorArgs( $arguments )
			->setMockClassName( $mockClassName )
			->setProxyTarget( $proxyTarget );
		if ( $callOriginalConstructor ) {
			$builder->enableOriginalConstructor();
		} else {
			$builder->disableOriginalConstructor();
		}
		if ( $callOriginalClone ) {
			$builder->enableOriginalClone();
		} else {
			$builder->disableOriginalClone();
		}
		if ( $callAutoload ) {
			$builder->enableAutoload();
		} else {
			$builder->disableAutoload();
		}
		if ( $cloneArguments ) {
			$builder->enableArgumentCloning();
		} else {
			$builder->disableArgumentCloning();
		}
		if ( $callOriginalMethods ) {
			$builder->enableProxyingToOriginalMethods();
		} else {
			$builder->disableProxyingToOriginalMethods();
		}

		return $builder->getMock();
	}
}
