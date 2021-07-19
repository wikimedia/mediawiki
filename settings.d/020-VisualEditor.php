<?php

//Config decription can be found here:
//https://www.mediawiki.org/wiki/Extension:VisualEditor
wfLoadExtension( 'VisualEditor' );

// Enable by default for everybody
$wgDefaultUserOptions['visualeditor-enable'] = 1;
$wgDefaultUserOptions['visualeditor-enable-experimental'] = 1;
$wgDefaultUserOptions['visualeditor-newwikitext'] = 1;

// Optional: Set VisualEditor as the default for anonymous users
// otherwise they will have to switch to VE
// $wgDefaultUserOptions['visualeditor-editor'] = "visualeditor";

// Don't allow users to disable it
$wgHiddenPrefs[] = 'visualeditor-enable';
$wgHiddenPrefs[] = 'visualeditor-newwikitext';

// Per default, the VisualEditor only works with Namespace 0 (NS_MAIN),
// i.e. the main article namespace. To change this, adapt the following example
// that enables namespaces 0 (main), 2 (user) and 102 (some user specific one)
$wgVisualEditorAvailableNamespaces = [
    NS_MAIN => true,
    NS_USER => true,
    102 => true,
    "_merge_strategy" => "array_plus"
];

$wgVisualEditorEnableWikitext = true;
$wgVisualEditorShowBetaWelcome = false;
