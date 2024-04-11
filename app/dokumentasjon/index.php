<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasjon</title>
</head>
<body>

<h1>Dokumentasjon</h1>
<h2>Medlemmer</h2>
<p>Jonas Emil Høgseth - jonaseh@hiof.no</p>
<p>Thomas Kinn - thomaski@hiof.no</p>
<p>Tobias William Sommervold - tobiasws@hiof.no</p>
<p>Thomas Waaler - thomaswa@hiof.no</p>
<p>Petter Viktor Åmot - petterva@hiof.no</p>

<h2>Steg 1</h2>
<h3>Hva har blitt gjort</h3>
<p>
    Under utviklingen har vi benyttet oss av følgende verktøy og tjenester:
    <ul>
        <li><b>GitHub</b> - For versjonskontrollering og samarbeid</li>
        <li><b>Docker</b> - For at vi alle skal ha likt arbeidsmiljø som kjører på linux, sånn som den virtuelle serveren.</li>
        <li><b>MySQL Workbench</b> - Design av database</li>
    </ul>
</p>
<p>
    <!-- Skriv om hva som er laget og hva vi har gjort, men ikke beskriv sikkerhetsløsningen, kjente hull osv -->
    Nettsiden er laget slik at index filen holder på alt relatert til meldingssystemet og layoutet endrer seg basert på login status, i tillegg er det redirects for login og registrering. 
    Når man logger inn blir man tildelt en token og sendt tilbake til index siden. 
    Under login siden ligger det også en lenke "glemt passord" som vil redirecte brukeren til glemtPassord siden. På denne siden kan man fylle inn e-post adressen sin for å motta en email (i form av en stub) 
    som inneholder en lenke med en login-status token. På registrering siden kan man velge mellom student og foreleser som da har forskjellige nødvendige inputs.
    Når man er logget inn vil index siden ta form etter login status hvor foreleser bare ser sitt eget fag, student har en drop down meny for 
    å velge fag å framvise, og gjest (default, no-login) har muligheten til å fylle pin og emnekode for å framvise et gitt fag. Når man er logget in vil knappen for login endres til 
    "bytt passord", her vil man kunne endre passord når man allerede har en login status.
</p>

<h2>Steg 2</h2>
<p>DISCLAMER: Vi har utført mesteparten av oppgaven på engelsk. Så diagrammene være kommer til å være på engelsk.</p>
<h3>Risk Management Framework</h3>
<p>Vi startet med å lage en Risk-Matrix. Se bort ifra teksten i de fargede rutene.</p>
<img src="RMF_matrix.png" alt="img_RMF_matrix">

<p>Deretter skrev vi en liten liste over de delene vi valgte å fokusere på.</p>
<img src="" alt="img_RMF_list">

<p>Etter dette diskuterte vi sammen hvor å plassere de forskjellige delene utifra sannsynlighet og konsekvense. Plasseringene vi endte opp med kan bli sett i RMF-matrix bildet over.</p>


<h3>Abuse Cases og Sikkerhetskrav</h3>
<p>Vi lagde et diagram over noen abuse cases. Noen av disse er tatt inspirasjon fra rapportene fra "hacking" runden.</p>
<img src="abuse_cases.png" alt="img_abuse_case">

<p>Sikkerhetskrav</p>
<ol>
    <li>Systemet skal ha "noreferrer" relasjon på alle lenker.</li>
    <li>Systemet skal spesifisere XSS-beskyttelse i header.</li>
    <li>Systemet skal validere input fra brukeren for å forhindre XSS/SQL-injeksjon/Javascript.</li>
    <li>Systemet skal sette cookies til HTTPOnly og Secure.</li>
    <li>Systemet skal bare tillate 10 åpne tilkoblinger fra samme IP.</li>
    <li>Systemet skal begrense mengden forespørsler for én bruker til 5 hvert minutt.</li>
    <li>Systemet skal ikke tillate bruk av lokale konfigurasjonsfiler for Apache.</li>
    <li>Systemet skal bare tillate GET, POST, og HEAD forespørselstyper.</li>
    <li>Systemet skal bare lagre hashet passord i databasen.</li>
    <li>Systemet skal bruke 250 tegn i session-ID.</li>
    <li>Systemet skal bruke salt og pepper på passord.</li>
</ol>

<h3>Code Review</h3>
<p>Oversikt over svakheter funnet med ZAP fra Steg 1.</p>
<img src="datasec_zap_før.png" alt="img_datasec_zap_før">
<p>Oversikt over svakheter funnet med ZAP etter all implementasjonen på Steg 2.</p>
<img src="datasec_zap_etter.png" alt="img_datasec_zap_etter">
<p>Vi kan se her at konfigurasjonene og implementasjonene har hjulpet med å gjøre nettsiden mer sikker. Men dette vil ikke si at nettsiden er 100% sikker for den grunn.</p>

<h3>Risk-based Security Test</h3>
<p>Sikkerhetskravene vi har oppfylt er; 1, 2, 3, 4, 7, 8, 9, 10, 11. Det er vanskelig å kjøre tester for å vise at det er oppfylt, her må man heller sjekke konfigurasjonsfiler.</p>
<p>Krav 9 kan vi se er oppfylt ved å sjekke tabellen passordene er lagret:</p>
<img src="database_password_hashed.png" alt="img_database_password_hashed">

