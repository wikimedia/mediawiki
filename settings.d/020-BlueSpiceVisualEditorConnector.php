<?php
return; // Disabled. Needs Parsoid
wfLoadExtension( "BlueSpiceVisualEditorConnector" );
$GLOBALS['bsgVisualEditorConnectorUploadDialogType'] = 'simple';

$GLOBALS['wgUploadDialog']['fields']['categories'] = true;
$GLOBALS['wgUploadDialog']['format']['filepage'] = '$DESCRIPTION $CATEGORIES';
