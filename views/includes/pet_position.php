<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
    <div class="_buttons">
        <a href="#" onclick="new_sitterposition(); return false;" class="btn btn-info pull-left display-block">
            <?php echo _l('new_sitterposition'); ?>
        </a>
    </div>
    <div class="clearfix"></div>
    <hr class="hr-panel-heading" />
    <div class="clearfix"></div>
    <table class="table dt-table">
        <thead>
            <th><?php echo _l('id'); ?></th>
            <th><?php echo _l('sitterposition'); ?></th>
            <th><?php echo _l('options'); ?></th>
        </thead>
        <tbody>
            <?php foreach ($positions as $job) { ?>
                <tr>
                    <td><?php echo html_entity_decode($job['position_id']); ?></td>
                    <td><?php echo html_entity_decode($job['position_name']); ?></td>
                    <td>
                        <a href="#" onclick="edit_sitterposition(this,<?php echo html_entity_decode($job['position_id']); ?>); return false" data-name="<?php echo html_entity_decode($job['position_name']); ?>" data-position_description="<?php echo html_entity_decode($job['position_description']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                        <a href="<?php echo admin_url('appointment/delete_sitterposition/' . $job['position_id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="modal fade" id="sitterposition" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <?php echo form_open(admin_url('appointment/sitterposition')); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title"><?php echo _l('edit_sitterposition'); ?></span>
                        <span class="add-title"><?php echo _l('new_sitterposition'); ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="additional"></div>
                            <div class="form">
                                <?php
                                echo render_input('position_name', 'sitterposition'); ?>
                                <?php echo render_textarea('position_description', 'description', '') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
            </div><!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
</body>

</html>