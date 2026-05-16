<?php

// Carica tutte le dipendenze installate con Composer (MQTT + MongoDB client, ecc.)
require __DIR__ . '/vendor/autoload.php';

// Include connessione MongoDB già configurata nel file db.php
require_once __DIR__ . '/includes/db.php';

// Import delle classi della libreria MQTT
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

// -------------- CONFIGURAZIONE MQTT --------------
// Indirizzo del broker MQTT (qui locale)
$server = '127.0.0.1';

// Porta standard MQTT (1883)
$port = 1883;

// ID univoco del client PHP (deve essere diverso dagli altri client)
$clientId = 'php-mqtt-subscriber';

// ------- CREAZIONE CLIENT MQTT -------
// Istanzia il client MQTT con server, porta e clientId
$mqtt = new MqttClient($server, $port, $clientId);

// Configurazione connessione (default settings)
$connectionSettings = new ConnectionSettings();

// Connessione al broker MQTT
// true = clean session (non mantiene stati precedenti)
$mqtt->connect($connectionSettings, true);

echo "Connesso a MQTT\n";

// -------------- CONFIGURAZIONE MONGODB --------------
// Collezione MongoDB dove salverai i dati sensori
$collection = $db->sensori;

echo "Connesso a MongoDB Atlas\n";

// -------------- FILE DI SUPPORTO LOCALI --------------
// File che memorizza il timestamp dell’ultimo salvataggio su MongoDB
$lastSaveFile = __DIR__ . '/last_save.txt';

// File che memorizza l’ultimo valore ricevuto (per confronto cambiamenti)
$lastDataFile = __DIR__ . '/last_data.json';

// -------------- SOTTOSCRIZIONE AL TOPIC MQTT --------------
// Ci si sottoscrive al topic dove l’ESP pubblica i dati sensore
$mqtt->subscribe(
    'astrodesk/sensori',

    // CALLBACK: viene eseguita ogni volta che arriva un messaggio MQTT
    function ($topic, $message) use ($collection, $lastSaveFile, $lastDataFile) {

        // Stampa il messaggio grezzo ricevuto dal broker
        echo "Messaggio ricevuto:\n";
        echo $message . "\n";

        // Decodifica: converte il JSON MQTT in array PHP
        $data = json_decode($message, true);

        // Se il JSON è invalido interrompe la funzione
        if (!$data) {
            echo "JSON non valido\n";
            return;
        }

        // Estrazione dei dati provenienti dall'ESP
        $temp = $data['temperatura'];
        $hum = $data['umidita'];

        // Timestamp attuale (server PHP)
        $now = time(); // serve per il controllo del rate limit (60s) e per il timestamp MongoDB
    
        // Lettura del timestamp dell’ultimo salvataggio su MongoDB (per controllo rate limit)
        if (file_exists($lastSaveFile)) {

            // Il file esiste → leggo il contenuto
            $fileContent = file_get_contents($lastSaveFile);

            // Converto il valore in intero (timestamp)
            $lastSave = (int) $fileContent;

        } else {

            // Il file non esiste → imposto valore di default
            $lastSave = 0;
        }

        // ======================
// LETTURA ULTIMI DATI SALVATI
// ======================
// Questo file serve per confrontare i nuovi valori con i precedenti
// ed evitare salvataggi inutili su MongoDB
    
        $lastData = [
            'temperatura' => null,
            'umidita' => null
        ];

        if (file_exists($lastDataFile)) {

            $fileContent = file_get_contents($lastDataFile);

            if ($fileContent !== false) {

                $decoded = json_decode($fileContent, true);

                // Controllo che il JSON sia valido e sia un array
                if (is_array($decoded)) {
                    $lastData = $decoded;
                }
            }
        }

        // Controllo se i valori sono cambiati abbastanza rispetto all’ultimo salvataggio
        // Default: nessuna variazione rilevata
        $tempChanged = false;
        $humChanged = false;

        // Se è il primo avvio, forzo il salvataggio
        if ($lastData['temperatura'] === null) {
            $tempChanged = true;
        } else {
            $tempChanged = abs($temp - $lastData['temperatura']) >= 0.2;
        }

        // Se è il primo avvio, forzo il salvataggio
        if ($lastData['umidita'] === null) {
            $humChanged = true;
        } else {
            $humChanged = abs($hum - $lastData['umidita']) >= 1;
        }

        // Controllo se è passato almeno 1 minuto dall’ultimo salvataggio
        $timePassed = ($now - $lastSave) >= 60;

        // LOGICA DI SALVATAGGIO
        // Il dato viene salvato SOLO se:
        // - è passato almeno 1 minuto
        //   OPPURE
        // - i valori sono cambiati abbastanza
        if ($timePassed || $tempChanged || $humChanged) {

            // Creazione del documento da inserire in MongoDB
            $document = [
                'temperatura' => $temp,
                'umidita' => $hum,
                'timestamp' => new MongoDB\BSON\UTCDateTime()
            ];

            // Inserimento nel database
            $result = $collection->insertOne($document);

            // Output debug con ID documento MongoDB
            echo "Inserito documento ID: " . $result->getInsertedId() . "\n";

            // Aggiornamento della cache locale (file) con il timestamp dell’ultimo salvataggio
            // Aggiorna timestamp ultimo salvataggio
            file_put_contents($lastSaveFile, $now);

            // Aggiorna ultimi valori ricevuti
            file_put_contents($lastDataFile, json_encode([
                'temperatura' => $temp,
                'umidita' => $hum
            ]));

        } else {

            // Caso in cui i dati non vengono salvati
            echo "Skip save (troppo frequente / dati invariati)\n";
        }

    },

    0 // QoS (Quality of Service): 0 = consegna “best effort”
);

// Output di conferma che il subscriber è attivo e in ascolto
echo "In ascolto sul topic astrodesk/sensori...\n";

// Loop del client MQTT: mantiene la connessione attiva e gestisce i messaggi in arrivo
// true = loop infinito (non termina mai, a meno di errori o interruzioni
$mqtt->loop(true);

// Se il loop viene interrotto (es. con Ctrl+C), disconnette il client MQTT
$mqtt->disconnect();