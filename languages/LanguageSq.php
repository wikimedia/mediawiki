<?php

require_once("LanguageUtf8.php");

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

/* private */ $wgNamespaceNamesSq = array(
	-2	=> "Media",
	-1	=> "Speciale",
	0	=> "",
	1	=> "Diskutim",
	2	=> "Përdoruesi",
	3	=> "Përdoruesi_diskutim",
	4	=> $wgMetaNamespace,
	5	=> $wgMetaNamespace . "_diskutim",
	6	=> "Figura",
	7	=> "Figura_diskutim",
	8	=> "MediaWiki",
	9	=> "MediaWiki_diskutim",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSq = array(
	"Asgjë", "Lidhur majtas", "Lidhur djathtas", "Fluturo majtas"
);

/* private */ $wgSkinNamesSq = array(
	'standard' => "Standarte",
	'nostalgia' => "Nostalgjike",
	'cologneblue' => "Kolonjë Blu",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);


/* private */ $wgDateFormatsSq = array(
	"Pa preferencë",
	"Janar 15, 2001",
	"15 Janar 2001",
	"2001 Janar 15"
);


/* Please customize this list... */


# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesSq = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Vendos preferimet e mia",
	"Watchlist"		=> "Lista mbikqyrëse",
	"Recentchanges" => "Ndryshimet e fundit",
	"Upload"		=> "Jep skedar",
	"Imagelist"		=> "Lista e figurave",
	"Listusers"		=> "Përdorues të rregjistruar",
	"Statistics"	=> "Statistikat e faqeve",
	"Randompage"	=> "Artikull kuturu",

	"Lonelypages"	=> "Faqe të palidhura",
	"Unusedimages"	=> "Figura të papërdorura",
	"Popularpages"	=> "Artikuj të frekuentuar shpesh",
	"Wantedpages"	=> "Artikuj më të dëshiruar",
	"Shortpages"	=> "Artikuj të shkurtër",
	"Longpages"		=> "Artikuj të gjatë",
	"Newpages"		=> "Artikuj të rinj",
	"Ancientpages"	=> "Artikuj më të vjetër",
	"Allpages"		=> "Të gjitha faqet sipas emrave",

	"Ipblocklist"	=> "Përdoruesit dhe IP-të e bllokuara",
	"Maintenance" => "Faqe mirëmbajtëse",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Burime të jashtme librash",
	"Export"		=> "XML export",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesSq = array(
	"Blockip"		=> "Blloko një përdorues/IP adresë",
	"Asksql"		=> "Pyet rregjistrin",
	"Undelete"		=> "Restauro faqet e grisura"
);

/* private */ $wgDeveloperSpecialPagesSq = array(
	"Lockdb"		=> "Bëje rregjistrin vetëm për tu lexuar",
	"Unlockdb"		=> "Lejo mundësinë e shrimit në rregjistër",
);

/* private */ $wgAllMessagesSq = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles

"tog-hover"		=> "Trego kutine fluturuse sipër lidhjeve wiki",
"tog-underline" => "Nënvizo lidhjet",
"tog-highlightbroken" => "Trego lidhjet e faqeve bosh <a href=\"\" class=\"new\">kështu </a> (ndryshe: kështu<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "Rregullim i kryeradhës",
"tog-hideminor" => "Fshih redaktimet e vogla në ndryshimet e fundit",
"tog-usenewrc" => "Ndryshimet e fundit me formatin e ri (jo për të gjithë shfletuesit)",
"tog-numberheadings" => "Numëro automatikish mbishkrimet",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editondblclick" => "Redakto faqet me dopjo-shtypje (JavaScript)",
"tog-editsection"=>"Lejo redaktimin e seksioneve me [redakto] lidhje",
"tog-editsectiononrightclick"=>"Lejo redaktimin e seksioneve me djathtas-shtypje<br> mbi emrin e seksionit (JavaScript)",
"tog-showtoc"=>"Trego tabelën e përmbajtjeve<br>(për faqet me më shume se 3 tituj)",
"tog-rememberpassword" => "Mbaj mënd fjalëkalimin për vizitën e ardhshme",
"tog-editwidth" => "Kutija e redaktimit ka gjerësi te plotë",
"tog-watchdefault" => "Shto faqet që redakton tek lista mbikqyrëse",
"tog-minordefault" => "Shëno të gjitha redaktimet si të vogla automatikisht",
"tog-previewontop" => "Trego parashikimin përpara kutisë redaktuese, jo mbas saj",
"tog-nocache" => "Mos ruaj kopje te faqeve",

# Dates
#

'sunday' => 'E Djelë',
'monday' => 'E Hënë',
'tuesday' => 'E Martë',
'wednesday' => 'E Mërkurë',
'thursday' => 'E Enjte',
'friday' => 'E Premte',
'saturday' => 'E Shtunë',
'january' => 'Janar',
'february' => 'Shkurt',
'march' => 'Mars',
'april' => 'Prill',
'may_long' => 'Maj',
'june' => 'Qershor',
'july' => 'Korrik',
'august' => 'Gusht',
'september' => 'Shtator',
'october' => 'Tetor',
'november' => 'Nëntor',
'december' => 'Dhjetor',
'jan' => 'Jan',
'feb' => 'Shk',
'mar' => 'Mar',
'apr' => 'Pri',
'may' => 'Maj',
'jun' => 'Qer',
'jul' => 'Kor',
'aug' => 'Gus',
'sep' => 'Sht',
'oct' => 'Tet',
'nov' => 'Nën',
'dec' => 'Dhj',

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Faqja Kryesore",
"mainpagetext"	=> "Wiki software u instalua me sukses.",
"about"			=> "Rreth",
"aboutsite"      => "Rreth $wgSitename",
"aboutpage"		=> "$wgMetaNamespace:Rreth",
"help"			=> "Ndihmë",
"helppage"		=> "$wgMetaNamespace:Ndihmë",
"wikititlesuffix" => "$wgSitename",
"bugreports"	=> "Raporto yçkla",
"bugreportspage" => "$wgMetaNamespace:Raporto_yçkla",
"sitesupport"   => "Dhurime",
"sitesupportpage" => "", # If not set, won't appear. Can be wiki page or URL
"faq"			=> "Pyetje e Përgjigje",
"faqpage"		=> "$wgMetaNamespace:Pyetje_e_Përgjigje",
"edithelp"		=> "Ndihmë për redaktim",
"edithelppage"	=> "$wgMetaNamespace:Si_redaktohet_një_faqe",
"cancel"		=> "Harroje",
"qbfind"		=> "Kërko",
"qbbrowse"		=> "Shfleto",
"qbedit"		=> "Redakto",
"qbpageoptions" => "Opsionet e faqes",
"qbpageinfo"	=> "Informacion mbi faqen",
"qbmyoptions"	=> "Opsionet e mia",
"mypage"		=> "Faqja ime",
"mytalk"		=> "Diskutimet e mia",
"currentevents" => "Evenimente",
"errorpagetitle" => "Gabim",
"returnto"		=> "Kthehu tek $1.",
"tagline"      	=> "Nga $wgSitename, Enciklopedia e Lirë.", # FIXME
"whatlinkshere"	=> "Lidhjet këtu ",
"help"			=> "Ndihmë",
"search"		=> "Kërko",
"go"		=> "Shko",
"history"		=> "Histori e faqes",
"printableversion" => "Version i shtypshëm",
"editthispage"	=> "Redakto faqen",
"deletethispage" => "Grise faqen",
"protectthispage" => "Mbroje faqen",
"unprotectthispage" => "Liroje faqen",
"newpage" => "Faqe e re",
"talkpage"		=> "Diskuto faqen",
"postcomment"   => "Bëj koment",
"articlepage"	=> "Shiko artikullin",
"subjectpage"	=> "Shiko subjektin", # For compatibility
"userpage" => "Shiko faqen",
"wikipediapage" => "Shiko faqen meta",
"imagepage" => 	"Shiko faqen e figurës",
"viewtalkpage" => "Shiko diskutimin",
"otherlanguages" => "Gjuhë të tjera",
"redirectedfrom" => "(Ridrejtuar nga $1)",
"lastmodified"	=> "Kjo faqe është ndryshuar për herë te fundit më $1.",
"viewcount"		=> "Kjo faqe është parë $1 herë.",
"gnunote" => "I gjithë teksti është publikuar sipas kushteve të <a class=internal href='$wgScriptPath/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(Nga http://sq.wikipedia.org)",
"protectedpage" => "Faqe e mbrojtur",
"administrators" => "$wgMetaNamespace:Administruesit",
"sysoptitle"	=> "Nevojitet titulli sysop",
"sysoptext"		=> "Veprimi që kerkove mund të bëhet vetëm nga një përdorues me titullin \"sysop\". Shiko $1.",
"developertitle" => "Nevojitet titulli zhvillues",
"developertext"	=> "Veprimi që kërkove mund bëhet vetëm nga një përdorues me titullin \"zhvillues\". Shiko $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Shko",
"ok"			=> "Shko",
"sitetitle"		=> $wgSitename,
"sitesubtitle"	=> "Enciklopedia e Lirë",
"retrievedfrom" => "Marrë nga \"$1\"",
"newmessages" => "Ti ke $1.",
"newmessageslink" => "mesazhe të reja",
"editsection"=>"redakto",
"toc" => "Tabela e përmbajtjeve",
"showtoc" => "trego",
"hidetoc" => "fshih",
"thisisdeleted" => "Shiko ose restauro $1?",
"restorelink" => "$1 redaktimet e prishura",

# Main script and global functions
#
"nosuchaction"	=> "Nuk ekziston ky veprim",
"nosuchactiontext" => "Veprimi i caktuar nga URL nuk
njihet nga wiki software",
"nosuchspecialpage" => "Nuk ekziston kjo faqe",
"nospecialpagetext" => "Ti ke kërkuar një faqe speciale që nuk
njihet nga wiki software.",

# General errors
#
"error"			=> "Gabim",
"databaseerror" => "Gabim rregjistri",
"dberrortext"	=> "Ka ndodhur një gabim me pyetjen e rregjistrit.
Pyetja e fundit qe ti i bëre rregjistrit ishte:
<blockquote><tt>$1</tt></blockquote>
nga funksioni \"<tt>$2</tt>\".
MySQL kthehu gabimin \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Ka ndodhur një gabim me formatin e pyetjes së rregjistrit.
Pyetja e fundit qe ti i bëre rregjistrit ishte:
\"$1\"
nga funksioni \"$2\".
MySQL kthehu gabimin \"$3: $4\".\n",
"noconnect"		=> "Ju kërkojmë ndjesë! Difekt teknik, rifillojmë së shpejti.",
"nodb"			=> "Nuk mund të zgjidhte rregjistrin $1",
"cachederror"	=> "Kjo është një kopje e faqes së kërkuar dhe mund të jetë e vjetër.",
"readonly"		=> "Rregjistri i bllokuar",
"enterlockreason" => "Fut një arsye për bllokimin, gjithashtu fut edhe kohën se kur
pritet të ç'bllokohet",
"readonlytext"	=> "Rregjistri $wgSitename është i bllokuar dhe nuk lejohen redaktime dhe
artikuj të ri, ka mundësi të jetë bllokuar për mirëmbajtje,
dhe do të kthehe në gjëndje normale mbas mirëmbajtjes.
Administruesi i cili e bllokoi dha këtë arsye:
<p>$1",
"missingarticle" => "Rregjistri nuk e gjeti tekstin e faqes
që duhet të kishte gjetur, të quajtur \"$1\".

<p>Kjo ndodh zakonisht kur ndjek një ndryshe ose histori lidhje tek një
faqe që është grisur.

<p>Në qoftë se ky nuk është rasti, atëherë ti mund të kesh gjetur një yçkël në softuerin.
Tregoja këtë përmbledhje një administruesi, duke shënuar edhe URL-in.",
"internalerror" => "Gabim i brëndshëm",
"filecopyerror" => "Nuk mundi të kopjojë skedarin \"$1\" tek \"$2\".",
"filerenameerror" => "Nuk mundi të ndërrojë emrin e skedarit \"$1\" në \"$2\".",
"filedeleteerror" => "Nuk mundi të grisi skedarin \"$1\".",
"filenotfound"	=> "Nuk mundi të gjejë skedarin \"$1\".",
"unexpected"	=> "Vlerë e papritur: \"$1\"=\"$2\".",
"formerror"		=> "Gabim: nuk mundi të dërgojë formularin",
"badarticleerror" => "Ky veprim nuk mund të bëhet në këtë faqe.",
"cannotdelete"	=> "Nuk mundi të grisi këtë faqe ose figurë të dhënë. (Ka mundësi të jetë grisur nga dikush tjeter.)",
"badtitle"		=> "Titull i keq",
"badtitletext"	=> "Titulli i faqes që kërkove nuk është ishte saktë, ishte bosh, ose
ishte një lidhje gabim me një titull wiki internacional.",
"perfdisabled" => "Ju kërkojmë ndjesë! Ky veprim është bllokuar përkohsisht
sepse e ngadalëson rregjistrin aq shumë sa nuk e përdor dot njeri tjetër.",
"perfdisabledsub" => "Kjo është nje kopje e ruajtur nga $1:",
"viewsource" => "Shiko tekstin",
"protectedtext" => "Kjo faqe është e mbrojtur që të mos redaktohet; mund të ketë
disa arsye përse kjo është bërë, të lutem shiko
[[$wgMetaNamespace:Faqe e mbrojtur]].

Mund të shikosh dhe kopjosh tekstin e kësaj faqeje:",

# Login and logout pages
#
"logouttitle"	=> "Përdoruesi doli",
"logouttext"	=> "Tani ti ke dalë jashtë.
Mund të vazhdosh të përdorësh $wgSitename anonimisht, ose mund të hysh brënda
përsëri me emrin që kishe ose me një emër tjetër.\n",

"welcomecreation" => "<h2>Mirë se erdhe, $1!</h2><p>Llogaria jote ështe hapur.
Mos harro të vendosësh preferimet e tua të uikipedias.",

"loginpagetitle" => "Hyrje përdoruesi",
"yourname"		=> "Fut emrin tënd",
"yourpassword"	=> "Fut fjalëkalimin tënd",
"yourpasswordagain" => "Fut fjalëkalimin përsëri",
"newusersonly"	=> " (përdoruesit e rinj vetëm)",
"remembermypassword" => "Mbaj mënd fjalëkalimin tim për tërë vizitat e ardhshme.",
"loginproblem"	=> "<b>Kishte një problem me hyrjen tënde.</b><br>Provoje përsëri!",
"alreadyloggedin" => "<font color=red><b>Përdorues $1, ti ke hyrë brënda më parë!</b></font><br>\n",

"areyounew"		=> "N.q.s. je një përdorues i ri i $wgSitename dhe do të hapësh një llogari,
fut një emër, pastaj fut një fjalëkalim dy herë.
Adresa jote e email-it nuk është e detyrueshme; n.q.s. ti harron fjalëkalimin mund të kërkosh që
ta dergojme tek adresa që na dhe.<br>\n",

"login"			=> "Hyrje",
"userlogin"		=> "Hyrje",
"logout"		=> "Dalje",
"userlogout"	=> "Dalje",
"notloggedin"	=> "Nuk ke hyrë brënda",
"createaccount"	=> "Hap një llogari",
"badretype"		=> "Fjalëkalimet nuk janë njësoj.",
"userexists"	=> "Emri që përdore është në përdorim. Zgjidh një emër tjetër.",
"youremail"		=> "Adresa e email-it*",
"yournick"		=> "Nofka jote (për firmosje)",
"emailforlost"	=> "* Futja e email-it nuk është e detyrueshme. Por lejon përdorues të tjerë
të të kontaktojnë nëpërmjet faqes pa u treguar adresën, gjithashtu kjo adresë
është e dobishme n.q.s. harron fjalëkalimin",
"loginerror"	=> "Gabim hyrje",
"noname"		=> "Nuk ke dhënë një emër të saktë.",
"loginsuccesstitle" => "Hyrje me sukses",
"loginsuccess"	=> "Tani ke hyrë brënda në $wgSitename si \"$1\".",
"nosuchuser"	=> "Nuk ka ndonjë përdorues me emrin \"$1\".
Kontrollo gërmat, ose përdor formularin e mëposhtëm për të hapur një llogari të re.",
"wrongpassword"	=> "Fjalëkalimi që fute nuk është i saktë. Provoje përsëri!",
"mailmypassword" => "Më dërgo një fjalëkalim të ri tek adresa ime",
"passwordremindertitle" => "Kujtim për fjalëkalimin nga $wgSitename",
"passwordremindertext" => "Dikush (ndoshta ti, nga IP adresa $1)
kërkojë që të dërgojmë një fjalëkalim hyrje të ri për $wgSitename.
Fjalëkalimi për përdoruesin \"$2\" tani është \"$3\".
Duhet të hysh përsëri dhe të ndërrosh fjalëkalimin tënd menjëherë.",
"noemail"		=> "Rregjistri nuk ka adresë për përdoruesin \"$1\".",
"passwordsent"	=> "Një fjalëkalim i ri është dërguar tek adresa e rregjistruar për \"$1\".
Hyni përsëri mbasi ta kesh marrë.",

# Edit pages
#
"summary"		=> "Përmbledhje",
"subject"		=> "Subjekt/Titull",
"minoredit"		=> "Ky është një redaktim i vogël",
"watchthis"		=> "Mbikqyr këtë faqe",
"savearticle"	=> "Kryej ndryshimin",
"preview"		=> "Parashiko",
"showpreview"	=> "Trego parashikimin",
"blockedtitle"	=> "Përdoruesi është bllokuar",
"blockedtext"	=> "Emri yt ose adresa e IP-së është bllokuar nga $1.
Arsyeja e dhënë është kjo:<br>''$2''<p>Mund të kontaktosh $1 ose një nga
[[$wgMetaNamespace:Administruesit|administruesit]] e tjerë për të diskutuar bllokimin.

Vë re, nuk mund të përdorësh \"dërgoji email këtij përdoruesi\" n.q.s. nuk ke një adresë të saktë
të rregjistruar në [[Speciale:Preferences|preferimet e përdoruesit]].

Adresa e IP-së që ke është $3. Na e jep këtë adresë në çdo ankesë.

==Shënim për përdoruesit e AOL-it==
Për shkak të vandalizmeve të një përdoruesit të AOL-it, Uikipedia shpesh bllokon AOL ndërmjetse. Për fat të keq, një ndërmjetse shërbyese mund të jetë duke u përdorur nga një numër i madh njerëzish, prandaj shpesh disa përdorues të pafajshëm të AOL-its bllokohen. Ju kërkojmë ndjesë për çdo problem që ka ndodhur.

Në qoftë se kjo të ndodh ty, të lutem njoftoni një administrues duke përdorur një adresë AOL-i. Gjithashtu dërgoni edhe adresën e IP-së dhënë mësipër.",
"newarticle"	=> "(I Ri)",
"newarticletext" =>
"Ke ndjekur një lidhje tek një faqe që nuk ekziston akoma.
Për ta krijuar këtë faqe, fillo të shtypësh në kutinë poshtë
(shiko [[$wgMetaNamespace:Ndihmë|faqen ndihmuese]] për më shumë informacion).
Në qoftë se je këtu gabimisht, thjesht shtyp butonin '''Back''' të shfletuesit tuaj.",
"anontalkpagetext" => "---- ''Kjo është një faqe diskutimi për një përdorues anonim i cili nuk ka hapur akoma një llogari ose nuk e përdor atë. Prandaj, neve na duhet të përdorim numrin e adresës [[IP adresë|IP]] për ta identifikuar. Kjo adresë mund të përdoret nga disa njerëz. Në qoftë se ti je një përdorues anonim dhe mendon se komente kot janë drejtuar ndaj teje, të lutem [[Speciale:Userlogin|krijo një llogari ose hyni brënda]] për të mos
u ngatarruar me përdorues të tjerë anonim.'' ",
"noarticletext" => "(Tani për tani, nuk ka tekst në këtë faqe)",
"updated"		=> "(E ndryshuar)",
"note"			=> "<strong>Shënim:</strong> ",
"previewnote"	=> "Kini kujdes se ky është vetëm një parashikim, nuk është ruajtur akoma!",
"previewconflict" => "Ky parashikim reflekton tekstin sipër
kutisë së redaktimit siç do të duket kur ta ruani.",
"editing"		=> "Duke redaktuar $1",
"sectionedit"	=> " (seksion)",
"commentedit"	=> " (koment)",
"editconflict"	=> "Konflikt redaktimi: $1",
"explainconflict" => "Dikush tjetër ka ndryshuar këtë faqe kur ti po e
redaktoje.
Kutija e redaktimit mësipër tregon tekstin e faqes siç ekziston tani.
Nryshimet e tua janë treguar poshtë kutisë së redaktimit.
Të duhet të përputhësh ndryshimet e tua me tekstin ekzistues.
<b>Vetëm</b> teksti në kutinë e sipërme të redaktimit do të ruhet kur ti
të shtypësh \"Ruaje faqen\".\n<p>",
"yourtext"		=> "Teksti yt",
"storedversion" => "Versioni i ruajtur",
"editingold"	=> "<strong>KUJDES: Po redakton një version të vjetër të kësaj faqeje.
Në qoftë se e ruan, çdo ndryshim i bërë deri tani do të humbet.</strong>\n",
"yourdiff"		=> "Ndryshimet",
# REPLACE THE COPYRIGHT WARNING IF YOUR SITE ISN'T GFDL!
"copyrightwarning" => "Të lutem vë re që të gjitha kontributet tek $wgSitename janë
të konsideruara të dhëna nën liçensën GNU Free Documentation License
(shiko $1 për detaje).
Në qoftë se nuk dëshiron që kontributet e tua të redaktohen pa mëshirë dhe të jepen
kudo, atëherë mos i jep këtu.<br>
Gjithashtu, ti po na premton që i ke shkruajtur vetë këto, ose i ke kopjuar nga një
vënd public (public domain) ose diçka e ngjashme e lirë.
<strong>MOS JEPNI PUNIME QE JANE NEN COPYRIGHT PA PASUR LEJE!</strong>",
"longpagewarning" => "KUJDES: Kjo faqe është $1 kilobytes e gjatë; disa
shfletues mund të kenë probleme për të redaktuar faqe që afrohen ose janë akoma më shumë se 32kb.
Konsidero ta ndash faqen në disa seksione më të vogla.",
"readonlywarning" => "KUJDES: Rregjistri është bllokuar për mirëmbajtje,
kështuqë nuk do kesh mundësi të ruash redaktimet e tua tani. Mund të kopjosh dhe ruash tekstin
në një skedar për më vonë.",
"protectedpagewarning" => "KUJDES:  Kjo faqe është bllokuar kështuqë vetëm përdorues me titullin

sysop mund ta redaktojnë. Ndiq rregullat e dhëna tek
<a href='$wgScriptPath/$wgMetaNamespace:Rregullat_për_faqe_të_bllokuara'>faqet e bllokuara</a>.",

# History pages
#
"revhistory"	=> "Historia e redaktimeve",
"nohistory"		=> "Nuk ka histori redaktimesh për këtë faqe.",
"revnotfound"	=> "Versioni nuk u gjet",
"revnotfoundtext" => "Versioni i vjetër i faqes së kërkuar nuk mund të gjehej.
Të lutem kontrollo URL-in që përdore për të ardhur tek kjo faqe.\n",
"loadhist"		=> "Duke karikuar historinë e faqes",
"currentrev"	=> "Versioni i tanishëm",
"revisionasof"	=> "Versioni i $1",
"cur"			=> "tani",
"next"			=> "mbas",
"last"			=> "fund",
"orig"			=> "parë",
"histlegend"	=> "Legjenda: (tani) = ndryshimet me versionin e tanishëm,
(fund) = ndryshimet me versionin e parardhshëm, V = redaktim i vogël",

# Diffs
#
"difference"	=> "(Ndryshime midis versioneve)",
"loadingrev"	=> "duke karikuar versionin për ndryshimin",
"lineno"		=> "Rreshti $1:",
"editcurrent"	=> "Redakto versionin e tanishëm të kësaj faqeje",

# Search results

#
"searchresults" => "Rezultatet e kërkimit",
"searchresulttext" => "Për më shumë informacion për kërkimin e {{SITENAME}}, shiko [[Project:Kërkim|Duke kërkuar {{SITENAME}}]].",
"searchquery"	=> "Për pyetjen \"$1\"",
"badquery"		=> "Pyetje kërkese e formuluar gabim",
"badquerytext"	=> "Nuk mundi t'i pergjigjet pyetjes tende.
Kjo ka mundësi të ketë ndodhur ngaqë provove të kërkosh për një
fjalë me më pak se tre gërma, gjë që s'mund të behet akoma.
Ka mundësi që edhe të kesh shtypur keq pyetjen, për
shëmbull \"peshku dhe dhe halat\".
Provo një pyetje tjetër.",
"matchtotals"	=> "Pyetja \"$1\" u përpuq $2 tituj faqesh
dhe teksti i $3 artikujve te pasardhshëm.",
"nogomatch" => "Nuk ka asnjë faqe me atë emër ekzakt, duke provuar për kërkim me tekst të plotë.",
"titlematches"	=> "Tituj faqesh që përputhen",
"notitlematches" => "Nuk ka asnjë titull faqeje që përputhet",
"textmatches"	=> "Tekst faqesh që përputhet",
"notextmatches"	=> "Nuk ka asnjë tekst faqeje që përputhet",
"prevn"			=> "$1 më para",
"nextn"			=> "$1 më pas",
"viewprevnext"	=> "Shiko ($1) ($2) ($3).",
"showingresults" => "Duke treguar më poshtë <b>$1</b> rezultate dhe duke filluar me #<b>$2</b>.",
"showingresultsnum" => "Duke treguar më poshtë <b>$3</b> rezultate dhe duke filluar me #<b>$2</b>.",
"nonefound"		=> "<strong>Shënim</strong>: kërkimet pa rezultat shpesh ndodhin
kur kërkon për fjalë që rastisen shpesh si \"ke\" and \"nga\",
të cilat nuk janë të futura në rregjistër, ose duke dhënë më shumë se një fjalë (vetëm faqet
që i kanë të gjitha ato fjalë do të tregohen si rezultate).",
"powersearch" => "Kërko",
"powersearchtext" => "
Kërko në hapësirën:<br>
$1<br>
$2 Lidhje ridrejtuese &nbsp; Kërko për $3 $9",
"searchdisabled" => "<p>Kërkim me tekst të plotë është bllokuar tani për tani ngaqë
shërbyesi është shumë i ngarkuar; shpresojmë ta nxjerrim prapë në gjëndje normale mbas disa punimeve.
Gjer atëherë, mund të përdorësh google për kërkime:</p>

",
"googlesearch" => "<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Kërkim me Google\">
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
"blanknamespace" => "(Main)",

# Preferences page
#
"preferences"	=> "Preferimet",
"prefsnologin" => "Nuk ke hyrë brënda",
"prefsnologintext"	=> "Duhet të kesh <a href=\"" .
  "{{localurle:Speciale:Userlogin}}\">hyrë brënda</a>
për të vedosur preferimet e përdoruesit.",
"prefslogintext" => "Ke hyrë si \"$1\".
Numri yt i brëndshëm i identifikimit është $2.

Shiko [[$wgMetaNamespace:Ndihmë për preferimet e përdoruesit]] për ndihmë për të kuptuar opsionet.",
"prefsreset"	=> "Preferimet janë rikthyer siç ishin.",
"qbsettings"	=> "Vendimet e shpejta",
"changepassword" => "Ndërro fjalëkalimin",
"skin"			=> "Pamja",
"math"			=> "Tregimi i matematikës",
"dateformat"	=> "Pamja e datës",
"math_failure"		=> "Nuk e kuptoj",
"math_unknown_error"	=> "gabim i panjohur",
"math_unknown_function"	=> "funksion i panjohur ",
"math_lexing_error"	=> "gabim leximi",
"math_syntax_error"	=> "gabim sintakse",
"saveprefs"		=> "Ruaj preferimet",
"resetprefs"	=> "Rikthe preferimet",
"oldpassword"	=> "Fjalëkalimi i vjetër",
"newpassword"	=> "Fjalëkalimi i ri",
"retypenew"		=> "Rishtyp fjalëkalimin e ri",
"textboxsize"	=> "Dimensionet e kutisë së redaktimit",
"rows"			=> "Rreshta",
"columns"		=> "Kolona",
"searchresultshead" => "Preferimet e rezultateve të kërkimit",
"resultsperpage" => "Sa përputhje të tregohen për faqe",
"contextlines"	=> "Sa rreshta të tregohen për përputhje",
"contextchars"	=> "Sa gërma të tregohen për çdo rresht",
"stubthreshold" => "Kufiri për tregimin e cungjeve",
"recentchangescount" => "Numri i titujve në ndryshimet e fundit",
"savedprefs"	=> "Preferimet e tuaja janë ruajtur.",
"timezonetext"	=> "Fut numrin e orëve prej të cilave ndryshon ora lokale
nga ajo e shërbyesit (UTC).",
"localtime"	=> "Tregimi i orës lokale",
"timezoneoffset" => "Ndryshimi",
"servertime"	=> "Ora e shërbyesit tani është",
"guesstimezone" => "Gjeje nga shfletuesi",
"emailflag"		=> "Blloko e-mail nga përdorues të tjerë",
"defaultns"		=> "Kërko automatikisht vetëm në këto hapësira:",

# Recent changes
#
"changes" => "ndryshime",
"recentchanges" => "Ndryshimet e fundit",
# This is the default text, and can be overriden by editing [[$wgMetaNamespace:Recentchanges]]
"recentchangestext" => "Ndiq ndryshimet e fundit të $wgSitename në këtë faqe.",
"rcloaderr"		=> "Duke karikuar ndryshimet e fundit",
"rcnote"		=> "Më poshtë janë <strong>$1</strong> ndryshimet e fundit gjatë <strong>$2</strong> ditëve.",
"rcnotefrom"	=> "Më poshtë janë ndryshimet e fundit nga <b>$2</b> (treguar deri në <b>$1</b>).",
"rclistfrom"	=> "Trego ndryshimet e reja duke filluar nga $1",
# "rclinks"		=> "Trego $1 ndryshimet e fundit gjatë $2 orëve të kaluara / $3 ditëve të kaluara",
# "rclinks"		=> "Trego $1 ndryshime gjatë $2 ditëve të fundit.",
"rclinks"		=> "Trego $1 ndryshime gjatë $2 ditëve; $3 redaktime të vogla",
"rchide"		=> "në $4 formë; $1 redaktime të vogla; $2 hapësira të dyta; $3 redaktime të shumta.",
"rcliu"			=> "; $1 redaktime nga përdorues të rregjistruar",
"diff"			=> "ndrysh",
"hist"			=> "hist",
"hide"			=> "fshih",
"show"			=> "trego",
"tableform"		=> "tabelë",
"listform"		=> "listë",
"nchanges"		=> "$1 ndryshime",
"minoreditletter" => "V",
"newpageletter" => "R",

# Upload
#
"upload"		=> "Jep skedar",
"uploadbtn"		=> "Jep skedar",
"uploadlink"	=> "Jep skedar",
"reupload"		=> "Ri-jep",
"reuploaddesc"	=> "Kthehu tek formulari i dhënies.",
"uploadnologin" => "Nuk ke hyrë brënda",
"uploadnologintext"	=> "Duhet të kesh <a href=\"" .
  "{{localurle:Speciale:Userlogin}}\">hyrë brënda</a>
për të dhënë skedarë.",
"uploadfile"	=> "Jep figura, zë, dokumente etj.",
"uploaderror"	=> "Gabim dhënie",
"uploadtext"	=> "'''NDALO!''' Përpara se të japësh këtu,
lexo dhe ndiq {{SITENAME}}'s
[[Project:Rregullat_përdorim_figurash|Rregullat e përdorimit të figurave]].

Për të parë ose për të kërkuar figurat e dhëna më parë,
shko tek [[Speciale:Imagelist|lista e figurave të dhëna]].
Dhëniet dhe grisjet janë të rregjistruara në
[[Project:Jep_rregj|rregjistrin e dhënies]].

Përdorni formularin e më poshtëm për të dhënë skedarë të figurave të reja për tu përdorur
në illustrimet e artikujve.
Për shumicën e shfletuesve, do të shihni një \"Browse...\" buton, i cili do të
hapi dialogun standart të skedarëve të operating system që përdorni.
Zgjedhja e një skedari do të mbushi emrin në rreshtin e tekstit, afer butonit.
Duhet të konfirmosh që nuk je duke thyer rregullat e të drejtave të kopimit duke vënë shenjën.
Shtyp butonin \"Jep\" për të mbaruar dhënien.
Kjo mund të zgjasi për pak kohë n.q.s. keni lidhje të ngadaltë të internet-it.

Formatet e preferuara janë JPEG për fotografi, PNG
për vizatime dhe ikona të tjera, dhe OGG për zë dhe muzikë.
Të lutem fut një emër përshkrues për të mos patur konfuzion më vonë.
Për të futur një figurë në një artikull, përdor lidhjen sipas formës
'''<nowiki>[[figura:skedar.jpg]]</nowiki>''' ose '''<nowiki>[[figura:skedar.png|tekst përshkrues]]</nowiki>'''
ose '''<nowiki>[[media:skedar.ogg]]</nowiki>''' për zë.

Vini re se si me të gjitha faqet e tjera wiki, të tjerë mund të redaktojnë ose
grisin dhëniet tuaja n.q.s. mendojnë se nuk janë enciklopedike, dhe
ti mund të bllokohesh nga dhënja n.q.s. e abuzon sistemin.",
"uploadlog"		=> "rregjistër dhënie",
"uploadlogpage" => "Jep_rregj",
"uploadlogpagetext" => "Më poshtë është një listë e skedarëve më të rinj që janë dhënë.
Të gjitha orët janë me orën e shërbyesit (UTC).
<ul>
</ul>
",
"filename"		=> "Skedaremër",
"filedesc"		=> "Përmbledhje",
"affirmation"	=> "Unë konfirmoj që jam mbajtësi i të drejtave të kopimit të këtij skedarit
dhe jap leje të liçensohet nën rregullat e $1.",
"copyrightpage" => "$wgMetaNamespace:Të drejta kopimi",
"copyrightpagename" => "$wgSitename Të drejta kopimi",
"uploadedfiles"	=> "Jep skedarë",
"noaffirmation" => "Duhet të konfirmosh që dhënja e jote nuk thyen ndonjë të drejtë kopimi.",
"ignorewarning"	=> "Injoroje shënimin e kujdesisë dhe ruaje skedarin.",
"minlength"		=> "Emrat e skedarëve duhet të kenë të paktën tre gërma.",
"badfilename"	=> "Emri i skedarit është ndërruar në \"$1\".",
"badfiletype"	=> "\".$1\" nuk rekomandohet si tip skedari.",
"largefile"		=> "Rekomandohet që skedarët të most kalojnë 100k në madhësi.",
"successfulupload" => "Dhënie e sukseshme",
"fileuploaded"	=> "Skedari \"$1\" u mor me sukses.
Te lutem ndiq këtë lidhje : ($2) për të shkuar tek faqja e përshkrimit dhe për të futur
informacion për skedarin, si p.sh. ku e gjete, kur u bë, kush e bëri, dhe çdo gjë
tjetër që na duhet të dimë për të.",
"uploadwarning" => "Kujdes dhënie",
"savefile"		=> "Ruani skedarin",
"uploadedimage" => "dha \"$1\"",
"uploaddisabled" => "Ndjesë, dhëniet janë bllokuar në këtë shërbyes dhe nuk është gabimi yt.",

# Image list
#
"imagelist"		=> "Lista e figurave",
"imagelisttext"	=> "Më poshtë është një listë e $1 figurave të renditura sipas $2.",
"getimagelist"	=> "duke karikuar të gjithë listën e figurave",
"ilshowmatch"	=> "Trego të gjitha figurat me emërat që i përputhen",
"ilsubmit"		=> "Kërko",
"showlast"		=> "Trego $1 figurat e fundit të renditura sipas $2.",
"all"			=> "të gjitha",
"byname"		=> "emrit",
"bydate"		=> "datës",
"bysize"		=> "madhësisë",
"imgdelete"		=> "gris",
"imgdesc"		=> "për",
"imglegend"		=> "Legjendë: (për) = trego/redakto përshkrimin e skedarit.",
"imghistory"	=> "Historia e skedarit",
"revertimg"		=> "ktheje",
"deleteimg"		=> "gris",
"deleteimgcompletely"		=> "gris",
"imghistlegend" => "Legjendë: (tani) = ky është skedari i tanishëm, (gris) = grise
këtë version të vjetër, (ktheje) = ktheje në këtë version të vjetër.
<br><i>Shtyp datën për të parë skedarin e dhënë në atë ditë</i>.",
"imagelinks"	=> "Lidhje skedarësh",
"linkstoimage"	=> "Këto faqe lidhen tek ky skedar:",
"nolinkstoimage" => "Nuk ka asnjë faqe që të lidhet tek ky skedar.",

# Statistics
#
"statistics"	=> "Statistika",
"sitestats"		=> "Statistikat e faqeve",
"userstats"		=> "Statistikat e përdoruesit",
"sitestatstext" => "Gjënden <b>$1</b> faqe në totalin e rregjistrit.
Këto përfshijnë faqet e  \"diskutimit\", faqe rreth $wgSitename, faqe \"cungje\" të vogla,
ridrejtime, dhe të tjera që ndoshta nuk kualifikohen si artikuj.
Duke mos i përfshirë këto, gjënden <b>$2</b> faqe që janë artikuj të ligjshëm.<p>
Gjënden <b>$3</b> shikime faqesh, dhe <b>$4</b> redaktime faqesh që nga dita kur
softueri u ndërrua (July 20, 2002).
Kjo do të thotë se janë bërë <b>$5</b> redaktime për faqe afërsisht, dhe <b>$6</b> shikime për redaktim.",
"userstatstext" => "Gjënden <b>$1</b> përdorues të rregjistruar.
<b>$2</b> prej tyre janë me titull administrues (shiko $3).",

# Maintenance Page
#
"maintenance"		=> "Faqja mirëmbajtëse",
"maintnancepagetext"	=> "Kjo faqe ka disa vegla to dobishme për mirëmbajtjen e përditshme. Disa nga këto funksiones e përdorin shumë

rregjistrin, kështuqë mos e fresko faqen mbas çdo ndryshimi ;-)",
"maintenancebacklink"	=> "Mbrapsh tek faqja mirëmbajtëse",
"disambiguations"	=> "Faqe qartësuese",
"disambiguationspage"	=> "$wgMetaNamespace:Lidhje_tek_faqe_qartësuese",
"disambiguationstext"	=> "Artikujt që vijojnë lidhen tek një <i>faqe qartësuese</i>. Ato duhet të lidhen tek tema e përshtatshme

