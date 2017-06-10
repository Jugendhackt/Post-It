const ajax = (resource, action, data, callback) => {
		$.ajax({
			url: 'api.php',
			type: 'POST',
			data: {resource, action, data},
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

class TodoEntry {
	constructor(text, done, id) {
		this.text = text;
		this.done = done;
		this.id = id;
	}

	setText(text) {
		this.text = text;
	}

	setState(done) {
		this.done = done;
	}

	static getList(callback){
		ajax('todos', 'get', null, (result) => {
			callback(result.entries.map((e) => new TodoEntry(e.text, e.done, e.id)));
		});
	}

	save() {
		if(!this.id) {
			ajax('todos', 'put', {'text': this.text}, (result) => {
				this.id = result.id;
			});
		} else {
			ajax('todos', 'post', {'text': this.text, 'done': this.done, 'id': this.id}, () => {});
		}
	}

	delete() {
		ajax('todos', 'delete', null, function(result){});
	}
}

let template = null;
const getTodoTemplate = () => {
	if(!template){
		template = $('.todoRow');
		template.detach();
		template.hidden = false;
	}
	return template;
}

const addTodoRow = (entry) => {
	const t = getTodoTemplate();
	t.html(t.html().replace("{{$active}}", entry.text));
	$('#todoTable').append(t);
}

$('#loginbutton').click(() => {
	login($('#usernameinput').val(), $('#passwordinput').val());
});

$('#signupbutton').click(() => {
	singup($('#usernameinput').val(), $('#passwordinput').val())
});

if($('#todoTable')) {
	addTodoRow({'text': 'Text'});
	TodoEntry.getList((entries) => {
		entries.each((e) => {
			addTodoRow(e);
		});
	});
}
