<?php

/**
 * <h1> By Author </h1>
 * View albums by author
 *
 * @since 2014-08-24
 * @version 0.6
 */
class byAuthor
{


	public function contents()
	{

		global $db, $ws;

		$result='';

		$q=$db->query("SELECT * FROM `users` ORDER BY `fname` ASC ");

		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){

				$qin=$db->query("SELECT *,
				             IFNULL(
				                (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
				                    ORDER BY `phid` DESC LIMIT 1), '') AS `photo`,
				             IFNULL(
		                		(SELECT SUM(`likes`) as `alllikes` FROM `votes` WHERE `votes`.`aid`=`albums`.`aid`),'0')
							AS `alllikes`,
					     IFNULL(
		                		(SELECT SUM(`dislikes`) as `alldislikes` FROM `votes` WHERE `votes`.`aid`=`albums`.`aid`),'0')
		                			AS `alldislikes`,
		                	     IFNULL(
		                	     	(SELECT COUNT(`cid`) as `comments` FROM `comments` WHERE `comments`.`aid`=`albums`.`aid`),'0')
		                			AS `comments`
						FROM `albums` WHERE `uid`='".$a['uid']."' ORDER BY `aid` DESC");

				if($db->num_rows($qin)>0){

					$result.='
					<div class="albums-blk-author">
						<h2>'.$a['fname'].' '.$a['lname'].'&lsquo;s albums:</h2>
						<div class="albums-blk-items-author">';

					while($b=$db->fetch_array($qin)){

						$result.=$ws->albumPreview(
							0,
							$b['aid'],
							$b['uid'],
							$b['photo'],
							$b['name'],
							$b['created'],
							$b['alllikes'],
							$b['alldislikes'],
							$b['comments']
						);

					}

					$result.='
						</div>
					</div>';

				}

			}
		}


		return $result;

	}
}
