# Dynamic Mail
Versendet eine E-Mail mit einem dynamischen Text.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Versenden einer E-Mail mit einem festen/dynamischen Text
* Dynamik durch das Ersetzen von Platzhaltern durch die Variablenwerte

### 2. Voraussetzungen

- IP-Symcon ab Version 7.1

### 3. Software-Installation

* Über den Module Store das 'Dynamic Mail'-Modul installieren.

### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'Dynamic Mail'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

Name     | Beschreibung
-------- | ------------------
SMTP Instanz  | SMTP Instanz welche vollständig eingerichtet ist
Dynamischer Betreff  | Texteingabe welche gesetzt werden kann.
Dynamischer Text | Mehrzeilige Texteingabe.

Der Betreff wie auch der Text bekommt eine dynamik durchs einsetzen von Variablenwerte. Diese können innerhalb des Textes durch die in geschweifte Klammern gesetzte VariablenID gesetzt werden. Ist die ID zu kurz oder keine Variable, so bleiben die Klammern mit der Nummer bestehen.

__Aktionsbereich__:

Name | Beschreibung 
---- |  ------ 
Versenden Testen | Verschickt eine E-Mail mit dem gesetzten Betreff und Text
Vorschau E-Mail mit Betreff  | Eine Vorschau mit gesetzten Variablewerte
Platzhalter Variablen | Tabelle mit den gegebenen Platzhaltern

Tabelle: 
In der Tabelle sind die gefundenen Platzhalter mit ihren aktuellen Wert und ihrem Status aufgelistet. Ist ein Platzhalter nicht verfügbar ist die eingegebene Nummer keine ID. Ist ein Platzhalter ungültig, so ist die gefundene ID keine Variable. 

### 5. Statusvariablen und Profile

Es werden keine Variablen oder Profile angelegt. 

### 6. Visulaisierung

Die Instanz hat keine Funktionalität in der Visualisierung. 

### 7. PHP-Befehlsreferenz

`boolean DM_SendMail(integer $InstanzID);`
Nutzt den Betreff und Text um eine E-Mail zu versenden. 

Beispiel:
`DM_SendMail(12345);`