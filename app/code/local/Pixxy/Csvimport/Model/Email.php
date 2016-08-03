<?php

	class Pixxy_Csvimport_Model_Email extends Mage_Core_Model_Abstract{
		
		private $_helper;
		private $_send_email;
		private $_email;
		
		public function _construct(){
			$this->_helper = Mage::helper('csvimport');
			$this->_send_email = $this->_helper->getConfig('debug/send_email');
			$this->_email = $this->_helper->getConfig('debug/email');
		}
		
		public function send($filename_original, $status){
			if($this->_send_email){
				$emailTemplate = Mage::getModel('core/email_template')->loadDefault('pixxy_csvimport_email');
				$senderName = Mage::getStoreConfig('trans_email/ident_general/name');
				$senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
				
				$emailTemplateVariables = array();
				$emailTemplateVariables['filename_original'] = $filename_original;
				$emailTemplateVariables['status'] = $status;
				
				$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);

				$emails = explode(',', $this->_email);
				if(count($emails)){
					foreach ($emails as $email_address){
						//$emailTemplate->send(trim($email_address), 'Pixxy CSV Import', $emailTemplateVariables);
						$mail = Mage::getModel('core/email')
						 ->setToName($senderName)
						 ->setToEmail($email_address)
						 ->setBody($processedTemplate)
						 ->setSubject('Ecomitize CSV Import')
						 ->setFromEmail($senderEmail)
						 ->setFromName($senderName)
						 ->setType('html');
				 		try{
							$mail->send();
						}
				 		catch(Exception $error){
				 			Mage::getSingleton('core/session')->addError($error->getMessage());
				 			return false;
			 			}
					}
				}
			}
		}
		
	}