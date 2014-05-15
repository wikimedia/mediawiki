class MainPage
  include PageObject

  include URL
  page_url URL.url("Main_Page")

  a(:edit_link, href: /action=edit/)
  a(:help_link, href: "https://www.mediawiki.org/wiki/Special:MyLanguage/Help:Contents")
  h1(:main_page_title, text: /Main Page/)
  div(:page_content, id: "content")
  a(:page_information_link, href: /action=info/)
  a(:permanent_link_link, href: /oldid/, index: 1)
  a(:printable_version_link, href: /printable=yes/)
  a(:random_page_link, href: /Special:Random/)
  a(:recent_changes_link, href: /Special:RecentChanges/)
  a(:related_changes_link, href: /Special:RecentChangesLinked/)
  a(:special_pages_link, href: /Special:SpecialPages/)
  a(:view_history_link, href: /action=history/)
  a(:what_links_here_link, href: /Special:WhatLinksHere/)

end