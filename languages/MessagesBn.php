<?php
/** Bengali (বাংলা)
  *
  * @package MediaWiki
  * @subpackage Language
  */

$namespaceNames = array(
	NS_SPECIAL        => 'বিশেষ',
	NS_MAIN           => '',
	NS_TALK           => 'আলাপ',
	NS_USER           => 'ব্যবহারকারী',
	NS_USER_TALK      => 'ব্যবহারকারী_আলাপ',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_আলাপ',
	NS_IMAGE          => 'চিত্র',
	NS_IMAGE_TALK     => 'চিত্র_আলাপ',
	NS_MEDIAWIKI_TALK => 'MediaWiki_আলাপ'
);
$datePreferences = false;
$digitTransformTable = array(
	'0' => '০',
	'1' => '১',
	'2' => '২',
	'3' => '৩',
	'4' => '৪',
	'5' => '৫',
	'6' => '৬',
	'7' => '৭',
	'8' => '৮',
	'9' => '৯'
);


$messages = array(
# Dates

'sunday' => 'রবিবার',
'monday' => 'সোমবার',
'tuesday' => 'মঙ্গলবার',
'wednesday' => 'বুধবার',
'thursday' => 'বৃহস্পতিবার',
'friday' => 'শুক্রবার',
'saturday' => 'শনিবার',
'january' => 'জানুয়ারী',
'february' => 'ফেব্রুয়ারী',
'march' => 'মার্চ',
'april' => 'এপ্রিল',
'may_long' => 'মে',
'june' => 'জুন',
'july' => 'জুলাই',
'august' => 'আগস্ট',
'september' => 'সেপ্টেম্বর',
'october' => 'অক্টোবর',
'november' => 'নভেম্বর',
'december' => 'ডিসেম্বর',
'jan' => 'জানু',
'feb' => 'ফেব্রু',
'mar' => 'মার্চ',
'apr' => 'এপ্রিল',
'may' => 'মে',
'jun' => 'জুন',
'jul' => 'জুলাই',
'aug' => 'আগস্ট',
'sep' => 'সেপ্টে',
'oct' => 'অক্টো',
'nov' => 'নভে',
'dec' => 'ডিসে',

# Bits of text used by many pages:
#

'mainpage'    => 'প্রধান পাতা',
'about'     => 'বৃত্তান্ত',
'aboutsite'      => '{{SITENAME}}র বৃত্তান্ত', // TODO: grammar
'aboutpage'   => '{{ns:project}}:বৃত্তান্ত',
'help'      => 'সহায়িকা',
'helppage'    => '{{ns:project}}:সহায়িকা',
'bugreports'  => 'ত্রুটি বিবরণী',
'bugreportspage' => '{{ns:project}}:ত্রুটি_বিবরণী',
'faq'     => 'প্রশ্নোত্তর',
'faqpage'   => '{{ns:project}}:প্রশ্নোত্তর',
'edithelp'    => 'সম্পাদনা সহায়িকা',
'edithelppage'  => '{{ns:project}}:কিভাবে_একটি_পৃষ্ঠা_সম্পাদনা_করবেন',
'cancel'    => 'বাতিল কর',
'qbfind'    => 'খঁুজে দেখ',
'qbbrowse'    => 'ঘুরে দেখ',
'qbedit'    => 'সম্পাদনা কর',
'qbpageoptions' => 'এ পৃষ্ঠার বিকল্পসমূহ',
'qbpageinfo'  => 'পৃষ্ঠা-সংক্রান্ত তথ্য',
'qbmyoptions' => 'আমার পছন্দ',
'mypage'    => 'আমার পাতা',
'mytalk'    => 'আমার কথাবার্তা',
'currentevents' => 'সমসাময়িক ঘটনা',
'errorpagetitle' => 'ভুল',
'returnto'    => 'ফিরে যাও $1.',
'whatlinkshere' => 'যেসব পাতা থেকে এখানে সংযোগ আছে',
'help'      => 'সহায়িকা',
'search'    => 'খঁুজে দেখ',
'searchbutton'    => 'খঁুজে দেখ',
'go'    => 'চল',
'history'   => 'এ পৃষ্ঠার ইতিহাস',
'printableversion' => 'ছাপার যোগ্য সংস্করণ',
'editthispage'  => 'এই পৃষ্ঠাটি সম্পাদনা করুন',
'deletethispage' => 'এই পৃষ্ঠাটি মুছে ফেলুন',
'protectthispage' => 'এই পৃষ্ঠাটি সংরক্ষণ করুন',
'unprotectthispage' => 'এই পৃষ্ঠার সংরক্ষণ ছেড়ে দিন',
'newpage' => 'নতুন পাতা',
'talkpage'    => 'এই পৃষ্ঠা নিয়ে আলোচনা করুন',
'articlepage' => 'নিবন্ধ দেখুন',
'userpage' => 'ব্যাবহারকারীর পাতা দেখুন',
'projectpage' => 'মেটা-পাতা দেখুন',
'imagepage' =>  'ছবির পাতা দেখুন',
'viewtalkpage' => 'আলোচনা দেখুন',
'otherlanguages' => 'অন্যান্য ভাষা',
'redirectedfrom' => '($1 থেকে ঘুরে এসেছে)',
'lastmodifiedat'  => 'এ পৃষ্ঠায় শেষ পরিবর্তন হয়েছিল $2, $1.',
'viewcount'   => 'এ পৃষ্ঠা দেখা হয়েছে $1 বার।',
'nbytes'    => '$1 বাইট',
'go'      => 'চল',
'ok'      => 'ঠিক আছে',
'retrievedfrom' => '\'$1\' থেকে আনীত',
'newmessageslink' => 'নতুন বার্তা',
'editsection'=>'সম্পাদনা করুন',
'editold'=>'সম্পাদনা করুন',
'toc' => 'সূচীপত্র',
'showtoc' => 'দেখাও',
'hidetoc' => 'সরিয়ে রাখ',

);


?>
