<?php
/**
 *  Copyright 2016 JetBrains
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
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
 * Helper autocomplete for php redis extension
 * @author Max Kamashev <max.kamashev@gmail.com>
 * @link https://github.com/ukko/phpredis-phpdoc
 *
 * @method echo string $string Sends a string to Redis, which replies with the same string
 *
 * @method  eval( $script, $args = array(), $numKeys = 0 )
 *  Evaluate a LUA script serverside
 *  @param  string  $script
 *  @param  array   $args
 *  @param  int     $numKeys
 *  @return Mixed.  What is returned depends on what the LUA script itself returns, which could be a scalar value
 *  (int/string), or an array. Arrays that are returned can also contain other arrays, if that's how it was set up in
 *  your LUA script.  If there is an error executing the LUA script, the getLastError() function can tell you the
 *  message that came back from Redis (e.g. compile error).
 *  @link   http://redis.io/commands/eval
 *  @example
 *  <pre>
 *  $redis->eval("return 1"); // Returns an integer: 1
 *  $redis->eval("return {1,2,3}"); // Returns Array(1,2,3)
 *  $redis->del('mylist');
 *  $redis->rpush('mylist','a');
 *  $redis->rpush('mylist','b');
 *  $redis->rpush('mylist','c');
 *  // Nested response:  Array(1,2,3,Array('a','b','c'));
 *  $redis->eval("return {1,2,3,redis.call('lrange','mylist',0,-1)}}");
 * </pre>
 *
 */
class Redis
{
    const AFTER                 = 'after';
    const BEFORE                = 'before';

    /**
     * Options
     */
    const OPT_SERIALIZER        = 1;
    const OPT_PREFIX            = 2;
    const OPT_READ_TIMEOUT = 3;
    const OPT_SCAN = 4;

    /**
     * Serializers
     */
    const SERIALIZER_NONE       = 0;
    const SERIALIZER_PHP        = 1;
    const SERIALIZER_IGBINARY   = 2;

    /**
     * Multi
     */
    const ATOMIC = 0;
    const MULTI = 1;
    const PIPELINE = 2;

    /**
     * Type
     */
    const REDIS_NOT_FOUND       = 0;
    const REDIS_STRING          = 1;
    const REDIS_SET             = 2;
    const REDIS_LIST            = 3;
    const REDIS_ZSET            = 4;
    const REDIS_HASH            = 5;


    /**
     * Scan
     */
     const SCAN_NORETRY = 0;
     const SCAN_RETRY = 1;

     /**
     * Creates a Redis client
     *
     * @example $redis = new Redis();
     */
    public function __construct( ) {}

    /**
     * Connects to a Redis instance.
     *
     * @param string    $host       can be a host, or the path to a unix domain socket
     * @param int       $port       optional
     * @param float     $timeout    value in seconds (optional, default is 0.0 meaning unlimited)
     * @return bool                 TRUE on success, FALSE on error.
     * <pre>
     * $redis->connect('127.0.0.1', 6379);
     * $redis->connect('127.0.0.1');            // port 6379 by default
     * $redis->connect('127.0.0.1', 6379, 2.5); // 2.5 sec timeout.
     * $redis->connect('/tmp/redis.sock');      // unix domain socket.
     * </pre>
     */
    public function connect( $host, $port = 6379, $timeout = 0.0 ) {}

    /**
     * Set the string value in argument as value of the key, with a time to live.
     *
     * @param   string $key
     * @param   int $ttl in milliseconds
     * @param   string $value
     * @return  bool:   TRUE if the command is successful.
     * @link    http://redis.io/commands/setex
     * $redis->psetex('key', 100, 'value'); // sets key → value, with 0.1 sec TTL.
     */
    public function psetex($key, $ttl, $value) {}

    /**
     * Scan a set for members.
     *
     * @see scan()
     * @param   string $key
     * @param   int $iterator
     * @param   string $pattern
     * @param   int $count
     * @return  array|bool
     */
    public function sScan($key, $iterator, $pattern = '', $count = 0) {}

    /**
     * Scan the keyspace for keys.
     *
     * @param   int $iterator
     * @param   string $pattern
     * @param   int $count How many keys to return in a go (only a sugestion to Redis)
     * @return  array|bool   an array of keys or FALSE if there are no more keys
     * @link    http://redis.io/commands/scan
     * <pre>
     * $it = NULL; // Initialize our iterator to NULL
     * $redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY); // retry when we get no keys back
     * while($arr_keys = $redis->scan($it)) {
     *   foreach($arr_keys as $str_key) {
     *     echo "Here is a key: $str_key\n";
     *   }
     *   echo "No more keys to scan!\n";
     * }
     * </pre>
     */
    public function scan($iterator, $pattern = '', $count = 0) {}

    /**
     * Scan a sorted set for members, with optional pattern and count.
     *
     * @see scan()
     * @param   string  $key
     * @param   int $iterator
     * @param   string  $pattern
     * @param   int $count
     * @return  array|bool
     */
    public function zScan($key, $iterator, $pattern = '', $count = 0) {}

    /**
     * Scan a HASH value for members, with an optional pattern and count.
     *
     * @see scan()
     * @param   string $key
     * @param   int $iterator
     * @param   string $pattern
     * @param   int $count
     * @return  array
     */
    public function hScan($key, $iterator, $pattern = '', $count = 0) {}



    /**
     * Issue the CLIENT command with various arguments.
     * @param   string $command list | getname | setname | kill
     * @param   string $arg
     * @return  mixed
     * @link    http://redis.io/commands/client-list
     * @link    http://redis.io/commands/client-getname
     * @link    http://redis.io/commands/client-setname
     * @link    http://redis.io/commands/client-kill
     * <pre>
     * $redis->client('list');
     * $redis->client('getname');
     * $redis->client('setname', 'somename');
     * $redis->client('kill', <ip:port>);
     * </pre>
     *
     *
     * CLIENT LIST will return an array of arrays with client information.
     * CLIENT GETNAME will return the client name or false if none has been set
     * CLIENT SETNAME will return true if it can be set and false if not
     * CLIENT KILL will return true if the client can be killed, and false if not
     */
    public function client($command, $arg = '') {}

    /**
     * Access the Redis slow log.
     *
     * @param   string $command get | len | reset
     * @return  mixed
     * @link    http://redis.io/commands/slowlog
     * <pre>
     * // Get ten slowlog entries
     * $redis->slowlog('get', 10);
     *
     * // Get the default number of slowlog entries
     * $redis->slowlog('get');
     *
     * // Reset our slowlog
     * $redis->slowlog('reset');
     *
     * // Retrieve slowlog length
     * $redis->slowlog('len');
     * </pre>
     */
    public function slowlog($command) {}

    /**
     * @see connect()
     * @param string    $host
     * @param int       $port
     * @param float     $timeout
     */
    public function open( $host, $port = 6379, $timeout = 0.0 ) {}

    /**
     * Connects to a Redis instance or reuse a connection already established with pconnect/popen.
     *
     * The connection will not be closed on close or end of request until the php process ends.
     * So be patient on to many open FD's (specially on redis server side) when using persistent connections on
     * many servers connecting to one redis server.
     *
     * Also more than one persistent connection can be made identified by either host + port + timeout
     * or unix socket + timeout.
     *
     * This feature is not available in threaded versions. pconnect and popen then working like their non persistent
     * equivalents.
     *
     * @param string    $host       can be a host, or the path to a unix domain socket
     * @param int       $port       optional
     * @param float     $timeout    value in seconds (optional, default is 0 meaning unlimited)
     * @return bool                 TRUE on success, FALSE on ertcnror.
     * <pre>
     * $redis->connect('127.0.0.1', 6379);
     * $redis->connect('127.0.0.1');            // port 6379 by default
     * $redis->connect('127.0.0.1', 6379, 2.5); // 2.5 sec timeout.
     * $redis->connect('/tmp/redis.sock');      // unix domain socket.
     * </pre>
     */
    public function pconnect( $host, $port = 6379, $timeout = 0.0 ) {}

    /**
     * @see pconnect()
     * @param string    $host
     * @param int       $port
     * @param float     $timeout
     */
    public function popen( $host, $port = 6379, $timeout = 0.0 ) {}

    /**
     * Disconnects from the Redis instance, except when pconnect is used.
     */
    public function close( ) {}

    /**
     * Set client option.
     *
     * @param   string  $name    parameter name
     * @param   string  $value   parameter value
     * @return  bool:   TRUE on success, FALSE on error.
     * @example
     * <pre>
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);        // don't serialize data
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);         // use built-in serialize/unserialize
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);    // use igBinary serialize/unserialize
     * $redis->setOption(Redis::OPT_PREFIX, 'myAppName:');                      // use custom prefix on all keys
     * </pre>
     */
    public function setOption( $name, $value ) {}

    /**
     * Get client option
     *
     * @param   string  $name parameter name
     * @return  int     Parameter value.
     * @example
     * // return Redis::SERIALIZER_NONE, Redis::SERIALIZER_PHP, or Redis::SERIALIZER_IGBINARY.
     * $redis->getOption(Redis::OPT_SERIALIZER);
     */
    public function getOption( $name ) {}

    /**
     * Check the current connection status
     *
     * @return  string STRING: +PONG on success. Throws a RedisException object on connectivity error, as described above.
     * @link    http://redis.io/commands/ping
     */
    public function ping( ) {}

    /**
     * Get the value related to the specified key
     *
     * @param   string  $key
     * @return  string|bool: If key didn't exist, FALSE is returned. Otherwise, the value related to this key is returned.
     * @link    http://redis.io/commands/get
     * @example $redis->get('key');
     */
    public function get( $key ) {}


    /**
     * Set the string value in argument as value of the key.
     *
     * @param   string  $key
     * @param   string  $value
     * @param   int   $timeout [optional] Calling setex() is preferred if you want a timeout.
     * @return  bool:   TRUE if the command is successful.
     * @link    http://redis.io/commands/set
     * @example $redis->set('key', 'value');
     */
    public function set( $key, $value, $timeout = 0 ) {}

    /**
     * Set the string value in argument as value of the key, with a time to live.
     *
     * @param   string  $key
     * @param   int     $ttl
     * @param   string  $value
     * @return  bool:   TRUE if the command is successful.
     * @link    http://redis.io/commands/setex
     * @example $redis->setex('key', 3600, 'value'); // sets key → value, with 1h TTL.
     */
    public function setex( $key, $ttl, $value ) {}

    /**
     * Set the string value in argument as value of the key if the key doesn't already exist in the database.
     *
     * @param   string  $key
     * @param   string  $value
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/setnx
     * @example
     * <pre>
     * $redis->setnx('key', 'value');   // return TRUE
     * $redis->setnx('key', 'value');   // return FALSE
     * </pre>
     */
    public function setnx( $key, $value ) {}

    /**
     * Remove specified keys.
     *
     * @param   int|array   $key1 An array of keys, or an undefined number of parameters, each a key: key1 key2 key3 ... keyN
     * @param   string      $key2 ...
     * @param   string      $key3 ...
     * @return int Number of keys deleted.
     * @link    http://redis.io/commands/del
     * @example
     * <pre>
     * $redis->set('key1', 'val1');
     * $redis->set('key2', 'val2');
     * $redis->set('key3', 'val3');
     * $redis->set('key4', 'val4');
     * $redis->delete('key1', 'key2');          // return 2
     * $redis->delete(array('key3', 'key4'));   // return 2
     * </pre>
     */
    public function del( $key1, $key2 = null, $key3 = null ) {}

    /**
     * @see del()
     * @param $key1
     * @param null $key2
     * @param null $key3
     */
    public function delete( $key1, $key2 = null, $key3 = null ) {}

    /**
     * Enter and exit transactional mode.
     *
     * @internal param Redis::MULTI|Redis::PIPELINE
     * Defaults to Redis::MULTI.
     * A Redis::MULTI block of commands runs as a single transaction;
     * a Redis::PIPELINE block is simply transmitted faster to the server, but without any guarantee of atomicity.
     * discard cancels a transaction.
     * @return Redis returns the Redis instance and enters multi-mode.
     * Once in multi-mode, all subsequent method calls return the same object until exec() is called.
     * @link    http://redis.io/commands/multi
     * @example
     * <pre>
     * $ret = $redis->multi()
     *      ->set('key1', 'val1')
     *      ->get('key1')
     *      ->set('key2', 'val2')
     *      ->get('key2')
     *      ->exec();
     *
     * //$ret == array (
     * //    0 => TRUE,
     * //    1 => 'val1',
     * //    2 => TRUE,
     * //    3 => 'val2');
     * </pre>
     */
    public function multi( ) {}

