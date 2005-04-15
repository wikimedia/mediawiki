<?php
#
# In general you should not make customizations in these language files
# directly, but should use the MediaWiki: special namespace to customize
# user interface messages through the wiki.
# See http://meta.wikipedia.org/wiki/MediaWiki_namespace
#
# NOTE TO TRANSLATORS: Do not copy this whole file when making translations!
# A lot of common constants and a base class with inheritable methods are
# defined here, which should not be redefined. See the other LanguageXx.php
# files for examples.
#

#--------------------------------------------------------------------------
# Constants
#--------------------------------------------------------------------------

# Magic words
define("MAG_REDIRECT", 0);
define("MAG_NOTOC", 1);
define("MAG_START", 2);
define("MAG_CURRENTMONTH", 3);
define("MAG_CURRENTMONTHNAME", 4);
define("MAG_CURRENTDAY", 5);
define("MAG_CURRENTDAYNAME", 6);
define("MAG_CURRENTYEAR", 7);
define("MAG_CURRENTTIME", 8);
define("MAG_NUMBEROFARTICLES", 9);
define("MAG_CURRENTMONTHNAMEGEN", 10);
define("MAG_MSG", 11);
define("MAG_SUBST", 12);
define("MAG_MSGNW", 13);
define("MAG_NOEDITSECTION", 14);
define("MAG_END", 15);
define("MAG_IMG_THUMBNAIL",  16);
define("MAG_IMG_RIGHT",      17);
define("MAG_IMG_LEFT",       18);
define("MAG_IMG_NONE",       19);
define("MAG_IMG_WIDTH",      20);
define("MAG_IMG_CENTER",      21);


#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# NOTE: To turn off "Disclaimers" in the title links,
# set "disclaimers" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( " ", "_", $wgSitename );

/* private */ $wgNamespaceNamesEn = array(
	-2	=> "Media",
	-1	=> "Special",
	0	=> "",
	1	=> "Talk",
	2	=> "User",
	3	=> "User_talk",
	4	=> $wgMetaNamespace,
	5	=> $wgMetaNamespace . "_talk",
	6	=> "Image",
	7	=> "Image_talk",
	8	=> "MediaWiki",
	9	=> "MediaWiki_talk"
);

/* private */ $wgDefaultUserOptionsEn = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1,
	"cols" => 80, "rows" => 25, "searchlimit" => 20,
	"contextlines" => 5, "contextchars" => 50,
	"skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
	"highlightbroken" => 1, "stubthreshold" => 0,
	"previewontop" => 1, "editsection"=>1,"editsectiononrightclick"=>0, "showtoc"=>1,
	"showtoolbar" =>1,
	"date" => 0
);

/* private */ $wgQuickbarSettingsEn = array(
	"None", "Fixed left", "Fixed right", "Floating left"
);

/* private */ $wgSkinNamesEn = array(
	"Standard", "Nostalgia", "Cologne Blue", "Paddington", "Montparnasse"
);

/* private */ $wgMathNamesEn = array(
	"Always render PNG",
	"HTML if very simple or else PNG",
	"HTML if possible or else PNG",
	"Leave it as TeX (for text browsers)",
	"Recommended for modern browsers"
);

/* private */ $wgDateFormatsEn = array(
	"No preference",
	"January 15, 2001",
	"15 January 2001",
	"2001 January 15",
	"2001-01-15"
);

