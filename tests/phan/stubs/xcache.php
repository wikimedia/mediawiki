<?php

/**
 * Minimal set of classes necessary for XCache classes to be happy.
 * Methods as documented in https://xcache.lighttpd.net/wiki/XcacheApi
 * @codingStandardsIgnoreFile
 */

/**
 * @param string $name Key name
 *
 * @return mixed
 */
function xcache_get( $name ) {
}

/**
 * @param string $name Key name
 * @param mixed $value Value to store
 * @param int $ttl TTL in seconds
 *
 * @return bool TRUE on success, FALSE otherwise
 */
function xcache_set( $name, $value, $ttl = 0 ) {
}

/**
 * @param string $name Key name
 *
 * @return bool
 */
function xcache_unset( $name ) {
}

/**
 * @param string $name
 * @param mixed $value
 * @param int $ttl
 *
 * @return int
 */
function xcache_inc( $name, $value = 1, $ttl = 0 ) {
}

/**
 * @param string $name
 * @param mixed $value
 * @param int $ttl
 *
 * @return int
 */
function xcache_dec( $name, $value = 1, $ttl = 0 ) {
}
