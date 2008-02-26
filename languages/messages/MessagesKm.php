<?php
/** Khmer (ភាសាខ្មែរ)
 *
 * @addtogroup Language
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Bunly
 * @author តឹក ប៊ុនលី
 * @author Chhorran
 * @author Siebrand
 * @author SPQRobin
 * @author គីមស៊្រុន
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
'tog-underline'               => 'គូសបន្ទាត់ក្រោម តំណភ្ជាប់៖',
'tog-highlightbroken'         => 'បង្ហាញ <a href="" class="new">ជា ក្រហម </a>តំណភ្ជាប់ នានា ទៅទំព័រ មិនមាន (alternative:ដូចនេះ<a href="" class="internal">នេះ ឬ ?</a>)',
'tog-justify'                 => 'តំរឹម កថាខណ្ឌ',
'tog-hideminor'               => 'បិទបាំង កំណែប្រែតិចតួច ក្នុងបញ្ជីបំលាស់ប្តូរថ្មីៗ',
'tog-extendwatchlist'         => 'ពង្រីកបញ្ជីតាមដាន ដើម្បីបង្ហាញ គ្រប់បំលាស់ប្តូរ',
'tog-usenewrc'                => 'ធ្វើអោយ បំលាស់ប្តូរ​ថ្មីៗ(JavaScript) កាន់តែប្រសើរឡើង',
'tog-numberheadings'          => 'វាយលេខ ចំណងជើងរង ដោយស្វ័យប្រវត្តិ',
'tog-showtoolbar'             => 'បង្ហាញ របារឧបករកែប្រែ (JavaScript)',
'tog-editondblclick'          => 'ចុចពីរដងដើម្បីកែប្រែទំព័រ (JavaScript)',
'tog-editsection'             => 'អនុញ្ញាតិ អោយកែប្រែផ្នែកណាមួយ តាមរយៈតំណភ្ជាប់[កែប្រែ]',
'tog-editsectiononrightclick' => 'អនុញ្ញាតិអោយកែប្រែ​ ផ្នែកណាមួយ(JavaScript) ដោយចុចស្តាំកណ្តុរ លើចំណងជើងរបស់វា',
'tog-showtoc'                 => 'បង្ហាញ តារាងមាតិកា(ចំពោះទំព័រ ដែលមាន លើសពី៣ ចំណងជើងរង)',
'tog-rememberpassword'        => 'ចងចាំ ការពិនិត្យចូលរបស់ខ្ញុំ លើខំព្យូរើនេះ',
'tog-editwidth'               => 'បង្ហាញ បង្អួច កែប្រែ ជា ទទឹង ពេញ',
'tog-watchcreations'          => 'បន្ថែម ទំព័រ ដែលខ្ញុំបង្កើត ទៅ បញ្ជីតាមដាន របស់ខ្ញុំ',
'tog-watchdefault'            => 'បន្ថែម ទំព័រ ដែលខ្ញុំកែប្រែ ទៅ បញ្ជីតាមដាន របស់ខ្ញុំ',
'tog-watchmoves'              => 'បន្ថែមទំព័រ នានា ដែល ខ្ញុំប្តូរទីតាំង ទៅ បញ្ជីតាមដាន របស់ខ្ញុំ',
'tog-watchdeletion'           => 'បន្ថែមទំព័រ ដែលខ្ញុំលុបចេញ ទៅ បញ្ជីតាមដាន របស់ខ្ញុំ',
'tog-minordefault'            => 'ចំណាំ គ្រប់កំណែប្រែ របស់ខ្ញុំ ថាជា តិចតួច',
'tog-previewontop'            => 'បង្ហាញ ការមើលមុន ពីលើ ប្រអប់ កែប្រែ',
'tog-previewonfirst'          => 'បង្ហាញ ការមើលមុន ចំពោះ កំណែប្រែ ដំបូង',
'tog-enotifwatchlistpages'    => 'អ៊ីមែវល៍ មកខ្ញុំ កាលបើ បញ្ជីតាមដាន របស់អ្នក បានត្រូវ ផ្លាស់ប្តូរ',
'tog-enotifusertalkpages'     => 'អ៊ីមែវល៍មកខ្ញុំ កាលបើទំព័រពិភាក្សា របស់អ្នក បានត្រូវផ្លាស់ប្តូរ',
'tog-enotifminoredits'        => 'អ៊ីមែវល៍មកខ្ញុំ ផងដែរ ចំពោះបំលាស់ប្តូរតិចតួច នៃទំព័រនានា',
'tog-enotifrevealaddr'        => 'បង្ហាញ អាស័យដ្ឋានអ៊ីមែវល៍ របស់ខ្ញុំ ក្នុង មែវល៍ក្រើនរំលឹក នានា',
'tog-shownumberswatching'     => 'បង្ហាញចំនួនអ្នកប្រើប្រាស់ ដែលតាមដានទំព័រនេះ',
'tog-fancysig'                => 'ហត្ថលេខាឆៅ (គ្មានតំណភ្ជាប់ ស្វ័យប្រវត្តិ)',
'tog-externaleditor'          => 'ប្រើប្រាស់ឧបករកែប្រែ ខាងក្រៅ តាមលំនាំដើម',
'tog-externaldiff'            => 'ប្រើប្រាស់ឧបករ ប្រៀបធៀបក្រៅ តាមលំនាំដើម',
'tog-showjumplinks'           => 'ធ្វើអោយសកម្ម តំណភ្ជាប់ «ត្រាច់រក» និង «ស្វែងរក» នៅផ្នែកលើ នៃទំព័រ (ចំពោះសំបក Myskin និង ផ្សេងទៀត)',
'tog-uselivepreview'          => 'ប្រើប្រាស់ ការមើលមុនរហ័ស (JavaScript) (អ្នកមានបទពិសោធ)',
'tog-forceeditsummary'        => 'រំលឹកខ្ញុំ កាលបើខ្ញុំទុកប្រអប់វិចារ អោយទំនេរ',
'tog-watchlisthideown'        => 'បិទបាំងកំណែប្រែរបស់ខ្ញុំ ពីបញ្ជីតាមដាន',
'tog-watchlisthidebots'       => 'បិទបាំងកំណែប្រែ របស់​រូបយន្ត ពីបញ្ជីតាមដាន',
'tog-watchlisthideminor'      => 'បិទបាំង កំណែប្រែតិចតួច ពីបញ្ជីតាមដាន',
'tog-ccmeonemails'            => 'ផ្ញើខ្ញុំ ច្បាប់ចំលងអ៊ីមែវល៍ ដើម្បីខ្ញុំផ្ញើទៅ អ្នកប្រើប្រាស់ផ្សេងទៀត',
'tog-diffonly'                => 'មិនបង្ហាញ ខ្លឹមសារទំព័រ នៅពីក្រោម ភាពខុសគ្នា',
'tog-showhiddencats'          => 'បង្ហាញ ចំណាត់ក្រុម ដែលត្រូវបានបិទបាំង',

'underline-always'  => 'ជានិច្ច',
'underline-never'   => 'មិនដែលសោះ',
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
'categories'               => 'ចំណាត់ក្រុម',
'pagecategories'           => "'''$1''' ចំណាត់ក្រុម",
'category_header'          => 'ទំព័រ ដែលមាន ក្នុងចំណាត់ក្រុម "$1"',
'subcategories'            => 'ចំណាត់ក្រុមរង',
'category-media-header'    => 'ឯកសារមីឌាក្នុងចំណាត់ក្រុម "$1"',
'category-empty'           => "''ចំណាត់ក្រុមនេះ មិនមានផ្ទុកទំព័រ ឬ ឯកសារមីឌា ណាមួយទេ។''",
'hidden-categories'        => '{{PLURAL:$1|ចំណាត់ក្រុម ត្រូវបានបិទបាំង|ចំណាត់ក្រុមនានា ត្រូវបានបិទបាំង}}',
'hidden-category-category' => 'ចំណាត់ក្រុម ត្រូវបានបិទបាំង', # Name of the category where hidden categories will be listed

'mainpagetext'      => "<big>'''មីឌាវិគី ត្រូវបានតំលើង ដោយជោគជ័យ'''</big>",
'mainpagedocfooter' => 'ពិនិត្យមើល [http://meta.wikimedia.org/wiki/ជំនួយ៖ខ្លឹមសារ ណែនាំប្រើប្រាស់] សំរាប់ ពត៌មានបន្ថែម ចំពោះបំរើប្រាស់ ផ្នែកទន់វិគី។

== ចាប់ផ្តើមជាមួយ មីឌាវិគី ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings បញ្ជីកំណត់ទំរង់]
* [http://www.mediawiki.org/wiki/Manual:FAQ/km សំណួរញឹកញាប់ MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce បញ្ជីពិភាក្សា ការផ្សព្វផ្សាយរបស់ MediaWiki]',

'about'          => 'អំពី',
'article'        => 'មាតិកាអត្ថបទ',
'newwindow'      => '(បើកលើផ្ទាំងបង្អួចថ្មី)',
'cancel'         => 'បោះបង់',
'qbfind'         => 'រកមើល',
'qbbrowse'       => 'រាវរក',
'qbedit'         => 'កែប្រែ',
'qbpageoptions'  => 'ទំព័រនេះ',
'qbpageinfo'     => 'ពត៌មានទំព័រ',
'qbmyoptions'    => 'ទំព័រនានា របស់ខ្ញុំ',
'qbspecialpages' => 'ទំព័រពិសេសៗ',
'moredotdotdot'  => 'បន្ថែមទៀត...',
'mypage'         => 'ទំព័រ​ របស់ខ្ញុំ',
'mytalk'         => 'ការពិភាក្សា របស់ខ្ញុំ',
'anontalk'       => 'ពិភាក្សា ចំពោះ IP នេះ',
'navigation'     => 'ទិសដៅ',
'and'            => 'និង',

# Metadata in edit box
'metadata_help' => 'ទិន្នន័យមេតា៖',

'errorpagetitle'    => 'កំហុស',
'returnto'          => "ត្រលប់ទៅកាន់ '''$1''' វិញ។",
'tagline'           => 'ពី {{SITENAME}}',
'help'              => 'ជំនួយ',
'search'            => 'ស្វែងរក',
'searchbutton'      => 'ស្វែងរក',
'go'                => 'ទៅ',
'searcharticle'     => 'ទៅ',
'history'           => 'ប្រវត្តិទំព័រ',
'history_short'     => 'ប្រវត្តិ',
'updatedmarker'     => 'បានបន្ទាន់សម័យ តាំងពី ការចូលមើលចុងក្រោយ របស់ខ្ញុំ',
'info_short'        => 'ពត៌មាន',
'printableversion'  => 'ទំរង់ សំរាប់បោះពុម្ភ',
'permalink'         => 'តំណភ្ជាប់អចិន្ត្រៃ',
'print'             => 'បោះពុម្ភ',
'edit'              => 'កែប្រែ',
'editthispage'      => 'កែប្រែទំព័រនេះ',
'delete'            => 'លុបចេញ',
'deletethispage'    => 'លុបចេញ ទំព័រនេះ',
'undelete_short'    => 'លែង លុបចេញ {{PLURAL:$1|មួយកំណែប្រែ|$1 ច្រើនកំណែប្រែ}}',
'protect'           => 'ការពារ',
'protect_change'    => 'ផ្លាស់ប្តូរ ការការពារ',
'protectthispage'   => 'ការពារ ទំព័រនេះ',
'unprotect'         => 'លែងការពារ',
'unprotectthispage' => 'លែងការពារ ទំព័រនេះ',
'newpage'           => 'ទំព័រថ្មី',
'talkpage'          => 'ពិភាក្សាទំព័រនេះ',
'talkpagelinktext'  => 'ពិភាក្សា',
'specialpage'       => 'ទំព័រពិសេស',
'personaltools'     => 'ឧបករ ផ្ទាល់ខ្លួន',
'postcomment'       => 'ដាក់មួយវិចារ',
'articlepage'       => 'មើលអត្ថបទ',
'talk'              => 'ពិភាក្សា',
'views'             => 'ការមើលនានា',
'toolbox'           => 'ប្រអប់ ឧបករ',
'userpage'          => 'បង្ហាញទំព័រអ្នកប្រើប្រាស់',
'projectpage'       => 'មើល​ទំព័រគំរោង',
'imagepage'         => 'មើលទំព័រមីឌា',
'mediawikipage'     => 'មើល​ទំព័រសារ',
'templatepage'      => 'មើល ទំព័រគំរូខ្នាត',
'viewhelppage'      => 'មើលទំព័រជំនួយ',
'categorypage'      => 'មើល​ទំព័រចំណាត់ក្រុម',
'viewtalkpage'      => 'មើលការពិភាក្សា',
'otherlanguages'    => 'ជាភាសាដទៃទៀត',
'redirectedfrom'    => '(ត្រូវបានប្តូរទិស ពី $1)',
'redirectpagesub'   => 'ប្តូរទិស ទំព័រ',
'lastmodifiedat'    => 'ទំព័រនេះ ត្រូវបានផ្លាស់ប្តូរ ចុងក្រោយ $2, $1។', # $1 date, $2 time
'viewcount'         => "ទំព័រនេះត្រូវបានចូលមើលចំនួន'''$1'''លើក",
'protectedpage'     => 'ទំព័រ ត្រូវបានការពារ',
'jumpto'            => 'ទៅកាន់៖',
'jumptonavigation'  => 'ត្រាច់រក',
'jumptosearch'      => 'ស្វែងរក',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'អំពី {{SITENAME}}',
'aboutpage'         => 'Project:អំពី',
'bugreports'        => 'របាយការ កំហុស',
'bugreportspage'    => 'Project:របាយការ កំហុស',
'copyright'         => 'រក្សាសិទ្ធិគ្រប់យ៉ាងដោយ$1។',
'copyrightpagename' => '{{SITENAME}} រក្សាសិទ្ធិ ចំលង',
'copyrightpage'     => '{{ns:project}}:រក្សាសិទ្ធិ ចំលង',
'currentevents'     => 'ព្រឹត្តិការថ្មីៗ',
'currentevents-url' => 'Project:ព្រឹត្តិការថ្មីៗ',
'disclaimers'       => 'ការបដិសេធ',
'disclaimerpage'    => 'Project:ការបដិសេធទូទៅ',
'edithelp'          => 'ជំនួយក្នុងកំណែប្រែ',
'edithelppage'      => 'Help:កំណែប្រែ',
'faq'               => 'សំណួរ ដែលសួរញឹកញាប់',
'faqpage'           => 'Project:សំណួរ ដែលសួរញឹកញាប់',
'helppage'          => 'Help:មាតិកា',
'mainpage'          => 'ទំព័រដើម',
'policy-url'        => 'Project:គោលការ',
'portal'            => 'ក្លោងទ្វារសហគម',
'portal-url'        => 'Project:ក្លោងទ្វារសហគម',
'privacy'           => 'គោលការឯកជន',
'privacypage'       => 'Project:វិធាន លាក់ការ',
'sitesupport'       => 'វិភាគទាន',
'sitesupport-url'   => 'Project:ទំព័រគាំទ្រ',

'badaccess'        => 'កំហុសនៃការអនុញ្ញាតិ',
'badaccess-group0' => 'សកម្មភាព ដែលអ្នកបានស្នើ មិនត្រូវបានអនុញ្ញាតិទេ ។',
'badaccess-group1' => 'មានតែអ្នកប្រើប្រាស់ក្នុងក្រុម $1 ប៉ុណ្ណោះទើបអាចធ្វើសកម្មភាពដែលអ្នកបានស្នើ។',
'badaccess-group2' => 'មានតែ អ្នកប្រើប្រាស់ នៃក្រុម $1 នានា ទើបអាចធ្វើសកម្មភាព ដែលអ្នកបានស្នើ។',
'badaccess-groups' => 'មានតែ អ្នកប្រើប្រាស់ ក្នុងក្រុម $1 នានា ទើបអាចធ្វើសកម្មភាព ដែលអ្នកបានស្នើ។',

'versionrequired'     => 'តំរូវអោយមាន កំណែ $1 នៃ មីឌាវិគី',
'versionrequiredtext' => 'ត្រូវការកំណែ $1 នៃមីឌាវិគី (MediaWiki) ដើម្បីប្រើប្រាស់ទំព័រនេះ។ មើល  [[Special:Version|ទំព័រកំណែ]]។',

'ok'                      => 'យល់ព្រម',
'retrievedfrom'           => 'បានមកវិញពី "$1"',
'youhavenewmessages'      => 'អ្នកមាន $1 ($2).',
'newmessageslink'         => 'សារថ្មីៗ',
'newmessagesdifflink'     => 'បំលាស់ប្តូរចុងក្រោយ',
'youhavenewmessagesmulti' => 'អ្នកមានសារថ្មីៗ នៅ $1',
'editsection'             => 'កែប្រែ',
'editold'                 => 'កែប្រែ',
'editsectionhint'         => 'កែប្រែផ្នែក៖ $1',
'toc'                     => 'មាតិកា',
'showtoc'                 => 'បង្ហាញ',
'hidetoc'                 => 'បិទបាំង',
'thisisdeleted'           => 'ចង់បង្ហាញ ឬ​ ទុក $1 នៅដដែល?',
'viewdeleted'             => 'មើល $1?',
'restorelink'             => '{{PLURAL:$1|កំណែប្រែ ត្រូវបានលុបចេញ|$1 កំណែប្រែ ត្រូវបានលុបចេញ}}',
'feedlinks'               => 'បំរែបំរួល៖',
'feed-invalid'            => 'ប្រភេទ បំរែបំរួល ដែលគ្មាន សុពលភាព។',
'site-rss-feed'           => 'បំរែបំរួល RSS នៃ $1',
'site-atom-feed'          => 'បំរែបំរួល Atom នៃ $1',
'page-rss-feed'           => 'បំរែបំរួល RSS នៃ "$1"',
'page-atom-feed'          => 'បំរែបំរួល Atom Feed នៃ "$1"',
'red-link-title'          => '$1 (មិនទាន់ បានសរសេរ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ទំព័រ',
'nstab-user'      => 'ទំព័រអ្នកប្រើប្រាស់',
'nstab-media'     => 'ទំព័រ មីឌា',
'nstab-special'   => 'ពិសេស',
'nstab-project'   => 'ទំព័រគំរោង',
'nstab-image'     => 'ឯកសារ',
'nstab-mediawiki' => 'សារ',
'nstab-template'  => 'គំរូខ្នាត',
'nstab-help'      => 'ទំព័រជំនួយ',
'nstab-category'  => 'ចំណាត់ក្រុម',

# Main script and global functions
'nosuchaction'      => 'មិនមាន សកម្មភាព បែបនេះ',
'nosuchactiontext'  => 'សកម្មភាព បានបង្ហាញដោយ URL មិនត្រូវបាន ទទួលស្គាល់ដោយ វិគី',
'nosuchspecialpage' => 'គ្មានទំព័រពិសេស បែបនេះទេ',
'nospecialpagetext' => "<big>'''អ្នកបានស្នើរក មួយទំព័រពិសេស ដែលមិនត្រូវបាន ទទួលស្គាល់ ដោយ វិគី។'''</big>

បញ្ជី នៃ ទំព័រពិសេស អាចត្រូវបាន រកឃើញ នៅ [[Special:Specialpages]]។",

# General errors
'error'                => 'កំហុស',
'databaseerror'        => 'កំហុស មូលដ្ឋានទិន្នន័យ',
'noconnect'            => 'អភ័យទោស! បន្ទាប់ពី មានបញ្ហាបច្ចេកទេស, វាមិនអាច ទាក់ទងទៅ មូលដ្ឋានទិន្នន័យ នាពេលនេះទេ។ <br />
$1',
'nodb'                 => 'មិនអាចជ្រើសយក មូលដ្ឋានទិន្នន័យ $1',
'laggedslavemode'      => 'ប្រយ័ត្ន៖ ទំព័រនេះ អាចមិនផ្ទុក បំលាស់ប្តូរ នានា ចុងក្រោយ បំផុត។',
'readonly'             => 'មូលដ្ឋានទិន្នន័យ ជាប់សោ',
'enterlockreason'      => 'បង្ហាញ ហេតុផល ចំពោះ ការជាប់សោ រួមទាំង ការប្រមាណ ទេសកាល របស់វា',
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
'cannotdelete'         => 'មិនអាច លុបចេញ ទំព័រ ឬ ឯកសារ ដែលបានសំដៅ។ (វាអាច ត្រូវបានលុបចេញហើយ ដោយ នរណាម្នាក់ ។)',
'badtitle'             => 'ចំណងជើង មិនត្រឹមត្រូវ',
'viewsource'           => 'មើល អក្សរកូដ',
'viewsourcefor'        => 'សំរាប់ $1',
'actionthrottled'      => 'សកម្មភាព ត្រូវបានកំរិត',
'protectedpagetext'    => 'ទំព័រនេះ បានត្រូវចាក់សោ ដើម្បីការពារកំណែប្រែ។',
'viewsourcetext'       => 'អ្នកអាចមើល និង ចំលងអក្សរកូដ នៃទំព័រនេះ៖',
'protectedinterface'   => 'ទំព័រនេះ ផ្តល់នូវ អត្ថបទអន្តរមុខ សំរាប់ផ្នែកទន់, និង បានត្រូវចាក់សោ ដើម្បីចៀសវាង ការបំពាន ។',
'editinginterface'     => "'''ប្រយ័ត្ន ៖''' អ្នកកំពុង កែប្រែ មួយទំព័រ ដែលបានប្រើប្រាស់ ដើម្បីបង្កើត អត្ថបទ អន្តរមុខ សំរាប់ផ្នែកទន់​។ បំលាស់ប្តូរ ចំពោះទំព័រនេះ នឹងប៉ះពាល់ ដល់ទំព័រអន្តរមុខ នៃអ្នកប្រើប្រាស់ជាច្រើន ដែលប្រើប្រាស់ វ៉ែបសៃថ៍​ នេះ។ ដើម្បី កំណត់ជា គោលការ, សូមពិនិត្យមើល ការប្រើប្រាស់ [http://translatewiki.net/wiki/Main_Page?setlang=km Betawiki], គំរោង អន្តរជាតូបនីយកម្ម នៃ មីឌាវិគី ។",
'sqlhidden'            => '(ការអង្កេត SQL ត្រូវបិទបាំង)',
'namespaceprotected'   => "អ្នកមិនមានសិទ្ធិ កែប្រែទំព័រនានា ក្នុងវាលឈ្មោះ '''$1''' ។",
'customcssjsprotected' => 'អ្នកមិនមាន ការអនុញ្ញាតិ កែប្រែទំព័រនេះ, ព្រោះវាផ្ទុក ការកំណត់ផ្ទាល់ខ្លួន ផ្សេងទៀត នៃអ្នកប្រើប្រាស់។',
'ns-specialprotected'  => 'ទំព័រនានា ក្នុងវាលឈ្មោះ {{ns:special}} មិនអាចត្រូវបាន កែប្រែ។',
'titleprotected'       => 'ចំណងជើងនេះ ត្រូវបានការពារ ការបង្កើតថ្មី ដោយ [[User:$1|$1]]។ ហេតុផលលើកឡើង គឺ <i>$2</i>។',

# Login and logout pages
'logouttitle'                => 'ការពិនិត្យចេញ របស់អ្នកប្រើប្រាស់',
'logouttext'                 => '<strong>ឥឡូវនេះ អ្នកបានពិនិត្យចេញ ហើយ។</strong>

You can continue to use {{SITENAME}} anonymously, or you can log in again as the same or as a different user. Note that some pages may continue to be displayed as if you were still logged in, until you clear your browser cache.',
'welcomecreation'            => '== សូមស្វាគម $1! ==

គណនីរបស់អ្នក ត្រូវបានបង្កើតហើយ។ កុំភ្លេចផ្លាស់ប្តូរ ចំណូលចិត្ត{{SITENAME}}របស់អ្នក។',
'loginpagetitle'             => 'ពិនិត្យចូល របស់អ្នកប្រើប្រាស់',
'yourname'                   => 'ឈ្មោះអ្នកប្រើប្រាស់៖',
'yourpassword'               => 'ពាក្យសំងាត់៖',
'yourpasswordagain'          => 'វាយពាក្យសំងាត់ម្តងទៀត៖',
'remembermypassword'         => 'ចងចាំការពិនិត្យចូល របស់ខ្ញុំ ក្នុងខំព្យូរើនេះ',
'yourdomainname'             => 'កម្មសិទ្ធិរបស់អ្នក៖',
'loginproblem'               => '<b>មានបញ្ហា ចំពោះការពិនិត្យចូល របស់អ្នក។</b><br />ព្យាយាមឡើងវិញ!',
'login'                      => 'ពិនិត្យចូល',
'loginprompt'                => 'អ្នកត្រូវតែ សកម្ម cookies ដើម្បី ពិនិត្យចូល ទៅ {{SITENAME}}។',
'userlogin'                  => 'ពិនិត្យចូល / បង្កើតគណនី',
'logout'                     => 'ពិនិត្យចេញ',
'userlogout'                 => 'ពិនិត្យចេញ',
'notloggedin'                => 'មិនបានពិនិត្យចូល',
'nologin'                    => 'អ្នកមានគណនី ដើម្បីប្រើប្រាស់ ឬ នៅ? $1 ។',
'nologinlink'                => 'បង្កើត មួយគណនី',
'createaccount'              => 'បង្កើតគណនី',
'gotaccount'                 => 'មានគណនី រួចហើយ ឬ ? $1។',
'gotaccountlink'             => 'ពិនិត្យចូល',
'createaccountmail'          => 'តាម អ៊ីមែវល៍',
'badretype'                  => 'ពាក្យសំងាត់ ដែលអ្នក បានបញ្ចូលនោះ មិនស៊ីគ្នា។',
'userexists'                 => 'ឈ្មោះអ្នកប្រើប្រាស់ មាន គេ ប្រើប្រាស់ ហើយ។ សូម ជ្រើសរើស ឈ្មោះដទៃទៀត។',
'youremail'                  => 'អ៊ីមែវល៍៖',
'username'                   => 'ឈ្មោះ អ្នកប្រើប្រាស់៖',
'uid'                        => 'អត្តសញ្ញាណ អ្នកប្រើប្រាស់៖',
'yourrealname'               => 'ឈ្មោះពិត៖',
'yourlanguage'               => 'ភាសា៖',
'yournick'                   => 'ឈ្មោះ ហៅក្រៅ៖',
'badsig'                     => 'ហត្ថលេខាឆៅ មិនត្រឹមត្រូវ; ឆែកមើល ប្លាក HTML ។',
'badsiglength'               => 'ឈ្មោះហៅក្រៅ វែងជ្រុល; ត្រូវតែ តិចជាង $1 អក្សរ។',
'email'                      => 'អ៊ីមែវល៍',
'prefs-help-realname'        => '(ជំរើស) ៖ បើអ្នកផ្តល់អោយ, វានឹងត្រូវបាន ប្រើប្រាស់់ ដើម្បីបញ្ជាក់ ភាពជាម្ចាស់ លើការរួមចំណែក នានា របស់អ្នក។',
'loginerror'                 => 'កំហុស ពិនិត្យចូល',
'prefs-help-email'           => '(ជំរើស) ៖ វាព្រមអោយ អ្នកប្រើប្រាស់ដទៃ ទាក់ទងជាមួយអ្នក តាមអ៊ីមែវល៍ (ដែលតភ្ជាប់ ទំព័រអ្នកប្រើប្រាស់) ដោយមិនមើល ឃើញមែវល៍ របស់អ្នក និង ផ្ញើពាក្យសំងាត់ថ្មី អោយអ្នក បើអ្នកបានជា ភ្លេចវា។',
'prefs-help-email-required'  => 'តំរូវអោយមាន អាស័យដ្ឋានអ៊ីមែវល៍។',
'nocookiesnew'               => 'គណនីអ្នកប្រើប្រាស់ ត្រូវបានបង្កើត, ប៉ុន្តែ អ្នកមិនត្រូវបាន ពិនិត្យចូល ។ {{SITENAME}} ប្រើប្រាស់ cookies ដើម្បីពិនិត្យចូល ប៉ុន្តែ អ្នកបាន អសកម្ម ពួកវា។ ចូរ សកម្ម ពួកវា ឡើងវិញ, រួចពិនិត្យចូល ដោយ ឈ្មោះអ្នកប្រើប្រាស់ថ្មី  និង ពាក្យសំងាត់ថ្មី របស់អ្នក។',
'noname'                     => 'អ្នកមិនបាន កំណត់ត្រឹមត្រូវ ឈ្មោះអ្នកប្រើប្រាស់ ។',
'loginsuccesstitle'          => 'ពិនិត្យចូល ដោយជោគជ័យ',
'loginsuccess'               => "'''ពេលនេះ, អ្នកត្រូវបាន ពិនិត្យចូល ទៅ {{SITENAME}} ជា \"\$1\"។'''",
'nosuchuser'                 => 'គ្មានអ្នកប្រើប្រាស់ ឈ្មោះ "$1" ។ ឆែកមើល អក្ខរាវិរុទ្ធ ឬ បង្កើត គណនី ថ្មី ។',
'nosuchusershort'            => 'គ្មានអ្នកប្រើប្រាស់ ឈ្មោះ "$1" ។ ឆែកមើល អក្ខរាវិរុទ្ធ របស់អ្នក ។',
'nouserspecified'            => 'អ្នកត្រូវតែ កំណត់ ឈ្មោះ អ្នកប្រើប្រាស់ ។',
'wrongpassword'              => 'ពាក្យសំងាត់ បានបញ្ចូល មិនត្រឹមត្រូវ។ សូមព្យាយាម ឡើងវិញ។',
'wrongpasswordempty'         => 'ពាក្យសំងាត់ទទេ បានបញ្ចូល។ សូមព្យាយាម ឡើងវិញ។',
'passwordtooshort'           => 'ពាក្យសំងាត់ របស់អ្នក មិនមានសុពលភាព ឬ​ ខ្លីពេក។ វាត្រូវមាន យ៉ាងតិច $1 អក្សរ និង ត្រូវផ្សេងពី ឈ្មោះអ្នកប្រើប្រាស់ របស់អ្នក។',
'mailmypassword'             => 'អ៊ីមែវល៍មកខ្ញុំ ពាក្យសំងាត់ថ្មី',
'passwordremindertitle'      => 'ពាក្យសំងាត់ថ្មី បណ្តោះអាសន្ន សំរាប់ {{SITENAME}}',
'passwordremindertext'       => 'មានអ្នកណាម្នាក់ (ប្រហែលជា អ្នក, ពី អាស័យដ្ឋាន IP $1)
បានស្នើយើង អោយផ្ញើអ្នក មួយពាក្យសំងាត់ថ្មី សំរាប់ {{SITENAME}} ($4) ។
ពាក្យសំងាត់ ថ្មី សំរាប់ អ្នកប្រើប្រាស់ "$2" ឥឡូវ ជា "$3" ។
អ្នកគួរ ពិនិត្យចូល និង ផ្លាស់ប្តូរ ពាក្យសំងាត់ របស់អ្នក នៅពេលនេះ ។

បើមានអ្នកណាផ្សេង ស្នើករណីនេះ ឬ អ្នកបែរជា បាននឹកឃើញ ពាក្យសំងាត់ចាស់ របស់អ្នក ហើយ មិនចង់ ផ្លាស់ប្តូរ ទេ, អ្នកអាចបំភ្លេច សារនេះ ព្រមទាំង បន្តប្រើប្រាស់ ពាក្យសំងាត់ ចាស់ របស់អ្នក ។',
'noemail'                    => 'គ្មាន អាស័យដ្ឋានអ៊ីមែវល៍ ត្រូវបានថតទុក សំរាប់ អ្នកប្រើប្រាស់ "$1"។',
'passwordsent'               => 'មួយពាក្យសំងាត់ថ្មី ត្រូវបានផ្ញើ ទៅអាស័យដ្ឋានអ៊ីមែវល៍ ដែលបានចុះបញ្ជី អោយ "$1"។ សូម ពិនិត្យចូល ឡើងវិញ បន្ទាប់ពី បានទទួលវា។',
'blocked-mailpassword'       => 'អាស័យដ្ឋាន IP ត្រូវបានរាំងខ្ទប់ កំណែប្រែ និង មិនអនុញ្ញាតិ អោយប្រើប្រាស់ មុខងារសង្គ្រោះពាក្យសំងាត់ ដើម្បីបង្ការ ការបំពាន។',
'eauthentsent'               => 'អ៊ីមែវល៍ បញ្ជាក់ទទួលស្គាល់ ត្រូវបានផ្ញើទៅ អាស័យដ្ឋានអ៊ីមែវល៍ ដែលបានដាក់ឈ្មោះ។ មុននឹងមាន អ៊ីមែវល៍ ផ្សេងមួយទៀត ត្រូវផ្ញើទៅ គណនីនេះ, អ្នកត្រូវតែតាមមើល សេចក្តីណែនាំ ក្នុងអ៊ីមែវល៍, ដើម្បីបញ្ជាក់ទទួលស្គាល់ ថា គណនីបច្ចុប្បន្ន ជារបស់អ្នក។',
'throttled-mailpassword'     => 'ការរំលឹកពាក្យសំងាត់ ត្រូវបានផ្ញើទៅ អោយអ្នក ក្នុងអំឡុង $1 ម៉ោងចុងក្រោយ។ ដើម្បីបង្ការ អំពើបំពាន, អាចផ្ញើពាក្យសំងាត់ រាល់ $1 ម៉ោង ។',
'mailerror'                  => 'កំហុស ផ្ញើមែវល៍៖ $1',
'acct_creation_throttle_hit' => 'អភ័យទោស, អ្នកបានបង្កើត គណនី $1 រួចហើយ ។ អ្នកមិនអាច ធ្វើអ្វី បន្ថែម ទេ​ ។',
'emailauthenticated'         => 'អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក ត្រូវបានទទួល ស្របច្បាប់ ថ្ងៃ $1។',
'emailnotauthenticated'      => 'អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក មិនទាន់ ស្របច្បាប់ទេ។ គ្មានអ៊ីមែវល៍ ដែលនឹង ត្រូវបានផ្ញើទៅ សំរាប់មុខងារ ណាមួយ ខាងក្រោម។',
'noemailprefs'               => '<strong>គ្មាន អាស័យដ្ឋានអ៊ីមែវល៍ ណាមួយ ត្រូវបានបង្ហាញ,</strong> មុខងារបន្ទាប់ នឹងមិនធ្វើការ ។',
'emailconfirmlink'           => 'បញ្ជាក់ទទួលស្គាល់ អាស័យដ្ឋានអ៊ីមែវល៍',
'invalidemailaddress'        => 'ទំរង់ អាស័យដ្ឋានអ៊ីមែវល៍ មិនត្រឹមត្រូវ ។ ចូរ បញ្ចូល មួយអាស័យដ្ឋាន ដែលមាន ទំរង់ ត្រឹមត្រូវ ឬមួយក៏ ទុកទំនេរ វាលនោះ ។',
'accountcreated'             => 'គណនី ត្រូវបានបង្កើត',
'accountcreatedtext'         => 'គណនី អ្នកប្រើប្រាស់ ចំពោះ $1 ត្រូវបានបង្កើត ហើយ។',
'createaccount-title'        => 'ការបង្កើតគណនី សំរាប់ {{SITENAME}}',
'createaccount-text'         => 'មានអ្នកណាម្នាក់ បានបង្កើត មួយគណនី ជាឈ្មោះ "$2" លើ {{SITENAME}}($4), ព្រមទាំង ពាក្យសំងាត់ "$3" ។ អ្នកគួរតែ ពិនិត្យចូល ហើយ ផ្លាស់ប្តូរ ពាក្យសំងាត់ របស់អ្នក នៅពេលនេះ ។

អ្នកអាច រំលង សារ នេះ, ប្រសិនបើ​ មិនមែនអ្នក ជាអ្នកបង្កើត គណនី នេះ ។',
'loginlanguagelabel'         => 'ភាសា៖ $1',

# Password reset dialog
'resetpass'               => 'ធ្វើអោយ ពាក្យសំងាត់គណនី ដូចលើកទីសូន្យ',
'resetpass_announce'      => 'អ្នកបាន ពិនិត្យចូល ដោយ អក្សរកូដអ៊ីមែវល៍ បណ្តោះអាសន្ន មួយ ។ ដើម្បី​បញ្ចប់ ការពិនិត្យចូល, អ្នកត្រូវតែ កំណត់ មួយពាក្យសំងាត់ថ្មី នៅទីនេះ ៖',
'resetpass_header'        => 'ធ្វើអោយ ពាក្យសំងាត់ ដូចលើកទីសូន្យ',
'resetpass_submit'        => 'ផ្លាស់ប្តូរ ពាក្យសំងាត់ និង ពិនិត្យចូល',
'resetpass_success'       => 'ពាក្យសំងាត់ របស់អ្នក ត្រូវបានផ្លាស់ប្តូរ ដោយជោគជ័យ! កំពុងពិនិត្យចូល...',
'resetpass_bad_temporary' => 'ពាក្យសំងាត់ អសុពលភាព ជាបណ្តោះអាសន្ន។ ប្រហែលជា អ្នកបានផ្លាស់ប្តូរ ពាក្យសំងាត់ របស់អ្នក រួចហើយ ឬ បានស្នើ ពាក្យសំងាត់ បណ្តោះអាសន្ន ថ្មីមួយ។',
'resetpass_forbidden'     => 'ពាក្យសំងាត់ ទាំងឡាយ មិនអាចត្រូវបាន ផ្លាស់ប្តូរ លើ {{SITENAME}}',
'resetpass_missing'       => 'គ្មានទិន្នន័យ ណាមួយ ត្រូវបានបញ្ចូល។',

# Edit page toolbar
'bold_sample'     => 'អក្សរដិត',
'bold_tip'        => 'អក្សរដិត',
'italic_sample'   => 'អក្សរទ្រេត',
'italic_tip'      => 'អក្សរទ្រេត',
'link_sample'     => 'ចំណងជើង តំណភ្ជាប់',
'link_tip'        => 'តំណភ្ជាប់ ខាងក្នុង',
'extlink_sample'  => 'ចំណងជើង តំណភ្ជាប់ http://www.example.com',
'extlink_tip'     => 'តំណភ្ជាប់ ខាងក្រៅ (ត្រូវដាក់ http:// នៅពីមុខ)',
'headline_sample' => 'អត្ថបទ នៃ ចំណងជើងរង',
'headline_tip'    => 'ចំណងជើងរង កំរិត ២',
'math_sample'     => 'បញ្ចូលរូបមន្ត នៅទីនេះ',
'math_tip'        => 'រូបមន្ត គណិត (LaTeX)',
'nowiki_sample'   => 'បញ្ចូល អត្ថបទគ្មានទំរង់ នៅទីនេះ',
'nowiki_tip'      => 'មិនគិត ទំរង់ នៃ វិគី',
'image_sample'    => 'រូបភាព.jpg',
'image_tip'       => 'រូបភាពបង្កប់',
'media_tip'       => 'ឯកសារមីឌា',
'sig_tip'         => 'ហត្ថលេខា របស់អ្នក និង កាលបរិច្ឆេទ',
'hr_tip'          => 'បន្ទាត់ដេក (ប្រើប្រាស់ ម្តងម្កាល)',

# Edit pages
'summary'                   => 'សេចក្តីសង្ខេប',
'subject'                   => 'ប្រធានបទ/ចំណងជើង',
'minoredit'                 => 'នេះជា កំណែប្រែតិចតួចមួយ',
'watchthis'                 => 'តាមដាន ទំព័រនេះ',
'savearticle'               => 'រក្សាទុកទំព័រ',
'preview'                   => 'មើលមុន',
'showpreview'               => 'បង្ហាញ ការមើលមុន',
'showlivepreview'           => 'មើលមុន ទាន់ចិត្ត',
'showdiff'                  => 'បង្ហាញ បំលាស់ប្តូរ',
'anoneditwarning'           => "''ព្រមាន ៖''' អ្នកមិនទាន់បាន ពិនិត្យចូល ទេ។ អាស័យដ្ឋាន IP របស់អ្នក នឹងត្រូវបាន ថតទុក ក្នុងប្រវត្តិកែប្រែ នៃទំព័រ នេះ។",
'missingcommenttext'        => 'សូមបញ្ចូល មួយវិចារ នៅខាងក្រោម។',
'summary-preview'           => 'មើលមុន សេចក្តីសង្ខេប',
'subject-preview'           => 'មើលមុន ប្រធានបទ/ចំណងជើង',
'blockedtitle'              => 'អ្នកប្រើប្រាស់ បានត្រូវខ្ទប់',
'blockednoreason'           => 'គ្មានហេតុផល ត្រូវបានលើកឡើង',
'blockedoriginalsource'     => "អក្សរកូដ នៃ '''$1''' ត្រូវបានបង្ហាញ ខាងក្រោម៖",
'blockededitsource'         => "ខ្លឹមសារ នៃ '''កំណែប្រែ របស់អ្នក''' ចំពោះ '''$1''' បានត្រូវ បង្ហាញ ខាងក្រោម ៖",
'whitelistedittitle'        => 'តំរូវអោយ ពិនិត្យចូល ដើម្បីកែប្រែ',
'whitelistedittext'         => 'អ្នកត្រូវតែជា $1 ដើម្បី កែប្រែ ខ្លឹមសារទំព័រ។',
'whitelistreadtitle'        => 'តំរូវអោយ ពិនិត្យចូល ដើម្បីអាន',
'whitelistreadtext'         => 'អ្នកត្រូវតែ [[Special:Userlogin|ពិនិត្យចូល]] ដើម្បីអានទំព័រ។',
'whitelistacctitle'         => 'អ្នកមិនត្រូវបាន អនុញ្ញាតិអោយ បង្កើតគណនីទេ',
'whitelistacctext'          => 'ដើម្បីអាច បង្កើត មួយគណនី លើ {{SITENAME}}, អ្នកត្រូវតែ  [[Special:Userlogin|បានពិនិត្យចូល]] និង មាន ការអនុញ្ញាតិ សមស្រប ។',
'confirmedittitle'          => 'តំរូវអោយ បញ្ជាក់ទទួលស្គាល់ អ៊ីមែវល៍ ដើម្បីកែប្រែ',
'confirmedittext'           => 'អ្នកត្រូវតែ បញ្ជាក់ទទួលស្គាល់ អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក មុននឹង កែប្រែ ខ្លឹមសារអត្ថបទ ។ ចូរ កំណត់ និង ផ្តល់សុពលភាព អោយ អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក តាម [[Special:Preferences|ចំណូចិត្ត នានា របស់ អ្នកប្រើប្រាស់]] ។',
'nosuchsectiontitle'        => 'មិនមានផ្នែក បែបនេះ',
'nosuchsectiontext'         => 'អ្នកបាន ព្យាយាម កែប្រែ មួយផ្នែក ដែលមិនទាន់មាន នៅឡើយ ។  ដោយហេតុថា មិនមាន ផ្នែក $1, ម៉្លោះហើយ គ្មានកន្លែង សំរាប់ រក្សាទុក កំណែប្រែ របស់អ្នក ។',
'loginreqtitle'             => 'តំរូវអោយ ពិនិត្យចូល',
'loginreqlink'              => 'ពិនិត្យចូល',
'loginreqpagetext'          => 'អ្នកត្រូវតែ $1 ដើម្បី មើលទំព័រ ដទៃទៀត។',
'accmailtitle'              => 'ពាក្យសំងាត់ ត្រូវបានផ្ញើ ទៅហើយ។',
'accmailtext'               => 'ពាក្យសំងាត់ របស់ "$1" ត្រូវបានផ្ញើទៅ $2 ហើយ។',
'newarticle'                => '(ថ្មី)',
'noarticletext'             => 'ពេលនេះ មិនមានអត្ថបទ មួយណា ក្នុងទំព័រនេះទេ, អ្នកអាច [[Special:Search/{{PAGENAME}}|ស្វែងរក ចំណងជើង នៃទំព័រនេះ]] ក្នុងទំព័រ ផ្សេងទៀត ឬ [{{fullurl:{{FULLPAGENAME}}|action=edit}} កែប្រែ ទំព័រនេះ]។',
'userpage-userdoesnotexist' => 'គណនីអ្នកប្រើប្រាស់ "$1" មិនបានត្រូវ ចុះបញ្ជី ។ ចូរឆែកមើល តើ អ្នកចង់ បង្កើត / កែប្រែ ទំព័រ នេះ ។',
'clearyourcache'            => "'''ចំណាំ ៖''' បន្ទាប់ពីរក្សាទុក, អ្នកត្រូវតែសំអាត សតិភ្ជាប់ នៃ ឧបកររាវរក របស់អ្នក ដើម្បីមើលបំលាស់ប្តូរ។ វិធីសំអាត សតិភ្ជាប់ នៃ ឧបកររាវរក ចំពោះ កម្មវិធីរាវរក ៖ 
* '''Mozilla / Firefox / Safari '''៖ ចុច [Shift]-[Ctrl]-[R] (ចំពោះ PC), ចុច [Cmd]-[Shift]-[R] (ចំពោះ Apple Mac) ។
* '''IE(Internet Explorer) '''៖ ចុច ''[Ctrl-F5]''  
* '''Konqueror '''៖ ចុច ''[Ctrl-F5]'' 
* '''Opera '''៖ ចុច ''[Tools]→[Preferences]'' ។",
'usercssjsyoucanpreview'    => "<strong>គន្លឹះ ៖ </strong> ប្រើប្រាស់ ប្រអប់ 'បង្ហាញ មើលមុន' ដើម្បី សាកល្បង សន្លឹក CSS/JS ថ្មី របស់អ្នក មុននឹង រក្សាទុកវា ។",
'usercsspreview'            => "'''រំលឹក ថា អ្នកគ្រាន់តែ កំពុងមើលមុន សន្លឹក CSS របស់អ្នក, វាមិនទាន់ ត្រូវបានរក្សាទុក ទេ!'''",
'updated'                   => '(បានបន្ទាន់សម័យ)',
'note'                      => '<strong>ចំណាំ៖</strong>',
'previewnote'               => '<strong>នេះគ្រាន់តែជា ការបង្ហាញអោយ មើលជាមុន ប៉ុណ្ណោះ។ បំលាស់ប្តូរ មិនទាន់បាន រក្សាទុកទេ។</strong>',
'editing'                   => 'កំណែប្រែ នៃ $1',
'editinguser'               => "ផ្លាស់ប្តូរ សិទ្ធិអ្នកប្រើប្រាស់ នៃ អ្នកប្រើប្រាស់ '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'editingsection'            => 'កែប្រែ $1 (ផ្នែក)',
'editingcomment'            => 'កែប្រែ $1 (វិចារ)',
'editconflict'              => 'ភាពឆ្គង នៃកំណែប្រែ៖ $1',
'yourtext'                  => 'ឃ្លារបស់អ្នក',
'storedversion'             => 'កំណែ ដែលបានតំកល់ទុក',
'yourdiff'                  => 'ភាពខុសគ្នា នានា',
'semiprotectedpagewarning'  => "'''សំគាល់៖''' ទំព័រនេះ បានត្រូវ ចាក់សោ ដូច្នេះ មានតែអ្នកប្រើប្រាស់ ដែលបានចុះឈ្មោះ ទើបអាចកែប្រែ វា បាន។",
'templatesused'             => 'គំរូខ្នាតនានា បានប្រើប្រាស់ លើទំព័រនេះ៖',
'templatesusedpreview'      => 'គំរូខ្នាតនានា បានប្រើប្រាស់ ក្នុងការមើលមុននេះ៖',
'templatesusedsection'      => 'គំរូខ្នាតនានា បានប្រើប្រាស់ ក្នុងផ្នែកនេះ៖',
'template-protected'        => '(ត្រូវបានការពារ)',
'template-semiprotected'    => '(ត្រូវបានការពារ ពាក់កណ្តាល)',
'nocreatetitle'             => 'ការបង្កើតទំព័រ ត្រូវបានកំរិត',
'nocreate-loggedin'         => 'អ្នកគ្មានការអនុញ្ញាតិ បង្កើតទំព័រថ្មី លើ {{SITENAME}}។',
'permissionserrors'         => 'កំហុសអនុញ្ញាតិ នានា',

# Account creation failure
'cantcreateaccounttitle' => 'មិនអាចបង្កើត គណនី',
'cantcreateaccount-text' => "ការបង្កើតគណនី ពីអាស័យដ្ឋាន IP (<b>$1</b>) នេះ ត្រូវបានរាំងខ្ទប់ ដោយ [[User:$3|$3]]។

ហេតុផល លើកឡើង ដោយ $3 គឺ ''$2''",

# History pages
'viewpagelogs'        => 'មើលកំណត់ហេតុសំរាប់ទំព័រនេះ',
'nohistory'           => 'មិនមាន ប្រវត្តិកំណែប្រែ ចំពោះទំព័រនេះ។',
'revnotfound'         => 'រកមិនឃើញ កំណែ',
'loadhist'            => 'ផ្ទុក ប្រវត្តិ នៃ ទំព័រ',
'currentrev'          => 'កំណែបច្ចុប្បន្ន',
'revisionasof'        => 'កំណែ របស់ $1',
'revision-info'       => 'កំណែ របស់ $1 ដោយ $2',
'previousrevision'    => '← កំណែ មុន',
'nextrevision'        => 'កំណែ បន្ទាប់ →',
'currentrevisionlink' => 'កំណែ បច្ចុប្បន្ន',
'cur'                 => 'បច្ចុប្បន្ន',
'next'                => 'បន្ទាប់',
'last'                => 'ចុងក្រោយ',
'orig'                => 'ច្បាប់ដើម',
'page_first'          => 'ដំបូង',
'page_last'           => 'ចុងក្រោយ',
'deletedrev'          => '[ត្រូវបានលុបចេញ]',
'histfirst'           => 'ការរួមចំណែក ដំបូងៗ បំផុត',
'histlast'            => 'ការរួមចំណែក ចុងក្រោយ បំផុត',
'historysize'         => '({{PLURAL:$1|1 បៃ|$1 បៃ}})',
'historyempty'        => '(ទទេ)',

# Revision feed
'history-feed-title'          => 'ប្រវត្តិ នៃ កំណែ',
'history-feed-description'    => 'ប្រវត្តិ ទំព័រនេះ លើ វិគី',
'history-feed-item-nocomment' => '$1 នៅថ្ងៃ $2', # user at time

# Revision deletion
'rev-deleted-comment'       => '(វិចារ ត្រូវបានដកចេញ)',
'rev-deleted-user'          => '(ឈ្មោះអ្នកប្រើប្រាស់ ត្រូវបានដកចេញ)',
'rev-deleted-event'         => '(អត្ថបទ ត្រូវបានដកចេញ)',
'rev-delundel'              => 'បង្ហាញ/បិទបាំង',
'revisiondelete'            => 'លុបចេញ/លែងលុបចេញ កំណែ នានា',
'revdelete-nooldid-title'   => 'គ្មានគោលដៅ ចំពោះ កំណែ',
'revdelete-legend'          => 'ដាក់កំហិត នានា៖',
'revdelete-hide-text'       => 'បិទបាំង អត្ថបទ នៃ កំណែ',
'revdelete-hide-name'       => 'បិទបាំង សកម្មភាព និង គោលដៅ',
'revdelete-hide-comment'    => 'បិទបាំង កំណែប្រែ វិចារ',
'revdelete-hide-user'       => 'បិទបាំង ឈ្មោះអ្នកប្រើប្រាស់​ ឬ អាស័យដ្ឋាន IP នៃ អ្នករួមចំណែក',
'revdelete-hide-restricted' => 'អនុវត្ត ការដាក់កំហិត នានា ចំពោះ អ្នកថែទាំប្រព័ន្ធ(sysops) ក៏ដូចជា អ្នកប្រើប្រាស់ ដ៏ទៃទៀត',
'revdelete-hide-image'      => 'បិទបាំង ខ្លឹមសារ នៃឯកសារ',
'revdelete-unsuppress'      => 'ដកចេញ ការដាក់កំហិត លើកំណែ ដែលបានមកវិញ',
'revdelete-log'             => 'វិចារ នៃកំណត់ហេតុ ៖',
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
'mergelog'           => 'កំណត់ហេតុ នៃការបញ្ចូលរួមគ្នា',
'pagemerge-logentry' => 'បានបញ្ចូលរួមគ្នា [[$1]] ជាមួយ [[$2]] (កំណែ នានា រហូតដល់ $3)',
'revertmerge'        => 'បំបែកចេញ',

# Diffs
'history-title'           => 'ប្រវត្តិ កំណែប្រែ នានា នៃ "$1"',
'difference'              => '(ភាពខុសគ្នា នៃ កំណែ នានា)',
'lineno'                  => 'បន្ទាប់ទី $1:',
'compareselectedversions' => 'ប្រៀបធៀប កំណែប្រែ ដែលបាន ជ្រើសយក',
'editundo'                => 'ធ្វើអោយ ដូចដើមវិញ',

# Search results
'searchresults'         => 'លទ្ធផល ស្វែងរក',
'searchresulttext'      => 'ចំពោះ ពត៌មានបន្ថែម អំពី ការស្វែងរក {{SITENAME}}, មើល [[ជំនួយ:មាតិកា|ជំនួយ]] ។',
'searchsubtitle'        => "អ្នក បាន ស្វែងរក '''[[:$1]]'''",
'searchsubtitleinvalid' => "អ្នក បាន ស្វែងរក '''$1'''",
'noexactmatch'          => "'''គ្មានទំព័រ ដែលមានចំណងជើង \"\$1\"ទេ។''' អ្នកអាច [[:\$1|បង្កើតទំព័រនេះ]]។",
'noexactmatch-nocreate' => "'''គ្មានទំព័រ ដែលមានចំណងជើង \"\$1\"ទេ។'''",
'notitlematches'        => 'គ្មាន ឈ្មោះទំព័រ ណា ដែលមាន ខ្លឹមសារ ប្រហាក់ប្រហែល',
'notextmatches'         => 'គ្មាន ឃ្លាអក្សរ ណា ក្នុងទំព័រ នានា ដែលមាន ខ្លឹមសារ ប្រហាក់ប្រហែល',
'prevn'                 => 'មុន $1',
'nextn'                 => 'បន្ទាប់ $1',
'viewprevnext'          => 'មើល ($1) ($2) ($3)',
'powersearch'           => 'ស្វែងរក',

# Preferences page
'preferences'              => 'ចំណូលចិត្ត នានា',
'mypreferences'            => 'ចំណូលចិត្ត របស់ខ្ញុំ',
'prefs-edits'              => 'ចំនួន នៃ កំណែប្រែ៖',
'prefsnologin'             => 'មិនបាន ពិនិត្យចូល',
'prefsnologintext'         => 'អ្នកត្រូវតែ [[Special:Userlogin|បានពិនិត្យចូល]] ដើម្បី កំណត់ ចំណូលចិត្ត របស់ អ្នកប្រើប្រាស់។',
'qbsettings'               => 'របារទាន់ចិត្ត',
'qbsettings-none'          => 'ទទេ',
'qbsettings-fixedleft'     => 'តំរឹម ឆ្វេង',
'qbsettings-fixedright'    => 'តំរឹម ស្តាំ',
'qbsettings-floatingleft'  => 'អណ្តែតឆ្វេង',
'qbsettings-floatingright' => 'អណ្តែតស្តាំ',
'changepassword'           => 'ផ្លាស់ប្តូរ ពាក្យសំងាត់',
'skin'                     => 'សំបក',
'math'                     => 'គណិត',
'dateformat'               => 'ទំរង់ ថ្ងៃខែឆ្នាំ',
'datedefault'              => 'គ្មានចំណូលចិត្ត',
'datetime'                 => 'ថ្ងៃខែឆ្នាំ និង ពេលម៉ោង',
'math_failure'             => 'កំហុស គណិត',
'math_unknown_error'       => 'កំហុស មិនបានស្គាល់',
'math_unknown_function'    => 'អនុគម មិនត្រូវបាន ស្គាល់',
'math_syntax_error'        => 'កំហុស ពាក្យសម្ព័ន្ធ',
'math_bad_tmpdir'          => 'មិនអាច សរសេរទៅ ឬ បង្កើត ថតឯកសារ គណិត បណ្តោះអាសន្ន',
'math_bad_output'          => 'មិនអាច សរសេរទៅ ឬ បង្កើត ថតឯកសារ គណិត ទិន្នផល',
'prefs-personal'           => 'ពត៌មានផ្ទាល់ខ្លួន នៃ អ្នកប្រើប្រាស់',
'prefs-rc'                 => 'បំលាស់ប្តូរ ថ្មីៗ',
'prefs-watchlist'          => 'បញ្ជីតាមដាន',
'prefs-watchlist-days'     => 'ថ្ងៃ ត្រូវបង្ហាញ ក្នុង បញ្ជីតាមដាន៖',
'prefs-watchlist-edits'    => 'ចំនួន បំលាស់ប្តូរ ត្រូវបង្ហាញ ក្នុង បញ្ជីតាមដាន ដែលបានពង្រីក ៖',
'prefs-misc'               => 'ផ្សេងៗ',
'saveprefs'                => 'រក្សាទុក',
'resetprefs'               => 'ធ្វើអោយ ដូចដើមវិញ',
'oldpassword'              => 'ពាក្យសំងាត់ចាស់៖',
'newpassword'              => 'ពាក្យសំងាត់ថ្មី៖',
'retypenew'                => 'វាយពាក្យសំងាត់ថ្មី ឡើងវិញ៖',
'textboxsize'              => 'កំណែប្រែ',
'rows'                     => 'ជួរដេក៖',
'columns'                  => 'ជួរឈរ៖',
'searchresultshead'        => 'ស្វែងរក',
'resultsperpage'           => 'ចំនួន ចំលើយ ក្នុង មួយទំព័រ៖',
'contextlines'             => 'ចំនួន បន្ទាត់ ក្នុង មួយចំលើយ ៖',
'contextchars'             => 'ចំនួនអក្សរ ក្នុងមួយបន្ទាត់៖',
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
'defaultns'                => 'ស្វែងរក តាមលំនាំដើម ក្នុង វាលឈ្មោះទាំងនេះ ៖',
'default'                  => 'លំនាំដើម',
'files'                    => 'ឯកសារ',

# User rights
'userrights-lookup-user'           => 'គ្រប់គ្រង ក្រុមអ្នកប្រើប្រាស់',
'userrights-user-editname'         => 'បញ្ចូលឈ្មោះ អ្នកប្រើប្រាស់៖',
'editusergroup'                    => 'កែប្រែ ក្រុមអ្នកប្រើប្រាស់',
'userrights-editusergroup'         => 'កែប្រែ ក្រុមអ្នកប្រើប្រាស់',
'saveusergroups'                   => 'រក្សាទុក ក្រុមអ្នកប្រើប្រាស់',
'userrights-groupsmember'          => 'សមាជិកនៃ៖',
'userrights-groupsremovable'       => 'ក្រុម ដែលអាចដកចេញ ៖',
'userrights-groupsavailable'       => 'ក្រុម ទំនេរ ៖',
'userrights-reason'                => 'ហេតុផល ចំពោះបំលាស់ប្តូរ៖',
'userrights-available-add'         => 'អ្នកអាចបន្ថែមអ្នកប្រើប្រាស់ ចូលទៅ {{PLURAL:$2|ក្រុមនេះ}}៖ $1។',
'userrights-available-remove'      => 'អ្នកអាចដកចេញ អ្នកប្រើប្រាស់ ពី {{PLURAL:$2|ក្រុមនេះ|ក្រុមទាំងនេះ}}៖ $1។',
'userrights-available-add-self'    => 'អ្នកអាចបន្ថែម អ្នកផ្ទាល់ ទៅ {{PLURAL:$2|ក្រុមនេះ|ក្រុមទាំងនេះ}} ៖ $1។',
'userrights-available-remove-self' => 'អ្នកអាចដកចេញ អ្នកផ្ទាល់ ពី {{PLURAL:$2|ក្រុមនេះ|ក្រុមទាំងនេះ}} ៖ $1។',
'userrights-no-interwiki'          => 'អ្នកមិនមានការអនុញ្ញាតិ កែប្រែសិទ្ធិ នៃអ្នកប្រើប្រាស់ លើ វិគី ផ្សេង ទេ។',
'userrights-nodatabase'            => 'មូលដ្ឋានទិន្នន័យ $1 មិនមាន ឬ ថិតនៅខាងក្រៅ។',
'userrights-nologin'               => 'អ្នកត្រូវតែ [[Special:Userlogin|ពិនិត្យចូល]] ជាគណនី អ្នកអភិបាល ដើម្បីផ្តល់សិទ្ធិ អោយអ្នកប្រើប្រាស់ ។',

# Groups
'group'               => 'ក្រុម៖',
'group-autoconfirmed' => 'ពួកអ្នកប្រើប្រាស់ ត្រូវបានបញ្ជាក់ទទួលស្គាល់ ដោយស្វ័យប្រវត្តិ',
'group-bot'           => 'រូបយន្ត',
'group-sysop'         => 'ក្រុមអ្នកថែទាំប្រព័ន្ធ',
'group-bureaucrat'    => 'ក្រុមអ្នកការិយាល័យ',
'group-all'           => '(ទាំងអស់)',

'group-autoconfirmed-member' => 'អ្នកប្រើប្រាស់ ត្រូវបានបញ្ជាក់ទទួលស្គាល់ ដោយស្វ័យប្រវត្តិ',
'group-bot-member'           => 'រូបយន្ត',
'group-sysop-member'         => 'អ្នកថែទាំប្រព័ន្ធ',
'group-bureaucrat-member'    => 'អ្នកការិយាល័យ',

'grouppage-autoconfirmed' => '{{ns:project}}:ពួកអ្នកប្រើប្រាស់ ត្រូវបានបញ្ជាក់ទទួលស្គាល់ ដោយស្វ័យប្រវត្តិ',
'grouppage-bot'           => '{{ns:project}}:រូបយន្ត',
'grouppage-sysop'         => '{{ns:project}}:ក្រុមអ្នកអភិបាល',
'grouppage-bureaucrat'    => '{{ns:project}}:ក្រុមអ្នកការិយាល័យ',

# User rights log
'rightslog'     => 'កំណត់ហេតុ សិទ្ធិអ្នកប្រើប្រាស់',
'rightslogtext' => 'នេះជា កំណត់ហេតុ នៃបំលាស់ប្តូរ ចំពោះសិទ្ធិនានា របស់ អ្នកប្រើប្រាស់ ។',
'rightsnone'    => '(ទទេ)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|បំលាស់ប្តូរ|បំលាស់ប្តូរ នានា}}',
'recentchanges'                     => 'បំលាស់ប្តូរ ថ្មីៗ',
'rcnote'                            => "ខាងក្រោម នេះ ជា '''$1''' បំលាស់ប្តូរ ចុងក្រោយ ក្នុងរយះពេល '''$2''' ថ្ងៃ ចុងក្រោយ, គិតត្រឹម $3 ។",
'rcnotefrom'                        => 'ខាងក្រោម ជាបំលាស់ប្តូរ តាំងពីថ្ងៃ <b>$2</b> (បង្ហាញជាអតិបរិមា <b>$1</b> បំលាស់ប្តូរ)។',
'rclistfrom'                        => 'បង្ហាញ បំលាស់ប្តូរថ្មី ដែល ចាប់ផ្តើម ពី $1',
'rcshowhideminor'                   => '$1 កំណែប្រែ តិចតួច',
'rcshowhidebots'                    => '$1 រូបយន្ត',
'rcshowhideliu'                     => '$1 អ្នកប្រើប្រាស់ ដែលបាន ពិនិត្យចូល',
'rcshowhideanons'                   => '$1 អ្នកប្រើប្រាស់ អនាមិក',
'rcshowhidemine'                    => '$1 កំណែប្រែ របស់ខ្ញុំ',
'rclinks'                           => 'បង្ហាញ $1 បំលាស់ប្តូរ ចុងក្រោយ ក្នុង $2 ថ្ងៃ ចុងក្រោយ<br />$3',
'diff'                              => 'ខុសគ្នា',
'hist'                              => 'ប្រវត្តិ',
'hide'                              => 'បិទបាំង',
'show'                              => 'បង្ហាញ',
'newpageletter'                     => 'ថ្មី',
'boteditletter'                     => 'រូបយន្ត',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|អ្នកប្រើប្រាស់|អ្នកប្រើប្រាស់}} កំពុងមើល]',
'rc_categories'                     => 'កំរិតទីតាំង ចំណាត់ក្រុម (ខណ្ឌ ដោយ សញ្ញា "|")',
'rc_categories_any'                 => 'មួយណាក៏បាន',
'newsectionsummary'                 => '/* $1 */ ផ្នែកថ្មី',