<br>Një faqe trajtohet si qartësuese lidhet nga $1.<br>Lidhje nga hapësira të tjera <i>nuk</i> jepen këtu.",
"doubleredirects"	=> "Dopjo ridrejtime",
"doubleredirectstext"	=> "<b>Kujdes:</b> Kjo listë mund të ketë lidhje gabim. D.m.th. ka tekst dhe lidhje mbas #REDIRECT-it të parë.

<br>\nÇdo rresht ka lidhje tek ridrejtimi i parë dhe i dytë, gjithashtu ka edhe rreshtin e parë të tekstit të ridrejtimit të dytë,

duke dhënë dhe  artikullin e \"vërtetë\", me të cilin ridrejtimi i parë duhet të lidhet.",
"brokenredirects"	=> "Ridrejtime të prishura",
"brokenredirectstext"	=> "Ridrejtimet që vijojnë lidhen tek një artikull që s'ekziston.",
"selflinks"		=> "Faqe që lidhen tek vetëvetja",
"selflinkstext"		=> "Faqet që vijojnë kanë një lidhje tek vetëvetja, gjë që s'duhet të ndodhi.",
"mispeelings"           => "Faqe me gabime gramatikore",
"mispeelingstext"               => "Faqet që vijojnë kanë një gabim shkrimi që ndodh shpesh, të cilat jepen në $1. Shkrimi i vërtetë mund të jetë dhënë (si kështu).",
"mispeelingspage"       => "Lista e gabimeve më të shpeshta të shkrimit",
"missinglanguagelinks"  => "Mungojnë gjuhë-lidhjet",
"missinglanguagelinksbutton"    => "Gjej gjuhë-lidhjet që mungojnë për",
"missinglanguagelinkstext"      => "Këto artikuj <i>nuk</i> lidhen tek faqja korresponduese në $1. Ridrejtime dhe nën-faqet <i>nuk</i> janë treguar.",


