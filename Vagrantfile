# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure('2') do |config|

  # Box
  config.vm.box = 'precise64'
  config.vm.box_url = 'http://files.vagrantup.com/precise64.box'

  # Shared folders
  config.vm.synced_folder '.', '/srv'

  # Prerequisites
  config.vm.provision :shell, :inline => 'apt-get update --fix-missing'
  config.vm.provision :shell, :inline => 'apt-get install -q -y python-software-properties python g++ make git curl'

  # PHP 5.3
  config.vm.define :v53 do |box|
    box.vm.hostname = 'graze-event-v53'
    provision_php box
    box.vm.provider :virtualbox do |vb|
      vb.customize ['modifyvm', :id, '--name', 'graze.event.v53']
    end
  end

  # PHP 5.4
  config.vm.define :v54 do |box|
    box.vm.hostname = 'graze-event-v54'
    box.vm.provision :shell, :inline => 'add-apt-repository ppa:ondrej/php5 && apt-get update'
    provision_php box
    box.vm.provider :virtualbox do |vb|
      vb.customize ['modifyvm', :id, '--name', 'graze.event.v54']
    end
  end

  # Setup
  def provision_php(config)
    config.vm.provision :shell, :inline => 'apt-get install -q -y php5-cli php5-xdebug'
    config.vm.provision :shell, :inline => 'curl -s https://getcomposer.org/installer | php'
    config.vm.provision :shell, :inline => 'mv ./composer.phar /usr/local/bin/composer'
  end

end
