<?php
/** Samogitian (Žemaitėška)
 *
 * @ingroup Language
 * @file
 *
 * @author Hugo.arg
 * @author Zordsdavini
 * @author Siebrand
 * @author לערי ריינהארט
 * @author Nike
 */

$namespaceNames = array(
//	NS_MEDIA            => '',
	NS_SPECIAL          => 'Specēlos',
	NS_MAIN             => '',
	NS_TALK             => 'Aptarėms',
	NS_USER             => 'Nauduotuos',
	NS_USER_TALK        => 'Nauduotuojė_aptarėms',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_aptarėms',
	NS_IMAGE            => 'Abruozdielis',
	NS_IMAGE_TALK       => 'Abruozdielė_aptarėms',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_aptarėms',
	NS_TEMPLATE         => 'Šabluons',
	NS_TEMPLATE_TALK    => 'Šabluona_aptarėms',
	NS_HELP             => 'Pagelba',
	NS_HELP_TALK        => 'Pagelbas_aptarėms',
	NS_CATEGORY         => 'Kateguorėjė',
	NS_CATEGORY_TALK    => 'Kateguorėjės_aptarėms'
);

/** 
  * Aliases from the fallback language 'lt' to avoid breakage of links
  */

$namespaceAliases = array(
	'Specialus'             => NS_SPECIAL,
	'Aptarimas'             => NS_TALK,
	'Naudotojas'            => NS_USER,
	'Naudotojo_aptarimas'   => NS_USER_TALK,
	'$1_aptarimas'          => NS_PROJECT_TALK,
	'Vaizdas'               => NS_IMAGE,
	'Vaizdo_aptarimas'      => NS_IMAGE_TALK,
	'MediaWiki_aptarimas'   => NS_MEDIAWIKI_TALK,
	'Šablonas'              => NS_TEMPLATE,
	'Šablono_aptarimas'     => NS_TEMPLATE_TALK,
	'Pagalba'               => NS_HELP,
	'Pagalbos_aptarimas'    => NS_HELP_TALK,
	'Kategorija'            => NS_CATEGORY,
	'Kategorijos_aptarimas' => NS_CATEGORY_TALK,
);

$fallback = 'lt';

$messages = array(
# User preference toggles
'tog-highlightbroken'         => 'Fuormoutė nasontiu poslapiu nūruodas <a href="#" class="new">šėtēp</a> (prīšėngā - šėtēp <a href="#" class="internal">?</a>).',
'tog-justify'                 => 'Līgintė pastraipas palē abi poses',
'tog-hideminor'               => 'Pakavuotė mažus pataisėmus vielībūju taisīmu sārašė',
'tog-extendwatchlist'         => 'Ėšpliestė keravuojamu sāraša, kū ruodītu vėsus tėnkamus pakeitėmus',
'tog-usenewrc'                => 'Pažongē ruodomė vielibė̅jė pakeitėmā (JavaScript)',
'tog-showtoolbar'             => 'Ruodītė redagavėma rakondinė (JavaScript)',
'tog-editsectiononrightclick' => 'Ijongtė skėrsneliu redagavėma paspaudos skėrsnelė pavadėnėma<br />dešėniouju pelies klavėšu (JavaScript)',
'tog-editwidth'               => 'Pėlna pluotė redagavėma lauks',
'tog-watchcreations'          => 'Pridietė poslapius, katrūs sokorio, i keravuojamu sāraša',
'tog-watchdefault'            => 'Pridietė poslapius, katrūs taisau, i keravuojamu sāraša',
'tog-watchmoves'              => 'Pridietė poslapius, katrūs parkelio, i keravuojamu sāraša',
'tog-watchdeletion'           => 'Pridietė poslapius, katrūs ėštrino, i keravuojamu sāraša',
'tog-minordefault'            => 'Palē nutīliejėma pažīmietė redagavėmus kāp mažus',
'tog-nocache'                 => "Nenauduotė poslapiu kaupėma (''caching'')",
'tog-enotifusertalkpages'     => 'Siōstė mon gromata, kūmet pakaitams mona nauduotuojė aptarėma poslapis',
'tog-enotifminoredits'        => 'Siōstė mon gromata, kūmet poslapė keitėms īr mažos',
'tog-shownumberswatching'     => 'Ruodītė keravuojantiu nauduotuoju skatliu',
'tog-externaleditor'          => 'Palē nutīliejėma nauduotė ėšuorini radaktuoriu',
'tog-externaldiff'            => 'Palē nutīliejėma nauduotė ėšuorinė skėrtomu ruodīma pruograma',
'tog-watchlisthidebots'       => 'Kavuotė robotu pakeitėmos keravuojamu sārašė',

'underline-always'  => 'Visumet',
'underline-never'   => 'Nikumet',
'underline-default' => 'Palē naršīklės nostatīmos',

'skinpreview' => '(Parveiza)',

# Dates
'sunday'        => 'sekma dėina',
'monday'        => 'pėrmadėinis',
'tuesday'       => 'ontradėinis',
'wednesday'     => 'trečiadėinis',
'thursday'      => 'ketvėrtadėinis',
'friday'        => 'pėnktadėinis',
'saturday'      => 'subata',
'sun'           => 'Sekm',
'mon'           => 'Pėrm',
'tue'           => 'Ontr',
'wed'           => 'Treč',
'thu'           => 'Ketv',
'fri'           => 'Pėnk',
'sat'           => 'Sub',
'january'       => 'sausė',
'february'      => 'vasarė',
'march'         => 'kuova',
'april'         => 'balondė',
'may_long'      => 'gegožės',
'june'          => 'bėrželė',
'july'          => 'lėipas',
'august'        => 'rogpjūtė',
'september'     => 'siejės',
'october'       => 'spalė',
'november'      => 'lapkrėstė',
'december'      => 'groudė',
'january-gen'   => 'Sausis',
'february-gen'  => 'Vasaris',
'march-gen'     => 'Kuovs',
'april-gen'     => 'Balondis',
'may-gen'       => 'Gegožė',
'june-gen'      => 'Bėrželis',
'july-gen'      => 'Lėipa',
'august-gen'    => 'Rogpjūtis',
'september-gen' => 'Siejė',
'october-gen'   => 'Spalis',
'november-gen'  => 'Lapkrėstis',
'december-gen'  => 'Groudis',
'jan'           => 'sau',
'feb'           => 'vas',
'apr'           => 'bal',
'may'           => 'geg',
'aug'           => 'rgp',
'oct'           => 'spa',
'nov'           => 'lap',
'dec'           => 'grd',

# Bits of text used by many pages
'categories'     => 'Kateguorėjės',
'pagecategories' => '{{PLURAL:$1|Kateguorėjė|Kateguorėjės|Kateguorėju}}',
'category-empty' => "''Šėta kateguorėjė nūnā netor nė vėina straipsnė a faila.''",

'about'          => 'Aple',
'article'        => 'Straipsnis',
'newwindow'      => '(īr atverams naujam longė)',
'cancel'         => 'Nutrauktė',
'qbfind'         => 'Ėiškuotė',
'qbbrowse'       => 'Naršītė',
'qbedit'         => 'Taisītė',
'qbpageoptions'  => 'Tas poslapis',
'qbpageinfo'     => 'Konteksts',
'qbmyoptions'    => 'Mona poslapē',
'qbspecialpages' => 'Specēlė̅jė poslapē',
'moredotdotdot'  => 'Daugiau...',
'mypage'         => 'Mona poslapis',
'mytalk'         => 'Mona aptarėms',
'anontalk'       => 'Šėta IP aptarėms',
'navigation'     => 'Navigacėjė',

'errorpagetitle'   => 'Klaida',
'returnto'         => 'Grīžtė i $1.',
'tagline'          => 'Straipsnis ėš {{SITENAME}}.',
'help'             => 'Pagelba',
'search'           => 'Ėiškuotė',
'searchbutton'     => 'Ėiškuok',
'go'               => 'Ēk',
'searcharticle'    => 'Ēk',
'history'          => 'Poslapė istorėjė',
'history_short'    => 'Istuorėjė',
'updatedmarker'    => 'atnaujėnta nu paskotėnė mona apsėlonkīma',
'info_short'       => 'Infuormacėjė',
'printableversion' => 'Versėjė spausdintė',
'permalink'        => 'Nulatėnė nūruoda',
'print'            => 'Spausdintė',
'edit'             => 'Taisītė',
'editthispage'     => 'Taisītė ton poslapė',
'delete'           => 'Trintė',
'deletethispage'   => 'Trintė ton poslapė',
'jumpto'           => 'Paršuoktė i:',
'jumptonavigation' => 'navėgacėjė',
'jumptosearch'     => 'paėiška',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Aple {{SITENAME}}',
'bugreports'        => 'Praneštė aple klaida',
'bugreportspage'    => 'Project:Klaidū pranešėmā',
'copyrightpagename' => '{{SITENAME}} autorėnės teisės',
'currentevents'     => '** Vielībė̅jė ivīkē **',
'currentevents-url' => 'Project:Vielībė̅jė ivīkē',
'edithelppage'      => 'Help:Redagavėms',
'helppage'          => 'Help:Torėnīs',
'mainpage'          => 'Pėrms poslapis',
'sitesupport'       => 'Pagelba',
'sitesupport-url'   => 'Project:Tėnklalapė palaikīms',

'retrievedfrom'  => 'Gautė ėš „$1“',
'thisisdeleted'  => 'Veizėtė a atkortė $1?',
'site-rss-feed'  => '$1 RSS šaltėnis',
'site-atom-feed' => '$1 Atom šaltėnis',
'page-rss-feed'  => '„$1“ RSS šaltėnis',
'page-atom-feed' => '„$1“ Atom šaltėnis',
'red-link-title' => '$1 (da neparašīts)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user' => 'Nauduotuojė poslapis',

# Main script and global functions
'nosuchaction'      => 'Nier tuokė veiksma',
'nosuchspecialpage' => 'Nier tuokė specēlėjė poslapė',
'nospecialpagetext' => 'Tamsta prašiet nelaistėna specēlė̅jė poslapė, laistėnū specēliūju poslapiu sōraša rasėt [[Special:Specialpages|specēliūju poslapiu sārošė]].',

# General errors
'error'               => 'Klaida',
'badtitle'            => 'Bluogs pavadėnėms',
'badtitletext'        => 'Nuruodīts poslapė pavadėnėms bova neleistėns, toščės a neteisėngā sojongts terpkalbinis a terppruojektėnis pavadėnėms. Anamė gal būtė vėins a daugiau sėmbuoliu, neleistėnū pavadėnėmūs',
'ns-specialprotected' => '„{{ns:special}}“ vardū srėtī poslapē negal būtė keitamė.',

# Login and logout pages
'loginprompt'                => 'Ijonkėt pakavukus, jēgo nuorėt prisėjongtė pri {{SITENAME}}.',
'nologin'                    => 'Netorėt prisėjongėma varda? $1.',
'gotaccount'                 => 'Jau torėt paskīra? $1.',
'username'                   => 'Nauduotuojė vards:',
'uid'                        => 'Nauduotuojė ID:',
'yourlanguage'               => 'Aplėnkuos kalba:',
'prefs-help-realname'        => 'Tėkrs vards nier privaluoms, vuo jēgo Tamsta ana ivesėt, ons bus nauduojams Tamstas darba pažīmiejėmō.',
'nocookieslogin'             => "Vikipedėjė nauduo pakavukus (''cookies''), kū prijongtu nauduotuojus. Tamsta esat ėšjongės anūs. Prašuom ijongtė pakavukus ė pamiegītė viel.",
'loginsuccess'               => "'''Nūnā Tamsta esot prisėjongės pri {{SITENAME}} kāp „$1“.'''",
'nosuchuser'                 => 'Nier anėjuokė nauduotuojė pavadėnta „$1“. Patikrėnkėt rašība, a sokorkėt naujė paskīra.',
'nosuchusershort'            => 'Nier juokė nauduotuojė, pavadėnta „$1“. Patėkrinkėt rašība.',
'passwordtooshort'           => 'Tamstas slaptažuodis nier laistėns aba par tromps īr. Ans tor būtė nuors $1 sėmbuoliu ėlgoma ė skėrtės nū Tamstas nauduotuojė varda.',
'passwordremindertitle'      => 'Laikėns {{SITENAME}} slaptažuodis',
'passwordremindertext'       => 'Kažkastā (tėkriausē Tamsta, IP adreso $1)
paprašė, kū atsiōstomiet naujė slaptažuodi pruojektō {{SITENAME}} ($4).
Nauduotuojė „$2“ slaptažuodis nūnā īr „$3“.
Tamsta torietomiet prisėjongtė ė daba pakeistė sava slaptažuodi.

Jēgo kažkas kėts atlėka ta prašīma aba Tamsta prisėmėniet sava slaptažuodi ė
nebnuorėt ana pakeistė, Tamsta galėt tėisiuog nekreiptė diemiesė ė šėta gruomata ė tuoliau
nauduotis sava senu slaptažuodžiu.',
'noemail'                    => 'Nier anėjuokė el. pašta adresa ivesta nauduotuojō „$1“.',
'passwordsent'               => 'Naus slaptažuodis bova nusiōsts i el. pašta adresa,
ožregėstrouta nauduotuojė „$1“.
Prašuom prisėjongtė vielē, kumet Tamsta gausėt anū.',
'blocked-mailpassword'       => 'Tamstas IP adresos īr ožblokouts nū redagavėma, tudie neleidama nauduotė slaptažuodė priminėma funkcėjės, kū apsėsauguotomė nū pėktnaudžēvėma.',
'eauthentsent'               => 'Patvėrtėnėma gruomata bova nusiōsta i paskėrta el. pašta adresa.
Prīš ėšsiontiant kėta gruomata i Tamstas diežote, Tamsta torėt vīkdītė nuruodīmus gruomatuo, kū patvėrtėntomiet, kū diežotė tėkrā īr Tamstas.',
'throttled-mailpassword'     => 'Slaptažuodžė priminims jau bova ėšsiōsts, par paskotėnes $1 adīnas. Nuorint apsėsauguotė nū pėktnaudžēvėma, slaptažuodė priminims gal būt ėšsiōsts tėk kas $1 adīnas.',
'acct_creation_throttle_hit' => 'Tamsta jau sokūriet $1 prisėjongėma varda. Daugiau nebgalėma.',

# Password reset dialog
'resetpass_success' => 'Tamstas slaptažuodis pakeists siekmėngā! Daba prėsėjongiama...',

# Edit page toolbar
'extlink_sample' => 'http://www.pavīzdīs.lt nūruodas pavadėnėms',
'extlink_tip'    => 'Ėšuorėnė nūruoda (nepamėrškėt http:// priraša)',

# Edit pages
'blockedtitle'              => 'Nauduotuos īr ožblokouts',
'blockedtext'               => "<big>'''Tamstas nauduotuojė vards a IP adresos īr ožblokouts.'''</big>

Ožbluokava $1. Nuruodīta prižastis īr ''$2''.

* Bluokavėma pradžia: $8
* Bluokavėma pabenga: $6
* Numatīts bluokoujamasės: $7

Tamsta galėt sosėsėiktė so $1 a kėtu
[[{{MediaWiki:Grouppage-sysop}}|adminėstratuoriom]], kū aptartė ožbluokavėma.
Tamsta negalėt nauduotės funkcėjė „Rašītė laiška tam nauduotuojō“, jēgo nesot pateikis tėkra sava el. pašta adresa sava [[{{ns:special}}:Preferences|paskīruos nustatīmūs]] ė nesot ožblokouts nu anuos nauduojėma.
Tamstas dabartėnis IP adresos īr $3, a bluokavėma ID īr #$5. Prašuom nuruodītė vėina aba abo anūs, kumet kreipiatės diel bluokavėma.",
'newarticletext'            => "Tamsta pakliovuot i nūnā neesoti poslapi.
Nuoriedamė sokortė poslapi, pradiekėt rašītė žemiau esontiamė ivedima pluotė
(platiau [[{{MediaWiki:Helppage}}|pagelbas poslapī]]).
Jēgo pakliovuot čė netīčiuom, paprastiausē paspauskėt naršīklės mīgtoka '''atgal'''.",
'noarticletext'             => 'Tuo čiesu tamė poslapī nier juokė teksta, Tamsta galėt [[Special:Search/{{PAGENAME}}|ėiškuotė šėta poslapė pavadėnėma]] kėtūs poslapiūs a [{{fullurl:{{FULLPAGENAME}}|action=edit}} keistė ta poslapi].',
'usercsspreview'            => "'''Napamirškėt, kū Tamsta tėk parveizėt sava nauduotoja CSS, ans da nabova ėšsauguots!'''",
'userjspreview'             => "'''Nepamirškėt, kū Tamsta tėk testoujat/parvaizėt sava nauduotoja ''JavaScript'', ans da nabova ėšsauguots!'''",
'previewnote'               => '<strong>Nepamėrškėt, kū tas tėktās pervaiza, pakeitėmā da nier ėšsauguotė!</strong>',
'session_fail_preview'      => '<strong>Atsiprašuom! Mes nagalėm vīkdītė Tamstas keitėma diel sesėjės doumenū praradima.
Prašuom pamiegintė vielēk. Jei šėtā napaded, pamieginkėt atsėjongtė ėr prėsėjongtė atgal.</strong>',
'session_fail_preview_html' => "<strong>Atsėprašuom! Mes nagalėm apdoroutė Tamstas keitėma diel sesėjės doumenū praradėma.</strong>
''Kadaogi šėtom pruojekte grīnasės HTML īr ijongts, parveiza īr pasliepta kāp atsargoma prėimonė priš JavaScript atakas.''
<strong>Jei tā teisiets keitėma bandīms, prašuom pamiegint viel. Jei šėtā napaded, pamieginkėt atsėjongtė ėr prėsėjongtė atgal.</strong>",
'editinguser'               => "Taisuoms nauduotuos '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'copyrightwarning'          => 'Primenam, kū vėsks, kas patenk i {{SITENAME}}, īr laikuoma pavėišėnto palē $2 (platiau - $1). Jēgo nenuorit, kū Tamstas duovis būtou ba pasėgailiejėma keitams ė platėnams, nerašīkėt čė.<br />
Tamsta tēpuogi pasėžadat, kū tas īr Tamstas patėis rašīts torėnīs a kuopėjouts ėš vėišū a panašiū valnū šaltėniu.
<strong>NEKOPĖJOUKĖT AUTUORĖNIEM TEISIEM APSAUGUOTU DARBŪ BA LEIDĖMA!</strong>',
'longpagewarning'           => '<strong>DIEMESĖ: Tas poslapis īr $1 kilobaitu ėlgoma; katruos nekatruos
naršīklės gal torietė biedū redagounant poslapius bavēk a vėrš 32 KB.
Prašuom pamiegītė poslapi padalėntė i keleta smolkesniū daliū.</strong>',
'readonlywarning'           => '<strong>DIEMESĖ: Doumenū bazė bova ožrakėnta teknėnē pruofilaktėkā,
tudie negaliesėt ėšsauguotė sava pakeitėmu daba. Tamsta galėt nosėkopėjoutė teksta i tekstėni faila
ė paskum ikeltė ana čė.</strong>',
'nocreatetext'              => '{{SITENAME}} aprėbuojė galėmībe kortė naujus poslapius.
Tamsta galėt grīžtė ė redagoutė nūnā esonti poslapi, a [[Special:Userlogin|prėsėjongtė a sokortė paskīra]].',

# "Undo" feature
'undo-success' => 'Keitėms gal būtė atšaukts. Prašuom patėkrėntė palīgėnėma, asonti žemiau, kū patvėrtėntomiet, kū Tamsta šėta ė nuorėt padarītė, ė tumet ėšsauguokit pakeitėmos, asontios žemiau, kū ožbėngtomiet keitėma atšaukėma.',
'undo-summary' => 'Atšauktė [[Special:Contributions/$2|$2]] ([[User_talk:$2|Aptarėms]]) versėje $1',

# History pages
'revision-info' => '$1 versėjė nauduotuojė $2',
'cur'           => 'dab',
'last'          => 'pask',
'page_last'     => 'pask',
'histlast'      => 'Vielibė̅jė',

# Revision feed
'history-feed-item-nocomment' => '$1 $2', # user at time

# Revision deletion
'revdelete-unsuppress' => 'Šalėntė apribuojėmos atkortuos versėjės',

# Oversight log
'overlogpagetext' => 'Žemiau īr sārašos paskotėniu trīnimu ė bluokavėmu. [[Special:Ipblocklist|IP bluokavėmu istuorėjuo]] rasėt šėtuo čieso veikiantiu draudėmu ė bluokavėmu sāraša.',

# History merging
'mergehistory-success' => '$3 [[:$1]] versėju siekmėngā sojongta so [[:$2]].',

# Diffs
'diff-multi' => '($1 {{PLURAL:$1|tarpėnis keitėms nier ruoduoms|tarpėnē keitėmā nier ruoduomė|tarpėniu keitėmu nier ruoduoma}}.)',

# Search results
'searchsubtitle'    => 'Ėiškuoma „[[:$1]]“',
'noexactmatch'      => "'''Nier anėjuokė poslapė, pavadėnta „$1“.''' Tamsta galėt [[:$1|sokortė ta poslapi]].",
'showingresults'    => "Žemiau ruodoma lėgė '''$1''' rezoltatu pradedant #'''$2'''.",
'showingresultsnum' => "Žemiau ruodoma '''$3''' {{PLURAL:$3|rezoltata|rezoltatu|rezoltatu}} pradedant #'''$2'''.",
'powersearch'       => 'Ėiškuotė',
'powersearchtext'   => 'Ėiškoutė tuosė vardū srėtīsė:<br />$1<br /><label>$2 Ruodītė paradresavėmos</label><br />Ėiškoutė $3 $9',

# Preferences page
'prefs-personal'        => 'Nauduotuojė pruopilis',
'prefs-watchlist'       => 'Keravuojamu sārašos',
'prefs-watchlist-days'  => 'Kėik dėinū ruodītė keravuojamu sārašė:',
'prefs-watchlist-edits' => 'Kėik pakeitėmu ruodītė ėšpliestiniam keravuojamu sārašė:',
'recentchangesdays'     => 'Ruodomas dėinas vielībūju pakeitėmu sārašė:',
'recentchangescount'    => 'Kėik pakeitėmū ruodoma vielībūju kėitėmu poslapī',
'defaultns'             => 'Palē nutīliejėma ėiškuotė šėtuosė vardū srėtīsė:',
'default'               => 'palē nūtīliejėma',

# User rights
'userrights-lookup-user'    => 'Tvarkītė nauduotuojė gropės',
'userrights-user-editname'  => 'Iveskėt nauduotuojė varda:',
'editusergroup'             => 'Redagoutė nauduotuojė gropes',
'userrights-editusergroup'  => 'Keistė nauduotuoju gropes',
'userrights-available-none' => 'Tamsta nagalėt keistė gropės narīstės.',

'grouppage-autoconfirmed' => '{{ns:project}}:Automatėškā patvėrtintė nauduotuojē',
'grouppage-bot'           => '{{ns:project}}:Robuotā',
'grouppage-sysop'         => '{{ns:project}}:Adminėstratuorē',
'grouppage-bureaucrat'    => '{{ns:project}}:Biorokratā',

# User rights log
'rightslog'     => 'Nauduotuoju teisiu istuorėjė',
'rightslogtext' => 'Pateikiams nauduotuoju teisiu pakeitėmu sārašos.',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pakeitims|pakeitimā|pakeitimu}}',
'recentchanges'                     => 'Vielībė̅jė pakeitėmā',
'recentchanges-feed-description'    => 'Keravuokėt patius vielībiausius pakeitėmus pruojektō tamė šaltėnī.',
'rcnote'                            => "Žemiau īr '''$1''' {{PLURAL:$1|paskotinis pakeitims|paskotinē pakeitimā|paskotiniu pakeitimu}} par $2 {{PLURAL:$2|paskotinė̅jė dėina|paskotėniasės dėinas|paskotėniuju dėinū}} skaitlioujant nū $3.",
'rcshowhideminor'                   => '$1 mažus pakeitėmus',
'rcshowhidebots'                    => '$1 robuotus',
'hist'                              => 'ist',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'r',
'number_of_watching_users_pageview' => '[$1 keravuojantiu nauduotuoju]',
'rc_categories_any'                 => 'Bikuokė',

