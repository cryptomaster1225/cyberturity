FROM nginx:latest

COPY default.conf /etc/nginx/conf.d/
COPY cyberturity.local.crt /etc/nginx
COPY cyberturity.local.key /etc/nginx
COPY ssl-params.conf /etc/nginx
COPY dhparam.pem /etc/nginx
