echo "Provisioning virtual machine..."
sudo apt-get -y update
echo "Installing vim"
sudo apt-get -y install vim
echo "Installing php5"
sudo apt-get install python-software-properties build-essential -y > /dev/null
sudo add-apt-repository ppa:ondrej/php5 -y > /dev/null
sudo apt-get update > /dev/null
sudo apt-get install php5-common php5-dev php5-cli php5-fpm -y > /dev/null
echo "Installing PHP extensions"
sudo apt-get install curl php5-curl php5-gd php5-mcrypt php5-mysql -y > /dev/null

echo "Installing Composer"
curl -sS https://getcomposer.org/installer | php
chmod 777 composer.phar
sudo mv composer.phar /usr/local/bin/composer

echo "Installing Nodejs"
sudo apt-get -y install nodejs > /dev/null
sudo apt-get -y install npm > /dev/null
sudo ln -fs /usr/bin/nodejs /usr/local/bin/node

echo "Installing npm packages"
sudo npm install -g bower > /dev/null
sudo npm install -g gulp > /dev/null

echo "Installing Git"
sudo apt-get install git -y > /dev/null

echo "Installing MySQL"
sudo apt-get install debconf-utils -y > /dev/null

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password root"
    
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password root"

sudo apt-get -y install mysql-server > /dev/null

echo "Setting databases"
mysqladmin -uroot -proot create skills

echo "Installing frontend"
cd /var/www/skeleton/frontend
npm install
bower install -y

echo "Instaling backend"
cd /var/www/skeleton/backend
sed -i 's/Frozennode/\/\/Frozennode/g' /var/www/skeleton/backend/config/app.php
composer install
sed -i 's/\/\/Frozennode/Frozennode/g' /var/www/skeleton/backend/config/app.php
cp .env.example .env
php artisan migrate --force
php artisan vendor:publish
php artisan key:generate
