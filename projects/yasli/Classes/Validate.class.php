<?php
namespace Classes;

use model\Common;
use model\captcha\Captcha;
use model\Session;
use model\ErrorMessage;
use Classes\Singleton;
use model\Request;
use model\Config;
use model\Debug;
/**
 * Validate işlemlerini yürüten static class
 *
 * @author msamli
 */
final class Validate{
	/**
	 * singleton trait
	 */
	use Singleton;
	/**
	 * null değer olup olmadığını kontrol eden sabit
	 */
	const CHECKISNOTNULL = 'checkIsNotNull';
	/**
	 * Validation yapıldığında formdaki input değerlerinin session'da saklandigi key
	 */
	const FORMVALUENAME = 'formValueArr';
	/**
	 * null değer olup olmadığını kontrol eden sabit
	 */
	const CHECKSELECTIONISNOTNULL = 'checkSelectionIsNotNull';
	
	/**
	 * karakter uzunluğunu kontrol eden sabit
	 */
	const CHECKLENGTH = 'checkLength';
	/**
	 * mail olup olmadığını kontrol eden sabit
	 */
	const CHECKISEMAIL = 'checkIsEmail';
	
	/**
	 * sayı değeri olup olmadığını kontrol eden sabit
	 */
	const CHECKISNUMERIC = 'checkIsNumeric';
	
	/**
	 * cep telefonunu kontrol eden sabit
	 */
	const CHECKISCEPTEL = 'checkIsCepTel';
	
	/**
	 * cep telefonunu kontrol eden sabit
	 */
	const CHECKISTEL = 'checkIsTel';
	
	/**
	 * integer değer olup olmadığını kontrol eder
	 */
	const CHECKISINTEGER = 'checkIsInteger';
	
	/**
	 * float değer olup olmadığını kontrol eder
	 */
	const CHECKISFLOAT = 'checkIsFloat';
	
	/**
	 * string olup olmadığını kontrol eder
	 */
	const CHECKISSTRING = 'checkIsString';
	
	/**
	 * array olup olmadığını kontrol eden sabit
	 */
	const CHECKISARRAY = 'checkIsArray';
	
	/**
	 * bir sayı değerinin alabileceği minimum ve maxsimum değerleri kontrol
     * eder
	 */
	const CHECKISINTEGERMINMAX = 'checkIsIntegerMinMax';
	
	/**
	 * tc kimlik no sorgulaması yapar
	 */
	const CHECKISTCKIMLIKNO = 'checkIsTcKimlikNo';
	/**
	 * sorgu kısıtlaması yapar
	 */
	const CHECKISSORGUKISITLAMA = 'checkIsSorguKisitlama';
	/**
	 * ip adresi kontrol ediyor.
	 */
	const CHECKISIPADDRESS = 'checkIsIp';
	/**
	 * tarih alanının düzgün girilip girilmediğini kontrol eder
	 */
	const CHECKISDATE = 'checkIsDate';
	/**
	 * captcha
	 */
	const CHECKISCAPTCHA = 'checkIsCaptcha';
	/**
	 * alfanumerik karakterlerin kontrolünü yapar
	 */
	const CHECKISALPHANUMERIC = 'checkIsAlphaNumeric';
	/**
	 * saat dakika kontrolü
	 */
	const CHECKISHOUROFMINUTS = 'checkIsHourOfMinuts';
	const COMBOBOX_ERROR_MESSAGE = 'Lütfen alttaki alandan bir değer seçiniz!';
	/**
	 * validate verilerinin tutulduğu array
	 *
	 * @var array
	 */
	public $dataArr = array();
	/**
	 * validate edildiğinde hangi alanlarin hatalı olduğunu tutan dizi değişken
	 * 
	 * @var array
	 */
	private $returnArr = array();
	/**
	 * dil dosyası
	 * @var string
	 */
	private $languageFile;
    /**
     * construct method
     *
     * @return \model\validate\Validate
     */
	public function __construct(){
		$this->dataArr = array();
	}
	/**
	 * language dosyasını set eder
	 * 
	 * @param string $poFile
	 */
	public function setLanguageFile($poFile){
		$this->languageFile = $poFile;
	}
	/**
	 * language file dosyasını get eder
	 * @return string
	 */
	public function getLanguageFile(){
		return $this->languageFile;
	}
	/**
	 * hatalı alan set eder
	 *
	 * @param string $fieldKey        	
	 * @param string $fieldValue        	
	 */
	public function addErrorField($fieldKey,$fieldValue){
		$this->returnArr[$fieldKey] = $fieldValue;
	}
	
