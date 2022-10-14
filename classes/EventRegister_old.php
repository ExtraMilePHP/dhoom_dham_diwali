<?php
class EventRegister{
    public function registerTeam($post, $connPdo, $objPdoClass){        
        $teamId = "NAV" . date("dmYHis");
        
        Logger::info("pdo [eventRegistration] : Form Submitted with values  " . serialize($post));
        $arrBindingValues = array();
        $arrBindingValues[':teamid'] = $teamId;
        $arrBindingValues[':teamname'] = $post['teamname'];
        $arrBindingValues[':eventtype'] = $post['eventtype'];
        $arrBindingValues[':createddate'] = date('Y-m-d H:i:s');        
        
        $eventQuery = "INSERT INTO ".EVENT_REGISTRATION_TABLE." (teamid, teamname, eventtype, createddate) 
                      VALUES(:teamid, :teamname, :eventtype, :createddate)";        
        $result = $objPdoClass->exceuteInsertUpdate($connPdo, $eventQuery, $arrBindingValues);
        
        if($result == 1){            
            $resutlEventUser = $this->registerUserMapping($post, $connPdo, $objPdoClass, $teamId);
        }
        return $result;
    }
    
    public function registerUserMapping($post, $connPdo, $objPdoClass, $teamId){
        $numberUser = $post['numberuser'];
        unset($post['eventtype'],  $post['teamname'],  $post['Submit'], $post['numberuser']);        
                
        for($i = 1; $i <= $numberUser; $i++){
            $arrBindingValues = array();
            if($post['employeename_'.$i.''] != ""){
                $arrBindingValues[':teamid'] = $teamId;
                $arrBindingValues[':employeename_'.$i.''] = $post['employeename_'.$i.''];
                $arrBindingValues[':poornataid_'.$i.''] = $post['poornataid_'.$i.''];
                $arrBindingValues[':department_'.$i.''] = $post['department_'.$i.''];
                $arrBindingValues[':contactno_'.$i.''] = $post['contactno_'.$i.'']; 
                
                $eventUserQuery = "INSERT INTO ".EVENT_USER_MAPPING_TABLE."(teamid, employeename, poornataid, department, contactno)
                          VALUES(:teamid, :employeename_".$i.", :poornataid_".$i.", :department_".$i.", :contactno_".$i.")";        
                $resutlEventUser = $objPdoClass->exceuteInsertUpdate($connPdo, $eventUserQuery, $arrBindingValues);
            }
        }
        return $resutlEventUser;
    }
}
?>