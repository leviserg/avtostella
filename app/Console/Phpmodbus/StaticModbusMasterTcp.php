<?php

require_once dirname(__FILE__) . '/IecType.php';
require_once dirname(__FILE__) . '/PhpType.php'; 

class StaticModbusMasterTcp {
  private static $sock;
  public static $host = "127.0.0.1";
  public static $port = "502";
  public static $socket_protocol = "TCP"; // Socket protocol (TCP, UDP)
  public static $client = "";
  public static $client_port = "502";
  public static $status;
  public static $timeout_sec = 5; // Timeout 5 sec
  public $endianness = 0; // Endianness codding (little endian == 0, big endian == 1) 
  public static $connected;
  
  /**
   * ModbusMaster
   *
   * This is the constructor that defines {@link $host} IP address of the object. 
   *     
   * @param String $host An IP address of a Modbus TCP device. E.g. "192.168.1.1"
   * @param String $protocol Socket protocol (TCP, UDP)   
   */ 
/*
  public function __construct($host, $port, $protocol) {
    self::$host = $host;
    self::$port = $port;
    self::$socket_protocol = $protocol;    
  }
*/
  /**
   * __toString
   *
   * Magic method
   */
  /*
  function  __toString() {
      return "<pre>" . self::$status . "</pre>";
  }
  */

