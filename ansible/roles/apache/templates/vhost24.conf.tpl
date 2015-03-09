# Default Apache virtualhost template

<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot {{ doc_root }}
{% set servernames = servername.split() %}
{% for servername in servernames %}
{% if loop.first %}
    ServerName {{ servername }}
{% else %}
    ServerAlias {{ servername }}
{% endif %}
{% endfor %}
    DirectoryIndex index.php

    <Directory {{ doc_root }}>
        AllowOverride All
        Require all granted

        Options +FollowSymLinks
        RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ index.php [L]
    </Directory>
</VirtualHost>
