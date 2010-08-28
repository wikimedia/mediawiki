<?php
/** Romagnol (Rumagnôl)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Sentruper
 */

$fallback = 'it';

$messages = array(
# User preference toggles
'tog-underline'               => 'Link cun la sotliniadura',
'tog-highlightbroken'         => 'Fa avdé i link sbajé <a href="" class="new">scrètt acsè</a> (alternativa: scrètt acsè<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Paràgraf: giustifiché',
'tog-hideminor'               => 'Nascond al mudefghi znini int la pàgina "Ultum mudèfghi"',
'tog-hidepatrolled'           => "Nascònd j cambiamént verifichèdi int'aglj ultum mudèfigh",
'tog-newpageshidepatrolled'   => 'Nascònd al pàgin verifichèdi da la lèsta dal pàgin növi',
'tog-extendwatchlist'         => "Slèrga la funziòn \"tènn sot occ\" d'mod ch'us pòsa avdé toti al mudefchi, no sol l'ultma.",
'tog-usenewrc'                => "Drova agl'j ultmi mudèfchi avanzëdi (u j vô JavaScript)",
'tog-numberheadings'          => "Titul d'un paràgraf ch'l'à un nòmar daparlò",
'tog-showtoolbar'             => "Fam avdé la bara d'j strumént (u j vó JavaScript)",
'tog-editondblclick'          => 'Mudèfiga al pàgin scjazènd do volt (u j vò JavaScript)',
'tog-editsection'             => "S't'vù l'abilitaziòn ad'cambié al seziòn cum e' link [mudèfica]",
'tog-editsectiononrightclick' => "S't'vù l'abilitaziòn ad cambié al seziòn <br />scjazénd e tast a dèstra sora e titul (u j vò JavaScript)",
'tog-showtoc'                 => "Fam avdé l'indiz (sol par al pàgin cun piò d'3 seziòn)",
'tog-rememberpassword'        => "Arcurdam la parola d'ordin, par piasé (fèn a un masum $1 {{PLURAL:$1|dè|dè}})",
'tog-watchcreations'          => "Mett insèn al pàgin ch't'e' fat adès, intla lèsta da tní sot'occ",
'tog-watchdefault'            => "Mett insèn al pàgin ch'a j ò lavurè sora int la lèsta dal pàgin da tnì sot'òcc",
'tog-watchmoves'              => "Mett insèn al pàgin ch'a j ò spustè int la lèsta dal pàgin da tnì sot'òcc",
'tog-watchdeletion'           => "Mett insèn al pàgin ch'a j ò scanzlè int la lèsta dal pàgin da tnì d'occ",
'tog-previewontop'            => "Fam avdé l'anteprèma sora casèla d'mudèfica invezi che dciotta",
'tog-previewonfirst'          => "Fa' avdé l'anteprèma dop c'u's fa la prèma mudèfica",
'tog-nocache'                 => "↓ T'an stêga a tní in tla memoria al pàgin",
'tog-enotifwatchlistpages'    => "Fam' save' par e-mail quènd una pàgina dal mij l'è steda modifichèda",
'tog-enotifusertalkpages'     => "Fam' save' par e-mail quènd la mi pàgina dal discusiòn l'è steda modifichèda",
'tog-enotifminoredits'        => "Fam' save' par e-mail tòt al mudefchi, neca al znini",
'tog-enotifrevealaddr'        => "Lasa avdè e' mi indirèzi d'posta eletrònica int'j mesàg d'nutèfica",
'tog-shownumberswatching'     => "Fam' avdè e' nòmar d'j utent ch'j tèn sta pàgina sot occ",
'tog-oldsig'                  => 'Anteprèma dla fírma bona',
'tog-fancysig'                => "Tràta la firma cumpagna e' test int'la wiki (senza nissön ligam automatich)",
'tog-externaleditor'          => "Drova sempar un prugràma d'scritura esteran (editor testuale)",
'tog-externaldiff'            => 'Drova sempar un prugràma comparator esteran (sol par j utent espert)',
'tog-showjumplinks'           => "Fa' funzionè j leghèm d'acesibilitè tipo \"Va' a\"",
'tog-uselivepreview'          => 'Drova la funziòn "Anteprèma dal viv" (u j vo Javascript; sperimentêl)',
'tog-forceeditsummary'        => "Dam la vos quènd l'ugèt dla mudèfica l'è vut",
'tog-watchlisthideown'        => "Nascond al mi mudèfic dala lèsta dal pàgin da tnì d'occ",
'tog-watchlisthidebots'       => "↓ Nascond al mi mudèfic dala lèsta dal pàgin da tnì d'occ",
'tog-watchlisthideminor'      => "↓ Nascond al mi mudèfic dala lèsta dal pàgin da tnì d'occ",
'tog-watchlisthideliu'        => "↓ In tla lèsta da tnì d'occ, nascond al mudèfic d'j utent registrè",
'tog-watchlisthideanons'      => " ↓ In tla lèsta da tnì d'occ, nascond al mudèfic d'j utent senza nom",
'tog-watchlisthidepatrolled'  => "↓ Nascond tòt al mudèfic za cuntrulèdi in tla lèsta dal pàgin da tnì d'occ",
'tog-ccmeonemails'            => "↓ Mènd'm una còpia d'j mesèg ch'a spedèss a j étar druvador",
'tog-diffonly'                => "↓ T'an fëga avdé e' contnù dla pàgina dop e' cunfront tra l'versiòn",
'tog-showhiddencats'          => "↓ Fa'm avdé al categurèj nascosti",

# Dates
'january'       => 'Znèr',
'february'      => 'Febrér',
'march'         => 'Mêrz',
'april'         => 'Abril',
'may_long'      => 'Maz',
'june'          => 'Zógn',
'july'          => 'Lój',
'august'        => 'Agòst',
'september'     => 'Setémbar',
'october'       => 'Utóbar',
'november'      => 'Nuvèmbar',
'december'      => 'Dizèmbar',
'january-gen'   => 'Znèr',
'february-gen'  => 'Fevrer',
'march-gen'     => 'Mèrz',
'april-gen'     => 'Abril',
'may-gen'       => 'Maz',
'june-gen'      => 'Zògn',
'july-gen'      => 'Lòj',
'august-gen'    => 'Agòst',
'september-gen' => 'Setèmbar',
'october-gen'   => 'Utobar',
'november-gen'  => 'Nuvèmbar',
'december-gen'  => 'Dizèmbar',
'jan'           => 'znèr',
'feb'           => 'feb',
'mar'           => 'mêrz',
'apr'           => 'abr',
'may'           => 'maz',
'jun'           => 'zógn',
'jul'           => 'lój',
'aug'           => 'ago',
'sep'           => 'set',
'oct'           => 'utob',
'nov'           => 'nuv',
'dec'           => 'diz',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Categurèja|Categurei}}',
'category_header'        => 'Articul int la categurèja "$1"',
'subcategories'          => 'Sòtacategurèja',
'hidden-categories'      => '{{PLURAL:$1|Categurèja ardupèda|Categurèi ardupèdi}}',
'category-subcat-count'  => "{{PLURAL:$2|Sta categurèja l'à sol òna sota-categurèja|Sta categurèja l'à  {{PLURAL:$1|sota-categurèja|$1 sot-categuréj}}, sora $2 ch'l'è é tutel.}}",
'category-article-count' => "{{PLURAL:$2|Sta categuréja l'à sol sta pàgina què|In sta categuréja u j sta {{PLURAL:$1|sta pàgina|$1 pàgin}} sora $2, ch'l'è e' tutel.}}",
'listingcontinuesabbrev' => 'inênz',