# Recent changes linked
'recentchangeslinked'       => 'បំលាស់ប្តូរពាក់ព័ន្ធ',
'recentchangeslinked-title' => 'បំលាស់ប្តូរ ទាក់ទិននឹង $1',

# Upload
'upload'            => 'ផ្ទុកឯកសារឡើង',
'uploadbtn'         => 'ផ្ទុកឯកសារឡើង',
'reupload'          => 'ផ្ទុកឡើងម្តងទៀត',
'reuploaddesc'      => 'ត្រឡប់ទៅ បែបបទផ្ទុកឡើង។',
'uploadnologin'     => 'មិនបាន ពិនិត្យចូល',
'uploadnologintext' => 'អ្នកត្រូវតែ [[Special:Userlogin|បានពិនិត្យចូល]] ដើម្បីផ្ទុកឡើង ឯកសារ ទាំងឡាយ។',
'uploaderror'       => 'កំហុសផ្ទុកឡើង',
'upload-permitted'  => 'ទំរង់ឯកសារ ដែលត្រូវបានអនុញ្ញាតិ ៖ $1 ។',
'upload-preferred'  => 'ទំរង់ឯកសារ ដែលគួរប្រើប្រាស់ ៖ $1 ។',
'upload-prohibited' => 'ទំរង់ឯកសារ ដែលត្រូវបានហាម ៖ $1 ។',
'uploadlog'         => 'កំណត់ហេតុ នៃការផ្ទុកឡើង',
'uploadlogpage'     => 'កំណត់ហេតុ នៃការផ្ទុកឡើង',
'uploadlogpagetext' => 'ខាងក្រោមនេះ ជាបញ្ជីនៃការផ្ទុកឡើង ថ្មីបំផុត។',
'filename'          => 'ឈ្មោះឯកសារ',
'filedesc'          => 'សេចក្តីសង្ខេប',
'fileuploadsummary' => 'សេចក្តីសង្ខេប៖',
'filestatus'        => 'ស្ថានភាព រក្សាសិទ្ធិ',
'filesource'        => 'អក្សរកូដ',
'uploadedfiles'     => 'ឯកសារនានា ត្រូវបានផ្ទុកឡើង',
'ignorewarnings'    => 'មិនខ្វល់ ការព្រមាន ណាមួយ',
'minlength1'        => 'ឈ្មោះឯកសារ ត្រូវមាន យ៉ាងតិច ១ អក្សរ។',
'badfilename'       => 'ឈ្មោះឯកសារ បានត្រូវប្តូរ ជា "$1" ។',
'filetype-missing'  => 'ឯកសារ មិនមានកន្ទុយ (ដូចជា ".jpg")។',
'fileexists-thumb'  => "<center>'''រូបភាពមានស្រេច'''</center>",
'successfulupload'  => 'ផ្ទុកឡើង ដោយជោគជ័យ',
'uploadwarning'     => 'ប្រយ័ត្ន !',
'savefile'          => 'រក្សាទុក ឯកសារ',
'uploadedimage'     => 'បានផ្ទុកឡើង "[[$1]]"',
'uploaddisabled'    => 'ការផ្ទុកឡើង ឯកសារនានា ត្រូវអសកម្ម',
'sourcefilename'    => 'ឈ្មោះឯកសារ ប្រភព',
'destfilename'      => 'ឈ្មោះឯកសារ គោលដៅ',
'watchthisupload'   => 'តាមដានទំព័រនេះ',

