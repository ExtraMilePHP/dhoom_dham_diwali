<?php

class EventRegister {

	/*Games Diwali Functions Start*/
    public function updateName($post,$connPdo, $objPdoClass){
       $sql="INSERT INTO `navarang_userb` (`email`, `active`, `created_at`) VALUES ('".$post['username']."', '1','".date('Y-m-d H:i:s')."');";     
        $connPdo->query($sql);
    }

	public function getuserbingo($userid,$connPdo,$objpdoClass) {
			$query = "SELECT id from diwali_games_bingo WHERE userid='" . $userid . "' AND complete=1";
			$res = $connPdo->query($query);
			$result = $res->fetchAll();
			if(!empty($result)){
				echo true;
			}else{
				echo false;
		}
	}	
	public function addUserPointsBingo($post,$connPdo,$objpdoClass) {
		if($post['one']=='' || $post['two']=='' || $post['three']=='' || $post['four']=='' || $post['five']=='' || $post['six']=='' || $post['seven']=='' || $post['eight']=='' || $post['nine']=='' || $post['ten']=='' || $post['eleven']=='' || $post['twelve']==''){
			return false;
		}else{
			$sql = "INSERT INTO diwali_games_bingo(userid , one , two, three, four,five,six,seven,eight,nine,ten,eleven,twelve,createddate,complete,name,email,location,agent_code) VALUES ('" . $post['userid'] . "' , '" . $post['one'] . "' , '" . $post['two'] . "','" . $post['three'] . "','" . $post['four'] . "','" . $post['five'] . "','" . $post['six'] . "','" . $post['seven'] . "','" . $post['eight'] . "','" . $post['nine'] . "','" . $post['ten'] . "','" . $post['eleven'] . "','" . $post['twelve'] . "','".date('Y-m-d H:i:s')."' ,1,'" . $post['name'] . "','" . $post['email'] . "','" . $post['location'] . "','" . $post['agent_code'] . "')";
			$res = $connPdo->exec($sql);
			return true;
		}
	}
	
	public function fetchbingoPrintExist($userid,$connPdo) {
			$query = "SELECT * from diwali_games_bingo WHERE userid='" . $userid . "' AND complete=1 AND print=1";
			$res = $connPdo->query($query);
			$result = $res->fetchAll();
			if(!empty($result)){
				return true;
			}else{
				return false;
		}
	}
	
	public function fetchbingo($userid,$connPdo,$objpdoClass) {
			//$query = "SELECT * from diwali_games_bingo WHERE userid='" . $userid . "' AND complete=1 AND print=1";
			$query = "SELECT * from diwali_games_bingo WHERE userid='" . $userid . "' AND complete=1 AND print=0";
			$res = $connPdo->query($query);
			$result = $res->fetchAll();
			if(!empty($result)){
				return $result;
			}else{
				return false;
		}
	}
	
	public function updateBingoPrint($userid,$connPdo) {
        $sql = "Update diwali_games_bingo set print = '1', end_date = '".date('Y-m-d H:i:s')."' where userid='".$userid."'";
        $res = $connPdo->exec($sql);
    }
	
