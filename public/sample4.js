var Facility = Backbone.Model.extend({
	defaults: {
		building: '',
		room: ''
	}
});

// Backbone Collection

var Facilities = Backbone.Collection.extend({});

// instantiate two Blogs

/*var blog1 = new Blog({
	author: 'Michael',
	title: 'Michael\'s Blog',
	url: 'http://michaelsblog.com'
});
var blog2 = new Blog({
	author: 'John',
	title: 'John\'s Blog',
	url: 'http://johnsblog.com'
});*/

// instantiate a Collection

var facilities = new Facilities();

// Backbone View for one blog

var FacilityView = Backbone.View.extend({
	model: new Facility(),
	tagName: 'tr',
	initialize: function() {
		this.template = _.template($('.facilities-list-template').html());
	},
	events: {
		'click .edit-facility': 'edit',
		'click .update-facility': 'update',
		'click .cancel': 'cancel',
		'click .delete-facility': 'delete'
	},
	edit: function() {
		$('.edit-facility').hide();
		$('.delete-facility').hide();
		this.$('.update-facility').show();
		this.$('.cancel').show();

		var building = this.$('.building').html();
		var room = this.$('.room').html();
		var url = this.$('.url').html();

		this.$('.building').html('<input type="text" class="form-control building-update" value="' + building + '">');
		this.$('.room').html('<input type="text" class="form-control room-update" value="' + room + '">');
	},
	update: function() {
		this.model.set('building', $('.building-update').val());
		this.model.set('room', $('.room-update').val());
	},
	cancel: function() {
		facilitiesView.render();
	},
	delete: function() {
		this.model.destroy();
	},
	render: function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

// Backbone View for all blogs

var FacilitiesView = Backbone.View.extend({
	model: facilities,
	el: $('.facilities-list'),
	initialize: function() {
		var self = this;
		this.model.on('add', this.render, this);
		this.model.on('change', function() {
			setTimeout(function() {
				self.render();
			}, 30);
		},this);
		this.model.on('remove', this.render, this);
	},
	render: function() {
		var self = this;
		this.$el.html('');
		_.each(this.model.toArray(), function(facility) {
			self.$el.append((new FacilityView({model: facility})).render().$el);
		});
		return this;
	}
});

var facilitiesView = new FacilitiesView();

$(document).ready(function() {
	$('.add-facility').on('click', function() {
		var facility = new Facility({
			building: $('.building-input').val(),
			room: $('room-input').val()
		});
		$('.building-input').val('');
		$('.room-input').val('');
		console.log(facility.toJSON());
		facilities.add(facility);
	})
})