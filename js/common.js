function getStatus(){
	var status = localStorage.getItem("status");
	var statusBar = document.getElementById('statusBar');
	if (status!==undefined && status!='') {
		statusBar.innerHTML = status;
	}
	else {
		statusBar.innerHTML = "Изменить статус";
	}
}
function openComments(){
	var xhr = new XMLHttpRequest();
	var params = 'action=open';
	xhr.open("GET", 'php/ajax.php?' + params, true);
	xhr.onreadystatechange = function(){
	  	if (xhr.readyState==4 && xhr.status==200)
	    {
	    	document.getElementById("comments").innerHTML=xhr.responseText;
	    }
	};
	xhr.send();
}
function addComment(name, message){
	if(name == '' || message ==''){
		alert('Заполните все поля!');
		return false;
	}
	var xhr = new XMLHttpRequest();
	var params = 'action=add&name=' + encodeURIComponent(name) +
	  '&message=' + encodeURIComponent(message);
	xhr.open("GET", 'php/ajax.php?' + params, true);
	xhr.onreadystatechange = function(){
		if (xhr.readyState==4 && xhr.status==200)
	    {
	    	document.getElementById('login').value = '';
	    	document.getElementById('message').value = '';
	    	openComments();
	    }
	}
	xhr.send();
}
function deleteComments(id){
	var xhr = new XMLHttpRequest();
	var params = 'action=delete&id=' + id;
	xhr.open("GET", 'php/ajax.php?' + params, true);
	xhr.onreadystatechange = function(){
		if (xhr.readyState==4 && xhr.status==200)
	    {
	    	openComments();
	    }
	}
	xhr.send();
}
function changeStatus(){
	var statusUpdate = document.getElementById("statusUpdate");
	statusUpdate.style.display = 'block';
}
function saveStatus(){
	var status = document.getElementById('status').value;
	var statusBar = document.getElementById('statusBar');
	var statusUpdate = document.getElementById("statusUpdate");
	statusUpdate.style.display = 'none';
	localStorage.setItem('status', status);
	if (status!="") {
		statusBar.innerHTML = status;
	}
	else {
		statusBar.innerHTML = "Изменить статус";
	}

}
function dropdownMenu(){
	if(document.getElementById("dropdownMenu").style.display!='block'){
		document.getElementById("dropdownMenu").style.display = 'block';
		document.getElementById("dd").style.display = 'none';
		document.getElementById("ddo").style.display = 'inline-block';
	}
	else{
		document.getElementById("dropdownMenu").style.display = 'none';
		document.getElementById("dd").style.display = 'inline-block';
		document.getElementById("ddo").style.display = 'none';
	}
}
function loadPage(){
	getStatus();
	openComments();
	statusBar.onclick = changeStatus;
	status.keyup = saveStatus;
	dropdownMenuLink.onclick = dropdownMenu;
	status = document.getElementById("status");
	statusBar = document.getElementById("statusBar");
	dropdownMenu = document.getElementById("dropdownMenuLink");
}
document.addEventListener("DOMContentLoaded", loadPage);