	public function updateinputs($userid,$inputs,$identify,$connPdo) {
			switch($inputs){
			 case 1:
				$column = 'one_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}	
				break;
			case 2:
				$column = 'two_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 3:
				$column = 'three_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 4:
				$column = 'four_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 5:
				$column = 'five_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 6:
				$column = 'six_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 7:
				$column = 'seven_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 8:
				$column = 'eight_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 9:
				$column = 'nine_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 10:
				$column = 'ten_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 11:
				$column = 'eleven_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break;
			case 12:
				$column = 'twelve_color';
				if($identify=='disselect'){
					$inputs='';
				}else{
					$inputs=$inputs;
				}
				break; 
			}
        $sql = "Update diwali_games_bingo set $column = '$inputs' where userid='".$userid."'";
        $res = $connPdo->exec($sql);
    }
	/*Games Diwali Functions End*/
    public function registerTeam($post, $connPdo, $objPdoClass) {
        $teamId = "NAV" . date("dmYHis");

        Logger::info("pdo [eventRegistration] : Form Submitted with values  " . serialize($post));
        $arrBindingValues = array();
        $arrBindingValues[':teamid'] = $teamId;
        $arrBindingValues[':teamname'] = $post['teamname'];
        $arrBindingValues[':eventtype'] = $post['eventtype'];
        $arrBindingValues[':remark'] = $post['remark'];
        $arrBindingValues[':createddate'] = date('Y-m-d H:i:s');

        $eventQuery = "INSERT INTO " . EVENT_REGISTRATION_TABLE . " (teamid, teamname, eventtype, remark, createddate) 
                      VALUES(:teamid, :teamname, :eventtype, :remark, :createddate)";
        $result = $objPdoClass->exceuteInsertUpdate($connPdo, $eventQuery, $arrBindingValues);

        if ($result == 1) {
            $resutlEventUser = $this->registerUserMapping($post, $connPdo, $objPdoClass, $teamId);
        }
        return $result;
    }

    public function registerUserMapping($post, $connPdo, $objPdoClass, $teamId) {
        $numberUser = $post['numberuser'];
        unset($post['eventtype'], $post['teamname'], $post['Submit'], $post['numberuser']);

        for ($i = 1; $i <= $numberUser; $i++) {
            $arrBindingValues = array();
            if ($post['employeename_' . $i . ''] != "") {
                $arrBindingValues[':teamid'] = $teamId;
                $arrBindingValues[':employeename_' . $i . ''] = $post['employeename_' . $i . ''];
                $arrBindingValues[':poornataid_' . $i . ''] = $post['poornataid_' . $i . ''];
                $arrBindingValues[':department_' . $i . ''] = $post['department_' . $i . ''];
                $arrBindingValues[':contactno_' . $i . ''] = $post['contactno_' . $i . ''];

                $eventUserQuery = "INSERT INTO " . EVENT_USER_MAPPING_TABLE . "(teamid, employeename, poornataid, department, contactno)
                          VALUES(:teamid, :employeename_" . $i . ", :poornataid_" . $i . ", :department_" . $i . ", :contactno_" . $i . ")";
                $resutlEventUser = $objPdoClass->exceuteInsertUpdate($connPdo, $eventUserQuery, $arrBindingValues);
            }
        }
        return $resutlEventUser;
    }

    public function upadateUser($post, $connPdo, $objPdoClass, $objmessageClass) {
        $data = $post;
        $rules = array(
            "name" => "Require",
            "office" => "Require",
            "gender" => "Require"
        );

        $objValidator = new Validator($rules);

        if (!$objValidator->isValid($data)) {
            $errors = $objValidator->ErrorFields();

            $strError = $objValidator->formatError($errors);
            $objmessageClass->add('e', $strError);
        } else {
            $arrBindingValues = array();
            $arrBindingValues[':userid'] = $post['userid'];
            $arrBindingValues[':name'] = $post['name'];
            $arrBindingValues[':office'] = $post['office'];
            $arrBindingValues[':gender'] = $post['gender'];
            $arrBindingValues[':updatestatus'] = 1;

            $updateUser = "UPDATE " . USER_LOGIN . " SET name=:name, office=:office, gender=:gender, updatestatus=:updatestatus WHERE id=:userid";
            $resutlEventUser = $objPdoClass->exceuteInsertUpdate($connPdo, $updateUser, $arrBindingValues);
            return $resutlEventUser;
        }
    }

