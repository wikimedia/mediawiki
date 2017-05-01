<?php
class MssqlBlob extends Blob {
	public function __construct( $data ) {
		if ( $data instanceof MssqlBlob ) {
			return $data;
		} elseif ( $data instanceof Blob ) {
			$this->mData = $data->fetch();
		} elseif ( is_array( $data ) && is_object( $data ) ) {
			$this->mData = serialize( $data );
		} else {
			$this->mData = $data;
		}
	}

	/**
	 * Returns an unquoted hex representation of a binary string
	 * for insertion into varbinary-type fields
	 * @return string
	 */
	public function fetch() {
		if ( $this->mData === null ) {
			return 'null';
		}

		$ret = '0x';
		$dataLength = strlen( $this->mData );
		for ( $i = 0; $i < $dataLength; $i++ ) {
			$ret .= bin2hex( pack( 'C', ord( $this->mData[$i] ) ) );
		}

		return $ret;
	}
}