'newwindow'  => "(la s'avèra int'una fnèstra nova)",
'cancel'     => 'Làsa perdar',
'mytalk'     => 'Al mi cunversaziòn',
'navigation' => 'Navgaziòn',

# Cologne Blue skin
'qbfind' => 'Tróva',

'errorpagetitle'   => "Oci! T'ê fat una capèla",
'returnto'         => 'Torna indrì a $1.',
'tagline'          => 'Da {{SITENAME}}',
'help'             => "Êt absogn d'una man?",
'search'           => 'Zerca',
'searchbutton'     => 'Zerca',
'searcharticle'    => 'Và pù',
'history'          => "Stória d'la pàgina",
'history_short'    => 'Stória',
'printableversion' => 'Versiòn bona da stampè',
'permalink'        => 'Culegament fèss',
'edit'             => 'Mudèfica',
'create'           => 'Fa nov/a',
'editthispage'     => 'Mudèfica sta pàgina',
'delete'           => 'Scanzèla',
'protect'          => 'Metti-j una pruteziòn',
'protect_change'   => 'chèmbia',
'newpage'          => 'Pàgina nova',
'talkpage'         => 'Cunversaziòn',
'talkpagelinktext' => 'Cunversaziòn',
'personaltools'    => 'Strumént persunèl',
'talk'             => 'Cunversaziòn',
'views'            => 'Chi èl pasé da que',
'toolbox'          => 'Strumént',
'otherlanguages'   => 'Ètri lènguv',
'redirectedfrom'   => '(Ri-direziòn da <b>$1</b>)',
'redirectpagesub'  => "Pàgina d're-indirezzament",
'lastmodifiedat'   => "L'ultum c'l'à lavurè atoran a 'sta pàgina da $2, a $1.",
'jumpto'           => 'Sbèlza a què:',
'jumptonavigation' => 'navgaziòn',
'jumptosearch'     => 'zerca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => 'A prupòsit de {{SITENAME}}',
'aboutpage'      => 'Project: A pruposit',
'copyright'      => "Oci! T'an fèga e' patàca. Sora 'ste scrètt u j è e' brevet $1.",
'copyrightpage'  => "{{ns:project}}:Brevèt d'l'autor",
'disclaimers'    => 'Infurmaziòn legêli',
'disclaimerpage' => 'Project: Avìs generèl',
'edithelp'       => 'Livar dal spiegaziòn',
'edithelppage'   => 'Help: Scrivar un artècul',
'helppage'       => 'Help:Tòt j argumént',
'mainpage'       => 'Prèma Pagina',
'privacy'        => 'Léz sora agli infurmaziòn persunèli',
'privacypage'    => 'Project: Léz sora agli infurmaziòn persunèli',

'badaccess' => 'Parméss non sufizént',

'retrievedfrom'       => 'Tiré fora da "$1"',
'youhavenewmessages'  => "A j ò fët ch't'epa $1 ($2).",
'newmessageslink'     => 'mesàz nuv',
'newmessagesdifflink' => "U j è una quèlca diferenza cun l'ultma versiòn",
'editsection'         => 'Mudèfica',
'editold'             => 'mudèfica',
'editlink'            => 'mudèfiga',
'viewsourcelink'      => "guèrda e' codiz surgént",
'editsectionhint'     => 'Mudèfica la seziòn: $1',
'toc'                 => 'Indice',
'showtoc'             => "fam'avdé",
'hidetoc'             => 'ardòpa',
'site-rss-feed'       => 'Emissiòn RSS $1',
'site-atom-feed'      => 'Emissiòn Atom $1',
'page-rss-feed'       => 'Canël RSS par "$1"',
'page-atom-feed'      => 'Canël Atom par "$1"',
'red-link-title'      => "Ciò! $1 (sta pàgina la n'esèst incora)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Artécul',
'nstab-user'     => 'Pàgina persunèla',
'nstab-special'  => 'Pàgina particulèra',
'nstab-project'  => "Pàgina dagl'infurmaziòn",
'nstab-image'    => 'File',
'nstab-template' => 'Mudel',
'nstab-category' => 'Categurèja',

