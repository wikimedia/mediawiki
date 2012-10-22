<?php
/** meänkieli (meänkieli)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kaganer
 * @author Mestos
 */

$fallback = 'fi';

$messages = array(
# User preference toggles
'tog-underline' => 'Linkitten alleviivaus',
'tog-justify' => 'Tassaa kappalheet',
'tog-hideminor' => 'Piilota pienet muutokset vereksen muutoksitten listasta',
'tog-hidepatrolled' => 'Piilota tarkastetut muutokset vereksen muutoksitten listasta',
'tog-newpageshidepatrolled' => 'Piilota tarkastetut sivut uusitten sivuitten listalta',
'tog-extendwatchlist' => 'Laajena valvontalistaa näyttämhään kaikki tehtyt muutokset eikä vain viimisimät.',
'tog-usenewrc' => 'Käytä avanseerattu verekset muutokset (vaatii JavaScript)',
'tog-numberheadings' => 'Nymreeraa rypriikit',
'tog-showtoolbar' => 'Näytä työneuvopalkki (JavaScript)',
'tog-editondblclick' => 'Mookkaa sivuja kaksoisknapituksella (JavaScript)',
'tog-editsection' => 'Aktiveeraa seksuuni mookkaus [mookkaus]-linkilä',
'tog-editsectiononrightclick' => 'Aktiveeraa seksuuni mookkaus oikeapuolen klikkauksella seksuuni tittelhiin (JavaScript)',
'tog-showtoc' => 'Näytä sisältölista (sivuile, joila on yli 3 rypriikkiä)',
'tog-rememberpassword' => 'Muista minun lokkauksen tässä weppilukijassa (eninthään $1 {{PLURAL:$1|päivä|päivää}})',
'tog-watchcreations' => 'Lissää sivut mitä luon valvontasivule',
'tog-watchdefault' => 'Lissää sivut mitä mie mookkaan valvontasivule',
'tog-watchmoves' => 'Lissää sivut mitä mie siirän minun valvontasivule',
'tog-watchdeletion' => 'Lissää sivut mitä otan poies valvontasivule',
'tog-minordefault' => 'Markeeraa auttomaattisesti kaikki muutokset pieneks',
'tog-previewontop' => 'Näytä esitarkastelu mookkauspaikan yläpuolela',
'tog-previewonfirst' => 'Näytä esitarkastelu kun mookkaus alethaan',
'tog-nocache' => 'Älä säästä sivuja weppilukijan välimuisthiin',
'tog-enotifwatchlistpages' => 'Lähätä e-postipreivi mulle kun sivu minun valvontalistala on muutettu',
'tog-enotifusertalkpages' => 'Lähätä sähköposti, kun käyttäjäsivun keskustelusivu muuttuu',
'tog-enotifminoredits' => 'Lähätä epostieto pienistäki muutoksista',
'tog-enotifrevealaddr' => 'Näytä minun eposti atressin muile lähetetyissä ilmoituksissa',
'tog-shownumberswatching' => 'Näytä kuinka moni käyttäjä valvoo sivua',
'tog-oldsig' => 'Nykynen allekirjotus',
'tog-fancysig' => 'Mookkaamaton allekirjotus ilman auttomaattista linkkiä',
'tog-externaleditor' => 'Käytä ekterniä tekstiedituuria stantartina. Vain kokenheile käyttäjile, vaatii taattorin asetuksitten muuttamista. Käytä eksterniä tekstiedituuria oletuksena. Vain kokeneille käyttäjille, vaatii selaimen asetusten muuttamista. ([//www.mediawiki.org/wiki/Manual:External_editors Ohje])',

# Dates
'sunday' => 'pyhä',
'monday' => 'maanantai',
'tuesday' => 'tiistai',
'wednesday' => 'keskiviikko',
'thursday' => 'tuorestai',
'friday' => 'perjantai',
'saturday' => 'lauantai',
'sun' => 'py',
'mon' => 'ma',
'tue' => 'ti',
'wed' => 'ke',
'thu' => 'tuo',
'fri' => 'pe',
'sat' => 'la',
'january' => 'tammikuu',
'february' => 'helmikuu',
'march' => 'maaliskuu',
'april' => 'huhtikuu',
'may_long' => 'toukokuu',
'june' => 'kesäkuu',
'july' => 'heinäkuu',
'august' => 'elokuu',
'september' => 'syyskuu',
'october' => 'lokakuu',
'november' => 'marraskuu',
'december' => 'joulukuu',
'january-gen' => 'tammikuun',
'february-gen' => 'helmikuun',
'march-gen' => 'maaliskuun',
'april-gen' => 'huhtikuun',
'may-gen' => 'toukokuun',
'june-gen' => 'kesäkuun',
'july-gen' => 'heinäkuun',
'august-gen' => 'elokuun',
'september-gen' => 'syyskuun',
'october-gen' => 'lokakuun',
'november-gen' => 'marraskuun',
'december-gen' => 'joulukuun',
'jan' => 'tammikuu',
'feb' => 'helmikuu',
'mar' => 'maaliskuu',
'apr' => 'huhtikuu',
'may' => 'toukokuu',
'jun' => 'kesäkuu',
'jul' => 'heinäkuu',
'aug' => 'elokuu',
'sep' => 'syyskuu',
'oct' => 'lokakuu',
'nov' => 'marraskuu',
'dec' => 'joulukuu',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Katekuurit|Katekuurit}}',
'category_header' => 'Sivut, jokka on katekuurissa "$1"',
'subcategories' => 'Alakatekuurit',
'category-media-header' => 'Katekuurin ”$1” sisältämät fiilit',
'category-empty' => "''Tässä katekuuriassa ei ole sivuja eikä fiiliä.''",
'hidden-categories' => '{{PLURAL:$1|Piilotettu katekuuri|Piilotetut katekuurit}}',
'category-subcat-count' => '{{PLURAL:$2|Tässä katekuurissa on vain seuraava alakatekuuri.|{{PLURAL:$1|Seuraava alakatekuuri kuuluu|Seuraavat $1 alakatekuuria kuuluvat}} tähhään katekuurihaan. Alakatekuuritten kokonaismäärä katekuurissa on $2.}}',
'category-article-count' => '{{PLURAL:$2|Tässä katekuurissa on vain seuraava sivu.|Seuraava {{PLURAL:$1|sivu on|$1 sivut on}} tässä katekuurissa, kahen joukosta $2 }}',
'category-file-count' => '{{PLURAL:$2|Tässä katekuurissa on vain seuraava sivu.|Seuraava {{PLURAL:$1|fiili|$1 fiilit}} (kaikkians $2) on tässä katekuurissa.}}',
'listingcontinuesabbrev' => 'jatkuu',
'noindex-category' => 'Ei-indekseerattuja sivuja',

