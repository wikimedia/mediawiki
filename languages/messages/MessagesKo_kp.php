<?php
/** Korean (한국어(조선))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'ko';

$datePreferences = array(
	'default',
	'juche',
	'juche bracket',
	'ISO 8601',
);
$defaultDateFormat = 'ko';
$dateFormats = array(
	'ko time'            => 'H:i',
	'ko date'            => 'Y년 M월 j일 (D)',
	'ko both'            => 'Y년 M월 j일 (D) H:i',

	'juche time'         => 'H:i',
	'juche date'         => 'xoY년 M월 j일 (D)',
	'juche both'         => 'xoY년 M월 j일 (D) H:i',

	'juche bracket time' => 'H:i',
	'juche bracket date' => '주체xoY년 (Y년) M월 j일 (D)',
	'juche bracket both' => '주체xoY년 (Y년) M월 j일 (D) H:i',
);
