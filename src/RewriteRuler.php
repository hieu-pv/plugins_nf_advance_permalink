<?php

namespace App;

use App\Log;
use App\Models\Rule;
use Illuminate\Database\Capsule\Manager as Capsule;

class RewriteRuler
{
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
    /**
     * @var array
     *
     * Aggregate of rewrite rules
     */
    private $rules;
    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
    private function __construct()
    {
        $this->rules = Rule::all();
        $log         = new Log;
        $log->info('Render Rewrite Rule');
    }
    public static function getNewInstance()
    {
        static::$instance = new static();
        return static::$instance;
    }
    public function render()
    {
        if (Capsule::schema()->hasTable(DB_TABLE_NAME)) {
            foreach ($this->rules as $rule) {
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
