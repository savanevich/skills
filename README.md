# skills

Requirements:
-------------

vagrant 1.7.4  
virtualbox 5.x

It might be issue with vagrant guest access, so in any case install the following plugin:
```
vagrant plugin install vagrant-vbguest
```

Run vagrant:
```
vagrant up
```

After running vagrant go to home directory:

```
vagrant ssh  
cd /var/www
```


Run Frontend:
------------

```
cd skeleton/frontend
gulp monitor
```

Open your browser: http://192.168.55.55:3020 

Run Backend:
--------

```
cd skeleton/backend
php -S 0.0.0.0:8000 -t public/
```

Open your browser: http://192.168.55.55:8000/admin
 
