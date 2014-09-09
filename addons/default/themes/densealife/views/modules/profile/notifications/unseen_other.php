<ul>
    <?php foreach ( $notifications as $notification ): ?>
        <li>
            <?php echo load_view('profile', 'notifications/partials/noti_' . $notification->type, array( 'data' => $notification )); ?>
        </li>
    <?php endforeach; ?>
</ul>