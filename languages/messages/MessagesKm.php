<?php
/** Khmer (ភាសាខ្មែរ)
 *
 * @addtogroup Language
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Bunly
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

'cancel'         => 'បោះបង់ចោល',
'qbedit'         => 'កែ',
'qbspecialpages' => 'ទំព័រពិសេសៗ',

'returnto'         => 'ត្រលប់ទៅកាន់ $1 វិញ។',
'help'             => 'ជំនួយ',
'searchbutton'     => 'ស្វែងរក',
'go'               => 'ទៅ',
'searcharticle'    => 'ទៅ',
'history_short'    => 'ប្រវត្តិ',
'info_short'       => 'ព័ត៌មាន',
'printableversion' => 'ទំរង់សំរាប់បោះពុម្ភ',
'print'            => 'បោះពុម្ភ',
'edit'             => 'កែប្រែ',
'editthispage'     => 'កែប្រែទំព័រនេះ',
'delete'           => 'លុប',
'protect'          => 'ការពារ',
'unprotect'        => 'ឈប់ការពារ',
'specialpage'      => 'ទំព័រពិសេស',
'talk'             => 'ពិភាក្សា',
'views'            => 'មើល',
'toolbox'          => 'ប្រអប់ឧបករណ៍',
'jumptonavigation' => 'ទិសដៅ',
'jumptosearch'     => 'ស្វែងរក',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents' => 'ព្រិត្តិការណ៍ថ្មីៗ',
'edithelp'      => 'ជំនួយការកែប្រែ',
'helppage'      => 'ជំនួយ:មាតិកា',
'mainpage'      => 'ទំព័រដើម',
'sitesupport'   => 'វិភាគទាន',

'ok'          => 'យល់ព្រម',
'editsection' => 'កែប្រែ',
'editold'     => 'កែប្រែ',
'toc'         => 'មាតិកា',
'showtoc'     => 'បង្ហាញ',
'hidetoc'     => 'លាក់',

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
'yourname'           => 'អ្នកប្រើប្រាស់:',
'yourpassword'       => 'ពាក្យសំងាត់:',
'yourpasswordagain'  => 'វាយពាក្យសំងាត់ម្តងទៀត:',
'remembermypassword' => 'ចងចាំការឡុកអ៊ិនចូលរបស់ខ្ញុំនៅក្នុងកុំព្យូទ័រនេះ',
'login'              => 'ឡុកអ៊ិន',
'userlogin'          => 'ឡុកអ៊ិនឬបង្កើតគណនេយ្យ',
'userlogout'         => 'ចាកចេញ',
'nologin'            => 'តើអ្នកមានគណនេយ្យសំរាប់ប្រើប្រាស់ហើយរឺនៅ? $1 ។',
'nologinlink'        => 'បង្កើតគណនេយ្យ',
'gotaccountlink'     => 'ឡុកអ៊ិន',
'createaccountmail'  => 'តាមរយះអ៊ីម៉ែល',
'youremail'          => 'អ៊ីម៉ែល:',
'username'           => 'អ្នកប្រើប្រាស់:',
'uid'                => 'អត្តសញ្ញាណលេខ:',
'yourrealname'       => 'ឈ្មោះពិត:',
'yourlanguage'       => 'ភាសា:',
'yournick'           => 'ឈ្មោះហៅក្រៅ:',

# Edit page toolbar
'bold_sample' => 'អក្សរឌិត',
'bold_tip'    => 'អក្សរឌិត',

# Edit pages
'savearticle'            => 'រក្សាទំព័រទុក',
'preview'                => 'មើលជាមុន',
'showpreview'            => 'បង្ហាញការមើលជាមុន',
'showdiff'               => 'បង្ហាញបំលាស់ប្តូរ',
'template-protected'     => '(បានការពារ)',
'template-semiprotected' => '(ពាក់កណ្តាលបានការពារ)',

# Revision deletion
'rev-delundel' => 'បង្ហាញ/លាក់',

# Preferences page
'math'              => 'គណិតវិទ្យា',
'datetime'          => 'កាលបរិច្ឆេទនិងល្វែងម៉ោង',
'prefs-personal'    => 'ប្រវត្តិរបស់ខ្ញុំ',
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
'timezonelegend'    => 'ល្វែងម៉ោង',
'default'           => 'លំនាំដើម',
'files'             => 'ឯកសារ',

# Recent changes
'hide' => 'លាក់',
'show' => 'បង្ហាញ',

# Upload
'upload' => 'អាប់ឡូដឯកសារ',

# File deletion
'filedelete-submit' => 'លុប',

# Special:Log
'log-search-submit' => 'ទៅ',

# Table pager
'table_pager_limit_submit' => 'ទៅ',

);
