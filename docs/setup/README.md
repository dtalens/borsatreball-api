# Configurar el servidor

## Desplegament per a desenvolupament
Si la nostra màquina és per a desenvolupar l'aplicació el més senzill és crear un entorn amb Vagrant i **Homestead**. En la [documentació de Laravel](https://laravel.com/docs/8.x/homestead) hi ha informació de còm crear i configurar eixe entorn.

### Descàrrega de l'aplicació del backend
A l'hora de descarregar el codi, des de la carpeta `~/code` inicialitzem git i descarregem l'aplicació:
```bash
git clone https://github.com/cipfpbatoi/borsatreball-api.git
```

## Desplegament per a producció del backend
Si només volem tindre l'aplicació funcionant necessitem un servidor on instal·larem:
* **apache2 o nginx**
* **mysql-server** o **mariadb-server**
* **php**
* **phpmyadmin**
* **git**
* **composer**

Per a configurar el servidor de bases de dades hem d'executar el comando **`mysql_secure_installation`**. 

NOTA: ara la validació dels usuaris la fa el sistema (el _plugin_ 'auth_socket' o 'unix_socket'). Per a configurar un usuari amb privilegis el que hem de fer és:
```bash
mysql_secure_installation
sudo mysql -u root

mysql> CREATE USER nomusuari@localhost IDENTIFIED BY 'P@ssw0rd';
# Si volem vore-ho
mysql> SELECT User, Host, plugin, authentication_string FROM mysql.user;
# Creem la base de dades
mysql> CREATE DATABASE borsatreball;
# i li donem privilegis a l'usuari
mysql> GRANT ALL ON borsatreball.* TO 'nomusuari'@'localhost' IDENTIFIED BY 'P@ssw0rd' WITH GRANT OPTION;
mysql> FLUSH PRIVILEGES;
mysql> exit;
```

Ara descarreguem l'aplicació des de Github:
```bash
git clone https://github.com/cipfpbatoi/borsatreball-api.git
```

Copiem el fitxer **.env**, que no es descarrega de git, des de **.env-example**. Allí hem de configurar:
- APP_NAME: Posem el nostr nom (CIP FP Batoi)
- l'accés a la BBDD (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- el mail (s'explica més avall)

Ara em d'executar el `key:generate`:
```bash
php artisan key:generate
```

### Configurar apache
Creem els certificats (el _.key_ en /etc/ssl/private i els altres 2 en en /etc/ssl/certs):
```bash
openssl genrsa -out borsa.key 2048
openssl req -new -key ../private/borsa.key -out borsa.csr   # completem la informació que ens demanen
openssl x509 -req -in borsa.csr -signkey ../private/borsa.key -out borsa.crt
```

Configurem el lloc web SSL en _/etc/apache2/sites-available_:
* ServerName: p.ej. `ServerName borsa.my`
* DocumentRoot: `DocumentRoot /var/www/html/borsaBatoi/public`
* SSLCertificateFile: `SSLCertificateFile /etc/ssl/certs/borsa.crt`
* SSLCertificateKeyFile: `SSLCertificateKeyFile /etc/ssl/private/borsa.key`
* Creem un nou directori:`
```bash
<Directory /var/www/html/borsaBatoi/public>
  AllowOverride All
  Order Allow,Deny
  Allow from All
</Directory>
```

Configurem el lloc web no SSL en _/etc/apache2/sites-available_ per a que redireccione al SSL:
* ServerName: p.ej. `ServerName borsa.my`
* Redireccionem: `Redirect permanent  /  https://borsa.my/`
* DocumentRoot: `DocumentRoot /var/www/html/borsaBatoi/public`

Habilitem els sites si els hem creat nous:
```bash
sudo a2ensite borsa.conf
sudo a2ensite borsa-ssl.conf
```

