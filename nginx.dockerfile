FROM nginx:stable-alpine

WORKDIR /var/www
COPY src/ /var/www

COPY nginx/nginx-selfsigned.crt /etc/ssl/nginx-selfsigned.crt
COPY nginx/nginx-selfsigned.key /etc/ssl/nginx-selfsigned.key