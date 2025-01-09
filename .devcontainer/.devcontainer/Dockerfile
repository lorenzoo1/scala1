# Usa un'immagine di PHP predefinita
FROM php:8.0-cli

# Installa Apache (se desideri usare PHP con Apache)
RUN apt-get update && apt-get install -y apache2 && apt-get clean

# Copia il codice PHP all'interno del contenitore
COPY . /var/www/html/

# Imposta la cartella di lavoro
WORKDIR /var/www/html/

# Apri la porta 80
EXPOSE 80

# Avvia Apache quando il contenitore è in esecuzione
CMD ["apache2-foreground"]