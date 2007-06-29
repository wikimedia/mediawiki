<?php
/**
 * splitLanguageFiles
 * Should read each of the languages files then split them in several subpart
 * under ./languages/XX/ according to the arrays in splitLanguageFiles.inc .
 *
 * Also need to rewrite the wfMsg system / message-cache.
 */

include(dirname(__FILE__).'/../commandLine.inc');