# Miscellaneous special pages
#
"orphans"		=> "Faqe të palidhura",
"lonelypages"	=> "Faqe të palidhura",
"unusedimages"	=> "Figura të papërdorura",
"popularpages"	=> "Artikuj te frekuentuar shpesh",
"nviews"		=> "$1 shikime",
"wantedpages"	=> "Artikuj më të dëshiruar",
"nlinks"		=> "$1 lidhje",
"allpages"		=> "Të gjitha faqet",
"randompage"	=> "Artikull kuturu",
"shortpages"	=> "Artikuj të shkurtër",
"longpages"		=> "Artikuj të gjatë",
"listusers"		=> "Lista e përdoruesve",
"specialpages"	=> "Faqe speciale",
"spheading"		=> "Faqe speciale për të gjithë përdoruesit",
"sysopspheading" => "Vetëm për ato me titull sysop",
"developerspheading" => "Vetëm për zhvilluesit",
"protectpage"	=> "Mbroje faqen",
"recentchangeslinked" => "Ndryshime të përafërta",
"rclsub"		=> "(për faqet e lidhura nga \"$1\")",
"debug"			=> "Raporto yçkla",
"newpages"		=> "Artikuj të rinj",
"ancientpages"		=> "Artikuj më të vjetër",
"movethispage"	=> "Zhvendose faqen",
"unusedimagestext" => "<p>Të lutem, vë re se hapësira të tjera
si p.sh ato të që kanë të bejnë me gjuhë të ndryshme mund të lidhin
nje figurë me një URL në mënyrë direkte, kështuqë mund të keto figura mund të jepen
këtu edhe pse janë në përdorim.",
"booksources"	=> "Burime librash",
"booksourcetext" => "Më poshtë është një listë me lidhje tek hapësira të tjera që shesin
libra të rinj dhe të përdorur, dhe mund të kenë më shumë informacion
për librat që po kerkon.
$wgSitename nuk ka mardhënie me asnjë nga këto biznese, dhe
kjo listë nuk duhet të shikohet si një rreklamë.",
"alphaindexline" => "$1 deri në $2",

