(function($){

	if(window.File && window.FileReader){
		console.log("We're Good");
	}
	else {
		console.log("Bucky McBuckington");
	}

  Backbone.sync = function(method, model, success, error){ 
    success();
  }

	var File = Backbone.Model.extend({
		defaults: {
			file: '',
			title: '',
			type: '',
			size: '',
			progress: '50',
			selector: '<input type=\"file\" class=\"file\" name=\"files\">',
		}
	});

	var Queue = Backbone.Collection.extend({
		model: File
	});

	var FileView = Backbone.View.extend({
		tagName: 'tr',

		events: {
			'click a#queue': 'upload',
			'change input.file': 'fileSelect',
		},

		initialize: function() {
			_.bindAll(this, 'render', 'fileSelect', 'queue', 'upload');

			this.model.bind('change', this.render);
		},

		render: function() {
			$(this.el).html('<td>' + this.model.get('selector') +'</td><td>Title: <input type=\"text\" id=\"title\" value="'+ this.model.get('title') +'"><p><small>Type: ' + this.model.get('type') + '</small></p></td><td><div class=\"progress progress-warning inactive\" style=\"width: 200px; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div><p><small>Size: ' + this.model.get('size') + ' MB</small></p></td>')
			return this;
		},

		fileSelect: function() {

			var file = $(this.el).find(".file")[0].files;

			//var filesize = Math.floor((file[0]['size'])/1048576);
			var filesize = file[0]['size'];

			var changed = {
				file: file[0],
				title: file[0]['name'],
				type: file[0]['type'],
				size: filesize,
				selector: '<a class=\"btn btn-success\" data-loading-text=\"Queued!\" id=\"queue\" ><i class=\"icon-upload icon-white\"></i>  Queue</a>'
			}
			this.model.set(changed);
		},

		queue: function() {


			$(this.el).find(".progress").toggleClass('progress-warning progress-striped').toggleClass('inactive active').children("div");
			$(this.el).find("#queue").button('loading');

			console.log(this.model.toJSON());

			//console.log(this.el);
		},

		upload: function() {

			$(this.el).find(".progress").toggleClass('progress-warning progress-striped').toggleClass('inactive active').children("div").width("0%");
			$(this.el).find("#queue").button('loading');

			var socket = io.connect('http://10.0.2.23:8080');

			var fileName = this.model.get('title');
			var fileSize = this.model.get('size');

			FReader = new FileReader();

			FReader.onload = function(event){
				socket.emit('Upload', {'Name' : fileName, Data: event.target.result });
			}

			socket.emit('Start', {'Name': fileName, 'Size': fileSize });

			var model = this.model;

			var SelectedFile = this.model.get('file');

			socket.on('MoreData', function (data){
				//UpdateBar(data['Percent']);

				var update = {
					progress: data['Percent'],
				}

				model.set(update);

				var Place = data['Place'] * 524288; //The Next Blocks Starting Position
				var NewFile; //The Variable that will hold the new Block of Data
				if(SelectedFile.webkitSlice) 
					NewFile = SelectedFile.webkitSlice(Place, Place + Math.min(524288, (SelectedFile.size-Place)));
				else
					NewFile = SelectedFile.mozSlice(Place, Place + Math.min(524288, (SelectedFile.size-Place)));
				FReader.readAsBinaryString(NewFile);
			});
		}	

	})


	var QueueView = Backbone.View.extend({
		el: $('#upload_list'),

		events: {
			"click a#add_item": 'addItem',
		},

		initialize: function() {
			_.bindAll(this, 'render', 'addItem', 'appendItem');

			this.collection = new Queue();
			this.collection.bind('add', this.appendItem);

			this.render();
			this.addItem();
		},

		render: function() {
			var self = this;

			$(this.el).append("<a class=\"btn pull-right\" href=\"#\" id=\"add_item\"><i class=\"icon-plus\"></i> Add Another</a>");
			$(this.el).append("<table class=\"table\"><thead><tr><td width=\"25%\"></td><td width=\"40%\">File Info</td><td width=\"35%\">Status</td></tr></thead></table>");
		},

		addItem: function(){
			var file = new File();
			this.collection.add(file);
		},

		appendItem: function(file){

			var table = $(this.el).children("table");
			var fileView = new FileView({
				model: file,
				collection: this.collection
			});
			$(table).last().append(fileView.render().el);
		}

	});

	var queueView = new QueueView();

/*var QueueItem = Backbone.View.extend({
		tagName: '<tr>',

		events: {
			'click #queue': 'queue'
		},

		initialize: function() {
			_.bindAll(this, 'render', 'queue');
		},

		render: function() {
			$(this.el).html('');
			return this;
		},

		queue: function() {
			var queued = {
				file: '',
				title: $(this).children('#title').val()
			};
			this.model.set(queued);
		}

	})

	var QueueView = Backbone.View.extend({
		el: "#upload_list",

		events: {
			'click a#add_item': 'addItem'
		},

		initialize: function(){
			_.bindAll(this, 'render', 'addItem');

			this.collection = new Queue();
			this.collection.bind('add', this.addItem);
			console.log(this);

			this.render();
		},

		render: function() {
			var self = this;

			$(this.el).append("<table class=\"table\"><thead><td>File:</td><td>File Info</td><td>Status</td><td></td></thead></table>")

			_(this.collection.models).each(function(item){ // in case collection is not empty
       			self.appendItem(item);
    		}, this);
		},

		addItem: function() {
			var file = new File();

			this.collection.add(file);
		}

	});

	var queueView = new QueueView();
*/


})(jQuery);
