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
 * @author Sovichet
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
	NS_CATEGORY         => 'ចំណាត់ថ្នាក់ក្រុម',
	NS_CATEGORY_TALK    => 'ការពិភាក្សាអំពីចំណាត់ថ្នាក់ក្រុម',
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
	'ចំនាត់ថ្នាក់ក្រុម' => NS_CATEGORY,
	'ការពិភាក្សាអំពីចំនាត់ថ្នាក់ក្រុម' => NS_CATEGORY_TALK,
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
	'Activeusers'               => array( 'អ្នកប្រើប្រាស់សកម្ម' ),
	'Allmessages'               => array( 'គ្រប់សារ' ),
	'Allpages'                  => array( 'គ្រប់ទំព័រ' ),
	'Ancientpages'              => array( 'ទំព័រចាស់ៗ' ),
	'Blankpage'                 => array( 'ទំព័រទទេ' ),
	'Block'                     => array( 'រាំងខ្ទប់IP' ),
	'Blockme'                   => array( 'រាំងខ្ទប់' ),
	'Booksources'               => array( 'ប្រភពសៀវភៅ' ),
	'BrokenRedirects'           => array( 'ការបញ្ជូនបន្តដែលខូច' ),
	'Categories'                => array( 'ចំណាត់ថ្នាក់ក្រុម' ),
	'ChangePassword'            => array( 'ដាក់ពាក្យសំងាត់ថ្មីឡើងវិញ' ),
	'ComparePages'              => array( 'ប្រៀបធៀបទំព័រ' ),
	'Confirmemail'              => array( 'បញ្ជាក់ទទួលស្គាល់អ៊ីមែល' ),
	'Contributions'             => array( 'ការរួមចំណែក' ),
	'CreateAccount'             => array( 'បង្កើតគណនី' ),
	'Deadendpages'              => array( 'ទំព័រទាល់' ),
	'DeletedContributions'      => array( 'ការរួមចំណែកដែលត្រូវបានលុបចោល' ),
	'Disambiguations'           => array( 'ចំណងជើងស្រដៀងគ្នា' ),
	'DoubleRedirects'           => array( 'ការបញ្ជូនបន្តទ្វេដង' ),
	'Emailuser'                 => array( 'អ្នកប្រើប្រាស់អ៊ីមែល' ),
	'Export'                    => array( 'នាំចេញ' ),
	'Fewestrevisions'           => array( 'ទំព័រមានកំណែតិចជាងគេ' ),
	'FileDuplicateSearch'       => array( 'ស្វែងរកឯកសារដូចគ្នាបេះបិទ' ),
	'Filepath'                  => array( 'ផ្លូវនៃឯកសារ' ),
	'Import'                    => array( 'នាំចូល' ),
	'Invalidateemail'           => array( 'អ៊ីមែលមិនត្រឹមត្រូវ' ),
	'BlockList'                 => array( 'បញ្ជីហាមឃាត់IP' ),
	'LinkSearch'                => array( 'ស្វែងរកតំណភ្ជាប់' ),
	'Listadmins'                => array( 'បញ្ជីអ្នកអភិបាល' ),
	'Listbots'                  => array( 'បញ្ជីរូបយន្ត' ),
	'Listfiles'                 => array( 'បញ្ជីរូបភាព' ),
	'Listgrouprights'           => array( 'បញ្ជីក្រុមសិទ្ធិ' ),
	'Listredirects'             => array( 'បញ្ជីទំព័របញ្ជូនបន្ត' ),
	'Listusers'                 => array( 'បញ្ជីឈ្មោះអ្នកប្រើប្រាស់' ),
	'Lockdb'                    => array( 'ចាក់សោមូលដ្ឋានទិន្នន័យ' ),
	'Log'                       => array( 'កំណត់ហេតុ' ),
	'Lonelypages'               => array( 'ទំព័រកំព្រា' ),
	'Longpages'                 => array( 'ទំព័រវែងៗ' ),
	'MergeHistory'              => array( 'ច្របាច់បញ្ជូលប្រវត្តិ' ),
	'MIMEsearch'                => array( 'MIMEស្វែងរក' ),
	'Mostcategories'            => array( 'ទំព័រមានចំណាត់ថ្នាក់ច្រើនជាងគេ' ),
	'Mostimages'                => array( 'ទំព័រមានរូបភាពច្រើនជាងគេ' ),
	'Mostlinked'                => array( 'ទំព័រមានតំណភ្ជាប់មកច្រើនជាងគេ' ),
	'Mostlinkedcategories'      => array( 'ចំណាត់ថ្នាក់ក្រុមមានតំណភ្ជាប់មកច្រើនជាងគេ' ),
	'Mostlinkedtemplates'       => array( 'ទំព័រគំរូមានតំណភ្ជាប់មកច្រើនជាងគេ' ),
	'Mostrevisions'             => array( 'ទំព័រមានកំណែច្រើនជាងគេ' ),
	'Movepage'                  => array( 'ប្ដូរទីតាំងទំព័រ' ),
	'Mycontributions'           => array( 'ការរួមចំណែករបស់ខ្ញុំ' ),
	'Mypage'                    => array( 'ទំព័ររបស់ខ្ញុំ' ),
	'Mytalk'                    => array( 'ការពិភាក្សារបស់ខ្ញុំ' ),
	'Myuploads'                 => array( 'អ្វីដែលខ្ញុំផ្ទុកឡើង' ),
	'Newimages'                 => array( 'រូបភាពថ្មីៗ' ),
	'Newpages'                  => array( 'ទំព័រថ្មីៗ' ),
	'PasswordReset'             => array( 'កំណត់ពាក្យសំងាត់ឡើងវិញ' ),
	'PermanentLink'             => array( 'តំណភ្ជាប់អចិន្ត្រែយ៍' ),
	'Popularpages'              => array( 'ទំព័រដែលមានប្រជាប្រិយ' ),
	'Preferences'               => array( 'ចំណង់ចំណូលចិត្ត' ),
	'Prefixindex'               => array( 'លិបិក្រមបុព្វបទ' ),
	'Protectedpages'            => array( 'ទំព័របានការពារ' ),
	'Protectedtitles'           => array( 'ចំណងជើងបានការពារ' ),
	'Randompage'                => array( 'ទំព័រចៃដន្យ' ),
	'Randomredirect'            => array( 'ការបញ្ជូនបន្តដោយចៃដន្យ' ),
	'Recentchanges'             => array( 'បំលាស់ប្ដូរថ្មីៗ' ),
	'Recentchangeslinked'       => array( 'បំលាស់ប្ដូរទាក់ទិន' ),
	'Revisiondelete'            => array( 'កំណែបានលុបចោល' ),
	'Search'                    => array( 'ស្វែងរក' ),
	'Shortpages'                => array( 'ទំព័រខ្លីៗ' ),
	'Specialpages'              => array( 'ទំព័រពិសេសៗ' ),
	'Statistics'                => array( 'ស្ថិតិ' ),
	'Tags'                      => array( 'ប្លាក' ),
	'Unblock'                   => array( 'ឈប់រាំងខ្ទប់' ),
	'Uncategorizedcategories'   => array( 'ចំណាត់ថ្នាក់ក្រុមដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ),
	'Uncategorizedimages'       => array( 'រូបភាពដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ),
	'Uncategorizedpages'        => array( 'ទំព័រដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ),
	'Uncategorizedtemplates'    => array( 'ទំព័រគំរូដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ),
	'Undelete'                  => array( 'ឈប់លុបចេញ' ),
	'Unlockdb'                  => array( 'ដោះសោមូលដ្ឋានទិន្នន័យ' ),
	'Unusedcategories'          => array( 'ចំណាត់ថ្នាក់ក្រុមដែលមិនត្រូវបានប្រើប្រាស់' ),
	'Unusedimages'              => array( 'រូបភាពដែលមិនត្រូវបានប្រើប្រាស់' ),
	'Unusedtemplates'           => array( 'ទំព័រគំរូដែលមិនត្រូវបានប្រើប្រាស់' ),
	'Unwatchedpages'            => array( 'ទំព័រលែងបានតាមដាន' ),
	'Upload'                    => array( 'ផ្ទុកឯកសារឡើង' ),
	'Userlogin'                 => array( 'ការកត់ឈ្មោះចូលរបស់អ្នកប្រើប្រាស់' ),
	'Userlogout'                => array( 'ការចាកចេញរបស់អ្នកប្រើប្រាស់' ),
	'Userrights'                => array( 'សិទ្ធិអ្នកប្រើប្រាស់' ),
	'Version'                   => array( 'កំណែ' ),
	'Wantedcategories'          => array( 'ចំណាត់ថ្នាក់ក្រុមប្រើប្រាស់ច្រើន' ),
	'Wantedfiles'               => array( 'រូបភាពប្រើប្រាស់ច្រើន' ),
	'Wantedpages'               => array( 'ទំព័រប្រើប្រាស់ច្រើន' ),
	'Wantedtemplates'           => array( 'ទំព័រគំរូប្រើប្រាស់ច្រើន' ),
	'Watchlist'                 => array( 'បញ្ជីតាមដាន' ),
	'Whatlinkshere'             => array( 'អ្វីដែលភ្ជាប់មកទីនេះ' ),
	'Withoutinterwiki'          => array( 'ដោយគ្មានអន្តរវិគី' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#បញ្ជូនបន្ត', '#ប្ដូរទីតាំងទៅ', '#ប្តូរទីតាំងទៅ', '#ប្ដូរទីតាំង', '#ប្តូរទីតាំង', '#ប្ដូរចំណងជើង', '#REDIRECT' ),
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
'tog-highlightbroken'         => 'តំណភ្ជាប់​ទៅ​ទំព័រ​មិនទាន់មាន អោយបង្ហាញ<a href="" class="new">បែបនេះ</a> (ឬ ៖ បង្ហាញជា<a href="" class="internal">?</a>)',
'tog-justify'                 => 'តំរឹម​កថាខណ្ឌ',
'tog-hideminor'               => 'លាក់​កំណែប្រែតិចតួច​ក្នុងបញ្ជីបំលាស់ប្ដូរថ្មីៗ',
'tog-hidepatrolled'           => 'លាក់​កំណែប្រែ​ដែល​បាន​ល្បាត នៅ​ក្នុង​បំលាស់ប្ដូរ​ថ្មីៗ',
'tog-newpageshidepatrolled'   => 'លាក់​ទំព័រ​ដែល​បាន​ល្បាត ពី​បញ្ជី​ទំព័រ​ថ្មី',
'tog-extendwatchlist'         => 'ពង្រីក​បញ្ជីតាមដាន​ដើម្បី​បង្ហាញ​គ្រប់​បំលាស់ប្ដូរ មិន​មែន​ត្រឹមតែ​បំលាស់ប្ដូរថ្មី​ៗ​នោះ​ទេ',
'tog-usenewrc'                => 'បង្ហាញបំលាស់ប្ដូរ​ថ្មីៗតាមរបៀបទំនើប (តម្រូវអោយ​មាន JavaScript)',
'tog-numberheadings'          => 'បង្ហាញលេខ​ចំណងជើងរង​ដោយស្វ័យប្រវត្តិ',
'tog-showtoolbar'             => 'បង្ហាញ​របារឧបករណ៍កែប្រែ (តម្រូវអោយមាន JavaScript)',
'tog-editondblclick'          => 'កែប្រែទំព័រដោយចុចពីរដង​ជាប់គ្នា (តម្រូវអោយមាន JavaScript)',
'tog-editsection'             => 'អនុញ្ញាតកែប្រែ​ផ្នែកណាមួយ​តាម​តំណភ្ជាប់[កែប្រែ]',
'tog-editsectiononrightclick' => 'អនុញ្ញាត​កែប្រែ​​ផ្នែកណាមួយ ដោយ​ចុចស្តាំកណ្តុរ​លើ​ចំណងជើង​របស់វា (តម្រូវអោយមាន JavaScript)',
'tog-showtoc'                 => 'បង្ហាញ​តារាងមាតិកា (ចំពោះទំព័រ​ដែលមាន​ចំណងជើងរង​លើសពី៣)',
'tog-rememberpassword'        => 'ចងចាំ​ការកត់ឈ្មោះចូលរបស់ខ្ញុំ​លើកុំព្យូទ័រនេះ (សំរាប់រយៈពេលយ៉ាងយូរ$1 {{PLURAL:$1|ថ្ងៃ|ថ្ងៃ}})',
'tog-watchcreations'          => 'បន្ថែម​ទំព័រ​ដែលខ្ញុំបង្កើត​ទៅ​បញ្ជីតាមដាន​របស់ខ្ញុំ',
'tog-watchdefault'            => 'បន្ថែម​ទំព័រ​ដែលខ្ញុំកែប្រែ​ទៅ​បញ្ជីតាមដាន​របស់ខ្ញុំ',
'tog-watchmoves'              => 'បន្ថែម​ទំព័រ​ដែលខ្ញុំប្តូរទីតាំង​ទៅ​បញ្ជីតាមដាន​របស់ខ្ញុំ',
'tog-watchdeletion'           => 'បន្ថែម​ទំព័រ​ដែលខ្ញុំលុបចោល​ទៅ​បញ្ជីតាមដាន​របស់ខ្ញុំ',
'tog-minordefault'            => "ចំណាំ​គ្រប់កំណែប្រែ​របស់ខ្ញុំ​ថាជា​'កំណែប្រែតិចតួច'",
'tog-previewontop'            => 'បង្ហាញ​ការមើលមុន​ពីលើ​ប្រអប់​កែប្រែ',
'tog-previewonfirst'          => 'បង្ហាញ​ការមើលមុន​ចំពោះ​កំណែប្រែ​ដំបូង',
'tog-nocache'                 => 'មិនប្រើសតិភ្ជាប់​នៃ​ទំព័រ',
'tog-enotifwatchlistpages'    => 'ផ្ញើអ៊ីមែល​មកខ្ញុំ​កាលបើ​មានបំលាស់ប្ដូរនៃទំព័រ​ណាមួយដែលមានក្នុងបញ្ជីតាមដានរបស់ខ្ញុំ',
'tog-enotifusertalkpages'     => 'ផ្ញើអ៊ីមែល​មកខ្ញុំ​កាលបើ​មានបំលាស់ប្ដូរ​នៅ​ក្នុងទំព័រពិភាក្សា​របស់ខ្ញុំ',
'tog-enotifminoredits'        => 'ផ្ញើអ៊ីមែល​មកខ្ញុំផងដែរ​ចំពោះ​បំលាស់ប្ដូរតិចតួច​',
'tog-enotifrevealaddr'        => 'បង្ហាញ​អាសយដ្ឋានអ៊ីមែល​របស់ខ្ញុំ​ក្នុង​​មែល​ក្រើនរំលឹក​នានា',
'tog-shownumberswatching'     => 'បង្ហាញ​ចំនួនអ្នកប្រើប្រាស់​ដែលតាមដាន​ទំព័រនេះ',
'tog-oldsig'                  => 'ការមើលមុននៃហត្ថលេខាដែលមាន៖',
'tog-fancysig'                => 'ចុះហត្ថលេខា​ជា​អត្ថបទវិគី​ (ដោយ​គ្មានតំណភ្ជាប់​ស្វ័យប្រវត្តិ)',
'tog-externaleditor'          => 'ប្រើប្រាស់​ឧបករណ៍​កែប្រែខាងក្រៅ​តាមលំនាំដើម (សម្រាប់តែអ្នកមានជំនាញប៉ុណ្ណោះនិងត្រូវការការកំណត់ពិសេសៗនៅលើកុំព្យូទ័ររបស់អ្នក។ [http://www.mediawiki.org/wiki/Manual:External_editors ព័ត៌មានបន្ថែម]។)',
'tog-externaldiff'            => 'ប្រើប្រាស់​ឧបករណ៍​ប្រៀបធៀបខាងក្រៅ​តាមលំនាំដើម (សម្រាប់តែអ្នកមានជំនាញប៉ុណ្ណោះនិងត្រូវការការកំណត់ពិសេសៗនៅលើកុំព្យូទ័ររបស់អ្នក។ [http://www.mediawiki.org/wiki/Manual:External_editors ព័ត៌មានបន្ថែម]។)',
'tog-showjumplinks'           => 'ប្រើតំណភ្ជាប់ "លោតទៅ"',
'tog-uselivepreview'          => 'ប្រើប្រាស់​ការមើលមុនរហ័ស​ (តម្រូវអោយមាន JavaScript) (ស្ថិតក្រោមការពិសោធន៍នៅឡើយ)',
'tog-forceeditsummary'        => 'សូមរំលឹកខ្ញុំ​កាលបើខ្ញុំទុកប្រអប់ចំណារពន្យល់ឱ្យនៅទំនេរ',
'tog-watchlisthideown'        => 'លាក់​កំណែប្រែ​របស់ខ្ញុំ​ពី​បញ្ជីតាមដាន',
'tog-watchlisthidebots'       => 'លាក់កំណែប្រែ​របស់​រូបយន្ត​ពី​បញ្ជីតាមដាន',
'tog-watchlisthideminor'      => 'លាក់​កំណែប្រែតិចតួច​ពីបញ្ជីតាមដាន',
'tog-watchlisthideliu'        => 'លាក់កំណែប្រែរបស់អ្នកប្រើប្រាស់ដែលបានកត់ឈ្មោះចូលពីបញ្ជីតាមដាន',
'tog-watchlisthideanons'      => 'លាក់កំណែប្រែរបស់អ្នកប្រើប្រាស់អនាមិកពីបញ្ជីតាមដាន',
'tog-watchlisthidepatrolled'  => 'លាក់​កំណែប្រែ​ដែល​បាន​ល្បាតពី​បញ្ជីតាមដាន',
'tog-ccmeonemails'            => 'ផ្ញើច្បាប់ចម្លង​អ៊ីមែលដែលខ្ញុំផ្ញើទៅកាន់អ្នកប្រើប្រាស់ផ្សេងទៀតមកខ្ញុំផងដែរ',
'tog-diffonly'                => 'កុំបង្ហាញខ្លឹមសារទំព័រនៅពីក្រោមតារាងប្រៀបធៀបចំនុចខុសគ្នា',
'tog-showhiddencats'          => 'បង្ហាញចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានលាក់',
'tog-norollbackdiff'          => 'បំភ្លេច​ភាព​ខុស​គ្នា​បន្ទាប់​ពី​អនុវត្តការ​ស្ដារវិញ',

'underline-always'  => 'ជានិច្ច',
'underline-never'   => 'កុំអោយសោះ',
'underline-default' => 'តាមលំនាំដើមនៃ​កម្មវិធី​រុករក​',

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
'pagecategories'                 => '{{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុម|ចំណាត់ថ្នាក់ក្រុម}}',
'category_header'                => 'ទំព័រដែលមាន​ក្នុងចំណាត់ថ្នាក់ក្រុម"$1"',
'subcategories'                  => 'ចំណាត់ថ្នាក់ក្រុមរង',
'category-media-header'          => 'ឯកសារមេឌា​ដែលមានក្នុង​ចំណាត់ថ្នាក់ក្រុម "$1"',
'category-empty'                 => "''ចំណាត់ថ្នាក់ក្រុមនេះ​មិនមានផ្ទុកអត្ថបទឬ​ឯកសារមេឌា​ណាមួយទេ។''",
'hidden-categories'              => '{{PLURAL:|ចំណាត់ថ្នាក់ក្រុមមួយដែលត្រូវបានលាក់|ចំណាត់ថ្នាក់ក្រុមចំនួន$1ដែលត្រូវបានលាក់}}',
'hidden-category-category'       => 'ចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានលាក់',
'category-subcat-count'          => '{{PLURAL:$2|ចំណាត់ថ្នាក់ក្រុមនេះមានតែចំណាត់ថ្នាក់ក្រុមរងមួយដូចខាងក្រោមទេ។|ចំណាត់ថ្នាក់ក្រុមនេះមាន{{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុមរងមួយ|ចំណាត់ថ្នាក់ក្រុមរងចំនួន$1}}ដូចខាងក្រោម ក្នុងចំណោមចំណាត់ថ្នាក់ក្រុមរងសរុបចំនួន$2។}}',
'category-subcat-count-limited'  => 'ចំណាត់ថ្នាក់ក្រុមនេះមាន {{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុមរងមួយ|ចំណាត់ថ្នាក់ក្រុមរងចំនួន$1}}ដូចខាងក្រោម។',
'category-article-count'         => '{{PLURAL:$2|ចំណាត់ថ្នាក់ក្រុមនេះមានទំព័រមួយដូចខាងក្រោម។|{{PLURAL:$1|ទំព័រមួយ|ទំព័រចំនួន$1}}ក្នុងចំណោមទំព័រសរុប $2 ដូចខាងក្រោមស្ថិតក្នុងចំណាត់ថ្នាក់ក្រុមនេះ។}}',
'category-article-count-limited' => '{{PLURAL:$1|ទំព័រ|ទំព័រចំនួន$1}}ខាងក្រោមស្ថិតនៅក្នុងចំណាត់ថ្នាក់ក្រុមនេះ។',
'category-file-count'            => '{{PLURAL:$2|ចំណាត់ថ្នាក់ក្រុមនេះមានឯកសារមួយដូចខាងក្រោម។|{{PLURAL:$1|ឯកសារមួយ|ឯកសារចំនួន$1}}ក្នុងចំណោមឯកសារសរុប $2 ដូចខាងក្រោមស្ថិតនៅក្នុងចំណាត់ថ្នាក់ក្រុមនេះ។}}',
'category-file-count-limited'    => '{{PLURAL:$1|ឯកសារមួយ|ឯកសារចំនួន$1}}ដូចខាងក្រោមស្ថិតនៅក្នុងចំណាត់ថ្នាក់ក្រុមនេះ។',
'listingcontinuesabbrev'         => 'បន្ត',
'index-category'                 => 'ទំព័រដែលបានធ្វើលិបិក្រម',
'noindex-category'               => 'ទំព័រដែលមិនបានធ្វើលិបិក្រម',
'broken-file-category'           => 'ទំព័រទាំងឡាយដែលមានតំណភ្ជាប់ខូច',

'about'         => 'អំពី',
'article'       => 'មាតិកាអត្ថបទ',
'newwindow'     => '(បើក​លើ​បង្អួច​ថ្មី)',
'cancel'        => 'បោះបង់',
'moredotdotdot' => 'បន្ថែមទៀត...',
'mypage'        => 'ទំព័រ​ខ្ញុំ',
'mytalk'        => 'ការពិភាក្សា​',
'anontalk'      => 'ទំព័រពិភាក្សាសំរាប់ IP នេះ',
'navigation'    => 'ការណែនាំ',
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
'vector-action-addsection'       => 'បន្ថែម​ប្រធានបទ​',
'vector-action-delete'           => 'លុបចោល',
'vector-action-move'             => 'ប្តូរទីតាំង',
'vector-action-protect'          => 'ការពារ',
'vector-action-undelete'         => 'ឈប់លុបចោល',
'vector-action-unprotect'        => 'ប្ដូរការការពារ',
'vector-simplesearch-preference' => 'ប្រើអនុសាសន៍ពាក្យចង់ស្វែងរក (សំរាប់តែសំបក វ៉ិចទ័រប៉ុណ្ណោះ)',
'vector-view-create'             => 'បង្កើត​',
'vector-view-edit'               => 'កែប្រែ​',
'vector-view-history'            => 'មើល​ប្រវត្តិ​',
'vector-view-view'               => 'អាន',
'vector-view-viewsource'         => 'មើល​កូដ',
'actions'                        => 'សកម្មភាព​',
'namespaces'                     => 'លំហឈ្មោះ',
'variants'                       => 'អថេរ',

'errorpagetitle'    => 'មានបញ្ហា',
'returnto'          => 'ត្រឡប់ទៅ $1 វិញ ។',
'tagline'           => 'ដោយ{{SITENAME}}',
'help'              => 'ជំនួយ',
'search'            => 'ស្វែងរក',
'searchbutton'      => 'ស្វែងរក',
'go'                => 'ទៅ',
'searcharticle'     => 'ទៅ',
'history'           => 'ប្រវត្តិទំព័រ',
'history_short'     => 'ប្រវត្តិ',
'updatedmarker'     => 'ត្រូវបានបន្ទាន់សម័យបន្ទាប់ពីពេលខ្ញុំចូលមើលចុងក្រោយ',
'printableversion'  => 'ទម្រង់​សម្រាប់បោះពុម្ភ',
'permalink'         => 'តំណភ្ជាប់អចិន្ត្រៃយ៍',
'print'             => 'បោះពុម្ភ',
'view'              => 'មើល',
'edit'              => 'កែប្រែ',
'create'            => 'បង្កើត',
'editthispage'      => 'កែប្រែទំព័រនេះ',
'create-this-page'  => 'បង្កើតទំព័រនេះ',
'delete'            => 'លុបចោល',
'deletethispage'    => 'លុបទំព័រនេះចោល',
'undelete_short'    => 'ឈប់លុប{{PLURAL:$1|កំណែប្រែមួយ|កំណែប្រែចំនួន$1}}វិញ',
'viewdeleted_short' => 'មើល⁣⁣{{PLURAL:$1|កំណែប្រែមួយត្រូវបានលុបចោល|កំណែប្រែចំនួន $1 ត្រូវបានលុបចោល}}',
'protect'           => 'ការពារ',
'protect_change'    => 'ផ្លាស់ប្តូរការការពារ',
'protectthispage'   => 'ការពារទំព័រនេះ',
'unprotect'         => 'ប្ដូរការការពារ',
'unprotectthispage' => 'ផ្លាស់ប្ដូរការការពារទំព័រនេះ',
'newpage'           => 'ទំព័រថ្មី',
'talkpage'          => 'ពិភាក្សាទំព័រនេះ',
'talkpagelinktext'  => 'ការពិភាក្សា',
'specialpage'       => 'ទំព័រពិសេស',
'personaltools'     => 'ឧបករណ៍ផ្ទាល់ខ្លួន',
'postcomment'       => 'ផ្នែកថ្មី',
'articlepage'       => 'មើលខ្លឹមសារទំព័រ​',
'talk'              => 'ការពិភាក្សា',
'views'             => 'គំហើញ',
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
'jumpto'            => 'លោតទៅ៖',
'jumptonavigation'  => 'ការណែនាំ',
'jumptosearch'      => 'ស្វែងរក',
'view-pool-error'   => 'សូមអភ័យទោស។ ប្រព័ន្ធបំរើការមានការមមាញឹកខ្លាំងពេកនៅពេលនេះ។

មានអ្នកប្រើប្រាស់ជាច្រើនកំពុងព្យាយាមចូលមើលទំព័រនេះ។

សូមរង់ចាំមួយភ្លែតសិនរួចសាកល្បងចូលមកកាន់ទំព័រនេះឡើងវិញ។

$1',
'pool-errorunknown' => 'កំហុសមិនស្គាល់',

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
'edithelppage'         => 'Help:របៀបកែប្រែ',
'helppage'             => 'Help:មាតិកា',
'mainpage'             => 'ទំព័រដើម',
'mainpage-description' => 'ទំព័រដើម',
'policy-url'           => 'Project:គោលការណ៍',
'portal'               => 'ផតថលសហគមន៍',
'portal-url'           => 'Project:​ផតថលសហគមន៍',
'privacy'              => 'គោលការណ៍​ភាពឯកជន',
'privacypage'          => 'Project:គោលការណ៍ភាពឯកជន',

'badaccess'        => 'បញ្ហាច្បាប់អនុញ្ញាត',
'badaccess-group0' => 'សកម្មភាពដែលអ្នកបានស្នើសុំត្រូវបានហាមឃាត់។',
'badaccess-groups' => 'មានតែ​អ្នកប្រើប្រាស់​ដែលជាសមាជិកក្នុង{{PLURAL:$2|ក្រុម|ក្រុម១នៃក្រុម}}៖ $1 ទេ ​ទើបមានសិទ្ធិធ្វើសកម្មភាព​ដែលអ្នកបានស្នើសុំបាន។',

'versionrequired'     => 'តម្រូវអោយមាន​កំណែ $1 នៃមេឌាវិគី',
'versionrequiredtext' => 'តម្រូវអោយមានកំណែ $1 នៃមេឌាវិគី ដើម្បីអាចប្រើប្រាស់ទំព័រនេះ។

សូមមើល [[Special:Version|ទំព័រកំណែ]]។',

'ok'                      => 'យល់ព្រម',
'pagetitle'               => '$1 - {{SITENAME}}',
'retrievedfrom'           => 'បានពី "$1"',
'youhavenewmessages'      => 'អ្នកមាន $1 ($2)។',
'newmessageslink'         => 'សារថ្មីៗ',
'newmessagesdifflink'     => 'បំលាស់ប្ដូរចុងក្រោយ',
'youhavenewmessagesmulti' => 'អ្នកមានសារថ្មីៗនៅ $1',
'editsection'             => 'កែប្រែ',
'editold'                 => 'កែប្រែ',
'viewsourceold'           => 'មើលកូដ',
'editlink'                => 'កែប្រែ',
'viewsourcelink'          => 'មើលកូដ',
'editsectionhint'         => 'កែប្រែផ្នែក៖ $1',
'toc'                     => 'មាតិកា',
'showtoc'                 => 'បង្ហាញ',
'hidetoc'                 => 'លាក់',
'collapsible-collapse'    => 'បង្រួម',
'collapsible-expand'      => 'ពន្លាត',
'thisisdeleted'           => 'មើល ឬ​ ស្ដារ $1 ឡើងវិញ?',
'viewdeleted'             => 'មើល $1?',
'restorelink'             => '{{PLURAL:$1|កំណែប្រែមួយត្រូវបានលុបចោល|កំណែប្រែចំនួន $1 ត្រូវបានលុបចោល}}',
'feedlinks'               => 'Feed​៖',
'feed-invalid'            => 'ប្រភេទfeedដែលគ្មានសុពលភាព។',
'feed-unavailable'        => 'បម្រែ​បម្រួល ​Syndication feeds មិន​ទាន់​មាន​នៅ​ឡើយ​ទេ',
'site-rss-feed'           => 'បម្រែបម្រួល RSS Feed នៃ $1',
'site-atom-feed'          => 'បម្រែបម្រួល Atom Feed នៃ $1',
'page-rss-feed'           => 'បម្រែបម្រួល RSS Feed នៃ "$1"',
'page-atom-feed'          => 'បម្រែបម្រួល Atom Feed នៃ "$1"',
'red-link-title'          => '$1 (ទំព័រនេះមិនទាន់​មាននៅឡើយទេ)',
'sort-descending'         => 'តំរៀបតាមលំដាប់ចុះ',
'sort-ascending'          => 'តំរៀបតាមលំដាប់ឡើង',

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
នេះ​ក៏​អាច​បញ្ជាក់​ប្រាប់​ពី​កំហុស​នៅ​ក្នុង​ផ្នែកទន់​ប្រើដោយ {{SITENAME}} ។',
'nosuchspecialpage' => 'មិនមានទំព័រពិសេសបែបនេះទេ',
'nospecialpagetext' => '<strong>អ្នកបានស្នើរក​ទំព័រពិសេសមិនទាន់មាន។</strong>

អ្នកអាចមើលបញ្ជី​នៃ​ទំព័រពិសេស​ៗនៅ [[Special:SpecialPages|{{int:specialpages}}]]។',

# General errors
'error'                => 'មានបញ្ហា',
'databaseerror'        => 'មូលដ្ឋានទិន្នន័យមានបញ្ហា',
'laggedslavemode'      => "'''ប្រយ័ត្ន៖''' ទំព័រនេះ​មិនអាចទុក​បំលាស់ប្ដូរ​ថ្មីៗទេ។",
'readonly'             => 'មូលដ្ឋានទិន្នន័យត្រូវបានចាក់សោ',
'enterlockreason'      => 'សូមផ្ដល់ហេតុផលសម្រាប់ការជាប់សោ ព្រមទាំងកាលបរិច្ឆេទដោះសោវិញ',
'readonlytext'         => 'ពេលនេះ​មូលដ្ឋានទិន្នន័យ​កំពុងជាប់សោ ដើម្បីកុំឱ្យមាន​ការបញ្ចូល​ទិន្នន័យ​ថ្មីៗ​ឬ​ការកែប្រែ​ផ្សេងៗ។ នេះ​ប្រហែលគ្រាន់តែជាការ​ត្រួតពិនិត្យនិងថែទាំ​មូលដ្ឋានទិន្នន័យប្រចាំថ្ងៃ ដែលជាធម្មតាវានឹងវិលមកសភាពដើមវិញ​ក្នុងពេលឆាប់ៗ។
អភិបាល​ដែលបានចាក់សោវា​បានពន្យល់ដូចតទៅ៖ $1',
'missing-article'      => 'មូលដ្ឋាន​ទិន្នន័យ​មិនបានរកឃើញ​អត្ថបទនៃទំព័រ​ដែលវាគួរតែរកឃើញ ឈ្មោះ "$1" $2 ។

នេះតែងតែកើតឡើងដោយសារតែ​ការ​តាមដានភាពខុសគ្នាដែលហួសសម័យ ឬតំណបណ្ដាញប្រវត្តិទៅទំព័រមួយដែលត្រូវបានលុបហើយ​។

ប្រសិន មិនមែនជាករណីនេះទេ អ្នកប្រហែលជាបានរកឃើញ​កំហុស​ (bug) នៅក្នុង​សូហ្វវែរ​នេះ​។
សូមធ្វើការ​រាយការណ៍ទៅកាន់​ [[Special:ListUsers/sysop|administrator]] ដើម្បីកំណត់សម្គាល់លើអាសយដ្ឋាន URL នេះ​។',
'missingarticle-rev'   => '(កំណែ#៖ $1)',
'missingarticle-diff'  => '(ភាពខុសគ្នា: $1, $2)',
'readonly_lag'         => 'មូលដ្ឋានទិន្នន័យត្រូវបានចាក់សោដោយស្វ័យប្រវត្តិ ខណៈពេលដែលម៉ាស៊ីនបម្រើ(server)មូលដ្ឋានទិន្នន័យរង​កំពុង​ទាក់ទង​ទៅម៉ាស៊ីនបម្រើ​មូលដ្ឋានទិន្នន័យមេ',
'internalerror'        => 'បញ្ហាផ្នែកខាងក្នុង',
'internalerror_info'   => 'បញ្ហាផ្នែកខាងក្នុង៖ $1',
'fileappenderrorread'  => 'មិនអាចអាន "$1" បានទេពេលកំពុងសរសេរបន្ថែម។',
'fileappenderror'      => 'មិនអាចបន្ថែម "$1" ទៅខាងចុង "$2" បានទេ។',
'filecopyerror'        => 'មិនអាចចម្លងឯកសារ"$1" ទៅ "$2"បានទេ។',
'filerenameerror'      => 'មិនអាចប្តូរឈ្មោះឯកសារពី"$1" ទៅ "$2"បានទេ។',
'filedeleteerror'      => 'មិនអាចលុបឯកសារ"$1"បានទេ។',
'directorycreateerror' => 'មិនអាចបង្កើតថត"$1"បានទេ។',
'filenotfound'         => 'រក​ឯកសារ "$1" មិនឃើញទេ។',
'fileexistserror'      => 'មិនអាចសរសេរ​ទៅក្នុង​ឯកសារ "$1"ទេ៖ ឯកសារមានរួចហើយ',
'unexpected'           => 'តម្លៃ​មិនបានរំពឹងទុក៖ "$1"="$2"។',
'formerror'            => 'បញ្ហា៖ មិនអាចដាក់ស្នើ​សំណុំបែបបទ',
'badarticleerror'      => 'សកម្មភាពនេះ​មិនអាចអនុវត្ត​លើទំព័រនេះទេ។',
'cannotdelete'         => 'មិនអាច​លុបចោលទំព័រឬឯកសារដែលមានឈ្មោះ "$1"បានទេ។

វាប្រហែលជាត្រូវបាន​នរណាម្នាក់ផ្សេងទៀតលុបចោលហើយ។',
'badtitle'             => 'ចំណងជើង​មិនល្អ',
'badtitletext'         => 'ចំណងជើងទំព័រដែលបានស្នើ គ្មានសុពលភាព, ទទេ, ឬ ចំណងជើងតំណភ្ជាប់អន្តរភាសាឬអន្តរវិគី មិនត្រឹមត្រូវ ។ ប្រហែលជាមានតួអក្សរមួយឬច្រើន ដែលជាតួអក្សរហាមប្រើ​ក្នុង​ចំណងជើង។',
'perfcached'           => 'ទិន្នន័យទាំងនេះត្រូវបានដាក់ទៅសតិភ្ជាប់និងប្រហែលជាមិនទាន់សម័យ ។',
'perfcachedts'         => 'ទិន្នន័យខាងក្រោមនេះត្រូវបានដាក់ក្នុងសតិភ្ជាប់ និង បានត្រូវបន្ទាន់សម័យចុងក្រោយនៅ $1។',
'querypage-no-updates' => 'ការបន្ទាន់សម័យសម្រាប់ទំព័រនេះគឺមិនអាចធ្វើទៅរួចទេនាពេលឥឡូវទេ។

ទិន្នន័យនៅទីនេះនឹងមិនត្រូវផ្លាស់ប្ដូរថ្មីនាពេលបច្ចុប្បន្នទេ។',
'wrong_wfQuery_params' => 'ប៉ារ៉ា​មែត្រ​មិន​ត្រឹម​ត្រូវ​ចំពោះ​ wfQuery()<br />
មុខងារ​៖ $1<br />
អង្កេត​៖ $2',
'viewsource'           => 'មើល​កូដ',
'viewsourcefor'        => 'សម្រាប់ $1',
'actionthrottled'      => 'សកម្មភាពត្រូវបានកម្រិត',
'actionthrottledtext'  => 'ក្រោមវិធានការប្រឆាំងស្ប៉ាម​ អ្នកត្រូវបាន​គេកំហិតមិនឱ្យ​ធ្វើសកម្មភាពនេះ​ច្រើនដងពេកទេ​ក្នុងរយៈពេលខ្លីមួយ។

សូមព្យាយាមម្ដងទៀតក្នុងរយៈពេលប៉ុន្មាននាទីទៀត។',
'protectedpagetext'    => 'ទំព័រនេះបានត្រូវចាក់សោមិនឱ្យកែប្រែ​។',
'viewsourcetext'       => 'អ្នកអាចមើលនិងចម្លងកូដរបស់ទំព័រនេះ៖',
'protectedinterface'   => 'ទំព័រនេះ ផ្ដល់នូវ អត្ថបទអន្តរមុខ សម្រាប់ផ្នែកទន់, និង បានត្រូវចាក់សោ ដើម្បីចៀសវាង ការបំពាន ។',
'editinginterface'     => "'''ប្រយ័ត្ន៖''' អ្នកកំពុងតែកែប្រែទំព័រដែលបានប្រើប្រាស់​ដើម្បីផ្ដល់នូវអន្តរមុខសម្រាប់ផ្នែកទន់​។ បំលាស់ប្ដូរចំពោះទំព័រនេះ​នឹងប៉ះពាល់ដល់ទំព័រអន្តរមុខនៃអ្នកប្រើប្រាស់​ជាច្រើន ដែលប្រើប្រាស់វិបសាយនេះ។ សម្រាប់ការបកប្រែ សូមពិចារណាប្រើប្រាស់ [http://translatewiki.net/wiki/Main_Page?setlang=km translatewiki.net] (បេតាវិគី) គម្រោង​អន្តរជាតូបនីយកម្ម​នៃមេឌាវិគី ។",
'sqlhidden'            => '(ការអង្កេត SQL ត្រូវបិទបាំង)',
'cascadeprotected'     => 'ទំព័រនេះត្រូវបានការពារពីការការប្រែដោយសារវាមាន{{PLURAL:$1|ទំព័រ, ដែលមាន}} ដែលត្រូវបានការពារជាមួយ"cascading" option turned on:
$2',
'namespaceprotected'   => "អ្នកមិនមានសិទ្ធិកែប្រែទំព័រក្នុងប្រភេទ'''$1'''ទេ។",
'ns-specialprotected'  => 'ទំព័រពិសេសៗមិនអាចកែប្រែបានទេ។',
'titleprotected'       => "ចំណងជើងនេះត្រូវបានការពារមិនឱ្យបង្កើត​ដោយ [[User:$1|$1]]។
ហេតុផលលើកឡើងគឺ ''$2''។",

# Virus scanner
'virus-badscanner'     => "ការ​កំណត់​រចនា​សម្ព័ន្ធ​មិន​ល្អ​៖ កម្មវិធី​ស្កេន​មេរោគមិន​ស្គាល់​៖ ''$1''",
'virus-scanfailed'     => 'ស្កេនមិនបានសំរេច (កូដ $1)',
'virus-unknownscanner' => 'កម្មវិធីប្រឆាំងមេរោគមិនស្គាល់៖',

# Login and logout pages
'logouttext'                 => "'''ឥឡូវនេះលោកអ្នកបានកត់ឈ្មោះចេញពីគណនីរបស់លោកអ្នកហើយ។'''

អ្នកអាចបន្តប្រើប្រាស់{{SITENAME}}ក្នុងភាពអនាមិក ឬ [[Special:UserLogin|កត់ឈ្មោះចូលម្ដងទៀត]]ក្នុងនាមជាអ្នកប្រើប្រាស់ដដែលឬផ្សេងទៀត។

សូមកត់សំគាល់ថាទំព័រមួយចំនួនប្រហែលជានៅតែបង្ហាញដូចពេលលោកអ្នកកត់ឈ្មោះចូលក្នុងគណនីរបស់លោកអ្នកដដែល។ ប្រសិនបើមានករណីនេះកើតឡើង សូមសំអាត សតិភ្ជាប់នៃកម្មវិធីរុករករបស់លោកអ្នក។",
'welcomecreation'            => '== សូមស្វាគមន៍ $1! ==

គណនីរបស់អ្នកត្រូវបានបង្កើតហើយ។
កុំភ្លេចផ្លាស់ប្ដូរ[[Special:Preferences|ចំណង់ចំណូលចិត្ត{{SITENAME}}]]របស់អ្នក។',
'yourname'                   => 'អត្តនាម៖',
'yourpassword'               => 'ពាក្យសំងាត់៖',
'yourpasswordagain'          => 'វាយពាក្យសំងាត់ម្តងទៀត៖',
'remembermypassword'         => 'ចងចាំកំណត់ឈ្មោះចូលរបស់ខ្ញុំក្នុងកុំព្យូទ័រនេះ (សំរាប់រយៈពេលយូរបំផុត $1 {{PLURAL:$1|ថ្ងៃ|ថ្ងៃ}})',
'securelogin-stick-https'    => 'នៅភ្ជាប់ទៅ HTTPS ដដែលបន្ទាប់ពីចុះឈ្មោះចូលហើយក៏ដោយ',
'yourdomainname'             => 'ដូម៉ែនរបស់អ្នក៖',
'externaldberror'            => 'មាន​​បញ្ហាក្នុងការ​បញ្ជាក់​ផ្ទៀង​ផ្ទាត់​​មូលដ្ឋាន​ទិន្នន័យ​ ឬ​អ្នក​មិន​ត្រូវ​បាន​អនុញ្ញាត​ឲ្យ​បន្ទាន់​សម័យ​គណនី​ខាង​ក្រៅ​របស់​អ្នក​។​
​',
'login'                      => 'កត់ឈ្មោះចូល',
'nav-login-createaccount'    => 'កត់ឈ្មោះចូលឬបង្កើតគណនី',
'loginprompt'                => 'អ្នក​ត្រូវតែ​មាន​ខូគី ដើម្បី​អាច​កត់ឈ្មោះចូល​{{SITENAME}}។',
'userlogin'                  => 'កត់ឈ្មោះចូលឬបង្កើតគណនី',
'userloginnocreate'          => 'កត់ឈ្មោះចូល',
'logout'                     => 'កត់ឈ្មោះចេញ',
'userlogout'                 => 'កត់ឈ្មោះចេញ',
'notloggedin'                => 'មិនទាន់កត់ឈ្មោះចូល',
'nologin'                    => "​បើលោកអ្នក​មិនទាន់មាន​គណនី​សម្រាប់​ប្រើ​ទេ​ សូម'''$1''' ។",
'nologinlink'                => 'បង្កើតគណនី',
'createaccount'              => 'បង្កើតគណនី',
'gotaccount'                 => "បើលោកអ្នកមានគណនីសម្រាប់ប្រើហើយ  សូម'''$1'''។",
'gotaccountlink'             => 'កត់ឈ្មោះចូល',
'userlogin-resetlink'        => 'តើអ្នកភ្លេចព័ត៌មានលំអិតសំរាប់កត់ឈ្មោះចូលហើយ?',
'createaccountmail'          => 'តាមរយៈអ៊ីមែល',
'createaccountreason'        => 'មូលហេតុ៖',
'badretype'                  => 'ពាក្យសំងាត់ដែលអ្នកបានបញ្ចូលនោះ គឺមិនស៊ីគ្នាទេ។',
'userexists'                 => 'អត្តនាមដែលអ្នកបានវាយបញ្ចូលមានគេប្រើហើយ។
សូមជ្រើសរើសអត្តនាមផ្សេងពីនេះ។',
'loginerror'                 => 'កំហុសនៃការកត់ឈ្មោះចូល',
'createaccounterror'         => 'មិនអាចបង្កើតគណនីបានទេ៖ $1',
'nocookiesnew'               => 'គណនីប្រើប្រាស់របស់អ្នកត្រូវបានបង្កើតហើយ ក៏ប៉ុន្តែអ្នកមិនទាន់បានកត់ឈ្មោះចូលទេ។

{{SITENAME}}ប្រើប្រាស់ខូឃី ដើម្បីកត់ឈ្មោះចូល។

អ្នកបានជ្រើសមិនប្រើខូឃី។

សូមជ្រើសប្រើខូឃីវិញ រួចកត់ឈ្មោះចូលដោយប្រើអត្តនាមថ្មីនិងពាក្យសំងាត់ថ្មីរបស់អ្នក។',
'nocookieslogin'             => '{{SITENAME}}ប្រើខូឃីដើម្បីកត់ឈ្មោះចូល។

អ្នកបានជ្រើសមិនប្រើខូឃី។​

សូមជ្រើសប្រើខូឃីវិញ រួចព្យាយាមម្តងទៀត។',
'noname'                     => 'អ្នកមិនបានផ្ដល់អត្តនាមត្រឹមត្រូវទេ។',
'loginsuccesstitle'          => 'កត់ឈ្មោះចូលបានសំរេច',
'loginsuccess'               => "'''ពេលនេះអ្នកបានកត់ឈ្មោះចូល{{SITENAME}}ដោយប្រើឈ្មោះ \"\$1\"។'''",
'nosuchuser'                 => 'មិនមានអ្នកប្រើដែលមានឈ្មោះ "$1" ទេ។

សូម​ពិនិត្យ​ក្រែង​លោ​មានកំហុស​អក្ខរាវិរុទ្ធឬ [[Special:UserLogin/signup|បង្កើត​គណនី​ថ្មី]]។',
'nosuchusershort'            => 'គ្មានអ្នកប្រើដែលមានឈ្មោះ $1" ទេ។

សូម​ពិនិត្យ​​អក្ខរាវិរុទ្ធ​របស់អ្នក ។',
'nouserspecified'            => 'អ្នកត្រូវតែ​ផ្ដល់អត្តនាម។',
'login-userblocked'          => 'អ្នកប្រើប្រាស់នេះស្ថិតក្រោមការហាមឃាត់។ មិនអនុញ្ញាតអោយកត់ឈ្មោះចូលទេ។',
'wrongpassword'              => 'ពាក្យសំងាត់​ដែលបានបញ្ចូល​មិនត្រឹមត្រូវទេ។

សូមព្យាយាម​ម្តងទៀត។',
'wrongpasswordempty'         => 'ពាក្យសំងាត់មិនបានបញ្ចូលទេ។

សូមព្យាយាម​ម្តងទៀត។',
'passwordtooshort'           => 'ពាក្យសំងាត់ត្រូវ​មាន​យ៉ាងតិចណាស់​ {{PLURAL:$1|១ តួអក្សរ|$1តួអក្សរ}}។',
'password-name-match'        => 'ពាក្យសំងាត់ត្រូវតែខុសគ្នាពីឈ្មោះរបស់អ្នក។',
'password-login-forbidden'   => 'ហាមប្រាមមិនអោយប្រើអត្តនាមនិងពាក្យសំងាត់នេះ។',
'mailmypassword'             => 'ផ្ញើអ៊ីមែលពាក្យសំងាត់ថ្មី',
'passwordremindertitle'      => 'ពាក្យសំងាត់បណ្តោះអាសន្នថ្មីសម្រាប់{{SITENAME}}',
'passwordremindertext'       => 'មានអ្នកណាម្នាក់ (ប្រហែលជាអ្នក, ពីអាសយដ្ឋាន IP $1) បានស្នើសុំពាក្យសំងាត់ថ្មីមួយពី {{SITENAME}} ($4)។
ពាក្យសំងាត់បណ្ដោះអាសន្នមួយសម្រាប់អ្នកប្រើប្រាស់ "$2" ត្រូវបានប្ដូរទៅជា "$3"។ បើសិនជានេះជាចេតនារបស់អ្នក សូមអ្នកកត់ឈ្មោះចូល​ហើយជ្រើសប្រើពាក្យសំងាត់ថ្មី។
ពាក្យសំងាត់​បណ្ដោះអាសន្ន​របស់​អ្នក នឹង​ត្រូវ​ផុតកំណត់​ក្នុង​រយៈពេល {{PLURAL:$5|មួយ​ថ្ងៃ|$5ថ្ងៃ}} ។

ក្នុងករណីមានអ្នកណាផ្សេងធ្វើការស្នើសុំនេះ ឬ អ្នកនឹកឃើញពាក្យសំងាត់ចាស់របស់អ្នកវិញ ហើយមិនចង់ផ្លាស់ប្តូរពាក្យសំងាត់ទេនោះ សូមអ្នកអាចបំភ្លេចពីសារនេះ ហើយបន្តប្រើប្រាស់ពាក្យសំងាត់ចាស់របស់អ្នកបន្តទៀត។',
'noemail'                    => 'គ្មានអាសយដ្ឋានអ៊ីមែលណាមួយត្រូវបានកត់ត្រាទុកសម្រាប់អ្នកប្រើឈ្មោះ "$1" ទេ។',
'noemailcreate'              => 'អ្នកត្រូវតែផ្ដល់អាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវមួយ',
'passwordsent'               => 'ពាក្យសំងាត់​ថ្មី​ត្រូវ​បាន​ផ្ញើទៅ​អាសយដ្ឋាន​អ៊ីមែល​ដែល​បាន​ចុះបញ្ជី​សម្រាប់អ្នកប្រើឈ្មោះ "$1" ។

សូម​កត់ឈ្មោះចូល​ម្តងទៀត​បន្ទាប់ពី​អ្នក​បាន​ទទួល​ពាក្យសំងាត់ថ្មីនោះ។',
'blocked-mailpassword'       => 'អាសយដ្ឋានIPត្រូវបានហាមឃាត់មិនអោយធ្វើការកែប្រែ និងមិនអនុញ្ញាតឱ្យប្រើប្រាស់មុខងារសង្គ្រោះពាក្យសំងាត់ដើម្បីបង្ការការបំពានទេ។',
'eauthentsent'               => 'អ៊ីមែល​សម្រាប់​ផ្ទៀងផ្ទាត់​បញ្ជាក់ត្រូវបានផ្ញើទៅ​អាសយដ្ឋានអ៊ីមែល​ដែលបានដាក់ឈ្មោះហើយ។

មុននឹងមាន​អ៊ីមែលផ្សេងមួយទៀត​ត្រូវផ្ញើទៅ​គណនីនេះ អ្នកត្រូវតែ​ធ្វើតាមសេចក្តីណែនាំ​ក្នុងអ៊ីមែល​នោះ ដើម្បីបញ្ជាក់ថា​គណនីបច្ចុប្បន្ន​ពិតជា​របស់អ្នកពិតប្រាកដមែន។',
'throttled-mailpassword'     => 'អ៊ីមែលរំលឹកពាក្យសំងាត់ត្រូវបានផ្ញើទៅឱ្យអ្នកតាំងពី{{PLURAL:$1|មួយម៉ោង|$1ម៉ោង}}មុននេះហើយ។

ដើម្បីបង្ការអំពើបំពាន អ៊ីមែលរំលឹកពាក្យសំងាត់តែមួយគត់នឹងត្រូវបាន​ផ្ញើក្នុងរយៈពេល{{PLURAL:$1|មួយម៉ោង|$1ម៉ោង}}។',
'mailerror'                  => 'បញ្ហាក្នុងការផ្ញើអ៊ីមែល៖ $1',
'acct_creation_throttle_hit' => 'អ្នកទស្សនា​វិគី​នេះ​ដោយ​ប្រើប្រាស់​អាសយដ្ឋានIPរបស់​អ្នក​ បានបង្កើត{{PLURAL:$1|គណនីមួយ|គណនីចំនួន$1}}នៅ​ថ្ងៃ​ចុងក្រោយ។ ចំនួននេះ​ជា​ចំនួន​អតិបរមារ​ដែល​ត្រូវ​បាន​អនុញ្ញាត​សម្រាប់​រយៈពេល​នេះ​។

ហេតុនេះ អ្នកទស្សនា​ដោយ​ប្រើប្រាស់​អាសយដ្ឋានIPនេះ​​មិន​អាច​បង្កើត​គណនី​បន្ថែមទៀត​នៅ​ខណៈនេះ​បាន​ទេ​។',
'emailauthenticated'         => 'អាសយដ្ឋានអ៊ីមែលរបស់លោកអ្នក​ត្រូវបានបញ្ជាក់ទទួលស្គាល់នៅ$2នៅ$3។',
'emailnotauthenticated'      => 'អាសយដ្ឋានអ៊ីមែលរបស់លោកអ្នក មិនទាន់ត្រូវបានបញ្ជាក់ទទួលស្គាល់នៅឡើយទេ។

គ្មានអ៊ីមែលដែលនឹងត្រូវបានផ្ញើ សម្រាប់មុខងារពិសេសណាមួយដូចខាងក្រោមទេ។',
'noemailprefs'               => 'ផ្ដល់អាសយដ្ឋាន​អ៊ីមែល​នៅ​ក្នុង​ចំណង់ចំណូលចិត្ត​របស់​អ្នក​ដើម្បីប្រើប្រាស់មុខងារពិសេសទាំងនេះ​។',
'emailconfirmlink'           => 'ផ្ទៀងផ្ទាត់បញ្ជាក់អាសយដ្ឋានអ៊ីមែលរបស់អ្នក',
'invalidemailaddress'        => 'អាសយដ្ឋានអ៊ីមែល​នេះមិនអាចទទួលយកបានទេ​ដោយសារវាមានទម្រង់​​មិនត្រឹមត្រូវ។

សូមបញ្ចូល​អាសយដ្ឋានមួយ​ដែលមាន​ទម្រង់​ត្រឹមត្រូវ ឬមួយក៏ទុកវាលនោះឱ្យនៅទំនេរ​​។',
'accountcreated'             => 'គណនីរបស់លោកអ្នកត្រូវបានបង្កើតហើយ',
'accountcreatedtext'         => 'គណនីឈ្មោះ $1 ត្រូវបានបង្កើតហើយ។',
'createaccount-title'        => 'ការបង្កើតគណនីសម្រាប់{{SITENAME}}',
'createaccount-text'         => 'មានអ្នកផ្សេងបានបង្កើតគណនីជាឈ្មោះ "$2" លើ{{SITENAME}}($4) ព្រមទាំងពាក្យសំងាត់ "$3" ដោយប្រើអាសយដ្ឋានអ៊ីមែលរបស់អ្នកហើយ។

អ្នកគួរតែកត់ឈ្មោះចូលហើយផ្លាស់ប្តូរពាក្យសំងាត់របស់អ្នកនៅពេលនេះ។

អ្នកអាចបំភ្លេចពីសារនេះ ប្រសិនបើ​គណនីនេះត្រូវបានបង្កើតដោយមានបញ្ហា។',
'usernamehasherror'          => 'អត្តនាមមិនអាចមានតួអក្សរដែលជាសញ្ញាបានទេ',
'login-throttled'            => 'អ្នកបានកត់ឈ្មោះចូលមិនបានសំរេចច្រើនដងពេកហើយ។​

សូមរងចាំមួយរយៈ មុនពេលសាកល្បងម្ដងទៀត។',
'login-abort-generic'        => 'អ្នកចុះឈ្មោះចូលមិនបានសំរេចទេ។ ការចុះឈ្មោះចូលត្រូវបានបោះបង់។',
'loginlanguagelabel'         => 'ភាសា៖ $1',

# E-mail sending
'php-mail-error-unknown' => 'កំហុសមិនស្គាល់នៅក្នុងអនុគមន៍ mail() របស់ PHP',

# Change password dialog
'resetpass'                 => '​ប្តូរ​ពាក្យសំងាត់​',
'resetpass_announce'        => 'អ្នកបានកត់ឈ្មោះចូលដោយ​អក្សរកូដអ៊ីមែល​បណ្តោះអាសន្ន​មួយ​។

ដើម្បី​បញ្ចប់​ការកត់ឈ្មោះចូល អ្នកត្រូវតែ​កំណត់​ពាក្យសំងាត់ថ្មី​មួយនៅទីនេះ៖',
'resetpass_text'            => '<!-- បន្ថែមឃ្លានៅទីនេះ -->',
'resetpass_header'          => 'ប្ដូរ​ពាក្យសំងាត់​គណនី',
'oldpassword'               => 'ពាក្យសំងាត់ចាស់៖',
'newpassword'               => 'ពាក្យសំងាត់ថ្មី៖',
'retypenew'                 => 'សូមវាយពាក្យសំងាត់ថ្មី​ម្តងទៀត៖',
'resetpass_submit'          => 'ដាក់ប្រើពាក្យសំងាត់និង​កត់ឈ្មោះចូល',
'resetpass_success'         => 'ពាក្យសំងាត់របស់អ្នកត្រូវបានផ្លាស់ប្តូរបានសំរេចហើយ! ឥឡូវនេះកំពុងកត់ឈ្មោះចូល...',
'resetpass_forbidden'       => 'ពាក្យសំងាត់មិនអាចផ្លាស់ប្តូរបានទេ',
'resetpass-no-info'         => 'អ្នក​ចាំបាច់​ត្រូវតែ​កត់ឈ្មោះចូល ដើម្បី​ចូលទៅកាន់​ទំព័រ​នេះ​ដោយផ្ទាល់​។',
'resetpass-submit-loggedin' => 'ប្តូរពាក្យសំងាត់',
'resetpass-submit-cancel'   => 'បោះបង់',
'resetpass-wrong-oldpass'   => 'ពាក្យ​សម្ងាត់​បណ្ដោះ​អាសន្ន​ ឬ​បច្ចុប្បន្នមិន​ត្រឹមត្រូវ​។

អ្នក​​ប្រហែល​ជា​បាន​ផ្លាស់​ប្ដូរ​លេខ​សម្ងាត់​រួចហើតយ ឬ​បានស្នើ​សុំ​លេខ​សម្ងាត់​​បណ្ដោះ​អាសន្ន​​ថ្មី​មួយ​ហើយ។',
'resetpass-temp-password'   => 'ពាក្យសំងាត់បណ្តោះអាសន្ន:',

# Special:PasswordReset
'passwordreset'              => 'កំណត់​ពាក្យសំងាត់​សាឡើងវិញ',
'passwordreset-text'         => 'បំពេញសំណុំបែបបទនេះដើម្បីទទួលបានអ៊ីម៉ែលក្រើនរំលឹកពីព័ត៌មានលំអិតរបស់គណនីរបស់អ្នក។',
'passwordreset-legend'       => 'ប្ដូរទៅពាក្យសំងាត់ដើម',
'passwordreset-disabled'     => 'មុខងារប្ដូរទៅពាក្យសំងាត់ដើមត្រូវបានបិទមិនអោយប្រើនៅលើវិគីនេះ។',
'passwordreset-pretext'      => '{{PLURAL:$1||វាយបញ្ចូលផ្នែកមួយនៃទិន្នន័យខាងក្រោម}}',
'passwordreset-username'     => 'អត្តនាម៖',
'passwordreset-email'        => 'អាសយដ្ឋានអ៊ីមែល៖',
'passwordreset-emailelement' => 'ឈ្មោះអ្នកប្រើប្រាស់៖ $1
លេខសម្ងាត់បណ្ដោះអាសន្ន៖ $2',
'passwordreset-emailsent'    => 'អីុមែលរំលឹកមួយបានផ្ញើទៅហើយ។',

# Special:ChangeEmail
'changeemail'          => 'ផ្លាស់ប្ដូរអាសយដ្ឋានអ៊ីមែល',
'changeemail-header'   => 'ផ្លាស់ប្ដូរអាសយដ្ឋានអ៊ីមែលសំរាប់គណនីនេះ',
'changeemail-text'     => 'សូមបំពេញសំនុំបែបបទនេះដើម្បីផ្លាស់ប្ដូរអាសយដ្ឋានអ៊ីមែលរបស់អ្នក។ អ្នកនឹងត្រូវបញ្ចូលពាក្យសំងាត់ដើម្បីអះអាងលើការផ្លាស់ប្ដូរនេះ។',
'changeemail-no-info'  => 'អ្នក​ចាំបាច់​ត្រូវតែ​កត់ឈ្មោះចូល ដើម្បី​ចូលទៅកាន់​ទំព័រ​នេះ​ដោយផ្ទាល់​។',
'changeemail-oldemail' => 'អាសយដ្ឋានអ៊ីមែលបច្ចុប្បន្ន៖',
'changeemail-newemail' => 'អាសយដ្ឋានអ៊ីមែលថ្មី៖',
'changeemail-none'     => '(គ្មាន​)',
'changeemail-submit'   => 'ផ្លាស់ប្ដូរអ៊ីមែល',
'changeemail-cancel'   => 'បោះបង់',

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
'nowiki_sample'   => 'បញ្ចូល​អត្ថបទគ្មានទម្រង់​នៅទីនេះ',
'nowiki_tip'      => 'មិនគិត​ទម្រង់​នៃ​វិគី',
'image_sample'    => 'ឧទាហរណ៍.jpg',
'image_tip'       => 'រូបភាពបង្កប់',
'media_sample'    => 'ឧទាហរណ៍.ogg',
'media_tip'       => 'តំណភ្ជាប់ឯកសារ',
'sig_tip'         => 'ហត្ថលេខា​របស់អ្នកជាមួយនឹងកាលបរិច្ឆេទ',
'hr_tip'          => 'បន្ទាត់ដេក (មិនសូវប្រើទេ)',

# Edit pages
'summary'                          => 'ចំណារពន្យល់៖',
'subject'                          => 'ប្រធានបទ/ចំណងជើងរង:',
'minoredit'                        => 'នេះជា​កំណែប្រែតិចតួចប៉ុណ្ណោះ',
'watchthis'                        => 'តាមដាន​ទំព័រនេះ',
'savearticle'                      => 'រក្សាទំព័រទុក',
'preview'                          => 'មើលជាមុន',
'showpreview'                      => 'បង្ហាញ​ការមើលជាមុន',
'showlivepreview'                  => 'មើលជាមុនដោយផ្ទាល់',
'showdiff'                         => 'បង្ហាញ​បំលាស់ប្ដូរ',
'anoneditwarning'                  => "'''ប្រយ័ត្ន ៖''' អ្នកមិនបានកត់ឈ្មោះចូល​ទេ។ អាសយដ្ឋានIPរបស់អ្នក​នឹងត្រូវបាន​កត់ត្រាទុក​ក្នុងប្រវត្តិកែប្រែ​នៃទំព័រ​នេះ។",
'anonpreviewwarning'               => "''អ្នកមិនបានកត់ឈ្មោះចូល​ទេ។ ប្រសិនបើអ្នកធ្វើការរក្សាទុក នោះអាសយដ្ឋានIPរបស់អ្នក​នឹងត្រូវបាន​កត់ត្រាទុក​ក្នុងប្រវត្តិកែប្រែ​នៃទំព័រ​នេះ។''",
'missingsummary'                   => "'''រំលឹក៖''' អ្នកមិនទាន់បានផ្ដល់ចំណារពន្យល់អំពីកំណែប្រែនេះទេ។

បើសិនជាអ្នកចុច '''រក្សាទុក''' ម្ដងទៀតនោះកំណែប្រែរបស់អ្នកនឹងត្រូវរក្សាទុកដោយគ្មានចំណារពន្យល់។",
'missingcommenttext'               => 'សូមបញ្ចូលមួយវិចារនៅខាងក្រោម។',
'missingcommentheader'             => "'''រំលឹក៖''' អ្នកមិនទាន់បានផ្ដល់ឱ្យនូវ ប្រធានបទ/ចំណងជើង របស់វិចារនេះទេ។
បើសិនជាអ្នកចុច \"{{int:savearticle}}\" ម្ដងទៀតនោះកំណែប្រែរបស់អ្នកនឹងត្រូវរក្សាទុកដោយគ្មានវា។",
'summary-preview'                  => 'ការមើលជាមុនរបស់ចំណារពន្យល់:',
'subject-preview'                  => 'ការមើលជាមុនរបស់ប្រធានបទ/ចំណងជើង:',
'blockedtitle'                     => 'អ្នកប្រើនេះត្រូវបានហាមឃាត់ហើយ',
'blockedtext'                      => '\'\'\'ឈ្មោះគណនីឬអាសយដ្ឋានIPរបស់អ្នកស្ថិតក្រោមការហាមឃាត់ហើយ។\'\'\'

ការហាមឃាត់ត្រូវបានធ្វើដោយ $1

ដោយសំអាងលើហេតុផល \'\'$2\'\'។


* ចាប់ផ្ដើមការហាមឃាត់ ៖ $8
* ផុតកំណត់ការហាមឃាត់ ៖ $6
* គណនីហាមឃាត់់ ៖ $7


អ្នកអាចទាក់ទងទៅ $1 ឬ [[{{MediaWiki:Grouppage-sysop}}|អ្នកអភិបាល]]ដទៃទៀតដើម្បីពិភាក្សាពីការហាមឃាត់នេះ ។

អ្នកមិនអាចប្រើប្រាស់មុខងារ "អ៊ីមែលទៅអ្នកប្រើប្រាស់នេះ" បានទេ លើកលែងតែអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវមួយ​ត្រូវបានផ្ដល់អោយក្នុង[[Special:Preferences|ចំណង់ចំណូលចិត្ត]]​របស់លោកអ្នកហើយលោកអ្នកមិនត្រូវបានគេហាមឃាត់មិនឱ្យប្រើប្រាស់មុខងារនោះ។

អាសយដ្ឋានIPបច្ចុប្បន្នរបស់លោកអ្នកគឺ $3 និងអត្តលេខហាមឃាត់គឺ #$5 ។

សូមបញ្ចូលព័ត៌មានលំអិតទាំងអស់ខាងលើនេះ ក្នុងអ៊ីមែលទាក់ទងនឹងបញ្ហានេះ។',
'autoblockedtext'                  => 'អាសយដ្ឋានIPរបស់អ្នកបានត្រូវហាមឃាត់ដោយស្វ័យប្រវត្តិ ព្រោះវាត្រូវបានប្រើប្រាស់ដោយអ្នកប្រើប្រាស់ម្នាក់ទៀត ដែលត្រូវបានហាមឃាត់ដោយ $1 ។

មូលហេតុលើកឡើង៖

:\'\'$2\'\'

* ការចាប់ផ្តើមហាមឃាត់៖ $8
* ពេលផុតកំណត់ហាមឃាត់៖ $6
* គណនីហាមឃាត់៖ $7

អ្នកអាចទាក់ទង $1 ឬ[[{{MediaWiki:Grouppage-sysop}}|អ្នកអភិបាល]]ណាម្នាក់ ដើម្បីពិភាក្សាអំពីការហាមឃាត់នេះ។

សូមកត់សម្គាល់ថាអ្នកមិនអាចប្រើប្រាស់មុខងារ "អ៊ីមែលអ្នកប្រើប្រាស់នេះ" បានទេ លុះត្រាតែមានអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវមួយ បានចុះឈ្មោះក្នុង[[Special:Preferences|ចំណង់ចំណូលចិត្ត]]របស់អ្នក ហើយអ្នកមិនត្រូវបានហាមឃាត់មិនឱ្យប្រើប្រាស់មុខងារនោះ ។

អាសយដ្ឋានIPបច្ចុប្បន្នរបស់អ្នកគឺ $3។ អត្តលេខហាមឃាត់គឺ #$5។
សូមបញ្ចូលព័ត៌មានលំអិតទាំងអស់ខាងលើនេះ ក្នុងអ៊ីមែលទាក់ទងនឹងបញ្ហានេះ។',
'blockednoreason'                  => 'គ្មានហេតុផល​ត្រូវបានលើកឡើង',
'whitelistedittext'                => 'អ្នកត្រូវតែ $1 ដើម្បី​កែប្រែ​ខ្លឹមសារទំព័រ។',
'confirmedittext'                  => 'អ្នកត្រូវតែផ្ទៀងផ្ទាត់បញ្ជាក់អាសយដ្ឋានអ៊ីមែលរបស់អ្នកមុននឹងកែប្រែខ្លឹមសារអត្ថបទ។

សូមកំណត់និងផ្តល់សុពលភាពឱ្យអាសយដ្ឋានអ៊ីមែលរបស់អ្នកតាម [[Special:Preferences|ចំណង់ចំណូលចិត្ត]] របស់អ្នក។',
'nosuchsectiontitle'               => 'រកមិនឃើញផ្នែកនេះទេ',
'nosuchsectiontext'                => 'អ្នកបាន​ព្យាយាម​កែប្រែផ្នែក​មួយ​ដែលមិនទាន់មាន​នៅឡើយ។ ប្រហែលជាផ្នែកនោះត្រូវបានប្ដូរទីតាំងឬលុបចោល ក្នុងកំឡុងពេលដែលអ្នកកំពុងមើលវា។',
'loginreqtitle'                    => 'តម្រូវអោយកត់ឈ្មោះចូល',
'loginreqlink'                     => 'កត់ឈ្មោះចូល',
'loginreqpagetext'                 => 'អ្នកត្រូវតែ$1ដើម្បីមើលទំព័រដទៃផ្សេងទៀត។',
'accmailtitle'                     => 'ពាក្យសំងាត់ត្រូវបានផ្ញើរួចហើយ។',
'accmailtext'                      => "ពាក្យសំងាត់​ដែល​បាន​បង្កើត​ដោយ​ចៃដន្យ​សម្រាប់ [[User talk:$1|$1]] ត្រូវបានផ្ញើទៅ $2 ហើយ​។

ពាក្យសំងាត់​សម្រាប់​​គណនី​ថ្មី​នេះ អាច​​ប្ដូរ​បាននៅ​​ទំព័រ ''[[Special:ChangePassword|ប្ដូរ​ពាក្យសំងាត់]]'' បន្ទាប់ពីកត់ឈ្មោះចូលហើយ​។",
'newarticle'                       => '(ថ្មី)',
'newarticletext'                   => "អ្នកបានតាម​តំណភ្ជាប់​ទៅ​ទំព័រដែលមិនទាន់មាននៅឡើយ។
ដើម្បីបង្កើតទំព័រនេះ សូមចាប់ផ្ដើមវាយ​ក្នុងប្រអប់ខាងក្រោម (សូមមើល [[{{MediaWiki:Helppage}}|ទំព័រ​ជំនួយ]] សម្រាប់​ព័ត៌មានបន្ថែម)។
បើ​អ្នក​មក​ទីនេះដោយអចេតនា សូមចុចប៊ូតុង '''ត្រឡប់ក្រោយ''' របស់ឧបករណ៍រាវរករបស់អ្នក។",
'anontalkpagetext'                 => "----''ទំព័រពិភាក្សានេះគឺសម្រាប់តែអ្នកប្រើប្រាស់អនាមិកដែលមិនទាន់បានបង្កើតគណនីតែប៉ុណ្ណោះ។ ដូច្នេះអាសយដ្ឋានលេខIPរបស់កុំព្យូទ័ររបស់លោកអ្នក​នឹងត្រូវបានបង្ហាញ ដើមី្បសម្គាល់លោកអ្នក។

អាសយដ្ឋានIPទាំងនោះអាចនឹងត្រូវប្រើដោយមនុស្សច្រើននាក់។

ប្រសិនបើអ្នកជាអ្នកប្រើប្រាស់អនាមិក​ហើយ​ប្រសិនបើអ្នកឃើញមានការបញ្ចេញយោបល់មកអ្នកពីអ្វី​ដែល​មិន​ទាក់ទងទៅនឹងអ្វីដែល​អ្នកបាន​ធ្វើ​ សូម[[Special:UserLogin/signup|បង្កើតគណនី]] ឬ [[Special:UserLogin|កត់ឈ្មោះចូល]] ដើម្បីចៀសវាង​ការភ័ន្តច្រឡំ​ណាមួយជាយថាហេតុជាមួយនិងអ្នកប្រើប្រាស់អនាមិកដទៃទៀត។''",
'noarticletext'                    => 'បច្ចុប្បន្នគ្មានអត្ថបទក្នុងទំព័រនេះទេ។

អ្នកអាច [[Special:Search/{{PAGENAME}}|ស្វែងរក​ចំណងជើង​នៃទំព័រនេះ]]ក្នុងទំព័រដទៃទៀត​​ ឬ [{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ស្វែង​រក​កំណត់​ហេតុ​ដែល​ពាក់ព័ន្ធ] ឬ [{{fullurl:{{FULLPAGENAME}}|action=edit}} កែប្រែ​ទំព័រនេះ]។',
'noarticletext-nopermission'       => 'បច្ចុប្បន្ន គ្មានអត្ថបទណាមួយក្នុងទំព័រនេះទេ។

អ្នកអាច [[Special:Search/{{PAGENAME}}|ស្វែងរក​ចំណងជើង​នៃទំព័រនេះ]] ក្នុងទំព័រ​ផ្សេងៗ ឬ<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ស្វែង​រក​កំណត់​ហេតុ​ដែល​ពាក់ព័ន្ធ]</span>។',
'userpage-userdoesnotexist'        => 'គណនីអ្នកប្រើឈ្មោះ"<nowiki>$1</nowiki>" មិនទាន់បានចុះបញ្ជី។

ចូរគិតម្ដងទៀតថាអ្នកចង់ បង្កើត / កែប្រែ ទំព័រនេះឬទេ។',
'userpage-userdoesnotexist-view'   => 'គណនីអ្នកប្រើប្រាស់ដែលមានឈ្មោះ "$1"មិនទាន់បានចុះឈ្មោះទេ។',
'blocked-notice-logextract'        => 'អ្នកប្រើប្រាស់នេះត្រូវបានហាមឃាត់ហើយនាពេលនេះ។
កំណត់ត្រាស្ដីពីការហាមឃាត់ចុងក្រោយមានបង្ហាញដូចខាងក្រោមនេះ៖',
'clearyourcache'                   => "!'''សម្គាល់:''' បន្ទាប់ពីបានរក្សាទុករួចហើយ លោកអ្នកគួរតែសំអាត browser's cache របស់លោកអ្នកដើម្បីមើលការផ្លាស់ប្តូរ។ ខាងក្រោមនេះជាវិធីសំអាត browser's cache ចំពោះកម្មវិធីរុករក(Browser)មួយចំនួន។
* ''' Firefox / Safari:''' សង្កត់ [Shift] ឱ្យជាប់រួចចុចប៊ូតុង ''Reload'' ឬក៏ចុច  ''Ctrl-F5'' ឬ ''Ctrl-R'' (ចំពោះApple Mac វិញ​ចុច ''Command-R'')
* '''Google Chrome:''' ចុច ''Ctrl-Shift-R'' (''Command-Shift-R'' សំរាប់ Mac)
* '''IE(Internet Explorer):''' សង្កត់ [Ctrl] ឱ្យជាប់ រួចចុច ''Refresh''ប៊ូតុង ឬក៏ចុច ''Ctrl-F5''​។
* '''Konqueror:''' ចុចប៊ូតុង  ''Reload'' ឬក៏ចុច ''F5''
* '''Opera:''' សូមចុច  ''[Tools]→[Preferences]''​។",
'usercssyoucanpreview'             => "'''គន្លឹះ ៖ ''' សូមប្រើប្រាស់ប៊ូតុង\"{{int:showpreview}}\"ដើម្បី​ធ្វើតេស្ត​សន្លឹក CSS ថ្មីរបស់អ្នក​មុននឹង​រក្សាទុកវា ។",
'userjsyoucanpreview'              => "'''គន្លឹះ ៖ ''' សូមប្រើប្រាស់​ប៊ូតុង \"{{int:showpreview}}\" ដើម្បី​ធ្វើតេស្ត​សន្លឹក JS ថ្មីរបស់អ្នក​មុននឹង​រក្សាទុកវា ។",
'usercsspreview'                   => "'''កុំភ្លេចថា​អ្នកគ្រាន់តែ​កំពុងមើលជាមុនសន្លឹក CSS របស់អ្នក។
វាមិនទាន់​ត្រូវបានរក្សាទុកទេ!'''",
'userjspreview'                    => "'កុំភ្លេចថាអ្នកគ្រាន់តែកំពុង ធ្វើតេស្ត/មើលមុន ទំព័រអ្នកប្រើប្រាស់  JavaScript របស់អ្នក។ វាមិនទាន់ត្រូវបានរក្សាទុកទេ!'''",
'sitecsspreview'                   => '"កុំភ្លេចថាអ្នកកំពុងតែមើលមុន CSS នេះប៉ុណ្ណោះ។"
"វាមិនទាន់ត្រូវបានរក្សាទុកទេ!"',
'sitejspreview'                    => '"កុំភ្លេចថាអ្នកកំពុងតែមើលមុន កូដJavaScript  នេះប៉ុណ្ណោះ។"
"វាមិនទាន់ត្រូវបានរក្សាទុកទេ!"',
'userinvalidcssjstitle'            => "'''ប្រយ័ត្ន៖''' គ្មានសំបក \"\$1\"។ ចងចាំថា ទំព័រផ្ទាល់ខ្លួន .css និង .js ប្រើប្រាស់ ចំណងជើង ជាអក្សរតូច, ឧទាហរណ៍  {{ns:user}}:Foo/vector.css ត្រឹមត្រូវ, រីឯ {{ns:user}}:Foo/Vector.css មិនត្រឹមត្រូវ។",
'updated'                          => '(បានបន្ទាន់សម័យ)',
'note'                             => "'''ចំណាំ៖'''",
'previewnote'                      => "'''នេះគ្រាន់តែជា​ការបង្ហាញការមើលជាមុនប៉ុណ្ណោះ។ បំលាស់ប្ដូរ​មិនទាន់បាន​រក្សាទុកទេ!'''",
'previewconflict'                  => 'ការមើលមុននេះយោងតាមអត្ថបទក្នុងប្រអប់កែប្រែខាងលើ។ ទំព័រអត្ថបទនឹងបង្ហាញចេញបែបនេះប្រសិនបើអ្នកជ្រើសរើសរក្សាទុក។',
'session_fail_preview'             => "'''សូមអភ័យទោស! យើងមិនអាចរក្សាទុកការកែប្រែរបស់អ្នកបានទេ ដោយសារបាត់ទិន្នន័យវេនការងារ។

សូមព្យាយាមម្តងទៀត។

បើនៅតែមិនបានទេ សូមព្យាយាម[[Special:UserLogout|កត់ឈ្មោះចេញ]] រួចកត់ឈ្មោះចូលឡើងវិញ។'''",
'session_fail_preview_html'        => "'''សូមអភ័យទោស! យើងមិនអាចរក្សាទុកកំណែប្រែរបស់លោកអ្នកបានទេ ដោយសារបាត់ទិន្នន័យវេនការងារ។'''

''ដោយសារ {{SITENAME}} មានអក្សរកូដ HTMLឆៅ ត្រូវបានបើកឱ្យប្រើប្រាស់ ហេតុនេះទំព័រមើលមុនត្រូវបានបិទបាំង ដើម្បីចៀសវាងការវាយលុកដោយ JavaScript ។''

'''បើនេះជាការប៉ុនប៉ងកែប្រែសមស្រប សូមព្យាយាមម្តងទៀត។

បើនៅតែមិនបានទេ សូមព្យាយាម[[Special:UserLogout|កត់ឈ្មោះចេញ]] រួចកត់ឈ្មោះចូលឡើងវិញ។'''",
'editing'                          => 'កំពុងកែប្រែ​ $1',
'editingsection'                   => "កំពុងកែប្រែ'''$1'''(ផ្នែក)",
'editingcomment'                   => 'កែប្រែ $1 (ផ្នែកថ្មី)',
'editconflict'                     => 'ភាពឆ្គងនៃកំណែប្រែ៖ $1',
'explainconflict'                  => 'ចាប់តាំងពីអ្នកបានបង្កើតទំព័រនេះមក មានអ្នកដទៃបានកែប្រែវាហើយ។ ផ្នែកខាងលើនៃទំព័រអត្ថបទ គឺជាកំណែប្រែថ្មី។ កំណែប្រែរបស់អ្នក គឺនៅផ្នែកខាងក្រោម។ ចូរដាក់កំណែប្រែរបស់អ្នកបញ្ចូលគ្នាជាមួយអត្ថបទដែលមាននៅផ្នែកខាងលើ។​
<strong>អត្ថបទនៅផ្នែកខខាងលើ</strong> នឹងត្រូវរក្សាទុក នៅពេលអ្នក ចុច"រក្សាទំព័រ"។',
'yourtext'                         => 'អត្ថបទរបស់អ្នក',
'storedversion'                    => 'កំណែដែលបានស្តារឡើងវិញ',
'nonunicodebrowser'                => "​'''ប្រយ័ត្ន​៖ កម្មវិធី​រុករក​របស់​អ្នក​មិន​គាំ​ទ្រ​ដល់​អក្សរ​ពុម្ព​យូនីកូដ​ទេ​។'''
មាន​ដំណោះ​ស្រាយ​មួយ​ដែល​អនុញ្ញាត​ឲ្យ​អ្នក​កែ​ប្រែ​ទំព័រ​ដោយ​សុវត្ថិភាព​៖ តួ​អក្សរមិន​មែន​ ASCII ​(non-ASCII) នឹង​បង្ហាញ​នៅ​ក្នុង​ប្រអប់​កែ​ប្រែ​ជា​កូដ​គោល១៦ ។",
'editingold'                       => "'''បម្រាម:អ្នកកំពុងតែកែកំណែប្រែដែលហួសសម័យរបស់ទំព័រនេះ។

ប្រសិនបើអ្នករក្សាវាទុក កំណែប្រែពីមុនទាំងប៉ុន្មាននឹងត្រូវបាត់បង់។'''",
'yourdiff'                         => 'ចំនុចខុសគ្នា',
'copyrightwarning'                 => "សូមធ្វើការកត់សម្គាល់​ថា គ្រប់ការរួមចំណែក​របស់អ្នក​នៅលើ{{SITENAME}} ត្រូវបាន​ផ្សព្វផ្សាយ​តាម​លិខិតអនុញ្ញាត $2 (សូម​មើល $1 សម្រាប់​ព័ត៌មាន​លំអិត) ។ បើអ្នកមិនចង់ឱ្យ​​ត្រូវបានអ្នកដទៃធ្វើការកែប្រែ ផ្សព្វផ្សាយបន្តសំណេរ​របស់អ្នកទេនោះ សូមអ្នកកុំដាក់​ស្នើវា​នៅទីនេះអី។<br />
អ្នកត្រូវសន្យាថា ​អ្នកសរសេរវា​ដោយខ្លួនអ្នក ឬបានចម្លងវា​ពី​កម្មសិទ្ធិសាធារណៈឬពីប្រភពសេរី ។
'''មិនត្រូវ​ដាក់ស្នើ​ការងារមានជាប់កម្មសិទ្ឋិបញ្ញាដោយគ្មានការអនុញ្ញាតទេ!'''",
'copyrightwarning2'                => "សូមធ្វើការកត់សម្គាល់​ថា គ្រប់ការរួមចំណែក​ទៅ {{SITENAME}} អាច​ត្រូវបាន​កែប្រែ​ ផ្លាស់ប្ដូរ រឺលុបចោល ដោយអ្នករួមចំណែកដទៃទៀត។

បើអ្នកមិនចង់ឱ្យ​​ត្រូវបានអ្នកដទៃធ្វើការកែប្រែទេនោះ សូមអ្នកកុំដាក់​ស្នើវា​នៅទីនេះអី។<br />
អ្នកត្រូវសន្យាថា ​អ្នកសរសេរវា​ដោយខ្លួនអ្នក ឬបានចម្លងវា​ពី​កម្មសិទ្ធិសាធារណៈឬពីប្រភពសេរី (សូមមើល $1សំរាប់ព័ត៌មានលំអិត)។

'''មិនត្រូវ​ដាក់ស្នើ​ការងារមានជាប់កម្មសិទ្ឋិបញ្ញាដោយគ្មានការអនុញ្ញាតទេ!'''",
'longpageerror'                    => "'''បញ្ហា៖ អត្ថបទ​ដែល​អ្នក​បានដាក់​ស្នើ​មានទំហំ $1 គីឡូបៃ ដែលធំជាង​ទំហំអតិបរមា $2 គីឡូបៃ។ អត្ថបទនេះ​មិនអាច​រក្សាទុកបានទេ។'''",
'readonlywarning'                  => "'''ប្រយ័ត្ន:មូលដ្ឋានទិន្នន័យត្រូវបានចាក់សោសម្រាប់ការរក្សាទុក ដូច្នេះអ្នកនឹងមិនអាចរក្សាទុករាល់កំណែប្រែរបស់អ្នកបានទេឥឡូវនេះ។ សូមអ្នកចម្លងអត្ថបទ រួចដាក់ទៅក្នុងឯកសារដែលជាអត្ថបទ ហើយបន្ទាប់មករក្សាវាទុកនៅពេលក្រោយ។'''

អ្នកអភិបាលដែលបានចាក់សោវា បានផ្ដល់នួវការពន្យល់ដូចតទៅ៖ $1",
'protectedpagewarning'             => "'''ប្រយ័ត្ន៖ ទំព័រនេះ​ត្រូវបានចាក់សោ។ ដូច្នេះ​មានតែ​អ្នកប្រើប្រាស់​ដែល​មាន​អភ័យឯកសិទ្ឋិ​ជាអ្នកអភិបាលទេទើបអាច​កែប្រែ​វាបាន។'''

ខាងក្រោមនេះជាកំណត់ហេតុចូលចុងក្រោយ៖",
'semiprotectedpagewarning'         => "'''សម្គាល់់៖''' ទំព័រនេះ​បានត្រូវ​ចាក់សោ។ ដូច្នេះ​មានតែអ្នកប្រើប្រាស់​ដែលបានចុះឈ្មោះ​ទេទើបអាចកែប្រែ​វា​បាន។

ខាងក្រោមនេះជាកំណត់ហេតុចូលចុងក្រោយ៖",
'cascadeprotectedwarning'          => 'ប្រយ័ត្ន​៖ ទំព័រ​នេះ​ត្រូវ​បាន​ចាក់​សោ​ ដូច្នោះ​ហើយ​មាន​តែ​អ្នក​ប្រើ​ប្រាស់​ដែល​មាន​សិទ្ធិ​ជា​អ្នក​អភិបាល​ប៉ុណ្ណោះ​ អាច​កែ​ប្រែ​បាន។ ពីព្រោះ​ទំព័រ​នេះ​ត្រូវ​បាន​រួម​បញ្ចូល​ទៅ​ក្នុង​
{{PLURAL:$1|ទំព័រ​}}ដែលការ​ពារ​ជា​ថ្នាក់ (cascade-protected)៖',
'titleprotectedwarning'            => "'''ប្រយ័ត្ន៖ ទំព័រនេះត្រូវបានចាក់សោ។ ដូច្នេះមានតែ[[Special:ListGroupRights|អ្នកមានសិទ្ធពិសេស]]ប៉ុណ្ណោះទើបអាចបង្កើតវាបាន។'''

ខាងក្រោមនេះជាកំណត់ហេតុចូលចុងក្រោយ៖",
'templatesused'                    => '{{PLURAL:$1|ទំព័រគំរូ|ទំព័រគំរូទាំងឡាយដែល}}ប្រើនៅក្នុងទំព័រនេះ៖',
'templatesusedpreview'             => '{{PLURAL:$1|ទំព័រគំរូ​|ទំព័រគំរូ​ទាំងឡាយដែល​}}ប្រើ​ក្នុងការមើលមុននេះ៖',
'templatesusedsection'             => '{{PLURAL:$1|ទំព័រគំរូ|ទំព័រគំរូទាំងឡាយ}}ដែលមានប្រើក្នុងផ្នែកនេះ៖',
'template-protected'               => '(ត្រូវបានការពារ)',
'template-semiprotected'           => '(ត្រូវបានការពារពាក់កណ្តាល)',
'hiddencategories'                 => 'ទំព័រនេះស្ថិតនៅក្នុង {{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានបិទបាំងមួយ|ចំណាត់ថ្នាក់ក្រុមដែលត្រូវបានបិទបាំងចំនួន$1}}:',
'nocreatetitle'                    => 'ការបង្កើតទំព័រ​ត្រូវបានកម្រិត',
'nocreatetext'                     => '{{SITENAME}} បានដាក់កំហិតលទ្ធភាពបង្កើតទំព័រថ្មី ។
អ្នកអាចត្រឡប់ក្រោយ និង កែប្រែទំព័រមានស្រាប់ ឬ  [[Special:UserLogin|កត់ឈ្មោះចូលឬបង្កើតគណនី]]។',
'nocreate-loggedin'                => 'អ្នកគ្មានការអនុញ្ញាត​ឱ្យបង្កើតទំព័រថ្មី​ទេ។',
'sectioneditnotsupported-title'    => 'មិនអនុញ្ញាតអោយធ្វើការកែប្រែដោយផ្នែកទេ',
'sectioneditnotsupported-text'     => 'មិនអនុញ្ញាតអោយធ្វើការកែប្រែដោយផ្នែកនៅក្នុងទំព័រនេះទេ។',
'permissionserrors'                => 'បញ្ហាច្បាប់អនុញ្ញាត',
'permissionserrorstext'            => 'អ្នកគ្មានការអនុញ្ញាតឱ្យធ្វើបែបនោះទេ ដោយសារ{{PLURAL:$1|មូលហេតុ|មូលហេតុ}}ដូចខាងក្រោម៖',
'permissionserrorstext-withaction' => 'អ្នកគ្មានការអនុញ្ញាតឱ្យ$2ទេ ដោយសារ{{PLURAL:$1|មូលហេតុ|មូលហេតុ}}ដូចខាងក្រោម៖',
'recreate-moveddeleted-warn'       => "'''ប្រយ័ត្ន៖ អ្នកកំពុង​បង្កើតឡើងវិញ​នូវទំព័រដែលទើបតែ​បានលុបចេញ។'''

អ្នក​គួរពិចារណាមើលថា​​តើជាការសមស្របទេដែលបន្តកែប្រែ​ទំព័រនេះ។
កំណត់ហេតុ​លុបចេញ​នៃទំព័រនេះ ត្រូវបានផ្ដល់ជូន​នៅ​​ទីនេះ​ដើម្បីងាយ​តាមដាន៖",
'moveddeleted-notice'              => 'ទំព័រនេះត្រូវបានលុបចេញហើយ។
កំណត់ហេតុនៃ​ទំព័រនេះត្រូវបាន​ផ្ដល់ជូន​នៅ​ខាងក្រោម​សម្រាប់ជាឯកសារ​យោង​។',
'log-fulllog'                      => 'បង្ហាញ​កំណត់ហេតុ​ទាំងមូល​',
'edit-gone-missing'                => 'មិនអាចបន្ទាន់សម័យទំព័រនេះទេ។

ទំព័រនេះហាក់ដូចជាត្រូវបានលុបចោលហើយ។',
'edit-conflict'                    => 'កែប្រែ​ភាពឆ្គង​។',
'edit-no-change'                   => 'ការកែប្រែរបស់អ្នកត្រូវបានមិនទុកជាការទេ ព្រោះគ្មានការផ្លាស់ប្ដូរណាមួយត្រូវបានធ្វើនៅលើអត្ថបទនេះទេ។',
'edit-already-exists'              => 'មិនអាចបង្កើតទំព័រថ្មីមួយទេ។

ទំព័រនេះមានរួចហើយ។',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''ប្រយ័ត្ន៖''' ទំព័រនេះមានប្រើអនុគមន៍ញែកច្រើនពេកហើយ។

ពេលនេះអនុគមន៍ញែកត្រូវបានចំនួនប្រើ $1{{PLURAL:|ម្ដង|ដង}}។ អនុគមន៍ញែកគួរតែប្រើតិចជាង $2{{PLURAL:$2|ម្ដង|ដង}}។",
'expensive-parserfunction-category'       => 'ទំព័រ​មានប្រើអនុគមន៍​ញែក​ចំនួន​ច្រើនពេក​​',
'post-expand-template-inclusion-warning'  => "'''ប្រយ័ត្ន៖''' ទំព័រគំរូដែលបានបញ្ចូលមានទំហំធំពេកហើយ។

ទំព័រគំរូមួយចំនួនអាចនឹងមិនត្រូវបានបញ្ចូលទេ។",
'post-expand-template-inclusion-category' => 'ទំព័រទាំងឡាយដែលមានបញ្ចូលទំព័រគំរូហួសចំណុះ',
'post-expand-template-argument-category'  => 'ទំព័រ​ដែល​មាន​ផ្ទុក​នូវ​ការ​ពិភាក្សា​នៃ​គំរូ​ដែល​បាន​បោះបង់​',
'parser-template-loop-warning'            => 'បាន​រក​ឃើញ​រង្វិលជុំ​របស់ទំព័រគំរូ​៖ [[$1]]',

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
'currentrevisionlink'    => 'កំណែបច្ចុប្បន្ន',
'cur'                    => 'បច្ចុប្បន្ន',
'next'                   => 'បន្ទាប់',
'last'                   => 'ចុងក្រោយ',
'page_first'             => 'ដំបូង',
'page_last'              => 'ចុងក្រោយ',
'histlegend'             => "ជម្រើស៖ សូមគូសក្នុងកូនប្រអប់ពីមុខកំណែដែលអ្នកចង់ប្រៀបធៀប រួចចុចច្នុច enter ឬប៊ូតុងនៅខាងក្រោម។<br />
'''ពាក្យតំណាង'''៖(បច្ចុប្បន្ន) = ភាពខុសគ្នាជាមួយនឹងកំណែបច្ចុប្បន្ន, (ចុងក្រោយ) = ភាពខុសគ្នារវាងកំណែប្រែពីមុន, តិច = កំណែប្រែតិចតួច",
'history-fieldset-title' => 'ស្វែងរកក្នុងប្រវត្តិ',
'history-show-deleted'   => 'តែទំព័រលុបចោលប៉ុណ្ណោះ',
'histfirst'              => 'ដំបូងគេ',
'histlast'               => 'ក្រោយគេ',
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
'rev-deleted-comment'         => '(ចំណារពន្យល់ត្រូវបានដកចេញ)',
'rev-deleted-user'            => '(អត្តនាមត្រូវបានលុបចេញ)',
'rev-deleted-event'           => '(កំណត់ហេតុសកម្មភាពត្រូវបានដកចេញ)',
'rev-deleted-user-contribs'   => '[បានលុបចេញអត្តនាមឬអាសដ្ឋានIP នេះហើយ - ការកែប្រែលាក់មិនអោយអ្នករួមចំណែកដទៃមើលឃើញ]',
'rev-deleted-text-permission' => "កំណែ​ប្រែ​នៃ​ទំព័រ​នេះ​ត្រូវ​បាន'''​លុបចោល'''​។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការ​លុប​ចោល]។",
'rev-deleted-text-unhide'     => "កំណែ​ប្រែ​នៃ​ទំព័រ​នេះ​ត្រូវ​បាន'''​លុបចោល'''​។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការ​លុបចោល​]។
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
'rev-suppressed-no-diff'      => "អ្នកមិនអាចមើលភាពខុសគ្នានេះបានទេ ព្រោះថាកំណែមួយក្នុងចំណោមនោះត្រូវបាន'''លុបចោលហើយ'''។",
'rev-deleted-unhide-diff'     => "កំណែ​ប្រែ​មួយ​នៃភាព​ខុស​គ្នា​​នេះ​ត្រូវ​បាន'''​លុប'''​។
ប្រហែល​ជា​មាន​ព័ត៌មាន​លម្អិត​នៅ​ក្នុង​[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} កំណត់​ហេតុ​នៃ​ការ​លុប​]។
ក្នុង​នាម​ជា​អ្នក​អភិបាល​ អ្នក​នៅ​តែ​អាច​[$1 មើលភាព​ខុស​គ្នា​​នេះ​]ប្រសិន​បើ​អ្នក​ចង់​។",
'rev-delundel'                => 'បង្ហាញ/លាក់',
'rev-showdeleted'             => 'បង្ហាញ',
'revisiondelete'              => 'លុបចេញ / លែងលុបចេញ កំណែនានា',
'revdelete-nooldid-title'     => 'កំណែគោលដៅមិនត្រឹមត្រូវ',
'revdelete-nooldid-text'      => 'អ្នកមិនបានផ្ដល់កំណែគោលដៅសំរាប់អនុវត្តសកម្មភាពនេះ ឬកំណែគោលដៅដែលបានផ្ដល់អោយមិនមាន ឬអ្នកកំពុងព្យាយាមលាក់កំណែបច្ចុប្បន្ន។',
'revdelete-nologtype-title'   => 'មិន​បាន​ឲ្យ​ប្រភេទ​នៃ​កំណត់ហេតុ​',
'revdelete-nologtype-text'    => 'អ្នក​មិន​បាន​ផ្ដល់ប្រភេទរបស់​​កំណត់​ហេតុ​ដើម្បី​អនុវត្ត​សកម្មភាព​នេះ​ទេ។',
'revdelete-nologid-title'     => 'ការ​វាយ​បញ្ចូល​កំណត់ហេតុ​ដែល​គ្មាន​សុពលភាព​',
'revdelete-no-file'           => 'មិនមានឯកសារ​ដែលអ្នកចង់រកទេ។',
'revdelete-show-file-confirm' => 'តើ​អ្នក​ប្រាកដ​ហើយ​ថា​អ្នក​ចង់​មើល​កំណែ​ប្រែ​ដែល​បាន​លុប​នៃ​ឯកសារ​ "<nowiki>$1</nowiki>" ពី $2 នៅ $3 ?',
'revdelete-show-file-submit'  => 'បាទ/ចាស',
'revdelete-selected'          => "'''{{PLURAL:$2|កំណែប្រែ​ដែលបាន​ជ្រើសយក}}របស់​[[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|ព្រឹត្តិការណ៍​កំណត់​ហេតុ​ដែលបាន​ជ្រើសយក}}:'''",
'revdelete-suppress-text'     => "ការ​ហាមឃាត់​គួរ​ត្រូវ​បាន​អនុវត្តិ​លើ​ករណី​ដូច​ខាង​ក្រោម​នេះ​ប៉ុណ្ណោះ​៖
* ព័ត៌មាន​ផ្ទាល់​ខ្លួន​ ឯកជន​មិន​សមរម្យ​
*: ''អាសយដ្ឋាននៃ​គេហដ្ឋាន​​ ​លេខ​ទូរស័ព្ទ និងលេខ​សន្តិសុខ​សង្គម​ជាដើម​''",
'revdelete-legend'            => 'ដាក់កំហិតគំហើញ',
'revdelete-hide-text'         => 'បិទបាំងឃ្លានៃកំណែប្រែ',
'revdelete-hide-image'        => 'បិទបាំងខ្លឹមសារនៃឯកសារ',
'revdelete-hide-name'         => 'បិទបាំងសកម្មភាពនិងគោលដៅ',
'revdelete-hide-comment'      => 'បិទបាំងកំណែប្រែវិចារ',
'revdelete-hide-user'         => 'បិទបាំងអត្តនាម​ឬអាសយដ្ឋានIP របស់អ្នកកែប្រែ',
'revdelete-hide-restricted'   => 'ដាក់កំហិត​ទិន្នន័យ​ពី​អ្នកអភិបាល ក៏​ដូចជា​អ្នក​ដទៃ​ទៀត',
'revdelete-radio-same'        => '(មិនផ្លាស់ប្ដូរ)',
'revdelete-radio-set'         => 'បាទ/ចាស',
'revdelete-radio-unset'       => 'ទេ',
'revdelete-suppress'          => 'លាក់ទិន្នន័យពីអ្នកថែទាំប្រព័ន្ធ ព្រមទាំងពីសមាជិកដទៃទៀតផងដែរ',
'revdelete-unsuppress'        => 'ដកចេញការដាក់កំហិតលើកំណែដែលបានស្តារឡើងវិញ',
'revdelete-log'               => 'មូលហេតុ៖',
'revdelete-submit'            => 'អនុវត្តទៅលើ{{PLURAL:$1|កំណែ|កំណែទាំងឡាយ}}ដែលបានជ្រើសយក',
'revdelete-success'           => "'''បន្ទាន់សម័យគំហើញកំណែបានសំរេច។'''",
'revdelete-failure'           => "'''មិន​អាចបន្ទាន់សម័យគំហើញនៃ​កំណែប្រែ​បាន​៖'''
$1",
'logdelete-success'           => "'''បានកំណត់គំហើញកំណត់ហេតុដោយជោគជ័យ។'''",
'logdelete-failure'           => "'''មិន​អាចកំណត់គំហើញនៃ​កំណត់​ហេតុ​​បាន​៖'''
$1",
'revdel-restore'              => 'ផ្លាស់ប្ដូរគំហើញ',
'revdel-restore-deleted'      => 'កំណែដែលត្រូវបានលុប',
'revdel-restore-visible'      => 'កំណែដែលអាលមើលឃើញ',
'pagehist'                    => 'ប្រវត្តិទំព័រ',
'deletedhist'                 => 'ប្រវត្តិដែលត្រូវបានលុប',
'revdelete-hide-current'      => 'មាន​កំហុស​ពេល​កំពុង​លាក់​បាំង​វត្ថុ​ដែល​មាន​កាល​បរិច្ឆេទ $2, $1៖ នេះ​គឺ​ជា​កំណែបច្ចុប្បន្ន​។​
វា​មិន​អាច​លាក់​បាំង​បាន​ទេ​។',
'revdelete-show-no-access'    => 'មាន​កំហុស​ពេល​កំពុង​បង្ហាញ​វត្ថុ​ដែល​មាន​កាល​បរិច្ឆេទ $2, $1៖ វត្ថុ​នេះ​ត្រូវ​បាន​កត់​សម្គាល់​ជា"ការ​ដាក់​កំហិត​"​។​
អ្នក​មិន​អាច​ចូល​ទៅ​កាន់​វា​​បាន​ទេ​។',
'revdelete-modify-no-access'  => 'មាន​កំហុស​ពេល​កំពុង​កែ​សម្រួល​​វត្ថុ​ដែល​មាន​កាល​បរិច្ឆេទ $2, $1៖ វត្ថុ​នេះ​ត្រូវ​បាន​កត់​សម្គាល់​ជា"ការ​ដាក់​កំហិត​"​។​
អ្នក​មិន​អាច​ចូល​ទៅ​កាន់​វា​​បាន​ទេ​។',
'revdelete-modify-missing'    => 'មាន​កំហុស​ពេល​កំពុង​កែ​សម្រួលវត្ថុដែលមាន​លេខ​ ID $1​​៖ វា​បាន​បាត់​​ពី​មូលដ្ឋាន​ទិន្នន័យ​!​',
'revdelete-no-change'         => "'''ប្រយ័ត្ន​៖''' វត្ថុ​ដែល​មាន​កាល​បរិច្ឆេទ​ $2, $1 ត្រូវ​បាន​ស្នើ​សុំ​ការ​កំណត់​គំហើញ​រួច​ហើយ​។",
'revdelete-reason-dropdown'   => '*មូលហេតុលុបចោលទូទៅ
** បំពានលើកម្មសិទ្ធិបញ្ញា
** ព័ត៌មានផ្ទាល់ខ្លួនមិនសមរម្យ
** ព័ត៌មានបង្ខូចកេរ្តិ៍ឈ្មោះ',
'revdelete-otherreason'       => 'មូលហេតុផ្សេង​ៗ/ដទៃទៀត​៖',
'revdelete-reasonotherlist'   => 'មូលហេតុផ្សេង​ទៀត​',
'revdelete-edit-reasonlist'   => 'មូលហេតុនៃការលុបការកែប្រែ',
'revdelete-offender'          => 'ម្ចាស់កំណែ៖',

# Suppression log
'suppressionlog'     => 'កំណត់​ហេតុ​នៃ​ការ​ហាម​ឃាត់​',
'suppressionlogtext' => 'ខាងក្រោមនេះជាបញ្ជីការលុបចោលនិងការហាមឃាត់ទាក់ទិននឹងខ្លឹមសារដែលអ្នកអភិបាលបានលាក់។

សូមមើលបំរាមនិងការហាមឃាត់ដែលនៅជាធរមាននាពេលបច្ចុប្បន្ននៅក្នុង[[Special:BlockList|បញ្ជីនៃការហាមឃាត់ IP]]។',

# History merging
'mergehistory'                     => 'ច្របាច់ប្រវត្តិទាំងឡាយរបស់ទំព័របញ្ចូលគ្នា',
'mergehistory-header'              => 'ទំព័រនេះអាចអោយអ្នកច្របាច់កំណែរបស់ប្រវត្តិទំព័រប្រភពមួយបញ្ចូលគ្នាចូលទៅក្នុងទំព័រថ្មីមួយ។

សូមចាំថាការធ្វើបែបនេះនឹងរក្សាទំព័រប្រវត្តិអោយនៅជាប់តគ្នាដដែល។',
'mergehistory-box'                 => 'ច្របាច់បញ្ចូលកំណែទាំងអស់របស់ទំព័រ២បញ្ចូលគ្នា៖',
'mergehistory-from'                => 'ទំព័រប្រភព៖',
'mergehistory-into'                => 'ទំព័រគោលដៅ៖',
'mergehistory-list'                => 'ប្រវត្តិកំណែប្រែដែលអាចច្របាច់បញ្ចូលគ្នាបាន',
'mergehistory-go'                  => 'បង្ហាញកំណែប្រែដែលអាចច្របាច់បញ្ចូលបាន',
'mergehistory-submit'              => 'ច្របាច់កំណែនានាបញ្ចូលគ្នា',
'mergehistory-empty'               => 'គ្មានកំណែណាមួយអាចច្របាច់បញ្ចូលគ្នាទេ។',
'mergehistory-success'             => '$3 {{PLURAL:$3|កំណែ​​|កំណែ}}របស់[[:$1]] បានច្របាច់បញ្ចូល​​គ្នា​​ទៅ[[:$2]]បានសំរេចហើយ។',
'mergehistory-fail'                => 'មិនអាចធ្វើការច្របាច់ប្រវត្តិបញ្ចូលគ្នា។ សូមពិនិត្យទំព័រនេះ និងប៉ារ៉ាម៉ែត្រពេលវេលាឡើងវិញ។',
'mergehistory-no-source'           => 'ទំព័រប្រភព $1 មិនមានទេ។',
'mergehistory-no-destination'      => 'ទំព័រគោលដៅ $1 មិនមានទេ។',
'mergehistory-invalid-source'      => 'ទំព័រប្រភពត្រូវតែមានចំណងជើងបានការ។',
'mergehistory-invalid-destination' => 'ទំព័រគោលដៅត្រូវតែមានចំណងជើងបានការ។',
'mergehistory-autocomment'         => 'បានច្របាច់បញ្ចូល [[:$1]] ទៅក្នុង [[:$2]]',
'mergehistory-comment'             => 'បានច្របាច់បញ្ចូល [[:$1]] ទៅក្នុង [[:$2]]: $3',
'mergehistory-same-destination'    => 'ទំព័រប្រភពនិងទំព័រគោលដៅមិនអាចមានចំណងជើងដូចគ្នាបានទេ។',
'mergehistory-reason'              => 'មូលហេតុ៖',

# Merge log
'mergelog'           => 'កំណត់ហេតុនៃការច្របាច់បញ្ចូលគ្នា',
'pagemerge-logentry' => 'បានច្របាច់បញ្ចូល[[$1]]ទៅក្នុង[[$2]] (ចំនួនកំណែរហូតដល់$3)',
'revertmerge'        => 'បំបែកចេញ',
'mergelogpagetext'   => 'ខាងក្រោមគឺជាតារាងនៃការច្របាច់បញ្ចូលគ្នាថ្មីៗបំផុតរបស់ប្រវត្តិនៃទំព័រមួយទៅក្នុងប្រវត្តិនៃទំព័រមួយទៀត។',

# Diffs
'history-title'            => 'ប្រវត្តិកំណែប្រែនានានៃ "$1"',
'difference'               => '(ប្រៀបធៀបកំណែនានា)',
'difference-multipage'     => '(ភាពខុសគ្នារវាងទំព័រនានា)',
'lineno'                   => 'បន្ទាត់ទី$1៖',
'compareselectedversions'  => 'ប្រៀបធៀប​កំណែដែលបាន​ជ្រើសយក',
'showhideselectedversions' => 'បង្ហាញ​/លាក់​កំណែប្រែ​ដែលបាន​ជ្រើសយក',
'editundo'                 => 'មិនធ្វើវិញ',
'diff-multi'               => '({{PLURAL:$1|កំណែប្រែកម្រិតបង្គួរមួយ|កំណែប្រែកម្រិតបង្គួរចំនួន $1}}មិនត្រូវបានបង្ហាញ)',

# Search results
'searchresults'                    => 'លទ្ធផលស្វែងរក',
'searchresults-title'              => 'លទ្ធផល​ស្វែងរក​សម្រាប់ "$1"',
'searchresulttext'                 => 'សំរាប់ព័ត៌មានបន្ថែមស្ដីអំពីការស្វែងរកក្នុង{{SITENAME}} សូមមើល[[{{MediaWiki:Helppage}}|{{int:help}}]]។',
'searchsubtitle'                   => 'អ្នកបានស្វែងរក \'\'\'[[:$1]]\'\'\'([[Special:Prefixindex/$1|គ្រប់ទំព័រដែលផ្ដើមដោយ "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|គ្រប់ទំព័រដែលភ្ជាប់មក "$1"]])',
'searchsubtitleinvalid'            => "អ្នកបានស្វែងរក '''$1'''",
'toomanymatches'                   => 'មានតំណភ្ជាប់ច្រើនណាស់ត្រូវបានបង្ហាញ ចូរព្យាយាមប្រើសំណួរផ្សេងមួយទៀត',
'titlematches'                     => 'ភាពត្រូវគ្នានៃចំណងជើងទំព័រ',
'notitlematches'                   => 'ពុំមានចំណងជើងទំព័រណាដូចពាក្យនេះទេ',
'textmatches'                      => 'ទំព័រអត្ថបទផ្គូរផ្គងគ្នា',
'notextmatches'                    => 'គ្មានអត្ថបទទំព័រណាមួយដែលមានខ្លឹមសារផ្គូផ្គងនឹងឃ្លាឬពាក្យនេះទេ',
'prevn'                            => 'មុន {{PLURAL:$1|$1}}',
'nextn'                            => 'បន្ទាប់ {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|លទ្ធផល|លទ្ធផល}}មុន',
'nextn-title'                      => '$1 {{PLURAL:$1|លទ្ឋផល|លទ្ឋផល}}​បន្ទាប់​',
'shown-title'                      => 'បង្ហាញ $1 {{PLURAL:$1|លទ្ធផល|លទ្ធផល}}ក្នុងមួយទំព័រ',
'viewprevnext'                     => 'មើល ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'ជម្រើសនានាក្នុងការស្វែងរក',
'searchmenu-exists'                => "* ទំព័រ '''[[$1]]'''",
'searchmenu-new'                   => "'''បង្កើតទំព័រ \"[[:\$1]]\" នៅ​លើ​វិគី​នេះ!'''",
'searchhelp-url'                   => 'Help:មាតិកា',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|រុក​រក​ក្នុង​ទំព័រ​នានា​ជាមួយ​បុព្វបទ​នេះ​]]',
'searchprofile-articles'           => 'ទំព័រ​មាតិកា',
'searchprofile-project'            => 'ជំនួយ និង​ទំព័រ​គម្រោង',
'searchprofile-images'             => 'ពហុ​ព័ត៌មាន',
'searchprofile-everything'         => 'ទាំងអស់',
'searchprofile-advanced'           => 'ថ្នាក់ខ្ពស់',
'searchprofile-articles-tooltip'   => 'ស្វែងរកក្នុង $1',
'searchprofile-project-tooltip'    => 'ស្វែងរកក្នុង $1',
'searchprofile-images-tooltip'     => 'ស្វែងរកឯកសាររូបភាព',
'searchprofile-everything-tooltip' => 'ស្វែងរកក្នុងខ្លឹមសារទាំងអស់(រួមបញ្ចូលទាំងទំព័រពិភាក្សា)',
'searchprofile-advanced-tooltip'   => 'ស្វែងរកក្នុងប្រភេទកំនត់ដោយអ្នកប្រើប្រាស់',
'search-result-size'               => '$1({{PLURAL:$2|១ពាក្យ|$2ពាក្យ}})',
'search-result-category-size'      => '{{PLURAL:$1|សមាជិកម្នាក់|សមាជិក$1នាក់}} ({{PLURAL:$2|ចំណាត់ថ្នាក់ក្រុមរង១|$2 ចំណាត់ថ្នាក់ក្រុមរង}}, {{PLURAL:$3|1 ឯកសារ|$3 ឯកសារ}})',
'search-result-score'              => 'កម្រិតទាក់ទិន៖ $1%',
'search-redirect'                  => '(បញ្ជូនបន្ត $1)',
'search-section'                   => '(ផ្នែក $1)',
'search-suggest'                   => 'ប្រហែលជាអ្នកចង់រក៖ $1',
'search-interwiki-caption'         => 'គម្រោងជាបងប្អូន',
'search-interwiki-default'         => 'លទ្ធផលពី$1៖',
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
'nonefound'                        => "'''កំណត់​ចំណាំ​'''៖ តាម​លំនាំ​ដើម មាន​តែ​លំហ​ឈ្មោះ​ខ្លះៗ​ប៉ុណ្ណោះ​ដែល​ត្រូវបាន​ស្វែងរក​។​​
សូម​សាកប្រើ​បុព្វបទ ''all:''ក្នុង​សំណើ​របស់អ្នក ដើម្បី​ស្វែងរក​មាតិកាទាំងអស់ (រួមបញ្ចូល​ទាំង​ទំព័រ​ពិភាក្សានិងទំព័រគំរូជាដើម) ឬ​ក៏ប្រើ​លំហឈ្មោះដែលចង់រក​​ ជាបុព្វបទ​ក៏បាន​។​",
'search-nonefound'                 => 'មិនមានលទ្ធផលណាមួយ​ត្រូវគ្នានឹងសំណើសុំនេះទេ',
'powersearch'                      => 'ស្វែងរកថ្នាក់ខ្ពស់',
'powersearch-legend'               => 'ស្វែងរកថ្នាក់ខ្ពស់',
'powersearch-ns'                   => 'ស្វែងរកក្នុងប្រភេទ៖',
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
'prefsnologin'                  => 'មិនទាន់កត់ឈ្មោះចូលទេ',
'prefsnologintext'              => 'អ្នកចាំបាច់ត្រូវតែ<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} កត់ឈ្មោះចូល]</span> ដើម្បីកំណត់ចំណង់ចំណូលចិត្តរបស់អ្នក។',
'changepassword'                => 'ប្តូរពាក្យសំងាត់',
'prefs-skin'                    => 'សំបក',
'skin-preview'                  => 'មើលជាមុន',
'datedefault'                   => 'គ្មានចំណូលចិត្ត',
'prefs-beta'                    => 'មុខងារពិសេសថ្មីៗរបស់ស៊េរីបេតា',
'prefs-datetime'                => 'កាលបរិច្ឆេទនិងល្វែងម៉ោង',
'prefs-labs'                    => 'មុខងារពិសេសថ្មីៗដែលស្ថិតក្រោមការពិសោធន៍នៅឡើយ',
'prefs-personal'                => 'ប្រវត្តិរូប',
'prefs-rc'                      => 'បំលាស់ប្ដូរថ្មីៗ',
'prefs-watchlist'               => 'បញ្ជីតាមដាន',
'prefs-watchlist-days'          => 'ចំនួនថ្ងៃត្រូវបង្ហាញក្នុងបញ្ជីតាមដាន៖',
'prefs-watchlist-days-max'      => 'អតិបរមា ៧ថ្ងៃ',
'prefs-watchlist-edits'         => 'ចំនួនអតិបរមានៃបំលាស់ប្តូរត្រូវបង្ហាញក្នុងបញ្ជីតាមដានដែលបានពង្រីក៖',
'prefs-watchlist-edits-max'     => 'ចំនួនអតិបរមា៖ ១០០០',
'prefs-misc'                    => 'ផ្សេងៗ',
'prefs-resetpass'               => 'ប្តូរពាក្យសំងាត់',
'prefs-changeemail'             => 'ផ្លាស់ប្ដូរអ៊ីមែល',
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
'stub-threshold'                => 'ទំហំអប្បបរមាសំរាប់ដាក់ជាទំរង់<a href="#" class="stub">តំណភ្ជាប់ទៅទំព័រកំប៉ិចកំប៉ុក</a> (គិតជាបៃ)៖',
'stub-threshold-disabled'       => 'មិនប្រើ',
'recentchangesdays'             => 'ចំនួនថ្ងៃបង្ហាញក្នុង ទំព័របំលាស់ប្តូរថ្មីៗ៖',
'recentchangesdays-max'         => '(អតិបរមា $1 {{PLURAL:$1|ថ្ងៃ|ថ្ងៃ}})',
'recentchangescount'            => 'ចំនួន​កំណែប្រែ​ដែល​ត្រូវ​បង្ហាញ​តាមលំនាំដើម:',
'prefs-help-recentchangescount' => 'រាប់បញ្ចូលទាំងការកែប្រែនាពេលថ្មី ប្រវត្តិទំព័រនិងកំណត់ហេតុនានា។',
'savedprefs'                    => 'ចំណង់ចំណូលចិត្តនានារបស់អ្នកត្រូវបានរក្សាទុកហើយ។',
'timezonelegend'                => 'ល្វែង​ម៉ោង:',
'localtime'                     => 'ម៉ោងក្នុងស្រុក​៖',
'timezoneuseserverdefault'      => 'តាមការកំនត់ដើមរបស់វិគី ($1)',
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
'allowemail'                    => 'ទទួលអ៊ីមែលពីអ្នកប្រើប្រាស់ដទៃទៀត',
'prefs-searchoptions'           => 'ជម្រើសក្នុងការស្វែងរក',
'prefs-namespaces'              => 'ប្រភេទ',
'defaultns'                     => 'ស្វែងរក​ក្នុង​លំហឈ្មោះ​ទាំងនេះ​តាម​បែប​ផ្សេង៖',
'default'                       => 'លំនាំដើម',
'prefs-files'                   => 'ឯកសារ',
'prefs-custom-css'              => 'កែតំរូវ CSS',
'prefs-custom-js'               => 'កែតំរូវ JS',
'prefs-common-css-js'           => 'CSS/JavaScriptរួមសំរាប់សំបកទាំងអស់',
'prefs-reset-intro'             => 'អ្នក​អាច​ប្រើ​ទំព័រ​នេះ​ដើម្បី​កំណត់​ឡើង​វិញ​នូវ​ចំណូល​ចិត្ត​របស់​អ្នក​ដូច​លំនាំ​ដើម​របស់​តំបន់​វិញ​។
សកម្មភាព​នេះ​មិន​អាច​ធ្វើ​ឡើង​វិញ​បាន​ទេ​។',
'prefs-emailconfirm-label'      => 'បញ្ជាក់ទទួលស្គាល់អ៊ីមែល៖',
'prefs-textboxsize'             => 'ទំហំរបស់ផ្ទាំងកែប្រែទំព័រ',
'youremail'                     => 'អ៊ីមែល៖',
'username'                      => 'អត្តនាម៖',
'uid'                           => 'អត្តលេខ៖',
'prefs-memberingroups'          => 'សមាជិកក្នុង{{PLURAL:$1|ក្រុម|ក្រុម}}៖',
'prefs-registration'            => 'កាលបរិច្ឆេទចុះឈ្មោះ៖',
'yourrealname'                  => 'ឈ្មោះពិត៖',
'yourlanguage'                  => 'ភាសា៖',
'yourvariant'                   => 'អថេរ​៖',
'yournick'                      => 'ហត្ថលេខាថ្មី៖',
'prefs-help-signature'          => 'រាល់វិចារនៅលើទំព័រពិភាក្សានានា​គួរតែមានចុះហត្ថលេខាដោយប្រើ "<nowiki>~~~~</nowiki>" ដែលនឹងបំលែង​ចេញជាហត្ថលេខា​របស់អ្នក​ជាមួយនឹងកាលបរិច្ឆេទ។',
'badsig'                        => 'ហត្ថលេខាឆៅមិនត្រឹមត្រូវ។សូមពិនិត្យមើលស្លាក​ HTML ។',
'badsiglength'                  => 'ហត្ថលេខារបស់អ្នកវែងជ្រុល។

វាត្រូវតែមានតួអក្សរតិចជាង $1 {{PLURAL:$1|តួ|តួ}}។',
'yourgender'                    => 'ភេទ៖',
'gender-unknown'                => 'មិនបញ្ជាក់',
'gender-male'                   => 'ប្រុស',
'gender-female'                 => 'ស្រី',
'prefs-help-gender'             => 'ដាក់ក៏បានមិនដាក់ក៏បាន៖ ប្រើសំរាប់អោយសូហ្វវែរហៅតាមភេទអោយបាមត្រឹមត្រូវ។ ព័ត៌មាននេះនឹងត្រូវបង្ហាញជាសាធារណៈ។',
'email'                         => 'អ៊ីមែល',
'prefs-help-realname'           => 'អ្នកអាចផ្ដល់ឈ្មោះពិតរបស់អ្នកក៏បានមិនផ្ដល់ក៏បាន។ បើអ្នកផ្ដល់ឱ្យ វានឹងត្រូវបានប្រើប្រាស់់ដើម្បីបញ្ជាក់ភាពជាម្ចាស់​លើការរួមចំណែក​នានា​របស់អ្នក។',
'prefs-help-email'              => 'អ្នកអាចផ្ដល់អាសយដ្ឋានអ៊ីមែលរបស់អ្នកក៏បានមិនផ្ដល់ក៏បាន។ ប៉ុន្ដែអាសយដ្ឋានអ៊ីមែលដែលផ្ដល់អោយនឹងមានប្រយោជន៍ក្នុងការប្ដូរពាក្យសំងាត់ ពេលដែលអ្នកភ្លេចវា។',
'prefs-help-email-others'       => 'អ្នកក៏អាចជ្រើសរើស​ការផ្ដល់លទ្ឋភាព​​ឱ្យអ្នកដទៃទាក់ទងអ្នក​តាមរយៈ​​ទំព័រអ្នកប្រើប្រាស់​​ឬទំព័រពិភាក្សារបស់អ្នក​​ដោយមិនចាំបាច់ឱ្យគេដឹងពីអត្តសញ្ញាណរបស់អ្នកផងដែរ។',
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
'prefs-displayrc'               => 'ជំរើសការបង្ហាញ',
'prefs-displaysearchoptions'    => 'ជំរើសការបង្ហាញ',
'prefs-displaywatchlist'        => 'ជំរើសការបង្ហាញ',
'prefs-diffs'                   => 'ភាពខុសគ្នា',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'អាសយដ្ឋានអ៊ីមែលហាក់មានសុពលភាព',
'email-address-validity-invalid' => 'បញ្ចូលអាសយដ្ឋានអ៊ីមែលដែលមានសុពលភាព',

# User rights
'userrights'                   => 'ការគ្រប់គ្រងសិទ្ធិអ្នកប្រើប្រាស់',
'userrights-lookup-user'       => 'គ្រប់គ្រងក្រុមអ្នកប្រើប្រាស់',
'userrights-user-editname'     => 'បញ្ចូលអត្តនាម៖',
'editusergroup'                => 'កែប្រែក្រុមអ្នកប្រើប្រាស់',
'editinguser'                  => "ការប្ដូរសិទ្ធិអ្នកប្រើប្រាស់ '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'កែប្រែក្រុមអ្នកប្រើប្រាស់',
'saveusergroups'               => 'រក្សាក្រុមអ្នកប្រើប្រាស់ទុក',
'userrights-groupsmember'      => 'ក្រុមសមាជិកភាព៖',
'userrights-groupsmember-auto' => 'សមាជិកស្វ័យប្រវត្តិរបស់៖',
'userrights-groups-help'       => 'អ្នកអាចប្ដូរក្រុមនានាដែលអ្នកប្រើប្រាស់នេះនៅ៖
* ប្រអប់ដែលមានគូសធីកមានន័យថាអ្នកប្រើប្រាស់នេះស្ថិតនៅក្នុងនោះរួចហើយ
* ប្រអប់ដែលគ្មានគូសធីកមានន័យថាអ្នកប្រើប្រាស់នេះមិនស្ថិតនៅក្នុងនោះទេ
* សញ្ញា * បញ្ជាក់ថាអ្នកមិនអាចប្ដូរក្រុមនោះចេញទេពេលដែលអ្នកបាន​បន្ថែមវាហើយ។',
'userrights-reason'            => 'មូលហេតុ៖',
'userrights-no-interwiki'      => 'អ្នកគ្មានការអនុញ្ញាតកែប្រែសិទ្ធិរបស់អ្នកប្រើប្រាស់លើវិគីផ្សេងទេ។',
'userrights-nodatabase'        => 'មូលដ្ឋានទិន្នន័យ $1 មិនមាន ឬ ស្ថិតនៅខាងក្រៅ។',
'userrights-nologin'           => 'អ្នកត្រូវតែ [[Special:UserLogin|កត់ឈ្មោះចូល]]ដោយប្រើគណនីអ្នកអភិបាលដើម្បីផ្ដល់សិទ្ធិឱ្យអ្នកប្រើប្រាស់​។',
'userrights-notallowed'        => 'គណនីរបស់អ្នកគ្មានការអនុញ្ញាតដើម្បីកំណត់សិទ្ធិរបស់អ្នកប្រើប្រាស់ដទៃទេ។',
'userrights-changeable-col'    => 'ក្រុមនានាដែលអ្នកអាចផ្លាស់ប្ដូរបាន',
'userrights-unchangeable-col'  => 'ក្រុមនានាដែលអ្នកមិនអាចផ្លាស់ប្ដូរបាន',

# Groups
'group'               => 'ក្រុម៖',
'group-user'          => 'អ្នកប្រើប្រាស់',
'group-autoconfirmed' => 'អ្នកប្រើប្រាស់ទទួលស្គាល់ដោយស្វ័យប្រវត្តិ',
'group-bot'           => 'រូបយន្ត',
'group-sysop'         => 'អ្នកអភិបាល',
'group-bureaucrat'    => 'អ្នកការិយាល័យ',
'group-suppress'      => 'អធិការ',
'group-all'           => '(ទាំងអស់)',

'group-user-member'          => 'អ្នកប្រើប្រាស់',
'group-autoconfirmed-member' => 'អ្នកប្រើប្រាស់ទទួលស្គាល់ដោយស្វ័យប្រវត្តិ',
'group-bot-member'           => 'រូបយន្ត',
'group-sysop-member'         => 'អ្នកអភិបាល',
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
'right-suppressredirect'     => 'មិនបង្កើតការបញ្ជូនបន្តពីទំព័រប្រភពនៅពេលប្ដូរទីតាំងទំព័រ',
'right-upload'               => 'ផ្ទុកឡើងឯកសារ',
'right-reupload'             => 'សរសេរលុបពីលើឯកសារមានស្រាប់',
'right-reupload-own'         => 'សរសេរលុបពីលើឯកសារមានស្រាប់ដែលត្រូវបានផ្ទុកឡើងដោយម្ចាស់ដើម',
'right-reupload-shared'      => 'សរសេរលុបពីលើឯកសារនៅក្នុងថតមេឌារួមរបស់វិគីនេះ',
'right-upload_by_url'        => 'ផ្ទុកឡើងឯកសារមួយពីអាសយដ្ឋាន URL មួយ',
'right-autoconfirmed'        => 'កែប្រែទំព័រពាក់កណ្ដាលការពារនានា',
'right-bot'                  => 'ទុកជាដំណើរការស្វ័យប្រវត្តិមួយ',
'right-nominornewtalk'       => 'មិនបង្ហាញសាររំលឹក ពេលធ្វើកំនែតិចតួចនៅលើទំព័រពិភាក្សានានា',
'right-writeapi'             => 'ការប្រើប្រាស់ API សម្រាប់​សរសេរ',
'right-delete'               => 'លុបទំព័រចោល',
'right-bigdelete'            => 'លុបទំព័រទាំងឡាយដែលមានប្រវត្តិវែង',
'right-deleterevision'       => 'លុប​និង​ឈប់​លុប​កំណែ​ប្រែ​ច្បាស់លាស់​នៃ​ទំព័រ​',
'right-deletedhistory'       => 'មើលកំនត់ត្រាប្រវត្តិដែលត្រូវបានលុបចោល ដោយគ្មានអត្ថបទភ្ជាប់របស់វា',
'right-browsearchive'        => 'ស្វែងរកទំព័រដែលត្រូវបានលុបចោល',
'right-undelete'             => 'ឈប់លុបទំព័រមួយ',
'right-suppressrevision'     => 'ពិនិត្យនិងស្ដារកំណែដែលអ្នកអភិបាលបានលាក់',
'right-suppressionlog'       => 'មើលកំណត់ហេតុឯកជន',
'right-block'                => 'ហាមមិនឱ្យអ្នកប្រើប្រាស់ដទៃទៀតធ្វើការកែប្រែ',
'right-blockemail'           => 'ហាមឃាត់អ្នកប្រើប្រាស់ម្នាក់មិនអោយផ្ញើអ៊ីមែល',
'right-hideuser'             => 'ហាមឃាត់អ្នកប្រើប្រាស់ម្នាក់ រួចលាក់មិនអោយបង្ហាញជាសាធារណៈ',
'right-ipblock-exempt'       => 'ការហាមឃាត់IPជាប្រយោល ការហាមឃាត់ស្វ័យប្រវត្តិនិងការហាមឃាត់មានកំរិត',
'right-proxyunbannable'      => 'ពង្វាងការរាំងខ្ទប់ស្វ័យប្រវត្តិរបស់ប្រុកស៊ី',
'right-unblockself'          => 'ឈប់ហាមឃាត់ពួកគេ',
'right-protect'              => 'ប្ដូរកម្រិតការពាររួចកែប្រែទំព័រដែលបានការពារ',
'right-editprotected'        => 'កែប្រែទំព័រដែលបានការពារ (ដោយមិនរំលាយការការពារ)',
'right-editinterface'        => 'កែប្រែអន្តរមុខអ្នកប្រើប្រាស់',
'right-editusercssjs'        => 'កែប្រែឯកសារ CSS និង JS របស់អ្នកប្រើប្រាស់ផ្សេងទៀត',
'right-editusercss'          => 'កែប្រែឯកសារ CSS របស់អ្នកប្រើប្រាស់ផ្សេងទៀត',
'right-edituserjs'           => 'កែប្រែឯកសារ JS របស់អ្នកប្រើប្រាស់ផ្សេងទៀត',
'right-rollback'             => 'ត្រឡប់យ៉ាងរហ័សនូវកំណែប្រែទំព័រវិសេសណាមួយ​ដែលធ្វើឡើងដោយ​អ្នកប្រើប្រាស់ចុងក្រោយគេ។',
'right-markbotedits'         => 'ចំនាំកំនែប្រែត្រឡប់ឡើងវិញទាំងឡាយថាជាកំនែប្រែដោយរូបយន្ត',
'right-noratelimit'          => 'មិនទទួលរងឥទ្ធិពលពីការដាក់កំហិតណាទាំងអស់',
'right-import'               => 'នាំចូលទំព័រនានាពីវិគីផ្សេងៗទៀត',
'right-importupload'         => 'នាំចូលទំព័រនានាពីឯកសារដែលបានផ្ទុកឡើង',
'right-patrol'               => 'ចំណាំកំណែប្រែរបស់អ្នកដ៏ទៃថាជាកំណែប្រែបានល្បាត',
'right-autopatrol'           => 'កត់សម្គាល់ដោយស្វ័យប្រវត្តិនូវកំណែប្រែរបស់ខ្ញុំថាជាកំនែប្រែបានល្បាត',
'right-patrolmarks'          => 'មើក​កំណត់​សម្គាល់​ល្បាត​ដែល​ផ្លាស់​ប្តូរ​ថ្មី​ៗ​',
'right-unwatchedpages'       => 'បង្ហាញបញ្ជីទំព័រនានាដែលមិនត្រូវបានមើល',
'right-trackback'            => 'ដាក់ស្នើការ​តាម​ដាន​',
'right-mergehistory'         => 'ច្របាច់ប្រវត្តិរបស់ទំព័រនានាបញ្ចូលគ្នា',
'right-userrights'           => 'កែប្រែរាល់សិទ្ធិនៃអ្នកប្រើប្រាស់',
'right-userrights-interwiki' => 'កែប្រែសិទ្ធិអ្នកប្រើប្រាស់នៅលើវិគីផ្សេងៗទៀត',
'right-siteadmin'            => 'ចាក់សោនិងបើកសោមូលដ្ឋានទិន្នន័យ',
'right-sendemail'            => 'ផ្ញើអ៊ីមែលទៅកាន់អ្នកប្រើដទៃ',
'right-passwordreset'        => 'កំណត់​ពាក្យសំងាត់​ឡើងវិញ​សម្រាប់​អ្នកប្រើប្រាស់ ([[Special:PasswordReset|មើល​ទំព័រ​ពិសេស]])',

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
'action-reupload-shared'      => 'ផ្ទុកជាន់ពីលើឯកសារនេះក្នុងថតរួមគ្នា',
'action-upload_by_url'        => 'ផ្ទុក​ឯកសារ​នេះ​ឡើង​ពី​អាសយដ្ឋាន URL',
'action-writeapi'             => 'ប្រើប្រាស់​ write API',
'action-delete'               => 'លុបទំព័រនេះចោល',
'action-deleterevision'       => 'លុបកំណែប្រែនេះចោល',
'action-deletedhistory'       => 'មើលប្រវត្តិលុបចោលរបស់ទំព័រនេះ',
'action-browsearchive'        => 'ស្វែងរកទំព័រដែលត្រូវបានលុបចោល',
'action-undelete'             => 'ឈប់លុបទំព័រនេះ',
'action-suppressrevision'     => 'ត្រួតពិនិត្យ​និង​ស្ដារ​​កំណែ​ប្រែដែល​លាក់​បាំង',
'action-suppressionlog'       => 'មើលកំណត់ហេតុឯកជននេះ',
'action-block'                => 'ហាមឃាត់អ្នកប្រើប្រាស់នេះមិនឱ្យធ្វើការកែប្រែ',
'action-protect'              => 'ប្ដូរកម្រិតការពារសម្រាប់ទំព័រនេះ',
'action-import'               => 'នាំចូលទំព័រនេះពីវិគីផ្សេងមួយទៀត',
'action-importupload'         => 'នាំចូលទំព័រនេះពីឯកសារដែលបានផ្ទុកឡើង',
'action-patrol'               => 'ចំណាំកំណែប្រែរបស់អ្នកដទៃថាបានល្បាត',
'action-autopatrol'           => 'ផ្ដល់សិទ្ធិឱ្យគេចំណាំកំណែរបស់អ្នកថាបានល្បាត',
'action-unwatchedpages'       => 'មើលបញ្ជីនៃទំព័រមិនតាមដាន',
'action-trackback'            => 'ដាក់​ស្មើ​ការ​តាមដាម',
'action-mergehistory'         => 'ច្របាច់បញ្ចូលប្រវត្តិរបស់ទំព័រនេះ',
'action-userrights'           => 'កែប្រែសិទ្ធិរបស់អ្នកប្រើប្រាស់ទាំងអស់',
'action-userrights-interwiki' => 'កែប្រែសិទ្ធិនានារបស់អ្នកប្រើប្រាស់នៅលើវិគីដទៃ',
'action-siteadmin'            => 'ចាក់សោឬដោះសោមូលដ្ឋានទិន្នន័យ',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|បំលាស់ប្ដូរ|បំលាស់ប្ដូរ}}',
'recentchanges'                     => 'បំលាស់ប្ដូរ​ថ្មីៗ',
'recentchanges-legend'              => 'ជម្រើសនានា​ សម្រាប់ការបង្ហាញបន្លាស់ប្ដូរថ្មីៗ',
'recentchangestext'                 => 'តាមដានរាល់បន្លាស់ប្ដូររថ្មីៗបំផុត ចំពោះវិគីនៅលើទំព័រនេះ។',
'recentchanges-feed-description'    => 'តាមដាន​បន្លាស់ប្ដូរថ្មីៗ​បំផុត​នៃ​វិគី​នេះក្នុង​មតិព័ត៌មាន​នេះ​។',
'recentchanges-label-newpage'       => 'ការកែប្រែនេះបានបង្កើតទំព័រថ្មីមួយ',
'recentchanges-label-minor'         => 'នេះជាការកែប្រែតិចតួចមួយប៉ុណ្ណោះ',
'recentchanges-label-bot'           => 'ការកែប្រែនេះត្រូវបានធ្វើឡើងដោយរូបយន្ត',
'recentchanges-label-unpatrolled'   => 'ការកែប្រែនេះមិនទាន់ត្រូវបានល្បាតទេ',
'rcnote'                            => "ខាងក្រោម​នេះ​ជា​{{PLURAL:$1|១បំលាស់ប្ដូរ|'''$1'''បំលាស់ប្ដូរ}}​ចុងក្រោយក្នុងរយៈពេល​{{PLURAL:$2|ថ្ងៃ|'''$2'''ថ្ងៃ}}​ចុងក្រោយគិតត្រឹម$5 $4 ។",
'rcnotefrom'                        => "ខាងក្រោមនេះជាបំលាស់ប្ដូរនានាគិតចាប់តាំងពី '''$2''' (បង្ហាញអតិបរមា '''$1''' បំលាស់ប្ដូរ)។",
'rclistfrom'                        => 'បង្ហាញបំលាស់ប្ដូរថ្មីៗចាប់តាំងពី $1',
'rcshowhideminor'                   => '$1កំណែប្រែ​តិចតួច',
'rcshowhidebots'                    => '$1រូបយន្ត',
'rcshowhideliu'                     => '$1អ្នកប្រើប្រាស់ដែលបានកត់ឈ្មោះចូល',
'rcshowhideanons'                   => '$1អ្នកប្រើប្រាស់អនាមិក',
'rcshowhidepatr'                    => '$1កំណែប្រែដែលបានល្បាត',
'rcshowhidemine'                    => '$1កំណែប្រែរបស់ខ្ញុំ',
'rclinks'                           => 'បង្ហាញបំលាស់ប្ដូរ$1ចុងក្រោយធ្វើឡើងក្នុងរយៈពេល$2ថ្ងៃចុងក្រោយ<br />$3',
'diff'                              => 'ប្រៀបធៀប',
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
'rc-enhanced-expand'                => 'បង្ហាញព័ត៌មានលំអិត (តម្រូវអោយមាន JavaScript)',
'rc-enhanced-hide'                  => 'លាក់ព័ត៌មានលំអិត',

