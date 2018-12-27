<?php
/**
*   ChartDefinition
*
*   @version 170913
*/
namespace seiban_kanri2\model\chart;

use Concerto\chart\cpchart\ChartData;

class ChartDefinition extends ChartData
{
    /**
    *   sttting(overwrite)
    *
    *   @val array
    **/
    protected $setting = [];
    
    /**
    *   model
    *
    *   @var object
    **/
    protected $model;
    
    /**
    *   __construct
    *
    *   @param object $model,
    *   @param string $fileName,
    *   @param string $templateName
    **/
    public function __construct(
        $model,
        $fileName,
        $templateName
    ) {
        $this->model = $model;
        $this->importTemplate($templateName);
        $this->setFile($fileName);
    }
    
    /**
    *   importTemplate
    *
    *   @param string $templateName
    *   @return $this
    **/
    protected function importTemplate($templateName)
    {
        $chartData = $this->import($templateName);
        return $chartData->bind($this->setting);
    }
    
    /**
    *   setFile
    *
    *   @param string $fileName
    *   @return $this
    **/
    protected function setFile($fileName)
    {
        return $this->bind(['file' => $fileName]);
    }
    
    /**
    *   setPoints
    *
    *   @param array $dataset
    *   @return $this
    **/
    protected function setPoints(array $dataset)
    {
        return $this->bind(['points' => $dataset]);
    }
}
