<?php

/**
 * The main include file for PrintAnything class
 *
 * PHP version 4 and 5
 *
 * PrintAnything is a class that generates JavaScript, HTML and CSS code to add
 * links and form buttons which send any HTML markup to the printer. The class
 * supports multiple printing contexts for one screen page.
 *
 * PrintAnything PHP Class (c) 2008 Vagharshak Tozalakyan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @version  0.1.1
 * @author   Vagharshak Tozalakyan <vagh@tozalakyan.com>
 * @license  http://www.opensource.org/licenses/mit-license.php
 */

class PrintAnything
{
    var $_context = 0;

    function _prepareString($str)
    {
        $str = str_replace("\r", '', $str);
        $str = str_replace("\n", '\n', $str);
        $str = str_replace("\047", "\\'", $str);
        $str = str_replace('"', '\"', $str);
        return $str;
    }

    function readBody($fname)
    {
        $html = '';
        ob_start();
        if (@readfile($fname)) {
            $html = ob_get_contents();
        }
        ob_end_clean();
        if (preg_match('#<body[^>]*>(.+?)</body>#is', $html, $matches)) {
            $html = $matches[1];
        }
        return $html;
    }

    function addPrintContext($printHtml, $stylesheet = array(), $redirecturl)
    {
        $this->_context += 1;
        $printHtml = $this->_prepareString($printHtml);
        echo '<!-- PRINTING CONTEXT ' . $this->_context . ' -->' . "\n";
        echo '<style type="text/css">' . "\n";
        echo '@media print {' . "\n";
        echo '    #PAScreenOut' . $this->_context . ' { display: none; }' . "\n";
        foreach ($stylesheet as $k => $v) {
            echo '    ' . $k . ' { ' . $v . '}' . "\n";
        }
        echo '}' . "\n";
        echo '@media screen {' . "\n";
        echo '    #PAPrintOut' . $this->_context . ' { display: none; }' . "\n";
        echo '}' . "\n";
        echo '</style>' . "\n";
        echo '<script type="text/javascript" language="JavaScript">' . "\n";
        echo 'function PA_GoPrint_' . $this->_context . '()' . "\n";
        echo '{' . "\n";
        echo '    document.body.innerHTML = \'<div id="PAScreenOut' . $this->_context . '">\' + document.body.innerHTML + \'<\/div>\';' . "\n";
        echo '    document.body.innerHTML += \'<div id="PAPrintOut' . $this->_context . '">' . $printHtml . '<\/div>\';' . "\n";
        echo '    window.print(); window.location="'.$redirecturl.'"' . "\n";
        echo '} ' . "\n";
        echo 'window.onload = PA_GoPrint_1;' . "\n";
        echo '</script>' . "\n";
        echo '<!-- END OF PRINTING CONTEXT ' . $this->_context . ' -->' . "\n";
        return $this->_context;
    }
    
    function addPrintContextInternal($stylesheet = array())
    {        
        $printHtml = $this->_prepareString($printHtml);
        echo '<!-- PRINTING CONTEXT -->' . "\n";
        echo '<style type="text/css">' . "\n";
        echo '@media print {' . "\n";
        echo '    #PAScreenOutInternal { display: none; }' . "\n";
        foreach ($stylesheet as $k => $v) {
            echo '    ' . $k . ' { ' . $v . '}' . "\n";
        }
        echo '}' . "\n";
        echo '@media screen {' . "\n";
        echo '    #PAPrintOutInternal { display: none; }' . "\n";
        echo '}' . "\n";
        echo '</style>' . "\n";
        echo '<script type="text/javascript" language="JavaScript">' . "\n";
        echo 'function PA_GoPrint_Internal(innerText)' . "\n";
        echo '{' . "\n";
        echo '    document.body.innerHTML = \'<div id="PAScreenOutInternal">\' + document.body.innerHTML + \'<\/div>\';' . "\n";
        echo '    document.body.innerHTML += \'<div id="PAPrintOutInternal">\' + innerText + \'<\/div>\';' . "\n";
        echo '    window.print();' . "\n";
        echo '} ' . "\n";
        echo 'window.onload = PA_GoPrint_1;' . "\n";
        echo '</script>' . "\n";
        echo '<!-- END OF PRINTING CONTEXT Internal -->' . "\n";
//        return $this->_context;
    }
    
    function PrinterText($lockerno,$accesscode,$holdertype,$dateHolder){
        $str = '<html>
                <head>
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <meta http-equiv="content-style-type" content="text/css" />
                <meta http-equiv="content-script-type" content="text/javascript" />
                <meta http-equiv="pragma" content="no-cache" />
                <meta http-equiv="expires" content="-1" />
                <meta http-equiv="imagetoolbar" content="no" />
                <meta name="keywords" content="" />
                <meta name="description" content="" />
                <meta name="distribution" content="global" />
                </head>
                <body>
                Locker No : '.$lockerno.'<br/>
                Access Code : '.$accesscode.'<br/>
                Holder Type : '.$holdertype.'<br/>
                Date : '.$dateHolder.'<br/>
                </body>
                </html>';
        return $str;
    }
    
