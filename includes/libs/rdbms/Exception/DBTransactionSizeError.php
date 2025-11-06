<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * @newable
 * @ingroup Database
 */
class DBTransactionSizeError extends DBTransactionError {
	public function getKey(): string {
		return 'transaction-duration-limit-exceeded';
	}
}
