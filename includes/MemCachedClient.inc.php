<?php
/*
 * MemCached PHP client
 * Copyright (c) 2003
 * Ryan Gilfether <hotrodder@rocketmail.com>
 * http://www.gilfether.com
 *
 * Originally translated from Brad Fitzpatrick's <brad@danga.com> MemCached Perl client
 * See the memcached website:
 * http://www.danga.com/memcached/
 *
 * This module is Copyright (c) 2003 Ryan Gilfether.
 * All rights reserved.
 * You may distribute under the terms of the GNU General Public License
 * This is free software. IT COMES WITHOUT WARRANTY OF ANY KIND.
 *
 */

/**
 * version string
 */
define("MC_VERSION", "1.0.10");
/**
 * int, buffer size used for sending and receiving
 * data from sockets
 */
define("MC_BUFFER_SZ", 1024);
/**
 * MemCached error numbers
 */
define("MC_ERR_NOT_ACTIVE", 1001);	// no active servers
define("MC_ERR_SOCKET_WRITE", 1002);	// socket_write() failed
define("MC_ERR_SOCKET_READ", 1003);	// socket_read() failed
define("MC_ERR_SOCKET_CONNECT", 1004);	// failed to connect to host
define("MC_ERR_DELETE", 1005);		// delete() did not recieve DELETED command
define("MC_ERR_HOST_FORMAT", 1006);	// sock_to_host() invalid host format
define("MC_ERR_HOST_DEAD", 1007);	// sock_to_host() host is dead
define("MC_ERR_GET_SOCK", 1008);	// get_sock() failed to find a valid socket
define("MC_ERR_SET", 1009);		// _set() failed to receive the STORED response
define("MC_ERR_GET_KEY", 1010);		// _load_items no values returned for key(s)
define("MC_ERR_LOADITEM_END", 1011);	// _load_items failed to receive END response
define("MC_ERR_LOADITEM_BYTES", 1012);	// _load_items bytes read larger than bytes available


/**
 * MemCached PHP client Class.
 *
 * Communicates with the MemCached server, and executes the MemCached protocol
 * MemCached available at http://www.danga.com/memcached
 *
 * @author Ryan Gilfether <ryan@gilfether.com>
 * @package MemCachedClient
 * @access public
 * @version 1.0.10
 */
class MemCachedClient
{
    /**
     * array of servers no long available
     * @var array
     */
    var $host_dead;
    /**
     * array of open sockets
     * @var array
     */
    var $cache_sock;
    /**
     * determine if debugging is either on or off
     * @var bool
     */
    var $debug;
    /**
     * array of servers to attempt to use, "host:port" string format
     * @var array
     */
    var $servers;
    /**
     * count of currently active connections to servers
     * @var int
     */
    var $active;
    /**
     * error code if one is set
     * @var int
     */
    var $errno;
    /**
     * string describing error
     * @var string
     */
    var $errstr;
    /**
     * size of val to force compression; 0 turns off; defaults 1
     * @ var int
     */
    var $compress = 1;
    /**
     * temp flag to turn compression on/off; defaults on
     * @ var int
     */
    var $comp_active = 1;

    /**
     * array that contains parsed out buckets
     * @ var array
     */
    var $bucket;


    /**
     * Constructor
     *
     * Creates a new MemCachedClient object
	 * Takes one parameter, a array of options.  The most important key is
	 * $options["servers"], but that can also be set later with the set_servers()
	 * method.  The servers must be an array of hosts, each of which is
	 * either a scalar of the form <10.0.0.10:11211> or an array of the
	 * former and an integer weight value.  (the default weight if
	 * unspecified is 1.)  It's recommended that weight values be kept as low
	 * as possible, as this module currently allocates memory for bucket
	 * distribution proportional to the total host weights.
	 * $options["debug"] turns the debugging on if set to true
     *
     * @access public
     * @param array $option an array of servers and debug status
     * @return object MemCachedClient the new MemCachedClient object
     */
	function MemCachedClient($options = 0)
	{
		if(is_array($options))
		{
			$this->set_servers($options["servers"]);
			$this->debug = $options["debug"];
			$this->compress = $options["compress"];
			$this->cache_sock = array();
		}

		$this->errno = 0;
		$this->errstr = "";
	}


