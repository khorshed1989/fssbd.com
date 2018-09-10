<?php
session_start();
//error_reporting(0);
include("config/dbconnect.php");

/**
* Initializing
* IPG client IP, port and socket variables 
*/

$IPGClientIP = "127.0.0.1";
$IPGClientPort = "10000";

$ERRNO = "";
$ERRSTR = "";
$SOCKET_TIMEOUT = 2;
$IPGSocket = "";

$error_message = "";
$invoice_sent_error = "";
$encryption_ERR = "";

$Invoice = "";
$EncryptedInvoice = "";

$IPGServerURL = "http://ipaytest.bracbank.com:8080/ipg/servlet_pay";

	$currencyCode = $_POST["cur"];
    $MerchantID = $_POST["mer_id"];
    $MerchantRefID = $_POST["mer_txn_id"];
    $TxnAmount = $_POST["txn_amt"];
	
/*  $ReturnURL = $_POST["ret_url"];
    $MerchantVar1 = $_POST["mer_var1"];
    $MerchantVar1 = $_POST["mer_var2"];
    $MerchantVar1 = $_POST["mer_var3"];
    $MerchantVar1 = $_POST["mer_var4"]; */
	
	
/**
* Step 3 : Recieve the encrypted Invoice from IPG client
*/

    if(!$socket_creation_err && !$invoice_sent_error) {
        while (!feof($IPGSocket)) {
            $EncryptedInvoice .= fread($IPGSocket, 8192);
        }    
    }

/**
* Step 4 : Close the socket connection
*/
    if(!$socket_creation_err) {
        fclose($IPGSocket);
    }

/**
* Step 5 : Check for Encryption errors
*/

    if (!(strpos($EncryptedInvoice, '<error_code>') === false && strpos($EncryptedInvoice, '</error_code>') === false && strpos($EncryptedInvoice, '<error_msg>') === false && strpos($EncryptedInvoice, '</error_msg>') === false)) {
        $encryption_ERR = true;
        
        $Error_code = substr($EncryptedInvoice, (strpos($EncryptedInvoice, '<error_code>')+12), (strpos($EncryptedInvoice, '</error_code>') - (strpos($EncryptedInvoice, '<error_code>')+12)));
    
        $Error_msg = substr($EncryptedInvoice, (strpos($EncryptedInvoice, '<error_msg>')+11), (strpos($EncryptedInvoice, '</error_msg>') - (strpos($EncryptedInvoice, '<error_msg>')+11)));

    }

	/**
	* Step 6 : Submit Encripted invoice to IPG server
	*/

    if(!$socket_creation_err && !$invoice_sent_error && !$encryption_ERR) {
        ?>
		<html>
        <head>
        </head>

            <body onLoad="document.send_form.submit();">
                <form name="send_form" method="post" action="<?php echo $IPGServerURL?>" >
                    <input type="hidden" value="<?php echo $EncryptedInvoice?>" name="encryptedInvoicePay">
                </form>
            </body>

        </html>
        <?php
    } else {
	// Following HTML code do not have to be available in the production code
	// here merchant can redirect to a error page
    // Eg : header('Location: http://www.example.com/error.php');
        ?>
        <html>
        <head>
        </head>
            <body>                
                <h2>Error in generating Encrypted invoice</h2><br /><br />
                <h4>Socket Creation Errors</h4>
                <ul>
                    <li><b>Socket Error No : </b> <?php print $ERRNO ?></li>
                    <li><b>Socket Error String : </b><?php print $ERRSTR ?></li>
                    <li><b>Application Error Message : </b><?php print $error_message ?></li>
                </ul>

                <h4>Encryption Errors</h4>
                <ul>
                    <li><b>Error Code : </b> <?php print $Error_code ?></li>
                    <li><b>Error Message : </b><?php print $Error_msg ?></li>
                </ul>
			</body>
        </html>
        <?php
    }
?>