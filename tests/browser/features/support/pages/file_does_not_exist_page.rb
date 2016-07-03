class FileDoesNotExistPage
  include PageObject

  page_url 'File:<%=params[:page_name]%>'

  div(:file_does_not_exist_message, id: 'mw-imagepage-nofile')
end
