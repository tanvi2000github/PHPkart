		   /////////////////////////////////--> for b2b plugin
		   $('body').on('click','#reseller_info',function(){
				$.bootalert({
					ID        : 'alert-reseller_info-modal',
					LabelText : __('LABEL_FOR_INFO_POPUP'),
					TypeLabel : 'info',
					BodyText  : __('INFO_ON_RESELLERS'),
					TypeBody  : ''
				});			   
		   });
			$('body').on('click',':radio[name="is_company"]',function(){
			  if($('#guest:checked').length <= 0){
				if($(this).prop('checked') == false && $(this).val() == 'private'){
					$('#reseller,[rel="reseller"],#reseller_info').addClass('hidden');
				}
				if($(this).prop('checked') == false && $(this).val() == 'company'){
					$('#reseller,[rel="reseller"],#reseller_info').removeClass('hidden');
				}					
			  }
			});
			$('body').on('click','#guest,[for="guest"]',function(){
				$('#reseller,[rel="reseller"],#reseller_info').addClass('hidden');
			});
			$('body').on('click','#send_retailer_request_pop',function(){
				$.bootalert({
					ID         : 'alert-retailer_request',
					LabelText  : __('LABEL_FOR_SEND_RETAILER_REQUEST_POPUP'),
					TypeLabel  : 'info',
					BodyText   : __('TEXT_FOR_SEND_RETAILER_REQUEST_POPUP'),
					TypeBody   : '',
					Footer     : true,
					FooterText : __('FOOTER_FOR_SEND_RETAILER_REQUEST_POPUP')
				});				
			});
			$('body').on('click','#send_retailer_request',function(){
				$.ajax({
					type : 'POST',
					url : $('body').data('abs_client_path')+'/content/plugins/businesstype/send-retailer-request.php',	
					success:function(data){
						if(data == 'ok'){
							$('#alert-retailer_request .modal-footer,').empty();	
							$('<div class="well well-small">\
							      <strong class="lead">'+__('ACCOUNT_TITLE_RETAILER_STATUS')+'</strong><br/><br/>\
						          <strong class="text-info">'+__('RETAILER_REQUEST_STATUS_IN_PROCESS')+'</strong><br/><br/>\
						       </div>').insertBefore($('#send_retailer_request_pop'));
							$('#send_retailer_request_pop').remove();					 
							$('#alert-retailer_request .modal-body').html('<strong class="alert alert-success">'+__('TEXT_RETAILER_REQUEST_SUCCESS')+'</strong>');
						}
					}
				});
			});
		   $(function(){
			   $('<pan style="margin-left:10px"></span><input type="checkbox" id="reseller" data-icon="icon-ok icon-white" name="reseller" class="bootstyl" data-label-name="'+__('BUTTON_RESELLER')+'" data-additional-classes="btn-inverse solid hidden" value="1" /> \
                     <span id="reseller_info" class="hidden btn btn-info"><i class="icon-info-sign icon-white"></i></span>').insertAfter($('[rel="company"]').closest('div.btn-group'));
		   });
                    					 