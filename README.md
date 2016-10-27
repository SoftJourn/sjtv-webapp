### Softjourn Info TV web app
Manage TV playlist, add images or Youtube videos.

### Dependencies
[PhalconPHP](https://phalconphp.com)
php-mcrypt

### Install notes
1. Set DocumentRoot to httpdocs directory
2. Edit config file (config/main.php)
3. Set permissions

```
touch httpdocs/playlist.json && 
mkdir -p cache/volt && 
mkdir -p httpdocs/uploads/thumbs && 
chmod o+w httpdocs/playlist.json httpdocs/uploads httpdocs/uploads/thumbs &&
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