# Email this user
#
"mailnologin"	=> "S'ka adresë dërgimi",
"mailnologintext" => "Duhet të kesh <a href=\"" .
  "{{localurle:Speciale:Userlogin}}\">hyrë brënda</a>
dhe të kesh një adresë të saktë në <a href=\"" .
  "{{localurle:Speciale:Preferences}}\">preferimet</a>
për të dërguar një e-mail përdoruesve të tjerë.",
"emailuser"		=> "Dërgoji e-mail këtij përdoruesi",
"emailpage"		=> "Dërgo e-mail përdoruesve",
"emailpagetext"	=> "N.q.s. ky përdorues ka dhënë një adresë të saktë në
preferimet e tij, formulari më poshtë do t'i dërgojë një mesazh.
Adresa e email-it që ke dhënë në preferimet e tua do të duket
si pjesa \"From\" e adresës së mesazhit, kështuqë marrësi do të ketë
mundësi të të përgjigjet.",
"noemailtitle"	=> "S'ka adresë email-i",
"noemailtext"	=> "Ky përdorues s'ka dhënë një adresë të saktë,
ose ka vendosur të mos pranojë mesazhe email-i nga përdorues të tjerë.",
"emailfrom"		=> "Nga",
"emailto"		=> "Për",
"emailsubject"	=> "Subjekt",
"emailmessage"	=> "Mesazh",
"emailsend"		=> "Dërgo",
"emailsent"		=> "Email-i u nis",
"emailsenttext" => "Mesazhi e-mail është nisur.",

