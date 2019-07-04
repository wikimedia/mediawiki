<?php
return; // Disabled. Needs Parsoid
wfLoadExtension( "BlueSpiceVisualEditorConnector" );
$GLOBALS['bsgVisualEditorConnectorUploadDialogType'] = 'one-click';

$GLOBALS['wgUploadDialog']['fields']['categories'] = true;
$GLOBALS['wgUploadDialog']['format']['filepage'] = '$DESCRIPTION $CATEGORIES';
