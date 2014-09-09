
<div class="comman-box clearfix">
    <div class="comman-heading">Privacy</div>
    <form method="post" id="privacy_setting" action="/profile/privacy/create">
        <ul class="privacy">
            <?php foreach ( $parameters as $key => $params ): ?>
                <li class="privacy-li">
                <h3><?php echo $params['title']; ?></h3>
                <?php foreach ( $params['values'] as $value ): ?>
                    <div class="checkbox">
                        <label for="<?php echo $value; ?>"><?php echo ucfirst($value); ?></label>
                        <input type="radio" name="<?php echo $key; ?>" value="<?php echo $value; ?>" <?php echo $value == $params['default'] ? 'checked' : '';?>>
                    </div>
                <?php endforeach; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <button class="btn-color save" type="submit">Save</button>
    </form>
</div>