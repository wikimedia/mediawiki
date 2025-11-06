<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * @since 1.39
 * @ingroup Database
 */
class QueryStatus {
	/** @var ResultWrapper|bool|null Result set */
	public $res;
	/** @var int Returned row count */
	public $rowsReturned;
	/** @var int Affected row count */
	public $rowsAffected;
	/** @var string Error message or empty string */
	public $message;
	/** @var int|string Error code or zero */
	public $code;
	/** @var int Error flag bit field of Database::ERR_* constants */
	public $flags;

	/**
	 * @internal Should only be constructed by Database
	 *
	 * @param ResultWrapper|bool|null $res
	 * @param int $affected
	 * @param string $error
	 * @param int|string $errno
	 */
	public function __construct( $res, int $affected, string $error, $errno ) {
		if ( !( $res instanceof IResultWrapper ) && !is_bool( $res ) && $res !== null ) {
			throw new DBUnexpectedError(
				null,
				'Got ' . get_debug_type( $res ) . ' instead of IResultWrapper|bool'
			);
		}

		$this->res = $res;
		$this->rowsReturned = ( $res instanceof IResultWrapper ) ? $res->numRows() : 0;
		$this->rowsAffected = $affected;
		$this->message = $error;
		$this->code = $errno;
		$this->flags = 0;
	}
}