# Upload
'upload'         => 'Ikeltė faila',
'uploadscripted' => 'Šėts failos tor HTML a programėni kuoda, katros gal būtė klaidėngā soprasts interneta naršīklės.',
'uploadcorrupt'  => 'Fails īr pažeists a tor neteisėnga galūne. Prašuom patėkrėntė faila ėr ikeltė ana par naujė.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error28-text' => 'Atsakontė svetainė ožtronk par ėlgā. Patėkrėnkėt, a svetainė veik, palaukėt tropoti ė vielē pamiegīkit. Mažo Tamstā rēktu pamiegītė ne tuokio apkrauto čieso.',

'upload_source_file' => ' (fails Tamstas kompioterī)',

# Image list
'imagelist'     => 'Failu sārašos',
'imagelisttext' => "Žemiau īr '''$1''' failu sārašos, sorūšiouts $2.",
'filehist-help' => 'Paspauskėt ont datas/čiesa, ka paveizietomėt faila tuoki, kokis ons bova tū čiesu.',
'sharedupload'  => 'Tas fails īr ikelts bendram nauduojėmō ė gal būtė nauduojams kėtūs pruojektūs.',

# Unwatched pages
'unwatchedpages' => 'Nekeravuojėmė poslapē',

# List redirects
'listredirects' => 'Paradresavėmu sārašos',

