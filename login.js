user = '';
loggedIn = false;
$('#loginbutton').click(function(event){
	console.log('clicked button');
	if($('#usernameinput').val() != '' && $('#passwordinput').val() != ''){
		 console.log('not null')
		 $.getJSON('users.json', function(json){
		 	if(!json) return;
		 	if(json[$('#usernameinput').val()].password == $('#passwordinput').val()){
		 		user = $('#usernameinput').val();
		 		loggedIn = true;
		 	}
		 });
	}
})