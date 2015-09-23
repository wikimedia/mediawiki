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
 * Avro Schema and and Avro Schema support classes.
 * @package Avro
 */

/** TODO
 * - ARRAY have only type and item attributes (what about metadata?)
 * - MAP keys are (assumed?) to be strings
 * - FIXED size must be integer (must be positive? less than MAXINT?)
 * - primitive type names cannot have a namespace (so throw an error? or ignore?)
 * - schema may contain multiple definitions of a named schema
 *   if definitions are equivalent (?)
 *  - Cleanup default namespace and named schemata handling.
 *     - For one, it appears to be *too* global. According to the spec,
 *       we should only be referencing schemas that are named within the
 *       *enclosing* schema, so those in sibling schemas (say, unions or fields)
 *       shouldn't be referenced, if I understand the spec correctly.
 *     - Also, if a named schema is defined more than once in the same schema,
 *       it must have the same definition: so it appears we *do* need to keep
 *       track of named schemata globally as well. (And does this play well
 *       with the requirements regarding enclosing schema?
 *  - default values for bytes and fixed fields are JSON strings,
 *    where unicode code points 0-255 are mapped to unsigned 8-bit byte values 0-255
 *  - make sure other default values for other schema are of appropriate type
 *  - Should AvroField really be an AvroSchema object? Avro Fields have a name
 *    attribute, but not a namespace attribute (and the name can't be namespace
 *    qualified). It also has additional attributes such as doc, which named schemas
 *    enum and record have (though not fixed schemas, which also have names), and
 *    fields also have default and order attributes, shared by no other schema type.
 */

/**
 * Exceptions associated with parsing JSON schema represenations
 * @package Avro
 */
class AvroSchemaParseException extends AvroException {};

/**
 * @package Avro
 */
class AvroSchema
{
  /**
   * @var int lower bound of integer values: -(1 << 31)
   */
  const INT_MIN_VALUE = -2147483648;

  /**
   * @var int upper bound of integer values: (1 << 31) - 1
   */
  const INT_MAX_VALUE = 2147483647;

  /**
   * @var long lower bound of long values: -(1 << 63)
   */
  const LONG_MIN_VALUE = -9223372036854775808;

  /**
   * @var long upper bound of long values: (1 << 63) - 1
   */
  const LONG_MAX_VALUE =  9223372036854775807;

  /**
   * @var string null schema type name
   */
  const NULL_TYPE = 'null';

  /**
   * @var string boolean schema type name
   */
  const BOOLEAN_TYPE = 'boolean';

  /**
   * int schema type value is a 32-bit signed int
   * @var string int schema type name.
   */
  const INT_TYPE = 'int';

  /**
   * long schema type value is a 64-bit signed int
   * @var string long schema type name
   */
  const LONG_TYPE = 'long';

  /**
   * float schema type value is a 32-bit IEEE 754 floating-point number
   * @var string float schema type name
   */
  const FLOAT_TYPE = 'float';

  /**
   * double schema type value is a 64-bit IEEE 754 floating-point number
   * @var string double schema type name
   */
  const DOUBLE_TYPE = 'double';

  /**
   * string schema type value is a Unicode character sequence
   * @var string string schema type name
   */
  const STRING_TYPE = 'string';

  /**
   * bytes schema type value is a sequence of 8-bit unsigned bytes
   * @var string bytes schema type name
   */
  const BYTES_TYPE = 'bytes';

  // Complex Types
  // Unnamed Schema
  /**
   * @var string array schema type name
   */
  const ARRAY_SCHEMA = 'array';

  /**
   * @var string map schema type name
   */
  const MAP_SCHEMA = 'map';

  /**
   * @var string union schema type name
   */
  const UNION_SCHEMA = 'union';

  /**
   * Unions of error schemas are used by Avro messages
   * @var string error_union schema type name
   */
  const ERROR_UNION_SCHEMA = 'error_union';

  // Named Schema

  /**
   * @var string enum schema type name
   */
  const ENUM_SCHEMA = 'enum';

  /**
   * @var string fixed schema type name
   */
  const FIXED_SCHEMA = 'fixed';

  /**
   * @var string record schema type name
   */
  const RECORD_SCHEMA = 'record';
  // Other Schema

  /**
   * @var string error schema type name
   */
  const ERROR_SCHEMA = 'error';

  /**
   * @var string request schema type name
   */
  const REQUEST_SCHEMA = 'request';


  // Schema attribute names
  /**
   * @var string schema type name attribute name
   */
  const TYPE_ATTR = 'type';

  /**
   * @var string named schema name attribute name
   */
  const NAME_ATTR = 'name';

  /**
   * @var string named schema namespace attribute name
   */
  const NAMESPACE_ATTR = 'namespace';

