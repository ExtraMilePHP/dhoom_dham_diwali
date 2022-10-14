<?php

class PdoClass {

    public function exceuteQuery($connPdo, $query, $arrBindingValues, $offsetlimit = false) {        
        try {
            $stmt = $connPdo->prepare($query);

            if (!$offsetlimit) {
                unset($arrBindingValues[':offset']);
                unset($arrBindingValues[':limit']);
            }            
            foreach ($arrBindingValues as $key => $value) {
                if ($value != "") {
                    //echo $key . "-----" . $value . "<br/>";
                    if ($key === ":limit" || $key === ":offset") {
                        $stmt->bindValue("" . $key . "", $value, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue("" . $key . "", $value);
                    }
                }
            }
            $stmt->execute();
            $resultArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Logger::info("pdo [executeQuery] : Success");
            return $resultArr;
        } catch (PDOException $e) {            
            Logger::info("pdo [executeQuery] : Failed " . $e->getMessage());
            die($e->getMessage());
        }
    }
    
    public function exceuteInsertUpdate($connPdo, $query, $arrBindingValues) {
        try {
            $stmt = $connPdo->prepare($query);
                        
            foreach ($arrBindingValues as $key => $value) {                
                $stmt->bindValue("" . $key . "", $value);
            }
            $result = $stmt->execute();            
            Logger::info("pdo [exceuteInsertUpdate] : Success");
            return $result;
        } catch (PDOException $e) {            
            Logger::info("pdo [exceuteInsertUpdate] : Failed " . $e->getMessage());
            die($e->getMessage());
        }
    }
    
    public function unLinkEmpty($arrBindingValues){        
        foreach ($arrBindingValues as $key => $value) {
            if ($value == "") {                
                unset($arrBindingValues[$key]);
            }
        }        
        return $arrBindingValues;
    }

}
?>