# Unused templates
'unusedtemplates'     => 'Nenauduojamė šabluonā',
'unusedtemplatestext' => 'Šėts poslapis ruod sāraša poslapiu, esontiu šabluonu vardū srėtī, katrė nė iterptė i juoki kėta poslapi. Nepamėrškėt patėkrėntė kėtū nūruodu priš anūs ėštrėnont.',
'unusedtemplateswlh'  => 'kėtas nūruodas',

# Random page
'randompage' => 'Bikuoks poslapis',

# Random redirect
'randomredirect' => 'Bikuoks paradresavėms',

# Statistics
'sitestats' => 'Tėnklalapė statėstėka',

'withoutinterwiki'        => 'Poslapē ba kalbū nūruodu',
'withoutinterwiki-header' => 'Šėtė poslapē neruod i kėtū kalbū versėjės:',

# Miscellaneous special pages
'nbytes'                 => '$1 {{PLURAL:$1|baits|baitā|baitu}}',
'ncategories'            => '$1 kateguorėju',
'nlinks'                 => '$1 {{PLURAL:$1|nūruoda|nūruodas|nūruodu}}',
'nmembers'               => '$1 {{PLURAL:$1|narīs|narē|nariū}}',
'nrevisions'             => '$1 pakeitėmu',
'nviews'                 => '$1 paruodīmu',
'uncategorizedpages'     => 'Poslapē, napriskėrtė juokē kateguorėjē',
'uncategorizedimages'    => 'Abruozdielē, nepriskėrtė juokē kateguorėjē',
'uncategorizedtemplates' => 'Šabluonā, nepriskėrtė juokē kateguorėjē',
'unusedcategories'       => 'Nenauduojamas kateguorėjės',
'mostimages'             => 'Daugiausē ruodomė abruozdielē',
'prefixindex'            => 'Ruodīklė palē pavadinėma pradē',
'deadendpagestext'       => 'Tė poslapē netor nūruodu i kėtus poslapius šėtom pruojektė.',
'protectedpagestext'     => 'Šėtē poslapē īr apsauguotė nū parkielėma a redagavėma',
'protectedpagesempty'    => 'Šėtu čiesu nier apsauguots anėjuoks fails so šėtās parametrās.',
'listusers'              => 'Sārašos nauduotuoju',
'specialpages'           => 'Specēlė̅jė poslapē',
'spheading'              => 'Specēlė̅jė poslapē vėsėm nauduotuojam',
'unusedimagestext'       => 'Primenam, kū kėtas svetainės gal būtė nuruodiosės i abruozdieli tėisiogėniu URL, no vėstėik gal būtė šėtom sārašė, nuors ėr īr aktīvē naudounams.',
'unusedcategoriestext'   => 'Šėtū kateguorėju poslapē sokortė, nuors juoks kėts straipsnis a kateguorėjė ana nenauduo.',
'notargettext'           => 'Tamsta nenuruodiet nuorima poslapė a nauduotuojė,
katram ivīkdītė šėta funkcėjė.',
'pager-newer-n'          => '$1 {{PLURAL:$1|paskesnis|paskesni|paskesniū}}',
'pager-older-n'          => '{{PLURAL:$1|senesnis|senesni|senesniū}}',

'userrights' => 'Nauduotuoju teisiu valdīms',

# Special:Allpages
'allpagesbadtitle' => 'Douts poslapė pavadėnėms īr neteisings a tor terpkalbėnė a terppruojektėnė prīdielė. Anamė īr vėns a kelė žėnklā, katrū negal nauduotė pavadėnėmūs.',

# E-mail user
'noemailtext' => 'Šėts nauduotuos nier nuruodės teisėnga el.pašta adresa a īr pasėrinkės negautė el. pašta ėš kėtū nauduotuoju.',
'emailsend'   => 'Siōstė',

# Watchlist
'watchlist'            => 'Keravuojamė straipsnē',
'watchlistanontext'    => 'Prašuom $1, ka parveizietomėt a pakeistomiet elementus sava keravuojamu sārašė.',
'addedwatch'           => 'Pridieta pri keravuojamu',
'removedwatch'         => 'Pašalėntė ėš keravuojamu',
'watchlist-details'    => 'Keravuojama $1 {{PLURAL:$1|poslapis|poslapē|poslapiu}} neskaitlioujant aptarėmu poslapiu.',
'watchlistcontains'    => 'Tamsta kervuojamu sārašė īr $1 {{PLURAL:$1|poslapis|poslapē|poslapiu}}.',
'wlnote'               => "Ruoduoma '''$1''' paskotėniu pakeitėmu, atlėktū par '''$2''' paskotėniu adīnu.",
'watchlist-show-minor' => 'Ruodītė mažos keitėmos',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Itraukiama i keravuojamu sāraša...',
'unwatching' => 'Šalėnama ėš keravuojamu sāraša...',

