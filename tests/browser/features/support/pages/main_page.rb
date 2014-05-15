class MainPage
  include PageObject

  include URL
  page_url URL.url("")

  a(:edit_link, href: /action=edit/)
  li(:help_link, id: "n-help")
  h1(:main_page_title, text: /Main Page/)
  div(:page_content, id: "content")
  li(:page_information_link, id: "t-info")
  li(:permanent_link_link, id: "t-permalink")
  a(:printable_version_link, href: /printable=yes/)
  li(:random_page_link, id: "n-randompage")
  li(:recent_changes_link, id: "n-recentchanges")
  li(:related_changes_link, id: "t-recentchangeslinked")
  li(:special_pages_link, id: "t-specialpages")
  a(:view_history_link, href: /action=history/)
  li(:what_links_here_link, id: "t-whatlinkshere")

end