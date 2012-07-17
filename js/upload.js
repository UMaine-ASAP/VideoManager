(function($){


	// Initial check to ensure the user's browser supports the HTML5 File API
	// Currently, they are not alerted/redirected
	if(window.File && window.FileReader){
		console.log("We're Good");
	}
	else {
		console.log("Bucky McBuckington (That's not good!)");
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
			description: '',
			category: '',
			visability: '',
			type: '',
			size: '',
			progress: '0',
			status: '0',
		},
		url: function() {
			return 'sync';
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
	* -Functions
	*	@ initialize: 
	*	@ render: 
	*	@ fileSelect: 
	*	@ queue: 
	*	@ upload: 
	*
	*/

	var FileView = Backbone.View.extend({

		// New tag created to hold the FileView
		tagName: 'tr',

		// Special binds for events to functions
		events: {
			'click #queue': 'queue',
			'change input.file': 'fileSelect',
		},


		/**
		 *		Sets up the basics for the FileView View.  Binds "this" to main functions within 
		 *		this view.  Also provides emulated "queue" by watching for status changes to '2'
		 *		and calling upload on that view.
		 */
		initialize: function() {
			_.bindAll(this, 'render', 'fileSelect', 'queue', 'upload');  

			/**
			 *		This is really just a messy work around.  Ideally I should just call upload when the previous file is finished
			 *		But the only thing I could get back was a model, not the view I needed.  Probably a better way to handle this.
			 * 		ToDo: Make Better.
			 */
			var that = this;
			this.model.on('change:status', function(){
				if(this.get('status') == 2){
					that.upload();
				}
			});

			this.model.bind('change', this.render);
		},

		/**
		 *		Renders the FileView View which is essentially a table row.
		 *
		 */
		render: function() {
			var that = this;
			//The contents of the FileView <tr> element.  Note: This must be a single line, you get a parse error otherwise.
			if(this.model.get('status') == "2"){
				$(this.el).html('<td colspan=\"3\"><p>'+ this.model.get('title') + '<div class=\"progress\" style=\"width: 80%; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div>')
			}
			else if(this.model.get('status') == "3")
			{
				$(this.el).html('<td colspan=\"3\"><p>'+ this.model.get('title') + '<div class=\"progress progress-success\" style=\"width: 80%; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div>')
			}
			else
			{
				$(this.el).html('<td><p>Select a file to upload:</p><input type=\"file\" class=\"file\" name=\"files\"></td><td></td><td></td>');
			}
			
			return this;
		},

		/**
		 *		Function called when a file is chosen using the file picker.
		 *		Gets the HTML5 file element.   Changes file selector to a queue button.
		 *		Updates the model to reflect that change.
		 */

		fileSelect: function() {

			// Our file object
			var file = $(this.el).find(".file")[0].files;

			var changed = {
				file: file[0],
				title: file[0]['name'],
				type: file[0]['type'],
				size: file[0]['size'],
			}
			this.model.set(changed, {silent: true});

			//$(this.el).html('<td>' + this.model.get('selector') +'</td><td>Title: <input type=\"text\" id=\"title\" value="'+ this.model.get('title') +'"><p><small>Type: ' + this.model.get('type') + '</small></p></td><td><div class=\"progress style=\"width: 200px; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div><p><small>Size: ' + Math.floor(this.model.get('size')/1048576) + ' MB</small></p></td>')
			$(this.el).html('<td><p>Title:</p><input type=\"text\" id=\"title\" value="'+ this.model.get('title') +'"><p>Category:</p> <input type=\"hidden\" id=\"category\"></input></td><td><p>Description:</p><textarea id=\"description\"></textarea></td><td style="position: relative;"><p>Visability:</p><div id="visability" class="btn-group" data-toggle="buttons-radio"><button class="btn">Public</button><button class="btn">Private</button></div><button id=\"queue\" style="position: absolute; right: 10px; bottom: 10px;" class=\"btn btn-success\">Queue</button></td>');
			$(this.el).find("#visability").button();
			$(this.el).find("#category").select2({
				multiple: true,
				query: function (query) {
					var data = {results: []}, i, j, s;
					if(query.term != ''){
	                	for (i = 1; i < 5; i++) {
	                    	s = "";
	                    	for (j = 0; j < i; j++) {s = s + query.term;}
	                    	data.results.push({id: query.term + i, text: s});
	                	}
	                }
                query.callback(data);

				}
			});
		},

		/**
		 *		Function called by click of the queue button.  Simply sets the models status to 1, indicating it is ready for upload.
		 *		If it is the only Model in the Collection with a status of 1, upload is called here, otherwise it is left alone.
		 *
		 */

		queue: function() {


			var changed = {
				status: "1",
			}
			this.model.set(changed);

			var queued = this.collection.where({status: "1"});

			console.log(this);
			if(queued.length == 1){
				this.model.set({status: "2"});
				this.upload();
			}

		},


		/**
		 *		Upload function used to connect to the socket, run the uploads, and handle all queuing.
		 *		Step by step description within function.
		 */

		upload: function() {


			// Static definitions for common elements because I can't pass this to the socket functions
			var el = this.el;
			var model = this.model;
			var collection = this.collection;

			// Initialize a new socket with the node app.
			// Force new connection required to support asynchronous connections in the future
			var socket = io.connect('http://localhost:8080', {'force new connection': true});

			// Static definitions before I added the definitions at the top (Rework)
			var fileName = this.model.get('title');
			var fileSize = this.model.get('size');
			var fileType = this.model.get('type');

			// Initialize the HTML5 File Reader
			FReader = new FileReader();

			// Initialize the connection to the node app (ToDo: Explain Better)
			FReader.onload = function(event){

				socket.emit('Upload', {'Name' : fileName, Data: event.target.result });
			}
			socket.emit('Start', {'Name': fileName, 'Size': fileSize, 'Type': fileType });

			// Once again, static definition I should rework
			var SelectedFile = this.model.get('file');



			// After the upload is started above, the node app with send a request for more data.  That request is handled here.
			socket.on('MoreData', function (data){

				var update = {
					progress: data['Percent'],
				}

				model.set(update);


				// ToDo: Experiment with larger chunk sizes

				var Place = data['Place'] * 10485760; //The Next Blocks Starting Position
				var NewFile; //The Variable that will hold the new Block of Data
				
				// Webkit/Firefox Specific upload commands...
				if(SelectedFile.webkitSlice) 
					NewFile = SelectedFile.webkitSlice(Place, Place + Math.min(10485760, (SelectedFile.size-Place)));
				else
					NewFile = SelectedFile.mozSlice(Place, Place + Math.min(10485760, (SelectedFile.size-Place)));
				FReader.readAsBinaryString(NewFile);
			});



			// Eventually the upload will finish.

			socket.on('Done', function (data) {

				socket.disconnect();

				var update = {
					progress: '100',
					status: '3',
				}
				model.set(update);



				// Emulate a queue by uploading the next file with a status of 1 (queued).
				// Also, change that model's status to 2 so it gets uploaded.

				var nextUpload = collection.where({status: "1"});
				// This returns a model, not a view, meaning we can't directly call upload on it.

				var changeStatus = {
					status: '2'
				}
				nextUpload[0].set(changeStatus);
				// So instead we set its status to 2, triggering the on change event defined earlier in the View.
			});

			socket.on('Error', function (data){
				socket.disconnect();

				var update = {
					progress: '0',
					status: '4',
				}
				model.set(update);

				var nextUpload = collection.where({status: "1"});
				// This returns a model, not a view, meaning we can't directly call upload on it.

				var changeStatus = {
					status: '2'
				}
				nextUpload[0].set(changeStatus);
				// So instead we set its status to 2, triggering the on change event defined earlier in the View.
			});
		}	
	});

	/* Backbone View: QueueView
	*	A structure view.  Responsible for setting up page elements, basic functions, and organization.
	*
	* -Functions
	*	@ initialize: 
	*	@ render: 
	*	@ addFile: 
	*	@ appendItem: 
	*
	*/

	var QueueView = Backbone.View.extend({
		el: $('#upload_list'), // Setups up a simple selector for all HTML pieces of the View

		events: {
			"click a#add_item": 'addFile',
		},

		initialize: function() {
			_.bindAll(this, 'render', 'addFile', 'appendItem');

			this.collection = new Queue();
			this.collection.bind('add', this.appendItem);

			this.render();
			this.addFile();
		},

		render: function() {
			var self = this;

			$(this.el).append("<a class=\"btn pull-right\" href=\"#\" id=\"add_item\"><i class=\"icon-plus\"></i> Add Another</a>");
			$(this.el).append("<table class=\"table\"><thead><tr><td width=\"30%\"></td><td width=\"45%\"></td><td width=\"25%\"></td></tr></thead></table>");
		},

		addFile: function(){
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

	// Finally, put it all together.
	var queueView = new QueueView();

})(jQuery);
