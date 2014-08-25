<?php

/**
 * <h1>Public Photos</h1>
 * Photos visible to all users.
 *
 * @version 0.3
 * @since 2014-08-21
 */
class publicPhotos
{


	public function showAll()
	{
		global $db,$ws;

		$page=0;$resultperpage=30;

		$qt=$db->query("SELECT * FROM 'photos' ");
		$total=$db->num_rows($qt);

		$result='
		<div id="publicphotos">
			<h1>Latest Uploads</h1>
			<div id="p-albums-items">';

			$q=$db->query("SELECT * FROM `photos` ORDER BY `phid` DESC LIMIT ".$page.",".$resultperpage);
			if($db->num_rows($q)>0){
				while($a=$db->fetch_array($q)){

					$result.=$ws->photoPreview(0,$a['phid'],$a['uid'],$a['filename'],$a['name'],$a['created']);

				}
			}

		$result.='
			</div>
		</div>';


		if($total > $resultperpage){

			$result.='
			<div id="paging">';

				$a=0;$b=0;
				if($_GET['page'] > 0){
					$backpage=($_GET['page'] - $resultperpage);
					print '<a href="/gallery/'.$backpage.'/" class="prev">&laquo;</a>';
				}

				while($a < $total){
					$a=$a+$resultperpage;
					if(($b < (($_GET['page']/$resultperpage)+10)) && ($b > (($_GET['page']/$resultperpage)-10))){
						print '<a href="/gallery/'.($b*$resultperpage).'/"';
						if($_GET['page']==($b*$resultperpage))print ' class="sel"';print '>'.($b+1).'</a>';
					}
					$b++;
				}
				if($_GET['page'] < ($total - $resultperpage)){
					$nextpage=($_GET['page'] + $resultperpage);
					print '<a href="/gallery/'.$nextpage.'/" class="next">&raquo;</a>';
				}
			$result.='
			</div>';
		}

		return $result;

	}


}