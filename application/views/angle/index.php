<!DOCTYPE html>
	<html ng-app="todoApp">
	<head>
		<title>SkyNet</title>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta content="width=device-width, initial-scale=1" name="viewport"/>
		<link href="//fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="/theme/orange/assets/css/application.min.css" type="text/css" rel="stylesheet"/>
		<link href="/theme/orange/assets/plugins/flash-msg/flash-msg.min.css" type="text/css" rel="stylesheet"/>
		<link href="/theme/orange/assets/plugins/notify/notify.min.css" type="text/css" rel="stylesheet"/>
		<link href="/theme/orange/assets/plugins/table_sort/table_sort.min.css" type="text/css" rel="stylesheet"/>
	</head>
	<body>
		<div class="container">
			<h2>Todo</h2>
			<div ng-controller="TodoListController as todoList">
				<span>{{todoList.remaining()}} of {{todoList.todos.length}} remaining</span>
				[ <a href="" ng-click="todoList.archive()">archive</a> ]
				<ul class="unstyled">
					<li ng-repeat="todo in todoList.todos">
						<label class="checkbox">
							<input type="checkbox" ng-model="todo.done">
							<span class="done-{{todo.done}}">{{todo.text}}</span>
						</label>
					</li>
				</ul>
				<form ng-submit="todoList.addTodo()">
					<input type="text" ng-model="todoList.todoText"  size="30" placeholder="add new todo here">
					<input class="btn-primary" type="submit" value="add">
				</form>
			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jStorage/0.4.12/jstorage.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/tinysort/3.1.4/tinysort.js" type="text/javascript" charset="utf-8"></script>

		<script src="/theme/orange/assets/js/application.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/theme/orange/assets/plugins/bound-table-search/bound-table-search.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/theme/orange/assets/plugins/flash-msg/jquery.bootstrap.flash-msg.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/theme/orange/assets/plugins/notify/notify.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/theme/orange/assets/plugins/table_remember_position/table_remember_position.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/theme/orange/assets/plugins/table_sort/table_sort.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/theme/orange/assets/plugins/table_sticky_header/jquery.stickytableheaders.min.js" type="text/javascript" charset="utf-8"></script>

		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.js" type="text/javascript" charset="utf-8"></script>
		<script src="/hard/app.js" type="text/javascript" charset="utf-8"></script>
		<script>
			document.addEventListener("DOMContentLoaded",function(e){
			});
		</script>
	</body>
</html>