user = '';
loggedIn = false;
$('#loginbutton').click(function(event){
	console.log('clicked button');
	if($('#usernameinput').val() != '' && $('#passwordinput').val() != ''){
		 console.log('not null')
		 $.getJSON('pwReader.php', function(json){
		 	if(!json) return;
		 	console.log(json);
		 });
	}
})