# General errors
'missing-article'    => "E' database u n'à brisa truvé e'scret d'una pàgina ch'la avrebb duvu truvè sot e' nom di \"\$1\" \$2.

Quest é suzed d'solit in te méntr d'un cuntrol d'do version vèci intla storia d'una pàgina. E' pò esar suzest parchè l'è stè cjamé un culegamént a una pàgina scanzleda o parchè l'è stè cjamé un cunfront d'dò versiòn ch'j n'esést piò.

S' u n'è brisa acsè, t'é scvert par chès un bug int'é software.
Par piasé, fa raport a un [[Special:ListUsers/sysop|aministrador]] cun la nota de l'indirezzi dla pàgina.",
'missingarticle-rev' => '(revision n°: $1)',
'badtitletext'       => "La pàgina ch' t'ê cmandè, l'è vuda, sbaglieda o ta l'ê scrètta cun dal lettar particulèri. Oppure, ê pò dès ch'u j sèja un eror inter-lèngua o inter-wiki. Guèrda ben cus t'e scrètt: êt druvè dal lettar cu n' s pò druvè in t'j nom dal pàgin?",
'viewsource'         => "Guèrda e' codiz surgént",

# Login and logout pages
'yourname'                => 'Soranòm:',
'yourpassword'            => 'Paróla segreta:',
'remembermypassword'      => "Regèstra la mi parola d'ordin sora ste computer (for a maximum of $1 {{PLURAL:$1|day|days}})",
'login'                   => 'Va dentar',
'nav-login-createaccount' => 'Va dentar / Èla la prèma volta?',
'userlogin'               => 'Vèn dentar/A sit nov?',
'logout'                  => 'Va fora',
'userlogout'              => 'Và fora',
'nologinlink'             => 'Iscrivat adès',
'mailmypassword'          => "Mènda una nova parola d'ordin cun l'e-mail",

# Edit page toolbar
'bold_sample'     => "Pàroli in '''gros'''",
'bold_tip'        => 'Gros',
'italic_sample'   => 'Pàroli in cursiv',
'italic_tip'      => 'Pàroli in cursìv',
'link_sample'     => "Nòm d'e' culegamént",
'link_tip'        => 'Culegamént intéran',
'extlink_sample'  => "http://www.esempi.com Nòm d'e' culegamént",
'extlink_tip'     => 'Culegamént esteran (arcordat d\'scrivar dadnénz "http://")',
'headline_sample' => 'Intestaziòn',
'headline_tip'    => "Intestaziòn d'e' sgond livèl",
'math_sample'     => 'Mèt aquè dentar una formula',
'math_tip'        => 'Formula metemètica (LaTeX)',
'nowiki_sample'   => 'Mèt dentar i tu scrètt sìnza furmataziòn',
'nowiki_tip'      => 'Làsa perdar la furmataziòn wiki',
'image_tip'       => "Figura ch'la sta insèn a e' scrètt",
'media_tip'       => 'Culegamént a un file multimediél',
'sig_tip'         => 'Mèt aquè la firma, cun dèda e ora',
'hr_tip'          => "Riga urizuntèla (t'an fèga e' patàca: t'an esègera)",

