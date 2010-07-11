<?PHP

require('data.php');

$tenp = array(123.4, 123.4);
$twen = array(111.1, 111.1);
$obe2 = array(113.4, 113.4);
$over = array(97.2, 97.2);
$norm = array(81, 81);
$morb = array(129.6, 129.6);
$lall = array(0, count($data));

include ('jpgraph/src/jpgraph.php');
include ('jpgraph/src/jpgraph_line.php');

$graph = new Graph(800, 800, 'weight.png');
$graph->setMargin(50, 50, 50, 50);
$graph->legend->setPos(0.03, 0.1);
$graph->title = new Text('Mass against time');
$graph->title->align('center');
$graph->title->setFont(FF_FONT2);
$graph->title->setMargin(10);

$line = new LinePlot($data);
$line->mark->show();
$line->mark->setType(MARK_UTRIANGLE);

$graph->add($line);

$graph->setscale('textlin', 78, 140, 0, count($data));
$graph->setY2Scale('lin', 78.0 / (1.8*1.8), 140.0 / (1.8*1.8));
$graph->ygrid->show(false);
$graph->y2grid->show(true, true);

$graph->xaxis->setTitle('Week number', 'middle');
$graph->xaxis->setTitleMargin(15);

$graph->yaxis->setTitle('Mass (KG)', 'middle');
$graph->yaxis->setTitleMargin(32);

$graph->y2axis->setTitle('BMI', 'middle');
$graph->y2axis->setTitleMargin(30);

$graph->stroke();

?>
