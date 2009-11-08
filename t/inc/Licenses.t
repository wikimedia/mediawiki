#!/usr/bin/env php
<?php
require 't/Test.php';

plan(3);

error_reporting( E_ALL );

define( 'MEDIAWIKI', 1 ); // Hack

require_ok( 'languages/Language.php' );
require_ok( 'includes/GlobalFunctions.php' );
require_ok( 'includes/HTMLForm.php' );
require_ok( 'includes/Licenses.php' );

$str = "
* Free licenses:
** GFLD|Debian disagrees
";

#$lc = new Licenses ( $str );

#isa_ok( $lc, 'Licenses' );

#echo $lc->html;

/* vim: set filetype=php: */
