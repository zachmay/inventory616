/*jslint vars: true, plusplus: true, devel: true, nomen: true, indent: 4, maxerr: 50 */
/*jslint undef: true, white: true, browser: true */
/*global Backbone */
/*jslint nomen: true */
/*global _ */
/*global $ */
/*global define */
/* jslint unused: false */
var Create = Backbone.Model.extend( {
	defaults: {
	building : '',
	roomno  : ''
}
} );
var create = new Create();
var Creates = Backbone.Collection.extend({});
var create1 = new Create( {
	building: 'Powell1 ',
	roomno: '102'
});
var create2 = new Create( {
	building: ' Powell2',
	roomno: '103'
});
var creates = new Creates([create1, create2]);

var CreateView = Backbone.View.extend( {
	model: new Create(),
	tagname: 'tr',
	initialize: function() {
		'use strict';
		this.template = _.template($('.facilities-template').html());
	},
	render: function() {
		'use strict';
		this.$el.html(this.template(this.model.toJSON()));
		
	}
});
var CreatesView = Backbone.View.extend( {
	model: creates,
	el: $('.facilities'),
	initialize: function() {
		'use strict';
		this.model.on('add', this.render, this);
	},
	render: function() {
		'use strict';
		 self = this;
		this.$el.html('');
		_.each(this.model.toArray(), function(create) {
			self.$el.append((new CreateView({model: create})).render.$el);
		});
		return this;
	}
});

var createsView = new CreatesView();

$(document).ready(function() {
	'use strict';
	$('.add-create').on('click', function() {
		var create = new Create( {
			building: $('.building-input').val(),
			roomno: $('.roomno-input').val()
		});
		$('.building-input').val('');
		$('.roomno-input').val('');
		console.log(create.toJSON());
		creates.add(create);
	});
});