# Watchlist
#
"watchlist"		=> "Lista mbikqyrëse",
"watchlistsub"	=> "(për përdoruesin \"$1\")",
"nowatchlist"	=> "Ti nuk ke ndonjë faqe në listën mbikqyrëse.",
"watchnologin"	=> "Nuk ke hyrë brënda",
"watchnologintext"	=> "Duhet të kesh <a href=\"" .
  "{{localurle:Speciale:Userlogin}}\">hyrë brënda</a>
për të ndryshuar listën mbikqyrëse tënde.",
"addedwatch"	=> "Shtuar tek lista mbikqyrëse",
"addedwatchtext" => "Faqja \"$1\" është shtuar <a href=\"" .
  "{{localurle:Speciale:Watchlist}}\">listës mbikqyrëse</a> tënde.
Ndryshimet e ardhshme të kësaj faqeje dhe faqes së diskutimit të saj do të jepen më poshtë,
dhe emri i faqes do të duket i <b>trashë</b> në <a href=\"" .
  "{{localurle:Speciale:Recentchanges}}\">listën e ndryshimeve të fundit</a> për ta
dalluar më kollaj.</p>

<p>N.q.s. do të heqësh një faqe nga lista mbikqyrëse më vonë, shtyp \"Mos e mbikqyr\" në tabelën anësore.",
"removedwatch"	=> "U hoq nga lista mibkqyrëse",
"removedwatchtext" => "Faqja \"$1\" është hequr nga lista mbikqyrëse e jote.",
"watchthispage"	=> "Mbikqyr këtë faqe",
"unwatchthispage" => "Mos e mbikqyr",
"notanarticle"	=> "S'është një artikull",
"watchnochange" => "Asnjë nga artikujt nën mbikqyrje është redaktuar gjatë kohës së dhënë.",
"watchdetails" => "($1 faqe nën mbikqyrje duke mos numëruar faqet e diskutimit;
$2 faqe(t) brënda kufirit janë redaktuar;
$3...
<a href='$4'>trego dhe redakto tërë listën</a>.)",
"watchmethod-recent" => "duke parë ndryshimet e fundit për faqe nën mbikqyrje",
"watchmethod-list" => "duke parë faqet nën mbikqyrje për ndryshime të fundit",
"removechecked" => "Hiq të zgjedhurat",
"watchlistcontains" => "Lista mbikqyrëse e jote ka $1 faqe.",
"watcheditlist" => "Këtu jepet një listë e alfabetizuar e faqeve
nën mbikqyrje. Zgjidh kutinë e sejcilës faqe që dëshiron të heqësh nga lista
dhe shtyp butonin 'Hiq të zgjedhurat' në fund të ekranit.",
"removingchecked" => "Duke hequr artikujt e zgjedhur nga lista mbikqyrëse...",
"couldntremove" => "S'mundi të heq arikullin '$1'...",
"iteminvalidname" => "Problem me artikullin '$1', titull jo i saktë...",
"wlnote" => "Më poshtë janë $1 ndryshimet e <b>$2</b> orëve të fundit.",


