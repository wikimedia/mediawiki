<?php
/** Mirandese (Mirandés)
 *
 * @addtogroup Language
 *
 * @author MCruz
 * @author Malafaya
 * @author Nike
 */

$fallback = 'pt';

$messages = array(
'underline-always' => 'Siempre',
'underline-never'  => 'Nunca',

# Dates
'sunday'        => 'Demingo',
'monday'        => 'Segunda',
'tuesday'       => 'Terça',
'wednesday'     => 'Quarta',
'thursday'      => 'Quinta',
'friday'        => 'Sexta',
'saturday'      => 'Sábado',
'sun'           => 'Dem',
'mon'           => 'Seg',
'tue'           => 'Ter',
'wed'           => 'Qua',
'thu'           => 'Qui',
'fri'           => 'Sex',
'sat'           => 'Sab',
'january'       => 'Janeiro',
'february'      => 'Febreiro',
'march'         => 'Márçio',
'april'         => 'Abril',
'may_long'      => 'Maio',
'june'          => 'Junho',
'july'          => 'Julho',
'august'        => 'Agosto',
'september'     => 'Setembre',
'october'       => 'Outubre',
'november'      => 'Novembre',
'december'      => 'Dezembre',
'january-gen'   => 'Janeiro',
'february-gen'  => 'Febreiro',
'march-gen'     => 'Márcio',
'april-gen'     => 'Abril',
'may-gen'       => 'Maio',
'june-gen'      => 'Junho',
'july-gen'      => 'Julho',
'august-gen'    => 'Agosto',
'september-gen' => 'Setembre',
'october-gen'   => 'Outubre',
'november-gen'  => 'Novembre',
'december-gen'  => 'Dezembre',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'Mai',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Out',
'nov'           => 'Nov',
'dec'           => 'Dez',

# Categories related messages
'category_header'        => 'Páginas na categoria "$1"',
'listingcontinuesabbrev' => 'cont.',

'about'     => 'Sobre',
'newwindow' => '(abre numa nuoba janela)',
'cancel'    => 'Cancelar',
'qbfind'    => 'Procurar',
'qbedit'    => 'Editar',
'mytalk'    => 'Mie cumbersa',

'tagline'          => 'De {{SITENAME}}',
'help'             => 'Ajuda',
'search'           => 'Pesquisa',
'searchbutton'     => 'Pesquisar',
'searcharticle'    => 'Ir',
'history'          => 'Histórico da página',
'history_short'    => 'Histórico',
'printableversion' => 'Versão para impressão',
'permalink'        => 'Ligaçon permanente',
'edit'             => 'Editar',
'delete'           => 'Apagar',
'protect'          => 'Proteger',
'newpage'          => 'Nuoba página',
'talkpagelinktext' => 'Cumbersar',
'personaltools'    => 'Ferramentas pessoais',
'talk'             => 'Çcusson',
'views'            => 'Bistas',
'toolbox'          => 'Caixa de Ferramentas',
'jumpto'           => 'Saltar a:',
'jumptonavigation' => 'navegaçon',
'jumptosearch'     => 'pesquisa',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => 'Sobre {{SITENAME}}',
'aboutpage'      => 'Project:Sobre',
'currentevents'  => 'Amboras actuais',
'disclaimers'    => 'Alerta de Conteúdo',
'disclaimerpage' => 'Project:Aviso geral',
'helppage'       => 'Ayuda:Conteúdos',
'mainpage'       => 'Página principal',
'privacy'        => 'Política de privacidade',
'sitesupport'    => 'Donativos',

'retrievedfrom'      => 'Obtido an "$1"',
'youhavenewmessages' => 'Você tem $1 ($2).',
'newmessageslink'    => 'nuobas mensages',
'editsection'        => 'eitar',
'editold'            => 'editar',
'editsectionhint'    => 'Editar secção: $1',
'toc'                => 'Tabla de contenido',
'showtoc'            => 'mostrar',
'hidetoc'            => 'çconder',
'site-rss-feed'      => 'Feed RSS $1',
'site-atom-feed'     => 'Feed Atom $1',
'page-rss-feed'      => 'Feed RSS de "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => "Página d'utilizador",
'nstab-project'  => 'Página de proyecto',
'nstab-image'    => 'Ficheiro',
'nstab-template' => 'Modelo',
'nstab-category' => 'Categoria',

# General errors
'viewsource'    => 'Ber código',
'viewsourcefor' => 'para $1',

# Login and logout pages
'yourname'       => 'Su nome de utilizador',
'yourpassword'   => 'Palabra-chave',
'login'          => 'Entrar',
'userlogin'      => 'Entrar / criar cuonta',
'logout'         => 'Salir',
'userlogout'     => 'Salir',
'gotaccountlink' => 'Entrar',

# Edit page toolbar
'bold_sample'     => 'Testo carregado',
'bold_tip'        => 'Testo negro',
'italic_sample'   => 'Testo itálico',
'italic_tip'      => 'Testo an itálico',
'headline_sample' => 'Testo de cabeçalho',
'nowiki_tip'      => 'Ignorar formato wiki',

# Edit pages
'summary'               => 'Sumário',
'preview'               => 'Prever',
'showpreview'           => 'Mostrar prebison',
'blockedtext'           => '<big>O seu nome de utilizador ou endereço de IP foi bloqueado</big>

O bloqueio foi realizado por $1. O motivo apresentado foi \'\'$2\'\'.

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destino do bloqueio: $7

Você pode contactar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir sobre o bloqueio.

Note que não poderá utilizar a funcionalidade "Contactar utilizador" se não possuir uma conta neste wiki ({{SITENAME}}) com um endereço de email válido indicado nas suas [[{{ns:special}}:Preferences|preferências de utilizador]] e se tiver sido bloqueado de utilizar tal recurso.

O seu endereço de IP atual é $3 e a ID de bloqueio é $5. Por favor, inclua um desses (ou ambos) dados em quaisquer tentativas de esclarecimentos.',
'newarticle'            => '(Nuoba)',
'newarticletext'        => "Você seguiu uma ligaçon para unhaa página que inda num existe. 
Para criá-la, screva l sue conteúdo na caixa abaixo
(veja a [[{{MediaWiki:Helppage}}|página de ajuda]] para mais detalhes).
Se você chegou até aqui por angano, clique ne l boton '''boltar''' (o ''back'') de l sue navegador.",
'editing'               => 'A editar $1',
'copyrightwarning'      => 'Por fabor, note que todas las sues contribuiçons an {{SITENAME}} son consideradas cumo lhançadas ne ls termos de la lhicença $2 (ber $1 para detalhes). Se num deseija que o sue testo seija inexoravelmente editado i redistribuído de tal forma, num lo enbie.<br />
Você está, al mesmo tempo, a garantir-nos que isto ye algo escrito por si, o algo copiado de unha fonte de testos an domínio público o similarmente de teor libre.
<strong>NUM ENBIE TRABALHO PROTEGIDO POR DREITOS DE AUTOR SAN A DEBIDA PERMISSON!</strong>',
'template-protected'    => '(protegida)',
'recreate-deleted-warn' => "'''Atenção: Você está a criar uma página já anteriormente eliminada.'''

Certifique-se de que é adequado prosseguir a edição de esta página.
O registo de eliminação desta página é exibido a seguir, para sua comodidade:",

# History pages
'currentrev' => 'Revison actual',
'cur'        => 'act',
'last'       => 'último',
'page_first' => 'purmeira',
'page_last'  => 'última',

# Revision feed
'history-feed-item-nocomment' => '$1 a $2', # user at time

# Diffs
'lineno'   => 'Linha $1:',
'editundo' => 'desfazer',

# Search results
'prevn'        => 'anteriores $1',
'nextn'        => 'próximos $1',
'viewprevnext' => 'Ber ($1) ($2) ($3)',
'powersearch'  => 'Pesquisa',

# Preferences page
'mypreferences' => 'Las mies preferencias',

# Recent changes
'recentchanges'   => 'Alteraçons recentes',
'rcshowhidebots'  => '$1 robots',
'rcshowhideliu'   => '$1 utilizadores registados',
'rcshowhidemine'  => '$1 mies ediçons',
'diff'            => 'dif',
'hist'            => 'hist',
'hide'            => 'Esconder',
'show'            => 'Mostrar',
'minoreditletter' => 'm',
'newpageletter'   => 'N',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked' => 'Alterações relacionadas',

# Upload
'upload'        => 'Carregar ficheiro',
'uploadlogpage' => 'Registo de carregamento',

# Special:Imagelist
'imagelist' => 'Lista de ficheiros',

# Image description page
'filehist-current'  => 'actual',
'filehist-datetime' => 'Data/Hora',
'filehist-user'     => 'Utilizador',
'filehist-filesize' => 'Tamanho de ficheiro',
'filehist-comment'  => 'Comentário',
'imagelinks'        => 'Ligaçons (andereços web)',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|byte|bytes}}',
'nlinks'       => '$1 {{PLURAL:$1|link|links}}',
'nmembers'     => '$1 {{PLURAL:$1|membro|membros}}',
'specialpages' => 'Páginas speciales',
'newpages'     => 'Nuovas páginas',
'move'         => 'Mover',

