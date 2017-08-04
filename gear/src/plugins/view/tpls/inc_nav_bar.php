<?php if (isset($nav_bar) and (is_array($nav_bar))){?>
    <?php if (!isset($nav_bar['active'])){$nav_bar['active']=[];}?>
    <style>
        body{padding-top: 50px}
    </style>
    <nav class="navbar navbar-inverse navbar-fixed-top animated flipInX" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=url_based('Main/index')?>" ><?=V::displayVar($nav_bar['title'],'Title')?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse" style="padding-right: 20px;">
            <ul class="nav navbar-nav">
                <?php
                if (isset($nav_bar['left']) and (is_array($nav_bar['left']))){
                    foreach ($nav_bar['left'] as $key=>$value){
                        preg_match('/^<!--(@?)(\w+)-->/',$key,$matches);
                        $nav_id=isset($matches[2])?$matches[2]:'';
                        if (!is_array($value)){
                            $nav_bar['temp']=in_array($nav_id,$nav_bar['active'])?'class="active"':'';
                            $target=$matches[1]?'_blank':'_self';
                            echo "<li {$nav_bar['temp']}><a href={$value} target='$target'>{$key}</a></li>";
                        }else{
                            $nav_bar['temp']=in_array($nav_id,$nav_bar['active'])?'class="active dropdown"':'class="dropdown"';
                            echo "<li {$nav_bar['temp']}><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">$key<b class=\"caret\"></b></a>";
                            echo "<ul class=\"dropdown-menu\">";
                            foreach ($value as $k=>$v){
                                preg_match('/^<!--(@?)(\w+)-->/',$k,$matches);
                                $nav_id=isset($matches[2])?$matches[2]:'';
                                $nav_bar['temp']=in_array($nav_id,$nav_bar['active'])?'class="active"':'';
                                $target=$matches[1]?'_blank':'_self';
                                echo "<li {$nav_bar['temp']}><a href=\"$v\" target='$target'>$k</a></li>";
                            }
                            echo "</ul> </li>";
                        }

                    }
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($nav_bar['right']) and (is_array($nav_bar['left']))){
                    foreach ($nav_bar['right'] as $key=>$value){
                        preg_match('/^<!--(@?)(\w+)-->/',$key,$matches);
                        $nav_id=isset($matches[2])?$matches[2]:'';
                        if (!is_array($value)){
                            $nav_bar['temp']=in_array($nav_id,$nav_bar['active'])?'class="active"':'';
                            $target=$matches[1]?'_blank':'_self';
                            echo "<li {$nav_bar['temp']}><a href={$value} target='$target'>{$key}</a></li>";
                        }else{
                            $nav_bar['temp']=in_array($nav_id,$nav_bar['active'])?'class="active dropdown"':'class="dropdown"';
                            echo "<li {$nav_bar['temp']}><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">$key<b class=\"caret\"></b></a>";
                            echo "<ul class=\"dropdown-menu\">";
                            foreach ($value as $k=>$v){
                                preg_match('/^<!--(@?)(\w+)-->/',$k,$matches);
                                $nav_id=isset($matches[2])?$matches[2]:'';
                                $nav_bar['temp']=in_array($nav_id,$nav_bar['active'])?'class="active"':'';
                                $target=$matches[1]?'_blank':'_self';
                                echo "<li {$nav_bar['temp']}><a href=\"$v\" target='$target'>$k</a></li>";
                            }
                            echo "</ul> </li>";
                        }

                    }
                }
                ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
<?php }else{ ?>
<!--    未定义的导航栏数组-->
<?php }?>

