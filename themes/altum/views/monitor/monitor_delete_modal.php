<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="monitor_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title"><?= language()->monitor_delete_modal->header ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?= language()->global->close ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form name="monitor_delete_modal" method="post" action="<?= url('monitor/delete') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="monitor_id" value="" />

                    <p class="text-muted"><?= language()->monitor_delete_modal->subheader ?></p>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-danger"><?= language()->global->delete ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#monitor_delete_modal').on('show.bs.modal', event => {
        let monitor_id = $(event.relatedTarget).data('monitor-id');

        $(event.currentTarget).find('input[name="monitor_id"]').val(monitor_id);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
