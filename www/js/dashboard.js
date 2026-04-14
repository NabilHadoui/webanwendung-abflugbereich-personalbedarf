let dashboardDaten = [];

// Abfrage der JSON-Datei und Laden der Daten
fetch('../json/dashboard_daten.json')
    .then(response => response.json())
    .then(result => {
        dashboardDaten = result;

        // Für jeden Flug einmalig die eingecheckten Passagiere bestimmen
        dashboardDaten.forEach(flug => {
            let minPassagiere = Math.ceil(flug.passagier_anzahl * 0.9); // 90%
            let maxPassagiere = Math.floor(flug.passagier_anzahl * 1.0); // max 100%
            flug.checkinPassagiere = Math.floor(Math.random() * (maxPassagiere - minPassagiere + 1)) + minPassagiere;
        });

        dashboardTabelle(); // Tabelle nach dem Laden der Daten erstellen
    });

// Simulieren und Berechnen der Mitarbeiter
function simulateCheckinAndCalculate() {
    //let aktuelleZeit = new Date(); // aktuelle Zeit

    // Tabellen-Referenz
    let tablebodyFluege_plan = document.getElementById('fluege_plan');
    let tablebodyEngpass = document.getElementById('engpass');

    tablebodyFluege_plan.innerHTML = "";
    tablebodyEngpass.innerHTML = "";

    // Summen initialisieren
    let abflugCount = 0;
    let summeGeplantepassagiere = 0;
    let summeEingechecktePassagiere = 0;
    let summeCheckinMitarbeiter = 0;
    let summeSecurityMitarbeiter = 0;
	// Beamte: Passkontrolle
    let summeBeamte = 0;
    let summeBenoetigteSecurity = 0;
    let summeBenoetigteBeamte = 0;
    let summengpassSecurity = 0;
    let summengpassBeamte = 0;
    let summeSecurityplus = 0;
    let summeBeamteplus = 0;

    const leistungProCheckin = 20;
    const leistungProSecurity = 30;
    const leistungProBeamte = 20;

    dashboardDaten.forEach(flug => {
		let passagiereDifferenz = 0;
		let summeDifferenz = 0;
        let checkinStart = new Date(flug.checkin_start);
        let checkinEnde = new Date(flug.checkin_ende); 
		
		let aktuelleZeit = new Date();
		let abflug = new Date(flug.abflugzeit);      
        // Restzeit vor Abflug
        let restZeitMin = Math.floor((abflug - aktuelleZeit) / 1000 / 60);
		
        // Nur Flüge innerhalb der nächsten 3 Stunden
        if (restZeitMin > 180 || restZeitMin <= 0) {
            if (restZeitMin <= 0) {
                abflugCount++;
            }
            return;
        }
        // eingescheckte Passagiere
        let checkinPassagiere = flug.checkinPassagiere;

        // Statusberechnung
        let checkinStatus = "";
        let boardingStatus = "";
        let flugStatus = "";
        let statusSecurity = "";

        //Restzeit vor Abflug beträgt mehr als 120 Minuten
        /*
          Chekin-beginn: 2 Stunden ( = 120 Minuten ) vor Abflug,
          Checkin-ende : 1 Stunde ( = 60 Minuten) vor Abflug,
          Bording beginnt 45 Minuten und endet 15 Minuten vor Abflug
        */
        if (restZeitMin > 120) {
            checkinStatus = "beginnt in " + restZeitMin + " Minuten";
            flugStatus = "warte auf Checkin";
        } else if (restZeitMin <= 120 && restZeitMin >= 60) { // 
            checkinStatus = "offen noch " + (restZeitMin - 60) + " Minuten";
            statusSecurity = "offen";
            boardingStatus = "Boarding öffnet in " + (restZeitMin - 45) + " Minuten";
            flugStatus = "Checkin";
        } else if (restZeitMin <= 60 && restZeitMin > 45) {
            checkinStatus = "Check-in geschlossen, zum Boarding";
            statusSecurity = "offen";
            flugStatus = "Security Check noch: " + (restZeitMin - 30) + " Minuten";
        } else if (restZeitMin <= 45 && restZeitMin > 15) {
            boardingStatus = "Boarding geöffnet";
            checkinStatus = "Check-in geschlossen, zum Boarding";
            statusSecurity = "Security Check noch " + (restZeitMin - 20) + " Minuten ";
            flugStatus = "Boarding";
        } else if (restZeitMin <= 15) {
            checkinStatus = "Check-in geschlossen";
            boardingStatus = "Boarding geschlossen";
            statusSecurity = "Security Check geschlossen";
            flugStatus = "Abflug in " + restZeitMin + " Minuten";
        }
	
        // Berechnung Mitarbeiter 
        // geplante Daten
        let geplanteCheckin = Math.ceil(flug.passagier_anzahl / leistungProCheckin);
        let geplanteSecurity = Math.ceil(flug.passagier_anzahl / leistungProSecurity);
        let geplanteBeamte = Math.ceil(flug.passagier_anzahl / leistungProBeamte);

        // dynamische Daten
        let benoetigteSecurity = Math.ceil(checkinPassagiere / leistungProSecurity);
        let benoetigteBeamte = Math.ceil(checkinPassagiere / leistungProBeamte);

        // Summen berechnen
        summeCheckinMitarbeiter += geplanteCheckin;
        summeSecurityMitarbeiter += geplanteSecurity;
        summeBeamte += geplanteBeamte;
        summeBenoetigteSecurity += benoetigteSecurity;
        summeBenoetigteBeamte += benoetigteBeamte;

        /*Nur Flüge innerhalb der nächsten Stunde werden für Engpässe berücksichtigt,
          Checkin ist schon geschlossen
        */
        if (restZeitMin < 60 && restZeitMin > 0) {
            passagiereDifferenz = checkinPassagiere - Number(flug.passagier_anzahl);
            summeEingechecktePassagiere += checkinPassagiere;
            summeGeplantepassagiere += Number(flug.passagier_anzahl);
            summeDifferenz = summeEingechecktePassagiere - summeGeplantepassagiere;

            // anzeigen im Browser
            document.getElementById('anzahl_passagiere').innerText = summeGeplantepassagiere;
            document.getElementById('checkincount').innerText = summeEingechecktePassagiere;

            //  Security: Engpässe und Überkapazitäten prüfen
            if (geplanteSecurity < benoetigteSecurity) {
                summengpassSecurity += (benoetigteSecurity - geplanteSecurity);
                summeSecurityplus = 0;
            } else if (geplanteSecurity > benoetigteSecurity) {
                summeSecurityplus += (geplanteSecurity - benoetigteSecurity);
                summengpassSecurity = 0;
            }
             // Beamte: Engpässe und Überkapazitäten prüfen
            if (geplanteBeamte < benoetigteBeamte) {
                summengpassBeamte += (benoetigteBeamte - geplanteBeamte);
                summeBeamteplus = 0;
            } else if (geplanteBeamte > benoetigteBeamte) {
                summeBeamteplus += (geplanteBeamte - benoetigteBeamte);
                summengpassBeamte = 0;
            }
        }

        // Abgeflogene Passagiere abziehen
        if (restZeitMin <= 0) {
            summeEingechecktePassagiere = Math.max(summeEingechecktePassagiere - checkinPassagiere, 0);
            summeGeplantepassagiere = Math.max(summeGeplantepassagiere - Number(flug.passagier_anzahl), 0);
        }

        // Summen anzeigen: vergleich zwischen Engpässe und Überkapazitäten
        if (summeSecurityplus > summengpassSecurity) {
            summeSecurityplus -= summengpassSecurity;
            summengpassSecurity = 0;
        } else if (summeSecurityplus < summengpassSecurity) {
            summengpassSecurity -= summeSecurityplus;
            summeSecurityplus = 0;
        }
        // Anzeigen in Card im Browser 
        document.getElementById('abflugcount').innerText = abflugCount;
        document.getElementById('summesecurity').innerText = summeSecurityplus;
        document.getElementById('summebeamte').innerText = summeBeamteplus;
        document.getElementById('engpass-security-value').innerText = summengpassSecurity;
        document.getElementById('engpass-beamte-value').innerText = summengpassBeamte;

        // Tabelle Info Flüge
        tablebodyFluege_plan.innerHTML += `
            <tr>
                <td>${flug.flug_nr}</td>
                <td>${flug.airline_name}</td>
                <td>${flug.ziel}</td>
                <td>${flug.abflugzeit}</td>
                <td>${flug.schalter_nr}</td>
                <td>${flug.security_name}</td>
                <td>${checkinStatus}</td>
                <td>${statusSecurity}</td>
                <td>${boardingStatus}</td>
                <td>${flugStatus}</td>
            </tr>
        `;

        // Tabelle Engpässe, für Flüge nach dem checkin ende
        if (restZeitMin < 60 && restZeitMin > 0) {
            tablebodyEngpass.innerHTML += `
                <tr>
                    <td>${flug.flug_nr}</td>
                    <td>${flug.passagier_anzahl}</td>
                    <td>${checkinPassagiere}</td>
                    <td>${passagiereDifferenz}</td>
                    <td>${geplanteCheckin}</td>
                    <td>${geplanteSecurity}</td>
                    <td>${benoetigteSecurity}</td>
                    <td>${geplanteBeamte}</td>
                    <td>${benoetigteBeamte}</td>
                    <td>${summeGeplantepassagiere}</td>
                    <td>${summeEingechecktePassagiere}</td>                
                    <td>${summeDifferenz}</td>
                </tr>
            `;
        }
    });
}

// Tabelle erstellen
function dashboardTabelle() {
    simulateCheckinAndCalculate(); 
	// alle 10 Sekunden aktualisieren
    setInterval(simulateCheckinAndCalculate, 10000); 
}