  public static function connect(){
    // Create a protocol specific socket 
    if (self::$socket_protocol == "TCP"){ 
        // TCP socket
        self::$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);      
    } elseif (self::$socket_protocol == "UDP"){
        // UDP socket
        self::$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    } else {
        self::$connected = false;
        throw new Exception("Unknown socket protocol, should be 'TCP' or 'UDP'");
    }
    // Bind the client socket to a specific local port
    if (strlen(self::$client)>0){
        $result = socket_bind(self::$sock, self::$client, self::$client_port);
        if ($result === false) {
          self::$connected = false;
            throw new Exception("socket_bind() failed.</br>Reason: ($result)".
                socket_strerror(socket_last_error(self::$sock)));
        } else {
            self::$status .= "Bound\n";
        }
    }
    // Socket settings
    socket_set_option(self::$sock, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));
    // Connect the socket
    $result = @socket_connect(self::$sock, self::$host, self::$port);
    if ($result === false) {
        self::$connected = false;
        self::disconnect();
        //die(json_encode([0]));//ConnectionFailed
        exit("0");
        throw new Exception("socket_connect() failed.</br>Reason: ($result)".
            socket_strerror(socket_last_error(self::$sock)));
    } else {
        self::$status .= "Connected\n";
        self::$connected = true;
        return true;        
    }
    return true;
  }

  /**
   * disconnect
   *
   * Disconnect the socket
   */
  public static function disconnect(){
    if(self::$sock != null){
      socket_close(self::$sock);
    }
    self::$status .= "Disconnected\n";
  }

  /**
   * send
   *
   * Send the packet via Modbus
   *
   * @param string $packet
   */
  private function send($packet){
      socket_write(self::$sock, $packet, strlen($packet));  
      self::$status .= "Send\n";
  }

  /**
   * rec
   *
   * Receive data from the socket
   *
   * @return bool
   */
  private static function rec(){
    socket_set_nonblock(self::$sock);
    $readsocks[] = self::$sock;     
    $writesocks = NULL;
    $exceptsocks = NULL;
    $rec = "";
    $lastAccess = time();
    try{
      while (socket_select($readsocks, 
              $writesocks, 
              $exceptsocks,
              0, 
              300000) !== FALSE) {
              self::$status .= "Wait data ... \n";
          if (in_array(self::$sock, $readsocks)) {
              while (@socket_recv(self::$sock, $rec, 2000, 0)) {
                  self::$status .= "Data received\n";
                  return $rec;
              }
              $lastAccess = time();
          } else {             
              if (time()-$lastAccess >= self::$timeout_sec) {
                  throw new Exception( "Watchdog time expired [ " .
                    self::$timeout_sec . " sec]!!! Connection to " . 
                    self::$host . " is not established.");
                  return false;
              }
          }
          $readsocks[] = self::$sock;
          return true;
      }      
    } catch (Exception $e){
      return false;
    }

  } 
  
  /**
   * responseCode
   *
   * Check the Modbus response code
   *
   * @param string $packet
   * @return bool
   */
  private function responseCode($packet){    
    if((ord($packet[7]) & 0x80) > 0) {
      // failure code
      $failure_code = ord($packet[8]);
      // failure code strings
      $failures = array(
        0x01 => "ILLEGAL FUNCTION",
        0x02 => "ILLEGAL DATA ADDRESS",
        0x03 => "ILLEGAL DATA VALUE",
        0x04 => "SLAVE DEVICE FAILURE",
        0x05 => "ACKNOWLEDGE",
        0x06 => "SLAVE DEVICE BUSY",
        0x08 => "MEMORY PARITY ERROR",
        0x0A => "GATEWAY PATH UNAVAILABLE",
        0x0B => "GATEWAY TARGET DEVICE FAILED TO RESPOND");
      // get failure string
      if(key_exists($failure_code, $failures)) {
        $failure_str = $failures[$failure_code];
      } else {
        $failure_str = "UNDEFINED FAILURE CODE";
      }
      // exception response
      throw new Exception("Modbus response error code: $failure_code ($failure_str)");
    } else {
      self::$status .= "Modbus response error code: NOERROR\n";
      return true;
    }    
  }
  

  /**
   * readMultipleRegisters
   *
   * Modbus function FC 3(0x03) - Read Multiple Registers.
   * 
   * This function reads {@link $quantity} of Words (2 bytes) from reference 
   * {@link $referenceRead} of a memory of a Modbus device given by 
   * {@link $unitId}.
   *    
   *
   * @param int $unitId usually ID of Modbus device 
   * @param int $reference Reference in the device memory to read data (e.g. in device WAGO 750-841, memory MW0 starts at address 12288).
   * @param int $quantity Amounth of the data to be read from device.
   * @return false|Array Success flag or array of received data.
   */
  function readMultipleRegisters($unitId, $reference, $quantity){
    self::$status .= "readMultipleRegisters: START\n";
    // connect
    $this->connect();
    // send FC 3    
    $packet = $this->readMultipleRegistersPacketBuilder($unitId, $reference, $quantity);
    self::$status .= $this->printPacket($packet);    
    $this->send($packet);
    // receive response
    $rpacket = $this->rec();
    self::$status .= $this->printPacket($rpacket);    
    // parse packet    
    $receivedData = $this->readMultipleRegistersParser($rpacket);
    // disconnect
    $this->disconnect();
    self::$status .= "readMultipleRegisters: DONE\n";
    // return
    return $receivedData;
  }
 
  /**
   * fc3
   *
   * Alias to {@link readMultipleRegisters} method.
   *
   * @param int $unitId
   * @param int $reference
   * @param int $quantity
   * @return false|Array
   */
  function fc3($unitId, $reference, $quantity){
    return $this->readMultipleRegisters($unitId, $reference, $quantity);
  } 

  function readExtendMultipleRegisters($unitId, $reference, $quantity){
    self::connect();
    $packet = self::readMultipleRegistersPacketBuilder($unitId, $reference, $quantity);  
    try{
      self::send($packet);
      $rpacket = self::rec();
      if($rpacket!==false){
          $receivedData = self::readMultipleRegistersParser($rpacket);  
      }
    }
    catch (Exception $e){
      return $e;
    }
    self::disconnect();
    return $receivedData;
  }

  public static function ReadHoldingRegisters($unitId, $reference, $quantity){
    //return $this->readMultipleRegisters($unitId, $reference, $quantity);
    return self::readExtendMultipleRegisters($unitId, $reference, $quantity);
  } 
  
  /**
   * readMultipleRegistersPacketBuilder
   *
   * Packet FC 3 builder - read multiple registers
   *
   * @param int $unitId
   * @param int $reference
   * @param int $quantity
   * @return string
   */
  private function readMultipleRegistersPacketBuilder($unitId, $reference, $quantity){
    $dataLen = 0;
    // build data section
    $buffer1 = "";
    // build body
    $buffer2 = "";
    $buffer2 .= iecType::iecBYTE(3);             // FC 3 = 3(0x03)
    // build body - read section    
    $buffer2 .= iecType::iecINT($reference);  // refnumber = 12288      
    $buffer2 .= iecType::iecINT($quantity);       // quantity
    $dataLen += 5;
    // build header
    $buffer3 = '';
    $buffer3 .= iecType::iecINT(rand(0,65000));   // transaction ID
    $buffer3 .= iecType::iecINT(0);               // protocol ID
    $buffer3 .= iecType::iecINT($dataLen + 1);    // lenght
    $buffer3 .= iecType::iecBYTE($unitId);        //unit ID
    // return packet string
    return $buffer3. $buffer2. $buffer1;
  }
  
  /**
   * readMultipleRegistersParser
   *
   * FC 3 response parser
   *
   * @param string $packet
   * @return array
   */
  private function readMultipleRegistersParser($packet){    
    $data = array();
    // check Response code
    self::responseCode($packet);
    // get data
    for($i=0;$i<ord($packet[8]);$i++){
      $data[$i] = ord($packet[9+$i]);
    }    
    return $data;
  }
  
  /**
   * readMultipleInputRegisters
   *
   * Modbus function FC 4(0x04) - Read Multiple Input Registers.
   * 
   * This function reads {@link $quantity} of Words (2 bytes) from reference 
   * {@link $referenceRead} of a memory of a Modbus device given by 
   * {@link $unitId}.
   *    
   *
   * @param int $unitId usually ID of Modbus device 
   * @param int $reference Reference in the device memory to read data.
   * @param int $quantity Amounth of the data to be read from device.
   * @return false|Array Success flag or array of received data.
   */
  function readMultipleInputRegisters($unitId, $reference, $quantity){
    self::$status .= "readMultipleInputRegisters: START\n";
    // connect
    $this->connect();
    // send FC 4    
    $packet = $this->readMultipleInputRegistersPacketBuilder($unitId, $reference, $quantity);
    self::$status .= $this->printPacket($packet);    
    $this->send($packet);
    // receive response
    $rpacket = $this->rec();
    self::$status .= $this->printPacket($rpacket);    
    // parse packet    
    $receivedData = $this->readMultipleInputRegistersParser($rpacket);
    // disconnect
    $this->disconnect();
    self::$status .= "readMultipleInputRegisters: DONE\n";
    // return
    return $receivedData;
  }
  
  /**
   * fc4
   *
   * Alias to {@link readMultipleInputRegisters} method.
   *
   * @param int $unitId
   * @param int $reference
   * @param int $quantity
   * @return false|Array
   */
  public static function fc4($unitId, $reference, $quantity){
    return $this->readMultipleInputRegisters($unitId, $reference, $quantity);
  }  
  
  /**
   * readMultipleInputRegistersPacketBuilder
   *
   * Packet FC 4 builder - read multiple input registers
   *
   * @param int $unitId
   * @param int $reference
   * @param int $quantity
   * @return string
   */
  private function readMultipleInputRegistersPacketBuilder($unitId, $reference, $quantity){
    $dataLen = 0;
    // build data section
    $buffer1 = "";
    // build body
    $buffer2 = "";
    $buffer2 .= iecType::iecBYTE(4);                                                // FC 4 = 4(0x04)
    // build body - read section    
    $buffer2 .= iecType::iecINT($reference);                                        // refnumber = 12288      
    $buffer2 .= iecType::iecINT($quantity);                                         // quantity
    $dataLen += 5;
    // build header
    $buffer3 = '';
    $buffer3 .= iecType::iecINT(rand(0,65000));                                     // transaction ID
    $buffer3 .= iecType::iecINT(0);                                                 // protocol ID
    $buffer3 .= iecType::iecINT($dataLen + 1);                                      // lenght
    $buffer3 .= iecType::iecBYTE($unitId);                                          // unit ID
    // return packet string
    return $buffer3. $buffer2. $buffer1;
  }
  
  /**
   * readMultipleInputRegistersParser
   *
   * FC 4 response parser
   *
   * @param string $packet
   * @return array
   */
  private function readMultipleInputRegistersParser($packet){    
    $data = array();
    // check Response code
    $this->responseCode($packet);
    // get data
    for($i=0;$i<ord($packet[8]);$i++){
      $data[$i] = ord($packet[9+$i]);
    }    
    return $data;
  }
  

  /**
   * writeMultipleRegister
   *
   * Modbus function FC16(0x10) - Write Multiple Register.
   *
   * This function writes {@link $data} array at {@link $reference} position of 
   * memory of a Modbus device given by {@link $unitId}.
   *
   *
   * @param int $unitId usually ID of Modbus device 
   * @param int $reference Reference in the device memory (e.g. in device WAGO 750-841, memory MW0 starts at address 12288)
   * @param array $data Array of values to be written.
   * @param array $dataTypes Array of types of values to be written. The array should consists of string "INT", "DINT" and "REAL".    
   * @return bool Success flag
   */       
  function writeMultipleRegister($unitId, $reference, $data, $dataTypes){
    self::$status .= "writeMultipleRegister: START\n";
    // connect
    $this->connect();
    // send FC16    
    $packet = $this->writeMultipleRegisterPacketBuilder($unitId, $reference, $data, $dataTypes);
    self::$status .= $this->printPacket($packet);    
    $this->send($packet);
    // receive response
    $rpacket = $this->rec();
    self::$status .= $this->printPacket($rpacket);    
    // parse packet
    $this->writeMultipleRegisterParser($rpacket);
    // disconnect
    $this->disconnect();
    self::$status .= "writeMultipleRegister: DONE\n";
    return true;
  }


  /**
   * fc16
   *
   * Alias to {@link writeMultipleRegister} method
   *
   * @param int $unitId
   * @param int $reference
   * @param array $data
   * @param array $dataTypes
   * @return bool
   */
  public static function fc16($unitId, $reference, $data, $dataTypes){    
    return $this->writeMultipleRegister($unitId, $reference, $data, $dataTypes);
  }


  /**
   * writeMultipleRegisterPacketBuilder
   *
   * Packet builder FC16 - WRITE multiple register
   *     e.g.: 4dd90000000d0010300000030603e807d00bb8
   *
   * @param int $unitId
   * @param int $reference
   * @param array $data
   * @param array $dataTypes
   * @return string
   */
  private function writeMultipleRegisterPacketBuilder($unitId, $reference, $data, $dataTypes){
    $dataLen = 0;
    // build data section
    $buffer1 = "";
    foreach($data as $key=>$dataitem) {
      if($dataTypes[$key]=="INT"){
        $buffer1 .= iecType::iecINT($dataitem);   // register values x
        $dataLen += 2;
      }
      elseif($dataTypes[$key]=="DINT"){
        $buffer1 .= iecType::iecDINT($dataitem, $this->endianness);   // register values x
        $dataLen += 4;
      }
      elseif($dataTypes[$key]=="REAL") {
        $buffer1 .= iecType::iecREAL($dataitem, $this->endianness);   // register values x
        $dataLen += 4;
      }       
      else{
        $buffer1 .= iecType::iecINT($dataitem);   // register values x
        $dataLen += 2;
      }
    }
    // build body
    $buffer2 = "";
    $buffer2 .= iecType::iecBYTE(16);             // FC 16 = 16(0x10)
    $buffer2 .= iecType::iecINT($reference);      // refnumber = 12288      
    $buffer2 .= iecType::iecINT($dataLen/2);        // word count      
    $buffer2 .= iecType::iecBYTE($dataLen);     // byte count
    $dataLen += 6;
    // build header
    $buffer3 = '';
    $buffer3 .= iecType::iecINT(rand(0,65000));   // transaction ID    
    $buffer3 .= iecType::iecINT(0);               // protocol ID    
    $buffer3 .= iecType::iecINT($dataLen + 1);    // lenght    
    $buffer3 .= iecType::iecBYTE($unitId);        //unit ID    
    
    // return packet string
    return $buffer3. $buffer2. $buffer1;
  }
  
  /**
   * writeMultipleRegisterParser
   *
   * FC16 response parser
   *
   * @param string $packet
   * @return bool
   */
  private function writeMultipleRegisterParser($packet){
    $this->responseCode($packet);
    return true;
  }

  /**
   * byte2hex
   *
   * Parse data and get it to the Hex form
   *
   * @param char $value
   * @return string
   */
  private function byte2hex($value){
    $h = dechex(($value >> 4) & 0x0F);
    $l = dechex($value & 0x0F);
    return "$h$l";
  }

  /**
   * printPacket
   *
   * Print a packet in the hex form
   *
   * @param string $packet
   * @return string
   */
  private function printPacket($packet){
    $str = "";   
    $str .= "Packet: "; 
    for($i=0;$i<strlen($packet);$i++){
      $str .= $this->byte2hex(ord($packet[$i]));
    }
    $str .= "\n";
    return $str;
  }
}