    /**
     * @see multi()
     * @link    http://redis.io/commands/exec
     */
    public function exec( ) {}

    /**
     * @see multi()
     * @link    http://redis.io/commands/discard
     */
    public function discard( ) {}

    /**
     * Watches a key for modifications by another client. If the key is modified between WATCH and EXEC,
     * the MULTI/EXEC transaction will fail (return FALSE). unwatch cancels all the watching of all keys by this client.
     * @param string | array $key: a list of keys
     * @return void
     * @link    http://redis.io/commands/watch
     * @example
     * <pre>
     * $redis->watch('x');
     * // long code here during the execution of which other clients could well modify `x`
     * $ret = $redis->multi()
     *          ->incr('x')
     *          ->exec();
     * // $ret = FALSE if x has been modified between the call to WATCH and the call to EXEC.
     * </pre>
     */
    public function watch( $key ) {}

    /**
     * @see watch()
     * @link    http://redis.io/commands/unwatch
     */
    public function unwatch( ) {}

    /**
     * Subscribe to channels. Warning: this function will probably change in the future.
     *
     * @param array             $channels an array of channels to subscribe to
     * @param string | array    $callback either a string or an array($instance, 'method_name').
     * The callback function receives 3 parameters: the redis instance, the channel name, and the message.
     * @link    http://redis.io/commands/subscribe
     * @example
     * <pre>
     * function f($redis, $chan, $msg) {
     *  switch($chan) {
     *      case 'chan-1':
     *          ...
     *          break;
     *
     *      case 'chan-2':
     *                     ...
     *          break;
     *
     *      case 'chan-2':
     *          ...
     *          break;
     *      }
     * }
     *
     * $redis->subscribe(array('chan-1', 'chan-2', 'chan-3'), 'f'); // subscribe to 3 chans
     * </pre>
     */
    public function subscribe( $channels, $callback ) {}

    /**
     * Subscribe to channels by pattern
     *
     * @param   array           $patterns   The number of elements removed from the set.
     * @param   string|array    $callback   Either a string or an array with an object and method.
     *                          The callback will get four arguments ($redis, $pattern, $channel, $message)
     * @link    http://redis.io/commands/psubscribe
     * @example
     * <pre>
     * function psubscribe($redis, $pattern, $chan, $msg) {
     *  echo "Pattern: $pattern\n";
     *  echo "Channel: $chan\n";
     *  echo "Payload: $msg\n";
     * }
     * </pre>
     */
    public function psubscribe( $patterns, $callback ) {}

    /**
     * Publish messages to channels. Warning: this function will probably change in the future.
     *
     * @param   string $channel a channel to publish to
     * @param   string $message string
     * @link    http://redis.io/commands/publish
     * @return  int Number of clients that received the message
     * @example $redis->publish('chan-1', 'hello, world!'); // send message.
     */
    public function publish( $channel, $message ) {}

    /**
     * Verify if the specified key exists.
     *
     * @param   string $key
     * @return  bool: If the key exists, return TRUE, otherwise return FALSE.
     * @link    http://redis.io/commands/exists
     * @example
     * <pre>
     * $redis->set('key', 'value');
     * $redis->exists('key');               //  TRUE
     * $redis->exists('NonExistingKey');    // FALSE
     * </pre>
     */
    public function exists( $key ) {}

    /**
     * Increment the number stored at key by one.
     *
     * @param   string $key
     * @return  int    the new value
     * @link    http://redis.io/commands/incr
     * @example
     * <pre>
     * $redis->incr('key1'); // key1 didn't exists, set to 0 before the increment and now has the value 1
     * $redis->incr('key1'); // 2
     * $redis->incr('key1'); // 3
     * $redis->incr('key1'); // 4
     * </pre>
     */
    public function incr( $key ) {}

    /**
     * Increment the float value of a key by the given amount
     *
     * @param   string  $key
     * @param   float   $increment
     * @return  float
     * @link    http://redis.io/commands/incrbyfloat
     * @example
     * <pre>
     * $redis = new Redis();
     * $redis->connect('127.0.0.1');
     * $redis->set('x', 3);
     * var_dump( $redis->incrByFloat('x', 1.5) );   // float(4.5)
     *
     * // ! SIC
     * var_dump( $redis->get('x') );                // string(3) "4.5"
     * </pre>
     */
    public function incrByFloat( $key, $increment ) {}

    /**
     * Increment the number stored at key by one. If the second argument is filled, it will be used as the integer
     * value of the increment.
     *
     * @param   string    $key    key
     * @param   int       $value  value that will be added to key (only for incrBy)
     * @return  int         the new value
     * @link    http://redis.io/commands/incrby
     * @example
     * <pre>
     * $redis->incr('key1');        // key1 didn't exists, set to 0 before the increment and now has the value 1
     * $redis->incr('key1');        // 2
     * $redis->incr('key1');        // 3
     * $redis->incr('key1');        // 4
     * $redis->incrBy('key1', 10);  // 14
     * </pre>
     */
    public function incrBy( $key, $value ) {}

    /**
     * Decrement the number stored at key by one.
     *
     * @param   string $key
     * @return  int    the new value
     * @link    http://redis.io/commands/decr
     * @example
     * <pre>
     * $redis->decr('key1'); // key1 didn't exists, set to 0 before the increment and now has the value -1
     * $redis->decr('key1'); // -2
     * $redis->decr('key1'); // -3
     * </pre>
     */
    public function decr( $key ) {}

    /**
     * Decrement the number stored at key by one. If the second argument is filled, it will be used as the integer
     * value of the decrement.
     *
     * @param   string    $key
     * @param   int       $value  that will be substracted to key (only for decrBy)
     * @return  int       the new value
     * @link    http://redis.io/commands/decrby
     * @example
     * <pre>
     * $redis->decr('key1');        // key1 didn't exists, set to 0 before the increment and now has the value -1
     * $redis->decr('key1');        // -2
     * $redis->decr('key1');        // -3
     * $redis->decrBy('key1', 10);  // -13
     * </pre>
     */
    public function decrBy( $key, $value ) {}

    /**
     * Get the values of all the specified keys. If one or more keys dont exist, the array will contain FALSE at the
     * position of the key.
     *
     * @param   array $keys Array containing the list of the keys
     * @return  array Array containing the values related to keys in argument
     * @example
     * <pre>
     * $redis->set('key1', 'value1');
     * $redis->set('key2', 'value2');
     * $redis->set('key3', 'value3');
     * $redis->getMultiple(array('key1', 'key2', 'key3')); // array('value1', 'value2', 'value3');
     * $redis->getMultiple(array('key0', 'key1', 'key5')); // array(`FALSE`, 'value2', `FALSE`);
     * </pre>
     */
    public function getMultiple( array $keys ) {}

    /**
     * Adds the string values to the head (left) of the list. Creates the list if the key didn't exist.
     * If the key exists and is not a list, FALSE is returned.
     *
     * @param   string $key
     * @param   string $value1  String, value to push in key
     * @param   string $value2  Optional
     * @param   string $valueN  Optional
     * @return  int    The new length of the list in case of success, FALSE in case of Failure.
     * @link    http://redis.io/commands/lpush
     * @example
     * <pre>
     * $redis->lPush('l', 'v1', 'v2', 'v3', 'v4')   // int(4)
     * var_dump( $redis->lRange('l', 0, -1) );
     * //// Output:
     * // array(4) {
     * //   [0]=> string(2) "v4"
     * //   [1]=> string(2) "v3"
     * //   [2]=> string(2) "v2"
     * //   [3]=> string(2) "v1"
     * // }
     * </pre>
     */
    public function lPush( $key, $value1, $value2 = null, $valueN = null ) {}

    /**
     * Adds the string values to the tail (right) of the list. Creates the list if the key didn't exist.
     * If the key exists and is not a list, FALSE is returned.
     *
     * @param   string  $key
     * @param   string  $value1 String, value to push in key
     * @param   string  $value2 Optional
     * @param   string  $valueN Optional
     * @return  int     The new length of the list in case of success, FALSE in case of Failure.
     * @link    http://redis.io/commands/rpush
     * @example
     * <pre>
     * $redis->rPush('l', 'v1', 'v2', 'v3', 'v4');    // int(4)
     * var_dump( $redis->lRange('l', 0, -1) );
     * //// Output:
     * // array(4) {
     * //   [0]=> string(2) "v1"
     * //   [1]=> string(2) "v2"
     * //   [2]=> string(2) "v3"
     * //   [3]=> string(2) "v4"
     * // }
     * </pre>
     */
    public function rPush( $key, $value1, $value2 = null, $valueN = null ) {}

    /**
     * Adds the string value to the head (left) of the list if the list exists.
     *
     * @param   string  $key
     * @param   string  $value String, value to push in key
     * @return  int     The new length of the list in case of success, FALSE in case of Failure.
     * @link    http://redis.io/commands/lpushx
     * @example
     * <pre>
     * $redis->delete('key1');
     * $redis->lPushx('key1', 'A');     // returns 0
     * $redis->lPush('key1', 'A');      // returns 1
     * $redis->lPushx('key1', 'B');     // returns 2
     * $redis->lPushx('key1', 'C');     // returns 3
     * // key1 now points to the following list: [ 'A', 'B', 'C' ]
     * </pre>
     */
    public function lPushx( $key, $value ) {}

    /**
     * Adds the string value to the tail (right) of the list if the ist exists. FALSE in case of Failure.
     *
     * @param   string  $key
     * @param   string  $value String, value to push in key
     * @return  int     The new length of the list in case of success, FALSE in case of Failure.
     * @link    http://redis.io/commands/rpushx
     * @example
     * <pre>
     * $redis->delete('key1');
     * $redis->rPushx('key1', 'A'); // returns 0
     * $redis->rPush('key1', 'A'); // returns 1
     * $redis->rPushx('key1', 'B'); // returns 2
     * $redis->rPushx('key1', 'C'); // returns 3
     * // key1 now points to the following list: [ 'A', 'B', 'C' ]
     * </pre>
     */
    public function rPushx( $key, $value ) {}

    /**
     * Returns and removes the first element of the list.
     *
     * @param   string $key
     * @return  string if command executed successfully BOOL FALSE in case of failure (empty list)
     * @link    http://redis.io/commands/lpop
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->lPop('key1');        // key1 => [ 'B', 'C' ]
     * </pre>
     */
    public function lPop( $key ) {}

    /**
     * Returns and removes the last element of the list.
     *
     * @param   string $key
     * @return  string if command executed successfully BOOL FALSE in case of failure (empty list)
     * @link    http://redis.io/commands/rpop
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->rPop('key1');        // key1 => [ 'A', 'B' ]
     * </pre>
     */
    public function rPop( $key ) {}

    /**
     * Is a blocking lPop primitive. If at least one of the lists contains at least one element,
     * the element will be popped from the head of the list and returned to the caller.
     * Il all the list identified by the keys passed in arguments are empty, blPop will block
     * during the specified timeout until an element is pushed to one of those lists. This element will be popped.
     *
     * @param   array $keys Array containing the keys of the lists INTEGER Timeout
     * Or STRING Key1 STRING Key2 STRING Key3 ... STRING Keyn INTEGER Timeout
     * @return  array array('listName', 'element')
     * @link    http://redis.io/commands/blpop
     * @example
     * <pre>
     * // Non blocking feature
     * $redis->lPush('key1', 'A');
     * $redis->delete('key2');
     *
     * $redis->blPop('key1', 'key2', 10); // array('key1', 'A')
     * // OR
     * $redis->blPop(array('key1', 'key2'), 10); // array('key1', 'A')
     *
     * $redis->brPop('key1', 'key2', 10); // array('key1', 'A')
     * // OR
     * $redis->brPop(array('key1', 'key2'), 10); // array('key1', 'A')
     *
     * // Blocking feature
     *
     * // process 1
     * $redis->delete('key1');
     * $redis->blPop('key1', 10);
     * // blocking for 10 seconds
     *
     * // process 2
     * $redis->lPush('key1', 'A');
     *
     * // process 1
     * // array('key1', 'A') is returned
     * </pre>
     */
    public function blPop( array $keys ) {}