	/**
	 * her bir alan için ValidateAdd objesini push eden method
	 *
	 * @param ValidateAdd $arr        	
	 * @return void
	 */
	public function add(ValidateAdd $arr){
		array_push($this->dataArr, $arr);
	}
	
	/**
	 * tüm validate array değişkenini get eder
	 *
	 * @return array
	 */
	public function getData(){
		return $this->dataArr;
	}
	
	/**
	 * mail kontrolü yapar
	 *
     * @heads-up Buradaki deger InputData const'unda da kullaniliyor. Degistirilirse oranin da degistirilmesi gerekir.
	 * @param $email
	 * @return boolean
	 */
	public function checkIsEmail($email){
		return (preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9.]{2,15}$/', $email)) ? true : false;
	}
	/**
	 * saat formatının doğru olup olmadığını get eder "23:59"
	 * 
	 * @param string $subject
	 * @return boolean
	 */
	public function checkIsHourOfMinuts($subject){
		return ( preg_match('/^([0-1]?[0-9]|[2][0-3]):([0-5][0-9])$/', $subject) ? true: false);
	}

    /**
     * cep tel kontrolü yapar
     *
     * @heads-up: Buradaki pattern'ler degistirilirse InputData'dakilerin de degismesi gerekir.
     * @param $tel
     * @param bool $cep
     * @return boolean
     */
	public function checkIsTel($tel,$cep = false){
		if($cep) return (preg_match('/^[5][0-9]{9}$/', $tel));
		return (preg_match('/^[2-9][0-9]{2}[0-9][0-9]{6}$/', $tel));
	}
	
	/**
	 * değerin boş olup olmadığına bakar   
	 */
	private function isEmpty($value){
		return ($value == null || trim($value) == "");
	}

    /**
     * not null kontrol yapar
     *
     * @param mixed $value
     * @param int $min
     * @param int $max
     * @return boolean
     */
	public function checkIsNotNull($value,$min = null,$max = null){
		if($value == null || trim($value) == "") return 0;
		return $this->checkIsCharacterLength($value, $min, $max);
	}
	
	/**
	 * combobox için null kontrol yapar
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	public function checkSelectionIsNotNull($value){
		return ! ($value == null || trim($value) == "" || trim($value) == "-1");
	}
	
	/**
	 * karakter seyısını check eder
	 *
	 * @param string $value        	
	 * @param int $min        	
	 * @param int $max        	
	 * @return int 1- az karakter var 2- çok karakter var 3- geçerli
	 */
	private function checkIsCharacterLength($value,$min = null,$max = null){
		$val = mb_strlen(trim($value), 'UTF-8');
		if($min != null) {
			if($val < $min) return 1;
		}
		if($max != null) {
			if($val > $max) return 2;
		}
		return 3;
	}
	
	/**
	 * intger değer olup olmadığını get eder
	 *
	 * @param string $value        	
	 * @return boolean
	 */
	public function checkIsNumeric($value){
		return (preg_match('/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/', $value) > 0) ? true : false;
	}

