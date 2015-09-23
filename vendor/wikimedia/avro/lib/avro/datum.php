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
 * Classes for reading and writing Avro data to AvroIO objects.
 *
 * @package Avro
 *
 * @todo Implement JSON encoding, as is required by the Avro spec.
 */

/**
 * Exceptions arising from writing or reading Avro data.
 *
 * @package Avro
 */
class AvroIOTypeException extends AvroException
{
  /**
   * @param AvroSchema $expected_schema
   * @param mixed $datum
   */
  public function __construct($expected_schema, $datum)
  {
    parent::__construct(sprintf('The datum %s is not an example of schema %s',
                                var_export($datum, true), $expected_schema));
  }
}

/**
 * Exceptions arising from incompatibility between
 * reader and writer schemas.
 *
 * @package Avro
 */
class AvroIOSchemaMatchException extends AvroException
{
  /**
   * @param AvroSchema $writers_schema
   * @param AvroSchema $readers_schema
   */
  function __construct($writers_schema, $readers_schema)
  {
    parent::__construct(
      sprintf("Writer's schema %s and Reader's schema %s do not match.",
              $writers_schema, $readers_schema));
  }
}

/**
 * Handles schema-specific writing of data to the encoder.
 *
 * Ensures that each datum written is consistent with the writer's schema.
 *
 * @package Avro
 */
class AvroIODatumWriter
{
  /**
   * Schema used by this instance to write Avro data.
   * @var AvroSchema
   */
  private $writers_schema;

  /**
   * @param AvroSchema $writers_schema
   */
  function __construct($writers_schema=null)
  {
    $this->writers_schema = $writers_schema;
  }

  /**
   * @param AvroSchema $writers_schema
   * @param $datum
   * @param AvroIOBinaryEncoder $encoder
   * @returns mixed
   *
   * @throws AvroIOTypeException if $datum is invalid for $writers_schema
   */
  function write_data($writers_schema, $datum, $encoder)
  {
    if (!AvroSchema::is_valid_datum($writers_schema, $datum))
      throw new AvroIOTypeException($writers_schema, $datum);

    switch ($writers_schema->type())
    {
      case AvroSchema::NULL_TYPE:
        return $encoder->write_null($datum);
      case AvroSchema::BOOLEAN_TYPE:
        return $encoder->write_boolean($datum);
      case AvroSchema::INT_TYPE:
        return $encoder->write_int($datum);
      case AvroSchema::LONG_TYPE:
        return $encoder->write_long($datum);
      case AvroSchema::FLOAT_TYPE:
        return $encoder->write_float($datum);
      case AvroSchema::DOUBLE_TYPE:
        return $encoder->write_double($datum);
      case AvroSchema::STRING_TYPE:
        return $encoder->write_string($datum);
      case AvroSchema::BYTES_TYPE:
        return $encoder->write_bytes($datum);
      case AvroSchema::ARRAY_SCHEMA:
        return $this->write_array($writers_schema, $datum, $encoder);
      case AvroSchema::MAP_SCHEMA:
        return $this->write_map($writers_schema, $datum, $encoder);
      case AvroSchema::FIXED_SCHEMA:
        return $this->write_fixed($writers_schema, $datum, $encoder);
      case AvroSchema::ENUM_SCHEMA:
      return $this->write_enum($writers_schema, $datum, $encoder);
      case AvroSchema::RECORD_SCHEMA:
      case AvroSchema::ERROR_SCHEMA:
      case AvroSchema::REQUEST_SCHEMA:
        return $this->write_record($writers_schema, $datum, $encoder);
      case AvroSchema::UNION_SCHEMA:
        return $this->write_union($writers_schema, $datum, $encoder);
      default:
        throw new AvroException(sprintf('Uknown type: %s',
                                        $writers_schema->type));
    }
  }

  /**
   * @param $datum
   * @param AvroIOBinaryEncoder $encoder
   */
  function write($datum, $encoder)
  {
    $this->write_data($this->writers_schema, $datum, $encoder);
  }

