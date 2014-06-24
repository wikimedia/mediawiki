module URL
  def self.url(name)
    if ENV["MEDIAWIKI_URL"]
      mediawiki_url = ENV["MEDIAWIKI_URL"]
    else
      mediawiki_url = "http://127.0.0.1:80/w/index.php"
    end
    "#{mediawiki_url}#{name}"
  end
end
