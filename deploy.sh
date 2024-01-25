#!/bin/bash
# located in /var/builds/$3/deploy.sh
# deploy.sh build-$BITBUCKET_COMMIT build-$BITBUCKET_COMMIT.zip $BUILD_PATH $BITBUCKET_BRANCH'
# $1 commit to unzip
# $2 zip file to unzip
# $3 Path to unzip to
# $4 branch path

echo "Unzipping build $2"
echo "Deploying for $4"
unzip -q -o -d /var/builds/$3/$1/ /var/builds/$3/$2 || { echo 'unzip failed on build' ; exit 1; }

ln -sf /var/builds/$3/$1 /var/www/$3

# Link .env to www folder
ln -sf /var/builds/$3/.env /var/www/$3/.env
ln -sf /var/builds/$3/storage/app/public /var/www/$3/storage/app/
chown -R www-data:www-data /var/builds/$3
chmod -R 777 /var/builds/$3/$2/storage /var/builds/$3/$2/bootstrap/cache

# Update SQL
php /var/www/$3/artisan migrate --no-integration --force || { echo 'failed to update SQL' ; exit 1; }

# remove zip file
rm /var/builds/$3/$2