  /**#@+
   * @param AvroSchema $writers_schema
   * @param null|boolean|int|float|string|array $datum item to be written
   * @param AvroIOBinaryEncoder $encoder
   */
  private function write_array($writers_schema, $datum, $encoder)
  {
    $datum_count = count($datum);
    if (0 < $datum_count)
    {
      $encoder->write_long($datum_count);
      $items = $writers_schema->items();
      foreach ($datum as $item)
        $this->write_data($items, $item, $encoder);
    }
    return $encoder->write_long(0);
  }

  private function write_map($writers_schema, $datum, $encoder)
  {
    $datum_count = count($datum);
    if ($datum_count > 0)
    {
      $encoder->write_long($datum_count);
      foreach ($datum as $k => $v)
      {
        $encoder->write_string($k);
        $this->write_data($writers_schema->values(), $v, $encoder);
      }
    }
    $encoder->write_long(0);
  }

  private function write_union($writers_schema, $datum, $encoder)
  {
    $datum_schema_index = -1;
    $datum_schema = null;
    foreach ($writers_schema->schemas() as $index => $schema)
      if (AvroSchema::is_valid_datum($schema, $datum))
      {
        $datum_schema_index = $index;
        $datum_schema = $schema;
        break;
      }

    if (is_null($datum_schema))
      throw new AvroIOTypeException($writers_schema, $datum);

    $encoder->write_long($datum_schema_index);
    $this->write_data($datum_schema, $datum, $encoder);
  }

  private function write_enum($writers_schema, $datum, $encoder)
  {
    $datum_index = $writers_schema->symbol_index($datum);
    return $encoder->write_int($datum_index);
  }

  private function write_fixed($writers_schema, $datum, $encoder)
  {
    /**
     * NOTE Unused $writers_schema parameter included for consistency
     * with other write_* methods.
     */
    return $encoder->write($datum);
  }

  private function write_record($writers_schema, $datum, $encoder)
  {
    foreach ($writers_schema->fields() as $field)
      $this->write_data($field->type(), $datum[$field->name()], $encoder);
  }

  /**#@-*/
}

/**
 * Encodes and writes Avro data to an AvroIO object using
 * Avro binary encoding.
 *
 * @package Avro
 */
class AvroIOBinaryEncoder
{
  /**
   * Performs encoding of the given float value to a binary string
   *
   * XXX: This is <b>not</b> endian-aware! The {@link Avro::check_platform()}
   * called in {@link AvroIOBinaryEncoder::__construct()} should ensure the
   * library is only used on little-endian platforms, which ensure the little-endian
   * encoding required by the Avro spec.
   *
   * @param float $float
   * @returns string bytes
   * @see Avro::check_platform()
   */
  static function float_to_int_bits($float)
  {
    return pack('f', (float) $float);
  }

  /**
   * Performs encoding of the given double value to a binary string
   *
   * XXX: This is <b>not</b> endian-aware! See comments in
   * {@link AvroIOBinaryEncoder::float_to_int_bits()} for details.
   *
   * @param double $double
   * @returns string bytes
   */
  static function double_to_long_bits($double)
  {
    return pack('d', (double) $double);
  }

  /**
   * @param int|string $n
   * @returns string long $n encoded as bytes
   * @internal This relies on 64-bit PHP.
   */
  static public function encode_long($n)
  {
    $n = (int) $n;
    $n = ($n << 1) ^ ($n >> 63);
    $str = '';
    while (0 != ($n & ~0x7F))
    {
      $str .= chr(($n & 0x7F) | 0x80);
      $n >>= 7;
    }
    $str .= chr($n);
    return $str;
  }

  /**
   * @var AvroIO
   */
  private $io;

  /**
   * @param AvroIO $io object to which data is to be written.
   *
   */
  function __construct($io)
  {
    Avro::check_platform();
    $this->io = $io;
  }

