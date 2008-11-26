<?php
// This file exists to ensure that base classes are preloaded before
// LanguageKu.php is compiled, working around a bug in the APC opcode
// cache on PHP 5, where cached code can break if the include order
// changed on a subsequent page view.
// see http://lists.wikimedia.org/pipermail/wikitech-l/2006-January/021311.html

$dir = dirname(__FILE__) . '/';
require_once( $dir . '../LanguageConverter.php' );
require_once( $dir . 'LanguageKu_ku.php' );
