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

$namespaceNames = [
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
];

$namespaceAliases = [
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
];

$digitTransformTable = [
	'0' => '០', # U+17E0
	'1' => '១', # U+17E1
	'2' => '២', # U+17E2
	'3' => '៣', # U+17E3
	'4' => '៤', # U+17E4
	'5' => '៥', # U+17E5
	'6' => '៦', # U+17E6
	'7' => '៧', # U+17E7
	'8' => '៨', # U+17E8
	'9' => '៩', # U+17E9
];

$separatorTransformTable = [
	'.' => ','
];

$datePreferences = [
	'default',
	'km',
	'ISO 8601',
];

$defaultDateFormat = 'km';

$dateFormats = [
	'km time' => 'ម៉ោងH:i',
	'km date' => 'l ទីd F ឆ្នាំY',
	'km both' => 'ម៉ោងH:i l ទីd F ឆ្នាំY',
];

$specialPageAliases = [
	'Activeusers'               => [ 'អ្នកប្រើប្រាស់សកម្ម' ],
	'Allmessages'               => [ 'គ្រប់សារ' ],
	'Allpages'                  => [ 'គ្រប់ទំព័រ' ],
	'Ancientpages'              => [ 'ទំព័រចាស់ៗ' ],
	'Blankpage'                 => [ 'ទំព័រទទេ' ],
	'Block'                     => [ 'រាំងខ្ទប់IP' ],
	'Booksources'               => [ 'ប្រភពសៀវភៅ' ],
	'BrokenRedirects'           => [ 'ការបញ្ជូនបន្តដែលខូច' ],
	'Categories'                => [ 'ចំណាត់ថ្នាក់ក្រុម' ],
	'ChangePassword'            => [ 'ដាក់ពាក្យសំងាត់ថ្មីឡើងវិញ' ],
	'ComparePages'              => [ 'ប្រៀបធៀបទំព័រ' ],
	'Confirmemail'              => [ 'បញ្ជាក់ទទួលស្គាល់អ៊ីមែល' ],
	'Contributions'             => [ 'ការរួមចំណែក' ],
	'CreateAccount'             => [ 'បង្កើតគណនី' ],
	'Deadendpages'              => [ 'ទំព័រទាល់' ],
	'DeletedContributions'      => [ 'ការរួមចំណែកដែលត្រូវបានលុបចោល' ],
	'DoubleRedirects'           => [ 'ការបញ្ជូនបន្តទ្វេដង' ],
	'Emailuser'                 => [ 'អ្នកប្រើប្រាស់អ៊ីមែល' ],
	'Export'                    => [ 'នាំចេញ' ],
	'Fewestrevisions'           => [ 'ទំព័រមានកំណែតិចជាងគេ' ],
	'FileDuplicateSearch'       => [ 'ស្វែងរកឯកសារដូចគ្នាបេះបិទ' ],
	'Filepath'                  => [ 'ផ្លូវនៃឯកសារ' ],
	'Import'                    => [ 'នាំចូល' ],
	'Invalidateemail'           => [ 'អ៊ីមែលមិនត្រឹមត្រូវ' ],
	'BlockList'                 => [ 'បញ្ជីហាមឃាត់IP' ],
	'LinkSearch'                => [ 'ស្វែងរកតំណភ្ជាប់' ],
	'Listadmins'                => [ 'បញ្ជីអ្នកអភិបាល' ],
	'Listbots'                  => [ 'បញ្ជីរូបយន្ត' ],
	'Listfiles'                 => [ 'បញ្ជីរូបភាព' ],
	'Listgrouprights'           => [ 'បញ្ជីក្រុមសិទ្ធិ' ],
	'Listredirects'             => [ 'បញ្ជីទំព័របញ្ជូនបន្ត' ],
	'Listusers'                 => [ 'បញ្ជីឈ្មោះអ្នកប្រើប្រាស់' ],
	'Lockdb'                    => [ 'ចាក់សោមូលដ្ឋានទិន្នន័យ' ],
	'Log'                       => [ 'កំណត់ហេតុ' ],
	'Lonelypages'               => [ 'ទំព័រកំព្រា' ],
	'Longpages'                 => [ 'ទំព័រវែងៗ' ],
	'MergeHistory'              => [ 'ច្របាច់បញ្ជូលប្រវត្តិ' ],
	'MIMEsearch'                => [ 'MIMEស្វែងរក' ],
	'Mostcategories'            => [ 'ទំព័រមានចំណាត់ថ្នាក់ច្រើនជាងគេ' ],
	'Mostimages'                => [ 'ទំព័រមានរូបភាពច្រើនជាងគេ' ],
	'Mostlinked'                => [ 'ទំព័រមានតំណភ្ជាប់មកច្រើនជាងគេ' ],
	'Mostlinkedcategories'      => [ 'ចំណាត់ថ្នាក់ក្រុមមានតំណភ្ជាប់មកច្រើនជាងគេ' ],
	'Mostlinkedtemplates'       => [ 'ទំព័រគំរូមានតំណភ្ជាប់មកច្រើនជាងគេ' ],
	'Mostrevisions'             => [ 'ទំព័រមានកំណែច្រើនជាងគេ' ],
	'Movepage'                  => [ 'ប្ដូរទីតាំងទំព័រ' ],
	'Mycontributions'           => [ 'ការរួមចំណែករបស់ខ្ញុំ' ],
	'MyLanguage'                => [ 'ភាសារបស់ខ្ញុំ' ],
	'Mypage'                    => [ 'ទំព័ររបស់ខ្ញុំ' ],
	'Mytalk'                    => [ 'ការពិភាក្សារបស់ខ្ញុំ' ],
	'Myuploads'                 => [ 'អ្វីដែលខ្ញុំផ្ទុកឡើង' ],
	'Newimages'                 => [ 'រូបភាពថ្មីៗ' ],
	'Newpages'                  => [ 'ទំព័រថ្មីៗ' ],
	'PasswordReset'             => [ 'កំណត់ពាក្យសំងាត់ឡើងវិញ' ],
	'PermanentLink'             => [ 'តំណភ្ជាប់អចិន្ត្រែយ៍' ],
	'Preferences'               => [ 'ចំណង់ចំណូលចិត្ត' ],
	'Prefixindex'               => [ 'លិបិក្រមបុព្វបទ' ],
	'Protectedpages'            => [ 'ទំព័របានការពារ' ],
	'Protectedtitles'           => [ 'ចំណងជើងបានការពារ' ],
	'Randompage'                => [ 'ទំព័រចៃដន្យ' ],
	'Randomredirect'            => [ 'ការបញ្ជូនបន្តដោយចៃដន្យ' ],
	'Recentchanges'             => [ 'បំលាស់ប្ដូរថ្មីៗ' ],
	'Recentchangeslinked'       => [ 'បំលាស់ប្ដូរទាក់ទិន' ],
	'Revisiondelete'            => [ 'កំណែបានលុបចោល' ],
	'Search'                    => [ 'ស្វែងរក' ],
	'Shortpages'                => [ 'ទំព័រខ្លីៗ' ],
	'Specialpages'              => [ 'ទំព័រពិសេសៗ' ],
	'Statistics'                => [ 'ស្ថិតិ' ],
	'Tags'                      => [ 'ប្លាក' ],
	'Unblock'                   => [ 'ឈប់រាំងខ្ទប់' ],
	'Uncategorizedcategories'   => [ 'ចំណាត់ថ្នាក់ក្រុមដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ],
	'Uncategorizedimages'       => [ 'រូបភាពដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ],
	'Uncategorizedpages'        => [ 'ទំព័រដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ],
	'Uncategorizedtemplates'    => [ 'ទំព័រគំរូដែលគ្មានចំណាត់ថ្នាក់ក្រុម' ],
	'Undelete'                  => [ 'ឈប់លុបចេញ' ],
	'Unlockdb'                  => [ 'ដោះសោមូលដ្ឋានទិន្នន័យ' ],
	'Unusedcategories'          => [ 'ចំណាត់ថ្នាក់ក្រុមដែលមិនត្រូវបានប្រើប្រាស់' ],
	'Unusedimages'              => [ 'រូបភាពដែលមិនត្រូវបានប្រើប្រាស់' ],
	'Unusedtemplates'           => [ 'ទំព័រគំរូដែលមិនត្រូវបានប្រើប្រាស់' ],
	'Unwatchedpages'            => [ 'ទំព័រលែងបានតាមដាន' ],
	'Upload'                    => [ 'ផ្ទុកឯកសារឡើង' ],
	'Userlogin'                 => [ 'ការកត់ឈ្មោះចូលរបស់អ្នកប្រើប្រាស់' ],
	'Userlogout'                => [ 'ការចាកចេញរបស់អ្នកប្រើប្រាស់' ],
	'Userrights'                => [ 'សិទ្ធិអ្នកប្រើប្រាស់' ],
	'Version'                   => [ 'កំណែ' ],
	'Wantedcategories'          => [ 'ចំណាត់ថ្នាក់ក្រុមប្រើប្រាស់ច្រើន' ],
	'Wantedfiles'               => [ 'រូបភាពប្រើប្រាស់ច្រើន' ],
	'Wantedpages'               => [ 'ទំព័រប្រើប្រាស់ច្រើន' ],
	'Wantedtemplates'           => [ 'ទំព័រគំរូប្រើប្រាស់ច្រើន' ],
	'Watchlist'                 => [ 'បញ្ជីតាមដាន' ],
	'Whatlinkshere'             => [ 'អ្វីដែលភ្ជាប់មកទីនេះ' ],
	'Withoutinterwiki'          => [ 'ដោយគ្មានអន្តរវិគី' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#បញ្ជូនបន្ត', '#ប្ដូរទីតាំងទៅ', '#ប្តូរទីតាំងទៅ', '#ប្ដូរទីតាំង', '#ប្តូរទីតាំង', '#ប្ដូរចំណងជើង', '#REDIRECT' ],
	'notoc'                     => [ '0', '__លាក់មាតិកា__', '__លាក់បញ្ជីអត្ថបទ__', '__គ្មានមាតិកា__', '__គ្មានបញ្ជីអត្ថបទ__', '__កុំបង្ហាញមាតិកា__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__លាក់វិចិត្រសាល__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__បង្ខំមាតិកា__', '__បង្ខំបញ្ជីអត្ថបទ__', '__បង្ខំអោយបង្ហាញមាតិកា__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__មាតិកា__', '__បញ្ជីអត្ថបទ__', '__TOC__' ],
	'noeditsection'             => [ '0', '__ផ្នែកមិនត្រូវកែប្រែ__', '__មិនមានផ្នែកកែប្រែ__', '__លាក់ផ្នែកកែប្រែ__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'ខែនេះ', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'ឈ្មោះខែនេះ', 'CURRENTMONTHNAME' ],
	'currentday'                => [ '1', 'ថ្ងៃនេះ', 'CURRENTDAY' ],
	'currentdayname'            => [ '1', 'ឈ្មោះថ្ងៃនេះ', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'ឆ្នាំនេះ', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'ពេលនេះ', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'ម៉ោងនេះ', 'ម៉ោងឥឡូវ', 'CURRENTHOUR' ],
	'localyear'                 => [ '1', 'LOCALDAYNAME', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'ពេលវេលាក្នុងតំបន់', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'ម៉ោងតំបន់', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'ចំនួនទំព័រ', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'ចំនួនអត្ថបទ', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'ចំនួនឯកសារ', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'ចំនួនអ្នកប្រើប្រាស់', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'ចំនួនកំណែប្រែ', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'ឈ្មោះទំព័រ', 'PAGENAME' ],
	'namespace'                 => [ '1', 'លំហឈ្មោះ', 'NAMESPACE' ],
	'talkspace'                 => [ '1', 'លំហឈ្មោះទំព័រពិភាក្សា', 'TALKSPACE' ],
	'fullpagename'              => [ '1', 'ឈ្មោះទំព័រពេញ', 'FULLPAGENAME' ],
	'subpagename'               => [ '1', 'ឈ្មោះទំព័ររង', 'SUBPAGENAME' ],
	'talkpagename'              => [ '1', 'ឈ្មោះទំព័រពិភាក្សា', 'TALKPAGENAME' ],
	'msg'                       => [ '0', 'សារ:', 'MSG:' ],
	'msgnw'                     => [ '0', 'សារមិនមែនជាកូដវិគី:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'រូបភាពតូច', 'រូបតូច', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'រូបភាពតូច=$1', 'រូបតូច=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'ស្តាំ', 'ខាងស្តាំ', 'right' ],
	'img_left'                  => [ '1', 'ធ្វេង', 'ខាងធ្វេង', 'left' ],
	'img_none'                  => [ '1', 'ទទេ', 'គ្មាន', 'none' ],
	'img_width'                 => [ '1', '$1ភីកសែល', '$1ភស', '$1px' ],
	'img_center'                => [ '1', 'កណ្តាល', 'center', 'centre' ],
	'img_framed'                => [ '1', 'ស៊ុម', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'គ្មានស៊ុម', 'frameless' ],
	'img_page'                  => [ '1', 'ទំព័រ=$1', 'ទំព័រ$1', 'page=$1', 'page $1' ],
	'img_top'                   => [ '1', 'ផ្នែកលើ', 'ផ្នែកខាងលើ', 'top' ],
	'img_text_top'              => [ '1', 'ឃ្លានៅផ្នែកខាងលើ', 'ឃ្លាផ្នែកខាងលើ', 'text-top' ],
	'img_middle'                => [ '1', 'ផ្នែកកណ្តាល', 'middle' ],
	'img_bottom'                => [ '1', 'បាត', 'ផ្នែកបាត', 'bottom' ],
	'img_text_bottom'           => [ '1', 'ឃ្លានៅផ្នែកបាត', 'ឃ្លាផ្នែកបាត', 'text-bottom' ],
	'img_link'                  => [ '1', 'តំនភ្ជាប់=$1', 'តំណភ្ជាប់=$1', 'link=$1' ],
	'sitename'                  => [ '1', 'ឈ្មោះវិបសាយ', 'ឈ្មោះគេហទំព័រ', 'SITENAME' ],
	'ns'                        => [ '0', 'លឈ:', 'NS:' ],
	'server'                    => [ '0', 'ម៉ាស៊ីនបម្រើសេវា', 'SERVER' ],
	'servername'                => [ '0', 'ឈ្មោះម៉ាស៊ីនបម្រើសេវា', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'ផ្លូវស្រ្គីប', 'SCRIPTPATH' ],
	'grammar'                   => [ '0', 'វេយ្យាករណ៍:', 'GRAMMAR:' ],
	'currentweek'               => [ '1', 'សប្ដាហ៍នេះ', 'CURRENTWEEK' ],
	'plural'                    => [ '0', 'ពហុវចនៈ:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'URLពេញ:', 'FULLURL:' ],
	'displaytitle'              => [ '1', 'បង្ហាញចំណងជើង', 'បង្ហាញចំនងជើង', 'DISPLAYTITLE' ],
	'rawsuffix'                 => [ '1', 'រ', 'R' ],
	'newsectionlink'            => [ '1', '__តំនភ្ជាប់ផ្នែកថ្មី__', '__តំណភ្ជាប់ផ្នែកថ្មី__', '__NEWSECTIONLINK__' ],
	'language'                  => [ '0', '#ភាសា:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'កូដភាសា', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'numberofadmins'            => [ '1', 'ចំនួនអ្នកអភិបាល', 'ចំនួនអ្នកថែទាំប្រព័ន្ធ', 'NUMBEROFADMINS' ],
	'special'                   => [ '0', 'ពិសេស', 'special' ],
	'filepath'                  => [ '0', 'ផ្លូវនៃឯកសារ:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'ប្លាក', 'tag' ],
	'hiddencat'                 => [ '1', '__ចំណាត់ថ្នាក់ក្រុមមិនបានបង្ហាញ__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'ចំនួនទំព័រក្នុងចំនាត់ថ្នាក់ក្រុម', 'ចំនួនទំព័រក្នុងចំណាត់ថ្នាក់ក្រុម', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'ទំហំទំព័រ', 'PAGESIZE' ],
	'index'                     => [ '1', '__លិបិក្រម__', '__INDEX__' ],
	'noindex'                   => [ '1', '__មិនមានលិបិក្រម__', '__NOINDEX__' ],
	'staticredirect'            => [ '1', '__ស្ថិតិទំព័របញ្ជូនបន្ត__', '__STATICREDIRECT__' ],
];
