/*////////////////////////////////////////////////////
///////////////////// IMPORTANT //////////////////////
//// NOT CHANGE DATA INGLUDED INTO BRACES { } ////////
/////////////////////////////////////////////////////*/
/*
 Validation Message
*/
var validator_message = {
		   'validator_required':"Obbligatorio.",
		   'validator_remote':"Valore esistente.",
		   'validator_email':"E-mail non valida.",
		   'validator_website':"Url errato.",
		   'validator_websitehttp':"Url errato.",
		   'validator_url':"Url errato.",
		   'validator_equalTo':"Le Password non coincidono.",
		   'validator_date':"Data non valida.",
		   'validator_dateISO':"Iinserire una data in formato (ISO).",
		   'validator_number':"Solo Numeri",
		   'validator_data':"Data non valida",
		   'validator_accept':"Estensione non accettata.",
		   'validator_maxlength':"Massimo {0} caratteri.",
		   'validator_minlength':"Almeno {0} caratteri.",
		   'validator_rangelength':"Valori tra {0} e {1} caratteri.",
		   'validator_range':"Valori tra {0} e {1}.",
		   'validator_max':"Valori minori o uguali a {0}.",		   
		   'validator_min':"Valori maggiori o uguali a {0}.",
		   'validator_least_one':"Compilare almeno {0} campo."
};
jsIn.addDict(validator_message);
var $lang_client_ = {           
           'general_error_title':"ERRORE!!!",
		   'general_warning_title':"ATTENZIONE!!!",
		   'general_success_title':"CONGRATULAZIONI!!!",
		   'wrong_login_message':"UserID e/o Password errati",
		   'account_not_confirmed_message':"Devi ancora confermare il Tuo Account.<br/>Se non hai ricevuto la e-mail con il link per l\'attivazione, contattaci!!",		   
		   'tax_code': "Codice F.",
		   'vat':"IVA",
		   'retieve_data_not_match':"I dati immessi non esistono nel nostro Database",
		   'retieve_data_success':"Leggi la Tua e-mail (<strong>{data}</strong>) e Segui le istruzioni per settare nuovi parametri di accesso.<br/>Grazie.",
		   'send_order_not_success_cart_updated':"Il Tuo carrello è stato aggiornato perchè altri utenti hanno completato un ordine in cui erano coninvolti i Tuoi prodotti.<br/>Potrai vedere i dettagli dell\'aggiornamento nel report e decidere se continuare oppure no con l\'ordine.",
		   'availability_update_after_order':"La giacenza per questo prodotto è di {availability}",
		   'product_removed_from_cart_because_not_available':"Questo prodotto è stato rimosso dal Tuo carrello perchè non è più disponibile",
		   'pay_now_button':"Paga Ora",
		   'alert_title_for_product_not_saleable_online':"Prodotto non vendibile online",
		   'alert_message_for_product_not_saleable_online':'Questo prodotto può essere acquistato solo nel nostro punto vendita.<br/>Per Informazioni <a href="{link}">Contattaci</a>',
		   'contact_form_message_success':"Il Tuo messaggio è stato inviato con successo.<br/>Un nostro operatore risponderà appena possibile.<br/>Grazie",
		   'product_not_available':"Spiacenti!!!<br/>Al momento non abbiamo disponibilità per questo prodotto",
		   'wrong_quantity_purchased':"<strong>Inserire un valore maggiore di 0</strong>",
		   'alert_product_add_to_cart':'{name}<br/><img class="img-polaroid" width="100" src="{url_img}" alt="{name}" /><br/><br/>è stato inserito nel carrello<br/><br/><span class="close-add-to-cart-loader btn btn-info squared unbordered solid">Continua lo Shopping</span> <a href="{link_cart}" class="close-add-to-cart-loader btn btn-primary squared unbordered solid">Vai al Carrello</a>',
		   'client_address':'<strong>{name}</strong><br/>{address}<br/>{city} - {zip_code}<br/><abbr title="Phone">T: </abbr> {phone}<br/>{fax}<abbr title="E-mail">@: </abbr> {email}',
		   'filter_price_from':"da",
		   'filter_price_to':"a",
		   'userid_exists':"UserID esistente",
		   'email_exists':"E-mail esistente"
};
jsIn.addDict($lang_client_);