/* private */ $wgUserTogglesEn = array(
	"hover"		=> "Show hoverbox over wiki links",
	"underline" => "Underline links",
	"highlightbroken" => "Format broken links <a href=\"\" class=\"new\">like
this</a> (alternative: like this<a href=\"\" class=\"internal\">?</a>).",
	"justify"	=> "Justify paragraphs",
	"hideminor" => "Hide minor edits in recent changes",
	"usenewrc" => "Enhanced recent changes (not for all browsers)",
	"numberheadings" => "Auto-number headings",
	"showtoolbar"=>"Show edit toolbar",
	"editondblclick" => "Edit pages on double click (JavaScript)",
	"editsection"=>"Enable section editing via [edit] links",
	"editsectiononrightclick"=>"Enable section editing by right clicking<br> on section titles (JavaScript)",
	"showtoc"=>"Show table of contents<br>(for articles with more than 3 headings)",
	"rememberpassword" => "Remember password across sessions",
	"editwidth" => "Edit box has full width",
	"watchdefault" => "Add pages you edit to your watchlist",
	"minordefault" => "Mark all edits minor by default",
	"previewontop" => "Show preview before edit box and not after it",
	"nocache" => "Disable page caching"
);

/* private */ $wgBookstoreListEn = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* The following list is in native languages, not in English */
/* private */ $wgLanguageNames = array(
	"aa" => "Afar",			# Afar
	"ab" => "Abkhazian",	# Abkhazian - FIXME
	"af" => "Afrikaans",	# Afrikaans
	"ak" => "Akana",		# Akan
	"als" => "Els&auml;ssisch",	# Alsatian
	"am" => "&#4768;&#4635;&#4653;&#4763;",	# Amharic
	"ar" => "&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;",	# Arabic
	"arc" => "&#1813;&#1829;&#1810;&#1834;&#1848;&#1821;&#1819;",	# Aramaic
	"as" => "&#2437;&#2488;&#2478;&#2496;&#2527;&#2494;",	# Assamese
	"av" => "&#1040;&#1074;&#1072;&#1088;",	# Avar
	"ay" => "Aymar",		# Aymara
	"az" => "Az&#601;rbaycan",	# Azerbaijani
	"ba" => "&#1041;&#1072;&#1096;&#1185;&#1086;&#1088;&#1090;",	# Bashkir
	"be" => "&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1072;&#1103;",	# Belarusian ''or'' Byelarussian
	"bg" => "&#1041;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080;",	# Bulgarian
	"bh"	=> "Bihara",
	"bi" => "Bislama",		# Bislama
	"bn" => "&#2476;&#2494;&#2434;&#2482;&#2494; - (Bangla)",	# Bengali
	"bo" => "Bod skad",		# Tibetan
	"br" => "Brezhoneg",	# Breton 
	"bs" => "Bosanski",		# Bosnian
	"ca" => "Catal&agrave;",	# Catalan
	"ce" => "&#1053;&#1086;&#1093;&#1095;&#1080;&#1081;&#1085;",	# Chechen
	"ch" => "Chamoru",		# Chamorro
	"chy" => "Tsets&ecirc;hest&acirc;hese",	# Cheyenne
	"co" => "Corsu",		# Corsican
	"cr" => "Nehiyaw",		# Cree
	"cs" => "&#268;esky",	# Czech
	"csb" => "Cassubian",	# Cassubian - FIXME
	"cv" => "&#1063;&#1233;&#1074;&#1072;&#1096; - (&#264;&#259;va&#349;)",	# Chuvash 
	"cy" => "Cymraeg",		# Welsh
	"da" => "Dansk",		# Danish
	"de" => "Deutsch",		# German
	"dk" => "Dansk", # 'da' is correct for the language.
	"dv" => "Dhivehi",		# Dhivehi
	"dz" => "(Dzongkha)",	# Bhutani
	"ee" => "Eve",			# Eve
	"el" => "&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;",	# Greek
	"en" => "English",		# English
	"eo" => "Esperanto",	# Esperanto
	"es" => "Espa&ntilde;ol",	# Spanish
	"et" => "Eesti",		# Estonian
	"eu" => "Euskara",		# Basque
	"fa" => "&#1601;&#1575;&#1585;&#1587;&#1740;",	# Persian
	"ff" => "Fulfulde",		# Fulfulde
	"fi" => "Suomi",		# Finnish
	"fj" => "Na Vosa Vakaviti",	# Fijian
	"fo" => "F&oslash;royskt",	# Faroese
	"fr" => "Fran&ccedil;ais",	# French
	"fy" => "Frysk",		# Frisian
	"ga" => "Gaeilge",		# Irish
	"gd" => "G&agrave;idhlig",	# Scots Gaelic
	"gl" => "Galego",		# Gallegan
	"gn" => "Ava&ntilde;e'&#7869;",	# Guarani
	"gu" => "&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;",	# Gujarati 
	"gv" => "Gaelg",		# Manx
	"ha" => "&#1607;&#1614;&#1608;&#1615;&#1587;&#1614;",	# Hausa
	"haw" => "Hawai`i",		# Hawaiian
	"he" => "&#1506;&#1489;&#1512;&#1497;&#1514;",	# Hebrew
	"hi" => "&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;",	# Hindi
	"hr" => "Hrvatski",		# Croatian
	"hu" => "Magyar",		# Hungarian
	"hy" => "&#1344;&#1377;&#1397;&#1381;&#1408;&#1381;&#1398;",	# Armenian
	"hz" => "Otsiherero",	# Herero
	"ia" => "Interlingua",	# Interlingua (IALA)
	"id" => "Bahasa Indonesia",	# Indonesian
	"ie" => "Interlingue",	# Interlingue (Occidental)
	"ig" => "Igbo",			# Igbo
	"ik" => "I&ntilde;upiak",	# Inupiak
	"io" => "Ido",			# Ido
	"is" => "&Iacute;slensk",	# Icelandic
	"it" => "Italiano",		# Italian
	"iu" => "&#5123;&#5316;&#5251;&#5198;&#5200;&#5222;",	# Inuktitut
	"ja" => "&#26085;&#26412;&#35486;",	# Japanese
	"jv" => "Basa Jawa",	# Javanese
	"ka" => "&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;",	# Georgian
	"kk" => "&#1179;&#1072;&#1079;&#1072;&#1179;&#1096;&#1072;",	# Kazakh
	"kl" => "Kalaallisut",	# Greenlandic
	"km" => "&#6039;&#6070;&#6047;&#6070;&#6017;&#6098;&#6040;&#6082;&#6042;",	# Cambodian
	"kn" => "&#3221;&#3240;&#3277;&#3240;&#3233;",	# Kannada
	"ko" => "&#54620;&#44397;&#50612;",	# Korean
	"ks" => "&#2325;&#2358;&#2381;&#2350;&#2368;&#2352;&#2368; - (&#1603;&#1588;&#1605;&#1610;&#1585;&#1610;)",	# Kashmiri 
	"ku" => "Kurd&icirc;",	# Kurdish
	"kw" => "Kernewek",		# Cornish
	"ky" => "Kirghiz",
	"la" => "Latina",		# Latin
	"lb" => "L&euml;tzebuergesch",	# Luxemburguish
	"li" => "Limburgs",		# Limburgian
	"ln" => "Lingala",		# Lingala
	"lo" => "(Pha xa lao)",	# Laotian 
	"lt" => "Lietuvi&#371;",	# Lithuanian
	"lv" => "Latvie&scaron;u",	# Latvian
	"mi" => "Malagasy",		# Malagasy - FIXME
	"mh" => "Ebon",			# Marshallese
	"mi" => "M&#257;ori",	# Maori
	"mk" => "&#1052;&#1072;&#1082;&#1077;&#1076;&#1086;&#1085;&#1089;&#1082;&#1080;",	# Macedonian
	"ml" => "&#3374;&#3378;&#3375;&#3390;&#3379;&#3330;",	# Malayalam
	"mn" => "&#1052;&#1086;&#1085;&#1075;&#1086;&#1083;",	# Mongoloian
	"mo" => "Moldoveana",	# Moldovan
	"mr" => "&#2350;&#2352;&#2366;&#2336;&#2368;",	# Marathi
	"ms" => "Bahasa Melayu",	# Malay
	"mt" => "bil-Malti",	# Maltese
	"my" => "(Myanmasa)",	# Burmese
	"na" => "Nauru",		# Nauruan
	"nah" => "Nahuatl",
	"nds" => "Platd&uuml;&uuml;tsch",	# Low German ''or'' Low Saxon
	"ne" => "&#2344;&#2375;&#2346;&#2366;&#2354;&#2368;",	# Nepali
	"nl" => "Nederlands",	# Dutch
	"nb" => "Norsk",		# Norwegian [currently using old '''no''' code]
	"ne" => "&#2344;&#2375;&#2346;&#2366;&#2354;&#2368;",	# Nepali
	"nn" => "Nynorsk"	,	# (Norwegian) Nynorsk
	"no" => "Norsk",
	"nv" => "Din&eacute; bizaad",	# Navajo
	"ny" => "Chi-Chewa",	# Chichewa
	"oc" => "Occitan",		# Occitan
	"om" => "Oromoo", 		# Oromo
	"or" => "Oriya",		# Oriya - FIXME
	"pa" => "&#2346;&#2306;&#2332;&#2366;&#2348;&#2368; / &#2602;&#2588;&#2622;&#2604;&#2624; / &#1662;&#1606;&#1580;&#1575;&#1576;&#1610;",	# Punjabi
	"pi" => "&#2346;&#2366;&#2367;&#2356;",	# Pali
	"pl" => "Polski",		# Polish
	"ps" => "&#1662;&#1690;&#1578;&#1608;",	# Pashto
	"pt" => "Portugu&ecirc;s",	# Portuguese
	"qu" => "Runa Simi",	# Quechua
	"rm" => "Rumantsch",	# Raeto-Romance
	"rn" => "Kirundi",		# Kirundi
	"ro" => "Rom&acirc;n&#259;",	# Romanian
	"ru" => "&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;",	# Russian
	"rw" => "Kinyarwanda",
	"sa" => "&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;",	# Sanskrit
	"sc" => "Sardu",	# Sardinian
	"sd" => "&#2360;&#2367;&#2344;&#2343;&#2367;",	# Sindhi
	"se" => "S&aacute;megiella",	# (Northern) Sami
	"sg"	=> "Sangro",
#	"sh"	=> "&#1057;&#1088;&#1087;&#1089;&#1082;&#1086;&#1093;&#1088;&#1074;&#1072;&#1090;&#1089;&#1082;&#1080; (Srbskohrvatski)", ## -- Obsolete
	"si" => "(Simhala)",	# Sinhalese
	"simple" => "Simple English",
	"sk" => "Sloven&#269;ina",	# Slovak
	"sl" => "Sloven&scaron;&#269;ina",	# Slovenian
	"sm" => "Gagana Samoa",	# Samoan
	"sn" => "chiShona",		# Shona
	"so" => "Soomaaliga",	# Somali
	"sq" => "Shqip",		# Albanian
	"sr" => "&#1057;&#1088;&#1087;&#1089;&#1082;&#1080; / Srpski",	# Serbian
	"ss" => "SiSwati",		# Swati
	"st" => "seSotho",		# (Southern) Sotho
	"su" => "Basa Sunda",	# Sundanese
	"sv" => "Svenska",		# Swedish
	"sw" => "Kiswahili",	# Swahili
	"ta" => "&#2980;&#2990;&#3007;&#2996;&#3021;",	# Tamil
	"te" => "&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;",	# Telugu
	"tg" => "&#1058;&#1086;&#1207;&#1080;&#1082;&#1251;",	# Tajik
	"th" => "&#3652;&#3607;&#3618;",	# Thai
	"ti" => "Tigrinya",		# Tigrinya - FIXME
	"tk" => "&#1578;&#1585;&#1603;&#1605;&#1606; / &#1058;&#1091;&#1088;&#1082;&#1084;&#1077;&#1085;",	# Turkmen
	"tl" => "Tagalog",		# Tagalog (Filipino)
	"tn" => "Setswana",		# Setswana
	"to" => "Tonga",		# Tonga - FIXME
	"tpi" => "Tok Pisin",	# Tok Pisin
	"tr" => "T&uuml;rk&ccedil;e",	# Turkish
	"ts" => "Xitsonga",		# Tsonga
	"tt" => "Tatar",		# Tatar
	"tw" => "Twi",			# Twi -- FIXME
	"ty" => "Reo M&#257;`ohi",	# Tahitian
	"ug" => "Oyghurque",	# Uyghur
	"uk" => "&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;",	# Ukrainian
	"ur" => "&#1575;&#1585;&#1583;&#1608;",	# Urdu
	"uz" => "&#1038;&#1079;&#1073;&#1077;&#1082;",	# Uzbek
	"ve" => "Venda",		# Venda
	"vi" => "Ti&#7871;ng Vi&#7879;t",	# Vietnamese
	"vo" => "Volap&uuml;k",	# Volapük
	"wa" => "Walon",		# Walloon
	"wo" => "Wollof",		# Wolof
	"xh" => "isiXhosa",		# Xhosan
	"yi" => "&#1497;&#1497;&#1460;&#1491;&#1497;&#1513;",	# Yiddish
	"yo" => "Yor&ugrave;b&aacute;",	# Yoruba
	"za" => "(Cuengh)",		# Zhuang
	"zh" => "&#20013;&#25991;",	# (Zh&#333;ng Wén) - Chinese
	"zh-cn" => "&#20013;&#25991;(&#31616;&#20307;)",	# Simplified
	"zh-tw" => "&#20013;&#25991;(&#32321;&#20307;)",	# Traditional
	"zu" => "isiZulu",		# Zulu
);

$wgLanguageNamesEn =& $wgLanguageNames;


/* private */ $wgWeekdayNamesEn = array(
	"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday",
	"Friday", "Saturday"
);

/* private */ $wgMonthNamesEn = array(
	"January", "February", "March", "April", "May", "June",
	"July", "August", "September", "October", "November",
	"December"
);

/* private */ $wgMonthAbbreviationsEn = array(
	"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug",
	"Sep", "Oct", "Nov", "Dec"
);

/* private */ $wgMagicWordsEn = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    "#redirect"              ),
    MAG_NOTOC                => array( 0,    "__NOTOC__"              ),
    MAG_NOEDITSECTION        => array( 0,    "__NOEDITSECTION__"      ),
    MAG_START                => array( 0,    "__START__"              ),
    MAG_CURRENTMONTH         => array( 1,    "{{CURRENTMONTH}}"       ),
    MAG_CURRENTMONTHNAME     => array( 1,    "{{CURRENTMONTHNAME}}"   ),
    MAG_CURRENTDAY           => array( 1,    "{{CURRENTDAY}}"         ),
    MAG_CURRENTDAYNAME       => array( 1,    "{{CURRENTDAYNAME}}"     ),
    MAG_CURRENTYEAR          => array( 1,    "{{CURRENTYEAR}}"        ),
    MAG_CURRENTTIME          => array( 1,    "{{CURRENTTIME}}"        ),
    MAG_NUMBEROFARTICLES     => array( 1,    "{{NUMBEROFARTICLES}}"   ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    "{{CURRENTMONTHNAMEGEN}}"),
	MAG_MSG                  => array( 0,    "{{MSG:$1}}"             ),
	MAG_SUBST                => array( 0,    "{{SUBST:$1}}"           ),
    MAG_MSGNW                => array( 0,    "{{MSGNW:$1}}"           ),
	MAG_END                  => array( 0,    "__END__"                ),
    MAG_IMG_THUMBNAIL        => array( 1,    "thumbnail", "thumb"     ),
    MAG_IMG_RIGHT            => array( 1,    "right"                  ),
    MAG_IMG_LEFT             => array( 1,    "left"                   ),
    MAG_IMG_NONE             => array( 1,    "none"                   ),
    MAG_IMG_WIDTH            => array( 1,    "$1px"                   ),
    MAG_IMG_CENTER           => array( 1,    "center", "centre"       )

);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesEn = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Set my user preferences",
	"Watchlist"		=> "My watchlist",
	"Recentchanges" => "Recently updated pages",
	"Upload"		=> "Upload image files",
	"Imagelist"		=> "Image list",
	"Listusers"		=> "Registered users",
	"Statistics"	=> "Site statistics",
	"Randompage"	=> "Random article",

	"Lonelypages"	=> "Orphaned articles",
	"Unusedimages"	=> "Orphaned images",
	"Popularpages"	=> "Popular articles",
	"Wantedpages"	=> "Most wanted articles",
	"Shortpages"	=> "Short articles",
	"Longpages"		=> "Long articles",
	"Newpages"		=> "Newly created articles",
	"Ancientpages"	=> "Oldest articles",
        "Deadendpages"  => "Dead-end pages",