'about' => 'Tietoja',
'newwindow' => '(aukasee uuessa klasissa)',
'cancel' => 'Lopeta',
'mytalk' => 'Minun keskustelu',
'navigation' => 'Navikeerinki',

# Cologne Blue skin
'qbedit' => 'Mookkaa',
'qbpageoptions' => 'Tämä sivu',
'qbpageinfo' => 'Sisältö',
'qbmyoptions' => 'Minun inställninkit',
'qbspecialpages' => 'Spesiaali sivut',
'faq' => 'Useasti kysytyt kysymykset',
'faqpage' => 'Project:Useasti kysytyt kysymykset',

# Vector skin
'vector-action-addsection' => 'Lissää aine',
'vector-action-delete' => 'Ota poies',
'vector-action-move' => 'Siirä',
'vector-action-protect' => 'Suojaa',
'vector-action-undelete' => 'Pane takashiin',
'vector-action-unprotect' => 'Muuta suojaa',
'vector-simplesearch-preference' => 'Ota käythöön paranetut hakuehotukset (vain Vector-ulkoasu)',
'vector-view-create' => 'Luo',
'vector-view-edit' => 'Mookkaa',
'vector-view-history' => 'Näytä histuuria',
'vector-view-view' => 'Lue',
'vector-view-viewsource' => 'Näytä lähekooti',
'actions' => 'Toiminat',
'namespaces' => 'Nimityhjyyet',
'variants' => 'Varianttia',

'errorpagetitle' => 'Virhe',
'returnto' => 'Takashiin sivule $1.',
'tagline' => 'Asiasta {{SITENAME}}',
'help' => 'Apua',
'search' => 'Haku',
'searchbutton' => 'Hae',
'searcharticle' => 'Mene',
'history' => 'Sivun histuuria',
'history_short' => 'Histuuria',
'printableversion' => 'Printtausmaholinen versuuni',
'permalink' => 'Ikunen linkki',
'edit' => 'Mookkaa',
'create' => 'Luo sivu',
'delete' => 'Ota poies',
'protect' => 'Suojaa',
'protect_change' => 'muuta',
'newpage' => 'Uusi sivu',
'talkpagelinktext' => 'Keskustelu',
'personaltools' => 'Henkilökohtaiset työneuvot',
'talk' => 'Keskustelu',
'views' => 'Näyttöjä',
'toolbox' => 'Työneuvot',
'otherlanguages' => 'Muila kielilä',
'redirectedfrom' => '(Ohjattu sivulta $1)',
'lastmodifiedat' => 'Sivua on viimeksi muutettu $1 kello $2.',
'jumpto' => 'Hyppää:',
'jumptonavigation' => 'Navikeerinki',
'jumptosearch' => 'Hae',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Asiasta {{GRAMMAR:elative|{{SITENAME}}}}',
'aboutpage' => 'Project: Tioista',
'copyrightpage' => '{{ns:project}}:Tekijänoikeuet',
'currentevents' => 'Vereksiä tapahtumia',
'currentevents-url' => 'Project: Vereksiä tapahtumia',
'disclaimers' => 'Vastuuvaphaus',
'disclaimerpage' => 'Project: Ylheinen varoitus',
'edithelp' => 'Mookkausapua',
'edithelppage' => 'Help: Kuinka sivuja mookathaan',
'helppage' => 'Help: Sisältö',
'mainpage' => 'Alkusivu',
'mainpage-description' => 'Alkusivu',
'portal' => 'Kaikitten purthaali',
'portal-url' => 'Project: Kaikitten purthaali',
'privacy' => 'Tietosuojakäytäntö',
'privacypage' => 'Project: Intekriteettisääntö',

