<?php

/* Cymraeg - Welsh */

/* private */ $wgNamespaceNamesCy = array(
	-2 => "Media",
	-1 => "Arbennig",
	0  => "",
	1  => "Sgwrs",
	2  => "Defnyddiwr",
	3  => "Sgwrs_Defnyddiwr",
	4  => $wgMetaNamespace,
	5  => "Sgwrs_".$wgMetaNamespace,
	6  => "Delwedd",
	7  => "Sgwrs_Delwedd",
	8  => "MediaWiki",
	9  => "Sgwrs_MediaWiki",
       10  => "Nodyn",
       11  => "Sgwrs_Nodyn"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsCy = array(
	"Dim", "Sefydlog chwith", "Sefydlog de", "Arnawf de"
);

/* private */ $wgSkinNamesCy = array(
        'standard' => "Safonol",
        'nostalgia' => "Hiraeth",
        'cologneblue' => "Glas Cwlen",
        'smarty' => "Paddington",
        'montparnasse' => "Montparnasse",
        'davinci' => "DaVinci",
        'mono' => "Mono",
        'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);

/* private */ $wgMathNamesCy = array(
	"Rendrwch PNG o hyd",
	"HTML os yn syml iawn, PNG fel arall",
	"HTML os bosibl, PNG fel arall",
	"Gadewch fel TeX (am porwyr testun)",
	"Cymeradwedig am porwyr modern"
);

/* private */ $wgDateFormatsCy = array(
	"Dim dewis",
	"Ionawr 15, 2001",
	"15 Ionawr 2001",
	"2001 Ionawr 15",
	"2001-01-15"
);

/* private */ $wgUserTogglesCy = array(
	"hover"	 => "Dangos bocs hofren uwchben cysylltiadau wici",
	"underline" => "Tanllinellu cysylltiadau",
	"highlightbroken" => "Fformatio cysylltiadau wedi'i dorri <a href=\"\" class=\"new\">fel hyn</a> (dewis arall: fel hyn<a href=\"\" class=\"internal\">?</a>).",
	"justify" => "Unioni paragraffau",
	"hideminor" => "Cuddiwch golygiadau bach mewn newidiadau diweddar",
	"usenewrc" => "Newidiadau diweddar mwyhad (nid am pob porwr)",
	"numberheadings" => "Rhifwch teiltau yn awtomatig",
	"showtoolbar"=> "Dangos bar erfynbocs golygu",
	"editondblclick" => "Golygu tudalennau gyda clic dwbwl (JavaScript)",
	"editwidth" => "Mae gan bocs golygu lled llon",
        "editsection"  => "Galluogwch golygu adrannau trwy cysylltiadau [golygu]",
        "editsectiononrightclick" => "Galluogwch golygu adrannau trwy dde-clicio ar teitlau adran (JavaScript)",
        "showtoc" => "Dangoswch Taflen Cynnwys (am erthyglau gyda mwy na 3 pennawdau",
        "rememberpassword" => "Cofiwch allweddair dros sesiwnau",
	"watchdefault" => "Gwiliwch erthyglau newydd ac wedi adnewid",
	"minordefault" => "Marciwch pob golygiad fel un bach",
	"previewontop" => "Dangos blaenwelediad cyn y bocs golygu, nid ar ol e",
        "nocache" => "Anablwch casio tudanlen"
	
);

/* private */ $wgBookstoreListCy = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1",
        "Amazon.co.uk" => "http://www.amazon.co.uk/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesCy = array(
	"Dydd Sul", "Dydd Llun", "Dydd Mawrth", "Dydd Mercher", "Dydd Iau",
	"Dydd Gwener", "Dydd Sadwrn"
);

/* private */ $wgMonthNamesCy = array(
	"Ionawr", "Chwefror", "Mawrth", "Ebrill", "Mai", "Mehefin",
	"Gorffennaf", "Awst", "Medi", "Hydref", "Tachwedd",
	"Rhagfyr"
);

/* private */ $wgMonthAbbreviationsCy = array(
	"Ion", "Chwe", "Maw", "Ebr", "Mai", "Meh", "Gor", "Aws",
	"Med", "Hyd", "Tach", "Rhag"
);

 /* private */ $wgMagicWordsCy = array(
 #   ID                          CASE  SYNONYMS
    MAG_REDIRECT            => array( 0, "#redirect", "#ail-cyfeirio"               ),
    MAG_NOTOC               => array( 0, "__NOTOC__", "__DIMTAFLENCYNNWYS__"        ),
    MAG_NOEDITSECTION       => array( 0, "__NOEDITSECTION__", "__DIMADRANGOLYGU__"  ),
    MAG_START               => array( 0, "__START__", "__DECHRAU__"                 ),
    MAG_CURRENTMONTH        => array( 1, "CURRENTMONTH", "MISCYFOES"                ),
    MAG_CURRENTMONTHNAME    => array( 1, "CURRENTMONTHNAME", "ENWMISCYFOES"         ),
    MAG_CURRENTDAY          => array( 1, "CURRENTDAY", "DYDDIADCYFOES"              ),   
    MAG_CURRENTDAYNAME      => array( 1, "CURRENTDAYNAME", "ENWDYDDCYFOES"          ),
    MAG_CURRENTYEAR         => array( 1, "CURRENTYEAR", "FLWYDDYNCYFOES"            ),
    MAG_CURRENTTIME         => array( 1, "CURRENTTIME", "AMSERCYFOES"               ),
    MAG_NUMBEROFARTICLES    => array( 1, "NUMBEROFARTICLES","NIFEROERTHYGLAU"       ),
    MAG_CURRENTMONTHNAMEGEN => array( 1, "CURRENTMONTHNAMEGEN", "GENENWMISCYFOES"   ),
    MAG_MSG                 => array( 1, "MSG:"                                     ),
    MAG_SUBST               => array( 1, "SUBST:"                                   ),
    MAG_MSGNW               => array( 0, "MSGNW:"                                   ),
    MAG_END                 => array( 0, "DIWEDD"                                   ),
    MAG_IMG_THUMBNAIL       => array( 1, "ewin bawd", "bawd", "thumb", "thumbnail"  ),
    MAG_IMG_RIGHT           => array( 1, "de", "right"                              ),
    MAG_IMG_LEFT            => array( 1, "chwith", "left"                           ),
    MAG_IMG_NONE            => array( 1, "dim", "none"                              ),
    MAG_IMG_WIDTH           => array( 1, "$1px"                                     ),
    MAG_IMG_CENTER          => array( 1, "canol", "centre", "center"                ),
    MAG_INT                 => array( 0, "INT:"                                     )
);	

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesCy = array(
	"Userlogin" => "",
	"Userlogout" => "",
	"Preferences" => "Setiwch fy ffafraethau defnyddwr",
	"Watchlist" => "Rhestr gwylio fi",
	"Recentchanges" => "Tudalennau wedi adnewid yn diweddar",
	"Upload" => "Llwytho ffeiliau lluniau",
	"Imagelist" => "Rhestr lluniau",
	"Listusers" => "Defnyddwyr cofrestru",
	"Statistics" => "Ystadegau'r Safle",
	"Randompage" => "Erthygl hapgyrch",
	"Lonelypages" => "Erthyglau heb cysylltiadau",
	"Unusedimages" => "Lluniau heb cysylltiadau",
	"Popularpages" => "Erthyglau poblogol",
	"Wantedpages" => "Eisiau erthyglau am...",
	"Shortpages" => "Erthyglau byr",
	"Longpages" => "Erthyglau hir",
	"Newpages" => "Erthyglau newydd",
        "Ancientpages" => "Erthyglau hynach",
        "Deadendpages" => "Erthyglau heb cysylltiadau",
        "Intl" => "Cysylltiadau rhwng-ieithol",
	"Allpages" => "Pob tudalen gan teitl",
	"Ipblocklist" => "Cyfeiriadau IP wedi'i blocio",
	"Maintenance" => "Tudalen arofal",
	"Specialpages" => "",
	"Contributions" => "",
	"Emailuser" => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage" => "",
	"Booksources" => "Ffynnonellau llyfrau allanol",
        "Categories" => "Categorïau tudalennau",
        "Export" => "",
        "Version" => "Dangos fersiwn Mediawiki"
);

/* private */ $wgSysopSpecialPagesCy = array(
	"Blockip" => "Blociwch cyfeiriad IP",
	"Asksql" => "Gofynnwch y databas",
	"Undelete" => "Gwyliwch ac atgyweirio tundalennau wedi'u dileu",
        "Makesysop" => "Gwnewch sysop allan o defnyddiwr"
);

/* private */ $wgDeveloperSpecialPagesCy = array(
	"Lockdb" => "Databas darllen yn unig",
	"Unlockdb" => "Gwnewch y databas yn ysgrifennol"
);

/* private */ $wgAllMessagesCy = array(

# Bits of text used by many pages:
#
"categories" => "Categorïau tudalen",
"category" => "categori",
"category_header" => "Erthyglau mewn categori \"$1\"",
"subcategories" => "Is-categorïau",
"linktrail" => "/^((?:à|á|â|è|é|ê|ì|í|î|ï|ò|ó|ô|û|ŵ|ŷ|[a-z])+)(.*)\$/sD",
"mainpage" => "Prif tudalen",
"mainpagetext" => "Meddalwedd Wicipedia wedi sefydlu'n llwyddiannus",
"about"	=> "Amdano",
"aboutwikipedia" => "Amdano Wicipedia",
"aboutpage" => "$wgMetaNamespace:Amdano",
"help" => "Help",
"helppage" =>  "$wgMetaNamespace:Help",
"wikititlesuffix" => "$wgSitename",
"bugreports" => "Adroddiadau diffygion",
"bugreportspage" => "$wgMetaNamespace:Adroddiadau_diffygion",
"sitesupport" => "Rhoddion",
"sitesupportpage" => "", # If not set, won't appear. Can be wiki page or URL
"faq" => "COF",
"faqpage" => "$wgMetaNamespace:COF",
"edithelp" => "Help gyda golygu",
"edithelppage" => "$wgMetaNamespace:Sut_ydy_chi'n_golygu_tudalen",
"cancel" => "Dirymu",
"qbfind" => "Cael",
"qbbrowse" => "Pori",
"qbedit" => "Golygu",
"qbpageoptions" => "Dewysiadau tudalen",
"qbpageinfo" => "Gwybodaeth tudalen",
"qbmyoptions" => "Fy dewysiadau",
"qbspecialpages" => "Tudalennau arbennig",
"moredotdotdot"	=> "Mwy...",
"mypage" => "Fy nhudalen",
"mytalk" => "Sgwrs fi",
"disclaimers" => "Gwadiadau",
"disclaimerpage" => "$wgMetaNamespace:Gwadiad_cyffredin",
"currentevents" => "Digwyddiadau presennol",
"errorpagetitle" => "Gwall",
"returnto" =>  "Ewch yn ôl i $1.",
"fromwikipedia"	=> "Oddiwrth Wicipedia, y gwyddoniadur rhad.",
"whatlinkshere"	=> "Tudalennau sydd yn cysyllti fan hyn",
"help" => "Help",
"search" => "Chwilio",
"go" => "Mynd",
"history" => "Hanes y tudalen",
"printableversion" => "Fersiwn argraffiol",
"editthispage" => "Golygwch y tudalen hon",
"deletethispage" => "Dileuwch y tudalen hon",
"protectthispage" => "Amddiffynwch y tudalen hon",
"unprotectthispage" => "Di-amddiffynwch y tudalen hon",
"newpage" => "Tudalen newydd",
"talkpage" => "Sgwrsio amdano'r tudalen hon",
"postcomment" => "Postiwch esboniad",
"articlepage" => "Gwyliwch erthygl",
"subjectpage" => "Gwyliwch pwnc", # For compatibility
"userpage" => "Gwyliwch tudalen defnyddiwr",
"wikipediapage" => "Gwyliwch tudalen meta",
"imagepage" => "Gwyliwch tudalen llun",
"viewtalkpage" => "Gwyliwch sgwrs",
"otherlanguages" => "Ieithoed eraill",
"redirectedfrom" => "(Ail-cyfeiriad oddiwrth $1)",
"lastmodified" => "Pryd cafodd ei newid diwethaf $1.",
"viewcount" => "Mae'r tudalen hyn wedi cael ei gweld $1 o weithiau.",
"gnunote" => "Mae pob testun ar gael o dan term<a class=internal href='$wgScriptPath/GNU_FDL'>Goddefiad dogfennau rhad GNU</a>.",
"printsubtitle" => "(Oddiwrth http://www.wikipedia.org/)",
"protectedpage" => "Tudalen amddiffyniol",
"administrators" => "$wgMetaNamespace:Gweinyddwyr",
"sysoptitle" => "Mynediad Sysop yn unig",
"sysoptext" => "Mae'r peth rydych wedi gofyn amdano dim ond yn bosibl i ddefnyddwyr gyda statws \"sysop\".
Gwelwch $1.",
"developertitle" => "Mynediad Datblygwr yn unig",
"developertext"	=> "Mae'r peth rydych wedi gofyn amdano dim ond yn bosibl i ddefnyddwyr gyda statws \"datblygwr\".
Gwelwch $1.",
"bureaucrattitle" => "Mynediad Biwrocrat yn unig",
"bureaucrattext" => "Mae'r peth rydych wedi gofyn amdano dim ond yn bosibl i ddefnyddwyr gyda statws \"biwrocrat\"",
"nbytes" => "$1 bytes",
"go" => "Mynd",
"ok" => "OK",
"sitetitle" => "Wicipedia",
"sitesubtitle" => "Y Gwyddoniadur Rhydd",
"retrievedfrom" => "Wedi dod o \"$1\"",
"newmessages" => "Mae gen ti $1.",
"newmessageslink" => "Neges(eueon) newydd",
"editsection" => "golygu",
"toc" => "Taflen Cynnwys",
"showtoc" => "dangos",
"hidetoc" => "cuddio",
"thisisdeleted" => "Edrychwch at, neu atgyweirio $1?",
"restorelink" => "$1 golygiadau wedi eu dileuo",

# Main script and global functions
#
"nosuchaction" => "Does dim gweithred",
"nosuchactiontext" => "Dydy'r meddalwedd Wicipedia ddim yn deallt y gweithrediad mae'r URL yn gofyn iddo fe gwneud",
"nosuchspecialpage" => "Does dim tudalen arbennig",
"nospecialpagetext" => "Yr ydych wedi gofyn am tudalen arbennig dydy'r meddalwedd Wicipedia ddim yn adnabod.",

# General errors
#
"error"	=> "Gwall",
"databaseerror" => "Databas ar gam",
"dberrortext" => "Mae gwall cystrawen wedi digwydd ar y databas.
Wyrach mae hwn wedi digwydd am achos gofyniad chwilio anghyfreithlon (gwelwch $5), neu mae 'ne byg yn y meddalwedd.
Y gofyniad olaf triodd y databas oedd:
<blockquote><tt>$1</tt></blockquote>
oddiwrth ffwythiant \"<tt>$2</tt>\".
Dwedodd MySQL mae 'ne côd gwall \"<tt>$3: $4</tt>\".",
"dberrortextcl"              => "Mae gwall cystrawen wedi digwydd ar y databas.
Y gofyniad olaf triodd y databas oedd:
\"$1\"
oddiwrth ffwythiant \"$2\".
Dwedodd MySQL mae 'ne côd gwall \"$3: $4\".\n",
"noconnect" => "Ddim yn gallu cysylltu i'r databas ar $1",
"nodb" => "Ddim yn gallu dewis databas $1",
"cachederror" => "Dyma copi o'r stôr o'r tudalen rydych wedi gofyn, ac efallai dydi hi ddim yn cyfoes.",
"readonly" => "Databas ar gloi",
"enterlockreason" => "Rhowch reswm am paham mae'r databas yn cael eu gloi, yn cynnwys amcangyfrif pryd fydd y databas yn cael eu di-gloi",
"readonlytext" => "Mae'r databas Wicipedia wedi eu cloi yn erbyn erthyglau newydd ac adnewidiadau eraill, yn tebygol am gofalaeth trefn y databas -- fydd y databas yn ôl cyn bo hir.
Mae'r gweinyddwr wedi dweud yr achos cloi'r databas oedd:
<p>$1",
"missingarticle" => "Dydi'r databas ddim wedi dod o hyd i testun tudalen ddyler hi ffindio, sef \"$1\".
Dydi hwn ddim yn gwall y databas, ond debyg byg yn y meddalwedd.
Adroddwch hwn i gweinyddwr os gwelwch yn dda, a cofiwch sylwi'r URL.",
"internalerror" => "Gwall mewnol",
"filecopyerror" => "Ddim yn gallu copïo ffeil \"$1\" i \"$2\".",
"filerenameerror" => "Ddim yn gallu ail-enw ffeil \"$1\" i \"$2\".",
"filedeleteerror" => "Ddim yn gallu dileu ffeil \"$1\".",
"filenotfound" => "Ddim yn gallu ffeindio ffeil \"$1\".",
"unexpected" => "Gwerth annisgwyl: \"$1\"=\"$2\".",
"formerror" => "Gwall: ddim yn medru ymostwng y ffurflen",	
"badarticleerror" => "Mae'n amhosib perfformio'r gweithred hwn ar tudalen hon.",
"cannotdelete" => "Mae'n amhosib dileu y tudalen neu llun hwn. (Wyrach mae rhywun arall eisoes wedi eu dileu.)",
"badtitle" => "Teitl drwg",
"badtitletext" => "Mae'r teitl rydych wedi gofyn yn anilys, gwag, neu cysylltu'n anghywir rhwng ieithoedd neu wicïau.",
"perfdisabled" => "Sori! Mae'r nodwedd hon wedi cael eu anablo am achosion perfformiadau yn yr amserau brysyrach; dewch yn ôl rhwng 02:00 a 14:00 GMT a triwch eto.",
"perfdisabledsub" => "Dyma copi rydym wedi cadw ers $1:",
"wrong_wfQuery_params" => "Incorrect parameters to wfQuery()<br>
Function: $1<br>
Query: $2
",
"viewsource" => "Gwyliwch y ffynhonnell",
"protectedtext" => "Mae tudalen hon wedi cael eu gloi i gwahardd golygu'r tudalen. Mae nifer o rheswmau paham mae hwn wedi digwydd, gwelwch y tudalen
[[$wgMetaNamespace:Protected page]].

Ellwch gweld a copïo'r ffynhonnell y tudalen hon:",

# Login and logout pages
#
"logouttitle" => "Allgofnodi'r defnyddwr",
"logouttext" => "Yr ydych wedi allgofnodi.
Gallwch chi defnyddio'r Wicipedia yn anhysbys, neu gallwch chi mewngofnodi eto fel yr un defnyddwr neu un arall.\n",

"welcomecreation" => "<h2>Croeso, $1!</h2><p>Mae eich accownt wedi gael eu creu. Peidiwch ac anghofio i personaleiddio eich ffafraethau defnyddwr Wicipedia.",
"loginpagetitle" => "Mewngofnodi'r defnyddwr",
"yourname" => "Eich enw defnyddwr",
"yourpassword" => "Eich allweddair",
"yourpasswordagain" => "Ail-teipiwch allweddair",
"newusersonly" => " (defnyddwyr newydd yn unig)",
"remembermypassword" => "Cofiwch fy allweddair dros mwy nag un sesiwn.",
"loginproblem" => "<b>Mae problem efo'ch mewngofnodi.</b><br>Triwch eto!",
"alreadyloggedin" => "<font color=red><b>Defnyddwr $1, yr ydych eisioes wedi mewngofnodi!</b></font><br>\n",

"areyounew" => "Os ydych chi newydd wedi dod i Wicipedia ac yn eisiau cael accownt defnyddwr, teipiwch enw defnyddwr, felly teipiwch ac ail-teipiwch allweddair. Dydy eich cyfeiriad e-bost ddim yn gorfodol; os collwch eich allweddair mae'n bosibl i chi gofyn i gael hi eu anfon at y cyfeiriad e-bost hon.<br>\n",

"login"	=> "Mewngofnodi",
"loginprompt" => "Rhaid i chi galluogi cwcis i mewngofnodi i $wgSitename.",
"userlogin" => "Mewngofnodi",
"logout" => "Allgofnodi",
"userlogout" => "Allgofnodi",
"notloggedin" => "Nid wedi mewngofnodi",
"createaccount"	=> "Creuwch accownt newydd",
"createaccountmail" => "gan e-post",
"badretype" => "Dydy'r allweddgeiriau ddim yn cymharu.",
"userexists" => "Mae rhywun arall wedi dewis yr enw defnyddwr. Dewiswch un arall os gwelwch yn dda.",
"youremail" => "Eich cyfeiriad e-bost",
"yournick" => "Eich llysenw (am llofnod)",
"emailforlost" => "Os anghofiwch eich allweddair, cewch cael un newydd postwyd i'ch cyfeiriad e-bost.",
"loginerror" => "Problem mewngofnodi",
"nocookiesnew" => "Mae'r accownt defnyddiwr wedi gael eu creu, ond dydwch chi ddim wedi mewngofnodi. Mae $wgSitename yn defnyddio cwcis i mewngofnodi defnyddwyr. Rydych chi wedi anablo cwcis. Galluogwch nhw os welwch yn dda, felly mewngofnodwch gyda'ch enw defnyddiwr a cyfrinair newydd.",
"nocookieslogin" => "Mae $wgSitename yn defnyddio cwcis i mewngofnodi defnyddwyr. Rydych chi wedi anablo cwcis. Galluogwch nhw os welwch yn dda, a triwch eto.",
"noname" => "Dydi chi ddim wedi enwi enw defnyddwr dilys.",
"loginsuccesstitle" => "Mewngofnod llwyddiannus",
"loginsuccess" => "Yr ydych wedi mewngofnodi i Wicipedia fel \"$1\".",
"nosuchuser" => "Does dim defnyddwr gyda'r enw \"$1\".
Sicrhau rydych chi wedi sillafu'n iawn, neu creuwch accownt newydd gyda'r ffurflen isod.",
"wrongpassword"	=> "Mae'r allweddair rydych wedi teipio ddim yn cywir. Triwch eto, os gwelwch yn dda.",
"mailmypassword" => "E-postiwch allweddair newydd i fi",
"passwordremindertitle" => "Nodyn atgoffa allweddair oddiwrth Wicipedia",
"passwordremindertext" => "Mae rhywun (chi, yn tebygol, oddiwrth cyfeiriad IP $1) wedi gofyn iddi ni danfon allweddair mewngofnodi newydd Wicipedia.
Allweddair defnyddwr \"$2\" rwan yw \"$3\". Ddylwch chi mewngofnodi rwan a newid yr allweddair.",
"noemail" => "Does dim cyfeiriad e-bost wedi cofrestru dros defnyddwr \"$1\".",
"passwordsent" => "Mae allweddair newydd wedi gael eu ddanfon at y cyfeiriad e-bost cofrestredig am \"$1\". Mewngofnodwch eto, os gwelwch yn dda, ar ol i chi dderbyn yr allweddair.",
"loginend" => "",

# Edit page toolbar
"bold_sample" => "Testun cryf",
"bold_tip" => "Testun cryf",
"italic_sample" => "Testun italig",
"italic_tip" => "Testun italig",
"link_sample" => "Teitl cyswllt",
"link_tip" => "Cyswllt mewnol",
"extlink_sample" => "http://www.example.com cyswllt teitl",
"extlink_tip" => "Cyswllt allanol (cofiwch y rhagddodiad http:// )",
"headline_sample" => "Testun pennawd",
"headline_tip" => "Pennawd safon 2",
"math_sample" => "Mewnosodwch fformwla yma",
"math_tip" => "Fformwla mathemategol (LaTeX)",
"nowiki_sample" => "Mewnosodwch testun di-fformatedig yma",
"nowiki_tip" => "Anwybyddwch fformatiaeth wiki",
"image_sample" => "Example.jpg",
"image_tip" => "Delwedd mewnosod",
"media_sample" => "Example.mp3",
"media_tip" => "Cyswllt ffeil media",
"sig_tip" => "Eich llofnod gyda stamp amser",
"hr_tip" => "Llinell llorweddol (defnyddiwch yn cynnil)",
"infobox" => "Cliciwch botwm i cael testun enghraifft",

# Edit pages
#
"summary" => "Crynodeb",
"subject" => "Testun/pennawd",
"minoredit" => "Mae hwn yn golygiad bach",
"watchthis" => "Gwyliwch erthygl hon",
"savearticle" => "Cadw tudalen",
"preview" => "Blaenwelediad",
"showpreview" => "Gweler blaenwelediad",
"blockedtitle"	=> "Mae'r defnyddwr wedi gael eu blocio",
"blockedtext" => "Mae eich enw defnyddwr neu cyfeiriad IP wedi gael eu blocio gan $1. Y rheswm yw:<br>''$2''<p>Ellwch cysylltu $1 neu un o'r 
[[Wicipedia:administrators|swyddogion]] eraill i trafodi'r bloc.",
"whitelistedittitle" => "Rhaid mewngofnodi i golygu",
"whitelistedittext" => "Rhaid i chi [[Special:Userlogin|mewngofnodi]] i olygu erthyglau.",
"whitelistreadtitle" => "Rhaid mewngofnodi i ddarllen",
"whitelistreadtext" => "Rhaid i chi [[Special:Userlogin|mewngofnodi]] i ddarllen erthyglau.",
"whitelistacctitle" => "Ni chaniateir creu accownt",
"whitelistacctext" => "I gael caniatâd i creu accownt yn y wiki hon, rhaid i chi [[Special:Userlogin|mewngofnodi]] a chael y caniatâd priodol.",
"accmailtitle" => "Wedi danfon cyfrinair.",
"accmailtext" => "Mae'r cyfrinair am '$1' wedi danfon i $2.",
"newarticle" => "(Newydd)",
"newarticletext" =>
"Yr ydych wedi dilyn cysylltiad i tudalen sydd ddim wedi gael eu creu eto.
I creuo'r tudalen, dechreuwch teipio yn y bocs isaf 
(gwelwch y [[Wicipedia:Help|tudalen help]] am mwy o hysbys).
Os ydych yma trwy camgymeriad, cliciwch eich botwm '''nol'''.",
"anontalkpagetext" => "---- ''Dyma tudalen sgwrsio am defnyddwr sydd ddim eto wedi creu accownt, neu ddim yn eu defnyddio. Rhaid i ni defnyddio'r [[cyfeiriad IP]] rhifiadol i adnabod fe neu hi. Mae'n posib i llawer o bobl siario'r un cyfeiriad IP. Os ydych chi'n defnyddwr anhysbys ac yn teimlo mae esboniadau amherthynol wedi cael eu gwneud arnach chi, creuwch accownt neu mewngofnodwch i osgoi anhrefn gyda defnyddwyr anhysbys yn y dyfodol.'' ",
"noarticletext" => "(Does dim testun yn y tudalen hon eto)",
"updated" => "(Diweddariad)",
"note"	=> "<strong>Sylwch:</strong> ",
"previewnote" => "Cofiwch blaenwelediad ydi hwn, a dydi e ddim wedi cael eu chadw!",
"previewconflict" => "Mae blaenwelediad hwn yn dangos y testun yn yr ardal golygu uchaf, fel y fydd hi'n edrych os dewyswch chi arbed.",
"editing" => "Yn golygu $1",
"sectionedit" => " (rhan)",
"commentedit" => " (esboniad)",
"editconflict" => "Croestynnu golygyddol: $1",
"explainconflict" => "Mae rhywun arall wedi newid y tudalen hon ers i chi dechrau golygu hi. Mae'r ardal testun uchaf yn cynnwys testun y tudalen fel y mae hi rwan. Mae eich newidiadau yn ddangos yn yr ardal testun isaf.
Fydd rhaid i chi ymsoddi eich newidiadau i mewn i'r testun sydd mewn bod.
<b>Dim ond</b> y testun yn yr ardal testun uchaf fydd yn cael eu cadw pan rydwch yn gwasgu \"Cadw tudalen\".\n<p>",
"yourtext" => "Eich testun",
"storedversion" => "Fersiwn wedi cadw",
"editingold" => "<strong>RHYBUDD: Rydych yn golygu hen fersiwn y tudalen hon. Os cadwch hi, fydd unrhyw newidiadau hwyrach yn gael eu colli!.</strong>\n",
"yourdiff" => "Gwahaniaethau",
"copyrightwarning" => "Sylwch mae pob cyfraniad i Wicipedia yn gael eu rhyddhau o dan termau'r Trwydded Dogfen Rhydd GNU (gwelwch $1 am manylion).
Os nid ydych chi'n fodlon cael eich ysgrifeniad eu golygu heb trugaredd, a creu copïau dros y wê, peidiwch cyfranu'r ysgrifeniad yma.<br>
Hefyd, rydych chi'n addo chi yw'r awdur y cyfraniad, neu rydych chi wedi copïo fe oddiwrth y parth cyhoeddus neu rhyw modd rhydd tebyg.
<strong>PEIDIWCH CYFRANNU GWAITH O DAN HAWLFRAINT HEB CANIATÂD!</strong>",
"longpagewarning" => "RHYBUDD: Mae hyd y tudalen hon yn $1 kilobyte; mae rhai porwyr yn cael problemau yn golygu tudalennau hirach na 32kb.<br>
Ystyriwch torri'r tudalen i mewn i ddarnau llai, os gwelwch yn dda.",
"readonlywarning" => "RHYBUDD: Mae'r databas wedi cloi i gael eu trwsio,
felly fyddwch chi ddim yn medru cadw eich olygiadau rwan. Efalle fyddwch chi'n eisio tori-a-pastio'r
testun i mewn i ffeil testun, a cadw hi tan hwyrach.",
"protectedpagewarning" => "RHYBUDD:  Mae tudalen hon wedi eu gloi -- dim ond defnyddwyr
gyda braintiau 'sysop' sy'n medru eu olygu. Byddwch yn siwr rydych yn dilyn y
<a href='$wgScriptPath/$wgMetaNamespace:Protected_page_guidelines'>gwifrau tywys tudalen amddiffyn</a>.",

# History pages
#
"revhistory" => "Hanes cywiriadau",
"nohistory" => "Does dim hanes cywiriadau am tudalen hon.",
"revnotfound" => "Cywiriad nid wedi darganfod",
"revnotfoundtext" => "Ni ellir darganfod yr hen cywiriad y tudalen rydych wedi gofyn amdano. Gwiriwch yr URL rydych wedi defnyddio i darganfod y tudalen hon.\n",
"loadhist" => "Yn llwytho hanes y tudalen",
"currentrev" => "Diwygiad cyfoes",
"revisionasof"	=> "Diwygiad $1",
"cur" => "cyf",
"next"	=> "nesaf",
"last"	=> "olaf",
"orig"	=> "gwreidd",
"histlegend" => "Eglurhad: (cyf) = gwahaniaeth gyda fersiwn cyfoes,
(olaf) = gwahaniaeth gyda fersiwn gynt, M = golygiad mân",

# Diffs
#
"difference" => "(Gwahaniaethau rhwng fersiwnau)",
"loadingrev" => "yn llwytho diwygiad am wahaniaeth",
"lineno" => "Llinell $1:",
"editcurrent" => "Golygwch fersiwn cyfoes tudalen hon",

# Search results
#
"searchresults" => "Canlyniadau chwiliad",
"searchhelppage" => "Wicipedia: Yn chwilio",
"searchingwikipedia" => "Yn chwilio Wicipedia",
"searchresulttext" => "Am mwy o hysbys amdano chwilio Wicipedia, gwelwch $1.",
"searchquery" => "Am gofyniad \"$1\"",
"badquery" => "Gofyniad chwilio drwg",
"badquerytext" => "Roedd yn amhosibl i prosesu'ch gofyniad.
Mae'n tebygol roedd hyn am achos yr ydych wedi trio chwilio a gair gyda llai na tri llythyrau. Hefyd, wyrach rydych wedi cam-teipio'r gofyniad. Triwch gofyniad arall.",
"matchtotals" => "Mae'r gofyniad \"$1\" wedi cyfatebu $2 teitlau erthyglau, a'r testun oddiwrth $3 erthyglau.",
"nogomatch" => "Does dim erthygl gyda'r union teitl hon, yn trio chwiliad testun cyfan. ",
"titlematches" => "Teitlau erthygl yn cyfateb",
"notitlematches" => "Does dim teitlau erthygl yn cyfateb",
"textmatches" => "Testun erthygl yn cyfateb",
"notextmatches"	=> "Does dim testun erthyglau yn cyfateb",
"prevn"	=> "$1 gynt",
"nextn"	=> "$1 nesaf",
"viewprevnext"	=> "Gweler ($1) ($2) ($3).",
"showingresults" => "Yn dangos isod y <b>$1</b> canlyniadau yn dechrau gyda #<b>$2</b>.",
"nonefound" => "<strong>Sylwch</strong>: mae chwiliadau yn aml yn anlwyddiannus am achos mae'r chwiliad yn edrych a geiriau cyffredin fel \"y\" ac \"ac\",
sydd ddim yn cael eu mynegai.",
"powersearch" => "Chwilio",
"powersearchtext" => "
Edrychwch mewn lle-enw:<br>
$1<br>
$2 Rhestrwch ail-cyfeiriadau &nbsp; Chwiliwch am $3 $9",
"searchdisabled" => "<p>Mae'r peiriant chwilio'r holl databas wedi cael eu troi i ffwrdd i gwneud pethau'n hawddach ar y gwasanaethwr. Gobeithiwn fydd yn bosibl i troi'r peiriant ymlaen cyn bo hir, ond yn y cyfamser mae'n posibl gofyn Google:</p>",
"googlesearch" => "
<!--SiteSearch Google -->
<FORM method=GET action=\"http://www.google.co.uk/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.co.uk/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio
name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch
value=\"{$wgServer}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->
",
"blanknamespace" => "(Prif)",


# Preferences page
#
"preferences" => "ffafraethau",
"prefsnologin" => "Nid wedi mewngofnodi",
"prefsnologintext" => "Rhaid i chi <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">mewngofnodi</a>
i setio ffafraethau defnyddwr.",
"prefslogintext"  => "Yr ydych wedi mewngofnodi fel \"$1\".
$2 yw eich rhif hunaniaith mewnol.",
"prefsreset" => "Mae ffafraethau wedi gael eu ail-setio oddiwrth y storfa.",
"qbsettings" => "Gosodiadau bar-gyflym", 
"changepassword" => "Newydwch allweddair",
"skin"	=> "Croen",
"math"	=> "Rendro mathemateg",
"math_failure"	=> "wedi methu dosbarthu",
"math_unknown_error" => "gwall anhysbys",
"math_unknown_function"	=> "ffwythiant anhysbys ",
"math_lexing_error" => "gwall lecsio",
"math_syntax_error" => "gwall cystrawen",
"saveprefs" => "Cadw ffafraethau",
"resetprefs" => "Ail-setio ffafraethau",
"oldpassword" => "Hen allweddair",
"newpassword" => "Allweddair newydd",
"retypenew" => "Ail-teipiwch yr allweddair newydd",
"textboxsize" => "Maint y bocs testun",
"rows"	=> "Rhesi",
"columns" => "Colofnau",
"searchresultshead" => "Sefydliadau canlyniadau chwilio",
"resultsperpage" => "Hitiau i ddangos ar pob tudalen",
"contextlines" => "Llinellau i ddangos ar pob hit",
"contextchars" => "Characters of context per line",
"stubthreshold" => "Threshold for stub display",
"recentchangescount" => "Nifer o teitlau yn newidiadau diweddar",
"savedprefs" => "Mae eich ffafraethau wedi cael eu chadw.",
"timezonetext"	=> "Teipiwch y nifer o oriau mae eich amsel lleol yn wahân o'r amser y gwasanaethwr (UTC/GMT).",
"localtime" => "Amser lleol",
"timezoneoffset" => "Atred",
"servertime" => "Amser y gwasanaethwr yw",
"guesstimezone" => "Llenwch oddiwrth y porwr",
"emailflag" => "Anablo e-bost oddiwrth defnyddwyr eraill",
"defaultns" => "Gwyliwch yn llefydd-enw rhain:",

# Recent changes
#
"changes" => "newidiadau",
"recentchanges" => "Newidiadau diweddar",
"recentchangestext" => "Traciwch y newidiadau mor diweddar i'r Wicipedia ac i'r tudalen hon.",
"rcloaderr" => "Yn llwytho newidiadau diweddar",
"rcnote" => "Isod yw'r newidiadau <strong>$1</strong> olaf yn y <strong>$2</strong> dyddiau olaf.",
"rcnotefrom" => "Isod yw'r newidiadau ers <b>$2</b> (dangosir i fynu i <b>$1</b>).",
"rclistfrom" => "Dangos newidiadau newydd yn dechrau oddiwrth $1",
# "rclinks" => "Dangos y $1 newidiadau olaf yn y $2 oriau olaf / $3 dyddiau olaf",
"showhideminor" => "$1 golygiadau bach | $2 botiau | $3 defnyddwyr wedi mewngofnodi ",
"rclinks" => "Dangos y $1 newidiadau olaf yn y $2 dyddiau olaf.",
"rchide" => "yn ffurf $4; $1 golygiadon bach; $2 llefydd enw eilaidd; $3 golygiadon lluosrif.",
"rcliu"	=> "; $1 golygiadau gan defnyddwyr wedi mewngofnodi",
"diff"	=> "gwahan",
"hist"	=> "hanes",
"hide"	=> "cuddio",
"show"	=> "dangos",
"tableform" => "taflen",
"listform" => "rhestr",
"nchanges" => "$1 newidiadau",
"minoreditletter" => "B",
"newpageletter" => "N",

# Upload
#
"upload" => "Llwytho ffeil i fynu",
"uploadbtn" => "Llwytho ffeil i fynu",
"uploadlink" => "Llwytho lluniau i fynu",
"reupload" => "Ail-llwytho i fynu",
"reuploaddesc"	=> "Return to the upload form.",
"uploadnologin" => "Nid wedi mewngofnodi",
"uploadnologintext" => "Rhaid i chi bod wedi <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">mewngofnodi</a>
i lwytho ffeiliau i fynu.",
"uploadfile" => "Llwytho ffeil i fynu",
"uploaderror" => "Gwall yn llwytho ffeil i fynu",
"uploadtext" => "<strong>STOPIWCH!</strong> Cyn iddich chi llwytho lluniau yma, darllenwch a dilynwch <a href=\"" .
wfLocalUrlE( "Wicipedia:Polisi_defnyddio_lluniau" ) . "\">polisi defnyddio lluniau</a> Wicipedia os gwelwch yn dda.
<p>I gweld neu chwilio hen lluniau ewch i'r <a href=\"" . wfLocalUrlE( "Arbennig:Imagelist" ) .
"\">rhestr lluniau wedi llwytho</a>.
Mae pob llwyth a dileuo ffeil yn cael eu recordio ar y <a href=\"" .
wfLocalUrlE( "Wicipedia:Upload_log" ) . "\">log llwytho</a>.
<p>Defnyddwch y ffurflen isod i llwytho ffeil llun newydd i darluno eich erthyglau.
Ar y mwyafrif o porwyr, fyddwch yn gweld botwm \"Pori/Browse...\" i agor y dialog agor ffeil arferol.
Fydd dewis ffeil y llenwi enw'r ffeil yn y cae testun nesaf i'r botwm.
Mae rhaid i chi hefyd ticio'r blaidd i addo rydych chi ddim yn torri hawlfraintiau rhywun arall trwy llwytho'r ffeil.
Gwasgwch y botwm \"Llwytho/Upload\" i gorffen y llwyth.
Ellith hwn cymyd dipyn o amser os mae gennych chi cysylltiad rhyngrwyd araf.
<p>Y fformatiau gwell gennym ni yw JPEG am lluniau ffotograffiaeth, PNG
am lluniadau a delweddau iconydd eraill, ag OGG am seiniau.
Enwch eich ffeil yn disgrifiadol i osgoi anhrefn os gwelwch yn dda.
I cynnwys y llun mewn erthygl, defnyddwch cysylltiad yn y ffurf
<b>[[llun:ffeil.jpg]]</b> neu <b>[[llun:ffeil.png|testun arall]]</b>
neu <b>[[media:ffeil.ogg]]</b> am sain.
<p>Sylwch -- fel efo tudalennau Wicipedia, ellith pobl eraill golygu neu dileu eich ffeil os ydyn nhw'n meddwl fyddynt yn helpu'r gwyddoniadur, ac ellwch chi cael eich gwaharddio os ydych chi'n sarhau'r system.",
"uploadlog" => "log llwytho i fynu",
"uploadlogpage" => "log_llwytho_i_fynu",
"uploadlogpagetext" => "Isod mae rhestr o'r llwythu ffeil diweddarach.
Pob amser sy'n dangos yw amser y gwasanaethwr (UTC).
<ul>
</ul>
",
"filename" => "Enw ffeil",
"filedesc" => "Crynodeb",
"filestatus" => "Statws hawlfraint",
"filesource" => "Ffynhonnell",
"affirmation" => "Rwy'n addo mae'r hawlfraintiwr y ffeil hon wedi cytuno trwyddo'r ffeil o dan termau'r $1.",
"copyrightpage" =>  "$wgMetaNamespace:Hawlfraint",
"copyrightpagename" => "Hawlfraint $wgSitename",
"uploadedfiles"	=> "Ffeiliau wedi llwytho i fynu",
"noaffirmation" => "Rhaid i chi addo dydy'r llwyth i fynu ddim y torri unrhyw hawlfraint.",
"ignorewarning"	=> "Anwybyddwch y rhybudd, a chadwch y tudalen beth bynnag.",
"minlength" => "Rhaid enwau lluniau bod o leia tri llythrennau.",
"badfilename" => "Mae enw'r llun wedi newid i \"$1\".",
"badfiletype" => "Nid yw \".$1\" yn fformat ffeil lluniau cymeradwy.",
"largefile" => "Mae'n cymeradwy dydy lluniau nid mwy na 100k o faint.",
"successfulupload" => "Llwyth i fynu yn llwyddiannus",
"fileuploaded" => "Mae ffeil \"$1\" wedi llwytho'n llwyddiannnus.
Dilynwch y cyswllt hon: ($2) i'r tudalen disgrifiad a llenwch gwybodaeth amdano'r ffeil (ble mae'n dod o, pwy a creu o, beth bynnag arall rydych chi'n gwybod amdano'r ffeil.",
"uploadwarning" => "Rhybudd llwytho i fynu",
"savefile" => "Cadw ffeil",
"uploadedimage" => "\"$1\" wedi llwytho",
"uploaddisabled" => "Mae ddrwg gennym ni, mae uwchllwytho wedi anablo.",

# Image list
#
"imagelist" => "Rhestr delweddau",
"imagelisttext"	=> "Isod mae rhestr o $1 delweddau wedi trefnu $2.",
"getimagelist" => "yn nôl rhestr delweddau",
"ilshowmatch" => "Dangos pob delwedd gyda enwau yn cyfateb",
"ilsubmit" => "Chwilio",
"showlast" => "Dangos y $1 delweddau olaf wedi trefnu $2.",
"all" => "holl",
"byname" => "gan enw",
"bydate" => "gan dyddiad",
"bysize" => "gan maint",
"imgdelete" => "difl",
"imgdesc" => "disg",
"imglegend" => "Eglurhad: (disg) = dangos/golygu disgrifiad y delwedd.",
"imghistory" => "Hanes y delwedd",
"revertimg" => "dych",
"deleteimg" => "dil",
"deleteimgcompletely" => "dil",
"imghistlegend" => "Eglurhad: (cyf) = hon yw'r delwedd cyfoes, (dil) = dilewch yr hen fersiwn hon, (dych) = dychwelio i hen fersiwn hon.
<br><i>Cliciwch ar dyddiad i weld y delwedd ag oedd llwythiad ar y dyddiad hon</i>.",
"imagelinks" => "Cysylltiadau delwedd",
"linkstoimage" => "Mae'r tudalennau isod yn cysylltu i'r delwedd hon: ",
"nolinkstoimage" => "Does dim tudalen yn cysylltu i'r  delwedd hon. ",

# Statistics
#
"statistics" => "Ystadegau",
"sitestats" => "Ystadegau'r seit",
"userstats" => "Ystadegau defnyddwyr",
"sitestatstext" => "Mae <b>$1</b> tudalennau ar y databas.
Mae hyn yn cynnwys tudalennau \"sgwrs\", tudalennau amdano Wicipedia, tudalennau \"stwbyn\" bach, ail-cyfeirnodau, ac eraill sydd dim yn cymwysoli fel erthyglau. Ag eithrio y rheini, mae <b>$2</b> tudalennau yn tebyg yn erthyglau iawn.<p>
Mae 'ne wedi bod <b>$3</b> golygon o tudalennau, a <b>$4</b> tudalennau wedi golygu ers i'r meddalwedd gael eu sefydliad (12 Gorffennaf 2003).
Sef <b>$5</b> golygiadau pob tudalen, ar gyfartaledd, a <b>$6</b> golygon o bob golygiad.",
"userstatstext" => "Mae 'ne <b>$1</b> defnyddwyr wedi cofrestru.
(Mae <b>$2</b> yn gweinyddwyr (gwelwch $3)).",

# Maintenance Page
#
"maintenance" => "Maintenance page",
"maintnancepagetext" => "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
"maintenancebacklink" => "Back to Maintenance Page",
"disambiguations" => "Disambiguation pages",
"disambiguationspage" => "Wicipedia:Links_to_disambiguating_pages",
"disambiguationstext" => "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br>A page is treated as dismbiguation if it is linked from $1.<br>Links from other namespaces are <i>not</i> listed here.",
"doubleredirects" => "Double Redirects",
"doubleredirectstext" => "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br>\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects" => "Broken Redirects",
"brokenredirectstext" => "The following redirects link to a non-existing article.",
"selflinks" => "Pages with Self Links",
"selflinkstext"	=> "The following pages contain a link to themselves, which they should not.",
"mispeelings" => "Pages with misspellings",
"mispeelingstext" => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage" => "List of common misspellings",
"missinglanguagelinks" => "Missing Language Links",
"missinglanguagelinksbutton" => "Find missing language links for",
"missinglanguagelinkstext" => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans" => "Erthyglau heb cysylltiadau",
"lonelypages" => "Erthyglau heb cysylltiadau",
"unusedimages"	=> "Lluniau di-defnyddio",
"popularpages"	=> "Erthyglau poblogol",
"nviews" => "$1 golwgfeydd",
"wantedpages" => "Erthyglau mewn eisiau",
"nlinks" => "$1 cysylltiadau",
"allpages" => "Pob tudalennau",
"randompage" => "Erthygl hapgyrch",
"shortpages" => "Erthyglau byr",
"longpages" => "Erthyglau hir",
"deadendpages" => "Tudalennau heb cysylltiadau",  
"listusers" => "Rhestr defnyddwyr",
"specialpages" => "Erthyglau arbennig",
"spheading" => "Erthyglau arbennig",
"sysopspheading" => "Erthyglau arbennig am defnyddiad y sysop",
"developerspheading" => "Erthyglau arbennig am defnyddiad y datblygydd",
"protectpage" => "Sicrhau erthygl",
"recentchangeslinked" => "Newidiadau perthnasol",
"rclsub" => "(i erthyglau cysyllt oddiwrth \"$1\")",
"debug"	=> "Debug",
"newpages" => "Erthyglau newydd",
"ancientpages" => "Erthyglau hynach",
"intl" => "Cysylltiadau rhwng ieithau",
"movethispage"	=> "Symydwch tudalen hon",
"unusedimagestext" => "<p>Sylwch mae gwefannau eraill, e.e. y Wicipediau Rhwngwladol, yn medru cysylltu at llun gyda URL uniongychol, felly mae'n bosibl dangos enw ffeil yma er gwaethaf mae hi'n dal mewn iws.",
"booksources" => "Ffynonellau llyfrau",
"booksourcetext" => "Isod mae rhestr cysylltiadau i gwefannau eraill sydd yn gwerthu llyfrau newydd ac ail-law, ac wyrach mae ganddynt gwybodaeth am y llyfrau rydych yn chwilio amdano.
Does gan Wicipedia dim cysylltiad gyda unrhyw o'r masnachau, a dydy rhestr hon ddim yn cymeradwyaeth o honnynt.",
"alphaindexline" => "$1 i $2",
"version" => "Fersiwn",

# Email this user
#
"mailnologin" => "Dim cyfeiriad i anfon",
"mailnologintext" => "Rhaid i chi wedi <a href=\"" .
  wfLocalUrl( "Arbennig:Mewngofnodidefnyddwr" ) . "\">mewngofnodi</a>
a rhoi cyfeiriad e-bost dilyn yn eich <a href=\"" .
  wfLocalUrl( "Arbennig:Ffafraethau" ) . "\">ffafraethau</a>
i anfon e-bost i ddefnyddwyr eraill.",
"emailuser" => "Anfon e-bost i defnyddwr hwn",
"emailpage" => "Anfon e-bost i defnyddwr",
"emailpagetext"	 => "Os yw defnyddwr hwn wedi rhoi cyfeiriad e-bost yn eu ffafraethau, fydd y ffurf isod yn anfon un neges iddo ef. Fydd y cyfeiriad e-bost rydych chi wedi rhoi yn eich ffafraethau yn dangos yn yr \"Oddiwrth\" cyfeiriad yr e-bost, felly fydd y defnyddwr arall yn gallu ateb.",
"defemailsubject" => "e-post $wgSitename",				       
"noemailtitle" => "Dim cyfeiriad e-bost",
"noemailtext" => "Dydy defnyddwr hwn ddim wedi rhoi cyfeiriad e-bost dilys, neu mae e wedi dewis nid i dderbyn e-bost oddiwrth defnyddwyr eraill.",
"emailfrom" => "Oddiwrth",
"emailto" => "I",
"emailsubject" => "Pwnc",
"emailmessage" => "Neges",
"emailsend" => "Anfon",
"emailsent" => "Neges e-bost wedi danfon",
"emailsenttext" => "Mae eich neges e-bost wedi gael ei anfon.",

# Watchlist
#
"watchlist" => "Fy rhestr gwylio",
"watchlistsub" => "(am defnyddwr \"$1\")",
"nowatchlist"  => "Does ganddoch chi ddim eitem ar eich rhestr gwylio.",
"watchnologin"	=> "Dydych chi ddim wedi mewngofnodi",
"watchnologintext" => "Rhaid i chi bod wedi <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">mewngofnodi</a>
i adnewid eich rhestr gwylio.",
"addedwatch" => "Wedi adio i'ch rhestr gwylio",
"addedwatchtext" => "Mae tudalen \"$1\" wedi gael eu ychwanegu i eich <a href=\"" .
  wfLocalUrl( "Arbennig:Rhestr_gwylio" ) . "\">rhestr gwylio</a>.
Pan fydd y tudalen hon, a'i tudalen Sgwrs, yn newid, fyddynt yn dangos  <b>yn cryf</b> yn y <a href=\"" .
  wfLocalUrl( "Arbennig:Newidiadau_diweddar" ) . "\">rhestr newidiadau diweddar</a>, i bod yn hawsach i gweld.</p>

<p>Os ydych chi'n eisiau cael gwared ar y tudalen yn hwyrach, cliciwch ar \"Stopiwch gwylio\" yn y bar ar y chwith.",
"removedwatch" => "Wedi diswyddo oddiwrth y rhestr gwylio",
"removedwatchtext" => "Mae tudalen \"$1\" wedi cael ei diswyddo oddiwrth eich rhestr gwylio.",
"watchthispage"	=> "Gwyliwch y tudalen hon",
"unwatchthispage" => "Stopiwch gwylio",
"notanarticle"	=> "Nid erthygl",
"watchnochange" => "Does dim o'r erthyglau rydych chi'n gwylio wedi golygu yn yr amser sy'n dangos.",
"watchdetails" => "(Yn gwylio $1 tudalennau, nid yn cyfri tudalennau sgwrs;
wedi olygu $2 tudalennau ers y toriad;
$3...
<a href='$4'>dangos ac olygu y rhestr cyfan</a>.)",
"watchmethod-recent"=> "gwiriwch golygiadau diweddar am tudalennau gwyliad",
"watchmethod-list" => "yn gwirio tudalennau gwyliad am olygiadau diweddar",
"removechecked" => "Dileuwch eitemau sydd gyda tic o'ch rhestr gwylio",
"watchlistcontains" => "Mae eich rhestr gwylio yn cynnwys $1 tudalennau.",
"watcheditlist"	=> "Dyma rhestr wyddorol o'r tudalennau rydych yn wylio.
Ticiwch blwchau y tudalennau rydych eisiau symud o'ch rhestr gwylio, a cliciwch
y botwm 'dileu' ar gwaelod y sgrîn.",
"removingchecked" => "Yn dileu'r eitemau rydych wedi gofyn o'ch rhestr gwylio...",
"couldntremove" => "Wedi methu dileu eitem '$1'...",
"iteminvalidname" => "Problem gyda eitem '$1', enw annilys...",
"wlnote" => "Isod yw'r $1 newidiadau olaf yn y <b>$2</b> oriau diwethaf.",
"wlshowlast" => "Dangos y $1 oriau $2 dyddiau $3 diwethaf",
"wlsaved" => "Dyma copi o'ch rhestr gwylio rydym ni wedi cadw.",

# Delete/protect/revert
#
"deletepage" => "Dileuwch y tudalen",
"confirm" => "Cadarnhau",
"excontent" => "y cynnwys oedd:",
"exbeforeblank" => "y cynnwys cyn blancio oedd:",
"exblank" => "y tudalen oedd yn wâg",
"confirmdelete" => "Cadarnhaewch y dileuad",
"deletesub" => "(Yn dileuo \"$1\")",
"historywarning" => "Rhubydd: Mae hanes gan y tudalen yr ydych yn mynd i dileuo: ",
"confirmdeletetext" => "Rydych chi'n mynd i dileu erthygl neu llun yn parhaol, hefyd gyda'u hanes, oddiwrth y databas.
Cadarnhaewch yr ydych yn bwriadu gwneud hwn, ac yr ydych yn ddeallt y canlyniad, ac yr ydych yn gwneud hwn yn ôl [[Wicipedia:Polisi]].",
"confirmcheck" => "Ie, 'Dwi eisio dileu hwn mewn gwirionedd.",
"actioncomplete" => "Gweithred llwyr",
"deletedtext" => "Mae \"$1\" wedi eu dileu.
Gwelwch $2 am cofnod o dileuon diweddar.",
"deletedarticle" => "wedi dileu \"$1\"",
"dellogpage" => "Log_dileuo",
"dellogpagetext" => "Isod mae rhestr o'r dileuon diweddarach.
<ul>
</ul>
",
"deletionlog" => "Log dileuon",
"reverted" => "Wedi mynd nôl i fersiwn gynt",
"deletecomment"	=> "Achos dileuad",
"imagereverted" => "Gwrthdroad i fersiwn gynt yn llwyddiannus.",
"rollback" => "Roliwch golygon yn ôl",
"rollbacklink" => "rolio nôl",
"cantrollback" => "Ddim yn gallu gwrthdroi golygiad; y cyfrannwr olaf oedd yr unrhyw awdur yr erthygl hon.",
"alreadyrolled"	=> "Amhosib rolio nôl golygiad olaf [[$1]]
gan [[Defnyddwr:$2|$2]] ([[Sgwrs defnyddwr:$2|Sgwrs]]); mae rhywun arall yn barod wedi olygu neu rolio nôl yr erthygl. 

[[Defnyddwr:$3|$3]] ([[Sgwrs defnyddwr:$3|Sgwrs]] gwneuthoedd yr olygiad olaf). ",
#   only shown if there is an edit comment
"editcomment" => "Crynodeb y golygiad oedd: \"<i>$1</i>\".", 
"revertpage" => "Wedi gwrthdroi i golygiad olaf gan $1",
"protectlogpage" => "Log_amdiffyno",
"protectlogtext" => "Isod mae rhestr o cloion/datgloion tudalennau.
Gwelwch [[$wgMetaNamespace:Tudalen amddiffynol]] am mwy o wybodaeth.",
"protectedarticle" => "wedi amddiffyno [[:$1]]",
"unprotectedarticle" => "wedi di-amddiffyno [[:$1]]",

# Undelete   
"undelete" => "Gwrthdroi tudalen wedi dileuo",
"undeletepage" => "Gwyliwch ac adferiwch tudalennau wedi dileuo",
"undeletepagetext" => "Mae'r tudalennau isod wedi cael eu dileuo ond mae nhw'n dal yn yr archif ac maen bosibl adferio nhw. Mae'r archif yn cael eu glanhau o dro i dro.",
"undeletearticle" => "Adferiwch erthygl wedi dileu",
"undeleterevisions" => "$1 fersiwnau yn yr archif",
"undeletehistory" => "Os adferiwch y tudalen, fydd holl y fersiwnau yn gael eu adferio yn yr hanes. Os mae tudalen newydd wedi gael eu creu ers i'r tudalen bod yn dileu, fydd y fersiwnau adferol yn dangos yn yr hanes gynt ond ni fydd y fersiwn cyfoes yn gael eu allosodi.",
"undeleterevision" => "wedi dileu fersiwn $1",
"undeletebtn" => "Adferiwch!",
"undeletedarticle" => "wedi adferio \"$1\"",

"undeletedtext" => "Mae'r erthygl [[$1]] wedi cael eu adferio'n llwyddiannus.
Gwyliwch [[Wicipedia:Log_dileuon]] am record dileuon ac adferion diweddar.",

# Contributions
#
"contributions"	=> "Cyfraniadau defnyddwr",
"mycontris" => "Fy nghyfraniadau",
"contribsub" => "Dros $1",
"nocontribs" => "Dim wedi dod o hyd i newidiadau gyda criterion hyn.",
"ucnote" => "Isod mae y <b>$1</b> newidiadau yn y <b>$2</b> dyddiau olaf am defnyddwr hwn.",
"uclinks" => "Gwelwch y $1 newidiadau olaf; gwelwch y $2 dyddiau olaf.",
"uctop"	=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "Beth sy'n cysylltu yma",
"notargettitle" => "Dim targed",
"notargettext" => "Dydych chi ddim wedi dewis tudalen targed neu defnyddwr.",
"linklistsub"  => "(Rhestr cysylltiadau)",
"linkshere" => "Mae'r tudalennau isod yn cysylltu yma:",
"nolinkshere" => "Does dim tudalennau yn cysylltu yma.",
"isredirect" => "tudalen ail-cyfeirnod",

# Block/unblock IP
#
"blockip" => "Blociwch cyfeiriad IP",
"blockiptext" => "Defnyddwch y ffurflen isod i blocio mynedfa ysgrifenol oddiwrth cyfeiriad IP cymharol.
Ddylwch dim ond gwneud hwn i stopio fandaliaeth, yn dilyn a  [[Wicipedia:Polisi|polisi Wicipedia]].
Llenwch rheswm am y bloc, isod (e.e. enwch y tudalennau a oedd wedi fandalo).",
"ipaddress" => "Cyfeiriad IP",
"ipbexpiry" => "Diwedd",
"ipbreason" => "Achos",
"ipbsubmit" => "Blociwch y cyfeiriad hwn",
"badipaddress" => "Dydy'r cyfeiriad IP ddim yn ddilys.",
"noblockreason" => "Rhaid i chi rhoi rheswm am y bloc.",
"blockipsuccesssub" => "Bloc yn llwyddiannus",
"blockipsuccesstext" => "Mae cyfeiriad IP \"$1\" wedi cael eu blocio.
<br>Gwelwch [[Arbennig:Ipblocklist|rhestr bloc IP]] i arolygu blociau.",
"unblockip" => "Di-blociwch cyfeiriad IP",
"unblockiptext"	=> "Defnyddwch y ffurflen isod i di-blocio mynedfa ysgrifenol i cyfeiriad IP sydd wedi cael eu blocio'n gynt.",
"ipusubmit" => "Di-blociwch y cyfeiriad hwn",
"ipusuccess" => "Cyfeiriad IP \"$1\" wedi di-blocio",
"ipblocklist" => "Rhestr cyfeiriadau IP wedi blocio",
"blocklistline"	=> "$1, $2 wedi blocio $3",
"blocklink" => "bloc",
"unblocklink" => "di-bloc",
"contribslink" => "cyfraniadau",
"autoblocker" => "Wedi cloi'n awtomatig am achos rydych chi'n rhannu cyfeiriad IP gyda \"$1\". Rheswm \"$2\".",
"blocklogpage" => "Log_blociau",
"blocklogentry"	=> 'wedi blocio "$1" efo amser diwedd o $2',
"blocklogtext" => "Dyma log o pryd mae cyfeiriadau wedi cael eu blocio a datblocio. Dydy cyfeiriad 
a sydd wedi blocio'n awtomatig ddim yn cael eu ddangos yma. Gwelwch [[Special:Ipblocklist|rhestr block IP]] am
y rhestr o blociau a gwaharddiadau sydd yn effeithiol rwan.",
"unblocklogentry" => 'wedi datblocio "$1"',
"range_block_disabled" => "Mae gallu sysop i creu dewis o blociau wedi anablo.",
"ipb_expiry_invalid" => "Amser diwedd ddim yn dilys.",
"ip_range_invalid" => "Dewis IP annilys.\n",

# Developer tools - left untranslated as the developers would probably
#  prefer them in English!
#
"lockdb" => "Lock database",
"unlockdb" => "Unlock database",
"lockdbtext" => "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm" => "Yes, I really want to lock the database.",
"unlockconfirm"	=> "Yes, I really want to unlock the database.",
"lockbtn" => "Lock database",
"unlockbtn" => "Unlock database",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Database lock succeeded",
"unlockdbsuccesssub" => "Database lock removed",
"lockdbsuccesstext" => "The database has been locked.
<br>Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The database has been unlocked.",

# SQL query -- left untranslated
#
"asksql" => "SQL query",
"asksqltext" => "Use the form below to make a direct query of the
Wicipedia database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlquery" => "Enter query",
"querybtn" => "Submit query",
"selectonly" => "Queries other than \"SELECT\" are restricted to
Wicipedia developers.",
"querysuccessful" => "Query successful",

# Make sysop
"makesysoptitle" => "Gwnewch sysop allan o defnyddiwr",
"makesysoptext"	=> "Defnyddiwch y ffurflen hon i troi defnyddiwr cyffredin i gweinyddwr.  
Teipiwch enw'r defnyddiwr yn y blwch a cliciwch y botwm i troi'r defnyddiwr i gweinyddwr",
"makesysopname"	=> "Enw'r defnyddiwr:",
"makesysopsubmit" => "Gwnewch y defnyddiwr hwn yn gweinyddwr",
"makesysopok" => "<b>Mae defnyddwr '$1' rwan yn gweinyddwr</b>",
"makesysopfail"	=> "<b>Wedi methu troi defnyddwr '$1' i gweinyddwr. (Ydych chi wedi sillafu'r enw'n iawn?)</b>",
"setbureaucratflag" => "Gosod y fflag biwrocrat",
"bureaucratlog"	=> "Log_biwrocrat",
"bureaucratlogentry" => " gosod $1: $2",

# Move page
#
"movepage" => "Symud tudalen",
"movepagetext" => "Fydd defnyddio'r ffurflen isod yn ail-enwi tudalen, symud eu hanes gyfan i'r enw newydd.
Fydd yr hen teitl yn dod tudalen ail-cyfeiriad i'r teitl newydd. 
Ni fydd cysylltiadau i'r hen teitl yn newid; mae rhaid i chi gwirio mae cysylltau'n dal yn mynd i'r lle mae angen iddyn nhw mynd!

Sylwch fydd y tudalen '''ddim''' yn symud os mae 'ne tudalen efo'r enw newydd yn barod ar y databas (sef os mae hi'n gwâg neu yn ail-cyfeiriad heb unrhyw hanes golygu). Mae'n posibl i chi ail-enwi tudalen yn ôl i lle oedd hi os ydych chi wedi gwneud camgymeriad, ac mae'n amhosibl i ysgrifennu dros tudalen sydd barod yn bodoli.

<b>RHYBUDD!</b>
Ellith hwn bod newid sydyn a llym i tudalen poblogol; byddwch yn siwr rydych chi'n deallt y canlyniadau cyn iddich chi mynd ymlaen gyda hwn.",
"movepagetalktext" => "Fydd y tudalen sgwrs , os oes ne un, yn symud gyda tudalen hon '''ac eithrio:'''
*rydych yn symud y tudalen wrth llefydd-enw,
*mae tudalen sgwrs di-wâg yn barod efo'r enw newydd, neu
*rydych chi'n di-ticio'r blwch isod.",
"movearticle" => "Symud tudalen",
"movenologin" => "Nid wedi mewngofnodi",
"movenologintext" => "Rhaid i chi bod defnyddwr cofrestredig ac wedi <a href=\"" .
  wfLocalUrl( "Arbennig:Userlogin" ) . "\">mewngofnodi</a>
to move a page.",
"newtitle" => "i teitl newydd",
"movepagebtn" => "Symud tudalen",
"pagemovedsub"	=> "Symud yn llwyddiannus",
"pagemovedtext" => "Mae tudalen \"[[$1]]\" wedi symud i \"[[$2]]\".",
"articleexists" => "Mae tudalen gyda'r enw newydd yn bodoli'n barod, neu mae eich enw newydd ddim yn dilys.
Dewiswch enw newydd os gwelwch yn dda.",
"talkexists" => "Mae'r tudalen wedi symud yn llwyddiannus, ond roedd hi'n amhosibl symud y tudalen sgwrs am achos roedd ne un efo'r teitl newydd yn bodoli'n barod. Cysylltwch nhw eich hun, os gwelwch yn dda.",
"movedto" => "symud i",
"movetalk" => "Symud tudalen \"sgwrs\" hefyd, os oes un.",
"talkpagemoved" => "Mae'r tudalen sgwrs hefyd wedi symud.",
"talkpagenotmoved" => "Dydy'r tudalen sgwrs <strong>ddim</strong> wedi symud.",
"1movedto2" => "$1 wedi symud i $2",

# Export

"export" => "Export pages",
"exporttext" => "You can export the text and editing history of a particular
page or set of pages wrapped in some XML; this can then be imported into another
wiki running MediaWiki software, transformed, or just kept for your private
amusement.",
"exportcuronly"	=> "Include only the current revision, not the full history",

# Namespace 8 related

"allmessages" => "Holl_negeseuon",
"allmessagestext" => "Dyma rhestr holl y negeseuon ar gael yn y lle-enw MediaWiki: ",

# Thumbnails

"thumbnail-more" => "Helaethwch",

#Math 
           'mw_math_png' => "Rendrwch PNG o hyd", 
           'mw_math_simple' => "HTML os yn syml iawn, PNG fel arall", 
           'mw_math_html' => "HTML os bosibl, PNG fel arall", 
           'mw_math_source' => "Gadewch fel TeX (am porwyr testun)", 
           'mw_math_modern' => "Cymeradwedig am porwyr modern", 
           'mw_math_mathml' => 'MathML', 
    
);  

require_once( "LanguageUtf8.php" );

class LanguageCy extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListCy;
		return $wgBookstoreListCy;
	}

	function getNamespaces() {
		global $wgNamespaceNamesCy;
		return $wgNamespaceNamesCy;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesCy;
		return $wgNamespaceNamesCy[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesCy;

		foreach ( $wgNamespaceNamesCy as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Fallback to English names for compatibility
		return Language::getNsIndex( $text );
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsCy;
		return $wgQuickbarSettingsCy;
	}

	function getSkinNames() {
		global $wgSkinNamesCy;
		return $wgSkinNamesCy;
	}

        function getMathNames() {
                global $wgMathNamesCy;
                return $wgMathNamesCy;
        }

	function getDateFormats() {
		global $wgDateFormatsCy;
		return $wgDateFormatsCy;
	}

	function getUserToggles() {
		global $wgUserTogglesCy;
		return $wgUserTogglesCy;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesCy;
		return $wgMonthNamesCy[$key-1];
	}
	
	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsCy;
		return $wgMonthAbbreviationsCy[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesCy;
		return $wgWeekdayNamesCy[$key-1];
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesCy;
		return $wgValidSpecialPagesCy;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesCy;
		return $wgSysopSpecialPagesCy;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesCy;
		return $wgDeveloperSpecialPagesCy;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesCy;
		return @$wgAllMessagesCy[$key];
	}
	
	function getAllMessages()
	{
		global $wgAllMessagesCy;
		return $wgAllMessagesCy;
	}

	function getMagicWords() 
	{
		global $wgMagicWordsCy, $wgMagicWordsEn;
		return $wgMagicWordsCy + $wgMagicWordsEn;
	}
}

?>
