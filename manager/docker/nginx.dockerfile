FROM nginx:1.18.0-alpine

COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /app