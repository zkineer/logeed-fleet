FROM php:8.3-fpm-bullseye

ENV DEBIAN_FRONTEND=noninteractive

# Dependencias básicas
RUN apt-get update && apt-get install -y \
    gnupg2 \
    curl \
    apt-transport-https \
    ca-certificates \
    unixodbc \
    unixodbc-dev \
    libgssapi-krb5-2

# Crear carpeta para keyrings
RUN mkdir -p /etc/apt/keyrings && chmod 755 /etc/apt/keyrings

# Descargar y agregar la llave de Microsoft
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc \
    | gpg --dearmor > /etc/apt/keyrings/microsoft-prod.gpg

# Agregar repositorio de Microsoft para Debian 11
RUN echo "deb [signed-by=/etc/apt/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/11/prod bullseye main" \
    > /etc/apt/sources.list.d/mssql-release.list

# Instalar ODBC y herramientas
RUN apt-get update && ACCEPT_EULA=Y apt-get install -y \
    msodbcsql18 \
    mssql-tools18

# Instalar extensiones PHP
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Limpieza
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