<p>Krav 11 må vi se på PHP kode for å sjekke at kravet er oppfylt. Vi bruker password_hash() funksjonen i PHP, som vil si at den bruker allerede salt. Deretter tar vi å legger på pepper på passordet og hasher dette sammen, som vist på bildet under.</p>
<img src="password_salt_pepper.png" alt="password_salt_pepper">

<h3>Endringer gjort fra Steg 1</h3>
<ul>
    <li>Konfigurerte Apache på globalt nivå. Fulgte presentasjon om Webservere. Konfigurasjoner satt:
        <ul>
            <li>Deaktivert bruken av lokale konfig-filer.</li>
            <li>Skrudde av indeksering av filer.</li>
            <li>Nekter all adgang til konfigurasjonsfiler i inc/ mappen.</li>
            <li>Fikset hotlinking/leeching.</li>
            <li>Cookies er satt til HTTPOnly og Secure.</li>
            <li>Og mer, sjekk <i>datasec.conf</i> for resten av konfigurasjonen.</li>
        </ul>
    </li>

    <br>
    <li>Konfigurerte PHP på globalt nivå.
        <ul>
            <li>Satt expose_php til Off.</li>
            <li>Endret navnet til PHP session cookie til monsterMunchMunch.</li>
            <li>Cookie lifetime er satt til 4 timer.</li>
            <li>Lengden til session ID'en er satt til 250.</li>
            <li>Og mer, sjekk <i>php.ini</i> for resten av konfigurasjonen.</li>
        </ul>
    </li>

    <br>
    <li>Endret på rettigheter til alle konfigurasjon- og log-filer sånn at bare root og webserverbruker har tilgang.
        <ul>
            <li>På logfilene til applikasjonen så har webserverbrukeren bare rettigheter til å skrive til filene, mens root har tilgang til å lese og skrive.</li>
            <li>For mer detaljer og hvilke rettigheter som blir satt, sjekk filene <i>permissions.sh</i> under <i>build/mysql</i> og <i>build/php</i></li>
        </ul>
    </li>

    <br>
    <li>Lagt til anti-CSRF til alle Forms. Her brukte vi ikke noen biblioteker og heller lagde det selv, så implementasjonen er veldig rudimentær og kan helt sikkert komme seg rundt. Det ble gjort slik bare for å få en bedre forståelse for hva anti-CSRF tokens er og hvordan det fungerer.</li>

    <br>
    <li>Lagde en egen bruker i databasen som applikasjonen bruker som har bare rettigheter til det den trenger og ikke noe mer.</li>

    <br>
    <li>Ikke lenger mulig å opprette en foreleser uten å spesifisere profilbilde.</li>

    <br>
    <li>"Glemt Passord" siden forteller ikke lenger om en epost er registrert i systemet eller ikke.</li>

    <br>
    <li>Saniterer og validerer bruker input før det prosesseres og sendes videre i systemet. Dette er gjort på nesten all bruker input.
        <ul>
            <li>All html text man legger til i meldinger blir sanitert og vil bare vise html i klartekst.</li>
        </ul>
    </li>

    <br>
    <li>Lagt til MonoLog til logging av hendelser.
        <ul>
            <li>Bruker RotatingFileHandler som roterer på log filer hver måned.</li>
            <li>Hver log entry har med info om hvilken client IP som serveren prater med. Dette for å gjøre det enklere å spore handlinger fra én IP.</li>
            <li>Det er tre forskjellige log filer; validation, system, og api. Validation inneholder logging av alle hendelser som omhandler validering av data. API inneholder logging fra API'et. System inneholder all logging som ikke går innom de to andre.</li>
        </ul>
    </li>

    <br>
    <li>Lagde en maks lengde på meldinger man kan sende inn. PHP sender ikke lenger en fatal error tilbake til brukeren, men heller en egen error melding som sier at meldingen er for lang.</li>

    <br>
    <li>Implementerte limit på login forsøk. Dersom man har feilet innlogging 3 ganger så blir man utestengt, på IP, i 10 minutter.
        <ul>
            <li>Alle login forsøkene er logget i databasen. når en bruker logger inn på vellykket måte vil serveren slette gamle login forsøk fra databasen sånn at den ikke fyller opp disken.</li>
        </ul>
    </li>
</ul>

<h3>Det vi ikke rakk/prioriterte å gjøre</h3>
<ul>
    <li>Legge til GrayLog. Så man må manuelt lese logfiler istedenfor.</li>
    <li>Lage forskjellige brukere i databasen basert på "hvem man er" i applikasjonen, altså guest, student, lecturer, med forskjellige rettigheter for hva brukerne har tilgang til å gjøre.</li>
    <li>Vi logger ikke når kritiske handlinger går gjennom fint, bare når noen prøver seg på noe ondsinnet.</li>
    <li>Har ikke egen konfigurasjon vi kan svitsje over til når det er detektert ondsinnet bruk på nettsiden.</li>
    <li>Logger ikke når en bruker får statuskoden 400, 500 osv. Så vi har ingen oversikt over dette.</li>
    <li>Lage en timeout når en bruker sender for mange requests over kort periode. Dette for å stoppe oppretting av 10.000 meldinger på få minutter.</li>
    <li>Begrense antall koblinger fra én IP-adresse. Dette for å stoppe SlowLoris angrep.</li>
</ul>

</body>
</html>