# Delete/protect/revert
#
"deletepage"	=> "Gris faqen",
"confirm"		=> "Konfirmo",
"excontent" => "përmbajtja ishte:",
"exbeforeblank" => "përmbajtja përpara boshatisjes ishte:",
"exblank" => "faqja është bosh",
"confirmdelete" => "Konfirmo grisjen",
"deletesub"		=> "(Duke grisur \"$1\")",
"historywarning" => "Kujdes: Faqja që je bërë gati pët të grisur ka një histori: ",
"confirmdeletetext" => "Je duke grisur '''përfundimisht''' një faqe
ose një skedar me tërë historinë e tij nga rregjistri.
Të lutem konfirmo që ke ndër mënd ta bësh këtë gjë, që e kupton se cilat janë
pasojat, dhe që po vepron ne përputhje me [[$wgMetaNamespace:Rregullat]].",
"confirmcheck"	=> "Po, dëshiroj me të vërtetë ta gris këtë.",
"actioncomplete" => "Veprim i mbaruar",
"deletedtext"	=> "\"$1\" është grisur nga rregjistri.
Shiko $2 për një rekord të grisjeve të fundit.",
"deletedarticle" => "grisi \"$1\"",
"dellogpage"	=> "Gris_rregj",
"dellogpagetext" => "Më poshtë është një listë e grisjeve më të fundit.
Të gjitha kohët janë sipas orës së shërbyesit (UTC).
<ul>
</ul>
",
"deletionlog"	=> "grisje rekordesh",
"reverted"		=> "Kthehu tek një version i vjetër",
"deletecomment"	=> "Arsyeja për grisjen",
"imagereverted" => "Kthimi tek një version i sukseshëm.",
"rollback"		=> "Rrotulloji mbrapsh redaktimet",
"rollbacklink"	=> "rrotullo",
"rollbackfailed" => "Rrotullimi dështoi",
"cantrollback"	=> "Nuk mund të kthejë redaktimin; redaktori i fundit është i vetmi autor i këtij artikulli.",
"alreadyrolled"	=> "Nuk mund të rrotullojë redaktimin e fundit e [[$1]]
nga [[Përdoruesi:$2|$2]] ([[Përdoruesi diskutim:$2|Diskutim]]); dikush tjetër ka redaktuar ose rrotulluar këtë faqe.