'retrievedfrom' => 'Nouettu osoitheesta $1',
'youhavenewmessages' => 'Sulla on $1 ($2).',
'newmessageslink' => 'uusia meiliä',
'newmessagesdifflink' => 'viiminen muutos',
'editsection' => 'mookkaa',
'editold' => 'mookkaa',
'viewsourceold' => 'näytä lähekooti',
'editlink' => 'mookkaa',
'viewsourcelink' => 'näytä lähekooti',
'editsectionhint' => 'Mookkaa seksuunia $1',
'toc' => 'Sisältö',
'site-atom-feed' => '$1-Atom-syöttö',
'page-atom-feed' => '$1 (Atom-syöttö)',
'red-link-title' => '$1 (sivua ei ole)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Sivu',
'nstab-user' => 'Käyttäjäsivu',
'nstab-special' => 'Spesiaali sivut',
'nstab-project' => 'Prujektisivu',
'nstab-image' => 'Fiili',
'nstab-template' => 'Malli',
'nstab-category' => 'Katekuuri',

# General errors
'missing-article' => 'Sivun sisältöä ei löytyny taattapaasista: $1 $2.

Useimiten tämä johtuu vanhentuneesta vertailu- tai histuuriasivulinkistä poistethuun sivhuun.

Jos kysheessä ei ole poistettu sivu, olet piian löytäny virheen ohjelmassa.
Ilmota tämän sivun atressi wikin [[Special:ListUsers/sysop|atministratöörile]].',
'missingarticle-rev' => '(versuuni: $1)',
'badtitle' => 'Virheelinen titteli',
'badtitletext' => 'Pyytämästi sivurypriikki oli virheelinen, tyhjä eli titteli on väärin linkitetty muusta wikistä. Se saattaa sisältää yhen eli monta sympoolia, joita ei saa käyttää sivutittelissä.',
'viewsource' => 'Näytä lähekooti',

# Login and logout pages
'yourname' => 'Käyttäjänimi',
'yourpassword' => 'Salasana',
'yourpasswordagain' => 'Salasana uuesti',
'remembermypassword' => 'Muista minun lokkauksen tässä taattorissa (korkeinthaans $1 {{PLURAL:$1|päivä|päivää}})',
'login' => 'Lokkaa sisäle',
'nav-login-createaccount' => 'Lokkaa sisäle / luo konttu',
'loginprompt' => 'Lokkauksheen tähhään {{SITENAME}} tarvithaan ette olet aktiveeranu kuukit .',
'userlogin' => 'Lokkaa sisäle/ luo konttu',
'userlogout' => 'Lokkaa ulos',
'nologin' => "Eikos sulla ole käyttäjäkonttua, '''$1'''.",
'nologinlink' => 'Luo käyttäjäkonttu',
'createaccount' => 'Luo käyttäjäkonttu',
'gotaccount' => "Jos sulla on käyttäjäkonttu,  voit '''$1'''.",
'gotaccountlink' => 'Lokkaa sisäle',
'userlogin-resetlink' => 'Unhoutitko sinun salasanan?',
'mailmypassword' => 'Lähätä e-postissa uusi salasana',
'loginlanguagelabel' => 'Kieli: $1',

# Edit page toolbar
'bold_sample' => 'Lihava teksti',
'bold_tip' => 'Lihava teksti',
'italic_sample' => 'Kyrsiveerattu teksti',
'italic_tip' => 'Kyrsiveerattu',
'link_sample' => 'linkin nimi',
'link_tip' => 'Sisäinen linkki',
'extlink_sample' => 'http://www.example.com linkin rypriikki',
'extlink_tip' => 'Eksterni linkki (muista http:// eessä)',
'headline_sample' => 'Rypriikkiteksti',
'headline_tip' => 'Aste 2 rypriikki',
'nowiki_sample' => 'Lissää muotoilematon teksti tähhään',
'nowiki_tip' => 'Iknureeraa wiki formateerinkin',
'image_tip' => 'Piilotettu fiili',
'media_tip' => 'Linkki fiilhiin',
'sig_tip' => 'Allekirjotus aikaleimala',
'hr_tip' => 'Horisontaali linja (käytethään säästävästi)',

