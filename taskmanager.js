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
		ajax('todos', 'delete',  {'id': this.id}, function(result){});
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
	$('#todoTable').append(t);
	$(t.children()[1]).click((event) => {
		const parent = $(event.currentTarget).parent();
		console.log(parent.data('entry'));
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

$('#addtask').click(() => {
	if($('addtaskinput').val() != ''){
		const tEntry = new TodoEntry($('#addtaskinput').val(), false, null);
		$('#addtaskinput').val('');
		tEntry.save();
		addTodoRow(tEntry);
	}
});

if($('#todoTable').length > 0) {
	console.log('isTodo');
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