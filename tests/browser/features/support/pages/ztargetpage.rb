class ZtargetPage < MainPage
  include URL
  page_url URL.url("<%=params[:article_name]%>")
  include PageObject

  a(:link_target_page_link, text: "link to the test target page")
end