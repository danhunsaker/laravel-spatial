<?php

namespace LaravelSpatial\Schema\Grammars;

use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class MySqlGrammar
 *
 * @package LaravelSpatial\Schema\Grammars
 */
class MySqlGrammar extends \Illuminate\Database\Schema\Grammars\MySqlGrammar
{
    /**
     * Compile a spatial index key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint $blueprint
     * @param  \Illuminate\Support\Fluent $command
     *
     * @return string
     */
    public function compileSpatial(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileKey($blueprint, $command, 'spatial');
    }

    /**
     * Compile a drop index command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint $blueprint
     * @param  \Illuminate\Support\Fluent $command
     *
     * @return string
     */
    public function compileDropSpatial(Blueprint $blueprint, Fluent $command)
    {
        $table = $this->wrapTable($blueprint);

        $index = $this->wrap($command->index);

        return "alter table {$table} drop index {$index}";
    }
}
