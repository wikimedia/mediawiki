<?php
require_once('commandLine.inc');

$hits = intval($wgMemc->get("$wgDBname:stats:pcache_hit"));
$invalid = intval($wgMemc->get("$wgDBname:stats:pcache_miss_invalid"));
$expired = intval($wgMemc->get("$wgDBname:stats:pcache_miss_expired"));
$absent = intval($wgMemc->get("$wgDBname:stats:pcache_miss_absent"));
$total = $hits + $invalid + $expired + $absent;
printf( "hits:    %-10d %6.2f%%\n", $hits, $hits/$total*100 );
printf( "invalid: %-10d %6.2f%%\n", $invalid, $invalid/$total*100 );
printf( "expired: %-10d %6.2f%%\n", $expired, $expired/$total*100 );
printf( "absent:  %-10d %6.2f%%\n", $absent, $absent/$total*100 );
printf( "total:   %-10d %6.2f%%\n", $total, 100 );
?>
