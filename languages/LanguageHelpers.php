<?php
class LanguageHelpers {

	public static function isRTL( $code ) {
		$RTL = [ 'aeb_arab', 'arc', 'ar', 'arz', 'azb', 'bcc', 'bgn', 'bqi',
			'ckb', 'dv', 'fa', 'glk', 'he', 'khw', 'kk_cn', 'kk_arab', 'ks_arab', 'ku_arab', 'lki',
			'lrc', 'luz', 'mzn', 'pnb', 'ps', 'sd', 'sdh', 'skr_arab', 'ug_arab', 'ur', 'yi'
		];
		return array_search( $code, $RTL );
	}
}