  /**
   * @var string derived attribute: doesn't appear in schema
   */
  const FULLNAME_ATTR = 'fullname';

  /**
   * @var string array schema size attribute name
   */
  const SIZE_ATTR = 'size';

  /**
   * @var string record fields attribute name
   */
  const FIELDS_ATTR = 'fields';

  /**
   * @var string array schema items attribute name
   */
  const ITEMS_ATTR = 'items';

  /**
   * @var string enum schema symbols attribute name
   */
  const SYMBOLS_ATTR = 'symbols';

  /**
   * @var string map schema values attribute name
   */
  const VALUES_ATTR = 'values';

  /**
   * @var string document string attribute name
   */
  const DOC_ATTR = 'doc';

  /**
   * @var array list of primitive schema type names
   */
  private static $primitive_types = array(self::NULL_TYPE, self::BOOLEAN_TYPE,
                                          self::STRING_TYPE, self::BYTES_TYPE,
                                          self::INT_TYPE, self::LONG_TYPE,
                                          self::FLOAT_TYPE, self::DOUBLE_TYPE);

  /**
   * @var array list of named schema type names
   */
  private static $named_types = array(self::FIXED_SCHEMA, self::ENUM_SCHEMA,
                                      self::RECORD_SCHEMA, self::ERROR_SCHEMA);

  /**
   * @param string $type a schema type name
   * @returns boolean true if the given type name is a named schema type name
   *                  and false otherwise.
   */
  public static function is_named_type($type)
  {
    return in_array($type, self::$named_types);
  }

  /**
   * @param string $type a schema type name
   * @returns boolean true if the given type name is a primitive schema type
   *                  name and false otherwise.
   */
  public static function is_primitive_type($type)
  {
    return in_array($type, self::$primitive_types);
  }

  /**
   * @param string $type a schema type name
   * @returns boolean true if the given type name is a valid schema type
   *                  name and false otherwise.
   */
  public static function is_valid_type($type)
  {
    return (self::is_primitive_type($type)
            || self::is_named_type($type)
            || in_array($type, array(self::ARRAY_SCHEMA,
                                     self::MAP_SCHEMA,
                                     self::UNION_SCHEMA,
                                     self::REQUEST_SCHEMA,
                                     self::ERROR_UNION_SCHEMA)));
  }

  /**
   * @var array list of names of reserved attributes
   */
  private static $reserved_attrs = array(self::TYPE_ATTR,
                                         self::NAME_ATTR,
                                         self::NAMESPACE_ATTR,
                                         self::FIELDS_ATTR,
                                         self::ITEMS_ATTR,
                                         self::SIZE_ATTR,
                                         self::SYMBOLS_ATTR,
                                         self::VALUES_ATTR);

  /**
   * @param string $json JSON-encoded schema
   * @uses self::real_parse()
   * @returns AvroSchema
   */
  public static function parse($json)
  {
    $schemata = new AvroNamedSchemata();
    return self::real_parse(json_decode($json, true), null, $schemata);
  }

  /**
   * @param mixed $avro JSON-decoded schema
   * @param string $default_namespace namespace of enclosing schema
   * @param AvroNamedSchemata &$schemata reference to named schemas
   * @returns AvroSchema
   * @throws AvroSchemaParseException
   */
  static function real_parse($avro, $default_namespace=null, &$schemata=null)
  {
    if (is_null($schemata))
      $schemata = new AvroNamedSchemata();

    if (is_array($avro))
    {
      $type = AvroUtil::array_value($avro, self::TYPE_ATTR);

      if (self::is_primitive_type($type))
        return new AvroPrimitiveSchema($type);

      elseif (self::is_named_type($type))
      {
        $name = AvroUtil::array_value($avro, self::NAME_ATTR);
        $namespace = AvroUtil::array_value($avro, self::NAMESPACE_ATTR);
        $new_name = new AvroName($name, $namespace, $default_namespace);
        $doc = AvroUtil::array_value($avro, self::DOC_ATTR);
        switch ($type)
        {
          case self::FIXED_SCHEMA:
            $size = AvroUtil::array_value($avro, self::SIZE_ATTR);
            return new AvroFixedSchema($new_name, $doc,
                                       $size,
                                       $schemata);
          case self::ENUM_SCHEMA:
            $symbols = AvroUtil::array_value($avro, self::SYMBOLS_ATTR);
            return new AvroEnumSchema($new_name, $doc,
                                      $symbols,
                                      $schemata);
          case self::RECORD_SCHEMA:
          case self::ERROR_SCHEMA:
            $fields = AvroUtil::array_value($avro, self::FIELDS_ATTR);
            return new AvroRecordSchema($new_name, $doc,
                                        $fields,
                                        $schemata, $type);
          default:
            throw new AvroSchemaParseException(
              sprintf('Unknown named type: %s', $type));
        }
      }
      elseif (self::is_valid_type($type))
      {
        switch ($type)
        {
          case self::ARRAY_SCHEMA:
            return new AvroArraySchema($avro[self::ITEMS_ATTR],
                                       $default_namespace,
                                       $schemata);
          case self::MAP_SCHEMA:
            return new AvroMapSchema($avro[self::VALUES_ATTR],
                                     $default_namespace,
                                     $schemata);
          default:
            throw new AvroSchemaParseException(
              sprintf('Unknown valid type: %s', $type));
        }
      }
      elseif (!array_key_exists(self::TYPE_ATTR, $avro)
              && AvroUtil::is_list($avro))
        return new AvroUnionSchema($avro, $default_namespace, $schemata);
      else
        throw new AvroSchemaParseException(sprintf('Undefined type: %s',
                                                   $type));
    }
    elseif (self::is_primitive_type($avro))
      return new AvroPrimitiveSchema($avro);
    else
      throw new AvroSchemaParseException(
        sprintf('%s is not a schema we know about.',
                print_r($avro, true)));
  }

