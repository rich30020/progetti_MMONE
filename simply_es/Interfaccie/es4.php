<?php
    interface Logging {
        public function log($messaggio);
    }

    class FileLogger implements Logging {
        public function log($messaggio) {
            $file = fopen("log.txt", "a");
            fwrite($file, $messaggio . "\n");
            fclose($file);
        }
    }
    $logger = new FileLogger();
    $logger->log("Ciao questo è il file log");
?>