# Recent changes linked
'recentchangeslinked'          => 'បន្លាស់ប្ដូរពាក់ព័ន្ធ',
'recentchangeslinked-feed'     => 'បំលាស់ប្ដូរពាក់ព័ន្ធ',
'recentchangeslinked-toolbox'  => 'បន្លាស់ប្ដូរពាក់ព័ន្ធ',
'recentchangeslinked-title'    => 'បន្លាស់ប្ដូរ​ទាក់ទងនឹង "$1"',
'recentchangeslinked-noresult' => 'គ្មានបន្លាស់ប្ដូរ​លើទំព័រ​ដែលត្រូវបានតភ្ជាប់ ក្នុងថេរវេលា​ដែលត្រូវបានផ្តល់ឱ្យ ។',
'recentchangeslinked-summary'  => "នេះជាបញ្ជីបន្លាស់ប្ដូរនានា ដែលត្រូវបានធ្វើឡើងនៅលើទំព័រទាំងឡាយ ដែលមានតំណភ្ជាប់ពីទំព័រកំណត់មួយ(ឬ មានតំណភ្ជាប់ទៅទំព័រ ដែលមានក្នុងចំណាត់ថ្នាក់ក្រុមណាមួយ) នាពេលថ្មីៗនេះ ។ ទំព័រ​នានាក្នុង[[Special:Watchlist|បញ្ជីតាមដាន​របស់អ្នក]]ត្រូវបានសរសេរជា '''អក្សរដិត''' ។",
'recentchangeslinked-page'     => 'ឈ្មោះទំព័រ៖',
'recentchangeslinked-to'       => 'បង្ហាញ​បន្លាស់ប្ដូររបស់​ទំព័រដែល​មានតំណភ្ជាប់នឹង​ទំព័រ​ដែល​បាន​ផ្ដល់​ឱ្យ​​វិញ',

