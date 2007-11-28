<?php
/** Malayalam (മലയാളം)
  *
  * @addtogroup Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @author Sadik Khalid <sadik.khalid@gmail.com>
  * @author Manjith Joseph <manjithkaini@gmail.com>
  * @author Praveen Prakash <me.praveen@gmail.com>
  * @author Shiju Alex <shijualexonline@gmail.com>
  */

$namespaceNames = array(
	NS_MEDIA => 'മീഡിയ',
	NS_SPECIAL => 'പ്രത്യേകം',
	NS_MAIN => '',
	NS_TALK => 'സംവാദം',
	NS_USER => 'ഉപയോക്താവ്',
	NS_USER_TALK => 'ഉപയോക്താവിന്റെ_സംവാദം',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK => '$1_സംവാദം',
	NS_IMAGE => 'ചിത്രം',
	NS_IMAGE_TALK => 'ചിത്രത്തിന്റെ_സംവാദം',
	NS_MEDIAWIKI => 'മീഡിയവിക്കി',
	NS_MEDIAWIKI_TALK => 'മീഡിയവിക്കി_സംവാദം',
	NS_TEMPLATE => 'ഫലകം',
	NS_TEMPLATE_TALK => 'ഫലകത്തിന്റെ_സംവാദം',
	NS_CATEGORY => 'വിഭാഗം',
	NS_CATEGORY_TALK => 'വിഭാഗത്തിന്റെ_സംവാദം',
	NS_HELP => 'സഹായം',
	NS_HELP_TALK => 'സഹായത്തിന്റെ_സംവാദം',
);

$namespaceAliases = array(
	"അംഗം" 			=> NS_USER,
	"അംഗങ്ങളുടെ സംവാദം" 		=> NS_USER_TALK,
);

$skinNames = array(
	'standard'      => 'സാര്‍വത്രികം',
	'simple'        => 'ലളിതം',
	'nostalgia'     => 'ഗൃഹാതുരത്വം',
	'cologneblue'   => 'ക്ലോണ്‍ നീല',
	'monobook'      => 'മോണോബുക്ക്',
	'chick'         => 'സുന്ദരി',
);

/**
 * Magic words
 * Customisable syntax for wikitext and elsewhere
 *
 * Note to translators:
 *   Please include the English words as synonyms.  This allows people
 *   from other wikis to contribute more easily.
 *
 * This array can be modified at runtime with the LanguageGetMagic hook
 */
$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0, '#REDIRECT' , '#തിരിച്ചുവിടുക' , 'തിരിച്ചുവിടല്‍' ),
	'notoc'                  => array( 0, '__NOTOC__' , '__ഉള്ളടക്കംവേണ്ട__' ),
	'nogallery'              => array( 0, '__NOGALLERY__' , '__ചിത്രസഞ്ചയംവേണ്ട__' ),
	'forcetoc'               => array( 0, '__FORCETOC__' , '__ഉള്ളടക്കംഇടുക__' ),
	'toc'                    => array( 0, '__TOC__' , '__ഉള്ളടക്കം__' ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__' , '__സംശോധിക്കേണ്ട__' ),
	'start'                  => array( 0, '__START__' , '__തുടക്കം__' , '__പ്രാരംഭം__' ),
	
	'currentmonth'           => array( 1,'CURRENTMONTH' , 'മാസം' ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME' , 'മാസത്തിന്റെപേര്‌' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV'  , 'മാസത്തിന്റെപേര്‌സംഗ്രഹം' , 'മാസത്തിന്റെപേര്‌ചുരുക്കം' ),
	'currentday'             => array( 1, 'CURRENTDAY' , 'ദിവസം' ),
	'currentday2'            => array( 1, 'CURRENTDAY2' , 'ദിവസം2' ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME' , 'ദിവസത്തിന്റെപേര്‌' ),
	'currentyear'            => array( 1, 'CURRENTYEAR' , 'വര്‍ഷം' ),
	'currenttime'            => array( 1, 'CURRENTTIME' , 'സമയം' ),
	'currenthour'            => array( 1, 'CURRENTHOUR' , 'മണിക്കൂര്‍' ),
	
	'localmonth'             => array( 1, 'LOCALMONTH' , 'പ്രാദേശികമാസം' ),
	'localmonthname'         => array( 1, 'LOCALMONTHNAME' , 'പ്രാദേശികമാസത്തിന്റെപേര്‌' ),
	'localmonthabbrev'       => array( 1, 'LOCALMONTHABBREV' , 'പ്രാദേശികമാസത്തിന്റെപേര്‌സംഗ്രഹം' ,  'പ്രാദേശികമാസത്തിന്റെപേര്‌ചുരുക്കം' ),
	'localday'               => array( 1, 'LOCALDAY' , 'പ്രാദേശികദിവസം' ),
	'localday2'              => array( 1, 'LOCALDAY2' , 'പ്രാദേശികദിവസം2' ),
	'localdayname'           => array( 1, 'LOCALDAYNAME' , 'പ്രാദേശികദിവസത്തിന്റെപേര്‌' ),
	'localyear'              => array( 1, 'LOCALYEAR' , 'പ്രാദേശികവര്‍ഷം' ),
	'localtime'              => array( 1, 'LOCALTIME' , 'പ്രാദേശികസമയം' ),
	'localhour'              => array( 1, 'LOCALHOUR' , '', 'പ്രാദേശികമണിക്കൂര്‍' ),

	'numberofpages'          => array( 1, 'NUMBEROFPAGES' , 'താളുകളുടെഎണ്ണം' ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES' , 'ലേഖങ്ങളുടെഎണ്ണം' ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES' , 'പ്രമാണങ്ങളുടെഎണ്ണം'),
	'numberofusers'          => array( 1, 'NUMBEROFUSERS' , 'ഉപയോക്താക്കളുടെഎണ്ണം' , 'അംഗങ്ങളുടെഎണ്ണം' ),

	'pagename'               => array( 1, 'PAGENAME' , 'താളിന്റെപേര്‌' ),
	'pagenamee'              => array( 1, 'PAGENAMEE' , 'താളിന്റെപേര്‌സമഗ്രം' ),
	'namespace'              => array( 1, 'NAMESPACE' , 'നാമമേഖല' ),
	'namespacee'             => array( 1, 'NAMESPACEE' , 'നാമമേഖലസമഗ്രം' ),
	'talkspace'              => array( 1, 'TALKSPACE' , 'സംവാദമേഖല' ),
	'talkspacee'             => array( 1, 'TALKSPACEE' , 'സംവാദമേഖലസമഗ്രം' ),
	'subjectspace'           => array( 1, 'SUBJECTSPACE', 'ARTICLESPACE'  , 'വിഷയമേഖല' , 'ലേഖനമേഖല' ),
	'subjectspacee'          => array( 1, 'SUBJECTSPACEE', 'ARTICLESPACEE'  , 'വിഷയമേഖലസമഗ്രം'  , 'ലേഖനമേഖലസമഗ്രം' ),
	'fullpagename'           => array( 1, 'FULLPAGENAME' , 'താളിന്റെമുഴുവന്‍പേര്‌' ),
	'fullpagenamee'          => array( 1, 'FULLPAGENAMEE'  , 'താളിന്റെമുഴുവന്‍പേര്സമഗ്രം'  ),
	'subpagename'            => array( 1, 'SUBPAGENAME' , 'അനുബന്ധതാളിന്റെപേര്‌'),
	'subpagenamee'           => array( 1, 'SUBPAGENAMEE'  , 'അനുബന്ധതാളിന്റെപേര്സമഗ്രം' ),
	'basepagename'           => array( 1, 'BASEPAGENAME' , 'അടിസ്ഥാനതാളിന്റെപേര്‌' ),
	'basepagenamee'          => array( 1, 'BASEPAGENAMEE'  , 'അടിസ്ഥാനതാളിന്റെപേര്‌സമഗ്രം' ),
	'talkpagename'           => array( 1, 'TALKPAGENAME' , 'സംവാദതാളിന്റെപേര്‌'),
	'talkpagenamee'          => array( 1, 'TALKPAGENAMEE' , 'സംവാദതാളിന്റെപേര്‌സമഗ്രം' ),
	'subjectpagename'        => array( 1, 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' , 'ലേഖനതാളിന്റെപേര്‌' ),
	'subjectpagenamee'       => array( 1, 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' , 'ലേഖനതാളിന്റെപേര്‌സമഗ്രം' ),

	'msg'                    => array( 0, 'MSG:' , 'സന്ദേശം:' ),
	'subst'                  => array( 0, 'SUBST:' , 'ബദല്‍:' ),
	'msgnw'                  => array( 0, 'MSGNW:' , 'മൂലരൂപം:' ),
	'end'                    => array( 0, '__END__' , '__ശുഭം__' ),

	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb' , 'ലഘുചിത്രം' , 'ലഘു' ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1' , 'ലഘുചിത്രം=$1' , 'ലഘു=$1' ),
	'img_right'              => array( 1, 'right' , 'വലത്ത്‌' , 'വലത്‌' ),
	'img_left'               => array( 1, 'left' , 'ഇടത്ത്‌' , 'ഇടത്‌' ),
	'img_none'               => array( 1, 'none' , 'ശൂന്യം' ),
	'img_width'              => array( 1, '$1px' , '$1ബിന്ദു' ),
	'img_center'             => array( 1, 'center', 'centre' , 'നടുവില്‍' , 'നടുക്ക്‌' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame' , 'ചട്ടം', 'ചട്ടത്തില്‍' ),
	'img_page'               => array( 1, 'page=$1', 'page $1', 'താള്‍=$1', 'താള്‍ $1' ),

	'sitename'               => array( 1, 'SITENAME' ,'സൈറ്റിന്റെപേര്' ),
	'ns'                     => array( 0, 'NS:' , 'നാമേ:' , 'നാമമേഖല' ),
	'localurl'               => array( 0, 'LOCALURL:' , 'ലോക്കല്‍യുആര്‍എല്‍:' ),
	'localurle'              => array( 0, 'LOCALURLE:' , 'ലോക്കല്‍യുആര്‍എല്‍ഇ:' ),
	'server'                 => array( 0, 'SERVER' , 'സെര്‍വര്‍' ),
	'servername'             => array( 0, 'SERVERNAME' , 'സെര്‍വറിന്റെപേര്' ),
	'scriptpath'             => array( 0, 'SCRIPTPATH' ,'സ്ക്രിപ്റ്റ്പാത്ത്'),
	'grammar'                => array( 0, 'GRAMMAR:' , 'വ്യാകരണം:' ),

	'currentweek'            => array( 1, 'CURRENTWEEK' , 'ആഴ്ച' ,'ആഴ്‌ച' ),
	'currentdow'             => array( 1, 'CURRENTDOW', 'ദിവസത്തിന്റെപേര്‌അക്കത്തില്‍' ),
	'localweek'              => array( 1, 'LOCALWEEK' , 'പ്രാദേശികആഴ്ച' , 'പ്രാദേശികആഴ്‌ച' ),
	'localdow'               => array( 1, 'LOCALDOW' , 'ആഴ്ചയുടെപേര്‌അക്കത്തില്‍' , 'ആഴ്‌ചയുടെപേര്‌അക്കത്തില്‍' ),

	'revisionid'             => array( 1, 'REVISIONID' , 'തിരുത്തല്‍അടയാളം' ),
	'revisionday'            => array( 1, 'REVISIONDAY' , 'തിരുത്തിയദിവസം' , 'തിരുത്തിയദിനം' ),
	'revisionday2'           => array( 1, 'REVISIONDAY2' , 'തിരുത്തിയദിവസം2' , 'തിരുത്തിയദിനം2'),
	'revisionmonth'          => array( 1, 'REVISIONMONTH' , 'തിരുത്തിയമാസം'),
	'revisionyear'           => array( 1, 'REVISIONYEAR' , 'തിരുത്തിയവര്‍ഷം' ),
	'revisiontimestamp'      => array( 1, 'REVISIONTIMESTAMP' , 'തിരുത്തിയസമയമുദ്ര' ),
	
	'plural'                 => array( 0, 'PLURAL:' , 'ബഹുവചനം:'),
	'raw'                    => array( 0, 'RAW:' , 'വരി:' ),
	'displaytitle'           => array( 1, 'DISPLAYTITLE' , 'ശീര്‍ഷകംപ്രദര്‍ശിപ്പിക്കുക' , 'തലക്കെട്ട്പ്രദര്‍ശിപ്പിക്കുക'  ),
	'rawsuffix'              => array( 1, 'R' , 'വ' ),
	'newsectionlink'         => array( 1, '__NEWSECTIONLINK__' , '__പുതിയവിഭാഗംകണ്ണി__', '__പുതിയഖണ്ഡിക്കണ്ണി__'),
	
	'currentversion'         => array( 1, 'CURRENTVERSION' , 'പതിപ്പ്' ),
	'currenttimestamp'       => array( 1, 'CURRENTTIMESTAMP' , 'സമയമുദ്ര' ),
	'localtimestamp'         => array( 1, 'LOCALTIMESTAMP' , 'പ്രാദേശികസമയമുദ്ര' ),
	'directionmark'          => array( 1, 'DIRECTIONMARK', 'DIRMARK' , 'ദിശാ‍അടയാളം' ),
	'language'               => array( 0, '#LANGUAGE:' , '#ഭാഷ:' ),
	'contentlanguage'        => array( 1, 'CONTENTLANGUAGE', 'CONTENTLANG' , 'ഉള്ളടക്കഭാഷ' ),
	'pagesinnamespace'       => array( 1, 'PAGESINNAMESPACE:', 'PAGESINNS:' , 'നാമമേഖലയിലുള്ളതാളുകള്‍' ),
	'numberofadmins'         => array( 1, 'NUMBEROFADMINS'  , 'കാര്യനിര്‍വ്വാഹകരുടെഎണ്ണം' ),
	'formatnum'              => array( 0, 'FORMATNUM' , 'ദശാംശഘടന' , 'സംഖ്യാഘടന' ),
	'padleft'                => array( 0, 'PADLEFT' , 'ഇടത്ത്നിറക്കുക' ),
	'padright'               => array( 0, 'PADRIGHT' , 'വലത്ത്നിറക്കുക' ),
	'special'                => array( 0, 'special' , 'പ്രത്യേകം' ),
);

$messages = array(
# Dates
'sunday'    => 'ഞായര്‍',
'monday'    => 'തിങ്കള്‍',
'tuesday'   => 'ചൊവ്വ',
'wednesday' => 'ബുധന്‍',
'thursday'  => 'വ്യാഴം',
'friday'    => 'വെള്ളി',
'saturday'  => 'ശനി',
'sun'       => 'ഞാ.',
'mon'       => 'തി.',
'tue'       => 'ചൊ.',
'wed'       => 'ബു.',
'thu'       => 'വ്യാ.',
'fri'       => 'വെ.',
'sat'       => 'ശ.',
'january'   => 'ജനുവരി',
'february'  => 'ഫെബ്രുവരി',
'march'     => 'മാര്‍ച്ച്‌',
'april'     => 'ഏപ്രില്‍',
'may_long'  => 'മേയ്‌',
'june'      => 'ജൂണ്‍',
'july'      => 'ജൂലൈ',
'august'    => 'ഓഗസ്റ്റ്‌',
'september' => 'സെപ്റ്റംബര്‍',
'october'   => 'ഒക്ടോബര്‍',
'november'  => 'നവംബര്‍',
'december'  => 'ഡിസംബര്‍',
'jan'       => 'ജനു.',
'feb'       => 'ഫെബ്രു.',
'mar'       => 'മാര്‍.',
'apr'       => 'ഏപ്രി.',
'may'       => 'മേയ്‌',
'jun'       => 'ജൂണ്‍',
'jul'       => 'ജൂലൈ',
'aug'       => 'ഓഗ.',
'sep'       => 'സെപ്റ്റം.',
'oct'       => 'ഒക്ടോ‍.‍',
'nov'       => 'നവം.',
'dec'       => 'ഡിസം.',

# Auto-summaries
'autosumm-blank'   => 'താളിലെ എല്ലാവിവരങ്ങളും നീക്കം ചെയ്യുന്നു',
'autosumm-replace' => 'താളിലെ വിവരങ്ങള്‍ $1 എന്നാക്കിയിരിക്കുന്നു',
'autoredircomment' => '[[$1]]-ലേക്ക് തിരിച്ചുവിടുന്നു',
'autosumm-new'     => 'പുതിയ താള്‍: $1',

);
