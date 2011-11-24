<?php
/** Kashmiri (Arabic script) (کٲشُر)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

# @todo FIXME: Inherit almost everything for now
$rtl = true;

$digitTransformTable = array(
	'0' => '٠', # &#x0660;
	'1' => '١', # &#x0661;
	'2' => '٢', # &#x0662;
	'3' => '٣', # &#x0663;
	'4' => '٤', # &#x0664;
	'5' => '٥', # &#x0665;
	'6' => '٦', # &#x0666;
	'7' => '٧', # &#x0667;
	'8' => '٨', # &#x0668;
	'9' => '٩', # &#x0669;
	'.' => '٫', # &#x066b; wrong table ?
	',' => '٬', # &#x066c;
);

$messages = array(
# Dates
'monday'    => 'ژِنٛدٕروار',
'february'  => 'فرؤری',
'april'     => 'اپریٖل',
'may_long'  => 'مٔی',
'june'      => 'جوٗن',
'august'    => 'اَگست',
'september' => 'سیٚپٹَمبَر',
'november'  => 'نَوَمبَر',
'december'  => 'ڈیٚسَمبَر',

'about' => 'مُتعلِق',

'help'             => 'مدد',
'search'           => 'ژھارُن',
'searchbutton'     => 'ژھارُن',
'history_short'    => 'توٲریٖخ',
'talkpagelinktext' => 'بَحَژ',
'talk'             => 'بَحَژ',
'jumptosearch'     => 'ژھارُن',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'مُتعلِق {{SITENAME}}',
'mainpage'  => 'گَرٕ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'صَفہٕ',
'nstab-category' => 'زٲژ',

# Edit pages
'summary' => 'خُلاسہٕ:',

# Recent changes
'recentchanges' => 'نَوِ تبدیلی',

# Upload
'filedesc' => 'خُلاسہٕ',

'sp-contributions-talk'   => 'بَحَژ',
'sp-contributions-submit' => 'ژھارُن',

# Namespace 8 related
'allmessagesname' => 'ناو',

);
