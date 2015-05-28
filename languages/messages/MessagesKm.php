<?php
/** Khmer (ភាសាខ្មែរ)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bunly
 * @author Chhorran
 * @author Kaganer
 * @author Kiensvay
 * @author Lovekhmer
 * @author Nemo bis
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
	'MyLanguage'                => array( 'ភាសារបស់ខ្ញុំ' ),
	'Mypage'                    => array( 'ទំព័ររបស់ខ្ញុំ' ),
	'Mytalk'                    => array( 'ការពិភាក្សារបស់ខ្ញុំ' ),
	'Myuploads'                 => array( 'អ្វីដែលខ្ញុំផ្ទុកឡើង' ),
	'Newimages'                 => array( 'រូបភាពថ្មីៗ' ),
	'Newpages'                  => array( 'ទំព័រថ្មីៗ' ),
	'PasswordReset'             => array( 'កំណត់ពាក្យសំងាត់ឡើងវិញ' ),
	'PermanentLink'             => array( 'តំណភ្ជាប់អចិន្ត្រែយ៍' ),

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
	'redirect'                  => array( '0', '#បញ្ជូនបន្ត', '#ប្ដូរទីតាំងទៅ', '#ប្តូរទីតាំងទៅ', '#ប្ដូរទីតាំង', '#ប្តូរទីតាំង', '#ប្ដូរចំណងជើង', '#REDIRECT' ),
	'notoc'                     => array( '0', '__លាក់មាតិកា__', '__លាក់បញ្ជីអត្ថបទ__', '__គ្មានមាតិកា__', '__គ្មានបញ្ជីអត្ថបទ__', '__កុំបង្ហាញមាតិកា__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__លាក់វិចិត្រសាល__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__បង្ខំមាតិកា__', '__បង្ខំបញ្ជីអត្ថបទ__', '__បង្ខំអោយបង្ហាញមាតិកា__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__មាតិកា__', '__បញ្ជីអត្ថបទ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__ផ្នែកមិនត្រូវកែប្រែ__', '__មិនមានផ្នែកកែប្រែ__', '__លាក់ផ្នែកកែប្រែ__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'ខែនេះ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'ឈ្មោះខែនេះ', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'ថ្ងៃនេះ', 'CURRENTDAY' ),
	'currentdayname'            => array( '1', 'ឈ្មោះថ្ងៃនេះ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ឆ្នាំនេះ', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'ពេលនេះ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ម៉ោងនេះ', 'ម៉ោងឥឡូវ', 'CURRENTHOUR' ),
	'localyear'                 => array( '1', 'LOCALDAYNAME', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'ពេលវេលាក្នុងតំបន់', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ម៉ោងតំបន់', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'ចំនួនទំព័រ', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ចំនួនអត្ថបទ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ចំនួនឯកសារ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'ចំនួនអ្នកប្រើប្រាស់', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'ចំនួនកំណែប្រែ', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'ឈ្មោះទំព័រ', 'PAGENAME' ),
	'namespace'                 => array( '1', 'លំហឈ្មោះ', 'NAMESPACE' ),
	'talkspace'                 => array( '1', 'លំហឈ្មោះទំព័រពិភាក្សា', 'TALKSPACE' ),
	'fullpagename'              => array( '1', 'ឈ្មោះទំព័រពេញ', 'FULLPAGENAME' ),
	'subpagename'               => array( '1', 'ឈ្មោះទំព័ររង', 'SUBPAGENAME' ),
	'talkpagename'              => array( '1', 'ឈ្មោះទំព័រពិភាក្សា', 'TALKPAGENAME' ),
	'msg'                       => array( '0', 'សារ:', 'MSG:' ),
	'msgnw'                     => array( '0', 'សារមិនមែនជាកូដវិគី:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'រូបភាពតូច', 'រូបតូច', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'រូបភាពតូច=$1', 'រូបតូច=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'ស្តាំ', 'ខាងស្តាំ', 'right' ),
	'img_left'                  => array( '1', 'ធ្វេង', 'ខាងធ្វេង', 'left' ),
	'img_none'                  => array( '1', 'ទទេ', 'គ្មាន', 'none' ),
	'img_width'                 => array( '1', '$1ភីកសែល', '$1ភស', '$1px' ),
	'img_center'                => array( '1', 'កណ្តាល', 'center', 'centre' ),
	'img_framed'                => array( '1', 'ស៊ុម', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'គ្មានស៊ុម', 'frameless' ),
	'img_page'                  => array( '1', 'ទំព័រ=$1', 'ទំព័រ$1', 'page=$1', 'page $1' ),
	'img_top'                   => array( '1', 'ផ្នែកលើ', 'ផ្នែកខាងលើ', 'top' ),
	'img_text_top'              => array( '1', 'ឃ្លានៅផ្នែកខាងលើ', 'ឃ្លាផ្នែកខាងលើ', 'text-top' ),
	'img_middle'                => array( '1', 'ផ្នែកកណ្តាល', 'middle' ),
	'img_bottom'                => array( '1', 'បាត', 'ផ្នែកបាត', 'bottom' ),
	'img_text_bottom'           => array( '1', 'ឃ្លានៅផ្នែកបាត', 'ឃ្លាផ្នែកបាត', 'text-bottom' ),
	'img_link'                  => array( '1', 'តំនភ្ជាប់=$1', 'តំណភ្ជាប់=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'ឈ្មោះវិបសាយ', 'ឈ្មោះគេហទំព័រ', 'SITENAME' ),
	'ns'                        => array( '0', 'លឈ:', 'NS:' ),
	'server'                    => array( '0', 'ម៉ាស៊ីនបម្រើសេវា', 'SERVER' ),
	'servername'                => array( '0', 'ឈ្មោះម៉ាស៊ីនបម្រើសេវា', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'ផ្លូវស្រ្គីប', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'វេយ្យាករណ៍:', 'GRAMMAR:' ),
	'currentweek'               => array( '1', 'សប្ដាហ៍នេះ', 'CURRENTWEEK' ),
	'plural'                    => array( '0', 'ពហុវចនៈ:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'URLពេញ:', 'FULLURL:' ),
	'displaytitle'              => array( '1', 'បង្ហាញចំណងជើង', 'បង្ហាញចំនងជើង', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'រ', 'R' ),
	'newsectionlink'            => array( '1', '__តំនភ្ជាប់ផ្នែកថ្មី__', '__តំណភ្ជាប់ផ្នែកថ្មី__', '__NEWSECTIONLINK__' ),
	'language'                  => array( '0', '#ភាសា:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'កូដភាសា', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'numberofadmins'            => array( '1', 'ចំនួនអ្នកអភិបាល', 'ចំនួនអ្នកថែទាំប្រព័ន្ធ', 'NUMBEROFADMINS' ),
	'special'                   => array( '0', 'ពិសេស', 'special' ),
	'filepath'                  => array( '0', 'ផ្លូវនៃឯកសារ:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'ប្លាក', 'tag' ),
	'hiddencat'                 => array( '1', '__ចំណាត់ថ្នាក់ក្រុមមិនបានបង្ហាញ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'ចំនួនទំព័រក្នុងចំនាត់ថ្នាក់ក្រុម', 'ចំនួនទំព័រក្នុងចំណាត់ថ្នាក់ក្រុម', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'ទំហំទំព័រ', 'PAGESIZE' ),
	'index'                     => array( '1', '__លិបិក្រម__', '__INDEX__' ),
	'noindex'                   => array( '1', '__មិនមានលិបិក្រម__', '__NOINDEX__' ),
	'staticredirect'            => array( '1', '__ស្ថិតិទំព័របញ្ជូនបន្ត__', '__STATICREDIRECT__' ),
);

