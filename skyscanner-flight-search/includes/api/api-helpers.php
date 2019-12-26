<?php

/*
*  sfs_include
*
*  This function will include a file
*
*  @type	function
*  @date	23/12/2019
*  @since	1.0.0
*
*  @param	$file (string)
*  @return	N/A
*/

function sfs_include( $file ) {
	
	$path = sfs_get_path( $file );
	
	if( file_exists($path) ) {
		
		include_once( $path );
		
	}
	
}

/*
*  sfs_get_path
*
*  This function will return the path to a file within the SFS plugin folder
*
*  @type	function
*  @date	23/12/2019
*  @since	1.0.0
*
*  @param	$path (string) the relative path from the root of the SFS plugin folder
*  @return	(string)
*/

function sfs_get_path( $path = '' ) {
	
	return SFS_PATH . $path;
	
}

/**
*  sfs_get_url
*
*  This function will return the url to a file within the SFS plugin folder
*
*  @date	12/12/17
*  @since	5.6.8
*
*  @param	string $path The relative path from the root of the SFS plugin folder
*  @return	string
*/

function sfs_get_url( $path = '' ) {
	
	// define SFS_URL to optimise performance
	if( !defined('SFS_URL') ) {
		return $path;
	}
	
	// return
	return SFS_URL . $path;
	
}

?>