'upload-file-error' => 'កំហុស ខាងក្នុង',
'upload-misc-error' => 'កំហុសចំលែក ពេលផ្ទុកឡើង',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'មិនអាច ចូលទៅដល់ URL',
'upload-curl-error28' => 'ផ្ទុកឡើង បានផុតកំណត់អនុញ្ញាតិ',

'license'            => 'អាជ្ញាបណ្ណ',
'nolicense'          => 'គ្មានអ្វី ត្រូវបានជ្រើសយក',
'license-nopreview'  => '(មើលមុន មិនបាន)',
'upload_source_file' => ' (ឯកសារ លើខំព្យូរើ របស់អ្នក)',

# Image list
'imagelist'                 => 'បញ្ជីរូបភាព',
'imagelisttext'             => "នេះជា បញ្ជី '''$1''' {{PLURAL:$1|ឯកសារ|ឯកសារ}} បានរៀបតាមលំដាប់ $2 ។",
'ilsubmit'                  => 'ស្វែងរក',
'showlast'                  => 'បង្ហាញ $1 រូបភាពថ្មី បំផុត រៀបតាមលំដាប់ $2 ។',
'byname'                    => 'តាម ឈ្មោះ',
'bydate'                    => 'តាម ថ្ងៃខែឆ្នាំ',
'bysize'                    => 'តាម ទំហំ',
'imgdelete'                 => 'លុបចេញ',
'imgdesc'                   => 'លំដាប់ ចុះ',
'imgfile'                   => 'ឯកសារ',
'filehist'                  => 'ប្រវត្តិ ឯកសារ',
'filehist-help'             => 'ចុចលើ ថ្ងៃខែឆ្នាំ / ពេលម៉ោង ដើម្បីមើល ឯកសារ ដូចដែល វាបាន បង្ហាញចេញ នៅពេលនោះ ។',
'filehist-deleteall'        => 'លុបចេញ ទាំងអស់',
'filehist-deleteone'        => 'លុបនេះចេញ',
'filehist-revert'           => 'ត្រឡប់',
'filehist-current'          => 'បច្ចុប្បន្ន',
'filehist-datetime'         => 'ថ្ងៃខែឆ្នាំ/ម៉ោងពេល',
'filehist-user'             => 'អ្នកប្រើប្រាស់',
'filehist-dimensions'       => 'វិមាត្រ',
'filehist-filesize'         => 'ទំហំឯកសារ',
'filehist-comment'          => 'វិចារ',
'imagelinks'                => 'តំណភ្ជាប់ នានា',
'linkstoimage'              => 'ខាងក្រោមនេះគឺជាទំព័រដែលភ្ជាប់ទៅនឹងឯកសារ(ហ្វាល់)នេះ៖',
'nolinkstoimage'            => 'គ្មានទំព័រណាមួយ ដែលតភ្ជាប់ទៅ ឯកសារនេះ។',
'shareduploadwiki-linktext' => 'ទំព័រពិពណ៌នា ឯកសារ',
'noimage'                   => 'គ្មានឈ្មោះ រូបភាព នេះទេ, អ្នកអាច $1 ។',
'noimage-linktext'          => 'ផ្ទុកវាឡើង',
'uploadnewversion-linktext' => 'ផ្ទុកឡើង មួយកំណែថ្មី នៃ ឯកសារនេះ',
'imagelist_date'            => 'កាលបរិច្ឆេទ',
'imagelist_name'            => 'ឈ្មោះ',
'imagelist_user'            => 'អ្នកប្រើប្រាស់',
'imagelist_size'            => 'ទំហំ',
'imagelist_description'     => 'ការពិពណ៌នា',
'imagelist_search_for'      => 'ស្វែងរក ឈ្មោះមីឌា៖',

