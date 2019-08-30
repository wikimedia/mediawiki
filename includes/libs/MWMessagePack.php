<?php
/**
 * MessagePack serializer
 *
 * MessagePack is a space-efficient binary data interchange format. This
 * class provides a pack() method that encodes native PHP values as MessagePack
 * binary strings. The implementation is derived from msgpack-php.
 *
 * Copyright (c) 2013 Ori Livneh <ori@wikimedia.org>
 * Copyright (c) 2011 OnlineCity <https://github.com/onlinecity/msgpack-php>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @see <http://msgpack.org/>
 * @see <http://wiki.msgpack.org/display/MSGPACK/Format+specification>
 *
 * @since 1.23
 * @file
 * @deprecated since 1.34, no longer used
 */
class MWMessagePack {
	/** @var bool|null Whether current system is bigendian. */
	public static $bigendian = null;

	/**
	 * Encode a value using MessagePack
	 *
	 * This method supports null, boolean, integer, float, string and array
	 * (both indexed and associative) types. Object serialization is not
	 * supported.
	 *
	 * @deprecated since 1.34, no longer used
	 *
	 * @param mixed $value
	 * @return string
	 * @throws InvalidArgumentException if $value is an unsupported type or too long a string
	 */
	public static function pack( $value ) {
		wfDeprecated( __METHOD__, '1.34' );
		if ( self::$bigendian === null ) {
			self::$bigendian = pack( 'S', 1 ) === pack( 'n', 1 );
		}

		switch ( gettype( $value ) ) {
			case 'NULL':
				return "\xC0";

			case 'boolean':
				return $value ? "\xC3" : "\xC2";

			case 'double':
			case 'float':
				return self::$bigendian
					? "\xCB" . pack( 'd', $value )
					: "\xCB" . strrev( pack( 'd', $value ) );

			case 'string':
				$length = strlen( $value );
				if ( $length < 32 ) {
					return pack( 'Ca*', 0xA0 | $length, $value );
				} elseif ( $length <= 0xFFFF ) {
					return pack( 'Cna*', 0xDA, $length, $value );
				} elseif ( $length <= 0xFFFFFFFF ) {
					return pack( 'CNa*', 0xDB, $length, $value );
				}
				throw new InvalidArgumentException( __METHOD__
					. ": string too long (length: $length; max: 4294967295)" );

			case 'integer':
				if ( $value >= 0 ) {
					if ( $value <= 0x7F ) {
						// positive fixnum
						return chr( $value );
					}
					if ( $value <= 0xFF ) {
						// uint8
						return pack( 'CC', 0xCC, $value );
					}
					if ( $value <= 0xFFFF ) {
						// uint16
						return pack( 'Cn', 0xCD, $value );
					}
					if ( $value <= 0xFFFFFFFF ) {
						// uint32
						return pack( 'CN', 0xCE, $value );
					}
					if ( $value <= 0xFFFFFFFFFFFFFFFF ) {
						// uint64
						$hi = ( $value & 0xFFFFFFFF00000000 ) >> 32;
						$lo = $value & 0xFFFFFFFF;
						return self::$bigendian
							? pack( 'CNN', 0xCF, $lo, $hi )
							: pack( 'CNN', 0xCF, $hi, $lo );
					}
				} else {
					if ( $value >= -32 ) {
						// negative fixnum
						return pack( 'c', $value );
					}
					if ( $value >= -0x80 ) {
						// int8
						return pack( 'Cc', 0xD0, $value );
					}
					if ( $value >= -0x8000 ) {
						// int16
						$p = pack( 's', $value );
						return self::$bigendian
							? pack( 'Ca2', 0xD1, $p )
							: pack( 'Ca2', 0xD1, strrev( $p ) );
					}
					if ( $value >= -0x80000000 ) {
						// int32
						$p = pack( 'l', $value );
						return self::$bigendian
							? pack( 'Ca4', 0xD2, $p )
							: pack( 'Ca4', 0xD2, strrev( $p ) );
					}
					if ( $value >= -0x8000000000000000 ) {
						// int64
						// pack() does not support 64-bit ints either so pack into two 32-bits
						$p1 = pack( 'l', $value & 0xFFFFFFFF );
						// @phan-suppress-next-line PhanTypeInvalidLeftOperandOfIntegerOp
						$p2 = pack( 'l', ( $value >> 32 ) & 0xFFFFFFFF );
						return self::$bigendian
							? pack( 'Ca4a4', 0xD3, $p1, $p2 )
							: pack( 'Ca4a4', 0xD3, strrev( $p2 ), strrev( $p1 ) );
					}
				}
				throw new InvalidArgumentException( __METHOD__ . ": invalid integer '$value'" );

			case 'array':
				$buffer = '';
				$length = count( $value );
				if ( $length > 0xFFFFFFFF ) {
					throw new InvalidArgumentException( __METHOD__
						. ": array too long (length: $length, max: 4294967295)" );
				}

				$index = 0;
				foreach ( $value as $k => $v ) {
					if ( $index !== $k || $index === $length ) {
						break;
					} else {
						$index++;
					}
				}
				$associative = $index !== $length;

				if ( $associative ) {
					if ( $length < 16 ) {
						$buffer .= pack( 'C', 0x80 | $length );
					} elseif ( $length <= 0xFFFF ) {
						$buffer .= pack( 'Cn', 0xDE, $length );
					} else {
						$buffer .= pack( 'CN', 0xDF, $length );
					}
					foreach ( $value as $k => $v ) {
						$buffer .= self::pack( $k );
						$buffer .= self::pack( $v );
					}
				} else {
					if ( $length < 16 ) {
						$buffer .= pack( 'C', 0x90 | $length );
					} elseif ( $length <= 0xFFFF ) {
						$buffer .= pack( 'Cn', 0xDC, $length );
					} else {
						$buffer .= pack( 'CN', 0xDD, $length );
					}
					foreach ( $value as $v ) {
						$buffer .= self::pack( $v );
					}
				}
				return $buffer;

			default:
				throw new InvalidArgumentException( __METHOD__ . ': unsupported type ' . gettype( $value ) );
		}
	}
}