# Upload
'upload'                      => 'ផ្ទុកឯកសារឡើង',
'uploadbtn'                   => 'ផ្ទុកឯកសារឡើង',
'reuploaddesc'                => 'ឈប់ផ្ទុកឡើងរួចត្រឡប់ទៅបែបបទផ្ទុកឡើងវិញ។',
'upload-tryagain'             => 'ដាក់ស្នើការពណ៌នារបស់ឯកសារដែលបានកែរួច',
'uploadnologin'               => 'មិនទាន់កត់ឈ្មោះចូលទេ',
'uploadnologintext'           => 'អ្នកត្រូវតែ [[Special:UserLogin|កត់ឈ្មោះចូល]] ដើម្បីមានសិទ្ធិផ្ទុកឯកសារទាំងឡាយឡើង។',
'upload_directory_missing'    => 'ថតសំរាប់ទុកឯកសារផ្ទុកឡើង ($1) បាត់ ហើយប្រព័ន្ធបំរើការមិនអាចបង្កើតវាបានទេ។',
'upload_directory_read_only'  => 'ប្រព័ន្ធបំរើការមិនអាចសរសេរចូលទៅក្នុងថតសំរាប់ទុកឯកសារផ្ទុកឡើង ($1) ទេ។',
'uploaderror'                 => 'បញ្ហាក្នុងការផ្ទុកឡើង',
'upload-recreate-warning'     => "''ប្រយ័ត្ន៖ ឯកសារដែលមានឈ្មោះដូចគ្នានេះត្រូវបានលុបចោលឬប្ដូរទីតាំង។'''

