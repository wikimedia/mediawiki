class CreateAccountPage
  include PageObject

  page_url '<%=params[:page_title]%>'

  button(:create_account, id: 'wpCreateaccount')
  div(:error_message, id: 'mw-createacct-status-area')
end
