<?php

	class Pixxy_Csvimport_Model_System_Mode {
		
		public function toOptionArray(){
			return array(
				'1'=>'Import only',
				'2'=>'Update only',
				'3'=>'Import and update',
			);
		}
		
	}