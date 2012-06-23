var xml;
var ajax;
var loc = false;

function ajax() {
	try {
		ajax = new XMLHttpRequest(); 
		return;
	}
	catch(e) {
		var activex = ['Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.3.0', 'Msxml2.XMLHTTP', 'Microsoft.XMLHTTP'];
		for(var i=0; i<activex.length; i++) {
			try {
				ajax = new ActiveXObject(activex[i]);
				return;
			}
			catch(e) {
			}
		}
	}
	
	fatalError('Przeglądarka nie obsługuje XMLHttpRequest');
}

function status(msg) {
	document.getElementById('result1').innerHTML = document.getElementById('result2').innerHTML
	document.getElementById('result2').innerHTML = document.getElementById('result3').innerHTML;
	document.getElementById('result3').innerHTML = msg;
}

function error(msg) {
	status('<span class="error">'+msg+'</span>');
	document.getElementById('audio').play();
}

function fatalError(msg) {
	status('<span class="error">'+msg+'</span>');
	throw new Exception('Błąd krytyczny: '+msg);
}

function changeLocation(element) {
	while(element && element.parentNode!=document && element.tagName != 'TABLE') {
		element = element.parentNode
	}
	
	if(!element || element.tagName != 'TABLE') {
		error('Podana lokalizacja nie istnieje lub jest nieznana.');
		return;
	}
	
	if(loc) {
		loc.className = '';
	}
	
	loc = element;
	loc.className = 'current';
	loc.scrollIntoView();
}

function makeCaption(regal, polka, rzad) {
	var caption = document.createElement('caption');
	caption.appendChild(document.createTextNode('Półka: '+regal+'/'+polka+'/'+rzad));
	caption.onclick = 'changeLocation(this)';
	return caption;
}

function textValue(element) {
	var text = '';
	
	for(var i=0; i<element.childNodes.length; i++) {
		if(element.childNodes.item(i) instanceof String) {
			text += element.childNodes.item(i);
		}
		else if(element.childNodes.item(i) instanceof Text) {
			text += element.childNodes.item(i).nodeValue;
		}
		else
		{
			text += textValue(element.childNodes.item(i));
		}
	}
	
	return text;
}

function makeHeader(caption) {
	var tr = document.createElement('tr');
	var th = document.createElement('th');
	th.appendChild(document.createTextNode('ID'));
	th.appendChild(document.createElement('br'));
	th.appendChild(document.createTextNode('status'));
	tr.appendChild(th);
	var th = document.createElement('th');
	th.appendChild(document.createTextNode('Autor'));
	th.appendChild(document.createElement('br'));
	th.appendChild(document.createTextNode('Tytuł'));
	tr.appendChild(th);
	var th = document.createElement('th');
	th.appendChild(document.createTextNode('Miejsce, rok'));
	th.appendChild(document.createElement('br'));
	th.appendChild(document.createTextNode('Wydawnictwo'));
	tr.appendChild(th);
	
	return tr;
}

function processBook(book) {
	var tr = document.createElement('tr');
	tr.id = book.attributes['id'].nodeValue;
	tr.onclick = 'changeBook(this)';
	
	var th = document.createElement('td');
	th.appendChild(document.createTextNode(book.attributes['id'].nodeValue.substr(1)));
	th.appendChild(document.createElement('br'));
	if(!book.hasAttribute('status')) {
		th.appendChild(document.createTextNode('Nieznany'));
	}
	else if(book.getAttribute('status') == 'ok') {
		tr.className = 'ok';
		th.appendChild(document.createTextNode('Na miejscu'));
	}
	else
	{
		tr.className = 'ok';
		th.appendChild(document.createTextNode('Przeniesiona'));
	}
	tr.appendChild(th);
	var th = document.createElement('td');
	th.appendChild(document.createTextNode(textValue(book.getElementsByTagName('autor').item(0))));
	th.appendChild(document.createElement('br'));
	th.appendChild(document.createTextNode(textValue(book.getElementsByTagName('tytul').item(0))));
	tr.appendChild(th);
	var th = document.createElement('td');
	th.appendChild(document.createTextNode(textValue(book.getElementsByTagName('miejsce').item(0))+' '+textValue(book.getElementsByTagName('rok').item(0))));
	th.appendChild(document.createElement('br'));
	th.appendChild(document.createTextNode(textValue(book.getElementsByTagName('wydawnictwo').item(0))));
	tr.appendChild(th);
	
	return tr;
}

function changeBook(book) {
	if(book.parentNode == loc) {
		xml.getElementById(book.id).setAttribute('status', 'ok');
		
		book.className = 'ok';
		book.childNodes[0].childNodes[2].data = 'Na miejscu';
		book.scrollIntoView();
	}
	else
	{
		xml.getElementById(loc.id).appendChild(xml.getElementById(book.id));
		xml.getElementById(book.id).setAttribute('status', 'moved');
		
		var book2 = book.cloneNode(true);
		book2.className = 'ok';
		book2.childNodes[0].childNodes[2].data = 'Przeniesiona';
		
		if(document.getElementById('e'+book.id)) {
			document.getElementById('e'+book.id).parentNode.removeChild(document.getElementById('e'+book.id));
		}
		
		book.className = 'err';
		book.id = 'e'+book.id;
		book.childNodes[0].childNodes[2].data = 'Przeniesiona';
		
		loc.appendChild(book2);
		book2.scrollIntoView();
	}
}

