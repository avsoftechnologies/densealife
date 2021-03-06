<div class="comman-heading">All Events<button class="right events-btn margin-zero btn-color">+ Create Event</button></div>
<div class="clearfix margin"><a href="#">Festivals</a> | <a href="#">Concert</a> | <a href="#">Tours</a> | <a href="#">Club Events</a> | <a href="#">More</a> 
    <div class="right events-btn" style="margin-right: -7px;">
        <select>
            <option>Festivals</option>
            <option>Concert</option>
            <option>Tours</option>
            <option>Club Events</option>
            <option>Trending</option>
            <option>Upcoming</option>
        </select>
    </div>
</div>
<ul class="stream">
    <h2 class="heading">Trending</h2>
    <?php if(!empty($trendings)):?>
    <?php foreach($trendings as $trending):?>
    <?php echo $this->load->view('index/loop',array('data' => $trending), true);?>
    <?php endforeach;?>
    <?php else: ?>
    <li>No records found</li>
    <?php endif;?>
    
    <h2 class="heading">Favorites</h2>
    <?php if(!empty($favorites)):?>
    <?php foreach($favorites as $favorite):?>
    <?php echo $this->load->view('index/loop',array('data' => $favorite), true);?>
    <?php endforeach;?>
    <?php else:?>
    <li>No records Found</li>
    <?php endif;?>

    <h2 class="heading">Upcoming</h2>
    <?php if(!empty($upcomings)):?>
    <?php foreach($upcomings as $upcoming):?>
    <?php echo $this->load->view('index/loop',array('data' => $upcoming), true);?>
    <?php endforeach;?>
    <?php else:?>
    <li>No records found</li>
    <?php endif; ?>
</ul>