    /**
     * Is a blocking rPop primitive. If at least one of the lists contains at least one element,
     * the element will be popped from the head of the list and returned to the caller.
     * Il all the list identified by the keys passed in arguments are empty, brPop will
     * block during the specified timeout until an element is pushed to one of those lists. T
     * his element will be popped.
     *
     * @param   array $keys Array containing the keys of the lists INTEGER Timeout
     * Or STRING Key1 STRING Key2 STRING Key3 ... STRING Keyn INTEGER Timeout
     * @return  array array('listName', 'element')
     * @link    http://redis.io/commands/brpop
     * @example
     * <pre>
     * // Non blocking feature
     * $redis->lPush('key1', 'A');
     * $redis->delete('key2');
     *
     * $redis->blPop('key1', 'key2', 10); // array('key1', 'A')
     * // OR
     * $redis->blPop(array('key1', 'key2'), 10); // array('key1', 'A')
     *
     * $redis->brPop('key1', 'key2', 10); // array('key1', 'A')
     * // OR
     * $redis->brPop(array('key1', 'key2'), 10); // array('key1', 'A')
     *
     * // Blocking feature
     *
     * // process 1
     * $redis->delete('key1');
     * $redis->blPop('key1', 10);
     * // blocking for 10 seconds
     *
     * // process 2
     * $redis->lPush('key1', 'A');
     *
     * // process 1
     * // array('key1', 'A') is returned
     * </pre>
     */
    public function brPop( array $keys ) {}


    /**
     * Returns the size of a list identified by Key. If the list didn't exist or is empty,
     * the command returns 0. If the data type identified by Key is not a list, the command return FALSE.
     *
     * @param   string  $key
     * @return  int     The size of the list identified by Key exists.
     * bool FALSE if the data type identified by Key is not list
     * @link    http://redis.io/commands/llen
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->lLen('key1');       // 3
     * $redis->rPop('key1');
     * $redis->lLen('key1');       // 2
     * </pre>
     */
    public function lLen( $key ) {}

    /**
     * @see     lLen()
     * @param   string    $key
     * @link    http://redis.io/commands/llen
     */
    public function lSize( $key ) {}


    /**
     * Return the specified element of the list stored at the specified key.
     * 0 the first element, 1 the second ... -1 the last element, -2 the penultimate ...
     * Return FALSE in case of a bad index or a key that doesn't point to a list.
     * @param string    $key
     * @param int       $index
     * @return String the element at this index
     * Bool FALSE if the key identifies a non-string data type, or no value corresponds to this index in the list Key.
     * @link    http://redis.io/commands/lindex
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->lGet('key1', 0);     // 'A'
     * $redis->lGet('key1', -1);    // 'C'
     * $redis->lGet('key1', 10);    // `FALSE`
     * </pre>
     */
    public function lIndex( $key, $index ) {}

    /**
     * @see lIndex()
     * @param   string    $key
     * @param   int       $index
     * @link    http://redis.io/commands/lindex
     */
    public function lGet( $key, $index ) {}


    /**
     * Set the list at index with the new value.
     *
     * @param string    $key
     * @param int       $index
     * @param string    $value
     * @return BOOL TRUE if the new value is setted. FALSE if the index is out of range, or data type identified by key
     * is not a list.
     * @link    http://redis.io/commands/lset
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->lGet('key1', 0);     // 'A'
     * $redis->lSet('key1', 0, 'X');
     * $redis->lGet('key1', 0);     // 'X'
     * </pre>
     */
    public function lSet( $key, $index, $value ) {}


    /**
     * Returns the specified elements of the list stored at the specified key in
     * the range [start, end]. start and stop are interpretated as indices: 0 the first element,
     * 1 the second ... -1 the last element, -2 the penultimate ...
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @return  array containing the values in specified range.
     * @link    http://redis.io/commands/lrange
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');
     * $redis->lRange('key1', 0, -1); // array('A', 'B', 'C')
     * </pre>
     */
    public function lRange( $key, $start, $end ) {}

    /**
     * @see lRange()
     * @link http://redis.io/commands/lrange
     * @param string    $key
     * @param int       $start
     * @param int       $end
     */
    public function lGetRange( $key, $start, $end ) {}


    /**
     * Trims an existing list so that it will contain only a specified range of elements.
     *
     * @param string    $key
     * @param int       $start
     * @param int       $stop
     * @return array    Bool return FALSE if the key identify a non-list value.
     * @link        http://redis.io/commands/ltrim
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');
     * $redis->lRange('key1', 0, -1); // array('A', 'B', 'C')
     * $redis->lTrim('key1', 0, 1);
     * $redis->lRange('key1', 0, -1); // array('A', 'B')
     * </pre>
     */
    public function lTrim( $key, $start, $stop ) {}

    /**
     * @see lTrim()
     * @link  http://redis.io/commands/ltrim
     * @param string    $key
     * @param int       $start
     * @param int       $stop
     */
    public function listTrim( $key, $start, $stop ) {}


    /**
     * Removes the first count occurences of the value element from the list.
     * If count is zero, all the matching elements are removed. If count is negative,
     * elements are removed from tail to head.
     *
     * @param   string  $key
     * @param   string  $value
     * @param   int     $count
     * @return  int     the number of elements to remove
     * bool FALSE if the value identified by key is not a list.
     * @link    http://redis.io/commands/lrem
     * @example
     * <pre>
     * $redis->lPush('key1', 'A');
     * $redis->lPush('key1', 'B');
     * $redis->lPush('key1', 'C');
     * $redis->lPush('key1', 'A');
     * $redis->lPush('key1', 'A');
     *
     * $redis->lRange('key1', 0, -1);   // array('A', 'A', 'C', 'B', 'A')
     * $redis->lRem('key1', 'A', 2);    // 2
     * $redis->lRange('key1', 0, -1);   // array('C', 'B', 'A')
     * </pre>
     */
    public function lRem( $key, $value, $count ) {}

    /**
     * @see lRem
     * @link    http://redis.io/commands/lremove
     * @param string    $key
     * @param string    $value
     * @param int       $count
     */
    public function lRemove( $key, $value, $count ) {}


    /**
     * Insert value in the list before or after the pivot value. the parameter options
     * specify the position of the insert (before or after). If the list didn't exists,
     * or the pivot didn't exists, the value is not inserted.
     *
     * @param   string  $key
     * @param   int     $position Redis::BEFORE | Redis::AFTER
     * @param   string  $pivot
     * @param   string  $value
     * @return  int     The number of the elements in the list, -1 if the pivot didn't exists.
     * @link    http://redis.io/commands/linsert
     * @example
     * <pre>
     * $redis->delete('key1');
     * $redis->lInsert('key1', Redis::AFTER, 'A', 'X');     // 0
     *
     * $redis->lPush('key1', 'A');
     * $redis->lPush('key1', 'B');
     * $redis->lPush('key1', 'C');
     *
     * $redis->lInsert('key1', Redis::BEFORE, 'C', 'X');    // 4
     * $redis->lRange('key1', 0, -1);                       // array('A', 'B', 'X', 'C')
     *
     * $redis->lInsert('key1', Redis::AFTER, 'C', 'Y');     // 5
     * $redis->lRange('key1', 0, -1);                       // array('A', 'B', 'X', 'C', 'Y')
     *
     * $redis->lInsert('key1', Redis::AFTER, 'W', 'value'); // -1
     * </pre>
     */
    public function lInsert( $key, $position, $pivot, $value ) {}


    /**
     * Adds a values to the set value stored at key.
     * If this value is already in the set, FALSE is returned.
     *
     * @param   string  $key        Required key
     * @param   string  $value1     Required value
     * @param   string  $value2     Optional value
     * @param   string  $valueN     Optional value
     * @return  int     The number of elements added to the set
     * @link    http://redis.io/commands/sadd
     * @example
     * <pre>
     * $redis->sAdd('k', 'v1');                // int(1)
     * $redis->sAdd('k', 'v1', 'v2', 'v3');    // int(2)
     * </pre>
     */
    public function sAdd( $key, $value1, $value2 = null, $valueN = null ) {}


    /**
     * Removes the specified members from the set value stored at key.
     *
     * @param   string  $key
     * @param   string  $member1
     * @param   string  $member2
     * @param   string  $memberN
     * @return  int     The number of elements removed from the set.
     * @link    http://redis.io/commands/srem
     * @example
     * <pre>
     * var_dump( $redis->sAdd('k', 'v1', 'v2', 'v3') );    // int(3)
     * var_dump( $redis->sRem('k', 'v2', 'v3') );          // int(2)
     * var_dump( $redis->sMembers('k') );
     * //// Output:
     * // array(1) {
     * //   [0]=> string(2) "v1"
     * // }
     * </pre>
     */
    public function sRem( $key, $member1, $member2 = null, $memberN = null ) {}

    /**
     * @see sRem()
     * @link    http://redis.io/commands/srem
     * @param   string  $key
     * @param   string  $member1
     * @param   string  $member2
     * @param   string  $memberN
     */
    public function sRemove( $key, $member1, $member2 = null, $memberN = null ) {}


    /**
     * Moves the specified member from the set at srcKey to the set at dstKey.
     *
     * @param   string  $srcKey
     * @param   string  $dstKey
     * @param   string  $member
     * @return  bool    If the operation is successful, return TRUE.
     * If the srcKey and/or dstKey didn't exist, and/or the member didn't exist in srcKey, FALSE is returned.
     * @link    http://redis.io/commands/smove
     * @example
     * <pre>
     * $redis->sAdd('key1' , 'set11');
     * $redis->sAdd('key1' , 'set12');
     * $redis->sAdd('key1' , 'set13');          // 'key1' => {'set11', 'set12', 'set13'}
     * $redis->sAdd('key2' , 'set21');
     * $redis->sAdd('key2' , 'set22');          // 'key2' => {'set21', 'set22'}
     * $redis->sMove('key1', 'key2', 'set13');  // 'key1' =>  {'set11', 'set12'}
     *                                          // 'key2' =>  {'set21', 'set22', 'set13'}
     * </pre>
     */
    public function sMove( $srcKey, $dstKey, $member ) {}


    /**
     * Checks if value is a member of the set stored at the key key.
     *
     * @param   string  $key
     * @param   string  $value
     * @return  bool    TRUE if value is a member of the set at key key, FALSE otherwise.
     * @link    http://redis.io/commands/sismember
     * @example
     * <pre>
     * $redis->sAdd('key1' , 'set1');
     * $redis->sAdd('key1' , 'set2');
     * $redis->sAdd('key1' , 'set3'); // 'key1' => {'set1', 'set2', 'set3'}
     *
     * $redis->sIsMember('key1', 'set1'); // TRUE
     * $redis->sIsMember('key1', 'setX'); // FALSE
     * </pre>
     */
    public function sIsMember( $key, $value ) {}

    /**
     * @see sIsMember()
     * @link    http://redis.io/commands/sismember
     * @param   string  $key
     * @param   string  $value
     */
    public function sContains( $key, $value ) {}

    /**
     * Returns the cardinality of the set identified by key.
     *
     * @param   string  $key
     * @return  int     the cardinality of the set identified by key, 0 if the set doesn't exist.
     * @link    http://redis.io/commands/scard
     * @example
     * <pre>
     * $redis->sAdd('key1' , 'set1');
     * $redis->sAdd('key1' , 'set2');
     * $redis->sAdd('key1' , 'set3');   // 'key1' => {'set1', 'set2', 'set3'}
     * $redis->sCard('key1');           // 3
     * $redis->sCard('keyX');           // 0
     * </pre>
     */
    public function sCard( $key ) {}


    /**
     * Removes and returns a random element from the set value at Key.
     *
     * @param   string  $key
     * @return  string  "popped" value
     * bool FALSE if set identified by key is empty or doesn't exist.
     * @link    http://redis.io/commands/spop
     * @example
     * <pre>
     * $redis->sAdd('key1' , 'set1');
     * $redis->sAdd('key1' , 'set2');
     * $redis->sAdd('key1' , 'set3');   // 'key1' => {'set3', 'set1', 'set2'}
     * $redis->sPop('key1');            // 'set1', 'key1' => {'set3', 'set2'}
     * $redis->sPop('key1');            // 'set3', 'key1' => {'set2'}
     * </pre>
     */
    public function sPop( $key ) {}