    function printRenewalReceipt($receiptno,$receivedfrom,$cash,$lockerno,$periodfrom,$periodto,$receiptdate,$paymentmode,$ddchequeno,$ddchequedate,$lockertype,$additionalcharges,$receiptamount,$bankname,$words,$companyname, $memebershipno, $memebershiptype){   
		if(trim($companyname) != "" && trim($companyname) != "-"){			
            $receivedname = $companyname;
        }else{			
            $receivedname = $receivedfrom;
        }
        
        $memeberstring = "";
        
        if($memebershiptype == 1){
            $memeberstring = "BTM ";
        }else if($memebershiptype == 2){
            $memeberstring = "BATM ";
        }else if($memebershiptype == 3){
            $memeberstring = "BPM ";
        }else if($memebershiptype == 4){
            $memeberstring = "MDMA ";
        }
        $grandtotalGST = (SGST_Percent/100)*$receiptamount + (SGST_Percent/100)*$receiptamount;
		$Totalwords= $receiptamount+$grandtotalGST;
		$lastDigit=substr(ceil($Totalwords),-1);
		if($lastDigit<=4){
			$plusminus='-';
			switch($lastDigit){
				case 0:
					$Totalwords=$Totalwords;
				break;
				case 1:
					$Totalwords=$Totalwords-1;
				break;
				case 2:
					$Totalwords=$Totalwords-2;
				break;
				case 3:
					$Totalwords=$Totalwords-3;
				break;
				case 4:
					$Totalwords=$Totalwords-4;
				break;
			
			}
		}else{
		    $plusminus='+';
			switch($lastDigit){
				case 5:
					$Totalwords=$Totalwords+5;
				break;
				case 6:
					$Totalwords=$Totalwords+4;
				break;
				case 7:
					$Totalwords=$Totalwords+3;
				break;
				case 8:
					$Totalwords=$Totalwords+2;
				break;
				case 9:
					$Totalwords=$Totalwords+1;
				break;
			}
		}
        $str = '<div style="margin:90px 0px 0px 10px;">
            <table>
                <tr>
                    <td colspan="3" align="center">
                        <span>
                            RENEWAL RECEIPT
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">RECEIPT NO.: <b>'.$receiptno.'</b> </td>
                    <td width="40%" align="center">'.$memeberstring.' <b>'.$memebershipno.'</b> </td>
                    <td width="30%" align="right">DATE : <b>'.$receiptdate.'</b></td>
                </tr>                
                <tr>
                    <td colspan="3">
                        <p>
                            RECEIVED WITH THANKS FROM <b>'.$receivedname.'</b>';
                     if($paymentmode != "Cash"){
                         $str.='<br/>BY CHEQUE NO. <b>'.$ddchequeno.'</b> &nbsp;&nbsp; DATED : <b>'.$ddchequedate.'</b> &nbsp;&nbsp;DRAWN ON <b>'.$bankname.'</b><br/>TOWARDS RENT OF LOCKER NO. <b>'.$lockerno.'</b> TYPE <b>'.$lockertype.'</b>';
                     }else{
                         $str.='<br/>BY '.$paymentmode.' TOWARDS RENT OF LOCKER NO. <b>'.$lockerno.'</b> TYPE <b>'.$lockertype.'</b>';
                     }                     
                     $str.='</br>FOR THE PERIOD &nbsp;&nbsp;<b>'.$periodfrom.'</b>&nbsp;&nbsp; TO &nbsp;&nbsp;<b>'.$periodto.'</b>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table align="center" style="margin:0px auto; width: 30%;line-height: 18px">
                            <tr>
                                <td width="70px">RENT</td>
                                <td>:</td>
                                <td align="right">
                                    '.$cash.'
                                </td>
                            </tr>
							<tr>
                                <td>INTEREST</td>
                                <td>:</td>
                                <td align="right">
                                    '.$additionalcharges.'
                                </td>
                            </tr>
							<tr>
                                <td>SGST(9%)</td>
                                <td>:</td>
                                <td align="right">
                                   '.number_format((SGST_Percent/100)*$receiptamount, 2, '.', '').'
                                </td>
                            </tr>
							<tr>
                                <td>CGST(9%)</td>
                                <td>:</td>
                                <td align="right">
                                    '.number_format((GST_Percent/100)*$receiptamount, 2, '.', '').'
                                </td>
                            </tr>
							<tr>
                                <td>Round</td>
                                <td>:</td>
                                <td align="right">
                                    '.$plusminus.' '.number_format($lastDigit, 2, '.', '').'
                                </td>
                            </tr>
                            <tr>
                                <td><b>TOTAL</b></td>
                                <td>:</td>
                                <td align="right" style="border-top: 1px solid; border-bottom: 1px solid">
                                    <b>'.number_format(ceil($Totalwords), 2, '.', '').'</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        (Rs. '.ucwords($words).')
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span>
                            Rs. '.number_format(ceil($Totalwords), 2, '.', '').'
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="left" class="smallfont">
                        GSTIN: '.GST_ID.' <br/>
                        Receipt is valid subject to realization of cheque<br/>
                        Received rent is non-refundable in any circumstances.
                    </td>
                </tr>
            </table>

        </div>
        <div style="margin:230px 0px 0px 10px;">
            <table>
                <tr>
                    <td colspan="3" align="center">
                        <span>
                            RENEWAL RECEIPT
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">RECEIPT NO.: <b>'.$receiptno.'</b> </td>
                    <td width="40%" align="center">'.$memeberstring.' <b>'.$memebershipno.'</b> </td>
                    <td width="30%" align="right">DATE : <b>'.$receiptdate.'</b></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p>
                            RECEIVED WITH THANKS FROM <b>'.$receivedname.'</b>';
                     if($paymentmode != "Cash"){
                         $str.='<br/>BY CHEQUE NO. <b>'.$ddchequeno.'</b> &nbsp;&nbsp; DATED : <b>'.$ddchequedate.'</b> &nbsp;&nbsp;DRAWN ON <b>'.$bankname.'</b><br/>TOWARDS RENT OF LOCKER NO. <b>'.$lockerno.'</b> TYPE <b>'.$lockertype.'</b>';
                     }else{
                         $str.='<br/>BY '.$paymentmode.' TOWARDS RENT OF LOCKER NO. <b>'.$lockerno.'</b> TYPE <b>'.$lockertype.'</b>';
                     }                     
                     $str.='</br>FOR THE PERIOD &nbsp;&nbsp;'.$periodfrom.'</b>&nbsp;&nbsp; TO &nbsp;&nbsp;<b>'.$periodto.'</b>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table align="center" style="margin:0px auto; width: 30%;line-height: 15px">
                            <tr>
                                <td width="70px">RENT</td>
                                <td>:</td>
                                <td align="right">
                                    '.$cash.'
                                </td>
                            </tr>
                            <tr>
                                <td>INTEREST</td>
                                <td>:</td>
                                <td align="right">
                                    '.$additionalcharges.'
                                </td>
                            </tr>
							<tr>
                                <td>SGST(9%)</td>
                                <td>:</td>
                                <td align="right">
                                    '.number_format((SGST_Percent/100)*$receiptamount, 2, '.', '').'
                                </td>
                            </tr>
							<tr>
                                <td>CGST(9%)</td>
                                <td>:</td>
                                <td align="right">
                                     '.number_format((GST_Percent/100)*$receiptamount, 2, '.', '').'
                                </td>
                            </tr>
							<tr>
                                <td>Round</td>
                                <td>:</td>
                                <td align="right">
                                    '.$plusminus.' '.number_format($lastDigit, 2, '.', '').'
                                </td>
                            </tr>
                            <tr>
                                <td><b>TOTAL</b></td>
                                <td>:</td>
                                <td align="right" style="border-top: 1px solid; border-bottom: 1px solid">
                                    <b>'.number_format(ceil($Totalwords), 2, '.', '').'</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        (Rs. '.ucwords($words).')
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span>
                            Rs. '.number_format(ceil($Totalwords), 2, '.', '').'
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="left" class="smallfont">
                        GSTIN: '.GST_ID.' <br/>
                        Receipt is valid subject to realization of cheque<br/>
                        Received rent is non-refundable in any circumstances.
                    </td>
                </tr>

            </table>
        </div>';
        return $str;
    }
       