function clearInput() {
	document.getElementById('i1').value = document.getElementById('i2').value = document.getElementById('i3').value = '';
	document.getElementById('i1').focus();
}

function processInput() {
	var reg = /^([0-9]{1,8})$/;
	var i1 = document.getElementById('i1').value;
	var i2 = document.getElementById('i2').value;
	var i3 = document.getElementById('i3').value;
	
	if(i2 != '' || i3 != '') {
		if(!document.getElementById('m_'+i1+'_'+i2+'_'+i3)) {
			error('Podane regał/półka/rząd nie istnieją!');
			clearInput();
			return false;
		}
		
		changeLocation(document.getElementById('m_'+i1+'_'+i2+'_'+i3));
		status('Wybrano '+i1+'/'+i2+'/'+i3);
		clearInput();
		return true;
	}
	
	if(reg.test(i1)) {
		i1 = parseInt(i1, 10);
		if(!document.getElementById('k'+i1)) {
			error('Wybrana książka nie istnieje!');
			clearInput();
			return false;
		}
		
		changeBook(document.getElementById('k'+i1));
		status('OK - książka '+i1);
		clearInput();
		return true;
	}
	
	error('Nieznany typ (książka - 8 cyfr; regał - tekst; półka/rząd - liczby)!');
	clearInput();
	return false;
}

function keyEvent(e) {
	if(!e) e = window.event;
	
	if(e.keyCode == 13) {
		processInput();
		clearInput();
		return false;
	}
}

function process() {
	var number = 0;
	var header = makeHeader();
	
	xml = ajax.responseXML;
	if(!(xml instanceof XMLDocument)) {
		fatalError('Pobrany dokument nie jest poprawnym arkuszem XML');
	}
	
	var total = xml.getElementsByTagName('ksiazka').length;
	
	if(!xml.getElementById) {
		fatalError('Przeglądarka nie wspiera XMLDocument.getElementById');
	}
	
	status('Książek do przetworzenia: '+total);
	var miejsca = xml.getElementsByTagName('lokalizacja');
	for(var i=0; i<miejsca.length; i++) {
		var table = document.createElement('table');
		if(loc == false) {
			loc = table;
		}
		table.id = 'm_'+miejsca[i].attributes['regal'].nodeValue+'_'+miejsca[i].attributes['polka'].nodeValue+'_'+miejsca[i].attributes['rzad'].nodeValue;
		table.appendChild(makeCaption(miejsca[i].attributes['regal'].nodeValue, miejsca[i].attributes['polka'].nodeValue, miejsca[i].attributes['rzad'].nodeValue));
		table.appendChild(header.cloneNode(true));
		
		for(var j=0; j<miejsca[i].childNodes.length; j++) {
			if(!miejsca[i].childNodes[j].tagName) continue;
			table.appendChild(processBook(miejsca[i].childNodes[j]));
			
			if((++number % 100) == 0) {
				status('Przetworzono: '+Math.floor(number*100/total)+'% ('+number+' z '+total+')');
			}
		}
		
		document.getElementById('data').appendChild(table);
	}
	
	status('Przetworzono: 100% ('+total+' z '+total+')');
	
	changeLocation(loc);
	
	document.getElementById('input').style.display = 'block';
	
	document.getElementById('i1').onkeydown = document.getElementById('i2').onkeydown = document.getElementById('i3').onkeydown = keyEvent;
	document.getElementById('i4').onclick = processInput;
	document.getElementById('i5').onclick = save;
	document.getElementById('i1').focus();
	
	status('Gotowy do pracy.');
}

function save() {
	document.getElementById('input').style.display = 'none';
	status('Zapisywanie. Proszę czekać...');
	
	ajax.open('POST', 'save.php', true);
	ajax.onreadystatechange = function() {
		if(ajax.readyState == 3) {
			status('Wysyłanie danych...');
		}
		else if(ajax.readyState == 4) {
			if(ajax.status == 200) {
				status('Dane zostały zapisane. '+ajax.status);
			}
			else
			{
				error('Zapis nie powiódł się. Błąd HTTP '+ajax.status);
			}
			
			document.getElementById('input').style.display = 'block';
		}
	};
	ajax.send(xml);
}

function getData() {
	status('Inicjowanie transferu...');
	ajax();
	ajax.open('GET', 'list.xml?time='+((new Date()).getTime())+'&rand='+Math.random(), true);
	ajax.onreadystatechange = function() {
		if(ajax.readyState == 3) {
			status('Pobieranie danych...');
		}
		else if(ajax.readyState == 4) {
			if(ajax.status == 200 || ajax.status == 304) {
				status('Przetwarzanie danych...');
				process();
			}
			else
			{
				fatalError('Kod HTTP '+ajax.status+'. Nie udało się pobrać danych. Spróbuj przeładować stronę.');
			}
		}
	};
	ajax.send();
}

window.onload = getData; 
