#!/usr/bin/env bash


if [[ ! -f './composer.json' ]] || ! grep -q '"jahudka/nittro-training"' './composer.json'; then
    echo "Run this script from the root directory of the project"
    exit 1
fi


echo "Do you use MySQL or Postgres?"

DB_TYPE=""
while [[ "$DB_TYPE" != "p" && "$DB_TYPE" != "m" ]]; do
    echo -n "Type 'm' for MySQL or 'p' for Postgres: "
    read -r -s -n 1 DB_TYPE
    echo ""
done

if [[ "$DB_TYPE" = "p" ]]; then DB_DRIVER="pgsql"; else DB_DRIVER="mysql"; fi

echo "Thank you. Now I'll need some details to connect to the database."
echo -n "Host (default is 'localhost'): "
read -r DB_HOST
echo -n "Port (if a non-default port is used): "
read -r DB_PORT
echo -n "User name: "
read -r DB_USER
echo -n "Password (output is silenced): "
read -r -s DB_PASSWORD
echo ""
echo -n "Database name: "
read -r DB_NAME

if [[ "$DB_HOST" = "" ]]; then
    DB_HOST="localhost"
fi

echo "Checking database connection..."

php <<- EOT
	<?php

	\$dsn = '$DB_DRIVER:host=$DB_HOST;dbname=$DB_NAME';

	if ('$DB_PORT') {
	    \$dsn .= ';port=$DB_PORT';
	}

	try {
	    \$conn = new PDO(\$dsn, '$DB_USER', '$DB_PASSWORD');
	    \$stmt = \$conn->query('SELECT 1');
	    \$res = \$stmt->fetch(PDO::FETCH_NUM);
	    exit(\$res === [1] ? 0 : 1);
	} catch (\Throwable \$e) {
	    echo \$e->getMessage() . "\n";
	    exit(1);
	}
EOT

[[ "$?" != "0" ]] && echo "Database connection test failed." && exit 1
echo "Database connection test successful."

CLEANUP_MSG=" You can now safely remove the './install' directory."

if [[ "$DB_TYPE" = "p" ]]; then
    if command -v 'psql' > /dev/null; then
        echo "Importing database structure and data..."
        PGPASSWORD="$DB_PASSWORD" psql -h "$DB_HOST" -U "$DB_USER" -q "$DB_NAME" < "install/pg.sql"
        [[ "$?" != "0" ]] && echo "Database import failed." && exit 1
        echo "Database import successful."
    else
        echo "Command-line Postgres client 'psql' not found, you'll need to manually import the file 'install/pg.sql'."
        CLEANUP_MSG=""
    fi
else
    if command -v mysql > /dev/null; then
        echo "Importing database structure and data..."
        MYSQL_PWD="$DB_PASSWORD" mysql -h "$DB_HOST" -U "$DB_USER" "$DB_NAME" < "install/my.sql"
        [[ "$?" != "0" ]] && echo "Database import failed." && exit 1
        echo "Database import successful."
    else
        echo "Command-line MySQL client 'mysql' not found, you'll need to manually import the file 'install/my.sql'."
        CLEANUP_MSG=""
    fi
fi

echo "Creating required directory structure..."
mkdir -p -m 0775 var/{cache,log,mail,sessions} public/images

echo "Creating config files..."
cp "install/contacts.json.dist" "etc/contacts.json"

cat > "etc/config.local.neon" <<- EOT
	doctrine:
	    driver: pdo_$DB_DRIVER
	    host: $DB_HOST
	    port: $DB_PORT
	    user: $DB_USER
	    password: '$DB_PASSWORD'
	    dbname: $DB_NAME

EOT

echo "Moving example assets into place..."
cp install/images/* public/images/

if command -v composer > /dev/null; then
    echo "Running composer..."
    composer install --no-suggest
    [[ "$?" = "0" ]] && echo $'\n\n'"All finished.$CLEANUP_MSG Have fun!"$'\n'
else
    echo $'\n\n'"Okay, everything should be ready, but I can't find the Composer executable,"
    echo "so you'll need to run 'composer install' yourself.$CLEANUP_MSG Have fun!"$'\n'
fi