# File reversion
'filerevert'         => 'ត្រឡប់ $1',
'filerevert-legend'  => 'ត្រឡប់ ឯកសារ',
'filerevert-comment' => 'វិចារ៖',
'filerevert-submit'  => 'ត្រឡប់',

# File deletion
'filedelete'                  => 'លុបចេញ $1',
'filedelete-legend'           => 'លុបចេញ ឯកសារ',
'filedelete-intro'            => "អ្នកកំពុងលុបចេញ '''[[Media:$1|$1]]'''។",
'filedelete-comment'          => 'ហេតុផល ចំពោះ ការលុបចេញ ៖',
'filedelete-submit'           => 'លុបចេញ',
'filedelete-success'          => "'''$1''' ត្រូវបានលុបចោលហើយ",
'filedelete-otherreason'      => 'ហេតុផល ផ្សេង/បន្ថែម៖',
'filedelete-reason-otherlist' => 'ហេតុផល ផ្សេង',

# MIME search
'mimesearch' => 'ស្វែងរក MIME',
'mimetype'   => 'ប្រភេទ MIME ៖',
'download'   => 'ទាញយក',

# Unwatched pages
'unwatchedpages' => 'ទំព័រ មិនត្រូវបាន តាមដាន',

# Unused templates
'unusedtemplates'    => 'គំរូខ្នាត លែងត្រូវបាន ប្រើប្រាស់',
'unusedtemplateswlh' => 'តំណភ្ជាប់ ដទៃទៀត',

