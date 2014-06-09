// Establish connection to server
var conn = new WebSocket('ws://localhost:8080');

conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onerror = function(e) {
	alert("Network Problem");
}

conn.onmessage = function(e) {
	// Replace image with the screenshot received
	var imgDiv = document.getElementById("imgDiv");
	imgDiv.innerHTML = e.data;
    //console.log(e.data);
};

function init() {
	var imgDiv = document.getElementById("imgDiv");
	imgDiv.onmousemove = function(evt) {
		if(evt == null) { evt = window.event }
		sendChat(1,evt.clientX,evt.clientY,0);
	}

	imgDiv.onmousedown = function(evt) {
		sendChat(1,evt.clientX,evt.clientY,evt.which);
	}
}

document.onkeypress = function(evt) {
    // Prevent default actions of the keys ( backspace, escape etc )
    evt.preventDefault();
    evt.stopPropagation();

    // For keys for which charcode doesn't exist, sen keycode
	var specKeys = [8, 13, 27, 37, 38, 39, 40, 46];

    if( specKeys.indexOf( evt.keyCode ) > -1 ) {
    	sendChat(0,evt.keyCode,2,0);
    } else {
		sendChat(0,evt.charCode,1,0);
	}
	
}

// Request screenshot from server after every second
setInterval(function() {sendChat(3,0,0,0);},1000);

function sendChat(typ,fst,snd,trd) {
	// Type : 
	// Mouse action : [1, X, Y, <0 - move , 1,2,3 - clicks>]
	// Keypress : [2, code, <1 - charCode, 2 - keyCode>, 0]
	// Request screenshot : [3,0,0,0]

	if(typ == 1) {
		// map movements according to screen

		// adjust for border and margins
		snd = snd - 15;
		fst = fst + 15;

		// take image resolution
		var imgWidth = document.getElementById('imgDiv').offsetWidth;
		var imgHeight = document.getElementById('imgDiv').offsetHeight;
  
  		// Give Server Screen Resolution
		var width = 1366;
		var height = 786;

		// map coordinates
		fst = Math.round((fst/imgWidth)*width);
		snd = Math.round((snd/imgHeight)*height);

	}

	// Convert array to JSON
	var textToSend = [typ, fst, snd, trd];
	var JSONText = JSON.stringify(textToSend);
	//console.log(JSONText);

	// Send to server
	conn.send(JSONText);
}