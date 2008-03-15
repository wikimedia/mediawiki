<?php
/** Mongolian (Монгол)
 *
 * @addtogroup Language
 *
 * @author לערי ריינהארט
 * @author Chinneeb
 */

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюя“»]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'       => 'Линкүүдийн доогуур зураас зур:',
'tog-highlightbroken' => 'Эвдэрхий линкүүдийг <a href="" class="new">ингэж</a> үзүүлэх: (өөрөөр: ингэж<a href="" class="internal">?</a>).',

# Dates
'sunday'    => 'Ням',
'monday'    => 'Даваа',
'tuesday'   => 'Мягмар',
'wednesday' => 'Лхагва',
'thursday'  => 'Пүрэв',
'friday'    => 'Баасан',
'saturday'  => 'Бямба',
'january'   => 'Нэгдүгээр сар',
'february'  => 'Хоёрдугаар сар',
'march'     => 'Гуравдугаар сар',
'april'     => 'Дөрөвдүгээр сар',
'may_long'  => 'Тавдугаар сар',
'june'      => 'Зургаадугаар сар',
'july'      => 'Долоодугаар сар',
'august'    => 'Наймдугаар сар',
'september' => 'Есдүгээр сар',
'october'   => 'Аравдугаар сар',
'november'  => 'Арваннэгдүгээр сар',
'december'  => 'Арванхоёрдугаар сар',
'jan'       => '1-р сар',
'feb'       => '2-р сар',
'mar'       => '3-р сар',
'apr'       => '4-р сар',
'may'       => '5-р сар',
'jun'       => '6-р сар',
'jul'       => '7-р сар',
'aug'       => '8-р сар',
'sep'       => '9-р сар',
'oct'       => '10-р сар',
'nov'       => '11-р сар',
'dec'       => '12-р сар',

'qbedit'     => 'Өөрчлөх',
'mytalk'     => 'Миний яриа',
'navigation' => 'Залуурдах',

'tagline'          => '{{SITENAME}}-с',
'help'             => 'Тусламж',
'search'           => 'Хайлт',
'searchbutton'     => 'Хайх',
'go'               => 'Очих',
'searcharticle'    => 'Явах',
'history_short'    => 'Түүх',
'printableversion' => 'Хэвлэх хувилбар',
'permalink'        => 'Байнгын холбоос',
'edit'             => 'Өөрчлөх',
'protect'          => 'Хамгаал',
'talkpagelinktext' => 'Яриа',
'personaltools'    => 'Өөрийн багаж хэрэгслүүд',
'talk'             => 'Хэлэлцүүлэг',
'views'            => 'Харагдацууд',
'toolbox'          => 'Багаж хэрэгслүүд',
'otherlanguages'   => 'Бусад хэлээр',
'jumptonavigation' => 'Удирдах',
'jumptosearch'     => 'Хайлт',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => '{{SITENAME}}-н тухай',
'aboutpage'      => 'Project:Тухай',
'currentevents'  => 'Сүүлийн үеийн үйл явдлууд',
'disclaimers'    => 'Татгалзлууд',
'disclaimerpage' => 'Project:Ерөнхий татгалзал',
'mainpage'       => 'Нүүр хуудас',
'portal'         => 'Бүлгэмийн портал',
'privacy'        => 'Хувийн мэдээллийн талаарх баримтлал',
'privacypage'    => 'Project:Хувийн мэдээллийн талаарх баримтлал',
'sitesupport'    => 'Хандив',

'retrievedfrom'   => '"$1" хуудаснаас авсан',
'editsection'     => 'засварлах',
'editold'         => 'Өөрчлөх',
'editsectionhint' => 'Хэсгийг засварлах: $1',
'site-rss-feed'   => '$1-н RSS фийд',
'site-atom-feed'  => '$1-н Atom фийд',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Бичлэг or Өгүүлэл',

# Login and logout pages
'yourname'          => 'Хэрэглэгчийн нэр',
'yourpassword'      => 'Нууц үг',
'yourpasswordagain' => 'Нууц үгээ дахин оруул',
'login'             => 'Нэвтрэх',
'userlogin'         => 'Нэвтрэх / Бүртгүүлэх',
'userlogout'        => 'Гарах',
'createaccount'     => 'Бүртгүүлэх',
'youremail'         => 'Мэйл хаяг *',
'email'             => 'Мэйл хаяг',

# Edit pages
'minoredit'    => 'Бичлэгийн агуулгатай холбоогүй өөрчлөлт',
'watchthis'    => 'Энэ хуудсыг хяна',
'showpreview'  => 'Урьдчилан харах',
'showdiff'     => 'Өөрчлөлтийг харуул',
'loginreqlink' => 'Нэвтрэх',

# Search results
'powersearch' => 'Сонгож хайх',

# Preferences page
'preferences' => 'Хэрэглэгчийн тохиргоо',

# Recent changes
'recentchanges' => 'Сүүлд шинэчлэгдсэн',

# Recent changes linked
'recentchangeslinked' => 'Холбогдох өөрчлөлтүүд',

# Upload
'upload'    => 'Файл оруулах',
'uploadbtn' => 'Файл оруулах',

# Random page
'randompage' => 'Дурын хуудас',

# Miscellaneous special pages
'specialpages' => 'Тусгай хуудсууд',
'move'         => 'Зөөх',

'alphaindexline' => '$1-с $2 хүртэл',

# Watchlist
'watchlist'     => 'Миний хянаж буй хуудсууд',
'watch'         => 'Хянах',
'watchthispage' => 'Энэ хуудсыг хяна',

# Contributions
'mycontris' => 'Миний оруулсан хувь нэмэр',

# What links here
'whatlinkshere' => 'Энд холбогдсон хуудсууд',

# Block/unblock
'contribslink' => 'хувь нэмэр',

# Tooltip help for the actions
'tooltip-n-mainpage' => 'Нүүр хуудас руу зочлох',

);
