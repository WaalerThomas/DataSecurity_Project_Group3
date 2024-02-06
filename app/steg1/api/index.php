<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Dokumentasjon</title>
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
<h3>Antagelser</h3>
<p>Antar at vi ikke trenger å håndtere innlogging i API med tanke på at den skal ha "... de samme mulighetene som nettsiden for <b>innlogget</b> student ..."</p>

<h3>Bruksanvisning</h3>
<h4>Oppretting av API nøkkel</h4>
</body>
</html>