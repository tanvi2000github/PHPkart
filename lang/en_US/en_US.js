/*////////////////////////////////////////////////////
///////////////////// IMPORTANT //////////////////////
//// NOT CHANGE DATA INGLUDED INTO BRACES { } ////////
/////////////////////////////////////////////////////*/
/*
 Validation Message
*/
var validator_message = {
		   'validator_required':"Required Field.",
		   'validator_remote':"Existing Value.",
		   'validator_email':"Invalid E-mail address.",
		   'validator_website':"Wrong Url.",
		   'validator_websitehttp':"Wrong Url.",
		   'validator_url':"Wrong Url.",
		   'validator_equalTo':"Passwords do not match.",
		   'validator_date':"Invalid date.",
		   'validator_dateISO':"Please enter a valid date (ISO).",
		   'validator_number':"Only Numbers",
		   'validator_data':"Invalid date",
		   'validator_accept':"Extension is not accepted.",
		   'validator_maxlength':"Maximum {0} characters.",
		   'validator_minlength':"At least {0} characters.",
		   'validator_rangelength':"Only characters between {0} and {1} of length .",
		   'validator_range':"Only values ​​between {0} and {1}.",
		   'validator_max':"Only values ​​less than or equal to {0}.",		   
		   'validator_min':"Only values ​​greater than or equal to {0}.",
		   'validator_least_one':"Fill in at least {0} field correctly."
};
jsIn.addDict(validator_message);
var $lang_client_ = {           
           'general_error_title':"ERROR!!!",
		   'general_warning_title':"WARNING!!!",
		   'general_success_title':"CONGRATS!!!",
		   'wrong_login_message':"Wrong UserID and/or Password",
		   'account_not_confirmed_message':"You still have to confirm Your Account.<br/>If you have not received the e-mail with a link to activate it contact us!!",		   
		   'tax_code': "Tax Code",
		   'vat':"VAT",
		   'retieve_data_not_match':"The data entered does not exist in our Database",
		   'retieve_data_success':"Check your E-mail box (<strong>{data}</strong>) and follow the instructions to set new access data.<br/>Thank You.",
		   'send_order_not_success_cart_updated':"Your cart has been updated as other customers have placed an order in which they were involved your products.<br/>You will see the update details in the report and decide whether to proceed with the order.",
		   'availability_update_after_order':"The amount for this product is {availability}",
		   'product_removed_from_cart_because_not_available':"This product has been removed from your cart because it is no longer available",
		   'pay_now_button':"Pay Now",
		   'alert_title_for_product_not_saleable_online':"Product is not purchased online",
		   'alert_message_for_product_not_saleable_online':'This product can be bought only in our store.<br/> For more information <a href="{link}">Contact Us</a>',
		   'contact_form_message_success':"Your message has been sent successfully.<br/>One of our operators will reply you as soon as possible.<br/>Thank You",
		   'product_not_available':"Sorry!!!<br/>At the moment we have no availability for this product",
		   'wrong_quantity_purchased':"<strong>Enter a value greater than 0</strong>",
		   'alert_product_add_to_cart':'{name}<br/><img class="img-polaroid" width="100" src="{url_img}" alt="{name}" /><br/><br/>has been added to shopping cart<br/><br/><span class="close-add-to-cart-loader btn btn-info squared unbordered solid">Continue Shopping</span> <a href="{link_cart}" class="close-add-to-cart-loader btn btn-primary squared unbordered solid">View Cart</a>',
		   'client_address':'<strong>{name}</strong><br/>{address}<br/>{city} - {zip_code}<br/><abbr title="Phone">T: </abbr> {phone}<br/>{fax}<abbr title="E-mail">@: </abbr> {email}',
		   'filter_price_from':"from",
		   'filter_price_to':"to",
		   'userid_exists':"UserID existing",
		   'email_exists':"E-mail existing"
};
jsIn.addDict($lang_client_);