    function printRentReceipt($receiptno,$receivedfrom,$cash,$lockerno,$periodfrom,$periodto,$receiptdate,$paymentmode,$ddchequeno,$ddchequedate,$lockertype,$words,$drawnfrom,$companyname, $memebershipno, $memebershiptype){
        $str = '<div style="margin: 84px 0px 0px 10px;">
            <table width="100%">
                <tr>
                    <td colspan="3" align="center">
                        <span>
                            RENT RECEIPT
                        </span>                        
                    </td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td width="30%">RECEIPT NO.: <b>'.$receiptno.'</b> </td>
                    <td width="40%" align="center">'.$memeberstring.' <b>'.$memebershipno.'</b> </td>
                    <td width="30%" align="right">DATE : <b>'.$receiptdate.'</b></td>
                </tr>
                <tr>
                    <td colspan="3">					
					<p>
						RECEIVED WITH THANKS FROM <b>'.$receivedname.'</b>
						';
        if($paymentmode == "Cash"){
			$str.= '<br/>BY CASH <b>Rs. '.$Totalwords.' &nbsp;(Rs.'.ucwords($words).')</b>';
        }else{
        $str.= '<br/>BY CHEQUE NO. <b>'.$ddchequeno.'</b>  &nbsp;DATE : <b>'.$ddchequedate.'</b> &nbsp;DRAWN ON <b>'.$drawnfrom.'</b>
                <br/>FOR Rs. <b>'.$Totalwords.' &nbsp;(Rs. '.ucwords($words).')</b>';
        }                
         $str.= '	<br/>TOWARDS RENT OF LOCKER NO. <b>'.$lockerno.' &nbsp;&nbsp;&nbsp;&nbsp;TYPE : <b>'.$lockertype.'</b></b> 
					<br/>FOR THE PERIOD &nbsp;&nbsp;<b>'.$periodfrom.'</b>&nbsp;&nbsp; TO &nbsp;&nbsp;<b>'.$periodto.'</b>
					</p>                        
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        <table align="center" style="margin:0px auto; width: 80%;line-height: 18px">
                            <tr>
                                <td align="right" width="250px">RENT</td><td width="4px">:</td><td width="70px" align="right">'.$cash.'</td><td></td>
                            </tr>
							<tr>
								<td align="right" width="250px">SGST</td><td width="4px">:</td><td width="70px" align="right">'.number_format((SGST_Percent/100)*$cash, 2, '.', '').'</td><td></td>
                            </tr>
							<tr>
                                <td align="right" width="250px">CGST</td><td width="4px">:</td><td width="70px" align="right">'.number_format((GST_Percent/100)*$cash, 2, '.', '').'</td><td></td>
                            </tr>
							<tr>
                                <td align="right" width="250px">Round</td><td width="4px">:</td><td width="70px" align="right">'.$plusminus.' '.number_format($lastDigit, 2, '.', '').'</td><td></td>
                            </tr>
                            <!--<tr>
                                <td align="right">&nbsp;</td><td>&nbsp;</td><td align="right">'.$documentationCharges.'</td><td></td>
                            </tr>-->
                            <tr>
                                <td align="right"><strong>TOTAL</strong></td><td>:</td><td align="right" style="border-top: 1px solid; border-bottom: 1px solid"><strong>'.  number_format(ceil($Totalwords + $documentationCharges), '2', '.', '').'</strong></td><td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span>
                            Rs. '.number_format(ceil($Totalwords), '2', '.', '').'
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="left" class="smallfont">
                        GSTIN: '.GST_ID.' <br/>
                        Receipt is valid subject to realization of cheque.<br/>
                        Received rent is non-refundable in any circumstances
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin: 230px 0px 0px 10px;">
            <table width="100%">
                <tr>
                    <td colspan="3" align="center">
                        <span>
                            RENT RECEIPT
                        </span>                        
                    </td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td width="30%">RECEIPT NO.: <b>'.$receiptno.'</b> </td>
                    <td width="40%" align="center">'.$memeberstring.' <b>'.$memebershipno.'</b> </td>
                    <td width="30%" align="right">DATE : <b>'.$receiptdate.'</b></td>
                </tr>
                <tr>
                    <td colspan="3">					
					<p>
						RECEIVED WITH THANKS FROM <b>'.$receivedname.'</b>
						';
        if($paymentmode == "Cash"){
			$str.= '<br/>BY CASH <b>Rs. '.$Totalwords.' &nbsp;&nbsp;(Rs.'.ucwords($words).')</b>';
        }else{
        $str.= '<br/>BY CHEQUE NO. <b>'.$ddchequeno.'</b>  &nbsp;DATE : <b>'.$ddchequedate.'</b> &nbsp;DRAWN ON <b>'.$drawnfrom.'</b>
                <br/>FOR Rs. <b>'.$Totalwords.' &nbsp;(Rs. '.ucwords($words).')</b>';
        }                
         $str.= '	<br/>TOWARDS RENT OF LOCKER NO. <b>'.$lockerno.' &nbsp;&nbsp;&nbsp;&nbsp;TYPE : <b> '.$lockertype.'</b></b> 
					<br/>FOR THE PERIOD &nbsp;&nbsp;<b>'.$periodfrom.'</b>&nbsp;&nbsp; TO &nbsp;&nbsp;<b>'.$periodto.'</b>
					</p>                        
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        <table align="center" style="margin:0px auto; width: 80%;line-height: 18px">
                            <tr>
                                <td align="right" width="250px">RENT</td><td width="4px">:</td><td width="70px" align="right">'.$cash.'</td><td></td>
                            </tr>
							<tr>
								<td align="right" width="250px">SGST</td><td width="4px">:</td><td width="70px" align="right">'.number_format((SGST_Percent/100)*$cash, 2, '.', '').'</td><td></td>
                            </tr>
							<tr>
                                <td align="right" width="250px">CGST</td><td width="4px">:</td><td width="70px" align="right">'.number_format((GST_Percent/100)*$cash, 2, '.', '').'</td><td></td>
                            </tr>
							<tr>
                                <td align="right" width="250px">Round</td><td width="4px">:</td><td width="70px" align="right">'.$plusminus.' '.number_format($lastDigit, 2, '.', '').'</td><td></td>
                            </tr>
                            <!--<tr>
                                <td align="right">&nbsp;</td><td>&nbsp;</td><td align="right">'.$documentationCharges.'</td><td></td>
                            </tr>-->
                            <tr>
                                <td align="right"><strong>TOTAL</strong></td><td>:</td><td align="right" style="border-top: 1px solid; border-bottom: 1px solid"><strong>'.  number_format(ceil($Totalwords + $documentationCharges), '2', '.', '').'</strong></td><td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span>
                            Rs. '.number_format(ceil($Totalwords), '2', '.', '').'
                        </span>
                    </td>
                </tr>
                <!--<tr>
                    <td colspan="3" align="left" class="smallfont">
                        GSTIN:  <br/>
                        Receipt is valid subject to realization of cheque.<br/>
                        Received rent is non-refundable in any circumstances
                    </td>
                </tr>-->
            </table>
        </div>';
        
        return $str;
    }
    
