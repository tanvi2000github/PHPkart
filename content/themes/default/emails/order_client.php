<?php
$email_template_client = <<<'EOF'
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=320, target-densitydpi=device-dpi">
<style type="text/css">
/* Mobile-specific Styles */
@media only screen and (max-width: 660px) { 
table[class=w0], td[class=w0] { width: 0 !important; }
table[class=w10], td[class=w10], img[class=w10] { width:10px !important; }
table[class=w15], td[class=w15], img[class=w15] { width:5px !important; }
table[class=w30], td[class=w30], img[class=w30] { width:10px !important; }
table[class=w60], td[class=w60], img[class=w60] { width:10px !important; }
table[class=w125], td[class=w125], img[class=w125] { width:80px !important; }
table[class=w130], td[class=w130], img[class=w130] { width:55px !important; }
table[class=w140], td[class=w140], img[class=w140] { width:90px !important; }
table[class=w160], td[class=w160], img[class=w160] { width:180px !important; }
table[class=w170], td[class=w170], img[class=w170] { width:100px !important; }
table[class=w180], td[class=w180], img[class=w180] { width:80px !important; }
table[class=w195], td[class=w195], img[class=w195] { width:80px !important; }
table[class=w220], td[class=w220], img[class=w220] { width:80px !important; }
table[class=w240], td[class=w240], img[class=w240] { width:180px !important; }
table[class=w255], td[class=w255], img[class=w255] { width:185px !important; }
table[class=w275], td[class=w275], img[class=w275] { width:135px !important; }
table[class=w280], td[class=w280], img[class=w280] { width:135px !important; }
table[class=w300], td[class=w300], img[class=w300] { width:140px !important; }
table[class=w325], td[class=w325], img[class=w325] { width:95px !important; }
table[class=w360], td[class=w360], img[class=w360] { width:140px !important; }
table[class=w410], td[class=w410], img[class=w410] { width:180px !important; }
table[class=w470], td[class=w470], img[class=w470] { width:200px !important; }
table[class=w580], td[class=w580], img[class=w580] { width:280px !important; }
table[class=w640], td[class=w640], img[class=w640] { width:300px !important; }
table[class*=hide], td[class*=hide], img[class*=hide], p[class*=hide], span[class*=hide] { display:none !important; }
table[class=h0], td[class=h0] { height: 0 !important; }
p[class=footer-content-left] { text-align: center !important; }
#headline p { font-size: 30px !important; }
.article-content, #left-sidebar{ -webkit-text-size-adjust: 90% !important; -ms-text-size-adjust: 90% !important; }
.header-content, .footer-content-left {-webkit-text-size-adjust: 80% !important; -ms-text-size-adjust: 80% !important;}
img { height: auto; line-height: 100%;}
 } 
