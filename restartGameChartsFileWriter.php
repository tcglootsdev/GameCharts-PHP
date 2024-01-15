<?php
$myfile = fopen("/var/www/vhosts/gamecharts.org/httpdocs/restartFilewriter.txt", "w") or die("Fallo al reiniciar GameChartsFileWriter");
fclose($myfile);
echo "GameChartsFileWriter se reiniciará en breve."
?>