  /**
   * @returns boolean true if $datum is valid for $expected_schema
   *                  and false otherwise.
   * @throws AvroSchemaParseException
   */
  public static function is_valid_datum($expected_schema, $datum)
  {
    switch($expected_schema->type)
    {
      case self::NULL_TYPE:
        return is_null($datum);
      case self::BOOLEAN_TYPE:
        return is_bool($datum);
      case self::STRING_TYPE:
      case self::BYTES_TYPE:
        return is_string($datum);
      case self::INT_TYPE:
        return (is_int($datum)
                && (self::INT_MIN_VALUE <= $datum)
                && ($datum <= self::INT_MAX_VALUE));
      case self::LONG_TYPE:
        return (is_int($datum)
                && (self::LONG_MIN_VALUE <= $datum)
                && ($datum <= self::LONG_MAX_VALUE));
      case self::FLOAT_TYPE:
      case self::DOUBLE_TYPE:
        return (is_float($datum) || is_int($datum));
      case self::ARRAY_SCHEMA:
        if (is_array($datum))
        {
          foreach ($datum as $d)
            if (!self::is_valid_datum($expected_schema->items(), $d))
              return false;
          return true;
        }
        return false;
      case self::MAP_SCHEMA:
        if (is_array($datum))
        {
          foreach ($datum as $k => $v)
            if (!is_string($k)
                || !self::is_valid_datum($expected_schema->values(), $v))
              return false;
          return true;
        }
        return false;
      case self::UNION_SCHEMA:
        foreach ($expected_schema->schemas() as $schema)
          if (self::is_valid_datum($schema, $datum))
            return true;
        return false;
      case self::ENUM_SCHEMA:
        return in_array($datum, $expected_schema->symbols());
      case self::FIXED_SCHEMA:
        return (is_string($datum)
                && (strlen($datum) == $expected_schema->size()));
      case self::RECORD_SCHEMA:
      case self::ERROR_SCHEMA:
      case self::REQUEST_SCHEMA:
        if (is_array($datum))
        {
          foreach ($expected_schema->fields() as $field)
            if (!array_key_exists($field->name(), $datum) || !self::is_valid_datum($field->type(), $datum[$field->name()]))
              return false;
          return true;
        }
        return false;
      default:
        throw new AvroSchemaParseException(
          sprintf('%s is not allowed.', $expected_schema));
    }
  }

  /**
   * @internal Should only be called from within the constructor of
   *           a class which extends AvroSchema
   * @param string $type a schema type name
   */
  public function __construct($type)
  {
    $this->type = $type;
  }

  /**
   * @param mixed $avro
   * @param string $default_namespace namespace of enclosing schema
   * @param AvroNamedSchemata &$schemata
   * @returns AvroSchema
   * @uses AvroSchema::real_parse()
   * @throws AvroSchemaParseException
   */
  protected static function subparse($avro, $default_namespace, &$schemata=null)
  {
    try
    {
      return self::real_parse($avro, $default_namespace, $schemata);
    }
    catch (AvroSchemaParseException $e)
    {
      throw $e;
    }
    catch (Exception $e)
    {
      throw new AvroSchemaParseException(
        sprintf('Sub-schema is not a valid Avro schema. Bad schema: %s',
                print_r($avro, true)));
    }

  }

  /**
   * @returns string schema type name of this schema
   */
  public function type() { return $this->type;  }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    return array(self::TYPE_ATTR => $this->type);
  }

  /**
   * @returns string the JSON-encoded representation of this Avro schema.
   */
  public function __toString() { return json_encode($this->to_avro()); }

  /**
   * @returns mixed value of the attribute with the given attribute name
   */
  public function attribute($attribute) { return $this->$attribute(); }

}

