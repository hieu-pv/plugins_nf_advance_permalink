@if(isset($_GET['success']))
<div class="updated notice is-dismissible" style="margin: 10px 0px;">
    <p>Something has been updated</p>
</div>
@endif
@if(isset($_GET['error']))
<div class="error notice is-dismissible" style="margin: 10px 0px;">
    <p>There has been an error.</p>
</div>
@endif
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
                @foreach($rules as $key => $rule)
                <tr>
                    <td>{!! $key+1 !!}</td>
                    <td>{!! $rule->type !!}</td>
                    <td>{!! $rule->taxonomy->name !!}</td>
                    <td>
                        <a href="{!! bloginfo('siteurl') !!}/{!! $rule->regex !!}">{!! bloginfo('siteurl') !!}/{!! $rule->regex !!}</a>
                    </td>
                    <td>
                        <form method="post" action="{!! admin_url('admin-post.php') !!}">
                            <input type="hidden" name="action" value="delete_rule">
                            <input type="hidden" name="id" value="{!! $rule->id !!}">
                            <button style="border:none; cursor: pointer; background: none;"><span class="dashicons dashicons-trash"></span></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </thead>
    </table>
</div>
<div id="new-rewrite-rule">
    <div id="rewrite-category">
        <table class="form-table">
            <tbody>
                <form method="POST" action="{!! admin_url('admin-post.php') !!}">
                    <input type="hidden" name="action" value="save_rule">
                    <tr>
                        <th>
                            <label>Select category</label>
                        </th>
                        <td>
                            <select id="nf-advance-permalink" name="entity_id">
                                @foreach($terms as $term)
                                <option value="{!! $term->term_id !!}">{!! $term->name !!}</option>
                                @endforeach
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
                            <label><i>{!! bloginfo('siteurl') !!}/</i></label>
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
