<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * Exception class for attempted DB write access to a DBConnRef with the DB_REPLICA role
 *
 * @newable
 * @ingroup Database
 */
class DBReadOnlyRoleError extends DBUnexpectedError {
}
