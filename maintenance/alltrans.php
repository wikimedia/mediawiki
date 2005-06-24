<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once('commandLine.inc');

foreach(array_keys($wgAllMessagesEn) as $key)
	echo "$key\n";
?>