# Book sources
'booksources' => 'Fontes de lhibros',

# Special:Log
'log' => 'Registos',

# Special:Allpages
'allpages'       => 'Todas las páginas',
'alphaindexline' => '$1 a $2',
'allarticles'    => 'Todas las páginas',
'allpagessubmit' => 'Ir',

# Watchlist
'watchlistfor' => "(para '''$1''')",
'watch'        => 'Bigiar',
'wlshowlast'   => 'Ber últimas $1 horas $2 dias $3',

# Delete/protect/revert
'deletepage'              => 'Apagar página',
'deletedarticle'          => 'apagado "[[$1]]"',
'rollbacklink'            => 'voltar',
'protect-legend'          => 'Confirmar protecçon',
'protect-summary-cascade' => 'p. progressiva',

# Undelete
'undeletebtn' => 'Restaurar',

# Namespace form on various pages
'blanknamespace' => '(Principal)',

# Contributions
'mycontris'   => 'Mies contribuiçons',
'contribsub2' => 'Para $1 ($2)',
'uctop'       => ' (revison actual)',

'sp-contributions-newbies-sub' => 'Para nuobas cuontas',

# What links here
'whatlinkshere'       => 'Páginas afluentes',
'whatlinkshere-title' => 'Páginas que apontam para $1',
'istemplate'          => 'incluson',
'whatlinkshere-links' => '← andereços da anternet',

