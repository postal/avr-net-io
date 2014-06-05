set   :application,   "Raspberry Pi"
set   :deploy_to,     "/var/www/raspberry-pi"
set   :domain,        "192.168.178.25"

set   :scm,           :git
set   :repository,    "git@github.com:postal/avr-net-io.git"

role  :web,           domain
role  :app,           domain, :primary => true

set   :use_sudo,      true
set   :keep_releases, 3
set :use_composer, true
set :update_vendors, false
set :shared_files,      ["app/config/parameters.yml"]
set :writable_dirs,       ["app/cache", "app/logs", "web/bundles"]
set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true
#set   :deploy_via,    :copy
#ssh_options[:port] = "444"
