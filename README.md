## How to use

This system requires "phinx" as database migration tool, [Please install "composer"](http://qiita.com/CatCable/items/02364adacf36410f449e)

### Mac OSX

```bash
# clone this
git clone {this repository}
cd {cloned directory}
 
# If you have not start VM yet, you should start it.
docker-machine start {VM name}
eval "$(docker-machine env default)"
 
# run docker containers
cp docker-compose.example.yml docker-compose.yml
docker-compose build
docker-compose up -d
 
# migrate database
cd migration
composer install
./vendor/bin/phinx init 
/bin/cp -f phinx.example.yml phinx.yml
./vendor/bin/phinx migrate -e development
 
# test request by curl
curl -i http://`docker-machine ip | tr -d '\n'`
 
# login mysql as root
mysql -h `docker-machine ip | tr -d '\n'` -uroot -ppass
```

### Amazon Linux

```bash
# install composer
curl -sS https://getcomposer.org/installer | php
chmod +x composer.phar
sudo mv composer.phar /usr/local/bin/composer
 
# install docker
sudo yum install docker -y
sudo service docker start
sudo pip install -U docker-compose
sudo usermod -aG docker ${USER}
 
# setup project
git clone {this repository}
cd dc-lamp/
cp docker-compose.example.yml docker-compose.yml
sudo docker-compose build
sudo docker-compose up -d
cd migration
composer install
./vendor/bin/phinx init
/bin/cp -f phinx.example.yml phinx.yml
./vendor/bin/phinx migrate -e testing
 
# test request by curl
curl -i http://localhost
 
# login mysql as root
mysql -h 127.0.0.1 -uroot -ppass
```
