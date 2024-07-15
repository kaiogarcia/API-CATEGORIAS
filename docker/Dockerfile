# Define a versão do PHP como argumento
ARG PHP_VERSION

# Usa a imagem base do PHP com a versão especificada
FROM php:${PHP_VERSION}

# Define o diretório da aplicação como argumento
ARG APP_DIR=/var/www/app

# Define a versão da biblioteca Redis para PHP como argumento
ARG REDIS_LIB_VERSION=5.3.7

# Instala apt-utils e supervisor
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    apt-utils \
    supervisor

# Instala dependências comuns para desenvolvimento PHP
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpng-dev \
    libpq-dev \
    libxml2-dev

# Instala extensões PHP necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql pgsql session xml zip iconv simplexml pcntl gd fileinfo

# Instala e habilita extensão Redis para PHP
RUN pecl install redis-${REDIS_LIB_VERSION} \
    && docker-php-ext-enable redis

# Instala o Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia arquivos de configuração do Supervisor
COPY ./docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/supervisord/conf /etc/supervisord.d/

# Copia arquivos de configuração adicional do PHP
COPY ./docker/php/extra-php.ini "$PHP_INI_DIR/conf.d/99_extra.ini"
COPY ./docker/php/extra-php-fpm.conf /etc/php/${PHP_VERSION}/php-fpm.d/www.conf

# Define o diretório de trabalho como APP_DIR
WORKDIR $APP_DIR

# Copia a aplicação para o diretório de trabalho
COPY --chown=www-data:www-data ./app .

# Define as permissões corretas para o diretório da aplicação
RUN chown -R www-data:www-data $APP_DIR

# Remove a pasta vendor se existir (limpeza anterior)
RUN rm -rf vendor

# Instala as dependências do Composer sem interação
RUN composer install --no-interaction || true

# Instala o Nginx e configurações
RUN apt-get install nginx -y
RUN rm -rf /etc/nginx/sites-enabled/* && rm -rf /etc/nginx/sites-available/*
COPY ./docker/nginx/sites.conf /etc/nginx/sites-enabled/default.conf
COPY ./docker/nginx/error.html /var/www/html/error.html

# Limpa o cache do apt e remove listas de pacotes
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Define o comando padrão para iniciar o Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