កំណត់ហេតុស្ដីពីការលុបចោលនិងការប្ដូរទីតាំងរបស់ទំព័រនេះមានបង្ហាញនៅទីនេះ៖",
'uploadtext'                  => "សូមប្រើប្រាស់សំនុំបែបបទខាងក្រោមដើម្បីផ្ទុកឯកសារ​ឡើង។

ដើម្បីមើល ឬស្វែងរកឯកសារដែលបានផ្ទុកឡើងពីពេលមុន សូមចូលទៅ[[Special:FileList|បញ្ជីឯកសារដែលបានផ្ទុកឡើង]]។ ការផ្ទុកឡើងវិញ​នូវឯកសារបង្ហាញនៅក្នុង[[Special:Log/upload|កំណត់ហេតុនៃការផ្ទុកឯកសារឡើង]] និងការលុបចោលឯកសារមានបង្ហាញនៅក្នុង[[Special:Log/delete|កំណត់ហេតុនៃការលុប]]។


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
'filedesc'                    => 'ចំណារពន្យល់',
'fileuploadsummary'           => 'ចំណារពន្យល់៖',
'filereuploadsummary'         => 'បំលាស់ប្ដូរ​ឯកសារ​៖',
'filestatus'                  => 'ស្ថានភាពរក្សាសិទ្ធិ៖',
'filesource'                  => 'ប្រភព',
'uploadedfiles'               => 'ឯកសារដែលត្រូវបានផ្ទុកឡើង',
'ignorewarning'               => 'មិនខ្វល់​ការព្រមាន ហើយរក្សាទុក​ឯកសារ​តែម្តង។',
'ignorewarnings'              => 'មិនខ្វល់​ការព្រមាន​ណាមួយ',
'minlength1'                  => 'ឈ្មោះឯកសារ​ត្រូវមាន​យ៉ាងតិច​១​អក្សរ។',
'illegalfilename'             => 'ឈ្មោះឯកសារ "$1" មាន​អក្សរ​ហាមឃាត់​​ក្នុងចំណងជើងទំព័រ។ សូម​ប្តូរឈ្មោះ​ឯកសារ ហើយ​ព្យាយាមផ្ទុកវា​ឡើង​ម្តងទៀត។',
'badfilename'                 => 'ឈ្មោះឯកសារ បានត្រូវប្តូរ ជា "$1" ។',
'filetype-mime-mismatch'      => 'កន្ទុយរបស់ឯកសារ ".$1" មិនត្រូវគ្នានឹងប្រភេទ MIME របស់ឯកសារ ($2) ទេ។',
'filetype-badmime'            => 'ឯកសារ​ប្រភេទ MIME "$1" មិនត្រូវបាន​អនុញ្ញាត​ឱ្យផ្ទុកឡើង។',
'filetype-bad-ie-mime'        => 'មិនអាចផ្ទុកឯកសារនេះឡើងបានទេ ព្រោះInternet Explorerបានរកឃើញថាឯកសារនេះជា "$1" ដែលជាប្រភេទឯកសារហាមឃាត់និងមានគ្រោះថ្នាក់ខ្លាំង។',
'filetype-unwanted-type'      => '\'".$1"\'\'\' ជាប្រភេទឯកសារមិនចង់បាន។