	/**
	 * sets up the list of servers and the ports to connect to
	 * takes an array of servers in the same format as in the constructor
	 *
	 * @access public
	 * @param array $servers array of servers in the format described in the constructor
	 */
	function set_servers($servers)
	{
		$this->servers = $servers;
		$this->active = count($this->servers);
	}


	/**
	 * if $do_debug is set to true, will print out
	 * debugging info, else debug is turned off
	 *
	 * @access public
	 * @param bool $do_debug set to true to turn debugging on, false to turn off
	 */
	function set_debug($do_debug)
	{
		$this->debug = $do_debug;
	}


	/**
	 * remove all cached hosts that are no longer good
	 *
	 * @access public
	 */
	function forget_dead_hosts()
	{
		unset($this->host_dead);
	}


	/**
	 * disconnects from all servers
	 *
	 * @access public
	 */
	function disconnect_all()
	{
		foreach($this->cache_sock as $sock)
			socket_close($sock);

		unset($this->cache_sock);
		$this->active = 0;
	}


	/**
	 * removes the key from the MemCache
	 * $time is the amount of time in seconds (or Unix time) until which
	 * the client wishes the server to refuse "add" and "replace" commands
	 * with this key. For this amount of item, the item is put into a
	 * delete queue, which means that it won't possible to retrieve it by
	 * the "get" command, but "add" and "replace" command with this key
	 * will also fail (the "set" command will succeed, however). After the
	 * time passes, the item is finally deleted from server memory.
	 * The parameter $time is optional, and, if absent, defaults to 0
	 * (which means that the item will be deleted immediately and further
	 * storage commands with this key will succeed).
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *		MC_ERR_DELETE
	 *
	 * @access public
	 * @param string $key the key to delete
	 * @param timestamp $time optional, the amount of time server will refuse commands on key
	 * @return bool TRUE on success, FALSE if key does not exist
	 */
	function delete($key, $time = 0)
	{
		if(!$this->active)
		{
			$this->errno = MC_ERR_NOT_ACTIVE;
			$this->errstr = "No active servers are available";

			if($this->debug)
				$this->_debug("delete(): There are no active servers available.");

			return FALSE;
		}

		$sock = $this->get_sock($key);

		if(!is_resource($sock))
		{
			$this->errno = MC_ERR_GET_SOCK;
			$this->errstr = "Unable to retrieve a valid socket.";

			if($this->debug)
				$this->_debug("delete(): get_sock() returned an invalid socket.");

			return FALSE;
		}

		if(is_array($key))
			$key = $key[1];

		$cmd = "delete $key $time\r\n";
		$cmd_len = strlen($cmd);
		$offset = 0;

		// now send the command
		while($offset < $cmd_len)
		{
			$result = socket_write($sock, substr($cmd, $offset, MC_BUFFER_SZ), MC_BUFFER_SZ);

			if($result !== FALSE)
				$offset += $result;
			else if($offset < $cmd_len)
			{
				$this->errno = MC_ERR_SOCKET_WRITE;
				$this->errstr = "Failed to write to socket.";

				if($this->debug)
				{
					$sockerr = socket_last_error($sock);
					$this->_debug("delete(): socket_write() returned FALSE. Socket Error $sockerr: ".socket_strerror($sockerr));
				}

				return FALSE;
			}
		}

		// now read the server's response
		if(($retval = socket_read($sock, MC_BUFFER_SZ, PHP_NORMAL_READ)) === FALSE)
		{
			$this->errno = MC_ERR_SOCKET_READ;
			$this->errstr = "Failed to read from socket.";

			if($this->debug)
			{
				$sockerr = socket_last_error($sock);
				$this->_debug("delete(): socket_read() returned FALSE. Socket Error $sockerr: ".socket_strerror($sockerr));
			}

			return FALSE;
		}

		// remove the \r\n from the end
		$retval = rtrim($retval);

		// now read the server's response
		if($retval == "DELETED")
			return TRUE;
		else
		{
			// something went wrong, create the error
			$this->errno = MC_ERR_DELETE;
			$this->errstr = "Failed to receive DELETED response from server.";

			if($this->debug)
				$this->_debug("delete(): Failed to receive DELETED response from server. Received $retval instead.");

			return FALSE;
		}
	}


