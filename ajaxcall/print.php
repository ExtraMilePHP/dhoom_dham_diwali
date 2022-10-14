<?php
include_once '../dao/config.php';
include_once '../classes/EventRegister.php';
include_once '../classes/PdoClass.php';
$objEventRegister = new EventRegister();
$objpdoClass = new PdoClass();
$result = $objEventRegister->addUserPointsBingo($_POST,$connPdo,$objpdoClass);
if($result==true){
echo '<table id="Table_01" width="798" height="457" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto">
                        <tr>
                            <td valign="top" style="line-height: 0">
                                <img src="images/tickets/ticketarea_01.png" width="100" height="76" alt=""></td>
                            <td valign="top" style="line-height: 0">
                                <img src="images/tickets/ticketarea_02.png" width="589" height="76" alt=""></td>
                            <td valign="top" style="line-height: 0">
                                <img src="images/tickets/ticketarea_03.png" width="109" height="76" alt=""></td>
                        </tr>
                        <tr>
                            <td valign="top" style="line-height: 0">
                                <img src="images/tickets/ticketarea_04.png" width="100" height="307" alt=""></td>

                            <td valign="top" style="background-color: #c91429;">
                                <table cellpadding="0" cellspacing="0" style="margin: 20px auto;height: 256px">
                                    <tr> 
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">
                                            '.$_POST['one'].'
                                        </td>
                                        <td style="background-color: #000000;width: 95px;padding: 10px 4px">&nbsp;</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">
                                            '.$_POST['two'].'
                                        </td>
                                        <td style="background-color: #000000;width: 95px;padding: 10px 4px">&nbsp;</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">
                                            '.$_POST['three'].'
                                        </td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">
                                           '.$_POST['four'].'
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="background-color: #000000;width: 95px;padding: 10px 4px">&nbsp;</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">'.$_POST['five'].'</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">'. $_POST['six'].'</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">'.$_POST['seven'].'</td>
                                        <td style="background-color: #000000;width: 95px;padding: 10px 4px">&nbsp;</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">'.$_POST['eight'].'</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">'.$_POST['nine'].'</td>
                                        <td style="background-color: #000000;width: 95px;padding: 10px 4px">&nbsp;</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">'.$_POST['ten'].'</td>
                                        <td style="background-color: #000000;width: 95px;padding: 10px 4px">&nbsp;</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">'.$_POST['eleven'].'</td>
                                        <td style="background-color: #ffffff;width: 95px;padding: 10px 4px">'.$_POST['twelve'].'</td>
                                    </tr>

                                </table>
                            </td>

                            <td valign="top" style="line-height: 0">
                                <img src="images/tickets/ticketarea_06.png" width="109" height="307" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="line-height: 0">
                                <img src="images/tickets/ticketarea_07.png" width="100" height="74" alt=""></td>
                            <td valign="top" style="line-height: 0">
                                <img src="images/tickets/ticketarea_08.png" width="589" height="74" alt=""></td>
                            <td valign="top" style="line-height: 0">
                                <img src="images/tickets/ticketarea_09.png" width="109" height="74" alt=""></td>
                        </tr>
                    </table>';
					}
					?>