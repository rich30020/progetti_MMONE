<?php
    //Scrivi una classe PHP chiamata 'File' con proprietà come 'name' e 'size'. Implementa un metodo statico per calcolare la dimensione totale di più file.

    class File {
        public $name;
        public $size;




        public function __construct($name = "text", $size = 0) {
            $this->name = $name;
            $this->size = $size;
        }


        public static function CalcolateTotalsizeFile ($files) {
            $totalSize = 0;
  
            foreach ($files as $file) {
                $totalSize += $file->size;
            }
            return $totalSize;
        }
        
} 

$file1 = new File("file1.txt", 1000);
$file2 = new File("file2.txt", 2000);
$file3 = new File("file3.pdf", 5000);
$files = [
    $file1, $file2, $file3,
];
$totalSize = File::CalcolateTotalsizeFile($files);
echo  $totalSize;
?>