	/**
	 * Like set(), but only stores in memcache if the key doesn't already exist.
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *		MC_ERR_SET
	 *
	 * @access public
	 * @param string $key the key to set
	 * @param mixed $val the value of the key
	 * @param timestamp $exptime optional, the to to live of the key
	 * @return bool TRUE on success, else FALSE
	 */
	function add($key, $val, $exptime = 0)
	{
		return $this->_set("add", $key, $val, $exptime);
	}


	/**
	 * Like set(), but only stores in memcache if the key already exists.
	 * returns TRUE on success else FALSE
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *		MC_ERR_SET
	 *
	 * @access public
	 * @param string $key the key to set
	 * @param mixed $val the value of the key
	 * @param timestamp $exptime optional, the to to live of the key
	 * @return bool TRUE on success, else FALSE
	 */
	function replace($key, $val, $exptime = 0)
	{
		return $this->_set("replace", $key, $val, $exptime);
	}


	/**
	 * Unconditionally sets a key to a given value in the memcache.  Returns true
	 * if it was stored successfully.
	 * The $key can optionally be an arrayref, with the first element being the
	 * hash value, as described above.
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *		MC_ERR_SET
	 *
	 * @access public
	 * @param string $key the key to set
	 * @param mixed $val the value of the key
	 * @param timestamp $exptime optional, the to to live of the key
	 * @return bool TRUE on success, else FALSE
	 */
	function set($key, $val, $exptime = 0)
	{
		return $this->_set("set", $key, $val, $exptime);
	}


	/**
	 * Retrieves a key from the memcache.  Returns the value (automatically
	 * unserialized, if necessary) or FALSE if it fails.
	 * The $key can optionally be an array, with the first element being the
	 * hash value, if you want to avoid making this module calculate a hash
	 * value.  You may prefer, for example, to keep all of a given user's
	 * objects on the same memcache server, so you could use the user's
	 * unique id as the hash value.
	 * Possible errors set are:
	 *		MC_ERR_GET_KEY
	 *
	 * @access public
	 * @param string $key the key to retrieve
	 * @return mixed the value of the key, FALSE on error
	 */
	function get($key)
	{
		$val =& $this->get_multi($key);

		if(!$val)
		{
			$this->errno = MC_ERR_GET_KEY;
			$this->errstr = "No value found for key $key";

			if($this->debug)
				$this->_debug("get(): No value found for key $key");

			return FALSE;
		}

		return $val[$key];
	}


	/**
	 * just like get(), but takes an array of keys, returns FALSE on error
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *
	 * @access public
	 * @param array $keys the keys to retrieve
	 * @return array the value of each key, FALSE on error
	 */
	function get_multi($keys)
	{
		$sock_keys = array();
		$socks = array();
		$val = 0;

		if(!$this->active)
		{
			$this->errno = MC_ERR_NOT_ACTIVE;
			$this->errstr = "No active servers are available";

			if($this->debug)
				$this->_debug("get_multi(): There are no active servers available.");

			return FALSE;
		}

		if(!is_array($keys))
		{
			$arr[] = $keys;
			$keys = $arr;
		}

		foreach($keys as $k)
		{
			$sock = $this->get_sock($k);

			if($sock)
			{
				$k = is_array($k) ? $k[1] : $k;

				if(@!is_array($sock_keys[$sock]))
					$sock_keys[$sock] = array();

				// if $sock_keys[$sock] doesn't exist, create it
				if(!$sock_keys[$sock])
					$socks[] = $sock;

				$sock_keys[$sock][] = $k;
			}
		}

		if(!is_array($socks))
		{
			$arr[] = $socks;
			$socks = $arr;
		}

		foreach($socks as $s)
		{
			$this->_load_items($s, $val, $sock_keys[$sock]);
		}

		if($this->debug)
		{
			while(list($k, $v) = @each($val))
				$this->_debug("MemCache: got $k = $v\n");
		}

		return $val;
	}


	/**
	 * Sends a command to the server to atomically increment the value for
	 * $key by $value, or by 1 if $value is undefined.  Returns FALSE if $key
	 * doesn't exist on server, otherwise it returns the new value after
	 * incrementing.  Value should be zero or greater.  Overflow on server
	 * is not checked.  Be aware of values approaching 2**32.  See decr.
	 * ONLY WORKS WITH NUMERIC VALUES
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *
	 * @access public
	 * @param string $key the keys to increment
	 * @param int $value the amount to increment the key bye
	 * @return int the new value of the key, else FALSE
	 */
	function incr($key, $value = 1)
	{
		return $this->_incrdecr("incr", $key, $value);
	}