# Delete/protect/revert
'historywarning'        => 'Diemesė: Trėnams poslapis tor istuorėjė:',
'confirmdeletetext'     => 'Tamsta pasėrėnkuot ėštrėntė poslapi a abruozdieli draugum so vėsa anuo istuorėjė.
Prašuom patvėrtėntė, kū Tamsta tėkrā nuorėt šėtu padarītė, žėnuot aple galėmus padarėnius, ė kū Tamsta šėtā daruot atsėžvelgdamė i [[{{MediaWiki:Policy-url}}|puolitėka]].',
'dellogpagetext'        => 'Žemiau īr pateikiams paskotiniu ėštrīnimu sārašos.',
'sessionfailure'        => 'Atruod kū īr biedū so Tamstas prėsėjongėma sesėjė; šėts veiksmos bova atšaukts kāp atsargoma prėimonė priš sesėjės vuogėma.
Prašoum paspaustė „atgal“ ėr parkrautė poslapi ėš katruo atiejot, ė pamieginkėt vielē.',
'confirmprotect'        => 'Ožrakėnima patvėrtėnims',
'protect-text'          => 'Čė Tamsta galėt paveizėtė ė pakeistė apsauguos līgi šėtuo poslapio <strong>$1</strong>.',
'protect-locked-access' => 'Tamstas paskīra netor teisiu keistė poslapiu apsauguos līgiu.
Čė īr dabartėnē nustatīmā poslapiō <strong>$1</strong>:',
'protect-cascadeon'     => 'Tas poslapis nūnā īr apsauguots, kadongi ons īr itraukts i {{PLURAL:$1|ta poslapi, apsauguota|tūs poslapiūs, apsauguotus}} „pakuopėnės apsauguos“ pasėrėnkėmu. Tamsta galėt pakeistė šėta poslapė apsauguos līgi, no tas nepaveiks pakuopėnės apsauguos.',
'protect-default'       => '(palē nutīliejėma)',
'protect-fallback'      => 'Rēkalautė „$1“ teisės',
'protect-level-sysop'   => 'Tėktās adminėstratuorē',
'protect-cantedit'      => 'Tamsta negalėt keistė šėta poslapė apsauguojėma līgiu, kagongi netorėt teisiu anuo redagoutė.',

# Restrictions (nouns)
'restriction-create' => 'Sokortė',

# Restriction levels
'restriction-level-all' => 'bikuoks',

# Undelete
'undeleterevisions'        => '$1 versėju soarkīvouta',
'undeleterevision-missing' => 'Neteisėnga a dėngosė versėjė. Tamsta mažo torėt bluoga nūruoda, a versėjė bova atkorta a pašalėnta ėš arkīva.',
'undeletedrevisions'       => 'atkorta $1 versėju',
'undeletedrevisions-files' => 'atkorta $1 versėju ėr $2 failu',
'undeletedfiles'           => 'atkorta $1 failu',
'undeletedpage'            => "<big>'''$1 bova atkurts'''</big>
Parveizėkiet [[Special:Log/delete|trīnimu sāraša]], nuoriedamė rastė paskotėniu trīnimu ėr atkorėmu sāraša.",

