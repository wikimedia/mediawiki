<?php

require_once( dirname(__FILE__).'/commandLine.inc' );
$i = $wgLoadBalancer->getReaderIndex();
print $wgDBservers[$i]['host'] . "\n";

?>
