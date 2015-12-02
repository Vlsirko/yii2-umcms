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
$pathToConsole = __DIR__ . '/../../../../console';
$pathToUploadBackend = $pathToBackend . '/web/upload';
$pathToUploadFrontend = $pathToFrontend . '/web/upload';
$pathToRoot = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..','..', '..',]);
$baseMigrationPath = implode(DIRECTORY_SEPARATOR, [$pathToRoot, 'console', 'migrations']);
$vendorDir = implode(DIRECTORY_SEPARATOR, [$pathToRoot, '', 'vendor']);

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


/**
 * rewrite console config add rbac generator module
 */
$pathToConsoleConfig = $pathToConsole . implode(DIRECTORY_SEPARATOR, ['', 'config', 'main.php']);
$consoleConfig = file_get_contents($pathToConsoleConfig);
$consoleConfig =  preg_replace("#'bootstrap' => \[([^,]+)\]#mi", "'bootstrap' => [$1, 'rbac_rule']", $consoleConfig);

$moduleConfig = [
	'rbac_rule' => [
		'class' => 'Rbac\console\Module',
		'scan_path' => [
			'@vendor/vlsirko/yii2-umcms'
		]
	]
];

$moduleConfig = var_export($moduleConfig, true);
$consoleConfig = preg_replace("#('components' => \[(.|\n)+\],)#mi", "$1\n'modules' => $moduleConfig,", $consoleConfig);
file_put_contents($pathToConsoleConfig, $consoleConfig) ;
print('Rewrite config file success' . PHP_EOL);



print('Copying migrations to base path' . PHP_EOL);
$migrationsDirs = [
	implode(DIRECTORY_SEPARATOR, [$vendorDir, 'yiisoft', 'yii2', 'rbac', 'migrations']), //path to yii2 rbac migration
	__DIR__ . DIRECTORY_SEPARATOR . 'migrations',
	__DIR__ . implode(DIRECTORY_SEPARATOR, ['', '..', '..', 'yii2-rbac', 'migrations'])
];

foreach($migrationsDirs as $dir){
	
	$files = array_diff(scandir($dir), ['.', '..']);
	
	foreach($files as $file){
		$source = implode(DIRECTORY_SEPARATOR, [$dir, $file]);
		$dest = implode(DIRECTORY_SEPARATOR, [$baseMigrationPath, $file]);
		copy($source, $dest);
	}
		
}

print('Copying migrations to base path is finised' . PHP_EOL);
exit;
