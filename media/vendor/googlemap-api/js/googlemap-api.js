function initialize(lat, lng, labelContent) {
   if(typeof(lat)==='undefined') lat = 44.831268;
   if(typeof(lng)==='undefined') lng = -0.570826;
   var myLatlng = new google.maps.LatLng(lat, lng);
	var mapOptions = {
	   center: myLatlng,
	   zoom: 15,
	   scrollwheel: false,
	   draggableCursor:'default',
	   backgroundColor: '#ECF0F1',
	   disableDefaultUI: true,
	   styles: [
    {
        "stylers": [
            {
                "hue": "#ECF0F1"
            },
            {
                "saturation": -70
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
            {
                "lightness": 100
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
	{
	    "featureType": "road.arterial",
	    "elementType": "labels",
	    "stylers": [
		      { 
		      	"visibility": "on"
		      }
	    ]
	}
]
	};
  var map = new google.maps.Map(document.getElementById("map-canvas"),
        mapOptions);
  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Hello World!'
  });
     var marker1 = new MarkerWithLabel({
       position: myLatlng,
       draggable: false,
       raiseOnDrag: false,
       map: map,
       labelContent: labelContent,
       labelAnchor: new google.maps.Point(42, 11),
       labelStyle: {opacity: 0.75},
       icon: {}
     });
}
