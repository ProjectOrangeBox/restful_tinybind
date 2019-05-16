var storage = {
	get(key,defaultValue) {
		var value = localStorage.getItem(key);
		return (value) ? value : defaultValue;
	},
	set(key,value) {
		localStorage.setItem(key,value);
	},
	delete(key) {
		localStorage.removeItem(key);
	}
}