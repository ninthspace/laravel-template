FROM php:7.4-fpm

LABEL maintainer="Chris Aves"

COPY .docker/php/php.ini /usr/local/etc/php/php.ini

COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    apt-get install -yq \
        build-essential \
        apt-utils \
        curl \
        gnupg \
        unzip \
        nano \
        git

RUN rm -rf /var/lib/apt/lists/

# see https://github.com/mlocati/docker-php-extension-installer

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions --cleanup mbstring mysqli gd imagick opcache redis soap xdebug pdo_mysql intl gmp exif bcmath zip pcntl

COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# RUN pecl install -f apc

# NODE and NPM
# install from nodesource using apt-get
# https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-an-ubuntu-14-04-server

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash
RUN apt-get install -yq nodejs

# fix npm - not the latest version installed by apt-get
RUN npm install -g npm

# add user with sudo privileges within Docker container
# without adduser input questions
# http://askubuntu.com/questions/94060/run-adduser-non-interactively/94067#94067
#USER="testuser" && \
#adduser --disabled-password --gecos "" $USER && \
#sudo usermod -a -G sudo $USER && \
#echo "$USER:abc123" | chpasswd && \
#su - $USER # switch to testuser

# install common full-stack JavaScript packages globally
# http://blog.nodejs.org/2011/03/23/npm-1-0-global-vs-local-installation
# sudo npm install -g yo grunt-cli bower express

# add a local user so that we can share with host
# otherwise everything is root
# specifically, the following directories need to be not owned
# by root
# - help-docs
# - node_modules
# - vendor

RUN adduser --disabled-password --gecos '' chris
USER chris
WORKDIR /srv/app
COPY . /srv/app

# after booting
# eval `ssh-agent`
# ssh-add /home/chris/.ssh/id_docker_bitbucket

# put the above in  ~/.bash_aliases, as per
#
# https://stackoverflow.com/questions/18880024/start-ssh-agent-on-login
#
# then it'll remember between boots
#
# still need to do the ssh-add bit


# ENTRYPOINT "eval `ssh-agent`; ssh-add /home/chris/.ssh/id_docker_bitbucket" && /bin/bash

# RUN composer install \
#    --no-interaction \
#    --no-plugins \
#    --no-scripts \
#    --prefer-dist

# RUN npm install
# RUN npm run dev