{{PLURAL:$3|ប្រភេទឯកសារ|ប្រភេទឯកសារ}}ដែលគេចង់បានគឺ $2។',
'filetype-banned-type'        => "'''\".\$1\"''' គឺជា​ប្រភេទ​ឯកសារ​ដែល​មិន​ត្រូវ​បាន​គេ​អនុញ្ញាត​ទេ​។
ប្រភេទឯកសារ​ដែល​ត្រូវ​បាន​គេ​អនុញ្ញាត​គឺ \$2 ។",
'filetype-missing'            => 'ឯកសារ មិនមានកន្ទុយ (ដូចជា ".jpg")។',
'empty-file'                  => 'ឯកសារដែលអ្នកបានដាក់ស្នើគឺទទេ។',
'file-too-large'              => 'ឯកសារដែលអ្នកបានដាក់ស្នើធំពេកហើយ។',
'filename-tooshort'           => 'ឈ្មោះឯកសារខ្លីពេកហើយ។',
'filetype-banned'             => 'ឯកសារប្រភេទនេះត្រូវបានហាមប្រាម។',
'verification-error'          => 'ឯកសារឆ្លងមិនរួចពីការត្រួតពិនិត្យ។',
'illegal-filename'            => 'មិនអនុញ្ញាតអោយប្រើឈ្មោះឯកសារនេះ។',
'overwrite'                   => 'មិនអនុញ្ញាតអោយសរសេរជាន់ពីលើឯកសារដែលមានស្រាប់ហើយ។',
'unknown-error'               => 'មានបញ្ហាមិនស្គាល់មួយបានកើតឡើង។',
'tmp-create-error'            => 'មិនអាចបង្កើតឯកសារបណ្ដោះអាសន្ន។',
'tmp-write-error'             => 'មានបញ្ហាក្នុងការសរសេរឯកសារបណ្ដោះអាសន្ន។',
'large-file'                  => 'ឯកសារ​គួរតែ​មាន​​ទំហំ​មិនលើសពី $1។ ឯកសារ​នេះមាន​ទំហំ $2។',
'largefileserver'             => 'ឯកសារនេះមានទំហំធំជាងទំហំដែលម៉ាស៊ីនបម្រើការអនុញ្ញាត។',
'emptyfile'                   => 'ឯកសារដែលអ្នកបានផ្ទុកឡើងទំនងជាឯកសារទទេ។​

នេះប្រហែលជាមកពីកំហុសនៃការសរសេរឈ្មោះឯកសារ។

ចូរពិនិត្យ ថាតើអ្នកពិតជាចង់ដាក់បញ្ចេញឯកសារនេះឬក៏អត់។',
'fileexists'                  => "ឯកសារដែលមានឈ្មោះនេះមានរួចហើយ​ ចូរពិនិត្យ '''<tt>[[:$1]]</tt>''' ប្រសិនបើអ្នកមិនច្បាស់ថាតើអ្នកចង់ប្តូរវាឬក៏អត់។ [[$1|thumb]]",
'filepageexists'              => "ទំព័រពណ៌នារបស់ឯកសារនជត្រូវបានបង្កើតរួចម្ដងហើយនៅ '''<tt>[[:$1]]</tt>''' ក៏ប៉ុន្តែឯកសារដែលមានឈ្មោះដូចនេះមិនទាន់មានទេ។

ចំណារពន្យល់របស់អ្នកនឹងមិនត្រូវបានបង្ហាញនៅក្នុងទំព័រពណ៌នាឡើយ។

ដើម្បីបង្ហាញចំណារពន្យល់របស់អ្នក អ្នកត្រូវតែកែប្រែវាដោយផ្ទាល់។
[[$1|thumb]]",
'fileexists-extension'        => "មាន​ឯកសារ​មួយ​ដែល​មាន​ឈ្មោះស្រដៀង​៖ [[$2|thumb]]
* ឈ្មោះ​ឯកសារដែលបាន​ផ្ទុកឡើង​ ៖ '''<tt>[[:$1]]</tt>'''
* ឈ្មោះ​ឯកសារ​ដែល​មានស្រាប់​៖ '''<tt>[[:$2]]</tt>'''
សូម​ជ្រើសរើសឈ្មោះ​ផ្សេងទៀត។",
'fileexists-thumbnail-yes'    => "ឯកសារនេះទំនងជារូបភាពដែលបានបង្រួមទំហំ ''(កូនរូបភាព)''។
[[$1|thumb]]
សូមពិនិត្យមើលឯកសារ '''<tt>[[:$1]]</tt>'''។
បើសិនជាឯកសារដែលអ្នកបានពិនិត្យខាងលើគឺជារូបភាពតែមួយដែលមានទំហំដើម នោះអ្នកមិនចាំបាច់ផ្ទុកឡើងនូវកូនរូបភាពបន្ថែមទេ។",
'file-thumbnail-no'           => "ឈ្មោះឯកសារផ្ដើមដោយ '''<tt>$1</tt>'''។

វាទំនងជារូបភាពដែលត្រូវបានបង្រួមទំហំ ''(កូនរូបភាព)''។

ផ្ទុករូបភាពពេញទំហំប្រសិនបើអ្នកមាន បើមិនអញ្ចឹងទេសូមប្ដូរឈ្មោះឯកសារ។",
'fileexists-forbidden'        => 'មាន​ឯកសារ​ដែល​មាន​ឈ្មោះ​នេះ​រួចហើយ និង​មិន​អាច​សរសេរ​ជាន់​ពីលើ​បាន​ទេ​។ ប្រសិនបើ អ្នក​នៅតែ​ចង់​ផ្ទុក​ឯកសារ​របស់​អ្នក​ឡើង សូម​ត្រឡប់​ក្រោយ ហើយ​ប្រើ​ឈ្មោះ​ថ្មី​មួយ​ផ្សេង​វិញ​។​[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'ឯកសារដែលមានឈ្មោះនេះ មានរួចហើយនៅក្នុងកន្លែងដាក់ឯកសាររួម។