	/**
	 * Like incr, but decrements.  Unlike incr, underflow is checked and new
	 * values are capped at 0.  If server value is 1, a decrement of 2
	 * returns 0, not -1.
	 * ONLY WORKS WITH NUMERIC VALUES
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *
	 * @access public
	 * @param string $key the keys to increment
	 * @param int $value the amount to increment the key bye
	 * @return int the new value of the key, else FALSE
	 */
	function decr($key, $value = 1)
	{
		return $this->_incrdecr("decr", $key, $value);
	}


	/**
	 * When a function returns FALSE, an error code is set.
	 * This funtion will return the error code.
	 * See error_string()
	 *
	 * @access public
	 * @return int the value of the last error code
	 */
	function error()
	{
		return $this->errno;
	}


	/**
	 * Returns a string describing the error set in error()
	 * See error()
	 *
	 * @access public
	 * @return int a string describing the error code given
	 */
	function error_string()
	{
		return $this->errstr;
	}


	/**
	 * Resets the error number and error string
	 *
	 * @access public
	 */
	function error_clear()
	{
		// reset to no error
		$this->errno = 0;
		$this->errstr = "";
	}


	/**
 	*	temporarily sets compression on or off
 	*	turning it off, and then back on will result in the compression threshold going
 	*	back to the original setting from $options
 	*	@param int $setting setting of compression (0=off|1=on)
 	*/

 	function set_compression($setting=1) {
 		if ($setting != 0) {
 			$this->comp_active = 1;
 		} else {
 			$this->comp_active = 0;
 		}
 	}



	/*
	 * PRIVATE FUNCTIONS
	 */


	/**
	 * connects to a server
	 * The $host may either a string int the form of host:port or an array of the
	 * former and an integer weight value.  (the default weight if
	 * unspecified is 1.) See the constructor for details
	 * Possible errors set are:
	 *		MC_ERR_HOST_FORMAT
	 *		MC_ERR_HOST_DEAD
	 *		MC_ERR_SOCKET_CONNECT
	 *
	 * @access private
	 * @param mixed $host either an array or a string
	 * @return resource the socket of the new connection, else FALSE
	 */
	function sock_to_host($host)
	{
		if(is_array($host))
			$host = array_shift($host);

		$now = time();

		// seperate the ip from the port, index 0 = ip, index 1 = port
		$conn = explode(":", $host);
		if(count($conn) != 2)
		{
			$this->errno = MC_ERR_HOST_FORMAT;
			$this->errstr = "Host address was not in the format of host:port";

			if($this->debug)
				$this->_debug("sock_to_host(): Host address was not in the format of host:port");

			return FALSE;
		}

		if(@($this->host_dead[$host] && $this->host_dead[$host] > $now) ||
		@($this->host_dead[$conn[0]] && $this->host_dead[$conn[0]] > $now))
		{
			$this->errno = MC_ERR_HOST_DEAD;
			$this->errstr = "Host $host is not available.";

			if($this->debug)
				$this->_debug("sock_to_host(): Host $host is not available.");

			return FALSE;
		}

		// connect to the server, if it fails, add it to the host_dead below
		$sock = socket_create (AF_INET, SOCK_STREAM, getprotobyname("TCP"));

		// we need surpress the error message if a connection fails
		if(!@socket_connect($sock, $conn[0], $conn[1]))
		{
			$this->host_dead[$host]=$this->host_dead[$conn[0]]=$now+60+intval(rand(0, 10));

			$this->errno = MC_ERR_SOCKET_CONNECT;
			$this->errstr = "Failed to connect to ".$conn[0].":".$conn[1];

			if($this->debug)
				$this->_debug("sock_to_host(): Failed to connect to ".$conn[0].":".$conn[1]);

			return FALSE;
		}

		// success, add to the list of sockets
		$cache_sock[$host] = $sock;

		return $sock;
	}