  /**
   * @param null $datum actual value is ignored
   */
  function write_null($datum) { return null; }

  /**
   * @param boolean $datum
   */
  function write_boolean($datum)
  {
    $byte = $datum ? chr(1) : chr(0);
    $this->write($byte);
  }

  /**
   * @param int $datum
   */
  function write_int($datum) { $this->write_long($datum); }

  /**
   * @param int $n
   */
  function write_long($n)
  {
    if (Avro::uses_gmp())
      $this->write(AvroGMP::encode_long($n));
    else
      $this->write(self::encode_long($n));
  }

  /**
   * @param float $datum
   * @uses self::float_to_int_bits()
   */
  public function write_float($datum)
  {
    $this->write(self::float_to_int_bits($datum));
  }

  /**
   * @param float $datum
   * @uses self::double_to_long_bits()
   */
  public function write_double($datum)
  {
    $this->write(self::double_to_long_bits($datum));
  }

  /**
   * @param string $str
   * @uses self::write_bytes()
   */
  function write_string($str) { $this->write_bytes($str); }

  /**
   * @param string $bytes
   */
  function write_bytes($bytes)
  {
    $this->write_long(strlen($bytes));
    $this->write($bytes);
  }

  /**
   * @param string $datum
   */
  function write($datum) { $this->io->write($datum); }
}

/**
 * Handles schema-specifc reading of data from the decoder.
 *
 * Also handles schema resolution between the reader and writer
 * schemas (if a writer's schema is provided).
 *
 * @package Avro
 */
class AvroIODatumReader
{
  /**
   *
   * @param AvroSchema $writers_schema
   * @param AvroSchema $readers_schema
   * @returns boolean true if the schemas are consistent with
   *                  each other and false otherwise.
   */
  static function schemas_match($writers_schema, $readers_schema)
  {
    $writers_schema_type = $writers_schema->type;
    $readers_schema_type = $readers_schema->type;

    if (AvroSchema::UNION_SCHEMA == $writers_schema_type
        || AvroSchema::UNION_SCHEMA == $readers_schema_type)
      return true;

    if ($writers_schema_type == $readers_schema_type)
    {
      if (AvroSchema::is_primitive_type($writers_schema_type))
        return true;

      switch ($readers_schema_type)
      {
        case AvroSchema::MAP_SCHEMA:
          return self::attributes_match($writers_schema->values(),
                                        $readers_schema->values(),
                                        array(AvroSchema::TYPE_ATTR));
        case AvroSchema::ARRAY_SCHEMA:
          return self::attributes_match($writers_schema->items(),
                                        $readers_schema->items(),
                                        array(AvroSchema::TYPE_ATTR));
        case AvroSchema::ENUM_SCHEMA:
          return self::attributes_match($writers_schema, $readers_schema,
                                        array(AvroSchema::FULLNAME_ATTR));
        case AvroSchema::FIXED_SCHEMA:
          return self::attributes_match($writers_schema, $readers_schema,
                                        array(AvroSchema::FULLNAME_ATTR,
                                              AvroSchema::SIZE_ATTR));
        case AvroSchema::RECORD_SCHEMA:
        case AvroSchema::ERROR_SCHEMA:
          return self::attributes_match($writers_schema, $readers_schema,
                                        array(AvroSchema::FULLNAME_ATTR));
        case AvroSchema::REQUEST_SCHEMA:
          // XXX: This seems wrong
          return true;
          // XXX: no default
      }

      if (AvroSchema::INT_TYPE == $writers_schema_type
          && in_array($readers_schema_type, array(AvroSchema::LONG_TYPE,
                                                  AvroSchema::FLOAT_TYPE,
                                                  AvroSchema::DOUBLE_TYPE)))
        return true;

      if (AvroSchema::LONG_TYPE == $writers_schema_type
          && in_array($readers_schema_type, array(AvroSchema::FLOAT_TYPE,
                                                  AvroSchema::DOUBLE_TYPE)))
        return true;

      if (AvroSchema::FLOAT_TYPE == $writers_schema_type
          && AvroSchema::DOUBLE_TYPE == $readers_schema_type)
        return true;

      return false;
    }

  }