    /**
     * Returns a random element from the set value at Key, without removing it.
     *
     * @param   string  $key
     * @return  string  value from the set
     * bool FALSE if set identified by key is empty or doesn't exist.
     * @link    http://redis.io/commands/srandmember
     * @example
     * <pre>
     * $redis->sAdd('key1' , 'set1');
     * $redis->sAdd('key1' , 'set2');
     * $redis->sAdd('key1' , 'set3');   // 'key1' => {'set3', 'set1', 'set2'}
     * $redis->sRandMember('key1');     // 'set1', 'key1' => {'set3', 'set1', 'set2'}
     * $redis->sRandMember('key1');     // 'set3', 'key1' => {'set3', 'set1', 'set2'}
     * </pre>
     */
    public function sRandMember( $key ) {}

    /**
     * Returns the members of a set resulting from the intersection of all the sets
     * held at the specified keys. If just a single key is specified, then this command
     * produces the members of this set. If one of the keys is missing, FALSE is returned.
     *
     * @param   string  $key1  keys identifying the different sets on which we will apply the intersection.
     * @param   string  $key2  ...
     * @param   string  $keyN  ...
     * @return  array, contain the result of the intersection between those keys.
     * If the intersection between the different sets is empty, the return value will be empty array.
     * @link    http://redis.io/commands/sinterstore
     * @example
     * <pre>
     * $redis->sAdd('key1', 'val1');
     * $redis->sAdd('key1', 'val2');
     * $redis->sAdd('key1', 'val3');
     * $redis->sAdd('key1', 'val4');
     *
     * $redis->sAdd('key2', 'val3');
     * $redis->sAdd('key2', 'val4');
     *
     * $redis->sAdd('key3', 'val3');
     * $redis->sAdd('key3', 'val4');
     *
     * var_dump($redis->sInter('key1', 'key2', 'key3'));
     *
     * //array(2) {
     * //  [0]=>
     * //  string(4) "val4"
     * //  [1]=>
     * //  string(4) "val3"
     * //}
     * </pre>
     */
    public function sInter( $key1, $key2, $keyN = null ) {}

    /**
     * Performs a sInter command and stores the result in a new set.
     *
     * @param   string  $dstKey the key to store the diff into.
     * @param   string  $key1 are intersected as in sInter.
     * @param   string  $key2 ...
     * @param   string  $keyN ...
     * @return  int:    The cardinality of the resulting set, or FALSE in case of a missing key.
     * @link    http://redis.io/commands/sinterstore
     * @example
     * <pre>
     * $redis->sAdd('key1', 'val1');
     * $redis->sAdd('key1', 'val2');
     * $redis->sAdd('key1', 'val3');
     * $redis->sAdd('key1', 'val4');
     *
     * $redis->sAdd('key2', 'val3');
     * $redis->sAdd('key2', 'val4');
     *
     * $redis->sAdd('key3', 'val3');
     * $redis->sAdd('key3', 'val4');
     *
     * var_dump($redis->sInterStore('output', 'key1', 'key2', 'key3'));
     * var_dump($redis->sMembers('output'));
     *
     * //int(2)
     * //
     * //array(2) {
     * //  [0]=>
     * //  string(4) "val4"
     * //  [1]=>
     * //  string(4) "val3"
     * //}
     * </pre>
     */
    public function sInterStore( $dstKey, $key1, $key2, $keyN = null ) {}

    /**
     * Performs the union between N sets and returns it.
     *
     * @param   string  $key1 Any number of keys corresponding to sets in redis.
     * @param   string  $key2 ...
     * @param   string  $keyN ...
     * @return  array   of strings: The union of all these sets.
     * @link    http://redis.io/commands/sunionstore
     * @example
     * <pre>
     * $redis->delete('s0', 's1', 's2');
     *
     * $redis->sAdd('s0', '1');
     * $redis->sAdd('s0', '2');
     * $redis->sAdd('s1', '3');
     * $redis->sAdd('s1', '1');
     * $redis->sAdd('s2', '3');
     * $redis->sAdd('s2', '4');
     *
     * var_dump($redis->sUnion('s0', 's1', 's2'));
     *
     * array(4) {
     * //  [0]=>
     * //  string(1) "3"
     * //  [1]=>
     * //  string(1) "4"
     * //  [2]=>
     * //  string(1) "1"
     * //  [3]=>
     * //  string(1) "2"
     * //}
     * </pre>
     */
    public function sUnion( $key1, $key2, $keyN = null ) {}

    /**
     * Performs the same action as sUnion, but stores the result in the first key
     *
     * @param   string  $dstKey  the key to store the diff into.
     * @param   string  $key1    Any number of keys corresponding to sets in redis.
     * @param   string  $key2    ...
     * @param   string  $keyN    ...
     * @return  int     Any number of keys corresponding to sets in redis.
     * @link    http://redis.io/commands/sunionstore
     * @example
     * <pre>
     * $redis->delete('s0', 's1', 's2');
     *
     * $redis->sAdd('s0', '1');
     * $redis->sAdd('s0', '2');
     * $redis->sAdd('s1', '3');
     * $redis->sAdd('s1', '1');
     * $redis->sAdd('s2', '3');
     * $redis->sAdd('s2', '4');
     *
     * var_dump($redis->sUnionStore('dst', 's0', 's1', 's2'));
     * var_dump($redis->sMembers('dst'));
     *
     * //int(4)
     * //array(4) {
     * //  [0]=>
     * //  string(1) "3"
     * //  [1]=>
     * //  string(1) "4"
     * //  [2]=>
     * //  string(1) "1"
     * //  [3]=>
     * //  string(1) "2"
     * //}
     * </pre>
     */
    public function sUnionStore( $dstKey, $key1, $key2, $keyN = null ) {}

    /**
     * Performs the difference between N sets and returns it.
     *
     * @param   string  $key1 Any number of keys corresponding to sets in redis.
     * @param   string  $key2 ...
     * @param   string  $keyN ...
     * @return  array   of strings: The difference of the first set will all the others.
     * @link    http://redis.io/commands/sdiff
     * @example
     * <pre>
     * $redis->delete('s0', 's1', 's2');
     *
     * $redis->sAdd('s0', '1');
     * $redis->sAdd('s0', '2');
     * $redis->sAdd('s0', '3');
     * $redis->sAdd('s0', '4');
     *
     * $redis->sAdd('s1', '1');
     * $redis->sAdd('s2', '3');
     *
     * var_dump($redis->sDiff('s0', 's1', 's2'));
     *
     * //array(2) {
     * //  [0]=>
     * //  string(1) "4"
     * //  [1]=>
     * //  string(1) "2"
     * //}
     * </pre>
     */
    public function sDiff( $key1, $key2, $keyN = null ) {}

    /**
     * Performs the same action as sDiff, but stores the result in the first key
     *
     * @param   string  $dstKey    the key to store the diff into.
     * @param   string  $key1      Any number of keys corresponding to sets in redis
     * @param   string  $key2      ...
     * @param   string  $keyN      ...
     * @return  int:    The cardinality of the resulting set, or FALSE in case of a missing key.
     * @link    http://redis.io/commands/sdiffstore
     * @example
     * <pre>
     * $redis->delete('s0', 's1', 's2');
     *
     * $redis->sAdd('s0', '1');
     * $redis->sAdd('s0', '2');
     * $redis->sAdd('s0', '3');
     * $redis->sAdd('s0', '4');
     *
     * $redis->sAdd('s1', '1');
     * $redis->sAdd('s2', '3');
     *
     * var_dump($redis->sDiffStore('dst', 's0', 's1', 's2'));
     * var_dump($redis->sMembers('dst'));
     *
     * //int(2)
     * //array(2) {
     * //  [0]=>
     * //  string(1) "4"
     * //  [1]=>
     * //  string(1) "2"
     * //}
     * </pre>
     */
    public function sDiffStore( $dstKey, $key1, $key2, $keyN = null ) {}

    /**
     * Returns the contents of a set.
     *
     * @param   string  $key
     * @return  array   An array of elements, the contents of the set.
     * @link    http://redis.io/commands/smembers
     * @example
     * <pre>
     * $redis->delete('s');
     * $redis->sAdd('s', 'a');
     * $redis->sAdd('s', 'b');
     * $redis->sAdd('s', 'a');
     * $redis->sAdd('s', 'c');
     * var_dump($redis->sMembers('s'));
     *
     * //array(3) {
     * //  [0]=>
     * //  string(1) "c"
     * //  [1]=>
     * //  string(1) "a"
     * //  [2]=>
     * //  string(1) "b"
     * //}
     * // The order is random and corresponds to redis' own internal representation of the set structure.
     * </pre>
     */
    public function sMembers( $key ) {}

    /**
     * @see sMembers()
     * @param   string  $key
     * @link    http://redis.io/commands/smembers
     */
    public function sGetMembers( $key ) {}

    /**
     * Sets a value and returns the previous entry at that key.
     *
     * @param   string  $key
     * @param   string  $value
     * @return  string  A string, the previous value located at this key.
     * @link    http://redis.io/commands/getset
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $exValue = $redis->getSet('x', 'lol');   // return '42', replaces x by 'lol'
     * $newValue = $redis->get('x')'            // return 'lol'
     * </pre>
     */
    public function getSet( $key, $value ) {}

    /**
     * Returns a random key.
     *
     * @return string: an existing key in redis.
     * @link    http://redis.io/commands/randomkey
     * @example
     * <pre>
     * $key = $redis->randomKey();
     * $surprise = $redis->get($key);  // who knows what's in there.
     * </pre>
     */
    public function randomKey( ) {}


    /**
     * Switches to a given database.
     *
     * @param   int     $dbindex
     * @return  bool    TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/select
     * @example
     * <pre>
     * $redis->select(0);       // switch to DB 0
     * $redis->set('x', '42');  // write 42 to x
     * $redis->move('x', 1);    // move to DB 1
     * $redis->select(1);       // switch to DB 1
     * $redis->get('x');        // will return 42
     * </pre>
     */
    public function select( $dbindex ) {}

    /**
     * Moves a key to a different database.
     *
     * @param   string  $key
     * @param   int     $dbindex
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/move
     * @example
     * <pre>
     * $redis->select(0);       // switch to DB 0
     * $redis->set('x', '42');  // write 42 to x
     * $redis->move('x', 1);    // move to DB 1
     * $redis->select(1);       // switch to DB 1
     * $redis->get('x');        // will return 42
     * </pre>
     */
    public function move( $key, $dbindex ) {}

    /**
     * Renames a key.
     *
     * @param   string  $srcKey
     * @param   string  $dstKey
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/rename
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $redis->rename('x', 'y');
     * $redis->get('y');   // → 42
     * $redis->get('x');   // → `FALSE`
     * </pre>
     */
    public function rename( $srcKey, $dstKey ) {}

    /**
     * @see rename()
     * @link    http://redis.io/commands/rename
     * @param   string  $srcKey
     * @param   string  $dstKey
     */
    public function renameKey( $srcKey, $dstKey ) {}

    /**
     * Renames a key.
     *
     * Same as rename, but will not replace a key if the destination already exists.
     * This is the same behaviour as setNx.
     *
     * @param   string  $srcKey
     * @param   string  $dstKey
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/renamenx
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $redis->rename('x', 'y');
     * $redis->get('y');   // → 42
     * $redis->get('x');   // → `FALSE`
     * </pre>
     */
    public function renameNx( $srcKey, $dstKey ) {}

    /**
     * Sets an expiration date (a timeout) on an item.
     *
     * @param   string  $key    The key that will disappear.
     * @param   int     $ttl    The key's remaining Time To Live, in seconds.
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/expire
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $redis->setTimeout('x', 3);  // x will disappear in 3 seconds.
     * sleep(5);                    // wait 5 seconds
     * $redis->get('x');            // will return `FALSE`, as 'x' has expired.
     * </pre>
     */
    public function expire( $key, $ttl ) {}

    /**
     * Sets an expiration date (a timeout in milliseconds) on an item.
     *
     * @param   string  $key    The key that will disappear.
     * @param   int     $ttl   The key's remaining Time To Live, in milliseconds.
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/pexpire
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $redis->pExpire('x', 11500); // x will disappear in 11500 milliseconds.
     * $redis->ttl('x');            // 12
     * $redis->pttl('x');           // 11500
     * </pre>
     */
    public function pExpire( $key, $ttl ) {}

