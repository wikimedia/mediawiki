<?php
/** Sanskrit (संस्कृत)
  *
  * @addtogroup Language
  */
  
$fallback = hi;

$digitTransformTable = array(
	'0' => '०',
	'1' => '१',
	'2' => '२',
	'3' => '३',
	'4' => '४',
	'5' => '५',
	'6' => '६',
	'7' => '७',
	'8' => '८',
	'9' => '९',
);

$linkPrefixExtension = false;

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN	            => '',
	NS_TALK	            => 'संभाषणम्',
	NS_USER             => 'योजक',
	NS_USER_TALK        => 'योजकसंभाषणम्',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1संभाषणम्',
	NS_IMAGE            => 'चित्रम्',
	NS_IMAGE_TALK       => 'चित्रसंभाषणम्',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'उपकार',
	NS_HELP_TALK        => 'उपकारसंभाषणम्',
	NS_CATEGORY         => 'पदार्थ',
	NS_CATEGORY_TALK    => 'पदार्थसंभाषणम्',
);

$skinNames = array(
	'standard' => 'पूर्व',
	'nostalgia' => 'पुराण',
	'cologneblue' => 'नील',
	'davinci' => 'कालिदास',
	'mono' => 'Mono',
	'monobook' => 'पुस्तक',
	'myskin' => 'मे_चर्मन्',
	'chick' => 'Chick'
);

?>