/**
 * Avro schema for basic types such as null, int, long, string.
 * @package Avro
 */
class AvroPrimitiveSchema extends AvroSchema
{

  /**
   * @param string $type the primitive schema type name
   * @throws AvroSchemaParseException if the given $type is not a
   *         primitive schema type name
   */
  public function __construct($type)
  {
    if (self::is_primitive_type($type))
      return parent::__construct($type);
    throw new AvroSchemaParseException(
      sprintf('%s is not a valid primitive type.', $type));
  }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = parent::to_avro();
    // FIXME: Is this if really necessary? When *wouldn't* this be the case?
    if (1 == count($avro))
      return $this->type;
    return $avro;
  }
}

/**
 * Avro array schema, consisting of items of a particular
 * Avro schema type.
 * @package Avro
 */
class AvroArraySchema extends AvroSchema
{
  /**
   * @var AvroName|AvroSchema named schema name or AvroSchema of
   *                          array element
   */
  private $items;

  /**
   * @var boolean true if the items schema
   * FIXME: couldn't we derive this from whether or not $this->items
   *        is an AvroName or an AvroSchema?
   */
  private $is_items_schema_from_schemata;

  /**
   * @param string|mixed $items AvroNamedSchema name or object form
   *        of decoded JSON schema representation.
   * @param string $default_namespace namespace of enclosing schema
   * @param AvroNamedSchemata &$schemata
   */
  public function __construct($items, $default_namespace, &$schemata=null)
  {
    parent::__construct(AvroSchema::ARRAY_SCHEMA);

    $this->is_items_schema_from_schemata = false;
    $items_schema = null;
    if (is_string($items)
        && $items_schema = $schemata->schema_by_name(
          new AvroName($items, null, $default_namespace)))
      $this->is_items_schema_from_schemata = true;
    else
      $items_schema = AvroSchema::subparse($items, $default_namespace, $schemata);

    $this->items = $items_schema;
  }


  /**
   * @returns AvroName|AvroSchema named schema name or AvroSchema
   *          of this array schema's elements.
   */
  public function items() { return $this->items; }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = parent::to_avro();
    $avro[AvroSchema::ITEMS_ATTR] = $this->is_items_schema_from_schemata
      ? $this->items->qualified_name() : $this->items->to_avro();
    return $avro;
  }
}

/**
 * Avro map schema consisting of named values of defined
 * Avro Schema types.
 * @package Avro
 */
class AvroMapSchema extends AvroSchema
{
  /**
   * @var string|AvroSchema named schema name or AvroSchema
   *      of map schema values.
   */
  private $values;

  /**
   * @var boolean true if the named schema
   * XXX Couldn't we derive this based on whether or not
   * $this->values is a string?
   */
  private $is_values_schema_from_schemata;

  /**
   * @param string|AvroSchema $values
   * @param string $default_namespace namespace of enclosing schema
   * @param AvroNamedSchemata &$schemata
   */
  public function __construct($values, $default_namespace, &$schemata=null)
  {
    parent::__construct(AvroSchema::MAP_SCHEMA);

    $this->is_values_schema_from_schemata = false;
    $values_schema = null;
    if (is_string($values)
        && $values_schema = $schemata->schema_by_name(
          new AvroName($values, null, $default_namespace)))
      $this->is_values_schema_from_schemata = true;
    else
      $values_schema = AvroSchema::subparse($values, $default_namespace,
                                            $schemata);

    $this->values = $values_schema;
  }

  /**
   * @returns XXX|AvroSchema
   */
  public function values() { return $this->values; }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = parent::to_avro();
    $avro[AvroSchema::VALUES_ATTR] = $this->is_values_schema_from_schemata
      ? $this->values->qualified_name() : $this->values->to_avro();
    return $avro;
  }
}

/**
 * Union of Avro schemas, of which values can be of any of the schema in
 * the union.
 * @package Avro
 */
class AvroUnionSchema extends AvroSchema
{
  /**
   * @var AvroSchema[] list of schemas of this union
   */
  private $schemas;

  /**
   * @var int[] list of indices of named schemas which
   *                are defined in $schemata
   */
  public $schema_from_schemata_indices;

