# -*- mode: ruby -*-
# vi: set ft=ruby :
require 'digest'
DOMAIN = "local.com"

Vagrant.configure(2) do |config|
  config.vm.provider "virtualbox" do |conf|
    conf.gui = false
    conf.memory = "512"
    conf.cpus = 2
  end
  config.vm.box_check_update = false

  if Vagrant.has_plugin?("vagrant-triggers")
    config.trigger.after [:up, :destroy, :halt] do
      run "vagrantsync"
    end
  end  

  vmname = "intrawiki"
  config.vm.define vmname do |conf|
  config.ssh.username = 'root'
  config.ssh.password = 'vagrant'
  config.ssh.insert_key = 'true'
    #config.ssh.insert_key = false #workaround for https://github.com/mitchellh/vagrant/issues/7610
    conf.vm.synced_folder '.', '/vagrant', disabled: true #enable and tune here, if you want to share some folder with some local folder
    conf.vm.box = "relativkreativ/centos-7-minimal"
    conf.vm.hostname = vmname  + "." + DOMAIN
    start_port = (Digest::SHA256.hexdigest conf.vm.hostname)[1..4].to_i(16)+1000
    subip = (Digest::SHA256.hexdigest conf.vm.hostname)[1..2].to_i(16)
    conf.vm.network "private_network", ip: "192.168.4.#{subip}"
    conf.vm.network "forwarded_port", guest: 80,   host: start_port 
    conf.vm.network "forwarded_port", guest: 3306, host: start_port + 1
    if Vagrant.has_plugin?("vagrant-hosts")
      conf.vm.provision :hosts, :sync_hosts => true    
    end  
    conf.vm.provision "ansible" do |ansible|
      ansible.verbose = "vvv"
        ansible.groups = {
            vmname  => [vmname],
            "all" => [],
            "all:vars" => [
                "domain =  '#{DOMAIN}'",
                "debug = 1",
                "site_name  = '#{conf.vm.hostname}'",
                "wikiadminpass  = 'Wiki1729Admin'",
            ]
        }
      ansible.playbook = "intrawiki.yml"
    end  
    conf.vm.provider :virtualbox do |vb|
      vb.customize ["modifyvm", :id, "--memory", "2048"]
      vb.customize ["modifyvm", :id, "--cpus", "2"]      
    end  
  end
end
