<?

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

## 1 De fariabelen $wgSitename en $wgServer binne foarôf beskikber.
## 2 Yn de tekst binne alle bysûndere letters troch teken-omskriuwings ferfongen.
##    Sa hawwe âlde blêdzjers it minst lêst. Utsûndering is de nammeromte dy't
##    faaks net werkent wurde soe nei sa'n feroaring.

include_once( "LanguageUtf8.php" );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( " ", "_", $wgSitename );

/* private */ $wgNamespaceNamesFy = array(
        -2      => "Media",
	-1	=> "Wiki",
	0	=> "",
	1	=> "Oerlis",
	2	=> "Brûker",
	3	=> "Brûker_oerlis",
	4	=> $wgMetaNamespace,
	5	=> $wgMetaNamespace . "_oerlis",
	6	=> "Ofbyld",
	7	=> "Ofbyld_oerlis",
	8	=> "MediaWiki",
	9	=> "MediaWiki_oerlis"

);

/* private */ $wgDefaultUserOptionsFy = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1,
	"cols" => 80, "rows" => 25, "searchlimit" => 20,
	"contextlines" => 5, "contextchars" => 50,
	"skin" => 0, "math" => 1, "rcdays" => 7, "rclimit" => 50,
	"highlightbroken" => 0, "stubthreshold" => 250,
	"previewontop" => 1, "editsection"=>0, "editsectiononrightclick"=>0, "showtoc"=>1,
	"date" => 2
);

/* private */ $wgQuickbarSettingsFy = array(
	"Ut", "Lofts f&ecirc;st", "Rjochts f&ecirc;st", "Lofsts sweevjend"
);

/* private */ $wgSkinNamesFy = array(
	"Standert", "Nostalgy", "Keuls blau", "Paddington", "Montparnasse"
);

/* private */ $wgMathNamesFy = array(
           "Altiten as PNG &ocirc;fbyldzje",
           "HTML foar ienf&acirc;ldiche formules, oars PNG",
           "HTML as mooglik, oars PNG",
           "Lit de TeX ferzje stean (foar tekstbl&ecirc;dzjers)",
           "Oanbefelle foar resinte bl&ecirc;dzjers"
);

/* private */ $wgDateFormatsFy = array(
	"Gjin foarkar",
	"jannewaris 8, 2001",
	"8 jannewaris 2001",
	"2001 jannewaris 8"
);