  /**
   * Checks equivalence of the given attributes of the two given schemas.
   *
   * @param AvroSchema $schema_one
   * @param AvroSchema $schema_two
   * @param string[] $attribute_names array of string attribute names to compare
   *
   * @returns boolean true if the attributes match and false otherwise.
   */
  static function attributes_match($schema_one, $schema_two, $attribute_names)
  {
    foreach ($attribute_names as $attribute_name)
      if ($schema_one->attribute($attribute_name)
          != $schema_two->attribute($attribute_name))
        return false;
    return true;
  }

  /**
   * @var AvroSchema
   */
  private $writers_schema;

  /**
   * @var AvroSchema
   */
  private $readers_schema;

  /**
   * @param AvroSchema $writers_schema
   * @param AvroSchema $readers_schema
   */
  function __construct($writers_schema=null, $readers_schema=null)
  {
    $this->writers_schema = $writers_schema;
    $this->readers_schema = $readers_schema;
  }

  /**
   * @param AvroSchema $readers_schema
   */
  public function set_writers_schema($readers_schema)
  {
    $this->writers_schema = $readers_schema;
  }

  /**
   * @param AvroIOBinaryDecoder $decoder
   * @returns string
   */
  public function read($decoder)
  {
    if (is_null($this->readers_schema))
      $this->readers_schema = $this->writers_schema;
    return $this->read_data($this->writers_schema, $this->readers_schema,
                            $decoder);
  }

  /**#@+
   * @param AvroSchema $writers_schema
   * @param AvroSchema $readers_schema
   * @param AvroIOBinaryDecoder $decoder
   */
  /**
   * @returns mixed
   */
  public function read_data($writers_schema, $readers_schema, $decoder)
  {
    if (!self::schemas_match($writers_schema, $readers_schema))
      throw new AvroIOSchemaMatchException($writers_schema, $readers_schema);

    // Schema resolution: reader's schema is a union, writer's schema is not
    if (AvroSchema::UNION_SCHEMA == $readers_schema->type()
        && AvroSchema::UNION_SCHEMA != $writers_schema->type())
    {
      foreach ($readers_schema->schemas() as $schema)
        if (self::schemas_match($writers_schema, $schema))
          return $this->read_data($writers_schema, $schema, $decoder);
      throw new AvroIOSchemaMatchException($writers_schema, $readers_schema);
    }

    switch ($writers_schema->type())
    {
      case AvroSchema::NULL_TYPE:
        return $decoder->read_null();
      case AvroSchema::BOOLEAN_TYPE:
        return $decoder->read_boolean();
      case AvroSchema::INT_TYPE:
        return $decoder->read_int();
      case AvroSchema::LONG_TYPE:
        return $decoder->read_long();
      case AvroSchema::FLOAT_TYPE:
        return $decoder->read_float();
      case AvroSchema::DOUBLE_TYPE:
        return $decoder->read_double();
      case AvroSchema::STRING_TYPE:
        return $decoder->read_string();
      case AvroSchema::BYTES_TYPE:
        return $decoder->read_bytes();
      case AvroSchema::ARRAY_SCHEMA:
        return $this->read_array($writers_schema, $readers_schema, $decoder);
      case AvroSchema::MAP_SCHEMA:
        return $this->read_map($writers_schema, $readers_schema, $decoder);
      case AvroSchema::UNION_SCHEMA:
        return $this->read_union($writers_schema, $readers_schema, $decoder);
      case AvroSchema::ENUM_SCHEMA:
        return $this->read_enum($writers_schema, $readers_schema, $decoder);
      case AvroSchema::FIXED_SCHEMA:
        return $this->read_fixed($writers_schema, $readers_schema, $decoder);
      case AvroSchema::RECORD_SCHEMA:
      case AvroSchema::ERROR_SCHEMA:
      case AvroSchema::REQUEST_SCHEMA:
        return $this->read_record($writers_schema, $readers_schema, $decoder);
      default:
        throw new AvroException(sprintf("Cannot read unknown schema type: %s",
                                        $writers_schema->type()));
    }
  }

