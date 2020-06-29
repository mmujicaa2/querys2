<?php

$conn = oci_connect('tg_penaltg', 'tg20170523', 'rpenprod');


$sql = 'BEGIN Gnt_ComboJuecesSel_Pkg(:SRV_Message, :In_Crr_IdEvento, :Col_Crr_IdFuncionario, :Col_xGls_JuecesAud); END;';

$stmt = oci_parse($conn,$sql);

//  Bind the input parameter
oci_bind_by_name($stmt,':In_Crr_IdEvento',$In_Crr_IdEvento,32);

// Bind the output parameter
oci_bind_by_name($stmt,':Col_xGls_JuecesAud',$Col_xGls_JuecesAud,32);

// Assign a value to the input 
$In_Crr_IdEvento = '95834872';

oci_execute($stmt);

// $message is now populated with the output value
print "$Col_xGls_JuecesAud\n";
?>