    function printDepositReceipt($receiptno,$receivedfrom,$cash,$lockerno,$periodfrom,$periodto,$receiptdate,$paymentmode,$ddchequeno,$ddchequedate,$lockertype,$words,$drawnfrom,$companyname){
        if($companyname != "" && $companyname != "-"){
            $receivedname = $companyname;
        }else{
            $receivedname = $receivedfrom;
        }
        $str = '<div style="height: 16px">&nbsp;</div><div style="margin: 100px 0px 0px 10px;">
            <table>
                <tr>
                    <td colspan="2" align="center">
                        <span>
                            DEPOSIT RECEIPT
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="50%">RECEIPT NO. <b>'.$receiptno.'</b> </td>
                    <td width="50%" align="right">DATE <b>'.$receiptdate.'</b></td>
                </tr>
                <tr>
                    <td colspan="2">					
					<p>
						RECEIVED WITH THANKS FROM <b>'.$receivedname.'</b>
						';
        if($paymentmode == "Cash"){
			$str.= '<br/>BY CASH <b>Rs. '.$cash.' &nbsp;&nbsp;(Rs. '.ucwords($words).')</b>';
        }else{
        $str.= '<br/>BY CHEQUE NO. <b>'.$ddchequeno.'</b>  &nbsp;&nbsp;DATE : <b>'.$ddchequedate.'</b> &nbsp;&nbsp; DRAWN ON <b>'.$drawnfrom.'</b>
                <br/>FOR Rs. <b>'.$cash.' &nbsp;&nbsp;(Rs. '.ucwords($words).')</b>';
        }                
         $str.= '	<br/>TOWARDS DEPOSIT OF LOCKER NO. <b>'.$lockerno.' &nbsp;&nbsp;&nbsp;&nbsp;TYPE : <b>'.$lockertype.'</b></b> 					
					</p>                        
                    </td>
                </tr>              
                <tr>
                    <td colspan="2">                        
                        <span>
                            Rs. '.number_format(ceil($cash), '2', '.', '').'
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="left" class="smallfont">                        
                        <br/>
						GSTIN: '.GST_ID.' <br/>
                        Receipt is valid subject to realization of cheque.<br/>
                        Deposit will be refunded in favour of receipt holder at the time of <br/>
                        surrender of locker by cross cheque only.<br/>
						Sardar safe is exclusively liable for the deposit amount <br/>
						received from customers & not BDB.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin: 200px 0px 0px 10px;">
            <table>
                <tr>
                    <td colspan="2" align="center">
                        <span>
                            DEPOSIT RECEIPT
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="50%">RECEIPT NO. <b>'.$receiptno.'</b> </td>
                    <td width="50%" align="right">DATE <b>'.$receiptdate.'</b></td>
                </tr>
                <tr>
                    <td colspan="2">					
					<p>
						RECEIVED WITH THANKS FROM <b>'.$receivedname.'</b>
						';
        if($paymentmode == "Cash"){
			$str.= '<br/>BY CASH <b>Rs. '.$cash.' &nbsp;&nbsp;(Rs. '.ucwords($words).')</b>';
        }else{
        $str.= '<br/>BY CHEQUE NO. <b>'.$ddchequeno.'</b>  &nbsp;&nbsp;DATE : <b>'.$ddchequedate.'</b> &nbsp;&nbsp; DRAWN ON <b>'.$drawnfrom.'</b>
                <br/>FOR Rs. <b>'.$cash.' &nbsp;&nbsp;(Rs. '.ucwords($words).')</b>';
        }                
         $str.= '	<br/>TOWARDS DEPOSIT OF LOCKER NO. <b>'.$lockerno.' &nbsp;&nbsp;&nbsp;&nbsp;TYPE : <b>'.$lockertype.'</b></b> 					
					</p>                        
                    </td>
                </tr>               
                <tr>
                    <td colspan="2">
                        <span>
                            Rs. '.number_format(ceil($cash), '2', '.', '').'
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="left" class="smallfont">  
						GSTIN: '.GST_ID.' <br/>
                        Receipt is valid subject to realization of cheque.<br/>
                        Deposit will be refunded in favour of receipt holder at the time of <br/>
                        surrender of locker by cross cheque only.<br/>
						Sardar safe is exclusively liable for the deposit amount received from customers & not BDB
                    </td>
                </tr>
            </table>
        </div>';
        
        return $str;
    }
    
