<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Dokumentasjon</title>
    <style>
        .endpoint {
            margin-top: 20px;
            border: 1px solid black;
        }
        .method-get {
            color: green;
        }
        .method-post {
            color: #8B8000;
        }
        .adress {
            font-weight: bold;
        }
    </style>
</head>
<body>
    
<h1>API Dokumentasjon</h1>

<?php
/**
 * Følger denne for API auth, ting: https://stackoverflow.com/questions/36514344/how-to-make-use-of-session-in-rest-api
 * 
 * Det som trengs å bli lagt til i API er:
 * - De samme mulighetene som nettsiden for innlogget student, untatt glemt passord og passordbytte.
 * X Se en liste over alle emnene
 * X Se informasjon om et valgt emne
 * X Se en liste over alle meldinger til et valgt emne
 * X Se alle kommentarer til en melding
 * - Legge inn en melding på et valgt emne
 * 
 * 
 * API
 * 
 * Courses:
 * - GET /course
 *  - Get a list of all the courses available
 * - GET /course?name={name}
 *  - Get info about a single course
 * 
 * Messages:
 * - GET /message?course={course_name}
 *  - Get a list of all the messages in a course
 * - POST /message/index.php
 *  - Add a message to course
 *  - form-data
 *      - message = Message to send
 *      - course = Name of the course
 * 
 * Comments:
 * - GET /message/comment?msg_id={id}
 *  - Get a list of all the comments for a message
 */
?>
<h2>Antagelser</h2>
<p>Antar at vi ikke trenger å håndtere innlogging i API med tanke på at den skal ha "... de samme mulighetene som nettsiden for <b>innlogget</b> student ..."</p>

<h2>Bruksanvisning</h2>
Anbefaler å bruke Postman eller noe lignende for utføre forespørslene.
<h3>Oppretting av API nøkkel</h3>
For å kunne bruke API'et og sende forespørseler så trenger man en sesjon id som er knyttet til én bruker.
<ol>
    <li>Generer API og Auth nøkkler ved å bruke /accessToken</li>
    <li>Hash API og Auth nøkklene sammen med md5. For at det skal bli riktig så skal Auth nøkkelen legges til back API nøkkelen. For eksempel
        <ul>
            <li>API nøkkel: <b>c3b93f66ea2df47b9f5779cdf99929c111bf2b09d0</b></li>
            <li>Auth nøkkel: <b>ee7bad332b90068380cceb590501367e5e69e2a6ed</b></li>
            <li>md5(<b>c3b93f66ea2df47b9f5779cdf99929c111bf2b09d0ee7bad332b90068380cceb590501367e5e69e2a6ed</b>)</li>
            <li>For å hashe så kan denne nettsiden brukes: <a href="https://www.md5hashgenerator.com/">https://www.md5hashgenerator.com/</a></li>
        </ul>
    </li>
    <li>Login og knytt API nøkkel sammen med en bruker ved å bruke /accessToken/login.php
        <ul>
            <li>!!! Husk å legge til API nøkkel i header "api_key", og legge til "hash" i form-data.</li>
        </ul>
    </li>
    <li>Du skal nå ha fått tilbake en sesjon id. Sesjon id'en skal legges i header på hver forespørsel for å identifisere.</li>
</ol>

<h2>Endpoints</h2>
Alle endpoints starter med "ip_adresse"/steg1/api/.
<div class="endpoint">
    <div class="method-get">GET</div>
    <div class="adress">/accessToken</div>
    <div class="desc">Genererer API og Auth nøkkler og returnerer nøkklene. Nøkklene utløper etter 14 dager</div>
</div>

<div class="endpoint">
    <div class="method-post">POST</div>
    <div class="adress">/accessToken/login.php</div>
    <div class="desc">Logger inn og oppretter en sesjon id som blir knyttet til brukeren. Returnerer sesjon id.</div>
    <div>Header
        <ul>
            <li>api_key = {din genererte api nøkkel}</li>
        </ul>
    </div>
    <div>form-data 
        <ul>
            <li>email = {bruker-konto sin epost}</li>
            <li>password = {bruker-konto sitt passord}</li>
            <li>hash = {den genererte md5 hashen fra API og Auth nøkkel}</li>
        </ul>
    </div>
</div>

<div class="endpoint">
    <div class="method-get">GET</div>
    <div class="adress">/course</div>
    <div class="desc">Returnerer en liste over alle emnene som er opprettet</div>
    <div>Header
        <ul>
            <li>session_id = {din sesjon id}</li>
        </ul>
    </div>
</div>

<div class="endpoint">
    <div class="method-get">GET</div>
    <div class="adress">/course?name={course_name}</div>
    <div class="desc">Returnerer informasjon om én valgt emne. {course_name} er navnet på emnet.</div>
    <div>Header
        <ul>
            <li>session_id = {din sesjon id}</li>
        </ul>
    </div>
</div>

<div class="endpoint">
    <div class="method-get">GET</div>
    <div class="adress">/message?course={course_name}</div>
    <div class="desc">Returnerer en liste over alle spørsmålene til et valgt emne. {course_name} er navnet på emnet.</div>
    <div>Header
        <ul>
            <li>session_id = {din sesjon id}</li>
        </ul>
    </div>
</div>

<div class="endpoint">
    <div class="method-get">GET</div>
    <div class="adress">/message/comment?msg_id={message_id}</div>
    <div class="desc">Returnerer en liste over alle kommentarene på et valgt spørsmål. {message_id} er id'en til spørsmålet.</div>
    <div>Header
        <ul>
            <li>session_id = {din sesjon id}</li>
        </ul>
    </div>
</div>

<div class="endpoint">
    <div class="method-post">POST</div>
    <div class="adress">/message/index.php</div>
    <div class="desc">Oppretter et spørsmål til et valgt emne.</div>
    <div>Header
        <ul>
            <li>session_id = {din sesjon id}</li>
        </ul>
    </div>
    <div>form-data 
        <ul>
            <li>message = {Ditt spørsmål}</li>
            <li>course = {navnet på emnet spørmålet legges på}</li>
        </ul>
    </div>
</div>

</body>
</html>