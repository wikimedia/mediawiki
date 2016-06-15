class CreateAccountPage
  include PageObject

  page_url '<%=params[:page_title]%>'

  button(:create_account, id: 'wpCreateaccount')
  div(:error_message, css: 'form#userlogin2 div.error')
end
