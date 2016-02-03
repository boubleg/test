#!/usr/bin/env bash

sudo aptitude update

# install mysql and make it available from outside of vagrant
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
sudo aptitude install -y vim curl python-software-properties
sudo aptitude update
sudo aptitude -y install mysql-server
sed -i "s/^bind-address/#bind-address/" /etc/mysql/my.cnf
mysql -u root -proot -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root' WITH GRANT OPTION; FLUSH PRIVILEGES;"
sudo /etc/init.d/mysql restart


# add php7 repo
sudo LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php -y
sudo aptitude update

# install reamining packages
sudo aptitude install php7.0 php7.0-fpm php7.0-mysql mysql-server -y

# import db
mysql -h127.0.0.1 -u root -proot < /vagrant/vagrant/test-dump.sql