<?php

namespace App;

use App\Models\Rule;

class RewriteRuler
{
    public static function render()
    {
        if (Capsule::schema()->hasTable(DB_TABLE_NAME)) {
            $rules = Rule::all();
            foreach ($rules as $rule) {
                $taxonomy = get_term($rule->entity_id);
                switch ($taxonomy->taxonomy) {
                    case 'category':
                        add_rewrite_rule('^' . $rule->regex, 'index.php?cat=' . $taxonomy->term_id, 'top');
                        break;
                    default:
                        add_rewrite_rule('^' . $rule->regex, 'index.php?' . $taxonomy->taxonomy . '=' . $taxonomy->slug, 'top');
                        break;
                }
            }
        }
    }
}
