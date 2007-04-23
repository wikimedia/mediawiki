<?php
/** Sanskrit (संस्कृत)
  *
  * @addtogroup Language
  */

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
	NS_TALK	            => 'संभाषणं',
	NS_USER             => 'योजकः',
	NS_USER_TALK        => 'योजकसंभाषणं',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1संभाषणं',
	NS_IMAGE            => 'चित्रं',
	NS_IMAGE_TALK       => 'चित्रसंभाषणं',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'उपकारः',
	NS_HELP_TALK        => 'उपकारसंभाषणं',
	NS_CATEGORY         => 'वर्गः',
	NS_CATEGORY_TALK    => 'वर्गसंभाषणं',
);

$skinNames = array(
	'standard' => 'पूर्व',
	'nostalgia' => 'पुराण',
	'cologneblue' => 'नील',
	'davinci' => 'कालिदास',
	'mono' => 'Mono',
	'monobook' => 'पुस्तक',
	'myskin' => 'मे चर्मन्',
	'chick' => 'Chick'
);

$messages = array(
# dates
'sunday' => 'विश्रामवासरे',
'monday' => 'सोमवासरे',
'tuesday' => 'मंगलवासरे',
'wednesday' => 'बुधवासरे',
'thursday' => 'गुरुवासरे',
'friday' => 'शुक्रवासरे',
'saturday' => 'शनिवासरे',
'sun' => 'विश्राम',
'mon' => 'सोम',
'tue' => 'मंगल',
'wed' => 'बुध',
'thu' => 'गुरु',
'fri' => 'शुक्र',
'sat' => 'शनि',
'january' => 'पौषमाघे',
'february' => 'फाल्गुने',
'march' => 'फाल्गुनचैत्रे',
'april' => 'मधुमासे',
'may_long' => 'वैशाखज्येष्ठे',
'june' => 'ज्येष्ठाषाढके',
'july' => 'आषाढश्रावणे',
'august' => 'नभस्ये',
'september' => 'भाद्रपदाश्विने',
'october' => 'अश्विनकार्तिके',
'november' => 'कार्तिकमार्गशीर्षे',
'december' => 'मार्गशीर्षपौषे',
);

?>