  /**
   * @returns array
   */
  public function read_array($writers_schema, $readers_schema, $decoder)
  {
    $items = array();
    $block_count = $decoder->read_long();
    while (0 != $block_count)
    {
      if ($block_count < 0)
      {
        $block_count = -$block_count;
        $block_size = $decoder->read_long(); // Read (and ignore) block size
      }
      for ($i = 0; $i < $block_count; $i++)
        $items []= $this->read_data($writers_schema->items(),
                                    $readers_schema->items(),
                                    $decoder);
      $block_count = $decoder->read_long();
    }
    return $items;
  }

  /**
   * @returns array
   */
  public function read_map($writers_schema, $readers_schema, $decoder)
  {
    $items = array();
    $pair_count = $decoder->read_long();
    while (0 != $pair_count)
    {
      if ($pair_count < 0)
      {
        $pair_count = -$pair_count;
        // Note: we're not doing anything with block_size other than skipping it
        $block_size = $decoder->read_long();
      }

      for ($i = 0; $i < $pair_count; $i++)
      {
        $key = $decoder->read_string();
        $items[$key] = $this->read_data($writers_schema->values(),
                                        $readers_schema->values(),
                                        $decoder);
      }
      $pair_count = $decoder->read_long();
    }
    return $items;
  }

  /**
   * @returns mixed
   */
  public function read_union($writers_schema, $readers_schema, $decoder)
  {
    $schema_index = $decoder->read_long();
    $selected_writers_schema = $writers_schema->schema_by_index($schema_index);
    return $this->read_data($selected_writers_schema, $readers_schema, $decoder);
  }

  /**
   * @returns string
   */
  public function read_enum($writers_schema, $readers_schema, $decoder)
  {
    $symbol_index = $decoder->read_int();
    $symbol = $writers_schema->symbol_by_index($symbol_index);
    if (!$readers_schema->has_symbol($symbol))
      null; // FIXME: unset wrt schema resolution
    return $symbol;
  }

  /**
   * @returns string
   */
  public function read_fixed($writers_schema, $readers_schema, $decoder)
  {
    return $decoder->read($writers_schema->size());
  }

  /**
   * @returns array
   */
  public function read_record($writers_schema, $readers_schema, $decoder)
  {
    $readers_fields = $readers_schema->fields_hash();
    $record = array();
    foreach ($writers_schema->fields() as $writers_field)
    {
      $type = $writers_field->type();
      if (isset($readers_fields[$writers_field->name()]))
        $record[$writers_field->name()]
          = $this->read_data($type,
                             $readers_fields[$writers_field->name()]->type(),
                             $decoder);
      else
        $this->skip_data($type, $decoder);
    }
    // Fill in default values
    if (count($readers_fields) > count($record))
    {
      $writers_fields = $writers_schema->fields_hash();
      foreach ($readers_fields as $field_name => $field)
      {
        if (!isset($writers_fields[$field_name]))
        {
          if ($field->has_default_value())
            $record[$field->name()]
              = $this->read_default_value($field->type(),
                                          $field->default_value());
          else
            null; // FIXME: unset
        }
      }
    }

    return $record;
  }
  /**#@-*/