    /**
     * @see expire()
     * @param   string  $key
     * @param   int     $ttl
     * @link    http://redis.io/commands/expire
     */
    public function setTimeout( $key, $ttl ) {}

    /**
     * Sets an expiration date (a timestamp) on an item.
     *
     * @param   string  $key        The key that will disappear.
     * @param   int     $timestamp  Unix timestamp. The key's date of death, in seconds from Epoch time.
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/expireat
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $now = time(NULL);               // current timestamp
     * $redis->expireAt('x', $now + 3); // x will disappear in 3 seconds.
     * sleep(5);                        // wait 5 seconds
     * $redis->get('x');                // will return `FALSE`, as 'x' has expired.
     * </pre>
     */
    public function expireAt( $key, $timestamp ) {}

    /**
     * Sets an expiration date (a timestamp) on an item. Requires a timestamp in milliseconds
     *
     * @param   string  $key        The key that will disappear.
     * @param   int     $timestamp  Unix timestamp. The key's date of death, in seconds from Epoch time.
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/pexpireat
     * @example
     * <pre>
     * $redis->set('x', '42');
     * $redis->pExpireAt('x', 1555555555005);
     * echo $redis->ttl('x');                       // 218270121
     * echo $redis->pttl('x');                      // 218270120575
     * </pre>
     */
    public function pExpireAt( $key, $timestamp ) {}

    /**
     * Returns the keys that match a certain pattern.
     *
     * @param   string  $pattern pattern, using '*' as a wildcard.
     * @return  array   of STRING: The keys that match a certain pattern.
     * @link    http://redis.io/commands/keys
     * @example
     * <pre>
     * $allKeys = $redis->keys('*');   // all keys will match this.
     * $keyWithUserPrefix = $redis->keys('user*');
     * </pre>
     */
    public function keys( $pattern ) {}

    /**
     * @see keys()
     * @param   string  $pattern
     * @link    http://redis.io/commands/keys
     */
    public function getKeys( $pattern ) {}

    /**
     * Returns the current database's size.
     *
     * @return int:     DB size, in number of keys.
     * @link    http://redis.io/commands/dbsize
     * @example
     * <pre>
     * $count = $redis->dbSize();
     * echo "Redis has $count keys\n";
     * </pre>
     */
    public function dbSize( ) {}

    /**
     * Authenticate the connection using a password.
     * Warning: The password is sent in plain-text over the network.
     *
     * @param   string  $password
     * @return  bool:   TRUE if the connection is authenticated, FALSE otherwise.
     * @link    http://redis.io/commands/auth
     * @example $redis->auth('foobared');
     */
    public function auth( $password ) {}

    /**
     * Starts the background rewrite of AOF (Append-Only File)
     *
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/bgrewriteaof
     * @example $redis->bgrewriteaof();
     */
    public function bgrewriteaof( ) {}

    /**
     * Changes the slave status
     * Either host and port, or no parameter to stop being a slave.
     *
     * @param   string  $host [optional]
     * @param   int $port [optional]
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/slaveof
     * @example
     * <pre>
     * $redis->slaveof('10.0.1.7', 6379);
     * // ...
     * $redis->slaveof();
     * </pre>
     */
    public function slaveof( $host = '127.0.0.1', $port = 6379 ) {}

    /**
     * Describes the object pointed to by a key.
     * The information to retrieve (string) and the key (string).
     * Info can be one of the following:
     * - "encoding"
     * - "refcount"
     * - "idletime"
     *
     * @param   string  $string
     * @param   string  $key
     * @return  string  for "encoding", int for "refcount" and "idletime", FALSE if the key doesn't exist.
     * @link    http://redis.io/commands/object
     * @example
     * <pre>
     * $redis->object("encoding", "l"); // → ziplist
     * $redis->object("refcount", "l"); // → 1
     * $redis->object("idletime", "l"); // → 400 (in seconds, with a precision of 10 seconds).
     * </pre>
     */
    public function object( $string = '', $key = '' ) {}

    /**
     * Performs a synchronous save.
     *
     * @return  bool:   TRUE in case of success, FALSE in case of failure.
     * If a save is already running, this command will fail and return FALSE.
     * @link    http://redis.io/commands/save
     * @example $redis->save();
     */
    public function save( ) {}

    /**
     * Performs a background save.
     *
     * @return  bool:    TRUE in case of success, FALSE in case of failure.
     * If a save is already running, this command will fail and return FALSE.
     * @link    http://redis.io/commands/bgsave
     * @example $redis->bgSave();
     */
    public function bgsave( ) {}

    /**
     * Returns the timestamp of the last disk save.
     *
     * @return  int:    timestamp.
     * @link    http://redis.io/commands/lastsave
     * @example $redis->lastSave();
     */
    public function lastSave( ) {}


    /**
     * Returns the type of data pointed by a given key.
     *
     * @param   string  $key
     * @return  int
     *
     * Depending on the type of the data pointed by the key,
     * this method will return the following value:
     * - string: Redis::REDIS_STRING
     * - set:   Redis::REDIS_SET
     * - list:  Redis::REDIS_LIST
     * - zset:  Redis::REDIS_ZSET
     * - hash:  Redis::REDIS_HASH
     * - other: Redis::REDIS_NOT_FOUND
     * @link    http://redis.io/commands/type
     * @example $redis->type('key');
     */
    public function type( $key ) {}

    /**
     * Append specified string to the string stored in specified key.
     *
     * @param   string  $key
     * @param   string  $value
     * @return  int:    Size of the value after the append
     * @link    http://redis.io/commands/append
     * @example
     * <pre>
     * $redis->set('key', 'value1');
     * $redis->append('key', 'value2'); // 12
     * $redis->get('key');              // 'value1value2'
     * </pre>
     */
    public function append( $key, $value ) {}


    /**
     * Return a substring of a larger string
     *
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @return  string: the substring
     * @link    http://redis.io/commands/getrange
     * @example
     * <pre>
     * $redis->set('key', 'string value');
     * $redis->getRange('key', 0, 5);   // 'string'
     * $redis->getRange('key', -5, -1); // 'value'
     * </pre>
     */
    public function getRange( $key, $start, $end ) {}

    /**
     * Return a substring of a larger string
     *
     * @deprecated
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     */
    public function substr( $key, $start, $end ) {}


    /**
     * Changes a substring of a larger string.
     *
     * @param   string  $key
     * @param   int     $offset
     * @param   string  $value
     * @return  string: the length of the string after it was modified.
     * @link    http://redis.io/commands/setrange
     * @example
     * <pre>
     * $redis->set('key', 'Hello world');
     * $redis->setRange('key', 6, "redis"); // returns 11
     * $redis->get('key');                  // "Hello redis"
     * </pre>
     */
    public function setRange( $key, $offset, $value ) {}

    /**
     * Get the length of a string value.
     *
     * @param   string  $key
     * @return  int
     * @link    http://redis.io/commands/strlen
     * @example
     * <pre>
     * $redis->set('key', 'value');
     * $redis->strlen('key'); // 5
     * </pre>
     */
    public function strlen( $key ) {}

    /**
     * Return a single bit out of a larger string
     *
     * @param   string  $key
     * @param   int     $offset
     * @return  int:    the bit value (0 or 1)
     * @link    http://redis.io/commands/getbit
     * @example
     * <pre>
     * $redis->set('key', "\x7f");  // this is 0111 1111
     * $redis->getBit('key', 0);    // 0
     * $redis->getBit('key', 1);    // 1
     * </pre>
     */
    public function getBit( $key, $offset ) {}

    /**
     * Changes a single bit of a string.
     *
     * @param   string  $key
     * @param   int     $offset
     * @param   bool|int $value bool or int (1 or 0)
     * @return  int:    0 or 1, the value of the bit before it was set.
     * @link    http://redis.io/commands/setbit
     * @example
     * <pre>
     * $redis->set('key', "*");     // ord("*") = 42 = 0x2f = "0010 1010"
     * $redis->setBit('key', 5, 1); // returns 0
     * $redis->setBit('key', 7, 1); // returns 0
     * $redis->get('key');          // chr(0x2f) = "/" = b("0010 1111")
     * </pre>
     */
    public function setBit( $key, $offset, $value ) {}

    /**
     * Count bits in a string.
     *
     * @param   string  $key
     * @return  int     The number of bits set to 1 in the value behind the input key.
     * @link    http://redis.io/commands/bitcount
     * @example
     * <pre>
     * $redis->set('bit', '345'); // // 11 0011  0011 0100  0011 0101
     * var_dump( $redis->bitCount('bit', 0, 0) ); // int(4)
     * var_dump( $redis->bitCount('bit', 1, 1) ); // int(3)
     * var_dump( $redis->bitCount('bit', 2, 2) ); // int(4)
     * var_dump( $redis->bitCount('bit', 0, 2) ); // int(11)
     * </pre>
     */
    public function bitCount( $key ) {}

    /**
     * Bitwise operation on multiple keys.
     *
     * @param   string  $operation  either "AND", "OR", "NOT", "XOR"
     * @param   string  $retKey     return key
     * @param   string  $keys
     * @return  int     The size of the string stored in the destination key.
     * @link    http://redis.io/commands/bitop
     * @example
     * <pre>
     * $redis->set('bit1', '1'); // 11 0001
     * $redis->set('bit2', '2'); // 11 0010
     *
     * $redis->bitOp('AND', 'bit', 'bit1', 'bit2'); // bit = 110000
     * $redis->bitOp('OR',  'bit', 'bit1', 'bit2'); // bit = 110011
     * $redis->bitOp('NOT', 'bit', 'bit1', 'bit2'); // bit = 110011
     * $redis->bitOp('XOR', 'bit', 'bit1', 'bit2'); // bit = 11
     * </pre>
     */
    public function bitOp( $operation, $retKey, ...$keys) {}

    /**
     * Removes all entries from the current database.
     *
     * @return  bool: Always TRUE.
     * @link    http://redis.io/commands/flushdb
     * @example $redis->flushDB();
     */
    public function flushDB( ) {}

    /**
     * Removes all entries from all databases.
     *
     * @return  bool: Always TRUE.
     * @link    http://redis.io/commands/flushall
     * @example $redis->flushAll();
     */
    public function flushAll( ) {}

    /**
     * Sort
     *
     * @param   string  $key
     * @param   array   $option array(key => value, ...) - optional, with the following keys and values:
     * - 'by' => 'some_pattern_*',
     * - 'limit' => array(0, 1),
     * - 'get' => 'some_other_pattern_*' or an array of patterns,
     * - 'sort' => 'asc' or 'desc',
     * - 'alpha' => TRUE,
     * - 'store' => 'external-key'
     * @return  array
     * An array of values, or a number corresponding to the number of elements stored if that was used.
     * @link    http://redis.io/commands/sort
     * @example
     * <pre>
     * $redis->delete('s');
     * $redis->sadd('s', 5);
     * $redis->sadd('s', 4);
     * $redis->sadd('s', 2);
     * $redis->sadd('s', 1);
     * $redis->sadd('s', 3);
     *
     * var_dump($redis->sort('s')); // 1,2,3,4,5
     * var_dump($redis->sort('s', array('sort' => 'desc'))); // 5,4,3,2,1
     * var_dump($redis->sort('s', array('sort' => 'desc', 'store' => 'out'))); // (int)5
     * </pre>
     */
    public function sort( $key, $option = null ) {}


