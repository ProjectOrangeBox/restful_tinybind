<?php

// https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js

class AngleController extends \MY_Controller
{
	public function indexAction() : void
	{
		ci('page')->render();
	}

}