# Edit pages
'summary' => 'Yhteenveto',
'minoredit' => 'Tämä on pieni muutos',
'watchthis' => 'Valvo tätä sivua',
'savearticle' => 'Säästä sivu',
'preview' => 'Etukätheen katto',
'showpreview' => 'Näytä esikuvvaus',
'showdiff' => 'Näytä muutokset',
'anoneditwarning' => "'''Varotus:''' Et ole lokanu sisäle.
IP-atressi säästethään tämän sivun muutoshistuuriassa.",
'newarticle' => '(Uusi)',
'newarticletext' => 'Linkki vei sinun sivule, joka ei vielä ole.
Saatat luoa sivun kirjottamalla alla olehvaan kenthään (katto [[{{MediaWiki:Helppage}}|apusivu]] lisää tietoja).
Jos et halua luoa sivua, käytä browserin "takashiin" knappia.',
'noarticletext' => 'Tällä hetkellä tällä sivulla ei ole tekstiä.
Tällä hetkelä tällä sivula ei ole tekstiä.
Saatat [[Special:Search/{{PAGENAME}}|hakea sivun nimelä]] muilta sivuilta,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} hakea aiheesheen liittyviä lokkia]
eli [{{fullurl:{{FULLPAGENAME}}|action=edit}} mookata tätä sivua]</span>.',
'noarticletext-nopermission' => 'Tällä hetkelä tällä sivula ei ole tekstiä.
Saatat [[Special:Search/{{PAGENAME}}|hakea sivun nimelä]] muilta sivuilta,
eli <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} hakea relevantista lokista]
eli [{{fullurl:{{FULLPAGENAME}}|action=edit}} mookata tätä sivua]</span>.',
'previewnote' => "'''Tämä on vasta sivun etukattelu. Sivua ei ole vielä säästetty!'''",
'editing' => 'Mookathaan sivua $1',
'editingsection' => 'Mookathaan $1 (seksuuni)',
'templatesused' => 'Tällä sivula {{PLURAL:$1|käytetty malli|käytetyt mallit}}:',
'template-protected' => '(suojattu)',
'template-semiprotected' => '(osittain suojattu)',
'hiddencategories' => 'Tämä sivu kuuluu {{PLURAL:$1|seuraavhaan piilotethuun katekuurihaan|seuraavhiin piilotethuin katekuurhiin}}:',
'permissionserrorstext-withaction' => 'Sulla ei ole luppaa {{lcfirst:$2}} {{PLURAL:$1|seuraavasta syystä|seuraavista syistä}} johtuen:',
'recreate-moveddeleted-warn' => "''Varotus: Olet luomassa sivua, joka on vasta otettu poies.'''",
'moveddeleted-notice' => 'Tämä sivu on otettu poies. Alla on tämän sivun poistotieto ja lokkaushistuuria referensinä',

# Parser/template warnings
'post-expand-template-inclusion-warning' => 'Varotus: Tämä sivu sisältää liian suurta mallikootia.
Joitakin mallia ei tulta säästämhään.',
'post-expand-template-inclusion-category' => 'Sivut missä mallikootin sisältö on liian suuri',
'post-expand-template-argument-warning' => 'Varotus: Tällä sivula on ainaki yks malliparameetteri, jonka koko on liian suuri ekspansuunissa.
Nämät parameetterit on poistettu.',
'post-expand-template-argument-category' => 'Sivut missä on poistettuja malliparameetteria',

# History pages
'viewpagelogs' => 'Näytä tämän sivun lokit',
'currentrev-asof' => 'Nykynen versuuni $1',
'revisionasof' => 'Versuuni $1',
'revision-info' => 'Versuuni hetkelä $1 – tehny $2',
'previousrevision' => 'Vanheempi versuuni',
'nextrevision' => 'Uuempi versuuni',
'currentrevisionlink' => 'Nykynen versuuni',
'cur' => 'nyk.',
'last' => 'eel.',
'histlegend' => 'Eron valinta: markkeeraa klikkiruuissa ette pääset vertaamhaan versuunia, ja paina enter eli knaphiin kaikhiin alla. 
Merkinät: (nyk.) = eroavaisuuet nykyisheen versuunhiin, (eel.) = eroavaisuuet eelisheen versuunhiin, <span class="minor">p</span> = pieni muutos',
'history-fieldset-title' => 'Plaavaa muutoshistuuriaa',
'history-show-deleted' => 'Vain poistetut',
'histfirst' => 'Ensimäiset',
'histlast' => 'Viimisimät',

# Revision feed
'history-feed-item-nocomment' => '$1 ($2)',

# Revision deletion
'rev-delundel' => 'näytä/piilota',
'revdel-restore' => 'muuta näkyvyyttä',
'revdel-restore-deleted' => 'poistetut muutokset',
'revdel-restore-visible' => 'Näkyvät muutokset',

# Merge log
'revertmerge' => 'Pane takashiin yhistäminen',

# Diffs
'history-title' => 'Sivun $1 muutoshistuuria',
'lineno' => 'Rivi $1:',
'compareselectedversions' => 'Vertaile valittuja sivu versuunia',
'editundo' => 'kumota',
'diff-multi' => '(Näytetyitten versuunitten välissä on {{PLURAL:$1|yks mookkaus|$1 versuunit, jokka on {{PLURAL:$2|yhen käyttäjän tekemiä|$2 eri käyttäjän tekemiä}}}}.)',

