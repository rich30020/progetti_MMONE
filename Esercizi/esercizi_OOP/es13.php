<?php
class Logger {
    public static $logCount = 0;

    public static function log($message) {
        echo $message . "</br>";
        self::$logCount++;
    }
}
Logger::log("Messaggio di log 1");
Logger::log("Messaggio di log 2");
Logger::log("Messaggio di log 3");

echo "Messaggi di log totali: " . Logger::$logCount . "</br>";

?>
