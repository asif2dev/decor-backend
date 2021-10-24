#!/bin/sh

RUN sed -i "s/80/$PORT/g" /etc/apache2/sites-available/app.conf /etc/apache2/ports.conf
RUN a2enmod rewrite
RUN service apache2 restart
