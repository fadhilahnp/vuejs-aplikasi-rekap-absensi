<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

include("config.php");

$IP = "192.168.1.201";
$Key = "0";
$response = Array();

if($IP == "") $IP = "192.168.1.201";
if($Key == "") $Key="0";

$err = [
        'status' => 'error',
        'data' => '',
        'message' => 'Koneksi Gagal! Periksa jaringan anda'
        ];

$Connect = @fsockopen($IP, "80", $errno, $errstr, 1) or exit(json_encode($err));

if($Connect){
    $soap_request = "<GetAttLog>
                        <ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey>
                        <Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg>
                    </GetAttLog>";

    $newLine = "\r\n";
    fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
    fputs($Connect, "Content-Type: text/xml".$newLine);
    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
    fputs($Connect, $soap_request.$newLine);
    $buffer = "";
    while($Response = fgets($Connect, 1024)){
        $buffer = $buffer.$Response;
    }
} else {
    $response = [
        'status' => 'error',
        'data' => '',
        'message' => 'Koneksi Gagal! Periksa jaringan anda'
    ];

    echo json_encode($response);
}


$buffer = Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
$buffer = explode("\r\n",$buffer);
$total = 0;

for($a = 0; $a < count($buffer); $a++){
    $data = Parse_Data($buffer[$a],"<Row>","</Row>");

    $pin = Parse_Data($data,"<PIN>","</PIN>");
    $datetime = Parse_Data($data,"<DateTime>","</DateTime>");
    $status = Parse_Data($data,"<Status>","</Status>");

    $check = $conn->prepare("SELECT * FROM attendance WHERE pin='$pin' AND presence='$datetime'");
    $check->execute();

    if ($check->fetchColumn() > 0) {
        //proses mengingatkan data sudah ada
        // echo "<script>alert('Username Sudah Digunakan');history.go(-1) </script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO attendance (pin, presence, status) values ('$pin','$datetime','$status')");
        $stmt->execute();
    }

    $total++;

    if ($total == count($buffer)) {
        $response = [
            'status' => 'success',
            'data' => '',
            'message' => 'Sinkronisasi data berhasil'
        ];
    
        echo json_encode($response);
    }

    ini_set('max_execution_time', 300);
}

function Parse_Data ($data,$p1,$p2) {
    $data = " ".$data;
    $result = "";
    $start = strpos($data,$p1);
    if ($start != "") {
        $end = strpos(strstr($data,$p1),$p2);
        if ($end != ""){
        $result=substr($data,$start+strlen($p1),$end-strlen($p1));
        }
    }
    return $result; 
}

?>