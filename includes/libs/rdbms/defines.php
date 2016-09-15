<?php

/**@{
 * Database related constants
 */
define( 'DBO_DEBUG', 1 );
define( 'DBO_NOBUFFER', 2 );
define( 'DBO_IGNORE', 4 );
define( 'DBO_TRX', 8 ); // automatically start transaction on first query
define( 'DBO_DEFAULT', 16 );
define( 'DBO_PERSISTENT', 32 );
define( 'DBO_SYSDBA', 64 ); // for oracle maintenance
define( 'DBO_DDLMODE', 128 ); // when using schema files: mostly for Oracle
define( 'DBO_SSL', 256 );
define( 'DBO_COMPRESS', 512 );
/**@}*/

/**@{
 * Valid database indexes
 * Operation-based indexes
 */
define( 'DB_REPLICA', -1 );     # Read from a replica (or only server)
define( 'DB_MASTER', -2 );    # Write to master (or only server)
/**@}*/

/**@{
 * Flags for IDatabase::makeList()
 * These are also available as Database class constants
 */
define( 'LIST_COMMA', 0 );
define( 'LIST_AND', 1 );
define( 'LIST_SET', 2 );
define( 'LIST_NAMES', 3 );
define( 'LIST_OR', 4 );
/**@}*/
