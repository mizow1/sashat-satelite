<?php
require_once(LIB_DIR.'local/kyodo/Master.php');

class SKM {
	public static $master;
	public static function init(){
		self::$master = new KyodoMaster();
	}

	public static function getServiceId(){
		return self::$master->getServiceId();
	}

	public static function getCommodityId(){
		return self::$master->getCommodityId();
	}
	public static function getServiceList(){
		return self::$master->getServiceList();
	}

	public static function getCommodityList(){
		return self::$master->getCommodityList();
	}

	public static function getCommodity($commodity_key){
		return self::$master->getCommodity($commodity_key);
	}

	public static function getStoreCodeData($commodity_key){
		return self::$master->getStoreCodeData($commodity_key);
	}

	public static function getCampaignList(){
		return self::$master->getCampaignList();
	}

	public static function getPaymentTypeNameList(){
		return self::$master->getPaymentTypeNameList();
	}

	public static function getMemberTypeList(){
		return self::$master->getMemberTypeList();
	}

	public static function getPrefectureList(){
		return self::$master->getPrefectureList();
	}

	public static function getGenderList(){
		return self::$master->getGenderList();
	}

	public static function getJobList(){
		return self::$master->getJobList();
	}

	public static function getJobListAll(){
		return self::$master->getJobListAll();
	}

	public static function getPurchaseList(){
		return self::$master->getPurchaseList();
	}

	public static function getMailServiceList(){
		return self::$master->getMailServiceList();
	}

	public static function getMailServiceKeyList(){
		return self::$master->getMailServiceKeyList();
	}

	public static function getTrainLineList(){
		return self::$master->getTrainLineList();
	}

	public static function getTrainLineKeyList(){
		return self::$master->getTrainLineKeyList();
	}

	public static function getInquiryCategoryMember(){
		return self::$master->getInquiryCategoryMember();
	}

	public static function getInquiryCategoryNoMember(){
		return self::$master->getInquiryCategoryNoMember();
	}

	public static function getChargeCategoryList(){
		return self::$master->getChargeCategoryList();
	}

	public static function getStartSubscribeList(){
		return array(
			'year' => range(date('Y'),date('Y')+1),
			'month' => range(1, 12),
			'day' => range(1, 31),
		);
	}

	public static function getSubscribePaper(){
		return self::$master->getSubscribePaper();
	}

	public static function getSubscribePaperTransfer(){
		return self::$master->getSubscribePaperTransfer();
	}

	public static function getSubscribePaperCredit(){
		return self::$master->getSubscribePaperCredit();
	}

	public static function getSubscribeSportsTransfer(){
		return self::$master->getSubscribeSportsTransfer();
	}

	public static function getSubscribeSportsCredit(){
		return self::$master->getSubscribeSportsCredit();
	}

	public static function getSubscribePeriod(){
		return self::$master->getSubscribePeriod();
	}

	public static function getAlreadyPaper(){
		return self::$master->getAlreadyPaper();
	}

	public static function getAlreadyPaperPeriod(){
		return self::$master->getAlreadyPaperPeriod();
	}

	public static function getTriggerList(){
		return self::$master->getTriggerList();
	}

	public static function isPassportDmj($dmj){
		return self::$master->isPassportDmj($dmj);
	}

	public static function isTrialDmj($dmj){
		return self::$master->isTrialDmj($dmj);
	}

	public static function isDenshiDmj($dmj){
		return self::$master->isDenshiDmj($dmj);
	}

	public static function isCourceDmj($dmj){
		return self::$master->isCourceDmj($dmj);
	}

	public static function isDenshiStudentDmj($dmj){
		return self::$master->isDenshiStudentDmj($dmj);
	}

	public static function isCorpDmj($dmj){
		return self::$master->isCorpDmj($dmj);
	}

	public static function isCorpDenshiDmj($dmj){
		return self::$master->isCorpDenshiDmj($dmj);
	}

	public static function isCorpPaperDmj($dmj){
		return self::$master->isCorpPaperDmj($dmj);
	}

	public static function isDigitalDmj($dmj){
		return self::$master->isDigitalDmj($dmj);
	}

	public static function isDigitalDiscountDmj($dmj){
		return self::$master->isDigitalDiscountDmj($dmj);
	}

	public static function isDigitalTrialDmj($dmj){
		return self::$master->isDigitalTrialDmj($dmj);
	}

	public static function isDigitalYearDmj($dmj){
		return self::$master->isDigitalYearDmj($dmj);
	}

	public static function isDogaiYearDmj($dmj){
		return self::$master->isDogaiYearDmj($dmj);
	}

	public static function isSpDigitalDmj($dmj){
		return self::$master->isSpDigitalDmj($dmj);
	}

	public static function isCorpDigitalDmj($dmj){
		return self::$master->isCorpDigitalDmj($dmj);
	}
	public static function isSpCorpDigitalDmj($dmj){
		return self::$master->isSpCorpDigitalDmj($dmj);
	}
	public static function isTemporaryMember($ukb){
		return self::$master->isTemporaryMember($ukb);
	}

	public static function isDenshiMember($ukb){
		return self::$master->isDenshiMember($ukb);
	}

	public static function isCourceMember($ukb){
		return self::$master->isCourceMember($ukb);
	}

	public static function isCampaignMember($ukb){
		return self::$master->isCampaignMember($ukb);
	}

	public static function isCorpMember($ukb){
		return self::$master->isCorpMember($ukb);
	}

	public static function isPassportMember($ukb){
		return self::$master->isPassportMember($ukb);
	}

	public static function isParentMember($ust){
		return self::$master->isParentMember($ust);
	}

	public static function isChildMember($ust){
		return self::$master->isChildMember($ust);
	}
	public static function getPurchaseCidList(){
		return self::$master->getPurchaseCidList();
	}
	public static function getPaperViewerCidList(){
		return self::$master->getPaperViewerCidList();
	}
}