	/**
	 * retrieves the socket associated with a key
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *
	 * @access private
	 * @param string $key the key to retrieve the socket from
	 * @return resource the socket of the connection, else FALSE
	 */
	function get_sock($key)
	{
		if(!$this->active)
		{
			$this->errno = MC_ERR_NOT_ACTIVE;
			$this->errstr = "No active servers are available";

			if($this->debug)
				$this->_debug("get_sock(): There are no active servers available.");

			return FALSE;
		}

		$hv = is_array($key) ? intval($key[0]) : $this->_hashfunc($key);

		if(!$this->buckets)
		{
			$bu = $this->buckets = array();

			foreach($this->servers as $v)
			{
				if(is_array($v))
				{
					for($i = 1;  $i <= $v[1]; ++$i)
						$bu[] =  $v[0];
				}
				else
					$bu[] = $v;
			}

			$this->buckets = $bu;
		}

		$real_key = is_array($key) ? $key[1] : $key;
		$tries = 0;
		while($tries < 20)
		{
			$host = @$this->buckets[$hv % count($this->buckets)];
			$sock = $this->sock_to_host($host);

			if(is_resource($sock))
				return $sock;

			$hv += $this->_hashfunc($tries.$real_key);
			++$tries;
		}

		$this->errno = MC_ERR_GET_SOCK;
		$this->errstr = "Unable to retrieve a valid socket.";

		if($this->debug)
			$this->_debug("get_sock(): Unable to retrieve a valid socket.");

		return FALSE;
	}


	/**
	 * increments or decrements a numerical value in memcached. this function is
	 * called from incr() and decr()
	 * ONLY WORKS WITH NUMERIC VALUES
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *
	 * @access private
	 * @param string $cmdname the command to send, either incr or decr
	 * @param string $key the key to perform the command on
	 * @param mixed $value the value to incr or decr the key value by
	 * @return int the new value of the key, FALSE if something went wrong
	 */
	function _incrdecr($cmdname, $key, $value)
	{
		if(!$this->active)
		{
			$this->errno = MC_ERR_NOT_ACTIVE;
			$this->errstr = "No active servers are available";

			if($this->debug)
				$this->_debug("_incrdecr(): There are no active servers available.");

			return FALSE;
		}

		$sock = $this->get_sock($key);
		if(!is_resource($sock))
		{
			$this->errno = MC_ERR_GET_SOCK;
			$this->errstr = "Unable to retrieve a valid socket.";

			if($this->debug)
				$this->_debug("_incrdecr(): Invalid socket returned by get_sock().");

			return FALSE;
		}

		if($value == "")
			$value = 1;

		$cmd = "$cmdname $key $value\r\n";
		$cmd_len = strlen($cmd);
		$offset = 0;

		// write the command to the server
		while($offset < $cmd_len)
		{
			$result = socket_write($sock, substr($cmd, $offset, MC_BUFFER_SZ), MC_BUFFER_SZ);

			if($result !== FALSE)
				$offset += $result;
			else if($offset < $cmd_len)
			{
				$this->errno = MC_ERR_SOCKET_WRITE;
				$this->errstr = "Failed to write to socket.";

				if($this->debug)
				{
					$sockerr = socket_last_error($sock);
					$this->_debug("_incrdecr(): socket_write() returned FALSE. Error $errno: ".socket_strerror($sockerr));
				}

				return FALSE;
			}
		}

		// now read the server's response
		if(($retval = socket_read($sock, MC_BUFFER_SZ, PHP_NORMAL_READ)) === FALSE)
		{
			$this->errno = MC_ERR_SOCKET_READ;
			$this->errstr = "Failed to read from socket.";

			if($this->debug)
			{
				$sockerr = socket_last_error($sock);
				$this->_debug("_incrdecr(): socket_read() returned FALSE. Socket Error $errno: ".socket_strerror($sockerr));
			}

			return FALSE;
		}

		// strip the /r/n from the end and return value
		return trim($retval);
	}

