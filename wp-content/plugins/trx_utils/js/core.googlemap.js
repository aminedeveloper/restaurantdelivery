function pizzahouse_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof PIZZAHOUSE_STORAGE['googlemap_init_obj'] == 'undefined') pizzahouse_googlemap_init_styles();
	PIZZAHOUSE_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		PIZZAHOUSE_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: PIZZAHOUSE_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		pizzahouse_googlemap_create(id);

	} catch (e) {
		if (typeof dcl == 'function') { 
  dcl(PIZZAHOUSE_STORAGE['strings']['googlemap_not_avail']);
}
	};
}

function pizzahouse_googlemap_create(id) {
	"use strict";

	// Create map
	PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].dom, PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers)
		PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	pizzahouse_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].map)
			PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].map.setCenter(PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function pizzahouse_googlemap_add_markers(id) {
	"use strict";
	for (var i in PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (PIZZAHOUSE_STORAGE['googlemap_init_obj'].geocoder == '') PIZZAHOUSE_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			PIZZAHOUSE_STORAGE['googlemap_init_obj'].geocoder.geocode({address: PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						pizzahouse_googlemap_add_markers(id);
						}, 200);
				} else
					dcl(PIZZAHOUSE_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].title;
			PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].map.setCenter(PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].opt.center);
			}
			
			// Add description window
			if (PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].map,
								PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			PIZZAHOUSE_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function pizzahouse_googlemap_refresh() {
	"use strict";
	for (var id in PIZZAHOUSE_STORAGE['googlemap_init_obj']) {
		pizzahouse_googlemap_create(id);
	}
}

function pizzahouse_googlemap_init_styles() {
	"use strict";
	// Init Google map
	PIZZAHOUSE_STORAGE['googlemap_init_obj'] = {};
	PIZZAHOUSE_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.pizzahouse_theme_googlemap_styles!==undefined)
		PIZZAHOUSE_STORAGE['googlemap_styles'] = pizzahouse_theme_googlemap_styles(PIZZAHOUSE_STORAGE['googlemap_styles']);
}