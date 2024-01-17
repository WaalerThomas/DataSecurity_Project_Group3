# DataSecurity_Project_Group3
Brukte følgende tutorial til å sette dette opp: https://doc4dev.com/en/create-a-web-site-php-apache-mysql-in-5-minutes-with-docker/

Andre ressurser som kan være nyttig:
- https://code.tutsplus.com/how-to-build-a-simple-rest-api-in-php--cms-37000t (Brukte denne til å legge til API greiene)

## Utvikling
### Krav
- Docker / Docker Desktop

### Oppsett
1. I rot-mappen til prosjektet, der docker-compose.yml befinner seg, opprett to filer; *db_root_password.txt*, *db_someuser_password.txt*. I de respektive filene legges inn passordet.
2. Kjør `docker-compose up -d` i kommandovindu i rot-mappen til prosjektet. Dette vil lage og starte opp mysql og apache serveren.
3. Naviger til mappen /app/. Lag en kopi av *config.php.example* og fjern *.example* fra filnavnet.
4. Rediger *config.php* og legg til respektiv informasjon på variablene.
5. I nettleseren, åpne opp *localhost:80*.

### Koble til server
Dersom du ikke bruker docker desktop så kan du kjøre følgende til å koble til kommandovinduet til serverne. Merk at det er oppretter to docker containere, én for mysql og én for apache.
1. Finn liste over navnet til docker container ved å skrive inn `docker ps -a` i kommandovinduet.
2. Koble til ved å skrive inn `docker exec -ti <docker_container_name> /bin/bash` i kommandovinduet, hvor *<docker_container_name> er enten; *datasecurity_project_group3_mysql_1* eller *datasecurity_project_group3_php-apache_1*.