    /**
     * Returns an associative array of strings and integers
     * @param   string   $option    Optional. The option to provide redis.
     * SERVER | CLIENTS | MEMORY | PERSISTENCE | STATS | REPLICATION | CPU | CLASTER | KEYSPACE | COMANDSTATS
     *
     * Returns an associative array of strings and integers, with the following keys:
     * - redis_version
     * - redis_git_sha1
     * - redis_git_dirty
     * - arch_bits
     * - multiplexing_api
     * - process_id
     * - uptime_in_seconds
     * - uptime_in_days
     * - lru_clock
     * - used_cpu_sys
     * - used_cpu_user
     * - used_cpu_sys_children
     * - used_cpu_user_children
     * - connected_clients
     * - connected_slaves
     * - client_longest_output_list
     * - client_biggest_input_buf
     * - blocked_clients
     * - used_memory
     * - used_memory_human
     * - used_memory_peak
     * - used_memory_peak_human
     * - mem_fragmentation_ratio
     * - mem_allocator
     * - loading
     * - aof_enabled
     * - changes_since_last_save
     * - bgsave_in_progress
     * - last_save_time
     * - total_connections_received
     * - total_commands_processed
     * - expired_keys
     * - evicted_keys
     * - keyspace_hits
     * - keyspace_misses
     * - hash_max_zipmap_entries
     * - hash_max_zipmap_value
     * - pubsub_channels
     * - pubsub_patterns
     * - latest_fork_usec
     * - vm_enabled
     * - role
     * @link    http://redis.io/commands/info
     * @return string
     * @example
     * <pre>
     * $redis->info();
     *
     * or
     *
     * $redis->info("COMMANDSTATS"); //Information on the commands that have been run (>=2.6 only)
     * $redis->info("CPU"); // just CPU information from Redis INFO
     * </pre>
     */
    public function info( $option = null ) {}

    /**
     * Resets the statistics reported by Redis using the INFO command (`info()` function).
     * These are the counters that are reset:
     *      - Keyspace hits
     *      - Keyspace misses
     *      - Number of commands processed
     *      - Number of connections received
     *      - Number of expired keys
     *
     * @return bool: `TRUE` in case of success, `FALSE` in case of failure.
     * @example $redis->resetStat();
     * @link http://redis.io/commands/config-resetstat
     */
    public function resetStat( ) {}

    /**
     * Returns the time to live left for a given key, in seconds. If the key doesn't exist, FALSE is returned.
     *
     * @param   string  $key
     * @return  int,    the time left to live in seconds.
     * @link    http://redis.io/commands/ttl
     * @example $redis->ttl('key');
     */
    public function ttl( $key ) {}

    /**
     * Returns a time to live left for a given key, in milliseconds.
     *
     * If the key doesn't exist, FALSE is returned.
     *
     * @param   string  $key
     * @return  int     the time left to live in milliseconds.
     * @link    http://redis.io/commands/pttl
     * @example $redis->pttl('key');
     */
    public function pttl( $key ) {}

    /**
     * Remove the expiration timer from a key.
     *
     * @param   string  $key
     * @return  bool:   TRUE if a timeout was removed, FALSE if the key didn’t exist or didn’t have an expiration timer.
     * @link    http://redis.io/commands/persist
     * @example $redis->persist('key');
     */
    public function persist( $key ) {}

    /**
     * Sets multiple key-value pairs in one atomic command.
     * MSETNX only returns TRUE if all the keys were set (see SETNX).
     *
     * @param   array(key => value) $array Pairs: array(key => value, ...)
     * @return  bool    TRUE in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/mset
     * @example
     * <pre>
     * $redis->mset(array('key0' => 'value0', 'key1' => 'value1'));
     * var_dump($redis->get('key0'));
     * var_dump($redis->get('key1'));
     * // Output:
     * // string(6) "value0"
     * // string(6) "value1"
     * </pre>
     */
    public function mset( array $array ) {}


    /**
     * Returns the values of all specified keys.
     *
     * For every key that does not hold a string value or does not exist,
     * the special value false is returned. Because of this, the operation never fails.
     *
     * @param array $array
     * @return array
     * @link http://redis.io/commands/mget
     * @example
     * <pre>
     * $redis->delete('x', 'y', 'z', 'h');	// remove x y z
     * $redis->mset(array('x' => 'a', 'y' => 'b', 'z' => 'c'));
     * $redis->hset('h', 'field', 'value');
     * var_dump($redis->mget(array('x', 'y', 'z', 'h')));
     * // Output:
     * // array(3) {
     * // [0]=>
     * // string(1) "a"
     * // [1]=>
     * // string(1) "b"
     * // [2]=>
     * // string(1) "c"
     * // [3]=>
     * // bool(false)
     * // }
     * </pre>
     */
    public function mget( array $array ) {}

    /**
     * @see mset()
     * @param   array $array
     * @return  int 1 (if the keys were set) or 0 (no key was set)
     * @link    http://redis.io/commands/msetnx
     */
    public function msetnx( array $array ) {}

    /**
     * Pops a value from the tail of a list, and pushes it to the front of another list.
     * Also return this value.
     *
     * @since   redis >= 1.1
     * @param   string  $srcKey
     * @param   string  $dstKey
     * @return  string  The element that was moved in case of success, FALSE in case of failure.
     * @link    http://redis.io/commands/rpoplpush
     * @example
     * <pre>
     * $redis->delete('x', 'y');
     *
     * $redis->lPush('x', 'abc');
     * $redis->lPush('x', 'def');
     * $redis->lPush('y', '123');
     * $redis->lPush('y', '456');
     *
     * // move the last of x to the front of y.
     * var_dump($redis->rpoplpush('x', 'y'));
     * var_dump($redis->lRange('x', 0, -1));
     * var_dump($redis->lRange('y', 0, -1));
     *
     * //Output:
     * //
     * //string(3) "abc"
     * //array(1) {
     * //  [0]=>
     * //  string(3) "def"
     * //}
     * //array(3) {
     * //  [0]=>
     * //  string(3) "abc"
     * //  [1]=>
     * //  string(3) "456"
     * //  [2]=>
     * //  string(3) "123"
     * //}
     * </pre>
     */
    public function rpoplpush( $srcKey, $dstKey ) {}

    /**
     * A blocking version of rpoplpush, with an integral timeout in the third parameter.
     *
     * @param   string  $srcKey
     * @param   string  $dstKey
     * @param   int     $timeout
     * @return  string  The element that was moved in case of success, FALSE in case of timeout.
     * @link    http://redis.io/commands/brpoplpush
     */
    public function brpoplpush( $srcKey, $dstKey, $timeout ) {}

    /**
     * Adds the specified member with a given score to the sorted set stored at key.
     *
     * @param   string  $key    Required key
     * @param   float   $score1 Required score
     * @param   string  $value1 Required value
     * @param   float   $score2 Optional score
     * @param   string  $value2 Optional value
     * @param   float   $scoreN Optional score
     * @param   string  $valueN Optional value
     * @return  int     Number of values added
     * @link    http://redis.io/commands/zadd
     * @example
     * <pre>
     * <pre>
     * $redis->zAdd('z', 1, 'v2', 2, 'v2', 3, 'v3', 4, 'v4' );  // int(2)
     * $redis->zRem('z', 'v2', 'v3');                           // int(2)
     * var_dump( $redis->zRange('z', 0, -1) );
     * //// Output:
     * // array(2) {
     * //   [0]=> string(2) "v1"
     * //   [1]=> string(2) "v4"
     * // }
     * </pre>
     * </pre>
     */
    public function zAdd( $key, $score1, $value1, $score2 = null, $value2 = null, $scoreN = null, $valueN = null ) {}

    /**
     * Returns a range of elements from the ordered set stored at the specified key,
     * with values in the range [start, end]. start and stop are interpreted as zero-based indices:
     * 0 the first element,
     * 1 the second ...
     * -1 the last element,
     * -2 the penultimate ...
     *
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @param   bool    $withscores
     * @return  array   Array containing the values in specified range.
     * @link    http://redis.io/commands/zrange
     * @example
     * <pre>
     * $redis->zAdd('key1', 0, 'val0');
     * $redis->zAdd('key1', 2, 'val2');
     * $redis->zAdd('key1', 10, 'val10');
     * $redis->zRange('key1', 0, -1); // array('val0', 'val2', 'val10')
     * // with scores
     * $redis->zRange('key1', 0, -1, true); // array('val0' => 0, 'val2' => 2, 'val10' => 10)
     * </pre>
     */
    public function zRange( $key, $start, $end, $withscores = null ) {}

    /**
     * Deletes a specified member from the ordered set.
     *
     * @param   string  $key
     * @param   string  $member1
     * @param   string  $member2
     * @param   string  $memberN
     * @return  int     Number of deleted values
     * @link    http://redis.io/commands/zrem
     * @example
     * <pre>
     * $redis->zAdd('z', 1, 'v2', 2, 'v2', 3, 'v3', 4, 'v4' );  // int(2)
     * $redis->zRem('z', 'v2', 'v3');                           // int(2)
     * var_dump( $redis->zRange('z', 0, -1) );
     * //// Output:
     * // array(2) {
     * //   [0]=> string(2) "v1"
     * //   [1]=> string(2) "v4"
     * // }
     * </pre>
     */
    public function zRem( $key, $member1, $member2 = null, $memberN = null ) {}

    /**
     * @see zRem()
     * @param   string  $key
     * @param   string  $member1
     * @param   string  $member2
     * @param   string  $memberN
     * @return  int     Number of deleted values
     * @link    http://redis.io/commands/zrem
     */
    public function zDelete( $key, $member1, $member2 = null, $memberN = null ) {}

    /**
     * Returns the elements of the sorted set stored at the specified key in the range [start, end]
     * in reverse order. start and stop are interpretated as zero-based indices:
     * 0 the first element,
     * 1 the second ...
     * -1 the last element,
     * -2 the penultimate ...
     *
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @param   bool    $withscore
     * @return  array   Array containing the values in specified range.
     * @link    http://redis.io/commands/zrevrange
     * @example
     * <pre>
     * $redis->zAdd('key', 0, 'val0');
     * $redis->zAdd('key', 2, 'val2');
     * $redis->zAdd('key', 10, 'val10');
     * $redis->zRevRange('key', 0, -1); // array('val10', 'val2', 'val0')
     *
     * // with scores
     * $redis->zRevRange('key', 0, -1, true); // array('val10' => 10, 'val2' => 2, 'val0' => 0)
     * </pre>
     */
    public function zRevRange( $key, $start, $end, $withscore = null ) {}

    /**
     * Returns the elements of the sorted set stored at the specified key which have scores in the
     * range [start,end]. Adding a parenthesis before start or end excludes it from the range.
     * +inf and -inf are also valid limits.
     *
     * zRevRangeByScore returns the same items in reverse order, when the start and end parameters are swapped.
     *
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @param   array   $options Two options are available:
     *                      - withscores => TRUE,
     *                      - and limit => array($offset, $count)
     * @return  array   Array containing the values in specified range.
     * @link    http://redis.io/commands/zrangebyscore
     * @example
     * <pre>
     * $redis->zAdd('key', 0, 'val0');
     * $redis->zAdd('key', 2, 'val2');
     * $redis->zAdd('key', 10, 'val10');
     * $redis->zRangeByScore('key', 0, 3);                                          // array('val0', 'val2')
     * $redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE);              // array('val0' => 0, 'val2' => 2)
     * $redis->zRangeByScore('key', 0, 3, array('limit' => array(1, 1));                        // array('val2' => 2)
     * $redis->zRangeByScore('key', 0, 3, array('limit' => array(1, 1));                        // array('val2')
     * $redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE, 'limit' => array(1, 1));  // array('val2' => 2)
     * </pre>
     */
    public function zRangeByScore( $key, $start, $end, array $options = array() ) {}

    /**
     * @see zRangeByScore()
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @param   array   $options
	 *
	 * @return 	array
     */
    public function zRevRangeByScore( $key, $start, $end, array $options = array() ) {}

    /**
     * Returns the number of elements of the sorted set stored at the specified key which have
     * scores in the range [start,end]. Adding a parenthesis before start or end excludes it
     * from the range. +inf and -inf are also valid limits.
     *
     * @param   string  $key
     * @param   string  $start
     * @param   string  $end
     * @return  int     the size of a corresponding zRangeByScore.
     * @link    http://redis.io/commands/zcount
     * @example
     * <pre>
     * $redis->zAdd('key', 0, 'val0');
     * $redis->zAdd('key', 2, 'val2');
     * $redis->zAdd('key', 10, 'val10');
     * $redis->zCount('key', 0, 3); // 2, corresponding to array('val0', 'val2')
     * </pre>
     */
    public function zCount( $key, $start, $end ) {}