	/**
	 * sends the command to the server
	 * Possible errors set are:
	 *		MC_ERR_NOT_ACTIVE
	 *		MC_ERR_GET_SOCK
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *		MC_ERR_SET
	 *
	 * @access private
	 * @param string $cmdname the command to send, either incr or decr
	 * @param string $key the key to perform the command on
	 * @param mixed $value the value to set the key to
	 * @param timestamp $exptime expiration time of the key
	 * @return bool TRUE on success, else FALSE
	 */
	function _set($cmdname, $key, $val, $exptime = 0)
	{
		if(!$this->active)
		{
			$this->errno = MC_ERR_NOT_ACTIVE;
			$this->errstr = "No active servers are available";

			if($this->debug)
				$this->_debug("_set(): No active servers are available.");

			return FALSE;
		}

		$sock = $this->get_sock($key);
		if(!is_resource($sock))
		{
			$this->errno = MC_ERR_GET_SOCK;
			$this->errstr = "Unable to retrieve a valid socket.";

			if($this->debug)
				$this->_debug("_set(): Invalid socket returned by get_sock().");

			return FALSE;
		}

		$flags = 0;
		$key = is_array($key) ? $key[1] : $key;

		$raw_val = $val;

		// if the value is not scalar, we need to serialize it
		if(!is_scalar($val))
		{
			$val = serialize($val);
			$flags |= 1;
		}

		if (($this->compress_active) && ($this->compress > 0) && (strlen($val) > $this->compress)) {
			$this->_debug("_set(): compressing data. size in:".strlen($val));
			$cval=gzcompress($val);
			$this->_debug("_set(): done compressing data. size out:".strlen($cval));
			if ((strlen($cval) < strlen($val)) && (strlen($val) - strlen($cval) > 2048)){
				$flags |= 2;
				$val=$cval;
			}
			unset($cval);
		}

		$len = strlen($val);
		if (!is_int($exptime))
			$exptime = 0;

		// send off the request
		$cmd = "$cmdname $key $flags $exptime $len\r\n$val\r\n";
		$cmd_len = strlen($cmd);
		$offset = 0;

		// write the command to the server
		while($offset < $cmd_len)
		{
			$result = socket_write($sock, substr($cmd, $offset, MC_BUFFER_SZ), MC_BUFFER_SZ);

			if($result !== FALSE)
				$offset += $result;
			else if($offset < $cmd_len)
			{
				$this->errno = MC_ERR_SOCKET_WRITE;
				$this->errstr = "Failed to write to socket.";

				if($this->debug)
				{
					$errno = socket_last_error($sock);
					$this->_debug("_set(): socket_write() returned FALSE. Error $errno: ".socket_strerror($errno));
				}

				return FALSE;
			}
		}

		// now read the server's response
		if(($l_szResponse = socket_read($sock, 6, PHP_NORMAL_READ)) === FALSE)
		{
			$this->errno = MC_ERR_SOCKET_READ;
			$this->errstr = "Failed to read from socket.";

			if($this->debug)
			{
				$errno = socket_last_error($sock);
				$this->_debug("_set(): socket_read() returned FALSE. Error $errno: ".socket_strerror($errno));
			}

			return FALSE;
		}

		if($l_szResponse == "STORED")
		{
			if($this->debug)
				$this->_debug("MemCache: $cmdname $key = $raw_val");

			return TRUE;
		}

		$this->errno = MC_ERR_SET;
		$this->errstr = "Failed to receive the STORED response from the server.";

		if($this->debug)
			$this->_debug("_set(): Did not receive STORED as the server response! Received $l_szResponse instead.");

		return FALSE;
	}