# Edit pages
'summary'                          => "Mutiv d'e' cambiamént:",
'subject'                          => 'Argumént (intestaziòn):',
'minoredit'                        => "Quèsta l'è una mudèfiga znina (z)",
'watchthis'                        => "Tèn d'öcc 'sta pàgina què",
'savearticle'                      => "Regèstra e' tu scrètt",
'preview'                          => 'Guèrda prèma',
'showpreview'                      => 'Fam avdé prèma',
'showdiff'                         => 'Fam avdé i cambiamént',
'anoneditwarning'                  => "'''Oci!''' T'an sì miga intrè! T'an fèga e' patàca: a t'registrèn cun e' tu indirèzzi IP e a l' mitèn int la storia d'sta pàgina.",
'summary-preview'                  => "Prova a vdé l'uget:",
'newarticle'                       => '(Növ)',
'newarticletext'                   => "T'cì arìv int'una una pàgina ch'la n'esèst incora.
S't'vu fèla te, tàca scrivar int'e' spazi a què sotta (guèrda la [[{{MediaWiki:Helppage}}|pàgina d'servezi]] s't'vu saven piò).
S'cì arìv a que par sbaj, sciàza e butòn \"Indrì\" e t'ci a post.",
'noarticletext'                    => "In 'ste mumént un gn'è gnito in sta pàgina: l'è vuta.
Magari t'pù provè a [[Special:Search/{{PAGENAME}}|zirchè ste nòm]] in t'j ètar pàgin, <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} zirchè i regèstr relativ], o [{{fullurl:{{FULLPAGENAME}}|action=edit}} scrivj indentar adès].",
'previewnote'                      => "'''Òci! Lezz prèma cus t'e' fat; e' tu scrètt U N' è stè ancora registrè!'''",
'editing'                          => 'Cambiamént de $1',
'editingsection'                   => 'Mudèfiga $1 (seziòn)',
'copyrightwarning'                 => "Avìs: tot al contribuziòn sora {{SITENAME}} j è stimé coma sòta la \$2 (guèrda \$1 par i particulé).
S't'an vu che i tu scrètt j pòsa èsar cambié a piasé da ch'jetar, t'an perda témp a scrivar sora a què.
A e' stes mod, s't'vu carghé ste scrètt, ta t'e' da tü la respunsabilitè che l'è e' tü, uriginél, o ch'ta l'e' cupié d'un sit indov tot j po lezar, o t'l'è truvè da un'ètra font lèbra.
\"T'AN PRUVA GNÂNC A CARGHÈ DI SCRÈTT BREVÉTÉ, SÂNZA CMANDÉ E' PARMESS!\"",
'templatesused'                    => '{{PLURAL:$1|Mudel|Mudell}} druvé sora sta pàgina:',
'templatesusedpreview'             => '{{PLURAL:$1|Mudel|Mudell}} druvè in sta prova què',
'template-protected'               => '(prutèt)',
'template-semiprotected'           => '(mèz-prutet)',
'hiddencategories'                 => "Sta pàgina què la fa pèrt d'{{PLURAL:$1|una categureja nascosta|$1 categurej nascosti}}.",
'permissionserrorstext-withaction' => "Tan' ê e' parmess par $2, par {{PLURAL:$1|e' mutiv|i mutiv}} adqvè sotta:",

# History pages
'viewpagelogs'           => "Guèrda i regestar d'sta pàgina",
'currentrev-asof'        => 'Versiòn agiurnèda de $1',
'revisionasof'           => 'Revisiòn dal $1',
'previousrevision'       => '← Versiòn prèma ed questa',
'nextrevision'           => 'Versiòn piò nova →',
'currentrevisionlink'    => "Guèrda la versiòn d'adès",
'cur'                    => 'att',
'last'                   => 'prez',
'histlegend'             => "Cunfront tra'l versiòn d'un artècul: strésa cun e' maus sora al versiòn ch't'vu te e pu sciàza e' butòn \"Partès\" o e' butòn in bas.<br />
Lezenda: '''({{int:cur}})''' = difarenzi cun la versiòn d'adès; '''({{int:last}})''' = difarenzi cun la versiòn d'prèma; ''m''' = mudèfica znina",
'history-fieldset-title' => "Guèrda i cambiamént d'e' prèm a l'ultum",
'histfirst'              => 'Piò vecia',
'histlast'               => 'Piò rezent',

# Revision deletion
'rev-delundel'   => 'fam avdé/ardópa',
'revdel-restore' => 'chèmbia visiòn',

# Merge log
'revertmerge' => "Scanzèla l'uniòn",

# Diffs
'history-title'           => 'Elench d\'j cambiamént sora "$1"',
'difference'              => "(Difarénza fra'l versiòn)",
'lineno'                  => 'Riga $1:',
'compareselectedversions' => "Fa e' paragon tra 'l versiòn seleziunèdi",
'editundo'                => 'Scanzèla',

# Search results
'searchresults'             => 'I tu risulté',
'searchresults-title'       => 'I tu risulté par "$1"',
'searchresulttext'          => "A vut una mân par zarchè dagl'infurmaziòn sora {{SITENAME}}? Và a vdé [[{{MediaWiki:Helppage}}|zerca int é {{SITENAME}}]].",
'searchsubtitle'            => 'T\'e\' zarchè \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|toti al pàgin chj cminzèpia cun "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|toti al pàgin chj è atachedi a "$1"]])',
'searchsubtitleinvalid'     => "T'ë zarchè '''$1'''",
'notitlematches'            => "T'é zarchè un'artècul, ma guèrda che u'gn'è brisa 'ste nom. Si't sicur t'épa scrètt ben?",
'notextmatches'             => "T'e' zarché un quel ch'u'n à purté a gninto. U'n's trova gninto int'j parol dal pàgin.",
'prevn'                     => "{{PLURAL:quel prèma|i $1 prèma}} d'sti què",
'nextn'                     => "{{PLURAL:quél dòp|i $1 dòp}} d'sti què",
'viewprevnext'              => 'Guèrda ($1 {{int:pipe-separator}} $2) ($3).',
'search-result-size'        => '$1 ({{PLURAL:$2|1 parola|$2 paróli}})',
'search-redirect'           => '(ri-direziòn $1)',
'search-section'            => '(seziòn $1)',
'search-suggest'            => "V'levat di': $1",
'search-interwiki-caption'  => 'I prugèt fradel',
'search-interwiki-default'  => 'Arsultèd da $1:',
'search-interwiki-more'     => '(ad piò)',
'search-mwsuggest-enabled'  => 'cun j sugeriment',
'search-mwsuggest-disabled' => "'nciòn sugeriment",
'nonefound'                 => "'''Oci''': la rizerca l'a vèn fata in automatico sol in zert spàzi di nòm. S't'vù zirchè fra tot al pàgin (cumpresi al pàgin d'cunversaziòn, i template, ecc) próva a metar \"all:\", in inglés, dadnenz a é nòm ch't'é scrètt, piotòst scriv é spàzi di nòm, s't'al sé, e pu é nòm.",
'powersearch'               => 'Scandaja a fònd',
'powersearch-legend'        => 'Scandaja a fònd',
'powersearch-ns'            => "Zerca int'j spàzi d'nom:",
'powersearch-redir'         => "Fà una lèsta d'al ri-direziòn",
'powersearch-field'         => 'Zerca par',

# Preferences page
'preferences'   => 'I mì gost',
'mypreferences' => 'I mi gòst',

# Groups
'group-sysop' => 'Aministradór',

'grouppage-sysop' => '{{ns:project}}:Aministradór',

# User rights log
'rightslog' => "Dirètt d'j navigador",

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'Mudèfica sta pàgina',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|mudèfica|mudèfich}}',
'recentchanges'                  => 'I ultum cambiamént',
'recentchanges-legend'           => "Upziòn d'j ultum cambiamént",
'recentchanges-feed-description' => "Ste canël e' fa avdè i cambiamént piò rezent aj artècul d'sta wiki.",
'rcnote'                         => "A què sota t'pù truvé {{PLURAL:$1|l'ultum cambiamént|j ultum '''$1''' cambiamént in st'artècul}} int j ultum {{PLURAL:$2|dè|'''$2''' dè}}; agl'infurmaziòn j è agiurnèdi a e' $4 al $5.",
'rclistfrom'                     => 'Fam avdè i cambiamént növ a cminzipiè da $1',
'rcshowhideminor'                => '$1 al mudèfghi znini',
'rcshowhidebots'                 => '$1 i bot',
'rcshowhideliu'                  => '$1 i patàca registrè',
'rcshowhideanons'                => '$1 navigador anònim',
'rcshowhidemine'                 => '$1 völt a j ò scrètt',
'rclinks'                        => "Fam avdè la lèsta d'j $1 cambiamént int j ultum $2 dé<br />$3",
'diff'                           => 'dif.',
'hist'                           => 'cron',
'hide'                           => 'Ardòpa',
'show'                           => 'Fam avdé',
'minoreditletter'                => 'z',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Guèrda i particulèr (u j vó JavaScript)',
'rc-enhanced-hide'               => 'Arscònd i particulèr',

