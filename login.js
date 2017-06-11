const ajax = (resource, action, data, callback) => {
	$.ajax({
		url: 'api.php',
		type: 'POST',
		data: {resource, action, 'payload': data},
		dataType: 'json',
		success: function(result){
			return callback(result);
		}
	});
}

const login = (username, password) => {
	ajax('sessions', 'put', {username, password}, function(result){
		document.cookie = `sid=${result.sid}`;
	});
};

const singup = (username, password) => {
	ajax('users', 'put', {username, password}, () => {
		login(username, password);
	});
};

$('#loginbutton').click(() => {
	login($('#usernameinput').val(), $('#passwordinput').val());
});

$('#signupbutton').click(() => {
	singup($('#usernameinput').val(), $('#passwordinput').val())
});