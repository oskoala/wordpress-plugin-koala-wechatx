<?php
/**
 * 保存到本地
 */
function koala_wechat_x_save_to_local( $tem_file_path, $filename = "" ) {
	//保存网络图片
	$save_path = wp_upload_dir()['basedir'];

	$folder = date( "Y/m/", time() );

	$save_path = $save_path . "/" . $folder;

	if ( $filename == "" ) {
		$filename = mt_rand( 100000, 999999 ) . '.jpg';
	}

	if ( ! is_dir( $save_path ) ) {
		mkdir( $save_path, 0777, true );
	}

	move_uploaded_file( $tem_file_path, $save_path . $filename );
	return $filename;
}