# Namespace form on various pages
'namespace' => 'Vardū srėtis:',

# Contributions
'contributions' => 'Nauduotuojė duovis',
'contribsub2'   => 'Nauduotuojė $1 ($2)',
'month'         => 'Nu mienėsė (ėr onkstiau):',

'sp-contributions-search'   => 'Ėiškuotė duovė',
'sp-contributions-username' => 'IP adresos a nauduotuojė vards:',
'sp-contributions-submit'   => 'Ėiškuotė',

'sp-newimages-showfrom' => 'Ruodītė naujus abruozdielius pradedant nū $2, $1',

# What links here
'whatlinkshere-page' => 'Poslapis:',
'linklistsub'        => '(Nūruodu sārašos)',
'istemplate'         => 'iterpims',
'whatlinkshere-prev' => '$1 {{PLURAL:$1|onkstesnis|onkstesni|onkstesniū}}',
'whatlinkshere-next' => '$1 {{PLURAL:$1|kėts|kėtė|kėtū}}',

# Block/unblock
'ipbreason-dropdown' => '*Dažniausės bluokavėma prižastīs
** Melagėngas infuormacėjės rašīms
** Torėnė trīnims ėš poslapiu
** Spaminims
** Zaunu/bikuo rašīms i poslapios
** Gondinėmā/Pėktžuodiavėmā
** Pėktnaudžiavėms paskėruomis
** Netėnkams nauduotuojė vards',
'ipbcreateaccount'   => 'Nelaistė kortė paskīrū',
'ipb-blocklist-addr' => 'Ruodītė esontius $1 bluokavėmus',
'unblockip'          => 'Atbluokoutė nauduotuoja',
'unblockiptext'      => 'Nauduokėt šėta fuorma, kū atkortomiet rašīma teises
onkstiau ožbluokoutam IP adresō a nauduotuojō.',
'ipblocklist'        => 'Blokoutu IP adresū ė nauduotuoju sārašos',
'blocklogtext'       => 'Čė īr nauduotuoju blokavėma ėr atblokavėma sārašos. Autuomatėškā blokoutė IP adresā nier ėšvardėntė. Jeigu nuorėt paveizėtė nūnā blokoujamus adresus, veizėkėt [[Special:Ipblocklist|IP ožbluokavėmu istuorėjė]].',

# Developer tools
'unlockdbtext' => 'Atrakėnos doumenū baze grōžėns galimībe vėsėm
nauduotuojam redagoutė poslapios, keistė anū nostatīmos, keistė anū keravuojamu sāraša ė
kėtos dalīkos, rēkalaujontios pakeitėmu doumenū bazė.
Prašuom patvėrtėntė šėtā, kū ketinat padarītė.',

# Move page
'movepagetext'    => "Nauduodamė žemiau pateikta fuorma, parvadinsėt poslapi neprarasdamė anuo istuorėjės.
Senasis pavadinėms pataps nukrēpiamouju - ruodīs i naujīji.
'''Nūruodas i senaji poslapi nebus autuomatėškā pakeistos, tudie būtinā patėkrinkėt a nesokūriet dvėgobu a neveikontiu nukreipėmu'''.
Tamsta esat atsakėngs ož šėta, kū nūruodas ruodītu i ten, kor ė nuorieta.

Primenam, kū poslapis '''nebus''' parvadints, jēgo jau īr poslapis naujo pavadinėmo, nebent tas poslapis īr tuščės a nukreipēmasis ė netor redagavėma istuorėjės.
Tumet, Tamsta galėt parvadintė poslapi seniau nauduota vardu, jēgo priš šėta ons bova par klaida parvadints, a egzėstounantiu poslapiu sogadintė negalėt.

'''DIEMESĖ!'''
Jēgo parvadinat puopoliaru poslapi, tas gal sokeltė nepagēdaunamu šalotiniu efektu, tudie šėta veiksma vīkdīkit tėk isitėkine,
kū soprantat vėsas pasiekmes.",
'movenologintext' => 'Nuoriedamė parvadintė poslapi, torėt būtė ožsėregėstravės nauduotuos ė teipuogi būtė [[Special:Userlogin|prisėjongės]].',
'articleexists'   => 'Straipsnis so tuokiu vardo jau īr
a parinktāsis vards īr bluogs.
Parinkat kėta varda.',
'movelogpagetext' => 'Sārašos parvadintu poslapiu.',
'delete_and_move' => 'Ėštrintė ė parkeltė',

# Export
'export' => 'Ekspuortoutė poslapius',