	/**
	 * retrieves the value, and returns it unserialized
	 * Possible errors set are:
	 *		MC_ERR_SOCKET_WRITE
	 *		MC_ERR_SOCKET_READ
	 *		MC_ERR_GET_KEY
	 *		MC_ERR_LOADITEM_END
	 *		MC_ERR_LOADITEM_BYTES
	 *
	 * @access private
	 * @param resource $sock the socket to connection we are retriving from
	 * @param array $val reference to the values retrieved
	 * @param mixed $sock_keys either a string or an array of keys to retrieve
	 * @return array TRUE on success, else FALSE
	 */
	function _load_items($sock, &$val, $sock_keys)
	{
		$val = array();
		$cmd = "get ";

		if(!is_array($sock_keys))
		{
			$arr[] = $sock_keys;
			$sock_keys = $arr;
		}

		foreach($sock_keys as $sk)
			$cmd .= $sk." ";

		$cmd .="\r\n";
		$cmd_len = strlen($cmd);
		$offset = 0;

		// write the command to the server
		while($offset < $cmd_len)
		{
			$result = socket_write($sock, substr($cmd, $offset, MC_BUFFER_SZ), MC_BUFFER_SZ);

			if($result !== FALSE)
				$offset += $result;
			else if($offset < $cmd_len)
			{
				$this->errno = MC_ERR_SOCKET_WRITE;
				$this->errstr = "Failed to write to socket.";

				if($this->debug)
				{
					$errno = socket_last_error($sock);
					$this->_debug("_load_items(): socket_write() returned FALSE. Error $errno: ".socket_strerror($errno));
				}

				return FALSE;
			}
		}

		$len = 0;
		$buf = "";
		$flags_array = array();

		// now read the response from the server
		while($line = socket_read($sock, MC_BUFFER_SZ, PHP_BINARY_READ))
		{
			// check for a socket_read error
			if($line === FALSE)
			{
				$this->errno = MC_ERR_SOCKET_READ;
				$this->errstr = "Failed to read from socket.";

				if($this->debug)
				{
					$errno = socket_last_error($sock);
					$this->_debug("_load_items(): socket_read() returned FALSE. Error $errno: ".socket_strerror($errno));
				}

				return FALSE;
			}

			if($len == 0)
			{
				$header = substr($line, 0, strpos($line, "\r\n"));
				$matches = explode(" ", $header);

				if(is_string($matches[1]) && is_numeric($matches[2]) && is_numeric($matches[3]))
				{
					$rk = $matches[1];
					$flags = $matches[2];
					$len = $matches[3];

					if($flags)
						$flags_array[$rk] = $flags;

					$len_array[$rk] = $len;
					$bytes_read = 0;

					// get the left over data after the header is read
					$line = substr($line, strpos($line, "\r\n")+2, strlen($line));
				}
				else
				{
					$this->errno = MC_ERR_GET_KEY;
					$this->errstr = "Requested key(s) returned no values.";

					// something went wrong, we never recieved the header
					if($this->debug)
						$this->_debug("_load_items(): Requested key(s) returned no values.");

					return FALSE;
				}
			}

			// skip over the extra return or newline
			if($line == "\r" || $line == "\n")
				continue;

			$bytes_read += strlen($line);
			$buf .= $line;

			// if we're almost at the end, read the rest, so 
			// that we don't corrupt the \r\nEND\r\n
			if ($bytes_read >= $len && $bytes_read < ($len +7))
			{
				$lastbit = socket_read($sock, $len - $bytes_read + 7, PHP_BINARY_READ);
				$line .= $lastbit;
				$buf .= $lastbit;
				$bytes_read += strlen($lastbit);
			}

			// we read the all of the data, take in account
			// for the /r/nEND/r/n
			if($bytes_read == ($len + 7))
			{
				$end = substr($buf, $len+2, 3);
				if($end == "END")
				{
					$val[$rk] = substr($buf, 0, $len);

					foreach($sock_keys as $sk)
					{
						if(!isset($val[$sk]))
							continue;

						if(strlen($val[$sk]) != $len_array[$sk])
							continue;
						if(@$flags_array[$sk] & 2)
							$val[$sk] = gzuncompress($val[$sk]);

						if(@$flags_array[$sk] & 1)
							$val[$sk] = unserialize($val[$sk]);
					}

					return TRUE;
				}
				else
				{
					$this->errno = MC_ERR_LOADITEM_END;
					$this->errstr = "Failed to receive END response from server.";

					if($this->debug)
						$this->_debug("_load_items(): Failed to receive END. Received $end instead.");

					return FALSE;
				}
			}

			// take in consideration for the "\r\nEND\r\n"
			if($bytes_read > ($len + 7))
			{
				$this->errno = MC_ERR_LOADITEM_BYTES;
				$this->errstr = "Bytes read from server greater than size of data.";

				if($this->debug)
					$this->_debug("_load_items(): Bytes read is greater than requested data size.");

				return FALSE;
			}

		}
	}


	/**
	 * creates our hash
	 *
	 * @access private
	 * @param int $num
	 * @return hash
	 */
	function _hashfunc($num)
	{
		$hash = sprintf("%u",crc32($num));

		return $hash;
	}

    /**
     * function that can be overridden to handle debug output
     * by default debug info is print to the screen
     *
     * @access private
     * @param $text string to output debug info
     */
    function _debug($text)
    {
		print $text . "\r\n";
    }
}

?>
