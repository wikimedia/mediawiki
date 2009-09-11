<?php
/** Khmer (ភាសាខ្មែរ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bunly
 * @author Chhorran
 * @author Kiensvay
 * @author Lovekhmer
 * @author T-Rithy
 * @author Thearith
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author គីមស៊្រុន
 * @author តឹក ប៊ុនលី
 * @author វ័ណថារិទ្ធ
 */

$namespaceNames = array(
	NS_MEDIA            => 'មេឌា',
	NS_SPECIAL          => 'ពិសេស',
	NS_TALK             => 'ការពិភាក្សា',
	NS_USER             => 'អ្នកប្រើប្រាស់',
	NS_USER_TALK        => 'ការពិភាក្សារបស់អ្នកប្រើប្រាស់',
	NS_PROJECT_TALK     => 'ការពិភាក្សាអំពី$1',
	NS_FILE             => 'ឯកសារ',
	NS_FILE_TALK        => 'ការពិភាក្សាអំពីឯកសារ',
	NS_MEDIAWIKI        => 'មេឌាវិគី',
	NS_MEDIAWIKI_TALK   => 'ការពិភាក្សាអំពីមេឌាវិគី',
	NS_TEMPLATE         => 'ទំព័រគំរូ',
	NS_TEMPLATE_TALK    => 'ការពិភាក្សាអំពីទំព័រគំរូ',
	NS_HELP             => 'ជំនួយ',
	NS_HELP_TALK        => 'ការពិភាក្សាអំពីជំនួយ',
	NS_CATEGORY         => 'ចំនាត់ថ្នាក់ក្រុម',
	NS_CATEGORY_TALK    => 'ការពិភាក្សាអំពីចំនាត់ថ្នាក់ក្រុម',
);

$namespaceAliases = array(
	'មីឌា' => NS_MEDIA,
	'ពិភាក្សា' => NS_TALK,
	'អ្នកប្រើប្រាស់-ពិភាក្សា' => NS_USER_TALK,
	'$1_ពិភាក្ស' => NS_PROJECT_TALK,
	'រូបភាព' => NS_FILE,
	'ការពិភាក្សាអំពីរូបភាព' => NS_FILE_TALK,
	'រូបភាព-ពិភាក្សា' => NS_FILE_TALK,
	'មីឌាវិគី' => NS_MEDIAWIKI,
	'មីឌាវិគី-ពិភាក្សា' => NS_MEDIAWIKI_TALK,
	'ទំព័រគំរូ-ពិភាក្សា' => NS_TEMPLATE_TALK,
	'ជំនួយ-ពិភាក្សា' => NS_HELP_TALK,
	'ចំណាត់ថ្នាក់ក្រុម' => NS_CATEGORY,
	'ចំណាត់ក្រុម' => NS_CATEGORY,
	'ការពិភាក្សាអំពីចំណាត់ថ្នាក់ក្រុម' => NS_CATEGORY_TALK,
	'ចំណាត់ក្រុម-ពិភាក្សា' => NS_CATEGORY_TALK,
);


$digitTransformTable = array(
	'0' => '០', # &#x17e0;
	'1' => '១', # &#x17e1;
	'2' => '២', # &#x17e2;
	'3' => '៣', # &#x17e3;
	'4' => '៤', # &#x17e4;
	'5' => '៥', # &#x17e5;
	'6' => '៦', # &#x17e6;
	'7' => '៧', # &#x17e7;
	'8' => '៨', # &#x17e8;
	'9' => '៩', # &#x17e9;
);

$separatorTransformTable = array(
	'.' => ','
);

$datePreferences = array(
	'default',
	'km',
	'ISO 8601',
);
 
$defaultDateFormat = 'km';
 
$dateFormats = array(
	'km time' => 'ម៉ោងH:i',
	'km date' => 'l ទីd F ឆ្នាំY',
	'km both' =>  'ម៉ោងH:i l ទីd F ឆ្នាំY',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'ការបញ្ជូនបន្តទ្វេដង' ),
	'BrokenRedirects'           => array( 'ការបញ្ជូនបន្តដែលខូច' ),
	'Disambiguations'           => array( 'ចំណងជើងស្រដៀងគ្នា' ),
	'Userlogin'                 => array( 'ការឡុកអ៊ីនរបស់អ្នកប្រើប្រាស់' ),
	'Userlogout'                => array( 'ការចាកចេញរបស់អ្នកប្រើប្រាស់' ),
	'CreateAccount'             => array( 'បង្កើតគណនី' ),
	'Preferences'               => array( 'ចំណង់ចំណូលចិត្ត' ),
	'Watchlist'                 => array( 'បញ្ជីតាមដាន' ),
	'Recentchanges'             => array( 'បំលាស់ប្ដូរថ្មីៗ' ),
	'Upload'                    => array( 'ផ្ទុកឯកសារឡើង' ),
	'Listfiles'                 => array( 'បញ្ជីរូបភាព' ),
	'Newimages'                 => array( 'រូបភាពថ្មីៗ' ),
	'Listusers'                 => array( 'បញ្ជីឈ្មោះអ្នកប្រើប្រាស់' ),
	'Listgrouprights'           => array( 'បញ្ជីក្រុមសិទ្ធិ' ),
	'Statistics'                => array( 'ស្ថិតិ' ),
	'Randompage'                => array( 'ទំព័រចៃដន្យ' ),
	'Lonelypages'               => array( 'ទំព័រកំព្រា' ),
	'Uncategorizedpages'        => array( 'ទំព័រដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ),
	'Uncategorizedcategories'   => array( 'ចំណាត់ថ្នាក់ក្រុមដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ),
	'Uncategorizedimages'       => array( 'រូបភាពដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ),
	'Uncategorizedtemplates'    => array( 'ទំព័រគំរូដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ),
	'Unusedcategories'          => array( 'ចំណាត់ថ្នាក់ក្រុមដែលមិនត្រូវបានប្រើប្រាស់' ),
	'Unusedimages'              => array( 'រូបភាពដែលមិនត្រូវបានប្រើប្រាស់' ),
	'Wantedpages'               => array( 'ទំព័រប្រើប្រាស់ច្រើន' ),
	'Wantedcategories'          => array( 'ចំណាត់ថ្នាក់ក្រុមប្រើប្រាស់ច្រើន' ),
	'Wantedfiles'               => array( 'រូបភាពប្រើប្រាស់ច្រើន' ),
	'Wantedtemplates'           => array( 'ទំព័រគំរូប្រើប្រាស់ច្រើន' ),
	'Mostlinked'                => array( 'ទំព័រមានតំណភ្ជាប់មកច្រើនជាងគេ' ),
	'Mostlinkedcategories'      => array( 'ចំណាត់ថ្នាក់ក្រុមមានតំណភ្ជាប់មកច្រើនជាងគេ' ),
	'Mostlinkedtemplates'       => array( 'ទំព័រគំរូមានតំណភ្ជាប់មកច្រើនជាងគេ' ),
	'Mostimages'                => array( 'ទំព័រមានរូបភាពច្រើនជាងគេ' ),
	'Mostcategories'            => array( 'ទំព័រមានចំណាត់ថ្នាក់ច្រើនជាងគេ' ),
	'Mostrevisions'             => array( 'ទំព័រមានកំណែច្រើនជាងគេ' ),
	'Fewestrevisions'           => array( 'ទំព័រមានកំណែតិចជាងគេ' ),
	'Shortpages'                => array( 'ទំព័រខ្លីៗ' ),
	'Longpages'                 => array( 'ទំព័រវែងៗ' ),
	'Newpages'                  => array( 'ទំព័រថ្មីៗ' ),
	'Ancientpages'              => array( 'ទំព័រចាស់ៗ' ),
	'Deadendpages'              => array( 'ទំព័រទាល់' ),
	'Protectedpages'            => array( 'ទំព័របានការពារ' ),
	'Protectedtitles'           => array( 'ចំណងជើងបានការពារ' ),
	'Allpages'                  => array( 'គ្រប់ទំព័រ' ),
	'Prefixindex'               => array( 'លិបិក្រមបុព្វបទ' ),
	'Ipblocklist'               => array( 'បញ្ជីហាមឃាត់IP' ),
	'Specialpages'              => array( 'ទំព័រពិសេសៗ' ),
	'Contributions'             => array( 'ការរួមចំណែក' ),
	'Emailuser'                 => array( 'អ្នកប្រើប្រាស់អ៊ីមែល' ),
	'Confirmemail'              => array( 'បញ្ជាក់ទទួលស្គាល់អ៊ីមែល' ),
	'Whatlinkshere'             => array( 'អ្វីដែលភ្ជាប់មកទីនេះ' ),
	'Recentchangeslinked'       => array( 'បំលាស់ប្ដូរទាក់ទិន' ),
	'Movepage'                  => array( 'ប្ដូរទីតាំងទំព័រ' ),
	'Blockme'                   => array( 'រាំងខ្ទប់' ),
	'Booksources'               => array( 'ប្រភពសៀវភៅ' ),
	'Categories'                => array( 'ចំណាត់ថ្នាក់ក្រុម' ),
	'Export'                    => array( 'នាំចេញ' ),
	'Version'                   => array( 'កំណែ' ),
	'Allmessages'               => array( 'គ្រប់សារ' ),
	'Log'                       => array( 'កំណត់ហេតុ' ),
	'Blockip'                   => array( 'រាំងខ្ទប់IP' ),
	'Undelete'                  => array( 'ឈប់លុបចេញ' ),
	'Import'                    => array( 'នាំចូល' ),
	'Lockdb'                    => array( 'ចាក់សោមូលដ្ឋានទិន្នន័យ' ),
	'Unlockdb'                  => array( 'ដោះសោមូលដ្ឋានទិន្នន័យ' ),
	'Userrights'                => array( 'សិទ្ធិអ្នកប្រើប្រាស់' ),
	'MIMEsearch'                => array( 'MIMEស្វែងរក' ),
	'FileDuplicateSearch'       => array( 'ស្វែងរកឯកសារដូចគ្នាបេះបិទ' ),
	'Unwatchedpages'            => array( 'ទំព័រលែងបានតាមដាន' ),
	'Listredirects'             => array( 'បញ្ជីទំព័របញ្ជូនបន្ត' ),
	'Revisiondelete'            => array( 'កំណែបានលុបចោល' ),
	'Unusedtemplates'           => array( 'ទំព័រគំរូដែលមិនត្រូវបានប្រើប្រាស់' ),
	'Randomredirect'            => array( 'ការបញ្ជូនបន្តដោយចៃដន្យ' ),
	'Mypage'                    => array( 'ទំព័ររបស់ខ្ញុំ' ),
	'Mytalk'                    => array( 'ការពិភាក្សារបស់ខ្ញុំ' ),
	'Mycontributions'           => array( 'ការរួមចំណែករបស់ខ្ញុំ' ),
	'Listadmins'                => array( 'បញ្ជីអ្នកអភិបាល' ),
	'Listbots'                  => array( 'បញ្ជីរូបយន្ត' ),
	'Popularpages'              => array( 'ទំព័រដែលមានប្រជាប្រិយ' ),
	'Search'                    => array( 'ស្វែងរក' ),
	'Resetpass'                 => array( 'ដាក់ពាក្យសំងាត់ថ្មីឡើងវិញ' ),
	'Withoutinterwiki'          => array( 'ដោយគ្មានអន្តរវិគី' ),
	'MergeHistory'              => array( 'ច្របាច់បញ្ជូលប្រវត្តិ' ),
	'Filepath'                  => array( 'ផ្លូវនៃឯកសារ' ),
	'Invalidateemail'           => array( 'អ៊ីមែលមិនត្រឹមត្រូវ' ),
	'Blankpage'                 => array( 'ទំព័រទទេ' ),
	'LinkSearch'                => array( 'ស្វែងរកតំណភ្ជាប់' ),
	'DeletedContributions'      => array( 'ការរួមចំណែកដែលត្រូវបានលុបចោល' ),
	'Tags'                      => array( 'ប្លាក' ),
	'Activeusers'               => array( 'អ្នកប្រើប្រាស់សកម្ម' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#បញ្ជូនបន្ត', '#ប្ដូរទីតាំងទៅ #ប្តូរទីតាំងទៅ', '#ប្ដូរទីតាំង', '#ប្តូរទីតាំង', '#ប្ដូរចំណងជើង', '#REDIRECT' ),
	'notoc'                 => array( '0', '__លាក់មាតិកា__', '__លាក់បញ្ជីអត្ថបទ__', '__គ្មានមាតិកា__', '__គ្មានបញ្ជីអត្ថបទ__', '__កុំបង្ហាញមាតិកា__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__លាក់វិចិត្រសាល__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__បង្ខំមាតិកា__', '__បង្ខំបញ្ជីអត្ថបទ__', '__បង្ខំអោយបង្ហាញមាតិកា__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__មាតិកា__', '__បញ្ជីអត្ថបទ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__ផ្នែកមិនត្រូវកែប្រែ__', '__មិនមានផ្នែកកែប្រែ__', '__លាក់ផ្នែកកែប្រែ__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__លាក់បឋមកថា__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'ខែនេះ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'ឈ្មោះខែនេះ', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'ថ្ងៃនេះ', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'ឈ្មោះថ្ងៃនេះ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ឆ្នាំនេះ', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ពេលនេះ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ម៉ោងនេះ', 'ម៉ោងឥឡូវ', 'CURRENTHOUR' ),
	'localyear'             => array( '1', 'LOCALDAYNAME', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'ពេលវេលាក្នុងតំបន់', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ម៉ោងតំបន់', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'ចំនួនទំព័រ', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ចំនួនអត្ថបទ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ចំនួនឯកសារ', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ចំនួនអ្នកប្រើប្រាស់', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'ចំនួនកំណែប្រែ', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'ឈ្មោះទំព័រ', 'PAGENAME' ),
	'namespace'             => array( '1', 'លំហឈ្មោះ', 'NAMESPACE' ),
	'talkspace'             => array( '1', 'លំហឈ្មោះទំព័រពិភាក្សា', 'TALKSPACE' ),
	'fullpagename'          => array( '1', 'ឈ្មោះទំព័រពេញ', 'FULLPAGENAME' ),
	'subpagename'           => array( '1', 'ឈ្មោះទំព័ររង', 'SUBPAGENAME' ),
	'talkpagename'          => array( '1', 'ឈ្មោះទំព័រពិភាក្សា', 'TALKPAGENAME' ),
	'msg'                   => array( '0', 'សារ:', 'MSG:' ),
	'msgnw'                 => array( '0', 'សារមិនមែនជាកូដវិគី:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'រូបភាពតូច', 'រូបតូច', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'រូបភាពតូច=$1', 'រូបតូច=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'ស្តាំ', 'ខាងស្តាំ', 'right' ),
	'img_left'              => array( '1', 'ធ្វេង', 'ខាងធ្វេង', 'left' ),
	'img_none'              => array( '1', 'ទទេ', 'គ្មាន', 'none' ),
	'img_width'             => array( '1', '$1ភីកសែល', '$1ភស', '$1px' ),
	'img_center'            => array( '1', 'កណ្តាល', 'center', 'centre' ),
	'img_framed'            => array( '1', 'ស៊ុម', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'គ្មានស៊ុម', 'frameless' ),
	'img_page'              => array( '1', 'ទំព័រ=$1', 'ទំព័រ$1', 'page=$1', 'page $1' ),
	'img_top'               => array( '1', 'ផ្នែកលើ', 'ផ្នែកខាងលើ', 'top' ),
	'img_text_top'          => array( '1', 'ឃ្លានៅផ្នែកខាងលើ', 'ឃ្លាផ្នែកខាងលើ', 'text-top' ),
	'img_middle'            => array( '1', 'ផ្នែកកណ្តាល', 'middle' ),
	'img_bottom'            => array( '1', 'បាត', 'ផ្នែកបាត', 'bottom' ),
	'img_text_bottom'       => array( '1', 'ឃ្លានៅផ្នែកបាត', 'ឃ្លាផ្នែកបាត', 'text-bottom' ),
	'img_link'              => array( '1', 'តំនភ្ជាប់=$1', 'តំណភ្ជាប់=$1', 'link=$1' ),
	'sitename'              => array( '1', 'ឈ្មោះវិបសាយ', 'ឈ្មោះគេហទំព័រ', 'SITENAME' ),
	'ns'                    => array( '0', 'លឈ:', 'NS:' ),
	'server'                => array( '0', 'ម៉ាស៊ីនបម្រើសេវា', 'SERVER' ),
	'servername'            => array( '0', 'ឈ្មោះម៉ាស៊ីនបម្រើសេវា', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'ផ្លូវស្រ្គីប', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'វេយ្យាករណ៍:', 'GRAMMAR:' ),
	'currentweek'           => array( '1', 'សប្ដាហ៍នេះ', 'CURRENTWEEK' ),
	'plural'                => array( '0', 'ពហុវចនៈ:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'URLពេញ:', 'FULLURL:' ),
	'displaytitle'          => array( '1', 'បង្ហាញចំណងជើង', 'បង្ហាញចំនងជើង', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'រ', 'R' ),
	'newsectionlink'        => array( '1', '__តំនភ្ជាប់ផ្នែកថ្មី__', '__តំណភ្ជាប់ផ្នែកថ្មី__', '__NEWSECTIONLINK__' ),
	'language'              => array( '0', '#ភាសា:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'កូដភាសា', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'numberofadmins'        => array( '1', 'ចំនួនអ្នកអភិបាល', 'ចំនួនអ្នកថែទាំប្រព័ន្ធ', 'NUMBEROFADMINS' ),
	'special'               => array( '0', 'ពិសេស', 'special' ),
	'filepath'              => array( '0', 'ផ្លូវនៃឯកសារ:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'ប្លាក', 'tag' ),
	'hiddencat'             => array( '1', '__ចំណាត់ថ្នាក់ក្រុមមិនបានបង្ហាញ__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'ចំនួនទំព័រក្នុងចំនាត់ថ្នាក់ក្រុម', 'ចំនួនទំព័រក្នុងចំណាត់ថ្នាក់ក្រុម', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'ទំហំទំព័រ', 'PAGESIZE' ),
	'index'                 => array( '1', '__លិបិក្រម__', '__INDEX__' ),
	'noindex'               => array( '1', '__មិនមានលិបិក្រម__', '__NOINDEX__' ),
	'staticredirect'        => array( '1', '__ស្ថិតិទំព័របញ្ជូនបន្ត__', '__STATICREDIRECT__' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'គូសបន្ទាត់ក្រោម​តំណភ្ជាប់៖',
'tog-highlightbroken'         => 'តភ្ជាប់​ទៅ​ទំព័រ​មិនទាន់មាន នឹង <a href="" class="new">ដូចនេះ</a> (បើមិនដូច្នោះ ៖ <a href="" class="internal">ដូចនេះ</a>)',
'tog-justify'                 => 'តំរឹម​កថាខណ្ឌ',
'tog-hideminor'               => 'បិទបាំង​កំណែប្រែតិចតួច​ក្នុងបញ្ជីបំលាស់ប្ដូរថ្មីៗ',
'tog-hidepatrolled'           => 'លាក់​កំណែប្រែ​ដែល​បាន​ល្បាត នៅ​ក្នុង​បំលាស់ប្ដូរ​ថ្មីៗ',
'tog-newpageshidepatrolled'   => 'លាក់​ទំព័រ​ដែល​បាន​ល្បាត ពី​បញ្ជី​ទំព័រ​ថ្មី',
'tog-extendwatchlist'         => 'ពង្រីក​បញ្ជីតាមដាន​ដើម្បី​បង្ហាញ​គ្រប់​បំលាស់ប្ដូរ មិន​មែន​ត្រឹមតែ​បញ្ជី​ថ្មី​ៗ​នោះ​ទេ',
'tog-usenewrc'                => 'ធ្វើឱ្យ​បំលាស់ប្ដូរ​ថ្មីៗ កាន់តែប្រសើរឡើង (តម្រូវ​មាន JavaScript)',
'tog-numberheadings'          => 'បញ្ចូលលេខ​ចំណងជើងរង​ដោយស្វ័យប្រវត្តិ',
'tog-showtoolbar'             => 'បង្ហាញ​របារឧបករណ៍កែប្រែ (JavaScript)',
'tog-editondblclick'          => 'ចុចពីរដង​ដើម្បីកែប្រែទំព័រ (JavaScript)',
'tog-editsection'             => 'អនុញ្ញាតកែប្រែ​ផ្នែកណាមួយ​តាម​តំណភ្ជាប់[កែប្រែ]',
'tog-editsectiononrightclick' => 'អនុញ្ញាត​កែប្រែ​​ផ្នែកណាមួយ(JavaScript) ដោយ​ចុចស្តាំកណ្តុរ​លើ​ចំណងជើង​របស់វា',
'tog-showtoc'                 => 'បង្ហាញ​តារាងមាតិកា (ចំពោះទំព័រ​ដែលមាន​ចំណងជើងរង​លើសពី៣)',
'tog-rememberpassword'        => 'ចងចាំ​ការឡុកអ៊ីនរបស់ខ្ញុំ​លើកុំព្យូទ័រនេះ',
'tog-editwidth'               => 'ពង្រីក​ប្រអប់​កែប្រែ​ឱ្យ​បង្ហាញ​ពេញ​អេក្រង់',
'tog-watchcreations'          => 'បន្ថែម​ទំព័រ​ដែលខ្ញុំបង្កើត​ទៅ​បញ្ជីតាមដាន​របស់ខ្ញុំ',
'tog-watchdefault'            => 'បន្ថែម​ទំព័រ​ដែលខ្ញុំកែប្រែ​ទៅ​បញ្ជីតាមដាន​របស់ខ្ញុំ',
'tog-watchmoves'              => 'បន្ថែម​ទំព័រ​ដែលខ្ញុំប្តូរទីតាំង​ទៅ​បញ្ជីតាមដាន​របស់ខ្ញុំ',
'tog-watchdeletion'           => 'បន្ថែម​ទំព័រ​ដែលខ្ញុំលុបចេញ​ទៅ​បញ្ជីតាមដាន​របស់ខ្ញុំ',
'tog-minordefault'            => 'ចំណាំ​គ្រប់កំណែប្រែ​របស់ខ្ញុំ​ថាជា​តិចតួច',
'tog-previewontop'            => 'បង្ហាញ​ការមើលមុន​ពីលើ​ប្រអប់​កែប្រែ',
'tog-previewonfirst'          => 'បង្ហាញ​ការមើលមុន​ចំពោះ​កំណែប្រែ​ដំបូង',
'tog-nocache'                 => 'អសកម្ម​សតិភ្ជាប់​នៃ​ទំព័រ',
'tog-enotifwatchlistpages'    => 'សូមអ៊ីមែល​មកខ្ញុំ​កាលបើ​មានបំលាស់ប្ដូរនៃទំព័រ​ណាមួយក្នុងបញ្ជីតាមដានរបស់ខ្ញុំ',
'tog-enotifusertalkpages'     => 'សូមអ៊ីមែល​មកខ្ញុំ​កាលបើ​មានបំលាស់ប្ដូរ​នៅ​ទំព័រពិភាក្សា​របស់ខ្ញុំ',
'tog-enotifminoredits'        => 'សូមអ៊ីមែល​មកខ្ញុំ​ផងដែរ​ចំពោះ​បំលាស់ប្ដូរតិចតួច​នៃ​ទំព័រនានា',
'tog-enotifrevealaddr'        => 'សូមបង្ហាញ​អាសយដ្ឋានអ៊ីមែល​របស់ខ្ញុំ​ក្នុង​​មែល​ក្រើនរំលឹក​នានា',
'tog-shownumberswatching'     => 'បង្ហាញ​ចំនួនអ្នកប្រើប្រាស់​ដែលតាមដាន​ទំព័រនេះ',
'tog-oldsig'                  => 'ការមើលមុននៃហត្ថលេខាដែលមាន៖',
'tog-fancysig'                => 'ចុះហត្ថលេខា​ជា​អត្ថបទវិគី​ (ដោយ​គ្មានតំណភ្ជាប់​ស្វ័យប្រវត្តិ)',
'tog-externaleditor'          => 'ប្រើប្រាស់​ឧបករណ៍​កែប្រែខាងក្រៅ​តាមលំនាំដើម (សម្រាប់តែអ្នកមានជំនាញប៉ុណ្ណោះ, ត្រូវការការកំណត់ពិសេសៗនៅលើកុំព្យូទ័ររបស់អ្នក)',
'tog-externaldiff'            => 'ប្រើប្រាស់​ឧបករណ៍​ប្រៀបធៀបខាងក្រៅ​តាមលំនាំដើម (សម្រាប់តែអ្នកមានជំនាញប៉ុណ្ណោះ, ត្រូវការការកំណត់ពិសេសៗនៅលើកុំព្យូទ័ររបស់អ្នក)',
'tog-showjumplinks'           => 'សកម្មតំណភ្ជាប់ «ត្រាច់រក» និង «ស្វែងរក» នៅផ្នែកលើនៃទំព័រ(ចំពោះសំបក Myskin និងផ្សេងទៀត)',
'tog-uselivepreview'          => 'ប្រើប្រាស់​ការមើលមុនរហ័ស​ (JavaScript) (ពិសោធន៍)',
'tog-forceeditsummary'        => 'សូមរំលឹកខ្ញុំ​កាលបើខ្ញុំទុកប្រអប់វិចារឱ្យទំនេរ',
'tog-watchlisthideown'        => 'បិទបាំង​កំណែប្រែ​របស់ខ្ញុំ​ពី​បញ្ជីតាមដាន',
'tog-watchlisthidebots'       => 'បិទបាំង​កំណែប្រែ​របស់​រូបយន្ត​ពី​បញ្ជីតាមដាន',
'tog-watchlisthideminor'      => 'បិទបាំង​កំណែប្រែតិចតួច​ពីបញ្ជីតាមដាន',
'tog-watchlisthideliu'        => 'លាក់កំណែប្រែរបស់អ្នកប្រើប្រាស់ដែលបានឡុកអ៊ីនពីបញ្ជីតាមដាន',
'tog-watchlisthideanons'      => 'លាក់កំណែប្រែរបស់អ្នកប្រើប្រាស់អនាមិកពីបញ្ជីតាមដាន',
'tog-watchlisthidepatrolled'  => 'លាក់​កំណែប្រែ​ដែល​បាន​ល្បាត ពី​បញ្ជីតាមដាន',
'tog-ccmeonemails'            => 'ផ្ញើមកខ្ញុំផងដែរនូវច្បាប់ចម្លង​អ៊ីមែលដែលខ្ញុំផ្ញើទៅកាន់អ្នកប្រើប្រាស់ផ្សេងទៀត',
'tog-diffonly'                => 'សូមកុំបង្ហាញខ្លឹមសារទំព័រនៅពីក្រោមភាពខុសគ្នា',
'tog-showhiddencats'          => 'បង្ហាញចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានបិទបាំង',
'tog-norollbackdiff'          => 'បំភ្លេច​ភាព​ខុស​គ្នា​បន្ទាប់​ពី​អនុវត្តការ​ត្រឡប់​ក្រោយ​ (rollback)',

'underline-always'  => 'ជានិច្ច',
'underline-never'   => 'មិនដែលសោះ',
'underline-default' => 'លំនាំដើមនៃ​កម្មវិធី​រុករក​',

# Font style option in Special:Preferences
'editfont-style'     => 'កែសម្រួល​រចនាបទ​ពុម្ព​អក្សរ​សម្រាប់​តំបន់​',
'editfont-default'   => 'លំនាំដើមនៃ​កម្មវិធី​រុករក​',
'editfont-monospace' => 'ពុម្ព​អក្សរ​ដែល​ដក​ឃ្លា​តែមួយ​',
'editfont-sansserif' => 'ពុម្ពអក្សរ​​គ្មាន serif (Sans-serif font)',
'editfont-serif'     => 'ពុម្ពអក្សរ​​ serif (Serif font)',

# Dates
'sunday'        => 'ថ្ងៃអាទិត្យ',
'monday'        => 'ថ្ងៃច័ន្ទ',
'tuesday'       => 'ថ្ងៃអង្គារ',
'wednesday'     => 'ថ្ងៃពុធ',
'thursday'      => 'ថៃ្ងព្រហស្បតិ៍',
'friday'        => 'ថ្ងៃសុក្រ',
'saturday'      => 'ថ្ងៃសៅរ៍',
'sun'           => 'អាទិត្យ',
'mon'           => 'ច័ន្ទ',
'tue'           => 'អង្គារ',
'wed'           => 'ពុធ',
'thu'           => 'ព្រហស្បតិ៍',
'fri'           => 'សុក្រ',
'sat'           => 'សៅរ៍',
'january'       => 'ខែមករា',
'february'      => 'ខែកុម្ភៈ',
'march'         => 'ខែមីនា',
'april'         => 'ខែមេសា',
'may_long'      => 'ខែឧសភា',
'june'          => 'ខែមិថុនា',
'july'          => 'ខែកក្កដា',
'august'        => 'ខែសីហា',
'september'     => 'ខែកញ្ញា',
'october'       => 'ខែតុលា',
'november'      => 'ខែវិច្ឆិកា',
'december'      => 'ខែធ្នូ',
'january-gen'   => 'ខែមករា',
'february-gen'  => 'ខែកុម្ភៈ',
'march-gen'     => 'ខែមីនា',
'april-gen'     => 'ខែមេសា',
'may-gen'       => 'ខែឧសភា',
'june-gen'      => 'ខែមិថុនា',
'july-gen'      => 'ខែកក្កដា',
'august-gen'    => 'ខែសីហា',
'september-gen' => 'ខែកញ្ញា',
'october-gen'   => 'ខែតុលា',
'november-gen'  => 'ខែវិច្ឆិកា',
'december-gen'  => 'ខែធ្នូ',
'jan'           => 'មករា',
'feb'           => 'កុម្ភៈ',
'mar'           => 'មីនា',
'apr'           => 'មេសា',
'may'           => 'ឧសភា',
'jun'           => 'មិថុនា',
'jul'           => 'កក្កដា',
'aug'           => 'សីហា',
'sep'           => 'កញ្ញា',
'oct'           => 'តុលា',
'nov'           => 'វិច្ឆិកា',
'dec'           => 'ធ្នូ',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុម}}',
'category_header'                => 'ទំព័រដែលមាន​ក្នុងចំណាត់ថ្នាក់ក្រុម"$1"',
'subcategories'                  => 'ចំណាត់ថ្នាក់ក្រុមរង',
'category-media-header'          => 'ឯកសារមេឌា​ដែលមានក្នុង​ចំណាត់ថ្នាក់ក្រុម "$1"',
'category-empty'                 => "''ចំណាត់ថ្នាក់ក្រុមនេះ​មិនមានផ្ទុកអត្ថបទឬ​ឯកសារមេឌា​ណាមួយទេ។''",
'hidden-categories'              => '{{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានលាក់}}',
'hidden-category-category'       => 'ចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានលាក់',
'category-subcat-count'          => '{{PLURAL:$2|ចំណាត់ថ្នាក់ក្រុមនេះមានតែចំណាត់ថ្នាក់ក្រុមរងដូចតទៅ។|ចំណាត់ថ្នាក់ក្រុមនេះមាន{{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុមរង|$1 ចំណាត់ថ្នាក់ក្រុមរង}}ដូចតទៅ, ក្នុងចំណោមចំណាត់ថ្នាក់ក្រុមរងសរុប $2។}}',
'category-subcat-count-limited'  => 'ចំណាត់ថ្នាក់ក្រុមនេះមាន {{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុមរង|$1 ចំណាត់ថ្នាក់ក្រុមរង}} ដូចតទៅ។',
'category-article-count'         => '{{PLURAL:$2|ចំណាត់ថ្នាក់ក្រុមនេះមានទំព័រដូចតទៅនេះ។|ទំព័រចំនួន {{PLURAL:$1|១ ទំព័រ|$1 ទំព័រ}} ក្នុងចំណោមទំព័រសរុប $2 ដូចតទៅនេះស្ថិតក្នុងចំណាត់ថ្នាក់ក្រុមនេះ។}}',
'category-article-count-limited' => 'អត្ថបទ{{PLURAL:$1|ទំព័រ|$1 ស្ថិតនៅ}}ក្នុងចំណាត់ថ្នាក់ក្រុមនេះ។',
'category-file-count'            => '{{PLURAL:$2|ចំណាត់ថ្នាក់ក្រុមនេះមានឯកសារដូចទៅនេះ។|ឯកសារចំនួន {{PLURAL:$1|១ ឯកសារ|$1 ឯកសារ}} ក្នុងចំណោមឯកសារសរុប $2 ដូចតទៅនេះស្ថិតនៅក្នុងចំណាត់ថ្នាក់ក្រុមនេះ។}}',
'category-file-count-limited'    => 'ឯកសារ {{PLURAL:$1|១ ឯកសារ|$1 ឯកសារ}} ដូចតទៅនេះស្ថិតនៅក្នុងចំណាត់ថ្នាក់ក្រុមនេះ។',
'listingcontinuesabbrev'         => 'បន្ត',

'mainpagetext'      => "<big>'''មេឌាវិគីត្រូវបានដំឡើងដោយជោគជ័យហើយ​។'''</big>",
'mainpagedocfooter' => 'សូមពិនិត្យមើល [http://meta.wikimedia.org/wiki/ជំនួយ​៖ ខ្លឹមសារ​ណែនាំ​ប្រើប្រាស់]សម្រាប់​ព័ត៌មាន​​បន្ថែមចំពោះ​ការប្រើប្រាស់ ផ្នែកទន់វិគី​។

== ចាប់ផ្ដើមជាមួយមេឌាវិគី ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings បញ្ជីកំណត់ទម្រង់]
* [http://www.mediawiki.org/wiki/Manual:FAQ/km សំណួរញឹកញាប់​មេឌាវិគី]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce បញ្ជី​ពិភាក្សា​ការផ្សព្វផ្សាយ​របស់​មេឌាវិគី]',

'about'         => 'អំពី',
'article'       => 'មាតិកាអត្ថបទ',
'newwindow'     => '(បើក​លើ​បង្អួច​ថ្មី)',
'cancel'        => 'បោះបង់',
'moredotdotdot' => 'បន្ថែមទៀត...',
'mypage'        => 'ទំព័រ​របស់ខ្ញុំ',
'mytalk'        => 'ការពិភាក្សា​',
'anontalk'      => 'ពិភាក្សាចំពោះ IP នេះ',
'navigation'    => 'ទិសដៅ',
'and'           => '&#32;និង',

# Cologne Blue skin
'qbfind'         => 'ស្វែងរក',
'qbbrowse'       => 'រាវរក',
'qbedit'         => 'កែប្រែ',
'qbpageoptions'  => 'ទំព័រនេះ',
'qbpageinfo'     => 'ព័ត៌មានទំព័រ',
'qbmyoptions'    => 'ទំព័ររបស់ខ្ញុំ',
'qbspecialpages' => 'ទំព័រពិសេសៗ',
'faq'            => 'សំណួរដែលសួរញឹកញាប់',
'faqpage'        => 'Project:សំណួរដែលសួរញឹកញាប់',

# Vector skin
'vector-action-addsection'   => 'បន្ថែម​ប្រធានបទ​',
'vector-action-delete'       => 'លុបចោល',
'vector-action-move'         => 'ប្តូរទីតាំង',
'vector-action-protect'      => 'ការពារ',
'vector-action-undelete'     => 'ឈប់លុបចេញ',
'vector-action-unprotect'    => 'ឈប់ការពារ',
'vector-namespace-category'  => 'ចំណាត់ថ្នាក់ក្រុម',
'vector-namespace-help'      => 'ទំព័រជំនួយ',
'vector-namespace-image'     => 'ឯកសារ',
'vector-namespace-main'      => 'ទំព័រ',
'vector-namespace-media'     => 'ទំព័រមេឌា',
'vector-namespace-mediawiki' => 'សារ',
'vector-namespace-project'   => 'ទំព័រគម្រោង',
'vector-namespace-special'   => 'ទំព័រពិសេស',
'vector-namespace-talk'      => 'ការពិភាក្សា',
'vector-namespace-template'  => 'គំរូ​',
'vector-namespace-user'      => 'ទំព័រ​អ្នកប្រើប្រាស់​',
'vector-view-create'         => 'បង្កើត​',
'vector-view-edit'           => 'កែប្រែ​',
'vector-view-history'        => 'មើល​ប្រវត្តិ​',
'vector-view-view'           => 'អាន',
'vector-view-viewsource'     => 'មើល​កូដ',
'actions'                    => 'សកម្មភាព​',
'namespaces'                 => 'លំហឈ្មោះ',
'variants'                   => 'អថេរ',

# Metadata in edit box
'metadata_help' => 'ទិន្នន័យមេតា៖',

'errorpagetitle'    => 'កំហុស',
'returnto'          => 'ត្រឡប់ទៅ $1 វិញ ។',
'tagline'           => 'ដោយ {{SITENAME}}',
'help'              => 'ជំនួយ',
'search'            => 'ស្វែងរក',
'searchbutton'      => 'ស្វែងរក',
'go'                => 'ទៅ',
'searcharticle'     => 'ទៅ',
'history'           => 'ប្រវត្តិទំព័រ',
'history_short'     => 'ប្រវត្តិ',
'updatedmarker'     => 'បានបន្ទាន់សម័យតាំងពីពេលចូលមើលចុងក្រោយរបស់ខ្ញុំ',
'info_short'        => 'ព័ត៌មាន',
'printableversion'  => 'ទម្រង់​សម្រាប់បោះពុម្ភ',
'permalink'         => 'តំណភ្ជាប់អចិន្ត្រៃយ៍',
'print'             => 'បោះពុម្ភ',
'edit'              => 'កែប្រែ',
'create'            => 'បង្កើត',
'editthispage'      => 'កែប្រែទំព័រនេះ',
'create-this-page'  => 'បង្កើតទំព័រនេះ',
'delete'            => 'លុប',
'deletethispage'    => 'លុបទំព័រនេះចោល',
'undelete_short'    => 'ឈប់​លុប {{PLURAL:$1|មួយ កំណែប្រែ|$1 កំណែប្រែ}}',
'protect'           => 'ការពារ',
'protect_change'    => 'ផ្លាស់ប្តូរការការពារ',
'protectthispage'   => 'ការពារទំព័រនេះ',
'unprotect'         => 'ឈប់ការពារ',
'unprotectthispage' => 'ឈប់ការពារទំព័រនេះ',
'newpage'           => 'ទំព័រថ្មី',
'talkpage'          => 'ពិភាក្សាទំព័រនេះ',
'talkpagelinktext'  => 'ការពិភាក្សា',
'specialpage'       => 'ទំព័រពិសេស',
'personaltools'     => 'ឧបករណ៍ផ្ទាល់ខ្លួន',
'postcomment'       => 'ផ្នែកថ្មី',
'articlepage'       => 'មើលទំព័រ​មាតិការ​',
'talk'              => 'ការពិភាក្សា',
'views'             => 'ការមើលនានា',
'toolbox'           => 'ប្រអប់​ឧបករណ៍',
'userpage'          => 'មើលទំព័រអ្នកប្រើប្រាស់',
'projectpage'       => 'មើល​ទំព័រគម្រោង',
'imagepage'         => 'មើល​ទំព័រ​ឯកសារ',
'mediawikipage'     => 'មើល​ទំព័រសារ',
'templatepage'      => 'មើលទំព័រគំរូ',
'viewhelppage'      => 'មើលទំព័រជំនួយ',
'categorypage'      => 'មើល​ទំព័រចំណាត់ថ្នាក់ក្រុម',
'viewtalkpage'      => 'មើលការពិភាក្សា',
'otherlanguages'    => 'ជាភាសាដទៃទៀត',
'redirectedfrom'    => '(ត្រូវបានបញ្ជូនបន្តពី $1)',
'redirectpagesub'   => 'ទំព័របញ្ជូនបន្ត',
'lastmodifiedat'    => 'ទំព័រនេះត្រូវបានកែចុងក្រោយនៅ$2 $1',
'viewcount'         => "ទំព័រនេះ​ត្រូវបានចូលមើល​ចំនួន'''{{PLURAL:$1|ម្ដង|$1ដង}}'''",
'protectedpage'     => 'ទំព័រដែលត្រូវបានការពារ',
'jumpto'            => 'ទៅកាន់៖',
'jumptonavigation'  => 'ទិសដៅ',
'jumptosearch'      => 'ស្វែងរក',
'view-pool-error'   => 'សូមអភ័យទោស។ ប្រព័ន្ធបំរើការមានការមមាញឹកខ្លាំងពេកនៅពេលនេះ។

មានអ្នកប្រើប្រាស់ជាច្រើនកំពុងព្យាយាមចូលមើលទំព័រនេះ។

សូមរង់ចាំមួយភ្លែតសិនរួចសាកល្បងចូលមកកាន់ទំព័រនេះឡើងវិញ។

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'អំពី{{SITENAME}}',
'aboutpage'            => 'Project:អំពី',
'copyright'            => 'រក្សាសិទ្ធិគ្រប់យ៉ាងដោយ$1។',
'copyrightpage'        => '{{ns:project}}:រក្សាសិទ្ធិ​',
'currentevents'        => 'ព្រឹត្តិការណ៍​ថ្មីៗ',
'currentevents-url'    => 'Project:ព្រឹត្តិការណ៍​ថ្មីៗ',
'disclaimers'          => 'ការបដិសេធ',
'disclaimerpage'       => 'Project:ការបដិសេធ​ទូទៅ',
'edithelp'             => 'ជំនួយ​ក្នុងការកែប្រែ',
'edithelppage'         => 'Help:របៀបកែសម្រួល',
'helppage'             => 'Help:មាតិកា',
'mainpage'             => 'ទំព័រដើម',
'mainpage-description' => 'ទំព័រដើម',
'policy-url'           => 'Project:គោលការណ៍',
'portal'               => 'ផតថលសហគមន៍',
'portal-url'           => 'Project:​ផតថលសហគមន៍',
'privacy'              => 'គោលការណ៍​ភាពឯកជន',
'privacypage'          => 'Project:គោលការណ៍ភាពឯកជន',

'badaccess'        => 'កំហុសនៃការអនុញ្ញាត',
'badaccess-group0' => 'សកម្មភាពដែលអ្នកបានស្នើមិនត្រូវបានអនុញ្ញាតទេ ។',
'badaccess-groups' => 'មានតែ​អ្នកប្រើប្រាស់​ក្នុង{{PLURAL:$2|ក្រុម|ក្រុម១នៃក្រុម}}៖ $1 ទេ ​ទើបអាចធ្វើសកម្មភាព​ដែលអ្នកបានស្នើ។',

'versionrequired'     => 'តម្រូវឱ្យមាន​កំណែ $1 នៃមេឌាវិគី',
'versionrequiredtext' => 'ត្រូវការកំណែ $1 នៃមេឌាវិគី (MediaWiki) ដើម្បីប្រើប្រាស់ទំព័រនេះ។ សូមមើល [[Special:Version|ទំព័រកំណែ]]។',

'ok'                      => 'យល់ព្រម',
'pagetitle'               => '$1 - {{SITENAME}}',
'retrievedfrom'           => 'បានមកវិញពី "$1"',
'youhavenewmessages'      => 'អ្នកមាន $1 ($2)។',
'newmessageslink'         => 'សារថ្មីៗ',
'newmessagesdifflink'     => 'បំលាស់ប្ដូរចុងក្រោយ',
'youhavenewmessagesmulti' => 'អ្នកមានសារថ្មីៗនៅ $1',
'editsection'             => 'កែប្រែ',
'editold'                 => 'កែប្រែ',
'viewsourceold'           => 'មើលកូដ',
'editlink'                => 'កែប្រែ',
'viewsourcelink'          => 'មើលកូដ',
'editsectionhint'         => "កែប្រែផ្នែក៖ '''$1'''",
'toc'                     => 'មាតិកា',
'showtoc'                 => 'បង្ហាញ',
'hidetoc'                 => 'លាក់',
'thisisdeleted'           => 'ចង់បង្ហាញ ឬ​ ទុក $1 នៅដដែល?',
'viewdeleted'             => 'មើល $1?',
'restorelink'             => '{{PLURAL:$1|កំណែប្រែត្រូវបានលុបចេញ|$1 កំណែប្រែត្រូវបានលុបចេញ}}',
'feedlinks'               => 'បម្រែបម្រួល​៖',
'feed-invalid'            => 'ប្រភេទfeedដែលគ្មានសុពលភាព។',
'feed-unavailable'        => 'បម្រែ​បម្រួល ​Syndication feeds មិន​ទាន់​មាន​នៅ​ឡើយ​ទេ',
'site-rss-feed'           => 'បម្រែបម្រួល RSS Feed នៃ $1',
'site-atom-feed'          => 'បម្រែបម្រួល Atom Feed នៃ $1',
'page-rss-feed'           => 'បម្រែបម្រួល RSS Feed នៃ "$1"',
'page-atom-feed'          => 'បម្រែបម្រួល Atom Feed នៃ "$1"',
'red-link-title'          => '$1 (មិនទាន់​មានទំព័រនេះទេ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'អត្ថបទ',
'nstab-user'      => 'ទំព័រអ្នកប្រើប្រាស់',
'nstab-media'     => 'ទំព័រមេឌា',
'nstab-special'   => 'ទំព័រពិសេស',
'nstab-project'   => 'ទំព័រគម្រោង',
'nstab-image'     => 'ឯកសារ',
'nstab-mediawiki' => 'សារ',
'nstab-template'  => 'ទំព័រគំរូ',
'nstab-help'      => 'ទំព័រជំនួយ',
'nstab-category'  => 'ចំណាត់ថ្នាក់ក្រុម',

# Main script and global functions
'nosuchaction'      => 'មិនមានសកម្មភាពបែបនេះទេ',
'nosuchactiontext'  => 'សកម្មភាព​បានបង្ហាញដោយ URL មិន​ត្រឹមត្រូវ​។
អ្នក​ប្រហែលជាបាន​វាយ URL ខុស បើ​មិន​ដូច្នេះ​ទេ​មាន​តែ​តំណភ្ជាប់​មិន​ត្រឹមត្រូវ​។
នេះ​ក៏​អាច​បញ្ជាក់​ប្រាប់​ពី​កំហុស​នៅ​ក្នុង​ផ្នែកទន់ ដែល​ត្រូវ​បាន​ប្រើប្រាស់​ដោយ {{SITENAME}} ។',
'nosuchspecialpage' => 'មិនមានទំព័រពិសេសបែបនេះទេ',
'nospecialpagetext' => "<big>'''អ្នកបានស្នើរក​ទំព័រពិសេសមួយ ដែលមិនត្រូវបាន​ទទួលស្គាល់​ដោយ វិគី។'''</big>

បញ្ជី​នៃ​ទំព័រពិសេស​អាចត្រូវបាន​រកឃើញ​នៅ [[Special:SpecialPages|{{int:specialpages}}]]។",

# General errors
'error'                => 'កំហុស',
'databaseerror'        => 'កំហុសមូលដ្ឋានទិន្នន័យ',
'laggedslavemode'      => 'ប្រយ័ត្ន៖ ទំព័រនេះ​អាចមិនមានផ្ទុក​បំលាស់ប្ដូរ​ចុងក្រោយ​បំផុត។',
'readonly'             => 'មូលដ្ឋានទិន្នន័យត្រូវបានចាក់សោ',
'enterlockreason'      => 'សូមផ្ដល់ហេតុផលសម្រាប់ការជាប់សោ រួមទាំងការប្រមាណថាតើការជាប់សោនោះនឹងត្រូវដោះនៅពេលណា',
'readonlytext'         => 'ពេលនេះ​មូលដ្ឋានទិន្នន័យ​កំពុងជាប់សោ ដើម្បីកុំឱ្យមាន​ការបញ្ចូល​ទិន្នន័យ​ថ្មីៗ​ឬ​ការកែប្រែ​ផ្សេងៗ។ នេះ​ប្រហែលដោយ​ហេតុផល​ថែទាំ​មូលដ្ឋានទិន្នន័យប្រចាំថ្ងៃ ដែលជាធម្មតាវានឹងវិលមកសភាពដើមវិញ​ក្នុងពេលឆាប់ៗ។
អភិបាល​ដែលបានចាក់សោវា​បានពន្យល់ដូចតទៅ៖ $1',
'missingarticle-rev'   => '(កំណែប្រែ #៖ $1)',
'missingarticle-diff'  => '(ភាពខុសគ្នា: $1, $2)',
'readonly_lag'         => 'មូលដ្ឋានទិន្នន័យត្រូវបានចាក់សោដោយស្វ័យប្រវត្តិ ខណៈពេលដែលម៉ាស៊ីនបម្រើ(server)មូលដ្ឋានទិន្នន័យរង​កំពុង​ទាក់ទង​ទៅម៉ាស៊ីនបម្រើ​មូលដ្ឋានទិន្នន័យមេ',
'internalerror'        => 'កំហុសផ្នែកខាងក្នុង',
'internalerror_info'   => 'កំហុសផ្នែកខាងក្នុង៖ $1',
'fileappenderror'      => 'មិនអាចបន្ថែម "$1" ទៅខាងចុង "$2" បានទេ។',
'filecopyerror'        => 'មិនអាចចម្លងឯកសារ"$1" ទៅ "$2"បានទេ។',
'filerenameerror'      => 'មិនអាចប្តូរឈ្មោះឯកសារពី"$1" ទៅ "$2"បានទេ។',
'filedeleteerror'      => 'មិនអាចលុបឯកសារ"$1"បានទេ។',
'directorycreateerror' => 'មិនអាចបង្កើតថត"$1"បានទេ។',
'filenotfound'         => 'រក​ឯកសារ "$1" មិនឃើញទេ។',
'fileexistserror'      => 'មិនអាចសរសេរ​ទៅក្នុង​ឯកសារ "$1"៖ ឯកសារមានរួចហើយ',
'unexpected'           => 'តម្លៃ​មិនបានរំពឹងទុក៖ "$1"="$2"។',
'formerror'            => 'កំហុស៖ មិនអាចដាក់ស្នើ​សំណុំបែបបទ',
'badarticleerror'      => 'សកម្មភាពនេះ​មិនអាចត្រូវបានអនុវត្ត​លើទំព័រនេះទេ។',
'cannotdelete'         => 'មិនអាច​លុបចេញ ទំព័រ ឬ ឯកសារ ដែលបានសំដៅ។ វាអាច​ត្រូវបានលុបចេញហើយ​ដោយ​នរណាម្នាក់ផ្សេងទៀត។',
'badtitle'             => 'ចំណងជើង​មិនល្អ',
'badtitletext'         => 'ចំណងជើងទំព័រដែលបានស្នើ គ្មានសុពលភាព, ទទេ, ឬ ចំណងជើងតំណភ្ជាប់អន្តរភាសាឬអន្តរវិគី មិនត្រឹមត្រូវ ។ វាអាចមាន មួយ ឬ ច្រើនអក្សរ ដែលមិន​អាចប្រើប្រាស់​ក្នុង​ចំណងជើង។',
'perfcached'           => 'ទិន្នន័យទាំងនេះត្រូវបានដាក់ទៅសតិភ្ជាប់និងប្រហែលជាមិនទាន់សម័យ ។',
'perfcachedts'         => 'ទិន្នន័យខាងក្រោមនេះត្រូវបានដាក់ក្នុងឃ្លាំងសំងាត់ (Cache) និង បានត្រូវបន្ទាន់សម័យចុងក្រោយនៅ$1។',
'querypage-no-updates' => 'ការធ្វើឱ្យទាន់សម័យសម្រាប់ទំព័រនេះគឺមិនអាចធ្វើទៅរួចទេនាពេលឥឡូវ។ ទិន្នន័យនៅទីនេះនឹងមិនត្រូវផ្លាស់ប្ដូរថ្មីនាពេលបច្ចុប្បន្ន។',
'wrong_wfQuery_params' => 'ប៉ារ៉ា​មែត្រ​មិន​ត្រឹម​ត្រូវ​ចំពោះ​ wfQuery()<br />
មុខងារ​៖ $1<br />
អង្កេត​៖ $2',
'viewsource'           => 'មើល​កូដ',
'viewsourcefor'        => 'សម្រាប់ $1',
'actionthrottled'      => 'សកម្មភាពត្រូវបានកម្រិត',
'actionthrottledtext'  => 'ជាវិធានការប្រឆាំងស្ប៉ាម​(spam) អ្នកត្រូវបាន​គេកំហិតមិនឱ្យ​ធ្វើសកម្មភាពនេះ​ច្រើនដងពេកទេ​ក្នុងរយៈពេលខ្លីមួយ។

សូមព្យាយាមម្ដងទៀតក្នុងរយៈពេលប៉ុន្មាននាទីទៀត។',
'protectedpagetext'    => 'ទំព័រនេះបានត្រូវចាក់សោដើម្បីការពារមិនឱ្យកែប្រែ​។',
'viewsourcetext'       => 'លោកអ្នកអាចមើលនិងចម្លងកូដនៃទំព័រនេះ៖',
'protectedinterface'   => 'ទំព័រនេះ ផ្ដល់នូវ អត្ថបទអន្តរមុខ សម្រាប់ផ្នែកទន់, និង បានត្រូវចាក់សោ ដើម្បីចៀសវាង ការបំពាន ។',
'editinginterface'     => "'''សូមប្រយ័ត្ន៖''' អ្នកកំពុងតែកែប្រែទំព័រដែលបានប្រើប្រាស់​ដើម្បីផ្ដល់នូវអន្តរមុខសម្រាប់ផ្នែកទន់​។ បំលាស់ប្ដូរចំពោះទំព័រនេះ​នឹងប៉ះពាល់ដល់ទំព័រអន្តរមុខនៃអ្នកប្រើប្រាស់​ជាច្រើន ដែលប្រើប្រាស់វិបសាយនេះ។ សម្រាប់ការបកប្រែ សូមពិចារណាប្រើប្រាស់ [http://translatewiki.net/wiki/Main_Page?setlang=km translatewiki.net] (បេតាវិគី) គម្រោង​អន្តរជាតូបនីយកម្ម​នៃមេឌាវិគី ។",
'sqlhidden'            => '(ការអង្កេត SQL ត្រូវបិទបាំង)',
'cascadeprotected'     => 'ទំព័រនេះត្រូវបានការពារពីការការប្រែដោយសារវាមាន{{PLURAL:$1|ទំព័រ, ដែលមាន}} ដែលត្រូវបានការពារជាមួយ"cascading" option turned on:
$2',
'namespaceprotected'   => "អ្នកមិនមានសិទ្ធិកែប្រែទំព័រក្នុងលំហឈ្មោះ'''$1'''ទេ។",
'customcssjsprotected' => 'អ្នកមិនមាន​ការអនុញ្ញាត​ក្នុងការកែប្រែទំព័រនេះទេ ព្រោះវាផ្ទុកការកំណត់ផ្ទាល់ខ្លួនផ្សេងៗរបស់អ្នកប្រើប្រាស់ម្នាក់ផ្សេងទៀត។',
'ns-specialprotected'  => 'ទំព័រពិសេសៗមិនអាចកែប្រែបានទេ។',
'titleprotected'       => "ចំណងជើងនេះត្រូវបានការពារមិនឱ្យបង្កើត​ដោយ [[User:$1|$1]]។
ហេតុផលលើកឡើងគឺ ''$2''។",

# Virus scanner
'virus-badscanner'     => "ការ​កំណត់​រចនា​សម្ព័ន្ធ​មិន​ល្អ​៖ កម្មវិធី​ស្កេន​ virus មិន​ស្គាល់​៖ ''$1''",
'virus-scanfailed'     => 'ការស្កេនបានបរាជ័យ (កូដ $1)',
'virus-unknownscanner' => 'កម្មវិធីកំចាត់មេរោគដែលមិនស្គាល់:',

# Login and logout pages
'logouttext'                 => "'''ឥឡូវនេះលោកអ្នកបានចាកចេញពីគណនីរបស់លោកអ្នកហើយ!''' 

អ្នកអាចបន្តប្រើប្រាស់{{SITENAME}}ក្នុងភាពអនាមិក ឬ [[Special:UserLogin|ឡុកអ៊ីនម្ដងទៀត]] ក្នុងនាមជាអ្នកប្រើប្រាស់ដដែលឬផ្សេងទៀត។

'''សម្គាល់៖'''ទំព័រមួយចំនួនប្រហែលជានៅតែបង្ហាញលោកអ្នកនៅក្នុងភាពបានឡុកអ៊ីនចូលក្នុងគណនីរបស់លោកអ្នកដដែល។ ប្រសិនបើមានករណីនេះកើតឡើង សូមសំអាត ឃ្លាំងសំងាត់(Cache:ខាច់)នៃកម្មវិធីរុករករបស់លោកអ្នក។",
'welcomecreation'            => '== សូមស្វាគមន៍ $1! ==

គណនីរបស់អ្នកត្រូវបានបង្កើតហើយ។ 
កុំភ្លេចផ្លាស់ប្ដូរ[[Special:Preferences|ចំណូលចិត្ត {{SITENAME}}]]របស់អ្នក។',
'yourname'                   => 'ឈ្មោះអ្នកប្រើ៖',
'yourpassword'               => 'ពាក្យសំងាត់៖',
'yourpasswordagain'          => 'វាយពាក្យសំងាត់ម្តងទៀត៖',
'remembermypassword'         => 'ចងចាំការឡុកអ៊ីនរបស់ខ្ញុំក្នុងកុំព្យូទ័រនេះ',
'yourdomainname'             => 'ដែនរបស់អ្នក៖',
'externaldberror'            => 'មាន​​កំហុសសម្រាប់​ការ​បញ្ជាក់​ផ្ទៀង​ផ្ទាត់​​មូលដ្ឋាន​ទិន្នន័យ ​(authentication database error) បាន​កើត​ឡើង​ ឬ​អ្នក​មិន​ត្រូវ​បាន​អនុញ្ញាត​ឲ្យ​បន្ទាន់​សម័យ​គណនី​ខាង​ក្រៅ​របស់​អ្នក​។​
​',
'login'                      => 'ឡុកអ៊ីន',
'nav-login-createaccount'    => 'ឡុកអ៊ីនឬបង្កើតគណនី',
'loginprompt'                => 'អ្នក​ត្រូវតែ​មាន​ខូគី (cookies) ដើម្បី​អាច​ឡុកអ៊ីនចូល​ទៅ {{SITENAME}}។',
'userlogin'                  => 'ឡុកអ៊ីនឬបង្កើតគណនី',
'logout'                     => 'ចាកចេញ',
'userlogout'                 => 'ចាកចេញ',
'notloggedin'                => 'មិនបានឡុកអ៊ីន',
'nologin'                    => 'តើ​លោកអ្នក​មាន​គណនី​សម្រាប់​ប្រើ​ឬទេ​? $1 ។',
'nologinlink'                => 'បង្កើតគណនី',
'createaccount'              => 'បង្កើតគណនី',
'gotaccount'                 => 'តើលោកអ្នកមានគណនីសម្រាប់ប្រើរឺទេ?  $1។',
'gotaccountlink'             => 'ឡុកអ៊ីន',
'createaccountmail'          => 'តាមរយៈអ៊ីមែល',
'badretype'                  => 'ពាក្យសំងាត់ដែលអ្នកបានបញ្ចូលនោះ គឺមិនស៊ីគ្នាទេ។',
'userexists'                 => 'ឈ្មោះអ្នកប្រើប្រាស់ដែលបានវាយបញ្ចូល គឺឋិតនៅក្នុងការប្រើប្រាស់។ 
សូមជ្រើសរើសឈ្មោះដទៃទៀត។',
'loginerror'                 => 'កំហុសនៃការឡុកអ៊ីន',
'nocookiesnew'               => 'គណនីអ្នកប្រើប្រាស់ត្រូវបានបង្កើត ប៉ុន្តែអ្នកមិនត្រូវបានឡុកអ៊ីនទេ។ 
{{SITENAME}} ប្រើប្រាស់ cookies ដើម្បីឡុកអ៊ីន។
ប៉ុន្តែអ្នកបានអសកម្មពួកវា។
ចូរសកម្មពួកវាឡើងវិញ រួចឡុកអ៊ីនដោយប្រើឈ្មោះអ្នកប្រើប្រាស់ថ្មី  និង ពាក្យសំងាត់ថ្មីរបស់អ្នក។',
'nocookieslogin'             => '{{SITENAME}} ប្រើខូឃី(cookies)ដើម្បីឡុកអ៊ីន។ អ្នកមានខូឃីដែលមិនមានសុពលភាព។​ ចូរធ្វើឱ្យវាមានសុពលភាព រួចព្យាយាមម្តងទៀត។',
'noname'                     => 'អ្នកមិនបានកំណត់ត្រឹមត្រូវនូវឈ្មោះអ្នកប្រើប្រាស់ទេ។',
'loginsuccesstitle'          => 'ឡុកអ៊ីនដោយជោគជ័យ',
'loginsuccess'               => "'''ពេលនេះអ្នកត្រូវបានចូលទៅ{{SITENAME}}ជា \"\$1\"។'''",
'nosuchuser'                 => 'មិនមានអ្នកប្រើប្រាស់​ឈ្មោះ "$1" ទេ។ 
ឈ្មោះ​អ្នកប្រើប្រាស់ ត្រូវ​បាន​ដឹង​ដោយ​ញាណ​។
សូម​ពិនិត្យ​ក្រែង​លោ​មានកំហុស​អក្ខរាវិរុទ្ធឬ [[Special:UserLogin/signup|បង្កើត​គណនី​ថ្មី]]។',
'nosuchusershort'            => 'គ្មានអ្នកប្រើប្រាស់​ឈ្មោះ "$1" ទេ។ សូម​ពិនិត្យ​​អក្ខរាវិរុទ្ធ​របស់អ្នក ។',
'nouserspecified'            => 'អ្នកត្រូវតែ​បញ្ជាក់ឈ្មោះ​អ្នកប្រើប្រាស់។',
'wrongpassword'              => 'ពាក្យសំងាត់​ដែលបានបញ្ចូល​មិនត្រឹមត្រូវទេ។ សូមព្យាយាម​ម្តងទៀត។',
'wrongpasswordempty'         => 'ពាក្យសំងាត់ដែលបានបញ្ចូលមិនត្រូវ​ទេ។ សូមព្យាយាម​ម្តងទៀត។',
'passwordtooshort'           => 'ពាក្យសម្ងាត់​ត្រូវ​មាន​យ៉ាងតិចណាស់​ {{PLURAL:$1|1 តួអក្សរ}}។',
'password-name-match'        => 'ពាក្យសំងាត់ត្រូវតែខុសគ្នាពីឈ្មោះរបស់អ្នក។',
'mailmypassword'             => 'អ៊ីមែលពាក្យសំងាត់ថ្មី',
'passwordremindertitle'      => 'ពាក្យសំងាត់បណ្តោះអាសន្នថ្មីសម្រាប់{{SITENAME}}',
'passwordremindertext'       => 'មានអ្នកណាម្នាក់ (ប្រហែលជាអ្នក) ពីអាសយដ្ឋាន IP $1 បានស្នើសុំពាក្យសំងាត់ថ្មីមួយពី {{SITENAME}} ($4) ។
ពាក្យសំងាត់បណ្ដោះអាសន្នមួយសម្រាប់អ្នកប្រើប្រាស់ "$2" ត្រូវបានប្ដូរទៅជា "$3" ។ បើសិនជានេះមិនមែន​ជាអ្វីដែលអ្នកចង់បានទេ សូមអ្នកឡុកអ៊ីន​ហើយជ្រើសរើសយកពាក្យសំងាត់ថ្មី។ ពាក្យសំងាត់​បណ្ដោះអាសន្ន​របស់​អ្នក នឹង​ត្រូវ​ផុតកំណត់​ក្នុង​រយៈពេល {{PLURAL:$5|មួយ​ថ្ងៃ|$5 ថ្ងៃ}} ។

បើមានអ្នកណាផ្សេងស្នើករណីនេះ ឬ បើអ្នកនឹកឃើញពាក្យសំងាត់ចាស់របស់អ្នក ហើយមិនចង់ផ្លាស់ប្តូរទេនោះ សូមអ្នកអាចបំភ្លេចសារនេះ ហើយបន្តប្រើប្រាស់ពាក្យសំងាត់ចាស់របស់អ្នក ។',
'noemail'                    => 'គ្មានអាសយដ្ឋានអ៊ីមែលត្រូវបានកត់ត្រាទុកសម្រាប់អ្នកប្រើប្រាស់ "$1" ទេ។',
'passwordsent'               => 'ពាក្យសំងាត់​ថ្មី​ត្រូវ​បាន​ផ្ញើទៅ​អាសយដ្ឋាន​អ៊ីមែល​ដែល​បាន​ចុះបញ្ជី​សម្រាប់ "$1" ។ 

សូម​ឡុកអ៊ីន​ម្តងទៀត​បន្ទាប់ពី​អ្នក​បាន​ទទួល​វា​។',
'blocked-mailpassword'       => 'អាសយដ្ឋានIPត្រូវបានហាមឃាត់ពីការកែប្រែ និងមិនអនុញ្ញាតឱ្យប្រើប្រាស់មុខងារសង្គ្រោះពាក្យសំងាត់ដើម្បីបង្ការការបំពាន។',
'eauthentsent'               => 'អ៊ីមែល​សម្រាប់​ផ្ទៀងផ្ទាត់​ត្រូវបានផ្ញើទៅ​អាសយដ្ឋានអ៊ីមែល​ដែលបានដាក់ឈ្មោះ។ មុននឹងមាន​អ៊ីមែលផ្សេងមួយទៀត​ត្រូវផ្ញើទៅ​គណនីនេះ អ្នកត្រូវតែ​តាមមើល​សេចក្តីណែនាំ​ក្នុងអ៊ីមែល​នេះ ដើម្បី បញ្ជាក់ថា​គណនីបច្ចុប្បន្ន​ពិតជា​របស់អ្នកពិតប្រាកដមែន។',
'throttled-mailpassword'     => 'អ៊ីមែលរំលឹកពាក្យសំងាត់ត្រូវបានផ្ញើទៅឱ្យអ្នកក្នុងកំលុងពេល{{PLURAL:$1|ម៉ោង|$1ម៉ោង}}ចុងក្រោយនេះ។ 

ដើម្បីបង្ការអំពើបំពាន អ៊ីមែលរំលឹកពាក្យសំងាត់តែមួយគត់នឹងត្រូវបាន​ផ្ញើក្នុងរយៈពេល{{PLURAL:$1|ម៉ោង|$1ម៉ោង}}។',
'mailerror'                  => 'កំហុសនៃការផ្ញើសារ៖ $1',
'acct_creation_throttle_hit' => 'អ្នកទស្សនា​វិគី​នេះ​កំពុងតែ​ប្រើប្រាស់​អាសយដ្ឋាន IP របស់​អ្នក​បង្កើត {{PLURAL:$1|គណនី 1|គណនី $1}} នា​ថ្ងៃ​ចុងក្រោយ ដែល​ជា​ចំនួន​អតិបរមារ​ដែល​ត្រូវ​បាន​អនុញ្ញាត​សម្រាប់​រយៈពេល​នេះ​។ ជា​លទ្ធផល អ្នកទស្សនា​ដែល​ប្រើប្រាស់​អាសយដ្ឋាន IP នេះ​​មិន​អាច​បង្កើត​គណនី​ផ្សេងទៀត​នៅ​ខណៈនេះ​បាន​ទេ​។',
'emailauthenticated'         => 'អាសយដ្ឋានអ៊ីមែលរបស់លោកអ្នក​ត្រូវបានបញ្ជាក់ថាត្រឹមត្រូវពិតប្រាកដក្នុង$2នៅ$3។',
'emailnotauthenticated'      => 'អាសយដ្ឋានអ៊ីមែលរបស់លោកអ្នក មិនទាន់ត្រូវបានបញ្ជាក់ថាត្រឹមត្រូវពិតប្រាកដនៅឡើយទេ។ មិនមានអ៊ីមែល ដែលនឹងត្រូវបានផ្ញើ សម្រាប់មុខងារពិសេសណាមួយដូចខាងក្រោម។',
'noemailprefs'               => 'បញ្ជាក់​អាសយដ្ឋាន​អ៊ីមែល​នៅ​ក្នុង​ចំណង់ចំណូលចិត្ត​របស់​អ្នក​សម្រាប់​លក្ខណៈ​ទាំងនេះ​ដើម្បី​ធ្វើការ​។',
'emailconfirmlink'           => 'បញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែលរបស់អ្នក',
'invalidemailaddress'        => 'អាសយដ្ឋានអ៊ីមែល​នេះមិនអាចទទួលយកបានទេ​ដោយសារវាមានទម្រង់​​មិនត្រឹមត្រូវ។ 

សូមបញ្ចូល​អាសយដ្ឋានមួយ​ដែលមាន​ទម្រង់​ត្រឹមត្រូវ ឬមួយក៏ទុកវាលនោះឱ្យនៅទំនេរ​​។',
'accountcreated'             => 'គណនីរបស់លោកអ្នកត្រូវបានបង្កើតហើយ',
'accountcreatedtext'         => 'គណនី $1 ត្រូវបានបង្កើតហើយ។',
'createaccount-title'        => 'ការបង្កើតគណនីសម្រាប់{{SITENAME}}',
'createaccount-text'         => 'មានអ្នកណាម្នាក់បានបង្កើតគណនីជាឈ្មោះ "$2" លើ{{SITENAME}}($4) ព្រមទាំងពាក្យសំងាត់ "$3" ។ អ្នកគួរតែឡុកអ៊ីនហើយផ្លាស់ប្តូរពាក្យសំងាត់របស់អ្នកនៅពេលនេះ។

អ្នកអាចរំលងសារនេះ ប្រសិនបើ​គណនីនេះត្រូវបានបង្កើតដោយមានបញ្ហា។',
'login-throttled'            => 'អ្នកបានព្យាយាមបញ្ជូលពាក្យសំងាត់ចូលទៅក្នុងគណនីនេះច្រើនដងពេកហើយ។​ សូមរងចាំមួយរយៈ មុនពេលសាកល្បងម្ដងទៀត។',
'loginlanguagelabel'         => 'ភាសា៖ $1',

# Password reset dialog
'resetpass'                 => '​ប្តូរ​ពាក្យសំងាត់​',
'resetpass_announce'        => 'អ្នកបានឡុកអ៊ីន​ដោយ​អក្សរកូដអ៊ីមែល​បណ្តោះអាសន្ន​មួយ​។ ដើម្បី​បញ្ចប់​ការឡុកអ៊ីន អ្នកត្រូវតែ​កំណត់​ពាក្យសំងាត់ថ្មី​មួយនៅទីនេះ ៖',
'resetpass_text'            => '<!-- បន្ថែមឃ្លានៅទីនេះ -->',
'resetpass_header'          => 'ប្ដូរ​ពាក្យសំងាត់​គណនី',
'oldpassword'               => 'ពាក្យសំងាត់ចាស់៖',
'newpassword'               => 'ពាក្យសំងាត់ថ្មី៖',
'retypenew'                 => 'សូមវាយពាក្យសំងាត់ថ្មី​ម្តងទៀត៖',
'resetpass_submit'          => 'ដាក់ពាក្យសំងាត់ និង​ឡុកអ៊ីន',
'resetpass_success'         => 'ពាក្យសំងាត់របស់អ្នកត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យហើយ! ឥឡូវនេះកំពុងឡុកអ៊ីន...',
'resetpass_forbidden'       => 'ពាក្យសំងាត់មិនអាចផ្លាស់ប្តូរបានទេ',
'resetpass-no-info'         => 'អ្នក​ចាំបាច់​ត្រូវតែ​ឡុកអ៊ីន ដើម្បី​ចូលដំណើរការ​ទំព័រ​នេះ​ដោយផ្ទាល់​។',
'resetpass-submit-loggedin' => 'ប្តូរពាក្យសំងាត់',
'resetpass-wrong-oldpass'   => 'លេខ​សម្ងាត់​បណ្ដោះ​អាសន្ន​ ឬ​បច្ចុប្បន្នមិន​មាន​សុពលភាព​។
អ្នក​​ប្រហែល​ជា​បាន​ផ្លាស់​ប្ដូរ​លេខ​សម្ងាត់​ដោយ​ជោគជ័យ​ ឬ​បានស្នើ​សុំ​លេខ​សម្ងាត់​​បណ្ដោះ​អាសន្ន​​ថ្មី​មួយ​។',
'resetpass-temp-password'   => 'ពាក្យសំងាត់បណ្តោះអាសន្ន:',

# Edit page toolbar
'bold_sample'     => 'អក្សរដិត',
'bold_tip'        => 'អក្សរដិត',
'italic_sample'   => 'អក្សរទ្រេត',
'italic_tip'      => 'អក្សរទ្រេត',
'link_sample'     => 'ចំណងជើង​តំណភ្ជាប់',
'link_tip'        => 'តំណភ្ជាប់​ខាងក្នុង',
'extlink_sample'  => 'http://www.example.com ចំណងជើង​តំណភ្ជាប់',
'extlink_tip'     => 'តំណភ្ជាប់​ខាងក្រៅ (កុំភ្លេច​ដាក់ http:// នៅពីមុខ)',
'headline_sample' => 'ចំណងជើងរងនៃអត្ថបទ',
'headline_tip'    => 'ចំណងជើងរង​កម្រិត​២',
'math_sample'     => 'បញ្ចូលរូបមន្ត​នៅទីនេះ',
'math_tip'        => 'រូបមន្ត​គណិតវិទ្យា (LaTeX)',
'nowiki_sample'   => 'បញ្ចូល​អត្ថបទគ្មានទម្រង់​នៅទីនេះ',
'nowiki_tip'      => 'មិនគិត​ទម្រង់​នៃ​វិគី',
'image_sample'    => 'ឧទាហរណ៍.jpg',
'image_tip'       => 'រូបភាពបង្កប់',
'media_sample'    => 'ឧទាហរណ៍.ogg',
'media_tip'       => 'តំណភ្ជាប់ឯកសារ',
'sig_tip'         => 'ហត្ថលេខា​របស់អ្នកជាមួយនឹងកាលបរិច្ឆេទ',
'hr_tip'          => 'បន្ទាត់ដេក (មិនសូវប្រើទេ)',

# Edit pages
'summary'                          => 'សេចក្ដីសង្ខេប:',
'subject'                          => 'ប្រធានបទ/ចំណងជើងរង:',
'minoredit'                        => 'នេះជា​កំណែប្រែតិចតួចប៉ុណ្ណោះ',
'watchthis'                        => 'តាមដាន​ទំព័រនេះ',
'savearticle'                      => 'រក្សាទំព័រទុក',
'preview'                          => 'មើលជាមុន',
'showpreview'                      => 'បង្ហាញ​ការមើលជាមុន',
'showlivepreview'                  => 'មើលជាមុនដោយផ្ទាល់',
'showdiff'                         => 'បង្ហាញ​បំលាស់ប្ដូរ',
'anoneditwarning'                  => "'''ប្រយ័ត្ន ៖''' អ្នកមិនទាន់បានឡុកអ៊ីន​ទេ។ អាសយដ្ឋាន IP របស់អ្នក​នឹងត្រូវបាន​កត់ត្រាទុក​ក្នុងប្រវត្តិកែប្រែ​នៃទំព័រ​នេះ។",
'missingsummary'                   => "'''រំលឹក៖''' អ្នកមិនទាន់បានផ្ដល់ឱ្យនូវសេចក្ដីសង្ខេបអំពីកំណែប្រែទេ។
បើសិនជាអ្នកចុច '''រក្សាទុក''' ម្ដងទៀតនោះកំណែប្រែរបស់អ្នកនឹងត្រូវរក្សាទុកដោយគ្មានវា។",
'missingcommenttext'               => 'សូមបញ្ចូលមួយវិចារនៅខាងក្រោម។',
'missingcommentheader'             => "'''រំលឹក៖''' អ្នកមិនទាន់បានផ្ដល់ឱ្យនូវ ប្រធានបទ/ចំណងជើង របស់វិចារនេះទេ។
បើសិនជាអ្នកចុច '''រក្សាទុក''' ម្ដងទៀតនោះកំណែប្រែរបស់អ្នកនឹងត្រូវរក្សាទុកដោយគ្មានវា។",
'summary-preview'                  => 'ការមើលជាមុននូវសេចក្ដីសង្ខេប:',
'subject-preview'                  => 'ការមើលជាមុននូវប្រធានបទ/ចំណងជើង:',
'blockedtitle'                     => 'អ្នកប្រើប្រាស់ត្រូវបានហាមឃាត់',
'blockedtext'                      => '<big>\'\'\'ឈ្មោះគណនី (ឬអាសយដ្ឋាន IP)របស់អ្នកត្រូវបានហាមឃាត់ហើយ។\'\'\'</big>

ការហាមឃាត់ត្រូវបានធ្វើដោយ $1 

ដោយសំអាងលើហេតុផល \'\'$2\'\'។


* ចាប់ផ្ដើមការហាមឃាត់ ៖ $8
* ផុតកំណត់ការហាមឃាត់ ៖ $6
* គណនីបាននឹងត្រូវពន្យាការហាមឃាត់់ ៖ $7


អ្នកអាចទាក់ទង $1 ឬ [[{{MediaWiki:Grouppage-sysop}}|អភិបាល]]ដទៃទៀតដើម្បីពិភាក្សាពីការហាមឃាត់់នេះ ។

អ្នកមិនអាចប្រើប្រាស់មុខងារ "អ៊ីមែលទៅអ្នកប្រើប្រាស់នេះ" បានទេ លើកលែងអាសយដ្ឋានអ៊ីមែលមានសុពលភាពមួយ​ត្រូវបានបញ្ជាក់​ក្នុង[[Special:Preferences|ចំណូលចិត្តនានានៃគណនី]]របស់លោកអ្នកហើយលោកអ្នកមិនត្រូវបានគេហាមឃាត់មិនឱ្យប្រើប្រាស់មុខងារនោះ។

អាសយដ្ឋាន IP បច្ចុប្បន្នរបស់លោកអ្នកគឺ $3 និងអត្តសញ្ញាណរាំងខ្ទប់គឺ  #$5 ។ សូមបញ្ចូលអាសយដ្ឋានទាំងនេះសម្រាប់គ្រប់សំណួរអង្កេត។',
'autoblockedtext'                  => 'អាសយដ្ឋានIPរបស់អ្នកបានត្រូវរាំងខ្ទប់ដោយស្វ័យប្រវត្តិ ព្រោះវាត្រូវបានប្រើប្រាស់ដោយអ្នកប្រើប្រាស់ផ្សេងទៀត​ ដែលត្រូវបានរាំងខ្ទប់ដោយ $1 ។ 

មូលហេតុលើកឡើង៖

:\'\'$2\'\'

* ការចាប់ផ្តើមហាមឃាត់៖ $8
* ពេលផុតកំណត់ហាមឃាត់៖ $6
* អ្នកដែលត្រូវរាំងខ្ទប់៖ $7

អ្នកអាចទាក់ទង $1 ឬ[[{{MediaWiki:Grouppage-sysop}}|អ្នកអភិបាល]]ណាម្នាក់ ដើម្បីពិភាក្សាអំពីការរាំងខ្ទប់នេះ។

សូមកត់សម្គាល់ថាអ្នកមិនអាចប្រើប្រាស់មុខងារ"អ៊ីមែលអ្នកប្រើប្រាស់នេះ"បានទេ លុះត្រាតែមានមួយអាសយដ្ឋានអ៊ីមែលដែលមានសុពលភាព បានចុះឈ្មោះ ក្នុង
[[Special:Preferences|ចំណូលចិត្ត]]របស់អ្នក ហើយអ្នកមិនត្រូវបានរាំងខ្ទប់មិនឱ្យប្រើប្រាស់មុខងារនោះ ។

អាសយដ្ឋាន IP បច្ចុប្បន្នរបស់អ្នកគឺ $3។ ID ដែលត្រូវបានរាំងខ្ទប់គឺ #$5។
សូមបញ្ចូលព័ត៌មានលំអិតខាងលើនេះ ក្នុងគ្រប់សំណួរអង្កេតដែលអ្នកបានបង្កើត។',
'blockednoreason'                  => 'គ្មានហេតុផល​ត្រូវបានលើកឡើង',
'blockedoriginalsource'            => "កូដនៃទំព័រ '''$1''' ត្រូវបានបង្ហាញដូចខាងក្រោម៖",
'blockededitsource'                => "ខ្លឹមសារ​នៃ '''កំណែប្រែ​របស់អ្នក''' ចំពោះ '''$1''' ត្រូវបាន​បង្ហាញ​ខាងក្រោម ៖",
'whitelistedittitle'               => 'តម្រូវឱ្យឡុកអ៊ីនដើម្បីកែប្រែ',
'whitelistedittext'                => 'អ្នកត្រូវតែជា $1 ដើម្បី​កែប្រែ​ខ្លឹមសារទំព័រ។',
'confirmedittext'                  => 'អ្នកត្រូវតែបញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែលរបស់អ្នកមុននឹងកែប្រែខ្លឹមសារអត្ថបទ។ ចូរកំណត់និងផ្តល់សុពលភាពឱ្យអាសយដ្ឋានអ៊ីមែល របស់អ្នកតាម [[Special:Preferences|ចំណូលចិត្តនានារបស់អ្នកប្រើប្រាស់]] ។',
'nosuchsectiontitle'               => 'មិនមានផ្នែក​បែបនេះ',
'nosuchsectiontext'                => 'អ្នកបាន​ព្យាយាម​កែប្រែផ្នែក​មួយ​ដែលមិនទាន់មាន​នៅឡើយ ។  ដោយហេតុថា​មិនមាន​ផ្នែក $1 ម៉្លោះហើយ​គ្មានកន្លែង​សម្រាប់​រក្សាទុក​កំណែប្រែ​របស់អ្នកទេ ។',
'loginreqtitle'                    => 'តម្រូវឱ្យឡុកអ៊ីន',
'loginreqlink'                     => 'ឡុកអ៊ីន',
'loginreqpagetext'                 => 'អ្នកត្រូវតែ$1ដើម្បីមើលទំព័រដទៃផ្សេងទៀត។',
'accmailtitle'                     => 'ពាក្យសំងាត់ត្រូវបានផ្ញើរួចហើយ។',
'accmailtext'                      => "ពាក្យសំងាត់​ដែល​បាន​បង្កើត​ដោយ​ចៃដន្យ​សម្រាប់ [[User talk:$1|$1]] ត្រូវបានផ្ញើទៅ $2 ហើយ​។

ពាក្យសំងាត់​សម្រាប់​​គណនី​ថ្មី​នេះ អាច​ត្រូវ​គេ​ប្ដូរ​បាន នៅ​ត្រង់​ទំព័រ ''[[Special:ChangePassword|ប្ដូរ​ពាក្យសំងាត់]]'' ដែល​នៅ​ខាង​លើ​ពាក្យ​ឡុកអ៊ីន​។",
'newarticle'                       => '(ថ្មី)',
'newarticletext'                   => "អ្នកបានតាម​តំណភ្ជាប់​ទៅ​ទំព័រដែលមិនទាន់មាននៅឡើយ។
ដើម្បីបង្កើតទំព័រនេះ សូមចាប់ផ្ដើមវាយ​ក្នុងប្រអប់ខាងក្រោម (សូមមើល [[{{MediaWiki:Helppage}}|ទំព័រ​ជំនួយ]] សម្រាប់​ព័ត៌មានបន្ថែម)។
បើ​អ្នកមក​ទីនេះ​ដោយច្រឡំ​ សូមចុចប៊ូតុង '''ត្រឡប់ក្រោយ''' របស់ឧបករណ៍រាវរក(browser)​របស់អ្នក។",
'anontalkpagetext'                 => "----''ទំព័រពិភាក្សានេះគឺសម្រាប់តែអ្នកប្រើប្រាស់អនាមិកដែលមិនទាន់បានបង្កើតគណនីតែប៉ុណ្ណោះ។ ដូច្នេះអាសយដ្ឋានលេខIPរបស់កុំព្យូទ័ររបស់លោកអ្នក​នឹងត្រូវបានបង្ហាញ ដើមី្បសម្គាល់លោកអ្នក។ 

អាសយដ្ឋានIPទាំងនោះអាចនឹងត្រូវប្រើដោយមនុស្សច្រើននាក់។ 

ប្រសិនបើអ្នកជាអ្នកប្រើប្រាស់អនាមិក​ហើយ​ប្រសិនបើអ្នកឃើញមានការបញ្ចេញយោបល់​ដែល​មិន​ទាក់ទងទៅនឹងអ្វីដែល​អ្នកបាន​ធ្វើ​ សូម[[Special:UserLogin|ចូលឬបង្កើតគណនី]] ឬ [[Special:UserLogin|ឡុកអ៊ីន]] ដើម្បីចៀសវាង​ការភ័ន្តច្រឡំ​ណាមួយជាយថាហេតុជាមួយនិងអ្នកប្រើប្រាស់អនាមិកដទៃទៀត។
''",
'noarticletext'                    => 'បច្ចុប្បន្ន គ្មានអត្ថបទណាមួយក្នុងទំព័រនេះទេ។ អ្នកអាច [[Special:Search/{{PAGENAME}}|ស្វែងរក​ចំណងជើង​នៃទំព័រនេះ]] ក្នុងទំព័រ​ផ្សេងៗ​​ [{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} ស្វែង​រក​កំណត់​ហេតុ​ដែល​ពាក់ព័ន្ធ] ឬ [{{fullurl:{{FULLPAGENAME}}|action=edit}} កែប្រែ​ទំព័រនេះ]។',
'userpage-userdoesnotexist'        => 'គណនីអ្នកប្រើប្រាស់ "$1" មិនបានត្រូវ ចុះបញ្ជី ។ ចូរឆែកមើល តើ អ្នកចង់ បង្កើត / កែប្រែ ទំព័រ នេះ ។',
'clearyourcache'                   => "'''សម្គាល់:''' បន្ទាប់ពីបានរក្សាទុករួចហើយ លោកអ្នកគួរតែសំអាត browser's cache របស់លោកអ្នកដើម្បីមើលការផ្លាស់ប្តូរ។ ខាងក្រោមនេះជាវិធីសំអាត browser's cache ចំពោះកម្មវិធីរុករក(Browser)មួយចំនួន។
* '''Mozilla / Firefox / Safari:''' សង្កត់ [Shift] ឱ្យជាប់រួចចុចប៊ូតុង ''Reload'' ឬក៏ចុច  ''Ctrl-F5'' ឬ ''Ctrl-R'' (ចំពោះApple Mac វិញ​ចុច ''Command-R'')។
* '''IE(Internet Explorer):''' សង្កត់ [Ctrl] ឱ្យជាប់ រួចចុច ''Refresh''ប៊ូតុង ឬក៏ចុច ''Ctrl-F5''​។ 
* '''Konqueror:''' ចុចប៊ូតុង  ''Reload'' ឬក៏ចុច ''F5'' 
* '''Opera:''' សូមចុច  ''[Tools]→[Preferences]''​។",
'usercssyoucanpreview'             => "'''គន្លឹះ ៖ ''' សូមប្រើប្រាស់​ប្រអប់ 'បង្ហាញការមើលមុន' ដើម្បី​ធ្វើតេស្ត​សន្លឹក CSS ថ្មីរបស់អ្នក​មុននឹង​រក្សាទុកវា ។",
'userjsyoucanpreview'              => "'''គន្លឹះ ៖ ''' សូមប្រើប្រាស់​ប្រអប់ 'បង្ហាញការមើលមុន' ដើម្បី​ធ្វើតេស្ត​សន្លឹក JS ថ្មីរបស់អ្នក​មុននឹង​រក្សាទុកវា ។",
'usercsspreview'                   => "'''កុំភ្លេចថា​អ្នកគ្រាន់តែ​កំពុងមើលជាមុនសន្លឹក CSS របស់អ្នក។ 
វាមិនទាន់​ត្រូវបានរក្សាទុកទេ!'''",
'userjspreview'                    => "'កុំភ្លេចថាអ្នកគ្រាន់តែកំពុង ធ្វើតេស្ត/មើលមុន ទំព័រអ្នកប្រើប្រាស់  JavaScript របស់អ្នក។ វាមិនទាន់ត្រូវបានរក្សាទុកទេ!'''",
'userinvalidcssjstitle'            => "'''ប្រយ័ត្ន៖''' គ្មានសំបក \"\$1\"។ ចងចាំថា ទំព័រផ្ទាល់ខ្លួន .css និង .js ប្រើប្រាស់ ចំណងជើង ជាអក្សរតូច, ឧទាហរណ៍  {{ns:user}}:Foo/monobook.css ត្រឹមត្រូវ, រីឯ {{ns:user}}:Foo/Monobook.css មិនត្រឹមត្រូវ។",
'updated'                          => '(បានបន្ទាន់សម័យ)',
'note'                             => "'''ចំណាំ៖'''",
'previewnote'                      => "'''នេះគ្រាន់តែជា​ការបង្ហាញការមើលជាមុនប៉ុណ្ណោះ។ បំលាស់ប្ដូរ​មិនទាន់បាន​រក្សាទុកទេ!'''",
'previewconflict'                  => 'ការមើលមុននេះយោងតាមអត្ថបទក្នុងប្រអប់កែប្រែខាងលើ។ ទំព័រអត្ថបទនឹងបង្ហាញចេញបែបនេះប្រសិនបើអ្នកជ្រើសរើសរក្សាទុក។',
'session_fail_preview'             => "'''សូមអភ័យទោស! យើងមិនអាចរក្សាទុកការកែប្រែរបស់អ្នកបានទេ ដោយសារបាត់ទិន្នន័យវេនការងារ។

សូមព្យាយាមម្តងទៀត។ 

បើនៅតែមិនបានទេ សូមព្យាយាម[[Special:UserLogout|ចាកចេញពីគណនីរបស់អ្នក]] រួចឡុកអ៊ីនឡើងវិញ។'''",
'session_fail_preview_html'        => "'''សូមអភ័យទោស! យើងមិនអាចរក្សាទុកកំណែប្រែរបស់លោកអ្នកបានទេ ដោយសារបាត់ទិន្នន័យវេនការងារ។'''

''ដោយសារ {{SITENAME}} មានអក្សរកូដ HTMLឆៅ ត្រូវបានបើកឱ្យប្រើប្រាស់ ហេតុនេះទំព័រមើលមុនត្រូវបានបិទបាំង ដើម្បីចៀសវាងការវាយលុកដោយ JavaScript ។''

'''បើនេះជាការប៉ុនប៉ងកែប្រែសមស្រប សូមព្យាយាមម្តងទៀត។ 

បើនៅតែមិនបានទេ សូមព្យាយាម[[Special:UserLogout|ចាកចេញពីគណនីរបស់អ្នក]] រួចឡុកអ៊ីនឡើងវិញ។'''",
'editing'                          => 'កំពុងកែប្រែ​ $1',
'editingsection'                   => "កំពុងកែប្រែ'''$1'''(ផ្នែក)",
'editingcomment'                   => 'កែប្រែ $1 (ផ្នែកថ្មី)',
'editconflict'                     => 'ភាពឆ្គងនៃកំណែប្រែ៖ $1',
'explainconflict'                  => 'ចាប់តាំងពីអ្នកបានបង្កើតទំព័រនេះមក មានអ្នកដទៃបានកែប្រែវាហើយ។ ផ្នែកខាងលើនៃទំព័រអត្ថបទ គឺជាកំណែប្រែថ្មី។ កំណែប្រែរបស់អ្នក គឺនៅផ្នែកខាងក្រោម។ ចូរដាក់កំណែប្រែរបស់អ្នកបញ្ចូលគ្នាជាមួយអត្ថបទដែលមាននៅផ្នែកខាងលើ។​ <strong>អត្ថបទនៅផ្នែកខខាងលើ</strong> នឹងត្រូវរក្សាទុក នៅពេលអ្នក ចុច"រក្សាទំព័រ"។',
'yourtext'                         => 'អត្ថបទរបស់អ្នក',
'storedversion'                    => 'កំណែដែលបានស្តារឡើងវិញ',
'nonunicodebrowser'                => "​'''ប្រយ័ត្ន​៖ កម្មវិធី​រុករក​របស់​អ្នក​មិន​គាំ​ទ្រ​ដល់​អក្សរ​ពុម្ព​យ៉ូនីកូដ​ទេ​។'''
មាន​ដំណោះ​ស្រាយ​មួយ​ដែល​អនុញ្ញាត​ឲ្យ​អ្នក​កែ​ប្រែ​ទំព័រ​ដោយ​សុវត្ថិភាព​៖ តួ​អក្សរមិន​មែន​ ASCII ​(non-ASCII) នឹង​បង្ហាញ​នៅ​ក្នុង​ប្រអប់​កែ​ប្រែ​ជា​កូដ​ hexadecimal ។",
'editingold'                       => "'''បម្រាម:អ្នកកំពុងតែកែកំណែប្រែដែលហួសសម័យរបស់ទំព័រនេះ។

ប្រសិនបើអ្នករក្សាវាទុក កំណែប្រែពីមុនទាំងប៉ុន្មាននឹងត្រូវបាត់បង់។'''",
'yourdiff'                         => 'ភាពខុសគ្នា',
'copyrightwarning'                 => "សូមធ្វើការកត់សម្គាល់​ថា គ្រប់ការរួមចំណែក​របស់អ្នក​នៅលើ {{SITENAME}} ត្រូវបាន​ពិចារណា​ដើម្បី​ផ្សព្វផ្សាយ​តាម​លិខិតអនុញ្ញាត $2 (សូម​មើល $1 សម្រាប់​ព័ត៌មាន​លំអិត) ។ បើអ្នកមិនចង់ឱ្យ​សំណេរ​របស់អ្នក​ត្រូវបានគេលុប កែប្រែ ឬក៏អ្នកមិនមានបំណងផ្សព្វផ្សាយវា សូមកុំដាក់​ស្នើវា​នៅទីនេះអី។<br />
អ្នកត្រូវសន្យាថា ​អ្នកសរសេរវា​ដោយខ្លួនអ្នក ឬបានចម្លងវា​ពី​កម្មសិទ្ធិសាធារណៈឬពីប្រភពសេរី ។
'''មិនត្រូវ​ដាក់ស្នើ​ការងារមានរក្សាសិទ្ធិកម្មសិទ្ឋិបញ្ញាដោយគ្មានការអនុញ្ញាតទេ!'''",
'copyrightwarning2'                => "សូមធ្វើការកត់សម្គាល់​ថា គ្រប់ការរួមចំណែក​ទៅ {{SITENAME}} អាច​ត្រូវបាន​កែប្រែ​ ផ្លាស់ប្ដូរ រឺលុបចោល ដោយអ្នករួមចំណែកដទៃទៀត។ បើអ្នកមិនចង់ឱ្យ​សំណេររបស់អ្នក​ត្រូវបានគេកែប្រែដោយ​គ្មានអាសូរទេនោះ សូមកុំដាក់​ស្នើវា​នៅទីនេះអី។<br />
អ្នកត្រូវសន្យាជាមួយ​យើង​ខ្ញុំផងដែរថា ​អ្នកសរសេរវា​ដោយខ្លួនអ្នក ឬ បានចម្លងវា​ពី​កម្មសិទ្ធិសាធារណៈឬពីប្រភពសេរី (សូមមើល $1 សម្រាប់ព័ត៌មាន​លំអិត)។

'''មិនត្រូវ​ដាក់ស្នើ​ការងារមានរក្សាសិទ្ធិកម្មសិទ្ឋិបញ្ញាដោយគ្មានការអនុញ្ញាតទេ!'''",
'longpagewarning'                  => "'''ប្រយ័ត្ន ៖ ទំព័រនេះមានទំហំ $1 គីឡូបៃ។ ឧបករណ៍រាវរក(browser)ខ្លះអាចមានបញ្ហាក្នុងការកែប្រែទំព័រក្បែរឬធំជាង៣២គីឡូបៃ​។

សូមពិចារណាអំពីលទ្ឋភាពបំបែកទំព័រជាផ្នែកតូចៗ ។ '''",
'longpageerror'                    => "'''កំហុស៖ អត្ថបទ​ដែល​អ្នក​បានដាក់​ស្នើ​មានទំហំ $1 គីឡូបៃ ដែលធំជាង​ទំហំអតិបរមា $2 គីឡូបៃ។ អត្ថបទនេះ​មិនអាច​រក្សាទុកបានទេ។'''",
'readonlywarning'                  => "'''ប្រយ័ត្ន:មូលដ្ឋានទិន្នន័យត្រូវបានចាក់សោសម្រាប់ការរក្សាទុក ដូច្នេះអ្នកនឹងមិនអាចរក្សាទុករាល់កំណែប្រែរបស់អ្នកបានទេឥឡូវនេះ។ សូមអ្នកចម្លងអត្ថបទ រួចដាក់ទៅក្នុងឯកសារដែលជាអត្ថបទ ហើយបន្ទាប់មករក្សាវាទុកនៅពេលក្រោយ។'''

អ្នកអភិបាលដែលបានចាក់សោវា បានផ្ដល់នួវការពន្យល់ដូចតទៅ៖ $1",
'protectedpagewarning'             => "'''ប្រយ័ត្ន៖ ទំព័រនេះ​ត្រូវបានចាក់សោ។ ដូច្នេះ​មានតែ​អ្នកប្រើប្រាស់​ដែល​មាន​អភ័យឯកសិទ្ឋិ​ជាអ្នកថែទាំប្រព័ន្ឋ​ (sysop) ទេទើបអាច​កែប្រែ​វាបាន។'''",
'semiprotectedpagewarning'         => "'''សម្គាល់៖''' ទំព័រនេះ​បានត្រូវ​ចាក់សោ។ ដូច្នេះ​មានតែអ្នកប្រើប្រាស់​ដែលបានចុះឈ្មោះ​ទើបអាចកែប្រែ​វា​បាន។",
'cascadeprotectedwarning'          => 'ប្រយ័ត្ន​៖ ទំព័រ​នេះ​ត្រូវ​បាន​ចាក់​សោ​ ដូច្នោះ​ហើយ​មាន​តែ​អ្នក​ប្រើ​ប្រាស់​ដែល​មាន​សិទ្ធិ​ជា​អ្នក​អភិបាល​ប៉ុណ្ណោះ​ អាច​កែ​ប្រែ​បាន។ ពីព្រោះ​ទំព័រ​នេះ​ត្រូវ​បាន​រួម​បញ្ចូល​ទៅ​ក្នុង​
{{PLURAL:$1|ទំព័រ​}}ដែលការ​ពារ​ជា​ថ្នាក់ (cascade-protected)៖',
'titleprotectedwarning'            => "ប្រយ័ត្ន៖ ទំព័រនេះត្រូវបានចាក់សោ ដូច្នេះមានតែ [[Special:ListGroupRights|អ្នកប្រើប្រាស់មួយចំនួន]] ប៉ុណ្ណោះអាចបង្កើតវា។'''",
'templatesused'                    => 'ទំព័រគំរូប្រើនៅក្នុងទំព័រនេះគឺ៖',
'templatesusedpreview'             => 'ទំព័រគំរូ​នានាដែល​បានប្រើប្រាស់​ក្នុងការមើលមុននេះ៖',
'templatesusedsection'             => 'ទំព័រគំរូដែលត្រូវបានប្រើប្រាស់ក្នុងផ្នែកនេះ៖',
'template-protected'               => '(ត្រូវបានការពារ)',
'template-semiprotected'           => '(ត្រូវបានការពារពាក់កណ្តាល)',
'hiddencategories'                 => 'ទំព័រនេះស្ថិតនៅក្នុង {{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានបិទបាំង១|ចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានបិទបាំង $1}}:',
'nocreatetitle'                    => 'ការបង្កើតទំព័រ​ត្រូវបានកម្រិត',
'nocreatetext'                     => '{{SITENAME}} បានដាក់កំហិតលទ្ធភាពបង្កើតទំព័រថ្មី ។
អ្នកអាចត្រឡប់ក្រោយ និង កែប្រែទំព័រមានស្រាប់ ឬ  [[Special:UserLogin|ចូលឬបង្កើតគណនី]]។',
'nocreate-loggedin'                => 'អ្នកគ្មានការអនុញ្ញាត​ឱ្យបង្កើតទំព័រថ្មី​ទេ។',
'permissionserrors'                => 'កំហុសនៃការអនុញ្ញាតនានា',
'permissionserrorstext'            => 'អ្នកគ្មានការអនុញ្ញាតឱ្យធ្វើអ្វីទាំងនោះទេ សម្រាប់{{PLURAL:$1|ហេតុផល|ហេតុផល}}ដូចតទៅ៖',
'permissionserrorstext-withaction' => 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យ$2ទេ ដោយសារ{{PLURAL:$1|មូលហេតុ|មូលហេតុ}}ដូចខាងក្រោម:',
'recreate-moveddeleted-warn'       => "'''ប្រយ័ត្ន៖ អ្នកកំពុង​បង្កើតឡើងវិញ​នូវទំព័រដែលទើបតែ​បានលុបចេញ។'''

អ្នក​គួរពិចារណាមើលថា​​តើជាការសមស្របទេដែលបន្តកែប្រែ​ទំព័រនេះ។
កំណត់ហេតុ​លុបចេញ​នៃទំព័រនេះ ត្រូវបានផ្ដល់ជូន​នៅ​​ទីនេះ​ដើម្បីងាយ​តាមដាន៖",
'moveddeleted-notice'              => 'ទំព័រនេះត្រូវបានលុបចេញហើយ។
កំណត់ហេតុនៃ​ទំព័រនេះត្រូវបាន​ផ្ដល់ជូន​នៅ​ខាងក្រោម​សម្រាប់ជាឯកសារ​យោង​។',
'log-fulllog'                      => 'បង្ហាញ​កំណត់ហេតុ​ទាំងមូល​',
'edit-gone-missing'                => 'មិនអាចបន្ទាន់សម័យទំព័រនេះទេ។

ទំព័រនេះហាក់ដូចជាត្រូវបានលុបចោលហើយ។',
'edit-conflict'                    => 'កែប្រែ​ភាពឆ្គង​។',
'edit-no-change'                   => 'ការកែប្រែរបស់អ្នកត្រូវបានចោល ព្រោះគ្មានការផ្លាស់ប្ដូរណាមួយត្រូវបានធ្វើនៅលើអត្ថបទនេះទេ។',
'edit-already-exists'              => 'មិនអាចបង្កើតទំព័រថ្មីមួយទេ។

ទំព័រនេះមានរួចហើយ។',

# Parser/template warnings
'expensive-parserfunction-category'       => 'ទំព័រ​ជាមួយ​ការ​ហៅ​អនុគមន៍​ញែក​ចេញ​ពីគ្នា​ចំនួន​ច្រើនពេក​​',
'post-expand-template-inclusion-warning'  => 'ប្រយ័ត្ន៖ ទំព័រគំរូដែលបានបញ្ចូលមានទំហំធំពេកហើយ។

ទំព័រគំរូមួយចំនួនអាចនឹងមិនត្រូវបានបញ្ចូល។',
'post-expand-template-inclusion-category' => 'ទំព័រទាំងឡាយដែលមានបញ្ចូលទំព័រគំរូហួសចំណុះ',
'post-expand-template-argument-category'  => 'ទំព័រ​ដែល​មាន​ផ្ទុក​នូវ​ការ​ពិភាក្សា​នៃ​គំរូ​ដែល​បាន​បោះបង់​',
'parser-template-loop-warning'            => 'បាន​រក​ឃើញ​រង្វិល​ជុំ​នៃ​គំរូ​៖ [[$1]]',

# "Undo" feature
'undo-success' => 'ការកែប្រែគឺមិនអាចបញ្ចប់។ សូមពិនិត្យ​ការប្រៀបធៀបខាងក្រោមដើម្បីផ្ទៀងផ្ទាត់ថា​នេះគឺជាអ្វីដែលអ្នកចង់ធ្វើហើយបន្ទាប់មកទៀត​រក្សាបំលាស់ប្ដូរខាងក្រោមទុក ដើម្បីបញ្ចប់ការកែប្រែដែលមិនទាន់រួចរាល់។',
'undo-failure' => 'កំណែ​មិន​អាច​មិន​ធ្វើ​ឡើង​វិញ​បាន​ទេ​ ដោយ​សារ​ការ​ធ្វើ​ឲ្យ​មាន​ជម្លោះ​កំណែ​នៅ​ចន្លោះ​កណ្ដាល​។',
'undo-norev'   => 'កំណែ​មិន​អាច​មិន​ធ្វើ​ឡើង​វិញ​បាន​ទេ​ ពីព្រោះ​វា​មិន​មាន​ឬ​ត្រូវ​បាន​លុប​បាត់​ទៅ​ហើយ​។',
'undo-summary' => 'មិន​ធ្វើ​វិញ​នូវ​កំណែ​ប្រែ $1 ដោយ​ [[Special:Contributions/$2|$2]] ([[User talk:$2|ការពិភាក្សា​]])',

# Account creation failure
'cantcreateaccounttitle' => 'មិនអាចបង្កើតគណនីបានទេ',
'cantcreateaccount-text' => "ការបង្កើតគណនីពីអាសយដ្ឋាន IP ('''$1''') នេះ ត្រូវបានរារាំងដោយ [[User:$3|$3]]។

ហេតុផលដែលត្រូវលើកឡើងដោយ $3 គឺ ''$2''",

# History pages
'viewpagelogs'           => 'មើលកំណត់ហេតុសម្រាប់ទំព័រនេះ',
'nohistory'              => 'មិនមានប្រវត្តិកំណែប្រែ​ចំពោះទំព័រនេះ។',
'currentrev'             => 'កំណែបច្ចុប្បន្ន',
'currentrev-asof'        => 'កំណែប្រែបច្ចុប្បន្ន $1',
'revisionasof'           => 'កំណែ​របស់ $1',
'revision-info'          => 'កំណែ​របស់ $1 ដោយ $2',
'previousrevision'       => '← កំណែ​មុន',
'nextrevision'           => 'កំណែបន្ទាប់ →',
'currentrevisionlink'    => 'កំណែប្រែបច្ចុប្បន្ន',
'cur'                    => 'បច្ចុប្បន្ន',
'next'                   => 'បន្ទាប់',
'last'                   => 'ចុងក្រោយ',
'page_first'             => 'ដំបូង',
'page_last'              => 'ចុងក្រោយ',
'histlegend'             => "ជម្រើសផ្សេងគ្នា៖ សូមគូសក្នុងកូនប្រអប់ពីមុខកំណែ(versions)ដែលអ្នកចង់ប្រៀបធៀប រួចចុចច្នុច enter ឬប៊ូតុងនៅខាងក្រោម។<br />
'''ពាក្យតំណាង'''៖(បច្ចុប្បន្ន) = ភាពខុសគ្នាជាមួយនឹងកំណែបច្ចុប្បន្ន, (ចុងក្រោយ) = ភាពខុសគ្នារវាងកំណែប្រែពីមុន, តិច = កំណែប្រែតិចតួច",
'history-fieldset-title' => 'ស្វែងរកក្នុងប្រវត្តិ',
'histfirst'              => 'ដំបូងៗបំផុត',
'histlast'               => 'ថ្មីៗបំផុត',
'historysize'            => '({{PLURAL:$1|1បៃ|$1បៃ}})',
'historyempty'           => '(ទទេ)',

# Revision feed
'history-feed-title'          => 'ប្រវត្តិនៃកំណែ',
'history-feed-description'    => 'ប្រវត្តិនៃកំណែទំព័រនេះលើវិគី',
'history-feed-item-nocomment' => 'ដោយ$1នៅវេលា$2',
'history-feed-empty'          => 'ទំព័រដែលអ្នកបានស្នើមិនមានទេ។
ប្រហែលជាវាត្រូវបានគេលុបចោលពីវីគីឬ​ត្រូវបានគេដាក់ឈ្មោះថ្មី។
សូមសាក [[Special:Search|ស្វែងរកនៅក្នុងវិគី]] ដើម្បីរកទំព័រថ្មីដែលមានការទាក់ទិន។',

# Revision deletion
'rev-deleted-comment'         => '(វិចារត្រូវបានដកចេញ)',
'rev-deleted-user'            => '(ឈ្មោះអ្នកប្រើប្រាស់ត្រូវបានដកចេញ)',
'rev-deleted-event'           => '(កំណត់ហេតុសកម្មភាពត្រូវបានដកចេញ)',
'rev-deleted-text-permission' => "កំណែ​ប្រែ​នៃ​ទំព័រ​នេះ​ត្រូវ​បាន'''​លុប'''​។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការ​លុប​]។",
'rev-deleted-text-unhide'     => "កំណែ​ប្រែ​នៃ​ទំព័រ​នេះ​ត្រូវ​បាន'''​លុប'''​។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការ​លុប​]។
ក្នុង​នាម​ជា​អ្នក​អភិបាល​ អ្នក​នៅ​តែ​អាច​[$1 មើល​កំណែ​នេះ​]ប្រសិន​បើ​អ្នក​ចង់​។",
'rev-suppressed-text-unhide'  => "កំណែ​ប្រែ​នៃ​ទំព័រ​នេះ​ត្រូវ​បាន'''ហាម​ឃាត់​'''​។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការហាម​ឃាត់​​]។
ក្នុង​នាម​ជា​អ្នក​អភិបាល​ អ្នក​នៅ​តែ​អាច​[$1 មើល​កំណែ​នេះ​]ប្រសិន​បើ​អ្នក​ចង់​។",
'rev-deleted-text-view'       => "កំណែ​ប្រែ​នៃ​ទំព័រ​នេះ​ត្រូវ​បាន'''​លុប'''​។
ក្នុង​នាម​ជា​អ្នក​អភិបាល​ អ្នក​អាច​មើល​កំណែប្រែ​​នេះ​បាន​។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការ​លុប​]។",
'rev-suppressed-text-view'    => "កំណែ​ប្រែ​នៃ​ទំព័រ​នេះ​ត្រូវ​បាន'''ហាម​ឃាត់​'''​។
ក្នុង​នាម​ជា​អ្នក​អភិបាល​ អ្នក​​អាច​មើល​វា​បាន​។ ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការហាម​ឃាត់​​]។",
'rev-deleted-no-diff'         => "អ្នក​មិន​អាច​មើល​ភាព​ខុស​គ្នា​នេះ​បាន​ទេ​ពី​ព្រោះ​កំណែ​មួយ​នៃ​កំណែ​ប្រែ​ទាំង​អស់​ត្រូវ​បាន'''​លុប​'''។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការ​លុប​]។",
'rev-deleted-unhide-diff'     => "កំណែ​ប្រែ​មួយ​នៃភាព​ខុស​គ្នា​​នេះ​ត្រូវ​បាន'''​លុប'''​។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការ​លុប​]។
ក្នុង​នាម​ជា​អ្នក​អភិបាល​ អ្នក​នៅ​តែ​អាច​[$1 មើលភាព​ខុស​គ្នា​​នេះ​]ប្រសិន​បើ​អ្នក​ចង់​។",
'rev-delundel'                => 'បង្ហាញ/លាក់',
'revisiondelete'              => 'លុបចេញ / លែងលុបចេញ កំណែនានា',
'revdelete-nooldid-title'     => 'គ្មានកំណែប្រែដែលមានគោលដៅទេ',
'revdelete-nologtype-title'   => 'មិន​បាន​ឲ្យ​ប្រភេទ​នៃ​កំណត់ហេតុ​',
'revdelete-nologtype-text'    => 'អ្នក​មិន​បាន​បញ្ជាក់​អំពី​ប្រភេទរបស់​​កំណត់​ហេតុ​ដើម្បី​អនុវត្ត​សកម្មភាព​នេះ​។',
'revdelete-nologid-title'     => 'ការ​វាយ​បញ្ចូល​កំណត់ហេតុ​ដែល​គ្មាន​សុពលភាព​',
'revdelete-no-file'           => 'មិនមានឯកសារ​ដូចដែលបានបញ្ជាក់ទេ។',
'revdelete-show-file-confirm' => 'តើ​អ្នក​ប្រាកដ​ហើយ​ថា​អ្នក​ចង់​មើល​កំណែ​ប្រែ​ដែល​បាន​លុប​នៃ​ឯកសារ​ "<nowiki>$1</nowiki>" ពី $2 នៅ $3 ?',
'revdelete-show-file-submit'  => 'បាទ/ចាស',
'revdelete-selected'          => "'''{{PLURAL:$2|កំណែប្រែ​ដែលបាន​ជ្រើសយក}}របស់​[[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|ព្រឹត្តិការណ៍​កំណត់​ហេតុ​ដែលបាន​ជ្រើសយក}}:'''",
'revdelete-suppress-text'     => "ការ​ហាមឃាត់​គួរ​ត្រូវ​បាន​អនុវត្តិ​លើ​ករណី​ដូច​ខាង​ក្រោម​នេះ​ប៉ុណ្ណោះ​៖
* ព័ត៌មាន​ផ្ទាល់​ខ្លួន​ ឯកជន​មិន​សមរម្យ​
*: ''អាសយដ្ឋាននៃ​គេហដ្ឋាន​​ ​លេខ​ទូរស័ព្ទ និងលេខ​សន្តិសុខ​សង្គម​ជាដើម​''",
'revdelete-legend'            => 'ដាក់កំហិតគំហើញ',
'revdelete-hide-text'         => 'បិទបាំងឃ្លានៃកំណែប្រែ',
'revdelete-hide-name'         => 'បិទបាំងសកម្មភាពនិងគោលដៅ',
'revdelete-hide-comment'      => 'បិទបាំងកំណែប្រែវិចារ',
'revdelete-hide-user'         => 'បិទបាំងឈ្មោះអ្នកប្រើប្រាស់​ឬអាសយដ្ឋាន IP នៃអ្នករួមចំណែក',
'revdelete-hide-restricted'   => 'ដាក់កំហិត​ទិន្នន័យ​ពី​អ្នកអភិបាល ក៏​ដូចជា​អ្នក​ដទៃ​ទៀត',
'revdelete-suppress'          => 'លាក់ទិន្នន័យពីអ្នកថែទាំប្រព័ន្ធ ព្រមទាំងពីសមាជិកដទៃទៀតផងដែរ',
'revdelete-hide-image'        => 'បិទបាំងខ្លឹមសារនៃឯកសារ',
'revdelete-unsuppress'        => 'ដកចេញការដាក់កំហិតលើកំណដែលបានស្តារឡើងវិញ',
'revdelete-log'               => 'មូលហេតុ​ក្នុងការ​លុប​ចោល​៖',
'revdelete-submit'            => 'អនុវត្តន៍ទៅកំណែដែលបានជ្រើសយក',
'revdelete-logentry'          => 'បានផ្លាស់ប្តូរគំហើញកំណែនៃ[[$1]]',
'logdelete-logentry'          => 'បានផ្លាស់ប្តូរគំហើញហេតុការនៃ[[$1]]',
'revdelete-success'           => "'''បានកំណត់គំហើញកំណែដោយជោគជ័យ។'''",
'revdelete-failure'           => "'''មិន​អាចកំណត់គំហើញនៃ​កំណែប្រែ​បាន​៖'''
$1",
'logdelete-success'           => "'''បានកំណត់គំហើញកំណត់ហេតុដោយជោគជ័យ។'''",
'logdelete-failure'           => "'''មិន​អាចកំណត់គំហើញនៃ​កំណត់​ហេតុ​​បាន​៖'''
$1",
'revdel-restore'              => 'ផ្លាស់ប្ដូរគំហើញ',
'pagehist'                    => 'ប្រវត្តិទំព័រ',
'deletedhist'                 => 'ប្រវត្តិដែលត្រូវបានលុប',
'revdelete-content'           => 'ខ្លឹមសារ',
'revdelete-summary'           => 'កែប្រែសេចក្ដីសង្ខេប',
'revdelete-uname'             => 'អ្នកប្រើប្រាស់',
'revdelete-restricted'        => 'បានអនុវត្តការដាក់កំហិតចំពោះអ្នកថែទាំប្រព័ន្ធ',
'revdelete-unrestricted'      => 'បានដកការដាក់កំហិតចេញសម្រាប់អ្នកថែទាំប្រព័ន្ធ',
'revdelete-hid'               => 'បានលាក់$1',
'revdelete-unhid'             => 'ឈប់លាក់$1',
'revdelete-log-message'       => '$1 ចំពោះ $2 {{PLURAL:$2|កំណែ​ប្រែ​}}',
'logdelete-log-message'       => '$1 ចំពោះ $2 {{PLURAL:$2|ព្រឹត្តិការណ៍|ព្រឹត្តិការណ៍}}',
'revdelete-hide-current'      => 'មាន​កំហុស​ពេល​កំពុង​លាក់​បាំង​វត្ថុ​ដែល​មាន​កាល​បរិច្ឆេទ $2, $1៖ នេះ​គឺ​ជា​កំណែបច្ចុប្បន្ន​។​
វា​មិន​អាច​លាក់​បាំង​បាន​ទេ​។',
'revdelete-show-no-access'    => 'មាន​កំហុស​ពេល​កំពុង​បង្ហាញ​វត្ថុ​ដែល​មាន​កាល​បរិច្ឆេទ $2, $1៖ វត្ថុ​នេះ​ត្រូវ​បាន​កត់​សម្គាល់​ជា"ការ​ដាក់​កំហិត​"​។​
អ្នក​មិន​អាច​ចូល​ទៅ​កាន់​វា​​បាន​ទេ​។',
'revdelete-modify-no-access'  => 'មាន​កំហុស​ពេល​កំពុង​កែ​សម្រួល​​វត្ថុ​ដែល​មាន​កាល​បរិច្ឆេទ $2, $1៖ វត្ថុ​នេះ​ត្រូវ​បាន​កត់​សម្គាល់​ជា"ការ​ដាក់​កំហិត​"​។​ 
អ្នក​មិន​អាច​ចូល​ទៅ​កាន់​វា​​បាន​ទេ​។',
'revdelete-modify-missing'    => 'មាន​កំហុស​ពេល​កំពុង​កែ​សម្រួលវត្ថុដែលមាន​លេខ​ ID $1​​៖ វា​បាន​បាត់​​ពី​មូលដ្ឋាន​ទិន្នន័យ​!​',
'revdelete-no-change'         => "'''ប្រយ័ត្ន​៖''' វត្ថុ​ដែល​មាន​កាល​បរិច្ឆេទ​ $2, $1 ត្រូវ​បាន​ស្នើ​សុំ​ការ​កំណត់​គំហើញ​រួច​ហើយ​។",
'revdelete-otherreason'       => 'មូលហេតុផ្សេង​ៗ/ដទៃទៀត​៖',
'revdelete-reasonotherlist'   => 'មូលហេតុផ្សេង​ទៀត​',
'revdelete-edit-reasonlist'   => 'មូលហេតុនៃការលុបការកែប្រែ',

# Suppression log
'suppressionlog' => 'កំណត់​ហេតុ​នៃ​ការ​ហាម​ឃាត់​',

# History merging
'mergehistory'                     => 'បញ្ចូលរួមគ្នាប្រវត្តិទាំងឡាយនៃទំព័រ',
'mergehistory-box'                 => 'បញ្ចូលរួមគ្នាកំណែទាំងឡាយនៃពីរទំព័រ៖',
'mergehistory-from'                => 'ទំព័រកូដ៖',
'mergehistory-into'                => 'ទំព័រគោលដៅ៖',
'mergehistory-list'                => 'ប្រវត្តិកំណែប្រែដែលអាចបញ្ចូលរួមគ្នាបាន',
'mergehistory-go'                  => 'បង្ហាញកំណែប្រែដែលអាចបញ្ចូលរួមគ្នាបាន',
'mergehistory-submit'              => 'បញ្ចូលរួមគ្នានូវកំណែនានា',
'mergehistory-empty'               => 'គ្មានកំណែ ណាមួយ អាចត្រូវបាន បញ្ចូលរួមគ្នា.',
'mergehistory-success'             => '$3 {{PLURAL:$3|កំណែ​ប្រែ​}}នៃ[[:$1]] បានបញ្ចូល​រួម​គ្នា​ជាមួយ​[[:$2]]ដោយ​ជោគ​ជ័យ​។',
'mergehistory-fail'                => 'មិនអាចធ្វើការប្របាច់បញ្ចូលប្រវត្តិ។ សូមពិនិត្យទំព័រនេះ និងប៉ារ៉ាម៉ែត្រពេលវេលាឡើងវិញ។',
'mergehistory-no-source'           => 'ទំព័រប្រភព $1 មិនមានទេ។',
'mergehistory-no-destination'      => 'ទំព័រគោលដៅ $1 មិនមានទេ។',
'mergehistory-invalid-source'      => 'ទំព័រ ប្រភព ត្រូវតែមាន មួយចំណងជើង បានការ។',
'mergehistory-invalid-destination' => 'ទំព័រ គោលដៅ ត្រូវតែមាន មួយចំណងជើង បានការ។',
'mergehistory-autocomment'         => 'បានរំលាយបញ្ចូល [[:$1]] ទៅក្នុង [[:$2]]',
'mergehistory-comment'             => 'រំលាយបញ្ចូល [[:$1]] ទៅក្នុង [[:$2]]: $3',
'mergehistory-same-destination'    => 'ទំព័រប្រភពនិងទំព័រគោលដៅមិនអាចមានចំណងជើងដូចគ្នាបានទេ។',
'mergehistory-reason'              => 'មូលហេតុ:',

# Merge log
'mergelog'           => 'កំណត់ហេតុនៃការបញ្ចូលរួមគ្នា',
'pagemerge-logentry' => 'បានបញ្ចូល[[$1]]ជាមួយ[[$2]]រួមគ្នា (កំណែរហូតដល់$3)',
'revertmerge'        => 'បំបែកចេញ',
'mergelogpagetext'   => 'ខាងក្រោមគឺជាតារាងរបស់ការបញ្ចូលគ្នាថ្មីៗបំផុតរបស់ប្រវត្តិនៃទំព័រមួយទៅក្នុងប្រវត្តិនៃទំព័រមួយទៀត។',

# Diffs
'history-title'            => 'ប្រវត្តិកំណែប្រែនានានៃ "$1"',
'difference'               => '(ភាពខុសគ្នានៃកំណែនានា)',
'lineno'                   => 'បន្ទាត់ទី$1៖',
'compareselectedversions'  => 'ប្រៀបធៀប​កំណែប្រែ​ដែលបាន​ជ្រើសយក',
'showhideselectedversions' => 'បង្ហាញ​/លាក់​កំណែប្រែ​ដែលបាន​ជ្រើសយក',
'visualcomparison'         => 'បំរៀបធៀបរាងរៅ',
'wikicodecomparison'       => 'បំរៀបធៀបអត្ថបទវិគី',
'editundo'                 => 'មិនធ្វើវិញ',
'diff-multi'               => '({{PLURAL:$1|កំណែប្រែកម្រិតបង្គួរមួយ|កំណែប្រែកម្រិតបង្គួរចំនួន$1}}មិនត្រូវបានបង្ហាញ)',
'diff-movedto'             => 'បានប្ដូរទីតាំងទៅ $1',
'diff-styleadded'          => 'បានបន្ថែម$1រចនាបថ',
'diff-added'               => 'បានបន្ថែម$1',
'diff-changedto'           => 'បានផ្លាស់ប្ដូរទៅ$1',
'diff-movedoutof'          => 'បានប្ដូរទីតាំងចេញពី $1',
'diff-styleremoved'        => 'បានដកចេញ$1រចនាបថ',
'diff-removed'             => 'បានដកចេញ$1',
'diff-changedfrom'         => 'បានផ្លាស់ប្ដូរពី$1',
'diff-src'                 => 'ប្រភព',
'diff-withdestination'     => 'ជាមួយនឹងគោលដៅ$1',
'diff-with'                => '&#32;ជាមួយ $1 $2',
'diff-with-final'          => '&#32;និង $1 $2',
'diff-width'               => 'ទទឹង',
'diff-height'              => 'កម្ពស់',
'diff-p'                   => "'''កថាខណ្ឌ'''មួយ",
'diff-blockquote'          => "'''ពាក្យ​ពេចន៍​'''មួយ​",
'diff-h1'                  => "'''ចំណងជើង (កម្រិត ១)'''មួយ",
'diff-h2'                  => "'''ចំណងជើង (កម្រិត ២)'''មួយ",
'diff-h3'                  => "'''ចំណងជើង (កម្រិត ៣)'''មួយ",
'diff-h4'                  => "'''ចំណងជើង (កម្រិត ៤)'''មួយ",
'diff-h5'                  => "'''ចំណងជើង (កម្រិត ៥)'''មួយ",
'diff-ul'                  => "'''បញ្ជីគ្មានរបៀប'''មួយ",
'diff-ol'                  => "'''បញ្ជីមានរបៀប'''មួយ",
'diff-li'                  => "'''វត្ថុ​ក្នុង​បញ្ជី​'''មួយ​",
'diff-table'               => "'''តារាង'''មួយ",
'diff-tbody'               => "'''មាតិកាតារាង'''មួយ",
'diff-tr'                  => "'''ជួរដេក'''មួយ",
'diff-td'                  => "'''ប្រអប់'''មួយ",
'diff-th'                  => "'''ចំណង​ជើង​'''មួយ",
'diff-br'                  => "'''ចុះបន្ទាត់'''",
'diff-hr'                  => "'''បន្ទាត់ដេក'''មួយ",
'diff-code'                => "'''តួ​លេខ​កូដ​កុំព្យូទ័រ​'''មួយ​",
'diff-dl'                  => "'''បញ្ជីនិយមន័យ'''មួយ",
'diff-dt'                  => "'''ពាក្យ​និយមន័យ'''មួយ",
'diff-dd'                  => "'''និយមន័យ'''មួយ",
'diff-input'               => "'''ការ​វាយ​បញ្ចូល​'''មួយ​",
'diff-form'                => "'''សំនុំ​បែប​បទ'''មួយ​",
'diff-img'                 => "'''រូបភាព'''មួយ",
'diff-span'                => "'''វិសាលភាព​'''មួយ​",
'diff-a'                   => "'''តំណភ្ជាប់'''មួយ",
'diff-i'                   => "'''អក្សរទ្រេត'''",
'diff-b'                   => "'''អក្សរដិត'''",
'diff-strong'              => "'''ខ្លាំង​'''",
'diff-em'                  => "'''សង្កត់​ធ្ងន់​'''",
'diff-font'                => "'''ពុម្ពអក្សរ'''",
'diff-big'                 => "'''ធំ'''",
'diff-del'                 => "'''ត្រូវបានលប់ចោល'''",
'diff-tt'                  => "'''ទទឹងថេរ'''",
'diff-sub'                 => "'''អក្សរតូចក្រោម'''",
'diff-sup'                 => "'''អក្សរតូចលើ'''",
'diff-strike'              => "'''ឆូត'''",

# Search results
'searchresults'                    => 'លទ្ធផលស្វែងរក',
'searchresults-title'              => 'លទ្ធផល​ស្វែងរក​សម្រាប់ "$1"',
'searchresulttext'                 => 'ចំពោះព័ត៌មានបន្ថែមអំពីការស្វែងរកក្នុង{{SITENAME}}, សូមមើល[[ជំនួយ:មាតិកា|ទំព័រជំនួយ]]។',
'searchsubtitle'                   => 'អ្នកបានស្វែងរក \'\'\'[[:$1]]\'\'\'([[Special:Prefixindex/$1|គ្រប់ទំព័រដែលផ្ដើមដោយ "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|គ្រប់ទំព័រដែលភ្ជាប់មក "$1"]])',
'searchsubtitleinvalid'            => "អ្នកបានស្វែងរក '''$1'''",
'noexactmatch'                     => "'''គ្មានទំព័រ​ណាដែលមានចំណងជើង \"\$1\" ទេ។''' អ្នកអាច [[:\$1|បង្កើតទំព័រនេះ]]។",
'noexactmatch-nocreate'            => "'''គ្មានទំព័រ​ណាដែលមានចំណងជើង \"\$1\"ទេ។'''",
'toomanymatches'                   => 'មានតំណភ្ជាប់ច្រើនណាស់ត្រូវបានបង្ហាញ ចូរព្យាយាមប្រើសំណួរផ្សេងមួយទៀត',
'titlematches'                     => 'ភាពត្រូវគ្នានៃចំណងជើងទំព័រ',
'notitlematches'                   => 'ពុំមានចំណងជើងទំព័រណាផ្គូផ្គងទេ',
'textmatches'                      => 'ទំព័រអត្ថបទផ្គូរផ្គងគ្នា',
'notextmatches'                    => 'គ្មានទំព័រណាមួយដែលមានខ្លឹមសារផ្គូផ្គងនឹងឃ្លាឬពាក្យនេះទេ',
'prevn'                            => 'មុន {{PLURAL:$1|$1}}',
'nextn'                            => 'បន្ទាប់ {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|លទ្ធផល|លទ្ធផល}}មុន',
'nextn-title'                      => '$1 {{PLURAL:$1|លទ្ឋផល|លទ្ឋផល}}​បន្ទាប់​',
'shown-title'                      => 'បង្ហាញ $1 {{PLURAL:$1|លទ្ធផល|លទ្ធផល}}ក្នុងមួយទំព័រ',
'viewprevnext'                     => 'មើល ($1) ($2) ($3)',
'searchmenu-legend'                => 'ជម្រើសនានាក្នុងការស្វែងរក',
'searchmenu-exists'                => "* ទំព័រ '''[[$1]]'''",
'searchmenu-new'                   => "'''[[:$1|បង្កើត]]ទំព័រ ''[[$1]]'' នៅ​លើ​វិគី​នេះ!'''",
'searchhelp-url'                   => 'Help:មាតិកា',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|រុក​រក​ក្នុង​ទំព័រ​នានា​ជាមួយ​បុព្វបទ​នេះ​]]',
'searchprofile-articles'           => 'ទំព័រ​មាតិកា',
'searchprofile-project'            => 'ជំនួយ និង​ទំព័រ​គម្រោង',
'searchprofile-images'             => 'ពហុ​ព័ត៌មាន',
'searchprofile-everything'         => 'ទាំងអស់',
'searchprofile-advanced'           => 'កម្រិតខ្ពស់',
'searchprofile-articles-tooltip'   => 'ស្វែងរកក្នុង $1',
'searchprofile-project-tooltip'    => 'ស្វែងរកក្នុង $1',
'searchprofile-images-tooltip'     => 'ស្វែងរកឯកសាររូបភាព',
'searchprofile-everything-tooltip' => 'ស្វែងរកក្នុងខ្លឹមសារទាំងអស់(រួមបញ្ចូលទាំងទំព័រពិភាក្សា)',
'searchprofile-advanced-tooltip'   => 'ស្វែងរកក្នុងលំហឈ្មោះកំនត់ដោយអ្នកប្រើប្រាស់',
'search-result-size'               => '$1({{PLURAL:$2|១ពាក្យ|$2ពាក្យ}})',
'search-result-score'              => 'កម្រិតនៃភាពទាក់ទិន៖ $1%',
'search-redirect'                  => '(បញ្ជូនបន្ត $1)',
'search-section'                   => '(ផ្នែក $1)',
'search-suggest'                   => 'ប្រហែលជាអ្នកចង់រក៖ $1',
'search-interwiki-caption'         => 'គម្រោងជាបងប្អូន',
'search-interwiki-default'         => '$1 លទ្ធផល៖',
'search-interwiki-more'            => '(បន្ថែមទៀត)',
'search-mwsuggest-enabled'         => 'មានសំណើ',
'search-mwsuggest-disabled'        => 'គ្មានសំណើ',
'search-relatedarticle'            => 'ទាក់ទិន',
'mwsuggest-disable'                => 'មិនប្រើសំនើAJAX',
'searcheverything-enable'          => 'ស្វែងរកនៅក្នុងលំហឈ្មោះទាំងអស់',
'searchrelated'                    => 'ទាក់ទិន',
'searchall'                        => 'ទាំងអស់',
'showingresults'                   => "ខាងក្រោមកំពុងបង្ហាញរហូតដល់ {{PLURAL:$1|'''១''' លទ្ឋផល|'''$1''' លទ្ឋផល}} ចាប់ផ្ដើមពីលេខ #'''$2'''។",
'showingresultsnum'                => "កំពុងបង្ហាញ {{PLURAL:$3|'''1''' result|'''$3''' លទ្ឋផល}}ខាងក្រោម ចាប់ផ្ដើមដោយ #'''$2''' ។",
'showingresultsheader'             => "{{PLURAL:$5|លទ្ឋផល '''$1''' ក្នុងចំណោមលទ្ឋផលសរុប '''$3'''|លទ្ឋផល '''$1 - $2''' ក្នុងចំណោមលទ្ឋផលសរុប '''$3'''}} សម្រាប់ '''$4'''",
'nonefound'                        => "'''កំណត់​ចំណាំ​''': តាម​លំនាំ​ដើម មាន​តែ​លំហ​ឈ្មោះ​ខ្លះៗ​ប៉ុណ្ណោះ​ដែល​ត្រូវបាន​ស្វែងរក​។​​
សូម​ព្យាយាម​ប្រើ​បុព្វបទ ''all:''ក្នុង​សំណើ​របស់អ្នក ដើម្បី​ស្វែងរក​មាតិការ​ទាំងអស់ (រួមបញ្ចូល​ទាំង​ទំព័រ​ពិភាក្សា គំរូជាដើម) ឬ​ប្រើ​លំហឈ្មោះដែលចង់រក​​ ជាបុព្វបទ​ក៏បាន​។​",
'search-nonefound'                 => 'មិនមានលទ្ធផលណាមួយ​ត្រូវគ្នានឹងសំណើសុំនេះទេ',
'powersearch'                      => 'ស្វែងរកថ្នាក់ខ្ពស់',
'powersearch-legend'               => 'ស្វែងរកថ្នាក់ខ្ពស់',
'powersearch-ns'                   => 'ស្វែងរកក្នុងលំហឈ្មោះ:',
'powersearch-redir'                => 'បញ្ជីការបញ្ជូនបន្ត',
'powersearch-field'                => 'ស្វែងរក',
'powersearch-togglelabel'          => 'គូសធីក៖',
'powersearch-toggleall'            => 'ទាំងអស់',
'powersearch-togglenone'           => 'ទទេ',
'search-external'                  => 'ស្វែងរកនៅខាងក្រៅ',
'searchdisabled'                   => 'ឧបករណ៍​ស្វែងរក​របស់​{{SITENAME}} មិនបានអនុញ្ញាត​។
ក្នុង​ពេល​ឥឡូវ​នេះ​ អ្នកអាច​ស្វែង​រក​តាម​រយៈ​ Google បាន​។
សូមចងចាំ​ថា​ លិបិក្រម​នៃ​មាតិការ​របស់​{{SITENAME}} អាចហួស​សម័យ​។​',

# Quickbar
'qbsettings'               => 'របារទាន់ចិត្ត',
'qbsettings-none'          => 'ទទេ',
'qbsettings-fixedleft'     => 'ចុងខាងឆ្វេង',
'qbsettings-fixedright'    => 'ចុងខាងស្តាំ',
'qbsettings-floatingleft'  => 'អណ្តែតឆ្វេង',
'qbsettings-floatingright' => 'អណ្តែតស្តាំ',

# Preferences page
'preferences'                   => 'ចំណង់ចំណូលចិត្ត',
'mypreferences'                 => 'ចំណង់ចំណូលចិត្ត​',
'prefs-edits'                   => 'ចំនួនកំណែប្រែ៖',
'prefsnologin'                  => 'មិនបានឡុកអ៊ីន',
'prefsnologintext'              => 'អ្នកចាំបាច់ត្រូវតែ<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ឡុកអ៊ីន]</span> ដើម្បីកំណត់ចំណង់ចំណូលចិត្តរបស់អ្នកប្រើប្រាស់។',
'changepassword'                => 'ប្តូរពាក្យសំងាត់',
'prefs-skin'                    => 'សំបក',
'skin-preview'                  => 'មើលជាមុន',
'prefs-math'                    => 'គណិត',
'datedefault'                   => 'គ្មានចំណូលចិត្ត',
'prefs-datetime'                => 'កាលបរិច្ឆេទនិងល្វែងម៉ោង',
'prefs-personal'                => 'ប្រវត្តិរូប',
'prefs-rc'                      => 'បំលាស់ប្ដូរថ្មីៗ',
'prefs-watchlist'               => 'បញ្ជីតាមដាន',
'prefs-watchlist-days'          => 'ចំនួនថ្ងៃត្រូវបង្ហាញក្នុងបញ្ជីតាមដាន៖',
'prefs-watchlist-days-max'      => '(អតិបរមា ៧ថ្ងៃ)',
'prefs-watchlist-edits'         => 'ចំនួនអតិបរមានៃបំលាស់ប្តូរត្រូវបង្ហាញក្នុងបញ្ជីតាមដានដែលបានពង្រីក៖',
'prefs-watchlist-edits-max'     => '(ចំនួនអតិបរមា៖ ១០០០)',
'prefs-misc'                    => 'ផ្សេងៗ',
'prefs-resetpass'               => 'ប្តូរពាក្យសំងាត់',
'prefs-email'                   => '
ជំរើសទាក់ទិននឹងអ៊ីមែល',
'prefs-rendering'               => 'ការរចនា',
'saveprefs'                     => 'រក្សាទុក',
'resetprefs'                    => 'លុបចោលបំលាស់ប្ដូរមិនបានរក្សាទុក',
'restoreprefs'                  => 'ស្ដារ​ការកំណត់​ទាំងអស់​ទៅ​លំនាំដើម',
'prefs-editing'                 => 'កំណែប្រែ',
'prefs-edit-boxsize'            => 'ទំហំរបស់ផ្ទាំងកែប្រែទំព័រ។',
'rows'                          => 'ជួរដេក៖',
'columns'                       => 'ជួរឈរ៖',
'searchresultshead'             => 'ស្វែងរក',
'resultsperpage'                => 'ចំនួនលទ្ធផលក្នុងមួយទំព័រ៖',
'contextlines'                  => 'ចំនួនបន្ទាត់ក្នុងមួយលទ្ធផល៖',
'contextchars'                  => 'ចំនួនអក្សរក្នុងមួយជួរ៖',
'stub-threshold'                => 'កម្រិត ចំពោះ <a href="#" class="stub">តំណភ្ជាប់​ទៅ ពង្រាង </a> (បៃ)៖',
'recentchangesdays'             => 'ចំនួនថ្ងៃបង្ហាញក្នុងទំព័របំលាស់ប្តូរថ្មីៗ៖',
'recentchangesdays-max'         => '(អតិបរមា $1 {{PLURAL:$1|ថ្ងៃ|ថ្ងៃ}})',
'recentchangescount'            => 'ចំនួន​កំណែប្រែ​ដែល​ត្រូវ​បង្ហាញ​តាមលំនាំដើម:',
'prefs-help-recentchangescount' => 'រាប់បញ្ចូលទាំងការកែប្រែនាពេលថ្មី ប្រវត្តិទំព័រនិងកំណត់ហេតុនានា។',
'savedprefs'                    => 'ចំណូលចិត្តនានារបស់អ្នកត្រូវបានរក្សាទុកហើយ។',
'timezonelegend'                => 'ល្វែង​ម៉ោង:',
'localtime'                     => 'ម៉ោងក្នុងស្រុក​៖',
'timezoneuseserverdefault'      => 'តាមការកំនត់របស់ម៉ាស៊ីនបំរើការ',
'timezoneuseoffset'             => 'ផ្សេងទៀត (បញ្ចូលម៉ោងដោយខ្លួនឯង)',
'timezoneoffset'                => 'ទូទាត់¹​៖',
'servertime'                    => 'ម៉ោងម៉ាស៊ីនបម្រើ​៖',
'guesstimezone'                 => 'បំពេញពីកម្មវិធីរាវរក',
'timezoneregion-africa'         => 'អាហ្វ្រិក',
'timezoneregion-america'        => 'អាមេរិក',
'timezoneregion-antarctica'     => 'អង់តាកតិក',
'timezoneregion-arctic'         => 'អាកតិក',
'timezoneregion-asia'           => 'អាស៊ី',
'timezoneregion-atlantic'       => 'មហាសមុទ្រអាត្លង់ទិក',
'timezoneregion-australia'      => 'អូស្ត្រាលី',
'timezoneregion-europe'         => 'អឺរ៉ុប',
'timezoneregion-indian'         => 'មហាសមុទ្រឥណ្ឌា',
'timezoneregion-pacific'        => 'មហាសមុទ្រប៉ាស៊ីហ្វិក',
'allowemail'                    => 'អាចទទួលអ៊ីមែលពីអ្នកប្រើប្រាស់ដទៃទៀត',
'prefs-searchoptions'           => 'ជម្រើសក្នុងការស្វែងរក',
'prefs-namespaces'              => 'លំហឈ្មោះ',
'defaultns'                     => 'ស្វែងរក​ក្នុង​លំហឈ្មោះ​ទាំងនេះ​តាម​បែប​ផ្សេង:',
'default'                       => 'លំនាំដើម',
'prefs-files'                   => 'ឯកសារ',
'prefs-custom-css'              => 'កែតំរូវ CSS',
'prefs-custom-js'               => 'កែតំរូវ JS',
'prefs-reset-intro'             => 'អ្នក​អាច​ប្រើ​ទំព័រ​នេះ​ដើម្បី​កំណត់​ឡើង​វិញ​នូវ​ចំណូល​ចិត្ត​របស់​អ្នក​ដូច​លំនាំ​ដើម​របស់​តំបន់​វិញ​។
សកម្មភាព​នេះ​មិន​អាច​ធ្វើ​ឡើង​វិញ​បាន​ទេ​។',
'prefs-emailconfirm-label'      => 'បញ្ជាក់ទទួលស្គាល់អ៊ីមែល៖',
'prefs-textboxsize'             => 'ទំហំរបស់ផ្ទាំងកែប្រែទំព័រ',
'youremail'                     => 'អ៊ីមែល៖',
'username'                      => 'ឈ្មោះអ្នកប្រើប្រាស់៖',
'uid'                           => 'អត្តសញ្ញាណ៖',
'prefs-memberingroups'          => 'សមាជិកក្នុងក្រុម{{PLURAL:$1|ក្រុម|ក្រុម}}៖',
'prefs-registration'            => 'កាលបរិច្ឆេទចុះឈ្មោះ៖',
'yourrealname'                  => 'ឈ្មោះពិត៖',
'yourlanguage'                  => 'ភាសា៖',
'yourvariant'                   => 'អថេរ​៖',
'yournick'                      => 'ហត្ថលេខាថ្មី៖',
'prefs-help-signature'          => 'រាល់វិចារនៅលើទំព័រពិភាក្សានានា​គួរតែមានចុះហត្ថលេខាដោយប្រើ "<nowiki>~~~~</nowiki>" ដែលនឹងបំលែង​ចេញជាហត្ថលេខា​របស់អ្នក​ជាមួយនឹងកាលបរិច្ឆេទ។',
'badsig'                        => 'ហត្ថលេខាឆៅមិនត្រឹមត្រូវ;ពិនិត្យមើលស្លាក​ HTML ។',
'badsiglength'                  => 'ហត្ថលេខារបស់អ្នកវែងជ្រុល។

វាត្រូវតែតិចជាង $1 {{PLURAL:$1|អក្សរ|អក្សរ}}។',
'yourgender'                    => 'ភេទ៖',
'gender-unknown'                => 'មិនបញ្ជាក់',
'gender-male'                   => 'ប្រុស',
'gender-female'                 => 'ស្រី',
'prefs-help-gender'             => 'ដាក់ក៏បានមិនដាក់ក៏បាន៖ ប្រើសំរាប់អោយសូហ្វវែរហៅតាមភេទអោយបាមត្រឹមត្រូវ។ ព័ត៌មាននេះនឹងត្រូវបង្ហាញជាសាធារណៈ។',
'email'                         => 'អ៊ីមែល',
'prefs-help-realname'           => 'ការផ្ដល់ឈ្មោះពិត​ជាជម្រើសរបស់អ្នក។ បើអ្នកផ្ដល់ឱ្យ វានឹងត្រូវបានប្រើប្រាស់់ដើម្បីបញ្ជាក់ភាពជាម្ចាស់​លើការរួមចំណែក​នានា​របស់អ្នក។',
'prefs-help-email'              => 'ការផ្ដល់អាសយដ្ឋានអ៊ីមែលឬមិនផ្ដល់ជាជម្រើសរបស់អ្នក។ ប៉ុន្ដែវាអាចផ្តល់ឱ្យពាក្យសំងាត់ត្រូវបានផ្ញើប្រសិនបើអ្នកភ្លេច។
អ្នកក៏អាចជ្រើសរើស​ការផ្ដល់លទ្ឋភាព​​ឱ្យអ្នកដទៃទាក់ទងអ្នក​តាមរយៈ​​ទំព័រអ្នកប្រើប្រាស់​​ឬទំព័រពិភាក្សារបស់អ្នក​​ដោយមិនចាំបាច់ឱ្យគេដឹងពីអត្តសញ្ញាណរបស់អ្នក។',
'prefs-help-email-required'     => 'អាសយដ្ឋានអ៊ីមែលត្រូវការជាចាំបាច់។',
'prefs-info'                    => 'ព័ត៌មានផ្ទាល់​ខ្លួន',
'prefs-i18n'                    => 'ភាសា',
'prefs-signature'               => 'ហត្ថលេខា​',
'prefs-dateformat'              => 'ទំរង់កាលបរិច្ឆេទ',
'prefs-timeoffset'              => 'កែប្រែម៉ោង',
'prefs-advancedediting'         => 'ជំរើសថ្នាក់ខ្ពស់',
'prefs-advancedrc'              => 'ជំរើសថ្នាក់ខ្ពស់',
'prefs-advancedrendering'       => 'ជំរើសថ្នាក់ខ្ពស់',
'prefs-advancedsearchoptions'   => 'ជំរើសថ្នាក់ខ្ពស់',
'prefs-advancedwatchlist'       => 'ជំរើសថ្នាក់ខ្ពស់',
'prefs-display'                 => 'ជំរើសការបង្ហាញ',
'prefs-diffs'                   => 'ភាពខុសគ្នា',

# User rights
'userrights'                  => 'ការគ្រប់គ្រងសិទ្ធិអ្នកប្រើប្រាស់',
'userrights-lookup-user'      => 'គ្រប់គ្រងក្រុមអ្នកប្រើប្រាស់',
'userrights-user-editname'    => 'បញ្ចូលឈ្មោះអ្នកប្រើប្រាស់៖',
'editusergroup'               => 'កែប្រែក្រុមអ្នកប្រើប្រាស់',
'editinguser'                 => "ការប្ដូរសិទ្ធិអ្នកប្រើប្រាស់ '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'កែប្រែក្រុមអ្នកប្រើប្រាស់',
'saveusergroups'              => 'រក្សាក្រុមអ្នកប្រើប្រាស់ទុក',
'userrights-groupsmember'     => 'ក្រុមសមាជិកភាព៖',
'userrights-reason'           => 'មូលហេតុនៃការផ្លាស់ប្តូរ៖',
'userrights-no-interwiki'     => 'អ្នកមិនមានការអនុញ្ញាត កែប្រែសិទ្ធិ នៃអ្នកប្រើប្រាស់ លើ វិគី ផ្សេង ទេ។',
'userrights-nodatabase'       => 'មូលដ្ឋានទិន្នន័យ $1 មិនមាន ឬ ស្ថិតនៅខាងក្រៅ។',
'userrights-nologin'          => 'អ្នកត្រូវតែ [[Special:UserLogin|ឡុកអ៊ីន]] ជាគណនីអ្នកអភិបាលដើម្បីផ្ដល់សិទ្ធិឱ្យអ្នកប្រើប្រាស់​។',
'userrights-notallowed'       => 'គណនីរបស់អ្នកមិនមានការអនុញ្ញាតដើម្បីកំណត់សិទ្ធិរបស់អ្នកប្រើប្រាស់ដទៃ។',
'userrights-changeable-col'   => 'ក្រុមនានាដែលអ្នកអាចផ្លាស់ប្ដូរបាន',
'userrights-unchangeable-col' => 'ក្រុមនានាដែលអ្នកមិនអាចផ្លាស់ប្ដូរបាន',

# Groups
'group'               => 'ក្រុម៖',
'group-user'          => 'អ្នកប្រើប្រាស់',
'group-autoconfirmed' => 'អ្នកប្រើប្រាស់ទទួលស្គាល់ដោយស្វ័យប្រវត្តិ',
'group-bot'           => 'រូបយន្ត',
'group-sysop'         => 'អ្នកថែទាំប្រព័ន្ធ',
'group-bureaucrat'    => 'អ្នកការិយាល័យ',
'group-suppress'      => 'អធិការ',
'group-all'           => '(ទាំងអស់)',

'group-user-member'          => 'អ្នកប្រើប្រាស់',
'group-autoconfirmed-member' => 'អ្នកប្រើប្រាស់ទទួលស្គាល់ដោយស្វ័យប្រវត្តិ',
'group-bot-member'           => 'រូបយន្ត',
'group-sysop-member'         => 'អ្នកថែទាំប្រព័ន្ធ',
'group-bureaucrat-member'    => 'អ្នកការិយាល័យ',
'group-suppress-member'      => 'អធិការ',

'grouppage-user'          => '{{ns:project}}:អ្នកប្រើប្រាស់',
'grouppage-autoconfirmed' => '{{ns:project}}:អ្នកប្រើប្រាស់ទទួលស្គាល់ដោយស្វ័យប្រវត្តិ',
'grouppage-bot'           => '{{ns:project}}:រូបយន្ត',
'grouppage-sysop'         => '{{ns:project}}:អភិបាល',
'grouppage-bureaucrat'    => '{{ns:project}}:អ្នកការិយាល័យ',
'grouppage-suppress'      => '{{ns:project}}:អធិការ',

# Rights
'right-read'                 => 'អានអត្ថបទ',
'right-edit'                 => 'កែប្រែអត្ថបទ',
'right-createpage'           => 'បង្កើតទំព័រអត្ថបទ (ដែលមិនមែនជាទំព័រពិភាក្សា)',
'right-createtalk'           => 'បង្កើតទំព័រពិភាក្សា',
'right-createaccount'        => 'បង្កើតគណនីអ្នកប្រើប្រាស់ថ្មី',
'right-minoredit'            => 'កំណត់ចំណាំកំណែប្រែថាជាកំណែប្រែតិចតួច',
'right-move'                 => 'ប្ដូរទីតាំងទំព័រ',
'right-move-subpages'        => 'ប្ដូរទីតាំងទំព័ររួមជាមួយទំព័ររងរបស់វា',
'right-move-rootuserpages'   => 'ប្ដូរទីតាំងឫសទំព័រអ្នកប្រើប្រាស់',
'right-movefile'             => 'ប្ដូរទីតាំងឯកសារ',
'right-upload'               => 'ផ្ទុកឡើងឯកសារ',
'right-reupload'             => 'សរសេរលុបពីលើឯកសារមួយច្បាប់ដែលមានស្រាប់',
'right-upload_by_url'        => 'ផ្ទុកឡើងឯកសារមួយពីអាសយដ្ឋាន URL មួយ',
'right-autoconfirmed'        => 'កែប្រែទំព័រពាក់កណ្ដាលការពារនានា',
'right-bot'                  => 'ទុកដូចជាដំណើរការស្វ័យប្រវត្តិមួយ',
'right-writeapi'             => 'ការប្រើប្រាស់ API សម្រាប់​សរសេរ​ (the write API)',
'right-delete'               => 'លុបទំព័រចោល',
'right-bigdelete'            => 'លប់ទំព័រទាំងឡាយដែលមានប្រវត្តិវែង',
'right-deleterevision'       => 'លុប​និង​ឈប់​លុប​កំណែ​ប្រែ​ច្បាស់លាស់​នៃ​ទំព័រ​',
'right-browsearchive'        => 'ស្វែងរកទំព័រដែលត្រូវបានលុប',
'right-undelete'             => 'ឈប់លុបទំព័រមួយ',
'right-suppressionlog'       => 'មើលកំណត់ហេតុឯកជន',
'right-block'                => 'ហាមមិនឱ្យអ្នកប្រើប្រាស់ដទៃទៀតធ្វើការកែប្រែ',
'right-blockemail'           => 'រាំងខ្ទប់អ្នកប្រើប្រាស់ម្នាក់មិនអោយផ្ញើអ៊ីមែល',
'right-hideuser'             => 'រាំងខ្ទប់អ្នកប្រើប្រាស់ម្នាក់ រួចលាក់មិនបង្ហាញជាសាធារណៈ',
'right-protect'              => 'ប្ដូរកម្រិតការពាររួចកែប្រែទំព័រដែលបានការពារ',
'right-editprotected'        => 'កែប្រែទំព័រដែលបានការពារ (ដោយមិនរំលាយការការពារ)',
'right-editinterface'        => 'កែប្រែអន្តរមុខអ្នកប្រើប្រាស់',
'right-editusercssjs'        => 'កែប្រែឯកសារ CSS និង JS របស់អ្នកប្រើប្រាស់ផ្សេងទៀត',
'right-editusercss'          => 'កែប្រែឯកសារ CSS របស់អ្នកប្រើប្រាស់ផ្សេងទៀត',
'right-edituserjs'           => 'កែប្រែឯកសារ JS របស់អ្នកប្រើប្រាស់ផ្សេងទៀត',
'right-rollback'             => 'ត្រឡប់យ៉ាងរហ័សនូវកំណែប្រែទំព័រវិសេសណាមួយ​ដែលធ្វើឡើងដោយ​អ្នកប្រើប្រាស់ចុងក្រោយគេ។',
'right-import'               => 'នាំចូលទំព័រនានាពីវិគីផ្សេងៗទៀត',
'right-importupload'         => 'នាំចូលទំព័រនានាពីឯកសារដែលបានផ្ទុកឡើង',
'right-patrol'               => 'កត់សម្គាល់កំណែប្រែដ៏ទៃទៀតថាល្បាត',
'right-patrolmarks'          => 'មើក​កំណត់​សម្គាល់​ល្បាត​ដែល​ផ្លាស់​ប្តូរ​ថ្មី​ៗ​',
'right-unwatchedpages'       => 'បង្ហាញបញ្ជីទំព័រនានាដែលមិនត្រូវបានមើល',
'right-trackback'            => 'ដាក់ស្នើការ​តាម​ដាន​ត្រឡប់​វិញ​​(trackback)',
'right-mergehistory'         => 'រំលាយបញ្ចូលប្រវត្តិរបស់ទំព័រនានា',
'right-userrights'           => 'កែប្រែរាល់សិទ្ធិនៃអ្នកប្រើប្រាស់',
'right-userrights-interwiki' => 'កែប្រែសិទ្ធិអ្នកប្រើប្រាស់នៅលើវិគីផ្សេងៗទៀត',
'right-siteadmin'            => 'ចាក់សោនិងបើកសោមូលដ្ឋានទិន្នន័យ',
'right-reset-passwords'      => 'កំណត់​ឡើង​វិញ​នូវ​លេខ​សម្ងាត់​អ្នក​ប្រើប្រាស់​ដទៃ​ទៀត​',

# User rights log
'rightslog'      => 'កំណត់ហេតុនៃការប្តូរសិទ្ធិអ្នកប្រើប្រាស់',
'rightslogtext'  => 'នេះ​ជា​កំណត់ហេតុនៃបំលាស់ប្ដូរចំពោះកាប្ដូរក្រុមសមាជិកភាព​របស់​អ្នកប្រើប្រាស់។',
'rightslogentry' => 'បានប្ដូរក្រុមសមាជិកភាពសម្រាប់ $1 ពី $2 ទៅ $3',
'rightsnone'     => '(ទទេ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'អានទំព័រនេះ',
'action-edit'                 => 'កែប្រែទំព័រនេះ',
'action-createpage'           => 'បង្កើតទំព័រនានា',
'action-createtalk'           => 'បង្កើតទំព័រពិភាក្សានានា',
'action-createaccount'        => 'បង្កើតគណនីអ្នកប្រើប្រាស់នេះ',
'action-minoredit'            => 'ចំណាំកំណែប្រែនេះថាជាកំណែប្រែតិចតួច',
'action-move'                 => 'ប្ដូរទីតាំងទំព័រនេះ',
'action-move-subpages'        => 'ប្ដូរទីតាំងទំព័រនេះព្រមទាំងអនុទំព័ររបស់វា',
'action-move-rootuserpages'   => 'ប្ដូរទីតាំងឫសទំព័រអ្នកប្រើប្រាស់',
'action-movefile'             => 'ប្ដូរទីតាំងឯកសារនេះ',
'action-upload'               => 'ផ្ទុកឡើងឯកសារនេះ',
'action-reupload'             => 'ផ្ទុកជាន់ពីលើឯកសារដែលមានស្រាប់ហើយនេះ',
'action-upload_by_url'        => 'ផ្ទុក​ឯកសារ​នេះ​ឡើង​ពី​អាសយដ្ឋាន URL',
'action-writeapi'             => 'ប្រើប្រាស់​ write API',
'action-delete'               => 'លុបទំព័រនេះ',
'action-deleterevision'       => 'លុបកំណែប្រែនេះ',
'action-deletedhistory'       => 'មើលប្រវត្តិលុបរបស់ទំព័រនេះ',
'action-browsearchive'        => 'ស្វែងរកទំព័រដែលត្រូវបានលុបចោល',
'action-undelete'             => 'ឈប់លុបទំព័រនេះ',
'action-suppressrevision'     => 'ត្រួតពិនិត្យ​និង​ស្ដារ​​កំណែ​ប្រែដែល​លាក់​បាំង',
'action-suppressionlog'       => 'មើលកំណត់ហេតុឯកជននេះ',
'action-block'                => 'រាំងខ្ទប់អ្នកប្រើប្រាស់នេះមិនឱ្យធ្វើការកែប្រែ',
'action-protect'              => 'ប្ដូរកម្រិតការពារសម្រាប់ទំព័រនេះ',
'action-import'               => 'នាំចូលទំព័រនេះពីវិគីផ្សេងមួយទៀត',
'action-importupload'         => 'នាំចូលទំព័រនេះពីឯកសារដែលបានផ្ទុកឡើង',
'action-patrol'               => 'ចំណាំកំណែប្រែរបស់អ្នកដទៃថាបានល្បាត',
'action-autopatrol'           => 'ផ្ដល់សិទ្ធិឱ្យគេចំណាំកំណែរបស់អ្នកថាបានល្បាត',
'action-unwatchedpages'       => 'មើលបញ្ជីនៃទំព័រមិនតាមដាន',
'action-trackback'            => 'ដាក់​ស្មើ​ការ​ត្រឡប់​ដើម​វិញ​',
'action-mergehistory'         => 'ច្របាច់បញ្ចូលប្រវត្តិរបស់ទំព័រនេះ',
'action-userrights'           => 'កែប្រែសិទ្ធិរបស់អ្នកប្រើប្រាស់ទាំងអស់',
'action-userrights-interwiki' => 'កែប្រែសិទ្ធិនានារបស់អ្នកប្រើប្រាស់នៅលើវិគីដទៃ',
'action-siteadmin'            => 'ចាក់សោឬដោះសោមូលដ្ឋានទិន្នន័យ',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|បំលាស់ប្ដូរ|បំលាស់ប្ដូរ}}',
'recentchanges'                     => 'បំលាស់ប្ដូរ​ថ្មីៗ',
'recentchanges-legend'              => 'ជម្រើសនានា​សម្រាប់ការបង្ហាញបំលាស់ប្ដូរថ្មីៗ',
'recentchangestext'                 => 'តាមដានរាល់បំលាស់ប្ដូរថ្មីៗបំផុតចំពោះវិគីនៅលើទំព័រនេះ។',
'recentchanges-feed-description'    => 'តាមដាន​បំលាស់ប្ដូរថ្មីៗ​បំផុត​នៃ​វិគី​នេះក្នុង​មតិព័ត៌មាន​នេះ​។',
'recentchanges-label-legend'        => 'កំនត់សំគាល់៖ $1 ។',
'recentchanges-legend-newpage'      => '$1 - ទំព័រថ្មី',
'recentchanges-label-newpage'       => 'ការកែប្រែនេះបានបង្កើតទំព័រថ្មីមួយ',
'recentchanges-legend-minor'        => '$1 - ការកែប្រែតិចតួច',
'recentchanges-label-minor'         => 'នេះជាការកែប្រែតិចតួចមួយប៉ុណ្ណោះ',
'recentchanges-legend-bot'          => '$1 - ការកែប្រែដោយរូបយន្ត',
'recentchanges-label-bot'           => 'ការកែប្រែនេះត្រូវបានធ្វើឡើងដោយរូបយន្ត',
'recentchanges-legend-unpatrolled'  => '$1 - ការកែប្រែដែលបានល្បាត',
'recentchanges-label-unpatrolled'   => 'ការកែប្រែនេះមិនទាន់ត្រូវបានល្បាតទេ',
'rcnote'                            => "ខាងក្រោម​នេះ​ជា​{{PLURAL:$1|១បំលាស់ប្ដូរ|'''$1'''បំលាស់ប្ដូរ}}​ចុងក្រោយក្នុងរយៈពេល​{{PLURAL:$2|ថ្ងៃ|'''$2'''ថ្ងៃ}}​ចុងក្រោយគិតត្រឹម$5 $4 ។",
'rcnotefrom'                        => "ខាងក្រោមនេះជាបំលាស់ប្ដូរនានាគិតចាប់តាំងពី '''$2''' (បង្ហាញអតិបរមា '''$1''' បំលាស់ប្ដូរ)។",
'rclistfrom'                        => 'បង្ហាញបំលាស់ប្ដូរថ្មីៗដែលចាប់ផ្តើមពី $1',
'rcshowhideminor'                   => '$1កំណែប្រែ​តិចតួច',
'rcshowhidebots'                    => '$1រូបយន្ត',
'rcshowhideliu'                     => '$1អ្នកប្រើប្រាស់ដែលបានឡុកអ៊ីន',
'rcshowhideanons'                   => '$1អ្នកប្រើប្រាស់អនាមិក',
'rcshowhidepatr'                    => '$1កំណែប្រែដែលបានល្បាត',
'rcshowhidemine'                    => '$1កំណែប្រែរបស់ខ្ញុំ',
'rclinks'                           => 'បង្ហាញ$1ចុងក្រោយក្នុងរយៈពេល$2ថ្ងៃចុងក្រោយ<br />$3',
'diff'                              => 'ភាពខុសគ្នា',
'hist'                              => 'ប្រវត្តិ',
'hide'                              => 'លាក់',
'show'                              => 'បង្ហាញ',
'minoreditletter'                   => 'តិច',
'newpageletter'                     => 'ថ្មី',
'boteditletter'                     => 'រូបយន្ត',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[មាន{{PLURAL:$1|អ្នកប្រើប្រាស់|អ្នកប្រើប្រាស់}}$1នាក់កំពុងមើល]',
'rc_categories'                     => 'កម្រិតទីតាំងចំណាត់ថ្នាក់ក្រុម(ខណ្ឌដោយសញ្ញា "|")',
'rc_categories_any'                 => 'មួយណាក៏បាន',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ ផ្នែកថ្មី',
'rc-enhanced-expand'                => 'បង្ហាញភាពលំអិត (ត្រូវការ JavaScript)',
'rc-enhanced-hide'                  => 'លាក់ភាពលំអិត',

# Recent changes linked
'recentchangeslinked'          => 'បំលាស់ប្ដូរពាក់ព័ន្ធ',
'recentchangeslinked-feed'     => 'បំលាស់ប្ដូរពាក់ព័ន្ធ',
'recentchangeslinked-toolbox'  => 'បំលាស់ប្ដូរពាក់ព័ន្ធ',
'recentchangeslinked-title'    => 'បំលាស់ប្ដូរ​ទាក់ទិននឹង "$1"',
'recentchangeslinked-noresult' => 'គ្មានបំលាស់ប្ដូរ​លើទំព័រ​ដែលត្រូវបានតភ្ជាប់ ក្នុងថេរវេលា​ដែលត្រូវបានផ្តល់ឱ្យ ។',
'recentchangeslinked-summary'  => "នេះជាបញ្ជីបំលាស់ប្ដូរនានាដែលត្រូវបានធ្វើឡើងនាពេលថ្មីៗនេះ ទៅលើទំព័រនានាដែលមានតំណភ្ជាប់ពីទំព័រកំណត់មួយ (ឬមានតំណភ្ជាប់ទៅទំព័រក្នុងចំណាត់ថ្នាក់ក្រុមកំណត់មួយ)។ ទំព័រ​នានាលើ[[Special:Watchlist|បញ្ជីតាមដាន​របស់អ្នក]] ត្រូវបានសរសេរជា '''អក្សរដិត''' ។",
'recentchangeslinked-page'     => 'ឈ្មោះទំព័រ៖',
'recentchangeslinked-to'       => 'បង្ហាញ​បំលាស់ប្ដូរ​ទំព័រ ដែល​បាន​តភ្ជាប់​ទៅកាន់​ទំព័រ​ដែល​បាន​ផ្ដល់​ឱ្យ​ជំនួស​វិញ',

# Upload
'upload'                      => 'ផ្ទុកឯកសារឡើង',
'uploadbtn'                   => 'ផ្ទុកឯកសារឡើង',
'reupload'                    => 'ផ្ទុកឡើងម្តងទៀត',
'reuploaddesc'                => 'ឈប់ផ្ទុកឡើងរួចត្រឡប់ទៅបែបបទផ្ទុកឡើងវិញ។',
'uploadnologin'               => 'មិនបានឡុកអ៊ីនទេ',
'uploadnologintext'           => 'អ្នកត្រូវតែ [[Special:UserLogin|ឡុកអ៊ីន]] ដើម្បីផ្ទុកឡើងឯកសារទាំងឡាយ។',
'uploaderror'                 => 'កំហុសផ្ទុកឡើង',
'uploadtext'                  => "សូមប្រើប្រាស់បែបបទខាងក្រោមដើម្បីផ្ទុកឯកសារ​ឡើង។

ដើម្បីមើល ឬស្វែងរកឯកសារដែលបានផ្ទុកឡើងពីពេលមុន សូមចូលទៅ[[Special:FileList|បញ្ជីឯកសារដែលបានផ្ទុកឡើង]]។ ការផ្ទុកឡើងវិញ​នូវឯកសារបង្ហាញនៅក្នុង[[Special:Log/upload|កំណត់ហេតុនៃការផ្ទុកឯកសារឡើង]] និងការលុបចេញមានបង្ហាញនៅក្នុង[[Special:Log/delete|កំណត់ហេតុនៃការលុប]]។


ដើម្បីដាក់រូបភាពទៅក្នុងទំព័រ សូមប្រើប្រាស់តំណភ្ជាប់ក្នុងទម្រង់ដូចខាងក្រោម៖
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:ឈ្មោះឯកសារ.jpg]]</nowiki></tt>'''ដើម្បីប្រើប្រាស់ទម្រង់ពេញលេញនៃឯកសារ
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:ឈ្មោះឯកសារ.png|200px|thumb|left|ឃ្លាពិពណ៌នា]]</nowiki></tt>''' ដោយប្រើប្រាស់ទំហំ​២០០ភីកសែលក្នុងប្រអប់នៅ​គេមខាងធ្វេង​ជាមួយនឹង​ឃ្លារៀបរាប់អំពីឯកសារនេះ។
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:ឈ្មោះឯកសារ.ogg]]</nowiki></tt>''' ដើម្បីតភ្ជាប់​ដោយផ្ទាល់ទៅឯកសារនេះ​ដោយមិនបង្ហាញឯកសារ។",
'upload-permitted'            => 'ប្រភេទឯកសារដែលត្រូវបានអនុញ្ញាត៖ $1 ។',
'upload-preferred'            => 'ប្រភេទឯកសារដែលគួរប្រើប្រាស់៖ $1 ។',
'upload-prohibited'           => 'ប្រភេទឯកសារដែលត្រូវបានហាម៖ $1 ។',
'uploadlog'                   => 'កំណត់ហេតុនៃការផ្ទុកឡើង',
'uploadlogpage'               => 'កំណត់ហេតុនៃការផ្ទុកឡើង',
'uploadlogpagetext'           => 'ខាងក្រោមនេះ​ជាបញ្ជីនៃការផ្ទុកឡើង​ថ្មីបំផុត។

សូមមើល [[Special:NewFiles|វិចិត្រសាលរូបភាពថ្មីៗ]] ដើម្បីមើលដោយផ្ទាល់ភ្នែក។',
'filename'                    => 'ឈ្មោះឯកសារ',
'filedesc'                    => 'សេចក្តីសង្ខេប',
'fileuploadsummary'           => 'សេចក្តីសង្ខេប៖',
'filereuploadsummary'         => 'បំលាស់ប្ដូរ​ឯកសារ​៖',
'filestatus'                  => 'ស្ថានភាពរក្សាសិទ្ធិ៖',
'filesource'                  => 'ប្រភព',
'uploadedfiles'               => 'ឯកសារដែលត្រូវបានផ្ទុកឡើង',
'ignorewarning'               => 'មិនខ្វល់​ការព្រមាន ហើយរក្សាទុក​ឯកសារ​តែម្តង។',
'ignorewarnings'              => 'មិនខ្វល់​ការព្រមាន​ណាមួយ',
'minlength1'                  => 'ឈ្មោះឯកសារ​ត្រូវមាន​យ៉ាងតិច​១​អក្សរ។',
'illegalfilename'             => 'ឈ្មោះឯកសារ "$1" មាន​អក្សរ​ហាមឃាត់​​ក្នុងចំណងជើងទំព័រ។ សូម​ប្តូរឈ្មោះ​ឯកសារ ហើយ​ព្យាយាមផ្ទុកវា​ឡើង​ម្តងទៀត។',
'badfilename'                 => 'ឈ្មោះឯកសារ បានត្រូវប្តូរ ជា "$1" ។',
'filetype-badmime'            => 'ឯកសារ​ប្រភេទ MIME "$1" មិនត្រូវបាន​អនុញ្ញាត​ឱ្យផ្ទុកឡើង។',
'filetype-unwanted-type'      => '\'".$1"\'\'\' ជាប្រភេទឯកសារមិនចង់បាន។ 

{{PLURAL:$3|ប្រភេទឯកសារ|ប្រភេទឯកសារ}}ដែលគេចង់បានគឺ $2។',
'filetype-banned-type'        => "'''\".\$1\"''' គឺជា​ប្រភេទ​ឯកសារ​ដែល​មិន​ត្រូវ​បាន​គេ​អនុញ្ញាត​ទេ​។ {{PLURAL:\$3|file type is|ប្រភេទឯកសារ}}​ដែល​ត្រូវ​បាន​គេ​អនុញ្ញាត​គឺ \$2 ។",
'filetype-missing'            => 'ឯកសារ មិនមានកន្ទុយ (ដូចជា ".jpg")។',
'large-file'                  => 'ឯកសារ​គួរតែ​មាន​​ទំហំ​មិនលើសពី $1។ ឯកសារ​នេះមាន​ទំហំ $2។',
'largefileserver'             => 'ឯកសារនេះមានទំហំធំជាងទំហំដែលម៉ាស៊ីនបម្រើការ(server)អនុញ្ញាត។',
'emptyfile'                   => 'ឯកសារដែលអ្នកបានដាក់បញ្ចេញ ហាក់បីដូចជាទទេ។​ នេះប្រហែលជាមកពីកំហុសនៃការសរសេរឈ្មោះឯកសារ។ ចូរពិនិត្យ ថាតើអ្នកពិតជាចង់ដាក់បញ្ចេញឯកសារនេះឬក៏អត់។',
'fileexists'                  => "ឯកសារដែលមានឈ្មោះនេះមានរួចហើយ​ ចូរពិនិត្យ '''<tt>$1</tt>''' ប្រសិនបើអ្នកមិនច្បាស់ថាតើអ្នកចង់ប្តូរវាឬក៏អត់។",
'fileexists-extension'        => "មាន​ឯកសារ​មួយ​ដែល​មាន​ឈ្មោះស្រដៀង​៖<br />
ឈ្មោះ​ឯកសារដែលបាន​ផ្ទុកឡើង​ ៖ '''<tt>$1</tt>'''<br />
ឈ្មោះ​ឯកសារ​ដែល​មានស្រាប់​៖ '''<tt>$2</tt>'''<br />
សូម​ជ្រើសរើសឈ្មោះ​ផ្សេងទៀត។",
'fileexists-thumb'            => "<center>'''រូបភាពមានស្រេច'''</center>",
'fileexists-thumbnail-yes'    => "ឯកសារនេះទំនងជារូបភាពដែលបានបង្រួមទំហំ ''(កូនរូបភាព thumbnail)''.
សូមពិនិត្យមើលឯកសារ '''<tt>$1</tt>'''។<br />
បើសិនជាឯកសារដែលអ្នកបានពិនិត្យខាងលើគឺជារូបភាពតែមួយដែលមានទំហំដើម នោះអ្នកមិនចាំបាច់ផ្ទុកឡើងនូវកូនរូបភាព (thumbnail) បន្ថែមទេ។",
'fileexists-forbidden'        => 'មាន​ឯកសារ​ដែល​មាន​ឈ្មោះ​នេះ​រួចហើយ និង​មិន​អាច​សរសេរ​ជាន់​ពីលើ​បាន​ទេ​។ ប្រសិនបើ អ្នក​នៅតែ​ចង់​ផ្ទុក​ឯកសារ​របស់​អ្នក​ឡើង សូម​ត្រឡប់​ក្រោយ ហើយ​ប្រើ​ឈ្មោះ​ថ្មី​មួយ​ផ្សេង​វិញ​។​[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'ឯកសារដែលមានឈ្មោះនេះ មានរួចហើយនៅក្នុងកន្លែងដាក់ឯកសាររួម។

ចូរត្រឡប់ក្រោយវិញ​ហើយដាក់បញ្ចេញឯកសារនេះឡើងវិញ​ជាមួយ​នឹងឈ្មោះថ្មី។ [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'ឯកសារនេះជាច្បាប់ចម្លងរបស់ {{PLURAL:$1|ឯកសារ|ឯកសារ}}ដូចតទៅនេះ៖',
'successfulupload'            => 'ផ្ទុកឯកសារឡើងដោយជោគជ័យ',
'uploadwarning'               => 'សូមប្រុងប្រយ័ត្ន!',
'savefile'                    => 'រក្សាឯកសារទុក',
'uploadedimage'               => 'បានផ្ទុកឡើង "[[$1]]"',
'overwroteimage'              => 'ដាក់បញ្ចេញនូវកំណែប្រែថ្មីរបស់"[[$1]]"',
'uploaddisabled'              => 'ការផ្ទុកឡើង ឯកសារនានា ត្រូវអសកម្ម',
'uploaddisabledtext'          => 'ការផ្ទុកឯកសារ​ឡើង​គឺ​មិន​ត្រូវបាន​អនុញ្ញាត​ទេ​។',
'uploadcorrupt'               => 'ឯកសារ​នេះ​ខូច​ឬ​មានកន្ទុយដែលមិនត្រឹមត្រូវ។ សូម​ពិនិត្យ​មើល​វាឡើងវិញ​ ហើយ​ផ្ទុកឡើង​ម្តងទៀត។',
'uploadvirus'                 => 'ឯកសារមានមេរោគ! សេចក្តីលំអិត៖ $1',
'sourcefilename'              => 'ឈ្មោះឯកសារប្រភព៖',
'destfilename'                => 'ឈ្មោះឯកសារគោលដៅ៖',
'upload-maxfilesize'          => 'ទំហំអតិបរមារបស់ឯកសា​៖ $1',
'watchthisupload'             => 'តាមដាន​ឯកសារ​នេះ',
'filewasdeleted'              => 'ឯកសារដែលមានឈ្មោះនេះត្រូវបានដាក់បញ្ចេញមុននេះ ហើយក៏ត្រូវបានគេលុបចេញទៅវិញផងដែរ។​​​​ អ្នកគួរតែពិនិត្យ$1​មុននឹង​បន្តបញ្ចេញ​វាម្តង​ទៀត​។​',
'upload-wasdeleted'           => "'''ប្រយ័ត្ន៖ អ្នក​កំពុង​ផ្ទុក​ឡើង​នូវ​ឯកសារ​មួយ​ដែល​ត្រូវ​បានលុបចោល​មុននេះ។'''

អ្នកគួរ​ពិចារណាថាតើការផ្ទុក​ឯកសារ​នេះ​ឡើងសមរម្យឬទេ​។
ប្រវត្តិ​នៃការលុបឯកសារ​នេះ​​ត្រូវបានដាក់​នៅទីនេះ​តាមការគួរ៖",
'filename-prefix-blacklist'   => '  #<!-- leave this line exactly as it is --> <pre>
# Syntax is as follows:
#  * Everything from a "#" character to the end of the line is a comment
#  * Every non-blank line is a prefix for typical file names assigned automatically by digital cameras
CIMG # ម៉ាក Casio
DSC_ # ម៉ាក Nikon
DSCF # ម៉ាក Fuji
DSCN # ម៉ាក Nikon
DUW # ទូរស័ព្ទ​ចល័ត​នានា​
IMG # ទូទៅ​
JD # Jenoptik
MGP # ម៉ាក Pentax
PICT # ផ្សេង​ៗ​
  #</pre> <!-- leave this line exactly as it is -->',

'upload-proto-error'        => 'ពិធីការដែលមិនត្រឹមត្រូវ',
'upload-proto-error-text'   => 'ការផ្ទុកឡើងពីចម្ងាយត្រូវការ URL ដែលចាប់ផ្ដើម <code>http://</code> ឬ <code>ftp://</code>។',
'upload-file-error'         => 'កំហុសផ្នែកខាងក្នុង',
'upload-file-error-text'    => 'កំហុសផ្នែកខាងក្នុងបានកើតឡើង​ នៅពេលព្យាយាមបង្កើតឯកសារបណ្ដោះអាសន្នមួយ​នៅក្នុងម៉ាស៊ីនបម្រើការ។

សូមទំនាក់ទំនង[[Special:ListUsers/sysop|អ្នកអភិបាល]]។',
'upload-misc-error'         => 'កំហុសចម្លែក​ពេលផ្ទុកឡើង',
'upload-misc-error-text'    => 'កំហុសដែលមិនស្គាល់មួយបានកើតឡើងនៅក្នុងកំឡុងពេលផ្ទុកឡើង។ 

ចូរផ្ទៀងផ្ទាត់ថា URL គឺមានសុពលភាពហើយអាចដំណើរការ រួចហើយ​ព្យាយាមម្តងទៀត។ 

ប្រសិនបើបញ្ហានៅតែកើតឡើង សូមទំនាក់ទំនង[[Special:ListUsers/sysop|អ្នកអភិបាល]]។',
'upload-too-many-redirects' => 'URLនេះមានតំនភ្ជាប់បញ្ជូនបន្តច្រើនពេកហើយ',
'upload-unknown-size'       => 'មិនដឹងទំហំ',
'upload-http-error'         => 'មានកំហុសHTTPមួយបានកើតឡើង៖ $1',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'      => 'មិនអាច ចូលទៅដល់ URL',
'upload-curl-error6-text' => 'មិនអាចទៅកាន់ URL ដែលអ្នកបានផ្ដល់ឱ្យទេ។

សូមពិនិត្យឡើងវិញថា URL នោះពិតជាត្រឹមត្រូវឬអត់។',
'upload-curl-error28'     => 'ផ្ទុកឡើង បានផុតកំណត់អនុញ្ញាត',

'license'            => 'អាជ្ញាប័ណ្ណ',
'license-header'     => 'ដាក់​ជា​អាជ្ញាប័ណ្ណ',
'nolicense'          => 'មិនបានជ្រើសរើសយកទេ',
'license-nopreview'  => '(មិនទាន់មានការបង្ហាញការមើលជាមុនទេ)',
'upload_source_url'  => ' (URL ត្រឹមត្រូវនិងបើកចំហជាសាធារណៈ)',
'upload_source_file' => ' (ឯកសារក្នុងកុំព្យូទ័ររបស់អ្នក)',

# Special:ListFiles
'listfiles-summary'     => 'ទំព័រពិសេស​នេះ​បង្ហាញ​គ្រប់​ឯកសារ​ដែល​បានផ្ទុកឡើង។ តាម​លំនាំដើម​ឯកសារ​ដែល​បានផ្ទុកឡើង​ចុងក្រោយ​ត្រូវបាន​បង្ហាញ​នៅ​លើគេ​នៃបញ្ជីនេះ។ ការចុច​លើ​ក្បាល​ជួរ​ឈរ​ក៏​អាច​ផ្លាស់ប្តូរ​តម្រៀប​នៃ​ការ​បង្ហាញ​បាន​ដែរ។',
'listfiles_search_for'  => 'ស្វែងរកឈ្មោះមេឌា៖',
'imgfile'               => 'ឯកសារ',
'listfiles'             => 'បញ្ជីរូបភាព',
'listfiles_date'        => 'កាលបរិច្ឆេទ',
'listfiles_name'        => 'ឈ្មោះ',
'listfiles_user'        => 'អ្នកប្រើប្រាស់',
'listfiles_size'        => 'ទំហំ',
'listfiles_description' => 'ការពិពណ៌នា',
'listfiles_count'       => 'កំណែ',

# File description page
'file-anchor-link'          => 'ឯកសារ',
'filehist'                  => 'ប្រវត្តិ​ឯកសារ',
'filehist-help'             => "ចុចលើ'''ម៉ោងនិងកាលបរិច្ឆេទ'''ដើម្បីមើលឯកសារដែលបានផ្ទុកនៅពេលនោះ។",
'filehist-deleteall'        => 'លុបទាំងអស់',
'filehist-deleteone'        => 'លុបចេញ',
'filehist-revert'           => 'ត្រឡប់',
'filehist-current'          => 'បច្ចុប្បន្ន',
'filehist-datetime'         => 'ម៉ោងនិងកាលបរិច្ឆេទ',
'filehist-thumb'            => 'កូនរូបភាព',
'filehist-thumbtext'        => 'កូន​រូប​ភាព​​សម្រាប់​កំណែ​ (version) កាល​ពី​​ $1',
'filehist-nothumb'          => 'គ្មានកូនរូបភាព',
'filehist-user'             => 'អ្នកប្រើប្រាស់',
'filehist-dimensions'       => 'វិមាត្រ',
'filehist-filesize'         => 'ទំហំឯកសារ',
'filehist-comment'          => 'យោបល់',
'filehist-missing'          => 'ឯកសារបាត់បង់',
'imagelinks'                => 'តំណភ្ជាប់​​ឯកសារ',
'linkstoimage'              => '{{PLURAL:$1|ទំព័រ​|$1 ទំព័រ}} ខាងក្រោម​មានតំណភ្ជាប់មក​ឯកសារនេះ ៖',
'linkstoimage-more'         => 'មាន​​{{PLURAL:$1|តំណ​ភ្ជាប់ទំព័រ​}}ច្រើន​ជាង​ $1 មក​កាន់​ឯកសារ​នេះ​។
បញ្ជី​នេះ​បង្ហាញ​អំពី​{{PLURAL:$1|តំណ​ភ្ជាប់ទំព័រដំបូង​​|តំណ​ភ្ជាប់ទំព័រ $1ដំបូង​}}មក​កាន់​ឯកសារ​នេះប៉ុណ្ណោះ​​។
ក៏​មាន​[[Special:WhatLinksHere/$2|បញ្ជី​ពេញ​លេញ​]]ផង​ដែរ​។',
'nolinkstoimage'            => 'គ្មានទំព័រណាមួយដែលតភ្ជាប់មកឯកសារនេះទេ។',
'morelinkstoimage'          => 'មើល [[Special:WhatLinksHere/$1|តំណភ្ជាប់បន្ថែមទៀត]] ដែលតភ្ជាប់មកកាន់ឯកសារនេះ។',
'redirectstofile'           => '{{PLURAL:$1|file redirects|$1 ឯកសារ​បញ្ជូនបន្ត}}ដូចតទៅនេះ ត្រូវ​បញ្ជូនបន្ដទៅ​ឯកសារ​នេះ​៖',
'duplicatesoffile'          => '{{PLURAL:$1|file is a duplicate|$1 ឯកសារ​ជាច្បាប់ចម្លង}}ដូចតទៅ​នៃ​ឯកសារ​នេះ​ ([[Special:FileDuplicateSearch/$2|ព័ត៌មាន​លំអិត]])​៖',
'sharedupload'              => 'ឯកសារ​នេះ​​បាន​មក​ពី $1 និង​អាច​ត្រូវ​បាន​ប្រើប្រាស់​នៅ​គម្រោង​ដទៃ​។',
'sharedupload-desc-there'   => 'ឯកសារ​នេះ​មក​ពី ​$1 និង​អាច​ត្រូវ​បាន​ប្រើប្រាស់​ដោយ​គម្រោង​ផ្សេង​ៗ​ដទៃ​ទៀត​។
សូម​មើល​[ទំព័របរិយាយ​ឯកសារ​ $2] សម្រាប់​ព័ត៌មាន​បន្ថែម​។',
'filepage-nofile'           => 'គ្មានឯកសារ​ដែលមានឈ្មោះនេះទេ។',
'filepage-nofile-link'      => 'គ្មានរូបភាពដែលមានឈ្មោះនេះទេ។ ប៉ុន្តែអ្នកអាច [$1 ផ្ទុក​វា​ឡើង​] ។',
'uploadnewversion-linktext' => 'ផ្ទុកឡើងមួយកំណែថ្មីនៃឯកសារនេះ',
'shared-repo-from'          => 'ពី $1',
'shared-repo'               => 'ឃ្លាំងរួម​',

# File reversion
'filerevert'                => 'ត្រឡប់ $1',
'filerevert-legend'         => 'ត្រឡប់ឯកសារ',
'filerevert-intro'          => "អ្នក​កំពុង​នឹង​ចាប់​ផ្ដើម​ត្រឡប់​ឯកសារ​'''[[Media:$1|$1]]''' ទៅ​កាន់​[កំណែ​ $4 ដែលមាន​កាល​បរិច្ឆេទ​ $3, $2]។",
'filerevert-comment'        => 'យោបល់៖',
'filerevert-defaultcomment' => 'បម្លែងទៅកំណែប្រែដើមជា$2, $1',
'filerevert-submit'         => 'ត្រឡប់',

# File deletion
'filedelete'                  => 'លុបចេញ $1',
'filedelete-legend'           => 'លុបឯកសារចោល',
'filedelete-intro'            => "អ្នករៀបនឹងលុបចេញឯកសារ '''[[Media:$1|$1]]''' ព្រមជាមួយប្រវត្តិទាំងអស់របស់វាហើយ។",
'filedelete-intro-old'        => "អ្នក​កំពុង​លុប​ចោល​ កំណែ​ (version) នៃ'''[[Media:$1|$1]]''' នៅ​ [$4 $3, $2]។",
'filedelete-comment'          => 'ហេតុផលចំពោះការលុបចេញ៖',
'filedelete-submit'           => 'លុបចេញ',
'filedelete-success'          => "'''$1''' ត្រូវបានលុបចោលហើយ",
'filedelete-success-old'      => "កំណែ​នៃ​'''[[Media:$1|$1]]''' កាលពី​ $3, $2 ត្រូវ​បាន​លុប​។",
'filedelete-nofile'           => "មិនមាន '''$1''' ទេ។",
'filedelete-otherreason'      => 'មូលហេតុបន្ថែមផ្សេងទៀត៖',
'filedelete-reason-otherlist' => 'មូលហេតុផ្សេងទៀត',
'filedelete-reason-dropdown'  => '*ហេតុផលដែលលុបជារឿយៗ
**ការបំពានទៅលើកម្មសិទ្ធិបញ្ញា
**ឯកសារដែលចម្លងតាមគំរូ',
'filedelete-edit-reasonlist'  => 'មូលហេតុនៃការលុបការកែប្រែ',

# MIME search
'mimesearch'         => 'ស្វែងរក MIME',
'mimesearch-summary' => 'ទំព័រ​នេះ​អនុញ្ញាត​ឲ្យ​មាន​កា​ដាក់​តម្រង​លើ​ឯកសារ​តាម​ប្រភេទ MIME​ របស់​វា​។
វាយ​បញ្ចូល​៖ contenttype/subtype, ឧទាហរណ៍​ <tt>រូបភាព​/jpeg</tt> ។',
'mimetype'           => 'ប្រភេទ MIME ៖',
'download'           => 'ទាញយក',

# Unwatched pages
'unwatchedpages' => 'ទំព័រមិនត្រូវបានតាមដាន',

# List redirects
'listredirects' => 'បញ្ជីនៃការបញ្ជូនបន្ត',

# Unused templates
'unusedtemplates'     => 'ទំព័រគំរូ​ដែលលែងត្រូវបានប្រើ',
'unusedtemplatestext' => 'ទំព័រ​នេះ​មាន​រាយ​នាម​ទំព័រ​ទាំង​អស់​នៅ​ក្នុង​លំហ​ឈ្មោះ​{{ns:template}} ដែល​មិន​បាន​រាប់​បញ្ជូល​ក្នុង​ទំព័រ​ដទៃ​ទៀត​។
សូម​ចងចាំ​ក្នុង​ការ​ត្រួត​ពិនិត្យ​​តំណ​ភ្ជាប់​ផ្សេង​ៗ​ទៀត​ដែល​ភ្ជាប់​មក​ទំព័រ​គំរូ​មុន​នឹង​លុប​វា​ចោល​។',
'unusedtemplateswlh'  => 'តំណភ្ជាប់ផ្សេងៗទៀត',

# Random page
'randompage'         => 'ទំព័រចៃដន្យ',
'randompage-nopages' => 'គ្មាន​ទំព័រ​ណាមួយ​ក្នុង​{{PLURAL:$2|លំហឈ្មោះ}}នេះ​ទេ៖ "$1" ។',

# Random redirect
'randomredirect'         => 'ទំព័របញ្ជូនបន្តចៃដន្យ',
'randomredirect-nopages' => 'គ្មានទំព័របញ្ជូនបន្តណាមួយនៅក្នុងលំហឈ្មោះ "$1" ទេ។',

# Statistics
'statistics'                   => 'ស្ថិតិ',
'statistics-header-pages'      => 'ស្ថិតិទំព័រ',
'statistics-header-edits'      => 'ស្ថិតិកំណែប្រែ',
'statistics-header-views'      => 'មើលស្ថិតិ',
'statistics-header-users'      => 'ស្ថិតិអ្នកប្រើប្រាស់',
'statistics-header-hooks'      => 'ស្ថិតិ​ដទៃទៀត​',
'statistics-articles'          => 'ទំព័រខ្លឹមសារ',
'statistics-pages'             => 'ចំនួនទំព័រសរុប',
'statistics-pages-desc'        => 'ទំព័រទាំងអស់នៅក្នុងវិគី រាប់បញ្ចូលទាំងទំព័រពិភាក្សា ទំព័របញ្ជូនបន្ត -ល-',
'statistics-files'             => 'ឯកសារបានផ្ទុកឡើង',
'statistics-edits'             => 'ការកែប្រែទំព័រចាប់តាំងពី{{SITENAME}}ត្រូវបានដំឡើង',
'statistics-edits-average'     => 'កំណែប្រែជាមធ្យមក្នុងមួយទំព័រ',
'statistics-views-total'       => 'ចំនួនការចូលមើលសរុប',
'statistics-views-peredit'     => 'ចំនួនការចូលមើលក្នុងមួយកំណែប្រែ',
'statistics-jobqueue'          => 'ប្រវែង[http://www.mediawiki.org/wiki/Manual:Job_queue ជួរការងារ]',
'statistics-users'             => '[[Special:ListUsers|អ្នកប្រើប្រាស់]]ដែលបានចុះឈ្មោះ',
'statistics-users-active'      => 'អ្នកប្រើប្រាស់សកម្ម',
'statistics-users-active-desc' => 'អ្នក​ប្រើប្រាស់​ដែល​បាន​អនុវត្ត​សកម្មភាព​ក្នុង​{{PLURAL:$1|ថ្ងៃ​}}ចុង​ក្រោយ​',
'statistics-mostpopular'       => 'ទំព័រដែលត្រូវបានមើលច្រើនបំផុត',

'disambiguations' => 'ទំព័រមានចំណងជើងស្រដៀងគ្នា',

'doubleredirects'            => 'ទំព័របញ្ជូនបន្តទ្វេដង',
'double-redirect-fixed-move' => '[[$1]] ត្រូវបានដកចេញ។ វាត្រូវបានបញ្ជូនបន្តទៅ [[$2]]',
'double-redirect-fixer'      => 'អ្នកជួសជុលការបញ្ជូនបន្ត',

'brokenredirects'        => 'ការបញ្ជូនបន្តដែលខូច',
'brokenredirectstext'    => 'ការបញ្ជូនបន្ដដូចតទៅនេះ​សំដៅទៅ​ទំព័រដែលមិនមាន៖',
'brokenredirects-edit'   => 'កែប្រែ',
'brokenredirects-delete' => 'លុបចេញ',

'withoutinterwiki'         => 'ទំព័រ​គ្មានតំណភ្ជាប់ភាសា',
'withoutinterwiki-summary' => 'ទំព័រទាំងនេះ​មិនតភ្ជាប់​ទៅទម្រង់ជាភាសាដទៃ៖',
'withoutinterwiki-legend'  => 'បុព្វបទ',
'withoutinterwiki-submit'  => 'បង្ហាញ',

'fewestrevisions' => 'ទំព័រដែលត្រូវបានកែប្រែតិច​បំផុត',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|បៃ|បៃ}}',
'ncategories'             => '$1 {{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុម|ចំណាត់ថ្នាក់ក្រុម}}',
'nlinks'                  => '$1 {{PLURAL:$1|តំណភ្ជាប់|តំណភ្ជាប់}}',
'nmembers'                => '$1{{PLURAL:$1|សមាជិក|សមាជិក}}',
'nrevisions'              => '$1 {{PLURAL:$1|កំណែប្រែ}}',
'nviews'                  => '$1 {{PLURAL:$1|ការចូលមើល}}',
'specialpage-empty'       => 'គ្មានលទ្ធផលសម្រាប់របាយណ៍នេះទេ។',
'lonelypages'             => 'ទំព័រកំព្រា',
'lonelypagestext'         => 'ទំព័រដូចតទៅនេះមិនត្រូវបានភ្ជាប់ពីទំព័រដទៃនៅក្នុង {{SITENAME}}ទេ។',
'uncategorizedpages'      => 'ទំព័រគ្មានចំណាត់ថ្នាក់ក្រុម',
'uncategorizedcategories' => 'ចំណាត់ថ្នាក់ក្រុមដែលមិនត្រូវបានចាត់ជាថ្នាក់',
'uncategorizedimages'     => 'រូបភាពគ្មានចំណាត់ថ្នាក់ក្រុម',
'uncategorizedtemplates'  => 'ទំព័រគំរូគ្មានចំណាត់ថ្នាក់ក្រុម',
'unusedcategories'        => 'ចំណាត់ថ្នាក់ក្រុមដែលមិនត្រូវបានប្រើប្រាស់',
'unusedimages'            => 'ឯកសារ(មេឌា​ រូបភាព)ដែលមិនត្រូវបានប្រើប្រាស់',
'popularpages'            => 'ទំព័រដែលមានប្រជាប្រិយ',
'wantedcategories'        => 'ចំណាត់ថ្នាក់ក្រុមដែលគ្រប់គ្នាចង់បាន',
'wantedpages'             => 'ទំព័រ​ដែល​គ្រប់គ្នា​ចង់បាន',
'wantedpages-badtitle'    => 'ចំណងជើង​មិន​ត្រឹមត្រូវ​នៅ​ក្នុង​សំនុំ​លទ្ធផល​៖ $1',
'wantedfiles'             => 'ឯកសារចង់បាន',
'wantedtemplates'         => 'ទំព័រគំរូចង់បាន',
'mostlinked'              => 'ទំព័រដែលត្រូវបានតភ្ជាប់មកច្រើនបំផុត',
'mostlinkedcategories'    => 'ចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានតភ្ជាប់មកច្រើនបំផុត',
'mostlinkedtemplates'     => 'ទំព័រគំរូ​ដែលត្រូវបានប្រើប្រាស់​ច្រើនបំផុត',
'mostcategories'          => 'អត្ថបទដែលមានចំណាត់ថ្នាក់ក្រុមច្រើនបំផុត',
'mostimages'              => 'រូបភាពដែលត្រូវបានតភ្ជាប់មកច្រើនបំផុត',
'mostrevisions'           => 'អត្ថបទដែលត្រូវបានកែប្រែច្រើនបំផុត',
'prefixindex'             => 'ទំព័រ​ទាំង​អស់​ជាមួយ​បុព្វបទ',
'shortpages'              => 'ទំព័រខ្លីៗ',
'longpages'               => 'ទំព័រវែងៗ',
'deadendpages'            => 'ទំព័រ​ទាល់',
'deadendpagestext'        => 'ទំព័រដូចតទៅនេះមិនតភ្ជាប់ទៅទំព័រដទៃទៀតក្នុង {{SITENAME}} ទេ។',
'protectedpages'          => 'ទំព័រដែលត្រូវបានការពារ',
'protectedpages-indef'    => 'ចំពោះតែការការពារដែលមិនកំណត់ប៉ុណ្ណោះ',
'protectedpages-cascade'  => 'ការការពារ Cascading ប៉ុណ្ណោះ​',
'protectedpagestext'      => 'ទំព័រដូចតទៅនេះត្រូវបានការពារមិនឱ្យប្ដូរទីតាំងឬកែប្រែ',
'protectedpagesempty'     => '​មិន​មាន​ទំព័រ​ណា​ដែល​ត្រូវបាន​ការពារ ជាមួយប៉ារ៉ាម៉ែត​ទាំងនេះទេ។',
'protectedtitles'         => 'ចំណងជើងត្រូវបានការពារ',
'protectedtitlestext'     => 'ចំណងជើងទំព័រត្រូវបានការពារមិនឱ្យបង្កើត',
'protectedtitlesempty'    => 'មិន​មាន​ចំណងជើង​ណា​ដែល​ត្រូវ​បាន​ការពារ​ជាមួយនឹង​ប៉ារ៉ាម៉ែត​ទាំងនេះ​ទេ​នាពេលថ្មីៗនេះ។',
'listusers'               => 'បញ្ជីអ្នកប្រើប្រាស់',
'listusers-editsonly'     => 'បង្ហាញអ្នកប្រើប្រាស់ដែលមានកំណែប្រែប៉ុណ្ណោះ',
'listusers-creationsort'  => 'តំរៀបតាមលំដាប់កាលបរិច្ឆេទបង្កើត',
'usereditcount'           => '$1 {{PLURAL:$1|កំណែប្រែ|កំណែប្រែ}}',
'usercreated'             => 'បានបង្កើតនៅថ្ងៃទី$1 ម៉ោង $2',
'newpages'                => 'ទំព័រថ្មីៗ',
'newpages-username'       => 'ឈ្មោះអ្នកប្រើប្រាស់៖',
'ancientpages'            => 'ទំព័រ​ចាស់ៗ',
'move'                    => 'ប្ដូរទីតាំង',
'movethispage'            => 'ប្ដូរទីតាំងទំព័រនេះ',
'unusedcategoriestext'    => 'ចំណាត់ថ្នាក់ក្រុមដូចតទៅនេះមាន ប៉ុន្តែគ្មាទំព័រណាឬចំណាត់ថ្នាក់ណាដែលប្រើប្រាស់ពួកវាទេ។',
'notargettitle'           => 'គ្មានគោលដៅ',
'nopagetitle'             => 'គ្មានទំព័រគោលដៅបែបនេះទេ',
'nopagetext'              => 'ទំព័រគោលដៅដែលអ្នកបានសំដៅទៅ មិនមានទេ។',
'pager-newer-n'           => '{{PLURAL:$1|ថ្មីជាង$1}}',
'pager-older-n'           => '{{PLURAL:$1|ចាស់ជាង$1}}',

# Book sources
'booksources'               => 'ប្រភពសៀវភៅ',
'booksources-search-legend' => 'ស្វែងរកប្រភពសៀវភៅ',
'booksources-isbn'          => 'លេខ​កូដ​សៀវ​ភៅ​ ISBN ៖',
'booksources-go'            => 'ទៅ',
'booksources-text'          => 'ខាងក្រោមនេះជាបញ្ជីនៃតំណភ្ជាប់ទៅសៃថ៍នានាដែលលក់​សៀវភៅថ្មីនិងជជុះ ហើយអាចផ្ដល់ព័ត៌មានបន្ថែមផ្សេងទៀតអំពីសៀវភៅដែលអ្នកកំពុងស្វែងរក៖',

# Special:Log
'specialloguserlabel'  => 'អ្នកប្រើប្រាស់៖',
'speciallogtitlelabel' => 'ចំណងជើង៖',
'log'                  => 'កំណត់ហេតុ',
'all-logs-page'        => 'កំណត់ហេតុសាធារណៈទាំងអស់',
'logempty'             => 'គ្មានអ្វីក្នុងកំណត់ហេតុត្រូវនឹងទំព័រនេះទេ។',
'log-title-wildcard'   => 'ស្វែងរកចំណងជើងចាប់ផ្តើមដោយឃ្លានេះ',

# Special:AllPages
'allpages'          => 'ទំព័រទាំងអស់',
'alphaindexline'    => 'ពីទំព័រ $1 ដល់ទំព័រ $2',
'nextpage'          => 'ទំព័របន្ទាប់ ($1)',
'prevpage'          => 'ទំព័រមុន ($1)',
'allpagesfrom'      => 'បង្ហាញទំព័រផ្ដើមដោយ៖',
'allpagesto'        => 'បង្ហាញទំព័របញ្ជប់ដោយ៖',
'allarticles'       => 'គ្រប់ទំព័រ',
'allinnamespace'    => 'គ្រប់ទំព័រ(លំហឈ្មោះ$1)',
'allnotinnamespace' => 'គ្រប់ទំព័រ(មិននៅក្នុងលំហឈ្មោះ$1)',
'allpagesprev'      => 'មុន',
'allpagesnext'      => 'បន្ទាប់',
'allpagessubmit'    => 'ទៅ',
'allpagesprefix'    => 'បង្ហាញទំព័រដែលចាប់ផ្ដើមដោយ ៖',
'allpagesbadtitle'  => 'ចំណងជើង​ទំព័រ​ដែល​ត្រូវ​បាន​ផ្តល់ឱ្យ​គឺ​គ្មាន​សុពលភាព​ឬក៏​មាន​បុព្វបទ​ដែល​មាន​អន្តរភាសា​ឬអ​ន្តរវីគី​។ ប្រហែលជា​វា​មាន​អក្សរ​មួយ​ឬ​ច្រើន ដែល​មិន​អាច​ត្រូវ​ប្រើ​នៅក្នុង​ចំណងជើង​។',
'allpages-bad-ns'   => '{{SITENAME}}មិនមានលំហឈ្មោះ"$1"ទេ។',

# Special:Categories
'categories'                    => 'ចំណាត់ថ្នាក់ក្រុម',
'categoriespagetext'            => '{{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុម|ចំណាត់ថ្នាក់ក្រុម}}ខាងក្រោមនេះមានអត្ថបទឬមេឌា។

[[Special:UnusedCategories|ចំណាត់ថ្នាក់ក្រុមមិនប្រើ]]ត្រូវបានបង្ហាញទីនេះ។
សូមមើលផងដែរ [[Special:WantedCategories|ចំណាត់ថ្នាក់ក្រុមដែលគ្រប់គ្នាចង់បាន]]។',
'categoriesfrom'                => 'បង្ហាញចំណាត់ថ្នាក់ក្រុមចាប់ផ្តើមដោយ៖',
'special-categories-sort-count' => 'តម្រៀបតាមចំនួន',
'special-categories-sort-abc'   => 'តម្រៀបតាមអក្ខរក្រម',

# Special:DeletedContributions
'deletedcontributions'             => 'ការរួមចំណែកដែលត្រូវបានលុប',
'deletedcontributions-title'       => 'ការរួមចំណែកដែលត្រូវបានលុប',
'sp-deletedcontributions-contribs' => 'ការរួមចំណែក​',

# Special:LinkSearch
'linksearch'      => 'តំណភ្ជាប់ខាង​ក្រៅ​',
'linksearch-pat'  => 'ស្វែងរកគំរូ៖',
'linksearch-ns'   => 'លំហឈ្មោះ៖',
'linksearch-ok'   => 'ស្វែងរក',
'linksearch-line' => '$1បានតភ្ជាប់ពី$2',

# Special:ListUsers
'listusersfrom'      => 'បង្ហាញអ្នកប្រើប្រាស់ចាប់ផ្តើមដោយ៖',
'listusers-submit'   => 'បង្ហាញ',
'listusers-noresult' => 'មិនមានអ្នកប្រើប្រាស់នៅក្នុងក្រុមនេះទេ។',
'listusers-blocked'  => '(បានរាំងខ្ទប់)',

# Special:ActiveUsers
'activeusers'          => 'បញ្ជីរាយនាមអ្នកប្រើប្រាស់សកម្ម',
'activeusers-count'    => '$1 {{PLURAL:$1|កំនែប្រែ|កំនែប្រែ}}ថ្មីៗ',
'activeusers-from'     => 'បង្ហាញឈ្មោះអ្នកប្រើប្រាស់ផ្ដើមដោយ៖',
'activeusers-noresult' => 'អ្នកប្រើប្រាស់​រកមិនឃើញ​។​',

# Special:Log/newusers
'newuserlogpage'              => 'កំណត់ហេតុនៃការបង្កើតគណនី',
'newuserlogpagetext'          => 'នេះជាកំណត់ហេតុនៃការបង្កើតអ្នកប្រើប្រាស់។',
'newuserlog-byemail'          => 'ពាក្យសំងាត់ត្រូវបានផ្ញើតាមអ៊ីមែល',
'newuserlog-create-entry'     => 'អ្នកប្រើប្រាស់ថ្មី',
'newuserlog-create2-entry'    => "បាន​បង្កើត​គណនី​ថ្មី​សម្រាប់ '''$1'''",
'newuserlog-autocreate-entry' => 'គណនីត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ',

# Special:ListGroupRights
'listgrouprights'                      => 'សិទ្ធិនិងក្រុមអ្នកប្រើប្រាស់',
'listgrouprights-summary'              => 'ខាងក្រោមនេះជាបញ្ជីរាយឈ្មោះក្រុមអ្នកប្រើប្រាស់ដែលបានកំណត់ជាមួយនឹងសិទ្ធិរបស់គេនៅលើវិគីនេះ។ មាន[[{{MediaWiki:Listgrouprights-helppage}}|ព័ត៌មានបន្ថែម]] អំពីសិទ្ធិផ្ទាល់ខ្លួន។',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">សិទ្ធិ​ដែល​បាន​ផ្តល់​ជូន​</span>
* <span class="listgrouprights-revoked">សិទ្ធិ​ដែល​បាន​ដក​ហូត​</span>',
'listgrouprights-group'                => 'ក្រុម',
'listgrouprights-rights'               => 'សិទ្ធិ',
'listgrouprights-helppage'             => 'Help:ក្រុមនិងសិទ្ធិ',
'listgrouprights-members'              => '(បញ្ជីរាយនាមសមាជិក)',
'listgrouprights-addgroup'             => 'អាចបន្ថែម{{PLURAL:$2|ក្រុម|ក្រុម}}៖ $1',
'listgrouprights-removegroup'          => 'អាចដកចេញ {{PLURAL:$2|group|ក្រុម}}​៖ $1',
'listgrouprights-addgroup-all'         => 'អាចបន្ថែមគ្រប់ក្រុម',
'listgrouprights-removegroup-all'      => 'អាចដកចេញគ្រប់ក្រុម',
'listgrouprights-addgroup-self'        => 'បន្ថែម{{PLURAL:$2|ក្រុម}}ទៅ​គណនី​ផ្ទាល់ខ្លួន​៖ $1',
'listgrouprights-removegroup-self'     => 'យក​ចេញ​{{PLURAL:$2|ក្រុម}}ពី​​គណនី​ផ្ទាល់ខ្លួន​៖ $1',
'listgrouprights-addgroup-self-all'    => 'បន្ថែម​ក្រុម​ទាំងអស់​ទៅ​គណនី​ផ្ទាល់ខ្លួន​',
'listgrouprights-removegroup-self-all' => 'យក​ចេញ​​ក្រុម​ទាំងអស់​ពី​​គណនី​ផ្ទាល់ខ្លួន​',

# E-mail user
'mailnologin'      => 'មិនមានអាសយដ្ឋានផ្ញើទេ',
'mailnologintext'  => 'អ្នកត្រូវតែ [[Special:UserLogin|ឡុកអ៊ីន]] និង មានអាសយដ្ឋានអ៊ីមែលមានសុពលភាពមួយ ក្នុង[[Special:Preferences|ចំនូលចិត្តនានារបស់អ្នក]] ដើម្បីផ្ញើអ៊ីមែលទៅ អ្នកប្រើប្រាស់ដទៃទៀត។',
'emailuser'        => 'អ៊ីមែល​ទៅកាន់​អ្នក​ប្រើប្រាស់នេះ',
'emailpage'        => 'ទំព័រផ្ញើអ៊ីមែល',
'emailpagetext'    => 'អ្នក​អាច​ប្រើ​បែប​បទ​ខាង​ក្រោម​ក្នុង​ការ​ផ្ញើ​សារ​ជា​អ៊ីមែល​ទៅ​កាន់​អ្នក​ប្រើប្រាស់​នេះ​។
អាសយដ្ឋាន​អ៊ីមែល​ដែល​អ្នក​បាន​វាយ​បញ្ចូល​ក្នុង​[[Special:Preferences|ចំណូល​ចិត្ត​អ្នក​ប្រើ​ប្រាស់​របស់​អ្នក​]] នឹង​បង្ហាញ​ជា​អាសយដ្ឋាន​អ៊ីមែល "From" ដូច្នោះ​អ្នក​ទទួល​នឹង​អាច​ឆ្លើយ​តប​ទៅ​អ្នក​វិញ​ដោយ​ផ្ទាល់​។',
'usermailererror'  => 'កំហុសឆ្គងក្នុងចំណងជើងអ៊ីមែល៖',
'defemailsubject'  => 'អ៊ីមែលពី{{SITENAME}}',
'noemailtitle'     => 'គ្មានអាសយដ្ឋានអ៊ីមែល',
'noemailtext'      => 'អ្នកប្រើប្រាស់នេះមិនបានផ្ដល់អាសយដ្ឋានអ៊ីមែលដែលមានសុពលភាពទេ។',
'nowikiemailtitle' => 'មិនអនុញ្ញាតអោយប្រើអ៊ីមែល',
'nowikiemailtext'  => 'អ្នក​ប្រើប្រាស់​នេះ​បាន​ជ្រើសរើស​មិន​ទទួល​អ៊ីមែល​ពីអ្នកប្រើប្រាស់​ដទៃ​ទៀត​។',
'email-legend'     => 'ផ្ញើអ៊ីមែលទៅអ្នកប្រើប្រាស់{{SITENAME}}ម្នាក់ទៀត',
'emailfrom'        => 'ពី៖',
'emailto'          => 'ទៅកាន់៖',
'emailsubject'     => 'ប្រធានបទ៖',
'emailmessage'     => 'សារ៖',
'emailsend'        => 'ផ្ញើ',
'emailccme'        => 'អ៊ីមែលមកខ្ញុំនូវច្បាប់ចម្លងមួយនៃសាររបស់ខ្ញុំ។',
'emailccsubject'   => 'ច្បាប់ចម្លងនៃសាររបស់អ្នកចំពោះ $1 ៖ $2',
'emailsent'        => 'អ៊ីមែលត្រូវបានផ្ញើទៅហើយ',
'emailsenttext'    => 'សារអ៊ីមែលរបស់អ្នកត្រូវបានផ្ញើរួចហើយ។',
'emailuserfooter'  => 'អ៊ីមែលនេះត្រូវបានផ្ញើដោយ$1ទៅកាន់$2ដោយប្រើមុខងារ"អ៊ីមែលអ្នកប្រើប្រាស់"របស់{{SITENAME}}។',

# Watchlist
'watchlist'            => 'បញ្ជីតាមដានរបស់ខ្ញុំ',
'mywatchlist'          => 'បញ្ជីតាមដាន​',
'watchlistfor'         => "(សម្រាប់ '''$1''')",
'nowatchlist'          => 'គ្មានអ្វីនៅក្នុងបញ្ជីតាមដានរបស់អ្នកទេ។',
'watchlistanontext'    => 'សូម $1 ដើម្បី​មើល​ឬ​កែប្រែ​របស់​ក្នុង​បញ្ជីតាមដាន​របស់អ្នក។',
'watchnologin'         => 'មិនបានឡុកអ៊ីន',
'watchnologintext'     => 'អ្នកចាំបាច់ត្រូវតែ[[Special:UserLogin|ឡុកអ៊ីន]]ដើម្បីកែប្រែបញ្ជីតាមដានរបស់អ្នក។',
'addedwatch'           => 'បានបន្ថែមទៅបញ្ជីតាមដាន',
'addedwatchtext'       => "ទំព័រ \"[[:\$1]]\" ត្រូវបានដាក់បញ្ចូលទៅក្នុង​[[Special:Watchlist|បញ្ជីតាមដាន]]របស់លោកអ្នកហើយ ។ រាល់ការផ្លាស់ប្ដូរនៃទំព័រនេះ រួមទាំងទំព័រពិភាក្សារបស់វាផងដែរ នឹងត្រូវបានដាក់បញ្ចូលក្នុងបញ្ជីនៅទីនោះ។  ទំព័រនេះនឹងបង្ហាញជា'''អក្សរដិត''' នៅក្នុង [[Special:RecentChanges|បញ្ជីបំលាស់ប្ដូរថ្មីៗ]] ងាយស្រួលក្នុងការស្វែងរក។ ប្រសិនបើលោកអ្នកចង់យកវាចេញពី [[Special:Watchlist|បញ្ជីតាមដាន]]របស់លោកអ្នក សូមចុច '''ឈប់តាមដាន''' នៅលើរបារចំហៀងផ្នែកខាងលើ។",
'removedwatch'         => 'ត្រូវបានដកចេញពីបញ្ជីតាមដាន',
'removedwatchtext'     => 'ទំព័រ "[[:$1]]" ត្រូវបានដកចេញពី[[Special:Watchlist|បញ្ជីតាមដាន]]របស់លោកអ្នកហើយ ។',
'watch'                => 'តាមដាន',
'watchthispage'        => 'តាមដានទំព័រនេះ',
'unwatch'              => 'ឈប់​តាមដាន',
'unwatchthispage'      => 'ឈប់តាមដាន',
'notanarticle'         => 'មិនមែនជាទំព័រមាតិកា',
'notvisiblerev'        => 'ការកែតម្រូវត្រូវបានលុបចោល',
'watchnochange'        => 'មិនមានរបស់ដែលអ្នកកំពុងតាមដានណាមួយត្រូវបានគេកែប្រែក្នុងកំលុងពេលដូលដែលបានបង្ហាញទេ។',
'watchlist-details'    => '{{PLURAL:$1|$1 page|ទំព័រ $1}}នៅក្នុងបញ្ជីតាមដានរបស់អ្នក ដោយមិនរាប់បញ្ចូលទំព័រពិភាក្សា។',
'wlheader-enotif'      => '* អនុញ្ញាតឱ្យមានការផ្ដល់ដំណឹងតាមរយៈអ៊ីមែល',
'wlheader-showupdated' => "* ទំព័រដែលត្រូវបានផ្លាស់ប្តូរតាំងពីពេលចូលមើលចុងក្រោយរបស់អ្នក ត្រូវបានបង្ហាញជា '''អក្សរដិត'''",
'watchmethod-recent'   => 'ឆែកមើលកំណែប្រែថ្មីៗចំពោះទំព័រត្រូវបានតាមដាន',
'watchmethod-list'     => 'ឆែកមើលទំព័រត្រូវបានតាមដានចំពោះកំណែប្រែថ្មីៗ',
'watchlistcontains'    => 'បញ្ជីតាមដាន របស់អ្នក មាន $1 {{PLURAL:$1|ទំព័រ|ទំព័រ}}។',
'iteminvalidname'      => "មានបញ្ហាជាមួយនឹង'$1'​។ ឈ្មោះគឺមិនត្រឹមត្រូវ...",
'wlnote'               => "ខាងក្រោមនេះជា {{PLURAL:$1|បំលាស់ប្តូរចុងក្រោយ|'''$1'''បំលាស់ប្តូរចុងក្រោយ}}ក្នុងរយះពេល{{PLURAL:$2|'''$2'''ម៉ោង}}ចុងក្រោយ។",
'wlshowlast'           => 'បង្ហាញ $1ម៉ោងចុងក្រោយ $2ថ្ងៃចុងក្រោយ ឬ$3',
'watchlist-options'    => 'ជម្រើសនានាក្នុងបញ្ជីតាមដាន',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'កំពុង​តាមដាន...',
'unwatching' => 'ឈប់​តាមដាន...',

'enotif_mailer'                => 'ភ្នាក់ងារផ្ញើអ៊ីមែលផ្ដល់ដំណឹងរបស់ {{SITENAME}}',
'enotif_reset'                 => 'កត់សម្គាល់រាល់គ្រប់ទំព័រដែលបានចូលមើល',
'enotif_newpagetext'           => 'នេះជាទំព័រថ្មី។',
'enotif_impersonal_salutation' => 'អ្នកប្រើប្រាស់ {{SITENAME}}',
'changed'                      => 'បានផ្លាស់ប្តូរ',
'created'                      => 'បានបង្កើត',
'enotif_subject'               => 'ទំព័រ $PAGETITLE នៃ {{SITENAME}} ត្រូវបាន $CHANGEDORCREATED ដោយ $PAGEEDITOR',
'enotif_lastvisited'           => 'ពិនិត្យ $1 ចំពោះគ្រប់បំលាស់ប្តូរ តាំងពីពេលចូលមើល ចុងក្រោយ។',
'enotif_lastdiff'              => 'សូមពិនិត្យ$1ដើម្បីមើលបំលាស់ប្តូរនេះ។',
'enotif_anon_editor'           => 'អ្នកប្រើប្រាស់អនាមិក $1',
'enotif_body'                  => 'ជូនចំពោះ $WATCHINGUSERNAME ជាទីរាប់អាន,


ទំព័រ $PAGETITLE នៃ {{SITENAME}} ត្រូវបាន  $CHANGEDORCREATED ថ្ងៃ $PAGEEDITDATE ដោយ $PAGEEDITOR។ សូមមើល $PAGETITLE_URL សម្រាប់​កំណែបច្ចុប្បន្ន។

$NEWPAGE

សេចក្ដីសង្ខេប​នៃអ្នកកែប្រែ៖ $PAGESUMMARY $PAGEMINOREDIT

ទាក់ទង​អ្នកកែប្រែ៖
អ៊ីមែល៖ $PAGEEDITOR_EMAIL
វិគី៖ $PAGEEDITOR_WIKI

នឹងមិនមាន​ការផ្ដល់ដំណឹង​ជាលាយលក្សណ៍អក្សរ​ផ្សេងទៀតទេ លើកលែងតែ​អ្នកចូលមើល​ទំព័រនេះ។ អ្នកក៏អាចធ្វើ​ឱ្យ​ការផ្តល់ដំណឹង​ត្រឡប់ទៅលើកទីសូន្យ​ចំពោះគ្រប់ទំព័រ​នៃ​បញ្ជីតាមដាន​របស់អ្នក។

ប្រព័ន្ធផ្តល់ដំណឹង {{SITENAME}} ដ៏ស្និទ្ធស្នាល​របស់អ្នក

--
ដើម្បីផ្លាស់ប្តូរ ការកំណត់បញ្ជីតាមដាន, សូមចូលមើល
{{fullurl:{{#special:Watchlist}}/edit}}

មតិ​យោបល់​និងជំនួយបន្ថែម ៖
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'លុបទំព័រចេញ',
'confirm'                => 'បញ្ជាក់ទទួលស្គាល់',
'excontent'              => "ខ្លឹមសារគឺ៖ '$1'",
'excontentauthor'        => "អត្ថន័យគឺ៖ '$1' (ហើយអ្នករួមចំណែកតែម្នាក់គត់គឺ '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "អត្ថន័យមុនពេលលុបចេញ៖ '$1'",
'exblank'                => 'ទំព័រទទេ',
'delete-confirm'         => 'លុប"$1"',
'delete-legend'          => 'លុប',
'historywarning'         => 'ប្រយ័ត្ន​៖ ទំព័រដែលអ្នកទំនងជានឹងលុបមានប្រវត្តិ​៖',
'confirmdeletetext'      => 'អ្នកប្រុងនឹងលុបចេញទាំងស្រុង នូវទំព័រមួយដោយរួមបញ្ចូលទាំងប្រវត្តិកែប្រែរបស់វាផង។
សូមអ្នកអះអាងថា អ្នកពិតជាមានចេតនាធ្វើបែបហ្នឹង និងថាអ្នកបានយល់ច្បាស់ពីផលវិបាកទាំងឡាយដែលអាចកើតមាន និង​សូមអះអាងថា អ្នកធ្វើស្របតាម [[{{MediaWiki:Policy-url}}|គោលការណ៍]]។',
'actioncomplete'         => 'សកម្មភាពរួចរាល់ជាស្ថាពរ',
'actionfailed'           => 'សកម្មភាព​បរាជ័យ',
'deletedtext'            => '"<nowiki>$1</nowiki>"ត្រូវបានលុបរួចហើយ។ សូមមើល$2ចំពោះបញ្ជីនៃការលុបនាមពេលថ្មីៗ។',
'deletedarticle'         => 'បានលុប"[[$1]]"',
'suppressedarticle'      => 'បាន​ហាម​ឃាត់​ "[[$1]]"',
'dellogpage'             => 'កំណត់ហេតុនៃការលុប',
'dellogpagetext'         => 'ខាងក្រោមជាបញ្ជីនៃការលុបចេញថ្មីៗបំផុត។',
'deletionlog'            => 'កំណត់ហេតុនៃការលុប',
'reverted'               => 'បានត្រឡប់ ទៅកំណែមុន',
'deletecomment'          => 'មូលហេតុ៖',
'deleteotherreason'      => 'មូលហេតុបន្ថែមផ្សេងទៀត៖',
'deletereasonotherlist'  => 'មូលហេតុផ្សេងទៀត',
'deletereason-dropdown'  => '*ហេតុផលលុបជាទូទៅ
** សំណើរបស់អ្នកនិពន្ធ
** បំពានសិទ្ធិអ្នកនិពន្ធ
** អំពើបំផ្លាញទ្រព្យសម្បត្តិឯកជនឬសាធារណៈ',
'delete-edit-reasonlist' => 'ពិនិត្យផ្ទៀងផ្ទាត់ហេតុផលនៃការលុប',

# Rollback
'rollback'         => 'ត្រឡប់កំណែប្រែ',
'rollback_short'   => 'ត្រឡប់',
'rollbacklink'     => 'ត្រឡប់',
'rollbackfailed'   => 'ការ​ត្រឡប់​ក្រោយ (Rollback) បរាជ័យ​',
'cantrollback'     => 'មិនអាចត្រឡប់កំណែប្រែ។ អ្នករួមចំណែកចុងក្រោយទើបជាអ្នកនិពន្ធ​របស់ទំព័រនេះ។',
'editcomment'      => "សេចក្ដីសង្ខេបអំពីការកែប្រែគឺ៖ \"''\$1''\"។",
'revertpage'       => 'បានត្រឡប់កំណែប្រែដោយ[[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) ទៅកំណែប្រែចុងក្រោយដោយ [[User:$1|$1]]',
'rollback-success' => 'កំណែ​ដែល​ត្រូវ​បាន​ត្រឡប់​ដោយ​ $1។
បាន​ផ្លាស់​ប្ដូរ​ទៅ​កំណែ​ចុង​ក្រោយ​វិញ​ដោយ $2។',

# Protect
'protectlogpage'              => 'កំណត់ហេតុនៃការការពារ',
'protectlogtext'              => 'ខាងក្រោមនេះជាបញ្ជីនៃទំព័រដែលត្រូវបានចាក់សោនិងដោះសោ។

សូមមើល [[Special:ProtectedPages|បញ្ជីទំព័រត្រូវបានការពារ]]។',
'protectedarticle'            => 'បានការពារ"[[$1]]"',
'modifiedarticleprotection'   => 'បានផ្លាស់ប្តូរកម្រិតការពារនៃ"[[$1]]"',
'unprotectedarticle'          => 'បានឈប់ការពារ៖ "[[$1]]"',
'movedarticleprotection'      => 'បាន​ផ្លាស់​ប្ដូរ​ទី​តាំង​ការ​កំណត់​ការ​ការពារ​ពី"[[$2]]" ទៅ​ "[[$1]]"',
'protect-title'               => 'ការពារ "$1"',
'prot_1movedto2'              => 'បានប្តូរទីតាំង [[$1]] ទៅ [[$2]]',
'protect-legend'              => 'បញ្ជាក់ទទួលស្គាល់ការការពារ',
'protectcomment'              => 'មូលហេតុ៖',
'protectexpiry'               => 'ផុតកំណត់៖',
'protect_expiry_invalid'      => 'ពេលវេលាផុតកំណត់ មិនត្រឹមត្រូវ។',
'protect_expiry_old'          => 'ពេលវេលាផុតកំណត់ ឋិតក្នុងអតីតកាល។',
'protect-unchain'             => 'ឈប់ហាមឃាត់ការអនុញ្ញាតប្ដូរទីតាំង',
'protect-text'                => "លោកអ្នកអាចមើលនិងផ្លាស់ប្ដូរកម្រិតការពារទីនេះចំពោះទំព័រ'''<nowiki>$1</nowiki>'''។",
'protect-locked-blocked'      => "អ្នកមិនអាចប្តូរកម្រិតការពារនៅក្នុងកំលុងពេលដែលត្រូវបានគេរារាំង។ នេះគឺជាការរៀបចំថ្មីៗសម្រាប់ទំព័រ'''$1''':",
'protect-locked-dblock'       => "កម្រិត​នៃ​ការ​ការពារ​មិន​អាច​ផ្លាស់​ប្ដូរ​បាន​ទេ​ ដោយសារ​មាន​ជាប់​​សោ​មូលដ្នាន​ទិន្នន័យសកម្ម​។
នេះ​គឺជា​ការ​កំណត់​បច្ចុប្បន្ន​សម្រាប់​ទំព័រ​ '''$1''' ៖",
'protect-locked-access'       => "គណនីរបស់អ្នកគ្មានការអនុញ្ញាតក្នុងការផ្លាស់ប្ដូរ កម្រិតកាពារទំព័រ ។
នេះជាការកំណត់បច្ចុប្បន្ន ចំពោះទំព័រ '''$1''' ៖",
'protect-cascadeon'           => 'បច្ចុប្បន្ន ទំព័រនេះ ត្រូវបានការពារ ព្រោះ វាបាន ស្ថិតក្នុង {{PLURAL:$1|ទំព័រ, ដែលមាន}} ការការពារ ជាថ្នាក់ បានសកម្ម ​។ អ្នកអាច ផ្លាស់ប្តូរ កម្រិតការពារ នៃ ទំព័រ, វានឹង មិនប៉ះពាល់ ការការពារ ជាថ្នាក់ ។',
'protect-default'             => 'អនុញ្ញាត​អ្នក​ប្រើ​ប្រាស់​ទាំង​អស់​',
'protect-fallback'            => 'តម្រូវឱ្យមានការអនុញ្ញាតនៃ "$1"',
'protect-level-autoconfirmed' => 'ហាមឃាត់អ្នកប្រើប្រាស់ថ្មី​នឹង​អ្នក​​មិនទាន់ចុះឈ្មោះ',
'protect-level-sysop'         => 'សម្រាប់តែអ្នកថែទាំប្រព័ន្ធ',
'protect-summary-cascade'     => 'ការពារជា​ថ្នាក់',
'protect-expiring'            => 'ផុតកំណត់ $1 (UTC)',
'protect-expiry-indefinite'   => 'គ្មានកំណត់',
'protect-cascade'             => 'ការពារគ្រប់ទំព័រដែលឋិតក្នុងទំព័រនេះ (ការពារជាថ្នាក់)',
'protect-cantedit'            => 'អ្នកមិនអាចផ្លាស់ប្ដូរកម្រិតការពារនៃទំព័រនេះទេ ព្រោះអ្នកគ្មានការអនុញ្ញាតក្នុងការកែប្រែវា។',
'protect-othertime'           => 'រយៈពេលផុតកំណត់ផ្សេងទៀត៖',
'protect-othertime-op'        => 'រយៈពេលផុតកំណត់ផ្សេងទៀត',
'protect-existing-expiry'     => 'រយៈពេលផុតកំណត់មានស្រាប់៖ $3, $2',
'protect-otherreason'         => 'មូលហេតុបន្ថែមផ្សេងៗទៀត៖',
'protect-otherreason-op'      => 'មូលហេតុបន្ថែមផ្សេងៗទៀត',
'protect-dropdown'            => '*មូលហេតុការពារជាទូទៅ
** ទទួលការបំផ្លិចបំផ្លាញយ៉ាងសំបើមក្រៃលែង
** ស្ព៊ែមយ៉ាងសំបើមក្រៃលែង
** សង្រ្គាមនៃការកែប្រែដែលនាំឲខូចប្រយោជន៍
** ទំព័រដែលមានចរាចរកម្រិតខ្ពស់',
'protect-edit-reasonlist'     => 'មូលហេតុដែលគេការពារមិនឱ្យមានការកែប្រែ',
'protect-expiry-options'      => '១ ម៉ោង:1 hour,១ ថ្ងៃ:1 day,១ សប្ដាហ៍:1 week,២ សប្ដាហ៍:2 weeks,១ ខែ:1 month,៣ ខែ:3 months,៦ ខែ:6 months,១ ឆ្នាំ:1 year,គ្មានកំណត់:infinite',
'restriction-type'            => 'ការអនុញ្ញាត៖',
'restriction-level'           => 'កម្រិត​នៃ​ការដាក់កំហិត ៖',
'minimum-size'                => 'ទំហំអប្បបរមា',
'maximum-size'                => 'ទំហំអតិបរមា:',
'pagesize'                    => '(បៃ)',

# Restrictions (nouns)
'restriction-edit'   => 'កែប្រែ',
'restriction-move'   => 'ប្តូរទីតាំង',
'restriction-create' => 'បង្កើត',
'restriction-upload' => 'ផ្ទុកឡើង',

# Restriction levels
'restriction-level-sysop'         => 'បានការពារពេញលេញ',
'restriction-level-autoconfirmed' => 'បានការពារពាក់កណ្តាល',
'restriction-level-all'           => 'គ្រប់កម្រិត',

# Undelete
'undelete'                  => 'មើលទំព័រដែលត្រូវបានលុបចេញ',
'undeletepage'              => 'មើលហើយដាក់ឡើងវិញនូវទំព័រដែលបានលុប',
'undeletepagetitle'         => "'''ខាងក្រោមនេះមានកំណែប្រែដែលត្រូវបានលុបរបស់[[:$1]]'''.",
'viewdeletedpage'           => 'មើលទំព័រដែលត្រូវបានលុបចេញ',
'undelete-fieldset-title'   => 'ស្តារកំណែឡើងវិញ',
'undeleterevisions'         => '$1 {{PLURAL:$1|កំណែ​ប្រែ​}} បាន​ដាក់​ចូល​ជា​បណ្ណសារ​',
'undeletehistorynoadmin'    => 'ទំព័រនេះត្រូវបានលុបចេញហើយ។
មូលហេតុចំពោះការលុបចេញ​គឺត្រូវបានបង្ហាញនៅក្នុង​សេចក្តីសង្ខេបខាងក្រោម ជាមួយគ្នានឹងសេចក្តីលំអិតនៃ​អ្នកប្រើប្រាស់​ដែលបានធ្វើការកែប្រែ​ទំព័រនេះ​មុនពេលវាត្រូវបាន​លុបចេញ។ 
ឃ្លាជាការពិតនៃ​ការត្រួតពិនិត្យកំណែប្រែឡើងវិញ​​ដែលត្រូវបានលុបចេញគឺមានសុពលភាពចំពោះតែ​អ្នកអភិបាលប៉ុណ្ណោះ។',
'undelete-revision'         => 'កំណែប្រែដែលបាន​លុបចោល​នៃ $1 (នៅថ្ងៃ​ $4, នៅម៉ោង​ $5) ដោយ​ $3៖',
'undeleterevision-missing'  => 'កំណែ​មិន​មាន​សុពលភាព​​ឬ​បាត់​បង់​។​
អ្នក​ប្រហែល​ជា​មាន​តំណ​ភ្ជាប់​មិន​ល្អ​ ឬ​កំណែ​ប្រែ​ប្រហែល​ជា​ត្រូវ​បាន​ស្ដារ​ឡើង​វិញ​ ឬ​ដក​ចេញ​ពី​បណ្ណសារ​។',
'undelete-nodiff'           => 'គ្មានការកែតម្រូវពីមុនត្រូវបានឃើញទេ។',
'undeletebtn'               => 'ស្ដារឡើងវិញ',
'undeletelink'              => 'មើល​/​ស្តារឡើងវិញ',
'undeleteviewlink'          => 'មើល',
'undeletereset'             => 'ធ្វើឱ្យដូចដើមវិញ',
'undeleteinvert'            => 'បញ្ច្រាសជម្រើស',
'undeletecomment'           => 'យោបល់៖',
'undeletedarticle'          => 'បានស្តារ"[[$1]]"ឡើងវិញ',
'undeletedrevisions'        => 'បានស្តារឡើងវិញនូវ{{PLURAL:$1|១កំណែ|$1កំណែ}}',
'undeletedrevisions-files'  => 'បានស្តារឡើងវិញនូវ{{PLURAL:$1|១កំណែ|$1កំណែ}}និង{{PLURAL:$2|១ឯកសារ|$2ឯកសារ}}',
'undeletedfiles'            => '{{PLURAL:$1|១ ឯកសារ|$1 ឯកសារ}} ត្រូវបានស្ដារឡើងវិញ',
'cannotundelete'            => 'ឈប់លុបមិនសម្រេច។

ប្រហែលជាមាននរណាម្នាក់ផ្សេងទៀតបានឈប់លុបទំព័រនេះមុនអ្នក។',
'undeletedpage'             => "<big>'''$1 ត្រូវបានស្តារឡើងវិញហើយ'''</big>

សូម​ចូល​ទៅ [[Special:Log/delete|កំណត់ហេតុ​នៃ​ការលុប]] ដើម្បី​ពិនិត្យ​មើល​កំណត់ត្រា​នៃ​ការលុប​និង​ការ​ស្ដារ​ឡើង​វិញ​។",
'undelete-header'           => 'មើល[[Special:Log/delete|កំណត់ហេតុនៃការលុប]]ចំពោះទំព័រដែលត្រូវបានលុបថ្មីៗ។',
'undelete-search-box'       => 'ស្វែងរកទំព័រ ដែលបានត្រូវលុប',
'undelete-search-prefix'    => 'បង្ហាញទំព័រចាប់ផ្តើមដោយ៖',
'undelete-search-submit'    => 'ស្វែងរក',
'undelete-cleanup-error'    => 'កំហុស លុបចេញ បណ្ណសារ ដែលបានលែងប្រើប្រាស់ "$1" ។',
'undelete-error-short'      => 'កំហុស លែងលុបចេញ ឯកសារ ៖  $1',
'undelete-error-long'       => 'កំហុសផ្សេងៗបានកើតឡើងក្នុងពេលកំពុងឈប់លុបឯកសារនេះ៖
$1',
'undelete-show-file-submit' => 'បាទ/ចាស',

# Namespace form on various pages
'namespace'      => 'លំហឈ្មោះ៖',
'invert'         => 'ដាក់បញ្ច្រាសជម្រើស',
'blanknamespace' => '(ទូទៅ)',

# Contributions
'contributions'       => 'ការរួមចំណែក​របស់អ្នកប្រើប្រាស់',
'contributions-title' => 'ការរួមចំណែករបស់អ្នកប្រើប្រាស់ $1',
'mycontris'           => 'ការរួមចំណែក',
'contribsub2'         => 'សម្រាប់ $1 ($2)',
'nocontribs'          => 'គ្មានការផ្លាស់ប្តូរត្រូវបានឃើញដូចនឹងលក្ខណៈវិនិច្ឆ័យទាំងនេះ។',
'uctop'               => '(ទាន់សម័យ)',
'month'               => 'ខែ៖',
'year'                => 'ឆ្នាំ៖',

'sp-contributions-newbies'       => 'បង្ហាញតែការរួមចំណែករបស់អ្នកប្រើប្រាស់ថ្មីៗ',
'sp-contributions-newbies-sub'   => 'ចំពោះគណនីថ្មីៗ',
'sp-contributions-newbies-title' => 'ការរួមចំណែករបស់អ្នកប្រើប្រាស់ចំពោះគណនីថ្មី',
'sp-contributions-blocklog'      => 'កំណត់ហេតុនៃការហាមឃាត់',
'sp-contributions-deleted'       => 'ការរួមចំណែកដែលត្រូវបានលុប',
'sp-contributions-logs'          => 'កំណត់​ហេតុ​',
'sp-contributions-talk'          => 'ការពិភាក្សា',
'sp-contributions-userrights'    => 'ការគ្រប់គ្រងសិទ្ធិអ្នកប្រើប្រាស់',
'sp-contributions-search'        => 'ស្វែងរកការរួមចំណែក',
'sp-contributions-username'      => 'អាសយដ្ឋាន IP ឬឈ្មោះអ្នកប្រើ៖',
'sp-contributions-submit'        => 'ស្វែងរក',

# What links here
'whatlinkshere'            => 'អ្វី​ដែលភ្ជាប់មកទីនេះ',
'whatlinkshere-title'      => 'ទំព័រនានាដែល​តភ្ជាប់​ទៅ "$1"',
'whatlinkshere-page'       => 'ទំព័រ៖',
'linkshere'                => "ទំព័រដូចតទៅ​នេះតភ្ជាប់មក '''[[:$1]]''' ៖",
'nolinkshere'              => "គ្មានទំព័រណាមួយតភ្ជាប់ទៅ '''[[:$1]]''' ទេ។",
'nolinkshere-ns'           => "គ្មានទំព័រណាមួយ តភ្ជាប់ ទៅ '''[[:$1]]''' ក្នុងវាលឈ្មោះ ដែលបានជ្រើសរើស។",
'isredirect'               => 'ទំព័របញ្ជូនបន្ត',
'istemplate'               => 'ការរាប់បញ្ចូល',
'isimage'                  => 'តំណភ្ជាប់ទៅរូបភាព',
'whatlinkshere-prev'       => '{{PLURAL:$1|មុន|មុន $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|បន្ទាប់|បន្ទាប់ $1}}',
'whatlinkshere-links'      => '← តំណភ្ជាប់',
'whatlinkshere-hideredirs' => '$1ការបញ្ជូនបន្ត',
'whatlinkshere-hidetrans'  => '$1 ការរាប់បញ្ចូល',
'whatlinkshere-hidelinks'  => '$1តំណភ្ជាប់',
'whatlinkshere-hideimages' => '$1តំណភ្ជាប់រូបភាព',
'whatlinkshere-filters'    => 'តម្រងការពារនានា',

# Block/unblock
'blockip'                      => 'ហាមឃាត់អ្នកប្រើប្រាស់',
'blockip-legend'               => 'ហាមឃាត់អ្នកប្រើប្រាស់',
'blockiptext'                  => 'សូម​ប្រើប្រាស់​សំណុំ​បែបបទ​ខាងក្រោម​ដើម្បី​រាំងខ្ទប់​ការសរសេរ​ពី​អាសយដ្ឋាន IP ឬ​ឈ្មោះ​អ្នកប្រើប្រាស់ កំណត់​មួយ​។
ការ​ធ្វើ​បែបនេះ​គួរតែ​ធ្វើឡើង​ក្នុង​គោលបំណង​បង្ការ​ការប៉ុនប៉ង​បំផ្លាញ(vandalism)ដូច​ដែល​មាន​ចែង​ក្នុង[[{{MediaWiki:Policy-url}}|គោលការណ៍]]។
សូមបំពេញមូលហេតុច្បាស់លាស់មួយខាងក្រោម (ឧទាហរណ៍៖ រាយឈ្មោះទំព័រនានាដែលត្រូវបានគេបំផ្លាញ)។',
'ipaddress'                    => 'អាសយដ្ឋាន IP ៖',
'ipadressorusername'           => 'អាសយដ្ឋាន IP ឬឈ្មោះអ្នកប្រើ៖',
'ipbexpiry'                    => 'រយៈពេលផុតកំណត់៖',
'ipbreason'                    => 'មូលហេតុ៖',
'ipbreasonotherlist'           => 'មូលហេតុផ្សេងទៀត',
'ipbreason-dropdown'           => '*មូលហេតុហាមឃាត់ជាទូទៅ
** ដាក់បញ្ចូលព័ត៌មានមិនពិត
** ដកខ្លឹមទាំងស្រុងពីទំព័រ
** Spamming links to external sites
** Inserting nonsense/gibberish into pages
** Intimidating behaviour/harassment
** Abusing multiple accounts
** ប្រើប្រាស់ឈ្មោះដែលមិនអាចទទួលយកបាន',
'ipbanononly'                  => 'ហាមឃាត់តែអ្នកប្រើប្រាស់ជាអនាមិកជនប៉ុណ្ណោះ',
'ipbcreateaccount'             => 'ការពារការបង្កើតគណនី',
'ipbemailban'                  => 'ការពារអ្នកប្រើប្រាស់ពីការផ្ញើរអ៊ីមែល',
'ipbsubmit'                    => 'ហាមឃាត់អ្នកប្រើប្រាស់នេះ',
'ipbother'                     => 'រយៈពេលផ្សេងទៀត៖',
'ipboptions'                   => '២ម៉ោង:2 hours,១ថ្ងៃ:1 day,៣ថ្ងៃ:3 days,១សប្តាហ៍:1 week,២សប្តាហ៍:2 weeks,១ខែ:1 month,៣ខែ:3 months,៦ខែ:6 months,១ឆ្នាំ:1 year,គ្មានកំណត់:infinite',
'ipbotheroption'               => 'ផ្សេងៗទៀត',
'ipbotherreason'               => 'មូលហេតុ(ផ្សេងទៀតឬបន្ថែម)៖',
'ipbhidename'                  => 'លាក់​ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់​ពី​កំណែ​ប្រែ​នឹង​បញ្ជី​',
'ipbwatchuser'                 => 'តាមដានទំព័រអ្នកប្រើប្រាស់និងទំព័រពិភាក្សារបស់អ្នកប្រើប្រាស់នេះ។',
'ipballowusertalk'             => 'អនុញ្ញាតឱ្យអ្នកប្រើប្រាស់នេះ​កែប្រែទំព័រពិភាក្សារបស់ខ្លួន ទោះជាត្រូវបានរាំងខ្ទប់ក៏ដោយ',
'badipaddress'                 => 'អាសយដ្ឋានIPមិនត្រឹមត្រូវ',
'blockipsuccesssub'            => 'បានហាមឃាត់ដោយជោគជ័យ',
'ipb-edit-dropdown'            => 'កែប្រែ ហេតុផល រាំងខ្ទប់',
'ipb-unblock-addr'             => 'ឈប់ហាមឃាត់$1',
'ipb-unblock'                  => 'លែងរាំងខ្ទប់ អ្នកប្រើប្រាស់ ឬ អាសយដ្ឋាន IP',
'ipb-blocklist-addr'           => 'ការរាំងខ្ទប់ដែលមានស្រេចសម្រាប់$1',
'ipb-blocklist'                => 'មើលការហាមឃាត់ដែលមានស្រេច',
'ipb-blocklist-contribs'       => 'ការរួមចំណែកសម្រាប់ $1',
'unblockip'                    => 'ឈប់ហាមឃាត់អ្នកប្រើប្រាស់',
'unblockiptext'                => 'សូម​ប្រើប្រាស់​ទម្រង់​បែបបទ​ខាងក្រោម​នេះ ដើម្បី​បើក​សិទ្ឋិ​សរសេរ​ឡើងវិញ សម្រាប់​អាសយដ្ឋាន​IP​ឬ​អ្នកប្រើប្រាស់​ដែល​ត្រូវ​បាន​រាំងខ្ទប់​ពីមុន​។',
'ipusubmit'                    => 'ដក​ការរាំងខ្ទប់​នេះ​ចេញ',
'unblocked'                    => '[[User:$1|$1]] ត្រូវបានឈប់ហាមឃាត់',
'unblocked-id'                 => '$1 ត្រូវបានឈប់ហាមឃាត់ហើយ',
'ipblocklist'                  => 'ឈ្មោះអ្នកប្រើប្រាស់ និង អាសយដ្ឋាន IP ដែលត្រូវបានរាំងខ្ទប់',
'ipblocklist-legend'           => 'រកមើល អ្នកប្រើប្រាស់ ដែលត្រូវបានរាំងខ្ទប់',
'ipblocklist-username'         => 'ឈ្មោះអ្នកប្រើឬអាសយដ្ឋានIP៖',
'ipblocklist-sh-userblocks'    => '$1 ការរាំងខ្ទប់គណនី',
'ipblocklist-sh-tempblocks'    => '$1 ការរាំងខ្ទប់បណ្ដោះអាសន្ន',
'ipblocklist-sh-addressblocks' => '$1 ការរាំងខ្ទប់ IP តែមួយ',
'ipblocklist-submit'           => 'ស្វែងរក',
'blocklistline'                => '$1, $2 បានហាមឃាត់ $3 (រយៈពេល$4)',
'infiniteblock'                => 'គ្មានកំណត់',
'expiringblock'                => 'ផុតកំណត់ នៅថ្ងៃ $1 ម៉ោង $2',
'anononlyblock'                => 'សម្រាប់តែអនាមិកជនប៉ុណ្ណោះ',
'noautoblockblock'             => 'ការហាមឃាត់ដោយស្វ័យប្រវត្តិមិនត្រូវបានអនុញ្ញាតទេ',
'createaccountblock'           => 'ការបង្កើតគណនីត្រូវបានហាមឃាត់',
'emailblock'                   => 'អ៊ីមែលដែលត្រូវបានហាមឃាត់',
'blocklist-nousertalk'         => 'មិនអាចកែប្រែទំព័រពិភាក្សាខ្លួនឯងទេ',
'ipblocklist-empty'            => 'បញ្ជីរហាមឃាត់គឺទទេ។',
'ipblocklist-no-results'       => 'អាសយដ្ឋានIPឬឈ្មោះអ្នកប្រើដែលបានស្នើសុំគឺមិនត្រូវបានរារាំងទេ។',
'blocklink'                    => 'ហាមឃាត់',
'unblocklink'                  => 'ឈប់រាំងខ្ទប់',
'change-blocklink'             => 'ផ្លាស់ប្ដូរការរាំងខ្ទប់',
'contribslink'                 => 'ការរួមចំណែក',
'autoblocker'                  => 'អ្នកបានត្រូវបានហាមឃាត់ដោយស្វ័យប្រវត្តិ ពីព្រោះអាសយដ្ឋានIPរបស់អ្នកត្រូវបានប្រើប្រាស់ដោយ"[[User:$1|$1]]"។ មូលហេតុលើកឡើងចំពោះការហាមឃាត់$1គឺ៖ "$2"',
'blocklogpage'                 => 'កំណត់ហេតុនៃការហាមឃាត់',
'blocklog-fulllog'             => 'កំណត់ហេតុនៃការហាមឃាត់ពេញលេញ',
'blocklogentry'                => 'បានហាមឃាត់ [[$1]]​ដោយរយៈពេលផុតកំណត់$2 $3',
'blocklogtext'                 => 'នេះជាកំណត់ហេតុនៃការហាមឃាត់និងឈប់ហាមឃាត់អ្នកប្រើប្រាស់។ អាសយដ្ឋានIPដែលត្រូវបានហាមឃាត់ដោយស្វ័យប្រវត្តិមិនត្រូវបានដាក់ក្នុងបញ្ជីនេះទេ។ សូមមើល[[Special:IPBlockList|បញ្ជីនៃការហាមឃាត់IP]]ចំពោះបញ្ជីនៃហាមឃាត់នាថ្មីៗ។',
'unblocklogentry'              => 'បានឈប់ហាមឃាត់ $1',
'block-log-flags-anononly'     => 'សម្រាប់​តែ​អ្នកប្រើប្រាស់​អនាមិក​ប៉ុណ្ណោះ',
'block-log-flags-nocreate'     => 'ការបង្កើតគណនីត្រូវបានហាមឃាត់',
'block-log-flags-noautoblock'  => 'ការហាមឃាត់ដោយស្វ័យប្រវត្តិមិនត្រូវបានអនុញ្ញាតទេ',
'block-log-flags-noemail'      => 'អ៊ីមែលត្រូវបានហាមឃាត់',
'block-log-flags-nousertalk'   => 'មិនអាចកែប្រែទំព័រពិភាក្សាផ្ទាល់ខ្លួនទេ',
'block-log-flags-hiddenname'   => 'លាក់ឈ្មោះអ្នកប្រើប្រាស់',
'ipb_expiry_invalid'           => 'កាលបរិច្ឆេទផុតកំណត់មិនត្រឹមត្រូវទេ។',
'ipb_already_blocked'          => '"$1"ត្រូវបានរាំងខ្ទប់ហើយ',
'ipb-needreblock'              => '== ទប់ស្កាត់​រួចរាល់ ==
$1 ត្រូវ​បាន​ទប់ស្កាត់​ហើយ​។ តើ​អ្នក​ចង់​ធ្វើការ​ផ្លាស់ប្ដូរ​ការកំណត់​?',
'ipb_cant_unblock'             => 'កំហុស៖ រកមិនឃើញ ID រាំងខ្ទប់ $1 ។

វាប្រហែលជាត្រូវបានគេឈប់រាំងខ្ទប់ហើយ។',
'ip_range_invalid'             => 'ដែនកំណត់ IP គ្មានសុពលភាព។',
'blockme'                      => 'ហាមឃាត់ខ្ញុំ',
'proxyblocker'                 => 'កម្ម​វិធី​​រាំង​ផ្ទប់​ប្រូកស៊ី (Proxy)',
'proxyblocker-disabled'        => 'មុខងារនេះត្រូវបានអសកម្ម។',
'proxyblockreason'             => 'អាសយដ្ឋាន IP របស់អ្នកត្រូវបានរាំងខ្ទប់ហើយ ពីព្រោះវាជាប្រុកស៊ី(proxy)ចំហ។

សូមទំនាក់ទំនងអ្នកផ្ដល់សេវាអ៊ីនធឺណិតឬអ្នកបច្ចេកទេសរបស់អ្នក ហើយប្រាប់ពួកគេពីបញ្ហាសុវត្ថិភាពដ៏សំខាន់នេះ។',
'proxyblocksuccess'            => 'រួចរាល់ជាស្ថាពរ។',
'sorbsreason'                  => 'អាសយដ្ឋាន IP របស់អ្នកមានឈ្មោះក្នុងបញ្ជីប្រុកស៊ី(proxy)ចំហ នៅក្នុង DNSBL របស់ {{SITENAME}}។',
'sorbs_create_account_reason'  => 'អាសយដ្ឋាន IP របស់អ្នកមានឈ្មោះក្នុងបញ្ជីប្រុកស៊ី(proxy)ចំហ នៅក្នុង DNSBL របស់ {{SITENAME}}។

អ្នកមិនអាចបង្កើតគណនីបានទេ',
'cant-block-while-blocked'     => 'អ្នកមិនអាចរាំងខ្ទប់អ្នកប្រើប្រាស់ដទៃបានទេ ព្រោះអ្នកក៏ត្រូវបានរាំងខ្ទប់ដែរ។',

# Developer tools
'lockdb'              => 'ចាក់សោមូលដ្ឋានទិន្នន័យ',
'unlockdb'            => 'ដោះសោមូលដ្ឋានទិន្នន័យ',
'lockdbtext'          => 'ការ​ចាក់សោ​មូលដ្ឋាន​ទិន្នន័យ​នឹង​ផ្អាក​មិន​ឱ្យ​អ្នកប្រើប្រាស់​ទាំងអស់​ធ្វើការ​កែប្រែ​ទំព័រ​នានា ផ្លាស់ប្ដូរ​ចំណូលចិត្ត​របស់​ពួកគេ កែប្រែ​បញ្ជីតាមដាន​របស់​ពួកគេ និង​ធ្វើ​អ្វីៗ​ទាំងឡាយ​ណា​ដែល​ត្រូវការ​ការ​កែប្រែ​នៅក្នុង​មូលដ្ឋាន​ទិន្នន័យ​នេះ​។

សូម​អះអាង​ថា​នេះ​ពិតជា​អ្វី​ដែល​អ្នក​ចង់​ធ្វើ ហើយ​ថា​អ្នក​នឹង​ដោះ​សោ​មូលដ្ឋាន​ទិន្នន័យ​វិញ​នៅ​ពេល​ដែល​ការថែទាំ​របស់​អ្នក​បាន​បញ្ចប់​។',
'unlockdbtext'        => 'ការ​ដោះ​សោ​មូលដ្ឋាន​ទិន្នន័យ​នឹង​ផ្ដល់​លទ្ធភាព​ឱ្យ​អ្នកប្រើប្រាស់​ទាំងអស់​ធ្វើការ​កែប្រែ​ទំព័រ​នានា ផ្លាស់ប្ដូរ​ចំណូលចិត្ត​របស់​ពួកគេ កែប្រែ​បញ្ជីតាមដាន​របស់​ពួកគេ និង​ធ្វើ​អ្វីៗទាំងឡាយ​ណា​ដែល​ត្រូវការ​ការ​កែប្រែ​នៅក្នុង​មូលដ្ឋាន​ទិន្នន័យនេះ​។

សូម​អះអាង​ថា​នេះ​ពិតជា​អ្វី​ដែល​អ្នក​ចង់​ធ្វើ​។',
'lockconfirm'         => 'បាទ/ចាស, ខ្ញុំពិតជាចង់ចាក់សោមូលដ្ឋានទិន្នន័យមែន។',
'unlockconfirm'       => 'បាទ/ចាស, ខ្ញុំពិតជាចង់ដោះសោមូលដ្ឋានទិន្នន័យមែន។',
'lockbtn'             => 'ចាក់សោមូលដ្ឋានទិន្នន័យ',
'unlockbtn'           => 'ដោះសោមូលដ្ឋានទិន្នន័យ',
'locknoconfirm'       => 'អ្នកមិនបានពិនិត្យមើលប្រអប់បញ្ជាក់ទទួលស្គាល់ទេ។',
'lockdbsuccesssub'    => 'មូលដ្ឋានទិន្នន័យត្រូវបានចាក់សោរដោយជោគជ័យ',
'unlockdbsuccesssub'  => 'សោ មូលដ្ឋានទិន្នន័យ ត្រូវបានដកចេញ',
'lockdbsuccesstext'   => 'មូលដ្ឋានទិន្នន័យត្រូវបានចាក់សោ។<br />
កុំភ្លេច [[Special:UnlockDB|ដោះសោ]] បន្ទាប់ពីបញ្ជប់ការថែទាំរបស់អ្នក។',
'unlockdbsuccesstext' => 'មូលដ្ឋានទិន្នន័យត្រូវបានដោះសោរួចហើយ។',
'databasenotlocked'   => 'មូលដ្ឋានទិន្នន័យ មិនត្រូវបានចាក់សោ។',

# Move page
'move-page'                    => 'ប្តូរទីតាំង $1',
'move-page-legend'             => 'ប្តូរទីតាំងទំព័រ',
'movepagetext'                 => "ការប្រើប្រាស់​ទម្រង់​ខាងក្រោម​នឹង​ប្តូរ​ឈ្មោះ​ទំព័រ ប្តូរទីតាំង​គ្រប់​ប្រវត្តិ​របស់​វា​ទៅ​ឈ្មោះថ្មី​។
ចំណងជើង​ចាស់​នឹង​ក្លាយជា​ទំព័រ​ប្តូរទិសទៅ​ចំណងជើងថ្មី​។
តំណភ្ជាប់​ទៅ​ចំណងជើង នៃ​ទំព័រចាស់​នឹង​មិន​បាន​ត្រូវ​ផ្លាស់ប្តូរ; សូម​ពិនិត្យមើល ការប្តូរទិស មិនបានបង្កើត ទំព័រប្តូរទិសទ្វេ ឬ ទំព័រប្តូរទិសបាក់ ។
អ្នកត្រូវតែធានាប្រាកដ ថា​តំណភ្ជាប់ទាំងនោះ បន្តសំដៅ​ទៅ​គោលដៅបានសន្មត​។

ទំព័រចាស់ នឹង'''មិន'''ត្រូវ បានប្តូរទីតាំង កាលបើ​មានទំព័រ​ក្នុងចំណងជើងថ្មី​។ បើគ្មានទំព័រ​ក្នុងចំណងជើងថ្មី, ទំព័ចាស់​នឹង​ទទេ ឬ ជា​ទំព័រប្តូរទិស និង​គ្មានប្រវត្តិកំណែប្រែ​។ វាមានន័យថា អ្នកអាចប្តូរឈ្មោះទំព័រ​ទៅទីតាំងដើម ករណី​អ្នកបានធ្វើកំហុស, និង ដែលអ្នកមិនអាច សរសេរជាន់ពីលើ ទំព័រមានស្រាប់​។

'''ប្រយ័ត្ន!'''
វាអាចជា បំលាស់ប្តូរដល់ឫសគល់ និង​មិននឹកស្មានជាមុន ចំពោះ​ទំព័រប្រជាប្រិយ​។ អ្នកត្រូវតែ​ដឹងប្រាកដ​អំពី​ផលវិបាកទាំងអស់ មុននឹង​បន្តទង្វើនេះ​។",
'movepagetalktext'             => "ទំព័រសហពិភាក្សាបើមាន នឹងត្រូវបានប្តូរទៅឈ្មោះ​ថ្មី​ជាមួយគ្នា​ដោយ​ស្វ័យប្រវត្តិ '''លើកលែងតែ៖'''
*ទំព័រពិភាក្សាមិនទទេនិងមានរួចរាល់ក្រោមឈ្មោះថ្មី ឬ
*អ្នក​ដោះប្រអប់ធីក​ខាងក្រោម។

ក្នុង​ករណី​ទាំង​នោះ អ្នក​នឹង​ត្រូវតែ​ប្តូរឈ្មោះ​ទំព័រ​ឬ​បញ្ចូលរួមគ្នា​បើ​អ្នក​ចង់។",
'movearticle'                  => 'ប្ដូរទីតាំងទំព័រ៖',
'movenologin'                  => 'មិនបានឡុកអ៊ីន',
'movenologintext'              => 'អ្នក​ត្រូវតែ​ជា​អ្នកប្រើប្រាស់​ដែល​បាន​ចុះឈ្មោះ ហើយបាន [[Special:UserLogin|ឡុកអ៊ីន]] ដើម្បីប្ដូរទីតាំងទំព័រមួយ។',
'movenotallowed'               => 'អ្នកមិនត្រូវបាន​អនុញ្ញាត​ឱ្យ​ប្តូរទីតាំងទំព័រ​ទេ។',
'movenotallowedfile'           => 'អ្នកគ្មានការអនុញ្ញាតអោយប្ដូរទីតាំងឯកសារនានាទេ។',
'cant-move-user-page'          => 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យប្ដូរទីតាំងទំព័រអ្នកប្រើប្រាស់នានា(ដាច់ពីអនុទំព័ររបស់វា)ទេ។',
'cant-move-to-user-page'       => 'អ្នកគ្មានការអនុញ្ញាតអោយប្ដូរទីតាំងទំព័រមួយទៅកាន់ទំព័រអ្នកប្រើប្រាស់មួយទេ (លើកលែងតែទៅកាន់ទំព័ររងមួយ)។',
'newtitle'                     => 'ទៅចំណងជើងថ្មី៖',
'move-watch'                   => 'តាមដានទំព័រនេះ',
'movepagebtn'                  => 'ប្ដូរទីតាំង',
'pagemovedsub'                 => 'ប្ដូរទីតាំងដោយជោគជ័យ',
'movepage-moved'               => '<big>\'\'\'"$1"ត្រូវបានប្តូរទីតាំងទៅ"$2"\'\'\'ហើយ</big>',
'movepage-moved-redirect'      => 'ការបញ្ជូនបន្តត្រូវបានបង្កើត។',
'movepage-moved-noredirect'    => 'ការបង្កើតតំនបញ្ជូនបន្តត្រូវបានលុបចោល។',
'articleexists'                => 'ទំព័រដែលមានឈ្មោះបែបនេះមានរួចហើយ ឬ ឈ្មោះដែលអ្នកបានជ្រើសរើសមិនត្រឹមត្រូវ។
សូមជ្រើសរើសឈ្មោះមួយផ្សេងទៀត។',
'cantmove-titleprotected'      => 'អ្នកមិនអាច​ប្តូទីតាំង ទំព័រ​ ទៅទីតាំងនេះ, ព្រោះ ចំណងជើងថ្មី បានត្រូវការពារ ចំពោះការបង្កើតវា',
'talkexists'                   => "'''ទំព័រ ខ្លួនវា បានត្រូវប្ដូរទីតាំង ដោយជោគជ័យ, ប៉ុន្តែ ទំព័រពិភាក្សា មិនអាចត្រូវបាន ប្ដូរទីតាំង ព្រោះ នៅមាន មួយទំព័រពិភាក្សា នៅ ចំណងជើងថ្មី  ។ សូម បញ្ចូលរួមគ្នា ពួកវា ដោយដៃ ។'''",
'movedto'                      => 'បានប្ដូរទីតាំងទៅ',
'movetalk'                     => 'ប្ដូរទីតាំងទំព័រសហពិភាក្សា',
'move-subpages'                => 'ប្តូរទីតាំង​គ្រប់​ទំព័ររង (ទៅ $1)',
'move-talk-subpages'           => 'ប្តូរទីតាំង​គ្រប់​ទំព័ររង​នៃ​ទំព័រ​ពិភាក្សា (ទៅ $1)',
'movepage-page-exists'         => 'ទំព័រ $1 មាន​រួច​ជា​ស្រេច​ហើយ​និង​មិន​អាច​សរសេរ​ជាន់​ពី​លើ​ដោយ​ស្វ័យប្រវត្តិ​បាន​ទេ​។',
'movepage-page-moved'          => 'ទំព័រ$1ត្រូវបានប្តូរទីតាំងទៅកាន់$2ហើយ។',
'movepage-page-unmoved'        => 'ទំព័រ$1មិនអាចប្តូរទីតាំងទៅ$2បានទេ។',
'1movedto2'                    => 'បានប្ដូរទីតាំង [[$1]] ទៅ [[$2]]',
'1movedto2_redir'              => 'ទំព័រ [[$1]] ត្រូវបានប្តូរទីតាំងទៅ [[$2]] តាមរយៈការបញ្ជូនបន្ត។',
'move-redirect-suppressed'     => 'តំនបញ្ជូនបន្តត្រូវបានលុបចោល',
'movelogpage'                  => 'កំណត់ហេតុនៃការប្ដូរទីតាំង',
'movelogpagetext'              => 'ខាងក្រោមនេះជាបញ្ជីនៃទំព័រដែលត្រូវបានប្តូរទីតាំង។',
'movesubpage'                  => '{{PLURAL:$1|ទំព័ររង|ទំព័ររង}}',
'movesubpagetext'              => 'ទំព័រនេះមាន$1{{PLURAL:$1|ទំព័ររង|ទំព័ររង}}ដូចខាងក្រោម',
'movenosubpage'                => 'ទំព័រនេះគ្មានទំព័ររងទេ។',
'movereason'                   => 'មូលហេតុ៖',
'revertmove'                   => 'ត្រឡប់',
'delete_and_move'              => 'លុបនិងប្តូរទីតាំង',
'delete_and_move_text'         => '==ការលុបជាចាំបាច់==
"[[:$1]]"ដែលជាទីតាំងទំព័រត្រូវបញ្ជូនទៅ មានរួចជាស្រេចហើយ។
តើអ្នកចង់លុបវាដើម្បីជាវិធីសម្រាប់ប្តូរទីតាំងទេ?',
'delete_and_move_confirm'      => 'យល់ព្រម​លុប​ទំព័រ​នេះ',
'delete_and_move_reason'       => 'បានលុបដើម្បីផ្លាស់ប្តូរទីតាំង',
'selfmove'                     => 'ចំណងជើងប្រភពនិងចំណងជើងគោលដៅគឺតែមួយ។

មិនអាចប្ដូរទីតាំងទំព័រមួយទៅលើខ្លួនវាបានទេ។',
'immobile-source-namespace'    => 'មិនអាចប្តូរទីតាំងទំព័រក្នុងលំហឈ្មោះ "$1" បានទេ',
'immobile-target-namespace'    => 'មិនអាចប្តូរទីតាំងទំព័រទៅលំហឈ្មោះ "$1" បានទេ',
'immobile-source-page'         => 'ទំព័រនេះមិនអាចប្ដូរទីតាំងបានទេ។',
'immobile-target-page'         => 'មិនអាចប្ដូរទីតាំងទៅកាន់ចំណងជើងគោលដៅនោះបានទេ។',
'imagenocrossnamespace'        => 'មិន​អាច​ផ្លាស់​ទី​តាំង​ឯកសារ​ទៅ​កាន់​លំហ​ឈ្មោះ​ដែល​មិន​មែន​ជា​ឯកសារ​',
'imageinvalidfilename'         => 'ឈ្មោះឯកសារគោលដៅមិនត្រឹមត្រូវ',
'fix-double-redirects'         => 'បន្ទាន់សម័យនូវរាល់ការបញ្ជូនបន្តដែលសំដៅទៅរកចំណងជើងដើម',
'move-leave-redirect'          => 'បន្សល់ទុកតំនបញ្ជូនបន្តនៅទំព័រចាស់',
'protectedpagemovewarning'     => "'''ប្រយ័ត្ន៖''' ទំព័រនេះ​ត្រូវបានចាក់សោ។ ដូច្នេះ​មានតែ​អ្នកប្រើប្រាស់​ដែល​មាន​អភ័យឯកសិទ្ឋិជាអភិបាលប៉ុណ្ណោះដែលអាចប្តូរទីតាំងវា។",
'semiprotectedpagemovewarning' => "'''សំគាល់៖''' ទំព័រនេះ​បានត្រូវ​ចាក់សោ។ ដូច្នេះ​មានតែអ្នកប្រើប្រាស់​ដែលបានចុះឈ្មោះ​ប៉ុណ្ណោះដែលអាចប្ដូរទីតាំងវាបាន។",

# Export
'export'            => 'នាំទំព័រចេញ',
'exporttext'        => 'អ្នកអាចនាំចេញ អត្ថបទ និង ប្រវត្តិកែប្រែ នៃ​ មួយទំព័រ ឬ នៃ មួយសំណុំទំព័រ ទៅ ក្នុង ឯកសារ XML ។ ឯកសារ​ទាំងនេះ អាចត្រូវបាន នាំចេញទៅ វិគី ផ្សេង ដែលមានប្រើប្រាស់ មីឌាវិគី តាម រយះ [[Special:Import|នាំចូល ទំព័រ]]។

ដើម្បី នាំចេញ ទំព័រ, អ្នកត្រូវ បញ្ចូលចំណងជើង ក្នុងប្រអប់អត្ថបទ ខាងក្រោម, មួយចំណងជើង ក្នុងមួយបន្ទាត់, និង ជ្រើសយក កំណែ តាមបំណង របស់អ្នក (កំណែចាស់ ឬ កំណែថ្មី), រួមនឹង ប្រវត្តិ នៃ​ទំព័រ, ឬ ត្រឹមតែ កំណែបច្ចុប្បន្ន ដែលមានព័ត៌មាន អំពី កំណែប្រែ ចុងក្រោយ។

ក្នុងករណី បន្ទាប់ អ្នកអាចប្រើប្រាស់ តំណភ្ជាប់, ដូចជា [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] ចំពោះ ទំព័រ "[[{{MediaWiki:Mainpage}}]]"។',
'exportnohistory'   => "----
'''សម្គាល់​:''' ការ​នាំ​ចេញ​នូវ​ប្រវត្តិ​នៃ​ទំព័រ​តាម​រយៈ​សំនុំ​បែប​បទ​នេះ​ មិនត្រូវ​បានអនុញ្ញាត​ដោយ​មូល​ហេតុ​ប៉ះ​ពាល់​ដល់​គុណភាព​ដំឡើរ​ការ​។",
'export-submit'     => 'នាំចេញ',
'export-addcattext' => 'បន្ថែមទំព័រនានាពីចំណាត់ថ្នាក់ក្រុម៖',
'export-addcat'     => 'បន្ថែម',
'export-addnstext'  => 'បន្ថែមទំព័រនានាពីលំហឈ្មោះ៖',
'export-addns'      => 'បន្ថែម',
'export-download'   => 'រក្សាទុកជាឯកសារ',
'export-templates'  => 'រួមទាំងទំព័រគំរូ',

# Namespace 8 related
'allmessages'                   => 'សាររបស់ប្រព័ន្ធ',
'allmessagesname'               => 'ឈ្មោះ',
'allmessagesdefault'            => 'អត្ថបទលំនាំដើម',
'allmessagescurrent'            => 'អត្ថបទបច្ចុប្បន្ន',
'allmessagesnotsupportedDB'     => "ទំព័រនេះមិនអាចប្រើប្រាស់បានទេព្រោះ '''\$wgUseDatabaseMessages''' ត្រូវបានបិទមិនឱ្យប្រើ។",
'allmessages-filter-legend'     => 'តំរង',
'allmessages-filter-unmodified' => 'មិន​បានកែសម្រួល',
'allmessages-filter-all'        => 'ទាំងអស់',
'allmessages-filter-modified'   => 'បានកែសម្រួល',
'allmessages-prefix'            => 'តម្រង​តាម​បុព្វបទ​៖',
'allmessages-language'          => 'ភាសា៖',
'allmessages-filter-submit'     => 'ទៅ',

# Thumbnails
'thumbnail-more'           => 'ពង្រីក',
'filemissing'              => 'ឯកសារបាត់បង់',
'thumbnail_error'          => 'កំហុស​បង្កើត​កូនរូបភាព៖ $1',
'djvu_page_error'          => 'ទំព័រ DjVu ក្រៅដែនកំណត់',
'djvu_no_xml'              => 'មិនអាចនាំយក XML សម្រាប់ឯកសារ DjVu',
'thumbnail_invalid_params' => 'តួលេខ កូនទំព័រ គ្មានសុពលភាព',
'thumbnail_dest_directory' => 'មិនអាចបង្កើតថតឯកសារតាមគោលដៅបានទេ',
'thumbnail_image-missing'  => 'ឯកសារហាក់ដូចជាកំពុងបាត់ខ្លួន៖$1',

# Special:Import
'import'                     => 'ការនាំចូលទំព័រ',
'importinterwiki'            => 'ការនាំចូលអន្តរវិគី',
'import-interwiki-source'    => 'ប្រភព​ វិគី​/ទំព័រ​៖',
'import-interwiki-history'   => 'ចម្លង គ្រប់កំណែចាស់ នៃទំព័រនេះ',
'import-interwiki-templates' => 'រាប់​បញ្ចូល​ទំព័រគំរូ​ទាំងអស់​',
'import-interwiki-submit'    => 'នាំចូល',
'import-interwiki-namespace' => 'បញ្ជូនទំព័រទៅក្នុងលំហឈ្មោះ​៖',
'import-upload-filename'     => 'ឈ្មោះ​ឯកសារ​​៖',
'import-comment'             => 'យោបល់៖',
'importtext'                 => 'សូមនាំចេញឯកសារនេះពីវិគីប្រភពដោយប្រើប្រាស់[[Special:Export|ឧបករណ៍នាំចេញ]]។

រក្សាវាទុកទៅក្នុងកុំព្យូទ័ររបស់អ្នករួចផ្ទុកវាឡើងនៅទីនេះ។',
'importstart'                => 'កំពុងនាំចូលទំព័រ...',
'import-revision-count'      => '$1 {{PLURAL:$1|កំណែ}}',
'importnopages'              => 'មិមានទំព័រត្រូវនាំចូលទេ។',
'importfailed'               => 'ការនាំចូល ត្រូវបរាជ័យ ៖ <nowiki>$1</nowiki>',
'importunknownsource'        => 'មិនស្គាល់ ប្រភេទ នៃប្រភពនាំចូល',
'importcantopen'             => 'មិនអាចបើក ឯកសារនាំចូល',
'importbadinterwiki'         => 'តំណភ្ជាប់អន្តរវិគីមិនត្រឹមត្រូវ',
'importnotext'               => 'ទទេ ឬ គ្មានអត្ថបទ',
'importsuccess'              => 'នាំចូល ត្រូវបានបញ្ចប់!',
'importnofile'               => 'គ្មានឯកសារនាំចូល មួយណា ត្រូវបាន ផ្ទុកឡើង​។',
'importuploaderrorsize'      => 'ការផ្ទុកឡើងឯកសារនាំចូលបានបរាជ័យ។ ឯកសារនេះមានទំហំធំជាងទំហំដែលគេអនុញ្ញាតឱ្យផ្ទុកឡើង។',
'importuploaderrorpartial'   => 'ការផ្ទុកឡើងឯកសារនាំចូលបានបរាជ័យ។ ឯកសារនេះត្រូវបានផ្ទុកឡើងបានទើបតែមួយផ្នែកប៉ុណ្ណោះ។',
'importuploaderrortemp'      => 'ការផ្ទុកឡើងឯកសារនាំចូលបានបរាជ័យ។ កំពុងបាត់ថតឯកសារបណ្ដោះអាសន្នមួយ។',
'import-noarticle'           => 'គ្មានទំព័រណាមួយត្រូវនាំចូល!',
'xml-error-string'           => '$1 នៅ ជួរដេក $2, ជួរឈរ $3 (បៃ $4) ៖ $5',
'import-upload'              => 'ផ្ទុកឡើងទិន្នន័យ XML',
'import-invalid-interwiki'   => 'មិន​អាច​នាំ​ចូល​ពី​វិគី​ដែល​បាន​បញ្ជាក់​។',

# Import log
'importlogpage'                    => 'កំណត់ហេតុនៃការនាំចូល',
'import-logentry-upload'           => 'បាននាំចូល [[$1]] ដោយការផ្ទុកឡើង ឯកសារ',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|កំណែ}}',
'import-logentry-interwiki'        => 'បាននាំចូល$1ពីវិគីផ្សេងទៀត',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|កំណែ}} ពី $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ទំព័រអ្នកប្រើប្រាស់​របស់អ្នក​',
'tooltip-pt-mytalk'               => 'ទំព័រពិភាក្សា​របស់អ្នក​',
'tooltip-pt-anontalk'             => 'ការពិភាក្សាអំពីកំណែប្រែដែល​ធ្វើ​ឡើង​ចេញ​ពីអាសយដ្ឋាន IP នេះ',
'tooltip-pt-preferences'          => 'ចំណង់ចំណូលចិត្ត',
'tooltip-pt-watchlist'            => 'បញ្ជី​នៃ​ទំព័រ​ដែលអ្នកកំពុង​ត្រួតពិនិត្យ​រក​បំលាស់ប្ដូរ',
'tooltip-pt-mycontris'            => 'បញ្ជី​នៃ​ការរួមចំណែក​របស់​អ្នក',
'tooltip-pt-login'                => 'អ្នកត្រូវបានលើកទឹកចិត្តឱ្យឡុកអ៊ីន។ ប៉ុន្តែនេះមិនមែនជាការបង្ខំទេ។',
'tooltip-pt-anonlogin'            => 'អ្នកត្រូវបានលើកទឹកចិត្តឱ្យឡុកអ៊ីន មិនមែនជាការបង្ខំទេ។',
'tooltip-pt-logout'               => 'ចាកចេញ',
'tooltip-ca-talk'                 => 'ការពិភាក្សា​អំពីទំព័រខ្លឹមសារ​នេះ',
'tooltip-ca-edit'                 => "អ្នកអាច​កែប្រែ​ទំព័រ​នេះ ។ សូមប្រើប្រាស់​ប៊ូតុង 'បង្ហាញការមើលមុន' មុននឹង​រក្សាទុក​វា  ។",
'tooltip-ca-addsection'           => 'ចាប់​ផ្ដើម​វិចារថ្មី​មួយ​ទៅលើ​ការពិភាក្សានេះ',
'tooltip-ca-viewsource'           => 'ទំព័រ​នេះ​បានត្រូវការពារ ។ អ្នកអាច​មើល​អក្សរកូដ​របស់វា ។',
'tooltip-ca-history'              => 'កំណែកន្លងមក នៃ ទំព័រនេះ ។',
'tooltip-ca-protect'              => 'ការពារ​ទំព័រនេះ',
'tooltip-ca-unprotect'            => 'លែង​ការ​ពារ​ទំព័រ​នេះ​',
'tooltip-ca-delete'               => 'លុបទំព័រនេះចេញ',
'tooltip-ca-undelete'             => 'ស្ដារការកែប្រែនានាដែលត្រូវបានធ្វើចំពោះទំព័រនេះ មុនពេលដែលវាត្រូវបានគេលុបចោល',
'tooltip-ca-move'                 => 'ប្ដូរទីតាំង​ទំព័រនេះ',
'tooltip-ca-watch'                => 'បន្ថែមទំព័រនេះ​ទៅបញ្ជីតាមដាន​របស់អ្នក',
'tooltip-ca-unwatch'              => 'ដកចេញទំព័រនេះពីបញ្ជីតាមដានរបស់ខ្ញុំ',
'tooltip-search'                  => 'ស្វែងរក {{SITENAME}}',
'tooltip-search-go'               => 'ទៅកាន់ទំព័រដែលមានឈ្មោះដូចបេះបិទនឹងឈ្មោះនេះ ប្រសិនបើមាន',
'tooltip-search-fulltext'         => 'ស្វែងរកទំព័រនានាសម្រាប់ឃ្លានេះ',
'tooltip-p-logo'                  => 'ទំព័រដើម',
'tooltip-n-mainpage'              => 'ចូលមើលទំព័រដើម',
'tooltip-n-mainpage-description'  => 'ទៅកាន់​ទំព័រដើម',
'tooltip-n-portal'                => 'អំពីគម្រោង, វិធីប្រើប្រាស់ និង ការស្វែងរកព័ត៌មាន',
'tooltip-n-currentevents'         => 'រកមើលព័ត៌មានទាក់ទិននឹងព្រឹត្តិការណ៍បច្ចុប្បន្ន',
'tooltip-n-recentchanges'         => 'បញ្ជី​នៃ​បំលាស់ប្ដូរថ្មីៗ​នៅក្នុងវិគី។',
'tooltip-n-randompage'            => 'ផ្ទុក​ទំព័រចៃដន្យ​មួយទំព័រ',
'tooltip-n-help'                  => 'ជំនួយ​បន្ថែម',
'tooltip-t-whatlinkshere'         => 'បញ្ជី​ទំព័វិគី​ទាំងអស់​ដែលតភ្ជាប់​នឹងទីនេះ',
'tooltip-t-recentchangeslinked'   => 'បំលាស់ប្តូរថ្មីៗក្នុងទំព័រដែលត្រូវបានភ្ជាប់មកទំព័រនេះ',
'tooltip-feed-rss'                => 'បម្រែបម្រួល RSS ចំពោះទំព័រនេះ',
'tooltip-feed-atom'               => 'បម្រែបម្រួល Atom ចំពោះទំព័រនេះ',
'tooltip-t-contributions'         => 'បង្ហាញបញ្ជីរួមចំណែករបស់អ្នកប្រើប្រាស់នេះ',
'tooltip-t-emailuser'             => 'ផ្ញើអ៊ីមែលទៅកាន់អ្នកប្រើប្រាស់នេះ',
'tooltip-t-upload'                => 'ឯកសារផ្ទុកឡើង',
'tooltip-t-specialpages'          => 'បញ្ជីទំព័រពិសេសៗទាំងអស់',
'tooltip-t-print'                 => 'ទម្រង់សម្រាប់បោះពុម្ពចំពោះទំព័រនេះ',
'tooltip-t-permalink'             => 'តំណភ្ជាប់អចិន្ត្រៃយ៍ចំពោះកំណែនៃទំព័រនេះ',
'tooltip-ca-nstab-main'           => 'មើលទំព័រមាតិកា',
'tooltip-ca-nstab-user'           => 'មើលទំព័រអ្នកប្រើប្រាស់',
'tooltip-ca-nstab-media'          => 'មើលទំព័រមេឌា',
'tooltip-ca-nstab-special'        => 'នេះជាទំព័រពិសេស​មួយ។ អ្នកមិនអាចកែប្រែទំព័រនេះបានទេ។',
'tooltip-ca-nstab-project'        => 'មើលទំព័រគម្រោង',
'tooltip-ca-nstab-image'          => 'មើលទំព័រ​ឯកសារ',
'tooltip-ca-nstab-mediawiki'      => 'មើលសាររបស់ប្រព័ន្ធ',
'tooltip-ca-nstab-template'       => 'មើលទំព័រគំរូ',
'tooltip-ca-nstab-help'           => 'មើលទំព័រជំនួយ',
'tooltip-ca-nstab-category'       => 'មើល​ទំព័រ​ចំណាត់ថ្នាក់ក្រុម',
'tooltip-minoredit'               => 'ចំណាំ​កំណែប្រែនេះ​ថាជា កំណែប្រែ​តិចតួច',
'tooltip-save'                    => 'រក្សាបំលាស់ប្ដូររបស់អ្នកទុក',
'tooltip-preview'                 => 'មើលមុន​បំលាស់ប្ដូរ​របស់អ្នក។ សូមប្រើប្រាស់​វា​មុននឹង​រក្សាទុក!',
'tooltip-diff'                    => 'បង្ហាញ​បំលាស់ប្ដូរ​ដែលអ្នកបានធ្វើ​​ចំពោះអត្ថបទ។',
'tooltip-compareselectedversions' => 'មើលភាពខុសគ្នា​រវាងកំណែ​ដែលបានជ្រើសយកទាំង២ នៃទំព័រ​នេះ។',
'tooltip-watch'                   => 'បន្ថែម​ទំព័រនេះ​ទៅ​បញ្ជីតាមដាន​របស់អ្នក',
'tooltip-recreate'                => 'បង្កើតទំព័រនេះឡើងវិញ ទោះបីជាវាបានត្រូវលុបចេញក៏ដោយ',
'tooltip-upload'                  => 'ចាប់ផ្តើមផ្ទុកឡើងឯកសារ',
'tooltip-rollback'                => '"ត្រឡប់​ឬ Rollback" ត្រឡប់​ឡើង​វិញនូវ​កំណែ​ប្រែ​លើ​ទំព័រ​នេះ​នៃ​អ្នក​រួម​ចំណែក​ចុង​ក្រោយ​គេ​ ដោយគ្រាន់​តែ​ចុច​មួយ​ប៉ុណ្ណោះ​',
'tooltip-undo'                    => '"មិន​ធ្វើ​វិញ"​ ត្រឡប់​ឡើង​វិញនូវ​កំណែ​នេះ​និង​បើក​បែប​បទ​កំណែ​ក្នុង​ទម្រង់​មើល​ជាមុន​។
វា​អនុញ្ញាត​ឲ្យ​បន្ថែម​មូល​ហេតុ​នៅ​ក្នុង​សេចក្ដី​សង្ខេប​។',

# Stylesheets
'common.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើគ្រប់សំបកទាំងអស់ */',
'standard.css'    => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Standard */',
'nostalgia.css'   => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Nostalgia */',
'cologneblue.css' => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Cologne Blue */',
'monobook.css'    => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Monobook */',
'myskin.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Myskin */',
'chick.css'       => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Chick */',
'simple.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Simple */',
'modern.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Modern */',
'vector.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Vector */',

# Attribution
'anonymous'        => '{{PLURAL:$1|user|អ្នកប្រើប្រាស់}}អនាមិកនៃ {{SITENAME}}',
'siteuser'         => 'អ្នកប្រើប្រាស់$1នៃ{{SITENAME}}',
'lastmodifiedatby' => 'ទំព័រនេះត្រូវបានប្តូរចុងក្រោយដោយ$3នៅវេលា$2,$1។',
'othercontribs'    => 'ផ្អែកលើការងាររបស់$1។',
'others'           => 'ផ្សេងៗទៀត',
'siteusers'        => '{{PLURAL:$2|user|អ្នកប្រើប្រាស់}} $1 នៃ {{SITENAME}}',

# Spam protection
'spamprotectiontitle' => 'តម្រងការពារស្ប៉ាម(Spam)',
'spamprotectiontext'  => 'ទំព័រដែលអ្នកចង់រក្សាទុកត្រូវបានរាំងខ្ទប់ដោយតម្រងការពារស្ប៉ាម(spam)។

នេះ​ប្រហែល​ជា​មកពី​ទំព័រ​នេះ​មាន​តំណភ្ជាប់​ទៅ​សៃថ៍​ខាងក្រៅ​ដែល​មាន​ឈ្មោះ​ក្នុង​បញ្ជីខ្មៅ​។',
'spambot_username'    => 'ការសំអាតស្ប៉ាម(spam)របស់ MediaWiki',

# Info page
'infosubtitle'   => 'ព័ត៌មានសម្រាប់ទំព័រ',
'numedits'       => 'ចំនួននៃកំណែប្រែ (អត្ថបទ)៖ $1',
'numtalkedits'   => 'ចំនួននៃកំណែប្រែ (ទំព័រពិភាក្សា)៖ $1',
'numwatchers'    => 'ចំនួនអ្នកតាមដាន ៖ $1',
'numauthors'     => 'ចំនួនអ្នកនិពន្ឋ (អត្ថបទ): $1',
'numtalkauthors' => 'ចំនួនអ្នកនិពន្ធ (ទំព័រពិភាក្សា): $1',

# Skin names
'skinname-standard'    => 'បុរាណ',
'skinname-nostalgia'   => 'អាឡោះអាល័យ',
'skinname-cologneblue' => 'ទឹកអប់ខៀវ',
'skinname-monobook'    => 'សៀវភៅឯក',
'skinname-myskin'      => 'សំបកខ្ញុំ',
'skinname-chick'       => 'កូនមាន់',
'skinname-simple'      => 'សាមញ្ញ',
'skinname-modern'      => 'ទំនើប',
'skinname-vector'      => 'វ៉ិចទ័រ​​',

# Math options
'mw_math_png'    => 'ជានិច្ចការជាPNG',
'mw_math_simple' => 'ជា HTML បើសាមញ្ញបំផុត ឬ ផ្ទុយទៅវិញ ជា PNG',
'mw_math_html'   => 'ជា HTML បើអាចទៅរួច ឬ ផ្ទុយទៅវិញ ជា PNG',
'mw_math_source' => 'ទុកឱ្យនៅជា TeX (ចំពោះឧបករណ៍រាវរកអត្ថបទ)',
'mw_math_modern' => 'បានផ្តល់អនុសាសន៍ចំពោះកម្មវិធីរាវរកទំនើបៗ',
'mw_math_mathml' => 'MathML បើអាចទៅរួច (ពិសោធ)',

# Math errors
'math_failure'          => 'បរាជ័យ​ក្នុង​ការ​ញែក​ចេញ​',
'math_unknown_error'    => 'កំហុសមិនស្គាល់',
'math_unknown_function' => 'អនុគមន៍​មិន​ស្គាល់',
'math_syntax_error'     => 'កំហុសពាក្យសម្ព័ន្ធ',
'math_image_error'      => 'ការបម្លែងជា PNG បានបរាជ័យ។
សូមពិនិត្យមើលតើ latex, dvips, gs, បានដំឡើងត្រឹមត្រូវឬអត់ រួចបម្លែង',
'math_bad_tmpdir'       => 'មិនអាចសរសេរទៅ ឬ បង្កើតថតឯកសារគណិតបណ្តោះអាសន្ន',
'math_bad_output'       => 'មិនអាច សរសេរទៅ ឬ បង្កើត ថតឯកសារ គណិត ទិន្នផល',

# Patrolling
'markaspatrolleddiff'    => 'ចំណាំថាបានល្បាត',
'markaspatrolledtext'    => 'ចំណាំទំព័រនេះថាបានល្បាត',
'markedaspatrolled'      => 'បានចំណាំថាបានល្បាត',
'rcpatroldisabled'       => 'បំលាស់ប្តូរថ្មីៗនៃការតាមដានមិនត្រូវបានអនុញ្ញាតទេ',
'markedaspatrollederror' => 'មិនអាចគូសចំណាំថាបានល្បាត',

# Patrol log
'patrol-log-page'      => 'កំណត់ហេតុនៃការតាមដាន',
'patrol-log-header'    => 'នេះជាកំណត់ហេតុនៃកំណែ​ប្រែ​ដែល​បាន​តាមដាន',
'patrol-log-line'      => 'បានចំណាំការល្បាត $1 នៃ $2 ថា បានត្រួតពិនិត្យ $3',
'patrol-log-auto'      => '(ស្វ័យប្រវត្តិ)',
'patrol-log-diff'      => 'កំណែប្រែ​ $1',
'log-show-hide-patrol' => 'កំណត់ហេតុនៃការតាមដាន $1',

# Image deletion
'deletedrevision'                 => 'កំណែចាស់ដែលត្រូវបានលុបចេញ $1',
'filedeleteerror-short'           => 'កំហុសនៃការលុបឯកសារ៖ $1',
'filedeleteerror-long'            => 'កំហុសពេលលុបឯកសារចេញ៖

$1',
'filedelete-missing'              => 'មិនអាចលុប ឯកសារ "$1"  ព្រោះ វាមិនមាន។',
'filedelete-current-unregistered' => 'ឯកសារ "$1" មិនមាន ក្នុងមូលដ្ឋានទិន្នន័យ។',
'filedelete-archive-read-only'    => 'ម៉ាស៊ីនបម្រើសេវាវ៉ែប មិនអាច សរសេរទុក ថតបណ្ណសារ "$1" ។',

# Browsing diffs
'previousdiff' => '← កំណែប្រែមុននេះ',
'nextdiff'     => 'កំណែប្រែបន្ទាប់ →',

# Media information
'mediawarning'         => "'''បម្រាម''' ៖ ឯកសារនេះអាចមានផ្ទុកកូដពិសពុល កុំព្យូទ័ររបស់អ្នកអាចមានគ្រោះថ្នាក់បើឱ្យយវាមានដំណើរការ។<hr />",
'imagemaxsize'         => "កំណត់ទំហំរូបភាព៖<br />''(លើទំព័រពិពណ៌នារូបភាព)''",
'thumbsize'            => 'ទំហំកូនរូបភាព៖',
'widthheightpage'      => '$1×$2, $3{{PLURAL:$3|ទំព័រ|ទំព័រ}}',
'file-info'            => '(ទំហំឯកសារ៖ $1, ប្រភេទ MIME ៖ $2)',
'file-info-size'       => '($1 × $2 ភីកសែល ទំហំឯកសារ៖ $3 ប្រភេទ MIME៖ $4)',
'file-nohires'         => '<small>គ្មានភាពម៉ត់ ដែលខ្ពស់ជាង។</small>',
'svg-long-desc'        => '(ឯកសារប្រភេទSVG  $1 × $2 ភីកសែល ទំហំឯកសារ៖ $3)',
'show-big-image'       => 'រូបភាពពេញ',
'show-big-image-thumb' => '<small>ទំហំ​នៃការមើលជាមុននេះ៖ $1 × $2 ភីកសែល</small>',

# Special:NewFiles
'newimages'             => 'វិចិត្រសាលរូបភាពថ្មីៗ',
'imagelisttext'         => "ខាងក្រោមនេះជាបញ្ជី'''$1'''{{PLURAL:$1|ឯកសារ|ឯកសារ}}បានរៀបតាមលំដាប់$2។",
'newimages-summary'     => 'ទំព័រពិសេសនេះបង្ហាញឯកសារដែលផ្ទុកឡើងចុងក្រោយគេ។',
'newimages-legend'      => 'តម្រងការពារ',
'newimages-label'       => 'ឈ្មោះរូបភាព៖',
'showhidebots'          => '($1រូបយន្ត)',
'noimages'              => 'គ្មានឃើញអី សោះ។',
'ilsubmit'              => 'ស្វែងរក',
'bydate'                => 'តាមកាលបរិច្ឆេទ',
'sp-newimages-showfrom' => 'បង្ហាញឯកសារថ្មីៗចាប់ពី$2 $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 'វិ​.',
'minutes-abbrev' => 'និ​.',
'hours-abbrev'   => 'ម៉.',

# Bad image list
'bad_image_list' => 'ទម្រង់ ដូចតទៅ ៖

មានតែ បញ្ជីរាយមុខរបស់ (ឃ្លា ផ្តើមដោយ *) ត្រូវបាន យកជាការ ។ តំណភ្ជាប់ដំបូង នៃឃ្លា ត្រូវតែ ជាតំណភ្ជាប់ ទៅ មួយរូបភាពអន់ ។
តំណភ្ជាប់បន្ទាប់ លើឃ្លាតែមួយ ត្រូវបានយល់ថា ជា ករណីលើកលែង, ឧទាហរណ៍ ទំព័រ ដែលលើនោះ រូបភាព អាចនឹងលេចឡើង ។',

# Metadata
'metadata'          => 'ទិន្នន័យ​មេតា',
'metadata-help'     => 'ឯកសារនេះ​មាន​ព័ត៌មានបន្ថែម​ដែល​ទំនងជា​បានបន្ថែម​ពី ឧបករណ៍ថតរូបឌីជីថល ឬ ម៉ាស៊ីនស្កេន ដែលត្រូវបាន​ប្រើប្រាស់​ដើម្បីបង្កើត ឬ ធ្វើ​វា​ជា​ឌីជីថល។ បើសិនឯកសារ​បានត្រូវ​កែប្រែ​ពី ស្ថានភាពដើម នោះសេចក្តីលំអិតខ្លះ​អាចនឹងមិនអាច​​ឆ្លុះ​បញ្ចាំង​ពេញលេញទៅឯកសារ​ដែលបានកែប្រែទេ។',
'metadata-expand'   => 'បង្ហាញភាពលំអិត',
'metadata-collapse' => 'លាក់ភាពលំអិតដែលបានពន្លាត',
'metadata-fields'   => 'វាលទិន្នន័យមេតា EXIF ដែលបានរាយ​ក្នុងសារនេះ​នឹងត្រូវដាក់ក្នុង​ទំព័រ​ពិពណ៌នារូបភាព ពេល​តារាង​ទិន្នន័យមេតា​ត្រូវបានបង្រួមតូច ។ ព័ត៌មាន​ដទៃទៀត​នឹងត្រូវបាន បិទបាំង​តាមលំនាំដើម ។
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'ទទឹង',
'exif-imagelength'                 => 'កម្ពស់',
'exif-bitspersample'               => 'Bits per component',
'exif-orientation'                 => 'ទិស',
'exif-planarconfiguration'         => 'ការរៀបចំទិន្នន័យ',
'exif-xresolution'                 => 'Resolution ផ្ដេក (Horizontal resolution)',
'exif-yresolution'                 => 'Resolution បញ្ឈរ​ (Vertical resolution)',
'exif-stripoffsets'                => 'ទីតាំងទិន្នន័យរូបភាព',
'exif-jpeginterchangeformatlength' => 'ទំហំជាបៃនៃទិន្នន័យJPEG',
'exif-datetime'                    => 'កាលបរិច្ឆេទ​និង​ពេលវេលា​នៃ​ការផ្លាស់ប្តូរ​​ឯកសារ',
'exif-imagedescription'            => 'ចំណងជើងរូបភាព',
'exif-make'                        => 'ក្រុមហ៊ុនផលិតកាមេរ៉ា',
'exif-model'                       => 'ម៉ូដែលកាមេរ៉ា',
'exif-software'                    => 'ផ្នែកទន់​ត្រូវបា​ន​ប្រើប្រាស់',
'exif-artist'                      => 'អ្នកនិពន្ធ',
'exif-copyright'                   => 'ម្ចាស់កម្មសិទ្ធិ',
'exif-exifversion'                 => 'កំណែ នៃ Exif',
'exif-flashpixversion'             => 'បានគាំទ្រ កំណែ Flashpix',
'exif-colorspace'                  => 'លំហពណ៌',
'exif-compressedbitsperpixel'      => 'កម្រិតហាប់ នៃរូបភាព (ប៊ិត/ចំណុច)',
'exif-pixelydimension'             => 'ទទឹងសមស្រប នៃរូបភាព',
'exif-pixelxdimension'             => 'កម្ពស់សមស្រប នៃរូបភាព',
'exif-makernote'                   => 'កំណត់​ត្រារបស់​អ្នកផលិត',
'exif-usercomment'                 => 'យោបល់របស់អ្នកប្រើប្រាស់',
'exif-relatedsoundfile'            => 'ឯកសារសំឡេងពាក់ព័ន្ធ',
'exif-datetimeoriginal'            => 'ពេលវេលានិងកាលបរិច្ឆេទបង្កើតទិន្នន័យ',
'exif-datetimedigitized'           => 'ពេលវេលានិងការបរិច្ឆេទធ្វើជាឌីជីថល',
'exif-exposuretime-format'         => '$1វិនាទី($2)',
'exif-fnumber'                     => 'លេខ F (F Number)',
'exif-shutterspeedvalue'           => 'ល្បឿន Shutter (Shutter speed)',
'exif-aperturevalue'               => 'អាប៉ាឆឺ​(Aperture)',
'exif-brightnessvalue'             => 'ពន្លឺ',
'exif-lightsource'                 => 'ប្រភពពន្លឺ',
'exif-flash'                       => 'បញ្ចេញពន្លឺ',
'exif-focallength'                 => 'ប្រវែង​កំនុំ​ឡង់ទី',
'exif-flashenergy'                 => 'ថាមពល​បញ្ចេញពន្លឺ',
'exif-filesource'                  => 'ប្រភពឯកសារ',
'exif-scenetype'                   => 'ប្រភេទ​នៃ​ទិដ្ឋភាព​',
'exif-whitebalance'                => 'តុល្យភាពនៃ​​ពណ៌​ស​ (White Balance)',
'exif-contrast'                    => 'កម្រិតពណ៌',
'exif-saturation'                  => 'តិត្ថិភាព',
'exif-gpslatituderef'              => 'រយៈទទឹង​ខាងជើងឬខាងត្បូង',
'exif-gpslatitude'                 => 'រយៈទទឹង',
'exif-gpslongituderef'             => 'រយៈបណ្ដោយ​ខាងកើតឬខាងលិច',
'exif-gpslongitude'                => 'រយៈបណ្តោយ',
'exif-gpsaltitude'                 => 'រយៈកម្ពស់',
'exif-gpsspeedref'                 => 'ខ្នាតល្បឿន',
'exif-gpsspeed'                    => 'ល្បឿន​នៃ​ឧបករណ៍​ទទួល​​ GPS',
'exif-gpstrackref'                 => 'ឯកសារ​យោង​ ទិស​នៃ​ចលនា​',
'exif-gpstrack'                    => 'ទិស​នៃ​ចលនា​',
'exif-gpsimgdirectionref'          => 'ឯកសារ​យោង​ ទិស​នៃ​រូបភាព​',
'exif-gpsimgdirection'             => 'ទិស​នៃ​រូបភាព​',
'exif-gpsdestlatituderef'          => 'ឯកសារ​យោង​នៃ​រយៈទទឹង​នៃ​គោលដៅ​',
'exif-gpsdestlatitude'             => 'រយៈទទឹង​នៃ​គោលដៅ​',
'exif-gpsdestlongituderef'         => 'ឯកសារ​យោង​សម្រាប់​រយៈបណ្ដោយ​​នៃ​គោលដៅ​',
'exif-gpsdestlongitude'            => 'រយៈបណ្ដោយ​​នៃ​គោលដៅ​',
'exif-gpsdestdistance'             => 'ចម្ងាយ​ទៅ​គោលដៅ',
'exif-gpsareainformation'          => 'ឈ្មោះ នៃ តំបន់ GPS',
'exif-gpsdatestamp'                => 'កាលបរិច្ឆេទ GPS',

# EXIF attributes
'exif-compression-1' => 'លែងបានបង្ហាប់',

'exif-unknowndate' => 'មិនដឹងកាលបរិច្ឆេទ',

'exif-orientation-1' => 'ធម្មតា',
'exif-orientation-2' => 'ផ្កាប់​ផ្ដេក​',
'exif-orientation-3' => 'ត្រូវបាន​បង្វិល 180°',
'exif-orientation-4' => 'ផ្កាប់​បញ្ឈរ​',
'exif-orientation-5' => 'បង្វិល​ 90° បញ្ច្រាស់​ទ្រនិច​នាឡិកា​ រូច​ហើយ​ផ្កាប់​បញ្ឈរ​',
'exif-orientation-6' => 'បានបង្វិល 90° តាមទិសទ្រនិចនាឡិកា',
'exif-orientation-7' => 'បង្វិល​ 90° ស្រប​ទ្រនិច​នាឡិកា​ រូច​ហើយ​ផ្កាប់​បញ្ឈរ​',
'exif-orientation-8' => 'បានបង្វិល 90° ច្រាស់ទិសទ្រនិចនាឡិកា',

'exif-componentsconfiguration-0' => 'មិនមាន',

'exif-exposureprogram-0' => 'មិនត្រូវបានកំណត់',
'exif-exposureprogram-1' => 'ដោយដៃ',

'exif-subjectdistance-value' => '$1ម៉ែត្រ',

'exif-meteringmode-0'   => 'មិនបានស្គាល់',
'exif-meteringmode-1'   => 'មធ្យម',
'exif-meteringmode-255' => 'ផ្សេង',

'exif-lightsource-0'   => 'មិនដឹង',
'exif-lightsource-1'   => 'ពន្លឺថ្ងៃ',
'exif-lightsource-2'   => 'អំពូលម៉ែត',
'exif-lightsource-3'   => 'អំពូលតឹងស្តែន (ចង្កៀងរង្គុំ)',
'exif-lightsource-4'   => 'បញ្ចេញពន្លឺ',
'exif-lightsource-9'   => 'ធាតុអាកាសស្រឡះល្អ',
'exif-lightsource-10'  => 'អាកាស​ធាតុ​​មាន​ពពក​ច្រើន​ (Cloudy weather)',
'exif-lightsource-11'  => 'ម្លប់​',
'exif-lightsource-17'  => 'ពន្លឺ​ស្តង់​ដារ​ A',
'exif-lightsource-18'  => 'ពន្លឺ​ស្តង់​ដារ​ B',
'exif-lightsource-19'  => 'ពន្លឺ​ស្តង់​ដារ​ C',
'exif-lightsource-255' => 'ប្រភពពន្លឺដទៃ',

# Flash modes
'exif-flash-fired-0'    => 'ពន្លឺ​ហ្វេស​(Flash) អត់​ភ្លឺ​',
'exif-flash-fired-1'    => 'ពន្លឺ​ហ្វេស​(Flash) ភ្លឺ​',
'exif-flash-mode-3'     => 'ម៉ូដ​ហ្វ្លាស់​ ស្វ័យ​ប្រវត្តិ​ (auto mode)',
'exif-flash-function-1' => 'មុខ​ងារ​អត់​មាន​ពន្លឺ​ហ្វេស​(No flash function)',
'exif-flash-redeye-1'   => 'ម៉ូដ​ហ្វ្លាស់​ ​កាត់​បន្ថយ​ភ្នែក​ក្រហម​(red-eye reduction)',

'exif-focalplaneresolutionunit-2' => 'អ៊ិន្ឈ៍',

'exif-sensingmethod-1' => 'មិនត្រូវបានកំណត់',

'exif-customrendered-0' => 'ដំឡើរ​ការ​ធម្មតា​',
'exif-customrendered-1' => 'ដំឡើរ​ការ​ Custom',

'exif-whitebalance-0' => 'តុល្យភាព​ពណ៌ស​ដោយ​ស្វ័យ​ប្រវត្តិ (Auto white balance)',
'exif-whitebalance-1' => 'តុល្យភាព​ពណ៌ស​ដោយ​ខ្លួន​ឯង​ (Manual white balance)',

'exif-scenecapturetype-0' => 'ស្តង់ដារ',
'exif-scenecapturetype-1' => 'រូបផ្តេក',
'exif-scenecapturetype-2' => 'រូបបញ្ឈរ',
'exif-scenecapturetype-3' => 'ទិដ្ឋភាព​ពេលរាត្រី​',

'exif-gaincontrol-0' => 'ទទេ',

'exif-contrast-0' => 'ធម្មតា',
'exif-contrast-1' => 'ស្រទន់',
'exif-contrast-2' => 'ធ្ងន់',

'exif-saturation-0' => 'ធម្មតា',

'exif-sharpness-0' => 'ធម្មតា',
'exif-sharpness-1' => 'ស្រទន់',
'exif-sharpness-2' => 'ធ្ងន់',

'exif-subjectdistancerange-0' => 'មិនដឹង',
'exif-subjectdistancerange-1' => 'ម៉ាក្រូ',
'exif-subjectdistancerange-2' => 'បិទការមើល',
'exif-subjectdistancerange-3' => 'ទិដ្ឋភាព​ពីចម្ងាយ​',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'ខាងជើង',
'exif-gpslatitude-s' => 'ខាងត្បូង',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'ខាងកើត',
'exif-gpslongitude-w' => 'ខាងលិច',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'គីឡូម៉ែត្រក្នុងមួយម៉ោង',
'exif-gpsspeed-m' => 'ម៉ាយល៍ក្នុងមួយម៉ោង',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ខាងជើងពិត',
'exif-gpsdirection-m' => 'ខាងជើងម៉ាញេទិក',

# External editor support
'edit-externally'      => 'កែប្រែ​ឯកសារ​នេះដោយប្រើប្រាស់​កម្មវិធី​ខាងក្រៅ',
'edit-externally-help' => '(សូមមើល[http://www.mediawiki.org/wiki/Manual:External_editors ការណែនាំ​អំពី​ការ​ប្រើប្រាស់​]សម្រាប់​​ព័ត៌មាន​បន្ថែម)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ទាំងអស់',
'imagelistall'     => 'ទាំងអស់',
'watchlistall2'    => 'ទាំងអស់',
'namespacesall'    => 'ទាំងអស់',
'monthsall'        => 'ទាំងអស់',
'limitall'         => 'ទាំងអស់​',

# E-mail address confirmation
'confirmemail'             => 'បញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែល',
'confirmemail_noemail'     => 'អ្នកមិនមានអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវមួយ ដាក់នៅក្នុង[[Special:Preferences|ចំណង់ចំណូលចិត្ត]]របស់អ្នកទេ។',
'confirmemail_pending'     => 'កូដបញ្ជាក់ការទទួលស្គាល់មួយ​ត្រូវ​បាន​ផ្ញើ​ជា​អ៊ីមែល​ទៅ​អ្នក​រួច​រាល់​ហើយ​។
ប្រសិន​បើ​អ្នក​ទើប​តែ​បាន​បង្កើត​គណនី​របស់​អ្នក​ថ្មី​ៗនេះ​ទេ​ អ្នក​អាច​រង់​ចាំមួយ​ផ្លែត​សិន​ដើម្បី​ឲ្យ​វា​មក​ដល់​មុន​នឹង​​ព្យាយាម​ស្នើ​សុំ​លេខ​កូដ​ថ្មី​។',
'confirmemail_send'        => 'ផ្ញើកូដបញ្ជាក់ការទទួលស្គាល់',
'confirmemail_sent'        => 'ការបញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែលត្រូវបានផ្ញើទៅរួចហើយ។',
'confirmemail_oncreate'    => 'កូដបញ្ជាក់ការទទួលស្គាល់​មួយ​ត្រូវ​បាន​ផ្ញើ​ទៅ​កាន់​អាសយដ្ឋាន​អ៊ីមែល​របស់​អ្នក​។
កូដ​នេះ​មិន​តម្រូវ​ជា​ចាំបាច់​ក្នុង​ការ​ឡុកអ៊ីនទេ ប៉ុន្តែ​អ្នក​នឹង​ត្រូវ​ការ​វា​ក្នុង​ការ​អនុញ្ញាត​​ ឲ្យ​មាន​មុខងារទាក់ទង​នឹង​អ៊ីមែល​ផ្សេង​ៗ​ទៀត​នៅ​ក្នុង​​វិគី​។',
'confirmemail_sendfailed'  => '{{SITENAME}} មិន​អាច​ផ្ញើ​សំបុត្រ​នៃ​ការបញ្ជាក់ទទួលស្គាល់របស់​អ្នក​។
សូម​ពិនិត្យ​អាសយដ្ឋាន​អ៊ីមែល​របស់​អ្នក​ ដោយ​រក​តួ​អក្សរ​ដែល​មិន​ត្រឹម​ត្រូវ​។

Mailer បាន​ត្រឡប់​មក​វិញ៖ $1',
'confirmemail_invalid'     => 'កូដបញ្ជាក់ទទួលស្គាល់មិនត្រឹមត្រូវទេ។
កូដនេះប្រហែលជាផុតកំណត់ហើយ។',
'confirmemail_needlogin'   => 'អ្នកត្រូវការ$1ដើម្បីបញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែលរបស់អ្នក។',
'confirmemail_success'     => 'អាសយដ្ឋានអ៊ីមែលរបស់អ្នកត្រូវបានបញ្ជាក់ទទួលស្គាល់ហើយ។ ពេលនេះអ្នកអាចឡុកអ៊ីន និងចូលរួមសប្បាយរីករាយជាមួយវិគីបានហើយ។',
'confirmemail_loggedin'    => 'អាសយដ្ឋានអ៊ីមែលរបស់អ្នកត្រូវបានបញ្ជាក់ទទួលស្គាល់ហើយនាពេលនេះ។',
'confirmemail_error'       => 'រក្សាទុក ​ការបញ្ជាក់ទទួលស្គាល់ របស់អ្នក មានបញ្ហា ។',
'confirmemail_subject'     => 'ការបញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែល{{SITENAME}}',
'confirmemail_body'        => 'នរណាម្នាក់ ប្រហែលជាអ្នកពីអាសយដ្ឋានIP $1,
បានចុះបញ្ជីគណនី "$2" ជាមួយនឹងអាសយដ្ឋានអ៊ីមែលនេះនៅលើ{{SITENAME}}។

ដើម្បីបញ្ជាក់ថានេះពិតជាគណនីផ្ទាល់របស់អ្នកមែន សូមធ្វើឱ្យអ៊ីមែលរបស់អ្នកមានដំណើរការឡើងនៅលើ{{SITENAME}} ដោយបើកតំណភ្ជាប់ខាងក្រោមនេះក្នុងកម្មវិធីរុករករបស់អ្នក៖

$3

ប្រសិនបើអ្នក*មិនបាន*ចុះបញ្ជីគណនីនេះទេ សូមបើកតំណភ្ជាប់ខាងក្រោម ដើម្បីបោះបង់ចោលនូវការបញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែលនេះ៖

$5

កូដដើម្បីទទួលស្គាល់នេះនឹងផុតកំណត់នៅ  $4 ។',
'confirmemail_invalidated' => 'ការអះអាងបញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែលបានបោះបង់ចោលហើយ',
'invalidateemail'          => 'បោះបង់ចោលការបញ្ជាក់ទទួលស្គាល់អ៊ីមែល',

# Scary transclusion
'scarytranscludetoolong' => '[URL វែងជ្រុល]',

# Trackbacks
'trackbackbox'      => 'តាមដាន​ត្រឡប់​វិញ​ (Trackback) សម្រាប់​ទំព័រ​នេះ៖<br />
$1',
'trackbackremove'   => '([$1 លុបចេញ])',
'trackbacklink'     => 'តាមដាន​ត្រឡប់​វិញ​ (Trackback)',
'trackbackdeleteok' => 'តាមដាន​ត្រឡប់​វិញ​ (Trackback)ត្រូវ​បាន​លុប​ដោយ​ជោគជ័យ​។​',

# Delete conflict
'deletedwhileediting' => "'''ប្រយ័ត្ន''' ៖ ទំព័រនេះបានត្រូវលុបចោល បន្ទាប់ពីអ្នកបានចាប់ផ្តើមកែប្រែ!",
'confirmrecreate'     => "អ្នកប្រើប្រាស់ [[User:$1|$1]] ([[User talk:$1|talk]]) បានលុបទំព័រនេះចោលបន្ទាប់ពីអ្នកចាប់ផ្ដើមកែប្រែវា ដោយមានហេតុផលថា៖

៖ ''$2''

សូមអះអាងថាអ្នកពិតជាចង់បង្កើតទំព័រនេះឡើងវិញពិតប្រាកដមែន។",
'recreate'            => 'បង្កើតឡើងវិញ',

# action=purge
'confirm_purge_button' => 'យល់ព្រម',
'confirm-purge-top'    => 'សំអាតឃ្លាំងសំងាត់(cache)នៃទំព័រនេះ?',

# Multipage image navigation
'imgmultipageprev' => '← ទំព័រមុន',
'imgmultipagenext' => 'ទំព័របន្ទាប់ →',
'imgmultigo'       => 'ទៅ!',
'imgmultigoto'     => 'ទៅកាន់ទំព័រ$1',

# Table pager
'ascending_abbrev'         => 'លំដាប់ឡើង',
'descending_abbrev'        => 'លំដាប់ចុះ',
'table_pager_next'         => 'ទំព័របន្ទាប់',
'table_pager_prev'         => 'ទំព័រមុន',
'table_pager_first'        => 'ទំព័រដំបូង',
'table_pager_last'         => 'ទំព័រចុងក្រោយ',
'table_pager_limit'        => "បង្ហាញ'''$1'''ចំណុចក្នុងមួយទំព័រ",
'table_pager_limit_submit' => 'ទៅ',
'table_pager_empty'        => 'មិនមានលទ្ធផលទេ',

# Auto-summaries
'autosumm-blank'   => 'បានជំរះទំព័រ',
'autosumm-replace' => "ជំនួសខ្លឹមសារនៃទំព័រដោយ '$1'",
'autoredircomment' => 'បញ្ជូនបន្តទៅ [[$1]]',
'autosumm-new'     => 'បានបង្កើតទំព័រដែលផ្ដើមដោយ $1',

# Size units
'size-bytes'     => '$1បៃ',
'size-kilobytes' => '$1គីឡូបៃ',
'size-megabytes' => '$1មេកាបៃ',
'size-gigabytes' => '$1ជីកាបៃ',

# Live preview
'livepreview-loading' => 'កំពុងផ្ទុក…',
'livepreview-ready'   => 'កំពុងផ្ទុក… ហើយ!',
'livepreview-failed'  => 'ការមើលជាមុនដោយផ្ទាល់មិនទទួលបានជោគជ័យទេ! សូមសាកល្បងជាមួយនឹងការមើលជាមុនតាមធម្មតា។',
'livepreview-error'   => 'មិនអាចទាក់ទងទៅ៖ $1 "$2" បានទេ។

សូមសាកល្បងប្រើការមើលមុនធម្មតា។',

# Friendlier slave lag warnings
'lag-warn-normal' => 'បំលាស់ប្តូរថ្មីជាង {{PLURAL:$1|second|វិនាទី}}អាចមិនត្រូវបានបង្ហាញក្នុងបញ្ជីនេះ​។',

# Watchlist editor
'watchlistedit-numitems'       => 'បញ្ជីតាមដានរបស់អ្នកមាន{{PLURAL:$1|១ចំណងជើង|$1ចំណងជើង}}ដោយមិនរាប់បញ្ចូលទំព័រពិភាក្សាទេ។',
'watchlistedit-noitems'        => 'បញ្ជីតាមដាន របស់អ្នក គ្មានផ្ទុក ចំណងជើង។',
'watchlistedit-normal-title'   => 'កែប្រែបញ្ជីតាមដាន',
'watchlistedit-normal-legend'  => 'ដកចំណងជើងចេញពីបញ្ជីតាមដាន',
'watchlistedit-normal-explain' => 'ចំណងជើងក្នុងបញ្ជីតាមដានរបស់អ្នកត្រូវបានបង្ហាញខាងក្រោម។

ដើម្បីដកចេញនូវចំណងជើងណាមួយ សូមគូសឆែកប្រអប់ក្បែរវា រួចចុចលើដកចំណងជើងចេញ។

អ្នកអាច[[Special:Watchlist/raw|កែប្រែបញ្ជីឆៅ]]ផងដែរ។',
'watchlistedit-normal-submit'  => 'ដកចំណងជើងចេញ',
'watchlistedit-normal-done'    => '{{PLURAL:$1|១ចំណងជើង|$1ចំណងជើង}}ត្រូវបានដកចេញពីបញ្ជីតាមដានរបស់អ្នក៖',
'watchlistedit-raw-title'      => 'កែប្រែបញ្ជីតាមដានឆៅ',
'watchlistedit-raw-legend'     => 'កែប្រែបញ្ជីតាមដានឆៅ',
'watchlistedit-raw-explain'    => 'ចំណងជើង​នានា លើ​បញ្ជីតាមដាន​របស់អ្នក ត្រូវបាន​បង្ហាញខាងក្រោម, និង​អាចត្រូវបាន​កែប្រែ ដោយបន្ថែម​ទៅ ឬ ដកចេញ ពី​បញ្ជី, មួយ​ចំណងជើង​ក្នុង​មួយបន្ទាត់​។ ពេល​បានបញ្ចប់, ចុច​បន្ទាន់សម័យ​បញ្ជីតាមដាន ។
អ្នក​អាចផងដែរ [[Special:Watchlist/edit|ប្រើប្រាស់​ឧបករណ៍កែប្រែ​គំរូ]] ។',
'watchlistedit-raw-titles'     => 'ចំណងជើង៖',
'watchlistedit-raw-submit'     => 'បន្ទាន់សម័យបញ្ជីតាមដាន',
'watchlistedit-raw-done'       => 'បញ្ជីតាមដានរបស់អ្នកត្រូវបានធ្វើឱ្យទាន់សម័យហើយ។',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 title was|$1 ចំណងជើងបានត្រូវ}}ដាក់បន្ថែម៖',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|១ចំណងជើងបានត្រូវ|$1ចំណងជើងបានត្រូវ}}ដកចេញ៖',

# Watchlist editing tools
'watchlisttools-view' => 'មើលបំលាស់ប្ដូរពាក់ព័ន្ធ',
'watchlisttools-edit' => 'មើលនិងកែប្រែបញ្ជីតាមដាន',
'watchlisttools-raw'  => 'កែប្រែបញ្ជីតាមដានឆៅ',

# Core parser functions
'unknown_extension_tag' => 'ស្លាក​នៃផ្នែកបន្ថែម "$1" មិនស្គាល់',

# Special:Version
'version'                          => 'កំណែ',
'version-extensions'               => 'ផ្នែកបន្ថែមដែលបានដំឡើង',
'version-specialpages'             => 'ទំព័រពិសេសៗ',
'version-variables'                => 'អថេរ',
'version-other'                    => 'ផ្សេង',
'version-mediahandlers'            => 'កម្មវិធី​បើក​មេឌា​ (Media handlers)',
'version-extension-functions'      => 'មុខងារផ្នែកបន្ថែម',
'version-skin-extension-functions' => 'មុខងារផ្នែកបន្ថែមនៃសំបក',
'version-hook-name'                => 'ឈ្មោះ​ Hook',
'version-hook-subscribedby'        => 'បានជាវ ជាប្រចាំ ដោយ',
'version-version'                  => '(កំណែ $1)',
'version-license'                  => 'អាជ្ញាប័ណ្ណ',
'version-software'                 => 'ផ្នែកទន់​ដែល​បានដំឡើង',
'version-software-product'         => 'ផលិតផល',
'version-software-version'         => 'កំណែ',

# Special:FilePath
'filepath'         => 'ផ្លូវនៃឯកសារ',
'filepath-page'    => 'ឯកសារ៖',
'filepath-submit'  => 'ផ្លូវ',
'filepath-summary' => 'ទំព័រពិសេសនេះ បង្ហាញផ្លូវពេញលេញ នៃ មួយឯកសារ។
រូបភាពត្រូវបានបង្ហាញ ជាភាពម៉ត់ខ្ពស់, ប្រភេទឯកសារ ដទៃទៀត ធ្វើការដោយផ្ទាល់ ជាមួយ សហកម្មវិធី ។

បញ្ចូល ឈ្មោះឯកសារ ដោយគ្មានការភ្ជាប់ "{{ns:file}}:" នៅពីមុខវា ។',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'ស្វែងរកឯកសារដូចគ្នាបេះបិទ',
'fileduplicatesearch-legend'   => 'ស្វែងរកឯកសារដូចគ្នាបេះបិទ',
'fileduplicatesearch-filename' => 'ឈ្មោះឯកសារ៖',
'fileduplicatesearch-submit'   => 'ស្វែងរក',
'fileduplicatesearch-info'     => '$1 × $2 ភីកសែល<br />ទំហំឯកសារ:$3<br />ប្រភេទMIME:$4',
'fileduplicatesearch-result-1' => 'គ្មានឯកសារដែលដូចគ្នាបេះបិទទៅនឹងឯកសារ "$1" ទេ។',
'fileduplicatesearch-result-n' => 'មាន {{PLURAL:$2|1 ឯកសារដូចគ្នាបេះបិទ|$2 ឯកសារដូចគ្នាបេះបិទ}}ទៅនឹងឯកសារ "$1"។',

# Special:SpecialPages
'specialpages'                   => 'ទំព័រ​ពិសេស​ៗ',
'specialpages-note'              => '----
* ទំព័រពិសេសៗធម្មតាដែលអ្នកប្រើប្រាស់គ្រប់រូបអាចប្រើប្រាស់បាន។
* <strong class="mw-specialpagerestricted">ទំព័រពិសេសៗដែលត្រូវបានដាក់កំហិត អ្នកប្រើប្រាស់ធម្មតាមិនអាចប្រើប្រាស់បាន។</strong>',
'specialpages-group-maintenance' => 'របាយការណ៍នានាអំពីតំហែទាំ',
'specialpages-group-other'       => 'ទំព័រពិសេសៗផ្សេងៗទៀត',
'specialpages-group-login'       => 'ឡុកអ៊ីន / ចុះឈ្មោះ',
'specialpages-group-changes'     => 'បំលាស់ប្តូរថ្មីៗនិងកំណត់ហេតុ',
'specialpages-group-media'       => 'របាយការណ៍មេឌានិងការផ្ទុកឯកសារ',
'specialpages-group-users'       => 'អ្នកប្រើប្រាស់និងសិទ្ធិ',
'specialpages-group-highuse'     => 'ទំព័រដែលត្រូវបានប្រើច្រើន',
'specialpages-group-pages'       => 'បញ្ជីទំព័រនានា',
'specialpages-group-pagetools'   => 'ឧបករណ៍ទំព័រ',
'specialpages-group-wiki'        => 'ទិន្នន័យនិងឧបករណ៍វិគី',
'specialpages-group-redirects'   => 'ទំព័របញ្ជូនបន្តពិសេសៗ',
'specialpages-group-spam'        => 'ឧបករណ៍ស្ព៊ែម',

# Special:BlankPage
'blankpage'              => 'ទំព័រទទេ',
'intentionallyblankpage' => 'ទំព័រនេះត្រូវបានទុកចោលឱ្យនៅទំនេរដោយចេតនា',

# Special:Tags
'tags'                    => 'ស្លាក​បំលាស់​ប្ដូរ​ដែល​មាន​សុពលភាព​',
'tag-filter'              => '[[Special:Tags|ស្លាក​]] តម្រង​:',
'tag-filter-submit'       => 'តម្រង',
'tags-title'              => 'ស្លាក​',
'tags-intro'              => 'ទំព័រ​រាយ​នាម​ស្លាក​ទាំង​ឡាយ​ដែល​កម្មវិធី​ software អាចកត់​សម្គាល់កំណែ​ជាមួយ​ និង​អត្ថ​ន័យ​របស់​វា។​',
'tags-tag'                => 'ឈ្មោះ​ស្លាក',
'tags-display-header'     => 'Appearance លើ​បញ្ជី​បំលាស់​ប្ដូរ​',
'tags-description-header' => 'បរិយាយពេញលេញ​នៃអត្ថន័យ​',
'tags-hitcount-header'    => 'បំលាស់​ប្ដូរ​ដែលមានស្លាក​',
'tags-edit'               => 'កែប្រែ',
'tags-hitcount'           => '$1 {{PLURAL:$1|បំលាស់ប្ដូរ|បំលាស់ប្ដូរ}}',

# Database error messages
'dberr-header'      => 'វិគីនេះមានបញ្ហា',
'dberr-problems'    => 'សូមអភ័យទោស! វិបសាយនេះកំពុងជួបបញ្ហាបច្ចេកទេស។',
'dberr-again'       => 'សូមរង់ចាំប៉ុន្មាននាទីសិនហើយផ្ទុកឡើងវិញម្ដងទៀត។',
'dberr-info'        => '(មិនអាចទាក់ទងទៅប្រភពទិន្នន័យរបស់ប្រព័ន្ធបំរើការបានទេ៖ $1)',
'dberr-usegoogle'   => 'អ្នកអាចសាកស្វែងរកតាមរយៈហ្គូហ្គល(Google)ជាបណ្ដោះអាសន្នសិន។',
'dberr-outofdate'   => 'សូមចំណាំ​​ថា​ លិបិក្រម​នៃ​មាតិការ​របស់យើងប្រហែលជាហួស​សម័យ​។​',
'dberr-cachederror' => 'នេះ​គឺ​ជា​ច្បាប់​ចម្លង​ដែលបាន​ដាក់ទៅសតិភ្ជាប់នៃ​ទំព័រ​ដែលបានស្នើសុំ​ និងប្រហែលជាមិនទាន់សម័យ។',

# HTML forms
'htmlform-invalid-input'       => 'មាន​បញ្ហាខ្លះ​​​ជាមួយ​ការ​វាយ​បញ្ចូល​មួយ​ចំនួន​របស់​អ្នក​',
'htmlform-select-badoption'    => 'តំលៃលេខដែលអ្នកបានកំនត់មិនត្រឹមត្រូវទេ។',
'htmlform-int-invalid'         => 'តំលៃលេខដែលអ្នកបានកំនត់មិនមែនជាចំនួនគត់ទេ។',
'htmlform-float-invalid'       => 'តំលៃលេខដែលអ្នកបានកំនត់មិនមែនជាចំនួនទេ។',
'htmlform-int-toolow'          => 'តំលៃលេខដែលអ្នកបានកំនត់តូចជាងចំនួនអប្បបរមាដែលមានតំលៃ$1',
'htmlform-int-toohigh'         => 'តំលៃលេខដែលអ្នកបានកំនត់ធំជាងចំនួនអតិបរមាដែលមានតំលៃ$1',
'htmlform-submit'              => 'ដាក់ស្នើ',
'htmlform-reset'               => 'ធ្វើដូចដើមវិញ',
'htmlform-selectorother-other' => 'ផ្សេងទៀត',

# Add categories per AJAX
'ajax-add-category'            => 'បន្ថែម​ចំណាត់ថ្នាក់​ក្រុម​',
'ajax-add-category-submit'     => 'បន្ថែម​',
'ajax-confirm-title'           => 'បញ្ជាក់​សកម្មភាព​',
'ajax-confirm-save'            => 'រក្សាទុក',
'ajax-add-category-summary'    => 'បន្ថែម​ចំណាត់ថ្នាក់​ក្រុម​ "$1"',
'ajax-remove-category-summary' => 'ដកចេញ​​ចំណាត់ថ្នាក់​ក្រុម​ "$1"',
'ajax-confirm-actionsummary'   => 'សកម្មភាព​ត្រូវ​អនុវត្ត៖',
'ajax-error-title'             => 'កំហុស​',
'ajax-error-dismiss'           => 'យល់ព្រម​',

);
