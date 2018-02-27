<?php
class LanguageHelpers {
	const $RTL = [ 'aeb_arab', 'arc', 'ar', 'arz', 'azb', 'bcc', 'bgn', 'bqi',
		'ckb', 'dv', 'fa', 'glk', 'he', 'khw', 'kk_cn', 'kk_arab', 'ks_arab', 'ku_arab', 'lki',
		'lrc', 'luz', 'mzn', 'pnb', 'ps', 'sd', 'sdh', 'skr_arab', 'ug_arab', 'ur', 'yi'
	];

	public static function isRTL( $code ) {
		return array_search( $code, $RTL );
	}
}