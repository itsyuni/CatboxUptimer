<?php
if(
    !empty(settings()->ads->header_status_pages)
    && !$this->status_page_user->plan_settings->no_ads
): ?>
    <div class="container my-3"><?= settings()->ads->header_status_pages ?></div>
<?php endif ?>