    /**
     * Deletes the elements of the sorted set stored at the specified key which have scores in the range [start,end].
     *
     * @param   string          $key
     * @param   float|string    $start double or "+inf" or "-inf" string
     * @param   float|string    $end double or "+inf" or "-inf" string
     * @return  int             The number of values deleted from the sorted set
     * @link    http://redis.io/commands/zremrangebyscore
     * @example
     * <pre>
     * $redis->zAdd('key', 0, 'val0');
     * $redis->zAdd('key', 2, 'val2');
     * $redis->zAdd('key', 10, 'val10');
     * $redis->zRemRangeByScore('key', 0, 3); // 2
     * </pre>
     */
    public function zRemRangeByScore( $key, $start, $end ) {}

    /**
     * @see zRemRangeByScore()
     * @param string    $key
     * @param float     $start
     * @param float     $end
     */
    public function zDeleteRangeByScore( $key, $start, $end ) {}

    /**
     * Deletes the elements of the sorted set stored at the specified key which have rank in the range [start,end].
     *
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @return  int     The number of values deleted from the sorted set
     * @link    http://redis.io/commands/zremrangebyrank
     * @example
     * <pre>
     * $redis->zAdd('key', 1, 'one');
     * $redis->zAdd('key', 2, 'two');
     * $redis->zAdd('key', 3, 'three');
     * $redis->zRemRangeByRank('key', 0, 1); // 2
     * $redis->zRange('key', 0, -1, array('withscores' => TRUE)); // array('three' => 3)
     * </pre>
     */
    public function zRemRangeByRank( $key, $start, $end ) {}

    /**
     * @see zRemRangeByRank()
     * @param   string  $key
     * @param   int     $start
     * @param   int     $end
     * @link    http://redis.io/commands/zremrangebyscore
     */
    public function zDeleteRangeByRank( $key, $start, $end ) {}

    /**
     * Returns the cardinality of an ordered set.
     *
     * @param   string  $key
     * @return  int     the set's cardinality
     * @link    http://redis.io/commands/zsize
     * @example
     * <pre>
     * $redis->zAdd('key', 0, 'val0');
     * $redis->zAdd('key', 2, 'val2');
     * $redis->zAdd('key', 10, 'val10');
     * $redis->zCard('key');            // 3
     * </pre>
     */
    public function zCard( $key ) {}

    /**
     * @see zCard()
     * @param string $key
     */
    public function zSize( $key ) {}

    /**
     * Returns the score of a given member in the specified sorted set.
     *
     * @param   string  $key
     * @param   string  $member
     * @return  float
     * @link    http://redis.io/commands/zscore
     * @example
     * <pre>
     * $redis->zAdd('key', 2.5, 'val2');
     * $redis->zScore('key', 'val2'); // 2.5
     * </pre>
     */
    public function zScore( $key, $member ) {}

    /**
     * Returns the rank of a given member in the specified sorted set, starting at 0 for the item
     * with the smallest score. zRevRank starts at 0 for the item with the largest score.
     *
     * @param   string  $key
     * @param   string  $member
     * @return  int     the item's score.
     * @link    http://redis.io/commands/zrank
     * @example
     * <pre>
     * $redis->delete('z');
     * $redis->zAdd('key', 1, 'one');
     * $redis->zAdd('key', 2, 'two');
     * $redis->zRank('key', 'one');     // 0
     * $redis->zRank('key', 'two');     // 1
     * $redis->zRevRank('key', 'one');  // 1
     * $redis->zRevRank('key', 'two');  // 0
     * </pre>
     */
    public function zRank( $key, $member ) {}

    /**
     * @see zRank()
     * @param  string $key
     * @param  string $member
     * @return int    the item's score
     * @link   http://redis.io/commands/zrevrank
     */
    public function zRevRank( $key, $member ) {}

    /**
     * Increments the score of a member from a sorted set by a given amount.
     *
     * @param   string  $key
     * @param   float   $value (double) value that will be added to the member's score
     * @param   string  $member
     * @return  float   the new value
     * @link    http://redis.io/commands/zincrby
     * @example
     * <pre>
     * $redis->delete('key');
     * $redis->zIncrBy('key', 2.5, 'member1');  // key or member1 didn't exist, so member1's score is to 0
     *                                          // before the increment and now has the value 2.5
     * $redis->zIncrBy('key', 1, 'member1');    // 3.5
     * </pre>
     */
    public function zIncrBy( $key, $value, $member ) {}

