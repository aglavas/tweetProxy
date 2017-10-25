TweetProxy
========================

Preduvjeti
--------------

TeetProxy aplikacija koristi FULL TEXT SEARCH. 

FULL TEXT SEARCH je podržan od 5.6 verzije MySQL-a ili kasnije verzije.

Ukoliko koristite bazu podataka koja nije MySQL potrebne će biti dodatne modifikacije.

Instalacija
--------------

```bash
git clone https://github.com/mrgarbage/tweetProxy.git
cd tweetproxy
composer update
```

Konfiguracija
--------------

Potrebno je postaviti podatke za MySQL bazu podataka. Prilikom instalacije
Symfony će tražiti te podatke i kreirati config/parameters.yml file. Ako ne upišete prilikom
instalacije uvijek ih možete dopuniti.

Osim podataka za MySQL potrebno je postaviti podatke za prijavu na Twitter. I to u formatu:


```bash
endroid_twitter:
    consumer_key:
    consumer_secret: 
    access_token:
    access_token_secret:
```

Također pod key Parameters potrebno je dodati:

```bash
parameters:
    pagination_per_page: 20
    tweet_count: 20
```

To su parametri koji govore koliko tweetova treba dohvatiti te koliko ih treba prikazati paginacijom.

Nakon što je ovo sve postavljeno, potrebno je migrirati bazu. To se postiže naredbom:

```bash
php bin/console doctrine:database:create 
php bin/console doctrine:schema:update --force
```