# Search results
'searchresults' => 'Hakutulokset',
'searchresults-title' => 'Hakutulokset hakusanale ”$1”',
'prevn' => 'eelinen {{PLURAL:$1|$1}}',
'nextn' => '{{PLURAL:$1|seuraava|$1 seuraavaa}} →',
'prevn-title' => 'eelinen$1 {{PLURAL:$1|resyltaatit|resyltaatit}}',
'nextn-title' => 'eelinen$1 {{PLURAL:$1|resyltaatit|resyltaatit}}',
'shown-title' => 'Näytä $1 {{PLURAL:$1|resyltaatti|resyltaatti}} sivu sivulta',
'viewprevnext' => 'Näytä ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists' => "'''Sivu [[:$1]] löytyy tästä wikistä.'''",
'searchmenu-new' => "'''Luo sivu ''[[:$1]]'' tähhaän wikhiin.'''",
'searchprofile-articles' => 'Sisältösivut',
'searchprofile-project' => 'Apu ja prujektisivut',
'searchprofile-images' => 'Mylttimeetia',
'searchprofile-everything' => 'Kaikki',
'searchprofile-advanced' => 'Avanseerattu',
'searchprofile-articles-tooltip' => 'Hae nimityhjyyestä $1',
'searchprofile-project-tooltip' => 'Hae nimityhjyyestä $1',
'searchprofile-images-tooltip' => 'Hae fiiliä',
'searchprofile-everything-tooltip' => 'Hae kaikesta (keskustelusivut kans)',
'searchprofile-advanced-tooltip' => 'Hae tietyissä nimityhjyissä',
'search-result-size' => '$1 ({{PLURAL:$2|1 sana|$2 sannaa}})',
'search-result-category-size' => '{{PLURAL:$1|1 jäsen|$1 jäsentä}} ({{PLURAL:$2|1 alakatekuuria|$2 alakatekuuriaa}}, {{PLURAL:$3|1 fiili|$3 fiiliä}})',
'search-redirect' => '(ohjaus $1)',
'search-section' => '(seksuuni $1)',
'search-suggest' => 'Tarkoititko: $1',
'searchrelated' => 'relateerattu',
'searchall' => 'kaikki',
'showingresultsheader' => "{{PLURAL:$5|Resyltaatit'''$1'''–'''$3'''|Resyltaatit'''$1'''–'''$2''' kaiken joukosta '''$3''' }} haule '''$4'''",
'search-nonefound' => 'Ei yhtään resyltaattia sinun kysymyksheen',

# Preferences page
'mypreferences' => 'Omat inställninkit',
'youremail' => 'E-posti:',
'yourrealname' => 'Oikea nimi',
'prefs-help-email' => 'E-postin atressi on vapa, mutta tekkee maholiseks ette lähättää sulle salasanan meilissä, jos unhoutat sen.',
'prefs-help-email-others' => 'Saatat kans antaa muitten käyttäjitten ottaa ottaa yhteyttä sinhuun sähköpostila. Sin atressi ei näy toisen käyttäjän ottaessa sinhuun yhteyttä.',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'mookkaa tätä sivua',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|muutos|muutosta}}',
'recentchanges' => 'Verekset muutokset',
'recentchanges-legend' => 'Vereksitten muutoksitten inställninkit',
'recentchanges-summary' => 'Seuraa viimiset muutokset wikin tällä sivula',
'recentchanges-feed-description' => 'Tällä sivula saatat seurata wikin vereksiä muutoksia',
'recentchanges-label-newpage' => 'Tämä mookkaus loi uuen sivun',
'recentchanges-label-minor' => 'Tämä on pieni muutos',
'recentchanges-label-bot' => 'Tämän muutoksen teki botti',
'recentchanges-label-unpatrolled' => 'Tätä muutosta ei ole vielä tarkistettu',
'rcnote' => 'Alla on {{PLURAL:$1|yks muutos|$1 vereksimät muutokset}} {{PLURAL:$2|yhen päivän|$2 viimi päivän}} ajalta $4 kello $5 asti.',
'rcnotefrom' => "Alla on muutokset '''$2'''lähtien. (korkeinthaans '''$1''' näytethään).",
'rclistfrom' => 'Näytä uuet muutokset jälkhiin $1',
'rcshowhideminor' => '$1 pienet muutokset',
'rcshowhidebots' => '$1 ropootit',
'rcshowhideliu' => '
$1 sisäle lokaattuja käyttäjiä',
'rcshowhideanons' => '$1 anonyymit käyttäjät',
'rcshowhidepatr' => '$1 tarkistetut muutokset',
'rcshowhidemine' => '$1 omat muutokset',
'rclinks' => 'Näytä $1 verestä muutosta viimisitten $2 päivitten aikana.<br />$3',
'diff' => 'ero',
'hist' => 'histuuria',
'hide' => 'Piilota',
'show' => 'Näytä',
'minoreditletter' => 'p',
'newpageletter' => 'U',
'boteditletter' => 'b',
'rc-enhanced-expand' => 'Näytä detaljit (JavaScript)',
'rc-enhanced-hide' => 'Piilota detaljit',

# Recent changes linked
'recentchangeslinked' => 'Relateerattuja muutoksia',
'recentchangeslinked-toolbox' => 'Relateerattuja muutoksia',
'recentchangeslinked-title' => 'Muutokset relatterattuja "$1"',
'recentchangeslinked-noresult' => 'Ei muutoksia linkathuin sivhuin annetulla aikakauela',
'recentchangeslinked-summary' => 'Tämä on lista vereksistä muutoksista sivhuin, joihin on linkattu erikoiselta sivulta. Sivut sinun  [[Special:Watchlist|valvontalistala]] on markeerattu lihavala tyylilä',
'recentchangeslinked-page' => 'Sivun nimi',
'recentchangeslinked-to' => 'Näytä muutokset sivhuin, jolla sen eestä on linkki annethuun sivhuun',