/* Client-specific Styles */
#outlook a { padding: 0; }	/* Force Outlook to provide a "view in browser" button. */
body { width: 100% !important; }
.ReadMsgBody { width: 100%; }
.ExternalClass { width: 100%; display:block !important; } /* Force Hotmail to display emails at full width */
/* Reset Styles */
/* Add 100px so mobile switch bar doesn't cover street address. */
body { background-color: #ececec; margin: 0; padding: 0; }
img { outline: none; text-decoration: none; display: block;}
br, strong br, b br, em br, i br { line-height:100%; }
h1, h2, h3, h4, h5, h6 { line-height: 100% !important; -webkit-font-smoothing: antialiased; }
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: blue !important; }
h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {	color: red !important; }
/* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited { color: purple !important; }
/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */  
table td, table tr { border-collapse: collapse; }
.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
color: black; text-decoration: none !important; border-bottom: none !important; background: none !important;
}	/* Body text color for the New Yahoo.  This example sets the font of Yahoo's Shortcuts to black. */
/* This most probably won't work in all email clients. Don't include code blocks in email. */
code {
  white-space: normal;
  word-break: break-all;
}
#background-table { background-color: #ececec; }
/* Webkit Elements */
#top-bar { border-radius:6px 6px 0px 0px; -moz-border-radius: 6px 6px 0px 0px; -webkit-border-radius:6px 6px 0px 0px; -webkit-font-smoothing: antialiased; background-color: #818a8c; color: #d6d3bc; }
#top-bar a { font-weight: bold; color: #fff4e5; text-decoration: none;}
#footer { border-radius:0px 0px 6px 6px; -moz-border-radius: 0px 0px 6px 6px; -webkit-border-radius:0px 0px 6px 6px; -webkit-font-smoothing: antialiased; }
/* Fonts and Content */
body, td { font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; }
.header-content, .footer-content-left, .footer-content-right { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
/* Prevent Webkit and Windows Mobile platforms from changing default font sizes on header and footer. */
.header-content { font-size: 12px; color: #d6d3bc; }
.header-content a { font-weight: bold; color: #fff4e5; text-decoration: none; }
#headline p { color: #e7cba3; font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size: 36px; text-align: center; margin-top:0px; margin-bottom:30px; }
#headline p a { color: #e7cba3; text-decoration: none; }
#left-sidebar .toc-item { font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size: 12px; line-height: 16px; color: #28779C; margin-top: 0px; margin-bottom: 6px; }
#left-sidebar .toc-item a { color: #28779C; text-decoration: none; }
#left-sidebar .toc-heading { font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size: 11px; line-height: 15px; color:#000000; font-weight:bold; }
#left-sidebar .toc-heading a { color: #000000; text-decoration: none; }
#left-sidebar .left-column-heading { font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size: 11px; line-height:15px; color: #000000; font-weight:bold; }
#left-sidebar .left-column-heading a { color: #000000; text-decoration:none; }
#left-sidebar .left-column-subhead { font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size: 13px; line-height: 16px; color: #999999; font-weight: bold; margin-top: 0px; margin-bottom: 16px; }
#left-sidebar .left-column-subhead a { color: #999999; text-decoration:none; }
#left-sidebar .left-column-content { font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; font-size: 12px; line-height: 16px; color: #33454d; margin-top: 0px; margin-bottom: 16px; }
#left-sidebar .left-column-content a { color: #28779C; text-decoration: none; }
.article-title { font-size: 18px; line-height:24px; color: #28779c; font-weight:bold; margin-top:0px; margin-bottom:18px; font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; }
.article-title a { color: #28779c; text-decoration: none; }
.article-title.with-meta {margin-bottom: 0;}
.article-meta { font-size: 13px; line-height: 20px; color: #ccc; font-weight: bold; margin-top: 0;}
.article-content { font-size: 13px; line-height: 18px; color: #444444; margin-top: 0px; margin-bottom: 18px; font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif; }
.article-content a { color: #00e2f7; font-weight:bold; text-decoration:none; }
.article-content img { max-width: 100% }
.article-content ol, .article-content ul { margin-top:0px; margin-bottom:18px; margin-left:19px; padding:0; }
.article-content li { font-size: 13px; line-height: 18px; color: #444444; }
.article-content li a { color: #00e2f7; text-decoration:underline; }


.article-content p {margin-bottom: 15px;}
.footer-content-left { font-size: 12px; line-height: 15px; color: #D6D3BC; margin-top: 0px; margin-bottom: 15px; }
.footer-content-left a { color: #FFF4E5; font-weight: bold; text-decoration: none; }
.footer-content-right { font-size: 11px; line-height: 16px; color: #D6D3BC; margin-top: 0px; margin-bottom: 15px; }
.footer-content-right a { color: #FFF4E5; font-weight: bold; text-decoration: none; }
#footer { background-color: #818A8C; color: #D6D3BC; }
#footer a { color: #FFF4E5; text-decoration: none; font-weight: bold; }
#permission-reminder { white-space: normal; }
#street-address { color: #fafa78; white-space: normal; }
</style>
<!--[if gte mso 9]>
<style _tmplitem="14061" >
.article-content ol, .article-content ul {
   margin: 0 0 0 24px;
   padding: 0;
   list-style-position: inside;
}
</style>
<![endif]--></head><body><table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table">
	<tbody><tr>
		<td align="center" bgcolor="#ececec">
        	<table class="w640" style="margin:0 10px;" width="640" cellpadding="0" cellspacing="0" border="0">
            	<tbody><tr><td class="w640" width="640" height="20"></td></tr>                
                <tr>
                <td id="header" class="w640" width="640" align="left">
    
    <div align="left" style="text-align: left">
        <a href="{shop_url}">
        <img id="customHeaderImage" label="Header Image" editable="true" width="200" src="{template_logo}" class="w640" border="0" align="top" style="display: inline">
        </a>
    </div><br><br>
    
    
</td>
                </tr>
                
                <tr id="twocolumn-content-row"><td class="w640" width="640"><table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
	<tbody><tr>
        <td class="w15" width="15" bgcolor="#e2e2e2"></td>
        <td id="left-sidebar" class="w140" width="140" bgcolor="#e2e2e2" valign="top" align="left">
            
            <table class="w140" width="140" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w140" width="140" height="15"></td></tr>
                <tr>
                    <td class="w140" width="140">                        
                        <tableofcontents>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tbody>
                                {template_categories}
                            </tbody></table>
                        </tableofcontents>
                    </td>
                </tr>
                <tr><td class="w140" width="140" height="15"></td></tr>
            </tbody></table>
        </td>
        <td class="w15" width="15" bgcolor="#e2e2e2"></td>
        <td class="w470" width="470" bgcolor="#ffffff" valign="top">
            <table class="w470" width="470" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr><td class="w470" width="470" height="30"></td></tr>
            </tbody></table>
            <table class="w470" width="470" cellpadding="0" cellspacing="0" border="0">
                <tbody><tr>
                    <td class="w30" width="30"></td>
                    <td class="w410" width="410">
                        <repeater>                                                                            
                            <layout label="Text only">
                                <table class="w410" width="410" cellpadding="0" cellspacing="0" border="0">
                                    <tbody><tr>
                                        <td class="w410" width="410">
                                            <p align="left" class="article-title"><singleline repeatertitle="true" label="Article Title">{CLIENT_CONGRATS_MESSAGE}</singleline></p>
                                            <div align="left" class="article-content"><multiline label="Description">
                                             {EMAIL_TEXT_ORDER_CODE}: <strong>{tamplate_order_code}</strong>
                                            </multiline></div>
                                        </td>
                                    </tr>
                                    <tr><td class="w410" width="410" height="10"></td></tr>
                                </tbody></table>
                            </layout>
                                                
                            
                            <layout label="Text with full-width image">
                                <table class="w410" width="410" cellpadding="0" cellspacing="0" border="0">
                                    <tbody><tr>
                                        <td class="w410" width="410">
                                            <p align="left" class="article-title"><singleline repeatertitle="true" label="Article Title">{EMAIL_TEXT_PRODUCTS_ORDERED}</singleline></p>
                                        </td>
                                    </tr>
                                    {template_products_list}
                                    <tr><td class="w410" width="410" height="15"></td></tr>
                                </tbody></table>
                            </layout>
                            <layout label="Text with left-aligned image">
                                <table class="w410" width="410" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>                                                                              
                                        {template_products_report}
                                      <tr>
                                        <td class="w410" width="410" height="10"></td>
                                      </tr>                                      
                                    </tbody>
                                </table>
                            </layout>   
                            <layout label="Text only">
                                <table class="w410" width="410" cellpadding="0" cellspacing="0" border="0">
                                    <tbody><tr>
                                        <td class="w410" width="410">
                                            <p align="left" class="article-title"><singleline repeatertitle="true" label="Article Title">{EMAIL_TEXT_PAYMENT_INFO}</singleline></p>
                                            <div align="left" class="article-content"><multiline label="Description">
                                             {EMAIL_TEXT_CHOOSEN_PAYMENT_METHOD}: <strong>{tamplate_payment_method}</strong>
                                            </multiline></div>
                                            
                                            <div align="left" class="article-content"><multiline label="Description">
                                             {tamplate_payment_message}
                                            </multiline></div>                                            
                                        </td>
                                    </tr>
                                    <tr><td class="w410" width="410" height="10"></td></tr>
                                </tbody></table>
                            </layout> 
                            <layout label="Text only">
                                <table class="w410" width="410" cellpadding="0" cellspacing="0" border="0">
                                    <tbody><tr>
                                        <td class="w410" width="410">
                                            <p align="left" class="article-title"><singleline repeatertitle="true" label="Article Title">{EMAIL_TEXT_BILLING_ADDRESS}</singleline></p>
                                            <div align="left" class="article-content"><multiline label="Description">
                                             {tamplate_billing_address}
                                            </multiline></div>                                           
                                        </td>                                       
                                    </tr>
                                    <tr><td class="w410" width="410" height="10"></td></tr>
                                </tbody></table>
                            </layout>   
                            <layout label="Text only">
                                <table class="w410" width="410" cellpadding="0" cellspacing="0" border="0">
                                    <tbody><tr>
                                        <td class="w410" width="410">
                                            <p align="left" class="article-title"><singleline repeatertitle="true" label="Article Title">{EMAIL_TEXT_SHIPPNG_ADDRESS}</singleline></p>
                                            <div align="left" class="article-content"><multiline label="Description">
                                             {tamplate_shipping_address}
                                            </multiline></div>                                           
                                        </td>                                       
                                    </tr>
                                    <tr><td class="w410" width="410" height="10"></td></tr>
                                </tbody></table>
                            </layout>                                                                                                           
                        </repeater>
                    </td>
                    <td class="w30" width="30"></td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>
</td></tr>
                <tr>
                	<td class="w640" width="640"><table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
                        <tbody><tr>
                            <td class="w170" width="170" bgcolor="#e2e2e2" height="15"></td>
                            <td class="w470" width="470" bgcolor="#ffffff" height="15"></td>
                        </tr>
               		</tbody></table></td>
                </tr>
                
                <tr>
                <td class="w640" width="640">
    <table id="footer" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#818A8C">
        <tbody><tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="30"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
        <tr>
            <td class="w30" width="30"></td>
            <td class="w580" width="360" valign="top">
            <span class="hide"><p id="permission-reminder" align="left" class="footer-content-left"><span>{footer_message}</span></p></span>
            </td>
            <td class="hide w0" width="60"></td>
            <td class="hide w0" width="160" valign="top">
            <p id="street-address" align="right" class="footer-content-right"><span>{footer_address}</span></p>
            </td>
            <td class="w30" width="30"></td>
        </tr>
        <tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="15"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
    </tbody></table>
</td>
                </tr>
                <tr><td class="w640" width="640" height="60"></td></tr>
            </tbody></table>
        </td>
	</tr>
</tbody></table></body></html>
EOF;
?>