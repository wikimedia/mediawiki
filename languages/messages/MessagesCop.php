<?php
/** Coptic (ϯⲙⲉⲧⲣⲉⲙⲛ̀ⲭⲏⲙⲓ)
 *
 * @file
 * @ingroup Languages
 *
 * @author Bloomaround
 * @author Amir E. Aharoni
 */

$namespaceNames = [
	NS_MEDIA            => 'Ⲫⲙⲉⲥⲟⲧⲏⲥ',
	NS_SPECIAL          => 'Ⲫⲁ_ϯⲟⲓⲕⲟⲛⲟⲙⲓⲁ̀',
	NS_TALK             => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ',
	NS_USER             => 'Ⲫⲣⲉϥⲉⲣⲭⲣⲁⲥⲑⲉ',
	NS_USER_TALK        => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲙ̀ⲫⲣⲉϥⲉⲣⲭⲣⲁⲥⲑⲉ',
	NS_PROJECT_TALK     => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲛ̀$1',
	NS_FILE             => 'Ⲡⲕⲟⲓϩⲓ',
	NS_FILE_TALK        => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲛ̀ⲕⲟⲓϩⲓ',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲙ̀MediaWiki',
	NS_TEMPLATE         => 'Ⲯⲙⲟⲧ',
	NS_TEMPLATE_TALK    => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲛ̀ⲥⲙⲟⲧ',
	NS_HELP             => 'Ⲫⲃⲟⲏⲑⲟⲥ',
	NS_HELP_TALK        => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲙ̀ⲃⲟⲏⲑⲟⲥ',
	NS_CATEGORY         => 'Ⲡⲅⲉⲛⲟⲥ',
	NS_CATEGORY_TALK    => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲛ̀ⲅⲉⲛⲟⲥ',
];

$namespaceGenderAliases = [
	NS_USER => [
		'male'   => 'Ⲫⲣⲉϥⲉⲣⲭⲣⲁⲥⲑⲉ',
		'female' => 'Ⲑⲣⲉϥⲉⲣⲭⲣⲁⲥⲑⲉ'
	],
	NS_USER_TALK => [
		'male'   => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲙ̀ⲫⲣⲉϥⲉⲣⲭⲣⲁⲥⲑⲉ',
		'female' => 'Ⲫⲙⲁ_ⲛ̀ⲥⲁϫⲓ_ⲛ̀ⲑⲣⲉϥⲉⲣⲭⲣⲁⲥⲑⲉ'
	],
];

$linkTrail = '/^([\x{2C80}-\x{2CFF}]+)(.*)$/sDu';
