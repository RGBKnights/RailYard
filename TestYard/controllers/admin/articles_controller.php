<?php

class ArticlesController extends Controller {
	
	function before_action($request, $response) {
		
	}
	
	function index() {
		//$query = new Queryable(array(1,2,3,4,5,6,7,8,9));
		//$result = $query->where(function($current) { return !($current % 2); } )->average();
			
		//$person = new stdClass();
		//$person->fname = $this->request->parameter_or_default('fname', "Jamie");
		//$person->lname = $this->request->parameter_or_default('lname', "Webster");
		//$person->age = $this->request->parameter_or_default('age', 25);
		//$person->gender = $this->request->parameter_or_default('gender', "male");
		
		$this->response->flash("Auto Redirection from index to view.");
		$this->redirect(array('action' => 'view'));
	}
	
	function view() {
		$people = array();
		$person = new stdClass();
		$person->fname = "Jamie";
		$person->lname = "Webster";
		$person->age = 25;
		$person->gender = "male";
		$people[] = $person;
		
		$person = new stdClass();
		$person->fname = "Emily";
		$person->lname = "Stoltz";
		$person->age = 22;
		$person->gender = 'female';
		$people[] = $person;
		
		$person = new stdClass();
		$person->fname = "Dan";
		$person->lname = "Kelly";
		$person->age = 22;
		$person->gender = 'male';
		$people[] = $person;
		
		$person = new stdClass();
		$person->fname = "Mike";
		$person->lname = "White";
		$person->age = 30;
		$person->gender = 'male';
		$people[] = $person;
		
		$this->set('Message', "Hello World!!!");
		
		$this->response->layout->head->references->scripts[] = $this->script_for_controller();
		$this->response->layout->head->references->scripts[] = $this->script_for_action();
		
		$this->response->layout->head->scripts[] = $this->render_for_action($people, 'js');
		$this->response->layout->body->content = $this->render_for_action($people);
	}
}