# Random page
'randompage'         => 'ទំព័រ ឥតព្រាងទុក',
'randompage-nopages' => 'គ្មានទំព័រណាមួយ ក្នុងវាលឈ្មោះនេះ។',

# Random redirect
'randomredirect'         => 'ទំព័រប្តូរទិស​ ឥតព្រាងទុក',
'randomredirect-nopages' => 'គ្មានទំព័រ ប្តូរទិសមួយណា នៅក្នុង វាលឈ្មោះនេះ ។',

# Statistics
'statistics'             => 'ស្ថិតិ',
'sitestats'              => 'ស្ថិតិ {{SITENAME}}',
'userstats'              => 'ស្ថិតិ អ្នកប្រើប្រាស់',
'userstatstext'          => "មាន[[Special:Listusers|អ្នកប្រើប្រាស់]] ​ដែលបានចុះឈ្មោះចំនួន '''$1'''នាក់
ដែលក្នុងនោះមាន '''$2''' នាក់(ស្មើនឹង '''$4%''') មានសិទ្ធិជា $5 (អ្នកគ្រប់គ្រងថែរក្សា)។",
'statistics-mostpopular' => 'ទំព័រ ត្រូវបានមើល ច្រើនបំផុត',

'doubleredirects' => 'ប្តូរទិស ២ ដង',

'brokenredirects-edit'   => '(កែប្រែ)',
'brokenredirects-delete' => '(លុបចេញ)',

'withoutinterwiki'        => 'ទំព័រ គ្មានតំណភ្ជាប់ភាសា',
'withoutinterwiki-header' => 'ទំព័រទាំងនេះ មិនតភ្ជាប់ ទៅកំណែភាសាដទៃ៖',
'withoutinterwiki-submit' => 'បង្ហាញ',

# Miscellaneous special pages
'nbytes'                  => '$1 បៃ',
'ncategories'             => '$1 {{PLURAL:$1|ចំណាត់ក្រុម|ចំណាត់ក្រុមនានា}}',
'nlinks'                  => '$1 {{PLURAL:$1|តំណភ្ជាប់}}',
'nmembers'                => '$1 សមាជិក',
'nviews'                  => '$1 {{PLURAL:$1|ការចូលមើល}}',
'specialpage-empty'       => 'របាយការនេះ​ គ្មានលទ្ធផល។',
'uncategorizedpages'      => 'ទំព័រ មិនត្រូវបានដាក់ ចំណាត់ក្រុម',
'uncategorizedcategories' => 'ចំណាត់ក្រុមនានា ដែលមិនបានដាក់ជា ចំណាត់ក្រុម',
'uncategorizedimages'     => 'រូបភាព មិនបានដាក់ ចំណាត់ក្រុម',
'uncategorizedtemplates'  => 'គំរូខ្នាត មិនត្រូវបានដាក់ ចំណាត់ក្រុម',
'unusedcategories'        => 'ចំណាត់ក្រុម លែងត្រូវបាន ប្រើប្រាស់',
'unusedimages'            => 'ឯកសារ លែងត្រូវបាន ប្រើប្រាស់',
'popularpages'            => 'ទំព័រ ដែលមាន ប្រជាប្រិយ',
'wantedcategories'        => 'ចំណាត់ក្រុម ដែលគេចូលមើល ច្រើន',
'wantedpages'             => 'ទំព័រ ដែលគេចង់បានច្រើន',
'mostlinked'              => 'ភ្ជាប់ទៅទំព័រច្រើនជាងគេ',
'mostlinkedcategories'    => 'បានតភ្ជាប់ ច្រើនបំផុត ទៅ ចំណាត់ក្រុម​ នានា',
'mostcategories'          => 'អត្ថបទមានចំនាត់ក្រុមច្រើនជាងគេ',
'mostimages'              => 'រូបភាពដែលភ្ជាប់ច្រើនជាងគេ',
'mostrevisions'           => 'អត្ថបទ មានកំណែប្រែ ច្រើនជាងគេ',
'allpages'                => 'គ្រប់ទំព័រ',
'shortpages'              => 'ទំព័រខ្លីៗ',
'longpages'               => 'ទំព័រវែងៗ',
'protectedpages'          => 'ទំព័រនានា ដែលត្រូវបានការពារ',
'protectedtitles'         => 'ចំណងជើងនានា ដែលត្រូវបានការពារ',
'listusers'               => 'បញ្ជី អ្នកប្រើប្រាស់',
'specialpages'            => 'ទំព័រពិសេសៗ',
'spheading'               => 'ទំព័រ ពិសេសៗ សំរាប់ គ្រប់អ្នកប្រើប្រាស់',
'restrictedpheading'      => 'ទំព័រពិសេសនានា ត្រូវបាន កំរិត',
'newpages'                => 'ទំព័រថ្មីៗ',
'newpages-username'       => 'ឈ្មោះអ្នកប្រើប្រាស់៖',
'ancientpages'            => 'ទំព័រ ចាស់បំផុត',
'intl'                    => 'តំណភ្ជាប់ អន្តរភាសា នានា',
'move'                    => 'ប្តូរទីតាំង',
'movethispage'            => 'ប្តូរទីតាំង ទំព័រនេះ',
'notargettitle'           => 'គ្មានគោលដៅ',
'pager-newer-n'           => '{{PLURAL:$1|$1 ថ្មីជាង}}',
'pager-older-n'           => '{{PLURAL:$1|$1 ចាស់ជាង}}',

# Book sources
'booksources-go' => 'ទៅ',

'categoriespagetext' => 'ចំណាត់ក្រុម ទាំងនេះ មាននៅក្នុង វិគី។',
'data'               => 'ទិន្នន័យ',
'userrights'         => 'ការគ្រប់គ្រង សិទ្ធិអ្នកប្រើប្រាស់',
'groups'             => 'ក្រុមអ្នកប្រើប្រាស់ នានា',
'alphaindexline'     => '$1 ទៅ $2',
'version'            => 'កំណែ',

# Special:Log
'specialloguserlabel'  => 'អ្នកប្រើប្រាស់៖',
'speciallogtitlelabel' => 'ចំណងជើង៖',
'log'                  => 'កំណត់ហេតុ',
'all-logs-page'        => 'គ្រប់ កំណត់ហេតុ',
'log-search-legend'    => 'ស្វែងរក កំណត់ហេតុ',
'log-search-submit'    => 'ទៅ',
'log-title-wildcard'   => 'ស្វែងរក ចំណងជើងនានា ដែលចាប់ផ្តើម ដោយអត្ថបទនេះ',

# Special:Allpages
'nextpage'          => 'ទំព័របន្ទាប់ ($1)',
'prevpage'          => 'ទំព័រមុន ($1)',
'allpagesfrom'      => 'បង្ហាញទំព័រផ្តើមដោយ:',
'allarticles'       => 'គ្រប់ទំព័រ',
'allinnamespace'    => 'គ្រប់ទំព័រ ($1 វាលឈ្មោះ)',
'allnotinnamespace' => 'គ្រប់ទំព័រ (មិននៅក្នុង $1 វាលឈ្មោះ)',
'allpagesprev'      => 'មុន',
'allpagesnext'      => 'បន្ទាប់',
'allpagessubmit'    => 'ទៅ',
'allpages-bad-ns'   => '{{SITENAME}} មិនមានវាលឈ្មោះ "$1"។',

# Special:Listusers
'listusersfrom'      => 'បង្ហាញ អ្នកប្រើប្រាស់ ចាប់ផ្តើម នៅ ៖',
'listusers-submit'   => 'បង្ហាញ',
'listusers-noresult' => 'រកមិនឃើញ អ្នកប្រើប្រាស់។',

