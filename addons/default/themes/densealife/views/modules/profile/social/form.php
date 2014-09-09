<div class="comman-box clearfix social-user-profile">
    <div class="comman-heading">Social</div>
    <div class="msg d-none"></div>
    <form method ="post" id="social">
    <ul>
        <?php foreach($elements as $key => $value):?>
        <li title="<?php echo $value['title'];?>" class="social-buttons">
            {{ theme:image file="<?php echo $value['image'];?>" alt="<?php echo $value['title'];?>" }}
            <span class="left-links">
                <input type="text" name="<?php echo $key;?>" value="<?php echo $value['value'];?>"/>
            </span>
        </li>
        <?php endforeach;?>
    </ul>
        <div class="clear"></div>
        <button> Add Social </button>
    </form>

</div>