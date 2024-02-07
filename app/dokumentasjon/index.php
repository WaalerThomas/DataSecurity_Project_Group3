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
<p>Thomas Kinn - thomaki@hiof.no</p>
<p>Tobias William Sommervold - tobiasws@hiof.no</p>
<p>Thomas Waaler - thomaswa@hiof.no</p>
<p>Petter Viktor Åmot - petterva@hiof.no</p>

<h2>Hva har blitt gjort</h2>
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

</body>
</html>