  /**
   * @param AvroSchema $field_schema
   * @param null|boolean|int|float|string|array $default_value
   * @returns null|boolean|int|float|string|array
   *
   * @throws AvroException if $field_schema type is unknown.
   */
  public function read_default_value($field_schema, $default_value)
  {
    switch($field_schema->type())
    {
      case AvroSchema::NULL_TYPE:
        return null;
      case AvroSchema::BOOLEAN_TYPE:
        return $default_value;
      case AvroSchema::INT_TYPE:
      case AvroSchema::LONG_TYPE:
        return (int) $default_value;
      case AvroSchema::FLOAT_TYPE:
      case AvroSchema::DOUBLE_TYPE:
        return (float) $default_value;
      case AvroSchema::STRING_TYPE:
      case AvroSchema::BYTES_TYPE:
        return $default_value;
      case AvroSchema::ARRAY_SCHEMA:
        $array = array();
        foreach ($default_value as $json_val)
        {
          $val = $this->read_default_value($field_schema->items(), $json_val);
          $array []= $val;
        }
        return $array;
      case AvroSchema::MAP_SCHEMA:
        $map = array();
        foreach ($default_value as $key => $json_val)
          $map[$key] = $this->read_default_value($field_schema->values(),
                                                 $json_val);
        return $map;
      case AvroSchema::UNION_SCHEMA:
        return $this->read_default_value($field_schema->schema_by_index(0),
                                         $default_value);
      case AvroSchema::ENUM_SCHEMA:
      case AvroSchema::FIXED_SCHEMA:
        return $default_value;
      case AvroSchema::RECORD_SCHEMA:
        $record = array();
        foreach ($field_schema->fields() as $field)
        {
          $field_name = $field->name();
          if (!$json_val = $default_value[$field_name])
            $json_val = $field->default_value();

          $record[$field_name] = $this->read_default_value($field->type(),
                                                           $json_val);
        }
        return $record;
    default:
      throw new AvroException(sprintf('Unknown type: %s', $field_schema->type()));
    }
  }

  /**
   * @param AvroSchema $writers_schema
   * @param AvroIOBinaryDecoder $decoder
   */
  private function skip_data($writers_schema, $decoder)
  {
    switch ($writers_schema->type())
    {
      case AvroSchema::NULL_TYPE:
        return $decoder->skip_null();
      case AvroSchema::BOOLEAN_TYPE:
        return $decoder->skip_boolean();
      case AvroSchema::INT_TYPE:
        return $decoder->skip_int();
      case AvroSchema::LONG_TYPE:
        return $decoder->skip_long();
      case AvroSchema::FLOAT_TYPE:
        return $decoder->skip_float();
      case AvroSchema::DOUBLE_TYPE:
        return $decoder->skip_double();
      case AvroSchema::STRING_TYPE:
        return $decoder->skip_string();
      case AvroSchema::BYTES_TYPE:
        return $decoder->skip_bytes();
      case AvroSchema::ARRAY_SCHEMA:
        return $decoder->skip_array($writers_schema, $decoder);
      case AvroSchema::MAP_SCHEMA:
        return $decoder->skip_map($writers_schema, $decoder);
      case AvroSchema::UNION_SCHEMA:
        return $decoder->skip_union($writers_schema, $decoder);
      case AvroSchema::ENUM_SCHEMA:
        return $decoder->skip_enum($writers_schema, $decoder);
      case AvroSchema::FIXED_SCHEMA:
        return $decoder->skip_fixed($writers_schema, $decoder);
      case AvroSchema::RECORD_SCHEMA:
      case AvroSchema::ERROR_SCHEMA:
      case AvroSchema::REQUEST_SCHEMA:
        return $decoder->skip_record($writers_schema, $decoder);
      default:
        throw new AvroException(sprintf('Uknown schema type: %s',
                                        $writers_schema->type()));
    }
  }
}

/**
 * Decodes and reads Avro data from an AvroIO object encoded using
 * Avro binary encoding.
 *
 * @package Avro
 */
class AvroIOBinaryDecoder
{