  /**
   * @param AvroSchema[] $schemas list of schemas in the union
   * @param string $default_namespace namespace of enclosing schema
   * @param AvroNamedSchemata &$schemata
   */
  public function __construct($schemas, $default_namespace, &$schemata=null)
  {
    parent::__construct(AvroSchema::UNION_SCHEMA);

    $this->schema_from_schemata_indices = array();
    $schema_types = array();
    foreach ($schemas as $index => $schema)
    {
      $is_schema_from_schemata = false;
      $new_schema = null;
      if (is_string($schema)
          && ($new_schema = $schemata->schema_by_name(
                new AvroName($schema, null, $default_namespace))))
        $is_schema_from_schemata = true;
      else
        $new_schema = self::subparse($schema, $default_namespace, $schemata);

      $schema_type = $new_schema->type;
      if (self::is_valid_type($schema_type)
          && !self::is_named_type($schema_type)
          && in_array($schema_type, $schema_types))
        throw new AvroSchemaParseException(
          sprintf('"%s" is already in union', $schema_type));
      elseif (AvroSchema::UNION_SCHEMA == $schema_type)
        throw new AvroSchemaParseException('Unions cannot contain other unions');
      else
      {
        $schema_types []= $schema_type;
        $this->schemas []= $new_schema;
        if ($is_schema_from_schemata)
          $this->schema_from_schemata_indices []= $index;
      }
    }

  }

  /**
   * @returns AvroSchema[]
   */
  public function schemas() { return $this->schemas; }

  /**
   * @returns AvroSchema the particular schema from the union for
   * the given (zero-based) index.
   * @throws AvroSchemaParseException if the index is invalid for this schema.
   */
  public function schema_by_index($index)
  {
    if (count($this->schemas) > $index)
      return $this->schemas[$index];

    throw new AvroSchemaParseException('Invalid union schema index');
  }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = array();

    foreach ($this->schemas as $index => $schema)
      $avro []= (in_array($index, $this->schema_from_schemata_indices))
      ? $schema->qualified_name() : $schema->to_avro();

    return $avro;
  }
}

/**
 * Parent class of named Avro schema
 * @package Avro
 * @todo Refactor AvroNamedSchema to use an AvroName instance
 *       to store name information.
 */
class AvroNamedSchema extends AvroSchema
{
  /**
   * @var AvroName $name
   */
  private $name;

  /**
   * @var string documentation string
   */
  private $doc;

  /**
   * @param string $type
   * @param AvroName $name
   * @param string $doc documentation string
   * @param AvroNamedSchemata &$schemata
   * @throws AvroSchemaParseException
   */
  public function __construct($type, $name, $doc=null, &$schemata=null)
  {
    parent::__construct($type);
    $this->name = $name;

    if ($doc && !is_string($doc))
      throw new AvroSchemaParseException('Schema doc attribute must be a string');
    $this->doc = $doc;

    if (!is_null($schemata))
      $schemata = $schemata->clone_with_new_schema($this);
  }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = parent::to_avro();
    list($name, $namespace) = AvroName::extract_namespace($this->qualified_name());
    $avro[AvroSchema::NAME_ATTR] = $name;
    if ($namespace)
      $avro[AvroSchema::NAMESPACE_ATTR] = $namespace;
    if (!is_null($this->doc))
      $avro[AvroSchema::DOC_ATTR] = $this->doc;
    return $avro;
  }

  /**
   * @returns string
   */
  public function fullname() { return $this->name->fullname(); }

  public function qualified_name() { return $this->name->qualified_name(); }

}

/**
 * @package Avro
 */
class AvroName
{
  /**
   * @var string character used to separate names comprising the fullname
   */
  const NAME_SEPARATOR = '.';

  /**
   * @var string regular expression to validate name values
   */
  const NAME_REGEXP = '/^[A-Za-z_][A-Za-z0-9_]*$/';

  /**
   * @returns string[] array($name, $namespace)
   */
  public static function extract_namespace($name, $namespace=null)
  {
    $parts = explode(self::NAME_SEPARATOR, $name);
    if (count($parts) > 1)
    {
      $name = array_pop($parts);
      $namespace = join(self::NAME_SEPARATOR, $parts);
    }
    return array($name, $namespace);
  }

  /**
   * @returns boolean true if the given name is well-formed
   *          (is a non-null, non-empty string) and false otherwise
   */
  public static function is_well_formed_name($name)
  {
    return (is_string($name) && !empty($name)
            && preg_match(self::NAME_REGEXP, $name));
  }

  /**
   * @param string $namespace
   * @returns boolean true if namespace is composed of valid names
   * @throws AvroSchemaParseException if any of the namespace components
   *                                  are invalid.
   */
  private static function check_namespace_names($namespace)
  {
    foreach (explode(self::NAME_SEPARATOR, $namespace) as $n)
    {
      if (empty($n) || (0 == preg_match(self::NAME_REGEXP, $n)))
        throw new AvroSchemaParseException(sprintf('Invalid name "%s"', $n));
    }
    return true;
  }

  /**
   * @param string $name
   * @param string $namespace
   * @returns string
   * @throws AvroSchemaParseException if any of the names are not valid.
   */
  private static function parse_fullname($name, $namespace)
  {
    if (!is_string($namespace) || empty($namespace))
      throw new AvroSchemaParseException('Namespace must be a non-empty string.');
    self::check_namespace_names($namespace);
    return $namespace . '.' . $name;
  }

