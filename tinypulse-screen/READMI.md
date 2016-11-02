###Set apache user as a folder owner
```
chown www-data tinypulse-screen
```
###Set credentials in screencapture.js 
```
var config = {
	email: '',//TODO Set email!!! example john@smith.com
	password: '',//TODO Set password!!! example some_password
	img_path: 'tinypulse.png'
};

```
###Add task to cron
check cron_task_tinypulse