# Namespace 8 related
'allmessages'               => 'Vėsė sėstemas tekstā ė pranešėmā',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' nepalaikuoms īr, nes nustatīms '''\$wgUseDatabaseMessages''' ėšjungts īr.",

# Special:Import
'import-revision-count' => '$1 {{PLURAL:$1|versėjė|versėjės|versėju}}',

# Import log
'importlogpage'                    => 'Impuorta istuorėjė',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|keitims|keitimā|keitimu}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|keitims|keitimā|keitimu}} ėš $2',

# Tooltip help for the actions
'tooltip-pt-userpage'       => 'Mona nauduotuojė poslapis',
'tooltip-pt-anonuserpage'   => 'Nauduotuojė poslapis Tamstas IP adresō',
'tooltip-pt-watchlist'      => 'Poslapiu sārašos, katrūs Tamsta pasėrėnkuot keravuotė.',
'tooltip-pt-mycontris'      => 'Mona darītu keitimu sārašos',
'tooltip-ca-edit'           => 'Tamsta galėt keistė ta poslapi. Nepamėrškėt paspaustė parvaizuos mīgtoka priš ėšsauguodamė.',
'tooltip-ca-addsection'     => 'Pridietė kuomentara i aptarėma.',
'tooltip-ca-delete'         => 'Trėntė ta poslapi',
'tooltip-ca-move'           => 'Parvadėntė poslapi',
'tooltip-ca-watch'          => 'Pridietė poslapi i keravuojamu sāraša',
'tooltip-ca-unwatch'        => 'Pašalėntė poslapi ėš keravuojamu sāraša',
'tooltip-search'            => 'Ėiškuotė šėtom pruojektė',
'tooltip-n-mainpage'        => 'Aplonkītė pėrma poslapi',
'tooltip-n-portal'          => 'Aple pruojekta, ka galėma vēktė, kamė ka rastė',
'tooltip-n-currentevents'   => 'Raskėt naujausė infuormacėjė',
'tooltip-n-recentchanges'   => 'Vielībūju pakeitėmu sārašos tamė projektė.',
'tooltip-n-randompage'      => 'Atidarītė bikuoki straipsni',
'tooltip-n-help'            => 'Vėita, katruo rasėt rūpėmus atsakīmus.',
'tooltip-t-whatlinkshere'   => 'Poslapiu sārašos, ruodantiu i čė',
'tooltip-t-contributions'   => 'Ruodītė šėta nauduotuojė keitėmu sāraša',
'tooltip-t-upload'          => 'Idietė abruozdielios a medėjės failos',
'tooltip-t-specialpages'    => 'Specēliūju poslapiu sārašos',
'tooltip-ca-nstab-user'     => 'Ruodītė nauduotuojė poslapi',
'tooltip-ca-nstab-project'  => 'Ruodītė pruojekta poslapi',
'tooltip-ca-nstab-image'    => 'Ruodītė abruozdielė poslapi',
'tooltip-ca-nstab-template' => 'Ruodītė šabluona',
'tooltip-ca-nstab-help'     => 'Ruodītė pagelbas poslapi',
'tooltip-ca-nstab-category' => 'Ruodītė kateguorėjės poslapi',
'tooltip-minoredit'         => 'Pažīmietė pakeitėma kāp maža',
'tooltip-watch'             => 'Pridietė šėta poslapi i keravuojamu sāraša',

# Attribution
'creditspage' => 'Poslapė kūriejē',

# Info page
'numwatchers' => 'Keravuojantiu skaitlius: $1',

# Bad image list
'bad_image_list' => 'Fuormats tuoks īr:

Tėk eilotės, prasėdedantės *, īr itraukiamas. Pėrmuojė nūruoda eilotie tor būtė nūruoda i bluoga abruozdieli.
Vėsas kėtas nūoruodas tuo patiuo eilotie īr laikomas ėšėmtim, tas rēšk ka poslapē, katrūs leidama iterptė abruozdieli.',

# Metadata
'metadata-fields' => 'EXIF metadoumenū laukā, nuruodītė tamė pranešėmė, bus itrauktė i abruozdielė poslapi, kumet metadoumenū lentelė bus suskleista. Palē nutīliejėma kėtė laukā bus pakavuotė.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# AJAX search
'searchcontaining' => "Ėiškuotė straipsniu, katrė prasided ''$1''.",
'searchnamed'      => "Ėiškuotė straipsniu, so pavadėnėmu ''$1''.",

# Table pager
'ascending_abbrev'  => 'dėdiejėma tvarka',
'descending_abbrev' => 'mažiejontė tvarka',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Pakeitėmā, naujesnė nego $1 sekondiu, šėtom sārašė gal būtė neruodomė.',

# Watchlist editor
'watchlistedit-numitems'       => 'Tamstas keravuojamu sārašė īr $1 poslapiu neskaitliuojant aptarėmu poslapiu.',
'watchlistedit-noitems'        => 'Tamstas keravuojamu sārašė nė juokiū poslapiu.',
'watchlistedit-normal-legend'  => 'Šalėntė poslapios ėš keravuojamu sāraša',
'watchlistedit-normal-explain' => 'Žemiau īr ruodomė poslapē Tamstas keravuojamu sārašė. Nuoriedamė pašalėntė poslapi, pri anuo oždiekėt varnale ė paspauskėt „Šalėntė poslapios“. Tamsta tēpuogi galėt [[Special:Watchlist/raw|redagoutė grīnaji keravuojamu sāraša]], a [[Special:Watchlist/clear|pašalėntė vėsos poslapios]].',
'watchlistedit-normal-done'    => '$1 poslapiu bova pašalėnta ėš Tamstas keravuojmu sāraša:',
'watchlistedit-raw-legend'     => 'Keistė grīnōjė keravuojamu sāraša',
'watchlistedit-raw-explain'    => 'Žemiau ruodomė poslapē Tamstas keravuojamu sārašė, ė gal būtė pridietė i a pašalėntė ėš sāraša; vėins poslapis eilotie. Bėngė paspauskėt „Atnaujėntė keravuojamu sāraša“. Tamsta tēpuogi galėt [[Special:Watchlist/edit|nauduotė standartėni radaktuoriu]].',
'watchlistedit-raw-done'       => 'Tamstas keravuojamu sārošos bova atnaujėnts.',
'watchlistedit-raw-added'      => '$1 poslapiu bova pridiet:',
'watchlistedit-raw-removed'    => '$1 poslapiu bova pašalėnt:',

);