    public function uploadImage($post, $files, $connPdo, $objPdoClass, $objmessageClass) {
        $data = $post;
        $rules = array(
            "uploadimage" => "Require"
        );
        if ($files['uploadimage']['size'] == 2097152):

        endif;

        $objValidator = new Validator($rules);

        if (!$objValidator->isValid($data)) {
            $errors = $objValidator->ErrorFields();

            $strError = $objValidator->formatError($errors);
            $objmessageClass->add('e', $strError);
        } else {
            if ($files['uploadimage']['name'] != "") {
                $arrBindingValues = array();
                $arrBindingValues[':userid'] = $post['userid'];
                $arrBindingValues[':createddate'] = date('Y-m-d');

                $getTodayUpload = "SELECT id, image FROM " . USERIMAGEMAPPING . " WHERE userid=:userid AND DATE(created_date)=:createddate";
                $result = $objPdoClass->exceuteQuery($connPdo, $getTodayUpload, $arrBindingValues);
                if (count($result) == 0) {
                    $imageName = $this->createImage($post, $files);
                    if ($imageName != "") {
                        $arrBindingValues = array();
                        $arrBindingValues[':userid'] = $post['userid'];
                        $arrBindingValues[':image'] = $imageName;
                        $arrBindingValues[':createddate'] = date('Y-m-d H:i:s');

                        $uploadImageQuery = "INSERT INTO " . USERIMAGEMAPPING . "(userid, image, created_date)
                            VALUES(:userid, :image, :createddate)";
                        $result = $objPdoClass->exceuteInsertUpdate($connPdo, $uploadImageQuery, $arrBindingValues);
                    }
                } else {
                    $fullPath = "images/userimage/" . $result[0]['image'];
                    unlink($fullPath);
                    $imageName = $this->createImage($post, $files);

                    $arrBindingValues = array();
                    $arrBindingValues[':image'] = $imageName;
                    $arrBindingValues[':id'] = $result[0]['id'];
                    $arrBindingValues[':updateddate'] = date('Y-m-d H:i:s');

                    $updateImage = "UPDATE " . USERIMAGEMAPPING . " SET image=:image, updated_date=:updateddate WHERE id=:id";
                    $result = $objPdoClass->exceuteInsertUpdate($connPdo, $updateImage, $arrBindingValues);
                }

                return $result;
            }
        }
    }

    public function createImage($post, $files) {

        $extension = pathinfo($files['uploadimage']['name'], PATHINFO_EXTENSION);
        $imageName = $post['userid'] . "_" . date('YmdHis') . "." . $extension;
        $fullPath = "images/userimage/" . $imageName;

        $move = move_uploaded_file($files['uploadimage']['tmp_name'], $fullPath);
        if ($move) {
            return $imageName;
        } else {
            return "";
        }
    }