# Upload
'upload' => 'Lattaa ylös fiili',
'uploadlogpage' => 'Ylöslattauksen loki',
'filedesc' => 'Yhteenveto',
'uploadedimage' => 'lattasi ylös [[$1]]',

'license' => 'Lisensi',
'license-header' => 'Lisensi',

# File description page
'file-anchor-link' => 'Fiili',
'filehist' => 'Fiilin histuuria',
'filehist-help' => 'Klikkaa taattymia/aikaa niin näet fiilin kuinka se oli siihen aikhaan',
'filehist-revert' => 'pane takashiin',
'filehist-current' => 'nykynen',
'filehist-datetime' => 'Päivä/Aika',
'filehist-thumb' => 'Peukalokuva',
'filehist-thumbtext' => 'Peukalokuva säästetystä versuunista  $1',
'filehist-user' => 'Käyttäjä',
'filehist-dimensions' => 'Timensuunit',
'filehist-comment' => 'Komentti',
'imagelinks' => 'Fiilin käyttö',
'linkstoimage' => 'Seuraava {{PLURAL:$1|sivu |$1 sivut }} länkkaavat tähhään fiilhiin:',
'nolinkstoimage' => 'Ei ole yhtään sivua joka linkkaa tähhään fiilhiin.',
'sharedupload-desc-here' => 'Tämä fiili on jaettu kohtheesta $1 ja muut prujektit saattavat käyttää sitä.
Tiot [$2 fiilin kuvvaussivulta] näkyvät tässä alla.',

# Random page
'randompage' => 'Satunhainen sivu',

# Statistics
'statistics' => 'Statistiikkaa',

'disambiguationspage' => 'Template:Haarainsivu',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|tavu|tavua}}',
'nmembers' => '$1 {{PLURAL:$1|jäsen|jäsentä}}',
'prefixindex' => 'Kaikki sivut prefiksilä',
'usercreated' => 'Luottu $1 $2',
'newpages' => 'Uuet sivut',
'move' => 'Siirä',
'pager-newer-n' => '← {{PLURAL:$1|1 uuempi|$1 uuempaa}}',
'pager-older-n' => '{{PLURAL:$1|1 vanheempi|$1 vanheempaa}} →',

# Book sources
'booksources' => 'Kirjalähteet',
'booksources-search-legend' => 'Hae kirjalähtheitä',
'booksources-go' => 'Mene',

# Special:Log
'log' => 'Lokit',

# Special:AllPages
'allpages' => 'Kaikki sivut',
'alphaindexline' => '$1…$2',
'allarticles' => 'Kaikki sivut',
'allpagessubmit' => 'Mene',

# Special:Categories
'categories' => 'Katekuurit',

# Special:LinkSearch
'linksearch-line' => '$1 on linkattu sivulta $2',

# Special:Log/newusers
'newuserlogpage' => 'Uuitten käyttäjitten loki',

# Special:ListGroupRights
'listgrouprights-members' => '(jäsenlista)',

# E-mail user
'emailuser' => 'Lähätä e-posti tälle käyttäjälle',

# Watchlist
'watchlist' => 'Valvontalista',
'mywatchlist' => 'Minun valvontasivu',
'watchlistfor2' => 'Käyttäjälle $1 $2',
'watch' => 'Valvo',
'unwatch' => 'Lopeta valvonta',
'watchlist-details' => 'Valvontalistala on {{PLURAL:$1|$1 sivu|$1 sivua}} (keskustelusivuja mukhaan laskematta)',
'wlshowlast' => 'Näytä viimiset $1 tiimat eli $2 päivät$3',
'watchlist-options' => 'Valvontalistan altternatiivit',

# Delete
'actioncomplete' => 'Tehty',
'actionfailed' => 'Tehty epäonnistui',
'dellogpage' => 'Poistoloki',

# Rollback
'rollbacklink' => 'rullaa takashiin',

# Protect
'protectlogpage' => 'Suojausloki',
'protectedarticle' => 'suojasi sivun [[$1]]',

# Undelete
'undeletelink' => 'näytä/ota takashiin',
'undeleteviewlink' => 'näytä',

# Namespace form on various pages
'namespace' => 'Nimityhjyys:',
'invert' => 'Jätä pois valinta',
'blanknamespace' => '(Päätyhjyys)',

# Contributions
'contributions' => 'Omat mookkaukset',
'contributions-title' => 'Käyttäjän $1 mookkaukset',
'mycontris' => 'Omat mookkaukset',
'contribsub2' => 'Käyttäjän $1 ($2) mookkaukset',
'uctop' => '(viiminen)',
'month' => 'Kuukauesta (ja aiemin)',
'year' => 'Vuoesta (ja aiemin)',

'sp-contributions-newbies' => 'Näytä uusitten tulokhaitten muutokset',
'sp-contributions-blocklog' => 'blokeerinkiloki',
'sp-contributions-uploads' => 'Ylöslattauksia',
'sp-contributions-logs' => 'lokit',
'sp-contributions-talk' => 'keskustelu',
'sp-contributions-search' => 'Hae käyttäjitten bitraakia',
'sp-contributions-username' => 'IP-atressi eli käyttäjänimi',
'sp-contributions-toponly' => 'Näytä vain mookkaukset, jokka on vasta tehtyjä versuunia',
'sp-contributions-submit' => 'Hae',

