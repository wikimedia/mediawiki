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
 * @package Avro
 */

/**
 * Avro library for protocols
 * @package Avro
 */
class AvroProtocol
{
  public $name;
  public $namespace;
  public $schemata;

  public static function parse($json)
  {
    if (is_null($json))
      throw new AvroProtocolParseException( "Protocol can't be null");

    $protocol = new AvroProtocol();
    $protocol->real_parse(json_decode($json, true));
    return $protocol;
  }

  function real_parse($avro) {
    $this->protocol = $avro["protocol"];
    $this->namespace = $avro["namespace"];
    $this->schemata = new AvroNamedSchemata();
    $this->name = $avro["protocol"];

    if (!is_null($avro["types"])) {
        $types = AvroSchema::real_parse($avro["types"], $this->namespace, $this->schemata);
    }

    if (!is_null($avro["messages"])) {
      foreach ($avro["messages"] as $messageName => $messageAvro) {
        $message = new AvroProtocolMessage($messageName, $messageAvro, $this);
        $this->messages{$messageName} = $message;
      }
    }
  }
}

class AvroProtocolMessage
{
  /**
   * @var AvroRecordSchema $request
   */

  public $request;

  public $response;

  public function __construct($name, $avro, $protocol)
  {
    $this->name = $name;
    $this->request = new AvroRecordSchema(new AvroName($name, null, $protocol->namespace), null, $avro{'request'}, $protocol->schemata, AvroSchema::REQUEST_SCHEMA);

    if (array_key_exists('response', $avro)) {
      $this->response = $protocol->schemata->schema_by_name(new AvroName($avro{'response'}, $protocol->namespace, $protocol->namespace));
      if ($this->response == null)
        $this->response = new AvroPrimitiveSchema($avro{'response'});
    }
  }
}

class AvroProtocolParseException extends AvroException {};
