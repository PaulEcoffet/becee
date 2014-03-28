function initialize() {
	var mapOptions = {
	   center: new google.maps.LatLng(44.831268, -0.570826),
	   zoom: 17,
	   scrollwheel: false,
	   draggableCursor:'default',
	   backgroundColor: '#fff',
	   disableDefaultUI: true,
	   styles: [
    {
        "stylers": [
            {
                "hue": "#252b2e"
            },
            {
                "saturation": -20
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
}
google.maps.event.addDomListener(window, 'load', initialize);