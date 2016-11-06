# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT
        mysql -u root -proot < /var/www/database.sql
        mysql -u root -proot < /var/www/dummy.sql
SCRIPT

Vagrant.configure("2") do |config|

	config.vm.box = "scotch/box"
        config.vm.network "private_network", ip: "192.168.33.10"
	config.vm.hostname = "scotchbox"
	config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]
		    
	# Optional NFS. Make sure to remove other synced_folder line too
	#config.vm.synced_folder ".", "/var/www", :nfs => { :mount_options => ["dmode=777","fmode=666"] }

	config.vm.provision "shell",
        inline: $script

    config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"
end