# E-mail user
'mailnologin'     => 'គ្មានអាស័យដ្ឋានផ្ញើ',
'mailnologintext' => 'អ្នកត្រូវតែ [[Special:Userlogin|បានពិនិត្យចូល]] និងមាន អាស័យដ្ឋានអ៊ីមែវល៍មួយ ត្រឹមត្រូវ ក្នុង[[Special:Preferences|ចំណូលចិត្តនានា របស់អ្នក]] ដើម្បីផ្ញើអ៊ីមែវល៍ ទៅ អ្នកប្រើប្រាស់ ផ្សេងទៀត។',
'emailuser'       => 'អ៊ីមែវល៍ ទៅ អ្នកប្រើប្រាស់នេះ',
'emailpage'       => 'អ្នកប្រើប្រាស់ អ៊ីមែវល៍',
'defemailsubject' => 'អ៊ីមែវល៍ ផ្ញើពី {{SITENAME}}',
'noemailtitle'    => 'គ្មាន អាស័យដ្ឋានអ៊ីមែវល៍',
'emailfrom'       => 'ពី',
'emailto'         => 'ដល់',
'emailsubject'    => 'ប្រធានបទ',
'emailmessage'    => 'សារ',
'emailsend'       => 'ផ្ញើ',
'emailccme'       => 'អ៊ីមែវល៍មកខ្ញុំ មួយច្បាប់ចំលង នៃសាររបស់ខ្ញុំ។',
'emailsent'       => 'អ៊ីមែវល៍ ត្រូវបាន ផ្ញើទៅហើយ',
'emailsenttext'   => 'សារអ៊ីមែវល៍ របស់អ្នក ត្រូវបាន ផ្ញើទៅហើយ។',

# Watchlist
'watchlist'            => 'បញ្ជីតាមដាន របស់ខ្ញុំ',
'mywatchlist'          => 'បញ្ជីតាមដាន របស់ខ្ញុំ',
'watchlistfor'         => "(សំរាប់ '''$1''')",
'nowatchlist'          => 'អ្នកគ្មានរបស់អ្វី លើបញ្ជីតាមដាន របស់អ្នក',
'watchnologin'         => 'មិនបាន ពិនិត្យចូល',
'addedwatch'           => 'បានបន្ថែម ទៅ បញ្ជីតាមដាន',
'removedwatch'         => 'ត្រូវបានប្តូរទីតាំង ពី បញ្ជីតាមដាន',
'removedwatchtext'     => 'ទំព័រ "[[:$1]]" ត្រូវបាន ដកចេញ ពី បញ្ជីតាមដាន របស់អ្នក ។',
'watch'                => 'តាមដាន',
'watchthispage'        => 'តាមមើល ទំព័រនេះ',
'unwatch'              => 'លែង តាមដាន',
'unwatchthispage'      => 'ឈប់ តាមដាន',
'notanarticle'         => 'មិនមែនជាទំព័រមាតិកា',
'watchlist-details'    => 'អ្នកបានតាមដាន <b>$1</b> {{PLURAL:$1|ទំព័រ|ទំព័រ}} ដោយមិនរាប់ទាំង ទំព័រពិភាក្សា របស់អ្នក។',
'wlheader-showupdated' => "* ទំព័រ ដែលត្រូវបាន ផ្លាស់ប្តូរ តាំងពីពេល ចូលមើលចុងក្រោយ របស់អ្នក ត្រូវបានបង្ហាញ '''ដិត'''",
'watchmethod-recent'   => 'ឆែកមើល កំណែប្រែថ្មីៗ ចំពោះ ទំព័រត្រូវបានតាមដាន',
'watchmethod-list'     => 'ឆែកមើល ទំព័រត្រូវបានតាមដាន ចំពោះ កំណែប្រែថ្មីៗ',
'watchlistcontains'    => 'បញ្ជីតាមដាន របស់អ្នក មាន $1 {{PLURAL:$1|ទំព័រ|ទំព័រ}}។',
'wlshowlast'           => 'បង្ហាញ $1 ម៉ោងចុងក្រោយ, $2 ថ្ងៃចុងក្រោយ, ឬ $3',
'watchlist-show-bots'  => 'បង្ហាញ កំណែប្រែ របស់ រូបយន្ត',
'watchlist-hide-bots'  => 'បិទបាំង កំណែប្រែ របស់ រូបយន្ត',
'watchlist-show-own'   => 'បង្ហាញ កំណែប្រែ នានា ​របស់ខ្ញុំ',
'watchlist-hide-own'   => 'បិទបាំង កំណែប្រែ នានា ​របស់ខ្ញុំ',
'watchlist-show-minor' => 'បង្ហាញ កំណែប្រែ តិចតួច',
'watchlist-hide-minor' => 'បិទបាំង កំណែប្រែ តិចតួច',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'កំពុង តាមដាន...',
'unwatching' => 'លែង តាមដាន...',

'enotif_reset'                 => 'ចំណាំ គ្រប់ទំព័រ ដែលបាន ចូលមើល',
'enotif_newpagetext'           => 'នេះជា ទំព័រថ្មីមួយ។',
'enotif_impersonal_salutation' => 'អ្នកប្រើប្រាស់ {{SITENAME}}',
'changed'                      => 'បានផ្លាស់ប្តូរ',
'created'                      => 'បានបង្កើត',
'enotif_subject'               => 'ទំព័រ $PAGETITLE នៃ {{SITENAME}} ត្រូវបាន $CHANGEDORCREATED ដោយ $PAGEEDITOR',
'enotif_lastvisited'           => 'ពិនិត្យ $1 ចំពោះគ្រប់បំលាស់ប្តូរ តាំងពីពេលចូលមើល ចុងក្រោយ។',
'enotif_lastdiff'              => 'ពិនិត្យ $1 ដើម្បីមើលបំលាស់ប្តូរ នេះ។',
'enotif_anon_editor'           => 'អ្នកប្រើប្រាស់ អនាមិក $1',
'enotif_body'                  => '$WATCHINGUSERNAME ជាទីរាប់អាន,


ទំព័រ $PAGETITLE នៃ {{SITENAME}} ត្រូវបាន  $CHANGEDORCREATED ថ្ងៃ $PAGEEDITDATE ដោយ $PAGEEDITOR, មើល $PAGETITLE_URL ចំពោះកំណែបច្ចុប្បន្ន។

$NEWPAGE

សេចក្តីសង្ខេប នៃអ្នកកែប្រែ ៖ $PAGESUMMARY $PAGEMINOREDIT

ទាក់ទង អ្នកកែប្រែ ៖

មែវល៍ ៖ $PAGEEDITOR_EMAIL

វិគី ៖ $PAGEEDITOR_WIKI

នឹងមិនមាន ការផ្តល់ដំណឹង ជាលាយលក្សណ៍អក្សរ ផ្សេងទៀត លើកលែងតែ អ្នកចូលមើល ទំព័រនេះ។ អ្នកក៏អាចធ្វើ អោយ ការផ្តល់ដំណឹង ត្រលប់ទៅលើកទីសូន្យ ចំពោះគ្រប់ទំព័រ នៃបញ្ជីតាមដាន របស់អ្នក។ 

ប្រព័ន្ធផ្តល់ដំណឹង {{SITENAME}} ដ៏ស្និទ្ធស្នាល របស់អ្នក

--
ដើម្បីផ្លាស់ប្តូរ ការកំណត់បញ្ជីតាមដាន, សូមចូលមើល
{{fullurl:{{ns:special}}:Watchlist/edit}}

ប្រតិកម្ម និង ជំនួយបន្ថែម ៖
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'លុបចេញ ទំព័រ',
'confirm'                     => 'បញ្ជាក់ទទួលស្គាល់',
'excontent'                   => "ខ្លឹមសារ គឺ ៖ '$1'",
'exblank'                     => 'ទំព័រទទេ',
'delete-confirm'              => 'លុបចេញ "$1"',
'delete-legend'               => 'លុបចេញ',
'historywarning'              => 'ប្រយ័ត្ន ៖ អ្នកកំពុង លុបចេញ  ទំព័រ ដែលមាន ប្រវត្តិ ៖',
'deletedarticle'              => 'បានលុបចេញ "[[$1]]"',
'dellogpage'                  => 'កំណត់ហេតុ នៃការលុបចេញ',
'dellogpagetext'              => 'ខាងក្រោម ជាបញ្ជី នៃ ការលុបចេញថ្មីៗ បំផុត។',
'deletionlog'                 => 'កំណត់ហេតុ នៃ ការលុបចេញ',
'reverted'                    => 'បានត្រឡប់ ទៅកំណែមុន',
'deletecomment'               => 'ហេតុផល ចំពោះ ការលុបចេញ៖',
'deleteotherreason'           => 'ហេតុផល ផ្សេង/បន្ថែម៖',
'deletereasonotherlist'       => 'ហេតុផល ដទៃ',
'cantrollback'                => 'មិនអាចត្រឡប់ កំណែប្រែ; អ្នករួមចំណែកចុងក្រោយ ទើបជាអ្នកនិពន្ធ​អាចត្រឡប់ ទំព័រនេះ។',
'protectlogpage'              => 'កំណត់ហេតុ នៃការការពារ',
'protectedarticle'            => 'បានការពារ "[[$1]]"',
'modifiedarticleprotection'   => 'បានផ្លាស់ប្តូរ កំរិតការពារ នៃ "[[$1]]"',
'unprotectedarticle'          => 'លែង ការពារ អត្ថបទ: "[[$1]]"',
'protectsub'                  => '(ការពារ "$1")',
'confirmprotect'              => 'បញ្ជាក់ទទួលស្គាល់ ការការពារ',
'protectcomment'              => 'វិចារ៖',
'protectexpiry'               => 'ផុតកំណត់៖',
'protect_expiry_invalid'      => 'ពេលវេលា ផុតកំណត់ មិនមាន សុពលភាព។',
'protect_expiry_old'          => 'ពេលវេលា ផុតកំណត់ ថិតក្នុង អតីតកាល។',
'unprotectsub'                => '(លែងការពារ "$1")',
'protect-default'             => '(លំនាំដើម)',
'protect-fallback'            => 'តំរូវអោយមាន ការអនុញ្ញាតិ នៃ "$1"',
'protect-level-autoconfirmed' => 'ឃាត់ អ្នកប្រើប្រាស់ ដែលមិនបាន ចុះឈ្មោះ',
'protect-level-sysop'         => 'សំរាប់តែ អ្នកថែទាំប្រព័ន្ធ',
'protect-expiring'            => 'ផុតកំណត់ $1 (UTC)',
'restriction-type'            => 'ការអនុញ្ញាតិ៖',
'minimum-size'                => 'ទំហំ អប្បបរិមា',
'maximum-size'                => 'ទំហំ អតិបរិមា',
'pagesize'                    => '(បៃ)',

# Restrictions (nouns)
'restriction-edit'   => 'កែប្រែ',
'restriction-move'   => 'ប្តូរទីតាំង',
'restriction-create' => 'បង្កើត',

# Restriction levels
'restriction-level-sysop'         => 'បានការពារ ពេញ',
'restriction-level-autoconfirmed' => 'បានការពារ ពាក់កណ្តាល',
'restriction-level-all'           => 'កំរិត ណាក៏ដោយ',

# Undelete
'undelete'               => 'មើល ទំព័រនានា ដែលបានត្រូវលុបចេញ',
'viewdeletedpage'        => 'មើល ទំព័រនានា ដែលបានត្រូវលុបចេញ',
'undeletereset'          => 'ធ្វើអោយ ដូចដើមវិញ',
'undeletecomment'        => 'វិចារ៖',
'undelete-search-box'    => 'ស្វែងរកទំព័រ ដែលបានត្រូវលុប',
'undelete-search-prefix' => 'បង្ហាញទំព័រ ចាប់ផ្តើមដោយ៖',
'undelete-search-submit' => 'ស្វែងរក',
'undelete-cleanup-error' => 'កំហុស លុបចេញ បណ្ណសារ ដែលបានលែងប្រើប្រាស់ "$1" ។',
'undelete-error-short'   => 'កំហុស លែងលុបចេញ ឯកសារ ៖  $1',

# Namespace form on various pages
'namespace'      => 'វាលឈ្មោះ៖',
'invert'         => 'ត្រឡប់ជំរើសយក',
'blanknamespace' => '(ទូទៅ)',

# Contributions
'contributions' => 'ការរួមចំណែក របស់អ្នកប្រើប្រាស់',
'mycontris'     => 'ការរួមចំណែក របស់ខ្ញុំ',
'contribsub2'   => 'សំរាប់ $1 ($2)',
'ucnote'        => 'ខាងក្រោម ជា <b>$1</b> បំលាស់ប្តូរចុងក្រោយ របស់អ្នកប្រើប្រាស់នេះ ក្នុង <b>$2</b> ថ្ងៃ ចុងក្រោយ។',
'uctop'         => '(បន្ទាន់សម័យ)',
'month'         => 'ពី ខែ (និង មុនជាង)៖',
'year'          => 'ពី ឆ្នាំ (និង មុនជាង)៖',

'sp-contributions-newbies'     => 'បង្ហាញតែ ការរួមចំណែក របស់អ្នកប្រើប្រាស់ថ្មី',
'sp-contributions-newbies-sub' => 'ចំពោះ គណនីថ្មី នានា',
'sp-contributions-blocklog'    => 'កំណត់ហេតុ នៃការរាំងខ្ទប់',
'sp-contributions-search'      => 'ស្វែងរក ការរួមចំណែក នានា',
'sp-contributions-username'    => 'អាស័យដ្ឋាន IP ឬ ឈ្មោះអ្នកប្រើប្រាស់៖',
'sp-contributions-submit'      => 'ស្វែងរក',

# What links here
'whatlinkshere'       => 'តំណភ្ជាប់ ណាខ្លះ',
'whatlinkshere-title' => 'ទំព័រ ដែលតភ្ជាប់ ទៅ $1',
'whatlinkshere-page'  => 'ទំព័រ ៖',
'linklistsub'         => '(បញ្ជី នៃ តំណភ្ជាប់)',
'linkshere'           => "ទំព័រទាំងឡាយ តភ្ជាប់ ទៅ '''[[:$1]]''' ៖",
'nolinkshere'         => "គ្មានទំព័រ ណាមួយ តភ្ជាប់ ទៅ '''[[:$1]]'''។",
'nolinkshere-ns'      => "គ្មានទំព័រណាមួយ តភ្ជាប់ ទៅ '''[[:$1]]''' ក្នុងវាលឈ្មោះ ដែលបានជ្រើសរើស។",
'isredirect'          => 'ប្តូរទិស ទំព័រ',
'istemplate'          => 'ការរាប់បញ្ចូល',
'whatlinkshere-prev'  => '{{PLURAL:$1|មុន|មុន $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|បន្ទាប់|បន្ទាប់ $1}}',
'whatlinkshere-links' => '← តំណភ្ជាប់',