# What links here
'whatlinkshere' => 'Mitä linkkaa tänne',
'whatlinkshere-title' => 'Sivut jokka länkathaan "$1"',
'whatlinkshere-page' => 'Sivu',
'linkshere' => 'Seuraavila sivuila on linkki sivule <strong>[[:$1]]</strong>:',
'nolinkshere' => "Sivule \"'[[:\$1]]''' ei ole linkkiä.",
'isredirect' => 'ohjaussivu',
'istemplate' => 'sisäletty mallina',
'isimage' => 'linkki fiilhiin',
'whatlinkshere-prev' => '← {{PLURAL:$1|eelinen sivu|$1 eelistä sivua}}',
'whatlinkshere-next' => '{{PLURAL:$1|seuraava sivu|$1 seuraava sivu}} →',
'whatlinkshere-links' => 'linkit',
'whatlinkshere-hideredirs' => '$1 ohjaukset',
'whatlinkshere-hidetrans' => '$1 mallin inklyteerinkiä',
'whatlinkshere-hidelinks' => '$1 linkit',
'whatlinkshere-hideimages' => '$1 fiililinkit',
'whatlinkshere-filters' => 'Filtterit',

# Block/unblock
'ipboptions' => '2 tiimaa:2 hours,1 päivä:1 day,3 päivää:3 days,1 viikko:1 week,2 viikkoa:2 weeks,1 kuukausi:1 month,3 kuukautta:3 months,6 kuukautta:6 months,1 vuosi:1 year,ikunen:infinite',
'ipblocklist' => 'Plokeeratut käyttäjät',
'blocklink' => 'blokeeraa',
'unblocklink' => 'ota poies blokeerinki',
'change-blocklink' => 'muuta blokeerinki',
'contribslink' => 'mookkaukset',
'blocklogpage' => 'Blokeerinki lokkaus',
'blocklogentry' => 'blokeerattu [[$1]] blokeerausaika $2 $3',
'block-log-flags-nocreate' => 'toppaa kontturejistreerinkiä',

# Move page
'movelogpage' => 'Siirtoloki',
'revertmove' => 'siirä takashiin',

# Export
'export' => 'Eksporteeraa sivuja',

# Namespace 8 related
'allmessagesname' => 'Nimi',
'allmessagesdefault' => 'Stantartiteksti',

# Thumbnails
'thumbnail-more' => 'Isona',
'thumbnail_error' => 'Pienoiskuvan luominen epäonnistui: $1',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Oma käyttäjäsivu',
'tooltip-pt-mytalk' => 'Oma keskustelusivu',
'tooltip-pt-preferences' => 'Omat inställninkit',
'tooltip-pt-watchlist' => 'Lista sivuista, joitten mookkauksia valvot',
'tooltip-pt-mycontris' => 'Lista omista mookkauksista',
'tooltip-pt-login' => 'Lokkaa mielelhään sisäle, mutta ei ole pakko',
'tooltip-pt-logout' => 'Lokkaa ulos',
'tooltip-ca-talk' => 'Keskustelu sisälöstä',
'tooltip-ca-edit' => 'Voit mookata tätä sivua, mutta käytä esitarkastusknappia ennen kun säästät',
'tooltip-ca-addsection' => 'Alota keskustelu uuesta asiasta',
'tooltip-ca-viewsource' => 'Tämä sivu on suojattu. Saatat nähhä lähekootin',
'tooltip-ca-history' => 'Sivun aiemat versuunit',
'tooltip-ca-protect' => 'Suojaa tämä sivu',
'tooltip-ca-delete' => 'Ota poies tämä sivu',
'tooltip-ca-move' => 'Siirä tämä sivu',
'tooltip-ca-watch' => 'Lissää tämä sivu sinun valvontalistale',
'tooltip-ca-unwatch' => 'Ota poies tämä sivu sinun valvontalistasta',
'tooltip-search' => 'Hae {{GRAMMAR:elative|{{SITENAME}}}}',
'tooltip-search-go' => 'Siiry sivule joka on justhiins tällä nimelä',
'tooltip-search-fulltext' => 'Hae sivuja tälle tekstile',
'tooltip-p-logo' => 'Alkusivu',
'tooltip-n-mainpage' => 'Mene alkusivule',
'tooltip-n-mainpage-description' => 'Mene alkusivule',
'tooltip-n-portal' => 'Keskustelua projektista',
'tooltip-n-currentevents' => 'Löyä taustatietoja vereksistä tapahtumisista',
'tooltip-n-recentchanges' => 'Lista vereksistä muutoksista',
'tooltip-n-randompage' => 'Aukase satunhaisen sivun',
'tooltip-n-help' => 'Apua ja informasuunia',
'tooltip-t-whatlinkshere' => 'Lista wikisivuista jokka on länkattu tänne',
'tooltip-t-recentchangeslinked' => 'Verekset mookkaukset sivuissa, jokka on länkattu tästä sivusta',
'tooltip-feed-atom' => 'Atom-syöte tälle sivule',
'tooltip-t-contributions' => 'Näytä lista tämän käyttäjän mookkauksista',
'tooltip-t-emailuser' => 'Lähätä sähköposti tälle käyttäjälle',
'tooltip-t-upload' => 'Lattaa ylös fiiliä',
'tooltip-t-specialpages' => 'Lista kaikista spesiaalisivuista',
'tooltip-t-print' => 'Printtausmaholinen versuuni',
'tooltip-t-permalink' => 'Ikunen linkki tämän sivun  versuunhiin',
'tooltip-ca-nstab-main' => 'Näytä sisältösivu',
'tooltip-ca-nstab-user' => 'Näytä käyttäjäsivu',
'tooltip-ca-nstab-special' => 'Tämä on spesiaalisivu; sie et saata mookata itteä sivua',
'tooltip-ca-nstab-project' => 'Näytä prujektisivu',
'tooltip-ca-nstab-image' => 'Näytä fiilisivu',
'tooltip-ca-nstab-template' => 'Näytä mallia',
'tooltip-ca-nstab-category' => 'Näytä katekuurisivu',
'tooltip-minoredit' => 'Merkitte tämä pieneksi muutokseksi',
'tooltip-save' => 'Säästä mookkaukset',
'tooltip-preview' => 'Esikuvvaa sinun muutokset, käytä tätä ennen kun säästät',
'tooltip-diff' => 'Näytä sinun muutokset tekstistä',
'tooltip-compareselectedversions' => 'Vertaile valitut sivuversuunit',
'tooltip-watch' => 'Lissää tämä sivu sinun valvontalistale',
'tooltip-rollback' => '"Rullaa takashiin" kaataa yhelä klikilä viimisen mookkaajan muutokset',
'tooltip-undo' => '"Kumota" palauttaa tämän muutoksen ja aukasee artikkelin mookkausruutun esitarkastuksen kansa. Antaa maholisuuen kirjottaa mutiveerinkin mookkaajan yhteenvethoon',
'tooltip-summary' => 'Kirjota lyhy yhteenveto',

