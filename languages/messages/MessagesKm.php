<?php
/** Khmer (ភាសាខ្មែរ)
 *
 * @addtogroup Language
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Bunly
 * @author តឹក ប៊ុនលី
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
# Dates
'monday'    => 'ច័ន្ទ',
'tuesday'   => 'អង្គារ',
'wednesday' => 'ពុធ',
'thursday'  => 'ព្រហស្បតិ៍',
'friday'    => 'សុក្រ',
'saturday'  => 'សៅរ៏',
'january'   => 'មករា',
'february'  => 'កម្ភៈ',
'march'     => 'មិនា',
'april'     => 'មេសា',

'about'          => 'អំពី',
'cancel'         => 'បោះបង់ចោល',
'qbfind'         => 'រាវរក',
'qbedit'         => 'កែ',
'qbspecialpages' => 'ទំព័រពិសេសៗ',
'moredotdotdot'  => 'បន្ថែមទៀត...',
'navigation'     => 'ទៅដៅ',

'returnto'         => 'ត្រលប់ទៅកាន់ $1 វិញ។',
'help'             => 'ជំនួយ',
'search'           => 'ស្វែងរក',
'searchbutton'     => 'ស្វែងរក',
'go'               => 'ទៅ',
'searcharticle'    => 'ទៅ',
'history'          => 'ទំព័រប្រវត្តិ',
'history_short'    => 'ប្រវត្តិ',
'info_short'       => 'ព័ត៌មាន',
'printableversion' => 'ទំរង់សំរាប់បោះពុម្ភ',
'print'            => 'បោះពុម្ភ',
'edit'             => 'កែប្រែ',
'editthispage'     => 'កែប្រែទំព័រនេះ',
'delete'           => 'លុប',
'protect'          => 'ការពារ',
'unprotect'        => 'ឈប់ការពារ',
'talkpagelinktext' => 'ពិភាក្សា',
'specialpage'      => 'ទំព័រពិសេស',
'talk'             => 'ពិភាក្សា',
'views'            => 'មើល',
'toolbox'          => 'ប្រអប់ឧបករណ៍',
'protectedpage'    => 'ទំព័របានការពារ',
'jumptonavigation' => 'ទិសដៅ',
'jumptosearch'     => 'ស្វែងរក',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutpage'         => 'គំរោង:អំពី',
'currentevents'     => 'ព្រិត្តិការណ៍ថ្មីៗ',
'currentevents-url' => 'គំរោង:ព្រិត្តិការណ៍ថ្មីៗ',
'edithelp'          => 'ជំនួយការកែប្រែ',
'edithelppage'      => 'ជំនួយ:ការកែប្រែ',
'helppage'          => 'ជំនួយ:មាតិកា',
'mainpage'          => 'ទំព័រដើម',
'portal'            => 'សមាគមន៏',
'sitesupport'       => 'វិភាគទាន',

'ok'                  => 'យល់ព្រម',
'newmessagesdifflink' => 'បំលាស់ប្តូរចុងក្រោយ',
'editsection'         => 'កែប្រែ',
'editold'             => 'កែប្រែ',
'editsectionhint'     => 'កែប្រែផ្នែក: $1',
'toc'                 => 'មាតិកា',
'showtoc'             => 'បង្ហាញ',
'hidetoc'             => 'លាក់',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ទំព័រ',
'nstab-user'      => 'ទំព័រអ្នកប្រើ',
'nstab-special'   => 'ពិសេស',
'nstab-project'   => 'ទំព័រគំរោង',
'nstab-image'     => 'ឯកសារ',
'nstab-mediawiki' => 'សារ',
'nstab-template'  => 'ទំព័រគំរូ',
'nstab-help'      => 'ទំព័រជំនួយ',
'nstab-category'  => 'ចំណាត់ថ្នាក់ក្រុម',

# General errors
'viewsource'    => 'មើលកូដ',
'viewsourcefor' => 'សំរាប់ $1',

# Login and logout pages
'welcomecreation'    => '== សូមស្វាគមន៏ $1! ==

គណនីរបស់អ្នកត្រូវបានបង្កើតរួចហើយ។ សូមកុំភ្លេចផ្លាស់ប្តូរចំនូលចិត្ត{{SITENAME}}របស់អ្នក!',
'yourname'           => 'អ្នកប្រើប្រាស់:',
'yourpassword'       => 'ពាក្យសំងាត់:',
'yourpasswordagain'  => 'វាយពាក្យសំងាត់ម្តងទៀត:',
'remembermypassword' => 'ចងចាំការឡុកអ៊ិនចូលរបស់ខ្ញុំនៅក្នុងកុំព្យូទ័រនេះ',
'login'              => 'ឡុកអ៊ិន',
'userlogin'          => 'ឡុកអ៊ិនឬបង្កើតគណនី',
'logout'             => 'ចាកចេញ',
'userlogout'         => 'ចាកចេញ',
'nologin'            => 'តើអ្នកមានគណនេយ្យសំរាប់ប្រើប្រាស់ហើយរឺនៅ? $1 ។',
'nologinlink'        => 'បង្កើតគណនី',
'createaccount'      => 'បង្កើតគណនី',
'gotaccountlink'     => 'ឡុកអ៊ិន',
'createaccountmail'  => 'តាមរយះអ៊ីម៉ែល',
'youremail'          => 'អ៊ីម៉ែល:',
'username'           => 'អ្នកប្រើប្រាស់:',
'uid'                => 'អត្តសញ្ញាណលេខ:',
'yourrealname'       => 'ឈ្មោះពិត:',
'yourlanguage'       => 'ភាសា:',
'yournick'           => 'ឈ្មោះហៅក្រៅ:',
'loginsuccesstitle'  => 'ឡុកអ៊ីនចូលបានជោគជ័យហើយ!',

# Edit page toolbar
'bold_sample'   => 'អក្សរឌិត',
'bold_tip'      => 'អក្សរឌិត',
'italic_sample' => 'អក្សរទ្រេត',

# Edit pages
'summary'                => 'សេចក្តីសង្ខេប',
'savearticle'            => 'រក្សាទំព័រទុក',
'preview'                => 'មើលជាមុន',
'showpreview'            => 'បង្ហាញការមើលជាមុន',
'showdiff'               => 'បង្ហាញបំលាស់ប្តូរ',
'newarticle'             => '(ថ្មី)',
'template-protected'     => '(បានការពារ)',
'template-semiprotected' => '(ពាក់កណ្តាលបានការពារ)',

# History pages
'next'       => 'បន្ទាប់',
'last'       => 'ចុងក្រោយ',
'page_first' => 'ដំបូង',
'page_last'  => 'ចុងក្រោយ',

# Revision deletion
'rev-delundel' => 'បង្ហាញ/លាក់',

# Diffs
'lineno' => 'បន្ទាប់ទី $1:',

# Search results
'powersearch' => 'ស្វែងរក',

# Preferences page
'preferences'       => 'ចំនូលចិត្ត',
'mypreferences'     => 'ចំនូលចិត្តរបស់ខ្ញុំ',
'changepassword'    => 'ប្តូរពាក្យសម្ងាត់',
'math'              => 'គណិតវិទ្យា',
'datetime'          => 'វេលា',
'prefs-personal'    => 'ប្រវត្តិរូប',
'prefs-rc'          => 'បំលាស់ប្តូរថ្មីៗ',
'prefs-watchlist'   => 'បញ្ជីត្រួតពិនិត្យ',
'prefs-misc'        => 'ផ្សេងៗ',
'saveprefs'         => 'រក្សាទុក',
'resetprefs'        => 'ធ្វើឲដូចដើមវិញ',
'oldpassword'       => 'ពាក្យសំងាត់ចាស់:',
'newpassword'       => 'ពាក្យសំងាត់ថ្មី:',
'retypenew'         => 'វាយពាក្យសំងាត់ថ្មីម្តងទៀត:',
'textboxsize'       => 'ការកែប្រែ',
'searchresultshead' => 'ស្វែងរក',
'savedprefs'        => 'ចំនូលចិត្តរបសើអ្នកត្រូវបានរក្សាទុកហើយ។',
'timezonelegend'    => 'ល្វែងម៉ោង',
'default'           => 'លំនាំដើម',
'files'             => 'ឯកសារ',

# Recent changes
'recentchanges' => 'បំលាស់ប្តូរថ្មីៗ',
'hide'          => 'លាក់',
'show'          => 'បង្ហាញ',

# Upload
'upload'        => 'អាប់ឡូដ',
'uploadbtn'     => 'ផ្ទុកឯកសារ',
'uploadlogpage' => 'កំណត់ហេតុនៃការផ្ទុកឯកសារ',

'license' => 'អាជ្ញាបណ្ណ',

# Image list
'imagelist'             => 'បញ្ជីរូបភាព',
'filehist'              => 'ប្រវត្តិឯកសារ',
'filehist-filesize'     => 'ទំហំហ្វាល់',
'imagelinks'            => 'តំណភ្ជាប់',
'imagelist_date'        => 'កាលបរិច្ឆេទ',
'imagelist_description' => 'ពិពណ៌នា',

# File deletion
'filedelete-submit' => 'លុប',

# MIME search
'download' => 'ទាញយក',

# Statistics
'userstats' => 'ស្ថិតិអ្នកប្រើប្រាស់',

# Miscellaneous special pages
'unusedimages' => 'ហ្វាល់(ឯកសារ)ដែលមិនប្រើ',
'popularpages' => 'ទំព័រមានប្រជាប្រិយភាព',
'allpages'     => 'គ្រប់ទំព័រ',
'shortpages'   => 'ទំព័រខ្លីៗ',
'longpages'    => 'ទំព័រវែងៗ',
'listusers'    => 'បញ្ជីអ្នកប្រើប្រាស់',
'specialpages' => 'ទំព័រពិសេសៗ',
'ancientpages' => 'ទំព័រចាស់ជាងគេ',
'move'         => 'ប្តូរទីតាំង',

# Special:Log
'specialloguserlabel'  => 'អ្នកប្រើប្រាស់:',
'speciallogtitlelabel' => 'ចំណងជើង:',
'log'                  => 'កំណត់ហេតុ',
'all-logs-page'        => 'គ្រប់កំណត់ហេតុទាំងអស់',
'log-search-submit'    => 'ទៅ',

# Special:Allpages
'allarticles' => 'គ្រប់អត្ថបទទាំងអស់',

# Watchlist
'watch'         => 'ពិនិត្យមើល',
'watchthispage' => 'ពិនិត្យមើលទំព័រនេះ',
'unwatch'       => 'ឈប់មើល',

# Displayed when you click the "watch" button and it's in the process of watching
'watching' => 'កំពុងពិនិត្យមើល...',

# Delete/protect/revert
'deletereasonotherlist' => 'មូលហេតុផ្សេងទៀត',

# Contributions
'contributions' => 'ការរួមចំនែករបស់អ្នកប្រើប្រាស់',
'mycontris'     => 'ការរួមចំនែករបស់ខ្ញុំ',

# Block/unblock
'contribslink' => 'ការរួមចំនែក',
'blocklogpage' => 'កំណត់ហេតុនៃការហាមឃាត់',

# Move page
'movelogpage' => 'កំណត់ហេតុនៃការផ្លាស់ប្តូរទីតាំង',

# Tooltip help for the actions
'tooltip-ca-protect'     => 'ការពារទំព័រនេះ',
'tooltip-ca-delete'      => 'លុបទំព័រនេះ',
'tooltip-p-logo'         => 'ទំព័រដើម',
'tooltip-n-mainpage'     => 'មើលទំព័រដើម',
'tooltip-t-specialpages' => 'បញ្ជីគ្រប់ទំព័រពិសេសទាំងអស់',

# Special:Newimages
'newimages' => 'វិចិត្រសាលនៃឯកសារ(Gallery of new files)',

# Table pager
'table_pager_limit_submit' => 'ទៅ',

);
