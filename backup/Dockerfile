# Этап 1: Источник с MySQL 5.5
FROM mysql:5.5 AS mysql_source

# Этап 2: База Ubuntu
FROM ubuntu:22.04

# Отключаем интерактивные диалоги apt
ENV DEBIAN_FRONTEND=noninteractive

# Установка необходимых утилит и библиотек
RUN apt-get update && apt-get install -y \
    bash \
    findutils \
    curl \
    libaio1 \
    libncurses5 \
    && rm -rf /var/lib/apt/lists/*

# Копируем оригинальные утилиты по их реальному расположению в mysql:5.5
COPY --from=mysql_source /usr/bin/mysqldump /usr/bin/mysqldump
COPY --from=mysql_source /usr/bin/mysql /usr/bin/mysql

# Скачиваем Supercronic
ENV SUPERCRONIC_URL=https://github.com \
    SUPERCRONIC_SHA1SUM=5bcefed628e32adc08e32634db2d10e9230dbca0 \
    SUPERCRONIC=supercronic-linux-amd64

RUN curl -fsSLO "$SUPERCRONIC_URL" \
 && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
 && chmod +x "$SUPERCRONIC" \
 && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
 && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

# Копируем скрипты и crontab
COPY backup.sh prune.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/backup.sh /usr/local/bin/prune.sh
COPY crontab /etc/crontab

# Создаём директорию для логов
RUN mkdir -p /var/log

CMD ["supercronic", "/etc/crontab"]

