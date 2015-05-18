<?php

class Chartingservice{
	
	public function __construct() {
		$this->CI = & get_instance();
		require_once(FCPATH.'mtcharts/mtChart.php');
	}
	
	public function classStatistics(){
		
		$title ='';
		
		$term = Utilities::getCurrentTerm();
		
		if(!is_null($term)){
			$title='Class Numbers : '.$term->getName();
		}
		
		$this->CI->load->library('Statisticsservice');
		
		$statSvc = new Statisticsservice();
		
		$data = $statSvc->classStatisticsThisTerm();
		
		$counts = array();
		$classes = array();
		
		foreach($data as $c){
			$counts[] = $c['total'];
			$classes[] = $c['class'].' ('.$c['total'].')';
		}

		$imageName = 'chart-class-'.uniqid().'.png';
		
		$classStat = new mtChart(800,350);
		//$classStat->loadColorPalette('./misc/palette.txt','|'); 
		$classStat->addPoint($counts,"Serie1");
		$this->loadPalettes($classStat);
		$classStat->addPoint($classes,"Serie2");
		$classStat->addAllSeries();
		$classStat->setAbsciseLabelSerie("Serie2");
		
		// Initialise the graph
		$classStat->setFontProperties('DejaVuSansCondensed',9);
		
		// Draw the pie chart
		$classStat->drawBasicPieGraph(300,160,150,PIE_NOLABEL,10);
		
		$classStat->setFontProperties('DejaVuSansCondensed',10);
		$classStat->drawTitle(150,340,$title,50,50,50,400);
		
		$classStat->drawPieLegend(600,100,250,250,350);
		
		$classStat->Render('./tmp/'.$imageName);
		
		return base_url().'tmp/'.$imageName;
	}
	

	
	public function generalPieChart($title, $data){
		
		$this->CI->load->library('Statisticsservice');
		
		$imageName = $title.'-'.uniqid().'.png';
		
		$classStat = new mtChart(800,500);

		$classStat->addPoint($data['values'],"Serie1");
		
		$this->loadPalettes($classStat);
		
		$classStat->addPoint($data['labels'],"Serie2");
		$classStat->addAllSeries();
		$classStat->setAbsciseLabelSerie("Serie2");
		
		// Initialise the graph
		$classStat->setFontProperties('DejaVuSansCondensed',9);

		$classStat->setAntialiasQuality(0);
		$classStat->setShadowProperties(2,2,200,200,200);
		$classStat->drawFlatPieGraphWithShadow(400,250,200,PIE_PERCENTAGE_LABEL,10);
		$classStat->clearShadow();
		
		$classStat->setFontProperties('DejaVuSansCondensed',11);
		$classStat->drawTitle(250,20,$title,50,50,50,400);
		$classStat->Render('./tmp/'.$imageName);
		
		return base_url().'tmp/'.$imageName;
	}
		
	private function loadPalettes($graph){
		$graph->setColorPalette(8,255,0,0);
		$graph->setColorPalette(9,255,255,0);
		$graph->setColorPalette(10,155,200,128);
	}
}


?>