    function getContractPrintNew($post){
        if($post['companyname'] != "" AND $post['companyname'] != "-"){
                $addCompany = "<br/><b>[ FOR ".$post['companyname']."]</b>";
        }
        if($post['holdertype'] == 'A'){
        $str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>jQuery Print Plugin</title>
        <style>
            @media print{
                body{ background-color:#FFFFFF; background-image:none; color:#000000; margin: 92px 0px 14px 20px; padding: 0px }
            }
            
            div.box{
                border: 2px solid #000000;
                width: 98%;
                padding: 4px;
            }
            
            table{
                width: 100%;
                font-family: arial;
                line-height: 24px
            }
            
            table.tabletop{
                border-bottom: 2px solid #000000;
            }
            
            table.tabletop b{
                font-size: 20px;
            }
            
            table.lineheighttable{
                line-height: 60px;
            }
            
            table.specimentable{
                line-height: 36px;
            }
            
            div.divbox{
                border: 2px solid #000000;
                width: 90px;
                height: 90px;
                display: block;
                font-weight: bold;
                font-size: 30px;
                line-height: 40px
            }
            
            div.divbox span{
                font-size: 16px;
            }
        </style>
    </head>
    <body>
        <div class="box" style="margin:0px 0px 0px 0px;">
            <table cellpadding="0" cellspacing="0" class="tabletop">
                <tr>
                    <td>CONTRACT NO. : <b>'.$post['receiptno'].'</b></td>
                    <td>LOCKER NO. : <b>'.$post['lockerno'].'</b></td>
                    <td>TYPE : <b>'.$post['lockertypeinput'].'</b></td>
                    <td>KEY NO. : <b>'.$post['keyno'].'</b></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="lineheighttable">
                <tr>
                    <td width="200px">OPERATOR\'S NAME</td>
                    <td style="border-bottom:2px solid;"><b>1. '.$post['holdername'].'</b></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="border-bottom:2px solid"><b>2. </b></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="border-bottom:2px solid"><b>3. </b></td>
                </tr> 
                <tr>
                    <td style="border-bottom:2px solid;" width="200px">COMPANY NAME</td>
                    <td style="border-bottom:2px solid;"><b>'.$post['companyname'].'</b></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="200px" valign="top" style="height:78px;border-bottom:2px solid;">ADDRESS</td>
                    <td style="height:78px;border-bottom:2px solid;" valign="top">
                        <b>'.nl2br(strtoupper($post['address'])).'</b>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>NOMINEE : <b>'.strtoupper($post['nominee']).'</b></td>
                </tr>
                <tr>
                    <td style="border-bottom:2px solid;">RELATION WITH LOCKER OWNER : <b>'.strtoupper($post['nomineerelation']).'</b></td>
                </tr>
            </table>
            <br/>
            <table cellpadding="0" cellspacing="0" border="0" class="specimentable"  style="border-bottom:2px solid;">
                <tr>
                    <td align="center" style="line-height: 24px"><b>SPECIMEN SIGNATURE</b></td>
                    <td width="140" rowspan="7" align="center">
                        <br/>
                        <div class="divbox">
                            <span>LOCKER</span>                            
                            '.$post['lockerno'].'
                        </div>
                        <br/>
                        <div class="divbox">
                            <span>RECEIPT</span>                            
                            '.$post['receiptno'].'
                        </div>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td>
                        A. ______________________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        ________________________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        B. ______________________________________________
                    </td>
                </tr> 
                <tr>
                    <td>
                        ________________________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        C. ______________________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        ________________________________________________
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" style="border-bottom: none; font-size:13px; line-height: 23px;">
                <tr>
                    <td>
                        I/WE HEREBY CONFIRM THAT WE HAVE TAKEN  POSSESSION OF THE LOCKER NO. <b>'.$post['lockerno'].'</b> ALONG WITH THE KEY FROM SARDAR LAXMI SAFE VAULT LLP. 
                        I/WE UNDERSIGNED CONFIRM AND ACCEPT THE TERMS & CONDITION GIVEN IN THE LEASE AGREEMENT.
                        MY/OUR SECRET CODE IS - <b>'.$post['secretcode'].'</b>                                            
                    </td>
                </tr>                
            </table>
            <table cellpadding="0" cellspacing="0" style="border-bottom: none; font-size:14px; line-height: 23px;">
                <tr>
                    <td style="height:100px" valign="bottom">
                        <b>[SHREE SARDAR LAXMI SAFE VAULT LLP.]</b><br/>
                        PLACE :- MUMBAI <br/>
                        DATE :- '.$post['receiptDate'].'
                    </td>
                    <td valign="bottom" align="center" style="height:100px">
                        <b>'.strtoupper($post['holdername']).'</b>
                        '.$addCompany.'
                    </td>
                </tr>                
            </table>
        </div>        
    </body>
</html>';
        }else if($post['holdertype'] == 'B'){
            $str = '<div style="font-family:arial;margin:208px 0px 0px 241px; font-size:16px">'.$post['holdername'].'</div>';
        }else if($post['holdertype'] == 'C'){
            $str = '<div style="font-family:arial;margin:270px 0px 0px 241px; font-size:16px">'.$post['holdername'].'</div>';
        }
        return $str;
    }
    
    function getContractPrint($post){
                
//        if($post['holdername'] != ""){
//            $holdrname = '<u>' . $post['holdername'] . '</u>';
//        }
		$str = "";
		if($post['companyname'] != "" AND $post['companyname'] != "-"){
			$str = "<br/><b>[ FOR ".$post['companyname']."]</b>";
		}

        if($post['holdertype'] == 'A'){
            $str = '<div class="main">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>CONTRACT NO. : <b>'.$post['receiptno'].'</b></td>
                    <td>LOCKER NO. : <b>'.$post['lockerno'].'</b></td>
                    <td>TYPE : <b>'.$post['lockertypeinput'].'</b></td>
                    <td>KEY NO. : <b>'.$post['keyno'].'</b></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="100%" class="lineheighttable">
                <tr>
                    <td width="200px">OPERATOR\'S NAME</td>
                    <td style="border-bottom:2px solid;"><b>1. '.$post['holdername'].'</b></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="border-bottom:2px solid"><b>2. </b></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="border-bottom:2px solid"><b>3. </b></td>
                </tr> 
                <tr>
                    <td width="200px">COMPANY NAME</td>
                    <td><b>'.$post['companyname'].'</b></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="200px" valign="top" style="height:78px">ADDRESS</td>
                    <td style="height:78px" valign="top">
                        <b>'.nl2br($post['address']).'</b>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>NOMINEE : <b>'.$post['nominee'].'</b></td>
                </tr>
                <tr>
                    <td>RELATION WITH LOCKER OWNER : <b>'.$post['nomineerelation'].'</b></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center"><b>SPECIMEN SIGNATURE</b></td>
                    <td width="140" rowspan="7" align="center">
                        <br/>
                        <div class="divbox">
                            <span>LOCKER</span>                            
                            '.$post['lockerno'].'
                        </div>
                        <br/>
                        <div class="divbox">
                            <span>RECEIPT</span>                            
                            '.$post['receiptno'].'
                        </div>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td>
                        A. _________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        ___________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        B. _________________________________________________________
                    </td>
                </tr> 
                <tr>
                    <td>
                        ___________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        C. _________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        ___________________________________________________________
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="100%" style="border-bottom: none; font-size:13px; line-height: 23px;">
                <tr>
                    <td>
                        I/WE HEREBY CONFIRM THAT WE HAVE TAKEN  POSSESSION OF THE LOCKER NO. <b>'.$post['lockerno'].'</b> ALONG WITH THE KEY FROM SARDAR LAXMI SAFE VAULT LLP. 
                        I/WE UNDERSIGNED CONFIRM AND ACCEPT THE TERMS & CONDITION GIVEN IN THE LEASE AGREEMENT.
                        MY/OUR SECRET CODE IS - <b>'.$post['secretcode'].'</b>                                            
                    </td>
                </tr>                
            </table>
            <table cellpadding="0" cellspacing="0" width="100%" style="border-bottom: none; font-size:14px; line-height: 23px;">
                <tr>
                    <td style="height:100px" valign="bottom">
                        <b>['.MUMBAI_OFFICE_NAME.']</b><br/>
                        PLACE :- MUMBAI <br/>
                        DATE :- '.$post['receiptDate'].'
                    </td>
                    <td valign="bottom" align="center" style="height:100px">
                        <b>'.$post['holdername'].'</b>
						'.$str.'
                    </td>
                </tr>                
            </table>
        </div>';
        }else if($post['holdertype'] == 'B'){
            $str = '<div style="font-family:arial;margin:208px 0px 0px 241px; font-size:16px">'.$post['holdername'].'</div>';
        }else if($post['holdertype'] == 'C'){
            $str = '<div style="font-family:arial;margin:270px 0px 0px 241px; font-size:16px">'.$post['holdername'].'</div>';
        }
        
        return $str;
    }
    
    function printChangeLocker($post){
        $compStr = "";
        if($post['companyname'] != "" AND $post['companyname'] != "-"){
                $compStr = "<br/><b>[ FOR ".$post['companyname']."]</b>";
        }
        $str = '<div class="main" style="margin:0px 0px 0px 20px"><table style="font-family: arial; font-size: 14px;  border-bottom:none">
            <tr>
                <td>
                    From : <b>'.$post['firstholdername'].'</b>
                </td>
            </tr>            
            <tr>
                <td>
                    '.nl2br($post['address']).'
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>Date</b> &nbsp;&nbsp;&nbsp;&nbsp; '.date("d-m-Y").'    at  '.date("H:i").'
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>To :-</b>
                </td>
            </tr>
            <tr>
                <td>
                    The Custodian,<br/>
                    '.MUMBAI_OFFICE_NAME.',<br/>
                    '.MUMBAI_ADDRESS.'
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>Dear Sir,</b>
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>REF.:</b> LOCKER NO. <u>'.$post['lockerno'].'</u><br/>
                    <b>SUB :</b> TO CHANGE LOCKER HOLDER
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    I/We holder of above mentioned Locker Number hereby requesting you to remove/cancel the name 
                    of  <b><u>'.$post['oldname'].'</u></b> and replace/include <br/>the name of <b><u>'.$post['holdername'].'</u></b> in our Locker.
                    <br/><br/>
                    Kindly do the same with immediate effect.
                    <br/><br/>
                    Thanking you.
                    <br/><br/>
                    Yours faithfully
                    <br/><br/><br/><br/><br/>
                    <b>'.$post['firstholdername'].'</b>
                    '.$compStr.'
                </td>
            </tr>
        </table></div>';
        return $str;
    }
    
    function printDeleteUser($post){
        $compStr = "";
        if($post['companyname'] != "" AND $post['companyname'] != "-"){
                $compStr = "<br/><b>[ FOR ".$post['companyname']."]</b>";
        }
        $str = '<div class="main" style="margin:0px 0px 0px 20px"><table style="font-family: arial; font-size: 14px;  border-bottom:none">
            <tr>
                <td>
                    From : <b>'.$post['firstholdername'].'</b>
                </td>
            </tr>            
            <tr>
                <td>
                    '.nl2br($post['address']).'
                        
                </td>
            </tr>
            <tr>
                <td>  
                    <br/>
                    <b>Date</b> &nbsp;&nbsp;&nbsp;&nbsp; '.date("d-m-Y").'    at  '.date("H:i").'
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>To :-</b>
                </td>
            </tr>
            <tr>
                <td>
                    The Custodian,<br/>
                    '.MUMBAI_OFFICE_NAME.',<br/>
                    '.MUMBAI_ADDRESS.'
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>Dear Sir,</b>
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>REF.:</b> LOCKER NO. <u>'.$post['lockerno'].'</u><br/>
                    <b>SUB :</b> TO DELETE MEMEBER
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    I/We holder of above mentioned Locker Number hereby requesting you to remove/cancel the name 
                    of  <b><u>'.$post['oldname'].'</u></b> in our Locker.
                    <br/><br/>
                    Kindly do the same with immediate effect.
                    <br/>
                    Thanking you.
                    <br/>
                    Yours faithfully
                    <br/><br/><br/><br/><br/>
                    <b>'.$post['firstholdername'].'</b>
                    '.$compStr.'
                </td>
            </tr>
        </table></div>';
        return $str;
    }
    
    function printChangeLockerAddress($post){
        $compStr = "";
        if($post['companyname'] != "" AND $post['companyname'] != "-"){
                $compStr = "<br/><b>[ FOR ".$post['companyname']."]</b>";
        }
                
        $str = '<div class="main" style="margin:0px 0px 0px 20px"><table style="font-family: arial; font-size: 14px; border-bottom:none">
            <tr>
                <td>
                    From : <b>'.$post['firstholdername'].'</b>
                </td>
            </tr>            
            <tr>
                <td>
                    '.nl2br($post['address']).'
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    Date &nbsp;&nbsp;&nbsp;&nbsp; <b>'.date("d-m-Y").'</b>    at  <b>'.date("H:i").'</b>
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>To :-</b>
                </td>
            </tr>
            <tr>
                <td>
                    The Custodian,<br/>
                    '.MUMBAI_OFFICE_NAME.',<br/>
                    '.MUMBAI_ADDRESS.'.
                </td>
            </tr>
            <tr>
                <td>
					<br/>
                    <b>Dear Sir,</b>
                </td>
            </tr>
            <tr>
                <td>
					<br/>
                    REF.: <b>LOCKER NO. <u>'.$post['lockerno'].'</u></b><br/>
                    SUB : <b>TO CHANGE OF ADDRESS</b>
                </td>
            </tr>
			<tr>
				<td>
					With reference to the above. I / We would like to inform you that we have changed our correspondence address as under :
				</td>
			</tr>
            <tr>
                <td align="center">                   
					<br/>
					'.nl2br($post['address']).'
				</td>
			</tr>
			<tr>
				<td>
					<br/>				
                    Kindly made the above changes in your record and make future correspondence to my / our new address.
                    <br/><br/>
                    Thanking you.
                    <br/><br/>
                    Yours faithfully
                    <br/><br/><br/><br/><br/>
                    <b>[ '.$post['firstholdername'].' ]</b>
                    '.$compStr.'
                </td>
            </tr>
        </table></div>';
        return $str;
    }
    
    function printnonoperative($post){
        if($post['companynameonly'] != ""){
            $addCompany = "<br/><b>[ ".$post['companynameonly']." ]</b>";
        }
		$str = '<div style="width:700px;margin:50px auto"><table style="font-family: arial; font-size: 13px">
            <tr>
                <td>
                    From : <b>'.$post['holdernameonly'].'</b>
                </td>
            </tr>            
            <tr>
                <td>
                    '.nl2br($post['address']).'
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    Date &nbsp;&nbsp;&nbsp;&nbsp; <b>'.date("d-m-Y").'</b>    at  <b>'.date("H:i").'</b>
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>To :-</b>
                </td>
            </tr>
            <tr>
                <td>
                    The Custodian,<br/>
                    '.MUMBAI_OFFICE_NAME.',<br/>
                    '.MUMBAI_ADDRESS.'
                </td>
            </tr>
            <tr>
                <td>                    
                <br/>
                    <b>Dear Sir,</b>
                </td>
            </tr>
            <tr>
                <td>        
                <br/>
                    REF.: <b>LOCKER NO. <u>'.$post['lockernoonly'].'</u></b><br/>
                    SUB : <b>TO BREAK OPEN OF LOCKER</b>
                </td>
            </tr>
            <tr>
                <td>
                <br/>
                    With reference to the above, I/we hereby inform you that I/we have misplaced our/my Locker\'s Key.  We hereby requesting your goodselves that kindly stop our Locker\'s operation with immediate effect and inform Godrej Engineer for break open of our Locker.
                    <br/><br/>
                    I/We have ready to pay your necessary charges if any.
                </td>
            </tr>            
            <tr>
                <td>                    
                    <br/><br/>
                    Thanking you.
                    <br/><br/>
                    Yours faithfully
                    <br/><br/><br/><br/>
                    <b>[ '.$post['holdernameonly'].' ]</b>
                    '.$addCompany.'    
                </td>
            </tr>
        </table></div>';
		return $str;
	}
    
    function printRelease($arrPostdata){
		$compStr = "";
		if($arrPostdata['companyname'] != "" AND $arrPostdata['companyname'] != "-"){
			$compStr = "<br/><b>[ FOR ".$arrPostdata['companyname']."]</b>";
		}
        $str = '
            <div style="width: 700px;border:4px solid; margin:0px 0px 0px 40px">
        <table width="100%">
            <tr>
                <td align="center">
                    <p>
                        DECLARATION/SURRENDER NOTE
                    </p>
                </td>
            </tr>
            <tr>
                <td align="right">
                    Date : '.$arrPostdata['receiptdate'].'
                </td>                
            </tr> 
            <tr>
                <td>
                    I/We hereby discontinue/cancel my/our Locker No. <b>'.$arrPostdata['lockerno'].'</b> having in '.MUMBAI_ADDRESS.'.  I/We also confirm that I/We have taken my/our all valuables and other documents which I/We have kept in my/our Locker. I/we am/are handed over the empty Locker\'s key to the custodian and released the company from all responsibility as per mentioned in  memorandum of lease .
                    <br/><br/>
                    I/We have received Rs. <b>'.$arrPostdata['deposit'].'</b> (Rs. '.$arrPostdata['depositwords'].') by cheque No. <b>'.$arrPostdata['chequeno'].'</b> dated <b>'.$arrPostdata['chequedate'].'</b>, drawn on <b>INDUSIND BANK</b> which we have deposited as a security deposit at the time of allotment of Locker under Receipt No. <b>'.$arrPostdata['receiptno'].'</b>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>				
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td valign="top">'.MUMBAI_OFFICE_NAME.'.</td>
                            <td valign="top" align="right"><b>'.$arrPostdata['holdername'].'</b>'.$compStr.'</td>
                        </tr>
                    </table>                    
                </td>
            </tr>            
        </table>
    </div>
	
	<div style="width: 700px;border:4px solid; margin:100px 0px 0px 40px">
        <table width="100%">
            <tr>
                <td align="center">
                    <p>
                        DECLARATION/SURRENDER NOTE
                    </p>
                </td>
            </tr>
            <tr>
                <td align="right">
                    Date : '.$arrPostdata['receiptdate'].'
                </td>                
            </tr>  
            <tr>
                <td>
                    I/We hereby discontinue/cancel my/our Locker No. <b>'.$arrPostdata['lockerno'].'</b> having in '.MUMBAI_ADDRESS.'.  I/We also confirm that I/We have taken my/our all valuables and other documents which I/We have kept in my/our Locker. I/we am/are handed over the empty Locker\'s key to the custodian and released the company from all responsibility as per mentioned in  memorandum of lease .
                    <br/><br/>
                    I/We have received Rs. <b>'.$arrPostdata['deposit'].'</b> (Rs. '.$arrPostdata['depositwords'].') by cheque No. <b>'.$arrPostdata['chequeno'].'</b> dated <b>'.$arrPostdata['chequedate'].'</b>, drawn on <b>INDUSIND BANK</b> which we have deposited as a security deposit at the time of allotment of Locker under Receipt No. <b>'.$arrPostdata['receiptno'].'</b>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>				
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td valign="top">'.MUMBAI_OFFICE_NAME.'.</td>
                            <td valign="top" align="right"><b>'.$arrPostdata['holdername'].'</b>'.$compStr.'</td>
                        </tr>
                    </table>                    
                </td>
            </tr>
        </table>
    </div>';
        
        return $str;
    }
	
	function PrintChangeName($post){
		if($post["paymenttype"] == "Cash"){
			$temp = 'BY CASH <b>'.$post["fees"].'/- (Rs. '.$post["feeswords"].')</b><br/>';
        }else{
			$temp = 'BY CHEQUE NO. <b>'.$post["chequeno"].'</b>  &nbsp;DATE : <b>'.$post["receiptdate"].'</b> &nbsp;DRAWN ON <b>'.$post["bankname"].'</b>
                <br/>FOR Rs. <b>'.$post["fees"].'/- (Rs. '.$post["feeswords"].')</b><br/>';
        }   
		
        $str = '<br/><br/><br/><br/>
            <div style="width: 700px;margin:0px 0px 0px 40px;">
        <table width="100%">
            <tr>
                <td align="center" colspan="2">
                    <b class="small">
                        RECEIPT
                    </b>
                </td>
            </tr>
			<tr>
                <td align="center" colspan="2">
                    &nbsp;
                </td>
            </tr>
            <tr>
				<td align="left">
                    RECEIPT : <b>'.$post["receiptno"].'</b>
                </td> 
                <td align="right">
                    DATE : <b>'.$post["receiptdate"].'</b>
                </td>                
            </tr> 
            <tr>
                <td colspan="2">
                    RECEIVED WITH THANKS FROM <b>'.$post["holdername"].' </b><br/>
					'.$temp.'					
					TOWARDS <b>'.$post["receipttype"].' </b> FOR LOCKER NO. <b>'.$post["lockerno"].'.</b>
					<br/><br/>
                </td>
            </tr>
			<tr>
                <td align="left">
                    <b class="small">Rs. '.$post["fees"].'/-</b>
                </td>
            </tr>
			<tr>
				<td colspan="2" align="left" class="smallfont">                        
					<br/><br/><br/><br/>
					Receipt is inclusive of Service Tax.<br/>
					Receipt is valid subject to realization of cheque.<br/>
					Received rent is non-refundable in any circumstances
				</td>
			</tr>
        </table>
    </div>
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
	<div style="width: 700px;margin:0px 0px 0px 40px">
        <table width="100%">
            <tr>
                <td align="center" colspan="2">
                    <b class="small">
                        RECEIPT
                    </b>
                </td>
            </tr>
			<tr>
                <td align="center" colspan="2">
                    &nbsp;
                </td>
            </tr>
            <tr>
				<td align="left">
                    RECEIPT : <b>'.$post["receiptno"].'</b>
                </td> 
                <td align="right">
                    DATE : <b>'.$post["receiptdate"].'</b>
                </td>                
            </tr> 
            <tr>
                <td colspan="2">
                    RECEIVED WITH THANKS FROM <b>'.$post["holdername"].' </b><br/>
					'.$temp.'					
					TOWARDS <b>'.$post["receipttype"].' </b> FOR LOCKER NO. <b>'.$post["lockerno"].'.</b>
					<br/><br/>
                </td>
            </tr>   
			<tr>
                <td align="left" colspan="2">
                    <b class="small">Rs. '.$post["fees"].'/-</b>
                </td>
            </tr>
			<tr>
				<td colspan="2" align="left" class="smallfont">                        
					<br/><br/><br/><br/>
					Receipt is inclusive of Service Tax.<br/>
					Receipt is valid subject to realization of cheque.<br/>
					Received rent is non-refundable in any circumstances
				</td>
			</tr>
        </table>
    </div>';
        echo $str;
        return $str;
    }

    function showPrintLink($context, $linkText, $attributes = '')
    {
        echo '<a href="javascript:PA_GoPrint_' . $context . '()"' . (!empty($attributes) ? ' ' . $attributes : '') . '>' . $linkText . '</a>';
    }

    function showPrintButton($context, $buttonText, $attributes = '')
    {
        echo '<input type="button" value="' . $buttonText . '"' . (!empty($attributes) ? ' ' . $attributes : '') . ' onclick="PA_GoPrint_' . $context . '()" />';
    }

}

?>