/* private */ $wgUserTogglesFy = array(
	"hover"		=> "Wiki-keppelings yn sweeffak sjen litte",
	"underline"		=> "Keppelings &ucirc;nderstreekje",
	"highlightbroken"	=> "Keppelings mei lege siden <a href=\"\" class=\"new\">read</a>
					(oars mei in fraachteken<a href=\"\" class=\"internal\">?</a>).",
	"justify"		=> "Paragrafen &uacute;tfolje",
	"hideminor"		=> "Tekstwizigings wei litte &uacute;t 'Koarts feroare'",
	"usenewrc"		=> "Utwreide ferzje fan 'Koarts feroare' br&ucirc;ke (net mei alle bl&ecirc;dzjers mooglik)",
	"numberheadings"	=> "Koppen fansels n&ucirc;merje",
	"editondblclick"	=> "D&ucirc;belklik jout bewurkingsside (freget JavaScript)",
	"editsection"	=> "Jou [bewurk]-keppelings foar seksjebewurking",
	"editsectiononrightclick" => "Rjochtsklik op sekjsetitels jout seksjebewurking (freget JavaScript)",
 	"showtoc"		=> "Ynh&acirc;ldsopjefte, foar siden mei mear as twa koppen",
	"rememberpassword" => "Oare kear fansels oanmelde",
	"editwidth"		=> "Bewurkingsfjild sa breed as de side",
	"watchdefault"	=> "Sides dy't jo feroare hawwe folgje",
	"minordefault"	=> "Feroarings yn it earst oanjaan as tekstwizigings.",
	"previewontop"	=> "By it neisjen, bewurkingsfjild &ucirc;nderoan sette",
	"nocache"		=> "Gjin oerslag br&ucirc;ke"
);

/* private */ $wgBookstoreListFy = array(
);

/* private */ $wgLanguageNamesFy = array(
	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "&#8238;&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;&#8236; (Araby)",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1080;",
	"bh" => "Bihara",
	"bi" => "Bislama",
	"bn" => "Bengali",
	"bo" => "Tibetan",
	"br" => "Brezhoneg",
	"bs" => "Bosnian",
	"ca" => "Catal&agrave;",
	"ch" => "Chamoru",
	"co" => "Corsican",
	"cs" => "&#268;esk&#225;",
	"cy" => "Cymraeg",
	"da" => "Dansk", # Note two different subdomains.
	"dk" => "Dansk", # 'da' is correct for the language.
	"de" => "Deutsch",
	"dz" => "Bhutani",
	"el" => "&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940; (Ellenika)",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Espa&ntilde;ol",
	"et" => "Eesti",
	"eu" => "Euskara",
	"fa" => "&#8238;&#1601;&#1585;&#1587;&#1609;&#8236; (Farsi)",
	"fi" => "Suomi",
	"fj" => "Fijian",
	"fo" => "Faeroese",
	"fr" => "Fran&ccedil;ais",
	"fy" => "Frysk",
	"ga" => "Gaelige",
	"gd" => "G&agrave;idhlig",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752; (Gujarati)",
	"gv" => "Gaelg",
	"ha" => "Hausa",
	"he" => "&#1506;&#1489;&#1512;&#1497;&#1514; (Ivrit)",
	"hi" => "&#2361;&#2367;&#2344;&#2381;&#2342;&#2368; (Hindi)",
	"hr" => "Hrvatski",
	"hu" => "Magyar",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesia",
	"ik" => "Inupiak",
	"is" => "&#205;slenska",
	"it" => "Italiano",
	"iu" => "Inuktitut",
	"ja" => "&#26085;&#26412;&#35486; (Nihongo)",
	"jv" => "Javanese",
	"ka" => "&#4325;&#4304;&#4320;&#4311;&#4309;&#4308;&#4314;&#4312; (Kartuli)",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "&#3221;&#3240;&#3277;&#3240;&#3233; (Kannada)",
	"ko" => "&#54620;&#44397;&#50612; (Hangukeo)",
	"ks" => "Kashmiri",
	"kw" => "Kernewek",
	"ky" => "Kirghiz",
	"la" => "Latina",
	"ln" => "Lingala",
	"lo" => "Laotian",
	"lt" => "Lietuvi&#371;",
	"lv" => "Latvian",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Bahasa Melayu",
	"my" => "Burmese",
	"na" => "Nauru",
	"nah" => "Nahuatl",
	"nds" => "Plattd&uuml;&uuml;tsch",
	"ne" => "&#2344;&#2375;&#2346;&#2366;&#2354;&#2368; (Nepali)",
	"nl" => "Nederlands",
	"no" => "Norsk",
	"oc" => "Occitan",
	"om" => "Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polski",
	"ps" => "Pashto",
	"pt" => "Portugu&#234;s",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Rom&#226;n&#259;",
	"ru" => "&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081; (Russkij)",
	"rw" => "Kinyarwanda",
	"sa" => "&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340; (Samskrta)",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbocroatian",
	"si" => "Sinhalese",
	"simple" => "Simple English",
	"sk" => "Slovak",
	"sl" => "Slovensko",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Soomaali",
	"sq" => "Shqiptare",
	"sr" => "Srpski",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Svenska",
	"sw" => "Kiswahili",
	"ta" => "&#2980;&#2990;&#3007;&#2996;&#3021; (Tamil)",
	"te" => "&#3108;&#3142;&#3122;&#3137;&#3095;&#3137; (Telugu)",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "T&uuml;rk&ccedil;e",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uighur",
	"uk" => "&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072; (Ukrayins`ka)",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volap&#252;k",
	"wo" => "Wolof",
	"xh" => "isiXhosa",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zhuang",
	"zh" => "&#20013;&#25991; (Zhongwen)",
	"zh-cn" => "&#20013;&#25991;(&#31616;&#20307;) (Simplified Chinese)",
	"zh-tw" => "&#20013;&#25991;(&#32321;&#20307;) (Traditional Chinese)",
	"zu" => "Zulu"
);

/* private */ $wgWeekdayNamesFy = array(
	"snein", "moandei", "tiisdei", "woansdei", "tongersdei",
      "freed", "sneon"
);

/* private */ $wgMonthNamesFy = array(
	 "jannewaris", "febrewaris", "maart", "april", "maaie", "juny",
       "july", "augustus", "septimber", "oktober", "novimber",
       "decimber"
);

/* private */ $wgMonthAbbreviationsFy = array(
       "jan", "feb", "mar", "apr", "mai", "jun", "jul", "aug",
       "sep", "okt", "nov", "dec"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesFy = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Ynstellings",
	"Watchlist"		=> "Folchlist",
	"Recentchanges"   => "Koarts feroare",
	"Upload"		=> "Ofbyld oanbringe",
	"Imagelist"		=> "Ofbyld list",
	"Listusers"		=> "Bekinde br&ucirc;kers",
	"Statistics"	=> "Statistyk",
	"Randompage"	=> "Samar in side",

	"Lonelypages"	=> "Lossteande siden",
	"Unusedimages"	=> "Lossteande &ocirc;fbylden",
	"Popularpages"	=> "Grage siden",
	"Wantedpages"	=> "Nedige siden",
	"Shortpages"	=> "Koarte siden",
	"Longpages"		=> "Lange siden",
	"Newpages"		=> "Nije siden",
	"Ancientpages"	=> "Alde siden",
#	"Intl"                => "Interlanguage Links",
	"Allpages"		=> "Alle titels",

	"Ipblocklist"	=> "Utsletten br&ucirc;kers/Ynternet-adressen",
	"Maintenance"     => "Underh&acirc;ldsside",
	"Specialpages"    => "Bys&ucirc;ndere siden",
	"Contributions"   => "",
	"Emailuser"		=> "",
	"Whatlinkshere"   => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "",
#	"Categories"      => "Kategoryen",
	"Export"		=> ""
);

/* private */ $wgSysopSpecialPagesFy = array(
	"Blockip"		=> "Utsletten br&ucirc;ker/Ynternet-adres",
	"Asksql"		=> "Freegje de databank",
	"Undelete"		=> "Set wiske siden wer teplak"
);

/* private */ $wgDeveloperSpecialPagesFy = array(
	"Lockdb"  		=> "Skoattelje databank.",
	"Unlockdb"  	=> "Untskoattel de databank",
	"Debug"   		=> "Breksykynformaasje"
);

/* private */ $wgAllMessagesFy = array(

# Bits of text used by many pages:
#
"linktrail"		=> "/^([àáèéìíòóùúâêîôûa-z]+)(.*)\$/sD",
"mainpage"		=> "Haadside",
"mainpagetext"	=> "Wiki-programma goed ynstallearre.",
"about"		=> "Ynfo",
"aboutwikipedia" 	=> "Oer de $wgSitename",
"aboutpage"		=> "$wgMetaNamespace:Ynfo",
"help"		=> "Help",
"helppage"		=> "$wgMetaNamespace:Help",
"wikititlesuffix" => "$wgSitename",
"bugreports"	=> "Brekmelding",
"bugreportspage"	=> "$wgMetaNamespace:Brekmelding",
"faq"			=> "FAQ",
"faqpage"		=> "$wgMetaNamespace:FAQ",
"edithelp"		=> "Help",
"edithelppage"	=> "$wgMetaNamespace:Bewurk-rie",
"cancel"		=> "Ferlitte",
"qbfind"		=> "Sykje",
"qbbrowse"		=> "Bl&ecirc;dzje",
"qbedit"		=> "Bewurkje",
"qbpageoptions" 	=> "Side-opsjes",
"qbpageinfo"	=> "Side-ynfo",
"qbmyoptions"	=> "Myn Opsjes",
"mypage"		=> "Myn side",
"mytalk"		=> "Myn oerlis",
"currentevents" 	=> "Hjoeddeis",
"errorpagetitle" 	=> "Fout",
"returnto"		=> "Werom nei \"$1\".",
"fromwikipedia"	=> "In side fan de $wgSitename, de frije ensyklopedy.", # FIXME
"whatlinkshere"	=> "Siden mei in keppeling hjirhinne",
"help"		=> "Help",
"search"		=> "<B>Sykje</B>",
"go"			=> "Side",
"history"		=> "Sideskiednis",
"printableversion" => "Ofdruk-ferzje",
"editthispage"	=> "Side bewurkje",
"deletethispage" 	=> "Side wiskje",
"protectthispage" => "Side skoattelje",
"unprotectthisside" => "Side &ucirc;ntskoattelje",
"newpage" 		=> "Nije side",
"talkpage"		=> "Sideoerlis",
"postcomment"   	=> "Skriuw in opmerking",
"articlepage"	=> "Side l&ecirc;ze",
"subjectpage"	=> "M&ecirc;d l&ecirc;ze", # For compatibility
"userpage" 		=> "Br&ucirc;kerside",
"wikipediapage" 	=> "Metaside",
"imagepage" 	=> "Ofbyldside",
"viewtalkpage" 	=> "Oerlisside",
"otherlanguages" 	=> "Oare talen",
"redirectedfrom" 	=> "(Trochwiisd fan \"$1\")",
"lastmodified"	=> "L&ecirc;ste kear bewurke op $1.",
"viewcount"		=> "Disse side is $1 kear iepenslein.",
"gnunote" 		=> "Alle tekst is beskiber &ucirc;nder de betingsten fan de <a class=internal href='/wiki/GNU_FDL'>GNU Iepen Dokumentaasje Lisinsje</a>.",
"printsubtitle" 	=> "(Fan http://$wgServer)",
"protectedpage" 	=> "Skoattele side",
"administrators" 	=> "$wgMetaNamespace:Behear",
"sysoptitle"	=> "Allinnich foar behearders",
"sysoptext"		=> "Om dit te dwaan moatte jo behearder w&ecirc;ze. Sjoch \"$1\".",
"developertitle"  => "Allinich foar untwiklers",
"developertext"	=> "Om dit te dwaan moatte jo &ucirc;ntwikler w&ecirc;ze. Sjoch \"$1\".",
"nbytes"		=> "$1 byte",
"go"			=> "Side",
"ok"			=> "Goed",
"sitetitle"		=> $wgSitename,
"sitesubtitle"	=> "De frije ensyklopedy",
"retrievedfrom" 	=> "Untfongen fan \"$1\"",
"newmessages" 	=> "Jo hawwe $1.",
"newmessageslink" => "nije berjochten",
"editsection"	=> "edit",
"toc" 		=> "Ynh&acirc;ld",
"showtoc" 		=> "sjen litte",
"hidetoc" 		=> "net sjen litte",
"thisisdeleted"	=> "\"$1\" l&ecirc;ze of werombringje?",
"restorelink" 	=> "$1 wiske ferzjes",

# Main script and global functions
#
"nosuchaction"	=> "Unbekende aksje.",
"nosuchactiontext" => "De aksje dy't jo oanjoegen fia de URL is net bekind by it Wiki-programma",
"nosuchspecialpage" => "Unbekende side",
"nospecialpagetext" => "Jo hawwe in Wiki-side opfrege dy't net bekind is by it Wiki-programma.",


# General errors
#
"error"			=> "Fout",
"databaseerror" 		=> "Databankfout",
"dberrortext"		=> "Sinboufout yn databankfraach.
Dit soe troch in ferkearde sykfraach komme kinne (sjoch \"$5\"),
of it soe in brek yn it programma w&ecirc;ze kinne.
De l&ecirc;st besochte databankfraach wie:
<blockquote><tt>$1</tt></blockquote>
fan funksje \"<tt>$2</tt>\" &uacute;t.
MySQL joech fout \"<tt>$3: $4</tt>\" werom.",

"dberrortextcl" 		=> "Sinboufout in databankfraach.
De l&ecirc;st besochte databankfraach wie:
\"$1\"
fan funksje \"$2\" &uacute;t.
MySQL joech fout \"<tt>$3: $4</tt>\" werom.",

"noconnect"			=> "Sorry! Troch in fout yn de technyk, kin de Wiki gjin ferbining meitsje mei de databanktsjinner.",
"nodb"			=> "Kin databank \"$1\" net berikke.",
"cachederror"		=> "Dit is in ferzje &uacute;t de oerslag, mar it kin w&ecirc;ze dat dy fer&acirc;ldere is.",
"readonly"			=> "Databank is skoattele",
"enterlockreason" 	=> "Skriuw w&ecirc;rom de databank skoattele is,
en sawat hoenear't dy wer &ucirc;ntskoattele wurdt",
"readonlytext"	=> "De $wgSitename databank is skoattele foar nije siden en oare wizigings,
nei alle gedachten is it foar &ucirc;nderh&acirc;ld, en kinne jo der letter gewoan wer br&ucirc;k fan meitsje.
De behearder hat dizze &uacute;tlis joen:
<p>$1</p>",

"missingarticle" 		=> "De databank kin in side net fine, nammentlik: \"$1\".
<p>Faak is dit om't in &acirc;lde ferskil-, of skiednisside opfreege wurdt fan in side dy't wiske is.
<p>As dat it hjir net is, dan hawwe jo faaks in brek yn it programa f&ucirc;n.
Jou dat asjebleaft troch oan de [[$wgMetaNamespace:Brekmelding|behearder]], tegearre mei de URL.",

"internalerror" 		=> "Ynwindige fout",
"filecopyerror" 		=> "Koe triem \"$1\" net kopiearje as \"$2\".",
"filerenameerror" 	=> "Koe triem \"$1\" net werneame as \"$2\".",
"filedeleteerror" 	=> "Koe triem \"$1\" net wiskje.",
"filenotfound"		=> "Koe triem \"$1\" net fine.",
"unexpected"		=> "Hommelse wearde: \"$1\"=\"$2\".",
"formerror"			=> "Fout: koe formulier net oerlizze",	
"badarticleerror" 	=> "Dit kin op dizze side net dien wurden.",
"cannotdelete"		=> "Koe de oantsjutte side of &ocirc;fbyld net wiskje. (Faaks hat in oar dat al dien.)",
"badtitle"			=> "Misse titel",
"badtitletext"		=> "De opfreeche side titel wie &ucirc;njildich, leech, of in 
miskeppele ynter-taal of ynter-wiki titel.",
"perfdisabled" 		=> "Sorry! Dit &ucirc;nderdiel is tydlik &uacute;t set om't it de databank sa starich makket
dat gjinien de wiki br&ucirc;ke kin.",
"perfdisabledsub" 	=> "Dit is in opsleine ferzje fan \"$1\":",


# Login and logout pages
#
"logouttitle" 	=> "Ofmelde",
"logouttext"	=> "Jo binne no &ocirc;fmeld.
Jo kinne de $wgSitename fierders anonym br&ucirc;ke,
of jo op 'e nij [[Wiki:Userlogin|oanmelde]] &ucirc;nder in oare namme.\n",
"welcomecreation" => "<h2>Wolkom, $1!</h2><p>Jo ynstellings bin oanmakke.
Ferjit net se oan jo foarkar oan te passen.",

"loginpagetitle" 	=> "Oanmelde",
"yourname"  	=> "Jo br&ucirc;kersnamme",
"yourpassword" => "Jo wachtwurd",
"yourpasswordagain" => "Jo wachtwurd (nochris)",
"newusersonly" 	=> " (allinnich foar nije br&ucirc;kers)",
"remembermypassword" => "Oare kear fansels oanmelde.",
"loginproblem" 	=> "<b>Der wie wat mis mei jo oanmelden.</b><br>Besykje it nochris, a.j.w.",
"alreadyloggedin" => "<font color=red><b>Br&ucirc;ker $1, jo binne al oanmeld!</b></font><br>\n",
"areyounew"  	=> "Binne jo nij op de $wgSitename en wolle jo br&ucirc;kersynstellings oanmeitsje, 
jou dan in br&ucirc;kersnamme en twa kear itselde wachtwurd yn.
In netpostadres hoecht net, mar as jo it wachtwurd in kear ferjitte soenen,
dan koe jo d&ecirc;r in nijenien tastjoerd wurde.<br>\n",
"login"		=> "Oanmelde",
"userlogin"		=> "Oanmelde",
"logout"		=> "Ofmelde",
"userlogout"	=> "Ofmelde",
"notloggedin"	=> "Net oanmelde",
"createaccount"	=> "Nije ynstellings oanmeitsje",
"badretype"		=> "De ynfierde wuchtwurden binne net lyk. (Nochris?)",
"userexists"	=> "Dy br&ucirc;kersname wurdt al br&ucirc;kt. Besykje in oarenien.",
"youremail"		=> "Jo netpostadres (*).",
"yournick"		=> "Jo alias (foar sinjaturen)",
"emailforlost"	=> "* In netpostadres hoecht net.<br>
Mar it helpt, soenen jo jo wachtwurd ferjitte.
En mei in netpostadres hjir, kinne oaren fan jo side &ocric;f contact mei jo meitsje,
s&ucirc;nder dat se dat adres witte. (Dat leste kin ek wer &uacute;tset by de ynstellings.)",

"loginerror"	=> "Oanmeldflater",
"noname"		=> "Jo moatte in br&ucirc;kersnamme opjaan.",
"loginsuccesstitle" => "Oanmelden slagge.",
"loginsuccess"	=> "Jo binne no oanmelde op de $wgSitename as: $1.",
"nosuchuser"	=> "Br&ucirc;kersnamme en wachtwurd hearre net tegearre.
Besykje op 'e nij, of fier it wachtwurd twa kear yn en meitsje nije br&ucirc;kersynstellings.",

"wrongpassword"	=> "Br&ucirc;kersnamme en wachtwurd hearre net tegearre.
Besykje op 'e nij, of fier it wachtwurd twa kear yn en meitsje nije br&ucirc;kersynstellings.",

"mailmypassword" 	=> "Stjoer my in nij wachtwurd.",
"passwordremindertitle" => "Nij wachtwurd foar de $wgSitename",
"passwordremindertext" => "Immen (nei alle gedachten jo, fan Ynternet-adres $1)
hat frege en stjoer jo in nij $wgSitename wachtwurd.
I wachtwurd foar br&ucirc;ker \"$2\" is no \"$3\".
Meld jo no oan, en feroarje jo wachtwurd.",
"noemail"		=> "Der is gjin netpostadres foar br&ucirc;ker \"$1\".",
"passwordsent"	=> "In nij wachtwurd is tastjoert oan it netpostadres foar \"$1\".
Meld jo ope 'e nij oan nei't jo it krige hawwe, a.j.w.",

# Edit pages
#
"summary"		=> "Gearfetting",
"subject"		=> "M&ecirc;d",
"minoredit"		=> "Dit is in tekstwiziging",
"watchthis"		=> "Folgje dizze side",
"savearticle"	=> "F&ecirc;stlizze",
"preview"		=> "Oerl&ecirc;ze",
"showpreview"	=> "Earst oerl&ecirc;ze",
"blockedtitle"	=> "Br&ucirc;ker is &uacute;tsletten troch",
"blockedtext"	=> "Jo br&ucirc;kersname of Ynternet-adres is &uacute;tsletten.
As reden is opj&ucirc;n:<br>''$2''<p>As jo wolle, kinne jo hjiroer kontakt op nimme mei de behearder. 

(Om't Ynternet-adressen faak mar foar ien sessie tawiisd wurde, kin it w&ecirc;ze
dat it eins gjit om in oar dy't deselde kedizer hat as jo hawwe. As it jo
net betreft, besykje dan earst of it noch sa is as jo in skofke gjin
Ynternet-ferbining h&acirc;n hawwe. As it in probleem bliuwt, skriuw dan de behearder.
Sorry, foar it &ucirc;ngemak.)

Jo Ynternet-adres is: $3. Nim dat op yn jo berjocht.

Tink derom, dat \"skriuw dizze br&ucirc;ker\" allinich wol as jo in
netpostadres opj&ucirc;n hawwe in jo [[Wiki:Preferences|ynstellings]].",

"newarticle"	=> "(Nij)",
"newarticletext" =>
"Jo hawwe in keppeling folge nei in side d&ecirc;r't noch gjin tekst op stiet.
Jo kinne sels de tekst meitjsen troch dy gewoan yn te typen yn dit bewurkingsfjild. 
([[$wgMetaNamespace:Bewurk-rie|Mear ynformaasje oer bewurkjen]].)
Oars kinne jo tebek mei de tebek-knop fan jo bl&ecirc;dzjer.",

"anontalkpagetext" => "---- ''Dit is de oerlisside fan in unbekinde br&ucirc;ker; in br&ucirc;ker
dy't sich net oanmeld hat. Om't der gjin namme is wurd it Ynternet-adres br&ucirc;kt om
oan te jaan wa. Mar faak is it sa dat sa'n adres net altid troch deselde br&ucirc;kt wurdt.
As jo it idee hawwe dat jo as &ucirc;nbekinde br&ucirc;ker opmerkings foar in oar krije, dan kinne
jo jo [[Wiki:Userlogin|oanmelde]], dat jo allinnich opmerkings foar josels krije.'' ",
"noarticletext" => "(Der stiet noch gjin tekst op dizze side.)",
"updated"		=> "(Bewurke)",
"note"		=> "<strong>Opmerking:</strong> ",
"previewnote"	=> "Tink der om dat dizze side noch net f&ecirc;stlein is!",
"previewconflict" => "Dizze side belanget allinich it earste bewurkingsfjild oan.",
"editing"		=> "Bewurkje \"$1\"",
"sectionedit"	=> " (seksje)",
"commentedit"	=> " (nije opmerking)",
"editconflict"	=> "Tagelyk bewurke: \"$1\"",
"explainconflict" => "In oar hat de side feroare s&ucirc;nt jo beg&ucirc;n binne mei it bewurkjen.
It earste bewurkingsfjild is hoe't de tekst wilens wurde is. 
Jo feroarings stean yn it twadde fjild.
Dy wurde allinnich tapasse safier as jo se yn it earste fjild ynpasse.
<b>Allinnich</b> de tekst &uacute;t it earste fjild kin f&ecirc;stlein wurde.\n<p>",
"yourtext"		=> "Jo tekst",
"storedversion" => "F&ecirc;stleine ferzje",
"editingold"	=> "<strong><font color=red>Warsk&ocirc;ging</font>: Jo binne dwaande mei in &acirc;ldere ferzje fan dizze side.
Soenen jo dizze f&ecirc;stlizze, dan is al wat s&ucirc;nt dy tiid feroare is kwyt.</strong>\n",
"yourdiff"		=> "Feroarings",
# REPLACE THE COPYRIGHT WARNING IF YOUR SITE ISN'T GFDL!
"copyrightwarning" => "Alle bydragen ta de $wgSitename wurde sjoen
as fallend &ucirc;nder de GNU Iepen Dokumentaasje Lisinsje
(sjoch fierders: \"$1\").
As jo net wolle dat jo skriuwen &ucirc;nferbidlik oanpast en frij ferspraat wurdt,
dan is it baas, en set it net op de $wgSitename.<br>
Jo ferklare ek dat jo dit sels skreaun hawwe, of it oernaam hawwe &uacute;t in
publyk eigendom of in oare iepen boarne.
<strong><big>Foegje gjin wurk &ucirc;nder auteursrjocht ta s&ucirc;nder tastimming!</big></strong>",
"longpagewarning" => "<font color=red>Warsk&ocirc;ging</font>: Dizze side is $1 kilobyte lang; 
der binne bl&ecirc;dzjers dy problemen hawwe mei siden fan tsjin de 32kb. of langer.
Besykje de side yn lytsere stikken te brekken.",
"readonlywarning" => "<font color=red>Warsk&ocirc;ging</font>: De databank is skoattele foar
&ucirc;nderh&acirc;ld, dus jo kinne jo bewurkings no net f&ecirc;stlizze.
It wie baas en nim de tekst foar letter oer yn in teksttriem.",
"protectedpagewarning" => "<font color=red>Warsk&ocirc;ging</font>: Dizze side is skoattele, dat
gewoane br&ucirc;kers dy net bewurkje kinne. Tink om de
<a href='/wiki/$wgMetaNamespace:Skoattel-rie'>rie foar skoattele siden</a>.",

# History pages
#
"revhistory"	=> "Sideskiednis",
"nohistory"		=> "Dit is de earste ferzje fan de side.",
"revnotfound"	=> "Ferzje net f&ucirc;n",
"revnotfoundtext" => "De &acirc;lde ferzje fan dizze side d&ecirc;r't jo om frege hawwe, is der net.
Gean nei of de keppeling dy jo br&ucirc;kt hawwe wol goed is.\n",
"loadhist"		=> "Sideskiednis ...",
"currentrev"	=> "Dizze ferzje",
"revisionasof"	=> "Ferzje op $1",
"cur"			=> "no",
"next"		=> "dan",
"last"		=> "doe",
"orig"		=> "ea",
"histlegend"	=> "Utlis: (no) = ferskil mei de side sa't dy no is,
(doe) = ferskill mei de side sa't er doe wie, foar de feroaring, T = Tekstwiziging",


# Diffs
#
"difference"	=> "(Ferskil tusken ferzjes)",
"loadingrev"	=> "Ferskil tusken ferzjes ...",
"lineno"		=> "Rigel $1:",
"editcurrent"	=> "Bewurk de hjoeddeistiche ferzje fan dizze side",

# Search results
#
"searchresults" => "Sykresultaat",
"searchhelppage" => "$wgMetaNamespace:Syk-rie|Ynformaasje oer it sykjen",
"searchingwikipedia" => "Sykje troch de $wgSitename",
"searchresulttext" => "\"$1\" troch de $wgSitename.",
"searchquery"	=> "Foar fraach \"$1\"",
"badquery"		=> "Misfoarme sykfraach",
"badquerytext"	=> "Jo fraach koe net ferwurke wurde.
Dit is faaks om't jo besyke hawwe en sykje in word fan ien of twa letters,
wat it programma noch net kin. Of it soe kinne dat jo de fraach misskreaun hawwe,
lykas \"Midden fan fan Frysl&acirc;n\". Besykje it nochris.",
"matchtotals"	=> "Foar \"$1\" binne $2 titles f&ucirc;n en $3 siden.",
"nogomatch" => "Der is gjin side mei krekt dizze titel. Faaks is it better en Sykje nei dizze tekst.",
"titlematches"	=> "Titels",
"notitlematches" => "Gjin titels",
"textmatches"	=> "Siden",
"notextmatches"	=> "Gjin siden",
"prevn"		=> "foarige $1",
"nextn"		=> "folgende $1",
"viewprevnext"	=> "($1) ($2) ($3) besjen.",
"showingresults"	=> "<b>$1</b> resultaten fan <b>$2</b> &ocirc;f.",
"showingresultsnum" => "<b>$3</b> resultaten fan <b>$2</b> &ocirc;f.",
"nonefound"		=> "As der gjin resultaten binne, tink der dan om dat der <b>net</b> socht
wurde kin om wurden as \"it\" en \"in\", om't dy net byh&acirc;lden wurde, en dat as der mear
wurden syke wurde, allinnich siden f&ucirc;n wurde w&ecirc;r't <b>alle</b> worden op f&ucirc;n wurde.",

"powersearch" => "Sykje",
"powersearchtext" => "
Sykje yn nammeromten :<br>
$1<br>
$2 List trochferwizings &nbsp; Sykje nei \"$3\" \"$9\"",

"searchdisabled" => "<p>Op it stuit stiet it trochsykjen fan tekst net oan, om't de 
tsjinner it net oankin. Mei't we nije apparatuer krije wurdt it nei alle gedanken wer
mooglik. Foar now kinne jo sykje fia Google:</p>
                                                                                                                                                        
<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Sykje mei Google\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio
name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch
value=\"{$wgServer}\" checked> $wgSitename <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->
",

"blanknamespace" => "($wgSitename)",


# Preferences page
#
"preferences"		=> "Ynstellings",
"prefsnologin" 		=> "Net oanmeld",
"prefsnologintext"	=> "Jo moatte <a href=\""
. wfLocalUrl( "Wiki:Userlogin" ) 
. "\">oanmeld</a> w&ecirc;ze om jo ynstellings te feroarjen.",

"prefslogintext" 		=> "Jo binne oanmeld, $1.
Jo Wiki-n&ucirc;mer is $2.

([[$wgMetaNamespace:Ynstelling-rie|Help by de ynstellings]].",

"prefsreset"		=> "De ynstellings binne tebek set sa't se f&ecirc;stlein wienen.",
"qbsettings"		=> "Menu", 
"changepassword" 		=> "Wachtword feroarje",
"skin"			=> "Side-oansjen",
"math"			=> "Formules",
"dateformat"		=> "Datum",
"math_failure"		=> "Untsjutbere formule",
"math_unknown_error"	=> "Unbekinde fout",
"math_unknown_function"	=> "Unbekinde funksje",
"math_lexing_error"	=> "Unbekind wurd",
"math_syntax_error"	=> "Sinboufout",
"saveprefs"			=> "Ynstellings f&ecirc;stlizze",
"resetprefs"		=> "Ynstellings tebek sette",
"oldpassword"		=> "Ald wachtwurd",
"newpassword"		=> "Nij wachtwurd",
"retypenew"			=> "Nij wachtwurd (nochris)",
"textboxsize"		=> "Tekstfjid-omjittings",
"rows"			=> "Rigen",
"columns"			=> "Kolommen",
"searchresultshead" 	=> "Sykje",
"resultsperpage" 		=> "Treffers de side",
"contextlines"		=> "Rigels ynh&acirc;ld de treffer",
"contextchars"		=> "Tekens fan de inh&acirc;ld de rigel",
"stubthreshold" 		=> "Grins foar stobben",
"recentchangescount" 	=> "Tal fan titels op 'Koarts feroare'",
"savedprefs"		=> "Jo ynstellings binne f&ecirc;stlein.",
"timezonetext"		=> "Jou it tal fan oeren dat jo tiids&ocirc;ne ferskilt fan UTC (Greenwich).",
"localtime"			=> "Jo tiids&ocirc;ne",
"timezoneoffset" 		=> "Ferskil",
"servertime"		=> "UTC",
"guesstimezone" 		=> "Freegje de bl&ecirc;dzjer",
"emailflag"			=> "Gjin post fan oare br&ucirc;kers",
"defaultns"			=> "Nammeromten dy't normaal trochsykje wurde:",

# Recent changes
#
"changes" 			=> "feroarings",
"recentchanges" 		=> "Koarts feroare",
# This is the default text, and can be overriden by editing [[$wgMetaNamespace::Recentchanges]]
"recentchangestext" 	=> "De l&ecirc;ste feroarings fan de $wgSitename.",
"rcloaderr"			=> "Koarts feroare ...",
"rcnote"			=> "Dit binne de l&ecirc;ste <strong>$1</strong> feroarings yn de l&ecirc;ste <strong>$2</strong> dagen.",
"rcnotefrom"		=> "Dit binne de feroarings s&ucirc;nt <b>$2</b> (maksimaal <b>$1</b>).",
"rclistfrom"		=> "Jou nije feroarings, begjinnende mei $1",
"rclinks"			=> "Jou $1 nije feroarings yn de l&ecirc;ste $2 dagen; $3 tekstwiziging",
"rchide"			=> "yn $4 foarm; $1 tekstwizigings; $2 oare nammeromten; $3 meartallige feroarings.",
"rcliu"			=> "; $1 feroarings troch oanmelde br&ucirc;kers",
"diff"			=> "ferskil",
"hist"			=> "skiednis",
"hide"			=> "gjin",
"show"			=> "al",
"tableform"			=> "tabel",
"listform"			=> "list",
"nchanges"			=> "$1 feroarings",
"minoreditletter" 	=> "T",
"newpageletter" 		=> "N",

# Upload
#
"upload"		=> "Bring triem oan",
"uploadbtn"		=> "Bring triem oan",
"uploadlink"	=> "Bring &ocirc;fbylden oan",
"reupload"		=> "Op 'e nij oanbringe",
"reuploaddesc"	=> "Werom nei oanbring-side.",
"uploadnologin" 	=> "Net oanmelde",
"uploadnologintext" => "Jo moatte <a href=\""
. wfLocalUrl( "Wiki:Userlogin" ) 
. "\">oanmeld</a> w&ecirc;ze om in triem oanbringe te kinnen.",

"uploadfile"	=> "Bring &ocirc;fbylden, l&ucirc;den, dokuminten ensfh. oan.",
"uploaderror"	=> "Oanbring-fout",
"uploadtext"	=> "<strong>STOP!</strong> L&ecirc;s ear't jo eat oanbringe
de <a href=\"" . wfLocalUrlE( "$wgMetaNamespace:Ofbyld-rie" )
. "\">regels foar &ocirc;fbyldbr&ucirc;k</a> foar de $wgSitename.
<p>Earder oanbrochte &ocirc;fbylden, kinne jo fine op de <a href=\"" 
. wfLocalUrlE( "Wiki:Imagelist" ) 
. "\">list fan oanbrochte &ocirc;fbylden</a>.
Wat oanbrocht en wat wiske wurdt, wurdt delskreaun yn it <a href=\"" .
wfLocalUrlE( "$wgMetaNamespace:Oanbring-loch" ) . "\">lochboek</a>.
<p>Om't nije &ocirc;fbylden oan te bringen, kieze jo in triem &uacute;t sa't dat
normaal is foar jo bl&ecirc;dzjer en bestjoersysteem.
Dan jouwe jo oan jo gjin auteursrjocht skeine troch it oanbringen.
Mei \"Bring oan\" begjinne jo dan it oanbringen.
Dit kin efkes duorje as jo Ynternet-ferbining net sa flug is.
<p>Foar de triemfoarm wurdt foar foto's JPEG oanret, foar tekenings ensfh. PNG,
en foar l&ucirc;den OGG. Br&ucirc;k in d&uacute;dlike triemnamme, sa't in oar ek wit wat it is.
<P>Om it &ocirc;fbyld yn in side op te nimmen, meitsje jo d&ecirc;r sa'n keppeling:<br>
<b>[[&ocirc;fbyld:jo_foto.jpg|omskriuwing]]</b> of <b>[[&ocirc;fbyld:jo_logo.png|omskriuwing]]</b>;
en foar l&ucirc;den <b>[[media:jo_l&ucirc;d.ogg]]</b>.
<p>Tink derom dat oaren bewurkje kinne wat jo oanbringe, as dat better is foar de $wgSitename,
krekt's sa't dat foar siden jildt, en dat jo &uacute;tsletten wurde kinne as jo misbr&ucirc;k
meitsje fan it systeem..",

"uploadlog"		=> "oanbring log",
"uploadlogpage" 	=> "Oanbring_log",
"uploadlogpagetext" => "Liste fan de l&ecirc;st oanbrochte triemmen.
(Tiid oanj&ucirc;n as UTC).
<ul>
</ul>
",

"filename"		=> "triemnamme",
"filedesc"		=> "Omskriuwing",
"affirmation"	=> "Ik bef&ecirc;stigje dat de eigner fan de rjochten op dit triem 
ynstimt mei fersprieding &ucirc;nder de betingsten fan de $1.",

"copyrightpage" 	=> "$wgMetaNamespace:Auteursrjocht",
"copyrightpagename" => "$wgSitename auteursrjocht",
"uploadedfiles"	=> "Oanbrochte triemmen",
"noaffirmation" => "Jo moatte befestigje dat wat jo oanbringe gjin rjochten skeint.",
"ignorewarning"	=> "Sjoch oer de warsk&ocirc;ging hinne en lis triem dochs f&ecirc;st.",
"minlength"		=> "Ofbyldnammen moatte trije letters of mear w&ecirc;ze.",
"badfilename"	=> "De &ocirc;fbyldnamme is feroare nei \"$1\".",
"badfiletype"	=> "\".$1\" is net yn in oanrette triemfoarm.",
"largefile"		=> "It is baas as &ocirc;fbylden net grutter as 100k binne.",
"successfulupload" => "Oanbringen slagge.",
"fileuploaded"	=> "triem \"$1\" goed oanbrocht.
Gean no fierder nei de beskriuwingsside: ($2). D&ecirc;r kinne jo oanjaan
w&ecirc;r't it triem wei kaam, hoenear it oanmakke is en wa't it makke hat, 
en wat jo fierder mar oan ynformaasje hawwe.",

"uploadwarning" 	=> "Oanbring warsk&ocirc;ging",
"savefile"		=> "Lis triem f&ecirc;st",
"uploadedimage" 	=> " \"$1\" oanbrocht",
"uploaddisabled" => "Sorry, op dizze tsjinner kin net oanbrocht wurde.",

# Image list
#
"imagelist"		=> "Ofbyld list",
"imagelisttext"	=> "Dit is in list fan $1 &ocirc;fbylden, op $2.",
"getimagelist"	=> "Ofbyld list ...",
"ilshowmatch"	=> "Jou alle &ocirc;fbylden mei in name as",
"ilsubmit"		=> "Sykje",
"showlast"		=> "Jou l&ecirc;ste $1 &ocirc;fbylden, op $2.",
"all"			=> "alle",
"byname"		=> "namme",
"bydate"		=> "datum",
"bysize"		=> "grutte",
"imgdelete"		=> "wisk",
"imgdesc"		=> "tekst",
"imglegend"		=> "Utlis: (tekst) = Jou/bewurk &ocirc;fbyld-omskriuwing.",
"imghistory"	=> "Ofbyldskiednis",
"revertimg"		=> "tebek",
"deleteimg"		=> "wisk",
"imghistlegend"	=> "Utlis: (no) = dit is it hjoeddeiske &ocirc;fbyld,
(wisk) = wiskje dizze &acirc;ldere ferzje, (tebek) = set &ocirc;fbyld tebek nei dizze &acirc;ldere ferzje.
<br><i>Fia de datum kinne jo it &ocirc;fbyld dat doe oanbrocht waard besjen</i>.",

"imagelinks"	=> "Ofbyldkeppelings",
"linkstoimage"	=> "Dizze siden binne keppele oan it &ocirc;fbyld:",
"nolinkstoimage" => "Der binne gjin siden oan dit &ocirc;fbyld keppelje.",

# Statistics
#
"statistics"	=> "Statistyk",
"sitestats"		=> "Side statistyk",
"userstats"		=> "Br&ucirc;ker statistyk",
"sitestatstext" => "It tal fan siden yn de $wgSitename is: <b>$2</b>.<br>
(Oerlissiden, siden oer de $wgSitename, oare bys&ucirc;ndere siden, stobben en
trochferwizings yn de databank binne d&ecirc;rby net meiteld.)<br>
It tal fan siden yn de databank is: <b>$1</b>.
<p>
Der is <b>$3</b> kear in side opfrege, en <b>$4</b> kear in side bewurke,
s&ucirc;nt it programma bywurke is (15 oktober 2002).
Dat komt yn trochslach del op <b>$5</b> kear bewurke de side,
en <b>$6</b> kear opfrege de bewurking.",

"userstatstext" => "It tal fan registreare br&ucirc;kers is <b>$1</b>.
It tal fan behearders d&ecirc;rfan is: <b>$2</b>.",

# Maintenance Page
#
"maintenance"		=> "Underh&acirc;ld",
"maintnancepagetext"	=> "Op dizze side stiet ark foar it deistich &ucirc;nderh&acirc;ld.
In part fan de funksjes freegje in soad fan de databank, dus freegje net efter
eltse oanpassing daalks in fernijde side op.",

"maintenancebacklink"	=> "Werom nei Underh&acirc;ldside",
"disambiguations"		=> "Trochverwizings",
"disambiguationspage"	=> "$wgMetaNamespace:trochferwizing",
"disambiguationstext"	=> "Dizze siden binne keppele fia in
[[$wgMetaNamespace:trochferwizing]]. 
Se soenen mei de side sels keppele wurde moatte.<br>
(Allinnich siden &uacute;t deselde nammeromte binne oanj&ucirc;n.)",

"doubleredirects"	=> "D&ucirc;bele trochverwizings",
"doubleredirectstext"	=> "<b>Let op!</b> Der kinne missen yn dizze list stean!
Dat komt dan ornaris troch oare keppelings &ucirc;nder de \"#REDIRECT\".<br>
Eltse rigel jout keppelings foar de earste en twadde trochverwizing, en dan de earste regel fan
de twadde trochferwizing, wat it werklik doel w&ecirc;ze moat.",

"brokenredirects"		=> "Misse trochferwizings",
"brokenredirectstext"	=> "Dizze trochferwizings ferwize nei siden dy't der net binne.",
"selflinks"			=> "Siden mei sels-ferwizings",
"selflinkstext"		=> "Dizze siden hawwe in keppeling mei harrensels, wat net sa w&ecirc;ze moat.",
"mispeelings"           => "Siden mei skriuwflaters",
"mispeelingstext"		=> "Op dizze siden stiet in skriuw- of typ-flater dy't in soad makke wurd, lykas oanjoen op \"$1\".
D&ecirc;r soe ek stean moatte hoe't it (goed skreaun) wurdt.",
"mispeelingspage"       => "List fan faak makke flaters",
"missinglanguagelinks"  => "Untbrekkende taalkeppelings",
"missinglanguagelinksbutton"    => "Fyn &ucirc;ntbrekkende taalkeppelings foar",
"missinglanguagelinkstext"      => "Dizze siden hawwe gjin taalkeppeling mei deselde side yn taal \"$1\".
(Ferwizings en oanheake siden binne <i>net</i> besjoen.",


# Miscellaneous special pages
#
"orphans"		=> "Lossteande siden",
"lonelypages"	=> "Lossteande siden",
"unusedimages"	=> "Lossteande &ocirc;bylden",
"popularpages"	=> "Grage siden",
"nviews"		=> "$1 kear sjoen",
"wantedpages"	=> "Nedige siden",
"nlinks"		=> "$1 keer keppele",
"allpages"		=> "Alle titels",
"randompage"	=> "Samar in side",
"shortpages"	=> "Koarte siden",
"longpages"		=> "Lange siden",
"listusers"		=> "Br&ucirc;kerlist",
"specialpages"	=> "Bys&ucirc;ndere siden",
"spheading"		=> "Bys&ucirc;ndere siden foar all br&ucirc;kers",
"sysopspheading"	=> "Allinnich foar behearders",
"developerspheading" => "Allinich foar &ucirc;ntwiklers",
"protectpage"	=> "Skoattel side",
"recentchangeslinked" => "Feroare buorsiden",
"rclsub"		=> "(feroare siden d&ecirc;r't \"$1\" in keppeling mei hat)",
"debug"		=> "Breksykje",
"newpages"		=> "Nije siden",
"ancientpages"	=> "Alde siden",
"movethispage"	=> "Feroarje titel",
"unusedimagestext" => "<p>Tink derom dat oare webstee&euml;en as de $wgSitename,
begelyks oare parten fan itselde projekt,
in keppeling mei direkt de URL fan it &ocirc;fbyld makke hawwe kinne.
Dan wurdt it &ocirc;fbyld noch br&ucirc;ke, mar stiet al yn dizze list.",

"booksources"	=> "",
"booksourcetext" 	=> "",
"alphaindexline" 	=> "$1 oan't $2",


# Email this br&ucirc;ker
#
"mailnologin"	=> "Gjin adres beskikber",
"mailnologintext" => "Jo moatte <a href=\""
. wfLocalUrl( "Wiki:Userlogin" ) . "\">oanmeld</a>
w&ecirc;ze, en in jildich netpostadres <a href=\"" .
  wfLocalUrl( "Wiki:Preferences" ) . "\">ynsteld</a>
hawwe, om oan oare br&ucirc;kers netpost stjoere te kinnen.",

"emailuser"		=> "Skriuw dizze br&ucirc;ker",
"emailpage"		=> "Netpost nei br&ucirc;ker",
"emailpagetext"	=> "As dizze br&ucirc;ker in jildich netpostadres ynsteld hat,
dan kinne jo dat hjir ien berjocht tastjoere.
It netpostadres dat jo ynsteld hawwe wurdt br&ucirc;kt as de &ocirc;fstjoerder, sa't de &ucirc;ntfanger
antwurdzje kin.",
"noemailtitle"	=> "Gjin netpostadres",
"noemailtext"	=> "Dizze br&ucirc;ker had gjin jildich e-postadres ynsteld,
of hat oanj&ucirc;n gjin post hawwe te wollen.",
"emailfrom"		=> "Fan",
"emailto"		=> "Oan",
"emailsubject"	=> "Oer",
"emailmessage"	=> "Tekst",
"emailsend"		=> "Stjoer",
"emailsent"		=> "Berjocht stjoerd",
"emailsenttext" => "Jo berjocht is stjoerd.",

# Watchlist
#
"watchlist"		=> "Folchlist",
"watchlistsub"	=> "(foar br&ucirc;ker \"$1\")",
"nowatchlist"	=> "Jo hawwe gjin siden op jo folchlist.",
"watchnologin"	=> "Net oanmeld",
"watchnologintext"=> "Jo moatte <a href=\""
. wfLocalUrl( "Wiki:Userlogin" ) 
. "\">oanmeld</a> w&ecirc;ze om jo folchlist te feroarjen.",

"addedwatch"	=> "Oan folchlist tafoege",
"addedwatchtext"	=> "De side \"$1\" is tafoege oan jo <a href=\"" 
. wfLocalUrl( "Wiki:Watchlist" ) . "\">folchlist</a>.
As dizze side sels, of de oerlisside, feroare wurd, dan komt dat d&ecirc;r yn,
en de side stiet dan ek <b>fet</b> yn de <a href=\"" .
  wfLocalUrl( "Wiki:Recentchanges" ) . "\">Koarts feroare</a> list.

<p>As jo letter in side net mear folgje wolle, dan br&ucirc;ke jo \"Ferjit dizze side\".",
"removedwatch"	=> "Net mear folgje",
"removedwatchtext" => "De side \"$1\" stiet net mear op jo folchlist.",
"watchthispage"	=> "Folgje dizze side",
"unwatchthispage" => "Ferjit dizze side",
"notanarticle"	=> "Dit kin net folge wurde.",
"watchnochange" 	=> "Fan de siden dy't jo folgje is der yn dizze perioade net ien feroare.",
"watchdetails"	=> "Jo folchlist hat $1 siden (oerlissiden net meiteld).
Yn dizze perioade binne $2 siden feroare. (<a href='$4'>G&acirc;ns myn folchlist</a>.)
<br>$3:",

"watchmethod-recent" => "Koarts feroare ...",
"watchmethod-list" => "Folge ...",
"removechecked"	=> "Ferjit dizze siden",
"watchlistcontains" => "Jo folgje op it stuit $1 siden.",
"watcheditlist"	=> "Dit binne de siden op jo folchlist, oardere op alfabet.
Jou oan hokfoar siden jo net mear folgje wolle, en bef&ecirc;stigje dat &ucirc;nderoan de side.",

"removingchecked" => "Wiskje siden fan jo folchlist ...",
"couldntremove" 	=> "Koe \"$1\" net ferjitte ...",
"iteminvalidname" => "Misse namme: \"$1\" ...",
"wlnote" 		=> "Dit binne de l&ecirc;ste <strong>$1</strong> feroarings yn de l&ecirc;ste <strong>$2</strong> oeren.",


# Delete/protect/revert
#
"deletepage"	=> "Wisk side",
"confirm"		=> "Bef&ecirc;stigje",
"excontent"		=> "inh&acirc;ld wie:",
"exbeforeblank" 	=> "foar de tekst wiske wie, wie dat:",
"exblank"		=> "side wie leech",
"confirmdelete"	=> "Befestigje wiskjen",
"deletesub"		=> "(Wiskje \"$1\")",
"historywarning"	=> "Warsk&ocirc;ging: De side dy't jo wiskje wolle hat skiednis: ",
"confirmdeletetext" => "Jo binne dwaande mei it foar altyd wiskjen fan in side
of &ocirc;fbyld, tegearre mei alle skiednis, &uacute;t de databank.
Bef&ecirc;stigje dat jo dat wier dwaan wolle. Bef&ecirc;stigje dat dat is wat jo witte wat it gefolch 
is en dat jo dit dogge neffens de [[$wgMetaNamespace:wisk-rie]].",

"confirmcheck"	=> "Ja, ik woe dit wier wiskje!",
"actioncomplete"	=> "Dien",
"deletedtext"	=> "\"$1\" is wiske.
Sjoch \"$2\" foar in list fan wat resint wiske is.",
"deletedarticle"	=> "\"$1\" is wiske",
"dellogpage"	=> "Wisk_loch",
"dellogpagetext" => "Dit is wat der resint wiske is.
(Tiden oanj&ucirc;n as UTC).
<ul>
</ul>
",

"deletionlog"	=> "wisk loch",
"reverted"		=> "Tebekset nei eardere ferzje",
"deletecomment"	=> "Reden foar it wiskjen",
"imagereverted"	=> "Tebeksette nei eardere ferzje is slagge.",
"rollback"		=> "Feroarings tebeksette",
"rollbacklink"	=> "feroaring tebeksette",
"rollbackfailed"	=> "Feroaring tebeksette net slagge",
"cantrollback"	=> "Disse feroaringt kin net tebek set, om't der mar ien skriuwer is.",
"alreadyrolled"	=> "Kin de feroaring fan [[$1]]
troch [[Br&ucirc;ker:$2|$2]] ([[Br&ucirc;ker oerlis:$2|Oerlis]]) net tebeksette;
inoar hat de feroaring tebekset, of oars wat oan de side feroare.

De l&ecirc;ste feroaring wie fan [[Br&ucirc;ker:$3|$3]] ([[Br&ucirc;ker oerlis:$3|Oerlis]]). ",
#   only shown if there is an edit comment
"editcomment"	=> "De gearfetting wie: \"<i>$1</i>\".", 
"revertpage"	=> "Tebek set ta de ferzje fan \"$1\"",

# Undelete
"undelete"		=> "Side werom set",
"undeletepage"	=> "Side besjen en werom sette",
"undeletepagetext" => "Dizze siden binne wiske, mar sitte noch yn it argyf en kinne weromset wurde.
(It argyf kin &uacute;t en troch leechmeitsje wurde.)",
"undeletearticle" => "Set side werom",
"undeleterevisions" => "$1 ferzjes yn it argyf",
"undeletehistory" => "Soenen jo dizze side weromsette, dan wurde alle ferzjes weromset as part
fan de skiednis. As der in nije side is mei dizze namme, dan wurd de hjoeddeiske ferzje <b>net</b>
troch de l&ecirc;ste ferzje &uacute;t dy weromsette skiednis ferfangen.",
"undeleterevision" => "Wiske side, sa't dy $1 wie.",
"undeletebtn" 	=> "Weromset!",
"undeletedarticle" => "\"$1\" weromset",
"undeletedtext"   => "It weromsette fan side [[$1]] is slagge.
(List fan resint [[$wgMetaNamespace:wisk-loch|wiske of weromsette siden]].",

# Contributions
#
"contributions"	=> "Br&ucirc;ker bydragen",
"mycontris"		=> "Myn bydragen",
"contribsub"	=> "Foar \"$1\"",
"nocontribs"	=> "Der binne gjin feroarings f&ucirc;n dyt't hjirmei oerienkomme.",
"ucnote"		=> "Dit binne dizze br&ucirc;ker's leste <b>$1</b> feroarings yn de l&ecirc;ste <b>$2</b> dagen.",
"uclinks"		=> "Besjoch de l&ecirc;ste $1 feroarings; besjoch de l&ecirc;ste $2 dagen.",
"uctop"		=> " (boppen)" ,

# What links here
#
"whatlinkshere"	=> "Wat is hjirmei keppele?",
"notargettitle"	=> "Gjin side",
"notargettext"	=> "Jo hawwe net sein fan hokfoar side jo dit witte wolle.",
"linklistsub"	=> "(List fan keppelings)",
"linkshere"		=> "Dizze siden binne hjirmei keppele:",
"nolinkshere"	=> "Gjinien side is hjirmei keppele!",
"isredirect"	=> "trochverwizing",

# Block/unblock IP
#
"blockip"		=> "Slut br&ucirc;ker &uacute;t",
"blockiptext"	=> "Br&ucirc;k dizze fjilden om in br&ucirc;ker fan skriuwtagong &uacute;t te sluten.
Dit soe allinnich omwillens fan fandalisme dwaan wurde moatte, sa't de
[[$wgMetaNamespace:Utslut-rie|&uacute;tslut-rie]] it oanjout.
Meld de krekte reden! Begelyk, neam de siden dy't oantaaste waarden.",
"ipaddress"		=> "Br&ucirc;kernamme of Ynternet-adres",
"ipbreason"		=> "Reden",
"ipbsubmit"		=> "Slut dizze br&ucirc;ker &uacute;t",
"badipaddress"	=> "Sa'n br&ucirc;ker is der net",
"noblockreason"	=> "Jo moatte de krekte reden opjaan.",
"blockipsuccesssub" => "Utsluten slagge",
"blockipsuccesstext" => "Br&ucirc;ker \"$1\" is &uacute;tsletten.<br>
(List fan [[Wiki:Ipblocklist|&uacute;tslette br&ucirc;kers]].)",
"unblockip"		=> "Lit br&ucirc;ker der wer yn",
"unblockiptext"	=> "Br&ucirc;k dizze fjilden om in br&ucirc;ker wer skriuwtagong te jaan.",
"ipusubmit"		=> "Lit dizze br&ucirc;ker der wer yn",
"ipusuccess"	=> "Br&ucirc;ker \"$1\" ynlitten",
"ipblocklist"	=> "List fan &uacute;tsletten Ynternet-adressen en br&ucirc;kersnammen",
"blocklistline"	=> "$\"3\", troch \"$2\" op $1",
"blocklink"		=> "slut &uacute;t",
"unblocklink"	=> "lit yn",
"contribslink"	=> "bydragen",
"autoblocker"	=> "Jo wienen &uacute;tsletten om't jo Ynternet-adres oerienkomt mei dat fan \"$1\".
Foar it &uacute;tslute fan dy br&ucirc;ker waard dizze reden joen: \"$2\".",

# Developer tools
#
"lockdb"		=> "Skoattelje de databank",
"unlockdb"  	=> "Untskoattelje de databank",
"lockdbtext"	=> "Salang as de databank skoattele is,
is foar br&ucirc;kers it feroarjen fan siden, ynstellings, folchlisten, ensfh. net mooglik.
Bef&ecirc;stigje dat dit is wat jo wolle, en dat jo de databank wer &ucirc;ntskoattelje sille 
as jo &ucirc;nderh&acirc;ld ree is.",
"unlockdbtext"	=> "As de databank &ucirc;ntskoattele makke wurdt,
is foar br&ucirc;kers it feroarjen fan siden, ynstelingen, folchlisten, ensfh, wer mooglik.
Bef&ecirc;stigje dat dit is wat jo wolle.",
"lockconfirm"	=> "Ja, ik wol wier de databank skoattelje.",
"unlockconfirm"	=> "Ja, ik wol wier de databank &ucirc;ntskoattelje.",
"lockbtn"		=> "Skoattelje de databank",
"unlockbtn"		=> "Untskoattelje de databank",
"locknoconfirm"	=> "Jo hawwe jo hanneling net bef&ecirc;stige.",
"lockdbsuccesssub" => "Databank is skoattele",
"unlockdbsuccesssub" => "Databank is &ucirc;ntskoattele",
"lockdbsuccesstext" => "De $wgSitename databank is skoattele.
<br>Tink derom en &ucirc;ntskoattele de databank as jo &ucirc;nderh&acirc;ld ree is.",
"unlockdbsuccesstext" => "De $wgSitename databank is &ucirc;ntskoattele.",

# SQL query
#
"asksql"		=> "SQL-fraach",
"asksqltext"	=> "Br&ucirc;k dizze fjilden foar in databank-fraach oan de $wgSitename databank.
Br&ucirc;k inkele oanheltekens ('likas dit') foar tekst.
Dit kin foar de tsjinner in soad wurk betsjutte. Br&ucirc;k dit dus net &ucirc;nnedig.",
"sqlislogged"	=> "(Alle fragen komme yn in lochtriem.)",
"sqlquery"		=> "Fraach",
"querybtn"		=> "Bied de fraach oan",
"selectonly"	=> "Oare fragen as \"SELECT\" binne foarbeh&acirc;lden oan
$wgSitename &ucirc;ntwiklers.",
"querysuccessful" => "Fraach slagge",


# Move page
#
"movepage"		=> "Titel feroarje",
"movepagetext"	=> "Feroaret de titel, mei beh&acirc;ld fan de sideskiednis.
De &acirc;lde titel wurdt in trochferwizing nei de nije.
Keppelings mei de &acirc;lde side wurde net feroare; 
[[Wiki:Maintenance|gean sels nei]] of't der d&ucirc;bele of misse ferwizings binne.
It hinget fan jo &ocirc;f of't de siden noch keppele binne sa't it w&ecirc;ze moat.

De titel wurdt '''net''' feroare as der al in side mei dy titel is, &uacute;tsein as it in side
s&ucirc;nder skiednis is en de side leech is of in trochferwizing is. Sa kinne jo in titel
daalks weromferoarje as jo in flater meitsje, mar jo kinne in oare side net oerskriuwe.",

"movepagetalktext" => "As der in oerlisside by heart, dan bliuwt dy oan de side keppele, '''&uacute;tsein''':
*De nije titel yn in oare nammeromte is,
*Der keppele oan de nije titel al in net-lege oerlisside is, of
*Jo d&ecirc;r net foar kieze.

Yn dizze gefallen is it oan jo hoe't jo de oerlisside ynfoegje wolle.",

"movearticle"	=> "Feroarje titel",
"movenologin"	=> "Net oameld",
"movenologintext" => "Jo moatte <a href=\""
. wfLocalUrl( "Wiki:Userlogin" ) 
. "\">oanmeld</a> w&ecirc;ze om in titel te feroarjen.",

"newtitle"		=> "As nije titel",
"movepagebtn"	=> "Feroarje titel",
"pagemovedsub"	=> "Feroarjen slagge",
"pagemovedtext"	=> "Titel \"[[$1]]\" feroare yn \"[[$2]]\".",
"articleexists"	=> "Der is al in side mei dy titel,
of oars is de titel dy't jo oanj&ucirc;n hawwe net tastean.
Besykje it op 'e nij.",

"talkexists"	=> "De titel is al feroare, mar de eardere oerlisside is 
net mear keppele om't der foar de nije titel ek al in oerlisside wie.
Gearfoegje de oerlissiden h&acirc;nmjittig.",

"movedto"		=> "werenamd as",
"moveoerlis"	=> "De oerlisside, as dy der is, moat oan de side keppele bliuwe.",
"talkpagemoved"	=> "De oerlisside is al noch keppele.",
"talkpagenotmoved" => "De oerlisside is <strong>net</strong> mear keppele.",

);


class LanguageFy extends LanguageUtf8 {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsFy ;
		return $wgDefaultUserOptionsFy ;
		}

	function getBookstoreList () {
		global $wgBookstoreListFy ;
		return $wgBookstoreListFy ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFy;
		return $wgNamespaceNamesFy;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesFy;
		return $wgNamespaceNamesFy[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesFy;

		foreach ( $wgNamespaceNamesFy as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if ( 0 == strcasecmp( "Special", $text ) ) return -1;
		return false;
	}

# Inherit specialPage()

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFy;
		return $wgQuickbarSettingsFy;
	}

	function getSkinNames() {
		global $wgSkinNamesFy;
		return $wgSkinNamesFy;
	}

	function getMathNames() {
		global $wgMathNamesFy;
		return $wgMathNamesFy;
	}
	
	function getDateFormats() {
		global $wgDateFormatsFy;
		return $wgDateFormatsFy;
	}

	function getUserToggles() {
		global $wgUserTogglesFy;
		return $wgUserTogglesFy;
	}

	function getLanguageNames() {
		global $wgLanguageNamesFy;
		return $wgLanguageNamesFy;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesFy;
		if ( ! array_key_exists( $code, $wgLanguageNamesFy ) ) {
			return "";
		}
		return $wgLanguageNamesFy[$code];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesFy;
		return $wgMonthNamesFy[$key-1];
	}
	
	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		global $wgMonthNamesFy;
		return $wgMonthNamesFy[$key-1];
	}
	
	function getMonthRegex()
	{
		global $wgMonthNamesFy;
		return implode( "|", $wgMonthNamesFy );
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsFy;
		return $wgMonthAbbreviationsFy[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesFy;
		return $wgWeekdayNamesFy[$key-1];
	}

 # Inherit userAdjust()
 
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

		$t = substr( $ts, 8, 2 ) . "." . substr( $ts, 10, 2 );
		return $t;
	}

# Inherit timeanddate()

# Inherit rfc1123()

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesFy;
		return $wgValidSpecialPagesFy;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesFy;
		return $wgSysopSpecialPagesFy;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesFy;
		return $wgDeveloperSpecialPagesFy;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesFy;
		return $wgAllMessagesFy[$key];
	}
	
# Inherit iconv()

# Inherit ucfirst()

# Inherit checkTitleEncoding( )
	
# Inherit stripForSearch()

# Inherit setAltEncoding()

# Inherit recodeForEdit()

# Inherit recodeInput() 

# Inherit replaceDates()

# Inherit isRTL()

}

?>
