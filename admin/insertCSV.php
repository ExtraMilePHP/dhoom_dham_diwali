<?php

class insertCSV{
 private $filename;
 private $preexist_array;
 private $database_fields;
 private $table_name;
 private $csvType;
 private $arr;
 private $locate_cols;
 private $fill_match=0;
 private $con;
 private $organizationId;
 private $sessionId;
 
 public function __construct($filename,$preexist_array,$database_fields,$table_name,$con,$organizationId,$sessionId){
     $this->filename=$filename;
     $this->preexist_array=$preexist_array;
     $this->database_fields=$database_fields;
     $this->table_name=$table_name;
     $this->preexist_array=$this->preexist_array;
     $this->csvType=array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
     $this->con=$con;
     $this->organizationId=$organizationId;
     $this->sessionId=$sessionId;
 }

 public function run(){
    if(in_array($this->filename["type"],$this->csvType)){
        $this->collectData();
    }else{
        echo "Please use CSV File";
    }
 }

 public function collectData(){
    $this->arr = array(array(),array());
    $num = 0;
    $row = 0;
    $handle = fopen($this->filename['tmp_name'], "r");
    while($data = fgetcsv($handle,1000,",")){   
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            $this->arr[$row][$c] = $data[$c];
        }
        $row++;
    }
    $this->rowAuth();
 }

 public function truncateTable(){
     $table_name=$this->table_name;
     $trunc_pre="TRUNCATE TABLE $table_name";
     if($this->con->query($trunc_pre)){
      $this->insertRows();
      }else{
      echo "error in truncate table";
    }
 }

 public function rowAuth(){
      $header= $this->arr[0];
      $error_array=array();
      $this->locate_cols=array();
      $match=0;
      $fill_match=0;
      for($i=0; $i< count($this->preexist_array); $i++){
        $pushLocation=array_search($this->preexist_array[$i],$header);
        // remeber this 0 treated as empty use is numeric insted
        if(!is_numeric($pushLocation)){
            array_push($error_array,$this->preexist_array[$i]);
        }

        array_push($this->locate_cols,$pushLocation);
        if (in_array($this->preexist_array[$i],$header)){
              $match=$match+1;
        }
      }

      for($i=0; $i<count($this->locate_cols); $i++){
        ${"pointer$i"}=$this->locate_cols[$i];
    }

      if(count($this->preexist_array) == $match){
        $this->insertRows();
      }else{
        echo "not matched "." you must need to add ";
        foreach($error_array as $erros){
            echo $erros." ";
        }     
     }
 }


 public function insertRows(){
    for($i=0; $i<count($this->locate_cols); $i++){
        ${"pointer$i"}=$this->locate_cols[$i];
    }
    $sql_values=array();
    $collect_bingo_words=array();
          for($i=1; $i<count($this->arr); $i++){ 
              for($l=0; $l<count($this->preexist_array); $l++){
                ${"data$l"}=$this->arr[$i][${"pointer$l"}];
                $sql_values[$l]=${"data$l"};
              }
         // $sql_values=array_map('strtolower',$sql_values);    // remove this line if you don't want to lowercase the entire database
          $sql_values = array_map('trim', $sql_values);  
          $currentWord=$sql_values[0];

          // $insert_values= "'".implode("','",$sql_values)."'";
//print_r($sql_values);
//print_r($this->database_fields[1]);
          $organizationId=$this->organizationId;
          $sessionId=$this->sessionId;

          $flag=false;
          if(in_array($currentWord,$collect_bingo_words)){
            $flag=true;
            echo "duplicate word ".$currentWord. " exist";
            }else if(empty($currentWord)){
            $flag=true;
            echo "Csv fields cannot be empty";
            }else if(strlen($currentWord) >18){
            $flag=true;
            echo "World character length limit exceed";
            }
            if(!$flag){
              $this->fill_match=$this->fill_match+1;
              array_push($collect_bingo_words,$currentWord);
            }
          
          // $sql = "INSERT INTO `".$this->table_name."`(organizationId,sessionId," . implode(', ', $this->database_fields) . ") VALUES ('$organizationId','$sessionId',".$insert_values.")";
          // if($this->con->query($sql)){
          //     $this->fill_match=$this->fill_match+1;
          // }
          }
          $words_array=serialize($collect_bingo_words);
          if(sizeof($collect_bingo_words)==50){
                 $sql="update settings SET value='".$words_array."' where sessionId='".$this->sessionId."' and target='values_array'";
                   if($this->con->query($sql)){
                    //  $this->fill_match=$this->fill_match+1;
                  }
          }else{
            $this->fill_match=0;
            echo "50 words/numbers required<br>";
          }
          // print_r($collect_bingo_words);
          $sql_values=array();
          $this->result();
 }

 public function result(){
    if($this->fill_match == 50){
               echo "1010";
    }else{
               echo "something went wrong".mysqli_error($this->con);
    }
 }
}
?>