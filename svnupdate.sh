#!/bin/bash

printf "updating trunk ...\n"
svn update --accept postpone --ignore-externals .
printf "installing assets ...\n"
app/console assets:install
printf "dumping assets to cloudfront ...\n"
app/console buggl:assets:awsdump
printf "clearing symfony prod cache ...\n"
app/console cache:clear -e prod
printf "process done!\n"
