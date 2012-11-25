<?php
	//Include API Class
	require_once("API.php");

	class Tag extends API {

		public function __construct() {
			//Parent Constructor
			parent::__construct();

			//Connect to the database
			$this->link = $this->db_connect();
		}

		public function addTag() {
			$image_id = $this->load('image_id');
			$cat_id = $this->load('cat_id', false); // If category
			$user_id = $this->load('user_id', false); // If member

			$this->forceauth();

			$res = mysql_query("SELECT owner_id FROM images WHERE image_id='$image_id'");
			if($this->memberid != mysql_result($res, 0, owner_id))
				$this->response(json_encode(array('msg' => 'You do not own this image')), 403);

			if($tag != "") { // Add category tag
				$res = mysql_query("INSERT INTO category_tags VALUES ($image_id, $cat_id)");
				if(!$res) {
					$err = mysql_errno();
					if($err == 1452)
						$this->response(json_encode(array('msg' => 'Image and/or tag do not exist')), 404);
					if($err == 1062)
						$this->response(json_encode(array('msg' => 'Image already has specified tag')), 409);

					$this->response(json_encode(array('msg' => 'Unknown error - try again')), 503);
				}
			}
			else if($user_id != "") { // Add member tag
				$res = mysql_query("INSERT INTO mem_tags VALUES ($user_id, $image_id, NOW())");
				if(!$res) {
					$err = mysql_errno();
					if($err == 1452)
						$this->response(json_encode(array('msg' => 'Image and/or member do not exist')), 404);
					if($err == 1062)
						$this->response(json_encode(array('msg' => 'User already tagged in image')), 409);

					$this->response(json_encode(array('msg' => 'Unknown error - try again')), 503);
				}
			}

			$this->response('', 200);
		}
		public function deleteTag() {
			$image_id = $this->load('image_id');
			$cat_id = $this->load('cat_id', false); // If category
			$user_id = $this->load('user_id', false); // If member

			$this->forceauth();

			$res = mysql_query("SELECT owner_id FROM images WHERE image_id='$image_id'");
			if($this->memberid != mysql_result($res, 0, owner_id) && $this->memberid != $user_id)
				$this->response(json_encode(array('msg' => 'You do not own this image')), 403);

			if($user_id != "") // Delete member tag
				$res = mysql_query("DELETE FROM mem_tags where image_id=$image_id and member_id=$user_id");
			else if($tag != "") // Delete category tag	
				$res = mysql_query("DELETE FROM category_tags WHERE image_id='$image_id' and category_tag='$cat_id'");

			if(!$res)
				$this->response(json_encode(array('msg' => 'Unknown error - try again')), 503);

			$this->response('', 200);
		}

		public function addFavorite() {
			$image_id = $this->load('image_id');

			$this->forceauth();

			$res = mysql_query("INSERT INTO favorites VALUES ($image_id, $this->memberid)");
			if(!$res) {
				$err = mysql_errno();
				if($err == 1452)
					$this->response(json_encode(array('msg' => 'Image does not exist')), 404);
				if($err == 1062)
					$this->response(json_encode(array('msg' => 'You already favorited this image')), 409);
			
				$this->response(json_encode(array('msg' => 'Unknown error - try again')), 503);
			}

			$this->response('', 200);
		}

		public function deleteFavorite() {
			$image_id = $this->load('image_id');

			$this->forceauth();

			$res = mysql_query("DELETE FROM favorites where image_id=$image_id and member_id=$this->memberid");
			if(!$res)
				$this->response(json_encode(array('msg' => 'Unknown error - try again')), 503);
			
			$this->response(json_encode('', 200));
		}

		public function getFavorites() {
			$this->forceauth();

			$res = mysql_query("SELECT image_id FROM favorites WHERE member_id='$this->memberid'");
			if(!mysql_num_rows($res))
				$this->response('', 204); // User has no favorites

			$array = mysql_fetch_array($res);
			$this->response(json_encode($array), 200);
		}
	}

	$api = new Tag;
	$api->process();
?>