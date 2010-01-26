<?php
include "../includes/StringUtils.php";

$pattern = "/('')+/";
$subject = str_repeat("'' ", 1024*1024 + 7);

$m = memory_get_usage();

$ps1 = preg_split($pattern, $subject);

$r = "";
foreach ($ps1 as $c) {
	$r .= $c . "|";
}
echo "Original preg_split: " . md5($r) . "  " . (memory_get_usage()-$m) . "\n";

unset($ps1);

$r = "";
$ps2 = StringUtils::preg_split($pattern, $subject);
foreach ($ps2 as $c) {
	$r .= $c . "|";
}
echo "StringUtils preg_split: " . md5($r) . "  " . (memory_get_usage()-$m) . "\n";
