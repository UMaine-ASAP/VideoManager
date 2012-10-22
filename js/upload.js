(function($){


	// Initial check to ensure the user's browser supports the HTML5 File API
	// Currently, they are not alerted/redirected
	if(window.File && window.FileReader){
		console.log("We're Good");
	}
	else {
		console.log("Bucky McBuckington (That's not good!)");
	}

	Backbone.emulateHTTP = true;
	Backbone.emulateJSON = true;
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
			visibility: '',
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
			'click #remove': 'unrender',
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
			this.model.bind('remove', this.unrender);
		},

		/**
		 *		Renders the FileView View which is essentially a table row.
		 *
		 */
		render: function() {
			if(this.collection.length %2 != 0){
				$(this.el).addClass("striped");
			}
			//The contents of the FileView <tr> element.  Note: This must be a single line, you get a parse error otherwise.
			if(this.model.get('status') == "2"){
				$(this.el).html('<td colspan=\"3\"><p>'+ this.model.get('title') + '<div class=\"progress\" style=\"width: 80%; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div>')
			}
			else if(this.model.get('status') == "3")
			{
				$(this.el).html('<td colspan=\"3\"><p>'+ this.model.get('title') + '<div class=\"progress progress-success\" style=\"width: 80%; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div>')
			}
			else if(this.model.get('status') == "1")
			{
				$(this.el).html('<td colspan=\"3\"><p>'+ this.model.get('title') + '<div class=\"progress\" style=\"width: 80%; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div>')
			}
			else
			{
				$(this.el).html('<td>Select a file to upload <input type=\"file\" class=\"file\" name=\"files\"></td><td></td>');
			}
			
			return this;
		},

		unrender: function() {
			$(this.el).remove();

			
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


			var string = '<td style="width: 60%;"><h4>' + this.model.get('title') + '</h4><br>';
			string += '<form class="form-horizontal"><div class="control-group"><label class="control-label" for="title">Change Title</label><div class="controls"><input type="text" class="input-xlarge" id="title" value="'+this.model.get('title')+'"></div></div>';
			string += '<div class="control-group"><label class="control-label" for="description">Description</label><div class="controls"><textarea id="description" style="width: 100%; height: 150px;"></textarea></div></div></form>';
			string += '</td><td><div style="position: relative;">';
			string += '<button style="position: absolute; top: 10px; right: 8px;" id="remove" class="close">&times;</button><button id=\"queue\" style="position: absolute; right: 30px; top: 5px; z-index: 800;" class=\"btn btn-success\">Queue</button>';
			string += '<form style="margin-top: 30px;" class="form-horizontal"><div class="control-group"><label class="control-label" for="private">Visibility</label><div class="controls"><div id="visibility" class="btn-group" data-toggle="buttons-radio" ><a id="1" data-content="You must first upload the video before you can send it to MarcelTV" class="btn disabled" value="1">Public</a><a class="btn active disabled" data-content="You must first upload the video before you can send it to MarcelTV" id="0" value="0">Private</a></div></div></div>';
			string += '<div class="control-group"><label class="control-label" for="category">Category</label><div class="controls"><input type=\"hidden\" id=\"category_select\"></input></div></div></form></div></td></table>';
			//$(this.el).html('<td>' + this.model.get('selector') +'</td><td>Title: <input type=\"text\" id=\"title\" value="'+ this.model.get('title') +'"><p><small>Type: ' + this.model.get('type') + '</small></p></td><td><div class=\"progress style=\"width: 200px; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: ' + this.model.get('progress') + '%\"></div></div><p><small>Size: ' + Math.floor(this.model.get('size')/1048576) + ' MB</small></p></td>')
			//$(this.el).html('<td>Title  <input type=\"text\" id=\"title\" value="'+ this.model.get('title') +'"><br>Category <input type=\"hidden\" id=\"category\"></input></td><td><p>Description:</p><textarea id=\"description\"></textarea></td><td style="position: relative;"><p>Visability:</p><div id="visability" class="btn-group" data-toggle="buttons-radio"><button class="btn">Public</button><button class="btn">Private</button></div><button id=\"queue\" style="position: absolute; right: 10px; bottom: 10px;" class=\"btn btn-success btn-large\">Queue</button></td>');
			//$(this.el).html('<td><h4>'+ this.model.get('title') +'</h4><form class="form-horizontal><div class="control-group"><label class="control-label">')
			$(this.el).html(string);
			$(this.el).find("#visibility").button().children("a").popover();

			console.log($(this.el).find("#category_select"));

			$(this.el).find("#category_select").select2({
				multiple: false,
				placeholder: {title: "Select a Category", id: ""},
				minimumInputLength: 1,
				ajax: {
					url: WEB_ROOT + "/findCategoriesLike",
					type: 'get',
					dataType: 'json',
					data: function (term, page) {
						return {
							q: term,
							limit: 10,
						};
					},
					results: function (data, page) {
						return {results: data}
					}
				},
				formatResult: format_category,
				formatSelection: format_category,
			});
		},

		/**
		 *		Function called by click of the queue button.  Simply sets the models status to 1, indicating it is ready for upload.
		 *		If it is the only Model in the Collection with a status of 1, upload is called here, otherwise it is left alone.
		 *
		 */

		queue: function() {
			var category;
			var category_select =  $(this.el).find("#category_select").select2("val").split(',');

			if(category_select != ""){
				if(category_select[0] == '-1'){
					category = String(category_select[1]);
				}
				else {
					category = Number(category_select[0]);
				}
			}
			else
			{
				category = null;
			}

			var changed = {
				description: $(this.el).find("textarea").val(),
				//visibility: $(this.el).find("#visibility").children('.active').attr("id"),
				visibility: 0,
				category: category,
				status: "1",
			}

			var that = this;

			this.model.save(changed, {
				success: function(model, response){
					var active = that.collection.where({status: "2"});

					model.set({id: response['id'], unique_id: response['unique_id']});

					if(active.length == 0){
						model.set({status: "2"});
					}

				},
			});
		},


		/**
		 *		Upload function used to connect to the socket, run the uploads, and handle all queuing.
		 *		Step by step description within function.
		 */

		upload: function() {
			//TODO: Type checking before upload

			// Static definitions for common elements because I can't pass this to the socket functions
			var model = this.model;
			var collection = this.collection;

			uploadFile(this.model.get('file'));

			function progress(evt) {
				var newProgress = (evt.loaded/model.get('size'))*100;
				model.set({progress: newProgress})
			}

			function complete(evt) {
				var update = {
					progress: '100',
					status: '3'
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
				// So instead we set its status to 2, triggering the on change event defined earlier in the
			}

			function uploadFile(myFileObject) {
				// Open Our formData Object
				var formData = new FormData();
			 
				// Append our file to the formData object
				// Notice the first argument "file" and keep it in mind
				formData.append('file', myFileObject);
				formData.append('id', model.get('id'));
				formData.append('unique_id', model.get('unique_id'));
			 
				// Create our XMLHttpRequest Object
				var xhr = new XMLHttpRequest();

			 	var uuid = "";
     				for (i = 0; i < 32; i++) { uuid += Math.floor(Math.random() * 16).toString(16); }
				// Open our connection using the POST method
				xhr.upload.addEventListener('progress', progress, false);
				xhr.upload.addEventListener('load', complete, false);
				xhr.open("POST", '/upload_test/');
			 
				// Send the file
				xhr.send(formData);
			}
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
		el: $('#upload_list_container'), // Setups up a simple selector for all HTML pieces of the View

		events: {
			"click a#add_video": 'addFile',
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

			$(this.el).append("<a class=\"btn btn-primary pull-right\" id=\"add_video\"><i class=\"icon-plus icon-white\"></i> Add Another</a>");
			$(this.el).append("<table id=\"upload-list\" class=\"table table-striped\"><thead><tr><td width=\"40%\"></td><td width=\"60%\"></td></tr></thead></table>");
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
			$(table).last().prepend(fileView.render().el);
		}

	});

	// Finally, put it all together.
	var queueView = new QueueView();

})(jQuery);
