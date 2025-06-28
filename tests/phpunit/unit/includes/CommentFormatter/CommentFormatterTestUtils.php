<?php

namespace MediaWiki\Tests\Unit\CommentFormatter;

class CommentFormatterTestUtils {
	public static function dumpArray( array $a ): string {
		$s = '';
		foreach ( $a as $k => $v ) {
			if ( $v === null ) {
				continue;
			}
			if ( $s !== '' ) {
				$s .= ', ';
			}
			if ( $v === true ) {
				$s .= $k;
			} elseif ( $v === false ) {
				$s .= "!$k";
			} else {
				$s .= "$k=$v";
			}
		}
		return $s;
	}
}