# Recent changes linked
'recentchangeslinked'         => "Cambiament ch'j va impèt a sta pàgina",
'recentchangeslinked-title'   => 'Cambiamént ch\'j va impèt a "$1"',
'recentchangeslinked-summary' => "Questa ch't'é sott' aj occ l'è una lista d'j ultum cambiamént fât a piò pàgin atachèdi a una pàgina (o a elemént d'una categurèja).
Al pàgin int'la lèsta dal [[Special:Watchlist|pàgin tnudi sot occ]] l' è scrètti in '''gros'''.",
'recentchangeslinked-page'    => "Nom d'la pàgina:",
'recentchangeslinked-to'      => "Fam avdé sol i cambiamént al pàgin ch'j va impèt a quèla ch'la m'interèsa a me.",

# Upload
'upload'        => 'Carga so un file',
'uploadlogpage' => "Regèstar d'j file carghé",
'uploadedimage' => 'l\'à carghé "[[$1]]"',

# File description page
'filehist'                  => "Stória d'e' file",
'filehist-help'             => "Fà clic sora un gròp dèda/ora par avdé cun cl'éra e' file in ch'ë mumént.",
'filehist-current'          => "d'adès",
'filehist-datetime'         => 'Dèda/Ora',
'filehist-thumb'            => 'Visiòn znina',
'filehist-thumbtext'        => 'Visiòn znina dla versiòn dal $1',
'filehist-user'             => 'Utent',
'filehist-dimensions'       => 'Amsür',
'filehist-comment'          => "Ch'roba è'l",
'imagelinks'                => 'I culegamént a ste file',
'linkstoimage'              => "{{PLURAL:$1|La pàgina a què sòta l'è tachéda|Al $1 pàgin a què sòta j è tachédi}} a 'ste file:",
'sharedupload'              => "Ste file e' ven da $1 e u s' pò druvè neca sora d'j ètri prugèt wiki.",
'uploadnewversion-linktext' => "Chèrga so una versiòn nova d'ste file",

# Statistics
'statistics' => 'Statistich',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|byte|byte}}',
'nmembers'      => 'Lumbaart (lmo)
$1 {{PLURAL:$1|elemént|elemént}}',
'prefixindex'   => "Tòti al pàgin cun e' prefess",
'newpages'      => 'Pàgin növi',
'move'          => 'Chèmbia nòm',
'movethispage'  => 'Sposta sta pàgina',
'pager-newer-n' => '{{PLURAL:$1|é piò rezent|i $1 piò rezent}}',
'pager-older-n' => '{{PLURAL:$1|é piò vëc|i $1 piò vècc}}',

# Book sources
'booksources'               => 'Va a truvè dj libar',
'booksources-search-legend' => 'A vut zarchè int j livar?',
'booksources-go'            => 'Và pu',

# Special:Log
'log' => 'Regèstar',

# Special:AllPages
'allpages'       => 'Tot al pàgin',
'alphaindexline' => 'Da $1 a $2',
'prevpage'       => "Pàgina prèma d'questa ($1)",
'allpagesfrom'   => 'Fam avdè al pàgin cminzipiènd da:',
'allpagesto'     => 'Fam avdé al pàgin infèn a:',
'allarticles'    => 'Toti al pàgin',
'allpagessubmit' => 'Va mò',

# Special:LinkSearch
'linksearch' => 'Ghènz int ê web',

# Special:Log/newusers
'newuserlogpage'          => "Regèstar d'j nov",
'newuserlog-create-entry' => "L'è arivé un patàca nov",

# Special:ListGroupRights
'listgrouprights-members' => "(Lèsta d'j mèmbar)",

# E-mail user
'emailuser' => 'Manda un scrètt a ste patàca',

# Watchlist
'watchlist'         => "Pàgin ch'a ten d'öcc",
'mywatchlist'       => "Pàgin ch'a ten d'öcc",
'addedwatch'        => "Mett insem a la lèsta d'pàgin sot öcc",
'addedwatchtext'    => "La pàgina \"[[:\$1]]\" adès l'è int la lèsta dal [[Special:Watchlist|pàgin da tnì d'öcc]]. D'ôra inenz t'pù avdé tòt al mudèfic a sta pàgina e a la pàgina d'cunversaziòn int la [[Special:RecentChanges|lèsta d'j cambiamént rezent]], in '''gros''', acsè j 's'pò guardè mej.
Se in un sgond temp t'vu cavé la pàgina dala lèsta da tnì d'öcc, s-cjaza un'ètra volta sora e' butòn \"ten d'öcc\".",
'removedwatch'      => 'Cavé dala lèsta dal pàgin da tnì sot öcc',
'removedwatchtext'  => 'La pàgina "[[:$1]]" l\'è stëda scanzlèda dala lèsta dal [[Special:Watchlist|pàgin da tnì sot öcc]].',
'watch'             => "Tèn d'öcc",
'watchthispage'     => "Ten d'öcc sta pagina",
'unwatch'           => "T'an stèga piò a t'nì d'öcc",
'watchlist-details' => "J è, int la lèsta dal pàgin da tnì d'öcc, {{PLURAL:$1|$1 pàgina|$1 pàgin}}, senza cuntê al pàgin d'cunversaziòn.",
'wlshowlast'        => "Fam avdé agl'ultum $1 ör $2 dè $3",
'watchlist-options' => "Scielt int la lèsta d'pàgin da tnì d'öcc",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => "Mêt insèn al pàgin da t'nì d'öcc...",
'unwatching' => "Scanzèla dal pàgin da t'nì d'öcc...",

