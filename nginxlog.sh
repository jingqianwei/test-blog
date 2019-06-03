#!/bin/sh

#获取nigin日志

log_path='/data/wwwlogs/blog.ynolo.top_nginx.log'

cat $log_path | while read line
do
 if [ -n "$line" ]
 then
 ip=${line%%>>>>*}
 #echo $ip
 leavestr=${line#*>>>>}

 time=${leavestr% +0800>>>>*}

 day=${time%%/*}
 time=${time#*/}

 month=${time%%/*}
 time=${time#*/}

 year=${time%%:*}
 time=${time#*:}

 time=${month}' '${day},' '${year}' '${time}
 time=`date -d "$time" "+%Y-%m-%d %H:%M:%S"`
 #echo $time
 leavestr=${leavestr#*>>>>}

 method=${leavestr% /*}
 #echo $method
 leavestr=${leavestr#*' '}

 uri=${leavestr%% *}
 #echo $uri
 leavestr=${leavestr#*' '}

 http=${leavestr%%>>>>*}
 #echo $http
 leavestr=${leavestr#*>>>>}

 code=${leavestr%%>>>>*}
 #echo $code
 leavestr=${leavestr#*>>>>}

 datasize=${leavestr%%>>>>*}
 #echo $datasize
 leavestr=${leavestr#*>>>>}

 url=${leavestr%%>>>>*}
 #echo $url
 leavestr=${leavestr#*>>>>}

 head=${leavestr%%>>>>*}
 #echo $head
 leavestr=${leavestr#*>>>>}

 postdata=${leavestr%%>>>>*}
 #echo $leavestr

 #插入数据库信息
 HOSTNAME="127.0.0.1" #数据库信息
 PORT="3306"
 USERNAME="root"
 PASSWORD="Hmf!1008"

 DBNAME="test" #数据库名称
 TABLENAME="test" #数据库中表的名称

 insert_sql="insert into ${TABLENAME} (ip,time,method,uri,http,code,datasize,head,postdata) values ('${ip}','${time}','${method}','${uri}','${http}','${code}','${datasize}','${head}','${postdata}')"
 #insert_sql="insert into ${TABLENAME}(ip) values('${ip}')"
 #echo $insert_sql
 mysql -h${HOSTNAME} -P${PORT} -u${USERNAME} -p${PASSWORD} ${DBNAME} -e "${insert_sql}" 2>/dev/null
 fi
done
#日志清除
echo > "${log_path}"
