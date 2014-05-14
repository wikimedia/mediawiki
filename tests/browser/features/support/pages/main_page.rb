class MainPage
  include PageObject

  include URL
  page_url URL.url("Main_Page")

  div(:page_content, id: "content")
  a(:view_history_link, href: /action=history/)
  a(:edit_link, href: /action=edit/)

end