# Delete
'deletepage'            => 'Scanzela la pàgina',
'confirmdeletetext'     => "Oci! T'ste par scanzlè una pàgina cun tòta la storia d'j su cambiamént.
Par piasé, cunferma ch't'ê propri l'intenziòn d'fël, ch'at capèss al su conseguenzi e quel ch't fë adès l'è in regola cun al [[{{MediaWiki:Policy-url}}]].",
'actioncomplete'        => 'Lavor fät e finì',
'deletedtext'           => 'La pàgina "<nowiki>$1</nowiki>" l\'è stëda scanzlèda.
Guèrda $2 par avdé la lèsta daglj ultum scanzeladür.',
'deletedarticle'        => 'l\'à scanzlé "[[$1]]"',
'dellogpage'            => 'Regèstar dal scanzladùr',
'deletecomment'         => 'Rasòn:',
'deleteotherreason'     => 'Ètar mutiv:',
'deletereasonotherlist' => 'Ètar mutiv',

# Rollback
'rollbacklink' => "armèt sta pàgina cun cl'era prèma",

# Protect
'protectlogpage'              => 'Pruteziòn',
'protectedarticle'            => '"[[$1]]" l\'è prutèt',
'modifiedarticleprotection'   => 'l\'à cambié e\' livèl d\'pruteziòn par "[[$1]]"',
'protectcomment'              => 'Rasòn:',
'protectexpiry'               => 'Scadenza:',
'protect_expiry_invalid'      => "La scadenza la n'è piò bona.",
'protect_expiry_old'          => "T'cì ariv tèrd! La dèda d'scadenza l'è zà pasëda.",
'protect-text'                => "Cun ste mudèl u s'pò guardè e u s'pò cambié ê livèl d'pruteziòn dla pàgina '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Oci! Ta n'ê miga i parmess par cambié i livel d'pruteziòn dla pàgina.
S't'vù vdë in dô ch'j è i parmess, guèrda '''$1''':",
'protect-cascadeon'           => "Sta pàgina pr'adès l'è bluchëda parchè l'è stra l'{{PLURAL:$1|la pàgina aquè sota, ch'l'à|al pàgin aquè sota, ch'j'à}} la pruteziòn ricursiva.
T'pù cambié ê livel d'pruteziòn d'sta pàgina, mo la pruteziòn ricursiva la resta a lè.",
'protect-default'             => 'Dà ê parmess a tòt j navigadur',
'protect-fallback'            => 'A què u j vô ê parmess "$1"',
'protect-level-autoconfirmed' => "Bloca i navigador nov e quij ch'j n' s'è miga registrè",
'protect-level-sysop'         => 'Sol par j aministradôr',
'protect-summary-cascade'     => 'ricursiv',
'protect-expiring'            => 'ê schëd: $1 (UTC)',
'protect-cascade'             => "Prutezz al pàgin ch'j fa pèrt d'questa (pruteziòn ricursiva)",
'protect-cantedit'            => "Oci! T'an pù miga cambié i livel d'pruteziòn dla pàgina, parchè t'an ê incora i parmess par cambié la pàgina.",
'restriction-type'            => 'Parmess:',
'restriction-level'           => "Livel d'restriziòn:",

# Undelete
'undeletelink'     => 'Guèrda/Torna indrì',
'undeletedarticle' => 'l\'à artruvè "[[$1]]"',

# Namespace form on various pages
'namespace'      => "Spàzi d'é nom:",
'invert'         => 'Torna indrì cun la seleziòn',
'blanknamespace' => '(Prèma)',

# Contributions
'contributions'       => 'I mì lavor sora Vichipedia',
'contributions-title' => "Tòt quel ch'l'à scrètt $1",
'mycontris'           => "Quél ch'a j ò scrètt me",
'contribsub2'         => 'Par $1 ($2)',
'uctop'               => '(va sò)',
'month'               => "A cminzipiè d'e' mës (è d'j mës prezedént)",
'year'                => "A cminzipié d'l'àn (e d'j èn prezedent)",

'sp-contributions-newbies'  => "Fam avdé sol i lavòr d'j utent nuv",
'sp-contributions-blocklog' => "Regèstar d'j bloc",
'sp-contributions-search'   => 'Zerca j lavór',
'sp-contributions-username' => 'Indirèzz IP o soranòm',
'sp-contributions-submit'   => 'Zerca',

# What links here
'whatlinkshere'            => "Pagin ch'j è atachedi a questa",
'whatlinkshere-title'      => 'Pagin atachédi a "$1"',
'whatlinkshere-page'       => 'Pàgina:',
'linkshere'                => "Sti pàgin a què al cuntèn di culegamént a '''[[:$1]]''':",
'isredirect'               => 'Re-indirèzza',
'istemplate'               => 'inclusiòn',
'isimage'                  => 'culegamént a una figura',
'whatlinkshere-prev'       => '{{PLURAL:$1|quel prèma|i $1 prèma}}',
'whatlinkshere-next'       => '{{PLURAL:$1|quel dòp|i $1 dòp}}',
'whatlinkshere-links'      => '← culegamént',
'whatlinkshere-hideredirs' => "$1 u t' rmanda",
'whatlinkshere-hidetrans'  => '$1 inclusiòn',
'whatlinkshere-hidelinks'  => '$1 culegamént',
'whatlinkshere-filters'    => 'Filtar',

# Block/unblock
'blockip'                  => 'Indirèzz IP bluché',
'ipboptions'               => '2 ór:2 hours,2 dè:1 day,3 dè:3 days,1 smana:1 week,2 smani:2 weeks,1 mês:1 month,3 mis:3 months,6 mîs:6 months,1 àn:1 year,par sempar:infinite',
'ipblocklist'              => 'Soranom e indirezz IP bluché',
'blocklink'                => 'Met-j é carnaz',
'unblocklink'              => "chèva e' carnàz",
'change-blocklink'         => 'Chèmbia carnàz',
'contribslink'             => "Ch'l'un ch'l'à scrét",
'blocklogpage'             => "Regèstar d'j blocch",
'blocklogentry'            => '"[[$1]]" l\'è sté bluché par $2 $3',
'unblocklogentry'          => "l'à sbluchè $1",
'block-log-flags-nocreate' => "Un' s'pò brisa registrès un'étra volta",

# Move page
'movepagetext'     => "Cun st'uperaziòn t'pù cambiè e' nom a una pàgina. Tòti al versiòn prezedenti j và drì a la pàgina nova.
E' nom vec, nec quel, u t' pórta a la pàgina nova.
E adès, a l' set cus ch't' pù fè? T'pù andé a zarchè i nom vec e meti-j a post tot cun e' nom nov. S't'a n'e' brisa voja, l'è listéss, parò va a cuntrulè prèma s'j è di [[Special:DoubleRedirects|doppii]] ó [[Special:BrokenRedirects|ghènz scuvert]].
T'ci responsabil d'cuntrulé che tot i ghenz j seja a post.

Oci! La pàgina la '''n'srà''' mòsa s'u j è za una pàgina cun e' stès nom. T'an pù scrivar sora una pàgina ch'la j è za.
Parò, se la pàgina l'è vuta o l'è sol un reindirizament.

