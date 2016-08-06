<?php

namespace Drupal\file_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file_manager\FileStorage;
use Drupal\Core\Url;
use Drupal\Core\Render\Markup;
use Drupal\Core\QueryExtendableInterface;
use Drupal\Core\Database\Statement;
use Drupal\Core\Database\SelectQueryExtender;

class FileManagerController extends ControllerBase {
	
	/**
	*@file 
	* Contains
	*/
	

	public function content() {

/**
* $form is used to display the input type of file to upload your file.
*/
		$form=\Drupal:: formBuilder()-> getForm('Drupal\file_manager\Form\UploadFile');

		$user=\Drupal::currentUser(); #this command is used to get the current user details.
		$uid=$user->id(); #we are getting the user id from the current user like '1' for admin.
		$limit = 5;
		if(empty($_REQUEST['page'])) {
			$start= 0;
		}
		else {
			$start= $_REQUEST['page'] * $limit;
		}

		$table = 'file_managed';
		$query = 'SELECT * FROM {' . $table . '} WHERE uid='.$uid.'  ORDER BY filename ASC';
		$count_query = 'SELECT count(*) FROM {' . $table . '} WHERE uid='.$uid;
	    $count_result = db_query($count_query);
	    $count_result->allowRowCount = TRUE;
	    $total_count = $count_result->fetchAssoc();
	    //$total = $total_count['count'];
	    $total = $total_count['count(*)'];
	    $page = pager_default_initialize($total, $limit);
	    $query_items = db_query_range($query, $start, $limit);
	    $query_items->allowRowCount = TRUE;

	    if ($query_items->rowCount() == 0) {
	      	return t('No Results found yet Please import');
	    }
	    else {
				$header= array(
					'filename' => t('FileName'),
					'size' => t('filesize'),
					'Mimetype' => t('MimeType'),
					'created' => t('Created'),
					'share' => t('Share'),
					'Delete' => t('Delete'),
				);
				$rows= array();
				$user= \Drupal::currentUser();
		    $uid=$user->id();
		    	
				//foreach(FileStorage::getAll($uid) as $id=>$content) {
				foreach ($query_items as $key) {
					$share= Url::fromRoute('file_share',array('id'=>$key->filename));
					$delete= Url::fromRoute('file_delete',array('id'=>$key->fid));
					$rows[] =array(
						'data' => array(
							$key->filename,
							number_format($key->filesize / 1024, 2) . ' kB',
							$key->filemime,
							//date('l d m ,Y ',strtotime($content->created)),
							date('D Y/m/d h:i',$key->created),
							\Drupal::l('Share',$share),
							\Drupal::l('Delete',$delete),
							),
						);

				}

		$render= [];
		$render[] = array(
			'#type' => 'table',
			'#header' => $header,
			'#rows' => $rows,
			'#attributes' => array(
				'id' => 'files_list_view',
				),
			'#attached' => array('library'=>array('file_manager/file_manager_css')),	
			);
		$render[] = ['#type' => 'pager'];
		//$markup = drupal_render($render);
		return array($form,$render);
	  }
  }
}
