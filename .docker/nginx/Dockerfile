FROM nginx:1.15.3
LABEL maintainer="Chris Aves"
RUN rm /etc/nginx/conf.d/default.conf
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/conf.d/*.conf /etc/nginx/conf.d/
# COPY . /srv/app/