    /**
     * Tarih formatını kontrol eder GG/MM/YYYY
     *
     * @heads-up Buradaki deger InputData const'unda da kullaniliyor. Guncellenirse oranin da guncellenmesi lazim.
     * @param string $value
     * @param string $delimiter
     * @return boolean
     */
	public function checkIsDate($value,$delimiter = '/'){
		if(preg_match('%^([12][0-9]|0[1-9]|3[01])[' . $delimiter . '](0[1-9]|1[012])[' . $delimiter . '](19|20)\d\d$%', $value)){

			$format = 'd'.$delimiter.'m'.$delimiter.'Y';
			$date = \DateTime::createFromFormat($format, $value);
			
			if ($date!==FALSE && $date->format($format)===$value)
				return true;
			else
				return false;
		}
		return false;
	}
	/**
	 * Array olup olmadığını kontrol eder
	 *
	 * @param Array $value        	
	 * @return boolean
	 */
	public function checkIsArray($value){
		return is_array($value);
	}
	/**
	 * Integer olup olmadığını kontrol eder
	 *
	 * @param Array $value        	
	 * @return boolean
	 */
	public function checkIsInteger($value){
		return (preg_match('/^\s*[+-]?\d+\s*$/', $value)?true:false);
	}
	/**
	 * Float olup olmadığını kontrol eder
	 *
	 * @param float $value        	
	 * @return boolean
	 */
	public function checkIsFloat($value){
		return preg_match('%\A(?:^([+/-]?((([0-9]+(\.)?)|([0-9]*\.[0-9]+))([eE][+\-]?[0-9]+)?))$)\Z%', $value);
	}
	/**
	 * String olup olmadığını kontrol eder
	 *
	 * @param string $value        	
	 * @return boolean
	 */
	public function checkIsString($value){
		return is_string($value);
	}

    /**
     * Dosya uzantısının istediğiniz cinsten olup olmadığını check eder
     *
     * @param string $subject Dosya adi
     * @param string $extension Karsilastirilacak uzanti (nokta dahil yaziniz)
     * @return boolean Dosya adinin <u>sonunda</u> verilen ifade geciyorsa true, aksi halde false
     */
	public function checkFileExtension($subject,$extension = '.jpg'){
		return preg_match('/' . $extension . '$/', $subject);
	}
	/**
	 * Değerin alphanumeric değer olup olmadığını kontrol eder
	 *
	 * @param string $value        	
	 * @return boolean
	 */
	public function checkIsAlphaNumeric($value){
		return preg_match('/^[a-zA-Z0-9_]*$/', $value);
	}
	/**
	 * TC kimlik numarası doğruluğunu kontrol eder
	 *
	 * @param string $tc_kimlik_no
	 * @return boolean
	 */
	public function checkIsTcKimlikNo($tc_kimlik_no){
		if(mb_strlen($tc_kimlik_no, Config::CHARACTER_SET) != 11){
			return false;
		}
		
		if (!preg_match('/[0-9]{11}$/', $tc_kimlik_no)) {
			return false;
		}
		
		$tekler = (int) mb_substr($tc_kimlik_no, 0, 1, Config::CHARACTER_SET) + (int) mb_substr($tc_kimlik_no, 2, 1, Config::CHARACTER_SET) + (int) mb_substr($tc_kimlik_no, 4, 1, Config::CHARACTER_SET) + (int) mb_substr($tc_kimlik_no, 6, 1, Config::CHARACTER_SET) + (int) mb_substr($tc_kimlik_no, 8, 1, Config::CHARACTER_SET);
		$ciftler = (int) mb_substr($tc_kimlik_no, 1, 1, Config::CHARACTER_SET) + (int) mb_substr($tc_kimlik_no, 3, 1, Config::CHARACTER_SET) + (int) mb_substr($tc_kimlik_no, 5, 1, Config::CHARACTER_SET) + (int) mb_substr($tc_kimlik_no, 7, 1, Config::CHARACTER_SET);
		$t1 = $tekler * 3 + $ciftler;
		$c1 = (10 - $t1 % 10) % 10;
		$t2 = ($c1 + $ciftler);
		$t3 = ($t2 * 3 + $tekler);
		$c2 = (10 - $t3 % 10) % 10;
		if($c1 != mb_substr($tc_kimlik_no, 9, 1, Config::CHARACTER_SET) || $c2 != mb_substr($tc_kimlik_no, 10, 1, Config::CHARACTER_SET)) return false;
		else return true;
	}

