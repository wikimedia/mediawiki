<?php
/** Mirandese (Mirandés)
 *
 * @addtogroup Language
 *
 * @author MCruz
 * @author Malafaya
 * @author Nike
 * @author Siebrand
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
'subcategories'          => 'Subcategories',
'listingcontinuesabbrev' => 'cont.',

'about'     => 'Sobre',
'newwindow' => '(abre numa nuoba janela)',
'cancel'    => 'Cancelar',
'qbfind'    => 'Procurar',
'qbedit'    => 'Editar',
'mytalk'    => 'Mie cumbersa',

'errorpagetitle'   => 'Erro',
'returnto'         => 'Retornar para $1.',
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
'editthispage'     => 'Editar yesta página',
'delete'           => 'Apagar',
'protect'          => 'Proteger',
'newpage'          => 'Nuoba página',
'talkpage'         => 'Çcutir yesta página',
'talkpagelinktext' => 'Cumbersar',
'personaltools'    => 'Ferramentas pessoais',
'talk'             => 'Çcusson',
'views'            => 'Bistas',
'toolbox'          => 'Caixa de Ferramentas',
'redirectedfrom'   => '(Redireccionado de <b>$1</b>)',
'redirectpagesub'  => 'Página de redireccionamento',
'jumpto'           => 'Saltar a:',
'jumptonavigation' => 'navegaçon',
'jumptosearch'     => 'pesquisa',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Sobre {{SITENAME}}',
'aboutpage'            => 'Project:Sobre',
'bugreports'           => 'Reportar bugs',
'bugreportspage'       => 'Project:Relatos_de_bugs',
'copyrightpage'        => '{{ns:project}}:Direitos de autor',
'currentevents'        => 'Amboras actuais',
'currentevents-url'    => 'Project:Amboras actuales',
'disclaimers'          => 'Alerta de Conteúdo',
'disclaimerpage'       => 'Project:Aviso geral',
'edithelp'             => 'Ajuda de edição',
'edithelppage'         => 'Help:Editar',
'helppage'             => 'Help:Conteúdos',
'mainpage'             => 'Página principal',
'mainpage-description' => 'Página principal',
'portal'               => 'Portal da quemunidade',
'portal-url'           => 'Project:Portal da quemunidade',
'privacy'              => 'Política de privacidade',
'privacypage'          => 'Project:Política de privacidade',
'sitesupport'          => 'Donativos',
'sitesupport-url'      => 'Proyecto:Apoio',

'retrievedfrom'       => 'Obtido an "$1"',
'youhavenewmessages'  => 'Você tem $1 ($2).',
'newmessageslink'     => 'nuobas mensages',
'newmessagesdifflink' => 'comparar com la penúltima revison',
'editsection'         => 'eitar',
'editold'             => 'editar',
'editsectionhint'     => 'Editar secção: $1',
'toc'                 => 'Tabla de contenido',
'showtoc'             => 'mostrar',
'hidetoc'             => 'çconder',
'site-rss-feed'       => 'Feed RSS $1',
'site-atom-feed'      => 'Feed Atom $1',
'page-rss-feed'       => 'Feed RSS de "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => "Página d'utilizador",
'nstab-project'  => 'Página de proyecto',
'nstab-image'    => 'Ficheiro',
'nstab-template' => 'Modelo',
'nstab-category' => 'Categoria',

# General errors
'badtitle'       => 'Títalo inválido',
'viewsource'     => 'Ber código',
'viewsourcefor'  => 'para $1',
'viewsourcetext' => 'Você puode ber i copiar l código desta página:',

# Login and logout pages
'yourname'                => 'Su nome de utilizador',
'yourpassword'            => 'Palabra-chave',
'remembermypassword'      => 'Lhembrar la mie palabra-chave antre sessons neste computador.',
'login'                   => 'Entrar',
'nav-login-createaccount' => 'Entrar / criar cuonta',
'loginprompt'             => 'Tem que ter ls <i>cookies</i> activos para poder autenticar-se na {{SITENAME}}.',
'userlogin'               => 'Entrar / criar cuonta',
'logout'                  => 'Salir',
'userlogout'              => 'Salir',
'nologin'                 => 'Num tem ua cuonta? $1.',
'nologinlink'             => 'Crear ua cuonta',
'createaccount'           => 'Criar nuoba cuonta',
'gotaccount'              => 'Ya tem ua cuonta? $1.',
'gotaccountlink'          => 'Entrar',
'yourrealname'            => 'Nome berdadeiro:',
'loginsuccesstitle'       => 'Login bem sucedido',
'nouserspecified'         => 'Tem que specificar um nome de outelizador.',
'mailmypassword'          => 'Ambiar ua nuoba palabra-chabe por correio electrónico',

# Edit page toolbar
'bold_sample'     => 'Testo carregado',
'bold_tip'        => 'Testo negro',
'italic_sample'   => 'Testo itálico',
'italic_tip'      => 'Testo an itálico',
'link_sample'     => 'Título da ligação',
'link_tip'        => 'Ligação interna',
'extlink_sample'  => 'http://www.exemplo.pt títalu de ligaçon externa',
'extlink_tip'     => 'Ligaçon externa (lembre-se do prefixo http://)',
'headline_sample' => 'Testo de cabeçalho',
'headline_tip'    => 'Secção de nível 2',
'math_sample'     => 'Inserir fórmula aqui',
'math_tip'        => 'Fórmula matemática (LaTeX)',
'nowiki_sample'   => 'Inserir texto não-formatado aqui',
'nowiki_tip'      => 'Ignorar formato wiki',
'image_tip'       => 'Ficheiro embebido',
'media_tip'       => 'Ligação para ficheiro',
'sig_tip'         => 'Sua assinatura, com hora e data',
'hr_tip'          => 'Linha horizontal (utilize moderadamente)',

# Edit pages
'summary'                => 'Sumário',
'subject'                => 'Assunto/cabeçalho',
'minoredit'              => 'Marcar como edição mínima',
'watchthis'              => 'Observar esta página',
'savearticle'            => 'Grabar página',
'preview'                => 'Prever',
'showpreview'            => 'Mostrar prebison',
'showdiff'               => 'Mostrar alterações',
'anoneditwarning'        => "'''Atenção''': Você não se encontra autenticado. O seu endereço de IP será registado no histórico de edições desta página.",
'summary-preview'        => 'Previson de sumário',
'blockedtext'            => '<big>O seu nome de utilizador ou endereço de IP foi bloqueado</big>

O bloqueio foi realizado por $1. O motivo apresentado foi \'\'$2\'\'.

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destino do bloqueio: $7

Você pode contactar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir sobre o bloqueio.

Note que não poderá utilizar a funcionalidade "Contactar utilizador" se não possuir uma conta neste wiki ({{SITENAME}}) com um endereço de email válido indicado nas suas [[{{ns:special}}:Preferences|preferências de utilizador]] e se tiver sido bloqueado de utilizar tal recurso.

O seu endereço de IP atual é $3 e a ID de bloqueio é $5. Por favor, inclua um desses (ou ambos) dados em quaisquer tentativas de esclarecimentos.',
'newarticle'             => '(Nuoba)',
'newarticletext'         => "Você seguiu uma ligaçon para unhaa página que inda num existe. 
Para criá-la, screva l sue conteúdo na caixa abaixo
(veja a [[{{MediaWiki:Helppage}}|página de ajuda]] para mais detalhes).
Se você chegou até aqui por angano, clique ne l boton '''boltar''' (o ''back'') de l sue navegador.",
'previewnote'            => '<strong>Isto ye apenas unha prebison. Las alteraçons inda num foram grabadas!</strong>',
'editing'                => 'A editar $1',
'editingsection'         => 'Editando $1 (secçon)',
'copyrightwarning'       => 'Por fabor, note que todas las sues contribuiçons an {{SITENAME}} son consideradas cumo lhançadas ne ls termos de la lhicença $2 (ber $1 para detalhes). Se num deseija que o sue testo seija inexoravelmente editado i redistribuído de tal forma, num lo enbie.<br />
Você está, al mesmo tempo, a garantir-nos que isto ye algo escrito por si, o algo copiado de unha fonte de testos an domínio público o similarmente de teor libre.
<strong>NUM ENBIE TRABALHO PROTEGIDO POR DREITOS DE AUTOR SAN A DEBIDA PERMISSON!</strong>',
'longpagewarning'        => '<strong>AVISO: Esta página possui $1 kilobytes; alguns
navegadores possuem problemas em editar páginas maiores que 32kb.
Por favor, considere seccionar a página em secções de menor dimensão.</strong>',
'templatesused'          => 'Predefiniçons utilizadas nesta página:',
'templatesusedpreview'   => 'Templates usados nesta previsão:',
'template-protected'     => '(protegida)',
'template-semiprotected' => '(semi-protegida)',
'recreate-deleted-warn'  => "'''Atenção: Você está a criar uma página já anteriormente eliminada.'''

Certifique-se de que é adequado prosseguir a edição de esta página.
O registo de eliminação desta página é exibido a seguir, para sua comodidade:",

# History pages
'viewpagelogs'        => 'Ber registos para yesta página',
'currentrev'          => 'Revison actual',
'revisionasof'        => 'Revisão de $1',
'revision-info'       => 'Revison de $1; $2',
'previousrevision'    => '← Versão anterior',
'nextrevision'        => 'Verson posterior →',
'currentrevisionlink' => 'Ber berson actual',
'cur'                 => 'act',
'last'                => 'último',
'page_first'          => 'purmeira',
'page_last'           => 'última',
'histlegend'          => 'Selecção de diferença: marque as caixas em uma das versões que deseja comparar e carregue no botão.<br />
Legenda: (actu) = diferenças da versão actual,
(ult) = diferença da versão precedente, m = edição menor',
'histfirst'           => 'Mais antigas',
'histlast'            => 'Mais recentes',

# Revision feed
'history-feed-item-nocomment' => '$1 a $2', # user at time

# Diffs
'history-title'           => 'Histórico de ediçons de "$1"',
'difference'              => '(Diferença entre revisões)',
'lineno'                  => 'Linha $1:',
'compareselectedversions' => 'Compare as versões seleccionadas',
'editundo'                => 'desfazer',

# Search results
'noexactmatch' => "'''Num eisiste ua página com l títalo \"\$1\".''' Você puode [[:\$1|criar tal página]].",
'prevn'        => 'anteriores $1',
'nextn'        => 'próximos $1',
'viewprevnext' => 'Ber ($1) ($2) ($3)',
'powersearch'  => 'Pesquisa avançada',

# Preferences page
'preferences'   => 'Preferencies',
'mypreferences' => 'Las mies preferencias',

# Recent changes
'nchanges'        => '$1 {{PLURAL:$1|alteração|alterações}}',
'recentchanges'   => 'Alteraçons recentes',
'rcnote'          => "A seguir {{PLURAL:$1|está listada '''uma''' alteração ocorrida|estão listadas '''$1''' alterações ocorridas}} {{PLURAL:$2|no último dia|nos últimos '''$2''' dias}}, a partir de $3.",
'rclistfrom'      => 'Mostrar as novas alterações a partir de $1',
'rcshowhideminor' => '$1 edições mínimas',
'rcshowhidebots'  => '$1 robots',
'rcshowhideliu'   => '$1 utilizadores registados',
'rcshowhideanons' => '$1 utilizadores anónimos',
'rcshowhidemine'  => '$1 mies ediçons',
'rclinks'         => 'Mostrar as últimas $1 mudanças nos últimos $2 dias<br />$3',
'diff'            => 'dif',
'hist'            => 'hist',
'hide'            => 'Esconder',
'show'            => 'Mostrar',
'minoreditletter' => 'm',
'newpageletter'   => 'N',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked'          => 'Alterações relacionadas',
'recentchangeslinked-title'    => 'Alterações relacionadas com "$1"',
'recentchangeslinked-noresult' => 'Não ocorreram alterações em páginas relacionadas no intervalo de tempo fornecido.',
'recentchangeslinked-summary'  => "Esta página especial lista as alterações mais recentes de páginas que possuam um link a outra. Páginas que estejam em sua lista de artigos vigiados são mostradas a '''negrito'''.",

# Upload
'upload'        => 'Carregar ficheiro',
'uploadbtn'     => 'Carregar ficheiro',
'uploadlogpage' => 'Registo de carregamento',
'uploadedimage' => 'carregou "[[$1]]"',

# Special:Imagelist
'imagelist' => 'Lista de ficheiros',

# Image description page
'filehist'            => 'Histórico de l ficheiro',
'filehist-help'       => 'Clique an unha data/horário para ber l ficheiro tal como eilhe se encontraba an tal momento.',
'filehist-current'    => 'actual',
'filehist-datetime'   => 'Data/Hora',
'filehist-user'       => 'Utilizador',
'filehist-dimensions' => 'Dimensões',
'filehist-filesize'   => 'Tamanho de ficheiro',
'filehist-comment'    => 'Comentário',
'imagelinks'          => 'Ligaçons (andereços web)',
'linkstoimage'        => 'As seguintes páginas apontam para este ficheiro:',
'nolinkstoimage'      => 'Nenhuma página aponta para yeste ficheiro.',
'sharedupload'        => 'Este ficheiro encontra-se partilhado i puode ser usado por otros proyectos.',
'noimage-linktext'    => 'upload it',

# MIME search
'mimesearch' => 'Pesquisa MIME',

# Random page
'randompage' => 'Página aleatória',

# Statistics
'statistics' => 'Çtatísticas',

# Miscellaneous special pages
'nbytes'         => '$1 {{PLURAL:$1|byte|bytes}}',
'nlinks'         => '$1 {{PLURAL:$1|link|links}}',
'nmembers'       => '$1 {{PLURAL:$1|membro|membros}}',
'mostcategories' => 'Páginas de conteúdo com mais categories',
'prefixindex'    => 'Índice de prefixo',
'shortpages'     => 'Páginas curtas',
'longpages'      => 'Páginas longas',
'protectedpages' => 'Páginas protegidas',
'listusers'      => 'Lhista de outelizadores',
'specialpages'   => 'Páginas speciales',
'newpages'       => 'Nuovas páginas',
'ancientpages'   => 'Páginas mais antigas',
'move'           => 'Mover',
'movethispage'   => 'Mover esta página',

# Book sources
'booksources' => 'Fontes de lhibros',

# Special:Log
'specialloguserlabel'  => 'Outelizador:',
'speciallogtitlelabel' => 'Títalo:',
'log'                  => 'Registos',
'all-logs-page'        => 'Todos os registos',

# Special:Allpages
'allpages'       => 'Todas las páginas',
'alphaindexline' => '$1 a $2',
'nextpage'       => 'Próxima página ($1)',
'prevpage'       => 'Página anterior ($1)',
'allpagesfrom'   => 'Mostrar páginas começando an:',
'allarticles'    => 'Todas las páginas',
'allpagessubmit' => 'Ir',

# Special:Categories
'categories' => 'Categories',

# E-mail user
'emailuser' => 'Contactar yeste outelizador',

# Watchlist
'watchlist'            => 'Artigos vigiados',
'mywatchlist'          => 'Artigos vigiados',
'watchlistfor'         => "(para '''$1''')",
'watch'                => 'Bigiar',
'watchthispage'        => 'Bigiar yesta página',
'unwatch'              => 'Desinteressar-se',
'wlshowlast'           => 'Ber últimas $1 horas $2 dias $3',
'watchlist-hide-own'   => 'çconder mies ediçons',
'watchlist-hide-minor' => 'sconder ediçons menores',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Vigiando...',
'unwatching' => 'Deixando de vigiar...',

# Delete/protect/revert
'deletepage'              => 'Apagar página',
'actioncomplete'          => 'Acção terminada',
'deletedarticle'          => 'apagado "[[$1]]"',
'dellogpage'              => 'Registo de eliminação',
'deletereasonotherlist'   => 'Outro motivo',
'rollbacklink'            => 'voltar',
'protectlogpage'          => 'Registo de protecção',
'protect-legend'          => 'Confirmar protecçon',
'protect-default'         => '(padron)',
'protect-level-sysop'     => 'Apenas administradores',
'protect-summary-cascade' => 'p. progressiva',
'restriction-type'        => 'Permisson:',

# Undelete
'undeletebtn' => 'Restaurar',

# Namespace form on various pages
'namespace'      => 'Espaço de nomes:',
'invert'         => 'Amberter selecçon',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contribuições do utilizador',
'mycontris'     => 'Mies contribuiçons',
'contribsub2'   => 'Para $1 ($2)',
'uctop'         => ' (revison actual)',
'month'         => 'Mês (incluye meses anteriores):',
'year'          => 'Anho (incluye anhos anteriores):',

'sp-contributions-newbies-sub' => 'Para nuobas cuontas',
'sp-contributions-blocklog'    => 'Registo de bloqueios',

# What links here
'whatlinkshere'       => 'Páginas afluentes',
'whatlinkshere-title' => 'Páginas que apontam para $1',
'linklistsub'         => '(Lista de ligações)',
'linkshere'           => "As seguintes páginas possuem ligações para '''[[:$1]]''':",
'nolinkshere'         => "Num eisistem ligaçons para '''[[:$1]]'''.",
'isredirect'          => 'página de redireccionamento',
'istemplate'          => 'incluson',
'whatlinkshere-prev'  => '{{PLURAL:$1|anterior|$1 anteriores}}',
'whatlinkshere-next'  => '{{PLURAL:$1|próximo|próximos $1}}',
'whatlinkshere-links' => '← andereços da anternet',

# Block/unblock
'blockip'       => 'Bloquear outelizador',
'ipboptions'    => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 anho:1 year,indefinido:infinite', # display1:time1,display2:time2,...
'ipblocklist'   => 'Lhista de IPs i outelizadores bloqueados',
'blocklink'     => 'bloquear',
'contribslink'  => 'contribs',
'blocklogpage'  => 'Registo de bloqueio',
'blocklogentry' => '"[[$1]]" fue bloqueado com um tiempo de expiraçon de $2 $3',

# Move page
'move-page-legend' => 'Mover página',
'movearticle'      => 'Mover página',
'newtitle'         => 'Para nuovo títalo',
'movepagebtn'      => 'Mover página',
'movedto'          => 'movido para',
'1movedto2'        => '[[$1]] foi movido para [[$2]]',
'movelogpage'      => 'Registo de movimentos',
'movereason'       => 'Motivo:',
'revertmove'       => 'reverter',

# Export
'export' => 'Exportação de páginas',

# Thumbnails
'thumbnail-more'  => 'Aumentar',
'thumbnail_error' => 'Erro ao criar miniatura: $1',

# Import log
'importlogpage' => 'Registo de amportaçons',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "La mie página d'utilizador",
'tooltip-pt-mytalk'               => 'Página de mie cumbersa',
'tooltip-pt-preferences'          => 'Las mies preferencias',
'tooltip-pt-watchlist'            => 'Lista de artigos vigiados.',
'tooltip-pt-mycontris'            => 'Lhista das mies contribuiçons',
'tooltip-pt-login'                => 'Você é encorajado a autenticar-se, apesar disso não ser obrigatório.',
'tooltip-pt-logout'               => 'Sair',
'tooltip-ca-talk'                 => 'Discussão sobre o conteúdo da página',
'tooltip-ca-edit'                 => 'Você pode editar esta página. Por favor, use o botão Mostrar Previsão antes de gravar.',
'tooltip-ca-addsection'           => 'Adicionar comentário a yesta çcusson.',
'tooltip-ca-viewsource'           => 'Esta página está protegida. No entanto, você pode ver o seu código.',
'tooltip-ca-protect'              => 'Proteger esta página',
'tooltip-ca-delete'               => 'Apagar esta página',
'tooltip-ca-move'                 => 'Mover esta página',
'tooltip-ca-watch'                => 'Adicionar esta página als artigos vigiados',
'tooltip-ca-unwatch'              => 'Remover yesta página de ls artigos vigiados',
'tooltip-search'                  => 'Pesquisa {{SITENAME}}',
'tooltip-n-mainpage'              => 'Visitar la página principal',
'tooltip-n-portal'                => 'Sobre l proyecto',
'tooltip-n-currentevents'         => 'Informaçon temática sobre amboras actuales',
'tooltip-n-recentchanges'         => 'Lhista de mudanças recentes nesta wiki.',
'tooltip-n-randompage'            => 'Carregar página aleatória',
'tooltip-n-help'                  => 'Local com informação auxiliar.',
'tooltip-n-sitesupport'           => 'Ayude-nos',
'tooltip-t-whatlinkshere'         => 'Lista de todas las páginas que se lhigam a yesta',
'tooltip-t-contributions'         => 'Ber las contribuiçons de yeste outelizador',
'tooltip-t-emailuser'             => 'Enbiar um e-mail a yeste outelizador',
'tooltip-t-upload'                => 'Carregar imagens ou ficheiros',
'tooltip-t-specialpages'          => 'Lista de páginas especiais',
'tooltip-ca-nstab-user'           => 'Ber a página de l utilizador',
'tooltip-ca-nstab-project'        => 'Ber la página de l proyecto',
'tooltip-ca-nstab-image'          => 'Ber la página de l ficheiro',
'tooltip-ca-nstab-template'       => 'Ber l modelo',
'tooltip-ca-nstab-category'       => 'Ber la página da categoria',
'tooltip-minoredit'               => 'Marcar como ediçon menor',
'tooltip-save'                    => 'Grabar sues alterações',
'tooltip-preview'                 => 'Prever as alterações, por favor utilizar antes de gravar!',
'tooltip-diff'                    => 'Mostrar alterações que fez a este texto.',
'tooltip-compareselectedversions' => 'Ber las diferenças antre las dues versons seleccionadas desta página.',
'tooltip-watch'                   => 'Acrescentar yesta página a la sue lhista de artigos vigiados',

# Browsing diffs
'previousdiff' => '← Ber la alteraçom anterior',
'nextdiff'     => 'Ber la alteraçon posterior →',

# Media information
'file-info-size'       => '($1 × $2 pixel, tamanho: $3, tipo MIME: $4)',
'file-nohires'         => '<small>Sem resolução maior disponível.</small>',
'svg-long-desc'        => '(ficheiro SVG, de $1 × $2 pixels, tamanho: $3)',
'show-big-image'       => 'Resoluçon completa',
'show-big-image-thumb' => '<small>Tamanho desta previsão: $1 × $2 pixels</small>',

# Special:Newimages
'newimages' => 'Galeria de nuobos ficheiros',

# Bad image list
'bad_image_list' => 'O formato é o seguinte:

Apenas são considerados itens de lista (linhas começadas por *). O primeiro link numa linha deve ser um link para uma "bad image".
Links subsequentes na mesma linha são considerados excepções, i.e. artigos onde a imagem pode ocorrer "inline".',

# Metadata
'metadata'          => 'Metadados',
'metadata-help'     => 'Yeste ficheiro contém anformaçon adicional, probablemente adicionada a partir de la câmara digital o de l scanner usado para lo criar.
Caso l ficheiro tenha sido modificado a partir de l sue stado original, alguns detalhes poderon num reflectir completamente las mudanças efectuadas.',
'metadata-expand'   => 'Mostrar restantes detalhes',
'metadata-collapse' => 'Esconder detalhes restantes',
'metadata-fields'   => 'Os campos de metadados EXIF listados nesta mensagem poderão estar presente na exibição da página de imagem quando a tabela de metadados estiver no modo "expandida". Outros poderão estar escondidos por padrão.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'Editar yeste ficheiro outelizando ua aplicaçon externa',
'edit-externally-help' => 'Consulte as [http://meta.wikimedia.org/wiki/Help:External_editors instruções de instalação] para mais informação.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'todas',
'namespacesall' => 'todas',
'monthsall'     => 'todos',

# Watchlist editing tools
'watchlisttools-view' => 'Ber alteraçons amportantes',
'watchlisttools-raw'  => 'Ediçon bruta da lhista de ls bigiados',

);
