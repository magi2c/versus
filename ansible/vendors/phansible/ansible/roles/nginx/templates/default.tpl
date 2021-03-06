server {
    server_name {{ nginx.servername }} www.{{ nginx.servername }};
    root {{ nginx.docroot }}/web;

    location / {
        # try to serve file directly, fallback to app.php
        try_files $uri /app.php$is_args$args;
    }

    location ~ ^/app\.php(/|$) {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS off;
        internal;
    }

    access_log  {{ nginx.docroot }}/nginx/logs/access.log;
    error_log   {{ nginx.docroot }}/nginx/logs/error.log;
}