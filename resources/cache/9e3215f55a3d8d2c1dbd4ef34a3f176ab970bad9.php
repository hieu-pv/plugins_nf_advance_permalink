<?php if(isset($_GET['success'])): ?>
<div class="updated notice is-dismissible" style="margin: 10px 0px;">
    <p>Something has been updated</p>
</div>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
<div class="error notice is-dismissible" style="margin: 10px 0px;">
    <p>There has been an error.</p>
</div>
<?php endif; ?>
<div id="list-of-rules">
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <td>Order</td>
                <td>Type</td>
                <td>Title</td>
                <td>Permalink</td>
                <td>Delete</td>
            </tr>
            <tbody>
                <?php foreach($rules as $key => $rule): ?>
                <tr>
                    <td><?php echo $key+1; ?></td>
                    <td><?php echo $rule->type; ?></td>
                    <td><?php echo $rule->taxonomy->name; ?></td>
                    <td>
                        <a href="<?php echo bloginfo('siteurl'); ?>/<?php echo $rule->regex; ?>"><?php echo bloginfo('siteurl'); ?>/<?php echo $rule->regex; ?></a>
                    </td>
                    <td>
                        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                            <input type="hidden" name="action" value="delete_rule">
                            <input type="hidden" name="id" value="<?php echo $rule->id; ?>">
                            <button style="border:none; cursor: pointer; background: none;"><span class="dashicons dashicons-trash"></span></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </thead>
    </table>
</div>
<div id="new-rewrite-rule">
    <div id="rewrite-category">
        <table class="form-table">
            <tbody>
                <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="save_rule">
                    <tr>
                        <th>
                            <label>Select category</label>
                        </th>
                        <td>
                            <select id="nf-advance-permalink" name="entity_id">
                                <?php foreach($terms as $term): ?>
                                <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <style type="text/css">
                        .select2-container {
                            margin-top: 0px !important;
                        }
                        </style>
                    </tr>
                    <tr>
                        <th>
                            <label><i><?php echo bloginfo('siteurl'); ?>/</i></label>
                        </th>
                        <td>
                            <input type="text" class="regular-text" name="regex" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label>Position</label>
                        </th>
                        <td>
                            <select name="after">
                                <option value="top">Top</option>
                                <option value="bottom">Bottom</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label>Redirect 301 old URL</label>
                        </th>
                        <td>
                            <input type="checkbox" name="redirect_old_permalink" checked>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <input type="submit" class="button button-default" value="Submit">
                        </th>
                    </tr>
                </form>
            </tbody>
        </table>
    </div>
</div>
