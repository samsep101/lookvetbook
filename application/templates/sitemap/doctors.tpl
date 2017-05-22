   <div class="inner">
        <div class="search-block flo">
        </div>
        <div class="ilness-list flo">
            <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
                </li>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1>Карта сайта&nbsp;</H1></span></span>
                    <meta itemprop="position" content="1" />
                </li>
            <?php if ($doctor_type!='children' && $visit_type!='home' && !$location && !$specialty) {
            ?>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1>Все врачи&nbsp;</H1></span></span>
                    <meta itemprop="position" content="2" />
                </li>
            <?php
            } 
            if ($doctor_type=='children') {
            ?>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1>Детские врачи&nbsp;</H1></span></span>
                    <meta itemprop="position" content="3" />
                </li>
            <?php
            } 
            if ($visit_type=='home') {
            ?>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1>Врачи на дом&nbsp;</H1></span></span>
                    <meta itemprop="position" content="3" />
                </li>
            <?php
            }
            if ($specialty_name) {
            ?>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1><?php echo $specialty_name;?>&nbsp;</H1></span></span>
                    <meta itemprop="position" content="3" />
                </li>

            <?php 
            }
            ?>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1><?php echo $location_name;?>&nbsp;</H1></span></span>
                    <meta itemprop="position" content="5" />
                </li>
                
            </ol>
        <div>
        <ul><?php if(!$location) {
            ?>
            <?php if ($districtsList) :?>
            <li>
                <h2>По округам</h2>
                <?php 
                  foreach($districtsList as $s) {
                ?>
                <a href="/smap/doctors/<?php if ($p) {echo $p.'/';} ?><?php if ($specialty) {echo 'specialty/'.$specialty.'/';} ?>location/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
                <?php }
                ?>
                <br><br>
            </li>
            <?php endif;?>
            <?php if ($regionsList) :?>
            <li>
                <h2>По районам</h2>
                <?php 
                  foreach($regionsList as $s) {
                ?>
                <a href="/smap/doctors/<?php if ($p) {echo $p.'/';} ?><?php if ($specialty) {echo 'specialty/'.$specialty.'/';} ?>location/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
                <?php }
                ?>
                <br><br>
            </li>
            <?php endif;?>
            <?php if ($metroStationsList) :?>
            <li>
                <h2>По станциям метро</h2>
                <?php 
                  foreach($metroStationsList as $s) {
                ?>
                <a href="/smap/doctors/<?php if ($p) {echo $p.'/';} ?><?php if ($specialty) {echo 'specialty/'.$specialty.'/';} ?>location/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
                <?php }
                ?>
                <br><br>
            </li>
            <?php endif;?>
            <?php if ($streetsList) :?>
            <li>
                <h2>По улицам</h2>
                <?php 
                  foreach($streetsList as $s) {
                ?>
                <a href="/smap/doctors/<?php if ($p) {echo $p.'/';} ?><?php if ($specialty) {echo 'specialty/'.$specialty.'/';} ?>location/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
                <?php }
                ?>
                <br><br>
            </li>
            <?php endif;?>
            <?php
            }
            ?>
            <?php if (!$specialty) :?>
            <li>
                <h2>По специальностям</h2>
                <?php 
                  foreach($specialitiesList as $s) {
                ?>
                <a href="/smap/doctors/<?php if ($p) {echo $p.'/';} ?><?php if ($location) {echo 'location/'.$location.'/';} ?>specialty/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
                <?php }
                ?>
                <br><br>
            </li>
            <?php endif;?>
            <?php 
             if (is_Array($specialitiesList)) {
               while (list($sid,$sdata)=each($specialitiesList)) {
                 if ($sid==$specialty_id){
                   if (count($sdata['doctors'])>0) {
                     foreach($sdata['doctors'] as $d) {
                       echo "<a href='/doctor/".$d['alias']."'>".$d['name']."</a><br>";
                     }
                   } else {
                     echo "врачей с данной специальностью не зарегистрированно по данному местоположению";
                   }
                 }
               }
             }
            ?>
            <hr size="1">
            <?php 
             if  ($doctor_type!='children' && !$location && !$specialty && !$visit_type)
               echo "<h2><A HREF='".$_SERVER['REQUEST_URI']."/detskie'>Детские врачи</A></h2>";
             if  ($visit_type!='home' && !$location && !$specialty && !$doctor_type)
               echo "<h2><A HREF='".$_SERVER['REQUEST_URI']."/na-dom'>Врачи на дом</A></h2>";
            ?>
        </ul>
        </div>
        </div>
  </div>
