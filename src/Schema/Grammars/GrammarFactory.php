<?php

namespace LaravelSpatial\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\Grammar;

/**
 * Class GrammarFactory
 *
 * @package LaravelSpatial\Schema\Grammars
 * @internal
 */
class GrammarFactory {

	/**
	 * @param string $name
	 *
	 * @return \Illuminate\Database\Schema\Grammars\Grammar
	 */
	public static function make(string $name): Grammar {
		switch($name){
			case 'mysql':
				return new MySqlGrammar();
				break;
			case 'postgresql':
			case 'pg_sql':
				return new PostgresGrammar();
				break;
		}

		throw new \InvalidArgumentException(\sprintf('%s is not a supported grammar.', $name));
	}
}