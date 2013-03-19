# MG: moteur-graphique's develop

## Developer: cyy

## $data: 2013-03-13


## 分栏页面的标题去掉，保持与首页平衡
> taxonomy-skills.php line: 51 <h1 class="page-title mm"><?php echo $title; ?></h1>

## 详细页面的作者显示
> single-portfolio.php line: 36 echo '<span>|</span><span>' . get_the_author() . '</span>';

## 随机发布的作者勾选
> meta-boxes.php line: 190 <input type="checkbox" name="isRandomAuthor" <?php if ($post->post_author >= 10 && $post->post_author <= 100) { ?>checked="checked" <?php } ?>/>
> post.php line: 2437 if ($_POST['isRandomAuthor'] && ($postarr['post_author'] < 10 || $postarr['post_author'] > 100)) $postarr['post_author'] = rand(10, 100);
