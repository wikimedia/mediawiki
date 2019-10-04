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
 * @deprecated since 1.35; we don't support PHPUnit 4 any more
 */
trait PHPUnit4And6Compat {
	/**
	 * @see PHPUnit_Framework_TestCase::setExpectedException
	 *
	 * This function was renamed to expectException() in PHPUnit 6, so this
	 * is a temporary backwards-compatibility layer while we transition.
	 */
	public function setExpectedException( $name, $message = '', $code = null ) {
		if ( is_callable( 'parent::expectException' ) ) {
			if ( $name !== null ) {
				parent::expectException( $name );
			}
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
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	public function getMock( $originalClassName, $methods = [], array $arguments = [],
		$mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true,
		$callAutoload = true, $cloneArguments = false, $callOriginalMethods = false,
		$proxyTarget = null
	) {
		if ( is_callable( 'parent::getMock' ) ) {
			return parent::getMock(
				$originalClassName, $methods, $arguments, $mockClassName,
				$callOriginalConstructor, $callOriginalClone, $callAutoload,
				$cloneArguments, $callOriginalMethods, $proxyTarget
			);
		} else {
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
}
