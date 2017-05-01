class CreateAccountPage
  include PageObject

  page_url '<%=params[:page_title]%>'

  button(:create_account, id: 'wpCreateaccount')
  element(:error_message, css: 'input#wpName2:required:invalid')
end
