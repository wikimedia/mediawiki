class ZtargetPage < MainPage
  include URL
  page_url URL.url("<%=params[:article_name]%>?vehidebetadialog=true&veaction=edit")
  include PageObject

  a(:main_page_link, text: "link to the Main Page")
end