<?php
require_once(LIB_DIR.'local/aws/bin/vendor/autoload.php');
use Aws\AutoScaling;
use Aws\Exception\AwsException;

class SCF{
	private static $sharedConfig = [
		'profile' => 'default',
		'region' => 'ap-northeast-1',
		'version' => 'latest'
	];
	private static $sdk;
	private static $CloudFront;
	private static $distribution_name_list = array(
		'doshin' => array(
			'production' => 'E1O0G6O6HM6FZ3',
			'stage' => 'E1O0G6O6HM6FZ3',
		),
		'chunichi' => array(
			'production' => 'E30QKAN99XXJ1H',
			'stage' => 'E2ZU2R0QEKBPVO',
		),
		'tokyo' => array(
			'production' => 'EKSVDLRV3NS6D',
			'stage' => 'E1OOVOIAXPIDVK',
		),
	);

	public static function init(){
		self::$sdk = new Aws\Sdk(self::$sharedConfig);
		self::$CloudFront = self::$sdk->createCloudFront();
	}

	public static function CacheClear($target_path='/*'){
		if($target_path=='' || $target_path=='/' || $target_path=='/*'){
			error_log('[Error] target_path error');
			return false;
		}

		if(mb_substr($target_path, -1) == '/'){
			$target_path = $target_path.'*';
		}elseif(mb_substr($target_path, -1) != '*' && is_dir($target_path)){
			$target_path = $target_path.'/*';
		}
		$target_path = str_replace(DOCUMENT_ROOT,'/', $target_path);
		if(empty(self::$CloudFront)){
			self::init();
		}
		$environment = SAWS::getAwsEnv();
		$site = SAWS::getAwsSite();

		$DistributionName = self::$distribution_name_list[$site][$environment];
		$callerReference = microtime(true);

		if(empty($DistributionName)){
			error_log('[Error] empty DistributionName');
			return;
		}

		try {
			$result = self::$CloudFront->createInvalidation([
				'DistributionId' => $DistributionName,
				'InvalidationBatch' => [
					'CallerReference' => $callerReference,
					'Paths' => [
						'Items' => [$target_path],
						'Quantity' => 1,
					],
				]
			]);
		} catch (AwsException $e) {
			// output error message if fails
			error_log($e->getMessage());
			return;
		}
	}
}
