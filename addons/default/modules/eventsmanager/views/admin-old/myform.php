
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
    $(function() {
        jQuery('base').remove();
        $("#mytabs").tabs({
            beforeLoad: function(event, ui) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                            "If this wouldn't be a demo.");
                });
            }
        });
    });
</script>
<?php
$module_path = BASE_URL . $this->module_details['path'] ;
?>

<section class="title">
    <?php if ( $this->method == 'create' ): ?>
        <h4><?php echo lang('eventsmanager:new_event_label') ; ?></h4>
    <?php else: ?>
        <h4><?php echo lang('eventsmanager:manage_event_label') . ' : ' . $event->title ; ?></h4>
    <?php endif ; ?>
</section>
<section class="item">
    <div class="content">
        <div id="mytabs">
            <ul>
                <li><a href="#tab-1">Preloaded</a></li>
                <li><a href="ajax/content1.html">Tab 1</a></li>
                <li><a href="ajax/content2.html">Tab 2</a></li>
                <li><a href="ajax/content3-slow.php">Tab 3 (slow)</a></li>
                <li><a href="ajax/content4-broken.php">Tab 4 (broken)</a></li>
            </ul>
            <div id="tab-1">
                <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
            </div>
        </div>
    </div>
</section>
