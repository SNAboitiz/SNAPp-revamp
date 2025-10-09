<?php

namespace App\Models\Supports;

use Closure;
use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\QueryResults;
use Illuminate\Support\Str;

abstract class BigQueryModel
{
    /**
     * The name of the BigQuery table associated with the model.
     *
     * @var string
     */
    protected static $table;

    /**
     * The BigQuery dataset name.
     *
     * @var string
     */
    private static $dataset;

    /**
     * The BigQuery client instance.
     *
     * @var BigQueryClient
     */
    private static $bigQuery;

    /**
     * Filters for WHERE clause
     *
     * @var array
     */
    protected static $filters = [];

    /**
     * Orders for ORDER BY clause
     *
     * @var array
     */
    protected static $orders = [];

    /**
     * Limit for the number of results
     *
     * @var int|null
     */
    protected static $limit = null;

    /**
     * The SQL query to be executed.
     */
    private static $query;

    /**
     * Create a new BigQueryService instance.
     *
     * @return void
     */
    public function __construct()
    {
        self::init();
    }

    /**
     * Initialize BigQuery client and dataset
     *
     * @return static
     */
    private static function init()
    {
        $keyFilePath = config('database.connections.bigquery.key_file_path');
        $keyFile = config('database.connections.bigquery.key_file');

        $config = [
            'projectId' => config('database.connections.bigquery.project_id')
        ];

        if ($keyFilePath && file_exists($keyFilePath)) {
            $config['keyFilePath'] = $keyFilePath;
        } elseif ($keyFile && is_array($keyFile)) {
            $config['keyFile'] = $keyFile;
        }

        self::$bigQuery = new BigQueryClient($config);

        self::$dataset = config('database.connections.bigquery.dataset');

        if (!self::$table) {
            self::$table = Str::snake(class_basename(static::class));
        }
    }

    /**
     * Execute the prepared query.
     *
     * @return QueryResults The results of the query.
     */
    private static function execute(): QueryResults
    {
        return self::$bigQuery->runQuery(self::$query);
    }

    /**
     * Execute a SQL query against BigQuery.
     *
     * @param string $sql The SQL query to execute.
     * @param array $parameters Optional parameters for the query.
     */
    public static function query(?string $sql = null, ?array $parameters = [])
    {
        if (is_null(self::$bigQuery)) self::init();

        if ($sql) {
            $jobConfig = self::$bigQuery->query($sql);

            if (!empty($parameters)) {
                $jobConfig->parameters($parameters);
            }
        } else {
            $dataset = self::$dataset;
            $table = self::$table;

            $query = "SELECT * FROM `{$dataset}.{$table}`";

            // WHERE clause
            if (!empty(self::$filters)) {
                $query .= " WHERE " . self::compileFilters(self::$filters);
            }

            // ORDER BY
            if (!empty(self::$orders)) {
                $orders = array_map(fn($o) => "{$o[0]} {$o[1]}", self::$orders);
                $query .= " ORDER BY " . implode(', ', $orders);
            }

            // LIMIT
            if (self::$limit) {
                $query .= " LIMIT " . self::$limit;
            }

            $jobConfig = self::$bigQuery->query($query);
        }

        self::$query = $jobConfig;

        return new static;
    }

    /**
     * WHERE condition (supports closures for nested conditions)
     */
    public static function where($field, $operator = null, $value = null)
    {
        if ($field instanceof Closure) {
            $nested = new static;
            $field($nested); // run closure on new builder
            self::$filters[] = [
                'type' => 'and',
                'nested' => $nested->filters,
            ];
        } else {
            if ($operator !== null && $value === null) {
                $value = $operator;
                $operator = '=';
            }

            self::$filters[] = [
                'type' => 'and',
                'field' => $field,
                'operator' => $operator,
                'value' => $value,
            ];
        }

        return new static;
    }

    /**
     * OR WHERE condition (supports closures for nested conditions)
     */
    public static function orWhere($field, $operator = null, $value = null)
    {
        if ($field instanceof Closure) {
            $nested = new static;
            $field($nested);
            self::$filters[] = [
                'type' => 'or',
                'nested' => $nested->filters,
            ];
        } else {
            if ($operator !== null && $value === null) {
                $value = $operator;
                $operator = '=';
            }

            self::$filters[] = [
                'type' => 'or',
                'field' => $field,
                'operator' => $operator,
                'value' => $value,
            ];
        }

        return new static;
    }

    /**
     * ORDER BY
     */
    public static function orderBy($field, $direction = 'asc')
    {
        self::$orders[] = [$field, strtoupper($direction)];

        return new static;
    }

    /**
     * Return only the first row
     */
    public static function first()
    {
        self::$limit = 1;

        $rows = self::get();

        return $rows->first();
    }

    /**
     * Run the query
     */
    public static function get()
    {
        self::query();

        $job = self::execute();

        return collect(iterator_to_array($job));
    }

    /**
     * Compile filters into SQL
     */
    private static function compileFilters($filters)
    {
        $sqlParts = [];

        foreach ($filters as $filter) {
            if (isset($filter['nested'])) {
                $nestedSql = self::compileFilters($filter['nested']);
                $sqlParts[] = ($filter['type'] === 'or' ? 'OR' : 'AND') . " ($nestedSql)";
            } else {
                $value = is_numeric($filter['value'])
                    ? $filter['value']
                    : "'" . addslashes($filter['value']) . "'";

                $sqlParts[] = ($filter['type'] === 'or' ? 'OR' : 'AND') .
                    " {$filter['field']} {$filter['operator']} {$value}";
            }
        }

        // remove leading AND/OR
        $sql = preg_replace('/^(AND|OR)\s+/', '', implode(' ', $sqlParts));

        return $sql;
    }
}
