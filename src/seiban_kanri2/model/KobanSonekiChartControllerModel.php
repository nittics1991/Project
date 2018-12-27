<?php
/**
*   Controller Model
*
*   @version 171012
*/
namespace seiban_kanri2\model;

use \Exception;
use \InvalidArgumentException;
use Concerto\chart\cpchart\ImageOperation;
use Concerto\standard\ControllerModel;
use Concerto\standard\Server;

class KobanSonekiChartControllerModel extends ControllerModel
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
    *   @param KobanSonekiChartFactory $factory
    */
    public function __construct(KobanSonekiChartFactory $factory)
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
            $this->session->no_cyu = $query->no_cyu?? '';
            $this->session->fg_koban = $query->fg_koban?? '';
            $this->session->fg_project = $query->fg_project?? '';
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
        
        $url = Server::getRequestParentUrl() . 'koban_soneki_chart_main.php';
        $url .= "?no_cyu={$this->session->no_cyu}&id=";
        
        $file = './tmp/' . uniqid();
        $chart_count = 2;
        
        //注番
        $this->no_cyu = $this->session->no_cyu;
        
        for ($i = 1; $i <= $chart_count; $i++) {
            $child_url = "{$url}{$i}&file={$file}{$i}.png";
            $task->add("chart{$i}", $child_url);
        }
        
        $this->task_result = $task->start();
        $this->task_error = $task->getError();
        
        if (!$this->task_result) {
            $this->no_ko = '';
            
            for ($i = 1; $i <= $chart_count; $i++) {
                $this->id = $i;
                $this->file = "{$file}{$i}.png";
                $this->buildChartChildren();
            }
            $this->task_result = true;
        }
        
        for ($i = 1; $i <= $chart_count; $i++) {
            $images[0][$i - 1] = "{$file}{$i}.png";
        }
        
        //項番単位
        $model = $this->factory->getModel();
        $koban_list = $model->getKobanList($this->no_cyu);
        $count = 1;
        
        foreach ((array)$koban_list as $list) {
            $task = $this->factory->getTaskManager(30);
            
            for ($i = 1; $i <= $chart_count; $i++) {
                $child_url =
                    "{$url}{$i}&no_ko={$list['no_ko']}&file={$file}{$count}{$i}.png";
                $task->add("chart{$i}", $child_url);
            }
            
            $this->task_result = $task->start();
            $this->task_error = $task->getError();
            
            if (!$this->task_result) {
                $this->no_ko = '';
                
                for ($i = 1; $i <= $chart_count; $i++) {
                    $this->no_ko = $list['no_ko'];
                    $this->id = $i;
                    $this->file = "{$file}{$count}{$i}.png";
                    $this->buildChartChildren();
                }
                $this->task_result = true;
            }
            
            for ($i = 1; $i <= $chart_count; $i++) {
                $images[$count][$i - 1] = "{$file}{$count}{$i}.png";
            }
            $count++;
        }
        return ['images' =>$images];
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
        
        $this->no_cyu = $query->no_cyu??
            $this->session->no_cyu;
        $this->no_ko = $query->no_ko?? '';
        $this->id = $query->id?? 0;
        $this->file = $query->file?? '';
    }
    
    /**
    *   chart作成(children)
    *
    */
    public function buildChartChildren()
    {
        $no_cyu = $this->no_cyu;
        $no_ko = $this->no_ko;
        $template = __DIR__ . '\\chart\\StandardChart.php';
        
        switch ($this->id) {
            case '2':
                $id = '2a';
                $chart = $this->createChart(
                    $id,
                    $this->file,
                    $template,
                    $no_cyu,
                    $no_ko
                );
                
                $id2 = '2b';
                $file2 = "{$this->file}x";
                $chart2 = $this->createChart(
                    $id2,
                    $file2,
                    __DIR__ . '\\chart\\OverlayChart.php',
                    $no_cyu,
                    $no_ko
                );
                
                $imageOperation = ImageOperation::createFromFile($this->file);
                $imageOperation->merge($file2, 40, 0)
                    ->output($this->file);
                
                break;
            default:
                $chart = $this->createChart(
                    $this->id,
                    $this->file,
                    $template,
                    $no_cyu,
                    $no_ko
                );
        }
    }
    
    /**
    *   chart作成
    *
    *   @param string $id
    *   @param string $file
    *   @param string $template
    *   @param string $no_cyu
    *   @param string $no_ko
    */
    private function createChart($id, $file, $template, $no_cyu, $no_ko)
    {
        $chartData = $this->factory->getChartDefinition($id, $file, $template);
        $chartData->calcData($no_cyu, $no_ko);
        $builder = $this->factory->getChartBuilder();
        $builder->build($chartData->get());
    }
}