# Block/unblock
'blockip'                     => 'ខ្ទប់ អ្នកប្រើប្រាស់',
'ipaddress'                   => 'អាស័យដ្ឋាន IP ៖',
'ipadressorusername'          => 'អាស័យដ្ឋាន IP ឬ ឈ្មោះអ្នកប្រើប្រាស់៖',
'ipbexpiry'                   => 'រយៈពេលផុតកំណត់៖',
'ipbreason'                   => 'ហេតុផល៖',
'ipbreasonotherlist'          => 'ហេតុផល ផ្សេង',
'ipbanononly'                 => 'រាំងខ្ទប់ តែ អ្នកប្រើប្រាស់ អនាមិក',
'ipbsubmit'                   => 'ហាមឃាត់អ្នកប្រើប្រាស់នេះ',
'ipbother'                    => 'ពេល ផ្សេង៖',
'ipboptions'                  => '២ ម៉ោង:2 hours,១ ថ្ងៃ:1 day,៣ ថ្ងៃ:3 days,១ សប្តាហ៍:1 week,២ សប្តាហ៍:2 weeks,១ ខែ:1 month,៣ ខែ:3 months,៦ ខែ:6 months,១ ឆ្នាំ:1 year,គ្មានកំណត់:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'ផ្សេង',
'ipbotherreason'              => 'ហេតុផល ផ្សេង/បន្ថែម៖',
'badipaddress'                => 'អាស័យដ្ឋាន IP មិនត្រឹមត្រូវ',
'ipb-unblock-addr'            => 'លែងរាំងខ្ទប់ $1',
'ipb-unblock'                 => 'លែងរាំងខ្ទប់ អ្នកប្រើប្រាស់ ឬ អាស័យដ្ឋាន IP',
'unblockip'                   => 'លែងរាំងខ្ទាប់ អ្នកប្រើប្រាស់',
'ipusubmit'                   => 'លែងរាំងខ្ទប់ អាស័យដ្ឋាន នេះ',
'ipblocklist'                 => 'បញ្ជីអ្នកប្រើប្រាស់ និងអាស័យដ្ឋាន IP ដែលត្រូវបានហាមឃាត់',
'ipblocklist-legend'          => 'រកមើលអ្នកប្រើប្រាស់ដែលត្រូវបានហាមឃាត់',
'ipblocklist-username'        => 'ឈ្មោះអ្នកប្រើប្រាស់ ឬ អាស័យដ្ឋាន IP៖',
'ipblocklist-submit'          => 'ស្វែងរក',
'infiniteblock'               => 'គ្មានកំណត់',
'expiringblock'               => 'ផុតកំណត់ $1',
'anononlyblock'               => 'អនាមិក ប៉ុណ្ណោះ',
'noautoblockblock'            => 'មិនអាចរាំងខ្ទប់ ដោយស្វ័យប្រវត្តិ',
'createaccountblock'          => 'ការបង្កើតគណនី បានត្រូវរាំងខ្ទប់',
'emailblock'                  => 'អ៊ីមែវល៍ ត្រូវបានរាំងខ្ទប់',
'ipblocklist-empty'           => 'បញ្ជីរាំងខ្ទប់ ទទេ ។',
'blocklink'                   => 'រាំងខ្ទប់',
'unblocklink'                 => 'លែងរាំងខ្ទប់',
'contribslink'                => 'ការរួមចំណែក',
'blocklogpage'                => 'កំណត់ហេតុ នៃការរាំងខ្ទប់',
'blocklogtext'                => 'នេះជា កំណត់ហេតុនៃ សកម្មភាពរាំងខ្ទប់ និង លែងរាំងខ្ទប់ អ្នកប្រើប្រាស់។ អាស័យដ្ឋាន IP ដែលត្រូវបាន រាំងខ្ទប់ ដោយស្វ័យប្រវត្តិ មិនត្រូវបាន រាយបញ្ជី ទេ។ មើល [[Special:Ipblocklist|បញ្ជីរាំងខ្ទប់ IP ]] ចំពោះបញ្ជី ហាមឃាត់ និង រាំងខ្ទប់ ថ្មីៗ ។',
'unblocklogentry'             => 'លែងរាំងខ្ទប់ $1',
'block-log-flags-anononly'    => 'សំរាប់តែ អ្នកប្រើប្រាស់អនាមិក',
'block-log-flags-nocreate'    => 'ការបង្កើត គណនី ត្រូវបានហាម',
'block-log-flags-noautoblock' => 'មិនអាចរាំងខ្ទប់ ដោយស្វ័យប្រវត្តិ',
'block-log-flags-noemail'     => 'អ៊ីមែវល៍ ត្រូវបានរាំងខ្ទប់',
'ipb_already_blocked'         => '"$1" ត្រូវបានរាំងខ្ទប់​ហើយ',
'ip_range_invalid'            => 'ដែនកំណត់ IP គ្មានសុពលភាព។',
'blockme'                     => 'រាំងខ្ទប់ ខ្ញុំ',
'proxyblocksuccess'           => 'រួចរាល់។',

# Developer tools
'lockdb'              => 'ចាក់សោ មូលដ្ឋានទិន្នន័យ',
'unlockdb'            => 'ដោះសោ មូលដ្ឋានទិន្នន័យ',
'lockconfirm'         => 'បាទ/ចាស, ខ្ញុំពិតជាចង់ ចាក់សោ មូលដ្ឋានទិន្នន័យ។',
'unlockconfirm'       => 'បាទ/ចាស, ខ្ញុំពិតជាចង់ ដោះសោ មូលដ្ឋានទិន្នន័យ។',
'lockbtn'             => 'ចាក់សោ មូលដ្ឋានទិន្នន័យ',
'unlockbtn'           => 'ដោះសោ មូលដ្ឋានទិន្នន័យ',
'locknoconfirm'       => 'អ្នកមិនបាន ឆែកមើល ប្រអប់បញ្ជាក់ទទួលស្គាល់។',
'unlockdbsuccesssub'  => 'សោ មូលដ្ឋានទិន្នន័យ ត្រូវបានដកចេញ',
'unlockdbsuccesstext' => 'មូលដ្ឋានទិន្នន័យ ត្រូវបានដោះសោ រួចហើយ។',
'databasenotlocked'   => 'មូលដ្ឋានទិន្នន័យ មិនត្រូវបានចាក់សោ។',

# Move page
'movepage'                => 'ប្តូរទីតាំង ទំព័រ',
'movearticle'             => 'ប្តូរទីតាំង ទំព័រ៖',
'movenologin'             => 'មិនបាន ពិនិត្យចូល',
'movenotallowed'          => 'អ្នកគ្មានការអនុញ្ញាតិ ប្តូរទីតាំងទំព័រ លើ {{SITENAME}}។',
'newtitle'                => 'ទៅ ចំណងជើងថ្មី៖',
'move-watch'              => 'តាមដាន ទំព័រនេះ',
'movepagebtn'             => 'ប្តូរទីតាំង ទំព័រ',
'pagemovedsub'            => 'ប្តូទីតាំងបានសំរេច',
'movepage-moved'          => '<big>ទំព័រ(អត្ថបទ)\'\'\'"$1" ត្រូវបានប្តូរទីតាំងទៅជាទំព័រ "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'cantmove-titleprotected' => 'អ្នកមិនអាច​ប្តូទីតាំង ទំព័រ​ ទៅទីតាំងនេះ, ព្រោះ ចំណងជើងថ្មី បានត្រូវការពារ ចំពោះការបង្កើតវា',
'movedto'                 => 'បានប្តូរទីតាំង ទៅ',
'1movedto2'               => '[[$1]] បានប្តូរទីតាំងទៅ [[$2]]',
'1movedto2_redir'         => 'ទំព័រ [[$1]] ត្រូវបានប្តូរទីតាំងទៅ [[$2]] តាមរយៈការប្តូរទិស។',
'movelogpage'             => 'កំណត់ហេតុ នៃបណ្តូរទីតាំង',
'movelogpagetext'         => 'ខាងក្រោមជា បញ្ជី នៃ ទំព័រ ដែលត្រូវបាន ប្តូរទីតាំង។',
'movereason'              => 'ហេតុផល៖',
'revertmove'              => 'ត្រឡប់',
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
'allmessages'         => 'សារ នានា នៃ ប្រព័ន្ធ',
'allmessagesname'     => 'ឈ្មោះ',
'allmessagesdefault'  => 'អត្ថបទ លំនាំដើម',
'allmessagescurrent'  => 'អត្ថបទ បច្ចុប្បន្ន',
'allmessagesmodified' => 'បង្ហាញតែ បំលាស់ប្តូរ',

# Thumbnails
'thumbnail-more'           => 'ពង្រីក',
'filemissing'              => 'ឯកសារបាត់បង់',
'thumbnail_error'          => 'កំហុស បង្កើត កូនរូបភាព៖ $1',
'djvu_page_error'          => 'ទំព័រ DjVu ក្រៅដែនកំណត់',
'djvu_no_xml'              => 'មិនអាចនាំយក XML សំរាប់ឯកសារ DjVu',
'thumbnail_dest_directory' => 'មិនអាច បង្កើតថតឯកសារ គោលដៅ',

# Special:Import
'import'                     => 'នាំចូល ទំព័រនានា',
'importinterwiki'            => 'ការនាំចូល អន្តរវិគី',
'import-interwiki-history'   => 'ចំលង គ្រប់កំណែចាស់ នៃទំព័រនេះ',
'import-interwiki-submit'    => 'នាំចូល',
'import-interwiki-namespace' => 'ផ្ទេរទំព័រ នានា ទៅក្នុង វាលឈ្មោះ ៖',
'importstart'                => 'នាំចូល ទំព័រ...',
'importnopages'              => 'គ្មានទំព័រណាមួយ ត្រូវនាំចូល។',
'importfailed'               => 'ការនាំចូល ត្រូវបរាជ័យ ៖ <nowiki>$1</nowiki>',
'importunknownsource'        => 'មិនស្គាល់ ប្រភេទ នៃប្រភពនាំចូល',
'importcantopen'             => 'មិនអាចបើក ឯកសារនាំចូល',
'importbadinterwiki'         => 'តំណភ្ជាប់អន្តរវិគី មិនបានល្អ',
'importnotext'               => 'ទទេ ឬ គ្មានអត្ថបទ',
'importsuccess'              => 'នាំចូល ត្រូវបានបញ្ចប់!',
'importnofile'               => 'គ្មានឯកសារនាំចូល មួយណា ត្រូវបាន ផ្ទុកឡើង​។',
'import-noarticle'           => 'គ្មានទំព័រណា ត្រូវនាំចូល!',
'xml-error-string'           => '$1 នៅ ជួរដេក $2, ជួរឈរ $3 (បៃ $4) ៖ $5',

# Import log
'importlogpage'                    => 'កំណត់ហេតុ នៃការនាំចូល',
'import-logentry-upload'           => 'បាននាំចូល [[$1]] ដោយការផ្ទុកឡើង ឯកសារ',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|កំណែ}}',
'import-logentry-interwiki'        => 'បាននាំចូល $1 ពីវិគី ផ្សេង',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|កំណែ}} ពី $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ទំព័រអ្នកប្រើប្រាស់ របស់ខ្ញុំ',
'tooltip-pt-mytalk'               => 'ទំព័រពិភាក្សា របស់ខ្ញុំ',
'tooltip-pt-anontalk'             => 'ការពិភាក្សា អំពីកំណែប្រែ ពី អាស័យដ្ឋាន IP នេះ',
'tooltip-pt-preferences'          => 'ចំណូលចិត្តនានា របស់ខ្ញុំ',
'tooltip-pt-watchlist'            => 'បញ្ជី នៃ ទំព័រ ដែលអ្នកកំពុង តាមដាន សំរាប់ បំលាស់ប្តូរ',
'tooltip-pt-mycontris'            => 'បញ្ជីរួមចំណែក របស់ខ្ញុំ',
'tooltip-pt-login'                => 'អ្នកត្រូវបាន លើកទឹកចិត្ត អោយពិនិត្យចូល, មិនមែនជាការបង្ខំទេ។',
'tooltip-pt-anonlogin'            => 'អ្នកត្រូវបាន លើកទឹកចិត្ត អោយពិនិត្យចូល, មិនមែនជាការបង្ខំទេ។',
'tooltip-pt-logout'               => 'ពិនិត្យចេញ',
'tooltip-ca-talk'                 => 'ការពិភាក្សា អំពីទំព័រខ្លឹមសារ នេះ',
'tooltip-ca-edit'                 => "អ្នកអាច កែប្រែ ទំព័រ នេះ ។ ប្រើប្រាស់​ប្រអប់ 'មើលមុន' មុននឹង រក្សាទុក វា  ។",
'tooltip-ca-addsection'           => 'បន្ថែមមួយវិចារ ទៅ ការពិភាក្សានេះ។',
'tooltip-ca-viewsource'           => 'ទំព័រ នេះ បានត្រូវការពារ ។ អ្នកអាច មើល អក្សរកូដ របស់វា ។',
'tooltip-ca-protect'              => 'ការពារ ទំព័រនេះ',
'tooltip-ca-delete'               => 'លុបចេញ ទំព័រនេះ',
'tooltip-ca-move'                 => 'ប្តូរទីតាំង ទំព័រនេះ',
'tooltip-ca-watch'                => 'បន្ថែមទំព័រនេះ ទៅបញ្ជីតាមដាន របស់អ្នក',
'tooltip-ca-unwatch'              => 'ដកចេញ ទំព័រនេះ ពីបញ្ជីតាមដាន របស់ខ្ញុំ',
'tooltip-search'                  => 'ស្វែងរក {{SITENAME}}',
'tooltip-search-go'               => 'ទៅទំព័រ ដែលមាន ឈ្មោះត្រឹមត្រូវតាមនេះ បើមាន',
'tooltip-search-fulltext'         => 'ស្វែងរក ទំព័រនានា សំរាប់ អត្ថបទនេះ',
'tooltip-p-logo'                  => 'ទំព័រដើម',
'tooltip-n-mainpage'              => 'ចូលមើល ទំព័រដើម',
'tooltip-n-portal'                => 'អំពីគំរោង, វិធីប្រើប្រាស់ និង ការស្វែងរកពត៌មាន',
'tooltip-n-recentchanges'         => 'បញ្ជី នៃ បំលាស់ប្តូរថ្មីៗ នៅក្នុងវិគី។',
'tooltip-n-randompage'            => 'ផ្ទុក មួយទំព័រព្រាវ',
'tooltip-n-sitesupport'           => 'គាំទ្រ យើង',
'tooltip-t-whatlinkshere'         => 'រាយបញ្ជី ទំព័វិគី ទាំងអស់ ដែលតភ្ជាប់ នៅទីនេះ',
'tooltip-feed-rss'                => 'បំរែបំរួល RSS ចំពោះទំព័រនេះ',
'tooltip-feed-atom'               => 'បំរែបំរួល Atom ចំពោះទំព័រនេះ',
'tooltip-t-contributions'         => 'បង្ហាញ បញ្ជីរួមចំណែក នៃអ្នកប្រើប្រាស់នេះ',
'tooltip-t-emailuser'             => 'ផ្ញើ មួយមែវល៍ ទៅ អ្នកប្រើប្រាស់នេះ',
'tooltip-t-upload'                => 'ផ្ទុកឡើង ឯកសារនានា',
'tooltip-t-specialpages'          => 'បញ្ជី នៃ គ្រប់ទំព័រ ពិសេស',
'tooltip-ca-nstab-main'           => 'មើលទំព័រ នៃ មាតិកា',
'tooltip-ca-nstab-user'           => 'មើលទំព័រអ្នកប្រើប្រាស់',
'tooltip-ca-nstab-media'          => 'មើលទំព័រ មីឌា',
'tooltip-ca-nstab-special'        => 'នេះជា ទំព័រពិសេស, អ្នកមិនអាចកែប្រែ វា ទេ',
'tooltip-ca-nstab-project'        => 'មើលទំព័រគំរោង',
'tooltip-ca-nstab-image'          => 'មើលទំព័រ ឯកសារ',
'tooltip-ca-nstab-mediawiki'      => 'មើលសារ នៃ ប្រព័ន្ធ',
'tooltip-ca-nstab-template'       => 'មើល គំរូខ្នាត',
'tooltip-ca-nstab-help'           => 'មើលទំព័រជំនួយ',
'tooltip-ca-nstab-category'       => 'មើល ទំព័រ ចំណាត់ក្រុម',
'tooltip-minoredit'               => 'ចំណាំ នេះ ថាជា កំណែប្រែ តិចតួច',
'tooltip-save'                    => 'រក្សាទុក បំលាស់ប្តូរ',
'tooltip-preview'                 => 'មើលមុន បំលាស់ប្តូរ របស់អ្នក, សូមប្រើប្រាស់ នេះ មុននឹង រក្សាទុក!',
'tooltip-diff'                    => 'បង្ហាញ បំលាស់ប្តូរ ដែលអ្នកបានធ្វើ​ ចំពោះអត្ថបទ។',
'tooltip-compareselectedversions' => 'មើលភាពខុសគ្នា រវាងពីរកំណែ បានជ្រើសយក នៃទំព័រ នេះ។',
'tooltip-watch'                   => 'បន្ថែម ទំព័រនេះ ទៅ បញ្ជីតាមដាន របស់អ្នក',
'tooltip-recreate'                => 'បង្កើតទំព័រ ឡើងវិញ ថ្វីបើ វាបានត្រូវលុបចេញ',
'tooltip-upload'                  => 'ចាប់ផ្តើម ផ្ទុកឡើង',

# Attribution
'anonymous'        => 'អ្នកប្រើប្រាស់អនាមិក នៃ {{SITENAME}}',
'siteuser'         => 'អ្នកប្រើប្រាស់ $1 នៃ{{SITENAME}}',
'lastmodifiedatby' => 'ទំព័រនេះ បានត្រូវផ្លាស់ប្តូរ ចុងក្រោយ ដោយ $3 នៅវេលា $2, $1 ។', # $1 date, $2 time, $3 user
'othercontribs'    => 'ផ្អែកលើ ការងារ របស់ $1។',
'others'           => 'ផ្សេងៗទៀត',
'siteusers'        => 'អ្នកប្រើប្រាស់ $1 នៃ {{SITENAME}}',

