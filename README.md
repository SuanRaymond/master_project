# 模式
USETYPE=LOCAL

# Domain 設定
# 會員平台
API_MEMBER_DOMAIN={"0":"api.member.dev"}
# 入口平台
API_INDEX_DOMAIN={"0":"api.powerrun.dev"}
# 購物平台
API_SHOP_DOMAIN={"0":"api.shop.dev"}
# 後台
API_MANAGER_DOMAIN={"0":"manager.member.dev"}

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:ltBjfB4BYYPZc2qBC89KlNTT2ZYcxJP8gyTHqnspJJU=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost

# 資料庫設定
DB_CONNECTION=sqlsrv
DB_HOST=testDB
DB_PORT=1433
DB_DATABASE=MasterDB
DB_USERNAME=web_server
DB_PASSWORD=1qaz@WSX

# 發送驗證碼
SEND_MAIL_URL=http://sms-get.com/api_send.php
SEND_MAIL_ACCOUNT=ryb5478
SEND_MAIL_PASSWORD=ryb54785478

# 刷卡機制
SEND_CARD_URL=https://www.esafe.com.tw/Service/Etopm.aspx
SEND_CARD_KEY=S1710030352
SEND_CARD_PAS=RyB5478rYb

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
