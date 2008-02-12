<?php
/** Khmer (ភាសាខ្មែរ)
 *
 * @addtogroup Language
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Bunly
 * @author តឹក ប៊ុនលី
 * @author Chhorran
 */

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

$messages = array(
# User preference toggles
'tog-underline'            => 'គូសបន្ទាត់ក្រោម តំណភ្ជាប់៖',
'tog-justify'              => 'តំរឹម កថាខណ្ឌ',
'tog-hideminor'            => 'លាក់កំណែប្រែតិចតួចក្នុងបំលាស់ប្តូរថ្មីៗ',
'tog-showtoolbar'          => 'បង្ហាញ របារឧបករកែប្រែ(JavaScript)',
'tog-editondblclick'       => 'ចុចឌុប ដើម្បីកែប្រែទំព័រ (JavaScript)',
'tog-rememberpassword'     => 'ចងចាំ ការពិនិត្យចូល របស់ខ្ញុំ លើខំព្យូរើនេះ',
'tog-editwidth'            => 'បង្ហាញបង្អួចកែប្រែ ជាទទឹងពេញ',
'tog-watchcreations'       => 'បន្ថែមទំព័រ ដែលខ្ញុំបង្កើតនៅ បញ្ជីតាមដាន',
'tog-watchdefault'         => 'បន្ថែមទំព័រដែលខ្ញុំកែប្រែនៅបញ្ជីតាមដាន',
'tog-watchmoves'           => 'បន្ថែមទំព័រនានា ដែលខ្ញុំប្តូរទីតាំង ទៅបញ្ជីតាមដាន របស់ខ្ញុំ',
'tog-watchdeletion'        => 'បន្ថែមទំព័រ ដែលខ្ញុំលុប ទៅបញ្ជីតាមដាន របស់ខ្ញុំ',
'tog-previewontop'         => 'បង្ហាញការមើលមុន ពីលើប្រអប់កែប្រែ',
'tog-previewonfirst'       => 'បង្ហាញការមើលមុន ចំពោះកំណែប្រែដំបូង',
'tog-enotifwatchlistpages' => 'អ៊ីមែវល៍មកខ្ញុំ កាលបើបញ្ជីតាមដាន របស់អ្នក បានត្រូវផ្លាស់ប្តូរ',
'tog-enotifusertalkpages'  => 'អ៊ីមែវល៍មកខ្ញុំ កាលបើទំព័រពិភាក្សា របស់អ្នក បានត្រូវផ្លាស់ប្តូរ',
'tog-enotifminoredits'     => 'អ៊ីមែវល៍មកខ្ញុំ ផងដែរ ចំពោះបំលាស់ប្តូរតិចតួច នៃទំព័រនានា',
'tog-shownumberswatching'  => 'បង្ហាញចំនួនអ្នកប្រើប្រាស់ ដែលតាមដានទំព័រនេះ',
'tog-fancysig'             => 'ហត្ថលេខាឆៅ (គ្មានតំណភ្ជាប់ ស្វ័យប្រវត្តិ)',
'tog-externaleditor'       => 'ប្រើប្រាស់ ឧបករកែប្រែក្រៅ តាមលំនាំដើម',
'tog-externaldiff'         => 'ប្រើប្រាស់ឧបករ ប្រៀបធៀបក្រៅ តាមលំនាំដើម',
'tog-uselivepreview'       => 'ប្រើប្រាស់ ការមើលមុនរហ័ស (JavaScript) (អ្នកមានបទពិសោធ)',
'tog-forceeditsummary'     => 'រំលឹកខ្ញុំ កាលបើខ្ញុំទុកប្រអប់វិចារ អោយទំនេរ',
'tog-watchlisthideown'     => 'បិទបាំងកំណែប្រែរបស់ខ្ញុំ ពីបញ្ជីតាមដាន',
'tog-watchlisthideminor'   => 'បិទបាំង កំណែប្រែតិចតួច ពីបញ្ជីតាមដាន',
'tog-ccmeonemails'         => 'ផ្ញើខ្ញុំ ច្បាប់ចំលងអ៊ីមែវល៍ ដើម្បីខ្ញុំផ្ញើទៅ អ្នកប្រើប្រាស់ផ្សេងទៀត',
'tog-diffonly'             => 'មិនបង្ហាញ ខ្លឹមសារទំព័រ នៅពីក្រោម ការប្រៀបធៀប (diffs)',

'underline-always'  => 'ជានិច្ចកាល',
'underline-never'   => 'ទេ',
'underline-default' => 'តាមលំនាំដើម',

'skinpreview' => '(មើលមុន)',

# Dates
'sunday'        => 'ថ្ងៃអាទិត្យ',
'monday'        => 'ថ្ងៃច័ន្ទ',
'tuesday'       => 'ថ្ងៃអង្គារ',
'wednesday'     => 'ថ្ងៃពុធ',
'thursday'      => 'ថ្ងៃព្រហស្បតិ៍',
'friday'        => 'ថ្ងៃសុក្រ',
'saturday'      => 'ថ្ងៃសៅរ៏',
'sun'           => 'អាទិត្យ',
'mon'           => 'ច័ន្ទ',
'tue'           => 'អង្គារ',
'wed'           => 'ពុធ',
'thu'           => 'ព្រហស្បតិ៍',
'fri'           => 'សុក្រ',
'sat'           => 'សៅរ៏',
'january'       => 'ខែមករា',
'february'      => 'ខែកុម្ភៈ',
'march'         => 'ខែមិនា',
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
'march-gen'     => 'ខែមិនា',
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
'mar'           => 'មិនា',
'apr'           => 'មេសា',
'may'           => 'ឧសភា',
'jun'           => 'មិថុនា',
'jul'           => 'កក្កដា',
'aug'           => 'សីហា',
'sep'           => 'កញ្ញា',
'oct'           => 'តុលា',
'nov'           => 'វិច្ឆិកា',
'dec'           => 'ធ្នូ',

# Bits of text used by many pages
'categories'            => 'ចំណាត់ក្រុមនានា',
'pagecategories'        => '{{PLURAL:$1|ចំណាត់ក្រុម|ចំណាត់ក្រុមនានា}}',
'category_header'       => 'ទំព័រនានា ក្នុងចំណាត់ក្រុម "$1"',
'subcategories'         => 'ចំណាត់ក្រុមរង នានា',
'category-media-header' => 'ឯកសារមីឌា ក្នុងចំណាត់ក្រុម "$1"',
'category-empty'        => "''ចំណាត់ក្រុមនេះ មិនមានផ្ទុកទំព័រ ឬ ឯកសារមីឌា ណាមួយទេ។''",

'mainpagetext'      => "<big>'''មីឌាវិគី ត្រូវបានតំលើង ដោយជោគជ័យ'''</big>",
'mainpagedocfooter' => 'ពិនិត្យមើល [http://meta.wikimedia.org/wiki/ជំនួយ៖ខ្លឹមសារ ណែនាំប្រើប្រាស់] សំរាប់ ពត៌មានបន្ថែម ចំពោះបំរើប្រាស់ ផ្នែកទន់វិគី។

== ចាប់ផ្តើមជាមួយ មីឌាវិគី ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings បញ្ជីកំណត់ទំរង់]
* [http://www.mediawiki.org/wiki/Manual:FAQ/km សំណួរញឹកញាប់ MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce បញ្ជីពិភាក្សា ការផ្សព្វផ្សាយរបស់ MediaWiki]',

'about'          => 'អំពី',
'article'        => 'មាតិកាអត្ថបទ',
'newwindow'      => '(បើកនៅលើផ្ទាំងបង្អួចថ្មី)',
'cancel'         => 'បោះបង់',
'qbfind'         => 'រកមើល',
'qbbrowse'       => 'រាវរក',
'qbedit'         => 'កែប្រែ',
'qbpageoptions'  => 'ទំព័រនេះ',
'qbpageinfo'     => 'ពត៌មានទំព័រ',
'qbmyoptions'    => 'ទំព័រ ផ្ទាល់ខ្លួនខ្ញុំ',
'qbspecialpages' => 'ទំព័រពិសេសៗ',
'moredotdotdot'  => 'បន្ថែមទៀត...',
'mypage'         => 'ទំព័រផ្ទាល់ខ្លួន',
'mytalk'         => 'ការពិភាក្សា របស់ខ្ញុំ',
'anontalk'       => 'ពិភាក្សា ចំពោះ IP នេះ',
'navigation'     => 'ទិសដៅ',
'and'            => 'និង',

'errorpagetitle'    => 'កំហុស',
'returnto'          => 'ត្រលប់ទៅ $1 វិញ។',
'tagline'           => 'ពី {{SITENAME}}',
'help'              => 'ជំនួយ',
'search'            => 'ស្វែងរក',
'searchbutton'      => 'ស្វែងរក',
'go'                => 'ទៅ',
'searcharticle'     => 'ទៅ',
'history'           => 'ប្រវត្តិ',
'history_short'     => 'ប្រវត្តិ',
'updatedmarker'     => 'បានបន្ទាន់សម័យ តាំងពី ការចូលមើលចុងក្រោយ របស់ខ្ញុំ',
'info_short'        => 'ពត៌មាន',
'printableversion'  => 'ទំរង់សំរាប់បោះពុម្ភ',
'permalink'         => 'តំណភ្ជាប់អចិន្ត្រៃ',
'print'             => 'បោះពុម្ភ',
'edit'              => 'កែប្រែ',
'editthispage'      => 'កែប្រែទំព័រនេះ',
'delete'            => 'លុបចេញ',
'deletethispage'    => 'លុបចេញទំព័រនេះ',
'undelete_short'    => 'លែង លុបចេញ {{PLURAL:$1|មួយកំណែប្រែ|$1 ច្រើនកំណែប្រែ}}',
'protect'           => 'ការពារ',
'protect_change'    => 'ផ្លាស់ប្តូរ ការការពារ',
'protectthispage'   => 'ការពារទំព័រនេះ',
'unprotect'         => 'លែងការពារ',
'unprotectthispage' => 'លែងការពារ ទំព័រនេះ',
'newpage'           => 'ទំព័រថ្មី',
'talkpage'          => 'ពិភាក្សា ទំព័រនេះ',
'talkpagelinktext'  => 'ពិភាក្សា',
'specialpage'       => 'ទំព័រ ពិសេស',
'personaltools'     => 'ឧបករណ៍ផ្ទាល់ខ្លួន',
'postcomment'       => 'ដាក់មួយវិចារ',
'articlepage'       => 'មើលអត្ថបទ',
'talk'              => 'ពិភាក្សា',
'views'             => 'ការមើលនានា',
'toolbox'           => 'ប្រអប់ឧបករ',
'userpage'          => 'មើល​ ទំព័រអ្នកប្រើប្រាស់',
'projectpage'       => 'មើល​ ទំព័រគំរោង',
'imagepage'         => 'មើលទំព័ររូបភាព',
'mediawikipage'     => 'មើល​ ទំព័រសារ',
'templatepage'      => 'មើល​ ទំព័រគំរូខ្នាត',
'viewhelppage'      => 'មើល​ ទំព័រជំនួយ',
'categorypage'      => 'មើល​ ទំព័រចំណាត់ថ្នាក់',
'viewtalkpage'      => 'មើល ការពិភាក្សា',
'otherlanguages'    => 'ជាភាសា ដទៃទៀត',
'redirectedfrom'    => '(បានប្តូរទិស ពី $1)',
'redirectpagesub'   => 'ប្តូរទិសទំព័រ',
'lastmodifiedat'    => 'ទំព័រនេះ បានត្រូវផ្លាស់ប្តូរ ចុងក្រោយ $2, $1។', # $1 date, $2 time
'viewcount'         => 'ទំព័រនេះ ត្រូវបានចូលទៅ {{PLURAL:$1|ម្តង|$1 លើក}}។',
'protectedpage'     => 'ទំព័រ ត្រូវបានការពារ',
'jumpto'            => 'ទៅដល់៖',
'jumptonavigation'  => 'ការត្រាច់ចរ',
'jumptosearch'      => 'ស្វែងរក',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'អំពី {{SITENAME}}',
'aboutpage'         => 'Project:អំពី',
'bugreports'        => 'របាយការកំហុស',
'bugreportspage'    => 'Project:របាយការកំហុស',
'copyright'         => 'ខ្លឹមសារ នៅទំនេរ ក្រោម $1។',
'copyrightpagename' => '{{SITENAME}} រក្សាសិទ្ធិចំលង',
'copyrightpage'     => '{{ns:project}}:រក្សាសិទ្ធិចំលង',
'currentevents'     => 'ព្រឹត្តិការថ្មីៗ',
'currentevents-url' => 'Project:ព្រឹត្តិការថ្មីៗ',
'disclaimers'       => 'ការបដិសេធ នានា',
'disclaimerpage'    => 'Project:ការបដិសេធ ទូទៅ',
'edithelp'          => 'ជំនួយ កំណែប្រែ',
'edithelppage'      => 'Help:កំណែប្រែ',
'faq'               => 'សំណួរ ដែលសួរញឹកញាប់',
'faqpage'           => 'Project:សំណួរ ដែលសួរញឹកញាប់',
'helppage'          => 'Help:មាតិកា',
'mainpage'          => 'ទំព័រដើម',
'policy-url'        => 'Project:វិធាន',
'portal'            => 'ក្លោងទ្វារ សហគម',
'portal-url'        => 'Project:ក្លោងទ្វារ សហគម',
'privacy'           => 'វិធាន លាក់ការ',
'privacypage'       => 'Project:វិធាន លាក់ការ',
'sitesupport'       => 'ផ្តល់វិភាគទាន',
'sitesupport-url'   => 'Project:ផ្តល់វិភាគទាន',

'badaccess'        => 'កំហុស នៃការអនុញ្ញាតិ',
'badaccess-group0' => 'អ្នកមិនមានសិទ្ធិ ធ្វើសកម្មភាព ដែលអ្នកបានស្នើ នោះទេ។',
'badaccess-group1' => 'មានតែ អ្នកប្រើប្រាស់ ក្នុងក្រុម $1 ទើបអាចធ្វើសកម្មភាព ដែលអ្នកបានស្នើ។',
'badaccess-group2' => 'មានតែ អ្នកប្រើប្រាស់ នៃក្រុម $1 នានា ទើបអាចធ្វើសកម្មភាព ដែលអ្នកបានស្នើ។',
'badaccess-groups' => 'មានតែ អ្នកប្រើប្រាស់ ក្នុងក្រុម $1 នានា ទើបអាចធ្វើសកម្មភាព ដែលអ្នកបានស្នើ។',

'versionrequired'     => 'តុំរូវអោយមាន កំណែ(version) $1 នៃ មីឌាវិគី (MediaWiki)',
'versionrequiredtext' => 'ត្រូវការកំណែ $1 នៃមីឌាវិគី (MediaWiki) ដើម្បីប្រើប្រាស់ទំព័រនេះ។ មើល  [[Special:Version|ទំព័រកំណែ]]។',

'ok'                      => 'យល់ព្រម',
'retrievedfrom'           => 'បានមកវិញពី "$1"',
'youhavenewmessages'      => 'អ្នកមាន $1 ($2).',
'newmessageslink'         => 'សារថ្មីៗ',
'newmessagesdifflink'     => 'បំលាស់ប្តូរ ចុងក្រោយ',
'youhavenewmessagesmulti' => 'អ្នកមានសារថ្មីៗ នៅ $1',
'editsection'             => 'កែប្រែ',
'editold'                 => 'កែប្រែ',
'editsectionhint'         => 'កែប្រែផ្នែក៖ $1',
'toc'                     => 'មាតិកា',
'showtoc'                 => 'បង្ហាញ',
'hidetoc'                 => 'លាក់',
'thisisdeleted'           => 'ចង់បង្ហាញ ឬ​ ទុក $1 នៅដដែល?',
'viewdeleted'             => 'មើល $1?',
'restorelink'             => '{{PLURAL:$1|មួយកំណែប្រែត្រូវបានលុប|$1 ច្រើនកំណែប្រែត្រូវបានលុប}}',
'feedlinks'               => 'បំរែបំរួល៖',
'feed-invalid'            => 'ប្រភេទ បំរែបំរួល ដែលគ្មាន សុពលភាព។',
'site-rss-feed'           => 'បំរែបំរួល RSS នៃ $1',
'site-atom-feed'          => 'បំរែបំរួល Atom Feed នៃ $1',
'page-rss-feed'           => 'បំរែបំរួល RSS នៃ "$1"',
'page-atom-feed'          => 'បំរែបំរួល Atom Feed នៃ "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ទំព័រ',
'nstab-user'      => 'ទំព័រ អ្នកប្រើប្រាស់',
'nstab-media'     => 'ទំព័រ មីឌា(Media)',
'nstab-special'   => 'ពិសេស',
'nstab-project'   => 'ទំព័រ គំរោង',
'nstab-image'     => 'ឯកសារ',
'nstab-mediawiki' => 'សារ',
'nstab-template'  => 'គំរូខ្នាត',
'nstab-help'      => 'ទំព័រ ជំនួយ',
'nstab-category'  => 'ចំណាត់ក្រុម',

# Main script and global functions
'nosuchaction'      => 'គ្មានសកម្មភាព បែបនេះទេ',
'nosuchactiontext'  => 'សកម្មភាព បានបង្ហាញដោយ URL មិនត្រូវបាន ទទួលស្គាល់ដោយ វិគី',
'nosuchspecialpage' => 'គ្មានទំព័រពិសេស បែបនេះទេ',
'nospecialpagetext' => "<big>'''អ្នកបានស្នើរក មួយទំព័រពិសេស ដែលមិនត្រូវបាន ទទួលស្គាល់ដោយ វិគី។'''</big>

បញ្ជី នៃ ទំព័រពិសេស អាចត្រូវបាន រកឃើញ នៅ [[Special:Specialpages]]។",

# General errors
'error'                => 'កំហុស',
'databaseerror'        => 'កំហុស មូលដ្ឋានទិន្នន័យ',
'noconnect'            => 'អភ័យទោស! បន្ទាប់ពី មានបញ្ហាបច្ចេកទេស, វាមិនអាចទាក់ទងទៅ មូលដ្ឋានទិន្នន័យ នៅពេលនេះទេ។ <br />
$1',
'nodb'                 => 'មិនអាចជ្រើសយក មូលដ្ឋានទិន្នន័យ $1',
'laggedslavemode'      => 'ប្រយ័ត្ន៖ ទំព័រនេះ អាចមិនផ្ទុក បំលាស់ប្តូរ នានា ចុងក្រោយបំផុត។',
'readonly'             => 'មូលដ្ឋានទិន្នន័យ ជាប់សោ',
'enterlockreason'      => 'បង្ហាញ ហេតុផល ចំពោះការជាប់សោ រួមទាំង ការប្រមាណ ទេសកាល របស់វា',
'internalerror'        => 'កំហុស ខាងក្នុង',
'internalerror_info'   => 'កំហុស ខាងក្នុង៖ $1',
'filecopyerror'        => 'មិនអាចចំលង ឯកសារ "$1" ទៅ "$2"។',
'filerenameerror'      => 'មិនអាចប្តូរ ឈ្មោះឯកសារ "$1" ទៅ "$2".',
'filedeleteerror'      => 'មិនអាចលុបចេញ ឯកសារ "$1"។',
'directorycreateerror' => 'មិនអាចបង្កើត ថតឯកសារ "$1"។',
'filenotfound'         => 'មិនអាចរកឃើញ ឯកសារ "$1"។',
'fileexistserror'      => 'មិនអាចសរសេរ ទៅក្នុង ថតឯកសារ "$1"៖ ឯកសារ មានរួចហើយ',
'unexpected'           => 'តំលៃ មិនបានរំពឹងទុក៖ "$1"="$2"។',
'formerror'            => 'កំហុស៖ មិនអាចដាក់ស្នើ បែបបទ',
'badarticleerror'      => 'សកម្មភាពនេះ មិនអាចត្រូវបានអនុវត្ត លើទំព័រនេះ។',
'badtitle'             => 'ចំណងជើង មិនត្រឹមត្រូវ',
'viewsource'           => 'មើល អក្សរកូដ',
'viewsourcefor'        => 'សំរាប់ $1',
'actionthrottled'      => 'សកម្មភាព ត្រូវបានកំរិត',
'protectedpagetext'    => 'ទំព័រនេះ បានត្រូវចាក់សោ ដើម្បីការពារកំណែប្រែ។',
'viewsourcetext'       => 'អ្នកអាចមើល និង ចំលងអក្សរកូដ នៃទំព័រនេះ៖',
'sqlhidden'            => '(ការអង្កេត SQL ត្រូវបិទបាំង)',
'namespaceprotected'   => "អ្នកមិនមានសិទ្ធិ កែប្រែទំព័រនានា ក្នុងវាលឈ្មោះ '''$1''' ។",
'customcssjsprotected' => 'អ្នកមិនមានការអនុញ្ញាតិ កែប្រែទំព័រនេះ, ព្រោះវាផ្ទុក ការកំណត់ផ្ទាល់ខ្លួន ផ្សេងទៀត នៃអ្នកប្រើប្រាស់។',
'ns-specialprotected'  => 'ទំព័រនានា ក្នុងវាលឈ្មោះ {{ns:special}} មិនអាចត្រូវបាន កែប្រែ។',
'titleprotected'       => 'ចំណងជើងនេះ ត្រូវបានការពារ ការបង្កើតថ្មី ដោយ [[User:$1|$1]]។ ហេតុផលលើកឡើង គឺ <i>$2</i>។',

# Login and logout pages
'logouttitle'               => 'ពិនិត្យចេញ របស់អ្នកប្រើប្រាស់',
'welcomecreation'           => '== សូមស្វាគមន៍ $1! ==

គណនីរបស់អ្នកត្រូវបានបង្កើតរួចហើយ។ សូមកុំភ្លេចផ្លាស់ប្តូរ ចំនូលចិត្ត{{SITENAME}}របស់អ្នក។',
'loginpagetitle'            => 'ពិនិត្យចូល របស់អ្នកប្រើប្រាស់',
'yourname'                  => 'ឈ្មោះ អ្នកប្រើប្រាស់៖',
'yourpassword'              => 'ពាក្យសំងាត់៖',
'yourpasswordagain'         => 'វាយពាក្យសំងាត់ ឡើងវិញ៖',
'remembermypassword'        => 'ចងចាំ ការពិនិត្យចូលរបស់ខ្ញុំ លើខំព្យូរើនេះ',
'yourdomainname'            => 'កម្មសិទ្ធិ របស់អ្នក៖',
'loginproblem'              => '<b>មានបញ្ហា ចំពោះការពិនិត្យចូល របស់អ្នក។</b><br />ព្យាយាមឡើងវិញ!',
'login'                     => 'ពិនិត្យចូល',
'userlogin'                 => 'ចូលឬបង្កើតគណនី',
'logout'                    => 'ចាកចេញ',
'userlogout'                => 'ពិនិត្យចេញ',
'notloggedin'               => 'មិនបាន ពិនិត្យចូល',
'nologin'                   => 'គ្មានគណនី ទេ ឬ? $1 ។',
'nologinlink'               => 'បង្កើតគណនីសំរាប់ប្រើប្រាស់',
'createaccount'             => 'បង្កើត គណនី',
'gotaccount'                => 'មានគណនី រួចហើយ ឬ ? $1។',
'gotaccountlink'            => 'ពិនិត្យចូល',
'createaccountmail'         => 'តាម អ៊ីមែវល៍',
'badretype'                 => 'ពាក្យសំងាត់ ដែលអ្នក បានបញ្ចូលនោះ មិនស៊ីគ្នា។',
'youremail'                 => 'អ៊ីមែវល៍:',
'username'                  => 'ឈ្មោះអ្នកប្រើប្រាស់៖',
'uid'                       => 'អត្តសញ្ញាណ អ្នកប្រើប្រាស់៖',
'yourrealname'              => 'ឈ្មោះពិត៖',
'yourlanguage'              => 'ភាសា៖',
'yournick'                  => 'ឈ្មោះ ហៅក្រៅ៖',
'badsiglength'              => 'ឈ្មោះហៅក្រៅ វែងជ្រុល; ត្រូវតែ តិចជាង $1 អក្សរ។',
'email'                     => 'អ៊ីមែវល៍',
'prefs-help-realname'       => '(ជំរើស)៖ បើអ្នកផ្តល់អោយ, វានឹងត្រូវបាន ប្រើប្រាស់់ ដើម្បីបញ្ជាក់ ភាពជាម្ចាស់ លើការរួមចំណែក នានា របស់អ្នក។',
'loginerror'                => 'កំហុស ពិនិត្យចូល',
'prefs-help-email'          => '(ជំរើស)៖ វាអនុញ្ញាតិអោយ អ្នកប្រើប្រាស់ដទៃទៀត ទាក់ទងជាមួយអ្នក តាមអ៊ីមែវល៍ (ដែលតភ្ជាប់ ទំព័រអ្នកប្រើប្រាស់) ដោយមើល មិនឃើញមែវល៍ របស់អ្នក ហើយនិង ផ្ញើពាក្យសំងាត់ថ្មី អោយអ្នក បើអ្នកបានជាភ្លេចវា។',
'prefs-help-email-required' => 'តំរូវអោយមាន អាស័យដ្ឋានអ៊ីមែវល៍។',
'loginsuccesstitle'         => 'ពិនិត្យចូល ដោយជោគជ័យ',
'loginsuccess'              => "'''ពេលនេះ, អ្នកត្រូវបាន ពិនិត្យចូល ទៅ {{SITENAME}} ជា \"\$1\"។'''",
'wrongpassword'             => 'ពាក្យសំងាត់ បានបញ្ចូល មិនត្រឹមត្រូវ។ សូមព្យាយាម ឡើងវិញ។',
'wrongpasswordempty'        => 'ពាក្យសំងាត់ទទេ បានបញ្ចូល។ សូមព្យាយាម ឡើងវិញ។',
'passwordtooshort'          => 'ពាក្យសំងាត់ របស់អ្នក មិនមានសុពលភាព ឬ​ ខ្លីពេក។ វាត្រូវមាន យ៉ាងតិច $1 អក្សរ និង ត្រូវផ្សេងពី ឈ្មោះអ្នកប្រើប្រាស់ របស់អ្នក។',
'mailmypassword'            => 'អ៊ីមែវល៍មកខ្ញុំ ពាក្យសំងាត់ថ្មី',
'noemail'                   => 'គ្មាន អាស័យដ្ឋានអ៊ីមែវល៍ ត្រូវបានថតទុក សំរាប់ អ្នកប្រើប្រាស់ "$1"។',
'mailerror'                 => 'កំហុស ផ្ញើមែវល៍៖ $1',
'emailauthenticated'        => 'អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក ត្រូវបានទទួល ស្របច្បាប់ ថ្ងៃ $1។',
'emailnotauthenticated'     => 'អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក មិនទាន់ស្របច្បាប់ទេ។ គ្មានអ៊ីមែវល៍ ដែលនឹងត្រូវបានផ្ញើទៅ សំរាប់មុខងារណាមួយ ខាងក្រោម។',
'emailconfirmlink'          => 'បញ្ជាក់ទទួលស្គាល់ អាស័យដ្ឋានអ៊ីមែវល៍',
'accountcreated'            => 'គណនី ត្រូវបានបង្កើត',
'accountcreatedtext'        => 'គណនី អ្នកប្រើប្រាស់ ចំពោះ $1 ត្រូវបានបង្កើត ហើយ។',
'createaccount-title'       => 'ការបង្កើតគណនី សំរាប់ {{SITENAME}}',
'loginlanguagelabel'        => 'ភាសា៖ $1',

# Password reset dialog
'resetpass'               => 'ធ្វើអោយ ពាក្យសំងាត់គណនី ដូចលើកទីសូន្យ',
'resetpass_submit'        => 'ផ្លាស់ប្តូរ ពាក្យសំងាត់ និង ពិនិត្យចូល',
'resetpass_success'       => 'ពាក្យសំងាត់ របស់អ្នក ត្រូវបានផ្លាស់ប្តូរ ដោយជោគជ័យ! កំពុងពិនិត្យចូល...',
'resetpass_bad_temporary' => 'ពាក្យសំងាត់ អសុពលភាព ជាបណ្តោះអាសន្ន។ ប្រហែលជា អ្នកបានផ្លាស់ប្តូរ ពាក្យសំងាត់ របស់អ្នក រួចហើយ ឬ បានស្នើ ពាក្យសំងាត់ បណ្តោះអាសន្ន ថ្មីមួយ។',
'resetpass_forbidden'     => 'ពាក្យសំងាត់ ទាំងឡាយ មិនអាចត្រូវបាន ផ្លាស់ប្តូរ លើ {{SITENAME}}',
'resetpass_missing'       => 'គ្មានទិន្នន័យ ណាមួយ ត្រូវបានបញ្ចូល។',

# Edit page toolbar
'bold_sample'     => 'អក្សរដិត',
'bold_tip'        => 'អក្សរដិត',
'italic_sample'   => 'អក្សរទ្រេត',
'italic_tip'      => 'អត្ថបទទ្រេត',
'link_sample'     => 'ចំណងជើង តំណភ្ជាប់',
'link_tip'        => 'តំណភ្ជាប់ខាងក្នុង',
'extlink_sample'  => 'ចំណងជើង តំណភ្ជាប់ http://www.example.com',
'extlink_tip'     => 'តំណភ្ជាប់ខាងក្រៅ (ចងចាំ http:// នៅពីមុខ)',
'headline_sample' => 'អត្ថបទ នៃ ចំណងជើងរង',
'headline_tip'    => 'ចំណងជើងរង កំរិត ២',
'math_sample'     => 'បញ្ចូលរូបមន្ត នៅទីនេះ',
'math_tip'        => 'រូបមន្តគណិត (LaTeX)',
'nowiki_sample'   => 'បញ្ចូល អត្ថបទមិនបានដាក់ទំរង់ នៅទីនេះ',
'image_tip'       => 'រូបភាពបង្កប់',
'media_tip'       => 'តំណភ្ជាប់ទៅ ឯកសារមីឌា',
'sig_tip'         => 'ហត្ថលេខារបស់អ្នកនិងកាលបរិច្ឆេទ',
'hr_tip'          => 'បន្ទាត់ដេក (ប្រើប្រាស់ ដោយហ្មត់ចត់)',

# Edit pages
'summary'                => 'សេចក្តីសង្ខេប',
'subject'                => 'ប្រធានបទ/ចំណងជើង',
'minoredit'              => 'នេះជា កំណែប្រែតិចតួចមួយ',
'watchthis'              => 'តាមដានទំព័រនេះ',
'savearticle'            => 'រក្សាទុកទំព័រ',
'preview'                => 'មើលមុន',
'showpreview'            => 'បង្ហាញការមើលមុន',
'showlivepreview'        => 'មើលមុនទាន់ចិត្ត',
'showdiff'               => 'បង្ហាញបំលាស់ប្តូរ',
'missingcommenttext'     => 'សូមបញ្ចូលមួយវិចារ នៅខាងក្រោម។',
'summary-preview'        => 'មើលមុន សង្ខេប',
'subject-preview'        => 'មើលមុន ប្រធានបទ/ចំណងជើង',
'blockedtitle'           => 'អ្នកប្រើប្រាស់ បានត្រូវខ្ទប់',
'blockednoreason'        => 'គ្មានហេតុផល ត្រូវបានលើកឡើង',
'blockedoriginalsource'  => "អក្សរកូដ នៃ '''$1''' ត្រូវបានបង្ហាញ ខាងក្រោម៖",
'whitelistedittitle'     => 'តំរូវអោយ ពិនិត្យចូល ដើម្បីកែប្រែ',
'whitelistedittext'      => 'អ្នកត្រូវតែជា $1 ដើម្បីមានសិទ្ធិ កែប្រែខ្លឹមសារទំព័រ។',
'whitelistreadtitle'     => 'តំរូវអោយ ពិនិត្យចូល ដើម្បីអាន',
'whitelistreadtext'      => 'អ្នកត្រូវតែ [[Special:Userlogin|ពិនិត្យចូល]] ដើម្បីអានទំព័រ។',
'whitelistacctitle'      => 'អ្នកមិនត្រូវបាន អនុញ្ញាតិអោយ បង្កើតគណនីទេ',
'nosuchsectiontitle'     => 'មិនមានផ្នែក បែបនេះ',
'loginreqtitle'          => 'តំរូវអោយ ពិនិត្យចូល',
'loginreqlink'           => 'ពិនិត្យចូល',
'loginreqpagetext'       => 'អ្នកត្រូវតែ $1 ដើម្បី មើលទំព័រ ដទៃទៀត។',
'accmailtitle'           => 'ពាក្យសំងាត់ ត្រូវបានផ្ញើទៅហើយ។',
'accmailtext'            => 'ពាក្យសំងាត់ របស់ "$1" ត្រូវបានផ្ញើទៅ $2 ហើយ។',
'newarticle'             => '(ថ្មី)',
'updated'                => '(បានបន្ទាន់សម័យ)',
'note'                   => '<strong>ចំណាំ៖</strong>',
'editing'                => 'កំណែប្រែ នៃ $1',
'editinguser'            => "ផ្លាស់ប្តូរ សិទ្ធិអ្នកប្រើប្រាស់ នៃ អ្នកប្រើប្រាស់ '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'editingsection'         => 'កំណែប្រែ នៃ $1 (ផ្នែក)',
'editingcomment'         => 'កំណែប្រែ នៃ $1 (វិចារ)',
'editconflict'           => 'ភាពឆ្គង នៃកំណែប្រែ៖ $1',
'yourtext'               => 'អត្ថបទ របស់អ្នក',
'yourdiff'               => 'ភាពខុសគ្នា នានា',
'templatesused'          => 'គំរូខ្នាតនានា បានប្រើប្រាស់ លើទំព័រនេះ៖',
'templatesusedpreview'   => 'គំរូខ្នាតនានា បានប្រើប្រាស់ ក្នុងការមើលមុននេះ៖',
'templatesusedsection'   => 'គំរូខ្នាតនានា បានប្រើប្រាស់ ក្នុងផ្នែកនេះ៖',
'template-protected'     => '(ត្រូវបានការពារ)',
'template-semiprotected' => '(ត្រូវបានការពារ ពាក់កណ្តាល)',
'nocreatetitle'          => 'ការបង្កើតទំព័រ ត្រូវបានកំរិត',
'nocreate-loggedin'      => 'អ្នកគ្មានការអនុញ្ញាតិ បង្កើតទំព័រថ្មី លើ {{SITENAME}}។',
'permissionserrors'      => 'កំហុសអនុញ្ញាតិ នានា',

# Account creation failure
'cantcreateaccounttitle' => 'មិនអាចបង្កើត គណនី',

# History pages
'nohistory'           => 'មិនមាន ប្រវត្តិកំណែប្រែ ចំពោះទំព័រនេះ។',
'revnotfound'         => 'រកមិនឃើញ កំណែ(version)',
'loadhist'            => 'ផ្ទុក ប្រវត្តិ នៃ ទំព័រ',
'currentrev'          => 'កំណែបច្ចុប្បន្ន',
'revisionasof'        => 'កំណែ របស់ $1',
'revision-info'       => 'កំណែ របស់ $1 ដោយ $2',
'previousrevision'    => '← កំណែ មុន',
'nextrevision'        => 'កំណែ បន្ទាប់ →',
'currentrevisionlink' => 'កំណែ បច្ចុប្បន្ន',
'next'                => 'បន្ទាប់',
'last'                => 'ចុងក្រោយ',
'page_first'          => 'ដំបូង',
'page_last'           => 'ចុងក្រោយ',
'deletedrev'          => '[ត្រូវបានលុបចេញ]',
'histfirst'           => 'ការរួមចំណែក ដំបូងៗ បំផុត',
'histlast'            => 'ចុងក្រោយបំផុត',
'historysize'         => '({{PLURAL:$1|1 បៃ|$1 បៃ}})',
'historyempty'        => '(ទទេ)',

# Revision feed
'history-feed-title'       => 'ប្រវត្តិ នៃ កំណែ',
'history-feed-description' => 'ប្រវត្តិ ទំព័រនេះ លើ វិគី',

# Revision deletion
'rev-deleted-comment'       => '(វិចារ ត្រូវបានដកចេញ)',
'rev-deleted-user'          => '(ឈ្មោះអ្នកប្រើប្រាស់ ត្រូវបានដកចេញ)',
'rev-deleted-event'         => '(អត្ថបទ ត្រូវបានដកចេញ)',
'rev-delundel'              => 'បង្ហាញ/បិទបាំង',
'revisiondelete'            => 'លុបចេញ/ទុកនៅដដែល កំណែ នានា',
'revdelete-nooldid-title'   => 'គ្មានគោលដៅ ចំពោះ កំណែ',
'revdelete-legend'          => 'ដាក់កំហិត នានា៖',
'revdelete-hide-text'       => 'បិទបាំង អត្ថបទ នៃ កំណែ',
'revdelete-hide-name'       => 'បិទបាំង សកម្មភាព និង គោលដៅ',
'revdelete-hide-comment'    => 'បិទបាំង កំណែប្រែ វិចារ',
'revdelete-hide-user'       => 'បិទបាំង ឈ្មោះអ្នកប្រើប្រាស់​ ឬ អាស័យដ្ឋាន IP នៃ អ្នករួមចំណែក',
'revdelete-hide-restricted' => 'អនុវត្ត ការដាក់កំហិត នានា ចំពោះ អ្នកថែទាំប្រព័ន្ធ(sysops) ក៏ដូចជា អ្នកប្រើប្រាស់ ដ៏ទៃទៀត',
'revdelete-hide-image'      => 'បិទបាំង ខ្លឹមសារ នៃឯកសារ',
'revdelete-unsuppress'      => 'ដកចេញ ការដាក់កំហិត លើកំណែ ដែលបានមកវិញ',
'revdelete-submit'          => 'អនុវត្ត ទៅកំណែ ដែលបានជ្រើសយក',
'revdelete-logentry'        => 'បានផ្លាស់ប្តូរ គំហើញកំណែ នៃ [[$1]]',
'logdelete-logentry'        => 'បានផ្លាស់ប្តូរ គំហើញហេតុការ នៃ [[$1]]',
'revdelete-success'         => 'បានកំណត់ គំហើញកំណែ ដោយជោគជ័យ។',
'logdelete-success'         => 'បានកំណត់ គំហើញហេតុការ ដោយជោគជ័យ។',

# History merging
'mergehistory'                     => 'បញ្ចូលរួមគ្នា ប្រវត្តិទាំងឡាយ នៃ ទំព័រ',
'mergehistory-box'                 => 'បញ្ចូលរួមគ្នា កំណែទាំងឡាយ នៃ ពីរ ទំព័រ៖',
'mergehistory-from'                => 'ទំព័រអក្សរកូដ៖',
'mergehistory-into'                => 'ទំព័រ គោលដៅ៖',
'mergehistory-list'                => 'កំណែប្រែ នៃ ប្រវត្តិ ដែលអាចបញ្ចូលរួមគ្នា',
'mergehistory-go'                  => 'បង្ហាញ កំណែប្រែ ដែល អាចបញ្ចូលរួមគ្នា',
'mergehistory-submit'              => 'បញ្ចូលរួមគ្នា កំណែ នានា',
'mergehistory-empty'               => 'គ្មានកំណែ ណាមួយ អាចត្រូវបាន បញ្ចូលរួមគ្នា',
'mergehistory-no-source'           => 'ទំព័រ ប្រភព $1 មិនមានទេ។',
'mergehistory-no-destination'      => 'ទំព័រ គោលដៅ $1 មិនមានទេ។',
'mergehistory-invalid-source'      => 'ទំព័រ ប្រភព ត្រូវតែមាន មួយចំណងជើង បានការ។',
'mergehistory-invalid-destination' => 'ទំព័រ គោលដៅ ត្រូវតែមាន មួយចំណងជើង បានការ។',

# Merge log
'pagemerge-logentry' => 'បានបញ្ចូលរួមគ្នា [[$1]] ជាមួយ [[$2]] (កំណែនានា រហូតដល់ $3)',
'revertmerge'        => 'បំបែកចេញ',

# Diffs
'history-title'           => 'ប្រវត្តិ កំណែនានា នៃ "$1"',
'difference'              => '(ភាពខុសគ្នា នៃ កំណែនានា)',
'lineno'                  => 'បន្ទាប់ទី $1:',
'compareselectedversions' => 'ប្រៀបធៀប កំណែ ដែលបានជ្រើសយក',

# Search results
'searchresults'         => 'លទ្ធផល ស្វែងរក',
'searchsubtitle'        => "អ្នក បាន ស្វែងរក '''[[:$1]]'''",
'searchsubtitleinvalid' => "អ្នក បាន ស្វែងរក '''$1'''",
'noexactmatch'          => "'''គ្មានទំព័រ ដែលមានចំណងជើង \"\$1\"ទេ។''' អ្នកអាច [[:\$1|បង្កើតទំព័រនេះ]]។",
'noexactmatch-nocreate' => "'''គ្មានទំព័រ ដែលមានចំណងជើង \"\$1\"ទេ។'''",
'prevn'                 => 'មុន $1',
'nextn'                 => 'បន្ទាប់ $1',
'viewprevnext'          => 'មើល ($1) ($2) ($3)',
'powersearch'           => 'ស្វែងរក',

# Preferences page
'preferences'              => 'ចំណូលចិត្តនានា',
'mypreferences'            => 'ចំណូលចិត្តនានា របស់ខ្ញុំ',
'prefs-edits'              => 'ចំនួន នៃ កំណែប្រែ៖',
'prefsnologin'             => 'មិនបាន ពិនិត្យចូល',
'prefsnologintext'         => 'អ្នកត្រូវតែ [[Special:Userlogin|បានពិនិត្យចូល]] ដើម្បី កំណត់ ចំណូលចិត្ត របស់ អ្នកប្រើប្រាស់។',
'qbsettings'               => 'របារទាន់ចិត្ត',
'qbsettings-none'          => 'ទទេ',
'qbsettings-fixedleft'     => 'តំរឹម ឆ្វេង',
'qbsettings-fixedright'    => 'តំរឹម ស្តាំ',
'qbsettings-floatingleft'  => 'អណ្តែតឆ្វេង',
'qbsettings-floatingright' => 'អណ្តែតស្តាំ',
'changepassword'           => 'ប្តូរពាក្យសំងាត់',
'skin'                     => 'សំបក',
'math'                     => 'គណិត',
'dateformat'               => 'ទំរង់កាលបរិច្ឆេទ',
'datedefault'              => 'គ្មានចំណូលចិត្ត',
'datetime'                 => 'ថ្ងៃខែឆ្នាំ និង ពេលម៉ោង',
'math_failure'             => 'កំហុស គណិត',
'math_unknown_error'       => 'កំហុសមិនស្គាល់',
'math_unknown_function'    => 'អនុគម មិនត្រូវបានស្គាល់',
'math_syntax_error'        => 'កំហុស ពាក្យសម្ព័ន្ធ',
'math_bad_tmpdir'          => 'មិនអាច សរសេរទៅ ឬ បង្កើត ថតឯកសារ គណិត បណ្តោះអាសន្ន',
'prefs-personal'           => 'ប្រវត្តិរូប',
'prefs-rc'                 => 'បំលាស់ប្តូរថ្មីៗ',
'prefs-watchlist'          => 'បញ្ជីតាមដាន',
'prefs-watchlist-days'     => 'ថ្ងៃត្រូវបង្ហាញ ក្នុងបញ្ជីតាមដាន៖',
'prefs-misc'               => 'ផ្សេងៗ',
'saveprefs'                => 'រក្សាទុក',
'resetprefs'               => 'ធ្វើអោយដូចដើមវិញ',
'oldpassword'              => 'ពាក្យសំងាត់ចាស់៖',
'newpassword'              => 'ពាក្យសំងាត់ថ្មី៖',
'retypenew'                => 'វាយពាក្យសំងាត់ថ្មី ឡើងវិញ៖',
'textboxsize'              => 'កំណែប្រែ',
'rows'                     => 'ជួរដេក៖',
'columns'                  => 'ជួរឈរ៖',
'searchresultshead'        => 'ស្វែងរក',
'recentchangesdays'        => 'ថ្ងៃត្រូវបង្ហាញ ក្នុងបំលាស់ប្តូរថ្មី៖',
'recentchangescount'       => 'ចំនួនកំណែប្រែ ត្រូវបង្ហាញ ក្នុងបំលាស់ប្តូរថ្មី៖',
'savedprefs'               => 'ចំណូលចិត្តនានា របស់អ្នក ត្រូវបានរក្សាទុកហើយ។',
'timezonelegend'           => 'ល្វែងម៉ោង',
'timezonetext'             => 'ចំនួនម៉ោង ដែលម៉ោងតំបន់ ខុសគ្នាពី ម៉ោងម៉ាស៊ីនបំរើសេវា (UTC)។',
'localtime'                => 'ម៉ោងតំបន់',
'timezoneoffset'           => 'ទូទាត់¹',
'servertime'               => 'ម៉ោង ម៉ាស៊ីនបំរើសេវា',
'guesstimezone'            => 'បំពេញ ពីឧបកររាវរក',
'allowemail'               => 'អាចទទួល អ៊ីមែវល៍ ពីអ្នកប្រើប្រាស់ ដទៃទៀត',
'default'                  => 'លំនាំដើម',
'files'                    => 'ឯកសារ',

# User rights
'userrights-lookup-user'   => 'គ្រប់គ្រង ក្រុមអ្នកប្រើប្រាស់',
'userrights-user-editname' => 'បញ្ចូលឈ្មោះ អ្នកប្រើប្រាស់៖',
'editusergroup'            => 'កែប្រែ ក្រុមអ្នកប្រើប្រាស់',
'userrights-editusergroup' => 'កែប្រែ ក្រុមអ្នកប្រើប្រាស់',
'saveusergroups'           => 'រក្សាទុក ក្រុមអ្នកប្រើប្រាស់',
'userrights-groupsmember'  => 'សមាជិកនៃ៖',
'userrights-reason'        => 'ហេតុផល ចំពោះបំលាស់ប្តូរ៖',

# Groups
'group'       => 'ក្រុម៖',
'group-sysop' => 'អ្នកថែទាំប្រព័ន្ធ(Sysops) នានា',
'group-all'   => '(ទាំងអស់)',

'group-sysop-member' => 'អ្នកថែទាំប្រព័ន្ធ (Sysop)',

# User rights log
'rightsnone' => '(ទទេ)',

# Recent changes
'recentchanges'   => 'បំលាស់ប្តូរថ្មីៗ',
'rclistfrom'      => 'បង្ហាញ បំលាស់ប្តូរថ្មី ដែល ចាប់ផ្តើម ពី $1',
'rcshowhideminor' => '$1 កំណែប្រែ តិចតួច',
'rcshowhideliu'   => '$1 អ្នកប្រើប្រាស់ ដែលបានពិនិត្យចូល',
'rcshowhideanons' => '$1 អ្នកប្រើប្រាស់ អនាមិក',
'rcshowhidemine'  => '$1 កំណែប្រែ របស់ខ្ញុំ',
'rclinks'         => 'បង្ហាញ $1 បំលាស់ប្តូរ ចុងក្រោយ ក្នុង $2 ថ្ងៃ ចុងក្រោយ<br />$3',
'hide'            => 'បិទបាំង',
'show'            => 'បង្ហាញ',

# Recent changes linked
'recentchangeslinked' => 'បំលាស់ប្តូរ ទាក់ទិន',

# Upload
'upload'            => 'ផ្ទុកឯកសារឡើង',
'uploadbtn'         => 'ផ្ទុកឯកសារឡើង',
'reupload'          => 'ផ្ទុកឡើង ជាថ្មី',
'reuploaddesc'      => 'ត្រឡប់ទៅ បែបបទផ្ទុកឡើង។',
'uploadnologin'     => 'មិនបាន ពិនិត្យចូល',
'uploaderror'       => 'កំហុសផ្ទុកឡើង',
'uploadlogpage'     => 'កំណត់ហេតុ នៃការផ្ទុកឡើង',
'uploadlogpagetext' => 'ខាងក្រោមនេះ ជាបញ្ជីនៃការផ្ទុកឡើង ថ្មីបំផុត។',
'filename'          => 'ឈ្មោះឯកសារ',
'filedesc'          => 'សង្ខេប',
'fileuploadsummary' => 'សង្ខេប៖',
'filestatus'        => 'ស្ថានភាព រក្សាសិទ្ធិ',
'filesource'        => 'អក្សរកូដ',
'uploadedfiles'     => 'ឯកសារនានា ត្រូវបានផ្ទុកឡើង',
'ignorewarnings'    => 'មិនខ្វល់ ការព្រមាន ណាមួយ',
'filetype-missing'  => 'ឯកសារ មិនមានកន្ទុយ (ដូចជា ".jpg")។',
'successfulupload'  => 'ផ្ទុកឡើង ដោយជោគជ័យ',
'savefile'          => 'រក្សាទុក',
'uploadedimage'     => 'បានផ្ទុកឡើង "[[$1]]"',
'sourcefilename'    => 'ឈ្មោះឯកសារ ប្រភព',
'destfilename'      => 'ឈ្មោះឯកសារ គោលដៅ',
'watchthisupload'   => 'តាមដានទំព័រនេះ',

'upload-file-error' => 'កំហុសខាងក្នុង',

'license'   => 'អាជ្ញាបណ្ណ',
'nolicense' => 'គ្មានអ្វី ត្រូវបានជ្រើសយក',

# Image list
'imagelist'                 => 'បញ្ជីរូបភាព',
'ilsubmit'                  => 'ស្វែងរក',
'byname'                    => 'តាម ឈ្មោះ',
'bydate'                    => 'តាម ថ្ងៃខែឆ្នាំ',
'bysize'                    => 'តាម ទំហំ',
'imgdelete'                 => 'លុប',
'imgfile'                   => 'ឯកសារ',
'filehist'                  => 'ប្រវត្តិ ឯកសារ',
'filehist-deleteall'        => 'លុបចេញ ទាំងអស់',
'filehist-deleteone'        => 'លុបនេះចេញ',
'filehist-datetime'         => 'ថ្ងៃខែឆ្នាំ/ម៉ោងពេល',
'filehist-user'             => 'អ្នកប្រើប្រាស់',
'filehist-filesize'         => 'ទំហំ ឯកសារ',
'filehist-comment'          => 'វិចារ',
'imagelinks'                => 'តំណភ្ជាប់ នានា',
'nolinkstoimage'            => 'គ្មានទំព័រណាមួយ ដែលតភ្ជាប់ទៅ ឯកសារនេះ។',
'shareduploadwiki-linktext' => 'ទំព័រពិពណ៌នា ឯកសារ',
'noimage-linktext'          => 'ផ្ទុកវាឡើង',
'uploadnewversion-linktext' => 'ផ្ទុកឡើង មួយកំណែថ្មី នៃ ឯកសារនេះ',
'imagelist_date'            => 'ថ្ងៃខែឆ្នាំ',
'imagelist_name'            => 'ឈ្មោះ',
'imagelist_user'            => 'អ្នកប្រើប្រាស់',
'imagelist_size'            => 'ទំហំ',
'imagelist_description'     => 'ការពិពណ៌នា',
'imagelist_search_for'      => 'ស្វែងរក ឈ្មោះរូបភាព៖',

# File reversion
'filerevert-comment' => 'វិចារ៖',

# File deletion
'filedelete'                  => 'លុបចេញ $1',
'filedelete-legend'           => 'លុបចេញ ឯកសារ',
'filedelete-comment'          => 'ហេតុផល ចំពោះ ការលុបចេញ៖',
'filedelete-submit'           => 'លុបចេញ',
'filedelete-otherreason'      => 'ហេតុផល ផ្សេង/បន្ថែម៖',
'filedelete-reason-otherlist' => 'ហេតុផល ផ្សេងទៀត',

# MIME search
'mimesearch' => 'ស្វែងរក MIME',
'mimetype'   => 'ប្រភេទ MIME ៖',
'download'   => 'ទាញយក',

# Unused templates
'unusedtemplateswlh' => 'តំណភ្ជាប់ ដទៃទៀត',

# Random page
'randompage'         => 'ទំព័រ ឥតព្រាងទុក',
'randompage-nopages' => 'គ្មានទំព័រណាមួយ ក្នុងវាលឈ្មោះនេះ។',

# Statistics
'statistics'             => 'ស្ថិតិ',
'sitestats'              => 'ស្ថិតិ {{SITENAME}}',
'userstats'              => 'ស្ថិតិ អ្នកប្រើប្រាស់',
'statistics-mostpopular' => 'ទំព័រ ត្រូវបានមើល ច្រើនបំផុត',

'brokenredirects-edit'   => '(កែប្រែ)',
'brokenredirects-delete' => '(លុបចេញ)',

'withoutinterwiki'        => 'ទំព័រ គ្មានតំណភ្ជាប់ភាសា',
'withoutinterwiki-header' => 'ទំព័រទាំងនេះ មិនតភ្ជាប់ ទៅកំណែភាសាដទៃ៖',
'withoutinterwiki-submit' => 'បង្ហាញ',

# Miscellaneous special pages
'specialpage-empty'      => 'របាយការនេះ​ គ្មានលទ្ធផល។',
'uncategorizedpages'     => 'ទំព័រ មិនត្រូវបានដាក់ ចំណាត់ក្រុម',
'uncategorizedimages'    => 'រូបភាព មិនត្រូវបានដាក់ ចំណាត់ក្រុម',
'uncategorizedtemplates' => 'គំរូខ្នាត មិនត្រូវបានដាក់ ចំណាត់ក្រុម',
'unusedimages'           => 'ឯកសារ ដែលមិនត្រូវបាន ប្រើប្រាស់',
'popularpages'           => 'ទំព័រ ដែលមាន ប្រជាប្រិយ',
'allpages'               => 'គ្រប់ទំព័រ',
'shortpages'             => 'ទំព័រខ្លីៗ',
'longpages'              => 'ទំព័រវែងៗ',
'protectedpages'         => 'ទំព័រនានា ដែលត្រូវបានការពារ',
'protectedtitles'        => 'ចំណងជើងនានា ដែលត្រូវបានការពារ',
'listusers'              => 'បញ្ជី អ្នកប្រើប្រាស់',
'specialpages'           => 'ទំព័រពិសេសៗ',
'spheading'              => 'ទំព័រ ពិសេសៗ សំរាប់ គ្រប់អ្នកប្រើប្រាស់',
'newpages'               => 'ទំព័រថ្មី នានា',
'newpages-username'      => 'ឈ្មោះ អ្នកប្រើប្រាស់៖',
'ancientpages'           => 'ទំព័រ ចាស់បំផុត',
'intl'                   => 'តំណភ្ជាប់ អន្តរភាសា នានា',
'move'                   => 'ប្តូរទីតាំង',
'movethispage'           => 'ប្តូរទីតាំង ទំព័រនេះ',
'notargettitle'          => 'គ្មានគោលដៅ',

# Book sources
'booksources-go' => 'ទៅ',

'data'    => 'ទិន្នន័យ',
'groups'  => 'ក្រុមអ្នកប្រើប្រាស់ នានា',
'version' => 'កំណែ',

# Special:Log
'specialloguserlabel'  => 'អ្នកប្រើប្រាស់៖',
'speciallogtitlelabel' => 'ចំណងជើង៖',
'log'                  => 'កំណត់ហេតុ',
'all-logs-page'        => 'គ្រប់កំណត់ហេតុ',
'log-search-submit'    => 'ទៅ',
'log-title-wildcard'   => 'ស្វែងរក ចំណងជើងនានា ដែលចាប់ផ្តើម ដោយអត្ថបទនេះ',

# Special:Allpages
'nextpage'       => 'ទំព័របន្ទាប់ ($1)',
'prevpage'       => 'ទំព័រមុន ($1)',
'allarticles'    => 'គ្រប់ទំព័រ',
'allpagesprev'   => 'មុន',
'allpagesnext'   => 'បន្ទាប់',
'allpagessubmit' => 'ទៅ',

# Special:Listusers
'listusers-submit'   => 'បង្ហាញ',
'listusers-noresult' => 'រកមិនឃើញ អ្នកប្រើប្រាស់។',

# E-mail user
'emailuser'     => 'អ៊ីមែវល៍ ទៅ អ្នកប្រើប្រាស់នេះ',
'emailpage'     => 'អ្នកប្រើប្រាស់ អ៊ីមែវល៍',
'noemailtitle'  => 'គ្មាន អាស័យដ្ឋានអ៊ីមែវល៍',
'emailfrom'     => 'ពី',
'emailto'       => 'ដល់',
'emailsubject'  => 'ប្រធានបទ',
'emailmessage'  => 'សារ',
'emailsend'     => 'ផ្ញើ',
'emailccme'     => 'អ៊ីមែវល៍មកខ្ញុំ មួយច្បាប់ចំលង នៃសាររបស់ខ្ញុំ។',
'emailsent'     => 'អ៊ីមែវល៍ ត្រូវបាន ផ្ញើទៅហើយ',
'emailsenttext' => 'សារអ៊ីមែវល៍ របស់អ្នក ត្រូវបាន ផ្ញើទៅហើយ។',

# Watchlist
'watchlist'            => 'បញ្ជីតាមដាន របស់ខ្ញុំ',
'mywatchlist'          => 'បញ្ជីតាមដាន របស់ខ្ញុំ',
'nowatchlist'          => 'អ្នកគ្មានរបស់អ្វី លើបញ្ជីតាមដាន របស់អ្នក',
'watchnologin'         => 'មិនបាន ពិនិត្យចូល',
'addedwatch'           => 'បានបន្ថែម ទៅ បញ្ជីតាមដាន',
'removedwatch'         => 'ត្រូវបានប្តូរទីតាំង ពី បញ្ជីតាមដាន',
'watch'                => 'តាមដាន',
'watchthispage'        => 'តាមមើល ទំព័រនេះ',
'unwatch'              => 'លែង តាមដាន',
'unwatchthispage'      => 'ឈប់ តាមដាន',
'watchmethod-recent'   => 'ឆែកមើល កំណែប្រែថ្មីៗ ចំពោះ ទំព័រត្រូវបានតាមដាន',
'watchmethod-list'     => 'ឆែកមើល ទំព័រត្រូវបានតាមដាន ចំពោះ កំណែប្រែថ្មីៗ',
'watchlist-show-own'   => 'បង្ហាញ កំណែប្រែនានា ​របស់ខ្ញុំ',
'watchlist-hide-own'   => 'បិទបាំង កំណែប្រែនានា ​របស់ខ្ញុំ',
'watchlist-show-minor' => 'បង្ហាញ កំណែប្រែ តិចតួច',
'watchlist-hide-minor' => 'បិទបាំង កំណែប្រែ តិចតួច',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'កំពុងតាមមើល...',
'unwatching' => 'លែង តាមដាន...',

'enotif_newpagetext'           => 'នេះជា ទំព័រថ្មីមួយ។',
'enotif_impersonal_salutation' => 'អ្នកប្រើប្រាស់ {{SITENAME}}',
'changed'                      => 'បានផ្លាស់ប្តូរ',
'created'                      => 'បានបង្កើត',

# Delete/protect/revert
'deletepage'             => 'លុបចេញ ទំព័រ',
'confirm'                => 'បញ្ជាក់ទទួលស្គាល់',
'exblank'                => 'ទំព័រទទេ',
'delete-confirm'         => 'លុបចេញ "$1"',
'delete-legend'          => 'លុបចេញ',
'deletedarticle'         => 'បានលុបចេញ "[[$1]]"',
'deletecomment'          => 'ហេតុផល ចំពោះ ការលុបចេញ៖',
'deleteotherreason'      => 'ហេតុផល ផ្សេង/បន្ថែម៖',
'deletereasonotherlist'  => 'ហេតុផល ដទៃ',
'confirmprotect'         => 'បញ្ជាក់ទទួលស្គាល់ ការការពារ',
'protectcomment'         => 'វិចារ៖',
'protectexpiry'          => 'ផុតកំណត់៖',
'protect_expiry_invalid' => 'ពេលវេលា ផុតកំណត់ មិនមានសុពលភាព។',
'protect_expiry_old'     => 'ពេលវេលា ផុតកំណត់ ថិតក្នុង អតីតកាល។',
'protect-default'        => '(លំនាំដើម)',
'protect-level-sysop'    => 'សំរាប់តែ អ្នកថែទាំប្រព័ន្ធ (Sysops only)',
'restriction-type'       => 'ការអនុញ្ញាតិ៖',
'minimum-size'           => 'ទំហំអប្បបរិមា',
'maximum-size'           => 'ទំហំអតិបរិមា',
'pagesize'               => '(បៃ)',

# Restrictions (nouns)
'restriction-edit'   => 'កែប្រែ',
'restriction-move'   => 'ប្តូរទីតាំង',
'restriction-create' => 'បង្កើត',

# Restriction levels
'restriction-level-sysop'         => 'បានការពារ',
'restriction-level-autoconfirmed' => 'ពាក់កណ្តាលបានការពារ',
'restriction-level-all'           => 'កំរិត ណាក៏ដោយ',

# Undelete
'undelete'               => 'មើល ទំព័រនានា ដែលបានត្រូវលុបចេញ',
'viewdeletedpage'        => 'មើល ទំព័រនានា ដែលបានត្រូវលុបចេញ',
'undeletereset'          => 'កំណត់ ឡើងវិញ',
'undeletecomment'        => 'វិចារ៖',
'undelete-search-box'    => 'ស្វែងរកទំព័រ ដែលបានត្រូវលុប',
'undelete-search-prefix' => 'បង្ហាញទំព័រ ចាប់ផ្តើមដោយ៖',
'undelete-search-submit' => 'ស្វែងរក',

# Namespace form on various pages
'namespace' => 'វាលឈ្មោះ៖',

# Contributions
'contributions' => 'ការរួមចំណែក របស់អ្នកប្រើប្រាស់',
'mycontris'     => 'ការរួមចំណែក របស់ខ្ញុំ',
'ucnote'        => 'ខាងក្រោម ជា <b>$1</b> បំលាស់ប្តូរចុងក្រោយ របស់អ្នកប្រើប្រាស់នេះ ក្នុង <b>$2</b> ថ្ងៃ ចុងក្រោយ។',
'uctop'         => '(បន្ទាន់សម័យ)',
'month'         => 'ពី ខែ (និង មុនជាង)៖',
'year'          => 'ពី ឆ្នាំ (និង មុនជាង)៖',

'sp-contributions-newbies'     => 'បង្ហាញតែ ការរួមចំណែក របស់គណនីថ្មី',
'sp-contributions-newbies-sub' => 'ចំពោះ គណនីថ្មី នានា',
'sp-contributions-search'      => 'ស្វែងរក ការរួមចំណែក នានា',
'sp-contributions-username'    => 'អាស័យដ្ឋាន IP ឬ ឈ្មោះអ្នកប្រើប្រាស់៖',
'sp-contributions-submit'      => 'ស្វែងរក',

# What links here
'whatlinkshere-page' => 'ទំព័រ ៖',
'linklistsub'        => '(បញ្ជី នៃ តំណភ្ជាប់)',
'isredirect'         => 'ប្តូរទិស ទំព័រ',

# Block/unblock
'ipaddress'                => 'អាស័យដ្ឋាន IP ៖',
'ipadressorusername'       => 'អាស័យដ្ឋាន IP ឬ ឈ្មោះអ្នកប្រើប្រាស់៖',
'ipbreason'                => 'មូលហេតុ៖',
'ipbreasonotherlist'       => 'ហេតុផលដទៃទៀត',
'ipbotheroption'           => 'ផ្សេងទៀត',
'ipbotherreason'           => 'ហេតុផល ផ្សេង/បន្ថែម៖',
'badipaddress'             => 'អាស័យដ្ឋាន IP មិនត្រឹមត្រូវ',
'ipblocklist-username'     => 'ឈ្មោះអ្នកប្រើប្រាស់ ឬ អាស័យដ្ឋាន IP៖',
'ipblocklist-submit'       => 'ស្វែងរក',
'anononlyblock'            => 'អនាមិក ប៉ុណ្ណោះ',
'contribslink'             => 'ការរួមចំណែក',
'blocklogpage'             => 'កំណត់ហេតុ នៃការរាំងខ្ទប់',
'block-log-flags-anononly' => 'សំរាប់តែ អ្នកប្រើប្រាស់អនាមិក',
'proxyblocksuccess'        => 'រួចរាល់។',

# Developer tools
'lockdb'              => 'ចាក់សោ មូលដ្ឋានទិន្នន័យ',
'unlockdb'            => 'ដោះសោ មូលដ្ឋានទិន្នន័យ',
'lockconfirm'         => 'បាទ/ចាស, ខ្ញុំពិតជាចង់ ចាក់សោ មូលដ្ឋានទិន្នន័យ។',
'unlockconfirm'       => 'បាទ/ចាស, ខ្ញុំពិតជាចង់ ដោះសោ មូលដ្ឋានទិន្នន័យ។',
'lockbtn'             => 'ចាក់សោ មូលដ្ឋានទិន្នន័យ',
'unlockbtn'           => 'ចាក់សោ មូលដ្ឋានទិន្នន័យ',
'locknoconfirm'       => 'អ្នកមិនបាន ឆែកមើល ប្រអប់បញ្ជាក់ទទួលស្គាល់។',
'unlockdbsuccesssub'  => 'សោ មូលដ្ឋានទិន្នន័យ ត្រូវបានដកចេញ',
'unlockdbsuccesstext' => 'មូលដ្ឋានទិន្នន័យ ត្រូវបានដោះសោ រួចហើយ។',
'databasenotlocked'   => 'មូលដ្ឋានទិន្នន័យ មិនត្រូវបានចាក់សោ។',

# Move page
'movepage'                => 'ប្តូរទីតាំង ទំព័រ',
'movearticle'             => 'ប្តូរទីតាំង ទំព័រ៖',
'movenologin'             => 'មិនបាន ពិនិត្យចូល',
'newtitle'                => 'ទៅ ចំណងជើងថ្មី៖',
'move-watch'              => 'តាមដាន ទំព័រនេះ',
'movepagebtn'             => 'ប្តូរទីតាំង ទំព័រ',
'movedto'                 => 'បានប្តូរទីតាំង ទៅ',
'movelogpage'             => 'កំណត់ហេតុ នៃបណ្តូរទីតាំង',
'movereason'              => 'ហេតុផល៖',
'delete_and_move'         => 'លុបចេញ និង ប្តូរទីតាំង',
'delete_and_move_confirm' => 'បាទ/ចាស, លុបចេញ ទំព័រ',

# Export
'export'            => 'នាំចេញ ទំព័រនានា',
'export-submit'     => 'នាំចេញ',
'export-addcattext' => 'បន្ថែមទំព័រនានា ពីចំណាត់ក្រុម៖',
'export-addcat'     => 'បន្ថែម',
'export-download'   => 'រក្សាទុក ជា ឯកសារ',
'export-templates'  => 'រួមទាំង គំរូខ្នាតនានា',

# Namespace 8 related
'allmessagesname'    => 'ឈ្មោះ',
'allmessagesdefault' => 'អត្ថបទ លំនាំដើម',

# Thumbnails
'thumbnail-more' => 'ពង្រីក',

# Special:Import
'import'                  => 'នាំចូល ទំព័រនានា',
'import-interwiki-submit' => 'នាំចូល',
'importnopages'           => 'គ្មានទំព័រណាមួយ ត្រូវនាំចូល។',
'importcantopen'          => 'មិនអាចបើក ឯកសារនាំចូល',
'importnotext'            => 'ទទេ ឬ គ្មានអត្ថបទ',
'importsuccess'           => 'នាំចូល ត្រូវបានបញ្ចប់!',
'import-noarticle'        => 'គ្មានទំព័រណា ត្រូវនាំចូល!',

# Tooltip help for the actions
'tooltip-pt-userpage'     => 'ទំព័រអ្នកប្រើប្រាស់ របស់ខ្ញុំ',
'tooltip-pt-mytalk'       => 'ទំព័រពិភាក្សា របស់ខ្ញុំ',
'tooltip-pt-preferences'  => 'ចំណូលចិត្តនានា របស់ខ្ញុំ',
'tooltip-pt-mycontris'    => 'បញ្ជីរួមចំណែក របស់ខ្ញុំ',
'tooltip-pt-logout'       => 'ពិនិត្យចេញ',
'tooltip-ca-addsection'   => 'បន្ថែមមួយវិចារ ទៅ ការពិភាក្សានេះ។',
'tooltip-ca-protect'      => 'ការពារទំព័រនេះ',
'tooltip-ca-delete'       => 'លុបទំព័រនេះ',
'tooltip-ca-move'         => 'ប្តូរទីតាំង ទំព័រនេះ',
'tooltip-ca-watch'        => 'បន្ថែមទំព័រនេះ ទៅបញ្ជីតាមដាន របស់អ្នក',
'tooltip-ca-unwatch'      => 'ដកចេញ ទំព័រនេះ ពីបញ្ជីតាមដាន របស់ខ្ញុំ',
'tooltip-search-fulltext' => 'ស្វែងរក ទំព័រនានា សំរាប់ អត្ថបទនេះ',
'tooltip-p-logo'          => 'ទំព័រដើម',
'tooltip-n-mainpage'      => 'ចូលមើល ទំព័រដើម',
'tooltip-n-recentchanges' => 'បញ្ជី នៃ បំលាស់ប្តូរថ្មីៗ នៅក្នុងវិគី។',
'tooltip-n-randompage'    => 'ផ្ទុក មួយទំព័រព្រាវ',
'tooltip-n-sitesupport'   => 'គាំទ្រ យើង',
'tooltip-feed-rss'        => 'បំរែបំរួល RSS ចំពោះទំព័រនេះ',
'tooltip-feed-atom'       => 'បំរែបំរួល Atom ចំពោះទំព័រនេះ',
'tooltip-t-contributions' => 'បង្ហាញ បញ្ជីរួមចំណែក នៃអ្នកប្រើប្រាស់នេះ',
'tooltip-t-emailuser'     => 'ផ្ញើ មួយមែវល៍ ទៅ អ្នកប្រើប្រាស់នេះ',
'tooltip-t-upload'        => 'ផ្ទុកឡើង ឯកសាររូបភាព ឬ ឯកសារមីឌា នានា',
'tooltip-t-specialpages'  => 'បញ្ជី នៃ គ្រប់ទំព័រ ពិសេស',
'tooltip-upload'          => 'ចាប់ផ្តើម ផ្ទុកឡើង',

# Attribution
'others' => 'ផ្សេងៗទៀត',

# Spam protection
'listingcontinuesabbrev' => 'បន្ត.',

# Info page
'infosubtitle' => 'ពត៌មាន សំរាប់ ទំព័រ',

# Image deletion
'filedeleteerror-short' => 'កំហុស លុបឯកសារ៖ $1',

# Media information
'thumbsize' => 'ទំហំកូនរូបភាព៖',

# Special:Newimages
'newimages' => 'វិចិត្រសាលនៃឯកសារ(Gallery of new files)',
'noimages'  => 'គ្មានឃើញអី សោះ។',

# EXIF tags
'exif-imagedescription' => 'ចំណងជើង រូបភាព',
'exif-artist'           => 'អ្នកនិពន្ធ',
'exif-usercomment'      => 'វិចារ នានា របស់អ្នកប្រើប្រាស់',

'exif-meteringmode-0'   => 'មិនបានស្គាល់',
'exif-meteringmode-1'   => 'មធ្យម',
'exif-meteringmode-255' => 'ផ្សេងទៀត',

'exif-sensingmethod-1' => 'មិនត្រូវបានកំណត់',

'exif-scenecapturetype-1' => 'រូបផ្តេក',
'exif-scenecapturetype-2' => 'រូបបញ្ឈរ',

'exif-gaincontrol-0' => 'ទទេ',

'exif-sharpness-0' => 'ធម្មតា',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'គីឡូមែត្រ ក្នុងមួយម៉ោង',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ទាំងអស់',
'imagelistall'     => 'ទាំងអស់',
'watchlistall2'    => 'ទាំងអស់',
'namespacesall'    => 'ទាំងអស់',
'monthsall'        => 'ទាំងអស់',

# E-mail address confirmation
'confirmemail'          => 'បញ្ជាក់ទទួលស្គាល់ អាស័យដ្ឋានអ៊ីមែវល៍',
'confirmemail_sent'     => 'បញ្ជាក់ទទួលស្គាល់ អាស័យដ្ឋានអ៊ីមែវល៍ ដែលបានផ្ញើទៅ។',
'confirmemail_loggedin' => 'អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក ត្រូវបាន បញ្ជាក់ទទួលស្គាល់ ហើយ',

# Delete conflict
'recreate' => 'បង្កើតឡើងវិញ',

# action=purge
'confirm_purge_button' => 'យល់ព្រម',

# Multipage image navigation
'imgmultipageprev' => '← ទំព័រមុន',
'imgmultipagenext' => 'ទំព័របន្ទាប់ →',
'imgmultigo'       => 'ទៅ!',
'imgmultigotopre'  => 'ទៅទំព័រ',

# Table pager
'ascending_abbrev'         => 'លំដាប់ឡើង',
'descending_abbrev'        => 'លំដាប់ចុះ',
'table_pager_next'         => 'ទំព័របន្ទាប់',
'table_pager_prev'         => 'ទំព័រមុន',
'table_pager_first'        => 'ទំព័រដំបូង',
'table_pager_last'         => 'ទំព័រចុងក្រោយ',
'table_pager_limit'        => 'បង្ហាញ $1 មុខ ក្នុងមួយទំព័រ',
'table_pager_limit_submit' => 'ទៅ',
'table_pager_empty'        => 'គ្មានលទ្ធផល',

# Auto-summaries
'autosumm-new' => 'ទំព័រថ្មី៖ $1',

# Live preview
'livepreview-loading' => 'កំពុងផ្ទុក…',

# Watchlist editor
'watchlistedit-normal-title'  => 'កែប្រែ បញ្ជីតាមដាន',
'watchlistedit-normal-legend' => 'ដកចេញ ចំណងជើងនានា ពី បញ្ជីតាមដាន',
'watchlistedit-normal-submit' => 'ដកចេញ ចំណងជើងនានា',
'watchlistedit-raw-title'     => 'កែប្រែ បញ្ជីតាមដានឆៅ',
'watchlistedit-raw-legend'    => 'កែប្រែ បញ្ជីតាមដានឆៅ',
'watchlistedit-raw-titles'    => 'ចំណងជើងនានា៖',
'watchlistedit-raw-submit'    => 'បន្ទាន់សម័យ បញ្ជីតាមដាន',
'watchlistedit-raw-done'      => 'បញ្ជីតាមដាន ត្រូវបាន បន្ទាន់សម័យ ហើយ។',

# Watchlist editing tools
'watchlisttools-view' => 'មើល បំលាស់ប្តូរ ទាក់ទិន',
'watchlisttools-edit' => 'មើល និង កែប្រែ បញ្ជីតាមដាន',
'watchlisttools-raw'  => 'កែប្រែ បញ្ជីតាមដានឆៅ',

# Special:Version
'version-specialpages'     => 'ទំព័រ ពិសេសៗ',
'version-other'            => 'ផ្សេងទៀត',
'version-version'          => 'កំណែ (Version)',
'version-license'          => 'អាជ្ញាបណ្ណ',
'version-software'         => 'ផ្នែកទន់ ដែលបាន តំលើង',
'version-software-product' => 'ផលិតផល',
'version-software-version' => 'កំណែ',

# Special:Filepath
'filepath-page'   => 'ឯកសារ៖',
'filepath-submit' => 'ផ្លូវ',

);
