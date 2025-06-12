<?php
namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;

class DBSqlListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(QueryExecuted $event): void
    {
        if (! app()->environment(['testing', 'local'])) {
            return;
        }

        // $sql      = $event->sql;
        // $bindings = $event->bindings;
        // $bindings = array_map(function ($binding) {
        //     if (is_string($binding)) {
        //         return "'$binding'";
        //     } elseif ($binding instanceof DateTime) {
        //         return $binding->format("'Y-m-d H:i:s'");
        //     }
        // }, $bindings);
        // $time = $event->time;
        // $sql  = str_replace("?", '%s', $sql);
        // $sql  = sprintf($sql, ...$bindings);
        // Log::info('sql log', ['sql' => $sql, 'time' => $time]);
    }
}