    /**
     * Creates an union of sorted sets given in second argument.
     * The result of the union will be stored in the sorted set defined by the first argument.
     * The third optionnel argument defines weights to apply to the sorted sets in input.
     * In this case, the weights will be multiplied by the score of each element in the sorted set
     * before applying the aggregation. The forth argument defines the AGGREGATE option which
     * specify how the results of the union are aggregated.
     *
     * @param string    $Output
     * @param array     $ZSetKeys
     * @param array     $Weights
     * @param string    $aggregateFunction  Either "SUM", "MIN", or "MAX": defines the behaviour to use on
     * duplicate entries during the zUnion.
     * @return int The number of values in the new sorted set.
     * @link    http://redis.io/commands/zunionstore
     * @example
     * <pre>
     * $redis->delete('k1');
     * $redis->delete('k2');
     * $redis->delete('k3');
     * $redis->delete('ko1');
     * $redis->delete('ko2');
     * $redis->delete('ko3');
     *
     * $redis->zAdd('k1', 0, 'val0');
     * $redis->zAdd('k1', 1, 'val1');
     *
     * $redis->zAdd('k2', 2, 'val2');
     * $redis->zAdd('k2', 3, 'val3');
     *
     * $redis->zUnion('ko1', array('k1', 'k2')); // 4, 'ko1' => array('val0', 'val1', 'val2', 'val3')
     *
     * // Weighted zUnion
     * $redis->zUnion('ko2', array('k1', 'k2'), array(1, 1)); // 4, 'ko2' => array('val0', 'val1', 'val2', 'val3')
     * $redis->zUnion('ko3', array('k1', 'k2'), array(5, 1)); // 4, 'ko3' => array('val0', 'val2', 'val3', 'val1')
     * </pre>
     */
    public function zUnion($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM') {}

    /**
     * Creates an intersection of sorted sets given in second argument.
     * The result of the union will be stored in the sorted set defined by the first argument.
     * The third optional argument defines weights to apply to the sorted sets in input.
     * In this case, the weights will be multiplied by the score of each element in the sorted set
     * before applying the aggregation. The forth argument defines the AGGREGATE option which
     * specify how the results of the union are aggregated.
     *
     * @param   string  $Output
     * @param   array   $ZSetKeys
     * @param   array   $Weights
     * @param   string  $aggregateFunction Either "SUM", "MIN", or "MAX":
     * defines the behaviour to use on duplicate entries during the zInter.
     * @return  int     The number of values in the new sorted set.
     * @link    http://redis.io/commands/zinterstore
     * @example
     * <pre>
     * $redis->delete('k1');
     * $redis->delete('k2');
     * $redis->delete('k3');
     *
     * $redis->delete('ko1');
     * $redis->delete('ko2');
     * $redis->delete('ko3');
     * $redis->delete('ko4');
     *
     * $redis->zAdd('k1', 0, 'val0');
     * $redis->zAdd('k1', 1, 'val1');
     * $redis->zAdd('k1', 3, 'val3');
     *
     * $redis->zAdd('k2', 2, 'val1');
     * $redis->zAdd('k2', 3, 'val3');
     *
     * $redis->zInter('ko1', array('k1', 'k2'));               // 2, 'ko1' => array('val1', 'val3')
     * $redis->zInter('ko2', array('k1', 'k2'), array(1, 1));  // 2, 'ko2' => array('val1', 'val3')
     *
     * // Weighted zInter
     * $redis->zInter('ko3', array('k1', 'k2'), array(1, 5), 'min'); // 2, 'ko3' => array('val1', 'val3')
     * $redis->zInter('ko4', array('k1', 'k2'), array(1, 5), 'max'); // 2, 'ko4' => array('val3', 'val1')
     * </pre>
     */
    public function zInter($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM') {}

    /**
     * Adds a value to the hash stored at key. If this value is already in the hash, FALSE is returned.
     *
     * @param string $key
     * @param string $hashKey
     * @param string $value
     * @return int
     * 1 if value didn't exist and was added successfully,
     * 0 if the value was already present and was replaced, FALSE if there was an error.
     * @link    http://redis.io/commands/hset
     * @example
     * <pre>
     * $redis->delete('h')
     * $redis->hSet('h', 'key1', 'hello');  // 1, 'key1' => 'hello' in the hash at "h"
     * $redis->hGet('h', 'key1');           // returns "hello"
     *
     * $redis->hSet('h', 'key1', 'plop');   // 0, value was replaced.
     * $redis->hGet('h', 'key1');           // returns "plop"
     * </pre>
     */
    public function hSet( $key, $hashKey, $value ) {}

    /**
     * Adds a value to the hash stored at key only if this field isn't already in the hash.
     *
     * @param   string  $key
     * @param   string  $hashKey
     * @param   string  $value
     * @return  bool    TRUE if the field was set, FALSE if it was already present.
     * @link    http://redis.io/commands/hsetnx
     * @example
     * <pre>
     * $redis->delete('h')
     * $redis->hSetNx('h', 'key1', 'hello'); // TRUE, 'key1' => 'hello' in the hash at "h"
     * $redis->hSetNx('h', 'key1', 'world'); // FALSE, 'key1' => 'hello' in the hash at "h". No change since the field
     * wasn't replaced.
     * </pre>
     */
    public function hSetNx( $key, $hashKey, $value ) {}

    /**
     * Gets a value from the hash stored at key.
     * If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
     *
     * @param   string  $key
     * @param   string  $hashKey
     * @return  string  The value, if the command executed successfully BOOL FALSE in case of failure
     * @link    http://redis.io/commands/hget
     */
    public function hGet($key, $hashKey) {}

    /**
     * Returns the length of a hash, in number of items
     *
     * @param   string  $key
     * @return  int     the number of items in a hash, FALSE if the key doesn't exist or isn't a hash.
     * @link    http://redis.io/commands/hlen
     * @example
     * <pre>
     * $redis->delete('h')
     * $redis->hSet('h', 'key1', 'hello');
     * $redis->hSet('h', 'key2', 'plop');
     * $redis->hLen('h'); // returns 2
     * </pre>
     */
    public function hLen( $key ) {}

    /**
     * Removes a values from the hash stored at key.
     * If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.
     *
     * @param   string  $key
     * @param   string  $hashKey1
     * @param   string  $hashKey2
     * @param   string  $hashKeyN
     * @return  int     Number of deleted fields
     * @link    http://redis.io/commands/hdel
     * @example
     * <pre>
     * $redis->hMSet('h',
     *               array(
     *                    'f1' => 'v1',
     *                    'f2' => 'v2',
     *                    'f3' => 'v3',
     *                    'f4' => 'v4',
     *               ));
     *
     * var_dump( $redis->hDel('h', 'f1') );        // int(1)
     * var_dump( $redis->hDel('h', 'f2', 'f3') );  // int(2)
     * s
     * var_dump( $redis->hGetAll('h') );
     * //// Output:
     * //  array(1) {
     * //    ["f4"]=> string(2) "v4"
     * //  }
     * </pre>
     */
    public function hDel( $key, $hashKey1, $hashKey2 = null, $hashKeyN = null ) {}

    /**
     * Returns the keys in a hash, as an array of strings.
     *
     * @param   string  $key
     * @return  array   An array of elements, the keys of the hash. This works like PHP's array_keys().
     * @link    http://redis.io/commands/hkeys
     * @example
     * <pre>
     * $redis->delete('h');
     * $redis->hSet('h', 'a', 'x');
     * $redis->hSet('h', 'b', 'y');
     * $redis->hSet('h', 'c', 'z');
     * $redis->hSet('h', 'd', 't');
     * var_dump($redis->hKeys('h'));
     *
     * // Output:
     * // array(4) {
     * // [0]=>
     * // string(1) "a"
     * // [1]=>
     * // string(1) "b"
     * // [2]=>
     * // string(1) "c"
     * // [3]=>
     * // string(1) "d"
     * // }
     * // The order is random and corresponds to redis' own internal representation of the set structure.
     * </pre>
     */
    public function hKeys( $key ) {}

    /**
     * Returns the values in a hash, as an array of strings.
     *
     * @param   string  $key
     * @return  array   An array of elements, the values of the hash. This works like PHP's array_values().
     * @link    http://redis.io/commands/hvals
     * @example
     * <pre>
     * $redis->delete('h');
     * $redis->hSet('h', 'a', 'x');
     * $redis->hSet('h', 'b', 'y');
     * $redis->hSet('h', 'c', 'z');
     * $redis->hSet('h', 'd', 't');
     * var_dump($redis->hVals('h'));
     *
     * // Output
     * // array(4) {
     * //   [0]=>
     * //   string(1) "x"
     * //   [1]=>
     * //   string(1) "y"
     * //   [2]=>
     * //   string(1) "z"
     * //   [3]=>
     * //   string(1) "t"
     * // }
     * // The order is random and corresponds to redis' own internal representation of the set structure.
     * </pre>
     */
    public function hVals( $key ) {}

    /**
     * Returns the whole hash, as an array of strings indexed by strings.
     *
     * @param   string  $key
     * @return  array   An array of elements, the contents of the hash.
     * @link    http://redis.io/commands/hgetall
     * @example
     * <pre>
     * $redis->delete('h');
     * $redis->hSet('h', 'a', 'x');
     * $redis->hSet('h', 'b', 'y');
     * $redis->hSet('h', 'c', 'z');
     * $redis->hSet('h', 'd', 't');
     * var_dump($redis->hGetAll('h'));
     *
     * // Output:
     * // array(4) {
     * //   ["a"]=>
     * //   string(1) "x"
     * //   ["b"]=>
     * //   string(1) "y"
     * //   ["c"]=>
     * //   string(1) "z"
     * //   ["d"]=>
     * //   string(1) "t"
     * // }
     * // The order is random and corresponds to redis' own internal representation of the set structure.
     * </pre>
     */
    public function hGetAll( $key ) {}

    /**
     * Verify if the specified member exists in a key.
     *
     * @param   string  $key
     * @param   string  $hashKey
     * @return  bool:   If the member exists in the hash table, return TRUE, otherwise return FALSE.
     * @link    http://redis.io/commands/hexists
     * @example
     * <pre>
     * $redis->hSet('h', 'a', 'x');
     * $redis->hExists('h', 'a');               //  TRUE
     * $redis->hExists('h', 'NonExistingKey');  // FALSE
     * </pre>
     */
    public function hExists( $key, $hashKey ) {}

    /**
     * Increments the value of a member from a hash by a given amount.
     *
     * @param   string  $key
     * @param   string  $hashKey
     * @param   int     $value (integer) value that will be added to the member's value
     * @return  int     the new value
     * @link    http://redis.io/commands/hincrby
     * @example
     * <pre>
     * $redis->delete('h');
     * $redis->hIncrBy('h', 'x', 2); // returns 2: h[x] = 2 now.
     * $redis->hIncrBy('h', 'x', 1); // h[x] ← 2 + 1. Returns 3
     * </pre>
     */
    public function hIncrBy( $key, $hashKey, $value ) {}

    /**
     * Increment the float value of a hash field by the given amount
     * @param   string  $key
     * @param   string  $field
     * @param   float   $increment
     * @return  float
     * @link    http://redis.io/commands/hincrbyfloat
     * @example
     * <pre>
     * $redis = new Redis();
     * $redis->connect('127.0.0.1');
     * $redis->hset('h', 'float', 3);
     * $redis->hset('h', 'int',   3);
     * var_dump( $redis->hIncrByFloat('h', 'float', 1.5) ); // float(4.5)
     *
     * var_dump( $redis->hGetAll('h') );
     *
     * // Output
     *  array(2) {
     *    ["float"]=>
     *    string(3) "4.5"
     *    ["int"]=>
     *    string(1) "3"
     *  }
     * </pre>
     */
    public function hIncrByFloat( $key, $field, $increment ) {}

    /**
     * Fills in a whole hash. Non-string values are converted to string, using the standard (string) cast.
     * NULL values are stored as empty strings
     *
     * @param   string  $key
     * @param   array   $hashKeys key → value array
     * @return  bool
     * @link    http://redis.io/commands/hmset
     * @example
     * <pre>
     * $redis->delete('user:1');
     * $redis->hMset('user:1', array('name' => 'Joe', 'salary' => 2000));
     * $redis->hIncrBy('user:1', 'salary', 100); // Joe earns 100 more now.
     * </pre>
     */
    public function hMset( $key, $hashKeys ) {}

    /**
     * Retirieve the values associated to the specified fields in the hash.
     *
     * @param   string  $key
     * @param   array   $hashKeys
     * @return  array   Array An array of elements, the values of the specified fields in the hash,
     * with the hash keys as array keys.
     * @link    http://redis.io/commands/hmget
     * @example
     * <pre>
     * $redis->delete('h');
     * $redis->hSet('h', 'field1', 'value1');
     * $redis->hSet('h', 'field2', 'value2');
     * $redis->hmGet('h', array('field1', 'field2')); // returns array('field1' => 'value1', 'field2' => 'value2')
     * </pre>
     */
    public function hMGet( $key, $hashKeys ) {}

    /**
     * Get or Set the redis config keys.
     *
     * @param   string  $operation  either `GET` or `SET`
     * @param   string  $key        for `SET`, glob-pattern for `GET`. See http://redis.io/commands/config-get for examples.
     * @param   string  $value      optional string (only for `SET`)
     * @return  array   Associative array for `GET`, key -> value
     * @link    http://redis.io/commands/config-get
     * @link    http://redis.io/commands/config-set
     * @example
     * <pre>
     * $redis->config("GET", "*max-*-entries*");
     * $redis->config("SET", "dir", "/var/run/redis/dumps/");
     * </pre>
     */
    public function config( $operation, $key, $value ) {}

    /**
     * @see eval()
     * @param string $script
     * @param array  $args
     * @param int    $numKeys
     */
    public function evaluate( $script, $args = array(), $numKeys = 0 ) {}

    /**
     * Evaluate a LUA script serverside, from the SHA1 hash of the script instead of the script itself.
     * In order to run this command Redis will have to have already loaded the script, either by running it or via
     * the SCRIPT LOAD command.
     * @param   string  $scriptSha
     * @param   array   $args
     * @param   int     $numKeys
     * @return  mixed. @see eval()
     * @see     eval()
     * @link    http://redis.io/commands/evalsha
     * @example
     * <pre>
     * $script = 'return 1';
     * $sha = $redis->script('load', $script);
     * $redis->evalSha($sha); // Returns 1
     * </pre>
     */
    public function evalSha( $scriptSha, $args = array(), $numKeys = 0 ) {}

    /**
     * @see evalSha()
     * @param string $scriptSha
     * @param array  $args
     * @param int    $numKeys
     */
    public function evaluateSha( $scriptSha, $args = array(), $numKeys = 0 ) {}

    /**
     * Execute the Redis SCRIPT command to perform various operations on the scripting subsystem.
     * @param   string  $command load | flush | kill | exists
     * @param   string  $script
     * @return  mixed
     * @link    http://redis.io/commands/script-load
     * @link    http://redis.io/commands/script-kill
     * @link    http://redis.io/commands/script-flush
     * @link    http://redis.io/commands/script-exists
     * @example
     * <pre>
     * $redis->script('load', $script);
     * $redis->script('flush');
     * $redis->script('kill');
     * $redis->script('exists', $script1, [$script2, $script3, ...]);
     * </pre>
     *
     * SCRIPT LOAD will return the SHA1 hash of the passed script on success, and FALSE on failure.
     * SCRIPT FLUSH should always return TRUE
     * SCRIPT KILL will return true if a script was able to be killed and false if not
     * SCRIPT EXISTS will return an array with TRUE or FALSE for each passed script
     */
    public function script( $command, $script ) {}

    /**
     * The last error message (if any)
     * @return  string  A string with the last returned script based error message, or NULL if there is no error
     * @example
     * <pre>
     * $redis->eval('this-is-not-lua');
     * $err = $redis->getLastError();
     * // "ERR Error compiling script (new function): user_script:1: '=' expected near '-'"
     * </pre>
     */
    public function getLastError() {}

    /**
     * Clear the last error message
     *
     * @return bool true
     * @example
     * <pre>
     * $redis->set('x', 'a');
     * $redis->incr('x');
     * $err = $redis->getLastError();
     * // "ERR value is not an integer or out of range"
     * $redis->clearLastError();
     * $err = $redis->getLastError();
     * // NULL
     * </pre>
     */
    public function clearLastError() {}

    /**
     * A utility method to prefix the value with the prefix setting for phpredis.
     * @param  mixed $value  The value you wish to prefix
     * @return  string  If a prefix is set up, the value now prefixed.  If there is no prefix, the value will be returned unchanged.
     * @example
     * <pre>
     * $redis->setOption(Redis::OPT_PREFIX, 'my-prefix:');
     * $redis->_prefix('my-value'); // Will return 'my-prefix:my-value'
     * </pre>
     */
    public function _prefix( $value ) {}

    /**
     * A utility method to unserialize data with whatever serializer is set up.  If there is no serializer set, the
     * value will be returned unchanged.  If there is a serializer set up, and the data passed in is malformed, an
     * exception will be thrown. This can be useful if phpredis is serializing values, and you return something from
     * redis in a LUA script that is serialized.
     * @param   string  $value  The value to be unserialized
     * @return mixed
     * @example
     * <pre>
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
     * $redis->_unserialize('a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}'); // Will return Array(1,2,3)
     * </pre>
     */
    public function _unserialize( $value ) {}

    /**
     * Dump a key out of a redis database, the value of which can later be passed into redis using the RESTORE command.
     * The data that comes out of DUMP is a binary representation of the key as Redis stores it.
     * @param   string  $key
     * @return  string  The Redis encoded value of the key, or FALSE if the key doesn't exist
     * @link    http://redis.io/commands/dump
     * @example
     * <pre>
     * $redis->set('foo', 'bar');
     * $val = $redis->dump('foo'); // $val will be the Redis encoded key value
     * </pre>
     */
    public function dump( $key ) {}

    /**
     * Restore a key from the result of a DUMP operation.
     *
     * @param   string  $key    The key name
     * @param   int     $ttl    How long the key should live (if zero, no expire will be set on the key)
     * @param   string  $value  (binary).  The Redis encoded key value (from DUMP)
     * @return  bool
     * @link    http://redis.io/commands/restore
     * @example
     * <pre>
     * $redis->set('foo', 'bar');
     * $val = $redis->dump('foo');
     * $redis->restore('bar', 0, $val); // The key 'bar', will now be equal to the key 'foo'
     * </pre>
     */
    public function restore( $key, $ttl, $value ) {}

    /**
     * Migrates a key to a different Redis instance.
     *
     * @param   string  $host       The destination host
     * @param   int     $port       The TCP port to connect to.
     * @param   string  $key        The key to migrate.
     * @param   int     $db         The target DB.
     * @param   int     $timeout    The maximum amount of time given to this transfer.
     * @return  bool
     * @link    http://redis.io/commands/migrate
     * @example
     * <pre>
     * $redis->migrate('backup', 6379, 'foo', 0, 3600);
     * </pre>
     */
    public function migrate( $host, $port, $key, $db, $timeout ) {}

    /**
     * Return the current Redis server time.
     * @return  array If successfull, the time will come back as an associative array with element zero being the
     * unix timestamp, and element one being microseconds.
     * @link    http://redis.io/commands/time
     * <pre>
     * var_dump( $redis->time() );
     * // array(2) {
     * //   [0] => string(10) "1342364352"
     * //   [1] => string(6) "253002"
     * // }
     * </pre>
     */
    public function time() {}
}

class RedisException extends Exception {}

class RedisArray {
    /**
     * Constructor
     *
     * @param   string  $name   Name of the redis array to create (required if using redis.ini to define array)
     * @param   array   $hosts  Array of hosts to construct the array with
     * @param   array   $opts   Array of options
     * @link    https://github.com/nicolasff/phpredis/blob/master/arrays.markdown
     */
    function __construct($name = '', array $hosts = NULL, array $opts = NULL) {}

    /**
     * @return  array   list of hosts for the selected array
     */
    public function _hosts() {}

    /**
     * @return  string  the name of the function used to extract key parts during consistent hashing
     */
    public function _function() {}

    /**
     * @param   string  $key     The key for which you want to lookup the host
     * @return  string  the host to be used for a certain key
     */
    public function _target($key) {}

    /**
     * Use this function when a new node is added and keys need to be rehashed.
     */
    public function _rehash() {}
}
