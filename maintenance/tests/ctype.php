<?php
define( 'MEDIAWIKI', true );
$cont = file_get_contents( '../../includes/compatability/ctype.php' );
$cont = preg_replace( '~^<\?php~', '', $cont );
preg_match_all( '~function (ctype_[a-z]+)~', $cont, $m );
$cont = preg_replace( '~(function )(ctype_)~', '\1_\2', $cont );
$cont = preg_replace( '~\?>$~', '', $cont );

eval( $cont );

foreach ( $m[1] as $function ) {
	$php = "$function";
	$mw = "_$function";
	$range = range( -1000, 1000 );
	foreach ( $range as $i ) {
		ret_cmp( $php, $i, $php( $i ), $mw( $i ) );
	}

	foreach ( $range as $i ) {
		$i = chr( $i );
		ret_cmp( $php, $i, $php( $i ), $mw( $i ) );
	}

	ret_cmp( $php, $i, $php( array() ), $mw( array() ) );
}

function ret_cmp( $fname, $in, $php, $mw ) {
	if ( $php != '' )
		return;
	if ( $php !== $mw )
		echo "PHP $fname() returned '" . serialize( $php ) . "' for '" . serialize( $in) . "', MediaWiki returned '" . serialize( $mw ) . "'\n";
}
?>
