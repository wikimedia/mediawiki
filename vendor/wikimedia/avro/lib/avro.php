<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Avro library top-level file.
 *
 * This file in turn includes all files supporting the
 * Avro implementation.
 *
 * @package Avro
 */

/**
 * General Avro exceptions.
 * @package Avro
 */
class AvroException extends Exception {}

/**
 * Library-level class for PHP Avro port.
 *
 * Contains library details such as version number and platform checks.
 *
 * This port is an implementation of the
 * {@link http://avro.apache.org/docs/1.3.3/spec.html Avro 1.3.3 Specification}
 *
 * @package Avro
 *
 */
class Avro
{
  /**
   * @var string version number of Avro specification to which
   *             this implemenation complies
   */
  const SPEC_VERSION  = '1.3.3';

  /**#@+
   * Constant to enumerate endianness.
   * @access private
   * @var int
   */
  const BIG_ENDIAN    = 0x00;
  const LITTLE_ENDIAN = 0x01;
  /**#@-*/

  /**
   * Memoized result of self::set_endianness()
   * @var int self::BIG_ENDIAN or self::LITTLE_ENDIAN
   * @see self::set_endianness()
   */
  private static $endianness;

  /**#@+
   * Constant to enumerate biginteger handling mode.
   * GMP is used, if available, on 32-bit platforms.
   */
  const PHP_BIGINTEGER_MODE = 0x00;
  const GMP_BIGINTEGER_MODE = 0x01;
  /**#@-*/

  /**
   * @var int
   * Mode used to handle bigintegers. After Avro::check_64_bit() has been called,
   * (usually via a call to Avro::check_platform(), set to
   * self::GMP_BIGINTEGER_MODE on 32-bit platforms that have GMP available,
   * and to self::PHP_BIGINTEGER_MODE otherwise.
   */
  private static $biginteger_mode;

  /**
   * Wrapper method to call each required check.
   *
   */
  public static function check_platform()
  {
    self::check_64_bit();
    self::check_little_endian();
  }

  /**
   * Determines if the host platform can encode and decode long integer data.
   *
   * @throws AvroException if the platform cannot handle long integers.
   */
  private static function check_64_bit()
  {
    if (8 != PHP_INT_SIZE)
      if (extension_loaded('gmp'))
        self::$biginteger_mode = self::GMP_BIGINTEGER_MODE;
      else
        throw new AvroException('This platform cannot handle a 64-bit operations. '
                                . 'Please install the GMP PHP extension.');
    else
      self::$biginteger_mode = self::PHP_BIGINTEGER_MODE;

  }

  /**
   * @returns boolean true if the PHP GMP extension is used and false otherwise.
   * @internal Requires Avro::check_64_bit() (exposed via Avro::check_platform())
   *           to have been called to set Avro::$biginteger_mode.
   */
  static function uses_gmp()
  {
    return (self::GMP_BIGINTEGER_MODE == self::$biginteger_mode);
  }

  /**
   * Determines if the host platform is little endian,
   * required for processing double and float data.
   *
   * @throws AvroException if the platform is not little endian.
   */
  private static function check_little_endian()
  {
    if (!self::is_little_endian_platform())
      throw new AvroException('This is not a little-endian platform');
  }

  /**
   * Determines the endianness of the host platform and memoizes
   * the result to Avro::$endianness.
   *
   * Based on a similar check perfomed in http://pear.php.net/package/Math_BinaryUtils
   *
   * @throws AvroException if the endianness cannot be determined.
   */
  private static function set_endianness()
  {
    $packed = pack('d', 1);
    switch ($packed)
    {
      case "\77\360\0\0\0\0\0\0":
        self::$endianness = self::BIG_ENDIAN;
        break;
      case "\0\0\0\0\0\0\360\77":
        self::$endianness = self::LITTLE_ENDIAN;
        break;
      default:
        throw new AvroException(
          sprintf('Error determining platform endianness: %s',
                  AvroDebug::hex_string($packed)));
    }
  }

  /**
   * @returns boolean true if the host platform is big endian
   *                  and false otherwise.
   * @uses self::set_endianness()
   */
  private static function is_big_endian_platform()
  {
    if (is_null(self::$endianness))
      self::set_endianness();

    return (self::BIG_ENDIAN == self::$endianness);
  }

  /**
   * @returns boolean true if the host platform is little endian,
   *                  and false otherwise.
   * @uses self::is_bin_endian_platform()
   */
  private static function is_little_endian_platform()
  {
    return !self::is_big_endian_platform();
  }

}

require_once('avro/util.php');
require_once('avro/debug.php');
require_once('avro/schema.php');
require_once('avro/io.php');
require_once('avro/gmp.php');
require_once('avro/datum.php');
require_once('avro/data_file.php');
require_once('avro/protocol.php');
