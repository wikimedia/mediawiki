<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#
# Tatarish localisation for MediaWiki
#

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Maxsus',
	NS_MAIN             => '',
	NS_TALK             => 'Bäxäs',
	NS_USER             => 'Äğzä',
	NS_USER_TALK        => "Äğzä_bäxäse",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_bäxäse',
	NS_IMAGE            => "Räsem",
	NS_IMAGE_TALK       => "Räsem_bäxäse",
	NS_MEDIAWIKI        => "MediaWiki",
	NS_MEDIAWIKI_TALK   => "MediaWiki_bäxäse",
	NS_TEMPLATE         => "Ürnäk",
	NS_TEMPLATE_TALK    => "Ürnäk_bäxäse",
	NS_HELP             => "Yärdäm",
	NS_HELP_TALK        => "Yärdäm_bäxäse",
	NS_CATEGORY         => "Törkem",
	NS_CATEGORY_TALK    => "Törkem_bäxäse"
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y, H:i',
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
$magicWords = array(
#       ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#yünältü',                '#REDIRECT'),
	'notoc'                  => array( 0,    '__ETYUQ__',              '__NOTOC__'),
	'forcetoc'               => array( 0,    '__ETTIQ__',              '__FORCETOC__'),
	'toc'                    => array( 0,    '__ET__',                 '__TOC__'),
	'noeditsection'          => array( 0,    '__BÜLEMTÖZÄTÜYUQ__',     '__NOEDITSECTION__'),
	'start'                  => array( 0,    '__BAŞLAW__',             '__START__'),
	'currentmonth'           => array( 1,    'AĞIMDAĞI_AY',            'CURRENTMONTH'),
	'currentmonthname'       => array( 1,    'AĞIMDAĞI_AY_İSEME',      'CURRENTMONTHNAME'),
	'currentday'             => array( 1,    'AĞIMDAĞI_KÖN',           'CURRENTDAY'),
	'currentdayname'         => array( 1,    'AĞIMDAĞI_KÖN_İSEME',     'CURRENTDAYNAME'),
	'currentyear'            => array( 1,    'AĞIMDAĞI_YIL',           'CURRENTYEAR'),
	'currenttime'            => array( 1,    'AĞIMDAĞI_WAQIT',         'CURRENTTIME'),
	'numberofarticles'       => array( 1,    'MÄQÄLÄ_SANI',            'NUMBEROFARTICLES'),
	'currentmonthnamegen'    => array( 1,    'AĞIMDAĞI_AY_İSEME_GEN',  'CURRENTMONTHNAMEGEN'),
	'pagename'               => array( 1,    'BİTİSEME',               'PAGENAME'),
	'namespace'              => array( 1,    'İSEMARA',                'NAMESPACE'),
	'subst'                  => array( 0,    'TÖPÇEK:',                'SUBST:'),
	'msgnw'                  => array( 0,    'MSGNW:'                 ),
	'end'                    => array( 0,    '__AZAQ__',               '__END__'),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb'     ),
	'img_right'              => array( 1,    'uñda',                   'right'),
	'img_left'               => array( 1,    'sulda',                  'left'),
	'img_none'               => array( 1,    'yuq',                    'none'),
	'img_width'              => array( 1,    '$1px'                   ),
	'img_center'             => array( 1,    'center', 'centre'       ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' ),
	'int'                    => array( 0,    'EÇKE:',                   'INT:'),
	'sitename'               => array( 1,    'SÄXİFÄİSEME',            'SITENAME'),
	'ns'                     => array( 0,    'İA:',                    'NS:'),
	'localurl'               => array( 0,    'URINLIURL:',              'LOCALURL:'),
	'localurle'              => array( 0,    'URINLIURLE:',             'LOCALURLE:'),
	'server'                 => array( 0,    'SERVER'                 )
);

$fallback8bitEncoding = "windows-1254";

$messages = array(

# week days, months
'sunday' => "Yäkşämbe",
'monday' => "Düşämbe",
'tuesday' => "Sişämbe",
'wednesday' => "Çärşämbe",
'thursday' => "Pänceşämbe",
'friday' => "Comğa",
'saturday' => "Şimbä",
'january' => "Ğínwar",
'february' => "Febräl",
'march' => "Mart",
'april' => "Äpril",
'may_long' => "May",
'june' => "Yün",
'july' => "Yül",
'august' => "August",
'september' => "Sentäber",
'october' => "Öktäber",
'november' => "Nöyäber",
'december' => "Dekäber",
'jan' => "Ğín",
'feb' => "Feb",
'mar' => "Mar",
'apr' => "Äpr",
'may' => "May",
'jun' => "Yün",
'jul' => "Yül",
'aug' => "Aug",
'sep' => "Sen",
'oct' => "Ökt",
'nov' => "Nöy",
'dec' => "Dek",

);


?>
