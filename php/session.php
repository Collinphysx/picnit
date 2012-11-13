<html>
<?php
        //Include API Class
        require_once("API.php");

        class Session extends API {

                public function __construct() {
                        //Parent Constructor
                        parent::__construct();

                        //Connect to the database
                        $this->link = $this->db_connect();
                }

                public function process() {
                        //Get the action
                        $action = $_POST['action'];

                        //See if method exists in class
                        if(method_exists($this, $action))
                                $this->$action(); //Call if found, php magic
                        else
                                $this->response('',404); //Else send 404 (not found)
                }

		function createSession() {
                        $username = mysql_real_escape_string($_POST['username']);
                        if(isset($_POST['password']))
				$res = mysql_query("SELECT salt FROM members WHERE username='$username' LIMIT 1", $this->link);
				if(!$res)
					goto no_such_user;
				$hashedpass = sha1($password.mysql_result($res, 0, salt);
			}

                        //Ensure all variables needed are present
                        if(!empty($username) && !empty($password)) {
                                session_start();
                                $query = "SELECT member_id from members where username='$username' and password='$password'";
                                $sql = mysql_query($query, $this->link);

                                $_SESSION['member_id'] = mysql_fetch_array($sql, MYSQL_ASSOC);

                                $this->response(200);
                        }
no_such_user:
                        $error = json_encode(array('status' => 'Failed', 'msg' => 'Not logged in'));
                        $this->response($error, 400);
                }

                public function getDataFromSession() {
                        session_start();

                        //Ensure all variables needed are present
                        if(!empty($_SESSION['member_id']){
                                $query = "SELECT username, password from members where member_id='$member_id'";
                                $sql = mysql_query($query, $this->link);
                                $result = mysql_fetch_array($sql, MYSQL_ASSOC);

                                $_POST['username'] = $result['username'];
                                $_POST['password'] = $result['password'];

                                $this->response(200);
                        }

                        $error = json_encode(array('status' => 'Failed', 'msg' => 'No data in session'));
                        $this->response($error, 400);
                }

        $api = new Session;
        $api->process();
?>
</html>