ចូរត្រឡប់ក្រោយវិញ​ហើយដាក់បញ្ចេញឯកសារនេះឡើងវិញ​ជាមួយ​នឹងឈ្មោះថ្មី។ [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'ឯកសារនេះជាច្បាប់ចម្លងរបស់ {{PLURAL:$1|ឯកសារ|ឯកសារ}}ដូចតទៅនេះ៖',
'file-deleted-duplicate'      => 'ឯកសារមួយដែលដូចគ្នាបេះបិតជាមួយឯកសារនេះ ([[:$1]]) ត្រូវបានលុបចោលម្ដងហើយ។

អ្នកគួរតែចូលទៅពិនិត្យមើលនៅក្នុងប្រវត្តិនៃការលុបចោលរបស់ឯកសារនេះមុននឹងបន្តផ្ទុកវាឡើងវិញ។',
'uploadwarning'               => 'ប្រយ័ត្ន',
'uploadwarning-text'          => 'សូមកែការពិពណ៌នារបស់ឯកសារខាងក្រោម រួចសាកល្បងម្ដងទៀត។',
'savefile'                    => 'រក្សាឯកសារទុក',
'uploadedimage'               => 'បានផ្ទុកឡើង "[[$1]]"',
'overwroteimage'              => 'បានផ្ទុកឡើងកំណែថ្មីរបស់"[[$1]]"',
'uploaddisabled'              => 'មិនអនុញ្ញាតអោយផ្ទុកឯកសារឡើង',
'copyuploaddisabled'          => 'មានអោយប្រើការផ្ទុកឡើងដោយ URL។',
'uploadfromurl-queued'        => 'ការផ្ទុកឡើងរបស់អ្នកបានដាក់ក្នុងជួរ។',
'uploaddisabledtext'          => 'ការផ្ទុកឯកសារ​ឡើង​គឺ​មិន​ត្រូវបាន​អនុញ្ញាត​ទេ​។',
'php-uploaddisabledtext'      => 'មិនអនុញ្ញាតអោយផ្ទុកឯកសារឡើងក្នុង PHP ទេ។

សូមពិនិត្យមើលការកំណត់ស្ដីអំពីការផ្ទុកឯកសារឡើង។',
'uploadscripted'              => 'ឯកសារនេះមានកូដHTMLឬស្គ្រីបដែលអាចអោយឧបករណ៍រាវរកវិបសាយមានការយល់ច្រលំ។',
'uploadvirus'                 => 'ឯកសារមានមេរោគ!

សេចក្តីលំអិត៖ $1',
'upload-source'               => 'ឯកសារប្រភព',
'sourcefilename'              => 'ឈ្មោះឯកសារប្រភព៖',
'sourceurl'                   => 'URLប្រភព៖',
'destfilename'                => 'ឈ្មោះឯកសារគោលដៅ៖',
'upload-maxfilesize'          => 'ទំហំអតិបរមារបស់ឯកសា​៖ $1',
'upload-description'          => 'ពណ៌នា',
'upload-options'              => 'ជម្រើសក្នុងការផ្ទុកឡើង',
'watchthisupload'             => 'តាមដាន​ឯកសារ​នេះ',
'filewasdeleted'              => 'ឯកសារដែលមានឈ្មោះនេះត្រូវបានដាក់បញ្ចេញមុននេះ ហើយក៏ត្រូវបានគេលុបចេញទៅវិញផងដែរ។​​​​ អ្នកគួរតែពិនិត្យ$1​មុននឹង​បន្តបញ្ចេញ​វាម្តង​ទៀត​។​',
'filename-bad-prefix'         => "ឈ្មោះរបស់រូបភាពដែលអ្នកបានផ្ទុកឡើងចាប់ផ្ដើមដោយតួអក្សរ '''\"\$1\"''' ដែលនេះជាឈ្មោះមិនបរិយាយអោយរូបភាព (ជាធម្មតាជាឈ្មោះកាមេរ៉ាឌីជីថលដាក់អោយដោយស្វ័យប្រវត្តិ)។

សូមជ្រើសរើសឈ្មោះដែលបរិយាយអោយរូបភាពរបស់អ្នក។",
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
'upload-success-subj'         => 'ផ្ទុកឯកសារឡើងបានសំរេច',
'upload-success-msg'          => 'ការផ្ទុកឡើងរបស់អ្នកពី [$2] បានទទួលជោគជ័យ។ អ្នកអាចរកវាបាននៅទៅនេះ៖ [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'បញ្ហាក្នុងការផ្ទុកឡើង',
'upload-failure-msg'          => 'មានបញ្ហាមួយជាមួយការផ្ទុកឡើងរបស់អ្នកពី [$2]៖

$1',
'upload-warning-subj'         => 'ការព្រមានស្ដីពីការផ្ទុកឡើង',
'upload-warning-msg'          => 'មានបញ្ហាកើតឡើងជាមួយការផ្ទុកឡើងពី [$2] របស់អ្នក។ អ្នកអាចត្រលប់ទៅ[[Special:Upload/stash/$1|សំនុំបែបបទផ្ទុកឡើង]] ដើប្បីដោះស្រាយបញ្ហានេះ។',

'upload-proto-error'        => 'ប្រូតូខូលមិនត្រឹមត្រូវ',
'upload-proto-error-text'   => 'ការផ្ទុកឡើងពីចម្ងាយត្រូវការ URL ដែលចាប់ផ្ដើមដោយ <code>http://</code> ឬ <code>ftp://</code>។',
'upload-file-error'         => 'បញ្ហាផ្នែកខាងក្នុង',
'upload-file-error-text'    => 'បញ្ហាផ្នែកខាងក្នុងបានកើតឡើង​ នៅពេលព្យាយាមបង្កើតឯកសារបណ្ដោះអាសន្នមួយ​នៅក្នុងម៉ាស៊ីនបម្រើការ។

សូមទំនាក់ទំនង[[Special:ListUsers/sysop|អ្នកអភិបាល]]។',
'upload-misc-error'         => 'បញ្ហាក្នុងការផ្ទុកឡើង',
'upload-misc-error-text'    => 'បញ្ហាដែលមិនស្គាល់មួយបានកើតឡើងនៅក្នុងកំឡុងពេលផ្ទុកឡើង។

ចូរផ្ទៀងផ្ទាត់ថា URL គឺមានសុពលភាពហើយអាចដំណើរការ រួចហើយ​ព្យាយាមម្តងទៀត។

ប្រសិនបើបញ្ហានៅតែកើតឡើង សូមទំនាក់ទំនង[[Special:ListUsers/sysop|អ្នកអភិបាល]]។',
'upload-too-many-redirects' => 'URLនេះមានតំនភ្ជាប់បញ្ជូនបន្តច្រើនពេកហើយ',
'upload-unknown-size'       => 'មិនដឹងទំហំ',
'upload-http-error'         => 'មានកំហុសHTTPមួយបានកើតឡើង៖ $1',

# Special:UploadStash
'uploadstash-errclear' => 'ការសំអាតឯកសារមិនបានសំរេច។',
'uploadstash-refresh'  => 'ផ្ទុកបញ្ជីឯកសារឡើងវិញ',

# img_auth script messages
'img-auth-accessdenied' => 'ហាមចូល',
'img-auth-badtitle'     => 'មិនអាចបង្កើតចំណងជើងមានសុពលភាពពី "$1"។',
'img-auth-nologinnWL'   => 'អ្នកមិនទាន់បានកត់ឈ្មោះចូល ហើយ "$1" មិនស្ថិតនៅក្នុងបញ្ជីស។',
'img-auth-nofile'       => 'គ្មានឯកសារឈ្មោះ "$1"ទេ។',
'img-auth-noread'       => 'អ្នកប្រើប្រាស់មិនមានសិទ្ធិចូលអាន "$1" ទេ។',

# HTTP errors
'http-invalid-url'      => 'URLមិនត្រឹមត្រូវ៖ $1',
'http-host-unreachable' => 'មិនអាចទៅកាន់URLបានទេ',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'មិនអាច ចូលទៅដល់ URL',
'upload-curl-error6-text'  => 'មិនអាចទៅកាន់ URL ដែលអ្នកបានផ្ដល់ឱ្យទេ។

សូមពិនិត្យឡើងវិញថា URL នោះពិតជាត្រឹមត្រូវឬអត់។',
'upload-curl-error28'      => 'ដាច់ខ្សែរយៈក្នុងការផ្ទុកឡើង',
'upload-curl-error28-text' => 'វិបសាយស៊ីពេលវែងជ្រុលក្នុងការឆ្លើយតប។

សួមពិនិត្យថាវិបសាយនេះពិតជានៅដំណើរការ រួចរង់ចាំមួយរយៈមុននឹងសាកល្បងម្ដងទៀត។

អ្នកគួរតែសាកល្បងនៅពេលដែលវិបសាយនេះមិនសូវរវល់។',

'license'            => 'អាជ្ញាប័ណ្ណ',
'license-header'     => 'ដាក់​ជា​អាជ្ញាប័ណ្ណ',
'nolicense'          => 'គ្មាន',
'license-nopreview'  => '(មិនទាន់មានការបង្ហាញការមើលជាមុនទេ)',
'upload_source_url'  => ' (URL ត្រឹមត្រូវនិងបើកចំហជាសាធារណៈ)',
'upload_source_file' => ' (ឯកសារក្នុងកុំព្យូទ័ររបស់អ្នក)',

# Special:ListFiles
'listfiles-summary'     => 'ទំព័រពិសេស​នេះ​បង្ហាញ​គ្រប់​ឯកសារ​ដែល​បានផ្ទុកឡើង។

តាម​លំនាំដើម​ឯកសារ​ដែល​បានផ្ទុកឡើង​ចុងក្រោយ​ត្រូវបាន​បង្ហាញ​នៅ​លើគេ​នៃបញ្ជីនេះ។

ចុច​លើ​ក្បាល​ជួរ​ឈរ​ដើម្បី​ផ្លាស់ប្តូរ​លំដាប់លំដោយនៃ​ការ​បង្ហាញ​។',
'listfiles_search_for'  => 'ស្វែងរកឈ្មោះមេឌា៖',
'imgfile'               => 'ឯកសារ',
'listfiles'             => 'បញ្ជីរូបភាព',
'listfiles_thumb'       => 'កូនរូបភាព',
'listfiles_date'        => 'កាលបរិច្ឆេទ',
'listfiles_name'        => 'ឈ្មោះ',
'listfiles_user'        => 'អ្នកប្រើប្រាស់',
'listfiles_size'        => 'ទំហំ',
'listfiles_description' => 'ការពិពណ៌នា',
'listfiles_count'       => 'កំណែ',

# File description page
'file-anchor-link'          => 'ឯកសារ',
'filehist'                  => 'ប្រវត្តិ​ឯកសារ',
'filehist-help'             => "ចុចលើ'''ម៉ោងនិងកាលបរិច្ឆេទ'''ដើម្បីមើលឯកសារដែលបានផ្ទុកឡើងនៅពេលនោះ។",
'filehist-deleteall'        => 'លុបចោលទាំងអស់',
'filehist-deleteone'        => 'លុបចោល',
'filehist-revert'           => 'ត្រឡប់ដើម',
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
'duplicatesoffile'          => '{{PLURAL:$1|file is a duplicate|$1 ឯកសារ​ជាច្បាប់ចម្លង}}ដូចតទៅ​នៃ​ឯកសារ​នេះ​ ([[Special:FileDuplicateSearch/$2|ព័ត៌មាន​លំអិត]])​៖',
'sharedupload'              => 'ឯកសារ​នេះ​​បាន​មក​ពី $1 និង​អាច​ត្រូវ​បាន​ប្រើប្រាស់​នៅ​គម្រោង​ដទៃ​ទៀត។',
'sharedupload-desc-there'   => 'ឯកសារ​នេះ​មក​ពី ​$1 និង​អាច​ត្រូវ​បាន​ប្រើប្រាស់​ដោយ​គម្រោង​ផ្សេង​ៗ​ដទៃ​ទៀត​។
សូម​មើល​[ទំព័របរិយាយ​ឯកសារ​ $2] សម្រាប់​ព័ត៌មាន​បន្ថែម​។',
'sharedupload-desc-here'    => 'ឯកសារនេះមានប្រភពមកពី $1  និងអាចត្រូវបានប្រើដោយគំរោងដ៏ទៃទៀត។
ការពណ៌នានៅលើ [$2 ទំព័រពណ៌នា]អំពីវា មានបង្ហាញខាងក្រោមនេះ។',
'filepage-nofile'           => 'គ្មានឯកសារ​ដែលមានឈ្មោះនេះទេ។',
'filepage-nofile-link'      => 'គ្មានរូបភាពដែលមានឈ្មោះនេះទេ។ ប៉ុន្តែអ្នកអាច [$1 ផ្ទុក​វា​ឡើង​] ។',
'uploadnewversion-linktext' => 'ផ្ទុកឡើងមួយកំណែថ្មីនៃឯកសារនេះ',
'shared-repo-from'          => 'ពី $1',
'shared-repo'               => 'ឃ្លាំងរួម​',

# File reversion
'filerevert'                => 'ត្រឡប់ $1',
'filerevert-legend'         => 'ត្រឡប់ឯកសារ',
'filerevert-intro'          => "អ្នក​កំពុង​នឹង​ចាប់​ផ្ដើម​ត្រឡប់​ឯកសារ​'''[[Media:$1|$1]]''' ទៅ​កាន់​[កំណែ​ $4 ដែលមាន​កាល​បរិច្ឆេទ​ $3, $2]។",
'filerevert-comment'        => 'មូលហេតុ៖',
'filerevert-defaultcomment' => 'បម្លែងទៅកំណែប្រែដើមជា$2, $1',
'filerevert-submit'         => 'ត្រឡប់',
'filerevert-success'        => "បានត្រឡប់ '''[[Media:$1|$1]]''' ទៅ [$4 កំណែកាលពី $3, $2]",
'filerevert-badversion'     => 'ឯកសារដែលមានកាលបរិច្ឆេទដូចដែលអ្នកផ្ដល់អោយ មិនមានកំណែពីមុននៅក្នុងវិគីនេះទេ។',

# File deletion
'filedelete'                  => 'លុប $1 ចោល',
'filedelete-legend'           => 'លុបឯកសារចោល',
'filedelete-intro'            => "អ្នករៀបនឹងលុបចេញឯកសារ '''[[Media:$1|$1]]''' ព្រមជាមួយប្រវត្តិទាំងអស់របស់វាហើយ។",
'filedelete-intro-old'        => "អ្នក​កំពុង​លុប​ចោល​កំណែរបស់ '''[[Media:$1|$1]]''' នៅ​ [$4 $3, $2]។",
'filedelete-comment'          => 'មូលហេតុ៖',
'filedelete-submit'           => 'លុបចោល',
'filedelete-success'          => "'''$1''' ត្រូវបានលុបចោលហើយ",
'filedelete-success-old'      => "កំណែ​នៃ​'''[[Media:$1|$1]]''' កាលពី​ $3, $2 ត្រូវ​បាន​លុបចោល​។",
'filedelete-nofile'           => " '''$1''' គ្មានទេ។",
'filedelete-nofile-old'       => "មិនមានកំណែរក្សាទុករបស់ '''$1''' ត្រូវនឹងលក្ខខណ្ឌដែលបានអោយទេ។",
'filedelete-otherreason'      => 'មូលហេតុបន្ថែមផ្សេងទៀត៖',
'filedelete-reason-otherlist' => 'មូលហេតុផ្សេងទៀត',
'filedelete-reason-dropdown'  => '*ហេតុផលទូទៅ
**ការបំពានទៅលើកម្មសិទ្ធិបញ្ញា
**ឯកសារជាន់គ្នា',
'filedelete-edit-reasonlist'  => 'មូលហេតុនៃការលុបការកែប្រែ',
'filedelete-maintenance'      => 'ការលុបឬស្តារឯកសារឡើងវិញត្រូវបានផ្អាកជាបណ្ដោះអាសន្ន​ក្នុងពេលធ្វើការថែទាំប្រព័ន្ធ។',

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
'unusedtemplatestext' => 'ទំព័រ​នេះ​មាន​រាយ​នាម​ទំព័រ​ទាំង​អស់​នៅ​ក្នុង​ប្រភេទ​{{ns:template}} ដែល​មិន​បាន​រាប់​បញ្ជូល​ក្នុង​ទំព័រ​ដទៃ​ទៀត​។
សូម​ចងចាំ​ក្នុង​ការ​ត្រួត​ពិនិត្យ​​តំណ​ភ្ជាប់​ផ្សេង​ៗ​ទៀត​ដែល​ភ្ជាប់​មក​ទំព័រ​គំរូ​មុន​នឹង​លុប​វា​ចោល​។',
'unusedtemplateswlh'  => 'តំណភ្ជាប់ផ្សេងៗទៀត',

# Random page
'randompage'         => 'ទំព័រចៃដន្យ',
'randompage-nopages' => 'គ្មាន​ទំព័រ​ណាមួយ​ក្នុង​{{PLURAL:$2|លំហឈ្មោះ}}នេះ​ទេ៖ "$1" ។',

# Random redirect
'randomredirect'         => 'ទំព័របញ្ជូនបន្តចៃដន្យ',
'randomredirect-nopages' => 'គ្មានទំព័របញ្ជូនបន្តណាមួយនៅក្នុងប្រភេទ "$1" ទេ។',

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
'statistics-views-total-desc'  => 'មិនរាប់បញ្ចូលការចូលមើលទំព័រដែលមិនមាននិងទំព័រពិសេសៗទេ',
'statistics-views-peredit'     => 'ចំនួនការចូលមើលក្នុងមួយកំណែប្រែ',
'statistics-users'             => '[[Special:ListUsers|អ្នកប្រើប្រាស់]]ដែលបានចុះឈ្មោះ',
'statistics-users-active'      => 'អ្នកប្រើប្រាស់សកម្ម',
'statistics-users-active-desc' => 'អ្នក​ប្រើប្រាស់​ដែល​បាន​អនុវត្ត​សកម្មភាព​ក្នុង​{{PLURAL:$1|ថ្ងៃ​}}ចុង​ក្រោយ​',
'statistics-mostpopular'       => 'ទំព័រដែលត្រូវបានមើលច្រើនបំផុត',

'disambiguations'      => 'ទំព័រមានចំណងជើងស្រដៀងគ្នា',
'disambiguationspage'  => 'Template:ស្រដៀងគ្នា',
'disambiguations-text' => "ទំព័រទាំងឡាយខាងក្រោមនេះភ្ជាប់ទៅកាន់'''ទំព័រពាក្យស្រដៀងគ្នា'''។

ទំព័រទាំងនេះគួរតែភ្ជាប់ទៅប្រធានបទត្រឹមត្រូវតែម្ដង។<br />
ទំព័រមួយត្រូវចាត់ទុកជាទំព័រពាក្យស្រដៀងគ្នា ប្រសិនបើវាប្រើទំព័រគំរូដែលភ្ជាប់មកពី[[MediaWiki:Disambiguationspage]]",

'doubleredirects'                   => 'ទំព័របញ្ជូនបន្តទ្វេដង',
'doubleredirectstext'               => 'ទំព័រនេះរាយឈ្មោះទំព័រដែលបញ្ជូនបន្តទៅទំព័របញ្ជូនបន្ដផ្សេងទៀត។

ជួរនីមួយៗមានតំនភ្ជាប់ទៅកាន់ទំព័របញ្ជូនបន្តទី១និងទី២ ព្រមជាមួយទំព័រគោលដៅរបស់ទំព័របញ្ជូនបន្តទី២(ដែលជាធម្មតានេះក៏ជាទំព័រគោលដៅ"ពិត"របស់ទំព័របញ្ជូនបន្តទី១ដែរ)។',
'double-redirect-fixed-move'        => '[[$1]] ត្រូវបានដកចេញ។

វាត្រូវបានបញ្ជូនបន្តទៅ [[$2]]',
'double-redirect-fixed-maintenance' => 'កំពុងជួសជុលការបញ្ជូនបន្តផ្ទួនគ្នាពី [[$1]] ទៅ [[$2]] ។',
'double-redirect-fixer'             => 'អ្នកជួសជុលការបញ្ជូនបន្ត',

'brokenredirects'        => 'ការបញ្ជូនបន្តដែលខូច',
'brokenredirectstext'    => 'ការបញ្ជូនបន្ដដូចតទៅនេះ​សំដៅទៅ​ទំព័រដែលមិនមាន៖',
'brokenredirects-edit'   => 'កែប្រែ',
'brokenredirects-delete' => 'លុបចេញ',

'withoutinterwiki'         => 'ទំព័រ​គ្មានតំណភ្ជាប់ភាសា',
'withoutinterwiki-summary' => 'ទំព័រខាងក្រោមនេះ​មិនមានតំណភ្ជាប់​ទៅកំណែជាភាសាដទៃទេ។',
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
'nimagelinks'             => 'ត្រូវបានប្រើនៅលើទំព័រចំនួន $1។',
'ntransclusions'          => 'ត្រូវបានប្រើនៅលើទំព័រចំនួន $1',
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
'protectedpages-indef'    => 'សំរាប់តែការការពារដែលមិនកំណត់ប៉ុណ្ណោះ',
'protectedpages-cascade'  => 'សំរាប់ការការពារជាថ្នាក់ប៉ុណ្ណោះ​',
'protectedpagestext'      => 'ទំព័រដូចតទៅនេះត្រូវបានការពារមិនឱ្យប្ដូរទីតាំងឬកែប្រែ',
'protectedpagesempty'     => '​មិន​មាន​ទំព័រ​ណា​ដែល​ត្រូវបាន​ការពារ ជាមួយប៉ារ៉ាម៉ែត​ទាំងនេះទេ។',
'protectedtitles'         => 'ចំណងជើងត្រូវបានការពារ',
'protectedtitlestext'     => 'ចំណងជើងទំព័រត្រូវបានការពារមិនឱ្យបង្កើត',
'protectedtitlesempty'    => 'មិន​មាន​ចំណងជើង​ណា​ដែល​ត្រូវ​បាន​ការពារ​ជាមួយនឹង​ប៉ារ៉ាម៉ែត​ទាំងនេះ​ទេ​នាពេលថ្មីៗនេះ។',
'listusers'               => 'បញ្ជីអ្នកប្រើប្រាស់',
'listusers-editsonly'     => 'បង្ហាញតែអ្នកប្រើប្រាស់ដែលបានកែប្រែអត្ថបទប៉ុណ្ណោះ',
'listusers-creationsort'  => 'តំរៀបតាមលំដាប់កាលបរិច្ឆេទបង្កើត',
'usereditcount'           => '$1 {{PLURAL:$1|កំណែប្រែ|កំណែប្រែ}}',
'usercreated'             => 'បានបង្កើតនៅថ្ងៃទី$1 ម៉ោង $2',
'newpages'                => 'ទំព័រថ្មីៗ',
'newpages-username'       => 'អត្តនាម៖',
'ancientpages'            => 'ទំព័រ​ចាស់ៗ',
'move'                    => 'ប្ដូរទីតាំង',
'movethispage'            => 'ប្ដូរទីតាំងទំព័រនេះ',
'unusedimagestext'        => 'ឯកសារខាងក្រោមនេះមាន ប៉ុន្តែមិនត្រូវបានបង្កប់ចូលទៅក្នុងទំព័រណាមួយទេ។

សូមកត់សំគាល់ថាវិបសាយដទៃទៀតអាចតភ្ជាប់ទៅកាន់ឯកសារមួយតាមរយៈURLផ្ទាល់ ហេតុនេះឯកសារនោះនៅតែមានក្នុងបញ្ជីនេះទោះបីជាស្ថិតក្នុងភាពកំពុងប្រើក៏ដោយ។',
'unusedcategoriestext'    => 'ចំណាត់ថ្នាក់ក្រុមដូចតទៅនេះមាន ប៉ុន្តែគ្មាទំព័រណាឬចំណាត់ថ្នាក់ណាដែលប្រើប្រាស់ពួកវាទេ។',
'notargettitle'           => 'គ្មានគោលដៅ',
'notargettext'            => 'អ្នកមិនបានផ្ដល់ឈ្មោះទំព័រឬអ្នកប្រើសំរាប់អនុវត្តសកម្មភាពនេះទេ។',
'nopagetitle'             => 'គ្មានទំព័រគោលដៅបែបនេះទេ',
'nopagetext'              => 'ទំព័រគោលដៅដែលអ្នកបានសំដៅទៅ មិនមានទេ។',
'pager-newer-n'           => '{{PLURAL:$1|ថ្មីជាង$1}}',
'pager-older-n'           => '{{PLURAL:$1|ចាស់ជាង$1}}',
'suppress'                => 'អធិការ',
'querypage-disabled'      => 'ទំព័រពិសេសនេះត្រូវបានបិទមិនអោយប្រើដោយសារមូលហេតុដំណើរការ។',

# Book sources
'booksources'               => 'ប្រភពសៀវភៅ',
'booksources-search-legend' => 'ស្វែងរកប្រភពសៀវភៅ',
'booksources-isbn'          => 'លេខ​កូដ​សៀវ​ភៅ​ ISBN ៖',
'booksources-go'            => 'ទៅ',
'booksources-text'          => 'ខាងក្រោមនេះជាបញ្ជីនៃតំណភ្ជាប់ទៅវិបសាយនានាដែលលក់​សៀវភៅថ្មីនិងជជុះ ហើយអាចផ្ដល់ព័ត៌មានបន្ថែមផ្សេងទៀតអំពីសៀវភៅដែលអ្នកកំពុងស្វែងរក៖',
'booksources-invalid-isbn'  => 'លេខISBNដែលអ្នកផ្ដល់អោយហាក់ដូចជាមិនត្រឹមត្រូវទេ។ សូមពិនិត្យក្រែងលោមានកំហុសក្នុងការចម្លងចេញពីប្រភពដើម។',

# Special:Log
'specialloguserlabel'  => 'អ្នកប្រើប្រាស់៖',
'speciallogtitlelabel' => 'ចំណងជើង៖',
'log'                  => 'កំណត់ហេតុ',
'all-logs-page'        => 'កំណត់ហេតុសាធារណៈទាំងអស់',
'alllogstext'          => 'ការបង្ហាញកំណត់ហេតុទាំងអស់របស់{{SITENAME}}។

អ្នកអាចបង្រួមការបង្ហាញដោយជ្រើសរើសប្រភេទកំណត់ហេតុ អត្តនាម ឬ ទំព័រពាក់ព័ន្ធ។',
'logempty'             => 'គ្មានអ្វីក្នុងកំណត់ហេតុដែលត្រូវនឹងអ្វីដែលអ្នកចង់រកទេ។',
'log-title-wildcard'   => 'ស្វែងរកចំណងជើងចាប់ផ្តើមដោយឃ្លានេះ',

# Special:AllPages
'allpages'          => 'ទំព័រទាំងអស់',
'alphaindexline'    => 'ពីទំព័រ $1 ដល់ទំព័រ $2',
'nextpage'          => 'ទំព័របន្ទាប់ ($1)',
'prevpage'          => 'ទំព័រមុន ($1)',
'allpagesfrom'      => 'បង្ហាញទំព័រផ្ដើមដោយ៖',
'allpagesto'        => 'បង្ហាញទំព័របញ្ជប់ដោយ៖',
'allarticles'       => 'គ្រប់ទំព័រ',
'allinnamespace'    => 'គ្រប់ទំព័រ(ប្រភេទ$1)',
'allnotinnamespace' => 'គ្រប់ទំព័រ(ក្រៅពីប្រភេទ$1)',
'allpagesprev'      => 'មុន',
'allpagesnext'      => 'បន្ទាប់',
'allpagessubmit'    => 'ទៅ',
'allpagesprefix'    => 'បង្ហាញទំព័រដែលចាប់ផ្ដើមដោយ ៖',
'allpagesbadtitle'  => 'ចំណងជើង​ទំព័រ​ដែល​ត្រូវ​បាន​ផ្តល់ឱ្យ​គឺ​គ្មាន​សុពលភាព​ឬក៏​មាន​បុព្វបទ​ដែល​មាន​អន្តរភាសា​ឬអ​ន្តរវីគី​។ ប្រហែលជា​វា​មាន​អក្សរ​មួយ​ឬ​ច្រើន ដែល​មិន​អាច​ត្រូវ​ប្រើ​នៅក្នុង​ចំណងជើង​។',
'allpages-bad-ns'   => '{{SITENAME}}មិនមានឈ្មោះប្រភេទ"$1"ទេ។',

# Special:Categories
'categories'                    => 'ចំណាត់ថ្នាក់ក្រុម',
'categoriespagetext'            => '{{PLURAL:$1|ចំណាត់ថ្នាក់ក្រុម|ចំណាត់ថ្នាក់ក្រុម}}ខាងក្រោមនេះមានអត្ថបទឬមេឌា។
[[Special:UnusedCategories|ចំណាត់ថ្នាក់ក្រុមមិនប្រើ]]ត្រូវបានបង្ហាញទីនេះ។
សូមមើលផងដែរ [[Special:WantedCategories|ចំណាត់ថ្នាក់ក្រុមដែលគ្រប់គ្នាចង់បាន]]។',
'categoriesfrom'                => 'បង្ហាញចំណាត់ថ្នាក់ក្រុមចាប់ផ្តើមដោយ៖',
'special-categories-sort-count' => 'តម្រៀបតាមចំនួន',
'special-categories-sort-abc'   => 'តម្រៀបតាមអក្ខរក្រម',

# Special:DeletedContributions
'deletedcontributions'             => 'ការរួមចំណែកដែលត្រូវបានលុបចោល',
'deletedcontributions-title'       => 'ការរួមចំណែកដែលត្រូវបានលុបចោល',
'sp-deletedcontributions-contribs' => 'ការរួមចំណែក​',

# Special:LinkSearch
'linksearch'      => 'តំណភ្ជាប់ខាង​ក្រៅ​',
'linksearch-pat'  => 'ស្វែងរកគំរូ៖',
'linksearch-ns'   => 'ប្រភេទ៖',
'linksearch-ok'   => 'ស្វែងរក',
'linksearch-text' => 'កូដពិសេសដូចជា "*.wikipedia.org" អាចប្រើបាន។<br />
ប្រូតូខូលប្រើបាន៖ <tt>$1</tt>',
'linksearch-line' => '$1បានតភ្ជាប់ពី$2',

# Special:ListUsers
'listusersfrom'      => 'បង្ហាញអ្នកប្រើប្រាស់ចាប់ផ្តើមពី៖',
'listusers-submit'   => 'បង្ហាញ',
'listusers-noresult' => 'មិនមានអ្នកប្រើប្រាស់នៅក្នុងក្រុមនេះទេ។',
'listusers-blocked'  => '(ស្ថិតក្រោមការហាមឃាត់)',

# Special:ActiveUsers
'activeusers'            => 'បញ្ជីរាយនាមអ្នកប្រើប្រាស់សកម្ម',
'activeusers-intro'      => 'នេះជាបញ្ជីរាយនាមអ្នកប្រើប្រាស់ដែលមានសកម្មភាពក្នុងរូបភាពណាមួយក្នុងរយៈពេល $1 {{PLURAL:$1|ថ្ងៃ|ថ្ងៃ}}ចុងក្រោយ។',
'activeusers-count'      => '$1 {{PLURAL:$1|កំនែប្រែ|កំនែប្រែ}}ក្នុងរយៈពេល{{PLURAL:$3|ថ្ងៃ|$3 ថ្ងៃ}}ចុងក្រោយ',
'activeusers-from'       => 'បង្ហាញអត្តនាមផ្ដើមដោយ៖',
'activeusers-hidebots'   => 'លាក់រូបយន្ត',
'activeusers-hidesysops' => 'លាក់អ្នកអភិបាល',
'activeusers-noresult'   => 'អ្នកប្រើប្រាស់​រកមិនឃើញ​។​',

# Special:Log/newusers
'newuserlogpage'     => 'កំណត់ហេតុនៃការបង្កើតគណនី',
'newuserlogpagetext' => 'នេះជាកំណត់ហេតុនៃការបង្កើតអ្នកប្រើប្រាស់។',

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
'mailnologin'          => 'មិនមានអាសយដ្ឋានផ្ញើទេ',
'mailnologintext'      => 'អ្នកត្រូវតែ [[Special:UserLogin|កត់ឈ្មោះចូល]] និង មានអាសយដ្ឋានអ៊ីមែលមានសុពលភាពមួយ ក្នុង[[Special:Preferences|ចំណង់ចំណូលចិត្ត]]របស់អ្នក ដើម្បីមានសិទ្ធិផ្ញើអ៊ីមែលទៅអ្នកប្រើប្រាស់ដទៃទៀត។',
'emailuser'            => 'ផ្ញើអ៊ីមែល​ទៅកាន់​អ្នក​ប្រើប្រាស់នេះ',
'emailpage'            => 'ផ្ញើអ៊ីមែលទៅកាន់អ្នកប្រើប្រាស់',
'emailpagetext'        => 'អ្នក​អាច​ប្រើសំនុំ​បែប​បទ​ខាង​ក្រោម​ក្នុង​ការ​ផ្ញើ​សារ​ជា​អ៊ីមែល​ទៅ​កាន់​អ្នក​ប្រើប្រាស់​នេះ​។
អាសយដ្ឋាន​អ៊ីមែល​ដែល​អ្នក​បាន​វាយ​បញ្ចូល​ក្នុង​[[Special:Preferences|ចំណង់ចំណូល​ចិត្ត​]]​របស់​អ្នក នឹង​បង្ហាញ​ជា​អាសយដ្ឋាន​អ៊ីមែល "From" ដូច្នោះ​អ្នក​ទទួល​នឹង​អាច​ឆ្លើយ​តប​ទៅ​អ្នក​វិញ​ដោយ​ផ្ទាល់​។',
'usermailererror'      => 'កំហុសឆ្គងក្នុងចំណងជើងអ៊ីមែល៖',
'defemailsubject'      => 'អ៊ីមែលពី{{SITENAME}}',
'usermaildisabled'     => 'មិនប្រើអ៊ីមែល',
'usermaildisabledtext' => 'អ្នកមិនអាចផ្ញើអ៊ីមែលទៅកាន់អ្នកប្រើប្រាស់ដទៃទៀតនៅលើវិគីនេះបានទេ',
'noemailtitle'         => 'គ្មានអាសយដ្ឋានអ៊ីមែល',
'noemailtext'          => 'អ្នកប្រើប្រាស់នេះមិនបានផ្ដល់អាសយដ្ឋានអ៊ីមែលដែលមានសុពលភាពទេ។',
'nowikiemailtitle'     => 'មិនអនុញ្ញាតអោយប្រើអ៊ីមែល',
'nowikiemailtext'      => 'អ្នក​ប្រើប្រាស់​នេះ​បាន​ជ្រើសរើស​មិន​ទទួល​អ៊ីមែល​ពីអ្នកប្រើប្រាស់​ដទៃ​ទៀត​។',
'emailnotarget'        => 'អត្តនាមអ្នកទទួលមិនត្រឹមត្រូវឬជាអត្តនាមដែលគ្មាន។',
'emailtarget'          => 'វាយបញ្ចូលអត្តនាមអ្នកទទួល',
'emailusername'        => 'អត្តនាម៖',
'emailusernamesubmit'  => 'ដាក់ស្នើ',
'email-legend'         => 'ផ្ញើអ៊ីមែលទៅអ្នកប្រើប្រាស់{{SITENAME}}ម្នាក់ទៀត',
'emailfrom'            => 'ពី៖',
'emailto'              => 'ទៅកាន់៖',
'emailsubject'         => 'ប្រធានបទ៖',
'emailmessage'         => 'សារ៖',
'emailsend'            => 'ផ្ញើ',
'emailccme'            => 'ផ្ញើអ៊ីមែលមកខ្ញុំនូវច្បាប់ចម្លងមួយនៃសាររបស់ខ្ញុំ។',
'emailccsubject'       => 'ច្បាប់ចម្លងនៃសារដែលអ្នកផ្ញើទៅកាន់ $1 ៖ $2',
'emailsent'            => 'អ៊ីមែលត្រូវបានផ្ញើទៅហើយ',
'emailsenttext'        => 'សារអ៊ីមែលរបស់អ្នកត្រូវបានផ្ញើរួចហើយ។',
'emailuserfooter'      => 'អ៊ីមែលនេះត្រូវបានផ្ញើដោយ$1ទៅកាន់$2ដោយប្រើមុខងារ"អ៊ីមែលអ្នកប្រើប្រាស់"របស់{{SITENAME}}។',

# User Messenger
'usermessage-summary' => 'ទុកសារ',
'usermessage-editor'  => 'ប្រព័ន្ធផ្ញើសារ',

# Watchlist
'watchlist'            => 'បញ្ជីតាមដានរបស់ខ្ញុំ',
'mywatchlist'          => 'បញ្ជីតាមដាន​',
'watchlistfor2'        => 'សំរាប់ $1 $2',
'nowatchlist'          => 'គ្មានអ្វីនៅក្នុងបញ្ជីតាមដានរបស់អ្នកទេ។',
'watchlistanontext'    => 'សូម $1 ដើម្បី​មើល​ឬ​កែប្រែ​របស់​ក្នុង​បញ្ជីតាមដាន​របស់អ្នក។',
'watchnologin'         => 'មិនទាន់កត់ឈ្មោះចូលទេ',
'watchnologintext'     => 'អ្នកចាំបាច់ត្រូវតែ[[Special:UserLogin|កត់ឈ្មោះចូល]]ដើម្បីកែប្រែបញ្ជីតាមដានរបស់អ្នក។',
'addedwatchtext'       => "ទំព័រ \"[[:\$1]]\" ត្រូវបានដាក់បញ្ចូលទៅក្នុង​[[Special:Watchlist|បញ្ជីតាមដាន]]របស់លោកអ្នកហើយ ។ រាល់ការផ្លាស់ប្ដូរនៃទំព័រនេះ រួមទាំងទំព័រពិភាក្សារបស់វាផងដែរ នឹងត្រូវបានដាក់បញ្ចូលក្នុងបញ្ជីនៅទីនោះ។  ទំព័រនេះនឹងបង្ហាញជា'''អក្សរដិត''' នៅក្នុង [[Special:RecentChanges|បញ្ជីបំលាស់ប្ដូរថ្មីៗ]] ងាយស្រួលក្នុងការស្វែងរក។ ប្រសិនបើលោកអ្នកចង់យកវាចេញពី [[Special:Watchlist|បញ្ជីតាមដាន]]របស់លោកអ្នក សូមចុច '''ឈប់តាមដាន''' នៅលើរបារចំហៀងផ្នែកខាងលើ។",
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


ទំព័រ $PAGETITLE នៅលើ {{SITENAME}} ត្រូវបាន $CHANGEDORCREATED ថ្ងៃ $PAGEEDITDATE ដោយ $PAGEEDITOR។ សូមមើល $PAGETITLE_URL សម្រាប់​កំណែបច្ចុប្បន្ន។

$NEWPAGE

ចំណារពន្យល់របស់អ្នកកែប្រែ៖ $PAGESUMMARY $PAGEMINOREDIT

ទាក់ទង​អ្នកកែប្រែ៖
អ៊ីមែល៖ $PAGEEDITOR_EMAIL
វិគី៖ $PAGEEDITOR_WIKI

នឹងមិនមាន​ការផ្ដល់ដំណឹង​ជាលាយលក្សណ៍អក្សរ​ផ្សេងទៀតទេ លើកលែងតែ​អ្នកចូលមើល​ទំព័រនេះរួចសិន។

អ្នកក៏អាចធ្វើ​ឱ្យ​ការផ្តល់ដំណឹង​ត្រឡប់ទៅលើកទីសូន្យ​ចំពោះគ្រប់ទំព័រ​នៃ​បញ្ជីតាមដាន​របស់អ្នក។


ពីប្រព័ន្ធផ្តល់ដំណឹង {{SITENAME}}

--
ដើម្បីផ្លាស់ប្តូរការកំណត់ការផ្ដល់ដំណឹងតាមអ៊ីមែលរបស់អ្នក, សូមទៅកាន់
{{canonicalurl:{{#special:Preferences}}}}


ដើម្បីផ្លាស់ប្តូរការកំណត់បញ្ជីតាមដានរបស់អ្នក, សូមទៅកាន់
{{canonicalurl:{{#special:EditWatchlist}}}}


ដើម្បីដកទំព័រចេញពីបញ្ជីតាមដានរបស់អ្នក, សូមទៅកាន់
$UNWATCHURL

មតិ​យោបល់​និងជំនួយបន្ថែម ៖
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'លុបទំព័រចោល',
'confirm'                => 'ផ្ទៀងផ្ទាត់បញ្ជាក់',
'excontent'              => "ខ្លឹមសារគឺ៖ '$1'",
'excontentauthor'        => "អត្ថន័យគឺ៖ '$1' (ហើយអ្នករួមចំណែកតែម្នាក់គត់គឺ '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "អត្ថន័យមុនពេលលុបចេញ៖ '$1'",
'exblank'                => 'ទំព័រទទេ',
'delete-confirm'         => 'លុប"$1"ចោល',
'delete-legend'          => 'លុបចោល',
'historywarning'         => "'''ប្រយ័ត្ន​៖''' ទំព័រដែលអ្នកបំរុងនឹងលុប មានប្រវត្តិ​ចំនួនប្រហែល $1 {{PLURAL:$1|កំណែ|កំណែ}}៖",
'confirmdeletetext'      => 'អ្នកប្រុងនឹងលុបចោលទាំងស្រុង នូវទំព័រមួយដោយរួមបញ្ចូលទាំងប្រវត្តិកែប្រែរបស់វាផង។
សូមអ្នកអះអាងថា អ្នកពិតជាមានចេតនាធ្វើបែបហ្នឹង និងថាអ្នកបានយល់ច្បាស់ពីផលវិបាកទាំងឡាយដែលអាចកើតមាន និង​សូមអះអាងថា អ្នកធ្វើស្របតាម [[{{MediaWiki:Policy-url}}|គោលការណ៍]]។',
'actioncomplete'         => 'សកម្មភាពរួចរាល់ជាស្ថាពរ',
'actionfailed'           => 'សកម្មភាព​បរាជ័យ',
'deletedtext'            => '"$1"ត្រូវបានលុបចោលរួចហើយ។

សូមមើល$2សំរាប់បញ្ជីនៃការលុបចោលនាពេលថ្មីៗ។',
'dellogpage'             => 'កំណត់ហេតុនៃការលុបចោល',
'dellogpagetext'         => 'ខាងក្រោមជាបញ្ជីនៃការលុបចោលថ្មីៗបំផុត។',
'deletionlog'            => 'កំណត់ហេតុនៃការលុបចោល',
'reverted'               => 'បានត្រឡប់ទៅកំណែមុន',
'deletecomment'          => 'មូលហេតុ៖',
'deleteotherreason'      => 'មូលហេតុបន្ថែមផ្សេងទៀត៖',
'deletereasonotherlist'  => 'មូលហេតុផ្សេងទៀត',
'deletereason-dropdown'  => '*ហេតុផលទូទៅ
** សំណើរបស់អ្នកនិពន្ធ
** បំពានកម្មសិទ្ធិបញ្ញា
** អំពើបំផ្លាញទ្រព្យសម្បត្តិឯកជនឬសាធារណៈ',
'delete-edit-reasonlist' => 'ពិនិត្យផ្ទៀងផ្ទាត់ហេតុផលនៃការលុប',
'delete-toobig'          => 'ទំព័រនេះមានប្រវត្តិកែប្រែធំលើសពី $1 {{PLURAL:$1|កំណែ|កំណែ}}។

ការលុបទំព័របែបនេះចោលត្រូវបានហាមឃាត់ ដើម្បីបង្ការកុំអោយមានការរអាក់រអួលក្នុង{{SITENAME}}។',
'delete-warning-toobig'  => 'ទំព័រនេះមានប្រវត្តិកែប្រែធំលើសពី $1 {{PLURAL:$1|កំណែ|កំណែ}}។

ការលុបទំព័របែបនេះចោលអាចធ្វើអោយមានការរអាក់រអួលប្រតិបត្តិការរបស់មូលដ្ឋានទិន្នន័យក្នុង{{SITENAME}}។

សូមបន្តសកម្មភាពនេះដោយប្រុងប្រយ័ត្ន។',

# Rollback
'rollback'         => 'ត្រឡប់កំណែប្រែ',
'rollback_short'   => 'ត្រឡប់',
'rollbacklink'     => 'ត្រឡប់',
'rollbackfailed'   => 'ការ​ត្រឡប់​ក្រោយមិនបានសំរេច',
'cantrollback'     => 'មិនអាចត្រឡប់កំណែប្រែ។ អ្នករួមចំណែកចុងក្រោយទើបជាអ្នកនិពន្ធ​របស់ទំព័រនេះ។',
'alreadyrolled'    => 'មិនអាចធ្វើការត្រឡប់ [[:$1]] ទៅកាន់កំណែចុងក្រោយរបស់អ្នកប្រើឈ្មោះ [[User:$2|$2]] ([[User talk:$2|ការពិភាក្សា]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) ទេ។

មាននរណាម្នាក់បានកែប្រែឬត្រឡប់ទំព័រនោះរួចហើយ។

កំណែប្រែចុងក្រោយរបស់ទំព័រនេះធ្វើឡើងដោយអ្នកប្រើឈ្មោះ [[User:$3|$3]] ([[User talk:$3|ការពិភាក្សា]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]])។',
'editcomment'      => "ចំណារពន្យល់ពីការកែប្រែគឺ៖ \"''\$1''\"។",
'revertpage'       => 'បានត្រឡប់កំណែប្រែដោយ[[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) ទៅកំណែប្រែចុងក្រោយដោយ [[User:$1|$1]]',
'rollback-success' => 'កំណែ​ដែល​ត្រូវ​បាន​ត្រឡប់​ដោយ​ $1។
បាន​ផ្លាស់​ប្ដូរ​ទៅ​កំណែ​ចុង​ក្រោយ​វិញ​ដោយ $2។',

# Protect
'protectlogpage'              => 'កំណត់ហេតុនៃការការពារ',
'protectlogtext'              => 'ខាងក្រោមនេះជាបញ្ជីនៃទំព័រដែលត្រូវបានចាក់សោនិងដោះសោ។

សូមមើល [[Special:ProtectedPages|បញ្ជីទំព័រត្រូវបានការពារ]] សំរាប់បញ្ជីការការពារទំព័រដែលកំពុងនៅមានសុពលភាពនៅពេលនេះ។',
'protectedarticle'            => 'បានការពារ"[[$1]]"',
'modifiedarticleprotection'   => 'បានផ្លាស់ប្តូរកម្រិតការពារនៃ"[[$1]]"',
'unprotectedarticle'          => 'ដកការការពារពី "[[$1]]"',
'movedarticleprotection'      => 'បាន​ផ្លាស់​ប្ដូរ​ទី​តាំង​ការ​កំណត់​ការ​ការពារ​ពី"[[$2]]" ទៅ​ "[[$1]]"',
'protect-title'               => 'ការពារ "$1"',
'prot_1movedto2'              => 'បានប្តូរទីតាំង [[$1]] ទៅ [[$2]]',
'protect-legend'              => 'ផ្ទៀងផ្ទាត់បញ្ជាក់ការការពារ',
'protectcomment'              => 'មូលហេតុ៖',
'protectexpiry'               => 'ផុតកំណត់៖',
'protect_expiry_invalid'      => 'ពេលវេលាផុតកំណត់ មិនត្រឹមត្រូវ។',
'protect_expiry_old'          => 'ពេលវេលាផុតកំណត់ ឋិតក្នុងអតីតកាល។',
'protect-unchain-permissions' => 'ដោះសោរជំរើសក្នុងការការពារលើសពីនេះទៀត',
'protect-text'                => "លោកអ្នកអាចមើលនិងផ្លាស់ប្ដូរកម្រិតការពារទីនេះចំពោះទំព័រ'''$1'''។",
'protect-locked-blocked'      => "អ្នកមិនអាចប្តូរកម្រិតការពារពេលស្ថិតក្រោមការហាមឃាត់ទេ។ នេះគឺជាការកំណត់ថ្មីៗសម្រាប់ទំព័រ'''$1'''៖",
'protect-locked-dblock'       => "កម្រិត​នៃ​ការ​ការពារ​មិន​អាច​ផ្លាស់​ប្ដូរ​បាន​ទេ​ ដោយសារ​មាន​ជាប់​​សោ​មូលដ្នាន​ទិន្នន័យសកម្ម​។
នេះ​គឺជា​ការ​កំណត់​បច្ចុប្បន្ន​សម្រាប់​ទំព័រ​ '''$1''' ៖",
'protect-locked-access'       => "គណនីរបស់អ្នកគ្មានការអនុញ្ញាតក្នុងការផ្លាស់ប្ដូរ កម្រិតកាពារទំព័រ ។
នេះជាការកំណត់បច្ចុប្បន្ន ចំពោះទំព័រ '''$1''' ៖",
'protect-cascadeon'           => 'បច្ចុប្បន្នទំព័រនេះត្រូវបានការពារ ព្រោះវាបានស្ថិតក្នុង {{PLURAL:$1|ទំព័រដែលមាន}} ការការពារជាថ្នាក់​។

អ្នកអាចផ្លាស់ប្តូរកម្រិតការពារនៃ ទំព័រ ប៉ុន្តែវានឹងមិនប៉ះពាល់ដល់ការការពារជាថ្នាក់ទេ។',
'protect-default'             => 'អនុញ្ញាត​អ្នក​ប្រើ​ប្រាស់​ទាំង​អស់​',
'protect-fallback'            => 'តម្រូវឱ្យមានការអនុញ្ញាតនៃ "$1"',
'protect-level-autoconfirmed' => 'ហាមឃាត់អ្នកប្រើប្រាស់ថ្មី​នឹង​អ្នក​​មិនទាន់ចុះឈ្មោះ',
'protect-level-sysop'         => 'សម្រាប់តែអ្នកថែទាំប្រព័ន្ធ',
'protect-summary-cascade'     => 'ការពារជា​ថ្នាក់',
'protect-expiring'            => 'ផុតកំណត់ $1 (UTC)',
'protect-expiring-local'      => 'ផុតកំណត់ $1',
'protect-expiry-indefinite'   => 'គ្មានកំណត់',
'protect-cascade'             => 'ការពារគ្រប់ទំព័រដែលឋិតក្នុងទំព័រនេះ (ការពារជាថ្នាក់)',
'protect-cantedit'            => 'អ្នកមិនអាចផ្លាស់ប្ដូរកម្រិតការពារនៃទំព័រនេះទេ ព្រោះអ្នកគ្មានការអនុញ្ញាតក្នុងការកែប្រែវា។',
'protect-othertime'           => 'រយៈពេលផុតកំណត់ផ្សេងទៀត៖',
'protect-othertime-op'        => 'រយៈពេលផុតកំណត់ផ្សេងទៀត',
'protect-existing-expiry'     => 'រយៈពេលផុតកំណត់មានស្រាប់៖ $3, $2',
'protect-otherreason'         => 'មូលហេតុបន្ថែមផ្សេងៗទៀត៖',
'protect-otherreason-op'      => 'មូលហេតុផ្សេងទៀត',
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
'undelete'                     => 'មើលទំព័រដែលត្រូវបានលុបចោល',
'undeletepage'                 => 'មើលហើយស្ដារឡើងវិញនូវទំព័រដែលបានលុបចោល',
'undeletepagetitle'            => "'''ខាងក្រោមនេះមានកំណែប្រែដែលត្រូវបានលុបរបស់[[:$1]]'''.",
'viewdeletedpage'              => 'មើលទំព័រដែលត្រូវបានលុបចេញ',
'undeletepagetext'             => '{{PLURAL:$1|ទំព័រខាងក្រោម|ទំព័រទាំងឡាយខាងក្រោម}}ត្រូវបានលុបចោលហើយ ប៉ុន្តែវានៅមានរក្សាទុកក្នុងឃ្លាំងសំងាត់ដែលអាចអោយធ្វើការស្ដារទំព័រនោះឡើងវិញបា។

ឃ្លាំងសំងាត់អាចនឹងត្រូវបានបោសសម្អាតជាប្រចាំ។',
'undelete-fieldset-title'      => 'ស្តារកំណែឡើងវិញ',
'undeleteextrahelp'            => "ដើម្បីស្ដារប្រវត្តិទាំងមូលរបស់ទំព័រនេះឡើងវិញ សូមទុកប្រអប់គូសទាំងអោយនៅទំនេររួចចុចលើប៊ូតុង '''''{{int:undeletebtn}}'''''។

ដើម្បីស្ដារប្រវត្តិដោយផ្នែក សូមគូសក្នុងប្រអប់ខាងមុខកំណែដែលចង់ស្ដារ រួចចុចលើប៊ូតុង '''''{{int:undeletebtn}}'''''។",
'undeleterevisions'            => '$1 {{PLURAL:$1|កំណែ​​}} បាន​ដាក់​ចូលក្នុងឃ្លាំងសំងាត់',
'undeletehistory'              => 'ប្រសិនបើអ្នកស្ដារទំព័រនេះឡើងវិញ រាល់កំណែទាំងអស់នឹងត្រូវបានស្ដារឡើងវិញក្នុងប្រវត្តិ។

ប្រសិនបើទំព័រថ្មីមួយដែលមានឈ្មោះដូចគ្នាត្រូវបានបង្កើតបន្ទាប់ពីការលុបចោល នោះកំណែដែលបានស្ដារនឹងត្រូវបង្ហាញក្នុងប្រវត្តិមុនៗ។',
'undeletehistorynoadmin'       => 'ទំព័រនេះត្រូវបានលុបចោលហើយ។
មូលហេតុចំពោះការលុបចេញ​គឺត្រូវបានបង្ហាញនៅក្នុង​ចំណារពន្យល់ខាងក្រោម ជាមួយគ្នានឹងសេចក្តីលំអិតនៃ​អ្នកប្រើប្រាស់​ដែលបានធ្វើការកែប្រែ​ទំព័រនេះ​មុនពេលវាត្រូវបាន​លុបចោល។
ឃ្លាពិតនៃ​កំណែ​​ដែលត្រូវបានលុបចោលអាចមើលបានសំរាប់តែ​អ្នកអភិបាលប៉ុណ្ណោះ។',
'undelete-revision'            => 'កំណែប្រែដែលបាន​លុបចោល​នៃ $1 (នៅថ្ងៃ​ $4, នៅម៉ោង​ $5) ដោយ​ $3៖',
'undeleterevision-missing'     => 'កំណែ​មិន​មាន​សុពលភាព​​ឬ​បាត់​បង់​។​
អ្នក​ប្រហែល​ជា​មាន​តំណ​ភ្ជាប់​មិន​ល្អ​ ឬ​កំណែ​ប្រែ​ប្រហែល​ជា​ត្រូវ​បាន​ស្ដារ​ឡើង​វិញ​ ឬ​ដក​ចេញ​ពី​ឃ្លាំងសំងាត់​។',
'undelete-nodiff'              => 'រកមិនឃើញកំណែមុនទេ។',
'undeletebtn'                  => 'ស្ដារឡើងវិញ',
'undeletelink'                 => 'មើល​/​ស្តារឡើងវិញ',
'undeleteviewlink'             => 'មើល',
'undeletereset'                => 'ធ្វើឱ្យដូចដើមវិញ',
'undeleteinvert'               => 'ក្រៅពីនោះ',
'undeletecomment'              => 'មូលហេតុ៖',
'undeletedrevisions'           => 'បានស្តារឡើងវិញនូវ{{PLURAL:$1|១កំណែ|$1កំណែ}}',
'undeletedrevisions-files'     => 'បានស្តារឡើងវិញនូវ{{PLURAL:$1|១កំណែ|$1កំណែ}}និង{{PLURAL:$2|១ឯកសារ|$2ឯកសារ}}',
'undeletedfiles'               => '{{PLURAL:$1|១ ឯកសារ|$1 ឯកសារ}} ត្រូវបានស្ដារឡើងវិញ',
'cannotundelete'               => 'ឈប់លុបមិនសម្រេច។

ប្រហែលជាមាននរណាម្នាក់ផ្សេងទៀតបានឈប់លុបទំព័រនេះមុនអ្នក។',
'undeletedpage'                => "'''$1 ត្រូវបានស្តារឡើងវិញហើយ'''

សូម​ចូល​ទៅ [[Special:Log/delete|កំណត់ហេតុ​នៃ​ការលុប]] ដើម្បី​ពិនិត្យ​មើល​កំណត់ត្រា​នៃ​ការលុប​និង​ការ​ស្ដារ​ឡើង​វិញ​។",
'undelete-header'              => 'មើលទំព័រដែលត្រូវបានលុបចោលថ្មីៗក្នុង[[Special:Log/delete|កំណត់ហេតុនៃការលុបចោល]]។',
'undelete-search-box'          => 'ស្វែងរកទំព័រដែលបានត្រូវលុបចោល',
'undelete-search-prefix'       => 'បង្ហាញទំព័រចាប់ផ្តើមដោយ៖',
'undelete-search-submit'       => 'ស្វែងរក',
'undelete-no-results'          => 'គ្មានទំព័រដែលអ្នកចង់រកនៅក្នុងឃ្លាំងផ្ទុកទំព័រលុបចោលទេ។',
'undelete-filename-mismatch'   => 'មិនអាចឈប់លុបកំណែរបស់ឯកសារដែលមានកាលបរិច្ឆេទ $1 ទេ៖ ឈ្មោះឯកសារមិនត្រូវគ្នា',
'undelete-bad-store-key'       => 'មិនអាចឈប់លុបកំណែរបស់ឯកសារដែលមានកាលបរិច្ឆេទ $1 ទេ៖ ឯកសារបាត់បង់មុនការលុបចោល',
'undelete-cleanup-error'       => 'បញ្ហាក្នុងការលុបចោលឯកសារមិនប្រើក្នុងឃ្លាំងសំងាត់ "$1" ។',
'undelete-missing-filearchive' => 'មិនអាចស្ដារឡើងវិញនូវឯកសារក្នុងឃ្លាំងដែលមានអត្តលេខ $1 ព្រោះវាគ្មាននៅក្នុងមូលដ្ឋានទិន្នន័យទេ។

ប្រហែលជាថាត្រូវបានស្ដារឡើងវិញរួចហើយ។',
'undelete-error-short'         => 'បញ្ហាក្នុងការស្ដារឯកសារ ៖  $1',
'undelete-error-long'          => 'កំហុសផ្សេងៗបានកើតឡើងក្នុងពេលកំពុងឈប់លុបឯកសារនេះ៖
$1',
'undelete-show-file-confirm'   => 'តើអ្នកប្រាកដហើយថាអ្នកពិតជាចង់មើលកំណែដែលត្រូវបានលុបចោលរបស់ឯកសារ "<nowiki>$1</nowiki>" ពីថ្ងៃ $2 ម៉ោង $3?',
'undelete-show-file-submit'    => 'បាទ/ចាស',

# Namespace form on various pages
'namespace'      => 'លំហឈ្មោះ:',
'invert'         => 'ក្រៅពីនោះ',
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

'sp-contributions-newbies'             => 'បង្ហាញតែការរួមចំណែករបស់អ្នកប្រើប្រាស់ថ្មីៗ',
'sp-contributions-newbies-sub'         => 'ចំពោះគណនីថ្មីៗ',
'sp-contributions-newbies-title'       => 'ការរួមចំណែករបស់អ្នកប្រើប្រាស់ចំពោះគណនីថ្មី',
'sp-contributions-blocklog'            => 'កំណត់ហេតុនៃការហាមឃាត់',
'sp-contributions-deleted'             => 'ការរួមចំណែកដែលត្រូវបានលុប',
'sp-contributions-uploads'             => 'ឯកសារផ្ទុកឡើង',
'sp-contributions-logs'                => 'កំណត់​ហេតុ​',
'sp-contributions-talk'                => 'ការពិភាក្សា',
'sp-contributions-userrights'          => 'ការគ្រប់គ្រងសិទ្ធិអ្នកប្រើប្រាស់',
'sp-contributions-blocked-notice'      => 'អ្នកប្រើប្រាស់នេះត្រូវបានហាមឃាត់ហើយនាពេលនេះ។
កំណត់ត្រាស្ដីពីការហាមឃាត់ចុងក្រោយមានបង្ហាញដូចខាងក្រោមនេះ៖',
'sp-contributions-blocked-notice-anon' => 'អាសយដ្ឋានIPនេះត្រូវបានហាមឃាត់ហើយនាពេលនេះ។
កំណត់ត្រាស្ដីពីការហាមឃាត់ចុងក្រោយមានបង្ហាញដូចខាងក្រោមនេះ៖',
'sp-contributions-search'              => 'ស្វែងរកការរួមចំណែក',
'sp-contributions-username'            => 'អាសយដ្ឋានIP ឬអត្តនាម៖',
'sp-contributions-toponly'             => 'បង្ហាញតែការកែប្រែណាដែលជាកំណែថ្មីៗជាងគេប៉ុណ្ណោះ',
'sp-contributions-submit'              => 'ស្វែងរក',

# What links here
'whatlinkshere'            => 'អ្វី​ដែលភ្ជាប់មកទីនេះ',
'whatlinkshere-title'      => 'ទំព័រនានាដែល​តភ្ជាប់​ទៅ "$1"',
'whatlinkshere-page'       => 'ទំព័រ៖',
'linkshere'                => "ទំព័រដូចតទៅ​នេះតភ្ជាប់មក '''[[:$1]]''' ៖",
'nolinkshere'              => "គ្មានទំព័រណាមួយតភ្ជាប់ទៅ '''[[:$1]]''' ទេ។",
'nolinkshere-ns'           => "គ្មានទំព័រណាមួយតភ្ជាប់ទៅ '''[[:$1]]''' ក្នុងប្រភេទដែលបានជ្រើសរើស។",
'isredirect'               => 'ទំព័របញ្ជូនបន្ត',
'istemplate'               => 'ការដាក់បញ្ចូល',
'isimage'                  => 'តំណភ្ជាប់ឯកសារ',
'whatlinkshere-prev'       => '{{PLURAL:$1|មុន|មុន $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|បន្ទាប់|បន្ទាប់ $1}}',
'whatlinkshere-links'      => '← តំណភ្ជាប់',
'whatlinkshere-hideredirs' => '$1ការបញ្ជូនបន្ត',
'whatlinkshere-hidetrans'  => '$1 ការរាប់បញ្ចូល',
'whatlinkshere-hidelinks'  => '$1តំណភ្ជាប់',
'whatlinkshere-hideimages' => '$1តំណភ្ជាប់រូបភាព',
'whatlinkshere-filters'    => 'តម្រងការពារនានា',

# Block/unblock
'autoblockid'                 => 'ដាក់ការហាមឃាត់ជាស្វ័យប្រវត្តិលើ #$1',
'block'                       => 'ដាក់ការហាមឃាត់លើអ្នកប្រើប្រាស់',
'unblock'                     => 'ដកការហាមឃាត់លើអ្នកប្រើប្រាស់',
'blockip'                     => 'ដាក់ការហាមឃាត់លើអ្នកប្រើប្រាស់',
'blockip-title'               => 'ដាក់ការហាមឃាត់លើអ្នកប្រើប្រាស់',
'blockip-legend'              => 'ដាក់ការហាមឃាត់លើអ្នកប្រើប្រាស់',
'blockiptext'                 => 'សូម​ប្រើប្រាស់​សំណុំ​បែបបទ​ខាងក្រោម​ដើម្បី​ហាមឃាត់ការសរសេរ​ពី​អាសយដ្ឋាន IP ឬ​ឈ្មោះ​អ្នកប្រើប្រាស់ណាមួយ​។
ការ​ធ្វើ​បែបនេះ​គួរតែ​ធ្វើឡើង​ក្នុង​គោលបំណង​បង្ការ​ការប៉ុនប៉ង​បំផ្លាញ(vandalism)ដូច​ដែល​មាន​ចែង​ក្នុង[[{{MediaWiki:Policy-url}}|គោលការណ៍]]។
សូមបំពេញមូលហេតុច្បាស់លាស់មួយខាងក្រោម (ឧទាហរណ៍៖ រាយឈ្មោះទំព័រនានាដែលត្រូវបានគេបំផ្លាញ)។',
'ipadressorusername'          => 'អាសយដ្ឋានIP ឬអត្តនាម៖',
'ipbexpiry'                   => 'រយៈពេលផុតកំណត់៖',
'ipbreason'                   => 'មូលហេតុ៖',
'ipbreasonotherlist'          => 'មូលហេតុផ្សេងទៀត',
'ipbreason-dropdown'          => '*មូលហេតុហាមឃាត់ជាទូទៅ
** ដាក់បញ្ចូលព័ត៌មានមិនពិត
** ដកខ្លឹមទាំងស្រុងពីទំព័រនានា
** ដាក់តំណភ្ជាប់ស្ប៉ាមតភ្ជាប់ទៅវិបសៃថ៍ខាងក្រៅ
** បញ្ចូលខ្លឹមសារគ្មានន័យឬខ្លឹមសារមិនអាចយល់បានទៅក្នុងទំព័រនានា
** អំពើគំរាមកំហែងឬរំលោភបំពានសិទ្ធិមនុស្ស
** ប្រើប្រាស់គណនីច្រើនក្នុងគោលបំណងមិនល្អ
** ប្រើប្រាស់ឈ្មោះដែលមិនអាចទទួលយកបាន',
'ipb-hardblock'               => 'ហាមមិនអោយអ្នកប្រើប្រាស់ដែលបានកត់ឈ្មោះចូល មិនអោយធ្វើការកែប្រែពីអាសយដ្ឋាន IP នេះ',
'ipbcreateaccount'            => 'ហាមមិនអោយបង្កើតគណនី',
'ipbemailban'                 => 'ហាមមិនអោយអ្នកប្រើប្រាស់ផ្ញើរអ៊ីមែល',
'ipbsubmit'                   => 'ដាក់ការហាមឃាត់លើអ្នកប្រើប្រាស់នេះ',
'ipbother'                    => 'រយៈពេលផ្សេងទៀត៖',
'ipboptions'                  => '២ម៉ោង:2 hours,១ថ្ងៃ:1 day,៣ថ្ងៃ:3 days,១សប្តាហ៍:1 week,២សប្តាហ៍:2 weeks,១ខែ:1 month,៣ខែ:3 months,៦ខែ:6 months,១ឆ្នាំ:1 year,គ្មានកំណត់:infinite',
'ipbotheroption'              => 'ផ្សេងៗទៀត',
'ipbotherreason'              => 'មូលហេតុ(ផ្សេងទៀតឬបន្ថែម)៖',
'ipbhidename'                 => 'លាក់​ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់​ពី​កំណែ​ប្រែ​នឹង​បញ្ជី​',
'ipbwatchuser'                => 'តាមដានទំព័រអ្នកប្រើប្រាស់និងទំព័រពិភាក្សារបស់អ្នកប្រើប្រាស់នេះ។',
'ipb-disableusertalk'         => 'ហាមអ្នកប្រើប្រាស់នេះមិនអោយធ្វើការកែប្រែទំព័រពិភាក្សារបស់ខ្លួនពេលត្រូវបានហាមឃាត់។',
'ipb-change-block'            => 'ហាមឃាត់អ្នកប្រើប្រាស់នេះឡើងវិញតាមការកំណត់ទាំងនេះ',
'ipb-confirm'                 => 'បញ្ជាក់ទទួលស្គាល់ការហាមឃាត់',
'badipaddress'                => 'អាសយដ្ឋានIPមិនត្រឹមត្រូវ',
'blockipsuccesssub'           => 'ដាក់ការហាមឃាត់បានសំរេច',
'ipb-edit-dropdown'           => 'កែប្រែមូលហេតុនៃការហាមឃាត់',
'ipb-unblock-addr'            => 'ដកការហាមឃាត់លើ $1',
'ipb-unblock'                 => 'ដកការហាមឃាត់លើអ្នកប្រើប្រាស់ ឬ អាសយដ្ឋាន IP',
'ipb-blocklist'               => 'មើលការហាមឃាត់ដែលមានហើយ',
'ipb-blocklist-contribs'      => 'ការរួមចំណែកសម្រាប់ $1',
'unblockip'                   => 'ដកការហាមឃាត់លើអ្នកប្រើប្រាស់',
'unblockiptext'               => 'សូម​ប្រើប្រាស់​ទម្រង់​បែបបទ​ខាងក្រោម​នេះ ដើម្បី​បើក​សិទ្ឋិ​សរសេរ​ឡើងវិញ សម្រាប់​អាសយដ្ឋាន​IP​ឬ​អ្នកប្រើប្រាស់​ដែល​ត្រូវ​បាន​ហាមឃាត់ពីមុន​។',
'ipusubmit'                   => 'ដក​ការហាមឃាត់នេះ​ចេញ',
'unblocked'                   => '[[User:$1|$1]] ត្រូវបានដកការហាមឃាត់ហើយ',
'unblocked-range'             => '$1 ត្រូវបានដកការហាមឃាត់ហើយ',
'unblocked-id'                => '$1 ត្រូវបានដកការហាមឃាត់ហើយ',
'blocklist'                   => 'អ្នកប្រើប្រាស់ដែលជាប់ការហាមឃាត់',
'ipblocklist'                 => 'អ្នកប្រើប្រាស់ដែលជាប់ការហាមឃាត់',
'ipblocklist-legend'          => 'ស្វែងរកអ្នកប្រើប្រាស់ដែលជាប់ការហាមឃាត់',
'blocklist-userblocks'        => 'លាក់ការហាមឃាត់លើគណនី',
'blocklist-tempblocks'        => 'លាក់ការហាមឃាត់បណ្ណោះអាសន្ន',
'blocklist-addressblocks'     => 'លាក់ការហាមឃាត់ IP ទោល',
'blocklist-timestamp'         => 'ត្រាពេលវេលា',
'blocklist-target'            => 'គោលដៅ',
'blocklist-expiry'            => 'រយៈពេលផុតកំណត់',
'blocklist-by'                => 'អ្នកអភិបាលដែលបានដាក់ការហាមឃាត់',
'blocklist-params'            => 'ប៉ារ៉ាមែត្ររបស់ការហាមឃាត់',
'blocklist-reason'            => 'មូលហេតុ',
'ipblocklist-submit'          => 'ស្វែងរក',
'infiniteblock'               => 'ជារៀងរហូត',
'expiringblock'               => 'ផុតកំណត់ នៅថ្ងៃ $1 ម៉ោង $2',
'anononlyblock'               => 'សម្រាប់តែអនាមិកជនប៉ុណ្ណោះ',
'noautoblockblock'            => 'ការហាមឃាត់ដោយស្វ័យប្រវត្តិមិនត្រូវបានអនុញ្ញាតទេ',
'createaccountblock'          => 'ការបង្កើតគណនីត្រូវបានហាមឃាត់',
'emailblock'                  => 'អ៊ីមែលដែលត្រូវបានហាមឃាត់',
'blocklist-nousertalk'        => 'មិនអាចកែប្រែទំព័រពិភាក្សាខ្លួនឯងទេ',
'ipblocklist-empty'           => 'បញ្ជីហាមឃាត់គឺទទេ។',
'ipblocklist-no-results'      => 'អាសយដ្ឋានIPឬអត្តនាមដែលបានផ្ដល់មកគឺមិនស្ថិតក្នុងបញ្ជីការហាមឃាត់ទេ។',
'blocklink'                   => 'ដាក់ការហាមឃាត់',
'unblocklink'                 => 'ដកការហាមឃាត់',
'change-blocklink'            => 'ផ្លាស់ប្ដូរការហាមឃាត់',
'contribslink'                => 'ការរួមចំណែក',
'autoblocker'                 => 'អ្នកបានត្រូវបានហាមឃាត់ដោយស្វ័យប្រវត្តិ ពីព្រោះអាសយដ្ឋានIPរបស់អ្នកត្រូវបានប្រើប្រាស់ដោយ"[[User:$1|$1]]"។ មូលហេតុលើកឡើងចំពោះការហាមឃាត់$1គឺ៖ "$2"',
'blocklogpage'                => 'កំណត់ហេតុនៃការហាមឃាត់',
'blocklogentry'               => 'បានហាមឃាត់ [[$1]]​ដោយរយៈពេលផុតកំណត់$2 $3',
'blocklogtext'                => 'នេះជាកំណត់ហេតុនៃការហាមឃាត់និងឈប់ហាមឃាត់អ្នកប្រើប្រាស់។ អាសយដ្ឋានIPដែលត្រូវបានហាមឃាត់ដោយស្វ័យប្រវត្តិមិនត្រូវបានដាក់ក្នុងបញ្ជីនេះទេ។ សូមមើល[[Special:BlockList|បញ្ជីនៃការហាមឃាត់IP]]ចំពោះបញ្ជីនៃហាមឃាត់នាថ្មីៗ។',
'unblocklogentry'             => 'បានឈប់ហាមឃាត់ $1',
'block-log-flags-anononly'    => 'សម្រាប់​តែ​អ្នកប្រើប្រាស់​អនាមិក​ប៉ុណ្ណោះ',
'block-log-flags-nocreate'    => 'ការបង្កើតគណនីត្រូវបានហាមឃាត់',
'block-log-flags-noautoblock' => 'ការហាមឃាត់ដោយស្វ័យប្រវត្តិមិនត្រូវបានអនុញ្ញាតទេ',
'block-log-flags-noemail'     => 'អ៊ីមែលត្រូវបានហាមឃាត់',
'block-log-flags-nousertalk'  => 'មិនអាចកែប្រែទំព័រពិភាក្សាផ្ទាល់ខ្លួនទេ',
'block-log-flags-hiddenname'  => 'លាក់អត្តនាម',
'ipb_expiry_invalid'          => 'កាលបរិច្ឆេទផុតកំណត់មិនត្រឹមត្រូវទេ។',
'ipb_already_blocked'         => '"$1"ត្រូវបានហាមឃាត់ហើយ',
'ipb-needreblock'             => '$1 ត្រូវ​បាន​ហាមឃាត់ហើយ​។ តើ​អ្នក​ចង់​ធ្វើការ​ផ្លាស់ប្ដូរ​ការកំណត់ឬ​?',
'ipb-otherblocks-header'      => '{{PLURAL:$1|ការហាមឃាត់|ការហាមឃាត់}}ផ្សេងទៀត',
'unblock-hideuser'            => 'អ្នកមិនអាចដកការហាមឃាត់លើអ្នកប្រើប្រាស់នេះទេ ព្រោះអត្តនាមរបស់ពួកគេត្រូវបានលាក់។',
'ipb_cant_unblock'            => 'កំហុស៖ រកមិនឃើញ ID $1  ដែលត្រូវបានហាមឃាត់ទេ ។

វាប្រហែលជាត្រូវបានគេដកការហាមឃាត់ហើយ។',
'ip_range_invalid'            => 'ដែនកំណត់ IP គ្មានសុពលភាព។',
'blockme'                     => 'ហាមឃាត់ខ្ញុំ',
'proxyblocker'                => 'កម្ម​វិធី​​រាំង​ផ្ទប់​ប្រូកស៊ី (Proxy)',
'proxyblocker-disabled'       => 'មុខងារនេះត្រូវបានអសកម្ម។',
'proxyblockreason'            => 'អាសយដ្ឋាន IP របស់អ្នកត្រូវបានរាំងខ្ទប់ហើយ ពីព្រោះវាជាប្រុកស៊ី(proxy)ចំហ។

សូមទំនាក់ទំនងអ្នកផ្ដល់សេវាអ៊ីនធឺណិតឬអ្នកបច្ចេកទេសរបស់អ្នក ហើយប្រាប់ពួកគេពីបញ្ហាសុវត្ថិភាពដ៏សំខាន់នេះ។',
'proxyblocksuccess'           => 'រួចរាល់ជាស្ថាពរ។',
'sorbsreason'                 => 'អាសយដ្ឋាន IP របស់អ្នកមានឈ្មោះក្នុងបញ្ជីប្រុកស៊ី(proxy)ចំហ នៅក្នុង DNSBL របស់ {{SITENAME}}។',
'sorbs_create_account_reason' => 'អាសយដ្ឋាន IP របស់អ្នកមានឈ្មោះក្នុងបញ្ជីប្រុកស៊ី(proxy)ចំហ នៅក្នុង DNSBL របស់ {{SITENAME}}។

អ្នកមិនអាចបង្កើតគណនីបានទេ',
'cant-block-while-blocked'    => 'អ្នកមិនអាចដាក់ការហាមឃាត់លើអ្នកប្រើប្រាស់ដទៃបានទេ ពេលកំពុងជាប់ការហាមឃាត់នោះ។',

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
'moveuserpage-warning'         => "'''ប្រយ័ត្ន៖''' អ្នកបំរុងនឹងប្ដូរទីតាំងទំព័រអ្នកប្រើប្រាស់មួយហើយ។ សូមសំគាល់ថា ទំព័រនឹងត្រូវបានប្ដូរទីតាំង ក៏ប៉ុន្តែអ្នកប្រើប្រាស់នឹង''មិន''ត្រូវបានប្ដូរឈ្មោះទេ។",
'movenologin'                  => 'មិនទាន់កត់ឈ្មោះចូលទេ',
'movenologintext'              => 'អ្នក​ត្រូវតែ​ជា​អ្នកប្រើប្រាស់​ដែល​បាន​ចុះឈ្មោះ ហើយបាន [[Special:UserLogin|កត់ឈ្មោះចូល]] ដើម្បីប្ដូរទីតាំងទំព័រមួយ។',
'movenotallowed'               => 'អ្នកមិនត្រូវបាន​អនុញ្ញាត​ឱ្យ​ប្តូរទីតាំងទំព័រ​ទេ។',
'movenotallowedfile'           => 'អ្នកគ្មានការអនុញ្ញាតអោយប្ដូរទីតាំងឯកសារនានាទេ។',
'cant-move-user-page'          => 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យប្ដូរទីតាំងទំព័រអ្នកប្រើប្រាស់នានា(ដាច់ពីអនុទំព័ររបស់វា)ទេ។',
'cant-move-to-user-page'       => 'អ្នកគ្មានការអនុញ្ញាតអោយប្ដូរទីតាំងទំព័រមួយទៅកាន់ទំព័រអ្នកប្រើប្រាស់មួយទេ (លើកលែងតែទៅកាន់ទំព័ររងមួយ)។',
'newtitle'                     => 'ទៅចំណងជើងថ្មី៖',
'move-watch'                   => 'តាមដានទំព័រនេះ',
'movepagebtn'                  => 'ប្ដូរទីតាំង',
'pagemovedsub'                 => 'ប្ដូរទីតាំងដោយជោគជ័យ',
'movepage-moved'               => '\'\'\'"$1"ត្រូវបានប្តូរទីតាំងទៅ"$2"\'\'\'ហើយ',
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
'movepage-max-pages'           => 'ទំព័រអតិបរមាចំនួន $1 {{PLURAL:$1|ទំព័រ|ទំព័រ}} ត្រូវបានប្ដូរទីតាំងហើយ ហេតុនេះនឹងគ្មានការប្ដូរទីតាំងជាស្វ័យប្រវត្តិទៀតទេ។',
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
'immobile-target-namespace-iw' => 'តំណភ្ជាប់អន្តរវិគីមិនអាចប្រើជាទំព័រគោលដៅសំរាប់ធ្វើការប្ដូរទីតាំងទំព័រទេ។',
'immobile-source-page'         => 'ទំព័រនេះមិនអាចប្ដូរទីតាំងបានទេ។',
'immobile-target-page'         => 'មិនអាចប្ដូរទីតាំងទៅកាន់ចំណងជើងគោលដៅនោះបានទេ។',
'imagenocrossnamespace'        => 'មិន​អាច​ផ្លាស់​ទី​តាំង​ឯកសារ​ទៅ​កាន់​លំហ​ឈ្មោះ​ដែល​មិន​មែន​ជា​ឯកសារ​',
'imagetypemismatch'            => 'កន្ទុយរបស់ឯកសារថ្មីមិនត្រូវគ្នានឹងប្រភេទរបស់វាទេ',
'imageinvalidfilename'         => 'ឈ្មោះឯកសារគោលដៅមិនត្រឹមត្រូវ',
'fix-double-redirects'         => 'បន្ទាន់សម័យនូវរាល់ការបញ្ជូនបន្តដែលសំដៅទៅរកចំណងជើងដើម',
'move-leave-redirect'          => 'បន្សល់ទុកតំនបញ្ជូនបន្តនៅទំព័រចាស់',
'protectedpagemovewarning'     => "'''ប្រយ័ត្ន៖''' ទំព័រនេះ​ត្រូវបានការពារ។ ដូច្នេះ​មានតែ​អ្នកប្រើប្រាស់​ដែល​មាន​អភ័យឯកសិទ្ឋិជាអភិបាលប៉ុណ្ណោះដែលអាចប្តូរទីតាំងវា។

កំណត់ត្រាស្ដីពីការរាំងការពារចុងក្រោយមានបង្ហាញដូចខាងក្រោមនេះ៖",
'semiprotectedpagemovewarning' => "'''សំគាល់៖''' ទំព័រនេះ​បានត្រូវ​ចាក់សោ។ ដូច្នេះ​មានតែអ្នកប្រើប្រាស់​ដែលបានចុះឈ្មោះ​ប៉ុណ្ណោះដែលអាចប្ដូរទីតាំងវាបាន។

កំណត់ត្រាស្ដីពីការចាក់សោចុងក្រោយមានបង្ហាញដូចខាងក្រោមនេះ៖",
'move-over-sharedrepo'         => '== ឯកសារមានហើយ ==
[[:$1]] មានរួចហើយនៅលើថតឯកសាររួម។ ការប្ដូរទីតាំងឯកសារមួយទៅកាន់ចំណងជើងនេះ នឹងត្រូវសរសេរជាន់ពីលើឯកសារដែលមានហើយ។',
'file-exists-sharedrepo'       => 'ឈ្មោះឯកសារដែលអ្នកបានជ្រើសរើសមានប្រើរួចហើយនៅក្នុងថតឯកសាររួម។

សូមជ្រើសរើសឈ្មោះថ្មីមួយទៀត។',

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
'thumbnail_image-type'     => 'មិនស្គាល់ប្រើជាមួយឯកសារប្រភេទនេះទេ',
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
'tooltip-pt-login'                => 'អ្នកត្រូវបានលើកទឹកចិត្តឱ្យកត់ឈ្មោះចូល។ ប៉ុន្តែនេះមិនមែនជាការបង្ខំទេ។',
'tooltip-pt-anonlogin'            => 'អ្នកត្រូវបានលើកទឹកចិត្តឱ្យកត់ឈ្មោះចូល មិនមែនជាការបង្ខំទេ។',
'tooltip-pt-logout'               => 'កត់ឈ្មោះចេញ',
'tooltip-ca-talk'                 => 'ការពិភាក្សា​អំពីទំព័រខ្លឹមសារ​នេះ',
'tooltip-ca-edit'                 => "អ្នកអាច​កែប្រែ​ទំព័រ​នេះ ។ សូមប្រើប្រាស់​ប៊ូតុង 'បង្ហាញការមើលមុន' មុននឹង​រក្សាទុក​វា  ។",
'tooltip-ca-addsection'           => 'ចាប់​ផ្ដើមផ្នែកថ្មីមួយក្នុងទំព័រពិភាក្សានេះ',
'tooltip-ca-viewsource'           => 'ទំព័រ​នេះ​បានត្រូវការពារ ។ អ្នកអាច​មើល​អក្សរកូដ​របស់វា ។',
'tooltip-ca-history'              => 'កំណែកន្លងមករបស់ទំព័រនេះ ។',
'tooltip-ca-protect'              => 'ការពារ​ទំព័រនេះ',
'tooltip-ca-unprotect'            => 'ផ្លាស់ប្ដូរការការពារទំព័រនេះ',
'tooltip-ca-delete'               => 'លុបទំព័រនេះចេញ',
'tooltip-ca-undelete'             => 'ស្ដារការកែប្រែនានាដែលត្រូវបានធ្វើចំពោះទំព័រនេះ មុនពេលដែលវាត្រូវបានគេលុបចោល',
'tooltip-ca-move'                 => 'ប្ដូរទីតាំង​ទំព័រនេះ',
'tooltip-ca-watch'                => 'បន្ថែមទំព័រនេះ​ទៅបញ្ជីតាមដាន​របស់អ្នក',
'tooltip-ca-unwatch'              => 'ដកចេញទំព័រនេះពីបញ្ជីតាមដានរបស់ខ្ញុំ',
'tooltip-search'                  => 'ស្វែងរកក្នុង{{SITENAME}}',
'tooltip-search-go'               => 'ទៅកាន់ទំព័រដែលមានឈ្មោះដូចបេះបិទនឹងពាក្យដែលបានបញ្ចូលនេះ ប្រសិនបើមាន',
'tooltip-search-fulltext'         => 'ស្វែងរកទំព័រនានាដែលមានពាក្យនេះ',
'tooltip-p-logo'                  => 'ទំព័រដើម',
'tooltip-n-mainpage'              => 'ចូលមើលទំព័រដើម',
'tooltip-n-mainpage-description'  => 'ចូលមើលទំព័រដើម',
'tooltip-n-portal'                => 'អំពីគម្រោង វិធីប្រើប្រាស់ និង ការស្វែងរកព័ត៌មាន',
'tooltip-n-currentevents'         => 'រកមើលព័ត៌មានទាក់ទិននឹងព្រឹត្តិការណ៍បច្ចុប្បន្ន',
'tooltip-n-recentchanges'         => 'បញ្ជី​នៃ​បន្លាស់ប្ដូរថ្មីៗ​នៅក្នុងវិគីនេះ',
'tooltip-n-randompage'            => 'ផ្ទុក​ទំព័រចៃដន្យ​មួយទំព័រ',
'tooltip-n-help'                  => 'ជំនួយ​បន្ថែម',
'tooltip-t-whatlinkshere'         => 'បញ្ជី​ទំព័វិគី​ទាំងអស់​ដែលតភ្ជាប់​នឹងទីនេះ',
'tooltip-t-recentchangeslinked'   => 'បន្លាស់ប្ដូរថ្មីៗ ក្នុងទំព័រដែលត្រូវបានភ្ជាប់មក ទំព័រនេះ',
'tooltip-feed-rss'                => 'បម្រែបម្រួល RSS ចំពោះទំព័រនេះ',
'tooltip-feed-atom'               => 'បម្រែបម្រួល Atom ចំពោះទំព័រនេះ',
'tooltip-t-contributions'         => 'បង្ហាញបញ្ជីរួមចំណែករបស់អ្នកប្រើប្រាស់នេះ',
'tooltip-t-emailuser'             => 'ផ្ញើអ៊ីមែលទៅកាន់អ្នកប្រើប្រាស់នេះ',
'tooltip-t-upload'                => 'ឯកសារផ្ទុកឡើង',
'tooltip-t-specialpages'          => 'បញ្ជីទំព័រពិសេសៗទាំងអស់',
'tooltip-t-print'                 => 'ទម្រង់សម្រាប់បោះពុម្ភរបស់ទំព័រនេះ',
'tooltip-t-permalink'             => 'តំណភ្ជាប់អចិន្ត្រៃយ៍ដែលភ្ជាប់មកកំណែនៃទំព័រនេះ',
'tooltip-ca-nstab-main'           => 'មើលទំព័រមាតិកា',
'tooltip-ca-nstab-user'           => 'មើលទំព័រអ្នកប្រើប្រាស់',
'tooltip-ca-nstab-media'          => 'មើលទំព័រមេឌា',
'tooltip-ca-nstab-special'        => 'នេះជាទំព័រពិសេស​។ អ្នកមិនអាចកែប្រែទំព័រនេះបានទេ។',
'tooltip-ca-nstab-project'        => 'មើលទំព័រគម្រោង',
'tooltip-ca-nstab-image'          => 'មើលទំព័រ​ឯកសារ',
'tooltip-ca-nstab-mediawiki'      => 'មើលសាររបស់ប្រព័ន្ធ',
'tooltip-ca-nstab-template'       => 'មើលទំព័រគំរូ',
'tooltip-ca-nstab-help'           => 'មើលទំព័រជំនួយ',
'tooltip-ca-nstab-category'       => 'មើល​ទំព័រ​ចំណាត់ថ្នាក់ក្រុម',
'tooltip-minoredit'               => "ចំណាំ​កំណែប្រែនេះ​ថាជា 'កំណែប្រែ​តិចតួច'",
'tooltip-save'                    => 'រក្សាបំលាស់ប្ដូររបស់អ្នកទុក',
'tooltip-preview'                 => 'មើលមុន​បំលាស់ប្ដូរ​របស់អ្នក។ សូមប្រើប្រាស់​វា​មុននឹង​រក្សាទុក!',
'tooltip-diff'                    => 'បង្ហាញ​បំលាស់ប្ដូរ​ដែលអ្នកបានធ្វើ​​ចំពោះអត្ថបទ។',
'tooltip-compareselectedversions' => 'មើលភាពខុសគ្នា​រវាងកំណែ​ទាំង២របស់ទំព័រ​នេះ។',
'tooltip-watch'                   => 'បន្ថែម​ទំព័រនេះ​ទៅ​បញ្ជីតាមដាន​របស់អ្នក',
'tooltip-recreate'                => 'បង្កើតទំព័រនេះឡើងវិញ ទោះបីជាវាបានត្រូវលុបចេញក៏ដោយ',
'tooltip-upload'                  => 'ចាប់ផ្តើមផ្ទុកឡើងឯកសារ',
'tooltip-rollback'                => '"ត្រឡប់​"កំណែ​ប្រែ​ធ្វើឡើងដោយអ្នក​រួម​ចំណែក​ចុង​ក្រោយ​គេ ទៅកំណែប្រែមុននោះវិញ​ ដោយគ្រាន់​តែ​ចុច​មួយ​ច្នុចប៉ុណ្ណោះ​',
'tooltip-undo'                    => '"មិន​ធ្វើ​វិញ"​ ត្រឡប់​កំណែ​នេះឡើង​វិញ​ និង​បើក​បែប​បទ​កែប្រែ​ក្នុង​ទម្រង់​មើល​ជាមុន​។
អ្នកអាចបន្ថែម​មូល​ហេតុ​នៅ​ក្នុង​សេចក្ដី​សង្ខេប​បាន។',
'tooltip-preferences-save'        => 'រក្សាទុកចំណង់ចំណូលចិត្ត',
'tooltip-summary'                 => 'បញ្ចូលចំណារពន្យល់ថ្មីមួយ',

# Stylesheets
'common.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើគ្រប់សំបកទាំងអស់ */',
'standard.css'    => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Standard */',
'nostalgia.css'   => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Nostalgia */',
'cologneblue.css' => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Cologne Blue */',
'monobook.css'    => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Monobook */',
'myskin.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក MySkin */',
'chick.css'       => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Chick */',
'simple.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Simple */',
'modern.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Modern */',
'vector.css'      => '/* CSS បានដាក់ទីនេះនឹងមានអនុភាពលើអ្នកប្រើប្រាស់នៃសំបក Vector */',

# Attribution
'anonymous'        => '{{PLURAL:$1|user|អ្នកប្រើប្រាស់}}អនាមិកនៃ {{SITENAME}}',
'siteuser'         => 'អ្នកប្រើប្រាស់{{SITENAME}} $1',
'anonuser'         => 'អ្នកប្រើប្រាស់{{SITENAME}}អនាមិក $1',
'lastmodifiedatby' => 'ទំព័រនេះត្រូវបានប្តូរចុងក្រោយដោយ$3នៅវេលា$2,$1។',
'othercontribs'    => 'ផ្អែកលើការងាររបស់$1។',
'others'           => 'ផ្សេងៗទៀត',
'siteusers'        => '{{PLURAL:$2|អ្នកប្រើប្រាស់|អ្នកប្រើប្រាស់}} {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|អ្នកប្រើប្រាស់|អ្នកប្រើប្រាស់}} {{SITENAME}} ជាអនាមិក $1',
'creditspage'      => 'ក្រេឌិតទំព័រ',
'nocredits'        => 'គ្មានព័ត៌មានក្រេឌីតសំរាប់ទំព័រនេះទេ។',

# Spam protection
'spamprotectiontitle' => 'តម្រងការពារស្ប៉ាម',
'spamprotectiontext'  => 'ទំព័រដែលអ្នកចង់រក្សាទុកត្រូវបានហាមឃាត់ដោយតម្រងការពារស្ប៉ាម។

នេះ​ប្រហែល​ជា​មកពី​ទំព័រ​នេះ​មាន​តំណភ្ជាប់​ទៅ​សៃថ៍​ខាងក្រៅ​ដែល​មាន​ឈ្មោះ​ក្នុង​បញ្ជីខ្មៅ​។',
'spamprotectionmatch' => 'អត្ថបទខាងក្រោមនេះជាបុព្វហេតុដែលធ្វើអោយតម្រងការពារស្ប៉ាមរបស់យើងដើរ៖ $1',
'spambot_username'    => 'ការសំអាតស្ប៉ាមរបស់ MediaWiki',

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

# Patrolling
'markaspatrolleddiff'    => 'ចំណាំថាបានល្បាត',
'markaspatrolledtext'    => 'ចំណាំទំព័រនេះថាបានល្បាត',
'markedaspatrolled'      => 'បានចំណាំថាបានល្បាត',
'rcpatroldisabled'       => 'បំលាស់ប្តូរថ្មីៗនៃការតាមដានមិនត្រូវបានអនុញ្ញាតទេ',
'markedaspatrollederror' => 'មិនអាចគូសចំណាំថាបានល្បាត',

# Patrol log
'patrol-log-page'      => 'កំណត់ហេតុនៃការតាមដាន',
'patrol-log-header'    => 'នេះជាកំណត់ហេតុនៃកំណែ​ប្រែ​ដែល​បាន​តាមដាន',
'log-show-hide-patrol' => 'កំណត់ហេតុនៃការតាមដាន $1',

# Image deletion
'deletedrevision'                 => 'កំណែចាស់ដែលត្រូវបានលុបចេញ $1',
'filedeleteerror-short'           => 'កំហុសនៃការលុបឯកសារ៖ $1',
'filedeleteerror-long'            => 'កំហុសពេលលុបឯកសារចេញ៖

$1',
'filedelete-missing'              => 'មិនអាចលុប ឯកសារ "$1"  ព្រោះ វាមិនមាន។',
'filedelete-old-unregistered'     => 'កំណែ "$1" របស់ឯកសារដែលអ្នកបានបញ្ជាក់គ្មាននៅក្នុងមូលដ្ឋានទិន្នន័យទេ។',
'filedelete-current-unregistered' => 'ឯកសារ "$1" មិនមាន ក្នុងមូលដ្ឋានទិន្នន័យ។',
'filedelete-archive-read-only'    => 'ម៉ាស៊ីនបម្រើសេវាវ៉ែប មិនអាច សរសេរទុក ថតបណ្ណសារ "$1" ។',

# Browsing diffs
'previousdiff' => '← កំណែប្រែមុននេះ',
'nextdiff'     => 'កំណែប្រែបន្ទាប់ →',

# Media information
'mediawarning'         => "'''ប្រយ័ត្ន''' ៖ ឯកសារនេះអាចមានផ្ទុកកូដពិសពុល។ កុំព្យូទ័ររបស់អ្នកអាចមានគ្រោះថ្នាក់បើឱ្យវាមានដំណើរការ។",
'imagemaxsize'         => "កំណត់ទំហំរូបភាព៖<br />''(លើទំព័រពិពណ៌នារូបភាព)''",
'thumbsize'            => 'ទំហំកូនរូបភាព៖',
'widthheightpage'      => '$1×$2, $3{{PLURAL:$3|ទំព័រ|ទំព័រ}}',
'file-info'            => 'ទំហំឯកសារ៖ $1, ប្រភេទ MIME ៖ $2',
'file-info-size'       => '$1 × $2 ភីកសែល ទំហំឯកសារ៖ $3 ប្រភេទ MIME៖ $4',
'file-nohires'         => 'គ្មានភាពម៉ត់ ដែលខ្ពស់ជាង។',
'svg-long-desc'        => 'ឯកសារប្រភេទSVG  $1 × $2 ភីកសែល ទំហំឯកសារ៖ $3',
'show-big-image'       => 'រូបភាពពេញ',
'show-big-image-size'  => '$1 × $2 ភីកសែ',
'file-info-gif-looped' => 'រង្វិល',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ផ្ទាំង|ផ្ទាំង}}',
'file-info-png-looped' => 'រង្វិល',
'file-info-png-repeat' => 'ចាក់ $1 ដង',
'file-info-png-frames' => '$1 ផ្ទាំង',

# Special:NewFiles
'newimages'             => 'វិចិត្រសាលរូបភាពថ្មីៗ',
'imagelisttext'         => "ខាងក្រោមនេះជាបញ្ជី'''$1'''{{PLURAL:$1|ឯកសារ|ឯកសារ}}បានរៀបតាមលំដាប់$2។",
'newimages-summary'     => 'ទំព័រពិសេសនេះបង្ហាញឯកសារដែលផ្ទុកឡើងចុងក្រោយគេ។',
'newimages-legend'      => 'តម្រងការពារ',
'newimages-label'       => 'ឈ្មោះរូបភាព៖',
'showhidebots'          => '($1រូបយន្ត)',
'noimages'              => 'គ្មានអ្វីសំរាប់មើលទេ។',
'ilsubmit'              => 'ស្វែងរក',
'bydate'                => 'តាមកាលបរិច្ឆេទ',
'sp-newimages-showfrom' => 'បង្ហាញឯកសារថ្មីៗចាប់ពី$2 $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => '$1វិ​.',
'minutes-abbrev' => '$1និ​.',
'hours-abbrev'   => '$1ម៉.',

# Bad image list
'bad_image_list' => 'ទម្រង់ ដូចតទៅ ៖

មានតែ បញ្ជីរាយមុខរបស់ (ឃ្លា ផ្តើមដោយ *) ត្រូវបាន យកជាការ ។ តំណភ្ជាប់ដំបូង នៃឃ្លា ត្រូវតែ ជាតំណភ្ជាប់ ទៅ មួយរូបភាពអន់ ។
តំណភ្ជាប់បន្ទាប់ លើឃ្លាតែមួយ ត្រូវបានយល់ថា ជា ករណីលើកលែង, ឧទាហរណ៍ ទំព័រ ដែលលើនោះ រូបភាព អាចនឹងលេចឡើង ។',

# Metadata
'metadata'          => 'ទិន្នន័យ​មេតា',
'metadata-help'     => 'ឯកសារនេះ​មាន​ព័ត៌មានបន្ថែម​ដែល​ទំនងជា​បានបន្ថែម​ពី ឧបករណ៍ថតរូបឌីជីថល ឬ ម៉ាស៊ីនស្កេន ដែលត្រូវបាន​ប្រើប្រាស់​ដើម្បីបង្កើត ឬ ធ្វើ​វា​ជា​ឌីជីថល។ បើសិនឯកសារ​បានត្រូវ​កែប្រែ​ពី ស្ថានភាពដើម នោះសេចក្តីលំអិតខ្លះ​អាចនឹងមិនអាច​​ឆ្លុះ​បញ្ចាំង​ពេញលេញទៅឯកសារ​ដែលបានកែប្រែទេ។',
'metadata-expand'   => 'បង្ហាញភាពលំអិត',
'metadata-collapse' => 'លាក់លំព័ត៌មានលំអិតដែលបានពន្លាត',
'metadata-fields'   => 'វាលទិន្នន័យមេតា​ដែលបានរាយ​បញ្ជីក្នុងសារនេះ​នឹងត្រូវដាក់ក្នុង​ទំព័រ​បង្ហាញរូបភាព ពេល​តារាង​ទិន្នន័យមេតា​ត្រូវបានបង្រួមតូច ។ ព័ត៌មាន​ដទៃទៀត​នឹងត្រូវបានបិទបាំង​តាមលំនាំដើម ។
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'                  => 'ទទឹង',
'exif-imagelength'                 => 'កម្ពស់',
'exif-bitspersample'               => '',
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
'exif-pixelydimension'             => 'ទទឹងរូបភាព',
'exif-pixelxdimension'             => 'កម្ពស់រូបភាព',
'exif-usercomment'                 => 'យោបល់របស់អ្នកប្រើប្រាស់',
'exif-relatedsoundfile'            => 'ឯកសារសំឡេងពាក់ព័ន្ធ',
'exif-datetimeoriginal'            => 'ពេលវេលានិងកាលបរិច្ឆេទបង្កើតទិន្នន័យ',
'exif-datetimedigitized'           => 'ពេលវេលានិងការបរិច្ឆេទធ្វើជាឌីជីថល',
'exif-exposuretime-format'         => '$1វិនាទី($2)',
'exif-fnumber'                     => 'លេខ F (F Number)',
'exif-shutterspeedvalue'           => 'ល្បឿន Shutter APEX (Shutter speed)',
'exif-aperturevalue'               => 'អាប៉ាឆឺ​ APEX (Aperture)',
'exif-brightnessvalue'             => 'ពន្លឺ APEX',
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
'exif-keywords'                    => 'ពាក្យគន្លឹះ​',
'exif-worldregioncreated'          => 'តំបន់នៅលើផែនដីដែលរូបនេះត្រូវបានថត',
'exif-countrycreated'              => 'ប្រទេសដែលរូបនេះត្រូវបានថត',
'exif-countrycodecreated'          => 'លេខកូដប្រទេសដែលរូបនេះត្រូវបានថត',
'exif-provinceorstatecreated'      => 'ខេត្តឬរដ្ឋដែលរូបនេះត្រូវបានថត',
'exif-citycreated'                 => 'ទីក្រុងដែលរូបនេះត្រូវបានថត',
'exif-sublocationcreated'          => 'ឈ្មោះសង្កាត់របស់ទីក្រុងដែលរួបនេះត្រូវបានថត',
'exif-worldregiondest'             => 'តំបន់លើពិភពលោកដែលត្រូវបង្ហាញ',
'exif-countrydest'                 => 'ប្រទេសដែលត្រូវបង្ហាញ',
'exif-countrycodedest'             => 'លេខកូដសំរាប់ប្រទេសដែលត្រូវបង្ហាញ',
'exif-provinceorstatedest'         => 'ខេត្តឬរដ្ឋដែលត្រូវបង្ហាញ',
'exif-citydest'                    => 'ទីក្រុងដែលត្រូវបង្ហាញ',
'exif-sublocationdest'             => 'សង្កាត់របស់ទីក្រុងដែលត្រូវបង្ហាញ',
'exif-objectname'                  => 'ចំណងជើងខ្លី',
'exif-specialinstructions'         => 'ការណែនាំពិសេស',
'exif-credit'                      => 'អ្នកផ្ដល់',
'exif-source'                      => 'ប្រភព',

# EXIF attributes
'exif-compression-1' => 'លែងបានបង្ហាប់',

'exif-copyrighted-true'  => 'រក្សាសិទ្ឋ',
'exif-copyrighted-false' => 'សាធារណៈ',

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
'exif-lightsource-10'  => 'អាកាស​ធាតុ​​មាន​ពពក​ច្រើន​',
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
'exif-gpsspeed-n' => 'ណុត',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'គីឡូម៉ែត្រ',
'exif-gpsdestdistance-m' => 'ម៉ាយល៍',

'exif-gpsdop-excellent' => 'ល្អណាស់ ($1)',
'exif-gpsdop-good'      => 'ល្អ ($1)',
'exif-gpsdop-moderate'  => 'ល្អបង្គួរ ($1)',
'exif-gpsdop-fair'      => 'មធ្យម ($1)',
'exif-gpsdop-poor'      => 'អន់ ($1)',

'exif-objectcycle-a' => 'តែពេលព្រឹកប៉ុណ្ណោះ',
'exif-objectcycle-p' => 'តែពេលល្ងាចប៉ុណ្ណោះ',
'exif-objectcycle-b' => 'ទាំងពេលព្រឹកនិងពេលល្ងាច',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ខាងជើងពិត',
'exif-gpsdirection-m' => 'ខាងជើងម៉ាញេទិក',

'exif-dc-date'      => 'កាលបរិច្ឆេទ',
'exif-dc-publisher' => 'ក្រុមហ៊ុនផ្សព្វផ្យាយ',
'exif-dc-relation'  => 'មេឌាទាក់ទិន',
'exif-dc-rights'    => 'កម្មសិទ្ធិបញ្ញា',
'exif-dc-source'    => 'ប្រភពមេឌា',
'exif-dc-type'      => 'ប្រភេទមេឌា',

'exif-rating-rejected' => 'ច្រានចោល',

'exif-isospeedratings-overflow' => 'ធំជាង 65535',

'exif-iimcategory-ace' => 'សិល្បៈ វប្បធម៌និងការកំសាន្ត',
'exif-iimcategory-clj' => 'ឧក្រិដកម្មនិងច្បាប់',
'exif-iimcategory-dis' => 'គ្រោះមហន្តរាយនិងគ្រោះថ្នាក់',
'exif-iimcategory-fin' => 'សេដ្ឋកិច្ចនិងពាណិជ្ជកម្ម',
'exif-iimcategory-edu' => 'ការអប់រំ',
'exif-iimcategory-evn' => 'បរិស្ថាន',
'exif-iimcategory-hth' => 'សុខភាព',
'exif-iimcategory-hum' => 'ចំណាប់អារម្មណ៍របស់មនុស្សជាតិ',
'exif-iimcategory-lab' => 'ពលកម្ម',
'exif-iimcategory-lif' => 'ជីវភាពរស់នៅនិងការលំហែកាយ',
'exif-iimcategory-pol' => 'នយោបាយ',
'exif-iimcategory-rel' => 'ជំនឿនិងសាសនា',
'exif-iimcategory-sci' => 'វិទ្យាសាស្ត្រនិងបច្ចេកវិទ្យា',
'exif-iimcategory-soi' => 'បញ្ហាសង្គម',
'exif-iimcategory-spo' => 'កីឡា',
'exif-iimcategory-war' => 'សង្គ្រាម ជំលោះនិងអស្ថេរភាព',
'exif-iimcategory-wea' => 'អាកាសធាតុ',

'exif-urgency-normal' => 'ធម្មតា ($1)',

# External editor support
'edit-externally'      => 'កែប្រែ​ឯកសារ​នេះដោយប្រើប្រាស់​កម្មវិធី​ខាងក្រៅ',
'edit-externally-help' => '(សូមមើល[http://www.mediawiki.org/wiki/Manual:External_editors ការណែនាំ​អំពី​ការ​ប្រើប្រាស់​]សម្រាប់​​ព័ត៌មាន​បន្ថែម)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ទាំងអស់',
'namespacesall' => 'ទាំងអស់',
'monthsall'     => 'ទាំងអស់',
'limitall'      => 'ទាំងអស់​',

# E-mail address confirmation
'confirmemail'             => 'បញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែល',
'confirmemail_noemail'     => 'អ្នកមិនមានអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវមួយ ដាក់នៅក្នុង[[Special:Preferences|ចំណង់ចំណូលចិត្ត]]របស់អ្នកទេ។',
'confirmemail_text'        => '{{SITENAME}}តំរូវអោយអ្នកបញ្ជាក់សុពលភាពរបស់អាសយដ្ឋានអ៊ីមែលរបស់អ្នកមុននឹងអ្នកមានសិទ្ធប្រើមុខងារអ៊ីមែល។

ចុចលើប៊ូតុងខាងក្រោមដើម្បីផ្ញើអ៊ីមែលបញ្ជាក់ទទួលស្គាល់ទៅកាន់អាសយដ្ឋានរបស់អ្នក។

អ៊ីមែលនេះនឹងមានលេខកូដតំណភ្ជាប់នៅក្នុងនោះ។

សូមបើកតំណភ្ជាប់នោះ ដើម្បីបញ្ជាក់សុពលភាពអាសយដ្ឋានអ៊ីមែលរបស់អ្នក។',
'confirmemail_pending'     => 'កូដបញ្ជាក់ការទទួលស្គាល់មួយ​ត្រូវ​បាន​ផ្ញើ​ជា​អ៊ីមែល​ទៅ​អ្នក​រួច​រាល់​ហើយ​។
ប្រសិន​បើ​អ្នក​ទើប​តែ​បាន​បង្កើត​គណនី​របស់​អ្នក​ថ្មី​ៗនេះ​ទេ​ អ្នក​អាច​រង់​ចាំមួយ​ផ្លែត​សិន​ដើម្បី​ឲ្យ​វា​មក​ដល់​មុន​នឹង​​ព្យាយាម​ស្នើ​សុំ​លេខ​កូដ​ថ្មី​។',
'confirmemail_send'        => 'ផ្ញើកូដបញ្ជាក់ការទទួលស្គាល់',
'confirmemail_sent'        => 'ការបញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែលត្រូវបានផ្ញើទៅរួចហើយ។',
'confirmemail_oncreate'    => 'កូដបញ្ជាក់ការទទួលស្គាល់​មួយ​ត្រូវ​បាន​ផ្ញើ​ទៅ​កាន់​អាសយដ្ឋាន​អ៊ីមែល​របស់​អ្នក​។
កូដ​នេះ​មិន​តម្រូវ​ជា​ចាំបាច់​ក្នុង​ការ​កត់ឈ្មោះចូលទេ ប៉ុន្តែ​អ្នក​នឹង​ត្រូវ​ការ​វា​ក្នុង​ការ​អនុញ្ញាត​​ ឲ្យ​មាន​មុខងារទាក់ទង​នឹង​អ៊ីមែល​ផ្សេង​ៗ​ទៀត​នៅ​ក្នុង​​វិគី​។',
'confirmemail_sendfailed'  => '{{SITENAME}} មិន​អាច​ផ្ញើ​សំបុត្រ​នៃ​ការបញ្ជាក់ទទួលស្គាល់របស់​អ្នក​។
សូម​ពិនិត្យ​អាសយដ្ឋាន​អ៊ីមែល​របស់​អ្នក​ ដោយ​រក​តួ​អក្សរ​ដែល​មិន​ត្រឹម​ត្រូវ​។

Mailer បាន​ត្រឡប់​មក​វិញ៖ $1',
'confirmemail_invalid'     => 'កូដបញ្ជាក់ទទួលស្គាល់មិនត្រឹមត្រូវទេ។
កូដនេះប្រហែលជាផុតកំណត់ហើយ។',
'confirmemail_needlogin'   => 'អ្នកត្រូវការ$1ដើម្បីបញ្ជាក់ទទួលស្គាល់អាសយដ្ឋានអ៊ីមែលរបស់អ្នក។',
'confirmemail_success'     => 'អាសយដ្ឋានអ៊ីមែលរបស់អ្នកត្រូវបានបញ្ជាក់ទទួលស្គាល់ហើយ។ ពេលនេះអ្នកអាច[[Special:UserLogin|កត់ឈ្មោះចូល]] និងចូលរួមសប្បាយរីករាយជាមួយវិគីបានហើយ។',
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

# action=watch/unwatch
'confirm-watch-button' => 'យល់ព្រម',

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
'table_pager_limit'        => 'បង្ហាញវត្ថុចំនួន $1 ក្នុងមួយទំព័រ',
'table_pager_limit_label'  => 'ចំនួនវត្ថុក្នុងមួយទំព័រ៖',
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
'lag-warn-high'   => 'ដោយសារប្រព័ន្ធបំរើការមូលដ្ឋានទិន្នន័យមានភាពយឺតយ៉ាវខ្លាំង បំលាស់ប្ដូរដែលថ្មីជាង $1 វិនាទីមិនអាចបង្ហាញបានទេនៅក្នុងបញ្ជីនេះ។',

# Watchlist editor
'watchlistedit-numitems'       => 'បញ្ជីតាមដានរបស់អ្នកមាន{{PLURAL:$1|១ចំណងជើង|$1ចំណងជើង}}ដោយមិនរាប់បញ្ចូលទំព័រពិភាក្សាទេ។',
'watchlistedit-noitems'        => 'បញ្ជីតាមដាន របស់អ្នក គ្មានផ្ទុក ចំណងជើង។',
'watchlistedit-normal-title'   => 'កែប្រែបញ្ជីតាមដាន',
'watchlistedit-normal-legend'  => 'ដកចំណងជើងចេញពីបញ្ជីតាមដាន',
'watchlistedit-normal-explain' => 'ចំណងជើងក្នុងបញ្ជីតាមដានរបស់អ្នកត្រូវបានបង្ហាញខាងក្រោម។

ដើម្បីដកចេញនូវចំណងជើងណាមួយ សូមចុច"{{int:Watchlistedit-normal-submit}}"។

អ្នកអាច[[Special:EditWatchlist/raw|កែប្រែបញ្ជីឆៅ]]ផងដែរ។',
'watchlistedit-normal-submit'  => 'ដកចំណងជើងចេញ',
'watchlistedit-normal-done'    => '{{PLURAL:$1|១ចំណងជើង|$1ចំណងជើង}}ត្រូវបានដកចេញពីបញ្ជីតាមដានរបស់អ្នក៖',
'watchlistedit-raw-title'      => 'កែប្រែបញ្ជីតាមដានឆៅ',
'watchlistedit-raw-legend'     => 'កែប្រែបញ្ជីតាមដានឆៅ',
'watchlistedit-raw-explain'    => 'ចំណងជើង​នានា លើ​បញ្ជីតាមដាន​របស់អ្នក ត្រូវបាន​បង្ហាញខាងក្រោម។ អ្នកអាចត្រូវបាន​កែប្រែ ដោយបន្ថែម​ទៅ ឬ ដកចេញ ចំណងជើងទាំងនោះពី​បញ្ជីបាន។
ក្នុងមួយជួរមានចំណងជើងមួយ។
ពេលរួចរាល់ សូមចុច "{{int:Watchlistedit-raw-submit}}"។
អ្នក​អាចផងដែរ [[Special:EditWatchlist|ប្រើប្រាស់​ឧបករណ៍កែប្រែ​គំរូ]] ។',
'watchlistedit-raw-titles'     => 'ចំណងជើង៖',
'watchlistedit-raw-submit'     => 'បន្ទាន់សម័យបញ្ជីតាមដាន',
'watchlistedit-raw-done'       => 'បញ្ជីតាមដានរបស់អ្នកត្រូវបានធ្វើឱ្យទាន់សម័យហើយ។',
'watchlistedit-raw-added'      => '{{PLURAL:$1| ចំណងជើង១បានត្រូវ|$1 ចំណងជើងបានត្រូវ}}ដាក់បន្ថែម៖',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|១ចំណងជើងបានត្រូវ|$1ចំណងជើងបានត្រូវ}}ដកចេញ៖',

# Watchlist editing tools
'watchlisttools-view' => 'មើលបំលាស់ប្ដូរពាក់ព័ន្ធ',
'watchlisttools-edit' => 'មើលនិងកែប្រែបញ្ជីតាមដាន',
'watchlisttools-raw'  => 'កែប្រែបញ្ជីតាមដានឆៅ',

# Core parser functions
'unknown_extension_tag' => 'ស្លាក​នៃផ្នែកបន្ថែម "$1" មិនស្គាល់',

# Special:Version
'version'                     => 'កំណែ',
'version-extensions'          => 'ផ្នែកបន្ថែមដែលបានដំឡើង',
'version-specialpages'        => 'ទំព័រពិសេសៗ',
'version-variables'           => 'អថេរ',
'version-antispam'            => 'ការបង្ការស្ប៉ាម',
'version-skins'               => 'សំបក',
'version-other'               => 'ផ្សេង',
'version-mediahandlers'       => 'កម្មវិធី​បើក​មេឌា​ (Media handlers)',
'version-extension-functions' => 'មុខងារផ្នែកបន្ថែម',
'version-hook-name'           => 'ឈ្មោះ​ Hook',
'version-hook-subscribedby'   => 'បានជាវ ជាប្រចាំ ដោយ',
'version-version'             => '(កំណែ $1)',
'version-license'             => 'អាជ្ញាប័ណ្ណ',
'version-poweredby-credits'   => "វិគីនេះឧបត្ថម្ភដោយ '''[http://www.mediawiki.org/ មេឌាវិគី]''', រក្សាសិទ្ធ © ២០០១-$1 $2។",
'version-poweredby-others'    => 'អ្នកដទៃទៀត',
'version-software'            => 'ផ្នែកទន់​ដែល​បានដំឡើង',
'version-software-product'    => 'ផលិតផល',
'version-software-version'    => 'កំណែ',

# Special:FilePath
'filepath'         => 'ផ្លូវនៃឯកសារ',
'filepath-page'    => 'ឯកសារ៖',
'filepath-submit'  => 'ទៅ',
'filepath-summary' => 'ទំព័រពិសេសនេះ បង្ហាញផ្លូវពេញលេញ នៃ មួយឯកសារ។
រូបភាពត្រូវបានបង្ហាញ ជាភាពម៉ត់ខ្ពស់, ប្រភេទឯកសារ ដទៃទៀត ធ្វើការដោយផ្ទាល់ ជាមួយ សហកម្មវិធី ។

បញ្ចូល ឈ្មោះឯកសារ ដោយគ្មានការភ្ជាប់ "{{ns:file}}:" នៅពីមុខវា ។',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'ស្វែងរកឯកសារដូចគ្នាបេះបិទ',
'fileduplicatesearch-legend'    => 'ស្វែងរកឯកសារដូចគ្នាបេះបិទ',
'fileduplicatesearch-filename'  => 'ឈ្មោះឯកសារ៖',
'fileduplicatesearch-submit'    => 'ស្វែងរក',
'fileduplicatesearch-info'      => '$1 × $2 ភីកសែល<br />ទំហំឯកសារ:$3<br />ប្រភេទMIME:$4',
'fileduplicatesearch-result-1'  => 'គ្មានឯកសារដែលដូចគ្នាបេះបិទទៅនឹងឯកសារ "$1" ទេ។',
'fileduplicatesearch-result-n'  => 'មាន {{PLURAL:$2|1 ឯកសារដូចគ្នាបេះបិទ|$2 ឯកសារដូចគ្នាបេះបិទ}}ទៅនឹងឯកសារ "$1"។',
'fileduplicatesearch-noresults' => 'រកមិនឃើញឯកសារដែលមានឈ្មោះ "$1" ទេ។',

# Special:SpecialPages
'specialpages'                   => 'ទំព័រ​ពិសេស​ៗ',
'specialpages-note'              => '----
* ទំព័រពិសេសៗធម្មតា។
* <span class="mw-specialpagerestricted">ទំព័រពិសេសៗដែលមានការដាក់កំហិត។</span>

* <span class="mw-specialpagecached">ទំព័រពិសេសៗសំរាប់រក្សាទុក។</span>',
'specialpages-group-maintenance' => 'របាយការណ៍នានាអំពីតំហែទាំ',
'specialpages-group-other'       => 'ទំព័រពិសេសៗផ្សេងៗទៀត',
'specialpages-group-login'       => 'កត់ឈ្មោះចូល / ចុះឈ្មោះ',
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

# Special:ComparePages
'comparepages'     => 'ប្រៀបធៀបទំព័រ',
'compare-selector' => 'ប្រៀបធៀបកំណែទំព័រ',
'compare-page1'    => 'ទំព័រ ១',
'compare-page2'    => 'ទំព័រ ២',
'compare-rev1'     => 'កំណែ ១',
'compare-rev2'     => 'កំណែ ២',
'compare-submit'   => 'ប្រៀបធៀប',

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
'htmlform-required'            => 'ចាំបាច់អោយដាក់តំលៃលេខនេះ',
'htmlform-submit'              => 'ដាក់ស្នើ',
'htmlform-reset'               => 'ធ្វើដូចដើមវិញ',
'htmlform-selectorother-other' => 'ផ្សេងទៀត',

# New logging system
'revdelete-restricted'   => 'បានអនុវត្តការដាក់កំហិតចំពោះអ្នកអភិបាល',
'revdelete-unrestricted' => 'បានដកការដាក់កំហិតចេញសម្រាប់អ្នកអភិបាល',
'newuserlog-byemail'     => 'ពាក្យសំងាត់ត្រូវបានផ្ញើតាមអ៊ីមែល',

);
