<?php
namespace Classes;
/**
 * Validate class için array oluşturan class
 *
 * @author msamli
 */
class ValidateAdd
{
	/**
	 * push edilen sabit değişken
	 *
	 * @var Array
	 */
	private $validateArr = array();
	/**
	 * construct method
	 *
	 * @param string $formValidateName
	 * @param array $validateMethodArr
	 * @param array $messageArr
	*/
	public function __construct ($formValidateName, Array $validateMethodArr, Array $messageArr)
	{
		$this->validateArr = array($formValidateName, $validateMethodArr, $messageArr);
	}

	/**
	 * validateArr sabit değişkenini get eder
	 *
	 * @return Array
	 */
	public function getValidateData()
	{
		return $this->validateArr;
	}
}