# Spam protection
'subcategorycount'       => 'មាន {{PLURAL:$1|មួយចំណាត់ក្រុមរង|$1 ចំណាត់ក្រុមរង}} ចំពោះ ចំណាត់ក្រុម ។',
'categoryarticlecount'   => 'មាន $1 ទំព័រ ក្នុងចំណាត់ក្រុម នេះ ។',
'listingcontinuesabbrev' => 'បន្ត.',

# Info page
'infosubtitle'   => 'ពត៌មាន សំរាប់ ទំព័រ',
'numedits'       => 'ចំនួននៃកំណែប្រែ (អត្ថបទ)៖ $1',
'numtalkedits'   => 'ចំនួន នៃកំណែប្រែ (ទំព័រពិភាក្សា)៖ $1',
'numwatchers'    => 'ចំនួនអ្នកតាមដាន ៖ $1',
'numtalkauthors' => 'ចំនួនអ្នកនិពន្ធ (ទំព័រពិភាក្សា): $1',

# Patrolling
'markaspatrolleddiff'    => 'ចំណាំ ថា បានល្បាត',
'markaspatrolledtext'    => 'ចំណាំទំព័រនេះ ថា បានល្បាត',
'markedaspatrolled'      => 'បានចំណាំថា បានល្បាត',
'markedaspatrollederror' => 'មិនអាចចំណាំ ថា បានល្បាត',

# Patrol log
'patrol-log-page' => 'កំណត់ហេតុ នៃការល្បាត',
'patrol-log-line' => 'បានចំណាំការល្បាត $1 នៃ $2 ថា បានត្រួតពិនិត្យ $3',
'patrol-log-auto' => '(ស្វ័យប្រវត្តិ)',

# Image deletion
'deletedrevision'                 => 'បានលុបចេញ កំណែចាស់ $1',
'filedeleteerror-short'           => 'កំហុស លុបឯកសារ៖ $1',
'filedeleteerror-long'            => 'កំហុស ពេលលុបចេញ ឯកសារ ៖

$1',
'filedelete-missing'              => 'មិនអាចលុប ឯកសារ "$1"  ព្រោះ វាមិនមាន។',
'filedelete-current-unregistered' => 'ឯកសារ "$1" មិនមាន ក្នុងមូលដ្ឋានទិន្នន័យ។',
'filedelete-archive-read-only'    => 'ម៉ាស៊ីនបំរើសេវាវ៉ែប មិនអាច សរសេរទុក ថតបណ្ណសារ "$1" ។',

# Browsing diffs
'previousdiff' => '← ភាពខុសគ្នាមុននេះ',
'nextdiff'     => 'ខុសគ្នាបន្ទាប់ →',

# Media information
'mediawarning'         => "'''ព្រមាន''' ៖ ឯកសារនេះ អាចមានផ្ទុក អក្សរកូដពិសពុល, ខំព្យូរើ របស់អ្នក អាចមានគ្រោះថ្នាក់ បើអោយវាធ្វើការ ។<hr />",
'imagemaxsize'         => 'កំណត់ ទំហំរូបភាព លើទំព័រ ពិពណ៌នារូបភាព ត្រឹម៖',
'thumbsize'            => 'ទំហំកូនរូបភាព៖',
'widthheightpage'      => '$1×$2, $3 ទំព័រ',
'file-info'            => '(ទំហំឯកសារ៖ $1, ប្រភេទ MIME ៖ $2)',
'file-info-size'       => '($1 × $2 ធាតុរូបភាព, ទំហំឯកសារ៖ $3, ប្រភេទ MIME ៖ $4)',
'file-nohires'         => '<small>គ្មានភាពម៉ត់ ដែលខ្ពស់ជាង។</small>',
'show-big-image'       => 'ភាពម៉ត់ ពេញ',
'show-big-image-thumb' => '<small>ទំហំ នៃការមើលមុននេះ៖ $1 × $2 ធាតុរូបភាព</small>',

# Special:Newimages
'newimages'    => 'ទីតាំងពិពណ៌ រូបភាពថ្មី',
'showhidebots' => '($1 រូបយន្ត)',
'noimages'     => 'គ្មានឃើញអី សោះ។',

# Metadata
'metadata-expand'   => 'បង្ហាញភាពលំអិត',
'metadata-collapse' => 'បិទបាំង ភាពលំអិត ដែលបាន​ពង្រីក',

# EXIF tags
'exif-imagewidth'                  => 'ទទឹង',
'exif-imagelength'                 => 'កំពស់',
'exif-orientation'                 => 'ការតំរង់ទិស',
'exif-planarconfiguration'         => 'តំរៀបចំ ទិន្នន័យ',
'exif-jpeginterchangeformatlength' => 'ទំហំ ជា បៃ របស់ ទិន្នន័យ JPEG',
'exif-imagedescription'            => 'ចំណងជើង រូបភាព',
'exif-software'                    => 'ផ្នែកទន់ ត្រូវបានប្រើប្រាស់',
'exif-artist'                      => 'អ្នកនិពន្ធ',
'exif-exifversion'                 => 'កំណែ នៃ Exif',
'exif-flashpixversion'             => 'បានគាំទ្រ កំណែ Flashpix',
'exif-compressedbitsperpixel'      => 'កំរិតហាប់ នៃរូបភាព (ប៊ិត/ចំណុច)',
'exif-pixelydimension'             => 'ទទឹងសមស្រប នៃរូបភាព',
'exif-pixelxdimension'             => 'កំពស់សមស្រប នៃរូបភាព',
'exif-usercomment'                 => 'វិចារ នានា របស់អ្នកប្រើប្រាស់',
'exif-relatedsoundfile'            => 'ឯកសារសំលេង ទាក់ទិន',
'exif-gpslatitude'                 => 'រយះទទឹង',
'exif-gpslongitude'                => 'រយះបណ្តោយ',
'exif-gpsaltitude'                 => 'រយះកំពស់',
'exif-gpsareainformation'          => 'ឈ្មោះ នៃ តំបន់ GPS',
'exif-gpsdatestamp'                => 'ថ្ងៃខែឆ្នាំ GPS',

# EXIF attributes
'exif-compression-1' => 'លែងបានបង្ហាប់',

'exif-unknowndate' => 'មិនដឹង ថ្ងៃខែឆ្នាំ',

'exif-subjectdistance-value' => '$1 ម៉ែត្រ',

'exif-meteringmode-0'   => 'មិនបានស្គាល់',
'exif-meteringmode-1'   => 'មធ្យម',
'exif-meteringmode-255' => 'ផ្សេង',

'exif-lightsource-0' => 'មិនដឹង',

'exif-sensingmethod-1' => 'មិនត្រូវបានកំណត់',

'exif-scenecapturetype-0' => 'ស្តង់ដារ',
'exif-scenecapturetype-1' => 'រូបផ្តេក',
'exif-scenecapturetype-2' => 'រូបបញ្ឈរ',

'exif-gaincontrol-0' => 'ទទេ',

'exif-sharpness-0' => 'ធម្មតា',

'exif-subjectdistancerange-0' => 'មិនដឹង',
'exif-subjectdistancerange-1' => 'ម៉ាក្រូ',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'ខាងជើង',
'exif-gpslatitude-s' => 'ខាងត្បូង',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'ខាងកើត',
'exif-gpslongitude-w' => 'ខាងលិច',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'គីឡូមែត្រ ក្នុងមួយម៉ោង',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ខាងជើងពិត',
'exif-gpsdirection-m' => 'ខាងជើងម៉ាញេទិក',

# External editor support
'edit-externally' => 'កែប្រែ ឯកសារ នេះ ដោយប្រើប្រាស់ កម្មវិធី ខាងក្រៅ',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ទាំងអស់',
'imagelistall'     => 'ទាំងអស់',
'watchlistall2'    => 'ទាំងអស់',
'namespacesall'    => 'ទាំងអស់',
'monthsall'        => 'ទាំងអស់',

# E-mail address confirmation
'confirmemail'           => 'បញ្ជាក់ទទួលស្គាល់ អាស័យដ្ឋានអ៊ីមែវល៍',
'confirmemail_send'      => 'ផ្ញើ អក្សរកូដបញ្ជាក់ទទួលស្គាល់',
'confirmemail_sent'      => 'បញ្ជាក់ទទួលស្គាល់ អាស័យដ្ឋានអ៊ីមែវល៍ ដែលបានផ្ញើទៅ។',
'confirmemail_needlogin' => 'អ្នកត្រូវការ $1 ដើម្បី បញ្ជាក់ទទួលស្គាល់ អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក ។',
'confirmemail_success'   => 'អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក ត្រូវបាន បញ្ជាក់ទទួលស្គាល់ ហើយ ។ ពេលនេះ អ្នកអាច ពិនិត្យចូល និង រីករាយ ជាមួយ វិគី ។',
'confirmemail_loggedin'  => 'អាស័យដ្ឋានអ៊ីមែវល៍ របស់អ្នក ត្រូវបាន បញ្ជាក់ទទួលស្គាល់ ហើយ',

# Scary transclusion
'scarytranscludetoolong' => '[URL វែងជ្រុល; អធ្យាស្រ័យ!]',

# Trackbacks
'trackbackremove' => ' ([$1 លុបចេញ])',

# Delete conflict
'deletedwhileediting' => 'ប្រយ័ត្ន ៖ ទំព័រនេះ បានត្រូវលុបចេញហើយ បន្ទាប់ពី អ្នកបានចាប់ផ្តើម កែប្រែ!',
'recreate'            => 'បង្កើតឡើងវិញ',

# HTML dump
'redirectingto' => 'កំពុងប្តូរទិស ទៅ [[$1]]...',

# action=purge
'confirm_purge_button' => 'យល់ព្រម',

# AJAX search
'searchcontaining' => "ស្វែងរក អត្ថបទ ដែលផ្ទុក ''$1'' ។",
'searchnamed'      => "ស្វែងរក អត្ថបទ ដែលមានឈ្មោះ ''$1'' ។",
'articletitles'    => "អត្ថបទផ្តើមដោយ ''$1''",
'hideresults'      => 'បិទបាំង លទ្ធផល',
'useajaxsearch'    => 'ប្រើប្រាស់ ការស្វែងរក របស់ AJAX',

# Multipage image navigation
'imgmultipageprev' => '← ទំព័រមុន',
'imgmultipagenext' => 'ទំព័រ បន្ទាប់ →',
'imgmultigo'       => 'ទៅ!',
'imgmultigotopre'  => 'ទៅ ទំព័រ',

# Table pager
'ascending_abbrev'         => 'លំដាប់ ឡើង',
'descending_abbrev'        => 'លំដាប់ ចុះ',
'table_pager_next'         => 'ទំព័របន្ទាប់',
'table_pager_prev'         => 'ទំព័រ មុន',
'table_pager_first'        => 'ទំព័រ ដំបូង',
'table_pager_last'         => 'ទំព័រ ចុងក្រោយ',
'table_pager_limit'        => 'បង្ហាញ $1 មុខ ក្នុងមួយទំព័រ',
'table_pager_limit_submit' => 'ទៅ',
'table_pager_empty'        => 'គ្មាន លទ្ធផល',

# Auto-summaries
'autosumm-blank'   => 'ដកចេញ ខ្លឹមសារទាំងអស់ ពីទំព័រ',
'autosumm-replace' => "ជំនួស ខ្លឹមសារ នៃទំព័រ ដោយ '$1'",
'autoredircomment' => 'ប្តូរទិស ទៅ [[$1]]',
'autosumm-new'     => 'ទំព័រថ្មី៖ $1',

# Live preview
'livepreview-loading' => 'កំពុង ផ្ទុក…',

# Friendlier slave lag warnings
'lag-warn-normal' => 'បំលាស់ប្តូរ ថ្មីជាង $1 វិនាទី អាចមិនត្រូវបាន បង្ហាញ ក្នុងបញ្ជីនេះ ។',

# Watchlist editor
'watchlistedit-numitems'       => 'បញ្ជីតាមដាន របស់អ្នក មាន {{PLURAL:$1|1 ចំណងជើង|$1 ចំណងជើង}}, មិនរាប់ ទំព័រពិភាក្សា ទេ ។',
'watchlistedit-noitems'        => 'បញ្ជីតាមដាន របស់អ្នក គ្មានផ្ទុក ចំណងជើង។',
'watchlistedit-normal-title'   => 'កែប្រែ បញ្ជីតាមដាន',
'watchlistedit-normal-legend'  => 'ដកចេញ ចំណងជើង នានា ពី បញ្ជីតាមដាន',
'watchlistedit-normal-explain' => "ចំណងជើង លើ បញ្ជីតាមដាន របស់អ្នក ត្រូវបាន បង្ហាញ ខាងក្រោម ។
ដើម្បី ដកចេញ មួយចំណងជើង, គូសឆែក ប្រអប់ ក្បែរ វា, រួច ចុចលើ ប្រអប់ 'ដកចេញ ចំណងជើង នានា' ។
អ្នកអាច ផងដែរ [[Special:Watchlist/raw|កែប្រែ បញ្ជីឆៅ]] ។",
'watchlistedit-normal-submit'  => 'ដកចេញ ចំណងជើង នានា',
'watchlistedit-raw-title'      => 'កែប្រែ បញ្ជីតាមដានឆៅ',
'watchlistedit-raw-legend'     => 'កែប្រែ បញ្ជីតាមដានឆៅ',
'watchlistedit-raw-explain'    => 'ចំណងជើង នានា លើ បញ្ជីតាមដាន របស់អ្នក ត្រូវបាន បង្ហាញខាងក្រោម, និង អាចត្រូវបាន កែប្រែ ដោយបន្ថែម ទៅ ឬ ដកចេញ ពី បញ្ជី, មួយ​ចំណងជើង ក្នុង មួយបន្ទាត់ ។ ពេល បានបញ្ចប់, ចុច បន្ទាន់សម័យ បញ្ជីតាមដាន ។
អ្នក អាចផងដែរ [[Special:Watchlist/edit|ប្រើប្រាស់ ឧបករកែប្រែ គំរូ]] ។',
'watchlistedit-raw-titles'     => 'ចំណងជើង នានា ៖',
'watchlistedit-raw-submit'     => 'បន្ទាន់សម័យ បញ្ជីតាមដាន',
'watchlistedit-raw-done'       => 'បញ្ជីតាមដាន ត្រូវបាន បន្ទាន់សម័យ ហើយ។',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 ចំណងជើង បានត្រូវ|$1 ចំណងជើង បានត្រូវ}} បន្ថែម ៖',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 ចំណងជើង បានត្រូវ|$1 ចំណងជើង បានត្រូវ}} ដកចេញ ៖',

# Watchlist editing tools
'watchlisttools-view' => 'មើល បំលាស់ប្តូរ ទាក់ទិន',
'watchlisttools-edit' => 'មើល និង កែប្រែ បញ្ជីតាមដាន',
'watchlisttools-raw'  => 'កែប្រែ បញ្ជីតាមដានឆៅ',

# Special:Version
'version-specialpages'      => 'ទំព័រ ពិសេសៗ',
'version-other'             => 'ផ្សេង',
'version-hook-subscribedby' => 'បានជាវ ជាប្រចាំ ដោយ',
'version-version'           => 'កំណែ',
'version-license'           => 'អាជ្ញាបណ្ណ',
'version-software'          => 'ផ្នែកទន់ ដែលបាន តំលើង',
'version-software-product'  => 'ផលិតផល',
'version-software-version'  => 'កំណែ',

# Special:Filepath
'filepath'         => 'ផ្លូវ នៃឯកសារ',
'filepath-page'    => 'ឯកសារ៖',
'filepath-submit'  => 'ផ្លូវ',
'filepath-summary' => 'ទំព័រពិសេសនេះ បង្ហាញផ្លូវពេញលេញ នៃ មួយឯកសារ។

រូបភាពត្រូវបានបង្ហាញ ជាភាពម៉ត់ខ្ពស់, ប្រភេទឯកសារដទៃទៀត ធ្វើការដោយផ្ទាល់ ជាមួយកម្មវិធីរួមផ្សំជាមួយវា។ 

បញ្ចូល ឈ្មោះឯកសារ ដោយគ្មានការភ្ជាប់ "{{ns:image}}:" នៅពីមុខវា ។',

);
