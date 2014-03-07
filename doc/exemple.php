<?php

include 'news.model.php';

class NewsController
{
	public function viewListAction($request) /* ^news$ */
	{
		$request->parseTemplate('news/listnews.tpl.html', array('results' => $results));
	}
	
	public function viewNewsAction($request, $id) /* ^news/(?id:\d+)$ */
	{
		$news = new News(array('date'=>'2014-02-14'));
		$request->parseTemplate('news/news.tpl.html', array('news' => $news));
	}
	
	public function noteCommerceAction()
	{
		
	}
}

?>

<!-- news/listnews.tpl.html -->
<foreach array="{results}" as="$result">
	<a href="news/{result.value['id']}">{result.value['name']}</a><br />
</foreach>

<!-- news/news -->
<h1>{news->title}</h1>
