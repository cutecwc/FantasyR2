<?php   
/**  
* Recents  
* @package custom  
*/  

// * add by cutecwc, this code excludes by this theme to extend its function.
// * What is it?
// * An archive page will be added to templates, to add an indivial page by using this template page.

// by second.
// the times the essays it can fetch can be set to 'x' with value range 1 to infinity.
// the version coverse to 'recent' from 'archives' because the theme has complete the fuction by archive named "时光机".
// this conversion made by 2025-01-22 by cutecwc, with the commit "FantasyV30" which has verified via typecho1.2.1 and PHP8.


if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php'); ?>  

<main>
    <div class="wrap min">

    <?php 
    // yes, i refered above is the value 'x' is it, to change the value, you can get the page by custom.
    $x=100;
    $all=($stat->publishedPostsNum);
    //页数=向上取整（总归档数/每页文章数)；
    $pages=ceil($all/$x);
    
    //echo '<p>'.$pages.'</p>';
    
    $this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);   
	//$year=0; $mon=0; $i=0; $j=0;   
    // $output = '<span class="archive-page-counter"><h2>目前共计 '.$all.' 篇日志。</h2><ul></span>';   
    $count=0;
    $i=1;
    while($archives->next()):
        if($count%$x==0) {
            if($count==0) {
                $output.='<div id="archive page '.$i.'" class="archive" >';
            } else if($count!=0) {
                $output.='</div>';
                $output.='<div id="archive page '.$i.'" class="archive" style="display:none;">';
            }
           $i++;
        } 
        $output .='<li><time class="post-time" itemprop="dateCreated" content="'.date('Y-m-d',$archives->created).'">'.date('Y-m-d',$archives->created).'</time>'." ".'<a class="post-title-link" href="'.$archives->permalink .'" itemprop="url"><span itemprop="name">'. $archives->title .'</span></a></li>';

        $count++;
    endwhile;   
 
    $output.='</div>';
    
    echo $output;
    printNav($pages);
?>
<?php  
    function printNav($pages) {
        $script='<script type=\'text/javascript\'>';
        $output='<ol class="page-navigator" style="margin-left: 0px;">';
        $script.='page1();';
        for($i=1;$i<=$pages;$i++) {
            $output.='<li id="page'.$i.'" >';
            $script.='function page'.$i.'(){var elems=document.getElementsByClassName("archive");for(var i=0;i<elems.length;i+=1){elems[i].style.display="none";};document.getElementById("archive page '.$i.'").style.display="inline";';
            $script.='var current=document.getElementsByClassName("current");for(var i=0;i<current.length;i+=1){current[i].setAttribute("class","")};';
            $script.='document.getElementById("page'.$i.'").className="current";';
            $output.='<a href="javascript:void(0)" onclick="page'.$i.'()">'.$i.'</a></li>';
            $script.='}';
        }
        $output.='</ol>';
        $script.='</script>';
        echo $output;
        echo $script;
    }
?>

    </div>
</main>


<?php $this->need('footer.php'); ?>