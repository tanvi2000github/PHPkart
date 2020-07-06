(function($){  

//////////////////////// FUNCTION TO GIVE AUTOCOMPLETE TO EACH CALC INPUTS //////////////
function autocomplete_map(from_field){
     new google.maps.places.Autocomplete($(from_field)[0]);
     $(from_field).attr('placeholder','')   
}
//google.maps.event.addDomListener(window, 'load', center);
////////////////////////// START GOOGLE MAP API /////////////////
  var myOptions = {
      zoom: 7,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
  , rendererOptions = {
    draggable: true
  }
  , directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions)
  , directionsService = new google.maps.DirectionsService()
  , map
  , oldDirections = []
  , currentDirections
  , geocoder = new google.maps.Geocoder();

/****** start geolocation ********/
  function codeLatLng(lat, lng,$container_geolocation,$container_geolocation_text,$container_geolocation_val) {
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[1]) {
         //formatted address
          $container_geolocation_val == 'val' ? 
		         $($container_geolocation).val($container_geolocation_text.replace('{position}',results[0].formatted_address)) : 
				 $($container_geolocation).html($container_geolocation_text.replace('{position}',results[0].formatted_address));
        } else {
          $container_geolocation_val == 'val' ? 
		    $($container_geolocation).val($container_geolocation_text.replace('{position}','')) : 
			$($container_geolocation).html($container_geolocation_text.replace('{position}',''));
        }
      } else {
		$container_geolocation_val == 'val' ?
          $($container_geolocation).val($container_geolocation_text.replace('{position}','')) :
		  $($container_geolocation).html($container_geolocation_text.replace('{position}',''));
      }
    });
  } 
    
function center(imap,iaddress,info_window,zoom,zoom_well){
    map = new google.maps.Map(imap, {
      zoom: zoom,
	  scrollwheel: zoom_well,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var address = iaddress;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
		google.maps.event.addDomListener(window, 'resize', function() {
		  map.setCenter(results[0].geometry.location);
		});		
        if(info_window != ''){
          var infowindow = new google.maps.InfoWindow({
            content: info_window
          });   
          infowindow.open(map,marker);
           google.maps.event.addListener(marker, 'click', function() {
              infowindow.open(map,marker);              
           });
        }
      } else {
      }
    });
}    



  function initialize(imap,ipanel,start,end,DistanceContainer) {
    map = new google.maps.Map(imap, myOptions);
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(ipanel); 
    google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
        if (currentDirections) {
          oldDirections.push(currentDirections);
        }
        currentDirections = directionsDisplay.getDirections();  
        computeTotalDistance(directionsDisplay.directions,DistanceContainer);
    });    
    calcRoute(imap,start,end); 
  }

  function calcRoute(imap,start,end) {  
    var  request = {
      origin: start,
      destination: end,
      travelMode: google.maps.DirectionsTravelMode.DRIVING
    };    
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
      }
      if ( status != 'OK' ){ alert(status); return false;}	  
    }); 
  }
 
  function computeTotalDistance(result,DistanceContainer) {
    var total = 0;
    var myroute = result.routes[0];
    for (i = 0; i < myroute.legs.length; i++) {
      total += myroute.legs[i].distance.value;
    }
    total = total / 1000.
    $(DistanceContainer).html('Distanza Totale: '+total + " km")
  } 
////////////////////////// END GOOGLE MAP API /////////////////
  
 $.fn.JQMap = function(options) {  
            /***************************************/
            /** List of available default options **/
            /***************************************/
            var defaults = {  
            W_map               :  '',//--> map width
            H_map               :  '',//--> map height
            Destination         :  '',//--> Set a fix destinatio
            TexInfoWindow       :  '',//--> Info Window on page load
            FromField           :  '',//--> selector of FROM FIELD
            CalculateButton     :  '',//--> selector of button calculation
            RoutePanel          :  '',//--> selector if route panel
            DistanceContainer   :  '',//--> selector where calculated distance is written
			container_geolocation : '.current_location',//--> class or id for geolocation container
			container_geolocation_val : 'val', //--> type of container (val/html)
			container_geolocation_text : '{position}', //--> geolocation container text	
			scrollwheel: true,	//--> zoom with mouse well	
            ZoomStartPoint      : 7 //--> zoom for centered map
            }; 
          
   var o = $.extend(defaults, options); 
   $('head').append(' <style type="text/css">.map_style_for_plugin_mapcontact img{max-width:none;max-height:none;}</style>');
    return this.each(function() {                     
//--------------------------------------------------------------------------////---------------------------------------------------------//    
          /************** create map, form to calc route and info panel *********************/    
               var $this = $(this);
			  $this.addClass('map_style_for_plugin_mapcontact');
              if(o.W_map != '')
                $this.css('height',o.W_map+'px');
              if(o.H_map != '')
                $this.css('height',o.H_map+'px');
              if(o.FromField != '' && $(o.FromField).length > 0){
               autocomplete_map(o.FromField);
              }
			  if(o.container_geolocation != '' && o.container_geolocation_text != ''){
				function successFunction(position,$container_geolocation,$container_geolocation_text) {
					var lat = position.coords.latitude,
					    lng = position.coords.longitude,
					    $container_geolocation = o.container_geolocation,
						$container_geolocation_text = o.container_geolocation_text;
					codeLatLng(lat, lng,$container_geolocation,$container_geolocation_text,'val');
				}
				function errorFunction($container_geolocation,$container_geolocation_text){
					  var $container_geolocation = o.container_geolocation,
						  $container_geolocation_text = o.container_geolocation_text;					
					$($container_geolocation).val($container_geolocation_text.replace('{position}',''));
				}
				if (navigator.geolocation) {
				  navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
				} 				
			  }			  
               $(o.CalculateButton).click(function(){
                   initialize($this[0],$(o.RoutePanel)[0],$(o.FromField).val(),o.Destination,$(o.DistanceContainer)[0]);
               });
                 center($this[0],o.Destination,o.TexInfoWindow,o.ZoomStartPoint,o.scrollwheel); 
                              
//--------------------------------------------------------------------------////---------------------------------------------------------//

    });  
 };  
})(jQuery);