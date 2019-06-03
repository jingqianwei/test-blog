#!/bin/sh
source /etc/profile
source ~/.bashrc
cd /root/
envoy run deploy  # 运行 laravel envoy 自动pull代码(Envoy.blade.php)
