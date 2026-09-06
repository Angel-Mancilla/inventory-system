# PHP 8.4 es lo óptimo para Laravel 13
FROM php:8.4-cli

# 1. Dependencias del sistema + Chromium para Browsershot (PDFs de tickets/reportes)
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libicu-dev libzip-dev \
    chromium \
    fonts-liberation \
    libatk-bridge2.0-0 \
    libatk1.0-0 \
    libcups2 \
    libdbus-1-3 \
    libdrm2 \
    libgbm1 \
    libgtk-3-0 \
    libnss3 \
    libxcomposite1 \
    libxdamage1 \
    libxrandr2 \
    && docker-php-ext-install pdo pdo_mysql mbstring intl zip gd bcmath \
    && rm -rf /var/lib/apt/lists/*

# 2. Node.js 22 + pnpm (Vue 3 / Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g pnpm

# 3. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Crear el usuario real que faltaba — esto es lo que se había perdido.
#    Sin esto, HOME=/home/appuser apunta a una carpeta que no existe.
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} appuser \
    && useradd -u ${UID} -g ${GID} -m -d /home/appuser -s /bin/bash appuser

ENV HOME=/home/appuser
ENV NPM_CONFIG_CACHE=/home/appuser/.npm
ENV COMPOSER_HOME=/home/appuser/.composer
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

RUN mkdir -p /home/appuser/.npm /home/appuser/.config/pnpm /home/appuser/.composer \
    && chown -R appuser:appuser /home/appuser

WORKDIR /app
RUN chown appuser:appuser /app

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

CMD ["entrypoint.sh"]

# # PHP 8.4 es lo óptimo para Laravel 13
# FROM php:8.4-cli

# # 1. Dependencias del sistema + Chromium para Browsershot (PDFs de tickets/reportes)
# RUN apt-get update && apt-get install -y \
#     git curl zip unzip libpng-dev libonig-dev libxml2-dev libicu-dev libzip-dev \
#     chromium \
#     fonts-liberation \
#     libatk-bridge2.0-0 \
#     libatk1.0-0 \
#     libcups2 \
#     libdbus-1-3 \
#     libdrm2 \
#     libgbm1 \
#     libgtk-3-0 \
#     libnss3 \
#     libxcomposite1 \
#     libxdamage1 \
#     libxrandr2 \
#     && docker-php-ext-install pdo pdo_mysql mbstring intl zip gd bcmath \
#     && rm -rf /var/lib/apt/lists/*

# # 2. Node.js 22 + pnpm (Vue 3 / Vite)
# RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
#     && apt-get install -y nodejs \
#     && npm install -g pnpm

# # 3. Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Fix de HOME y permisos para pnpm / Puppeteer-core (Browsershot)
# ENV HOME=/home/appuser
# ENV NPM_CONFIG_CACHE=/home/appuser/.npm
# ENV COMPOSER_HOME=/home/appuser/.composer
# ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
# ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium
# # ENV HOME=/root
# # ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
# # ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium
# RUN mkdir -p /root/.config/pnpm \
#     && chmod -R 777 /root/.config

# WORKDIR /app

# COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# RUN chmod +x /usr/local/bin/entrypoint.sh

# CMD ["entrypoint.sh"]
















# # Usamos PHP 8.4 que es lo óptimo para Laravel 13
# FROM php:8.4-cli

# # 1. Instalar dependencias del sistema
# RUN apt-get update && apt-get install -y \
#     git curl zip unzip libpng-dev libonig-dev libxml2-dev libicu-dev libzip-dev \
#     && docker-php-ext-install pdo pdo_mysql mbstring intl zip gd

# # 2. Instalar Node.js moderno (Versión 22) para Vue 3, Vite y Shadcn
# RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
#     && apt-get install -y nodejs

# # 3. Instalar Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Configuramos el directorio de trabajo (que mapearemos con ./src)
# WORKDIR /app

# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]