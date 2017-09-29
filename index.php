<?php 

	include("config.php"); // Einladen der Config.php welche die Links verknüpft 

	class setLayout // Erstellen der classe setLayout 
		{ 
			private $page;  // Private variable erstellen 
			private $tpl;   // Private variable erstellen 

	public function setDyn($pfad)   // Funktion für das einladen der Dynamischen Dateien (php) aus dem Ordner module
		{ 
			ob_start();                     // Zwischenspeicher öffnen 
			require_once("module/".$pfad);  // Datei anfordern 
			$ausgabe = ob_get_contents();   // Inhalt der Datei auf temp. Variable übergeben
			ob_end_clean();                 // Zwischenspeicher schließen 

			return $ausgabe;                // Rückgabe der temp. Variable mit Inhalt der eingelesenen Datei
		} 
	public function setHTML($pfad)   // Funktion für das einladen der Statischen Dateien (html / tpl) aus dem Ordner template
		{ 
			$ausgabe = file_get_contents("template/".$pfad); // Anfordern des Inhaltes der Datei

			return $ausgabe;                // Rückgabe der temp. Variable mit Inhalt der eingelesenen Datei
		} 

	public function getTpl($pfad, $placeholder) // Funktion für das ersetzen der Platzhalter
		{ 
			if(file_exists("module/".$pfad))   			// Es wird überprüft, ob eine Datei im Modulesordner liegt, wenn ja
				{                                  		// rufen wir die setDyn function auf, welche uns den Inhalt der Datei
					$this->tpl = $this->setDyn($pfad);  // Einliest und auf die private zwischen Variable tpl legt 
				}else{ 
			if(file_exists("template/".$pfad))  			   // Wenn keine Datei mit dem Namen im Modules Ordner gefunden wird, wird im
				{                                   		   // template Ordner danach gesucht
					$this->tpl = $this->setHTML($pfad); 	   // ist dies der Fall, rufen wir die function zum Einlesen der HTML datei auf
				}else{                              		   // wenn garkeine Datei gefunden wird, (z.B. der User cat=12345 eingegeben hat)
					$this->tpl = $this->setHTML("fehler/404.php");     // lesen wir eine Hauptseite ein (diese muss von euch belegt werden,
				}                                          	   // kann auch natürlich setDyn() sein (also eine php))
		} 
	IF(!empty($placeholder))                     		 // hier wird nochmal geschaut, ob der Platzhalter belegt ist oder Leer / Null
		$this->setPageTPL($this->tpl, $placeholder);     // Das hat den Sinn, das Ihr in jeder x-beliebigen Datei eine Seite einbauen 
	ELSE                                         		 // könnt, so z.B. bei nicht angemeldeten Usern den Login
		echo $this->tpl; 
		} 

	private function setPageTPL($tpl, $placeholder)          						   // Funktion für das ersetzen der Platzhalter
		{                                               	 						   // mit preg_replace ersetzen wir unseren Platzhalter und legen nun
			$this->page = preg_replace("/\[\%".$placeholder."\%\]/",$tpl,$this->page); // den Inhalt auf $this->Page 
		} 

	public function setPage($tpl)                            // Funktion um private Variable $page zu befüllen,
		{                           					     // bzw um das Design einzuladen / anzufügen 
			$this->page .= file_get_contents($tpl); 
		} 

	public function showPage()                               //Funktion um die Seite auszugeben.
		{ 
			return $this->page; 
		} 
} 


$display = new setLayout();              // Neue Instanz von setLayout erstellen
$display->setPage("template/design.tpl");// wir lesen das Template auf $page ein 
$display->getTpl("navi.tpl","navi");     // wir ersetzen den Platzhalter "navi" mit dem inhalt der Datei "navi.tpl"
$display->getTpl("kopf.tpl","kopf");
$display->getTpl("fuss.tpl","fuss");

	IF(isset($_GET["cat"])) 		// Hier wird nun der Content ausgelesen, also welche Seite über ?cat= mitgeliefert wurde 
		$content = $_GET["cat"];	// da wir dies z.b. auch über ein Formular machen können, schauen wir ob Post oder GET
	ELSE                    		// gesetzt ist 
		$content = $_POST["cat"]; 
	
	IF(!isset($content))                          // Wenn Beide leer sind, kann hier die Seite angegeben werden, welche erscheinen soll,
		$content = 0;        					  // in diesem falle $cat[0]; 

		$display->getTpl($cat[$content],"content"); // Nun ersetzen wir den Platzhalter [%content%] mit dem Inhalt der Datei $cat[$content]
													// z.b. $cat[0] = "ordner/startseite.htm"
		echo $display->showPage();                  // als letztes noch die Ausgabe der Datei via showPage();
?>