  /**
   * @var string valid names are matched by self::NAME_REGEXP
   */
  private $name;

  /**
   * @var string
   */
  private $namespace;

  /**
   * @var string
   */
  private $fullname;

  /**
   * @var string Name qualified as necessary given its default namespace.
   */
  private $qualified_name;

  /**
   * @param string $name
   * @param string $namespace
   * @param string $default_namespace
   */
  public function __construct($name, $namespace, $default_namespace)
  {
    if (!is_string($name) || empty($name))
      throw new AvroSchemaParseException('Name must be a non-empty string.');

    if (strpos($name, self::NAME_SEPARATOR)
        && self::check_namespace_names($name))
      $this->fullname = $name;
    elseif (0 == preg_match(self::NAME_REGEXP, $name))
      throw new AvroSchemaParseException(sprintf('Invalid name "%s"', $name));
    elseif (!is_null($namespace))
      $this->fullname = self::parse_fullname($name, $namespace);
    elseif (!is_null($default_namespace))
      $this->fullname = self::parse_fullname($name, $default_namespace);
    else
      $this->fullname = $name;

    list($this->name, $this->namespace) = self::extract_namespace($this->fullname);
    $this->qualified_name = (is_null($this->namespace)
                             || $this->namespace == $default_namespace)
      ? $this->name : $this->fullname;
  }

  /**
   * @returns array array($name, $namespace)
   */
  public function name_and_namespace()
  {
    return array($this->name, $this->namespace);
  }

  /**
   * @returns string
   */
  public function fullname() { return $this->fullname; }

  /**
   * @returns string fullname
   * @uses $this->fullname()
   */
  public function __toString() { return $this->fullname(); }

  /**
   * @returns string name qualified for its context
   */
  public function qualified_name() { return $this->qualified_name; }

}

/**
 *  Keeps track of AvroNamedSchema which have been observed so far,
 *  as well as the default namespace.
 *
 * @package Avro
 */
class AvroNamedSchemata
{
  /**
   * @var AvroNamedSchema[]
   */
  private $schemata;

  /**
   * @param AvroNamedSchemata[]
   */
  public function __construct($schemata=array())
  {
    $this->schemata = $schemata;
  }

  public function list_schemas() {
    var_export($this->schemata);
    foreach($this->schemata as $sch) 
      print('Schema '.$sch->__toString()."\n");
  }

  /**
   * @param string $fullname
   * @returns boolean true if there exists a schema with the given name
   *                  and false otherwise.
   */
  public function has_name($fullname)
  {
    return array_key_exists($fullname, $this->schemata);
  }

  /**
   * @param string $fullname
   * @returns AvroSchema|null the schema which has the given name,
   *          or null if there is no schema with the given name.
   */
  public function schema($fullname)
  {
    if (isset($this->schemata[$fullname]))
        return $this->schemata[$fullname];
    return null;
  }

  /**
   * @param AvroName $name
   * @returns AvroSchema|null
   */
  public function schema_by_name($name)
  {
    return $this->schema($name->fullname());
  }

  /**
   * Creates a new AvroNamedSchemata instance of this schemata instance
   * with the given $schema appended.
   * @param AvroNamedSchema schema to add to this existing schemata
   * @returns AvroNamedSchemata
   */
  public function clone_with_new_schema($schema)
  {
    $name = $schema->fullname();
    if (AvroSchema::is_valid_type($name))
      throw new AvroSchemaParseException(
        sprintf('Name "%s" is a reserved type name', $name));
    else if ($this->has_name($name))
      throw new AvroSchemaParseException(
        sprintf('Name "%s" is already in use', $name));
    $schemata = new AvroNamedSchemata($this->schemata);
    $schemata->schemata[$name] = $schema;
    return $schemata;
  }
}

/**
 * @package Avro
 */
class AvroEnumSchema extends AvroNamedSchema
{
  /**
   * @var string[] array of symbols
   */
  private $symbols;

  /**
   * @param AvroName $name
   * @param string $doc
   * @param string[] $symbols
   * @param AvroNamedSchemata &$schemata
   * @throws AvroSchemaParseException
   */
  public function __construct($name, $doc, $symbols, &$schemata=null)
  {
    if (!AvroUtil::is_list($symbols))
      throw new AvroSchemaParseException('Enum Schema symbols are not a list');

    if (count(array_unique($symbols)) > count($symbols))
      throw new AvroSchemaParseException(
        sprintf('Duplicate symbols: %s', $symbols));

    foreach ($symbols as $symbol)
      if (!is_string($symbol) || empty($symbol))
        throw new AvroSchemaParseException(
          sprintf('Enum schema symbol must be a string %',
                  print_r($symbol, true)));

    parent::__construct(AvroSchema::ENUM_SCHEMA, $name, $doc, $schemata);
    $this->symbols = $symbols;
  }

