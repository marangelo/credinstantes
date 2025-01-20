# Imagen base
FROM kooldev/php:7.4-nginx-sqlsrv-prod

# Argumentos y variables de entorno
ARG ARG_APP_NAME=credinstantes

ENV APP_NAME=${ARG_APP_NAME} \
    PHP_FPM_LISTEN=/run/php-fpm.sock \
    NGINX_LISTEN=80 \
    NGINX_ROOT=/app/${ARG_APP_NAME}/public \
    NGINX_INDEX=index.php \
    NGINX_CLIENT_MAX_BODY_SIZE=25M \
    NGINX_PHP_FPM=unix:/run/php-fpm.sock \
    NGINX_FASTCGI_READ_TIMEOUT=60s \
    NGINX_FASTCGI_BUFFERS='8 8k' \
    NGINX_FASTCGI_BUFFER_SIZE='16k'

# Establece el directorio de trabajo
WORKDIR /app/${ARG_APP_NAME}

# Copia la plantilla de configuración de Nginx proporcionada
COPY default.tmpl /kool/default.tmpl

# Copia el resto del proyecto, excepto lo indicado en .dockerignore
COPY . .

# Instala las dependencias de Composer en modo de producción
RUN composer install --ignore-platform-reqs


RUN chown -R kool:kool /app/${ARG_APP_NAME} \
    && chmod -R 775 /app/${ARG_APP_NAME}/storage /app/${ARG_APP_NAME}/bootstrap/cache

