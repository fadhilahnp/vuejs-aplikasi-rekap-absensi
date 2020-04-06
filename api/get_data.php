<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

include("config.php");

$param = $_GET["param"];

if ($param == "") $param = "all";

if ($param == "name") {
    $response = Array();

    try {
                $stmt = $conn->prepare("SELECT * FROM employee ORDER BY name");
                $stmt->execute();
            
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $response = [
                    'status' => 'success',
                    'data' => $result,
                    'message' => 'Get employee data success'
                ];
           
                echo json_encode($response);
            }
            catch(PDOException $e) {
                $response = [
                    'status' => 'Error',
                    'data' => null,
                    'message' => $e->getMessage()
                ];

                echo json_encode($response);
            }
        
            $conn = null;
}

if ($param == "single") {
    $pin = $_GET["pin"];
    $fromDate = $_GET["fromDate"];
    $toDate = $_GET["toDate"];
    
    try {
        $stmt = $conn->prepare("SELECT a.pin, e.name, e.status as annotation, DATE(presence) as att_date, TIME(presence) as att_time, a.status FROM attendance a LEFT JOIN employee e ON e.pin = a.pin WHERE a.pin = ". $pin ." AND presence BETWEEN '".$fromDate."' AND '".$toDate."'");
        
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $response = [
            'status' => 'success',
            'data' => $result,
            'message' => 'Get personal attendance data success'
        ];
   
        echo json_encode($response);
    }
    catch(PDOException $e) {
        $response = [
            'status' => 'error',
            'data' => null,
            'message' => $e->getMessage()
        ];

        echo json_encode($response);
    }
    
    $conn = null;
}

if ($param == "all") {
    $fromDate = $_GET["fromDate"];
    $toDate = $_GET["toDate"];
    
    try {
        $stmt = $conn->prepare("SELECT a.pin, e.name, e.status as annotation, DATE(presence) as att_date, TIME(presence) as att_time FROM attendance a LEFT JOIN employee e ON e.pin = a.pin WHERE a.status = 0 AND presence BETWEEN '".$fromDate."' AND '".$toDate." LIMIT 40'");
        
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $response = [
            'status' => 'success',
            'data' => $result,
            'message' => 'Get all attendance data success'
        ];
   
        echo json_encode($response);
    }
    catch(PDOException $e) {
        $response = [
            'status' => 'Error',
            'data' => null,
            'message' => $e->getMessage()
        ];

        echo json_encode($response);
    }
    
    $conn = null;
}
?>