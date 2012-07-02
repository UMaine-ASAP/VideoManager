(function($){


	// Initial check to ensure the user's browser supports the HTML5 File API
	// Currently, they are not alerted/redirected
	if(window.File && window.FileReader){
		console.log("We're Good");
	}
	else {
		console.log("Bucky McBuckington (That's not good!)");
	}


//Backbone.sync workaround
Backbone.sync = function(method, model, success, error){ 
	success();
}

	/* Backbone Model: File
	*	Model used to store attributes of files being uploaded
	*
	* -Attributes:
	*	@ file: Stores the HTML5 File object for the selected file
	*	@ title: Display title
	*	@ type: Mime type
	*	@ size: Size in bytes
	*	@ progress: Stores current position when uploading
	* 	@ status: Status used to emulate a queue
	*		- 0: Default (Not Tracked)
	*		- 1: In Queue
	*		- 2: Next in line for queue
	*		- 3: Uploaded 
	*	@ selector: Stores HTML data for the queue button/file upload box
	*
	*/

	var File = Backbone.Model.extend({
		defaults: {
			file: '',
			title: '',
			type: '',
			size: '',
			progress: '0',
			status: '0',
			selector: '<input type=\"file\" class=\"file\" name=\"files\">',
		}
	});


	/* Backbone Collection: Queue
	*	Collection used to store all our files
	*
	* -Attributes
	*	@ model: file
	*		- Only model stored is our previously created file model
	*
	*/

	var Queue = Backbone.Collection.extend({
		model: File
	});

	/* Backbone View: FileView
	*	Responsible for all fields related to each file row
	*
	*
	*
	*
	*
	*
	*/

	var FileView = Backbone.View.extend({
		tagName: 'tr',

		events: {
			'click a#queue': 'queue',
			'change input.file': 'fileSelect',
		},

		initialize: function() {
			_.bindAll(this, 'render', 'fileSelect', 'queue', 'upload');

			var that = this;

			this.model.on('change:status', function(){
				if(this.get('status') == 2){
					that.upload();
				}
			});

			this.model.bind('change', this.render);
		},

		render: function() {
			var progress_type;
			if(this.model.get('progress') != '100'){
				progress_type = "progress-striped";
			}
			else {
				progress_type = "progress-success"
			}
			$(this.el).html('<td>' + this.model.get('selector') +'</td><td>Title: <input type=\"text\" id=\"title\" value="'+ this.model.get('title') +'"><p><small>Type: ' + this.model.get('type') + '</small></p></td><td><div class=\"progress ' + progress_type +'\" style=\"width: 200px; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div><p><small>Size: ' + Math.floor(this.model.get('size')/1048576) + ' MB</small></p></td>')
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
				selector: '<a class=\"btn btn-primary\" data-loading-text=\"Queued!\" id=\"queue\" ><i class=\"icon-upload icon-white\"></i>  Queue</a>'
			}
			this.model.set(changed);
		},

		queue: function() {


			$(this.el).find(".progress").toggleClass('progress-warning progress-striped').toggleClass('inactive active').children("div");
			$(this.el).find("#queue").button('loading');

			var changed = {
				status: "1",
			}
			this.model.set(changed);


			var queued = this.collection.where({status: "1"});

			console.log(this);
			if(queued.length == 1){
				this.upload();
			}

			console.log(queued.length);

			//console.log(this.el);
		},

		upload: function() {

			/*
			 *  Write a nice description here
			 *
			 */

			// Static definitions for common elements because I can't pass this to the socket functions
			var el = this.el;
			var model = this.model;
			var collection = this.collection;

			// Initialize a new socket with the node app.
			// Force new connection required to support asyncronous connections in the future
			var socket = io.connect('http://localhost:8080', {'force new connection': true});

			// Static definitions before I added the definitions at the top (Rework)
			var fileName = this.model.get('title');
			var fileSize = this.model.get('size');

			// Initialize the HTML5 File Reader
			FReader = new FileReader();

			// Initialize the connection to the node app (ToDo: Explain Better)
			FReader.onload = function(event){

				socket.emit('Upload', {'Name' : fileName, Data: event.target.result });
			}
			socket.emit('Start', {'Name': fileName, 'Size': fileSize });

			// Once again, static definition I should rework
			var SelectedFile = this.model.get('file');



			socket.on('MoreData', function (data){

				var update = {
					progress: data['Percent'],
					selector: '<a class=\"btn btn-primary disabled\" data-loading-text=\"Queued!\" id=\"queue\" ><i class=\"icon-upload icon-white \"></i>  Uploading!</a>',
				}

				model.set(update);


				// ToDo: Experiment with larger chunk sizes

				var Place = data['Place'] * 524288; //The Next Blocks Starting Position
				var NewFile; //The Variable that will hold the new Block of Data
				
				// Webkit/Firefox Specific upload commands...
				if(SelectedFile.webkitSlice) 
					NewFile = SelectedFile.webkitSlice(Place, Place + Math.min(524288, (SelectedFile.size-Place)));
				else
					NewFile = SelectedFile.mozSlice(Place, Place + Math.min(524288, (SelectedFile.size-Place)));
				FReader.readAsBinaryString(NewFile);
			});



			socket.on('Done', function (data) {

				socket.disconnect();

				var update = {
					progress: '100',
					selector: '<a class=\"btn btn-success disabled\" data-loading-text=\"Queued!\" id=\"queue\" ><i class=\"icon-upload icon-white\"></i>  Done</a>',
					status: '3',
				}
				model.set(update);


				var nextUpload = collection.where({status: "1"});


				var changeStatus = {
					status: '2'
				}
				nextUpload[0].set(changeStatus);


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
