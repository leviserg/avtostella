<?php
/*
    namespace globalvars;
    require_once dirname(__FILE__).'/../globalvars/gvl.php';
    use GVL;
*/
    namespace Phpmodbus;
    require_once dirname(__FILE__).'/../Phpmodbus/ExternModbusMasterTcp.php';

    use Exception;
    use ExternModbusMasterTcp as PLC;

    PeriodicCall();

    function PeriodicCall($period = 60, $tick = 2){
		try{

                $ModbusUnitId = 1;
                $plc = new PLC("127.0.0.1","502","TCP");
                $request = [
                    'start' => 0,
                    'length' => 100
                ];

            // $plc->connect();

            /*
            $start = microtime(true);
            $seconds = $period;
            $secondtick = $tick;
            set_time_limit($seconds);
            for($i=0; $i < $seconds; $i+=$secondtick){
                ReadRegisters($plc, $ModbusUnitId, $request);
                time_sleep_until($start + $i + $secondtick);
            }
            */

            /*
            ReadRegisters($plc, $ModbusUnitId, $request);
            $plc->disconnect();
            $datalen = 50;
            $arrdata = [];
            for($i=0;$i<$datalen;$i++){
                array_push($arrdata, random_int(($i+1)*10, ($i+2)*10));
            }
            UpdateData($arrdata);
            */
        }
        catch (Exception $e){
            die(var_dump($e));
        }
    }

    function ReadRegisters($plc, $unitId, $request){
        $registers = [];
        $data = $plc->ReadHoldingRegisters($unitId, $request['start'], $request['length']);
        $bytes = count($data);
        if($bytes>0){
            for($i = 0; $i < $bytes; $i+=2){
                array_push($registers, $data[$i]*256 + $data[$i+1]);
            }
        }
        UpdateData($registers);
    }

    function UpdateData($array){
        $dbname = "modbusdb";
        $tblname = "holding_reg";
        $sql = "UPDATE `".$tblname."` SET `recdate`=now(),";
        for($i=0; $i < count($array); $i++){
            $sql .= "`R".$i."`=".$array[$i].",";
        }
        $sql = rtrim($sql, ",");
        $sql .= " WHERE 1";
        try{
            $conn = mysqli_connect("localhost","root","",$dbname);
            try{
                if(mysqli_query($conn, $sql)){
                    echo("Data successfull updated at ".date("d-m-Y H:i:s",time())."\n");
                }
                else{
                    echo("Couldn't update data\n");
                }
            }
            catch (Exception $e){
                //echo("Couldn't update data 2\n");
                //echo $e;
            }
            mysqli_close($conn);
        }
        catch (Exception $e) {
            //echo "Couldn't connect to db\n";
            //echo $e;
            exit;
        }
    }
?>