Redaktimi i fundit është bërë nga [[Përdoruesi:$3|$3]] ([[Përdoruesi diskutim:$3|Diskutim]]). ",
#   only shown if there is an edit comment
"editcomment" => "Komenti i redaktimit ishte: \"<i>$1</i>\".",
"revertpage"	=> "Kthyer tek redaktimi i fundit nga $1",
"protectlogpage" => "Mbroj_rregj",
"protectlogtext" => "Më poshtë është një listë e \"mbrojtjeve/lirimeve\" të faqeve.
Shiko [[$wgMetaNamespace:Faqe e mbrojtur]] për më shumë informacion.",
"protectedarticle" => "mbrojti [[$1]]",
"unprotectedarticle" => "liroji [[$1]]",

# Undelete
"undelete" => "Restauro faqet e grisura",
"undeletepage" => "Shiko ose restauro faqet e grisura",
"undeletepagetext" => "Më poshtë janë faqet që janë grisur por që gjënden akoma në arshiv dhe
mund të restaurohen. Arshivi boshatiset periodikisht.",
"undeletearticle" => "Restauro artikullin e grisur",
"undeleterevisions" => "$1 versione u futën në arshiv",

"undeletehistory" => "N.q.s. restauron një faqe, të gjitha versionet do të restaurohen në histori.
N.q.s. një faqe e re me të njëjtin titull është krijuar që nga grisja, versionet e
restauruara do të duken më përpara në histori, dhe versioni i faqes së fundit nuk do të
shkëmbehet automatikisht.",
"undeleterevision" => "U gris versioni i $1",
"undeletebtn" => "Restauro!",
"undeletedarticle" => "u restaurua \"$1\"",
"undeletedtext"   => "Faqja [[$1]] është restauruar me sukses.
Shiko [[$wgMetaNamespace:Gris_rregj]] për një listë të grisjeve dhe restaurimeve të fundit.",

# Contributions
#
"contributions"	=> "Redaktimet e përdoruesit",
"mycontris" => "Redaktimet e mia",
"contribsub"	=> "Për $1",
"nocontribs"	=> "S'ka asnjë ndryshim që të përputhet me këto kritere.",
"ucnote"		=> "Më poshtë janë redaktimet më të fundit të <b>$1</b> gjatë <b>$2</b> ditëve.",
"uclinks"		=> "Shiko $1 redaktimet e fundit; shiko $2 ditët e fundit.",
"uctop"		=> " (sipër)" ,

# What links here
#
"whatlinkshere"	=> "Lidhjet këtu",
"notargettitle" => "Asnjë artikull",
"notargettext"	=> "Nuk ke dhënë asnjë artikull ose përdorues mbi të cilin
të përdorim këtë funksion.",
"linklistsub"	=> "(Listë lidhjesh)",
"linkshere"		=> "Faqet e mëposhtëme lidhen këtu:",
"nolinkshere"	=> "Asnjë faqe nuk lidhet këtu.",
"isredirect"	=> "faqe ridrejtuese",

# Block/unblock IP
#
"blockip"		=> "Blloko përdoruesin",
"blockiptext"	=> "Përdor formularin e mëposhtëm për të hequr lejen e shkrimit
për një përdorues ose IP-ë specifike.
Kjo duhet bërë vetëm në raste vandalizmi, dhe në përputhje me [[$wgMetaNamespace:Rregullat|$wgSitename regullat]].
Plotëso arsyen specifike më poshtë (p.sh., trego faqet specifike që u
vandalizuan).",
"ipaddress"		=> "IP Adresë/përdorues",
"ipbreason"		=> "Arsye",
"ipbsubmit"		=> "Blloko këtë përdorues",
"badipaddress"	=> "Nuk ka asnjë përdorues me atë emër",
"noblockreason" => "Duhet të japësh një arsye për bllokimin.",
"blockipsuccesssub" => "Bllokimi u bë me sukses",
"blockipsuccesstext" => "\"$1\" është bllokuar.
<br>Shiko [[Speciale:Ipblocklist|IP blloko listë]] për të parë bllokimet.",
"unblockip"		=> "Ç'blloko përdoruesin",
"unblockiptext"	=> "Përdor formularin e më poshtëm për t'i ridhënë leje shkrimi
një përdoruesi ose IP adreseje të bllokuar.",
"ipusubmit"		=> "Ç'blloko këtë adresë",
"ipusuccess"	=> "\"$1\" u ç'bllokua",
"ipblocklist"	=> "Lista e përdoruesve dhe e IP adresave të bllokuara",
"blocklistline"	=> "$1, $2 bllokoi $3",
"blocklink"		=> "blloko",
"unblocklink"	=> "ç'blloko",
"contribslink"	=> "kontribute",
"autoblocker"	=> "I bllokuar automatikisht sepse përdor të njëjtën IP adresë si \"$1\". Arsye \"$2\".",

