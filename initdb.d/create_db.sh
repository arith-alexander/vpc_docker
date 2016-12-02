# initdb.d以下のスクリプトは初回起動時（"docker start" or "docker-compose start"）に「一度だけ」実行される
# 今回はdocker-composeの設定で十分なので使わない
#mysql=( mysql --protocol=socket -uroot -p"${MYSQL_ROOT_PASSWORD}" )

#"${mysql[@]}" <<-EOSQL
#    CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE};
#    GRANT ALL ON ${MYSQL_DATABASE}.* TO '${MYSQL_USER}'@'%' ;
#EOSQL