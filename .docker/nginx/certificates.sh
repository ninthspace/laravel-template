#!/usr/bin/env bash
# on host:
# a) cd to this directory on
# b) ./certificates.sh
# c) then do the docker things
# d) install as per http://www.robpeck.com/2010/10/google-chrome-mac-os-x-and-self-signed-ssl-certificates/

# @author Adriano Rosa (http://adrianorosa.com)
# @date: 2014-05-13 09:43
#
# Bash Script to create a new self-signed SSL Certificate
# At the end of creating a new Certificate this script will output a few lines
# to be copied and placed into NGINX site conf
#
# USAGE: this command will ask for the certificate name and number in days it will expire
# $ mkselfssl
#
# OPTIONAL: run the command straightforward
# $ mkselfssl mycertname 365

# Default dir to place the Certificate
DIR_SSL_CERT="etc/nginx/ssl/cert"
DIR_SSL_KEY="etc/nginx/ssl/private"

SSLNAME=$1
SSLDAYS=$2

if [ -z $1 ]; then
  printf "Enter the Domain Name:"
  read SSLNAME
fi

if [ -z $2 ]; then
  printf "How many days the Certificate will be valid:"
  read SSLDAYS
fi

if [[ $SSLDAYS == "" ]]; then
  $SSLDAYS = 365
fi

echo "Creating a new Certificate ..."

# required to keep Chrome happy
sh <(cat /System/Library/OpenSSL/openssl.cnf <(printf "\n[SAN]\nsubjectAltName=DNS:$SSLNAME") > config.cnf)

openssl req \
    -newkey rsa:2048 \
    -x509 \
    -nodes \
    -keyout $SSLNAME.key \
    -new \
    -out $SSLNAME.crt \
    -subj /CN=$SSLNAME \
    -reqexts SAN \
    -extensions SAN \
    -config config.cnf \
    -sha256 \
    -days $SSLDAYS

# Make directory to place SSL Certificate if it doesn't exists
if [[ ! -d $DIR_SSL_KEY ]]; then
  mkdir -p $DIR_SSL_KEY
fi

if [[ ! -d $DIR_SSL_CERT ]]; then
  mkdir -p $DIR_SSL_CERT
fi

# Place SSL Certificate within defined path
cp $SSLNAME.key $DIR_SSL_KEY/$SSLNAME.key
cp $SSLNAME.crt $DIR_SSL_CERT/$SSLNAME.crt

# Print output for Nginx site config
printf "+-------------------------------
+ SSL Certificate has been created.
+ Here is the NGINX Config for $SSLNAME
+ Copy it into your nginx config file
+-------------------------------\n\n
ssl_certificate      /$DIR_SSL_CERT/$SSLNAME.crt;
ssl_certificate_key  /$DIR_SSL_KEY/$SSLNAME.key;

ssl_session_cache shared:SSL:1m;
ssl_session_timeout  5m;\n\n"