# Developer tools
#
"lockdb"		=> "Blloko rregjistrin",
"unlockdb"		=> "Ç'blloko rregjistrin",
"lockdbtext"	=> "Bllokimi i rregjistrit do të ndërpresi mundësinë e përdoruesve
për të redaktuar faqet, për të ndryshuar preferimet, për të ndryshuar listat mbikqyrëse të tyre, dhe
për gjëra të tjera për të cilat nevojiten shkrime në rregjistër.
Të lutem konfirmo që me vërte do të kryesh këtë veprim, dhe se do të ç'bllokosh rregjistrin
kur të mbarosh së kryeri mirëmbajtje.",
"unlockdbtext"	=> "Ç'bllokimi i rregjistrit do të lejojë mundësinë e të gjithë
përdoruesve për të redaktuar faqe, për të ndryshuar preferimet e tyre, për të ndryshuar listat mbikqyrëse të tyre, dhe
gjëra të tjera për të cilat nevojiten shkrime në rregjistër.
Të lutem konfirmo që me vërte do të kryesh këtë veprim.",
"lockconfirm"	=> "Po, dëshiroj me të vërtetë të bllokoj rregjistrin.",
"unlockconfirm"	=> "Po, dëshiroj me të vërtetë të ç'bllokoj rregjistrin",
"lockbtn"		=> "Blloko rregjistrin",
"unlockbtn"		=> "Ç'blloko rregjistrin",
"locknoconfirm" => "Nuk vendose kryqin tek kutia konfirmuese.",
"lockdbsuccesssub" => "Rregjistri u bllokua me sukses",
"unlockdbsuccesssub" => "Rregjistri u ç'bllokua me sukses",
"lockdbsuccesstext" => "Rregjistri i $wgSitename është bllokuar.
<br>Kujtohu ta ç'bllokosh mbasi të kesh mbaruar mirëmbajtjen.",
"unlockdbsuccesstext" => "Rregjistri i $wgSitename është ç'bllokuar.",

# SQL query
#
"asksql"		=> "SQL pyetje",
"asksqltext"	=> "Përdor formularin e më poshtëm për të bërë një pyetje direkte tek rregjistri i $wgSitename .
Përdor këto apostrofa ('kështu') për të ndarë vargjet e gërmave.
Ky veprim mund ta ngarkojë shumë shërbyesin, prandaj mos e përdor shumë shpesh.",
"sqlislogged"	=> "Vë re që të gjitha pyetjet janë të rregjistruara.",
"sqlquery"		=> "Fut pyetjen",
"querybtn"		=> "Bëj pyetjen",
"selectonly"	=> "Pyetje përveç atyre \"SELECT\" janë të lejuara vetëm për
$wgSitename zhvillues.",
"querysuccessful" => "Pyetje me sukses",

# Move page
#
"movepage"		=> "Zhvendose faqen",
"movepagetext"	=> "Duke përdor formularin e mëposhtëm do të ndërrosh titullin e një faqeje,
duke zhvendosur gjithë historinë përkatëse tek titulli i ri.
Titulli i vjetër do të bëhet një faqe ridrejtuese tek titulli i ri.
Lidhjet tek faqja e vjetër nuk do të ndryshohen; duhet të kontrollosh
[[Speciale:Maintenance|mirëmbajtjen]] për ridrejtime të dyfishta ose të prishura.
Ti ke përgjegjësinë për tu siguruar që lidhjet të vazhdojnë të jenë të sakta.

Vë re se kjo faqe '''nuk''' do të zhvendoset n.q.s. ekziston një faqe
me titullin e ri, përveçse kur ajo të jetë bosh ose një ridrejtim dhe të mos ketë
një histori të vjetër. Kjo do të thotë se ti mund ta zhvendososh një faqe prapë tek emri
i vjetër n.q.s. ke bërë një gabim, dhe s'mund të prisësh një faqe që ekziston.

<b>KUJDES!</b>
Ky mund të jetë një ndryshim i madh dhe gjëra të papritura mund të ndoshin për një faqe
të shumë-frekuentuar; të lutem, ki kujdes dhe mendohu mirë para se të përdorësh këtë funksion.",
"movepagetalktext" => "Faqja a bashkangjitur e diskutimit, n.q.s. ekziston, do të zhvendoset automatikisht '''përveçse''' kur:
*Zhvendos një faqe midis hapësirave të ndryshme,
*Një faqe diskutimi jo-boshe ekziston nën titullin e ri, ose
*Nuk zgjedh kutinë më poshtë.

Në ato raste, duhet ta zhvendosësh ose perpuqësh faqen vetë n.q.s. dëshiron.",
"movearticle"	=> "Zhvendose faqen",
"movenologin"	=> "Nuk ke hyrë brënda",
"movenologintext" => "Duhet të kesh hapur një llogari dhe të kesh <a href=\"" .
  "{{localurle:Speciale:Userlogin}}\">hyrë brënda</a>
për të zhvendosur një faqe.",
"newtitle"		=> "Tek titulli i ri",
"movepagebtn"	=> "Zhvendose faqen",
"pagemovedsub"	=> "Zhvendosja doli me sukses",
"pagemovedtext" => "Faqja \"[[$1]]\" u zhvendos tek \"[[$2]]\".",
"articleexists" => "Një faqe me atë titull ekziston, ose titulli që
zgjodhe nuk është i saktë.
Të lutem zgjidh një tjetër.",
"talkexists"	=> "Faqja për vete u zhvendos, ndërsa
faqja e diskutimit nuk u zhvendos sepse një e tillë ekziston tek titulli i ri.
Të lutem, përpuqi vetë.",
"movedto"		=> "zhvendosur tek",
"movetalk"		=> "Zhvendos edhe faqen e \"diskutimit\", n.q.s. është e mundur.",
"talkpagemoved" => "Faqja e diskutimit korrespondente u zhvendos gjithashtu.",
"talkpagenotmoved" => "Faqja e diskutimit korrespondente <strong>nuk</strong> u zhvendos.",

# Math

	'mw_math_png' => "Gjithmonë PNG",
	'mw_math_simple' => "HTML në qoftë se është e thjeshtë ose ndryshe PNG",
	'mw_math_html' => "HTML në qoftë se është e mundur ose ndryshe PNG",
	'mw_math_source' => "Lëre si TeX (për shfletuesit tekst)",
	'mw_math_modern' => "E rekomanduar për shfletuesit modern",
	'mw_math_mathml' => 'MathML',

);

class LanguageSq extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSq;
		return $wgNamespaceNamesSq;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesSq;
		return $wgNamespaceNamesSq[$index];
	}


	function getNsIndex( $text ) {
		global $wgNamespaceNamesSq;
		foreach ( $wgNamespaceNamesSq as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Compatbility with alt names:
		if( 0 == strcasecmp( "Special", $text ) ) { return -1; } # So both Speciale: and Special: work
		if( 0 == strcasecmp( "Perdoruesi", $text ) ) return 2;
		if( 0 == strcasecmp( "Perdoruesi_diskutim", $text ) ) return 3;
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSq;
		return $wgQuickbarSettingsSq;
	}

	function getSkinNames() {
		global $wgSkinNamesSq;
		return $wgSkinNamesSq;
	}

	function getDateFormats() {
		global $wgDateFormatsSq;
		return $wgDateFormatsSq;
	}

	# localised date and time
	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . " " .
			$this->getMonthName( substr( $ts, 4, 2 ) ) . " ".
			(0 + substr( $ts, 6, 2 ));
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
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesSq;
		return $wgValidSpecialPagesSq;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesSq;
		return $wgSysopSpecialPagesSq;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesSq;
		return $wgDeveloperSpecialPagesSq;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesSq;
		if(array_key_exists($key, $wgAllMessagesSq))
			return $wgAllMessagesSq[$key];
		else
			return Language::getMessage($key);
	}

}

?>