'''STA ATENTI!'''
Questa l'è un'uperaziòn delichèta. A sit sicur? Mo propri? T'an féga de casèn.
Un cunsej? Pensa al conseguenzi de tu att prèma d's-cjazè e' butòn.",
'movepagetalktext' => "La pàgina d'cunversaziòn atachèda a st'artècul la sta insèm a l'artècul, '''fòra ch'in 'sti chès''':
*quènd ch'u s' sposta la pàgina tra spazi d'e' nom difarent;
*quènd una pàgina d'cunversaziòn la j è za cun e stes nom (e la n'è vuta)
*e' quadret d'cunferma aquè sota un è piò spuntè.

In tot sti chès, s'et pazienzia, t'pù spustè a man tot quel c'u j è scrètt intla pàgina d'cunversaziòn.",
'movearticle'      => 'Chèmbia nom a la pàgina:',
'newtitle'         => 'Titul nóv:',
'move-watch'       => "Ten sot'öcc sta pàgina",
'movepagebtn'      => 'Sposta la pàgina',
'pagemovedsub'     => "T'cì ste brev! T'a j é fata.",
'movepage-moved'   => "'''\"\$1\" l'à cambié post a \"\$2\"'''",
'articleexists'    => "La j è za una pàgina ch'la s-cjama acsè. O e nom ch'ta j e dë un va brisa ben.
Par piaser, daij un ètar nom.",
'talkexists'       => "'''La pàgina l'è stëda spusteda ben, invezi la pàgina d'cunversaziòn l'è andëda a cuzè cun òna ch'l'à ê stes nom. Csa fasègna?
Par piasé, met insem al du pàgin te, fasend copia e incola.",
'movedto'          => 'Spusté vers:',
'movetalk'         => "Sposta ènca la pàgina d'cunversaziòn",
'1movedto2'        => "[[$1]] l'è stê mòs a [[$2]]",
'1movedto2_redir'  => "[[$1]] l'è stè moss in [[$2]] par redireziòn",
'movelogpage'      => "Regéstar d'j muvimént",
'movereason'       => 'Rasòn:',
'revertmove'       => 'Métla cum era prèma',

# Export
'export' => 'Espórta dal pàgin',

