<?php
/** Buginese (Buginese script)
 *
 * @file
 * @ingroup Languages
 *
 * @author ToluAyo
 */

$fallback = 'id';

$namespaceNames = [
	NS_MEDIA          => 'ᨆᨙᨉᨗᨐ',
	NS_SPECIAL        => 'ᨆᨒᨛᨅᨗ',
	NS_TALK           => 'ᨄᨊᨛᨊ',
	NS_USER           => 'ᨄᨁᨘᨊ',
	NS_USER_TALK      => 'ᨄᨊᨛᨊ_ᨄᨁᨘᨊ',
	NS_PROJECT_TALK   => 'ᨄᨊᨛᨊ_$1',
	NS_FILE           => 'ᨕᨈᨑᨚ',
	NS_FILE_TALK      => 'ᨄᨊᨛᨊ_ᨕᨈᨑᨚ',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'ᨄᨊᨛᨊ_MediaWiki',
	NS_TEMPLATE       => 'ᨕᨌᨘᨓ',
	NS_TEMPLATE_TALK  => 'ᨄᨊᨛᨊ_ᨕᨌᨘᨓ',
	NS_HELP           => 'ᨅᨈᨘᨂᨛ',
	NS_HELP_TALK      => 'ᨄᨊᨛᨊ_ᨅᨈᨘᨂᨛ',
	NS_CATEGORY       => 'ᨀᨈᨁᨚᨑᨗ',
	NS_CATEGORY_TALK  => 'ᨄᨊᨛᨊ_ᨀᨈᨁᨚᨑᨗ',
];

$linkTrail = '/^([a-z\x{1A00}-\x{1A1F}]+)(.*)$/sDu';
