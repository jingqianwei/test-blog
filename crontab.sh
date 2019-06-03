#!/bin/bash
source /etc/profile
source ~/.bashrc
step=1 #间隔的秒数

for (( i = 0; i < 60; i=(i + step) )); do
    echo $(date "+%Y-%m-%d %H:%M:%S")
    /usr/local/php/bin/php /home/work/laravel57/artisan schedule:run
    sleep $step
done

exit 0
