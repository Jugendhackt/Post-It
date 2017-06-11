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

class TodoEntry {
	constructor(text, done, id) {
		this.text = text;
		if(done == null)
			this.done = false;
		else
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
		ajax('todos', 'delete', this.id, function(result){});
	}
}



let template = null;
const getTodoTemplate = () => {
	if(!template){
		template = $('.todoRow');
		template.detach();
	}
	return template;
}

const addTodoRow = (entry) => {
	const t = getTodoTemplate().clone();
	t.html(t.html().replace("{{$text}}", entry.text));
	t.data('entry', entry);
	console.log(t.data('entry'));
	$('#todoTable').append(t);
	$(t.children()[1]).click((event) => {
		const parent = $(event.currentTarget).parent();
		parent.data('entry').delete();
		parent.remove();
	});
	//t.children
}

const deleteTodoRow = (event) => {
	console.log(event);
}

/*const changeTodoRow = (oldEntry, entry) => {
	$
}*/

$('#loginbutton').click(() => {
	login($('#usernameinput').val(), $('#passwordinput').val());
});

$('#signupbutton').click(() => {
	singup($('#usernameinput').val(), $('#passwordinput').val())
});

let oldEntries = null;

if($('#todoTable')) {
	TodoEntry.getList((entries) => {
		for(let e of entries){
			addTodoRow(e);
		}
	});
	/*setInterval(() => {
		TodoEntry.getList((entries) => {
			/*if(oldEntries != entries){
				for(let e of entries){
					let eExists = false;
					for(let oldE of oldEntries){
						if(e.id == oldE.id)
							eExists = true;
					}
					if(!eExists){

					}
				}
				oldEntries = entries;
			}
			for(let row of $('.todoRow')){
				row.remove();
			}
		});
	}, 5000);*/
}
