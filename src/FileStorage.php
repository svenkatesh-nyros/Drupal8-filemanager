<?php 
namespace Drupal\file_manager;

use Drupal\file_manager\Controller\FileManagerController;
use Drupal\Core\AjaxResponse;

class FileStorage {
	static function getAll($uid) {
		
		$result = db_query('SELECT * FROM {file_managed} where uid=:uid', array(':uid' => $uid));
		return $result;
	}

	static function filedelete($fid) {
		db_delete('file_managed')->condition('fid',$fid)->execute();
	}
}
