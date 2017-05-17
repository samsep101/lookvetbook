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
            <?php if (!$location && !$specialization) {
            ?>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1>Все клиники&nbsp;</H1></span></span>
                    <meta itemprop="position" content="2" />
                </li>
            <?php
            } 
            if ($specialization) {
            ?>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1><?php echo $specialization_name;?>&nbsp;</H1></span></span>
                    <meta itemprop="position" content="3" />
                </li>

            <?php 
            }
            if ($location) {
            ?>
                <li itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><H1><?php echo $location_name;?>&nbsp;</H1></span></span>
                    <meta itemprop="position" content="5" />
                </li>
            <?php
            }
            ?>
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
                <a href="/sitemap/clinics/<?php if ($specialization) {echo 'specialization/'.$specialization.'/';} ?>location/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
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
                <a href="/sitemap/clinics/<?php if ($specialization) {echo 'specialization/'.$specialization.'/';} ?>location/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
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
                <a href="/sitemap/clinics/<?php if ($specialization) {echo 'specialization/'.$specialization.'/';} ?>location/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
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
                <a href="/sitemap/clinics/<?php if ($specialization) {echo 'specialization/'.$specialization.'/';} ?>location/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
                <?php }
                ?>
                <br><br>
            </li>
            <?php endif;?>
            <?php
            }
            ?>
            <?php if (!$specialization) :?>
            <li>
                <h2>По специализациям</h2>
                <?php 
                  foreach($specializationsList as $s) {
                ?>
                <a href="/sitemap/clinics/<?php if ($location) {echo 'location/'.$location.'/';} ?>specialization/<?php echo $s['alias'];?>"><?php echo $s['name']; ?></a>&nbsp;
                <?php }
                ?>
                <br><br>
            </li>
            <?php endif;?>
            <?php 
             if (is_Array($specializationsList)) {
               while (list($sid,$sdata)=each($specializationsList)) {
                 if ($sid==$specialization_id && $location){
                   if (count($sdata['clinics'])>0 && is_array($sdata['clinics'])) {
                     foreach($sdata['clinics'] as $d) {
                       echo "<a href='/clinic/".$d['alias']."'>".$d['name']."</a><br>";
                     }
                   } else 
                     echo "клиники с данной специализацией не зарегистрированны по данному местоположению";
                 }
               }
             } else echo "клиники с данной специализацией не зарегистрированны по данному местоположению";
            ?>
        </ul>
        </div>
        </div>
  </div>