# Browsing diffs
'previousdiff' => 'Vanheempi muutos',
'nextdiff' => 'Uuempi muutos',

# Media information
'file-info-size' => '$1 × $2 pikseliä, fiilin koko: $3, MIME-tyyppi: $4',
'file-nohires' => 'Tarkempaa kuvvaa ei ole saatavissa.',
'svg-long-desc' => 'SVG-fiili; peruskoko $1 × $2 pikseliä, fiilikoko: $3',
'show-big-image' => 'Korkearesulusuuni versuuni',

# Bad image list
'bad_image_list' => 'Listan muoto on seuraava:

Vain *-merkilä alkavat rivit otethaan huomihoon.
Rivin ensimäinen linkki häätyy mennä kehnoon fiilhiin.
Kaikki muut linkit samala rivilä.käsitelthään poikkeuksena, eli toisin sanoen sivuja missä fiilin saapi käyttää.',

# Metadata
'metadata' => 'Meettataatta',
'metadata-help' => 'Tämä fiili sisältää lisätietoja esimerkiks kuvanlukijan, eli kuvakäsittelyprukrammin lisätietoja. Kaikki tiot ei en´nää välttämättä vastaa toelisuutheen, jos kuvvaa on mookattu sen alkuperäisen luomisen jälkhiin.',
'metadata-fields' => 'Seuraavaa meettataatta kentät listattu tässä informasuunissa, sisälethään näkyvänä kuvasivussa, kun meettataatta taulukko kolapsaa. Muut piilotethaan stantartina.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# External editor support
'edit-externally' => 'Mookkaa tätä fiiliä käyttämällä eksterniä aplikasuunia',
'edit-externally-help' => '(Katto [//www.mediawiki.org/wiki/Manual:External_editors ohjeet], jos haluat lissää tietoja.)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'kaikki',
'namespacesall' => 'kaikki',
'monthsall' => 'kaikki',

# Watchlist editing tools
'watchlisttools-view' => 'Näytä muutokset',
'watchlisttools-edit' => 'Näytä ja mookkaa valvontalistaa',
'watchlisttools-raw' => 'Mookkaa valvontalistaa raakamuoossa',

# Core parser functions
'duplicate-defaultsort' => 'Varotus: Stantartisortteerausavvain ”$2” korvaa aieman stantartisortteerausavvaimen”$1”.',

# Special:SpecialPages
'specialpages' => 'Spesiaali sivut',

# External image whitelist
'external_image_whitelist' => '#Älä muuta tätä riviä ollenkhaan.<pre>
#Kirjota rekyljääri frakmentitten meininkit (vain osa, joka mennee //-merkkitten välhiin) tähhään alle
#Niitä verrathaan ulkoisitten (suoralinkitetyitten) kuvitten URLhin
#Net jokka sopivat, näytethään kuvina, muuten kuvhiin näytethään vain linkit
#Rivit, jokka alkavat #-merkilä on komentaaria
#Tämä on riippumaton puukstavitten kokosta',

# Special:Tags
'tag-filter' => '[[Special:Tags|Merkki]] filtteri:',

);
