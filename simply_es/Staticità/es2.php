<?php
    class Configurazione {
        public static $opzioni = array(
            'lingua' => 'italiano',
            'tema' => 'scuro',
            'dimensione_font' => '12px'
        );

        public static function getOpzione($key) {
            if (array_key_exists($key, self::$opzioni)) {
                return self::$opzioni[$key];
            }
            return null;
        }
    }

    echo Configurazione::getOpzione('lingua');
    echo Configurazione::getOpzione('tema');
    echo Configurazione::getOpzione('dimensione_font');
    echo Configurazione::getOpzione('colore_sfondo');
?>