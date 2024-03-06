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
	private array $values = [];

	public function __construct( $value, ...$values ) {
		if ( !is_string( $value ) && !( $value instanceof LikeMatch ) ) {
			throw new InvalidArgumentException( "Value $value must be either string or LikeMatch" );
		}
		$this->values = [ $value ];

		foreach ( $values as $value ) {
			if ( !is_string( $value ) && !( $value instanceof LikeMatch ) ) {
				throw new InvalidArgumentException( "Value $value must be either string or LikeMatch" );
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

	private function escapeLikeInternal( $s, $escapeChar = '`' ) {
		return str_replace(
			[ $escapeChar, '%', '_' ],
			[ "{$escapeChar}{$escapeChar}", "{$escapeChar}%", "{$escapeChar}_" ],
			$s
		);
	}
}
