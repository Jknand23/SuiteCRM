#!/bin/bash
# Helper script to execute MySQL commands without password warnings
# Usage: ./mysql-exec.sh "SQL COMMAND"

docker exec -i suitecrm_mysql mysql --defaults-file=/etc/mysql/conf.d/.my.cnf suitecrm "$@" 