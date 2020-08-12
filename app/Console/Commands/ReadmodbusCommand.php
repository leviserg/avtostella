<?php
	namespace App\Console\Commands;
	use Illuminate\Console\Command;
	use Illuminate\Http\Request;

	class ReadmodbusCommand extends Command{

		protected $signature = 'modbus:read';
		protected $description = 'Call modbus read function every minute';

		public function __construct(){
			parent::__construct();
		}

		public function handle(){
			//require_once dirname(__FILE__).'/../Phpmodbus/ExternModbusMasterTcp.php';
			require_once dirname(__FILE__).'/updatedata.php';
		}

	}