# Block/unblock
'blocklink'    => 'bloquear',
'contribslink' => 'contribs',

# Move page
'move-page-legend' => 'Mover página',
'movearticle'      => 'Mover página',
'newtitle'         => 'Para nuovo títalo',
'movepagebtn'      => 'Mover página',
'movedto'          => 'movido para',
'movelogpage'      => 'Registo de movimentos',
'revertmove'       => 'reverter',

# Thumbnails
'thumbnail-more' => 'Aumentar',

# Tooltip help for the actions
'tooltip-pt-userpage'       => "La mie página d'utilizador",
'tooltip-pt-mytalk'         => 'Página de mie cumbersa',
'tooltip-pt-preferences'    => 'Las mies preferencias',
'tooltip-pt-mycontris'      => 'Lhista das mies contribuiçons',
'tooltip-pt-login'          => 'Você é encorajado a autenticar-se, apesar disso não ser obrigatório.',
'tooltip-pt-logout'         => 'Sair',
'tooltip-ca-delete'         => 'Apagar esta página',
'tooltip-ca-move'           => 'Mover esta página',
'tooltip-search'            => 'Pesquisa {{SITENAME}}',
'tooltip-n-mainpage'        => 'Visitar la página principal',
'tooltip-n-randompage'      => 'Carregar página aleatória',
'tooltip-t-whatlinkshere'   => 'Lista de todas las páginas que se lhigam a yesta',
'tooltip-t-upload'          => 'Carregar imagens ou ficheiros',
'tooltip-t-specialpages'    => 'Lista de páginas especiais',
'tooltip-ca-nstab-image'    => 'Ber la página de l ficheiro',
'tooltip-ca-nstab-template' => 'Ber l modelo',
'tooltip-save'              => 'Grabar sues alterações',

# Media information
'file-info-size' => '($1 × $2 pixel, tamanho: $3, tipo MIME: $4)',
'svg-long-desc'  => '(ficheiro SVG, de $1 × $2 pixels, tamanho: $3)',
'show-big-image' => 'Resoluçon completa',

# Bad image list
'bad_image_list' => 'The format is as follows:

Only list items (lines starting with *) are considered. The first link on a line must be a link to a bad image.
Any subsequent links on the same line are considered to be exceptions, i.e. articles where the image may occur inline.',

# Metadata
'metadata' => 'Metadados',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'todas',
'namespacesall' => 'todas',
'monthsall'     => 'todos',

);
