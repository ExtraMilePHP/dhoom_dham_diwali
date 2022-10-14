<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of register_Class
 *
 * @author openspace014
 */
class register_Class {
    public function add_Event($re){
        $sql="INSERT INTO register(PoornataID,EmployeeName,TeamName,Department,Contactno,type) VALUES ('".$re['Poornata_ID']."', '".$re['Employee_Name']."','".$re['Team_Name']."','".$re['Department']."','".$re['Contact_no']."','".$re['hiden']."')";
        return $sql;
    }
}