  /**
   * @returns string[] this enum schema's symbols
   */
  public function symbols() { return $this->symbols; }

  /**
   * @param string $symbol
   * @returns boolean true if the given symbol exists in this
   *          enum schema and false otherwise
   */
  public function has_symbol($symbol)
  {
    return in_array($symbol, $this->symbols);
  }

  /**
   * @param int $index
   * @returns string enum schema symbol with the given (zero-based) index
   */
  public function symbol_by_index($index)
  {
    if (array_key_exists($index, $this->symbols))
      return $this->symbols[$index];
    throw new AvroException(sprintf('Invalid symbol index %d', $index));
  }

  /**
   * @param string $symbol
   * @returns int the index of the given $symbol in the enum schema
   */
  public function symbol_index($symbol)
  {
    $idx = array_search($symbol, $this->symbols, true);
    if (false !== $idx)
      return $idx;
    throw new AvroException(sprintf("Invalid symbol value '%s'", $symbol));
  }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = parent::to_avro();
    $avro[AvroSchema::SYMBOLS_ATTR] = $this->symbols;
    return $avro;
  }
}

/**
 * AvroNamedSchema with fixed-length data values
 * @package Avro
 */
class AvroFixedSchema extends AvroNamedSchema
{

  /**
   * @var int byte count of this fixed schema data value
   */
  private $size;

  /**
   * @param AvroName $name
   * @param string $doc Set to null, as fixed schemas don't have doc strings
   * @param int $size byte count of this fixed schema data value
   * @param AvroNamedSchemata &$schemata
   */
  public function __construct($name, $doc, $size, &$schemata=null)
  {
    $doc = null; // Fixed schemas don't have doc strings.
    if (!is_integer($size))
      throw new AvroSchemaParseException(
        'Fixed Schema requires a valid integer for "size" attribute');
    parent::__construct(AvroSchema::FIXED_SCHEMA, $name, $doc, $schemata);
    return $this->size = $size;
  }

  /**
   * @returns int byte count of this fixed schema data value
   */
  public function size() { return $this->size; }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = parent::to_avro();
    $avro[AvroSchema::SIZE_ATTR] = $this->size;
    return $avro;
  }
}

/**
 * @package Avro
 */
class AvroRecordSchema extends AvroNamedSchema
{
  /**
   * @param mixed $field_data
   * @param string $default_namespace namespace of enclosing schema
   * @param AvroNamedSchemata &$schemata
   * @returns AvroField[]
   * @throws AvroSchemaParseException
   */
  static function parse_fields($field_data, $default_namespace, &$schemata)
  {
    $fields = array();
    $field_names = array();
    foreach ($field_data as $index => $field)
    {
      $name = AvroUtil::array_value($field, AvroField::FIELD_NAME_ATTR);
      $type = AvroUtil::array_value($field, AvroSchema::TYPE_ATTR);
      $order = AvroUtil::array_value($field, AvroField::ORDER_ATTR);

      $default = null;
      $has_default = false;
      if (array_key_exists(AvroField::DEFAULT_ATTR, $field))
      {
        $default = $field[AvroField::DEFAULT_ATTR];
        $has_default = true;
      }

      if (in_array($name, $field_names))
        throw new AvroSchemaParseException(
          sprintf("Field name %s is already in use", $name));

      $is_schema_from_schemata = false;
      $field_schema = null;
      if (is_string($type)
          && $field_schema = $schemata->schema_by_name(
            new AvroName($type, null, $default_namespace)))
        $is_schema_from_schemata = true;
      else
        $field_schema = self::subparse($type, $default_namespace, $schemata);

      $new_field = new AvroField($name, $field_schema, $is_schema_from_schemata,
                                 $has_default, $default, $order);
      $field_names []= $name;
      $fields []= $new_field;
    }
    return $fields;
  }

  /**
   * @var AvroSchema[] array of AvroNamedSchema field definitions of
   *                   this AvroRecordSchema
   */
  private $fields;

  /**
   * @var array map of field names to field objects.
   * @internal Not called directly. Memoization of AvroRecordSchema->fields_hash()
   */
  private $fields_hash;

