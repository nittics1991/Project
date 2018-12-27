<?php
/**
*   Controller Model
*
*   @version 171017
*/
namespace seiban_kanri2\model;

use \InvalidArgumentException;
use Concerto\chart\cpchart\ImageOperation;
use Concerto\standard\ControllerModel;
use Concerto\standard\Server;
use seiban_kanri2\model\CyubanSonekiChartFactory;

class CyubanSonekiChartControllerModel extends ControllerModel
{
    /**
    *   名前空間
    *
    *   @val string
    */
    protected $namespace = 'seiban_kanri';
    
    /**
    *   コンストラクタ
    *
    *   @param CyubanSonekiChartFactory $factory
    */
    public function __construct(CyubanSonekiChartFactory $factory)
    {
        parent::__construct($factory);
    }
    
    /**
    *   query処理
    *
    */
    public function setQuery()
    {
        $query = $this->factory->getQuery();
        
        if ($query->isValid()) {
            $this->session->kb_nendo = $query->kb_nendo??
                $this->session->kb_nendo;
            $this->session->cd_bumon = $query->cd_bumon??
                $this->session->cd_bumon;
            $this->session->fg_cyuban = $query->fg_cyuban??
                $this->session->fg_cyuban;
        }
    }
    
    /**
    *   chart作成
    *
    *   @return array imageファイル
    */
    public function buildChart()
    {
        $tmp = dirname(__DIR__) . '\\tmp';
        
        $fileOperation = $this->factory->getFileOperation();
        $fileOperation->createTempDir($tmp, 1);
        
        $task = $this->factory->getTaskManager(30);
        
        $url = Server::getRequestParentUrl() . 'cyuban_soneki_chart_main.php';
        $url .= "?kb_nendo={$this->session->kb_nendo}";
        $url .= "&cd_bumon={$this->session->cd_bumon}";
        $url .= "&id=";
        
        $file = './tmp/' . uniqid();
        $chart_count = 4;
        
        for ($i = 1; $i <= $chart_count; $i++) {
            $child_url = "{$url}{$i}&file={$file}{$i}.png";
            $task->add("chart{$i}", $child_url);
        }
        
        $this->task_result = $task->start();
        $this->task_error = $task->getError();
        
        if (!$this->task_result) {
            $this->kb_nendo = $this->session->kb_nendo;
            $this->cd_bumon = $this->session->cd_bumon;
            
            for ($i = 1; $i <= $chart_count; $i++) {
                $this->id = $i;
                $this->file = "{$file}{$i}.png";
                $this->buildChartChildren();
            }
            $this->task_result = true;
        }
        
        $result = [];
        for ($i = 1; $i <= $chart_count; $i++) {
            $result["image{$i}"] = "{$file}{$i}.png";
        }
        return $result;
    }
    
    /**
    *   query処理(chart children)
    *
    *   @throw InvalidArgumentException
    */
    public function setQueryChartChildren()
    {
        $query = $this->factory->getChartQuery();
        
        if (!$query->isValid()) {
            throw new InvalidArgumentException(
                "chart request error:file name={$query->id}"
            );
        }
        
        $this->kb_nendo = $query->kb_nendo??
            $this->session->kb_nendo;
        $this->cd_bumon = $query->cd_bumon??
            $this->session->cd_bumon;
        $this->id = $query->id?? 0;
        $this->file = $query->file?? '';
    }
    
    /**
    *   chart作成(children)
    *
    */
    public function buildChartChildren()
    {
        $kb_nendo = $this->kb_nendo;
        $cd_bumon = $this->cd_bumon;
        $template = __DIR__ . '\\chart\\StandardChart.php';
        
        switch ($this->id) {
            case '3':
                $id = '3a';
                $this->createChart(
                    $id,
                    $this->file,
                    $template,
                    $kb_nendo,
                    $cd_bumon
                );
                
                $id2 = '3b';
                $file2 = "{$this->file}x";
                $this->createChart(
                    $id2,
                    $file2,
                    __DIR__ . '\\chart\\OverlayChart.php',
                    $kb_nendo,
                    $cd_bumon
                );
                
                $imageOperation = ImageOperation::createFromFile($this->file);
                $imageOperation->merge($file2, 40, 0)
                    ->output($this->file);
                
                break;
            default:
                $this->createChart(
                    $this->id,
                    $this->file,
                    $template,
                    $kb_nendo,
                    $cd_bumon
                );
        }
    }
    
    /**
    *   chart作成
    *
    *   @param string $id
    *   @param string $file
    *   @param string $template
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    */
    private function createChart($id, $file, $template, $kb_nendo, $cd_bumon)
    {
        $chartData = $this->factory->getChartDefinition($id, $file, $template);
        $chartData->calcData($kb_nendo, $cd_bumon);
        $builder = $this->factory->getChartBuilder();
        $builder->build($chartData->get());
    }
}