# Thumbnails
'thumbnail-more' => 'Fal piò lèrg',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'La tu pàgina persunèla',
'tooltip-pt-mytalk'               => "La tu pàgina d'cunversaziòn cun ch'jetar",
'tooltip-pt-preferences'          => 'I tu gòst',
'tooltip-pt-watchlist'            => "Lèsta dal pàgin ch'te ténn sot öcc",
'tooltip-pt-mycontris'            => "Lèsta d'quel ch'a j ò scrètt mè",
'tooltip-pt-login'                => "E' srevv mej registrét, énch s't' antcé ublighè a fèl",
'tooltip-pt-logout'               => "Va fora, t'é finì",
'tooltip-ca-talk'                 => 'Guèrda al discussion sora sta pagina',
'tooltip-ca-edit'                 => "Te t' po' cambiè 'sta pagina. Par piasè, arcoldat, guèrda prèma cu'ste fat, e pu dòp sèlva e' tu lavor.",
'tooltip-ca-addsection'           => 'Taca una nova seziòn',
'tooltip-ca-viewsource'           => "Sta pàgina què l'à e' carnaz, ma t'pù avdé e' su codiz surgent.",
'tooltip-ca-history'              => "A vut avdé cun cl'era prèma sta pagina?",
'tooltip-ca-protect'              => 'Mett una pruteziòn a sta pàgina',
'tooltip-ca-delete'               => 'Scanzèla sta pàgina',
'tooltip-ca-move'                 => 'Chèmbia nòm a sta pàgina',
'tooltip-ca-watch'                => "Vut t'nì drì a sta pàgina?",
'tooltip-ca-unwatch'              => "Chèva sta pàgina d'la lista dj pàgin da tnì öcc",
'tooltip-search'                  => 'Zerca dentar {{SITENAME}}',
'tooltip-search-go'               => "Va a zirché una pàgina c'l'as cjèma acsè, s'la j è",
'tooltip-search-fulltext'         => "Zerca e' scrètt in tal pàgin",
'tooltip-n-mainpage'              => "Va a v'dé la Prèma Pagina",
'tooltip-n-mainpage-description'  => "Va a v'dé la prèma pagina",
'tooltip-n-portal'                => "A't spieghen nicosa sora e' purtèl, cosa t'pu fè a què e indov'ej al robi",
'tooltip-n-currentevents'         => "Zerca dagli infurmaziòn sora i fät d'incù dè",
'tooltip-n-recentchanges'         => "Lèsta d'j ultum cambiamént int'e' sit",
'tooltip-n-randompage'            => 'Fam avdé la pagina ch’ u t’ pé',
'tooltip-n-help'                  => "E't absogn d'spiegazion?",
'tooltip-t-whatlinkshere'         => "Lèsta d'tott al pagin ch'aglj è atachedi a questa",
'tooltip-t-recentchangeslinked'   => "Lèsta d'j ultum cambiament al pàgin atachédi a questa",
'tooltip-feed-rss'                => 'Canël RSS par sta pàgina',
'tooltip-feed-atom'               => 'Canël Atom par sta pàgina',
'tooltip-t-contributions'         => "Guèrda la lèsta d'tot i lavor ch'l'à fat ste patàca",
'tooltip-t-emailuser'             => 'Manda un scrètt a ste patàca',
'tooltip-t-upload'                => "Carga d'j file multimediél",
'tooltip-t-specialpages'          => 'Lèsta ad toti al pàgin particulèri',
'tooltip-t-print'                 => 'Versiòn bona da stampè sta pagina',
'tooltip-t-permalink'             => "Culegament fèss a 'sta version d'la pagina",
'tooltip-ca-nstab-main'           => "Guèrda st'ètra pagina",
'tooltip-ca-nstab-user'           => 'Guèrda la pàgina persunëla',
'tooltip-ca-nstab-special'        => "Csa fet ? Questa l'è una pàgina particulèra; t'an pu brisa scrivar sora",
'tooltip-ca-nstab-project'        => "Guèrda la pàgina dagl'infurmaziòn",
'tooltip-ca-nstab-image'          => "Guèrda la pàgina d'e' file",
'tooltip-ca-nstab-template'       => "Guèrda e' mudel",
'tooltip-ca-nstab-category'       => 'Guèrda la pàgina dla categurèja',
'tooltip-minoredit'               => "Segna sta mudèfga ch'l'è znina",
'tooltip-save'                    => 'Regèstra i tü cambiamént',
'tooltip-preview'                 => "Guèrda ste fat un bèl lavor, l'è sèmpar mej fè'l, prèma d'registrè!",
'tooltip-diff'                    => "Fa m'avdé i cambiamént ch'a j ò fat me",
'tooltip-compareselectedversions' => "Guèrda al diferénzi tra 'l du versiòn seleziunèdi d'sta pàgina",
'tooltip-watch'                   => 'Mett sta pagina insèm aglj ètri da tnì sot öcc',
'tooltip-rollback'                => "E butòn \"armèt cum'era prèma\" sérv par scanzlè quel ch'l'à fät ch'l'un ch'à lavurè par ultum in sta pàgina",
'tooltip-undo'                    => "\"Torna indrì\" e' serv par scanzlè 'sta mudèfica, l'avèrr la fnestra d'mudèfica in manira d'guardé prèma. Quènd t'e' fat, t'pù mètar in bas la spiegaziòn dla tu mudèfica.",

# Browsing diffs
'previousdiff' => "← Difarénza prèma d'questa",
'nextdiff'     => 'Mudèfica piò nova →',

# Media information
'file-info-size'       => "($1 × $2 pixel, amsùra d'e' file: $3, tipo MIME: $4)",
'file-nohires'         => "<small>U n' è pusèbil d'avdé cun òna risoluziòn piò élta. </small>",
'svg-long-desc'        => "(file d'taja SVG, dimensiòn numinèli $1 × $2 pixel, dimensiòn d'e' file: $3)",
'show-big-image'       => 'Versiòn ad amsura pina',
'show-big-image-thumb' => "<small>Amsùra d'sta versiòn: $1 × $2 pixel</small>",

# Bad image list
'bad_image_list' => "E' quèdar l'è quest'aquè:

As t'nèn bon sol j elemént dal lèsti (al righ chj cmènza par *)
E' prèm culegamént d'una riga l'à da èsar un culegamént a un file c'un gn'entra gninto
Tot i culegamént ch'j ven dop, sora la stèsa riga, j conta cumpagna d'j eceziòn (e' vò dì, pàgin indov e' file u's pò infilè nurmalment)",

# Metadata
'metadata'          => 'Dèd sora i dèd',
'metadata-help'     => "Ste file l'à d'l'infurmaziòn in piò. Forsi parchè al figur j è stedi cjapèdi con la machina digitèla o cun e' scàner.
Se parò un qvelcadon à lavurè sora sta figura, zerti infurmaziòn j n' curespòn piò cun l'uriginel.",
'metadata-expand'   => 'Fam avdé i particulèr',
'metadata-collapse' => 'Nascond i particulèr',
'metadata-fields'   => "I chêmp d'j metadata EXIF elenché in ste mesàg, j t' ven presenté int'la pàgina d'la figura, quènd la tabèla d'j metadata l'è ardòta znina. J ètar chèmp j srà nascost.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# External editor support
'edit-externally'      => 'Mudèfica ste file cun un prugrama esteran',
'edit-externally-help' => "(Guèrda e' [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] par avé d'j ètri infurmaziòn) (l'è in inglés)",

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tòt',
'namespacesall' => 'töt',
'monthsall'     => 'tòt',

# Watchlist editing tools
'watchlisttools-view' => 'Fam avdè al mudèfich impurtènti',
'watchlisttools-edit' => "Guèrda e mudèfica la lèsta d'pàgin da tnì d'öcc",
'watchlisttools-raw'  => "Mudèfiga la lèsta - scrètta - dal pàgin da tnì d'öcc",

# Special:SpecialPages
'specialpages' => 'Pàgin particulèri',

);
