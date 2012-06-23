function keypress(e) {
	if(!e) {
		e = window.event;
	}
	
	switch(e.keyCode) {
		case 112:
			setTimeout('window.location.replace("index.php");', 10);
			return false;
		break;
		case 113:
			setTimeout('window.location.replace("add.php");', 10);
			return false;
		break;
		case 114:
			setTimeout('window.location.replace("place.php");', 10);
			return false;
		break;
		case 115:
			setTimeout('window.location.replace("generate.php");', 10);
			return false;
		break;
	}
}

function ffalse(ids) {
	i=1;
	while(true) {
		obj = document.getElementById(ids+i);
		if(!obj) break;
		
		if((obj.hasAttribute('required') || obj.className=='focus') && obj.value=='') {
			obj.focus();
			return false;
		}
		i++;
	}
	
	return true;
}

function ffalse_focus() {
	el = document.getElementsByClassName('focus');
	
	for(i=0; i<el.length; i++) {
		el.item(i).onfocus = "this.className=''";
	}
}

function uc_all(ids, check) {
	list = document.getElementById(ids).getElementsByTagName('input');
	
	for(i=0; i<list.length; i++) {
		box = list.item(i);
		if(box.type == 'checkbox') {
			box.checked = check;
		}
	}
}

function pups_init() {
	an = document.getElementsByTagName('a');
	
	for(i=0; i<an.length; i++) {
		if((an.item(i).href+'').match('cover.php\?')) {
			an[i].onclick = pups;
		}
	}
}

function pups(an) {
	an = an.target.parentNode;
	if(an) {
		win = window.open(an.href+'&pop', '_blank', 'dependent=yes,toolbar=no,resizable=yes');
		return false;
	}
}

function on_loaded() {
	ffalse_focus();
	pups_init();
}

document.onkeydown = keypress;
window.onload = on_loaded();
