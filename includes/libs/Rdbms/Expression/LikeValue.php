<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use Wikimedia\Rdbms\Database\DbQuoter;

/**
 * Content of like value
 *
 * @newable
 * @since 1.42
 */
class LikeValue {
	/** @var (string|LikeMatch)[] */
	private array $values = [];

	/**
	 * @param string|LikeMatch $value
	 * @param string|LikeMatch ...$values
	 */
	public function __construct( $value, ...$values ) {
		if ( !is_string( $value ) && !( $value instanceof LikeMatch ) ) {
			$type = get_debug_type( $value );
			throw new InvalidArgumentException( "\$value must be string or LikeMatch, got $type" );
		}
		$this->values = [ $value ];

		foreach ( $values as $value ) {
			if ( !is_string( $value ) && !( $value instanceof LikeMatch ) ) {
				$type = get_debug_type( $value );
				throw new InvalidArgumentException( "\$value must be string or LikeMatch, got $type" );
			}
			$this->values[] = $value;
		}
	}

	/**
	 * @internal to be used by rdbms library only
	 * @return-taint none
	 */
	public function toSql( DbQuoter $dbQuoter ): string {
		$s = '';

		// We use ` instead of \ as the default LIKE escape character, since addQuotes()
		// may escape backslashes, creating problems of double escaping. The `
		// character has good cross-DBMS compatibility, avoiding special operators
		// in MS SQL like ^ and %
		$escapeChar = '`';

		foreach ( $this->values as $value ) {
			if ( $value instanceof LikeMatch ) {
				$s .= $value->toString();
			} else {
				$s .= $this->escapeLikeInternal( $value, $escapeChar );
			}
		}

		return $dbQuoter->addQuotes( $s ) . ' ESCAPE ' . $dbQuoter->addQuotes( $escapeChar );
	}

	private function escapeLikeInternal( string $s, string $escapeChar = '`' ): string {
		return str_replace(
			[ $escapeChar, '%', '_' ],
			[ "{$escapeChar}{$escapeChar}", "{$escapeChar}%", "{$escapeChar}_" ],
			$s
		);
	}
}
