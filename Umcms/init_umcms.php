#!/usr/bin/env php
<?php
/**
 * There are some actions when umcms2 init
 */
/**
 * create upload dir
 */
$pathToBackend = __DIR__ . '/../../../backend';
$pathToFrontend = __DIR__ . '/../../../frontend';
$pathToUploadBackend = $pathToBackend . '/web/upload';
$pathToUploadFrontend = $pathToFrontend . '/web/upload';

if (!is_dir($pathToUploadBackend)) {
	mkdir($pathToUploadBackend, 0777);
	print('Upload dir is created' . PHP_EOL);
}

/**
 * create symlink to frontend
 */
if (!is_link($pathToUploadFrontend) && symlink($pathToUploadBackend, $pathToUploadFrontend)) {
	print('Symlink for upload dir to frontend is created' . PHP_EOL);
}

exit;