  /**
   * @param string $name
   * @param string $namespace
   * @param string $doc
   * @param array $fields
   * @param AvroNamedSchemata &$schemata
   * @param string $schema_type schema type name
   * @throws AvroSchemaParseException
   */
  public function __construct($name, $doc, $fields, &$schemata=null,
                              $schema_type=AvroSchema::RECORD_SCHEMA)
  {
    if (is_null($fields))
      throw new AvroSchemaParseException(
        'Record schema requires a non-empty fields attribute');

    if (AvroSchema::REQUEST_SCHEMA == $schema_type)
      parent::__construct($schema_type, $name); 
    else
      parent::__construct($schema_type, $name, $doc, $schemata);

    list($x, $namespace) = $name->name_and_namespace();
    $this->fields = self::parse_fields($fields, $namespace, $schemata);
  }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = parent::to_avro();

    $fields_avro = array();
    foreach ($this->fields as $field)
      $fields_avro [] = $field->to_avro();

    if (AvroSchema::REQUEST_SCHEMA == $this->type)
      return $fields_avro;

    $avro[AvroSchema::FIELDS_ATTR] = $fields_avro;

    return $avro;
  }

  /**
   * @returns array the schema definitions of the fields of this AvroRecordSchema
   */
  public function fields() { return $this->fields; }

  /**
   * @returns array a hash table of the fields of this AvroRecordSchema fields
   *          keyed by each field's name
   */
  public function fields_hash()
  {
    if (is_null($this->fields_hash))
    {
      $hash = array();
      foreach ($this->fields as $field)
        $hash[$field->name()] = $field;
      $this->fields_hash = $hash;
    }
    return $this->fields_hash;
  }
}

/**
 * Field of an {@link AvroRecordSchema}
 * @package Avro
 */
class AvroField extends AvroSchema
{

  /**
   * @var string fields name attribute name
   */
  const FIELD_NAME_ATTR = 'name';

  /**
   * @var string
   */
  const DEFAULT_ATTR = 'default';

  /**
   * @var string
   */
  const ORDER_ATTR = 'order';

  /**
   * @var string
   */
  const ASC_SORT_ORDER = 'ascending';

  /**
   * @var string
   */
  const DESC_SORT_ORDER = 'descending';

  /**
   * @var string
   */
  const IGNORE_SORT_ORDER = 'ignore';

  /**
   * @var array list of valid field sort order values
   */
  private static $valid_field_sort_orders = array(self::ASC_SORT_ORDER,
                                                  self::DESC_SORT_ORDER,
                                                  self::IGNORE_SORT_ORDER);


  /**
   * @param string $order
   * @returns boolean
   */
  private static function is_valid_field_sort_order($order)
  {
    return in_array($order, self::$valid_field_sort_orders);
  }

  /**
   * @param string $order
   * @throws AvroSchemaParseException if $order is not a valid
   *                                  field order value.
   */
  private static function check_order_value($order)
  {
    if (!is_null($order) && !self::is_valid_field_sort_order($order))
      throw new AvroSchemaParseException(
        sprintf('Invalid field sort order %s', $order));
  }

  /**
   * @var string
   */
  private $name;

  /**
   * @var boolean whether or no there is a default value
   */
  private $has_default;

  /**
   * @var string field default value
   */
  private $default;

  /**
   * @var string sort order of this field
   */
  private $order;

  /**
   * @var boolean whether or not the AvroNamedSchema of this field is
   *              defined in the AvroNamedSchemata instance
   */
  private $is_type_from_schemata;

  /**
   * @param string $type
   * @param string $name
   * @param AvroSchema $schema
   * @param boolean $is_type_from_schemata
   * @param string $default
   * @param string $order
   * @todo Check validity of $default value
   * @todo Check validity of $order value
   */
  public function __construct($name, $schema, $is_type_from_schemata,
                              $has_default, $default, $order=null)
  {
    if (!AvroName::is_well_formed_name($name))
      throw new AvroSchemaParseException('Field requires a "name" attribute');

    $this->type = $schema;
    $this->is_type_from_schemata = $is_type_from_schemata;
    $this->name = $name;
    $this->has_default = $has_default;
    if ($this->has_default)
      $this->default = $default;
    $this->check_order_value($order);
    $this->order = $order;
  }

  /**
   * @returns mixed
   */
  public function to_avro()
  {
    $avro = array(AvroField::FIELD_NAME_ATTR => $this->name);

    $avro[AvroSchema::TYPE_ATTR] = ($this->is_type_from_schemata)
      ? $this->type->qualified_name() : $this->type->to_avro();

    if (isset($this->default))
      $avro[AvroField::DEFAULT_ATTR] = $this->default;

    if ($this->order)
      $avro[AvroField::ORDER_ATTR] = $this->order;

    return $avro;
  }

  /**
   * @returns string the name of this field
   */
  public function name() { return $this->name; }

  /**
   * @returns mixed the default value of this field
   */
  public function default_value() { return $this->default;  }

  /**
   * @returns boolean true if the field has a default and false otherwise
   */
  public function has_default_value() { return $this->has_default; }
}