    /**
     * toplu validate yapan method
     *
     * <code>
     * $VALIDATE = Validate::getInstange();
     * $VALIDATE->add(new ValidateAdd("mail", array(Validate::CHECKISEMAIL), array('Lütfen mail adresinizi doğru giriniz!')));
     * $VALIDATE->add(new ValidateAdd('username',array(Validate::CHECKISNOTNULL,5,10), array(
     *                              'Lütfen kullanıcı adınızı giriniz',
     *                              'Lütfen en az %s karakter giriniz',
     *                              'Lütfen kullanıcı adı ksımına en fazla %s karakter giriniz')));
     * $returnArr = $VALIDATE->checkValidate($_GET);
     * </code>
     *
     * @param array $paramArr Girdi dizisi (POST, GET, vb.)
     * @param boolean $setFieldMessage Form alanlarina hata mesaji eklensin mi?
     *            true olarak seçildiğinde otomatik olarak form elemanlarına
     *            hata mesajlarını set eder
     * @param bool $canBeNull CHECKISNOTNULL disindakilerin bos birakilmasina musade edilsin mi?
     * @return array
     */
	public function checkValidate(Array $paramArr,$setFieldMessage = false,$canBeNull = false){
		try {
		    $keyArr = array();
			$validateArr = $this->getData();
			if(is_array($validateArr)) {
                /** @var ValidateAdd $obj */
				foreach ($validateArr as $obj) {
					$valueArr = $obj->getValidateData();
					$keyArr[] = $valueArr[0];
					
					if(isset($paramArr[$valueArr[0]])) {
						$checkNeeded = ! ($canBeNull && $this->isEmpty($paramArr[$valueArr[0]]));
						
						switch ($valueArr[1][0]) {
							case 'checkIsNotNull' :
								$check = $this->checkIsNotNull($paramArr[$valueArr[0]], $valueArr[1][1], $valueArr[1][2]);
								if($check != 3) $this->returnArr[$valueArr[0]] = sprintf($valueArr[2][$check], $valueArr[1][$check]);
								break;
							case 'checkLength' :
								if($checkNeeded) {
									$check = $this->checkIsCharacterLength($paramArr[$valueArr[0]], $valueArr[1][1], $valueArr[1][2]);
									if($check != 3) $this->returnArr[$valueArr[0]] = sprintf($valueArr[2][$check], $valueArr[1][$check]);
								}
								break;
							case 'checkSelectionIsNotNull' :
								if(! $this->checkSelectionIsNotNull($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								break;
							case 'checkIsEmail' :
								if($checkNeeded) {
									if(! $this->checkIsEmail($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsCepTel' :
								if($checkNeeded) {
									if(! $this->checkIsTel($paramArr[$valueArr[0]], true)) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsTel' :
								if($checkNeeded) {
									if(! $this->checkIsTel($paramArr[$valueArr[0]], false)) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsString' :
								if($checkNeeded) {
									if(! $this->checkIsString($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsNumeric' :
								if($checkNeeded) {
									if(! $this->checkIsNumeric($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsArray' :
								if($checkNeeded) {
									if(! $this->checkIsArray($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsInteger' :
								if($checkNeeded) {
									if(! $this->checkIsInteger($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsFloat' :
								if($checkNeeded) {
									if(! $this->checkIsFloat($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsIp' :
								if($checkNeeded) {
									if(! $this->checkIsIpAddress($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsAlphaNumeric' :
								if($checkNeeded) {
									if(! $this->checkIsAlphaNumeric($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsHourOfMinuts' :
								if($checkNeeded) {
									if(! $this->checkIsHourOfMinuts($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsIntegerMinMax' :
								$check = $this->checkIsIntegerMinMax($paramArr[$valueArr[0]], $valueArr[1][1], $valueArr[1][2]);
								if($check < 2) $this->returnArr[$valueArr[0]] = $valueArr[2][$check];
								break;
							case 'checkIsCaptcha':
							    $hizmetKodu = Captcha::getHizmetKodu();
							    $captchArr = (array)Request::_session(Captcha::CAPTCHA_SESSION_NAME);
							    
							    if(array_key_exists($hizmetKodu,$captchArr)){
									$deger=Common::upper($paramArr[$valueArr[0]]);									
															
									if($captchArr[$hizmetKodu] != Captcha::getCaptchaHash($deger)) {
										$this->returnArr[$valueArr[0]] = $valueArr[2][0];
									}
									else {
										// Kullanıcı captcha'sını doğru
										// girdiyse, sayfayı refresh yapmaya
										// kalktığında
										// captcha session'ını yeniden generate
										// edip form sayfasına düşürüyoruz.
										Captcha::generateCaptcha(5,Captcha::getHizmetKodu());
									}
									// captcha input box'ının içi boş çıkacak.
									unset($paramArr[$valueArr[0]]);
								}
								break;
							case 'checkIsTcKimlikNo' :
								if($checkNeeded) {
									if(! $this->checkIsTcKimlikNo($paramArr[$valueArr[0]])) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							case 'checkIsSorguKisitlama' :
								if(! $this->checkIsSorguKisitlama($this->sorguKisitlamaIpBazlimiOlacak($valueArr[1]))) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								break;
							case 'checkIsDate' :
								if($checkNeeded) {
									if($valueArr[1][1] != '') $check = $this->checkIsDate($paramArr[$valueArr[0]], $valueArr[1][1]);
									else $check = $this->checkIsDate($paramArr[$valueArr[0]]);
									if(! $check) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
								}
								break;
							default :
								throw new \Exception('Bilinmeyen bir array ');
								break;
						}
					}
					# hiç set edilmeyen bir değer içeriyorsa
					else {
						if($valueArr[0] == '_max' && $valueArr[1][0] == 'checkIsSorguKisitlama') {
							if(! $this->checkIsSorguKisitlama($this->sorguKisitlamaIpBazlimiOlacak($valueArr[1]))) $this->returnArr[$valueArr[0]] = $valueArr[2][0];
						}
						# sayfada capcha varsa vede kullanıcı capcha adını sildi veya değiştirdiyse buradan hata döndüreceğiz!
						elseif($valueArr[1][0] == self::CHECKISCAPTCHA) {
						    
						    $hizmetKodu = Captcha::getHizmetKodu();
						    $captchArr = (array)Request::_session(Captcha::CAPTCHA_SESSION_NAME);
                            if(array_key_exists($hizmetKodu,$captchArr)){
                                $this->returnArr[$valueArr[0]] = $valueArr[2][0];
                            }				  
						}
						else{
							$this->returnArr[$valueArr[0]] = $valueArr[2][0];
						}
					}
				}
			}
		}
		catch ( \Exception $error) {
			throw new \Exception($error->getMessage());
		}
		
		# kombo bax'lardaki değerlerden farklı bir değer set edilirse
		#######################################################################################
		/*$CACHE =& \model\cache\Cache::factory(Config::CACHE_CLASS);
		//Debug::dump($keyArr);
		foreach ($paramArr as $formInputKey=>$formInputVal){
		    $cacheArr = $CACHE->getCache(\model\form\FormGenerator::getCacheId($formInputKey));
		    if(is_array($cacheArr)){
		        $combodakiDegerHaricindeBirDegerMiSetEdildi=true;
		        foreach ($cacheArr as $key=>$val){
		            if($key==$paramArr[$formInputKey] && $formInputVal!=''){
		                $combodakiDegerHaricindeBirDegerMiSetEdildi=false;
		                break;
		            }
		        }
		        if($combodakiDegerHaricindeBirDegerMiSetEdildi){
		            $this->returnArr[$formInputKey]=self::COMBOBOX_ERROR_MESSAGE;

		        }
		    }
		}*/
		#########################################################################################
		if($setFieldMessage) {
			$ERRORMESSAGE = new ErrorMessage($this->getLanguageFile());
			foreach ($this->returnArr as $fieldName=>$value) {
				$ERRORMESSAGE->addFieldError($fieldName, $value);
			}
			$ERRORMESSAGE->setErrorField();
			$formValueArr = [];
			
			foreach ($paramArr as $name=>$value) {
				if($value != '') $formValueArr[$name] = $value;
			}
			
			///Debug::dump($_SESSION,'AAAAAAA');
			Session::createSession([self::FORMVALUENAME=>$formValueArr]);
		}
		$returnArr = $this->returnArr;
		$this->returnArr = array();
		return $returnArr;
	}
    /**
     * sorgu kısıtlama ip bazlı mı olacak ona bakılıyor.
     *
     * @param array $array
     * @return boolean
     */
	private function sorguKisitlamaIpBazlimiOlacak(Array $array){
		return $array[1] ? true : null;
	}
	/**
	 * ip adresi doğru olup olmadığını kontrol eder
	 *
	 * @param string $subject        	
	 * @return boolean
	 */
	public function checkIsIp($subject){
		return preg_match('/\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/', $subject);
	}
	/**
	 * ipv4 ile ipv6 doğru olup olmadığını check eder
	 *
	 * @param string $ip        	
	 * @return boolean
	 */
	public function checkIsIpAddress($ip){
		// 2 adet ünlem olacak yanlış diye değiştirme!
		return ! ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
	}
	/**
	 * maksimum ve minimum değer seçtirirken değerleri check eden method
	 *
	 * @param integer $data        	
	 * @param integer $min        	
	 * @param integer $max        	
	 * @return integer
	 */
	public function checkIsIntegerMinMax($data,$min,$max){
		if((int) $data < (int) $min) return 0;
		elseif((int) $data > (int) $max) return 1;
		else return 2;
	}

    /**
     * Sorgu kısıtlama için kullanılan method.
     *
     * @param string $ip
     * @return boolean
     */
	public function checkIsSorguKisitlama($ip = null){
		return \model\SorguKisitlama::getInstance()->validate($ip);
	}
	/**
	 * formdan gelen token'in session'da ki değer ile aynı olup
	 * olmadığına bakar
	 *
	 * @return boolean
	 */
	public static function tokenValidation(){
		return Request::_post('token') == Request::_session('token') && self::_isset('submit', $_GET);
	}
	/**
	 * ajax connection için if bloğu oluşturur
	 *
	 * @return boolean
	 */
	public static function ajaxTokenValidation(){
		return Request::_post('token') == Request::_session('token') && self::_isset('submit', $_GET) && Validate::_isset('ajax', $_POST);
	}

    /**
     * request tanımlı olup olmadığını test eder
     *
     * @param string $requestName
     * @param $_GET_POST_SESSION_COOKIE
     * @return bool
     */
	public static function _isset($requestName,$_GET_POST_SESSION_COOKIE){
		return (is_array($_GET_POST_SESSION_COOKIE) && isset($_GET_POST_SESSION_COOKIE[$requestName]));
	}
	/**
	 * DEGISKEN BOSMU DEGİLMİ KONTROL EDER
	 * BOS DEGILSE FALSE DONER
	 * @author Serhat ÖZDAL
	 * @param var $var
	 * @return boolean
	 */
	public static function _empty($var){
	    return empty($var) ? true : false;
	}
}