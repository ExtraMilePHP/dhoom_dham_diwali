<?php
    class Logger{               
        public static function info($msg){            
            $filename = date("d-m-Y") . ".log";            
            $completepath = $_SERVER['DOCUMENT_ROOT'] . "/log" .  $filename;
            try {
                $file = fopen($completepath, a);
                $modifiedText = date("M d Y H:i:s") . " ac [".$_SERVER['REMOTE_ADDR']."] [".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."] [info] " . $msg . "\r\n";
                fwrite($file,$modifiedText);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
    }    
?>
