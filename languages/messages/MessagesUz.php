<?php
/** Uzbek (oʻzbekcha)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abdulla
 * @author Akmalzhon
 * @author Behzod Saidov <behzodsaidov@gmail.com>
 * @author Casual
 * @author CoderSI
 * @author Lyncos
 * @author Nataev
 * @author Sociologist
 * @author Urhixidur
 * @author Xexdof
 */

$fallback8bitEncoding = 'windows-1252';

$linkPrefixExtension = true;

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Maxsus',
	NS_TALK             => 'Munozara',
	NS_USER             => 'Foydalanuvchi',
	NS_USER_TALK        => 'Foydalanuvchi_munozarasi',
	NS_PROJECT_TALK     => '$1_munozarasi',
	NS_FILE             => 'Fayl',
	NS_FILE_TALK        => 'Fayl_munozarasi',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_munozarasi',
	NS_TEMPLATE         => 'Andoza',
	NS_TEMPLATE_TALK    => 'Andoza_munozarasi',
	NS_HELP             => 'Yordam',
	NS_HELP_TALK        => 'Yordam_munozarasi',
	NS_CATEGORY         => 'Turkum',
	NS_CATEGORY_TALK    => 'Turkum_munozarasi',
];

$namespaceAliases = [
	'Mediya'                => NS_MEDIA,
	'Tasvir'                => NS_FILE,
	'Tasvir_munozarasi'     => NS_FILE_TALK,
	'MediyaViki'            => NS_MEDIAWIKI,
	'MediyaViki_munozarasi' => NS_MEDIAWIKI_TALK,
	'Shablon'               => NS_TEMPLATE,
	'Shablon_munozarasi'    => NS_TEMPLATE_TALK,
	'Kategoriya'            => NS_CATEGORY,
	'Kategoriya_munozarasi' => NS_CATEGORY_TALK,
];

$magicWords = [
	'redirect'                  => [ '0', '#YOʻNALTIRISH', '#YONALTIRISH', '#REDIRECT' ],
	'notoc'                     => [ '0', '__ICHIDAGILARYOQ__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__GALEREYAYOQ__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__ICHIDAGILARMAJBURIY__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__ICHIDAGILARI__', '__ICHIDAGILAR__', '__TOC__' ],
	'noeditsection'             => [ '0', '__TAHRIRYOQ__', '__TARTIBLASHYOQ__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'JORIYOY', 'JORIYOY2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'JORIYOY1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'JORIYOYNOMI', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'JORIYOYNOMIQARATQICH', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'JORIYOYQISQARTMASI', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'JORIYKUN', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'JORIYKUN2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'JORIYKUNNOMI', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'JORIYYIL', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'JORIYVAQT', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'JORIYSOAT', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'MAHALLIYOY', 'MAHALLIYOY2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'MAHALLIYOY1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'MAHALLIYOYNOMI', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'MAHALLIYOYQARATQICH', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'MAHALLIYOYQISQARTMASI', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'MAHALLIYKUN', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'MAHALLIYKUN2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'MAHALLIYKUNNOMI', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'MAHALLIYYIL', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'MAHALLIYVAQT', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'MAHALLIYSOAT', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'SAHIFASONI', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'MAQOLASONI', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'FAYLSONI', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'FOYDALANUVCHISONI', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'FAOLFOYDALANUVCHISONI', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'OZGARISHSONI', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'SAHIFANOMI', 'PAGENAME' ],
	'namespace'                 => [ '1', 'NOMFAZO', 'NAMESPACE' ],
	'gender'                    => [ '0', 'JINS', 'GENDER:' ],
	'currentweek'               => [ '1', 'JORIYHAFTA', 'CURRENTWEEK' ],
	'language'                  => [ '0', '#TIL:', '#LANGUAGE:' ],
	'numberofadmins'            => [ '1', 'ADMINISTRATORSONI', 'NUMBEROFADMINS' ],
	'special'                   => [ '0', 'maxsus', 'special' ],
	'tag'                       => [ '0', 'yorliq', 'tag' ],
	'hiddencat'                 => [ '1', '__YASHIRINTURKUM__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'TURKUMDAGISAHIFALAR', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'SAHIFAHAJMI', 'PAGESIZE' ],
	'index'                     => [ '1', '__INDEKS__', '__INDEX__' ],
	'noindex'                   => [ '1', '__INDEKSYOQ__', '__NOINDEX__' ],
	'url_wiki'                  => [ '0', 'VIKI', 'WIKI' ],
];

$linkTrail = '/^([a-zʻʼ“»]+)(.*)$/sDu';
$linkPrefixCharset = 'a-zA-Z\\x80-\\xffʻʼ«„';

/**
 * Formats need to be overwritten. Others are inherited automatically
 */
$dateFormats = [
	'dmy date' => 'j-F Y',
	'dmy both' => 'H:i, j-F Y',
	'dmy pretty' => 'j-F'
];

/**
 * Transform table for decimal point '.' and thousands separator ','
 */
$separatorTransformTable = [
	'.' => ',',
	',' => "\u{00A0}", # nbsp
];
