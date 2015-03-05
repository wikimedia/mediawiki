module URL
  def self.url(name)
    if ENV["MEDIAWIKI_URL"]
      mediawiki_url = ENV["MEDIAWIKI_URL"]
    else
      mediawiki_url = "http://127.0.0.1:8080/wiki/"
    end
    "#{mediawiki_url}#{name}"
  end
end
