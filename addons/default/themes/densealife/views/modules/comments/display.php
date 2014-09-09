<?php if ($comments): ?>  
    <ul class="status-blog">
        <?php foreach ($comments as $item): ?>
            <li class='li-<?php echo $item->id; ?>'>
                <table>
                    <tr>
                        <td>
                            {{user:profile_pic user_id='<?php echo $item->user_id; ?>'}}
                        </td>
                        <td>
                            <span class="name"><?php echo $item->user_name ?></span>
                        </td>
                    </tr>
                    <tr>
                    <td colspan="2">
                        <div class="status-aera"> 
                            <?php
                            if ($item->media != ''):
                                $media = unserialize($this->encrypt->decode($item->media));
                                ?>
                                <a class="fancybox-effects-b pl10" data-fancybox-group="button" href="<?php echo $media['data']['path']; ?>"><img src="<?php echo base_url(); ?>files/medium/<?php echo $media['data']['id']; ?>" alt="" width="200" height="200"/></a> 
                                <?php
                            endif;
                            ?>
                            <span>
                                <?php if (Settings::get('comment_markdown') and $item->parsed): ?>
                                    <?php echo parse_comment($item->parsed) ?>
                                <?php else: ?>
                                    <p><?php echo nl2br(parse_comment($item->comment)) ?></p>
                                <?php endif ?>
                            </span>
                            <span class="comman-star stars">
                                <?php echo $this->comments->link_star($item->id); ?>

                                <a href="">Share</a>
                            </span> 
                        </div>
                    </td>
                    </tr>
                    <tr>
                    <td colspan="2">
                        <ul class="comments-hare">
                            <?php echo $this->comments->display_children($item->id); ?>
                            <li>
                            <span>{{user:profile_pic user_id='<?php echo $this->current_user->id; ?>'}}</span> 
                            <div class="status-aera children">
                                <?php echo $this->comments->form($item->id); ?>
                            </div>
                            </li>
                        </ul>
                    </td>
                    </tr>
                </table>
            </li>
        <?php endforeach ?>
    </ul>
    <?php

 endif;
