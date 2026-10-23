#!/bin/bash
set -euo pipefail

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/backups"
DB_HOST="${DB_HOST:-db}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASSWORD}"
DB_NAME="${DB_NAME:---all-databases}"

mkdir -p "$BACKUP_DIR"
DUMP_FILE="$BACKUP_DIR/${DB_NAME// /_}_${TIMESTAMP}.sql"

if [ "$DB_NAME" = "--all-databases" ]; then
	  mysqldump --skip-generator  -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" \
		      --single-transaction  --all-databases   \
		          > "$DUMP_FILE"
else
        mysqldump --skip-generator  -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" \
				          --single-transaction  "$DB_NAME" \
						      > "$DUMP_FILE"
fi

gzip "$DUMP_FILE"
echo "Backup created: ${DUMP_FILE}.gz"