    public function getOfficeDetails($officeId, $connPdo, $objPdoClass) {
        $arrBindingValues = array();
        $arrBindingValues[':office'] = $officeId;

        $query = "SELECT *
                    FROM " . OFFICEMASTER . "
                    WHERE id='" . $officeId . "'";

        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function getawardDetails($awardid, $connPdo, $objPdoClass) {
        $query = "SELECT id,starry_awards FROM starry_swards WHERE id= '" . $awardid . "'";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function getUploadImage($officeId, $connPdo, $objPdoClass) {


        $query = "SELECT uim.id, uim.image, COUNT(uil.imageid) AS likecount, um.name
                    FROM " . USER_LOGIN . " um 
                    INNER JOIN " . USERIMAGEMAPPING . " uim ON uim.userid=um.id AND DATE(uim.created_date)='" . date('Y-m-d') . "'
                    LEFT JOIN " . USERIMAGELIKE . " uil ON uil.imageid=uim.id
                    WHERE um.office='" . $officeId . "'
                    GROUP BY uim.id";

        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function getAwarImage($awardId, $connPdo) {
        // $query = "SELECT * FROM `award_maping` awd LEFT JOIN awardimagelike awdli ON  WHERE `awardid` ='".$awardId."'";
        $query = "SELECT count( awal.`awdimgid` ) AS likecount, awd.`name` , awd.`id` , awd.`photos`
                    FROM `award_maping` awd
                    INNER JOIN starry_swards sty ON awd.awardid = sty.`id`
                    LEFT JOIN awardimagelike awal ON awd.`id` = awal.`awdimgid` WHERE awd.awardid = '" . $awardId . "' GROUP BY awd.`id` ";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function checkForLike($imageid, $userid, $connPdo, $objPdoClass) {
        $arrBindingValues = array();
        $arrBindingValues[':imageid'] = $imageid;
        $arrBindingValues[':userid'] = $userid;

        $query = "SELECT COUNT(userid) AS likecount FROM " . USERIMAGELIKE . " WHERE imageid=:imageid AND userid=:userid";
        $result = $objPdoClass->exceuteQuery($connPdo, $query, $arrBindingValues);
        return $result;
    }

    public function checkawardLike($awdimgid, $userid, $connPdo) {

        $query = "SELECT * FROM `awardimagelike` WHERE `awdimgid` = '" . $awdimgid . "' AND `userid`= '" . $userid . "'";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function addUserImageLike($imageid, $userid, $connPdo, $objPdoClass) {
        $arrBindingValues = array();
        $arrBindingValues[':imageid'] = $imageid;
        $arrBindingValues[':userid'] = $userid;
        $arrBindingValues[':createddate'] = date('Y-m-d H:i:s');

        $query = "INSERT INTO " . USERIMAGELIKE . "(imageid, userid, created_date) VALUES(:imageid, :userid, :createddate)";
        $result = $objPdoClass->exceuteInsertUpdate($connPdo, $query, $arrBindingValues);

        return $result;
    }

    public function addAwardimage($imageid, $userid, $connPdo) {
        $insert = "INSERT INTO `awardimagelike`(`awdimgid`, `userid`) VALUES (" . $imageid . "," . $userid . ")";
        $res = $connPdo->exec($insert);
        return $res;
    }

    public function getOfficeList($connPdo, $objPdoClass) {
        $arrBindingValues = array();
        $query = "SELECT id, office_name FROM " . OFFICEMASTER . " ";
        $result = $objPdoClass->exceuteQuery($connPdo, $query, $arrBindingValues);

        return $result;
    }

    public function getSingleImageCount($imageid, $connPdo, $objPdoClass) {
        $arrBindingValues = array();
        $arrBindingValues[':imageid'] = $imageid;

        $query = "SELECT COUNT(userid) AS likecount FROM " . USERIMAGELIKE . " WHERE imageid=:imageid";

        $result = $objPdoClass->exceuteQuery($connPdo, $query, $arrBindingValues);

        return $result;
    }

    public function getSingleAwardImageCount($imageid, $connPdo) {
        $query = "SELECT COUNT(id) AS likecount FROM `awardimagelike` WHERE awdimgid = '" . $imageid . "'";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function add_Event($post) {
        $sql = "INSERT INTO register(PoornataID,EmployeeName,TeamName,Department,Contactno,type) VALUES ('" . $post['poornata_id'] . "', '" . $post['employee_name'] . "','" . $post['Team_Name'] . "','" . $post['department'] . "','" . $post['contact_no'] . "','" . $post['hiden'] . "')";
        return $sql;
    }

    public function getAllActiveUser($post, $connPdo) {
        $sql = "SELECT * from  " . USER_LOGIN . " WHERE username='" . $post['username'] . "' AND updatestatus = '1' ";
        $res = $connPdo->query($sql);
        $result = $res->fetchAll();
        return $result;
    }

    public function GetAllOfficeList() {

        $sql = "SELECT * from  " . OFFICEMASTER . " ";

        return $sql;
    }

    public function alluser($connPdo) {
        $query = "SELECT * from navarang_userb where active=0 AND email!='' order by email";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function insertPuzzle($userid, $connPdo) {
        $sql = "INSERT INTO na_puzzle(userid) VALUES ('" . $userid . "')";
        $res = $connPdo->exec($sql);
        return $res;
    }

    public function insertReadle($userid, $connPdo) {
        $sql = "INSERT INTO readle(userid , readletext , type) VALUES ('" . $userid . "' , '" . $_POST['readle'] . "' , '" . $_POST['riddleshidden'] . "'  )";
        $res = $connPdo->exec($sql);
        return $res;
    }

    public function insertlastWordd($userid, $connPdo) {
        $sql = "INSERT INTO lastword(userid , lastword) VALUES ('" . $userid . "' , '" . $_POST['lastword'] . "')";
        $res = $connPdo->exec($sql);
        return $res;
    }

    public function insertspotdiff($userid, $connPdo) {
        $sql = "INSERT INTO spotdiff(userid , spotdiff) VALUES ('" . $userid . "' , '" . $_POST['spotdiff'] . "')";
        $res = $connPdo->exec($sql);
        return $res;
    }

    public function insertcrossword($userid, $connPdo) {
        $sql = "INSERT INTO crossword(userid , crossword) VALUES ('" . $userid . "' , '" . $_POST['crossword'] . "')";
        $res = $connPdo->exec($sql);
        return $res;
    }

    public function navarangImageuploads($post, $connPdo, $objPdoClass, $objmessageClass) {
    //echo '<pre>';
    //print_r($post);die;
    
            $query = "SELECT * FROM `navarang_userb` WHERE email='" . $post['to_email'] . "'";
            $res = $connPdo->query($query);
            $result = $res->fetchAll();
            
       if (!empty($post['to_email'])) {
	   
           switch($post['category']){
           case 'workholic1':
           $category='Limitless - All work and No play';
           break;
           case 'workholic2':
           $category='The Dark Knight - Burning the midnight oil everyday';
           break;
           case 'workholic3':
           $category='Love Actually - Addicted Workaholic';
           break;
           case 'workholic4':
           $category='Terminator - Work Overload';
           break;
           
           }
         $sql = "INSERT INTO workaholic_data(userid, to_email,category,message,created_at,to_name,category_detail) VALUES ('" . $_SESSION['userid'] . "', '" . htmlspecialchars(stripcslashes(trim($post['to_email']))) . "' , '" . htmlspecialchars(stripcslashes(trim($post['category']))) . "', '" . htmlspecialchars(stripcslashes(trim($post['message']))) . "' , '" . date('Y-m-d H:i:s') . "', '".$result[0]['name']."' , '".$category	."')";
            $count = $connPdo->exec($sql);
            //echo '<pre>';
           // print_r($result);die;
            $mes = '<html>
		<head>
		  <title></title>
		</head>
		<body>
		  <table>
		    <tr>
		     
		       <td>Dear '.ucwords($result[0]['name']).',<br><br>
			On the occasion of Workaholics Day, you have been nominated for the category of <b>'.$category.'</b>.
			<br/><br/>
			Click <a href="http://engagenowatstaffroom.com/workaholic">here</a> to view your message and see who has nominated you.</br>
			<b>#ScaleItUp</b>
			<br> 
			</td>
		    </tr>
		    
		   
		  </table>
		</body>
		</html>';

            $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

            $mail->IsSMTP(); // telling the class to use SMTP
            
            try {
                 $mail->Host = "smtp.gmail.com"; // SMTP server
                $mail->SMTPDebug = 1;
                $mail->SMTPAuth = true;                   // enable SMTP authentication
                $mail->SMTPSecure = 'ssl';                  // sets the prefix to the servier
                $mail->Host = 'email-smtp.us-west-2.amazonaws.com';       // sets GMAIL as the SMTP server
                $mail->Port = '465';
                $mail->Username = 'AKIAIJZ7UUCVT5CPKSLQ';     // GMAIL username
                $mail->Password = 'AnC03lQq2mxbrF9lQp+OyyMfPktsGstkoBlxW1qoCjb5';        // GMAIL password
                $mail->SetFrom('info@engagenowatstaffroom.com', 'engagenowatstaffroom.com');
                $mail->Subject = 'Workaholics United';
                $mail->MsgHTML($mes);
				//$mail->AddAddress('tushar@openspaceservices.com');
                $mail->AddAddress($result[0]['email']);
                //$mail->AddCC('tushar@openspaceservices.com');
                //$mail->AddCC('hetal.mehta@openspaceservices.com');
                //$mail->AddCC('poojadave@extramile.in');
               // $mail->AddCC('pooja@extramile.in');
                 //$mail->AddCC('lavin@extramile.in');
                $mail->Send();
            } catch (phpmailerException $e) {
                echo $e->errorMessage(); //Pretty error messages from PHPMailer
            } catch (Exception $e) {
                echo $e->getMessage(); //Boring error messages from anything else!
            }
            //echo $e->errorMessage();
            /*
            $to='tushar@openspaceservices.com';
            $subject = "Are you a workaholic?";
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: engagenowatstaffroom" . "\r\n";
			$headers .= "CC: hetal.mehta@openspaceservices.com" . "\r\n";
			mail($to,$subject,$mes,$headers);*/

//echo '<pre>';
     //       print_r($result);die;
            return $count;
        } else {
            echo "Possible file upload attack!\n";
        }


//        echo 'Here is some more debugging info:';
//        print_r($_FILES);
    }

    public function getNavarangUploadImage($connPdo) {

        //$query = "SELECT `id`, `userid`, `name`, `department`, `location`, `imagename`, `created_at` FROM `navarang_name_department_map` ORDER BY id //DESC";
        $query = "SELECT uim.to_name,uim.id, uim.to_email,uim.category,uim.message, COUNT(uil.imageid) AS likecount, um.name FROM navarang_userb um INNER JOIN workaholic_data uim ON uim.userid=um.id  LEFT JOIN userimagelike uil ON uil.imageid=uim.id GROUP BY uim.id ORDER BY uim.id DESC ";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }
    
    public function getTopfiveworkholic1($connPdo){
         $query = "SELECT uim.to_name, COUNT(uil.imageid) AS likecount FROM navarang_userb um INNER JOIN workaholic_data uim ON uim.userid=um.id LEFT JOIN userimagelike uil ON uil.imageid=uim.id WHERE uim.category = 'workholic1' GROUP BY uim.to_name ORDER BY likecount DESC LIMIT 5";
        $res = $connPdo->query($query);
        $result = $res->fetchALL(PDO::FETCH_NUM);
        return $result;   
    }
    
    public function getTopfiveworkholic2($connPdo){
         $query = "SELECT uim.to_name, COUNT(uil.imageid) AS likecount FROM navarang_userb um INNER JOIN workaholic_data uim ON uim.userid=um.id LEFT JOIN userimagelike uil ON uil.imageid=uim.id WHERE uim.category = 'workholic2' GROUP BY uim.to_name ORDER BY likecount DESC LIMIT 5";
        $res = $connPdo->query($query);
        $result = $res->fetchALL(PDO::FETCH_NUM);
        return $result;   
    }
    
    public function getTopfiveworkholic3($connPdo){
         $query = "SELECT uim.to_name, COUNT(uil.imageid) AS likecount FROM navarang_userb um INNER JOIN workaholic_data uim ON uim.userid=um.id LEFT JOIN userimagelike uil ON uil.imageid=uim.id WHERE uim.category = 'workholic3' GROUP BY uim.to_name ORDER BY likecount DESC LIMIT 5";
        $res = $connPdo->query($query);
        $result = $res->fetchALL(PDO::FETCH_NUM);
        return $result;   
    }
    public function getTopfiveworkholic4($connPdo){
         $query = "SELECT uim.to_name, COUNT(uil.imageid) AS likecount FROM navarang_userb um INNER JOIN workaholic_data uim ON uim.userid=um.id LEFT JOIN userimagelike uil ON uil.imageid=uim.id WHERE uim.category = 'workholic4' GROUP BY uim.to_name  ORDER BY likecount DESC LIMIT 5";
        $res = $connPdo->query($query);
        $result = $res->fetchALL(PDO::FETCH_NUM);
        return $result;   
    }
    
    public function getTopfiveworkholic5($connPdo){
         $query = "SELECT um.e_number AS Employee_code, um.name AS Name, um.email AS Email, uim.message, uim.category_detail, COUNT( uil.imageid ) AS  `Like` 
FROM navarang_userb um
INNER JOIN workaholic_data uim ON uim.userid = um.id
LEFT JOIN userimagelike uil ON uil.imageid = uim.id
GROUP BY uim.category";
        $res = $connPdo->query($query);
        $result = $res->fetchALL(PDO::FETCH_NUM);
        return $result;   
    }

    public function getNavarangUploadImageCount($connPdo, $id) {
        $query = "SELECT * FROM `userimagelike` WHERE imageid='" . $id . "'";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function getCount($connPdo) {

        $query = "SELECT * FROM `navarang_name_department_map` WHERE userid = '" . $_SESSION['userid'] . "'";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        return $result;
    }

    public function userRegister($post, $connPdo) {

        $query = "SELECT * FROM `navarang_user` WHERE email='" . $post['email'] . "'";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        if (count($result) > 0):
            $rand = rand(1001, 9000);
            $sql = "UPDATE navarang_user SET password='" . $rand . "' WHERE email ='" . $post['email'] . "'";
            $count = $connPdo->exec($sql);
            $mes = '<html>
<head>
  <title></title>
</head>
<body>
  <p>Your Password </p>
  <table>
    <tr>
     
      <td>' . $rand . '</td>
    </tr>
    
   
  </table>
</body>
</html>';

            $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

            $mail->IsSMTP(); // telling the class to use SMTP
            
            try {
                $mail->Host = "smtp.gmail.com"; // SMTP server
                $mail->SMTPDebug = 1;
                $mail->SMTPAuth = true;                   // enable SMTP authentication
                $mail->SMTPSecure = 'ssl';                  // sets the prefix to the servier
                $mail->Host = 'bh-30.webhostbox.net';       // sets GMAIL as the SMTP server
                $mail->Port = '465';
                $mail->Username = 'kotakamc@engageatkotakamc.com';     // GMAIL username
                $mail->Password = 'OpenSpace123$';        // GMAIL password
                $mail->SetFrom('kotakamc@engageatkotakamc.com', 'engageatkotakamc.com');
                $mail->Subject = 'Your Password';
                $mail->MsgHTML($mes);
                $mail->AddAddress($post['email']);
                $mail->Send();
            } catch (phpmailerException $e) {
                echo $e->errorMessage(); //Pretty error messages from PHPMailer
            } catch (Exception $e) {
                echo $e->getMessage(); //Boring error messages from anything else!
            }

            return $count;
        else:
            echo "<script language='javascript' type='text/javascript'>";
            echo "alert('Your email does not match');";
            echo "</script>";

            $URL = "index.php";
            echo "<script>location.href='$URL'</script>";
        endif;
        
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        exit();
    }
    
    public function addUser($post, $connPdo) {

        $query = "SELECT * FROM `navarang_userb` WHERE email='" . $post['email'] . "'";
        $res = $connPdo->query($query);
        $result = $res->fetchAll();
        if (count($result) == 0):
            $rand = rand(1001, 9000);
            $sql = "INSERT INTO navarang_userb(e_number,name, email) VALUES('1','".$post['name']."', '".$post['email']."')";
            $count = $connPdo->exec($sql);
            return $count;
        else:
            echo "<script language='javascript' type='text/javascript'>";
            echo "alert('Email already in database');";
            echo "</script>";

            $URL = "adduser.php";
            echo "<script>location.href='$URL'</script>";
        endif;
        
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        exit();
    }

}

?>