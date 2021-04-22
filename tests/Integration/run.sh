#!/usr/bin/env bash

APP_NAME=lifeline

APP_INTEGRATION_DIR=$PWD
ROOT_DIR=${APP_INTEGRATION_DIR}/../../../..
composer install

cp -R ./app ../../../lifelineintegrationtesting

#php -S localhost:8080 -t ${ROOT_DIR} &
#PHPPID=$!
#echo $PHPPID

${ROOT_DIR}/occ app:enable $APP_NAME
${ROOT_DIR}/occ app:enable lifelineintegrationtesting
${ROOT_DIR}/occ app:list | grep $APP_NAME
${ROOT_DIR}/occ app:list | grep lifelineintegrationtesting

export TEST_SERVER_URL="http://localhost:8080/"
${APP_INTEGRATION_DIR}/vendor/bin/behat -f junit -f pretty $1 $2
RESULT=$?

#kill $PHPPID

${ROOT_DIR}/occ app:disable lifelineintegrationtesting
rm -rf ../../../lifelineintegrationtesting

exit $RESULT
