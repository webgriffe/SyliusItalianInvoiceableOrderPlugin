FROM nyholm/roave-bc-check-ga:latest

RUN docker-php-ext-install -j$(nproc) exif

RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev libxml2-dev && \
  docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j$(nproc) gd && \
  docker-php-ext-install -j$(nproc) soap && \
  apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev libxml2-dev
