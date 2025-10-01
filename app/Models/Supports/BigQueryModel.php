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
    protected $table;

    /**
     * The BigQuery dataset name.
     *
     * @var string
     */
    private string $dataset;

    /**
     * The BigQuery client instance.
     *
     * @var BigQueryClient
     */
    private BigQueryClient $bigQuery;

    /**
     * Filters for WHERE clause
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Orders for ORDER BY clause
     *
     * @var array
     */
    protected $orders = [];

    /**
     * Limit for the number of results
     *
     * @var int|null
     */
    protected $limit = null;

    /**
     * Create a new BigQueryService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $config = [
            'projectId' => config('database.connections.bigquery.project_id'),
            'keyFile' => config('database.connections.bigquery.key_file'),
            'keyFilePath' => config('database.connections.bigquery.key_file_path', null),
        ];

        $this->bigQuery = new BigQueryClient($config);

        $this->dataset = config('database.connections.bigquery.dataset');

        if (!$this->table) {
            $this->table = Str::snake(class_basename(static::class));
        }
    }

    /**
     * Execute a SQL query against BigQuery.
     *
     * @param string $sql The SQL query to execute.
     * @param array $parameters Optional parameters for the query.
     * @return QueryResults The results of the query.
     */
    public function query(string $sql, array $parameters = []): QueryResults
    {
        $jobConfig = $this->bigQuery->query($sql);

        if (!empty($parameters)) {
            $jobConfig->parameters($parameters);
        }

        return $this->bigQuery->runQuery($jobConfig);
    }

    /**
     * Query a table with optional filters and return the results as an array.
     *
     * @param string $table The table to query.
     * @param array $filters Optional associative array of filters (field => value).
     * @param array $columns Optional array of columns to select (default is all).
     * @param int $limit Optional limit on the number of results (default is 1000).
     * @return array The query results as an array.
     */
    public function queryWithFilters(string $table, array $filters = [], array $columns = ['*'], int $limit = 1000): array
    {
        $selectColumns = implode(', ', $columns);
        $sql = "SELECT {$selectColumns} FROM `{$table}`";

        $whereConditions = [];
        $parameters = [];

        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                $placeholders = [];
                foreach ($value as $index => $item) {
                    $paramName = $field . '_' . $index;
                    $placeholders[] = '@' . $paramName;
                    $parameters[$paramName] = $item;
                }
                $whereConditions[] = "{$field} IN (" . implode(', ', $placeholders) . ")";
            } else {
                $whereConditions[] = "{$field} = @{$field}";
                $parameters[$field] = $value;
            }
        }

        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }

        $sql .= " LIMIT {$limit}";

        $results = $this->query($sql, $parameters);

        $rows = [];
        foreach ($results as $row) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * WHERE condition (supports closures for nested conditions)
     */
    public function where($field, $operator = null, $value = null)
    {
        if ($field instanceof Closure) {
            $nested = new static;
            $field($nested); // run closure on new builder
            $this->filters[] = [
                'type' => 'and',
                'nested' => $nested->filters,
            ];
        } else {
            $this->filters[] = [
                'type' => 'and',
                'field' => $field,
                'operator' => $operator,
                'value' => $value,
            ];
        }

        return $this;
    }

    /**
     * OR WHERE condition (supports closures for nested conditions)
     */
    public function orWhere($field, $operator = null, $value = null)
    {
        if ($field instanceof Closure) {
            $nested = new static;
            $field($nested);
            $this->filters[] = [
                'type' => 'or',
                'nested' => $nested->filters,
            ];
        } else {
            $this->filters[] = [
                'type' => 'or',
                'field' => $field,
                'operator' => $operator,
                'value' => $value,
            ];
        }

        return $this;
    }

    /**
     * ORDER BY
     */
    public function orderBy($field, $direction = 'asc')
    {
        $this->orders[] = [$field, strtoupper($direction)];
        return $this;
    }

    /**
     * Return only the first row
     */
    public function first()
    {
        $this->limit = 1;
        $rows = $this->get();
        return $rows->first();
    }

    /**
     * Run the query
     */
    public function get()
    {
        $query = "SELECT * FROM `{$this->dataset}.{$this->table}`";

        // WHERE clause
        if (!empty($this->filters)) {
            $query .= " WHERE " . $this->compileFilters($this->filters);
        }

        // ORDER BY
        if (!empty($this->orders)) {
            $orders = array_map(fn($o) => "{$o[0]} {$o[1]}", $this->orders);
            $query .= " ORDER BY " . implode(', ', $orders);
        }

        // LIMIT
        if ($this->limit) {
            $query .= " LIMIT {$this->limit}";
        }

        $job = $this->bigQuery->query($query)->run();
        return collect(iterator_to_array($job));
    }

    /**
     * Compile filters into SQL
     */
    protected function compileFilters($filters)
    {
        $sqlParts = [];

        foreach ($filters as $filter) {
            if (isset($filter['nested'])) {
                $nestedSql = $this->compileFilters($filter['nested']);
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
