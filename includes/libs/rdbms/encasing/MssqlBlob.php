<?php

namespace Wikimedia\Rdbms;

class MssqlBlob extends Blob {
	/** @noinspection PhpMissingParentConstructorInspection */

	/**
	 * @param string $data
	 */
	public function __construct( $data ) {
		if ( $data instanceof MssqlBlob ) {
			return $data;
		} elseif ( $data instanceof Blob ) {
			$this->data = $data->fetch();
		} elseif ( is_array( $data ) && is_object( $data ) ) {
			$this->data = serialize( $data );
		} else {
			$this->data = $data;
		}
	}

	/**
	 * Returns an unquoted hex representation of a binary string
	 * for insertion into varbinary-type fields
	 * @return string
	 */
	public function fetch() {
		if ( $this->data === null ) {
			return 'null';
		}

		$ret = '0x';
		$dataLength = strlen( $this->data );
		for ( $i = 0; $i < $dataLength; $i++ ) {
			$ret .= bin2hex( pack( 'C', ord( $this->data[$i] ) ) );
		}

		return $ret;
	}
}