  /**
   * @param int[] array of byte ascii values
   * @returns long decoded value
   * @internal Requires 64-bit platform
   */
  public static function decode_long_from_array($bytes)
  {
    $b = array_shift($bytes);
    $n = $b & 0x7f;
    $shift = 7;
    while (0 != ($b & 0x80))
    {
      $b = array_shift($bytes);
      $n |= (($b & 0x7f) << $shift);
      $shift += 7;
    }
    return (($n >> 1) ^ -($n & 1));
  }

  /**
   * Performs decoding of the binary string to a float value.
   *
   * XXX: This is <b>not</b> endian-aware! See comments in
   * {@link AvroIOBinaryEncoder::float_to_int_bits()} for details.
   *
   * @param string $bits
   * @returns float
   */
  static public function int_bits_to_float($bits)
  {
    $float = unpack('f', $bits);
    return (float) $float[1];
  }

  /**
   * Performs decoding of the binary string to a double value.
   *
   * XXX: This is <b>not</b> endian-aware! See comments in
   * {@link AvroIOBinaryEncoder::float_to_int_bits()} for details.
   *
   * @param string $bits
   * @returns float
   */
  static public function long_bits_to_double($bits)
  {
    $double = unpack('d', $bits);
    return (double) $double[1];
  }

  /**
   * @var AvroIO
   */
  private $io;

  /**
   * @param AvroIO $io object from which to read.
   */
  public function __construct($io)
  {
    Avro::check_platform();
    $this->io = $io;
  }

  /**
   * @returns string the next byte from $this->io.
   * @throws AvroException if the next byte cannot be read.
   */
  private function next_byte() { return $this->read(1); }

  /**
   * @returns null
   */
  public function read_null() { return null; }

  /**
   * @returns boolean
   */
  public function read_boolean()
  {
    return (boolean) (1 == ord($this->next_byte()));
  }

  /**
   * @returns int
   */
  public function read_int() { return (int) $this->read_long(); }

  /**
   * @returns long
   */
  public function read_long()
  {
    $byte = ord($this->next_byte());
    $bytes = array($byte);
    while (0 != ($byte & 0x80))
    {
      $byte = ord($this->next_byte());
      $bytes []= $byte;
    }

    if (Avro::uses_gmp())
      return AvroGMP::decode_long_from_array($bytes);

    return self::decode_long_from_array($bytes);
  }

  /**
   * @returns float
   */
  public function read_float()
  {
    return self::int_bits_to_float($this->read(4));
  }

  /**
   * @returns double
   */
  public function read_double()
  {
    return self::long_bits_to_double($this->read(8));
  }

  /**
   * A string is encoded as a long followed by that many bytes
   * of UTF-8 encoded character data.
   * @returns string
   */
  public function read_string() { return $this->read_bytes(); }

  /**
   * @returns string
   */
  public function read_bytes() { return $this->read($this->read_long()); }

  /**
   * @param int $len count of bytes to read
   * @returns string
   */
  public function read($len) { return $this->io->read($len); }

  public function skip_null() { return null; }

  public function skip_boolean() { return $this->skip(1); }

  public function skip_int() { return $this->skip_long(); }

  protected function skip_long()
  {
    $b = $this->next_byte();
    while (0 != ($b & 0x80))
      $b = $this->next_byte();
  }

  public function skip_float() { return $this->skip(4); }

  public function skip_double() { return $this->skip(8); }

  public function skip_bytes() { return $this->skip($this->read_long()); }

  public function skip_string() { return $this->skip_bytes(); }

  /**
   * @param int $len count of bytes to skip
   * @uses AvroIO::seek()
   */
  public function skip($len) { $this->seek($len, AvroIO::SEEK_CUR); }

  /**
   * @returns int position of pointer in AvroIO instance
   * @uses AvroIO::tell()
   */
  private function tell() { return $this->io->tell(); }

  /**
   * @param int $offset
   * @param int $whence
   * @returns boolean true upon success
   * @uses AvroIO::seek()
   */
  private function seek($offset, $whence)
  {
    return $this->io->seek($offset, $whence);
  }
}

