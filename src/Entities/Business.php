<?php
namespace Becee\Entities;
class Business
{

	public $name = NULL;
	public $id = NULL; 
	public $longitude = NULL;
	public $latitude = NULL;
	public $manager = NULL;
	public $website = NULL;
	public $tags = NULL;
	public $images = NULL;
	public $visits = NULL;
	public $comments = NULL;
	public $features = NULL;

	public function add_features($new_features)
	{
		$this->$features = array_merge($this->$features, $new_features);
	}

	public function add_tags($new_tags)

	{
		$this->$tags = array_merge($this->$tags, $new_tags);
	}

	
}