var config = {
	email: '',//TODO Set email!!! example john@smith.com
	password: '',//TODO Set password!!! example some_password
	img_path: 'tinypulse.png'
};

var page = require('webpage').create();

page.viewportSize = {width: 1920, height: 1080};

page.clipRect = { top: 535, left: 460, width: 1020, height: 550 };

page.open('https://app.tinypulse.com/signin', function (status) {
	if (status !== 'success') {
		console.log('Unable to access network');
		console.log(status);
	} else {
		console.log('Login page loaded');
	}
});

page.onLoadFinished = function () {

	//console.log("page.onLoadFinished");

	var loc_conf = config;

	if (page.url === 'https://app.tinypulse.com/signin') {
		page.includeJs("https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js", function () {
			page.evaluate(function (loc_conf) {
				$('#session_email').val(loc_conf.email);
				$('#session_password').val(loc_conf.password);
				$('form').eq(0).submit();
			}, loc_conf);
			getImage();
		});
	} else {
		getImage();
	}
};

//Make screenshot
function getImage() {
	console.log(page.url);
	if (page.url === 'https://app.tinypulse.com/dashboard') {
		window.setTimeout(function () {
			page.render(config.img_path);
			console.log('Dashboard is saved!');
			phantom.exit();
		}, 10000);
	}

}