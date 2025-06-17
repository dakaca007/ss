FROM registry.cn-hangzhou.aliyuncs.com/ossrs/srs:5

# 安装 Nginx、PHP、PHP Redis 扩展、supervisor
RUN apt-get update \
    && apt-get install -y nginx php-fpm php-redis supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 拷贝 Nginx 配置
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# 拷贝 SRS 配置
COPY srs.conf /usr/local/srs/conf/srs.conf

# 拷贝 PHP 代码
COPY src /var/www/html

# 拷贝 supervisor 配置
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

EXPOSE 80 1935 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]