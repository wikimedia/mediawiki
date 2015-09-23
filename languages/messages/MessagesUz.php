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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Mediya'                => NS_MEDIA,
	'Tasvir'                => NS_FILE,
	'Tasvir_munozarasi'     => NS_FILE_TALK,
	'MediyaViki'            => NS_MEDIAWIKI,
	'MediyaViki_munozarasi' => NS_MEDIAWIKI_TALK,
	'Shablon'               => NS_TEMPLATE,
	'Shablon_munozarasi'    => NS_TEMPLATE_TALK,
	'Kategoriya'            => NS_CATEGORY,
	'Kategoriya_munozarasi' => NS_CATEGORY_TALK,
);

$magicWords = array(
	'redirect'                  => array( '0', '#YOʻNALTIRISH', '#YONALTIRISH', '#REDIRECT' ),
	'notoc'                     => array( '0', '__ICHIDAGILARYOQ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__GALEREYAYOQ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ICHIDAGILARMAJBURIY__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ICHIDAGILARI__', '__ICHIDAGILAR__', '__TOC__' ),
	'noeditsection'             => array( '0', '__TAHRIRYOQ__', '__TARTIBLASHYOQ__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'JORIYOY', 'JORIYOY2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'JORIYOY1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'JORIYOYNOMI', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'JORIYOYNOMIQARATQICH', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'JORIYOYQISQARTMASI', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'JORIYKUN', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'JORIYKUN2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'JORIYKUNNOMI', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'JORIYYIL', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'JORIYVAQT', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'JORIYSOAT', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'MAHALLIYOY', 'MAHALLIYOY2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'MAHALLIYOY1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'MAHALLIYOYNOMI', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'MAHALLIYOYQARATQICH', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'MAHALLIYOYQISQARTMASI', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'MAHALLIYKUN', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'MAHALLIYKUN2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'MAHALLIYKUNNOMI', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'MAHALLIYYIL', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'MAHALLIYVAQT', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'MAHALLIYSOAT', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'SAHIFASONI', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'MAQOLASONI', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FAYLSONI', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'FOYDALANUVCHISONI', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'FAOLFOYDALANUVCHISONI', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'OZGARISHSONI', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'SAHIFANOMI', 'PAGENAME' ),
	'namespace'                 => array( '1', 'NOMFAZO', 'NAMESPACE' ),
	'gender'                    => array( '0', 'JINS', 'GENDER:' ),
	'currentweek'               => array( '1', 'JORIYHAFTA', 'CURRENTWEEK' ),
	'language'                  => array( '0', '#TIL:', '#LANGUAGE:' ),
	'numberofadmins'            => array( '1', 'ADMINISTRATORSONI', 'NUMBEROFADMINS' ),
	'special'                   => array( '0', 'maxsus', 'special' ),
	'tag'                       => array( '0', 'yorliq', 'tag' ),
	'hiddencat'                 => array( '1', '__YASHIRINTURKUM__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'TURKUMDAGISAHIFALAR', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'SAHIFAHAJMI', 'PAGESIZE' ),
	'index'                     => array( '1', '__INDEKS__', '__INDEX__' ),
	'noindex'                   => array( '1', '__INDEKSYOQ__', '__NOINDEX__' ),
	'url_wiki'                  => array( '0', 'VIKI', 'WIKI' ),
);

$linkTrail = '/^([a-zʻʼ“»]+)(.*)$/sDu';
$linkPrefixCharset = 'a-zA-Z\\x80-\\xffʻʼ«„';

/**
 * Formats need to be overwritten. Others are inherited automatically
 */
$dateFormats = array(
	'dmy date' => 'j-F Y',
	'dmy both' => 'H:i, j-F Y',
	'dmy pretty' => 'j-F'
);

/**
 * Transform table for decimal point '.' and thousands separator ','
 */
$separatorTransformTable = array(
	'.' => ',',
	',' => "\xc2\xa0", # nbsp
);

