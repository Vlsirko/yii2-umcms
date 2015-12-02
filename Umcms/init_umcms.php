#!/usr/bin/env php
<?php
/**
 * There are some actions when umcms2 init
 */
/**
 * create upload dir
 */
$pathToBackend = __DIR__ . '/../../../../backend';
$pathToFrontend = __DIR__ . '/../../../../frontend';
$pathToUploadBackend = $pathToBackend . '/web/upload';
$pathToUploadFrontend = $pathToFrontend . '/web/upload';
$pathToRoot = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..','..', '..',]);

if (!is_dir($pathToUploadBackend)) {
	mkdir($pathToUploadBackend, 0777, true);
	print('Upload dir is created' . PHP_EOL);
}

chmod(__DIR__ . DIRECTORY_SEPARATOR . 'runtime', 0777);

/**
 * create symlink to frontend
 */
if (!is_link($pathToUploadFrontend) && symlink($pathToUploadBackend, $pathToUploadFrontend)) {
	print('Symlink for upload dir to frontend is created' . PHP_EOL);
}

/**
 * rewrite backend config
 */
print('Rewrite config file' . PHP_EOL);
$pathToBackendConfig = $pathToBackend . '/config/main.php';

$toRewrite = [
	'basePath' => '\'' . __DIR__ . '\'',
	'modules' =>   'require ("' . implode(DIRECTORY_SEPARATOR, [__DIR__, 'config', 'modules.php']) . '")',
	'identityClass' => "'Umcms\models\User'"
];
$config = file_get_contents($pathToBackendConfig);

foreach($toRewrite as $key => $value){
	$config =  preg_replace("#'{$key}' => ([^,]+)#mi", "'{$key}' => $value", $config);
}

file_put_contents($pathToBackendConfig, $config);
print('Rewrite config file success' . PHP_EOL);

print('Run migrations' . PHP_EOL);

$migrationPathes = [
	'',
	'@yii/rbac/migrations',
	__DIR__ . '/migrations/',
	__DIR__ . '/../../yii2-rbac/migrations',
	__DIR__ . '/../../yii2-uploads/Uploads/migrations'
];

$command = $pathToRoot . '/yii migrate';
foreach($migrationPathes as $path){
	$commandPath = $path ? '--migrationPath=' . $path : '';
	print("enter 'yes' to apply migration' {$path}" . PHP_EOL);
	print(shell_exec($command . ' ' . $commandPath));
}


exit;
