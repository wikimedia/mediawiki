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
 * Avro IO object classes
 * @package Avro
 */

/**
 * Exceptions associated with AvroIO instances.
 * @package Avro
 */
class AvroIOException extends AvroException {}

/**
 * Barebones IO base class to provide common interface for file and string
 * access within the Avro classes.
 *
 * @package Avro
 */
class AvroIO
{

  /**
   * @var string general read mode
   */
  const READ_MODE = 'r';
  /**
   * @var string general write mode.
   */
  const WRITE_MODE = 'w';

  /**
   * @var int set position equal to $offset bytes
   */
  const SEEK_CUR = SEEK_CUR;
  /**
   * @var int set position to current index + $offset bytes
   */
  const SEEK_SET = SEEK_SET;
  /**
   * @var int set position to end of file + $offset bytes
   */
  const SEEK_END = SEEK_END;

  /**
   * Read $len bytes from AvroIO instance
   * @var int $len
   * @return string bytes read
   */
  public function read($len)
  {
    throw new AvroNotImplementedException('Not implemented');
  }

  /**
   * Append bytes to this buffer. (Nothing more is needed to support Avro.)
   * @param str $arg bytes to write
   * @returns int count of bytes written.
   * @throws AvroIOException if $args is not a string value.
   */
  public function write($arg)
  {
    throw new AvroNotImplementedException('Not implemented');
  }

  /**
   * Return byte offset within AvroIO instance
   * @return int
   */
  public function tell()
  {
    throw new AvroNotImplementedException('Not implemented');
  }

  /**
   * Set the position indicator. The new position, measured in bytes
   * from the beginning of the file, is obtained by adding $offset to
   * the position specified by $whence.
   *
   * @param int $offset
   * @param int $whence one of AvroIO::SEEK_SET, AvroIO::SEEK_CUR,
   *                    or Avro::SEEK_END
   * @returns boolean true
   *
   * @throws AvroIOException
   */
  public function seek($offset, $whence=self::SEEK_SET)
  {
    throw new AvroNotImplementedException('Not implemented');
  }

  /**
   * Flushes any buffered data to the AvroIO object.
   * @returns boolean true upon success.
   */
  public function flush()
  {
    throw new AvroNotImplementedException('Not implemented');
  }

  /**
   * Returns whether or not the current position at the end of this AvroIO
   * instance.
   *
   * Note is_eof() is <b>not</b> like eof in C or feof in PHP:
   * it returns TRUE if the *next* read would be end of file,
   * rather than if the *most recent* read read end of file.
   * @returns boolean true if at the end of file, and false otherwise
   */
  public function is_eof()
  {
    throw new AvroNotImplementedException('Not implemented');
  }

  /**
   * Closes this AvroIO instance.
   */
  public function close()
  {
    throw new AvroNotImplementedException('Not implemented');
  }

}

/**
 * AvroIO wrapper for string access
 * @package Avro
 */
class AvroStringIO extends AvroIO
{
  /**
   * @var string
   */
  private $string_buffer;
  /**
   * @var int  current position in string
   */
  private $current_index;
  /**
   * @var boolean whether or not the string is closed.
   */
  private $is_closed;

  /**
   * @param string $str initial value of AvroStringIO buffer. Regardless
   *                    of the initial value, the pointer is set to the
   *                    beginning of the buffer.
   * @throws AvroIOException if a non-string value is passed as $str
   */
  public function __construct($str = '')
  {
    $this->is_closed = false;
    $this->string_buffer = '';
    $this->current_index = 0;

    if (is_string($str))
      $this->string_buffer .= $str;
    else
      throw new AvroIOException(
        sprintf('constructor argument must be a string: %s', gettype($str)));
  }

  /**
   * Append bytes to this buffer.
   * (Nothing more is needed to support Avro.)
   * @param str $arg bytes to write
   * @returns int count of bytes written.
   * @throws AvroIOException if $args is not a string value.
   */
  public function write($arg)
  {
    $this->check_closed();
    if (is_string($arg))
      return $this->append_str($arg);
    throw new AvroIOException(
      sprintf('write argument must be a string: (%s) %s',
              gettype($arg), var_export($arg, true)));
  }

  /**
   * @returns string bytes read from buffer
   * @todo test for fencepost errors wrt updating current_index
   */
  public function read($len)
  {
    $this->check_closed();
    $read='';
    for($i=$this->current_index; $i<($this->current_index+$len); $i++) 
      $read .= $this->string_buffer[$i];
    if (strlen($read) < $len)
      $this->current_index = $this->length();
    else
      $this->current_index += $len;
    return $read;
  }

  /**
   * @returns boolean true if successful
   * @throws AvroIOException if the seek failed.
   */
  public function seek($offset, $whence=self::SEEK_SET)
  {
    if (!is_int($offset))
      throw new AvroIOException('Seek offset must be an integer.');
    // Prevent seeking before BOF
    switch ($whence)
    {
      case self::SEEK_SET:
        if (0 > $offset)
          throw new AvroIOException('Cannot seek before beginning of file.');
        $this->current_index = $offset;
        break;
      case self::SEEK_CUR:
        if (0 > $this->current_index + $whence)
          throw new AvroIOException('Cannot seek before beginning of file.');
        $this->current_index += $offset;
        break;
      case self::SEEK_END:
        if (0 > $this->length() + $offset)
          throw new AvroIOException('Cannot seek before beginning of file.');
        $this->current_index = $this->length() + $offset;
        break;
      default:
        throw new AvroIOException(sprintf('Invalid seek whence %d', $whence));
    }

    return true;
  }

  /**
   * @returns int
   * @see AvroIO::tell()
   */
  public function tell() { return $this->current_index; }

