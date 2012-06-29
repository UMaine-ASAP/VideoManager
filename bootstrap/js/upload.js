(function($){

  Backbone.sync = function(method, model, success, error){ 
    success();
  }

	var File = Backbone.Model.extend({
		defaults: {
			file: '',
			title: '',
			type: '',
			size: '',
			progress: '',
			status: 0,
		}
	});

	var Queue = Backbone.Collection.extend({
		model: File
	});

	
	var QueueView = Backbone.View.extend({
		el: $('#upload_list'),

		events: {
			"click a#add_item": 'addItem',
			"click a#queue": 'queue'
		},

		initialize: function() {
			_.bindAll(this, 'render', 'addItem', 'appendItem', 'queue');

			this.collection = new Queue();
			this.collection.bind('add', this.appendItem);

			this.render();
			this.appendItem();
		},

		render: function() {
			var self = this;

			$(this.el).append("<a class=\"btn pull-right\" href=\"#\" id=\"add_item\"><i class=\"icon-plus\"></i> Add Another</a>");
			$(this.el).append("<table class=\"table\"><thead><td>File:</td><td>File Info</td><td>Status</td><td></td></thead></table>");

			return this;
		},

		addItem: function(){
			var file = new File();
			this.collection.add(file);
		},

		appendItem: function(){
			$('table', this.el).append("<tr><td><input type=\"file\" class=\"file\"></td><td>Title: <input type=\"text\" id=\"title\"><p><small>Type: video/mpeg</small></p></td><td><div class=\"progress progress-striped active\" style=\"width: 200px; margin-bottom: 8px;\"><div class=\"bar\" style=\"width: 49%\"></div></div><p><small>200000 MB</small></p></td><td><a class=\"btn btn-success\" id=\"queue\" href=\"#\" style=\"float: right;\"><i class=\"icon-upload icon-white\"></i>  Queue</a></td></tr>");
		},

		queue: function(){
			var file = $(this.el).children('.file').val();
			var queue = {
				file: file,
			}
			this.model.set(queue)
			console.log(this.model);
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
