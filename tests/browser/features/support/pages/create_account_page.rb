class CreateAccountPage
  include PageObject

  page_url '<%=params[:page_title]%>'

  button(:create_account, id: 'wpCreateaccount')
  element(:error_message, css: 'div#userloginForm div.error')
end
