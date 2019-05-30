var trigger = {
	register: function(name,closure) {
		document.addEventListener(name,function(e){
			closure(e,e.detail);
		});
	},
	fire: function(name,args) {
		document.dispatchEvent(new CustomEvent(name,{detail:args}));
	},
};