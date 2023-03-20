<?php

wfLoadExtension( "BlueSpiceTagCloud" );

$GLOBALS['bsgTagCloudTypeCategoryExclude'] = [
	'BPMN Association',
	'BPMN DataInputAssociation',
	'BPMN DataStoreReference',
	'BPMN EndEvent',
	'BPMN ExclusiveGateway',
	'BPMN IntermediateThrowEvent',
	'BPMN Lane',
	'BPMN Participant',
	'BPMN SequenceFlow',
	'BPMN StartEvent',
	'BPMN SubProcess',
	'BPMN TextAnnotation',
	'Imported vocabulary'
];