### Configuracio alternativa amb nginx
Creem una fitxer amb:
```bash
sudo nano /etc/nginx/sites-available/borsa.conf
```
Peguem el següent contingut:
```bash
server {
    listen 80;
    server_name borsa.my;
    root /var/www//borsaBatoi/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Després creem un enllaç simbòlic a la carpeta de llocs actius:
```bash
sudo ln -s /etc/nginx/sites-available/borsa.conf /etc/nginx/sites-enabled/
```
Per útlim comprovem si tot és correcte i recarreguem el servici:
```bash
sudo service nginx configtest
sudo service nginx reload
sudo service nginx restart
```

Posem el nostre domini en el **/etc/hosts**:
```bash
127.0.0.1   borsa.my
```

A continuació cal asegurar-se que l'usuari www-data pot escriure dins del directori **storage** i dins de **bootstrap/cache**.

Per a finalitzar hem d'activar (si no ho estan ja) els mòduls **ssl** i **rewrite** i reiniciar apache:
```bash
sudo a2enmod ssl
sudo a2enmod rewrite
sudo systemctl restart apache2.service
```
ATENCIÓ: cal que estiga la carpeta borsaBatoi ja creada abans de reiniciar Apache per que no done un error.

## Configuració de l'aplicació del backend
Si estem desplegant per a producció necessitarem el gestor de dependències **composer** (en Homestead ja el tenim) així que ho [descarreguem](https://getcomposer.org/download/) en el directori de l'aplicació.

Des de la carpeta on tenim l'aplicació descarregada instal·lem les llibreries necessàries (això tardarà prou perquè ha de baixar-se moltes llibreries de Internet):
```bash
composer install     # en producció: php composer.phar install
```

Creem la BBDD `borsatreball` i executem la migración (això no cal fer-ho si importem la BBDD en compte de crear-la):
```bash
php artisan migrate
php artisan db:seed
```

Per a l'autenticació hem d'instal·lar [laravel/passport](https://laravel.com/docs/5.8/passport). A continuació executem el comando `passport:install` que crea las claus d'encriptació que s'utilitzen per a generar els tokens. A més crea els clients "personal access" i "password grant" clients which will be used to generate access tokens):
```bash
php artisan passport:install
```

Si has de tornar a crear la base de dades hauras d'executar `php artisan passport:client --personal` per a que es tornen a generar les taules que utilitza _passport_ per a autenticar als usuaris.

Donem permisos d'escriptura a l'usuari **www-data** sobre la carpeta storage i el seu contingut.

## Configurar el mail
Nosaltres hem instal·lat **`exim4`** i hem creat en el sistema l'usuari `usrmail` per a enviar els correus. Configurem exim4 amb `dpkg-reconfigure exim4-config`. El fitxer /etc/exim4/update-exim4.conf.conf hauria de quedar-se:
```bash
dc_eximconfig_configtype='satellite'    // o 'smartmail'
dc_other_hostnames=''
dc_local_interfaces=''
dc_readhost='borsa@nosaltres.com'
dc_relay_domains=''
dc_minimaldns='false'
dc_relay_nets=''
dc_smarthost='smtp.gmail.com:587'
CFILEMODE='644'
dc_use_split_config='false'
dc_hide_mailname='true'
dc_mailname_in_oh='true'
dc_localdelivery='mail_spool'
```

Configurem el compte de correu a utilitzar en /etc/exim4/passwd.client:
```bash
gmail-smtp.l.google.com:borsa@nosaltres.com:P@ssW0rd
*.google.com:borsa@nosaltres.com:P@ssW0rd
smtp.gmail.com:borsa@nosaltres.com:P@ssW0rd
```

I en el fitxer **`.env`** configurem:
```bash
MAIL_DRIVER=smtp
MAIL_HOST=localhost
MAIL_PORT=25
MAIL_USERNAME=usrmail
MAIL_PASSWORD=P@ssW0rd
MAIL_ENCRYPTION=null
MAIL_FROM_NAME="Borsa Treball Batoi"
MAIL_FROM_ADDRESS=borsa@nosaltres.com
```

## Configurar l'aplicació del frontend
El que hem fet fins ara és preparar el nostre servidor per a rebre peticions de l'aplicació que utilitzarà l'usuari. Les instruccions per a descarregar i configurar l'aplicació d'usuari es troben a [https://github.com/cipfpbatoi/borsatreball-front](https://github.com/cipfpbatoi/borsatreball-front)
