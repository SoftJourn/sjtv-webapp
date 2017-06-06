### Softjourn Info TV web app
Manage TV playlist, add images or Youtube videos.

### Dependencies
[PhalconPHP](https://phalconphp.com)
php-mcrypt

### Install notes
1. Set DocumentRoot to public directory
2. Create config file (app/config/config.php)
3. Run composer installation
```
composer install

```

3. Set permissions

```
touch public/playlist.json && 
touch app/config/users.txt && 
mkdir -p cache/volt && 
mkdir -p public/uploads/thumbs && 
chmod o+w public/playlist.json public/uploads public/uploads/thumbs app/config/users.txt &&
chmod -R o+w cache/volt
```


### User managemnt
1. LDAP authorization
Check config file for details

2. Text file users list
CLI helper tool is available to manage users list

```
php user.php add user password
php user.php update user new-password
php user.php delete user
```
