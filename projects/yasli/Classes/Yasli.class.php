<?php
namespace Classes;

/**
 * PROJE ICIN DOSYA ISLEMLERINI YAPAN CLASS
 * 
 * @author Serhat 0ZDAL
 * @version 1.0
 */
final class Yasli
{   
    /**
     * $_REQUESTTEN GELEN DEGERI TUTAN ARRAY
     * @var array
     */
    private $criteria;
    
    public function __construct(Array &$criteria, &$file){
        $this->criteria = $criteria;
        $file = json_decode($file, true);
        
        if($file != null && isset($this->criteria["j"])){
            
            $count = count($file);
            for($i = 0; $i < $count; $i++){
                $this->writeSensorData($file[$i]["accuracy"], $file[$i]["x"], $file[$i]["y"], $file[$i]["z"], $file[$i]["timestamp"]);
            }
            
            $this->writeResult($this->criteria["j"]);
            $this->setMessage(100);
        }else{
            $this->setMessage(200);
        }
    
    }
    
    /**
     * SENSORDEN GELEN VERILERI EXCELE YAZAR
     * @param double $accuracy
     * @param double $x
     * @param double $y
     * @param double $z
     * @param long $date
     */
    private function writeSensorData($accuracy, $x, $y, $z, $date){
        $phpExcel = \PHPExcel_IOFactory::load("yasli.xlsx");
        $phpExcel->setActiveSheetIndex(0);
        $row = $phpExcel->getActiveSheet()->getHighestRow()+1;
        $phpExcel->getActiveSheet()->SetCellValue('A'.$row, "Accelerometer");
        $phpExcel->getActiveSheet()->SetCellValue('B'.$row, $accuracy);
        $phpExcel->getActiveSheet()->SetCellValue('C'.$row, $x);
        $phpExcel->getActiveSheet()->SetCellValue('D'.$row, $y);
        $phpExcel->getActiveSheet()->SetCellValue('E'.$row, $z);
        $phpExcel->getActiveSheet()->SetCellValue('F'.$row, $date);
        $objWriter = new \PHPExcel_Writer_Excel2007($phpExcel);
        $objWriter->save('yasli.xlsx');
    }
    
    /**
     * ISLEM SONUCUNU EXCELE YAZAN METOD
     * @param integer $result
     */
    private function writeResult($result){
        $phpExcel = \PHPExcel_IOFactory::load("yasli.xlsx");
        $phpExcel->setActiveSheetIndex(0);
        $row = $phpExcel->getActiveSheet()->getHighestRow()+1;
        $phpExcel->getActiveSheet()->SetCellValue('G'.$row, $result);
        $objWriter = new \PHPExcel_Writer_Excel2007($phpExcel);
        $objWriter->save('yasli.xlsx');
    }
    
    /**
     * SONUCU JSON TIPINDE EKRANA BASTIRAN METOD
     * @param string $message
     * @return void
     */
    private function setMessage($message){
        echo $message;
        die();
    }
}