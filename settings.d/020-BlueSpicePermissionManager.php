<?php
wfLoadExtension("BlueSpicePermissionManager");
$GLOBALS['bsgPermissionManagerActivePreset'] = 'private';
$GLOBALS['bsgOverridePermissionManagerAllowedPresets'] = [ 'public', 'protected', 'private' ];
