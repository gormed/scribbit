

//************************************************************************
function favToggle(scribbleid) {
	favorites[scribbleid] = !favorites[scribbleid];
	if (favorites[scribbleid])
		favCount[scribbleid]++;
	else 
		favCount[scribbleid]--;

	var temp = document.getElementById('fav_'+scribbleid);
	if (favorites[scribbleid])
		temp.setAttribute('src', path+'/ressources/img/ico/star.png');
	else
		temp.setAttribute('src', path+'/ressources/img/ico/unstar.png');

	var temp = document.getElementById('count_'+scribbleid);
	temp.innerHTML = favCount[scribbleid];
}

//************************************************************************
function favImage(scribbleid) {
	var xmlhttp;

	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			favToggle(scribbleid); //on success
			console.log(xmlhttp.responseText);
		}
	}
	xmlhttp.open("POST",path+"/fav_image.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("scribbleid="+scribbleid);

}

//************************************************************************
function showComments() {
	var comments = document.getElementById('comments');
	var content = document.getElementById('content');

	if(comments.className == "hidden") {
		comments.className = "visible";

		// var canvas = document.getElementById('canvas');
		// canvas.setAttribute('id', 'canvasbug');
		//content.className = "contentspace";
	} else {
		comments.className = "hidden";
		// var canvas = document.getElementById('canvasbug');
		// canvas.setAttribute('id', 'canvas');
		//canvas.setAttribute('class', '');
		//content.className = "contentnospace";
	}
}

//************************************************************************
function saveComment () {
	
	var xmlhttp;
	var canvas = document.getElementById('canvas');
	var img = canvas.toDataURL("image/png");
	document.getElementById("upload").innerHTML="Sending...";

	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			location.reload();
			console.log(xmlhttp.responseText);
			//document.getElementById("upload").innerHTML="Sent!" + "\n" + xmlhttp.responseText;
		}
	}
	document.getElementById("upload").innerHTML="Sending...";
	xmlhttp.open("POST",path+"/upload_comment.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("data=" + img + "&scribbleid=" + scribbleid);
}