// Note this works fine in an ES6 environment, if you have 
// requirements that do not meet this specification then you
// must use cls = cls || false

// FIX THIS FOR DYNAMIC ARGUMENT PARSING / REPLACE 

var Messages = function(identifier, cls=false){
  this.cont = (cls === false) ? $("#" + identifier): $("." + identifier);
  // this.regex = /\{([^}]+)\}/g;
  this.template = "<div class='alert alert-{css_class}' role='alert'>{msg}</div>";
  // this.matches = this.regex.exec(this.template);
};

$.extend(Messages.prototype, {

	fix_string: function(){
		console.log(css_class, msg);
		var arguments = arguments[0];
		var css_class = arguments[0];
		var msg = arguments[1];
		var temp = this.template;
		// for (var i=0; i < this.matches.length; i++){
			// console.log(this.matches[i]);
			// temp = temp.replace("{" + this.matches[i] + "}", arguments[i]);
			// console.log(temp);
		// }
		return temp.replace('{css_class}', css_class).replace("{msg}", msg);
	},
	add: function(){
		this.cont.append(this.fix_string(arguments));
	},
	delete: function(){
		console.log("CALLED DELETE IN MESSAGES");
	},
});

var messages = new Messages('messages-container');