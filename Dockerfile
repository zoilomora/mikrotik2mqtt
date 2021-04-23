FROM php:8.0-cli-alpine AS build
RUN apk update && \
    apk add --no-cache \
        libzip-dev \
        openssl-dev && \
    docker-php-ext-install -j$(nproc) \
        zip \
        sockets && \
    adduser -D -g '' mikrotik2mqtt
ENV PATH /var/www/html/bin:/var/www/html/vendor/bin:$PATH
WORKDIR /var/www/html
USER mikrotik2mqtt

FROM build AS compilation
USER root
ENV APP_ENV prod
COPY . .
RUN composer install --no-dev -o && \
    rm .dockerignore && \
    chown -R mikrotik2mqtt:mikrotik2mqtt .

FROM build AS production
ENV APP_ENV prod
COPY --from=compilation /var/www/html .
USER mikrotik2mqtt
CMD sh -c "console app:run"