#	"Intl"                => "Interlanguage Links",
	"Allpages"		=> "All pages by title",

	"Ipblocklist"	=> "Blocked users/IP addresses",
	"Maintenance"	=> "Maintenance page",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
        "Blockme"       => "",
	"Booksources"	=> "External book sources",
#	"Categories"	=> "Page categories",
	"Export"		=> "XML page export",
	"Version"		=> "Show MediaWiki version",
);

/* private */ $wgSysopSpecialPagesEn = array(
	"Blockip"		=> "Block a user/IP address",
	"Asksql"		=> "Query the database",
	"Undelete"		=> "Restore deleted pages",
	"Makesysop"		=> "Turn a user into a sysop"
);

/* private */ $wgDeveloperSpecialPagesEn = array(
	"Lockdb"		=> "Make database read-only",
	"Unlockdb"		=> "Restore DB write access"
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

/* private */ $wgAllMessagesEn = array(

# Bits of text used by many pages:
#
"categories" => "Page categories",
"category" => "category",
"category_header" => "Articles in category \"$1\"",
"subcategories" => "Subcategories",

"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Main Page",
"mainpagetext"	=> "Wiki software successfully installed.",
"mainpagedocfooter" => "Please see [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]
and the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] for usage and configuration help.",
"about"			=> "About",
"aboutwikipedia" => "About $wgSitename",
"aboutpage"		=> "$wgMetaNamespace:About",
"help"			=> "Help",
"helppage"		=> "$wgMetaNamespace:Help",
"wikititlesuffix" => "$wgSitename",
"bugreports"	=> "Bug reports",
"bugreportspage" => "$wgMetaNamespace:Bug_reports",
"sitesupport"   => "Donations", # Set a URL in $wgSiteSupportPage in LocalSettings.php
"faq"			=> "FAQ",
"faqpage"		=> "$wgMetaNamespace:FAQ",
"edithelp"		=> "Editing help",
"edithelppage"	=> "$wgMetaNamespace:How_does_one_edit_a_page",
"cancel"		=> "Cancel",
"qbfind"		=> "Find",
"qbbrowse"		=> "Browse",
"qbedit"		=> "Edit",
"qbpageoptions" => "This page",
"qbpageinfo"	=> "Context",
"qbmyoptions"	=> "My pages",
"qbspecialpages"	=> "Special pages",
"moredotdotdot"	=> "More...",
"mypage"		=> "My page",
"mytalk"		=> "My talk",
"currentevents" => "Current events",
"disclaimers" => "Disclaimers",
"disclaimerpage"		=> "$wgMetaNamespace:General_disclaimer",
"errorpagetitle" => "Error",
"returnto"		=> "Return to $1.",
"fromwikipedia"	=> "From $wgSitename, the free encyclopedia.",
"whatlinkshere"	=> "Pages that link here",
"help"			=> "Help",
"search"		=> "Search",
"go"		=> "Go",
"history"		=> "Page history",
"printableversion" => "Printable version",
"editthispage"	=> "Edit this page",
"deletethispage" => "Delete this page",
"protectthispage" => "Protect this page",
"unprotectthispage" => "Unprotect this page",
"newpage" => "New page",
"talkpage"		=> "Discuss this page",
"postcomment"   => "Post a comment",
"articlepage"	=> "View article",
"subjectpage"	=> "View subject", # For compatibility
"userpage" => "View user page",
"wikipediapage" => "View meta page",
"imagepage" => 	"View image page",
"viewtalkpage" => "View discussion",
"otherlanguages" => "Other languages",
"redirectedfrom" => "(Redirected from $1)",
"lastmodified"	=> "This page was last modified $1.",
"viewcount"		=> "This page has been accessed $1 times.",
"gnunote" => "All text is available under the terms of the <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(From $wgServer)",
"protectedpage" => "Protected page",
"administrators" => "$wgMetaNamespace:Administrators",
"sysoptitle"	=> "Sysop access required",
"sysoptext"		=> "The action you have requested can only be
performed by users with \"sysop\" status.
See $1.",
"developertitle" => "Developer access required",
"developertext"	=> "The action you have requested can only be
performed by users with \"developer\" status.
See $1.",
"bureaucrattitle"	=> "Bureaucrat access required",
"bureaucrattext"	=> "The action you have requested can only be
performed by sysops with  \"bureaucrat\" status.",
"nbytes"		=> "$1 bytes",
"go"			=> "Go",
"ok"			=> "OK",
"sitetitle"		=> "$wgSitename",
"sitesubtitle"	=> "The Free Encyclopedia",
"retrievedfrom" => "Retrieved from \"$1\"",
"newmessages" => "You have $1.",
"newmessageslink" => "new messages",
"editsection"=>"edit",
"toc" => "Table of contents",
"showtoc" => "show",
"hidetoc" => "hide",
"thisisdeleted" => "View or restore $1?",
"restorelink" => "$1 deleted edits",

# Main script and global functions
#
"nosuchaction"	=> "No such action",
"nosuchactiontext" => "The action specified by the URL is not
recognized by the wiki",
"nosuchspecialpage" => "No such special page",
"nospecialpagetext" => "You have requested a special page that is not
recognized by the wiki.",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Database error",
"dberrortext"	=> "A database query syntax error has occurred.
This could be because of an illegal search query (see $5),
or it may indicate a bug in the software.
The last attempted database query was:
<blockquote><tt>$1</tt></blockquote>
from within function \"<tt>$2</tt>\".
MySQL returned error \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "A database query syntax error has occurred.
The last attempted database query was:
\"$1\"
from within function \"$2\".
MySQL returned error \"$3: $4\".\n",
"noconnect"		=> "Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server.",
"nodb"			=> "Could not select database $1",
"cachederror"		=> "The following is a cached copy of the requested page, and may not be up to date.",
"readonly"		=> "Database locked",
"enterlockreason" => "Enter a reason for the lock, including an estimate
of when the lock will be released",
"readonlytext"	=> "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1",
"missingarticle" => "The database did not find the text of a page
that it should have found, named \"$1\".

<p>This is usually caused by following an outdated diff or history link to a
page that has been deleted.

<p>If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.",
"internalerror" => "Internal error",
"filecopyerror" => "Could not copy file \"$1\" to \"$2\".",
"filerenameerror" => "Could not rename file \"$1\" to \"$2\".",
"filedeleteerror" => "Could not delete file \"$1\".",
"filenotfound"	=> "Could not find file \"$1\".",
"unexpected"	=> "Unexpected value: \"$1\"=\"$2\".",
"formerror"		=> "Error: could not submit form",	
"badarticleerror" => "This action cannot be performed on this page.",
"cannotdelete"	=> "Could not delete the page or image specified. (It may have already been deleted by someone else.)",
"badtitle"		=> "Bad title",
"badtitletext"	=> "The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.",
"perfdisabled" => "Sorry! This feature has been temporarily disabled
because it slows the database down to the point that no one can use
the wiki.",
"perfdisabledsub" => "Here's a saved copy from $1:",
"wrong_wfQuery_params" => "Incorrect parameters to wfQuery()<br>
Function: $1<br>
Query: $2
",
"viewsource" => "View source",
"protectedtext" => "This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[$wgMetaNamespace:Protected page]].

You can view and copy the source of this page:",

# Login and logout pages
#
"logouttitle"	=> "User logout",
"logouttext" => "You are now logged out.
You can continue to use $wgSitename anonymously, or you can log in
again as the same or as a different user. Note that some pages may
continue to be displayed as if you were still logged in, until you clear
your browser cache\n",

"welcomecreation" => "<h2>Welcome, $1!</h2><p>Your account has been created.
Don't forget to change your $wgSitename preferences.",

"loginpagetitle" => "User login",
"yourname"		=> "Your user name",
"yourpassword"	=> "Your password",
"yourpasswordagain" => "Retype password",
"newusersonly"	=> " (new users only)",
"remembermypassword" => "Remember my password across sessions.",
"loginproblem"	=> "<b>There has been a problem with your login.</b><br>Try again!",
"alreadyloggedin" => "<font color=red><b>User $1, you are already logged in!</b></font><br>\n",

"login"			=> "Log in",
"loginprompt"           => "You must have cookies enabled to log in to $wgSitename.",
"userlogin"		=> "Log in",
"logout"		=> "Log out",
"userlogout"	=> "Log out",
"notloggedin"	=> "Not logged in",
"createaccount"	=> "Create new account",
"createaccountmail"	=> "by eMail",
"badretype"		=> "The passwords you entered do not match.",
"userexists"	=> "The user name you entered is already in use. Please choose a different name.",
"youremail"		=> "Your e-mail*",
"yournick"		=> "Your nickname (for signatures)",
"emailforlost"	=> "* Entering an email address is optional.  But it enables people to
contact you through the website without you having to reveal your 
email address to them, and it also helps you if you forget your   
password.",
"loginerror"	=> "Login error",
"nocookiesnew"	=> "The user account was created, but you are not logged in. $wgSitename uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.",
"nocookieslogin"	=> "$wgSitename uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
"noname"		=> "You have not specified a valid user name.",
"loginsuccesstitle" => "Login successful",
"loginsuccess"	=> "You are now logged in to $wgSitename as \"$1\".",
"nosuchuser"	=> "There is no user by the name \"$1\".
Check your spelling, or use the form below to create a new user account.",
"wrongpassword"	=> "The password you entered is incorrect. Please try again.",
"mailmypassword" => "Mail me a new password",
"passwordremindertitle" => "Password reminder from $wgSitename",
"passwordremindertext" => "Someone (probably you, from IP address $1)
requested that we send you a new $wgSitename login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
"noemail"		=> "There is no e-mail address recorded for user \"$1\".",
"passwordsent"	=> "A new password has been sent to the e-mail address
registered for \"$1\".
Please log in again after you receive it.",
"loginend"		=> "&nbsp;",

# Edit page toolbar
"bold_sample"=>"Bold text",
"bold_tip"=>"Bold text",
"italic_sample"=>"Italic text",
"italic_tip"=>"Italic text",
"link_sample"=>"Link title",
"link_tip"=>"Internal link",
"extlink_sample"=>"http://www.example.com link title",
"extlink_tip"=>"External link (remember http:// prefix)",
"headline_sample"=>"Headline text",
"headline_tip"=>"Level 2 headline",
"math_sample"=>"Insert formula here",
"math_tip"=>"Mathematical formula (LaTeX)",
"nowiki_sample"=>"Insert non-formatted text here",
"nowiki_tip"=>"Ignore wiki formatting",
"image_sample"=>"Example.jpg",
"image_tip"=>"Embedded image",
"media_sample"=>"Example.mp3",
"media_tip"=>"Media file link",
"sig_tip"=>"Your signature with timestamp",
"hr_tip"=>"Horizontal line (use sparingly)",
"infobox"=>"Click a button to get an example text",

# Edit pages
#
"summary"		=> "Summary",
"subject"		=> "Subject/headline",
"minoredit"		=> "This is a minor edit",
"watchthis"		=> "Watch this article",
"savearticle"	=> "Save page",
"preview"		=> "Preview",
"showpreview"	=> "Show preview",
"blockedtitle"	=> "User is blocked",
"blockedtext"	=> "Your user name or IP address has been blocked by $1.
The reason given is this:<br>''$2''<p>You may contact $1 or one of the other
[[$wgMetaNamespace:Administrators|administrators]] to discuss the block.

Note that you may not use the \"email this user\" feature unless you have a valid email address registered in your [[Special:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.
",
"whitelistedittitle" => "Login required to edit",
"whitelistedittext" => "You have to [[Special:Userlogin|login]] to edit articles.",
"whitelistreadtitle" => "Login required to read",
"whitelistreadtext" => "You have to [[Special:Userlogin|login]] to read articles.",
"whitelistacctitle" => "You are not allowed to create an account",
"whitelistacctext" => "To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.",
"loginreqtitle" => "Login Required",
"loginreqtext"  => "You must [[special:Userlogin|login]] to view other pages.",
"accmailtitle" => "Password sent.",
"accmailtext" => "The Password for '$1' has been sent to $2.",
"newarticle"	=> "(New)",
"newarticletext" =>
"You've followed a link to a page that doesn't exist yet.
To create the page, start typing in the box below 
(see the [[$wgMetaNamespace:Help|help page]] for more info).
If you are here by mistake, just click your browser's '''back''' button.",
"anontalkpagetext" => "---- ''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
"noarticletext" => "(There is currently no text in this page)",
"updated"		=> "(Updated)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Remember that this is only a preview, and has not yet been saved!",
"previewconflict" => "This preview reflects the text in the upper
text editing area as it will appear if you choose to save.",
"editing"		=> "Editing $1",
"sectionedit"	=> " (section)",
"commentedit"	=> " (comment)",
"editconflict"	=> "Edit conflict: $1",
"explainconflict" => "Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".\n<p>",
"yourtext"		=> "Your text",
"storedversion" => "Stored version",
"editingold"	=> "<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>\n",
"yourdiff"		=> "Differences",
# FIXME: This is inappropriate for third-party use!
"copyrightwarning" => "Please note that all contributions to $wgSitename are
considered to be released under the GNU Free Documentation License
(see $1 for details).
If you don't want your writing to be edited mercilessly and redistributed
at will, then don't submit it here.<br>
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource.
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>",
"longpagewarning" => "WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.",
"readonlywarning" => "WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.",
"protectedpagewarning" => "WARNING:  This page has been locked so that only
users with sysop privileges can edit it. Be sure you are following the
<a href='/wiki/$wgMetaNamespace:Protected_page_guidelines'>protected page
guidelines</a>.",

# History pages
#
"revhistory"	=> "Revision history",
"nohistory"		=> "There is no edit history for this page.",
"revnotfound"	=> "Revision not found",
"revnotfoundtext" => "The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.\n",
"loadhist"		=> "Loading page history",
"currentrev"	=> "Current revision",
"revisionasof"	=> "Revision as of $1",
"cur"			=> "cur",
"next"			=> "next",
"last"			=> "last",
"orig"			=> "orig",
"histlegend"	=> "Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit",

# Diffs
#
"difference"	=> "(Difference between revisions)",
"loadingrev"	=> "loading revision for diff",
"lineno"		=> "Line $1:",
"editcurrent"	=> "Edit the current version of this page",

# Search results
#
"searchresults" => "Search results",
"searchhelppage" => "$wgMetaNamespace:Searching",
"searchingwikipedia" => "Searching $wgSitename",
"searchresulttext" => "For more information about searching $wgSitename, see $1.",
"searchquery"	=> "For query \"$1\"",
"badquery"		=> "Badly formed search query",
"badquerytext"	=> "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query.",
"matchtotals"	=> "The query \"$1\" matched $2 article titles
and the text of $3 articles.",
"nogomatch" => "No page with this exact title exists, trying full text search.",
"titlematches"	=> "Article title matches",
"notitlematches" => "No article title matches",
"textmatches"	=> "Article text matches",
"notextmatches"	=> "No article text matches",
"prevn"			=> "previous $1",
"nextn"			=> "next $1",
"viewprevnext"	=> "View ($1) ($2) ($3).",
"showingresults" => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
"showingresultsnum" => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",
"powersearch" => "Search",
"powersearchtext" => "
Search in namespaces :<br>
$1<br>
$2 List redirects &nbsp; Search for $3 $9",
"searchdisabled" => "<p>Sorry! Full text search has been disabled temporarily, for performance reasons. In the meantime, you can use the Google search below, which may be out of date.</p>",
"googlesearch" => "
<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{$wgServer}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
"blanknamespace" => "(Main)",

# Preferences page
#
"preferences"	=> "Preferences",
"prefsnologin" => "Not logged in",
"prefsnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to set user preferences.",
"prefslogintext" => "You are logged in as \"$1\".
Your internal ID number is $2.

See [[$wgMetaNamespace:User preferences help]] for help deciphering the options.",
"prefsreset"	=> "Preferences have been reset from storage.",
"qbsettings"	=> "Quickbar settings", 
"changepassword" => "Change password",
"skin"			=> "Skin",
"math"			=> "Rendering math",
"dateformat"	=> "Date format",
"math_failure"		=> "Failed to parse",
"math_unknown_error"	=> "unknown error",
"math_unknown_function"	=> "unknown function ",
"math_lexing_error"	=> "lexing error",
"math_syntax_error"	=> "syntax error",
"math_image_error"	=> "PNG conversion failed; check for correct installation of latex, dvips, gs, and convert",
"math_bad_tmpdir"	=> "Can't write to or create math temp directory",
"math_bad_output"	=> "Can't write to or create math output directory",
"math_notexvc"	=> "Missing texvc executable; please see math/README to configure.",
"saveprefs"		=> "Save preferences",
"resetprefs"	=> "Reset preferences",
"oldpassword"	=> "Old password",
"newpassword"	=> "New password",
"retypenew"		=> "Retype new password",
"textboxsize"	=> "Textbox dimensions",
"rows"			=> "Rows",
"columns"		=> "Columns",
"searchresultshead" => "Search result settings",
"resultsperpage" => "Hits to show per page",
"contextlines"	=> "Lines to show per hit",
"contextchars"	=> "Characters of context per line",
"stubthreshold" => "Threshold for stub display",
"recentchangescount" => "Number of titles in recent changes",
"savedprefs"	=> "Your preferences have been saved.",
"timezonetext"	=> "Enter number of hours your local time differs
from server time (UTC).",
"localtime"	=> "Local time display",
"timezoneoffset" => "Offset",
"servertime"	=> "Server time is now",
"guesstimezone" => "Fill in from browser",
"emailflag"		=> "Disable e-mail from other users",
"defaultns"		=> "Search in these namespaces by default:",

# Recent changes
#
"changes" => "changes",
"recentchanges" => "Recent changes",
"recentchangestext" => "Track the most recent changes to the wiki on this page.",
"rcloaderr"		=> "Loading recent changes",
"rcnote"		=> "Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
"rcnotefrom"	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
"rclistfrom"	=> "Show new changes starting from $1",
# "rclinks"		=> "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks"		=> "Show last $1 changes in last $2 days.",
"showhideminor"         => "$1 minor edits | $2 bots | $3 logged in users ",
"rclinks"		=> "Show last $1 changes in last $2 days<br>$3",
"rchide"		=> "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"rcliu"			=> "; $1 edits from logged in users",
"diff"			=> "diff",
"hist"			=> "hist",
"hide"			=> "hide",
"show"			=> "show",
"tableform"		=> "table",
"listform"		=> "list",
"nchanges"		=> "$1 changes",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Upload file",
"uploadbtn"		=> "Upload file",
"uploadlink"	=> "Upload images",
"reupload"		=> "Re-upload",
"reuploaddesc"	=> "Return to the upload form.",
"uploadnologin" => "Not logged in",
"uploadnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to upload files.",
"uploadfile"	=> "Upload images, sounds, documents etc.",
"uploaderror"	=> "Upload error",
"uploadtext"	=> "<strong>STOP!</strong> Before you upload here,
make sure to read and follow the <a href=\"" .
wfLocalUrlE( "$wgMetaNamespace:Image_use_policy" ) . "\">image use policy</a>.
<p>If a file with the name you are specifying already
exists on the wiki, it'll be replaced without warning.
So unless you mean to update a file, it's a good idea
to first check if such a file exists.
<p>To view or search previously uploaded images,
go to the <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">list of uploaded images</a>.
Uploads and deletions are logged on the <a href=\"" .
wfLocalUrlE( "$wgMetaNamespace:Upload_log" ) . "\">upload log</a>.
<p>Use the form below to upload new image files for use in
illustrating your articles.
On most browsers, you will see a \"Browse...\" button, which will
bring up your operating system's standard file open dialog.
Choosing a file will fill the name of that file into the text
field next to the button.
You must also check the box affirming that you are not
violating any copyrights by uploading the file.
Press the \"Upload\" button to finish the upload.
This may take some time if you have a slow internet connection.
<p>The preferred formats are JPEG for photographic images, PNG
for drawings and other iconic images, and OGG for sounds.
Please name your files descriptively to avoid confusion.
To include the image in an article, use a link in the form
<b>[[image:file.jpg]]</b> or <b>[[image:file.png|alt text]]</b>
or <b>[[media:file.ogg]]</b> for sounds.
<p>Please note that as with wiki pages, others may edit or
delete your uploads if they think it serves the encyclopedia, and
you may be blocked from uploading if you abuse the system.",

"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Below is a list of the most recent file uploads.
All times shown are server time (UTC).
<ul>
</ul>
",
"filename"		=> "Filename",
"filedesc"		=> "Summary",
"filestatus" => "Copyright status",
"filesource" => "Source",
"affirmation"	=> "I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.",
"copyrightpage" => "$wgMetaNamespace:Copyrights",
"copyrightpagename" => "$wgSitename copyright",
"uploadedfiles"	=> "Uploaded files",
"noaffirmation" => "You must affirm that your upload does not violate
any copyrights.",
"ignorewarning"	=> "Ignore warning and save file anyway.",
"minlength"		=> "Image names must be at least three letters.",
"badfilename"	=> "Image name has been changed to \"$1\".",
"badfiletype"	=> "\".$1\" is not a recommended image file format.",
"largefile"		=> "It is recommended that images not exceed 100k in size.",
"successfulupload" => "Successful upload",
"fileuploaded"	=> "File \"$1\" uploaded successfully.
Please follow this link: ($2) to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it.",
"uploadwarning" => "Upload warning",
"savefile"		=> "Save file",
"uploadedimage" => "uploaded \"$1\"",
"uploaddisabled" => "Sorry, uploading is disabled.",
				       
# Image list
#
"imagelist"		=> "Image list",
"imagelisttext"	=> "Below is a list of $1 images sorted $2.",
"getimagelist"	=> "fetching image list",
"ilshowmatch"	=> "Show all images with names matching",
"ilsubmit"		=> "Search",
"showlast"		=> "Show last $1 images sorted $2.",
"all"			=> "all",
"byname"		=> "by name",
"bydate"		=> "by date",
"bysize"		=> "by size",
"imgdelete"		=> "del",
"imgdesc"		=> "desc",
"imglegend"		=> "Legend: (desc) = show/edit image description.",
"imghistory"	=> "Image history",
"revertimg"		=> "rev",
"deleteimg"		=> "del",
"imghistlegend" => "Legend: (cur) = this is the current image, (del) = delete
this old version, (rev) = revert to this old version.
<br><i>Click on date to see image uploaded on that date</i>.",
"imagelinks"	=> "Image links",
"linkstoimage"	=> "The following pages link to this image:",
"nolinkstoimage" => "There are no pages that link to this image.",

# Statistics
#
"statistics"	=> "Statistics",
"sitestats"		=> "Site statistics",
"userstats"		=> "User statistics",
"sitestatstext" => "There are <b>$1</b> total pages in the database.
This includes \"talk\" pages, pages about $wgSitename, minimal \"stub\"
pages, redirects, and others that probably don't qualify as articles.
Excluding those, there are <b>$2</b> pages that are probably legitimate
articles.<p>
There have been a total of <b>$3</b> page views, and <b>$4</b> page edits
since the software was upgraded (July 20, 2002).
That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit.",
"userstatstext" => "There are <b>$1</b> registered users.
<b>$2</b> of these are administrators (see $3).",

# Maintenance Page
#
"maintenance"		=> "Maintenance page",
"maintnancepagetext"	=> "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
"maintenancebacklink"	=> "Back to Maintenance Page",
"disambiguations"	=> "Disambiguation pages",
"disambiguationspage"	=> "$wgMetaNamespace:Links_to_disambiguating_pages",
"disambiguationstext"	=> "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br>A page is treated as dismbiguation if it is linked from $1.<br>Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "Double Redirects",
"doubleredirectstext"	=> "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br>\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects"	=> "Broken Redirects",
"brokenredirectstext"	=> "The following redirects link to a non-existing article.",
"selflinks"		=> "Pages with Self Links",
"selflinkstext"		=> "The following pages contain a link to themselves, which they should not.",
"mispeelings"           => "Pages with misspellings",
"mispeelingstext"               => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Missing Language Links",
"missinglanguagelinksbutton"    => "Find missing language links for",
"missinglanguagelinkstext"      => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans"		=> "Orphaned pages",
"lonelypages"	=> "Orphaned pages",
"unusedimages"	=> "Unused images",
"popularpages"	=> "Popular pages",
"nviews"		=> "$1 views",
"wantedpages"	=> "Wanted pages",
"nlinks"		=> "$1 links",
"allpages"		=> "All pages",
"randompage"	=> "Random page",
"shortpages"	=> "Short pages",
"longpages"		=> "Long pages",
"deadendpages"  => "Dead-end pages",				       
"listusers"		=> "User list",
"specialpages"	=> "Special pages",
"spheading"		=> "Special pages for all users",
"sysopspheading" => "For sysop use only",
"developerspheading" => "For developer use only",
"protectpage"	=> "Protect page",
"recentchangeslinked" => "Related changes",
"rclsub"		=> "(to pages linked from \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "New pages",
"ancientpages"		=> "Oldest articles",
"intl"		=> "Interlanguage links",
"movethispage"	=> "Move this page",
"unusedimagestext" => "<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.",
"booksources"	=> "Book sources",
# FIXME: Other sites, of course, may have affiliate relations with the booksellers list
"booksourcetext" => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
$wgSitename is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",
"alphaindexline" => "$1 to $2",
"version"		=> "Version",

# Email this user
#
"mailnologin"	=> "No send address",
"mailnologintext" => "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
and have a valid e-mail address in your <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferences</a>
to send e-mail to other users.",
"emailuser"		=> "E-mail this user",
"emailpage"		=> "E-mail user",
"emailpagetext"	=> "If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the \"From\" address of the mail, so the recipient will be able
to reply.",
"defemailsubject"  => "$wgSitename e-mail",				       
"noemailtitle"	=> "No e-mail address",
"noemailtext"	=> "This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.",
"emailfrom"		=> "From",
"emailto"		=> "To",
"emailsubject"	=> "Subject",
"emailmessage"	=> "Message",
"emailsend"		=> "Send",
"emailsent"		=> "E-mail sent",
"emailsenttext" => "Your e-mail message has been sent.",

# Watchlist
#
"watchlist"			=> "My watchlist",
"watchlistsub"		=> "(for user \"$1\")",
"nowatchlist"		=> "You have no items on your watchlist.",
"watchnologin"		=> "Not logged in",
"watchnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to modify your watchlist.",
"addedwatch"		=> "Added to watchlist",
"addedwatchtext"	=> "The page \"$1\" has been added to your <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">watchlist</a>.
Future changes to this page and its associated Talk page will be listed there,
and the page will appear <b>bolded</b> in the <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">list of recent changes</a> to
make it easier to pick out.</p>

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar.",
"removedwatch"		=> "Removed from watchlist",
"removedwatchtext" 	=> "The page \"$1\" has been removed from your watchlist.",
"watchthispage"		=> "Watch this page",
"unwatchthispage" 	=> "Stop watching",
"notanarticle"		=> "Not an article",
"watchnochange" 	=> "None of your watched items were edited in the time period displayed.",
"watchdetails"		=> "($1 pages watched not counting talk pages;
$2 total pages edited since cutoff;
$3...
<a href='$4'>show and edit complete list</a>.)",
"watchmethod-recent"=> "checking recent edits for watched pages",
"watchmethod-list"	=> "checking watched pages for recent edits",
"removechecked" 	=> "Remove checked items from watchlist",
"watchlistcontains" => "Your watchlist contains $1 pages.",
"watcheditlist"		=> "Here's an alphabetical list of your
watched pages. Check the boxes of pages you want to remove
from your watchlist and click the 'remove checked' button
at the bottom of the screen.",
"removingchecked" 	=> "Removing requested items from watchlist...",
"couldntremove" 	=> "Couldn't remove item '$1'...",
"iteminvalidname" 	=> "Problem with item '$1', invalid name...",
"wlnote" 			=> "Below are the last $1 changes in the last <b>$2</b> hours.",
"wlshowlast" 		=> "Show last $1 hours $2 days $3",
"wlsaved"			=> "This is a saved version of your watchlist.",


# Delete/protect/revert
#
"deletepage"	=> "Delete page",
"confirm"		=> "Confirm",
"excontent" => "content was:",
"exbeforeblank" => "content before blanking was:",
"exblank" => "page was empty",
"confirmdelete" => "Confirm delete",
"deletesub"		=> "(Deleting \"$1\")",
"historywarning" => "Warning: The page you are about to delete has a history: ",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[$wgMetaNamespace:Policy]].",
"confirmcheck"	=> "Yes, I really want to delete this.",
"actioncomplete" => "Action complete",
"deletedtext"	=> "\"$1\" has been deleted.
See $2 for a record of recent deletions.",
"deletedarticle" => "deleted \"$1\"",
"dellogpage"	=> "Deletion_log",
"dellogpagetext" => "Below is a list of the most recent deletions.
All times shown are server time (UTC).
<ul>
</ul>
",
"deletionlog"	=> "deletion log",
"reverted"		=> "Reverted to earlier revision",
"deletecomment"	=> "Reason for deletion",
"imagereverted" => "Revert to earlier version was successful.",
"rollback"		=> "Roll back edits",
"rollbacklink"	=> "rollback",
"rollbackfailed" => "Rollback failed",
"cantrollback"	=> "Cannot revert edit; last contributor is only author of this article.",
"alreadyrolled"	=> "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the article already. 

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ",
#   only shown if there is an edit comment
"editcomment" => "The edit comment was: \"<i>$1</i>\".", 
"revertpage"	=> "Reverted edit of $2, changed back to last version by $1",
"protectlogpage" => "Protection_log",
"protectlogtext" => "Below is a list of page locks/unlocks.
See [[$wgMetaNamespace:Protected page]] for more information.",
"protectedarticle" => "protected [[$1]]",
"unprotectedarticle" => "unprotected [[$1]]",

# Undelete
"undelete" => "Restore deleted page",
"undeletepage" => "View and restore deleted pages",
"undeletepagetext" => "The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.",
"undeletearticle" => "Restore deleted article",
"undeleterevisions" => "$1 revisions archived",
"undeletehistory" => "If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.",
"undeleterevision" => "Deleted revision as of $1",
"undeletebtn" => "Restore!",
"undeletedarticle" => "restored \"$1\"",
"undeletedtext"   => "The article [[$1]] has been successfully restored.
See [[$wgMetaNamespace:Deletion_log]] for a record of recent deletions and restorations.",

# Contributions
#
"contributions"	=> "User contributions",
"mycontris" => "My contributions",
"contribsub"	=> "For $1",
"nocontribs"	=> "No changes were found matching these criteria.",
"ucnote"		=> "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
"uclinks"		=> "View the last $1 changes; view the last $2 days.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "What links here",
"notargettitle" => "No target",
"notargettext"	=> "You have not specified a target page or user
to perform this function on.",
"linklistsub"	=> "(List of links)",
"linkshere"		=> "The following pages link to here:",
"nolinkshere"	=> "No pages link to here.",
"isredirect"	=> "redirect page",

# Block/unblock IP
#
"blockip"		=> "Block user",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[$wgMetaNamespace:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
"ipaddress"		=> "IP Address/username",
"ipbexpiry"		=> "Expiry",
"ipbreason"		=> "Reason",
"ipbsubmit"		=> "Block this user",
"badipaddress"	=> "Invalid IP address",
"noblockreason" => "You must supply a reason for the block.",
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "\"$1\" has been blocked.
<br>See [[Special:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock user",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address or username.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "\"$1\" unblocked",
"ipblocklist"	=> "List of blocked IP addresses and usernames",
"blocklistline"	=> "$1, $2 blocked $3 (expires $4)",
"blocklink"		=> "block",
"unblocklink"	=> "unblock",
"contribslink"	=> "contribs",
"autoblocker"	=> "Autoblocked because you share an IP address with \"$1\". Reason \"$2\".",
"blocklogpage"	=> "Block_log",
"blocklogentry"	=> 'blocked "$1" with an expiry time of $2',
"blocklogtext"	=> "This is a log of user blocking and unblocking actions. Automatically 
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.",
"unblocklogentry"	=> 'unblocked "$1"',
"range_block_disabled"	=> "The sysop ability to create range blocks is disabled.",
"ipb_expiry_invalid"	=> "Expiry time invalid.",
"ip_range_invalid"	=> "Invalid IP range.\n",
"proxyblocker"  => "Proxy blocker",
"proxyblockreason"      => "Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.",
"proxyblocksuccess"     => "Done.\n",

# Developer tools
#
"lockdb"		=> "Lock database",
"unlockdb"		=> "Unlock database",
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm"	=> "Yes, I really want to lock the database.",
"unlockconfirm"	=> "Yes, I really want to unlock the database.",
"lockbtn"		=> "Lock database",
"unlockbtn"		=> "Unlock database",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Database lock succeeded",
"unlockdbsuccesssub" => "Database lock removed",
"lockdbsuccesstext" => "The database has been locked.
<br>Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The database has been unlocked.",

# SQL query
#
"asksql"		=> "SQL query",
"asksqltext"	=> "Use the form below to make a direct query of the
database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlislogged"	=> "Please note that all queries are logged.",
"sqlquery"		=> "Enter query",
"querybtn"		=> "Submit query",
"selectonly"	=> "Only read-only queries are allowed.",
"querysuccessful" => "Query successful",

# Make sysop
"makesysoptitle"	=> "Make a user into a sysop",
"makesysoptext"		=> "This form is used by bureaucrats to turn ordinary users into administrators. 
Type the name of the user in the box and press the button to make the user an administrator",
"makesysopname"		=> "Name of the user:",
"makesysopsubmit"	=> "Make this user into a sysop",
"makesysopok"		=> "<b>User '$1' is now a sysop</b>",
"makesysopfail"		=> "<b>User '$1' could not be made into a sysop. (Did you enter the name correctly?)</b>",
"setbureaucratflag" => "Set bureaucrat flag",
"bureaucratlog"		=> "Bureaucrat_log",
"bureaucratlogentry"	=> " set $1: $2",

# Move page
#
"movepage"		=> "Move page",
"movepagetext"	=> "Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed; be sure to
[[Special:Maintenance|check]] for double or broken redirects.
You are responsible for making sure that links continue to
point where they are supposed to go.

Note that the page will '''not''' be moved if there is already
a page at the new title, unless it is empty or a redirect and has no
past edit history. This means that you can rename a page back to where
it was just renamed from if you make a mistake, and you cannot overwrite
an existing page.

<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.",
"movepagetalktext" => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.",
"movearticle"	=> "Move page",
"movenologin"	=> "Not logged in",
"movenologintext" => "You must be a registered user and <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to move a page.",
"newtitle"		=> "To new title",
"movepagebtn"	=> "Move page",
"pagemovedsub"	=> "Move succeeded",
"pagemovedtext" => "Page \"[[$1]]\" moved to \"[[$2]]\".",
"articleexists" => "A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.",
"talkexists"	=> "The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.",
"movedto"		=> "moved to",
"movetalk"		=> "Move \"talk\" page as well, if applicable.",
"talkpagemoved" => "The corresponding talk page was also moved.",
"talkpagenotmoved" => "The corresponding talk page was <strong>not</strong> moved.",
"1movedto2"		=> "$1 moved to $2",

# Export

"export"		=> "Export pages",
"exporttext"	=> "You can export the text and editing history of a particular
page or set of pages wrapped in some XML; this can then be imported into another
wiki running MediaWiki software, transformed, or just kept for your private
amusement.",
"exportcuronly"	=> "Include only the current revision, not the full history",

# Namespace 8 related

"allmessages"	=> "All_messages",
"allmessagestext"	=> "This is a list of all messages available in the MediaWiki: namespace",

# Thumbnails

"thumbnail-more"	=> "Enlarge",
"missingimage"		=> "<b>Missing image</b><br><i>$1</i>\n"

);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class Language {
	function Language(){
		# Copies any missing values in the specified arrays from En to the current language
		$fillin = array( "wgSysopSpecialPages", "wgValidSpecialPages", "wgDeveloperSpecialPages" );
		$name = get_class( $this );
		if( strpos( $name, "language" ) == 0){
			$lang = ucfirst( substr( $name, 8 ) );
			foreach( $fillin as $arrname ){
				$langver = "{$arrname}{$lang}";
				$enver = "{$arrname}En";
				if( ! isset( $GLOBALS[$langver] ) || ! isset( $GLOBALS[$enver] ))
					continue;
				foreach($GLOBALS[$enver] as $spage => $text){
					if( ! isset( $GLOBALS[$langver][$spage] ) )
						$GLOBALS[$langver][$spage] = $text;
				}
			}
		}
	}

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsEn ;
		return $wgDefaultUserOptionsEn ;
		}

	function getBookstoreList () {
		global $wgBookstoreListEn ;
		return $wgBookstoreListEn ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesEn;
		return $wgNamespaceNamesEn;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesEn;
		return $wgNamespaceNamesEn[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesEn;

		foreach ( $wgNamespaceNamesEn as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function specialPage( $name ) {
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEn;
		return $wgQuickbarSettingsEn;
	}

	function getSkinNames() {
		global $wgSkinNamesEn;
		return $wgSkinNamesEn;
	}

	function getMathNames() {
		global $wgMathNamesEn;
		return $wgMathNamesEn;
	}
	
	function getDateFormats() {
		global $wgDateFormatsEn;
		return $wgDateFormatsEn;
	}

	function getUserToggles() {
		global $wgUserTogglesEn;
		return $wgUserTogglesEn;
	}

	function getLanguageNames() {
		global $wgLanguageNamesEn;
		return $wgLanguageNamesEn;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesEn;
		if ( ! array_key_exists( $code, $wgLanguageNamesEn ) ) {
			return "";
		}
		return $wgLanguageNamesEn[$code];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesEn;
		return $wgMonthNamesEn[$key-1];
	}
	
	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		return $this->getMonthName( $key );
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsEn;
		return $wgMonthAbbreviationsEn[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesEn;
		return $wgWeekdayNamesEn[$key-1];
	}

	function userAdjust( $ts )
	{
		global $wgUser, $wgLocalTZoffset;

		$tz = $wgUser->getOption( "timecorrection" );
		if ( $tz === "" ) {
			$hrDiff = isset( $wgLocalTZoffset ) ? $wgLocalTZoffset : 0;
			$minDiff = 0;		
		} elseif ( strpos( $tz, ":" ) !== false ) {
			$tzArray = explode( ":", $tz );
			$hrDiff = intval($tzArray[0]);
			$minDiff = intval($hrDiff < 0 ? -$tzArray[1] : $tzArray[1]);
		} else {
			$hrDiff = intval( $tz );
		}
		if ( 0 == $hrDiff && 0 == $minDiff ) { return $ts; }

		$t = mktime( ( 
		  (int)substr( $ts, 8, 2) ) + $hrDiff, # Hours
		  (int)substr( $ts, 10, 2 ) + $minDiff, # Minutes
		  (int)substr( $ts, 12, 2 ), # Seconds
		  (int)substr( $ts, 4, 2 ), # Month
		  (int)substr( $ts, 6, 2 ), # Day
		  (int)substr( $ts, 0, 4 ) ); #Year
		return date( "YmdHis", $t );
	}
 
	function date( $ts, $adj = false )
	{
		global $wgAmericanDates, $wgUser, $wgUseDynamicDates;

		if ( $adj ) { $ts = $this->userAdjust( $ts ); }
		
		if ( $wgUseDynamicDates ) {
			$datePreference = $wgUser->getOption( 'date' );		
			if ( $datePreference == 0 ) {
				$datePreference = $wgAmericanDates ? 1 : 2;
			}
		} else {
			$datePreference = $wgAmericanDates ? 1 : 2;
		}
		
		if ( $datePreference == 1 ) {
			# MDY
			$d = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
			  " " . (0 + substr( $ts, 6, 2 )) . ", " .
			  substr( $ts, 0, 4 );
		} else if ( $datePreference == 2 ) {
			#DMY
			$d = (0 + substr( $ts, 6, 2 )) . " " .
			  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
			  substr( $ts, 0, 4 );
		} else {
			#YMD
			$d = substr( $ts, 0, 4 ) . " " . $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
				" " . (0 + substr( $ts, 6, 2 ));
		}

		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->time( $ts, $adj ) . ", " . $this->date( $ts, $adj );
	}

	function rfc1123( $ts )
	{
		return date( "D, d M Y H:i:s T", $ts );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesEn;
		return $wgValidSpecialPagesEn;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesEn;
		return $wgSysopSpecialPagesEn;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesEn;
		return $wgDeveloperSpecialPagesEn;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesEn;
		return $wgAllMessagesEn[$key];
	}
	
	function getAllMessages()
	{
		global $wgAllMessagesEn;
		return $wgAllMessagesEn;
	}

	function iconv( $in, $out, $string ) {
		# For most languages, this is a wrapper for iconv
		return iconv( $in, $out, $string );
	}
	
	function ucfirst( $string ) {
		# For most languages, this is a wrapper for ucfirst()
		return ucfirst( $string );
	}
	
	function lcfirst( $s ) {
		return strtolower( $s{0}  ). substr( $s, 1 );
	}

	function checkTitleEncoding( $s ) {
        global $wgInputEncoding;
		
        # Check for UTF-8 URLs; Internet Explorer produces these if you
		# type non-ASCII chars in the URL bar or follow unescaped links.
        $ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( ($wgInputEncoding != "utf-8") and $ishigh and $isutf )
			return iconv( "UTF-8", $wgInputEncoding, $s );
		
		if( ($wgInputEncoding == "utf-8") and $ishigh and !$isutf )
			return utf8_encode( $s );
		
		# Other languages can safely leave this function, or replace
		# it with one to detect and convert another legacy encoding.
		return $s;
	}
	
	function stripForSearch( $in ) {
		# Some languages have special punctuation to strip out
		# or characters which need to be converted for MySQL's
		# indexing to grok it correctly. Make such changes here.
		return $in;
	}


	function setAltEncoding() {
		# Some languages may have an alternate char encoding option
		# (Esperanto X-coding, Japanese furigana conversion, etc)
		# If 'altencoding' is checked in user prefs, this gives a
		# chance to swap out the default encoding settings.
		#global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
	}

	function recodeForEdit( $s ) {
		# For some languages we'll want to explicitly specify
		# which characters make it into the edit box raw
		# or are converted in some way or another.
		# Note that if wgOutputEncoding is different from
		# wgInputEncoding, this text will be further converted
		# to wgOutputEncoding.
		global $wgInputEncoding, $wgEditEncoding;
		if( $wgEditEncoding == "" or
		  $wgEditEncoding == $wgInputEncoding ) {
			return $s;
		} else {
			return $this->iconv( $wgInputEncoding, $wgEditEncoding, $s );
		}
	}

	function recodeInput( $s ) {
		# Take the previous into account.
		global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
		if($wgEditEncoding != "") {
			$enc = $wgEditEncoding;
		} else {
			$enc = $wgOutputEncoding;
		}
		if( $enc == $wgInputEncoding ) {
			return $s;
		} else {
			return $this->iconv( $enc, $wgInputEncoding, $s );
		}
	}

	# For right-to-left language support
	function isRTL() { return false; }

	# To allow "foo[[bar]]" to extend the link over the whole word "foobar"
	function linkPrefixExtension() { return false; }


	function getMagicWords() 
	{
		global $wgMagicWordsEn;
		return $wgMagicWordsEn;
	}

	# Fill a MagicWord object with data from here
	function getMagic( &$mw )
	{
		$raw = $this->getMagicWords(); # don't worry, it's reference counted not deep copy
		$rawEntry = $raw[$mw->mId];
		$mw->mCaseSensitive = $rawEntry[0];
		$mw->mSynonyms = array_slice( $rawEntry, 1 );
	}

	# Italic is unsuitable for some languages
	function emphasize( $text )
	{
		return "<em>$text</em>";
	}
}

@include_once( "Language" . ucfirst( $wgLanguageCode ) . ".php" );

?>