  /**
   * @returns boolean
   * @see AvroIO::is_eof()
   */
  public function is_eof()
  {
    return ($this->current_index >= $this->length());
  }

  /**
   * No-op provided for compatibility with AvroIO interface.
   * @returns boolean true
   */
  public function flush() { return true; }

  /**
   * Marks this buffer as closed.
   * @returns boolean true
   */
  public function close()
  {
    $this->check_closed();
    $this->is_closed = true;
    return true;
  }

  /**
   * @throws AvroIOException if the buffer is closed.
   */
  private function check_closed()
  {
    if ($this->is_closed())
      throw new AvroIOException('Buffer is closed');
  }

  /**
   * Appends bytes to this buffer.
   * @param string $str
   * @returns integer count of bytes written.
   */
  private function append_str($str)
  { 
    $this->check_closed(); 
    $this->string_buffer .= $str; 
    $len = strlen($str); 
    $this->current_index += $len; 
    return $len; 
  } 

  /**
   * Truncates the truncate buffer to 0 bytes and returns the pointer
   * to the beginning of the buffer.
   * @returns boolean true
   */
  public function truncate()
  {
    $this->check_closed();
    $this->string_buffer = '';
    $this->current_index = 0;
    return true;
  }

  /**
   * @returns int count of bytes in the buffer
   * @internal Could probably memoize length for performance, but
   *           no need do this yet.
   */
  public function length() { return strlen($this->string_buffer); }

  /**
   * @returns string
   */
  public function __toString() { return $this->string_buffer; }


  /**
   * @returns string
   * @uses self::__toString()
   */
  public function string() { return $this->__toString(); }

  /**
   * @returns boolean true if this buffer is closed and false
   *                       otherwise.
   */
  public function is_closed() { return $this->is_closed; }
}

/**
 * AvroIO wrapper for PHP file access functions
 * @package Avro
 */
class AvroFile extends AvroIO
{
  /**
   * @var string fopen read mode value. Used internally.
   */
  const FOPEN_READ_MODE = 'rb';

  /**
   * @var string fopen write mode value. Used internally.
   */
  const FOPEN_WRITE_MODE = 'wb';

  /**
   * @var string
   */
  private $file_path;

  /**
   * @var resource file handle for AvroFile instance
   */
  private $file_handle;

  public function __construct($file_path, $mode = self::READ_MODE)
  {
    /**
     * XXX: should we check for file existence (in case of reading)
     * or anything else about the provided file_path argument?
     */
    $this->file_path = $file_path;
    switch ($mode)
    {
      case self::WRITE_MODE:
        $this->file_handle = fopen($this->file_path, self::FOPEN_WRITE_MODE);
        if (false == $this->file_handle)
          throw new AvroIOException('Could not open file for writing');
        break;
      case self::READ_MODE:
        $this->file_handle = fopen($this->file_path, self::FOPEN_READ_MODE);
        if (false == $this->file_handle)
          throw new AvroIOException('Could not open file for reading');
        break;
      default:
        throw new AvroIOException(
          sprintf("Only modes '%s' and '%s' allowed. You provided '%s'.",
                  self::READ_MODE, self::WRITE_MODE, $mode));
    }
  }

  /**
   * @returns int count of bytes written
   * @throws AvroIOException if write failed.
   */
  public function write($str)
  {
    $len = fwrite($this->file_handle, $str);
    if (false === $len)
      throw new AvroIOException(sprintf('Could not write to file'));
    return $len;
  }

  /**
   * @param int $len count of bytes to read.
   * @returns string bytes read
   * @throws AvroIOException if length value is negative or if the read failed
   */
  public function read($len)
  {
    if (0 > $len)
      throw new AvroIOException(
        sprintf("Invalid length value passed to read: %d", $len));

    if (0 == $len)
      return '';

    $bytes = fread($this->file_handle, $len);
    if (false === $bytes)
      throw new AvroIOException('Could not read from file');
    return $bytes;
  }

  /**
   * @returns int current position within the file
   * @throws AvroFileExcpetion if tell failed.
   */
  public function tell()
  {
    $position = ftell($this->file_handle);
    if (false === $position)
      throw new AvroIOException('Could not execute tell on reader');
    return $position;
  }

  /**
   * @param int $offset
   * @param int $whence
   * @returns boolean true upon success
   * @throws AvroIOException if seek failed.
   * @see AvroIO::seek()
   */
  public function seek($offset, $whence = SEEK_SET)
  {
    $res = fseek($this->file_handle, $offset, $whence);
    // Note: does not catch seeking beyond end of file
    if (-1 === $res)
      throw new AvroIOException(
        sprintf("Could not execute seek (offset = %d, whence = %d)",
                $offset, $whence));
    return true;
  }

  /**
   * Closes the file.
   * @returns boolean true if successful.
   * @throws AvroIOException if there was an error closing the file.
   */
  public function close()
  {
    $res = fclose($this->file_handle);
    if (false === $res)
      throw new AvroIOException('Error closing file.');
    return $res;
  }

  /**
   * @returns boolean true if the pointer is at the end of the file,
   *                  and false otherwise.
   * @see AvroIO::is_eof() as behavior differs from feof()
   */
  public function is_eof()
  {
    $this->read(1);
    if (feof($this->file_handle))
      return true;
    $this->seek(-1, self::SEEK_CUR);
    return false;
  }

  /**
   * @returns boolean true if the flush was successful.
   * @throws AvroIOException if there was an error flushing the file.
   */
  public function flush()
  {
    $res = fflush($this->file_handle);
    if (false === $res)
      throw new AvroIOException('Could not flush file.');
    return true;
  }

}
