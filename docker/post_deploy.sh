#!/bin/sh

echo "host port is: $PORT"

sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

cat /etc/apache2/sites-available/app.conf
cat /etc/apache2/ports.conf

a2enmod rewrite

service apache2 restart
