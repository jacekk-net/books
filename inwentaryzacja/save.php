<?php
function errorHandler($errno, $errstr, $errfile, $errline) {
	header('HTTP/1.1 500 Internal Server Error');
}

set_error_handler('errorHandler');

file_put_contents('list.xml', strtr(
	file_get_contents('php://input'),
	array( '<!DOCTYPE inwentaryzacja>' => '<!DOCTYPE inwentaryzacja [
<!ENTITY % quot "&#34;">
<!ENTITY % amp "&#38;">
<!ENTITY % lt "&#60;">
<!ENTITY % gt "&#62;">
<!ELEMENT inwentaryzacja (lokalizacja)*>
<!ELEMENT lokalizacja (ksiazka)*>
<!ATTLIST lokalizacja
	id	ID	#IMPLIED
	regal	CDATA	#IMPLIED
	polka	CDATA	#IMPLIED
	rzad	CDATA	#IMPLIED>
<!ELEMENT ksiazka (tytul | autor | miejsce | rok | wydawnictwo)*>
<!ATTLIST ksiazka
	id	ID	#IMPLIED
	status	CDATA	#IMPLIED>
<!ELEMENT tytul (#PCDATA)>
<!ELEMENT autor (#PCDATA)>
<!ELEMENT miejsce (#PCDATA)>
<!ELEMENT rok (#PCDATA)>
<!ELEMENT wydawnictwo (#PCDATA)>
]>')
));
?>